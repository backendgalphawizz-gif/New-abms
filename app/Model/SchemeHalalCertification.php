<?php

namespace App\Model;

use App\User;
use App\Traits\CommonTrait;
use Illuminate\Database\Eloquent\Model;

class SchemeHalalCertification extends Model
{
    use CommonTrait;
    protected $table = 'halal_certification_body_applications';
    protected $casts = [
        'location_details' => 'json',
        'authorized_person_detail' => 'json',
        'islamic_affairs' => 'json',
        'halal_product_table_1' => 'json',
        'halal_product_table_2' => 'json',
        'halal_product_table_3' => 'json',
    ];

    protected $appends = [
        'stamp_url',
        'logo_url'
    ];

    public function getStampUrlAttribute() {
        return $this->belongsTo(Media::class, 'stamp');
    }
    public function getLogoUrlAttribute() {
        return $this->belongsTo(Media::class, 'logo');
    }
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
