<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    use HasFactory;
    protected $fillable = [
        'status',
        'price_name',
        'currency',
        'text_price',
        'coco_price',
        'image_price',
        'commission',
    ];
}
