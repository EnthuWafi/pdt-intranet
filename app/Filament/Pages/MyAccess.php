<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;

class MyAccess extends Page
{
    protected string $view = 'filament.pages.my-access';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedKey;

}
