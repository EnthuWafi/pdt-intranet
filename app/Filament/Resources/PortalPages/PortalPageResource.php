<?php

namespace App\Filament\Resources\PortalPages;

use App\Filament\Resources\PortalPages\Pages\CreatePortalPage;
use App\Filament\Resources\PortalPages\Pages\EditPortalPage;
use App\Filament\Resources\PortalPages\Pages\ListPortalPages;
use App\Filament\Resources\PortalPages\Pages\ViewPortalPage;
use App\Filament\Resources\PortalPages\Schemas\PortalPageForm;
use App\Filament\Resources\PortalPages\Schemas\PortalPageInfolist;
use App\Filament\Resources\PortalPages\Tables\PortalPagesTable;
use App\Models\PortalPage;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PortalPageResource extends Resource
{
    protected static ?string $model = PortalPage::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'title';

    public static function getNavigationGroup() : ?string {
        return __('navigation.groups.system_management');
    }

    public static function form(Schema $schema): Schema
    {
        return PortalPageForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PortalPageInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PortalPagesTable::configure($table);
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
            'index' => ListPortalPages::route('/'),
            'create' => CreatePortalPage::route('/create'),
            'view' => ViewPortalPage::route('/{record}'),
            'edit' => EditPortalPage::route('/{record}/edit'),
        ];
    }
}
