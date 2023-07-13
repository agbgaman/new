<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TranscribeResult extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'text',
        'file_url',
        'file_name',
        'file_size',
        'language',
        'language_flag',
        'format',
        'storage',
        'task_id',
        'gcp_task',
        'vendor_img',
        'vendor',
        'length',
        'words',
        'plan_type',
        'audio_type',
        'status',
        'mode',
        'raw',
        'project',
        'speaker_identity',
        'image_id',
        'text_model_id'
    ];


    /**
     * Result belongs to a user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    /**
     * Result belongs to a image
     */
    public function image()
    {
        return $this->belongsTo(Image::class);
    }
    /**
     * Result belongs to a text
     */
    public function csv_text()
    {
        return $this->belongsTo(TextModel::class,'text_model_id','id')->whereNull('type');
    }
    /**
     * Result belongs to a text
     */
    public function translatedText()
    {
        return $this->belongsTo(TextModel::class,'text_model_id','id')->where('type','text_translation');
    }
}
