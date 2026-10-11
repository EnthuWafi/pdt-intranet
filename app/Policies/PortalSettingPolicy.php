<?php

namespace App\Policies;

use App\Models\PortalSetting;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PortalSettingPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('ViewAny:PortalSetting');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, PortalSetting $portalSetting): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, PortalSetting $portalSetting): bool
    {
        return $user->can('Update:PortalSetting');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PortalSetting $portalSetting): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, PortalSetting $portalSetting): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, PortalSetting $portalSetting): bool
    {
        return false;
    }
}
