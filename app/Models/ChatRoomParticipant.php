<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatRoomParticipant extends Model
{
    //
    protected $guarded = [];

    public function chatRoom() {
        $this->belongsTo(ChatRoom::class, 'chat_room_id');
    }
    public function user() {
        $this->belongsTo(User::class, 'user_id');
    }
}
