<?php

namespace App\Filament\Resources\ActionItems\Pages;

use App\Filament\Resources\ActionItems\ActionItemResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditActionItem extends EditRecord
{
    protected static string $resource = ActionItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
