<?php

namespace App\Model;

use App\User;
use App\Traits\CommonTrait;
use Illuminate\Database\Eloquent\Model;

class SchemeInspectionBody extends Model
{

    use CommonTrait;
    protected $table = 'inspection_body_applications';
    protected $casts = [
        'scope_of_inspection' => 'json',
        'inspection_equipment' => 'json',
        'calibration_sites'=>'json',
        'calibration_sites_table_data' => 'json',
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
