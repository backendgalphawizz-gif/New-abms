<?php

namespace App\Model;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class Admin extends Authenticatable
{
    use Notifiable, HasApiTokens;

    public function role(){
        return $this->belongsTo(AdminRole::class,'admin_role_id');
    }
    public function assessor(){
        return $this->hasOne(Assessor::class,'assessor_id');
    }
    
    public function is_admin(){
        return $this->admin_role_id == 1;
    }

}
