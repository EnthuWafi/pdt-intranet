<?php

namespace App\Filament\Resources\ActionItems;

use App\Enums\ActionItemStatus;
use App\Filament\Resources\ActionItems\Pages\CreateActionItem;
use App\Filament\Resources\ActionItems\Pages\EditActionItem;
use App\Filament\Resources\ActionItems\Pages\ListActionItems;
use App\Filament\Resources\ActionItems\Pages\ViewActionItem;
use App\Filament\Resources\ActionItems\Schemas\ActionItemForm;
use App\Filament\Resources\ActionItems\Schemas\ActionItemInfolist;
use App\Filament\Resources\ActionItems\Tables\ActionItemsTable;
use App\Models\ActionItem;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ActionItemResource extends Resource
{
    protected static ?string $model = ActionItem::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'title';

    public static function getNavigationGroup(): ?string
    {
        return __('navigation.groups.my_work');
    }

    public static function getModelLabel(): string
    {
        return __('action_inbox.title');
    }

    public static function getPluralModelLabel(): string
    {
        return __('action_inbox.title');
    }

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return ActionItemForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ActionItemInfolist::configure($schema);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->forUser(auth()->user());
    }

    public static function table(Table $table): Table
    {
        return ActionItemsTable::configure($table);
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
            'index' => ListActionItems::route('/'),
            // 'create' => CreateActionItem::route('/create'),
            'view' => ViewActionItem::route('/{record}'),
            // 'edit' => EditActionItem::route('/{record}/edit'),
        ];
    }

    //badge

    public static function getNavigationBadge(): ?string
    {
        $count = static::getEloquentQuery()
            ->forUser(auth()->user())
            ->where('status', ActionItemStatus::PENDING_APPROVAL)
            ->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }
}
