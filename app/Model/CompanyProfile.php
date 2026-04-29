<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class CompanyProfile extends Model
{

    // protected $casts = [
    //     // 'price' => 'float',
    //     // 'seller_id' => 'integer',
    //     'contact_person_details' => 'json',
    //     'parent_organization' => 'json',
    //     'ownership_details' => 'json',
    // ];

    protected $casts = [
        'contact_person_details' => 'array',
        'parent_organization' => 'array',
        'ownership_details' => 'array',
        'invoice_address' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function auditor()
    {
        return $this->belongsTo(Admin::class, 'auditor_id');
    }
    public function application()
    {
        return $this->belongsTo(Application::class, 'application_id');
    }

    public static function tableAvailable(): bool
    {
        try {
            return Schema::hasTable((new static)->getTable());
        } catch (\Throwable $e) {
            return false;
        }
    }
}
