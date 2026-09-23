<?php

namespace App\Filament\Pages;

use \Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    public static function getNavigationGroup(): ?string
    {
        return __('navigation.groups.main');
    }

}
