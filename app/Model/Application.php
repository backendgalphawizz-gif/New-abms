<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{

    protected $casts = [
        'application_outside_usa' => 'array'
    ];

    public function application_assessment()
    {
        return $this->hasOne(ApplicationAssessment::class, 'application_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function company()
    {
        return $this->belongsTo(CompanyProfile::class, 'company_id');
    }
    public function scheme()
    {
        return $this->belongsTo(Scheme::class, 'scheme_id');
    }
    public function auditor()
    {
        return $this->belongsTo(Admin::class, 'auditor_id');
    }

    public function office_assessment()
    {
        return $this->belongsTo(Admin::class, 'office_assessment_id');
    }

    public function witness_assessment()
    {
        return $this->belongsTo(Admin::class, 'witness_assessment_id');
    }

    public function quality()
    {
        return $this->belongsTo(Admin::class, 'quality_check_id');
    }
    public function accreditation()
    {
        return $this->belongsTo(Admin::class, 'accreditation_id');
    }
    public function fees()
    {
        return $this->belongsTo(FeeStructure::class, 'scheme_id','scheme_id');
    }

    public function getOfficeAssessmentTeamAttribute()
    {
        if (!$this->office_assessment_team_ids) {
            return collect([]);
        }

        $ids = explode(',', $this->office_assessment_team_ids);

        return Admin::select('id','name','phone','email','witness_status')->whereIn('id', $ids)->get();
    }
    public function getWitnessAssessmentTeamAttribute()
    {
        if (!$this->witness_assessment_team_ids) {
            return collect([]);
        }

        $ids = explode(',', $this->witness_assessment_team_ids);

        return Admin::select('id','name','phone','email','witness_status')->whereIn('id', $ids)->get();
    }
    public function getAuditorTeamAttribute()
    {
        if (!$this->auditor_team_ids) {
            return collect([]);
        }

        $ids = explode(',', $this->auditor_team_ids);

        return Admin::select('id','name','phone','email','witness_status')->whereIn('id', $ids)->get();
    }
    public function getQualityTeamAttribute()
    {
        if (!$this->quality_check_team_ids) {
            return collect([]);
        }

        $ids = explode(',', $this->quality_check_team_ids);

        return Admin::select('id','name','phone','email','witness_status')->whereIn('id', $ids)->get();
    }
    public function getAccreditationTeamAttribute()
    {
        if (!$this->accreditation_team_ids) {
            return collect([]);
        }

        $ids = explode(',', $this->accreditation_team_ids);

        return Admin::select('id','name','phone','email','witness_status')->whereIn('id', $ids)->get();
    }

    public function basic_info()
    {
        return $this->hasOne(ApplicationBasicDetail::class, 'application_id');
    }
    public function document()
    {
        return $this->hasOne(ApplicationDocument::class, 'application_id');
    }
    public function checklist()
    {
        return $this->hasOne(ApplicationChecklist::class, 'application_id');
    }
    public function payments()
    {
        return $this->hasOne(ApplicationPaymentDetail::class, 'application_id');
    }
    public function assessmentstatus()
    {
        return $this->hasOne(ApplicationAssessmentStatus::class, 'application_id');
    }

    // public function schemeRelation()
    // {
    //     switch ($this->scheme_id) {
    //         case 1:
    //             return $this->hasOne(SchemeTestingLaboratory::class, 'application_id');
    //         case 2:
    //             return $this->hasOne(SchemeCalibrationLaboratory::class, 'application_id');
    //         case 3:
    //             return $this->hasOne(SchemeMedicalLaboratory::class, 'application_id');
    //         case 4:
    //             return $this->hasOne(SchemeInspectionBody::class, 'application_id');
    //         case 5:
    //             return $this->hasOne(SchemeMsCertificationBody::class, 'application_id');
    //         case 6:
    //             return $this->hasOne(SchemeProficiencyTesting::class, 'application_id');
    //         case 7:
    //             return $this->hasOne(SchemeProductCertification::class, 'application_id');
    //         case 8:
    //             return $this->hasOne(SchemePersonCertification::class, 'application_id');
    //         case 9:
    //             return $this->hasOne(SchemeForensicService::class, 'application_id');
    //         case 10:
    //             return $this->hasOne(SchemeHalalCertification::class, 'application_id');
    //         case 11:
    //             return $this->hasOne(SchemeBiotechnologyBiobank::class, 'application_id');
    //         default:
    //             return null;
    //     }
    // }


}
