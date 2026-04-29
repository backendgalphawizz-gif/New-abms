<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Wishlist extends Model
{
    /**
     * Product IDs in the customer wish list (used after login). Empty if table is missing or query fails.
     */
    public static function productIdsForCustomerId(int $customerId): array
    {
        if (!Schema::hasTable((new static)->getTable())) {
            return [];
        }
        try {
            return static::query()->whereHas('wishlistProduct', function ($q) {
                return $q;
            })->where('customer_id', $customerId)->pluck('product_id')->toArray();
        } catch (\Throwable $e) {
            return [];
        }
    }

    protected $casts = [
        'product_id'  => 'integer',
        'customer_id' => 'integer',
        'created_at'  => 'datetime',
        'updated_at'  => 'datetime',
    ];

    public function wishlistProduct()
    {
        return $this->belongsTo(Product::class, 'product_id')->active();
    }
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id')->select(['id','slug']);
    }

    public function product_full_info()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
