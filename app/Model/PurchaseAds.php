<?php

namespace App\Model;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class PurchaseAds extends Authenticatable
{
    protected $table = 'purchase_ads';

    public function advertisement() {
        return $this->belongsTo(Advertisement::class, 'ad_id', 'id');
    }

}
