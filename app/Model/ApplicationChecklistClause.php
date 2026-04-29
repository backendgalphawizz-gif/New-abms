<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;

class ApplicationChecklistClause extends Model
{

    protected $table = 'application_checklist_clauses';
    // protected $casts = [
    //     'general_data' => 'json',
    // ];

    public function commentby()
    {
        return $this->belongsTo(Admin::class, 'comment_by');
    }
    public function application()
    {
        return $this->belongsTo(Application::class, 'application_id');
    }
    public function checklist()
    {
        return $this->belongsTo(ApplicationChecklist::class, 'checklist_id');
    }


}
