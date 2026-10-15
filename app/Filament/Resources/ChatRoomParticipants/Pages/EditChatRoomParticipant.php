<?php

namespace App\Filament\Resources\ChatRoomParticipants\Pages;

use App\Filament\Resources\ChatRoomParticipants\ChatRoomParticipantResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditChatRoomParticipant extends EditRecord
{
    protected static string $resource = ChatRoomParticipantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
