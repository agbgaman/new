<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompaignMail extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_mail_list_id',
        'mail_body',
        'name',
        'created_by'

    ];
    public function userMailList()
    {
        return $this->belongsTo(UserMailList::class);
    }
    public function reports()
    {
        return $this->hasMany(EmailCampaignReport::class);
    }
    public function ubSubscriber()
    {
        return $this->belongsTo(unSubscribeMail::class,);
    }
}
