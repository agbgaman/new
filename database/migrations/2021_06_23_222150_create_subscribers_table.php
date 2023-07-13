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
        Schema::create('subscribers', function (Blueprint $table) {
            $table->id();
            $table->timestamp('active_until')->nullable();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('plan_id')->unsigned();
            $table->string('plan_name')->nullable();
            $table->string('status')->nullable();
            $table->string('gateway')->nullable();
            $table->string('subscription_id')->nullable();
            $table->integer('minutes')->default(0);
            $table->integer('characters')->default(0);
            $table->string('paystack_customer_code')->nullable();
            $table->string('paystack_authorization_code')->nullable();
            $table->string('paystack_email_token')->nullable();
            $table->string('mollie_customer_id')->nullable();
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
        Schema::dropIfExists('subscribers');
    }
};
