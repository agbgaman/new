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
     **/
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
                $table->bigInteger('user_id')->nullable();
            $table->string('project_name')->nullable();
            $table->string('accepted_data')->nullable();
            $table->string('rejected_data')->nullable();
            $table->string('referral_email')->nullable();
            $table->double('earning',15,2)->nullable();
            $table->double('commission',15,2)->nullable();
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
        Schema::dropIfExists('invoices');
    }
};
