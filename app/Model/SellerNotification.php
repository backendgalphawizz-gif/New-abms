<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SellerNotification extends Model
{
    public function unRead($query)
    {
        return $query->where('is_read', '=', 0);
    }
}
