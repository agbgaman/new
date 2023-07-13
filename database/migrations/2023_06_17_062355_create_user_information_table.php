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
        Schema::create('user_information', function (Blueprint $table) {
            $table->id();
            $table->string('gender')->nullable();
            $table->string('user_id')->nullable();
            $table->string('hasPet')->nullable();
            $table->string('date')->nullable();
            $table->string('hasTranslationExperience')->nullable();
            $table->string('englishLearningAge')->nullable();
            $table->string('spent_time_country')->nullable();
            $table->string('familyParticipation')->nullable();
            $table->string('experienceSearchEngineEvaluator')->nullable();
            $table->string('experienceProofreading')->nullable();
            $table->string('experienceTranscription')->nullable();
            $table->string('linguistics')->nullable();
            $table->string('education')->nullable();
            $table->string('residency_years')->nullable();
            $table->string('address')->nullable();
            $table->string('primary_language')->nullable();
            $table->string('race_and_ethnicity')->nullable();
            $table->string('android_functionality')->nullable();
            $table->string('country_you_lived')->nullable();
            $table->string('working_company')->nullable();
            $table->string('english_skills')->nullable();
            $table->string('born_city')->nullable();
            $table->string('state_province')->nullable();
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
        Schema::dropIfExists('user_information');
    }
};
