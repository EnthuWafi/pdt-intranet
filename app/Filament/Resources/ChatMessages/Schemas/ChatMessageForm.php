<?php

namespace App\Filament\Resources\ChatMessages\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ChatMessageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('sender_id')
                    ->relationship('sender', 'name'),
                TextInput::make('chat_room_id')
                    ->required()
                    ->numeric(),
                Textarea::make('message')
                    ->columnSpanFull(),
                DateTimePicker::make('read_at'),
            ]);
    }
}
