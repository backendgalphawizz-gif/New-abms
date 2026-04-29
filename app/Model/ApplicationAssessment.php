<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ApplicationAssessment extends Model {

    protected $casts = [
        'clause' => 'json',
        'main_general_info' => 'json',
        'team_leader_assessment' => 'json',
        'technical_assessment' => 'json',
        'vertical_assessment' => 'json',
        'witness_assessment' => 'json',
    ];

}
