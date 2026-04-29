<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Training extends Model
{
    protected $table = 'trainings';

    // protected $fillable = [
    //     'scheme_id',
    //     'area_id',
    //     'scope_id',
    //     'title',
    //     'description',
    //     'image',
    //     'status',
    //     'auth_id'
    // ];
    public function scheme()
    {
        return $this->belongsTo(Scheme::class, 'scheme_id');
    }

    public function area()
    {
        return $this->belongsTo(SchemeArea::class, 'area_id');
    }


    public function scopeData()
    {
        return $this->belongsTo(Scope::class, 'scope_id');
    }

    public function files()
    {
        return $this->hasMany(TrainingFile::class, 'training_id');
    }
    public function question()
    {
        return $this->hasMany(TrainingQuestion::class, 'training_id');
    }
}
