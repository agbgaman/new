<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'image',
        'folder_id',
        'status',
        'user_id',
        'comment',
        'remark_id'
    ];

    public function folder()
    {
        return $this->hasOne(Folder::class,'id','folder_id');
    }
    public function user()
    {
        return $this->hasOne(User::class,'id','user_id');
    }
    public function transcribe()
    {
        return $this->hasOne(TranscribeResult::class);
    }
    public function remark()
    {
        return $this->hasOne(ProjectRemark::class,'id','remark_id');
    }

}
