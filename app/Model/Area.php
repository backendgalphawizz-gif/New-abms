<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
// use NunoMaduro\Collision\Adapters\Phpunit\State;

class Area extends Model
{
    protected $table = 'areas';

    public function city()
    {
        return $this->belongsTo(City::class);
    }
}
