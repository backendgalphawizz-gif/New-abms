<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class UserNotification extends Model
{
    public function unRead($query)
    {
        return $query->where('is_read', '=', 0);
    }
}
