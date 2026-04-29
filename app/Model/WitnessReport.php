<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;

class WitnessReport extends Model
{

    protected $casts = [
        'evaluation_criteria' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(Admin::class, 'user_id');
    }

    public function scheme()
    {
        return $this->belongsTo(Scheme::class, 'scheme_id');
    }
    public function auditor()
    {
        return $this->belongsTo(Admin::class, 'assessor_id');
    }
    public function assessor()
    {
        return $this->belongsTo(Assessor::class, 'assessor_id', 'assessor_id');
    }


    public function approveby()
    {
        return $this->belongsTo(Admin::class, 'approve_by');
    }


}
