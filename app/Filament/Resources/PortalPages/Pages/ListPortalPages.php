<?php

namespace App\Filament\Resources\PortalPages\Pages;

use App\Filament\Resources\PortalPages\PortalPageResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPortalPages extends ListRecords
{
    protected static string $resource = PortalPageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
