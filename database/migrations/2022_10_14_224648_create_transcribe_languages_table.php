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
        Schema::create('transcribe_languages', function (Blueprint $table) {
            $table->id();
            $table->string('language');
            $table->string('language_code');
            $table->string('language_flag')->nullable();
            $table->string('vendor')->nullable();
            $table->string('vendor_img')->nullable();
            $table->string('speaker_identity')->nullable();
            $table->string('status')->nullable();
            $table->string('type')->nullable()->comment('live|file|both');
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
        Schema::dropIfExists('transcribe_languages');
    }
};
