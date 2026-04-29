<?php

namespace App\Model;

use App\CPU\Helpers;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class FeeStructure extends Model
{
   
    public function scheme()
    {
        return $this->hasOne(Scheme::class, 'scheme_id');
    }
    

}
