<?php

namespace App\Filament\Clusters\DataMaster\Resources\Positions\Pages;

use App\Filament\Clusters\DataMaster\Resources\Positions\PositionResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPosition extends ViewRecord
{
    protected static string $resource = PositionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
