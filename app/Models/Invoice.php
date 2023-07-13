<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'project_name',
        'accepted_data',
        'rejected_data',
        'referral_email',
        'earning',
        'commission'
    ];
}
