<?php

namespace App\Filament\Resources\PortalPages\Pages;

use App\Filament\Resources\PortalPages\PortalPageResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPortalPage extends ViewRecord
{
    protected static string $resource = PortalPageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
