<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Pages\Page;

class ChatRoom extends Page
{
    protected string $view = 'filament.pages.chat-room';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-inbox-stack';
    protected static ?int $navigationSort = 2;

      public static function getNavigationGroup(): ?string
    {
        return __('navigation.groups.my_work');
    }

    public static function getNavigationLabel(): string
    {
        return __('chat_room.title');
    }

    public function getTitle(): string
    {
        return __('chat_room.title');
    }
}
