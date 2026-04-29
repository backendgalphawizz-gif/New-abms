<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;

class SchemeChecklist extends Model
{

    protected $table = 'scheme_checklists';
    protected $casts = [
        'data' => 'json',
        'clause' => 'json',
        'team_leader_assessment' => 'json',
        'technical_assessment' => 'json',
        'vertical_assessment' => 'json',
        'findings' => 'json',
        'witness_assessment' => 'json',
        'witness_findings' => 'json',
    ];

    public function payment()
    {
        return $this->hasOne(FeeStructure::class, 'scheme_id','scheme_id');
    }
    // public function application()
    // {
    //     return $this->belongsTo(Application::class, 'application_id');
    // }

    // public function dynamicDetail() {
        
    // }

}
