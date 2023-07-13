<?php

namespace App\Services\Statistics;

use App\Models\Folder;
use App\Models\Invoice;
use App\Models\Referral;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\VoiceoverResult;
use App\Models\TranscribeResult;
use DB;

class UserUsageMonthlyService
{
    private $month;
    private $year;

    public function __construct(int $month, int $year)
    {
        $this->month = $month;
        $this->year = $year;
    }

    # Voiceover character usage

    public function getTotalStandardCharsUsage()
    {
        $standard_chars = VoiceoverResult::select(DB::raw("sum(characters) as data"))
                ->whereMonth('created_at', $this->month)
                ->whereYear('created_at', $this->year)
                ->where('user_id', Auth::user()->id)
                ->where('voice_type', 'standard')
                ->get();

        return $standard_chars;
    }


    public function getTotalNeuralCharsUsage()
    {
        $neural_chars = VoiceoverResult::select(DB::raw("sum(characters) as data"))
                ->whereMonth('created_at', $this->month)
                ->whereYear('created_at', $this->year)
                ->where('user_id', Auth::user()->id)
                ->where('voice_type', 'neural')
                ->get();

        return $neural_chars;
    }


    public function getTotalVoiceoverResults()
    {
        $audio_files = VoiceoverResult::select(DB::raw("count(result_url) as data"))
                ->whereMonth('created_at', $this->month)
                ->whereYear('created_at', $this->year)
                ->where('user_id', Auth::user()->id)
                ->get();

        return $audio_files;
    }

    # Transcribe minutes usage

    public function getTotalMinutes()
    {
        $now = Carbon::now();
        $startOfMonth = $now->startOfMonth();
        $endOfMonth = $now->copy()->endOfMonth();

        $folderImage = Folder::where('user_id',Auth::user()->id)
            ->where('status','paid')
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->count();

        $folderImage1 = Folder::where('assign_user_id',Auth::user()->id)
            ->where('status','paid')
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->count();

        return $folderImage + $folderImage1;
    }


    public function getTotalWords()
    {
        $words = TranscribeResult::select(DB::raw("sum(words) as data"))
                ->whereMonth('created_at', $this->month)
                ->whereYear('created_at', $this->year)
                ->where('user_id', Auth::user()->id)
                ->get();

        return $words;
    }


    public function getTotalTranscribeInputs()
    {
        $referrer = Referral::where('referrer_id', Auth::user()->id)
                ->count();

        return $referrer;
    }

    public function getEarning()
    {
        $now = Carbon::now();
        $startOfMonth = $now->startOfMonth();
        $endOfMonth = $now->copy()->endOfMonth();

        $earning = Invoice::where('user_id',Auth::user()->id)
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->sum('earning');

        return $earning;
    }


}
