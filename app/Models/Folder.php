<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Folder extends Model
{
    use HasFactory;
//    use SoftDeletes;

//    protected $dates = ['deleted_at'];

    protected $fillable = [
        'name',
        'status',
        'user_id',
        'assign_user_id',
        'quality_assurance_id',
        'language_id',
        'project_id',
        'is_frozen'
    ];

    public function images()
    {
        return $this->hasMany(Image::class);
    }
    public function text()
    {
        return $this->hasMany(TextModel::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function assignUser()
    {
        return $this->belongsTo(User::class,'assign_user_id','id');
    }
    public function qualityAssurance()
    {
        return $this->belongsTo(User::class,'quality_assurance_id','id');
    }
    public function project()
    {
        return $this->belongsTo(Project::class,'project_id','id');
    }
}
