<?php

namespace App\Services\Statistics;

use App\Models\Folder;
use App\Models\Project;
use App\Models\TextModel;
use App\Models\TranscribeResult;
use DB;

class TranscribeService
{
    private $year;
    private $month;

    public function __construct(int $year, int $month)
    {
        $this->year = $year;
        $this->month = $month;
    }


    public function getFreeMinutesUsageYearly()
    {
        $free_chars = TranscribeResult::select(DB::raw("sum(length) as data"), DB::raw("MONTH(created_at) month"))
            ->whereYear('created_at', $this->year)
            ->where('plan_type', 'free')
            ->groupBy('month')
            ->orderBy('month')
            ->get()->toArray();

        $data = [];

        for ($i = 1; $i <= 12; $i++) {
            $data[$i] = 0;
        }

        foreach ($free_chars as $row) {
            $month = $row['month'];
            $data[$month] = (number_format((float)$row['data'] / 60, 2));
        }

        return $data;
    }


    public function getPaidMinutesUsageYearly()
    {
        $paid_chars = TranscribeResult::select(DB::raw("sum(length) as data"), DB::raw("MONTH(created_at) as month"))
            ->whereYear('created_at', $this->year)
            ->where('plan_type', 'paid')
            ->groupBy('month')
            ->orderBy('month')
            ->get()->toArray();

        $data = [];

        for ($i = 1; $i <= 12; $i++) {
            $data[$i] = 0;
        }

        foreach ($paid_chars as $row) {
            $month = $row['month'];
            $data[$month] = (number_format((float)$row['data'] / 60, 2));
        }

        return $data;
    }


    public function getTotalFreeMinutesUsageYearly()
    {
        $free_minutes = TranscribeResult::select(DB::raw("sum(length) as data"))
            ->whereYear('created_at', $this->year)
            ->where('plan_type', 'free')
            ->get();

        return $free_minutes;
    }


    public function getTotalPaidMinutesUsageYearly()
    {
        $paid_minutes = TranscribeResult::select(DB::raw("sum(length) as data"))
            ->whereYear('created_at', $this->year)
            ->where('plan_type', 'paid')
            ->get();

        return $paid_minutes;
    }


    public function getTotalWordsYearly()
    {
        $words = TranscribeResult::select(DB::raw("sum(words) as data"))
            ->whereYear('created_at', $this->year)
            ->get();

        return $words;
    }


    public function getTotalFileTranscribeYearly()
    {
        $audio_files = TranscribeResult::select(DB::raw("count(id) as data"))
            ->whereYear('created_at', $this->year)
            ->where('mode', 'file')
            ->get();

        return $audio_files;
    }


    public function getTotalRecordingTranscribeYearly()
    {
        $audio_files = TranscribeResult::select(DB::raw("count(id) as data"))
            ->whereYear('created_at', $this->year)
            ->where('mode', 'record')
            ->get();

        return $audio_files;
    }


    public function getTotalLiveTranscribeYearly()
    {
        $audio_files = TranscribeResult::select(DB::raw("count(id) as data"))
            ->whereYear('created_at', $this->year)
            ->where('mode', 'live')
            ->get();

        return $audio_files;
    }


    public function getTotalTasksMonthly()
    {
        $tasks = TranscribeResult::select(DB::raw("count(id) as data"))
            ->whereMonth('created_at', $this->month)
            ->whereYear('created_at', $this->year)
            ->get();

        return $tasks;
    }


    public function getTotalTasksPastMonth()
    {
        $date = \Carbon\Carbon::now();
        $pastMonth = $date->subMonth()->format('m');

        $tasks = TranscribeResult::select(DB::raw("count(id) as data"))
            ->whereMonth('created_at', $pastMonth)
            ->whereYear('created_at', $this->year)
            ->get();

        return $tasks;
    }


    public function getFileMinutesUsage($user = null)
    {
        $file_minutes = TranscribeResult::select(DB::raw("sum(length) as data"), DB::raw("MONTH(created_at) month"))
            ->whereYear('created_at', $this->year)
            ->where('mode', 'file')
            ->groupBy('month')
            ->orderBy('month')
            ->get()->toArray();

        $data = [];

        for ($i = 1; $i <= 12; $i++) {
            $data[$i] = 0;
        }

        foreach ($file_minutes as $row) {
            $month = $row['month'];
            $data[$month] = (number_format((float)$row['data'] / 60, 2));
        }

        return $data;
    }


    public function getRecordMinutesUsage($user = null)
    {
        $record_minutes = TranscribeResult::select(DB::raw("sum(length) as data"), DB::raw("MONTH(created_at) as month"))
            ->whereYear('created_at', $this->year)
            ->where('mode', 'record')
            ->groupBy('month')
            ->orderBy('month')
            ->get()->toArray();

        $data = [];

        for ($i = 1; $i <= 12; $i++) {
            $data[$i] = 0;
        }

        foreach ($record_minutes as $row) {
            $month = $row['month'];
            $data[$month] = (number_format((float)$row['data'] / 60, 2));
        }

        return $data;
    }


    public function getLiveMinutesUsage()
    {
        $live_minutes = TranscribeResult::select(DB::raw("sum(length) as data"), DB::raw("MONTH(created_at) as month"))
            ->whereYear('created_at', $this->year)
            ->where('mode', 'live')
            ->groupBy('month')
            ->orderBy('month')
            ->get()->toArray();

        $data = [];

        for ($i = 1; $i <= 12; $i++) {
            $data[$i] = 0;
        }

        foreach ($live_minutes as $row) {
            $month = $row['month'];
            $data[$month] = (number_format((float)$row['data'] / 60, 2));
        }

        return $data;
    }


    public function getTotalFreeMinutesUsageMonthly()
    {
        $free_minutes = TranscribeResult::select(DB::raw("sum(length) as data"))
            ->whereMonth('created_at', $this->month)
            ->whereYear('created_at', $this->year)
            ->where('plan_type', 'free')
            ->get();

        return $free_minutes;
    }


    public function getTotalFreeMinutesUsagePastMonth()
    {
        $date = \Carbon\Carbon::now();
        $pastMonth = $date->subMonth()->format('m');

        $free_minutes = TranscribeResult::select(DB::raw("sum(length) as data"))
            ->whereMonth('created_at', $pastMonth)
            ->whereYear('created_at', $this->year)
            ->where('plan_type', 'free')
            ->get();

        return $free_minutes;
    }


    public function getTotalPaidMinutesUsageMonthly()
    {
        $paid_minutes = TranscribeResult::select(DB::raw("sum(length) as data"))
            ->whereMonth('created_at', $this->month)
            ->whereYear('created_at', $this->year)
            ->where('plan_type', 'paid')
            ->get();

        return $paid_minutes;
    }


    public function getTotalPaidMinutesUsagePastMonth()
    {
        $date = \Carbon\Carbon::now();
        $pastMonth = $date->subMonth()->format('m');

        $paid_minutes = TranscribeResult::select(DB::raw("sum(length) as data"))
            ->whereMonth('created_at', $pastMonth)
            ->whereYear('created_at', $this->year)
            ->where('plan_type', 'paid')
            ->get();

        return $paid_minutes;
    }


    public function getAWSUsageMonthly()
    {
        $vendor = TranscribeResult::select(DB::raw("sum(length) as data"))
            ->whereMonth('created_at', $this->month)
            ->whereYear('created_at', $this->year)
            ->where('vendor', 'aws')
            ->get();

        return $vendor;
    }


    public function getAWSUsageYearly()
    {
        $vendor = TranscribeResult::select(DB::raw("sum(length) as data"))
            ->whereYear('created_at', $this->year)
            ->where('vendor', 'aws')
            ->get();

        return $vendor;
    }


    public function getGCPUsageMonthly()
    {
        $vendor = TranscribeResult::select(DB::raw("sum(length) as data"))
            ->whereMonth('created_at', $this->month)
            ->whereYear('created_at', $this->year)
            ->where('vendor', 'gcp')
            ->get();

        return $vendor;
    }


    public function getGCPUsageYearly()
    {
        $vendor = TranscribeResult::select(DB::raw("sum(length) as data"))
            ->whereYear('created_at', $this->year)
            ->where('vendor', 'gcp')
            ->get();

        return $vendor;
    }



}
