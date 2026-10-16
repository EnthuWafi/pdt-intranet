<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\ChatMessage;
use Illuminate\Auth\Access\HandlesAuthorization;

class ChatMessagePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:ChatMessage');
    }

    public function view(AuthUser $authUser, ChatMessage $chatMessage): bool
    {
        return $authUser->can('View:ChatMessage');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:ChatMessage');
    }

    public function update(AuthUser $authUser, ChatMessage $chatMessage): bool
    {
        return $authUser->can('Update:ChatMessage');
    }

    public function delete(AuthUser $authUser, ChatMessage $chatMessage): bool
    {
        return $authUser->can('Delete:ChatMessage');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:ChatMessage');
    }

    public function restore(AuthUser $authUser, ChatMessage $chatMessage): bool
    {
        return $authUser->can('Restore:ChatMessage');
    }

    public function forceDelete(AuthUser $authUser, ChatMessage $chatMessage): bool
    {
        return $authUser->can('ForceDelete:ChatMessage');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:ChatMessage');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:ChatMessage');
    }

    public function replicate(AuthUser $authUser, ChatMessage $chatMessage): bool
    {
        return $authUser->can('Replicate:ChatMessage');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:ChatMessage');
    }

}