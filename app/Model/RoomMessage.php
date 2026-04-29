<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class RoomMessage extends Model {

    protected $appends = [
        'file_url'
    ];

    public function user() {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    public function getFileUrlAttribute() {
        return asset('storage/app/public/admin/' . $this->file);
    }

}
