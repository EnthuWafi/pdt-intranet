<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class MyActivity extends Page
{
    protected string $view = 'filament.pages.my-activity';

    public static function getNavigationGroup(): ?string
    {
        return __('navigation.groups.my_work');
    }

    public static function getNavigationLabel(): string
    {
        return __('my_activity.title');
    }

    public function getTitle(): string
    {
        return __('my_activity.title');
    }
}
