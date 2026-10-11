<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\PortalPage;
use Illuminate\Auth\Access\HandlesAuthorization;

class PortalPagePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:PortalPage');
    }

    public function view(AuthUser $authUser, PortalPage $portalPage): bool
    {
        return $authUser->can('View:PortalPage');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:PortalPage');
    }

    public function update(AuthUser $authUser, PortalPage $portalPage): bool
    {
        return $authUser->can('Update:PortalPage');
    }

    public function delete(AuthUser $authUser, PortalPage $portalPage): bool
    {
        return $authUser->can('Delete:PortalPage');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:PortalPage');
    }

    public function restore(AuthUser $authUser, PortalPage $portalPage): bool
    {
        return $authUser->can('Restore:PortalPage');
    }

    public function forceDelete(AuthUser $authUser, PortalPage $portalPage): bool
    {
        return $authUser->can('ForceDelete:PortalPage');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:PortalPage');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:PortalPage');
    }

    public function replicate(AuthUser $authUser, PortalPage $portalPage): bool
    {
        return $authUser->can('Replicate:PortalPage');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:PortalPage');
    }

}