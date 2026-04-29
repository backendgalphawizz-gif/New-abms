<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
// use NunoMaduro\Collision\Adapters\Phpunit\State;

class City extends Model
{
    protected $table = 'cities';

    public function state()
    {
        return $this->belongsTo(State::class);
    }
}
