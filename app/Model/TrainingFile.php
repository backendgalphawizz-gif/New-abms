<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class TrainingFile extends Model
{
    protected $table = 'training_files';

    protected $fillable = [
        'training_id',
        'file_type',
        'media_id',
        'file_path',
        'status'
    ];

    public function training()
    {
        return $this->belongsTo(Training::class, 'training_id');
    }
    public function media()
    {
        return $this->belongsTo(Media::class, 'media_id');
    }
}
