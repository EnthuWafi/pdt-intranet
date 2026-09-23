<?php

namespace App\Models;

use App\Contacts\HasActionUrl;
use App\Enums\ActionItemStatus;
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

    /**
     * Scope to items visible to the given user:
     * 1. Assigned specifically to them.
     * 2. Pooled to a permission the user holds.
     */
    public function scopeForUser(Builder $query, User $user): Builder
    {
        $permissions = $user->getAllPermissions()->pluck('name')->toArray();

        return $query->where('status', ActionItemStatus::PENDING_APPROVAL)
            ->where(function (Builder $q) use ($user, $permissions) {
                $q->where('assigned_to_user_id', $user->id)
                  ->orWhere(function (Builder $poolQuery) use ($permissions) {
                      $poolQuery->whereNull('assigned_to_user_id')
                                ->whereIn('required_permission', $permissions);
                  });
            });
    }

    public function markAsCompleted(User $resolver): void
    {
        $this->update([
            'status' => ActionItemStatus::COMPLETED,
            'completed_by_user_id' => $resolver->id,
            'completed_at' => now(),
        ]);
    }

}
