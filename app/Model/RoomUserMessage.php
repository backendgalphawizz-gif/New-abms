<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class RoomUserMessage extends Model {

    public function message() {
        return $this->belongsTo(RoomMessage::class, 'room_message_id');
    }

}
