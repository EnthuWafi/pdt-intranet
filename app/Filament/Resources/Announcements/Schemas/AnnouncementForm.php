<?php

namespace App\Filament\Resources\Announcements\Schemas;

use App\Enums\AnnouncementType;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class AnnouncementForm
{
    public static function configure(Schema $schema): Schema
    {

        return $schema
            ->components([
                Select::make('type')
                    ->label(__('portal.announcement.columns.type'))
                    ->options(AnnouncementType::class)
                    ->required(),
                TextInput::make('title')
                    ->label(__('portal.announcement.columns.title'))
                    ->required(),
                Textarea::make('content')
                    ->label(__('portal.announcement.columns.content'))
                    ->required()
                    ->columnSpanFull(),
                Toggle::make('is_active')
                    ->label(__('portal.announcement.columns.is_active'))
                    ->required(),
                DateTimePicker::make('starts_at')
                    ->label(__('portal.announcement.columns.starts_at'))
                    ->required()->default(now()->timestamp),
                DateTimePicker::make('expires_at')
                    ->label(__('portal.announcement.columns.expires_at'))
                    ->required()->default(now()->addDays(7)),
            ]);
    }
}
