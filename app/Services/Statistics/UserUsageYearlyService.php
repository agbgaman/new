<?php

namespace App\Services\Statistics;

use App\Models\UserInformation;
use Illuminate\Support\Facades\Auth;
use App\Models\VoiceoverResult;
use App\Models\TranscribeResult;
use DB;

class UserUsageYearlyService
{
    private $year;

    public function __construct(int $year)
    {
        $this->year = $year;
    }

    # Voiceover data usage

    public function getStandardCharsUsage($user = null)
    {
        $user_id = (is_null($user)) ? Auth::user()->id : $user;

        $standard_chars = VoiceoverResult::select(DB::raw("sum(characters) as data"), DB::raw("MONTH(created_at) month"))
                ->whereYear('created_at', $this->year)
                ->where('user_id', $user_id)
                ->where('voice_type', 'standard')
                ->groupBy('month')
                ->orderBy('month')
                ->get()->toArray();

        $data = [];

        for($i = 1; $i <= 12; $i++) {
            $data[$i] = 0;
        }

        foreach ($standard_chars as $row) {
            $month = $row['month'];
            $data[$month] = intval($row['data']);
        }

        return $data;
    }


    public function getNeuralCharsUsage($user = null)
    {
        $user_id = (is_null($user)) ? Auth::user()->id : $user;

        $neural_chars = VoiceoverResult::select(DB::raw("sum(characters) as data"), DB::raw("MONTH(created_at) as month"))
                ->whereYear('created_at', $this->year)
                ->where('user_id', $user_id)
                ->where('voice_type', 'neural')
                ->groupBy('month')
                ->orderBy('month')
                ->get()->toArray();

        $data = [];

        for($i = 1; $i <= 12; $i++) {
            $data[$i] = 0;
        }

        foreach ($neural_chars as $row) {
            $month = $row['month'];
            $data[$month] = intval($row['data']);
        }

        return $data;
    }


    public function getTotalStandardCharsUsage($user = null)
    {
        $user_id = (is_null($user)) ? Auth::user()->id : $user;

        $standard_chars = VoiceoverResult::select(DB::raw("sum(characters) as data"))
                ->whereYear('created_at', $this->year)
                ->where('user_id', $user_id)
                ->where('voice_type', 'standard')
                ->get();

        return $standard_chars;
    }


    public function getTotalNeuralCharsUsage($user = null)
    {
        $user_id = (is_null($user)) ? Auth::user()->id : $user;

        $neural_chars = VoiceoverResult::select(DB::raw("sum(characters) as data"))
                ->whereYear('created_at', $this->year)
                ->where('user_id', $user_id)
                ->where('voice_type', 'neural')
                ->get();

        return $neural_chars;
    }


    public function getTotalAudioFiles($user = null)
    {
        $user_id = (is_null($user)) ? Auth::user()->id : $user;

        $audio_files = VoiceoverResult::select(DB::raw("count(id) as data"))
                ->whereYear('created_at', $this->year)
                ->where('user_id', $user_id)
                ->where('mode', 'file')
                ->get();

        return $audio_files;
    }


    public function getTotalListenModes($user = null)
    {
        $user_id = (is_null($user)) ? Auth::user()->id : $user;

        $audio_files = VoiceoverResult::select(DB::raw("count(id) as data"))
                ->whereYear('created_at', $this->year)
                ->where('user_id', $user_id)
                ->where('mode', 'live')
                ->get();

        return $audio_files;
    }


    public function getAllCharsUsage($user = null)
    {
        $user_id = (is_null($user)) ? Auth::user()->id : $user;

        $chars = VoiceoverResult::select(DB::raw("sum(characters) as data"))
                ->where('user_id', $user_id)
                ->get();

        return $chars;
    }

    # Transcribe data usage

    public function getFileMinutesUsage($user = null)
    {
        $user_id = (is_null($user)) ? Auth::user()->id : $user;

        $file_minutes = TranscribeResult::select(DB::raw("sum(length) as data"), DB::raw("MONTH(created_at) month"))
                ->whereYear('created_at', $this->year)
                ->where('user_id', $user_id)
                ->where('mode', 'file')
                ->groupBy('month')
                ->orderBy('month')
                ->get()->toArray();

        $data = [];

        for($i = 1; $i <= 12; $i++) {
            $data[$i] = 0;
        }

        foreach ($file_minutes as $row) {
            $month = $row['month'];
            $data[$month] = (number_format((float)$row['data']/60, 2));
        }

        return $data;
    }


    public function getRecordMinutesUsage($user = null)
    {
        $user_id = (is_null($user)) ? Auth::user()->id : $user;

        $record_minutes = TranscribeResult::select(DB::raw("sum(length) as data"), DB::raw("MONTH(created_at) as month"))
                ->whereYear('created_at', $this->year)
                ->where('user_id', $user_id)
                ->where('mode', 'record')
                ->groupBy('month')
                ->orderBy('month')
                ->get()->toArray();

        $data = [];

        for($i = 1; $i <= 12; $i++) {
            $data[$i] = 0;
        }

        foreach ($record_minutes as $row) {
            $month = $row['month'];
            $data[$month] = (number_format((float)$row['data']/60, 2));
        }

        return $data;
    }


    public function getLiveMinutesUsage($user = null)
    {
        $user_id = (is_null($user)) ? Auth::user()->id : $user;

        $live_minutes = TranscribeResult::select(DB::raw("sum(length) as data"), DB::raw("MONTH(created_at) as month"))
                ->whereYear('created_at', $this->year)
                ->where('user_id', $user_id)
                ->where('mode', 'live')
                ->groupBy('month')
                ->orderBy('month')
                ->get()->toArray();

        $data = [];

        for($i = 1; $i <= 12; $i++) {
            $data[$i] = 0;
        }

        foreach ($live_minutes as $row) {
            $month = $row['month'];
            $data[$month] = (number_format((float)$row['data']/60, 2));
        }

        return $data;
    }


    public function getTotalMinutes($user = null)
    {
        $user_id = (is_null($user)) ? Auth::user()->id : $user;

        $minutes = TranscribeResult::select(DB::raw("sum(length) as data"))
                ->whereYear('created_at', $this->year)
                ->where('user_id', $user_id)
                ->get();

        return $minutes;
    }


    public function getTotalWords($user = null)
    {
        $user_id = (is_null($user)) ? Auth::user()->id : $user;

        $words = TranscribeResult::select(DB::raw("sum(words) as data"))
                ->whereYear('created_at', $this->year)
                ->where('user_id', $user_id)
                ->get();

        return $words;
    }


    public function getTotalFileTranscribe($user = null)
    {
        $user_id = (is_null($user)) ? Auth::user()->id : $user;

        $files = TranscribeResult::select(DB::raw("count(id) as data"))
                ->whereYear('created_at', $this->year)
                ->where('user_id', $user_id)
                ->where('mode', 'file')
                ->get();

        return $files;
    }


    public function getTotalRecordingTranscribe($user = null)
    {
        $user_id = (is_null($user)) ? Auth::user()->id : $user;

        $record = TranscribeResult::select(DB::raw("count(id) as data"))
                ->whereYear('created_at', $this->year)
                ->where('user_id', $user_id)
                ->where('mode', 'record')
                ->get();

        return $record;
    }


    public function getTotalLiveTranscribe($user = null)
    {
        $user_id = (is_null($user)) ? Auth::user()->id : $user;

        $live = TranscribeResult::select(DB::raw("count(id) as data"))
                ->whereYear('created_at', $this->year)
                ->where('user_id', $user_id)
                ->where('mode', 'live')
                ->get();

        return $live;
    }


    public function getAllMinutesUsage($user = null)
    {
        $user_id = (is_null($user)) ? Auth::user()->id : $user;

        $chars = TranscribeResult::select(DB::raw("sum(length) as data"))
                ->where('user_id', $user_id)
                ->get();

        return $chars;
    }

    public function userInformationPercentage($id)
    {
        $userInformation = UserInformation::where('user_id', $id)->first();
        if ($userInformation == null) return 0;
        $totalFields = 19; // total number of fields in user_information excluding 'id' and 'created_at', 'updated_at'

        $filledFields = 0;

        if($userInformation->gender) $filledFields++;
        if($userInformation->hasPet) $filledFields++;
        if($userInformation->date) $filledFields++;
        if($userInformation->hasTranslationExperience) $filledFields++;
        if($userInformation->englishLearningAge) $filledFields++;
        if($userInformation->spent_time_country) $filledFields++;
        if($userInformation->familyParticipation) $filledFields++;
//        if($userInformation->experienceSearchEngineEvaluator) $filledFields++;
//        if($userInformation->experienceProofreading) $filledFields++;
//        if($userInformation->experienceTranscription) $filledFields++;
        if($userInformation->linguistics) $filledFields++;
        if($userInformation->education) $filledFields++;
        if($userInformation->residency_years) $filledFields++;
        if($userInformation->address) $filledFields++;
        if($userInformation->primary_language) $filledFields++;
        if($userInformation->race_and_ethnicity) $filledFields++;
        if($userInformation->android_functionality) $filledFields++;
        if($userInformation->country_you_lived) $filledFields++;
        if($userInformation->working_company) $filledFields++;
        if($userInformation->english_skills) $filledFields++;
        if($userInformation->born_city) $filledFields++;
        if($userInformation->state_province) $filledFields++;

        // calculate the completion percentage
        return round(($filledFields / $totalFields) * 100, 2);
    }
}
