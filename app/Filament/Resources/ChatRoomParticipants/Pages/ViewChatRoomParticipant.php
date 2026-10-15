<?php

namespace App\Filament\Resources\ChatRoomParticipants\Pages;

use App\Filament\Resources\ChatRoomParticipants\ChatRoomParticipantResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewChatRoomParticipant extends ViewRecord
{
    protected static string $resource = ChatRoomParticipantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
