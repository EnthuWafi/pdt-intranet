<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatMessage extends Model
{
    //
    protected $fillable = [
        'sender_id',
        'receiver_id',
        'message',
        'read_at',
    ];

    protected function casts(): array
    {
        return [
            'read_at' => 'datetime',
        ];
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function chatRoom(): BelongsTo 
    {
        return $this->belongsTo(ChatRoom::class, 'chat_room_id');
    }

    //method
    public function scopeUnreadForUser(Builder $query, int $userId): Builder
    {
        return $query
            ->join(
                'chat_room_participants',
                'chat_messages.chat_room_id',
                '=',
                'chat_room_participants.chat_room_id'
            )
            ->where('chat_room_participants.user_id', $userId)
            ->where(function (Builder $q) use ($userId) {
                $q->where('chat_messages.sender_id', '!=', $userId)
                    ->orWhereNull('chat_messages.sender_id');
            })
            ->where(function (Builder $q) {
                $q->whereColumn('chat_messages.created_at', '>', 'chat_room_participants.last_read_at')
                    ->orWhereNull('chat_room_participants.last_read_at');
            });
    }
}
