<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class ChatRoom extends Page
{
    protected string $view = 'filament.pages.chat-room';


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
