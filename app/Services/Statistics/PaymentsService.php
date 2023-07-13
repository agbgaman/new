<?php

namespace App\Services\Statistics;

use App\Http\Controllers\User\STT\TranscribeStudioController;
use App\Models\Folder;
use App\Models\Image;
use App\Models\Payment;
use App\Models\Project;
use App\Models\ProjectRemark;
use App\Models\TextModel;
use App\Models\TranscribeResult;
use App\Models\User;
use DB;
use Illuminate\Support\Facades\Auth;

class PaymentsService
{
    private $year;
    private $month;

    public function __construct(int $year, int $month)
    {
        $this->year = $year;
        $this->month = $month;
    }


    public function getPayments()
    {
        $payments = Payment::select(DB::raw("sum(price) as data"), DB::raw("MONTH(created_at) month"))
                ->whereYear('created_at', $this->year)
                ->where('status', 'completed')
                ->groupBy('month')
                ->orderBy('month')
                ->get()->toArray();

        $data = [];

        for($i = 1; $i <= 12; $i++) {
            $data[$i] = 0;
        }

        foreach ($payments as $row) {
            $month = $row['month'];
            $data[$month] = intval($row['data']);
        }

        return $data;
    }


    public function getTotalPaymentsCurrentYear()
    {
        $payments = Payment::select(DB::raw("sum(price) as data"))
                ->whereYear('created_at', $this->year)
                ->where('status', 'completed')
                ->get();

        return $payments;
    }


    public function getTotalPaymentsCurrentMonth()
    {
        $payments = Payment::select(DB::raw("sum(price) as data"))
                ->whereMonth('created_at', $this->month)
                ->whereYear('created_at', $this->year)
                ->where('status', 'completed')
                ->get();

        return $payments;
    }


    public function getTotalPaymentsPastMonth()
    {
        $date = \Carbon\Carbon::now();
        $pastMonth =  $date->subMonth()->format('m');

        $payments = Payment::select(DB::raw("sum(price) as data"))
                ->whereMonth('created_at', $pastMonth)
                ->whereYear('created_at', $this->year)
                ->where('status', 'completed')
                ->get();

        return $payments;
    }

    # Voiceover character purchases

    public function getTotalPurchasedCharactersCurrentYear()
    {
        $characters = Payment::select(DB::raw("sum(characters) as data"))
                ->whereYear('created_at', $this->year)
                ->where('status', 'completed')
                ->get();

        return $characters;
    }


    public function getTotalPurchasedCharactersCurrentMonth()
    {
        $characters = Payment::select(DB::raw("sum(characters) as data"))
                ->whereMonth('created_at', $this->month)
                ->whereYear('created_at', $this->year)
                ->where('status', 'completed')
                ->get();

        return $characters;
    }


    public function getTotalPurchasedCharactersPastMonth()
    {
        $date = \Carbon\Carbon::now();
        $pastMonth =  $date->subMonth()->format('m');

        $characters = Payment::select(DB::raw("sum(characters) as data"))
                ->whereMonth('created_at', $pastMonth)
                ->whereYear('created_at', $this->year)
                ->where('status', 'completed')
                ->get();

        return $characters;
    }

    # Transcribe minutes purchases

    public function getTotalPurchasedMinutesCurrentYear()
    {
        $minutes = Payment::select(DB::raw("sum(minutes) as data"))
                ->whereYear('created_at', $this->year)
                ->where('status', 'completed')
                ->get();

        return $minutes;
    }


    public function getTotalPurchasedMinutesCurrentMonth()
    {
        $minutes = Payment::select(DB::raw("sum(minutes) as data"))
                ->whereMonth('created_at', $this->month)
                ->whereYear('created_at', $this->year)
                ->where('status', 'completed')
                ->get();

        return $minutes;
    }


    public function getTotalPurchasedMinutesPastMonth()
    {
        $date = \Carbon\Carbon::now();
        $pastMonth =  $date->subMonth()->format('m');

        $minutes = Payment::select(DB::raw("sum(minutes) as data"))
                ->whereMonth('created_at', $pastMonth)
                ->whereYear('created_at', $this->year)
                ->where('status', 'completed')
                ->get();

        return $minutes;
    }
    public function getRejectionReason($projectId)
    {
        $project = Project::where('id', $projectId)->first();
        $folders = Folder::where('project_id', $project->id)->pluck('id');
        $allRemarks = [];
        if ($project->type == 'image') {

                $texts = Image::whereNotNull('remark_id')->get();
                foreach ($texts as $text) {
                    if (!is_null($text->remark_id)) {
                        $allRemarks[] = $text->remark_id;
                    }
                }

        } elseif ($project->type == 'text_to_speech'){
            $data = TranscribeResult::select('transcribe_results.*')
                ->join('text_models', 'transcribe_results.text_model_id', '=', 'text_models.id')
                ->whereNotNull('transcribe_results.remark_id')
                ->whereNotNull('transcribe_results.text_model_id')
                ->whereIn('text_models.folder_id', $folders)
                ->latest()
                ->get();
            foreach ($data as $text) {
                if (!is_null($text->remark_id)) {
                    $allRemarks[] = $text->remark_id;
                }
            }
        } elseif ($project->type == 'text_to_text'){
            $texts = TextModel::whereIn('folder_id', $folders)->whereNotNull('remark_id')->get();
                foreach ($texts as $text) {
                    if (!is_null($text->remark_id)) {
                        $allRemarks[] = $text->remark_id;
                    }
                }
        } elseif ($project->type == 'image_to_speech'){
            $data = TranscribeResult::select('transcribe_results.*')
                ->join('images', 'transcribe_results.image_id', '=', 'images.id')
                ->whereNotNull('transcribe_results.remark_id')
                ->whereNotNull('transcribe_results.image_id')
                ->whereIn('images.folder_id', $folders)
                ->latest()
                ->get();
            foreach ($data as $image) {
                if (!is_null($image->remark_id)) {
                    $allRemarks[] = $image->remark_id;
                }
            }
        }

        $remarkCounts = array_count_values($allRemarks);
        arsort($remarkCounts); // sort in descending order
        $top10Remarks = array_slice($remarkCounts, 0, 10, true); // get top 10

        $totalRemarks = array_sum($top10Remarks);
        $remarkPercentages = [];
        foreach ($top10Remarks as $remarkId => $count) {
            $remarkPercentages[$remarkId] = ($count / $totalRemarks) * 100;
        }

        // get the names of the remarks from ProjectRemark model
        $remarkIds = array_keys($remarkPercentages);
        $remarkNames = ProjectRemark::whereIn('id', $remarkIds)->pluck('remark', 'id');

        // replace the remark id with the corresponding name in the remarkPercentages array
        foreach ($remarkPercentages as $remarkId => $percentage) {
            if (isset($remarkNames[$remarkId])) {
                $remarkPercentages[$remarkNames[$remarkId]] = $percentage;
                unset($remarkPercentages[$remarkId]);
            }
        }


        return $remarkPercentages;
    }
    public function getPerformerRejectionReason($projectId): array
    {
        $project = Project::where('id', $projectId)->first();
        $folders = Folder::where('project_id', $project->id)->pluck('id');

        $topUsers = [];
        if ($project->type == 'image') {

            $topUsers = User::whereHas('roles', function ($query) {
                $query->where('name', 'user');
            })->whereHas('images', function($query) {
                $query->where('status', 'active');
            })
                ->select('users.*')
                ->withCount(['roles', 'images' => function($query) {
                    $query->where('status', 'active');
                }])
                ->orderBy('images_count', 'desc')
                ->take(10)
                ->pluck('name', 'images_count');

        } elseif ($project->type == 'text_to_speech'){
            $topUsers = User::whereHas('transcribeResults.csv_text', function ($query) use ($folders) {
                $query->whereIn('folder_id', $folders);
            })
                ->withCount(['transcribeResults' => function ($query) use ($folders) {
                    $query->whereNotNull('text_model_id')
                        ->whereHas('csv_text', function ($query) use ($folders) {
                            $query->whereIn('folder_id', $folders);
                        });
                }])
                ->orderBy('transcribe_results_count', 'desc')
                ->take(10)
                ->pluck('name', 'transcribe_results_count');

        } elseif ($project->type == 'text_to_text'){

            $topUsers = Folder::where('project_id', $project->id)
                ->join('text_models', 'folders.id', '=', 'text_models.folder_id')
                ->join('users', 'folders.assign_user_id', '=', 'users.id')
                ->select('users.name', 'assign_user_id', DB::raw('COUNT(text_models.id) as text_count'))
                ->where('text_models.status', 'complete')
                ->groupBy('assign_user_id', 'users.name')
                ->orderBy('text_count', 'desc')
                ->take(10)
                ->pluck('name','text_count');


        } elseif ($project->type == 'image_to_speech'){
            $topUsers = User::whereHas('transcribeResults.csv_text', function ($query) use ($folders) {
                $query->whereIn('folder_id', $folders);
            })
                ->withCount(['transcribeResults' => function ($query) use ($folders) {
                    $query->whereNotNull('image_id')
                        ->whereHas('translatedText', function ($query) use ($folders) {
                            $query->whereIn('folder_id', $folders);
                        });
                }])
                ->orderBy('transcribe_results_count', 'desc')
                ->take(10)
                ->pluck('name', 'transcribe_results_count');
        }

        return $topUsers->toArray();
    }
    public function getQAPerformance($projectId){
        $project = Project::where('id', 17)->first();
        $folders = Folder::where('project_id', $project->id)->pluck('id');

        $topUsers = [];
        if ($project->type == 'image') {

            $topQualityAssurances = Folder::where('project_id', $project->id)
                ->join('images', 'folders.id', '=', 'images.folder_id')
                ->join('users', 'folders.quality_assurance_id', '=', 'users.id')
                ->select('users.name', 'quality_assurance_id', DB::raw('COUNT(images.id) as image_count'))
                ->where('images.status', 'active')
                ->groupBy('quality_assurance_id', 'users.name')
                ->orderBy('image_count', 'desc')
                ->take(10)
                ->pluck('name', 'image_count');



        } elseif ($project->type == 'text_to_speech'){

            $topQualityAssurances = User::whereHas('folders', function ($query) use ($folders) {
                $query->whereIn('id', $folders);
            })
                ->withCount(['transcribeResults' => function ($query) use ($folders) {
                    $query->whereNotNull('text_model_id')
                        ->whereHas('csv_text', function ($query) use ($folders) {
                            $query->whereIn('folder_id', $folders);
                        });
                }])
                ->orderBy('transcribe_results_count', 'desc')
                ->take(10)
                ->pluck('name', 'transcribe_results_count');
dd($topQualityAssurances);

        } elseif ($project->type == 'text_to_text'){

            $topUsers = Folder::where('project_id', $project->id)
                ->join('text_models', 'folders.id', '=', 'text_models.folder_id')
                ->join('users', 'folders.assign_user_id', '=', 'users.id')
                ->select('users.name', 'assign_user_id', DB::raw('COUNT(text_models.id) as text_count'))
                ->where('text_models.status', 'complete')
                ->groupBy('assign_user_id', 'users.name')
                ->orderBy('text_count', 'desc')
                ->take(10)
                ->pluck('name','text_count');


        } elseif ($project->type == 'image_to_speech'){
            $topUsers = User::whereHas('transcribeResults.csv_text', function ($query) use ($folders) {
                $query->whereIn('folder_id', $folders);
            })
                ->withCount(['transcribeResults' => function ($query) use ($folders) {
                    $query->whereNotNull('image_id')
                        ->whereHas('translatedText', function ($query) use ($folders) {
                            $query->whereIn('folder_id', $folders);
                        });
                }])
                ->orderBy('transcribe_results_count', 'desc')
                ->take(10)
                ->pluck('name', 'transcribe_results_count');
        }
    }

}
