<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Enums\UserCategory;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
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
                SpatieMediaLibraryFileUpload::make('avatar')
                    ->label(__('user.columns.user_profile'))
                    ->collection('avatar')
                    ->image()
                    ->imageEditor()
                    ->circleCropper()
                    ->maxSize(2048)
                    ->columnSpanFull(),
                TextInput::make('name')
                    ->label(__('user.columns.name'))
                    ->required(),
                TextInput::make('email')
                    ->label(__('user.columns.email'))
                    ->label('Email address')
                    ->email()
                    ->required(),
                DateTimePicker::make('email_verified_at'),
                TextInput::make('password')
                    ->label(__('user.columns.password'))
                    ->password()
                    ->required(),
                TextInput::make('position')
                ->label(__('user.columns.position')),

                TextInput::make('phone')
                    ->label(__('user.columns.phone'))
                    ->tel(),

                Select::make('user_category')
                    ->label(__('user.columns.user_category'))
                    ->options(UserCategory::class)
                    ->required(),

                TextInput::make('agency_name')
                    ->label(__('user.columns.agency_name'))
                    ->required(),

                Select::make('department')
                ->label(__('user.columns.department'))
                ->options(Department::query()->pluck('name', 'id'))
                ->live()
                ->afterStateUpdated(function (Set $set) {
                    $set('unit_id', null);
                    $set('agency_name', 'Pejabat Daerah dan Tanah Manjung');
                } )
                ->dehydrated(false),

                Select::make('unit_id')
                ->label(__('user.columns.unit'))
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

                Select::make('roles')
                ->label(__('user.columns.roles'))
                ->relationship(name: 'roles', titleAttribute: 'name')
                ->multiple()
                ->preload()
                ->searchable(),
            ]);
    }
}
