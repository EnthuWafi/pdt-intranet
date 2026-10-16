<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Enums\ExternalModuleRole;
use App\Enums\UserCategory;
use App\Enums\UserStatus;
use App\Models\Module;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Collection;
use App\Models\Department;
use App\Models\Unit;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Support\Facades\Hash;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('user.sections.personal_info'))
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('avatar')
                            ->label(__('user.columns.user_profile'))
                            ->collection('avatar')
                            ->image()
                            ->imageEditor()
                            ->circleCropper()
                            ->maxSize(2048)
                            ->columnSpanFull(),
                            
                        Grid::make(2)
                            ->schema([
                                TextInput::make('name')
                                    ->label(__('user.fields.name'))
                                    ->prefixIcon('heroicon-o-user')
                                    ->required()
                                    ->maxLength(255),

                                TextInput::make('email')
                                    ->label(__('user.fields.email'))
                                    ->prefixIcon('heroicon-o-at-symbol')
                                    ->email()
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(255),

                                TextInput::make('phone')
                                    ->label(__('user.fields.phone'))
                                    ->prefixIcon('heroicon-o-phone')
                                    ->tel()
                                    ->maxLength(30),

                                TextInput::make('password')
                                    ->label(__('user.fields.password'))
                                    ->prefixIcon('heroicon-o-lock-closed')
                                    ->password()
                                    ->revealable()
                                    ->dehydrateStateUsing(fn (?string $state): ?string => filled($state) ? Hash::make($state) : null)
                                    ->dehydrated(fn (?string $state): bool => filled($state))
                                    ->required(fn (string $operation): bool => $operation === 'create'),
                            ]),
                    ]),

                Section::make(__('user.sections.organization_role'))
                    ->description(__('user.sections.organization_hint'))
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('user_category')
                                    ->label(__('user.fields.category'))
                                    ->prefixIcon('heroicon-o-tag')
                                    ->options(UserCategory::class)
                                    ->default(UserCategory::INTERNAL)
                                    ->live()
                                    ->afterStateUpdated(function ($state, Set $set): void {
                                        $isInternal = self::isInternal($state);

                                        if ($isInternal) {
                                            $set('agency_name', __('common.organization.name', locale: 'ms'));
                                        } else {
                                            $set('agency_name', null);
                                            $set('department_id', null);
                                            $set('unit_id', null);
                                        }
                                    })
                                    ->required(),

                                TextInput::make('agency_name')
                                    ->label(__('user.fields.organization'))
                                    ->prefixIcon('heroicon-o-building-library')
                                    ->default(__('common.organization.name', locale: 'ms'))
                                    ->disabled(fn (Get $get): bool => self::isInternal($get('user_category')))
                                    ->dehydrated()
                                    ->dehydrateStateUsing(fn (?string $state, Get $get): ?string => self::isInternal($get('user_category'))
                                        ? __('common.organization.name', locale: 'ms')
                                        : $state)
                                    ->required(fn (Get $get): bool => ! self::isInternal($get('user_category')))
                                    ->maxLength(255),

                                Select::make('department_id')
                                    ->label(__('user.fields.department'))
                                    ->prefixIcon('heroicon-o-building-office')
                                    ->relationship('department', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->live()
                                    ->afterStateUpdated(fn (Set $set) => $set('unit_id', null))
                                    ->disabled(fn (Get $get): bool => ! self::isInternal($get('user_category')))
                                    ->dehydrated()
                                    ->required(fn (Get $get): bool => self::isInternal($get('user_category'))),

                                Select::make('unit_id')
                                    ->label(__('user.fields.unit'))
                                    ->prefixIcon('heroicon-o-command-line')
                                    ->options(fn (Get $get) => Unit::query()
                                        ->when($get('department_id'), fn ($q, $deptId) => $q->where('department_id', $deptId))
                                        ->pluck('name', 'id'))
                                    ->searchable()
                                    ->preload()
                                    ->disabled(fn (Get $get): bool => ! self::isInternal($get('user_category')) || blank($get('department_id')))
                                    ->dehydrated()
                                    ->required(fn (Get $get): bool => self::isInternal($get('user_category'))),

                                Select::make('position_id')
                                    ->label(__('user.fields.position'))
                                    ->prefixIcon('heroicon-o-briefcase')
                                    ->relationship('position', 'name')
                                    ->placeholder(__('user.placeholders.select_or_custom_position'))
                                    ->searchable()
                                    ->preload()
                                    ->live()
                                    ->afterStateUpdated(function ($state, Set $set): void {
                                        if (filled($state)) {
                                            $set('position', null);
                                        }
                                    })
                                    ->disabled(fn (Get $get): bool => (bool) $get('use_custom_position'))
                                    ->dehydrated()
                                    ->required(fn (Get $get): bool => ! $get('use_custom_position')),

                                TextInput::make('position')
                                    ->label(__('user.fields.custom_position'))
                                    ->prefixIcon('heroicon-o-pencil-square')
                                    ->placeholder(__('user.placeholders.custom_position'))
                                    ->disabled(fn (Get $get): bool => ! $get('use_custom_position') && filled($get('position_id')))
                                    ->dehydrated()
                                    ->required(fn (Get $get): bool => (bool) $get('use_custom_position') || blank($get('position_id')))
                                    ->maxLength(255),

                                Toggle::make('use_custom_position')
                                    ->label(__('user.fields.use_custom_position'))
                                    ->dehydrated(false)
                                    ->live()
                                    ->afterStateHydrated(function (Toggle $component, ?User $record): void {
                                        if ($record && blank($record->position_id) && filled($record->position)) {
                                            $component->state(true);
                                        }
                                    })
                                    ->afterStateUpdated(function (bool $state, Set $set): void {
                                        if ($state) {
                                            $set('position_id', null);
                                        } else {
                                            $set('position', null);
                                        }
                                    })
                                    ->columnSpanFull(),

                                Select::make('roles')
                                    ->label(__('user.fields.roles'))
                                    ->prefixIcon('heroicon-o-shield-check')
                                    ->relationship('roles', 'name')
                                    ->multiple()
                                    ->preload()
                                    ->searchable()
                                    ->required(),

                                Select::make('status')
                                    ->label(__('user.fields.status'))
                                    ->prefixIcon('heroicon-o-clock')
                                    ->options(UserStatus::class)
                                    ->default(UserStatus::Active)
                                    ->required(),
                            ]),
                    ]),

                Section::make(__('user.sections.external_modules'))
                    ->description(__('user.sections.external_modules_desc'))
                    ->schema([
                        Repeater::make('moduleRequests')
                            ->label(__('user.fields.module_requests'))
                            ->relationship()
                            ->schema([
                                Select::make('module_id')
                                    ->label(__('user.fields.module'))
                                    ->options(
                                        Module::query()
                                            ->where('is_internal', false)
                                            ->pluck('name', 'id')
                                    )
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->distinct()
                                    ->disableOptionsWhenSelectedInSiblingRepeaterItems(),

                                Select::make('role_assigned')
                                    ->label(__('user.fields.external_role'))
                                    ->options(ExternalModuleRole::class)
                                    ->required(),

                                Select::make('status')
                                    ->label(__('user.fields.request_status'))
                                    ->options([
                                        'pending' => __('common.status.pending'),
                                        'approved' => __('common.status.approved'),
                                        'rejected' => __('common.status.rejected'),
                                    ])
                                    ->default('approved')
                                    ->required(),
                            ])
                            ->columns(3)
                            ->defaultItems(0)
                            ->addActionLabel(__('user.actions.add_module_access')),
                    ]),
         
                
                ]);
    }
    protected static function isInternal(mixed $category): bool
    {
        return $category === UserCategory::INTERNAL
            || $category === UserCategory::INTERNAL->value;
    }
}
