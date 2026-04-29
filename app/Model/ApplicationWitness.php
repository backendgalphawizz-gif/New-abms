<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;

class ApplicationWitness extends Model
{

    protected $table = 'application_witnesses';
    protected $casts = [
        'attachments' => 'json',
        'witness_assessment' => 'json',
        'witness_findings' => 'json',
    ];

    // public function remarkby()
    // {
    //     return $this->belongsTo(Admin::class, 'remark_by');
    // }
    public function application()
    {
        return $this->belongsTo(Application::class, 'application_id');
    }
    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }
    public function scheme()
    {
        return $this->belongsTo(Scheme::class, 'scheme_id');
    }
    public function auditor()
    {
        return $this->belongsTo(Admin::class, 'auditor_id');
    }

    public function getAuditorTeamAttribute()
    {
        if (!$this->auditor_team_ids) {
            return collect([]);
        }

        $ids = explode(',', $this->auditor_team_ids);

        return Admin::select('id','name','phone','email','witness_status')->whereIn('id', $ids)->get();
    }

}
