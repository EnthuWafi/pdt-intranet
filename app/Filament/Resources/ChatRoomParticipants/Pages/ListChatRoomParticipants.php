<?php

namespace App\Filament\Resources\ChatRoomParticipants\Pages;

use App\Filament\Resources\ChatRoomParticipants\ChatRoomParticipantResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListChatRoomParticipants extends ListRecords
{
    protected static string $resource = ChatRoomParticipantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
