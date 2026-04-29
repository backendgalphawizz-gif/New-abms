<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\User;

class WalletTransaction extends Model
{
    use HasFactory;

    protected $casts = [
        'user_id' => 'integer',
        'reference'=>'string',
        'created_at'=>'string'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
