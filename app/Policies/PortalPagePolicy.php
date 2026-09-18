<?php

namespace App\Policies;

use App\Models\PortalPage;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PortalPagePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('ViewAny:PortalPage');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, PortalPage $portalPage): bool
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
    public function update(User $user, PortalPage $portalPage): bool
    {
        return $user->hasPermissionTo('Update:PortalPage');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PortalPage $portalPage): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, PortalPage $portalPage): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, PortalPage $portalPage): bool
    {
        return false;
    }
}
