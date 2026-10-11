<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class ManageSettings extends Page
{
    protected string $view = 'filament.pages.manage-settings';

     public static function getNavigationGroup(): ?string
    {
        return __('navigation.groups.setting');
    }

}
