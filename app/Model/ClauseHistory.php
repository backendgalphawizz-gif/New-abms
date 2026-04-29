<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;

class ClauseHistory extends Model
{

    protected $table = 'checklist_clause_history';
    protected $casts = [
        'old_data' => 'json',
        'new_data' => 'json',
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
