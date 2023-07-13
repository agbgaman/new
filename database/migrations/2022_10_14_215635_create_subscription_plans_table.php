<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->id();
            $table->string('plan_name')->unique();
            $table->decimal('price', 15, 2)->unsigned();
            $table->string('currency')->default('USD');
            $table->string('status')->default('active')->comment('active|closed');
            $table->integer('characters')->default(0);
            $table->integer('minutes')->default(0);
            $table->integer('synthesize_tasks')->default(-1);
            $table->string('voice_type')->default('both');
            $table->string('pricing_plan')->default('monthly')->comment('monthly|yearly');
            $table->boolean('featured')->nullable()->default(false);
            $table->boolean('free')->nullable()->default(false);
            $table->string('primary_heading')->nullable();
            $table->longText('plan_features')->nullable();
            $table->string('paypal_gateway_plan_id')->nullable();
            $table->string('stripe_gateway_plan_id')->nullable();
            $table->string('paystack_gateway_plan_id')->nullable();
            $table->string('razorpay_gateway_plan_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscription_plans');
    }
};
