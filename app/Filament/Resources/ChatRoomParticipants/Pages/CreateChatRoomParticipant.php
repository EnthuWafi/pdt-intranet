<?php

namespace App\Filament\Resources\ChatRoomParticipants\Pages;

use App\Filament\Resources\ChatRoomParticipants\ChatRoomParticipantResource;
use Filament\Resources\Pages\CreateRecord;

class CreateChatRoomParticipant extends CreateRecord
{
    protected static string $resource = ChatRoomParticipantResource::class;
}
