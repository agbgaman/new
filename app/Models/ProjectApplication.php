<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectApplication extends Model
{
    protected $fillable =[
        'user_id',
        'project_id',
        'status',
        'read_at',
        'contract_form',
        'appliedForm'
    ];
    use HasFactory;

    public function projects()
    {
        return $this->hasOne(Project::class,'id','project_id');
    }
    public function users()
    {
        return $this->hasOne(User::class,'id','user_id');
    }
}
