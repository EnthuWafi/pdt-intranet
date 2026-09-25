<?php

namespace App\Models;

use App\Contacts\HasActionUrl;
use App\Enums\ActionItemStatus;
use App\Enums\ActionType;
use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ActionItem extends Model
{
    //
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

    public function claim(User $user): void
    {
        $this->update(['assigned_to_user_id' => $user->id]);
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
                        return true; // Short-circuit: OR condition satisfied
                    }
                    if ($operator === 'and' && $hasMatch === false) {
                        return false; // Short-circuit: AND condition broken
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
        $userPermissions = $user->getAllPermissions()->pluck('name')->toArray();

        $allowedIds = static::query()
            ->where('status', ActionItemStatus::PENDING_APPROVAL)
            ->where(function (Builder $subQuery) use ($user) {
                $subQuery->where('assigned_to_user_id', $user->id)
                    ->orWhereNull('assigned_to_user_id');
            })
            ->get()
            ->filter(function (self $item) use ($user, $userPermissions): bool {
                // If directly assigned to this user, bypass permission checks
                if ($item->assigned_to_user_id === $user->id) {
                    return true;
                }

                return $item->userCanSeeAction($userPermissions);
            })
            ->pluck('id');

        return $query->whereIn($this->qualifyColumn('id'), $allowedIds);
    }

    // For suboordinate
    public function submitReview(User $officer, ?array $payload = null): self
    {
        return DB::transaction(function () use ($officer, $payload) {
            $this->update([
                'payload' => $payload ?? $this->payload,
                'status' => ActionItemStatus::COMPLETED,
                'completed_by_user_id' => $officer->id,
                'completed_at' => now(),
            ]);

            // Create manager review task
            return static::create([
                'assigned_to_user_id' => $officer->manager_id,
                'actionable_type' => $this->actionable_type,
                'actionable_id' => $this->actionable_id,
                'title' => __('action_inbox.titles.manager_approval', ['item' => $this->title]),
                'action_type' => ActionType::REVIEW,
                'payload' => $payload ?? $this->payload,
                'status' => ActionItemStatus::PENDING_APPROVAL,
            ]);
        });
    }

    /**
     * Manager approves: applies payload to live record and closes task.
     */
    public function approveReview(User $manager): void
    {
        DB::transaction(function () use ($manager) {
            $target = $this->actionable;

            // Apply staged payload changes to production record
            if (!empty($this->payload)) {
                $target->update($this->payload);
            }

            // TODO: after this, check if instance of UseActionUrl and use its function instead
            // yeah im reusing it
            if (method_exists($target, 'markApproved')) {
                $target->markApproved();
            }

            $this->update([
                'status' => ActionItemStatus::COMPLETED,
                'completed_by_user_id' => $manager->id,
                'completed_at' => now(),
            ]);
        });
    }

    /**
     * Manager rejects or sends back for revisions.
     */
    public function returnForRevision(User $manager, string $reason): self
    {
        return DB::transaction(function () use ($manager, $reason) {
            $this->update([
                'status' => ActionItemStatus::CANCELLED,
                'review_notes' => $reason,
                'completed_by_user_id' => $manager->id,
                'completed_at' => now(),
            ]);

            // Reassign back to the officer who submitted it
            return static::create([
                'assigned_to_user_id' => $this->completed_by_user_id,
                'actionable_type' => $this->actionable_type,
                'actionable_id' => $this->actionable_id,
                'title' => __('action_inbox.titles.revisions_needed', ['item' => $this->title]),
                'action_type' => ActionType::EDIT,
                'payload' => $this->payload,
                'review_notes' => $reason,
                'status' => ActionItemStatus::PENDING_APPROVAL,
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
