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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('job_role')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('status')->nullable();
            $table->integer('plan_id')->nullable();
            $table->string('group')->nullable();
            $table->string('company')->nullable();
            $table->string('website')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('country')->nullable();
            $table->integer('available_chars')->default(0);
            $table->integer('available_chars_prepaid')->default(0);
            $table->string('language')->nullable();
            $table->string('voice')->nullable();
            $table->string('project')->nullable();
            $table->string('language_file')->nullable();
            $table->string('language_live')->nullable();
            $table->decimal('available_minutes', 15, 3)->default(0);
            $table->decimal('available_minutes_prepaid', 15, 3)->default(0);
            $table->integer('synthesize_tasks')->default(-1);
            $table->string('voice_type')->nullable();
            $table->text('profile_photo_path')->nullable();
            $table->string('oauth_id')->nullable();
            $table->string('oauth_type')->nullable();     
            $table->string('referral_id')->nullable();
            $table->string('referred_by')->nullable();
            $table->string('referral_payment_method')->nullable();
            $table->string('referral_paypal')->nullable();
            $table->text('referral_bank_requisites')->nullable();
            $table->timestamp('last_seen')->nullable();
            $table->text('google2fa_secret')->nullable();
            $table->boolean('google2fa_enabled')->nullable()->default(0);
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
};
