<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Statistics\VoiceoverService;
use App\Services\Statistics\TranscribeService;

class StudioDashboardController extends Controller
{
    /**
     * Display Stuiod Dashboard
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $year = $request->input('year', date('Y'));
        $month = $request->input('month', date('m'));

        $tts = new VoiceoverService($year, $month);
        $stt = new TranscribeService($year, $month);

        $tts_data_yearly = [
            'total_free_chars' => $tts->getTotalFreeCharsUsageYearly(),
            'total_paid_chars' => $tts->getTotalPaidCharsUsageYearly(),
            'total_standard_chars' => $tts->getTotalStandardCharsUsageYearly(),
            'total_neural_chars' => $tts->getTotalNeuralCharsUsageYearly(),
            'total_audio_files' => $tts->getTotalAudioFilesYearly(),
            'total_listen_results' => $tts->getTotalListenResultsYearly(),
            'total_free_minutes' => $stt->getTotalFreeMinutesUsageYearly(),
            'total_paid_minutes' => $stt->getTotalPaidMinutesUsageYearly(),
            'total_words' => $stt->getTotalWordsYearly(),
            'total_file_transcribe' => $stt->getTotalFileTranscribeYearly(),
            'total_recording_transcribe' => $stt->getTotalRecordingTranscribeYearly(),
            'total_live_transcribe' => $stt->getTotalLiveTranscribeYearly(),
        ];

        $tts_data_monthly = [
            'free_chars' => $tts->getTotalFreeCharsUsageMonthly(),
            'paid_chars' => $tts->getTotalPaidCharsUsageMonthly(),
            'standard_chars' => $tts->getTotalStandardCharsUsageMonthly(),
            'neural_chars' => $tts->getTotalNeuralCharsUsageMonthly(),
            'free_minutes' => $stt->getTotalFreeMinutesUsageMonthly(),
            'paid_minutes' => $stt->getTotalPaidMinutesUsageMonthly(),
        ];

        $vendor_data = [
            'aws_month' => $tts->getAWSUsageMonthly(),
            'aws_year' => $tts->getAWSUsageYearly(),
            'azure_month' => $tts->getAzureUsageMonthly(),
            'azure_year' => $tts->getAzureUsageYearly(),
            'gcp_month' => $tts->getGCPUsageMonthly(),
            'gcp_year' => $tts->getGCPUsageYearly(),
            'ibm_month' => $tts->getIBMUsageMonthly(),
            'ibm_year' => $tts->getIBMUsageYearly(),
            'aws_month_transcribe' => $stt->getAWSUsageMonthly(),
            'aws_year_transcribe' => $stt->getAWSUsageYearly(),
            'gcp_month_transcribe' => $stt->getGCPUsageMonthly(),
            'gcp_year_transcribe' => $stt->getGCPUsageYearly(),
        ];
        
        $chart_data['free_chars'] = json_encode($tts->getFreeCharsUsageYearly());
        $chart_data['paid_chars'] = json_encode($tts->getPaidCharsUsageYearly());
        $chart_data['free_minutes'] = json_encode($stt->getFreeMinutesUsageYearly());
        $chart_data['paid_minutes'] = json_encode($stt->getPaidMinutesUsageYearly());
        $chart_data['file_minutes'] = json_encode($stt->getFileMinutesUsage());
        $chart_data['record_minutes'] = json_encode($stt->getRecordMinutesUsage());
        $chart_data['live_minutes'] = json_encode($stt->getLiveMinutesUsage());

        $percentage['aws_year'] = json_encode($tts->getAWSUsageYearly());
        $percentage['azure_year'] = json_encode($tts->getAzureUsageYearly());
        $percentage['gcp_year'] = json_encode($tts->getGCPUsageYearly());
        $percentage['ibm_year'] = json_encode($tts->getIBMUsageYearly());
        $percentage['free_current'] = json_encode($tts->getTotalFreeCharsUsageMonthly());
        $percentage['free_past'] = json_encode($tts->getTotalFreeCharsUsagePastMonth());
        $percentage['paid_current'] = json_encode($tts->getTotalPaidCharsUsageMonthly());
        $percentage['paid_past'] = json_encode($tts->getTotalPaidCharsUsagePastMonth());
        $percentage['standard_current'] = json_encode($tts->getTotalStandardCharsUsageMonthly());
        $percentage['standard_past'] = json_encode($tts->getTotalStandardCharsUsagePastMonth());
        $percentage['neural_current'] = json_encode($tts->getTotalNeuralCharsUsageMonthly());
        $percentage['neural_past'] = json_encode($tts->getTotalNeuralCharsUsagePastMonth());
        $percentage['aws_year_transcribe'] = json_encode($stt->getAWSUsageYearly());
        $percentage['gcp_year_transcribe'] = json_encode($stt->getGCPUsageYearly());
        $percentage['free_current_transcribe'] = json_encode($stt->getTotalFreeMinutesUsageMonthly());
        $percentage['free_past_transcribe'] = json_encode($stt->getTotalFreeMinutesUsagePastMonth());
        $percentage['paid_current_transcribe'] = json_encode($stt->getTotalPaidMinutesUsageMonthly());
        $percentage['paid_past_transcribe'] = json_encode($stt->getTotalPaidMinutesUsagePastMonth());

        return view('admin.studio.dashboard.index', compact('chart_data', 'percentage', 'tts_data_yearly', 'tts_data_monthly', 'vendor_data'));
    }

}
