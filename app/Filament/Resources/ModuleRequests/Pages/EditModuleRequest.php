<?php

namespace App\Filament\Resources\ModuleRequests\Pages;

use App\Filament\Resources\ModuleRequests\ModuleRequestResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditModuleRequest extends EditRecord
{
    protected static string $resource = ModuleRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
