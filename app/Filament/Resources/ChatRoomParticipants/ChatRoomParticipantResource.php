<?php

namespace App\Filament\Resources\ChatRoomParticipants;

use App\Filament\Clusters\ChatRecord\ChatRecordCluster;
use App\Filament\Resources\ChatRoomParticipants\Pages\CreateChatRoomParticipant;
use App\Filament\Resources\ChatRoomParticipants\Pages\EditChatRoomParticipant;
use App\Filament\Resources\ChatRoomParticipants\Pages\ListChatRoomParticipants;
use App\Filament\Resources\ChatRoomParticipants\Pages\ViewChatRoomParticipant;
use App\Filament\Resources\ChatRoomParticipants\Schemas\ChatRoomParticipantForm;
use App\Filament\Resources\ChatRoomParticipants\Schemas\ChatRoomParticipantInfolist;
use App\Filament\Resources\ChatRoomParticipants\Tables\ChatRoomParticipantsTable;
use App\Models\ChatRoomParticipant;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ChatRoomParticipantResource extends Resource
{
    protected static ?string $model = ChatRoomParticipant::class;

    protected static ?string $cluster = ChatRecordCluster::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return ChatRoomParticipantForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ChatRoomParticipantInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ChatRoomParticipantsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListChatRoomParticipants::route('/'),
            'create' => CreateChatRoomParticipant::route('/create'),
            'view' => ViewChatRoomParticipant::route('/{record}'),
            'edit' => EditChatRoomParticipant::route('/{record}/edit'),
        ];
    }
}
