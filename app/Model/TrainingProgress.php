<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class TrainingProgress extends Model
{
    protected $table = "training_progress";

    protected $fillable = [
        'training_id',
        'user_id',
        'file_id',
        'viewed',
    ];
}
