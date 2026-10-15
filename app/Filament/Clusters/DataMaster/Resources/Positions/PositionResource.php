<?php

namespace App\Filament\Clusters\DataMaster\Resources\Positions;

use App\Filament\Clusters\DataMaster\DataMasterCluster;
use App\Filament\Clusters\DataMaster\Resources\Positions\Pages\CreatePosition;
use App\Filament\Clusters\DataMaster\Resources\Positions\Pages\EditPosition;
use App\Filament\Clusters\DataMaster\Resources\Positions\Pages\ListPositions;
use App\Filament\Clusters\DataMaster\Resources\Positions\Pages\ViewPosition;
use App\Filament\Clusters\DataMaster\Resources\Positions\Schemas\PositionForm;
use App\Filament\Clusters\DataMaster\Resources\Positions\Schemas\PositionInfolist;
use App\Filament\Clusters\DataMaster\Resources\Positions\Tables\PositionsTable;
use App\Models\Position;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PositionResource extends Resource
{
    protected static ?string $model = Position::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $cluster = DataMasterCluster::class;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return PositionForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PositionInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PositionsTable::configure($table);
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
            'index' => ListPositions::route('/'),
            'create' => CreatePosition::route('/create'),
            'view' => ViewPosition::route('/{record}'),
            'edit' => EditPosition::route('/{record}/edit'),
        ];
    }
}
