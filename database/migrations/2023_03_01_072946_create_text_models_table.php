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
        Schema::create('text_models', function (Blueprint $table) {
            $table->id();
            $table->text('text')->nullable();
            $table->string('name',100);
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('folder_id');
            $table->string('status',50)->nullable();
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
        Schema::dropIfExists('text_models');
    }
};
