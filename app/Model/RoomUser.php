<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class RoomUser extends Model {
    public function admin() {
        return $this->belongsTo(Admin::class, 'user_id');
    }
}
