<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;

class ApplicationAssessmentStatus extends Model
{

    protected $table = 'application_assessment_status';
    // protected $casts = [
    //     'general_info' => 'json',
    //     'requirements' => 'json',
    //     'clause' => 'json',
    // ];

    public function startby()
    {
        return $this->belongsTo(Admin::class, 'start_by');
    }
    public function endby()
    {
        return $this->belongsTo(Admin::class, 'end_by');
    }
    public function application()
    {
        return $this->belongsTo(Application::class, 'application_id');
    }

    // public function dynamicDetail() {
        
    // }

}
