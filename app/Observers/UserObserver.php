<?php

namespace App\Observers;

use App\Enums\ActionItemStatus;
use App\Enums\ActionType;
use App\Models\ActionItem;
use App\Models\User;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        //
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        //
    }

    // when creating or updating run ts
    public function saved(User $user): void {
        $customPosition = $user->getRawOriginal('position');

        if (blank($user->position_id) && filled($customPosition)) {
            // Avoid duplicate open action items for the same user
            $exists = ActionItem::query()
                ->where('actionable_type', self::class)
                ->where('actionable_id', $user->id)
                ->where('status', ActionItemStatus::PENDING_APPROVAL)
                ->exists();

            if (! $exists) {
                ActionItem::create([
                    'title' => "Semak Jawatan Baharu: {$customPosition}",
                    'description' => "Pengguna {$user->name} telah memasukkan jawatan manual '{$customPosition}'. Sila sahkan sama ada jawatan ini perlu ditambah ke dalam Data Master Jawatan.",
                    'actionable_type' => self::class,
                    'actionable_id' => $user->id,
                    'type' => ActionType::REVIEW,
                    'status' => ActionItemStatus::PENDING_APPROVAL,
                    'creator_id' => $user->id,
                ]);
            }
        }
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        //
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}
