<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\ModuleRequest;
use Illuminate\Auth\Access\HandlesAuthorization;

class ModuleRequestPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:ModuleRequest');
    }

    public function view(AuthUser $authUser, ModuleRequest $moduleRequest): bool
    {
        return $authUser->can('View:ModuleRequest');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:ModuleRequest');
    }

    public function update(AuthUser $authUser, ModuleRequest $moduleRequest): bool
    {
        return $authUser->can('Update:ModuleRequest');
    }

    public function delete(AuthUser $authUser, ModuleRequest $moduleRequest): bool
    {
        return $authUser->can('Delete:ModuleRequest');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:ModuleRequest');
    }

    public function restore(AuthUser $authUser, ModuleRequest $moduleRequest): bool
    {
        return $authUser->can('Restore:ModuleRequest');
    }

    public function forceDelete(AuthUser $authUser, ModuleRequest $moduleRequest): bool
    {
        return $authUser->can('ForceDelete:ModuleRequest');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:ModuleRequest');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:ModuleRequest');
    }

    public function replicate(AuthUser $authUser, ModuleRequest $moduleRequest): bool
    {
        return $authUser->can('Replicate:ModuleRequest');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:ModuleRequest');
    }

}