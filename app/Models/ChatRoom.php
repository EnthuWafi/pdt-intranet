<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatRoom extends Model
{
    //
    protected $guarded = [];

    public function participants() {
        $this->hasMany(ChatRoomParticipant::class, 'chat_room_id');
    }
    public function messages() {
        $this->hasMany(ChatMessage::class, 'chat_room_id');
    }
}
