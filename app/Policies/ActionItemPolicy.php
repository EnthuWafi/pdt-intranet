<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\ActionItem;
use Illuminate\Auth\Access\HandlesAuthorization;

class ActionItemPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:ActionItem');
    }

    public function view(AuthUser $authUser, ActionItem $actionItem): bool
    {
        return $authUser->can('View:ActionItem');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:ActionItem');
    }

    public function update(AuthUser $authUser, ActionItem $actionItem): bool
    {
        return $authUser->can('Update:ActionItem');
    }

    public function delete(AuthUser $authUser, ActionItem $actionItem): bool
    {
        return $authUser->can('Delete:ActionItem');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:ActionItem');
    }

    public function restore(AuthUser $authUser, ActionItem $actionItem): bool
    {
        return $authUser->can('Restore:ActionItem');
    }

    public function forceDelete(AuthUser $authUser, ActionItem $actionItem): bool
    {
        return $authUser->can('ForceDelete:ActionItem');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:ActionItem');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:ActionItem');
    }

    public function replicate(AuthUser $authUser, ActionItem $actionItem): bool
    {
        return $authUser->can('Replicate:ActionItem');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:ActionItem');
    }

}