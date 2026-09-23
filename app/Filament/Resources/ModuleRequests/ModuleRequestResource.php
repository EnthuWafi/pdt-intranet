<?php

namespace App\Filament\Resources\ModuleRequests;

use App\Filament\Resources\ModuleRequests\Pages\CreateModuleRequest;
use App\Filament\Resources\ModuleRequests\Pages\EditModuleRequest;
use App\Filament\Resources\ModuleRequests\Pages\ListModuleRequests;
use App\Filament\Resources\ModuleRequests\Schemas\ModuleRequestForm;
use App\Filament\Resources\ModuleRequests\Tables\ModuleRequestsTable;
use App\Models\ModuleRequest;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ModuleRequestResource extends Resource
{
    protected static ?string $model = ModuleRequest::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function getNavigationGroup(): ?string
    {
        return __('navigation.groups.user_management');
    }
    public static function getModelLabel(): string
    {
        return __('modules.requests.resource.label');
    }
    public static function getPluralModelLabel(): string
    {
        return __('modules.requests.resource.plural_label');
    }


    // public static function form(Schema $schema): Schema
    // {
    //     return ModuleRequestForm::configure($schema);
    // }

    public static function table(Table $table): Table
    {
        return ModuleRequestsTable::configure($table);
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
            'index' => ListModuleRequests::route('/'),
            // 'create' => CreateModuleRequest::route('/create'),
            // 'edit' => EditModuleRequest::route('/{record}/edit'),
        ];
    }

    

    public static function canCreate(): bool
    {
        return false;
    }
}
