<?php

namespace App\Model;

use App\CPU\Helpers;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class Assessor extends Model
{
    protected $fillable = [
        'assessor_id',
        'apply_designation',
        'highest_qualification',
        'technical_area',
        'experience',
        'home_address',
        'residence_tel',
        'training',
        'specific_knowledge_gained',
        'additional_information',
        'professional_experience',
        'assessment_summery',
        'qualification_document',
        'work_experience_document',
        'consultancy_document',
        'audit_document',
        'training_document',
        'profile_status',
        'remark',
    ];

    protected $casts = [
        'professional_experience' => 'json',
        'assessment_summery' => 'json',
        'evaluation_details' => 'json',
        'qualifications' => 'json',
        'area_qualifications' => 'json',
        'scope_qualifications' => 'json',
    ];   
    public function assessor(){
        return $this->belongsTo(Admin::class,'assessor_id');
    }

    public function getSchemeNamesAttribute() {
        if ($this->scheme_id) {
            $ids = explode(',', $this->scheme_id);
            return \App\Model\Scheme::whereIn('id', $ids)->pluck('title')->implode(', ');
        }
        return null;
    }

    public function getAreaNamesAttribute() {
        if ($this->area_id) {
            $ids = explode(',', $this->area_id);
            return \App\Model\SchemeArea::whereIn('id', $ids)->pluck('title')->implode(', ');
        }
        return null;
    }

    public function getScopeNamesAttribute() {
        if ($this->scope_id) {
            $ids = explode(',', $this->scope_id);
            return \App\Model\Scope::whereIn('id', $ids)->pluck('title')->implode(', ');
        }
        return null;
    }

     public function admin()
    {
        return $this->belongsTo(Admin::class, 'assessor_id', 'id');
    }
}
