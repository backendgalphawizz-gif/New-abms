<?php

namespace App\Model;

use App\CPU\Helpers;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class Scheme extends Model
{
    
    public function area()
    {
        return $this->hasMany(SchemeArea::class, 'scheme_id');
    }
    public function feeStructure()
    {
        return $this->hasOne(FeeStructure::class, 'scheme_id');
    }
    

}
