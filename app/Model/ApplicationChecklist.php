<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;

class ApplicationChecklist extends Model
{

    protected $table = 'application_checklists';
    protected $casts = [
        'general_info' => 'json',
        'requirements' => 'json',
        'clause' => 'json',
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
