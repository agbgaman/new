<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'paypal_gateway_plan_id',
        'stripe_gateway_plan_id',
        'paystack_gateway_plan_id',
        'razorpay_gateway_plan_id',
        'status',
        'plan_name',
        'price',
        'currency',
        'characters',
        'minutes',
        'pricing_plan',
        'featured',
        'free',
        'primary_heading', 
        'featured',
        'plan_features', 
        'synthesize_tasks',
        'voice_type'
    ];

    /**
     * Plan can have many subscribers
     * 
     */
    public function subscriptions()
    {
        return $this->hasMany(Subscriber::class);
    }
}
