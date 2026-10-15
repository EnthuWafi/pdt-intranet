<?php

namespace App\Filament\Pages;

use App\Models\ChatMessage;
use App\Traits\HasSpecialHeader;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Auth;

class ChatRoom extends Page
{
    use HasSpecialHeader;
    protected string $view = 'filament.pages.chat-room';

    protected static string | BackedEnum | null $navigationIcon = Heroicon::ChatBubbleLeftRight;
    protected static ?int $navigationSort = 3;

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
    
    public function getHeaderDescription(): string | Htmlable | null
    {
        return __('chat_room.subtitle');
    }

    // public static function getNavigationBadge(): ?string
    // {
    //     $userId = Auth::id();

    //     // Count messages in the user's rooms that are newer than their last view time
    //     $unreadCount = ChatMessage::query()
    //         ->where('sender_id', '!=', $userId)
    //         ->whereHas('chatRoom.participants', function ($query) use ($userId) {
    //             $query->where('user_id', $userId)
    //                 ->where(function ($q) {
    //                     $q->whereColumn('chat_messages.created_at', '>', 'chat_room_participants.last_read_at')
    //                         ->orWhereNull('chat_room_participants.last_read_at');
    //                 });
    //         })
    //         ->count();

    //     return $unreadCount > 0 ? (string) $unreadCount : null;
    // }

}