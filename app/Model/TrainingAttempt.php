<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class TrainingAttempt extends Model
{
    // protected $table = 'training_attempts';

    // protected $fillable = [
    //     'training_id',
    //     'user_id',
    //     'total_questions',
    //     'correct_answers',
    //     'wrong_answers',
    //     'is_completed',
    // ];

    public function training()
    {
        return $this->belongsTo(Training::class, 'training_id');
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'user_id')
                    ->where('admin_role_id', 3);
    }
}
