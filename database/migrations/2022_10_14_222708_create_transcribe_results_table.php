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
        Schema::create('transcribe_results', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->string('language');
            $table->string('language_flag')->nullable();
            $table->longText('text')->nullable();
            $table->longText('raw')->nullable();
            $table->string('speaker_identity')->nullable();
            $table->string('file_url')->nullable();
            $table->string('format')->nullable();
            $table->string('storage')->nullable()->comment('gcp|aws');
            $table->string('task_id')->nullable();
            $table->string('gcp_task')->nullable();
            $table->string('vendor_img')->nullable();
            $table->string('vendor')->comment('aws|gcp');
            $table->unsignedDecimal('length', 15, 3)->nullable();
            $table->integer('words')->nullable();
            $table->string('plan_type')->comment('free|paid');
            $table->string('audio_type')->nullable();
            $table->string('status')->nullable();
            $table->string('project')->nullable();
            $table->string('file_size')->nullable();
            $table->string('file_name')->nullable();
            $table->string('mode')->nullable()->comment('record|file|live');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transcribe_results');
    }
};
