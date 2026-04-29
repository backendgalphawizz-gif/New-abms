<?php

namespace App\Model;

use App\User;
use App\Traits\CommonTrait;
use Illuminate\Database\Eloquent\Model;

class SchemeValidationVerification extends Model
{
    use CommonTrait;
    protected $table = 'validation_and_verification_applications';
    protected $casts = [
        'list_of_enclosures' => 'json',
        'no_of_employees' => 'json',
        'meeting_minimum_eligibility' => 'json',
        'type_of_validation_activities' => 'json',
        'standard_documents' => 'json',
        'organization_level_sector'=>'json',
        'project_level_sector' => 'json',
    ];

    // public function scheme()
    // {
    //     return $this->belongsTo(Scheme::class, 'scheme_id');
    // }
    public function remarkby()
    {
        return $this->belongsTo(Admin::class, 'remark_by');
    }
    public function application()
    {
        return $this->belongsTo(Application::class, 'application_id');
    }

    public function getSchemeNamesAttribute() {
        if ($this->scheme_id) {
            return \App\Model\Scheme::whereIn('id', $this->scheme_id)->pluck('title');
        }
        return null;
    }

    public function getAreaNamesAttribute() {
        if ($this->area_ids) {
            $ids = explode(',', $this->area_ids);
            return \App\Model\SchemeArea::whereIn('id', $ids)->pluck('title')->implode(', ');
        }
        return null;
    }

}
