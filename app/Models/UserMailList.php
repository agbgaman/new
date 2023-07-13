<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserMailList extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_ids',
        'name',
        'created_by',
        'description'
    ];
}
