<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'name',
        'default',
        'description',
        'type',
        'status',
        'term_and_condition',
        'consent_form',
        'short_description',
        'country',
        'contract_form',
        'price'
    ];

    public function user()
    {
        return $this->hasOne(User::class,'id','user_id');
    }
    public function remarks()
    {
        return $this->hasMany(ProjectRemark::class,'project_id','id');
    }
}
