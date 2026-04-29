<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;

class ApplicationBasicDetail extends Model
{

    protected $table = 'application_scheme_basic_details';
    protected $casts = [
        'main_activities' => 'json',
        'name_of_cab_appears' => 'json',
        'internal_audit_review' => 'json',
        'senior_staff_info' => 'json',
        'scheme_manager_info' => 'json',
        'another_key_person_info' => 'json',
        'quality_manager_info' => 'json',
        'local_regulation' => 'json',
        'certifications' => 'json',
        'suspended_or_withdrawn' => 'json',
    ];

    public function remarkby()
    {
        return $this->belongsTo(Admin::class, 'remark_by');
    }
    public function application()
    {
        return $this->belongsTo(Application::class, 'application_id');
    }

    public function dynamicDetail() {
        
    }

}
