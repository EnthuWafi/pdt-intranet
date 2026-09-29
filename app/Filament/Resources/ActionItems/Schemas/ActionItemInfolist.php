<?php

namespace App\Filament\Resources\ActionItems\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ActionItemInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('assigned_to_user_id')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('assigned_by_user_id')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('actionable_type'),
                TextEntry::make('actionable_id')
                    ->numeric(),
                TextEntry::make('title'),
                TextEntry::make('description')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('action_type')
                    ->badge(),
                TextEntry::make('status')
                    ->badge(),
                TextEntry::make('completed_by_user_id')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('completed_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('review_notes')
                    ->placeholder('-')
                    ->columnSpanFull(),
            ]);
    }
}
