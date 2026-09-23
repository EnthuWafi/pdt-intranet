<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Pages\Page;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;

class Notification extends Page implements HasTable
{
    use InteractsWithTable;
    protected string $view = 'filament.pages.notification';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-inbox-stack';
    protected static ?int $navigationSort = 2;

      public static function getNavigationGroup(): ?string
    {
        return __('navigation.groups.my_work');
    }

    public static function getNavigationLabel(): string
    {
        return __('notification.title');
    }

    public function getTitle(): string
    {
        return __('notification.title');
    }

    public static function canAccess(): bool
    {
        return auth()->user()?->can('viewAny', Notification::class) ?? false;
    }

}
