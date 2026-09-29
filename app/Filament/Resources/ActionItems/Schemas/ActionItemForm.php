<?php

namespace App\Filament\Resources\ActionItems\Schemas;

use App\Enums\ActionItemStatus;
use App\Enums\ActionType;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ActionItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('assigned_to_user_id')
                    ->numeric(),
                TextInput::make('assigned_by_user_id')
                    ->numeric(),
                TextInput::make('required_permission'),
                TextInput::make('actionable_type')
                    ->required(),
                TextInput::make('actionable_id')
                    ->required()
                    ->numeric(),
                TextInput::make('title')
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
                Select::make('action_type')
                    ->options(ActionType::class)
                    ->default('approve')
                    ->required(),
                Select::make('status')
                    ->options(ActionItemStatus::class)
                    ->default('pending_approval')
                    ->required(),
                TextInput::make('completed_by_user_id')
                    ->numeric(),
                DateTimePicker::make('completed_at'),
                TextInput::make('payload'),
                Textarea::make('review_notes')
                    ->columnSpanFull(),
            ]);
    }
}
