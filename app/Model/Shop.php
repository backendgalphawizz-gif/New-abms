<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    protected $casts = [
        // 'seller_id ' => 'integer',
        'created_at'  => 'datetime',
        'updated_at'  => 'datetime',
    ];

    public function seller()
    {
        return $this->belongsTo(Seller::class, 'seller_id');
    }
    public function cities()
    {
        return $this->belongsTo(City::class, 'city');
    }
    public function states()
    {
        return $this->belongsTo(State::class, 'state');
    }
    public function areas()
    {
        return $this->belongsTo(Area::class, 'area');
    }
    // public function area()
    // {
    //     return $this->belongsTo(Area::class, 'area');
    // }

    public function product(){
        return $this->hasMany(Product::class, 'user_id', 'seller_id')->where(['added_by'=>'seller', 'status'=>1]);
    }

    public function followers(){
        return $this->hasMany(ShopFollower::class, 'shop_id', 'id')->count();
    }

    public function scopeActive($query){
        return $query->whereHas('seller', function ($query) {
            $query->where(['status' => 'approved']);
        });
    }
}
