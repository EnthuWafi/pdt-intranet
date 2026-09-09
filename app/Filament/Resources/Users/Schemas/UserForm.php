<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Enums\UserCategory;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;
use Illuminate\Support\Collection;
use App\Models\Department;
use App\Models\Unit;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                DateTimePicker::make('email_verified_at'),
                TextInput::make('password')
                    ->password()
                    ->required(),
                TextInput::make('position'),
                TextInput::make('phone')
                    ->tel(),

                Select::make('user_category')
                    ->options(UserCategory::class)
                    ->required(),

                TextInput::make('agency_name')
                    ->required(),

                Select::make('department')
                ->options(Department::query()->pluck('name', 'id'))
                ->live()
                ->afterStateUpdated(function (Set $set) {
                    $set('unit_id', null);
                    $set('agency_name', 'Pejabat Daerah dan Tanah Manjung');
                } )
                ->dehydrated(false),

                Select::make('unit_id')
                ->options(function (Get $get): Collection {
                    $departmentId = $get('department');
                    
                    if (! $departmentId) {
                        return collect();
                    }

                    return Unit::query()
                        ->where('department_id', $departmentId)
                        ->pluck('name', 'id');
                })
                ->required()
                ->searchable()
                ->preload(),
            ]);
    }
}
