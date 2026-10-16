<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\ChatRoom;
use Illuminate\Auth\Access\HandlesAuthorization;

class ChatRoomPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:ChatRoom');
    }

    public function view(AuthUser $authUser, ChatRoom $chatRoom): bool
    {
        return $authUser->can('View:ChatRoom');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:ChatRoom');
    }

    public function update(AuthUser $authUser, ChatRoom $chatRoom): bool
    {
        return $authUser->can('Update:ChatRoom');
    }

    public function delete(AuthUser $authUser, ChatRoom $chatRoom): bool
    {
        return $authUser->can('Delete:ChatRoom');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:ChatRoom');
    }

    public function restore(AuthUser $authUser, ChatRoom $chatRoom): bool
    {
        return $authUser->can('Restore:ChatRoom');
    }

    public function forceDelete(AuthUser $authUser, ChatRoom $chatRoom): bool
    {
        return $authUser->can('ForceDelete:ChatRoom');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:ChatRoom');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:ChatRoom');
    }

    public function replicate(AuthUser $authUser, ChatRoom $chatRoom): bool
    {
        return $authUser->can('Replicate:ChatRoom');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:ChatRoom');
    }

}