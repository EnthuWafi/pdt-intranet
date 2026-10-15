<?php

namespace App\Filament\Resources\ChatRooms;

use App\Filament\Clusters\ChatRecord\ChatRecordCluster;
use App\Filament\Resources\ChatRooms\Pages\CreateChatRoom;
use App\Filament\Resources\ChatRooms\Pages\EditChatRoom;
use App\Filament\Resources\ChatRooms\Pages\ListChatRooms;
use App\Filament\Resources\ChatRooms\Pages\ViewChatRoom;
use App\Filament\Resources\ChatRooms\Schemas\ChatRoomForm;
use App\Filament\Resources\ChatRooms\Schemas\ChatRoomInfolist;
use App\Filament\Resources\ChatRooms\Tables\ChatRoomsTable;
use App\Models\ChatRoom;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ChatRoomResource extends Resource
{
    protected static ?string $model = ChatRoom::class;

    protected static ?string $cluster = ChatRecordCluster::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return ChatRoomForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ChatRoomInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ChatRoomsTable::configure($table);
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
            'index' => ListChatRooms::route('/'),
            'create' => CreateChatRoom::route('/create'),
            'view' => ViewChatRoom::route('/{record}'),
            'edit' => EditChatRoom::route('/{record}/edit'),
        ];
    }
}
