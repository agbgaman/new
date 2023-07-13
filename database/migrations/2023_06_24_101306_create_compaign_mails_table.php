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
        Schema::create('compaign_mails', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_mail_list_id')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->text('mail_body')->nullable();
            $table->string('name')->nullable();
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
        Schema::dropIfExists('compaign_mails');
    }
};
