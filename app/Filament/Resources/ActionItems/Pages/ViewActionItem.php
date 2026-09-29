<?php

namespace App\Filament\Resources\ActionItems\Pages;

use App\Filament\Resources\ActionItems\ActionItemResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewActionItem extends ViewRecord
{
    protected static string $resource = ActionItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
