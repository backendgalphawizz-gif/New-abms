<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;

class FindingHistory extends Model
{

    protected $table = 'finding_history';
    protected $casts = [
        'assessor_data' => 'json',
        'cab_data' => 'json',
    ];

    // public function remarkby()
    // {
    //     return $this->belongsTo(Admin::class, 'remark_by');
    // }
    // public function application()
    // {
    //     return $this->belongsTo(Application::class, 'application_id');
    // }

    // public function dynamicDetail() {
        
    // }

}
