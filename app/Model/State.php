<?php

namespace App\Model;

use App\Model\City;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    public function cities() {
        return $this->hasMany(City::class, 'state_id');
    }
}
