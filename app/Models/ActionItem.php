<?php

namespace App\Models;

use App\Contacts\HasActionUrl;
use App\Contracts\IsActionable;
use App\Enums\ActionItemStatus;
use App\Enums\ActionType;
use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;

class ActionItem extends Model
{
    //
    use HasRecursiveRelationships;
    protected $guarded = [];

    protected $casts = [
        'status' => ActionItemStatus::class,
        'action_type' => ActionType::class,
        'required_permissions' => 'array',
        'completed_at' => 'datetime',
        'payload' => 'array',
    ];

    public function actionable(): MorphTo
    {
        return $this->morphTo();
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to_user_id');
    }

    public function assignedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by_user_id');
    }

    public function getParentKeyName()
    {
        return 'parent_id';
    }

    public function getUrl(): ?string
    {
        if ($this->actionable instanceof HasActionUrl) {
            return $this->actionable->getActionUrl($this);
        }

        return null;
    }

    public function completedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'completed_by_user_id');
    }

    public function getWorkflowHistory(): Collection
    {
        // If this task has no ancestors, it IS the root; otherwise retrieve its top-level root.
        $root = $this->rootAncestor ?? $this;

        return $root->descendantsAndSelf()
            ->with(['assignedTo', 'assignedBy', 'completedBy'])
            ->orderBy('id', 'asc')
            ->get();
    }

    //for atomic operation
    public function claim(User $user): bool
    {
        return DB::transaction(function () use ($user) {
            $claimed = static::query()
                ->where('id', $this->id)
                ->whereNull('assigned_to_user_id')
                ->where('status', ActionItemStatus::PENDING_APPROVAL)
                ->lockForUpdate()
                ->update([
                    'assigned_to_user_id' => $user->id,
                ]);

            return $claimed > 0;
        });
    }

    public function release(): void
    {
        $this->update(['assigned_to_user_id' => null]);
    }


    public function userCanSeeAction(array $userPermissions): bool
    {
        $blocks = $this->required_permissions ?? [];

        foreach ($blocks as $block) {
            if ($block['type'] === 'permission_group') {
                $operator = strtolower($block['data']['operator'] ?? 'and');
                $rules = $block['data']['rules'] ?? [];

                foreach ($rules as $rule) {
                    $hasMatch = in_array($rule, $userPermissions);

                    if ($operator === 'or' && $hasMatch === true) {
                        return true; // OR condition satisfied
                    }
                    if ($operator === 'and' && $hasMatch === false) {
                        return false; // AND condition broken
                    }
                }
                
                return $operator === 'and';
            }
        }

        return false;
    }

    /**
     * Scope to items visible to the given user:
     * Assigned specifically to them.
     * Pooled to a permission the user holds.
     */
    public function scopeForUser(Builder $query, User $user): Builder
    {
        if ($user->can('viewAny', ActionItem::class)) {
            return $query;
        }

        $userPermissions = $user->getAllPermissions()->pluck('name')->toArray();

        $allowedPoolIds = static::query()
            ->whereNull('assigned_to_user_id')
            ->get(['id', 'required_permissions'])
            ->filter(fn (self $item): bool => $item->userCanSeeAction($userPermissions))
            ->pluck('id');

        // Combine direct ownership checks with authorized pool IDs
        return $query->where(function (Builder $subQuery) use ($user, $allowedPoolIds) {
            $subQuery->where('assigned_to_user_id', $user->id)
                ->orWhere('assigned_by_user_id', $user->id)
                ->orWhere('completed_by_user_id', $user->id)
                ->orWhereIn($this->qualifyColumn('id'), $allowedPoolIds);
        });
    }

    // -------------------------------------------------------------------------
    // Polymorphic Workflow Actions
    // -------------------------------------------------------------------------

    /**
     * Approver executes final approval: commits staged payload to live record.
     */
    public function approve(User $user, ?array $stagedData = null, ?string $notes = null): void
    {
        DB::transaction(function () use ($user, $stagedData, $notes) {
            if (!empty($stagedData)) {
                $this->payload = array_merge($this->payload ?? [], $stagedData);
            }

            if ($this->actionable instanceof IsActionable) {
                $this->actionable->handleActionApproval($this, $user);
            }

            $this->update([
                'payload'              => $this->payload,
                'review_notes'         => $notes ?? $this->review_notes,
                'status'               => ActionItemStatus::COMPLETED,
                'completed_by_user_id' => $user->id,
                'completed_at'         => now(),
            ]);
        });
    }

    /**
     * Approver executes final rejection.
     */
    public function reject(User $user, ?string $reason = null): void
    {
        DB::transaction(function () use ($user, $reason) {
            if ($this->actionable instanceof IsActionable) {
                $this->actionable->handleActionRejection($this, $user, $reason);
            }

            $this->update([
                'status'               => ActionItemStatus::CANCELLED,
                'review_notes'         => $reason,
                'completed_by_user_id' => $user->id,
                'completed_at'         => now(),
            ]);
        });
    }

    //Possibly transferring to here

    public function delegate(User $manager, User $subordinate, ?string $instructions = null): self
    {
        return DB::transaction(function () use ($manager, $subordinate, $instructions) {
            $this->update([
                'status'               => ActionItemStatus::COMPLETED,
                'review_notes'         => $instructions,
                'completed_by_user_id' => $manager->id,
                'completed_at'         => now(),
            ]);

            $permissions = $this->actionable instanceof IsActionable
                ? $this->actionable->getRequiredPermissionsFor(ActionType::REVIEW)
                : [];

            return static::create([
                'parent_id'            => $this->id,
                'assigned_to_user_id'  => $subordinate->id,
                'assigned_by_user_id'  => $manager->id,
                'actionable_type'      => $this->actionable_type,
                'actionable_id'        => $this->actionable_id,
                'title'                => "Review: {$this->title}",
                'description'          => $instructions,
                'action_type'          => ActionType::REVIEW,
                'payload'              => $this->payload,
                'status'               => ActionItemStatus::PENDING_APPROVAL,
                'required_permissions' => $permissions,
            ]);
        });
    }

    /**
     * Subordinate finishes review: stages data and spawns approval task for manager.
     */
    public function submitReview(User $subordinate, ?array $stagedData = null, ?string $notes = null): self
    {
        return DB::transaction(function () use ($subordinate, $stagedData, $notes) {
            $mergedPayload = array_merge($this->payload ?? [], $stagedData ?? []);

            $this->update([
                'payload'              => $mergedPayload,
                'review_notes'         => $notes,
                'status'               => ActionItemStatus::COMPLETED,
                'completed_by_user_id' => $subordinate->id,
                'completed_at'         => now(),
            ]);

            $targetManagerId = $this->assigned_by_user_id ?? $subordinate->manager_id;

            $permissions = $this->actionable instanceof IsActionable
                ? $this->actionable->getRequiredPermissionsFor(ActionType::APPROVE)
                : [];

            return static::create([
                'parent_id'            => $this->id,
                'assigned_to_user_id'  => $targetManagerId,
                'assigned_by_user_id'  => $subordinate->id,
                'actionable_type'      => $this->actionable_type,
                'actionable_id'        => $this->actionable_id,
                'title'                => "Approval Sign-off: {$this->title}",
                'description'          => $notes ?? "Review submitted by {$subordinate->name}.",
                'action_type'          => ActionType::APPROVE,
                'payload'              => $mergedPayload,
                'status'               => ActionItemStatus::PENDING_APPROVAL,
                'required_permissions' => $permissions,
            ]);
        });
    }

    /**
     * Manager sends the item back to the subordinate who reviewed it.
     */
    public function returnForRevision(User $manager, string $reason): self
    {
        return DB::transaction(function () use ($manager, $reason) {
            $this->update([
                'status'               => ActionItemStatus::CANCELLED,
                'review_notes'         => $reason,
                'completed_by_user_id' => $manager->id,
                'completed_at'         => now(),
            ]);

            return static::create([
                'parent_id'            => $this->id,
                'assigned_to_user_id'  => $this->assigned_by_user_id, // Return to subordinate
                'assigned_by_user_id'  => $manager->id,
                'actionable_type'      => $this->actionable_type,
                'actionable_id'        => $this->actionable_id,
                'title'                => "Revisions Required: {$this->title}",
                'description'          => $reason,
                'action_type'          => ActionType::REVISE,
                'payload'              => $this->payload,
                'review_notes'         => $reason,
                'status'               => ActionItemStatus::PENDING_APPROVAL,
            ]);
        });
    }

    /**
     * Determines whether the current item came from a subordinate review.
     */
    public function canBeReturnedForRevision(): bool
    {
        // must be an approval step assigned to user
        if ($this->action_type !== ActionType::APPROVE ||
            $this->status !== ActionItemStatus::PENDING_APPROVAL ||
            $this->assigned_to_user_id !== auth()->id()) {
            return false;
        }

        // Must have been submitted by someone other than the original applicant
        $applicantId = $this->actionable?->user_id;
        if (empty($this->assigned_by_user_id) || $this->assigned_by_user_id === $applicantId) {
            return false;
        }

        // must be a subordinate
        return User::query()
            ->where('id', $this->assigned_by_user_id)
            ->where('manager_id', auth()->id())
            ->exists();
    }

    /**
     * Closes the active task as CANCELLED and spawns a successor task for the new assignee.
     */
    public function reassign(User $actor, User $newAssignee, string $reason): self
    {
        return DB::transaction(function () use ($actor, $newAssignee, $reason) {
            $previousAssigneeName = $this->assignedTo?->name ?? 'Unassigned Pool';

            // 1. Close current task to preserve historical state
            $this->update([
                'status'               => ActionItemStatus::CANCELLED,
                'review_notes'         => __('action_inbox.audit.reassigned_note', [
                                                'assignee' => $newAssignee->name,
                                                'actor'    => $actor->name,
                                                'reason'   => $reason,
                                            ]),
                'completed_by_user_id' => $actor->id,
                'completed_at'         => now(),
            ]);

            // 2. Spawn replacement task carrying forward existing payload and configuration
            return static::create([
                'parent_id'            => $this->id,
                'assigned_to_user_id'  => $newAssignee->id,
                'assigned_by_user_id'  => $actor->id,
                'actionable_type'      => $this->actionable_type,
                'actionable_id'        => $this->actionable_id,
                'title'                => $this->title,
                'description'          => __('action_inbox.audit.transferred_description', [
                                                'previous' => $previousAssigneeName,
                                                'reason'   => $reason,
                                            ]),
                'action_type'          => $this->action_type,
                'payload'              => $this->payload,
                'status'               => ActionItemStatus::PENDING_APPROVAL,
                'required_permissions' => $this->required_permissions,
            ]);
        });
    }

    // mark as completed
    public function markAsCompleted(User $resolver): void
    {
        $this->update([
            'status' => ActionItemStatus::COMPLETED,
            'completed_by_user_id' => $resolver->id,
            'completed_at' => now(),
        ]);
    }

}
