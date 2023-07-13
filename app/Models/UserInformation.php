<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserInformation extends Model
{
    use HasFactory;

    protected $fillable = [
        'gender',
        'hasPet',
        'date',
        'hasTranslationExperience',
        'englishLearningAge',
        'spent_time_country',
        'familyParticipation',
        'education',
        'linguistics',
        'experienceSearchEngineEvaluator',
        'experienceProofreading',
        'experienceTranscription',
        'linguistics',
        'education',
        'residency_years',
        'address',
        'state_province',
        'primary_language',
        'race_and_ethnicity',
        'englishLearningAge',
        'android_functionality',
        'country_you_lived',
        'working_company',
        'english_skills',
        'user_id',
        'born_city',

    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
