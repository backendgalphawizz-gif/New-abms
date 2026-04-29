<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SellerSubscription extends Model
{
    
    protected $table = "seller_subscription_transactions";

    public function plan()
    {
        return $this->hasOne(Plan::class, 'id', 'plan_id');
    }

}
