<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\ChatRoomParticipant;
use Illuminate\Auth\Access\HandlesAuthorization;

class ChatRoomParticipantPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:ChatRoomParticipant');
    }

    public function view(AuthUser $authUser, ChatRoomParticipant $chatRoomParticipant): bool
    {
        return $authUser->can('View:ChatRoomParticipant');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:ChatRoomParticipant');
    }

    public function update(AuthUser $authUser, ChatRoomParticipant $chatRoomParticipant): bool
    {
        return $authUser->can('Update:ChatRoomParticipant');
    }

    public function delete(AuthUser $authUser, ChatRoomParticipant $chatRoomParticipant): bool
    {
        return $authUser->can('Delete:ChatRoomParticipant');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:ChatRoomParticipant');
    }

    public function restore(AuthUser $authUser, ChatRoomParticipant $chatRoomParticipant): bool
    {
        return $authUser->can('Restore:ChatRoomParticipant');
    }

    public function forceDelete(AuthUser $authUser, ChatRoomParticipant $chatRoomParticipant): bool
    {
        return $authUser->can('ForceDelete:ChatRoomParticipant');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:ChatRoomParticipant');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:ChatRoomParticipant');
    }

    public function replicate(AuthUser $authUser, ChatRoomParticipant $chatRoomParticipant): bool
    {
        return $authUser->can('Replicate:ChatRoomParticipant');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:ChatRoomParticipant');
    }

}