<?php

namespace App\Filament\Resources\ModuleRequests\Pages;

use App\Filament\Resources\ModuleRequests\ModuleRequestResource;
use Filament\Resources\Pages\ListRecords;

class ListModuleRequests extends ListRecords
{
    protected static string $resource = ModuleRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // CreateAction::make(),
        ];
    }
}
