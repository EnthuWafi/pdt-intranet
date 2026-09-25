<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Pages\Page;

class MyActivity extends Page
{
    protected string $view = 'filament.pages.my-activity';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-inbox-stack';
    protected static ?int $navigationSort = 3;
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
