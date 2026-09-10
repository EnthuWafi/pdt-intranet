<?php

namespace App\Filament\Resources\Modules\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ModuleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('code')
                    ->required()->unique(),
                TextInput::make('description'),
                TextInput::make('url_path'),
                TextInput::make('icon')->default('heroicon-o-cube'),
                Toggle::make('is_active')
                    ->required()->default(true),
            ]);
    }
}
