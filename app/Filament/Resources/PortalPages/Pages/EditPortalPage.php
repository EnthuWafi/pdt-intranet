<?php

namespace App\Filament\Resources\PortalPages\Pages;

use App\Filament\Resources\PortalPages\PortalPageResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditPortalPage extends EditRecord
{
    protected static string $resource = PortalPageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
