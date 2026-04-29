<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ShopFollower extends Model
{
    protected $casts = [
        'shop_id' => 'integer',
        'user_id' => 'integer',
    ];

    public function seller()
    {
        return $this->belongsTo(Seller::class, 'seller_id');
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class, 'shop_id');
    }

}
