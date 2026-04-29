<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class ProductCompare extends Model
{
    use HasFactory;

    /**
     * Product IDs in compare list for this customer user. Empty if table is missing.
     */
    public static function productIdsForUserId(int $userId): array
    {
        if (!Schema::hasTable((new static)->getTable())) {
            return [];
        }
        try {
            return static::query()->where('user_id', $userId)->pluck('product_id')->toArray();
        } catch (\Throwable $e) {
            return [];
        }
    }

    protected $casts = [
        'product_id'  => 'integer',
        'user_id'     => 'integer',
        'created_at'  => 'datetime',
        'updated_at'  => 'datetime',
    ];
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id')->active();
    }
}
