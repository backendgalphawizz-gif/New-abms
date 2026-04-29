<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;

class LoginReport extends Model
{
    protected $table = "login_logs";
    
    public function customer()
    {
        if($this->type == 1) {
            return $this->belongsTo(User::class, 'user_id');
        } else {
            return $this->belongsTo(Seller::class, 'user_id');
        }
    }

    public function seller()
    {
    }

}
