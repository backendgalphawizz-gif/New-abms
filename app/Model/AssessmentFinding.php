<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;

class AssessmentFinding extends Model
{

    protected $table = 'assessment_findings';
    protected $casts = [
        'general_info' => 'json',
        'detailed_findings' => 'json',
        'action_proposed_by_cab' => 'json',
        'responses' => 'json',
    ];

    // public function remarkby()
    // {
    //     return $this->belongsTo(Admin::class, 'remark_by');
    // }
    // public function application()
    // {
    //     return $this->belongsTo(Application::class, 'application_id');
    // }

    public function histroy() {
        return $this->hasMany(FindingHistory::class, 'finding_id');
    }

}
