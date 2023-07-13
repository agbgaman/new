<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TextModel extends Model
{
    use HasFactory;
    protected $fillable = [
        'text',
        'status',
        'name',
        'folder_id',
        'user_id',
        'type',
        'translated_text',
        'comment',
        'remark_id'
    ];
    public function folder()
    {
        return $this->hasOne(Folder::class,'id','folder_id');
    }
    public function transcribeText()
    {
        return $this->hasOne(TranscribeResult::class);
    }
}
