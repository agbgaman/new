<?php

namespace App\Http\Controllers\User\STT;

use App\Http\Controllers\Admin\LicenseController;
use App\Http\Controllers\Controller;
use App\Jobs\AWSStorageAudioJob;
use App\Jobs\TranscribeAudioJob;
use App\Models\Folder;
use App\Models\Image;
use App\Models\Project;
use App\Models\TextModel;
use App\Models\TranscribeLanguage;
use App\Models\TranscribeResult;
use App\Models\User;
use App\Services\AWSSTTService;
use App\Services\GCPSTTService;
use Aws\Exception\AwsException;
use Aws\S3\S3Client;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use DataTables;
use DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Log;


class TranscribeStudioController extends Controller
{
    private $api;
    private $aws;
    private $gcp;


    public function __construct()
    {
        $this->api = new LicenseController();
        $this->aws = new AWSSTTService();
        $this->gcp = new GCPSTTService();
    }

    public function assignImagesFolder(Request $request, $id)
    {
        # Today's TTS Results for Datatable
        if ($request->ajax()) {
            $data = Folder::withCount('images', 'text')->where('project_id', $request->project_id)->where('assign_user_id', auth()->user()->id)->having('images_count', '>', 0)->latest()->get();

            return Datatables::of($data)
                ->addColumn('name', function ($row) {
                    $name = '<a href="' . route("user.transcribe.live-image", $row["id"]) . '"> <span class="font-weight-bold-' . $row["name"] . '"">' . ucfirst($row["name"]) . '</span></a>';
                    return $name;
                })
                ->addColumn('images_count', function ($row) {
                    $images_count = '<a href="' . route("user.transcribe.live-image", $row["id"]) . '"><span class="font-weight-bold-' . $row["images_count"] . '"">' . ucfirst($row["images_count"]) . '</span></a>';
                    return $images_count;
                })
                ->addColumn('remaining-count', function ($row) {
                    $remainingRecordings = DB::table('folders as f')
                        ->join('images as i', 'f.id', '=', 'i.folder_id')
                        ->leftJoin('transcribe_results as r', 'i.id', '=', 'r.image_id')
                        ->where('f.id', $row["id"])
                        ->where('f.assign_user_id', auth()->user()->id)
                        ->groupBy('f.id')
                        ->selectRaw('f.id, COUNT(DISTINCT i.id) - COUNT(DISTINCT r.id) as remaining_recordings_count')
                        ->first();

                    $remainingCount = '<span class="font-weight-bold-' . isset($remainingRecordings->remaining_recordings_count) . '"">' . ucfirst($remainingRecordings->remaining_recordings_count ?? 0) . '</span>';
                    return $remainingCount;
                })
                ->addColumn('language', function ($row) {
                    if ($row["language_id"]) {
                        $language = '<span>' . \Illuminate\Support\Facades\DB::table('transcribe_languages')->where('id', $row["language_id"])->first()->language . '</span>';
                        return $language;
                    } else {
                        return $row["language_id"];
                    }
                })
                ->addColumn('updated-on', function ($row) {
                    $timezone = auth()->user()->timezone; // Assuming the user's timezone is stored in the "timezone" column
                    $created_at = $row['updated_at']->setTimezone($timezone);
                    $created_on = '<span>' . date_format($created_at, 'd M Y H:i:s A') . '</span>';
                    return $created_on;
                })
                ->addColumn('Status', function ($row) {
                    $status = '<span class="cell-box user-' . $row["status"] . '">' . ucfirst($row["status"]) . '</span>';
                    return $status;
                })
                ->rawColumns(['actions', 'name', 'assign_user', 'status', 'assignUser', 'images_count', 'updated-on', 'remaining-count'])
                ->make(true);
        }
        return view('user.transcribe.assignFolder', compact('id'));
    }

    public function assignTextFolder(Request $request, $id)
    {
        # Today's TTS Results for Datatable
        if ($request->ajax()) {
            $data = Folder::withCount('images', 'text')
                ->where('project_id', $id)
                ->where('assign_user_id', auth()->user()->id)
                ->having('text_count', '>', 0)
                ->latest()->get();

            return Datatables::of($data)
                ->addColumn('name', function ($row) {
                    $name = '<a href="' . route("user.transcribe.live.Text-folder", $row["id"]) . '"> <span class="font-weight-bold-' . $row["name"] . '"">' . ucfirst($row["name"]) . '</span></a>';
                    return $name;
                })
                ->addColumn('text_count', function ($row) {
                    $textCount = TextModel::where('folder_id', $row["id"])->whereNull('type')->count();
                    $text_count = '<a href="' . route("user.transcribe.live.Text-folder", $row["id"]) . '"><span class="font-weight-bold-' . $textCount . '"">' . $textCount . '</span></a>';
                    return $text_count;
                })
                ->addColumn('remaining-count', function ($row) {
                    $remainingRecordings = DB::table('folders as f')
                        ->join('text_models as i', 'f.id', '=', 'i.folder_id')
                        ->leftJoin('transcribe_results as r', 'i.id', '=', 'r.image_id')
                        ->where('f.id', $row["id"])
                        ->where('f.assign_user_id', auth()->user()->id)
                        ->whereNull('i.type')
                        ->groupBy('f.id')
                        ->selectRaw('f.id, COUNT(DISTINCT i.id) - COUNT(DISTINCT r.id) as remaining_recordings_count')
                        ->first();

                    $remainingCount = '<span class="font-weight-bold-' . isset($remainingRecordings->remaining_recordings_count) . '"">' . ucfirst($remainingRecordings->remaining_recordings_count ?? 0) . '</span>';
                    return $remainingCount;
                })
                ->addColumn('updated-on', function ($row) {
                    $timezone = auth()->user()->timezone; // Assuming the user's timezone is stored in the "timezone" column
                    $created_at = $row['updated_at']->setTimezone($timezone);
                    $created_on = '<span>' . date_format($created_at, 'd M Y H:i:s A') . '</span>';
                    return $created_on;
                })
                ->addColumn('Status', function ($row) {
                    $status = '<span class="cell-box user-' . $row["status"] . '">' . ucfirst($row["status"]) . '</span>';
                    return $status;
                })
                ->rawColumns(['actions', 'name', 'assign_user', 'status', 'assignUser', 'text_count', 'updated-on', 'remaining-count'])
                ->make(true);
        }
        return view('user.transcribe.assignText', compact('id'));
    }

    public function assignTextToTextFolder(Request $request, $id)
    {
        # Today's TTS Results for Datatable
        if ($request->ajax()) {

            $data = Folder::whereHas('text', function ($query) {
                $query->where('type', 'text_translation');
            })->where('project_id', $id)
                ->where('assign_user_id', auth()->user()->id)
                ->latest()
                ->get();

            return Datatables::of($data)
                ->addColumn('name', function ($row) {
                    $name = '<a href="' . route("user.transcribe.live.Text-text-to-text", $row["id"]) . '"> <span class="font-weight-bold-' . $row["name"] . '"">' . ucfirst($row["name"]) . '</span></a>';
                    return $name;
                })
                ->addColumn('text_count', function ($row) {
                    $textCount = TextModel::where('folder_id', $row["id"])->where('type', 'text_translation')->count();
                    $text_count = '<a href="' . route("user.transcribe.live.Text-text-to-text", $row["id"]) . '"><span class="font-weight-bold-' . $textCount . ' bg-success text-white py-1 px-2  text-center rounded">' . $textCount . '</span></a>';
                    return $text_count;
                })
                ->addColumn('accepted_text', function ($row) {
                    $textCount = TextModel::where('folder_id', $row["id"])->where('type', 'text_translation')->where('status','complete')->count();
                    $text_count = '<a href="' . route("user.transcribe.live.Text-text-to-text", $row["id"]) . '"><span class="font-weight-bold-' . $textCount . ' bg-success text-white py-1 px-2 text-center rounded">' . $textCount . '</span></a>';
                    return $text_count;
                })
                ->addColumn('rejected_text', function ($row) {
                    $textCount = TextModel::where('folder_id', $row["id"])->where('type', 'text_translation')->where('status','failed')->count();
                    $text_count = '<a href="' . route("user.transcribe.live.Text-text-to-text", $row["id"]) . '"><span class="font-weight-bold-' . $textCount . ' bg-danger text-white py-1 px-2 text-center rounded">' . $textCount . '</span></a>';
                    return $text_count;
                })
                ->addColumn('pending_text', function ($row) {
                    $textCount = TextModel::where('folder_id', $row["id"])->where('type', 'text_translation')->where('status','IN_PROGRESS')->count();
                    $text_count = '<a href="' . route("user.transcribe.live.Text-text-to-text", $row["id"]) . '"><span class="font-weight-bold-' . $textCount . ' bg-warning text-white py-1 px-2 text-center rounded">' . $textCount . '</span></a>';
                    return $text_count;
                })
//                ->addColumn('accepted_rejected_text', function ($row) {
//                    $textCountAccepted = TextModel::where('folder_id', $row["id"])->where('type', 'text_translation')->where('status','complete')->count();
//                    $textCountFailed   = TextModel::where('folder_id', $row["id"])->where('type', 'text_translation')->where('status','failed')->count();
//                    $accepted_rejected_text = '<a href="' . route("user.transcribe.live.Text-text-to-text", $row["id"]) . '"><span class="font-weight-bold bg-warning text-white py-1 px-2 text-center rounded">' . $textCountAccepted / $textCountFailed . '</span></a>';
//                    return $accepted_rejected_text;
//                })
                ->addColumn('remaining-count', function ($row) {
                    $remainingRecordings = DB::table('folders as f')
                        ->join('text_models as i', 'f.id', '=', 'i.folder_id')
                        ->where('f.id', $row["id"])
                        ->where('f.assign_user_id', auth()->user()->id)
                        ->where('i.type', 'text_translation')
                        ->where('i.status', 'active') // Add this line to filter by 'active' status
                        ->groupBy('f.id')
                        ->selectRaw('f.id, COUNT(DISTINCT i.id) as remaining_recordings_count')
                        ->first();

                    $remainingCount = '<span class="font-weight-bold-' . isset($remainingRecordings->remaining_recordings_count) . '"">' . ucfirst($remainingRecordings->remaining_recordings_count ?? 0) . '</span>';
                    return $remainingCount;
                })
                ->addColumn('updated-on', function ($row) {
                    $timezone = auth()->user()->timezone; // Assuming the user's timezone is stored in the "timezone" column
                    $created_at = $row['updated_at']->setTimezone($timezone);
                    $created_on = '<span>' . date_format($created_at, 'd M Y H:i:s A') . '</span>';
                    return $created_on;
                })
                ->addColumn('Status', function ($row) {
                    $status = '<span class="cell-box user-' . $row["status"] . '">' . ucfirst($row["status"]) . '</span>';
                    return $status;
                })
                ->rawColumns(['actions', 'name', 'assign_user', 'status', 'assignUser', 'text_count', 'updated-on', 'remaining-count','accepted_text','rejected_text','pending_text'])
                ->make(true);
        }
        $project = Project::find($id);
        return view('user.transcribe.assignTextToText', compact('id','project'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function fileTranscribe(Request $request)
    {
        # Today's TTS Results for Datatable
        if ($request->ajax()) {
            $data = TranscribeResult::where('user_id', Auth::user()->id)->where('mode', 'file')->whereDate('created_at', Carbon::today())->latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('actions', function ($row) {
                    $actionBtn = '<div>
                                        <a id="' . $row["id"] . '" href="' . route('user.transcribe.show.file', $row['id']) . '" class="transcribeResult"><i class="fa fa-clipboard table-action-buttons view-action-button" title="View Transcript Result"></i></a>
                                        <a class="deleteResultButton" id="' . $row["id"] . '" href="#"><i class="fa-solid fa-trash-xmark table-action-buttons delete-action-button" title="Delete Result"></i></a>
                                    </div>';
                    return $actionBtn;
                })
                ->addColumn('created-on', function ($row) {
                    $created_on = '<span>' . date_format($row["created_at"], 'd M Y') . '</span>';
                    return $created_on;
                })
                ->addColumn('custom-status', function ($row) {
                    switch ($row['status']) {
                        case 'IN_PROGRESS':
                            $value = 'In Progress';
                            break;
                        case 'FAILED':
                            $value = 'Failed';
                            break;
                        case 'COMPLETED':
                            $value = 'Completed';
                            break;
                        default:
                            $value = '';
                            break;
                    }

                    $custom_voice = '<span class="cell-box transcribe-' . strtolower($row["status"]) . '">' . $value . '</span>';
                    return $custom_voice;
                })
                ->addColumn('custom-length', function ($row) {
                    $custom_voice = '<span>' . gmdate("H:i:s", $row['length']) . '</span>';
                    return $custom_voice;
                })
                ->addColumn('custom-language', function ($row) {
                    if (config('stt.vendor_logos') == 'show') {
                        $language = '<span class="vendor-image-sm overflow-hidden"><img alt="vendor" class="mr-2" src="' . URL::asset($row['language_flag']) . '">' . $row['language'] . '<img alt="vendor" class="rounded-circle ml-2" src="' . URL::asset($row['vendor_img']) . '"></span> ';
                    } else {
                        $language = '<span class="vendor-image-sm overflow-hidden"><img alt="vendor" class="mr-2" src="' . URL::asset($row['language_flag']) . '">' . $row['language'] . '</span> ';
                    }
                    return $language;
                })
                ->addColumn('download', function ($row) {
                    $result = '<a class="result-download" href="' . $row['file_url'] . '" download title="Download Audio"><i class="fa fa-cloud-download table-action-buttons download-action-button"></i></a>';
                    return $result;
                })
                ->addColumn('single', function ($row) {
                    $result = '<button type="button" class="result-play pl-0" title="Play Audio" onclick="resultPlay(this)" src="' . $row['file_url'] . '" type="' . $row['audio_type'] . '" id="' . $row['id'] . '"><i class="fa fa-play table-action-buttons view-action-button"></i></button>';
                    return $result;
                })
                ->addColumn('result', function ($row) {
                    $result = $row['file_url'];
                    return $result;
                })
                ->addColumn('type', function ($row) {
                    $result = ($row['speaker_identity'] == 'true') ? 'Speaker Identification' : 'Standard';
                    return $result;
                })
                ->rawColumns(['actions', 'created-on', 'custom-status', 'custom-length', 'custom-language', 'result', 'download', 'single', 'type'])
                ->make(true);

        }


        $languages = DB::table('transcribe_languages')
            ->join('vendors', 'transcribe_languages.vendor', '=', 'vendors.vendor_id')
            ->where('vendors.enabled', 1)
            ->where('transcribe_languages.status', 'active')
            ->orWhere(function ($query) {
                $query->where('transcribe_languages.type', 'file')
                    ->where('transcribe_languages.type', 'both');
            })
            ->select('transcribe_languages.id', 'transcribe_languages.language', 'transcribe_languages.language_code', 'transcribe_languages.language_flag', 'transcribe_languages.vendor_img')
            ->orderBy('transcribe_languages.language', 'asc')
            ->get();

        $projects = Project::where('user_id', auth()->user()->id)->orderBy('name', 'asc')->get();

        return view('user.transcribe.file', compact('languages', 'projects'));
    }


    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function recordTranscribe(Request $request)
    {

        # Today's TTS Results for Datatable
        if ($request->ajax()) {
            $data = TranscribeResult::where('user_id', Auth::user()->id)->where('mode', 'record')->whereDate('created_at', Carbon::today())->latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('actions', function ($row) {
                    $actionBtn = '<div>
                                        <a id="' . $row["id"] . '" href="' . route('user.transcribe.show.record', $row['id']) . '" class="transcribeResult"><i class="fa fa-clipboard table-action-buttons view-action-button" title="View Transcript Result"></i></a>
                                        <a class="deleteResultButton" id="' . $row["id"] . '" href="#"><i class="fa-solid fa-trash-xmark table-action-buttons delete-action-button" title="Delete Result"></i></a>
                                    </div>';
                    return $actionBtn;
                })
                ->addColumn('created-on', function ($row) {
                    $timezone = auth()->user()->timezone; // Assuming the user's timezone is stored in the "timezone" column
                    $created_at = $row['created_at']->setTimezone($timezone);
                    $created_on = '<span>' . date_format($created_at, 'd M Y H:i:s A') . '</span>';
                    return $created_on;
                })
                ->addColumn('custom-status', function ($row) {
                    switch ($row['status']) {
                        case 'IN_PROGRESS':
                            $value = 'In Progress';
                            break;
                        case 'FAILED':
                            $value = 'Failed';
                            break;
                        case 'COMPLETED':
                            $value = 'Completed';
                            break;
                        default:
                            $value = '';
                            break;
                    }
                    $custom_voice = '<span class="cell-box transcribe-' . strtolower($row["status"]) . '">' . $value . '</span>';
                    return $custom_voice;
                })
                ->addColumn('custom-length', function ($row) {
                    $custom_voice = '<span>' . gmdate("H:i:s", $row['length']) . '</span>';
                    return $custom_voice;
                })
                ->addColumn('custom-language', function ($row) {
                    if (config('stt.vendor_logos') == 'show') {
                        $language = '<span class="vendor-image-sm overflow-hidden"><img alt="vendor" class="mr-2" src="' . URL::asset($row['language_flag']) . '">' . $row['language'] . '<img alt="vendor" class="rounded-circle ml-2" src="' . URL::asset($row['vendor_img']) . '"></span> ';
                    } else {
                        $language = '<span class="vendor-image-sm overflow-hidden"><img alt="vendor" class="mr-2" src="' . URL::asset($row['language_flag']) . '">' . $row['language'] . '</span> ';
                    }
                    return $language;
                })
                ->addColumn('download', function ($row) {
                    $result = '<a class="result-download" href="' . $row['file_url'] . '" download title="Download Audio"><i class="fa fa-cloud-download table-action-buttons download-action-button"></i></a>';
                    return $result;
                })
                ->addColumn('single', function ($row) {
                    $result = '<button type="button" class="result-play pl-0" title="Play Audio" onclick="resultPlay(this)" src="' . $row['file_url'] . '" type="' . $row['audio_type'] . '" id="' . $row['id'] . '"><i class="fa fa-play table-action-buttons view-action-button"></i></button>';
                    return $result;
                })
                ->addColumn('result', function ($row) {
                    $result = $row['file_url'];
                    return $result;
                })
                ->addColumn('type', function ($row) {
                    $result = ($row['speaker_identity'] == 'true') ? 'Speaker Identification' : 'Standard';
                    return $result;
                })
                ->rawColumns(['actions', 'created-on', 'custom-status', 'custom-length', 'result', 'custom-language', 'download', 'single', 'type'])
                ->make(true);

        }

        # Set Voice Types as Listed in TTS Config
        $languages = DB::table('transcribe_languages')
            ->join('vendors', 'transcribe_languages.vendor', '=', 'vendors.vendor_id')
            ->where('vendors.enabled', 1)
            ->where('transcribe_languages.status', 'active')
            ->orWhere(function ($query) {
                $query->where('transcribe_languages.type', 'file')
                    ->where('transcribe_languages.type', 'both');
            })
            ->select('transcribe_languages.id', 'transcribe_languages.language', 'transcribe_languages.language_code', 'transcribe_languages.language_flag', 'transcribe_languages.vendor_img')
            ->orderBy('transcribe_languages.language', 'asc')
            ->get();

        $projects = Project::where('user_id', auth()->user()->id)->orderBy('name', 'asc')->get();

        return view('user.transcribe.record', compact('languages', 'projects'));
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function liveTranscribe(Request $request)
    {
        # Today's STT Results for Datatable
        if ($request->ajax()) {

            $data = TranscribeResult::where('user_id', Auth::user()->id)->where('mode', 'live')->whereNotNull('image_id')->latest()->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('actions', function ($row) {
                    $actionBtn = '<div>
                                        <a id="' . $row["id"] . '" href="#" class="transcribeResult"><i class="fa fa-clipboard table-action-buttons view-action-button" title="Transcript Result"></i></a>
                                        <a class="deleteResultButton" id="' . $row["id"] . '" href="#"><i class="fa-solid fa-trash-xmark table-action-buttons delete-action-button" title="Delete Result"></i></a>
                                    </div>';
                    return $actionBtn;
                })
                ->addColumn('created-on', function ($row) {
                    $created_on = '<span>' . date_format($row["created_at"], 'Y-m-d H:i:s') . '</span>';
                    return $created_on;
                })
                ->addColumn('image_id', function ($row) {
                    if ($row['image_id']) {
                        $image = Image::where('id', $row["image_id"])->first();
                        if ($image) {

                            $image_id = '<div class="d-flex">
                                        <div class="widget-user-image-sm overflow-hidden mr-4"><img alt="Avatar" src="' . Image::find($row["image_id"])->image . '"></div>
                                      </div>';
                            return $image_id;
                        } else {
                            return $row["image_id"];
                        }
                    } else {
                        return $row["image_id"];
                    }
                })
                ->addColumn('result', function ($row) {
                    $result = $row['file_url'];
                    return $result;
                })
                ->addColumn('image_code', function ($row) {
                    if ($row['image_id']) {
                        $image = Image::where('id', $row["image_id"])->first();
                        if ($image) {
                            $image_code = '<div class="d-flex">
                                        <div class="widget-user-name"><span class="font-weight-bold">' . Image::find($row["image_id"])->name . '</span></div>
                                      </div>';
                            return $image_code;
                        } else {
                            return $row["image_id"];
                        }

                    } else {
                        return $row["image_code"];
                    }


                })
                ->addColumn('custom-length', function ($row) {
                    $custom_voice = '<span>' . gmdate("H:i:s", $row['length']) . '</span>';
                    return $custom_voice;
                })
                ->addColumn('custom-language', function ($row) {
                    $language = '<span class="vendor-image-sm overflow-hidden"><img alt="vendor" class="mr-2" src="' . URL::asset($row['language_flag']) . '">' . $row['language'] . '</span> ';
                    return $language;
                })
                ->rawColumns(['actions', 'created-on', 'custom-length', 'custom-language', 'image_id', 'image_code', 'result'])
                ->make(true);

        }

        if (!isset($request['folder'])) {
            return back();
        }
        $folderId = $request['folder'];
        # Show Languages
        $languages = DB::table('transcribe_languages')
            ->join('vendors', 'transcribe_languages.vendor', '=', 'vendors.vendor_id')
            ->where('vendors.enabled', 1)
            ->where('transcribe_languages.status', 'active')
            ->where('type', 'both')
            ->select('transcribe_languages.id', 'transcribe_languages.language', 'transcribe_languages.language_code', 'transcribe_languages.language_flag', 'transcribe_languages.vendor_img')
            ->orderBy('id', 'asc')
            ->get();

        $projects = Project::where('user_id', auth()->user()->id)->orderBy('name', 'asc')->get();

        $totalImages = Image::where('folder_id', $request['folder'])->orderBy('id', 'asc')->get();
        $imageUrlsWithSerialNumbers = [];

        foreach ($totalImages as $index => $image) {

            $serialNumber = $index + 1;
            $imageUrl = $image->image . $image->id;

            $imageUrlsWithSerialNumbers[$serialNumber] = $imageUrl;
        }

        Cache::put('imageUrlsWithSerialNumbers' . auth()->user()->id, $imageUrlsWithSerialNumbers, now()->addDay());

        $images = Image::where('folder_id', $request['folder'])->orderBy('id', 'asc')
            ->doesntHave('transcribe')
            ->take(1)
            ->orderBy('id', 'asc')->get();

        $imageUrlsWithSerialNumbers = Cache::get('imageUrlsWithSerialNumbers' . auth()->user()->id);

        foreach ($images as $image) {
            $imageUrl = $image->image;
            $imageCount = array_search($imageUrl . $image->id, $imageUrlsWithSerialNumbers);
        }

        if ($images->isEmpty()) {
            $images = Image::where('folder_id', $request['folder'])->orderBy('id', 'asc')
                ->take(1)
                ->orderBy('id', 'asc')->get();
            $imageCount = 1;
        }
        // Get the total count of images for the user
        $totalCount = Image::where('folder_id', $request['folder'])->count();

        $folder = Folder::where('assign_user_id', auth()->user()->id)->first();

        return view('user.transcribe.live', compact('languages', 'projects', 'images', 'folder', 'totalCount', 'imageCount', 'folderId'));
    }

    public function liveTranscribeImage($id)
    {
        return redirect()->route('user.transcribe.live', ['folder' => $id]);
    }

    public function liveTranscribeTextFolder($id)
    {

        return redirect()->route('user.transcribe.live.Text', ['folder' => $id]);
    }

    public function liveTranscribeTextToTextFolder($id)
    {
        return redirect()->route('user.transcribe.live.TextToText', ['folder' => $id]);
    }

    /**
     * Handle next and previous slide
     *
     * @param $currentSlide
     * @param $direction
     * @return JsonResponse
     */
    public function getImages($currentSlide, $direction, $folderID)
    {
        $images = Image::where('id', $direction === 'next' ? '>' : '<', $currentSlide)
            ->where('folder_id', $folderID)
            ->with('transcribe')
            ->orderBy('id', $direction === 'next' ? 'asc' : 'desc')
            ->take(1)
            ->orderBy('id', 'asc')
            ->get();

        $indicatorsHtml = view('user.transcribe.imageSlider', compact('images'))->render();

        return response()->json(compact('images', 'indicatorsHtml'));
    }

    /**
     * Display a listing of the resource according to the text display in text.
     *
     * @return Response
     */
    public function liveTranscribeText(Request $request)
    {
        # Today's STT Results for Datatable
        if ($request->ajax()) {

            $data = TranscribeResult::select('transcribe_results.*')
                ->join('text_models', 'transcribe_results.text_model_id', '=', 'text_models.id')
                ->where('transcribe_results.user_id', Auth::user()->id)
                ->where('transcribe_results.mode', 'live')
                ->whereNotNull('transcribe_results.text_model_id')
                ->where('text_models.folder_id', $request->folderId)
                ->latest()
                ->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('actions', function ($row) {
                    $actionBtn = '<div>
                                        <a id="' . $row["id"] . '" href="#" class="transcribeResult"><i class="fa fa-clipboard table-action-buttons view-action-button" title="Transcript Result"></i></a>
                                        <a class="deleteResultButton" id="' . $row["id"] . '" href="#"><i class="fa-solid fa-trash-xmark table-action-buttons delete-action-button" title="Delete Result"></i></a>
                                    </div>';
                    return $actionBtn;
                })
                ->addColumn('created-on', function ($row) {
                    $timezone = auth()->user()->timezone; // Assuming the user's timezone is stored in the "timezone" column
                    $created_at = $row['created_at']->setTimezone($timezone);
                    $created_on = '<span>' . date_format($created_at, 'd M Y H:i:s A') . '</span>';
                    return $created_on;
                })
                ->addColumn('text_id', function ($row) {
                    $textUrlsWithSerialNumbers = Cache::get('textUrlsWithSerialNumbers' . auth()->user()->id);
                    $textCount = array_search($row["text_model_id"], $textUrlsWithSerialNumbers);

                    $text_id = '<span>' . $textCount . '</span>';
                    return $text_id;
                })
                ->addColumn('text_model', function ($row) {
                    if ($row['text_model_id']) {
                        $textModel = TextModel::where('id', $row["text_model_id"])->first();
                        if ($textModel) {
                            $text_model = '<span class="font-weight-bold-' . $row["text_model_id"] . '"">' . ucfirst(TextModel::find($row["text_model_id"])->text) . '</span>';
                            return $text_model;
                        } else {
                            return $row['text_model_id'];
                        }
                    } else {
                        return $row["assign_user_id"];
                    }
                })
                ->addColumn('result', function ($row) {
                    $result = $row['file_url'];
                    return $result;
                })
                ->addColumn('image_code', function ($row) {
                    if ($row['image_id']) {
                        $image_code = '<div class="d-flex">
                                        <div class="widget-user-name"><span class="font-weight-bold">' . Image::find($row["image_id"])->name . '</span></div>
                                      </div>';
                        return $image_code;
                    } else {
                        return $row["image_code"];
                    }
                })
                ->addColumn('custom-status', function ($row) {
                    switch ($row['status']) {
                        case 'active':
                            $value = 'Complete';
                            break;
                        case 'IN_PROGRESS':
                            $value = 'In Progress';
                            break;
                        case 'inactive':
                            $value = 'Failed';
                            break;
                        case 'In QC':
                            $value = 'In QC';
                            break;
                        case 'complete':
                            $value = 'Completed';
                            break;
                        default:
                            $value = '';
                            break;
                    }

                    $status = '<span class="cell-box transcribe-' . strtolower(str_replace(' ', '_', $value)) . '">' . $value . '</span>';
                    return $status;
                })
                ->addColumn('custom-length', function ($row) {
                    $custom_voice = '<span>' . gmdate("H:i:s", $row['length']) . '</span>';
                    return $custom_voice;
                })
                ->addColumn('custom-language', function ($row) {
                    $language = '<span class="vendor-image-sm overflow-hidden"><img alt="vendor" class="mr-2" src="' . URL::asset($row['language_flag']) . '">' . $row['language'] . '</span> ';
                    return $language;
                })
                ->rawColumns(['actions', 'created-on', 'custom-length', 'custom-language', 'text_model', 'image_code', 'result', 'text_id', 'custom-status'])
                ->make(true);

        }
        if (!isset($request['folder'])) {
            return back();
        }
        $folderId = $request['folder'];

        $totalText = TextModel::where('folder_id', $request['folder'])
            ->whereNull('type')
            ->orderBy('id', 'asc')->get();
        $textUrlsWithSerialNumbers = [];

        foreach ($totalText as $index => $text) {
            $serialNumber = $index + 1;
            $textUrl = $text->id;
            $textUrlsWithSerialNumbers[$serialNumber] = $textUrl;
        }


        Cache::put('textUrlsWithSerialNumbers' . auth()->user()->id, $textUrlsWithSerialNumbers, now()->addDay());

        // Get the total count of images for the user
        $totalCount = $totalText->count();

        $texts = TextModel::where('folder_id', $folderId)
            ->whereNull('type')
            ->doesntHave('transcribeText')
            ->take(1)
            ->orderBy('id', 'asc')
            ->get();

        $textUrlsWithSerialNumbers = Cache::get('textUrlsWithSerialNumbers' . auth()->user()->id);

        foreach ($texts as $text) {
            $textUrl = $text->id;
            $textCount = array_search($textUrl, $textUrlsWithSerialNumbers);
        }

        if ($texts->isEmpty()) {
            $texts = $totalText->take(1);
            $textCount = 1;
        }

        $folder = Folder::where('assign_user_id', auth()->user()->id)->first();

        return view('user.transcribe.liveText', compact('texts', 'folder', 'folderId', 'totalCount', 'textCount'));
    }

    /**
     * Display a listing of the resource according to the text display in text.
     *
     * @return Response
     */
    public function liveTranscribeTextToText(Request $request)
    {
        # Today's STT Results for Datatable
        if ($request->ajax()) {
            $data = TextModel::where('folder_id', $request->folder_id)
                ->where('status', '!=', 'active')
                ->where('type', 'text_translation')
                ->latest()
                ->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('created-on', function ($row) {
                    $created_on = '<span>' . \Carbon\Carbon::parse($row["created_at"])->format('d M Y h:i:s A') . '</span>';
                    return $created_on;
                })
                ->addColumn('text_id', function ($row) {
                    $textUrlsWithSerialNumbers = Cache::get('textUrlsWithSerialNumbers' . auth()->user()->id);
                    $textCount = array_search($row["id"], $textUrlsWithSerialNumbers);

                    $text_id = '<span>' . $textCount . '</span>';
                    return $text_id;
                })
                ->addColumn('text', function ($row) {
                    $text = '<span class="font-weight-bold-' . $row["text"] . '"">' . ucfirst($row["text"]) . '</span>';
                    return $text;
                })
                ->addColumn('translated_text', function ($row) {
                    $text = '<span class="font-weight-bold-' . $row["text"] . '"">' . ucfirst($row["translated_text"]) . '</span>';
                    return $text;
                })
                ->addColumn('comment', function ($row) {
                    $text = '<span class="font-weight-bold-' . $row["text"] . '"">' . ucfirst($row["comment"]) . '</span>';
                    return $text;
                })
                ->addColumn('name', function ($row) {
                    $text = '<span class="font-weight-bold-' . $row["name"] . '"">' . ucfirst($row["name"]) . '</span>';
                    return $text;
                })
                ->addColumn('status', function ($row) {
                    $status = '<span class="cell-box user-' . $row["status"] . '">' . ucfirst($row["status"]) . '</span>';
                    return $status;
                })
                ->rawColumns(['actions', 'created-on', 'text', 'translated_text', 'name', 'status', 'text_id','comment'])
                ->make(true);

        }
        if (!isset($request['folder'])) {
            return back();
        }
        $folderId = $request['folder'];

        $totalText = TextModel::where('folder_id', $request['folder'])->where('type', 'text_translation')->orderBy('id', 'asc')->get();
        $textUrlsWithSerialNumbers = [];

        foreach ($totalText as $index => $text) {

            $serialNumber = $index + 1;
            $textUrl = $text->id;

            $textUrlsWithSerialNumbers[$serialNumber] = $textUrl;
        }

        Cache::put('textUrlsWithSerialNumbers' . auth()->user()->id, $textUrlsWithSerialNumbers, now()->addDay());

        // Get the total count of images for the user
        $totalCount = TextModel::where('folder_id', $request['folder'])
            ->where('type', 'text_translation')
            ->count();

        $texts = TextModel::where('folder_id', $folderId)
            ->where('type', 'text_translation')
            ->where('status', 'active')
            ->take(1)
            ->orderBy('id', 'asc')->get();


        $textUrlsWithSerialNumbers = Cache::get('textUrlsWithSerialNumbers' . auth()->user()->id);
        if ($texts->isEmpty()) {
            $texts = $totalText->take(1);
            $textCount = 1;
        }
        foreach ($texts as $text) {
            $textUrl = $text->id;
            $textCount = array_search($textUrl, $textUrlsWithSerialNumbers);

        }

        $folder = Folder::where('assign_user_id', auth()->user()->id)->first();

        return view('user.transcribe.liveTextToText', compact('texts', 'folder', 'folderId', 'totalCount', 'textCount'));
    }

    /**
     * handle
     * @param $currentSlide
     * @param $direction
     * @return JsonResponse
     */
    public function getText($currentSlide, $direction, $folderID, $filter)
    {
        if ($filter == 'notComplete') {
            $texts = TextModel::where('id', $direction === 'next' ? '>' : '<', $currentSlide)
                ->where('folder_id', $folderID)
                ->doesntHave('transcribeText')
                ->orderBy('id', $direction === 'next' ? 'asc' : 'desc')
                ->take(1)
                ->get();
        } else {
            $texts = TextModel::where('id', $direction === 'next' ? '>' : '<', $currentSlide)
                ->where('folder_id', $folderID)
                ->with('transcribeText')
                ->orderBy('id', $direction === 'next' ? 'asc' : 'desc')
                ->take(1)
                ->get();
        }

        $indicatorsHtml = view('user.transcribe.textSlider', compact('texts'))->render();

        return response()->json(compact('texts', 'indicatorsHtml'));
    }
    /**
     * handle
     * @param $currentSlide
     * @param $direction
     * @return JsonResponse
     */
    public function getTextToText($currentSlide, $direction, $folderID, $filter)
    {
        if ($filter == 'notComplete') {
            $texts = TextModel::where('id', $direction === 'next' ? '>' : '<', $currentSlide)
                ->where('folder_id', $folderID)
                ->whereIn('status', ['active', 'failed'])
                ->orderBy('id', $direction === 'next' ? 'asc' : 'desc')
                ->take(1)
                ->get();
        } else {
            $texts = TextModel::where('id', $direction === 'next' ? '>' : '<', $currentSlide)
                ->where('folder_id', $folderID)
                ->with('transcribeText')
                ->orderBy('id', $direction === 'next' ? 'asc' : 'desc')
                ->take(1)
                ->get();
        }

        $indicatorsHtml = view('user.transcribe.textSlider', compact('texts'))->render();

        return response()->json(compact('texts', 'indicatorsHtml'));
    }

    /**
     * handle
     * @param $currentSlide
     * @param $direction
     * @return JsonResponse
     */
    public function getTextById($id, $folderId)
    {
        $textUrlsWithSerialNumbers = Cache::get('textUrlsWithSerialNumbers' . auth()->user()->id);
        if (isset($textUrlsWithSerialNumbers[$id])) {
            $textId = $textUrlsWithSerialNumbers[$id];
            $texts = TextModel::where('id', $textId)
                ->with('transcribeText')
                ->take(1)
                ->get();
        } else {
            $texts = null; // If the index number is not found, set the $textId to null
        }


        $indicatorsHtml = view('user.transcribe.textSlider', compact('texts'))->render();

        return response()->json(compact('texts', 'indicatorsHtml'));
    }

    public function saveTextToText(Request $request)
    {
        $text = TextModel::updateOrCreate(
            ['id' => $request->activeItemId],
            [
                'translated_text' => $request->translated_text,
                'status' => 'IN_PROGRESS',
            ],
        );

        return response()->json(['status' => 'success', 'message' => __('File text is successfully created')]);
    }


    /**
     * Process audio transcribe request.
     *
     * @param Request $request
     * @return Response
     */
    public function transcribe(Request $request)
    {

        if ($request->ajax()) {

            request()->validate([
                'audiofile' => 'required',
                'language' => 'required',
                'identify' => 'nullable',
                'speakers' => 'nullable',
            ]);

            if (request()->hasFile('audiofile')) {

                $file = request()->file('audiofile');
                $extension = (request('extension')) ? 'wav' : $file->extension();
                $original_name = (request('extension')) ? 'Recording' : $file->getClientOriginalName();
                $size = $file->getSize();

                if (!request('extension')) {
                    if ($size > (config('stt.max_size_limit') * 1048576)) {
                        return response()->json(["error" => __('File is too large, maximum allowed file size is: ') . config('stt.max_size_limit') . 'MB'], 422);
                    }

                    if (auth()->user()->group == 'user') {
                        if ((request('audiolength') / 60) > config('stt.max_length_limit_file_none')) {
                            return response()->json(["error" => __('Audio length is too long, maximum allowed audio file length is: ') . config('stt.max_length_limit_file') . __(' minutes')], 422);
                        }
                    } else {
                        if ((request('audiolength') / 60) > config('stt.max_length_limit_file')) {
                            return response()->json(["error" => __('Audio length is too long, maximum allowed audio file length is: ') . config('stt.max_length_limit_file') . __(' minutes')], 422);
                        }
                    }


                } else {
                    if (auth()->user()->group == 'user') {
                        if ((request('audiolength') / 60) > config('stt.max_length_limit_file_none')) {
                            return response()->json(["error" => __('Audio length is too long, maximum allowed audio file length is: ') . config('stt.max_length_limit_file') . __(' minutes')], 422);
                        }
                    } else {
                        if ((request('audiolength') / 60) > config('stt.max_length_limit_file')) {
                            return response()->json(["error" => __('Audio length is too long, maximum allowed audio file length is: ') . config('stt.max_length_limit_file') . __(' minutes')], 422);
                        }
                    }
                }

            }

            $language = TranscribeLanguage::where('id', request('language'))->first();
            $plan_type = (Auth::user()->group == 'subscriber') ? 'paid' : 'free';
            $job_name = strtoupper(Str::random(10));
            $mode = (request('extension')) ? 'record' : 'file';
            $file_size = $this->formatBytes($size);

            # GCP check if not mp3
            if ($language->vendor == 'gcp_audio' && $extension == 'mp3') {
                return response()->json(["error" => __("GCP languages do not support MP3 audio file format. Use FLAC or WAC formats")], 422);
            }

            # Count minutes based on vendor requirements
            $audio_length = (request('audiolength') / 60);
            $audio_length = number_format((float)$audio_length, 3, '.', '');

            # Check if user has minutes available to proceed
            if ((Auth::user()->available_minutes + Auth::user()->available_minutes_prepaid) < $audio_length) {
                return response()->json(["error" => __("Not enough available minutes to process. Subscribe or Top up to get more")], 422);
            } else {
                $this->updateAvailableMinutes($audio_length);
            }

            # Name and extention of the result audio file
            if ($extension === 'mp3') {
                $file_name = $job_name . '.mp3';
            } elseif ($extension === 'mp4') {
                $file_name = $job_name . '.mp4';
            } elseif ($extension === 'ogg') {
                $file_name = $job_name . '.ogg';
            } elseif ($extension === 'flac') {
                $file_name = $job_name . '.flac';
            } elseif ($extension === 'webm') {
                $file_name = $job_name . '.webm';
            } elseif ($extension === 'wav') {
                $file_name = $job_name . '.wav';
            } else {
                return response()->json(["error" => __("Unsupported audio file extension was selected. Please try again")], 422);
            }

            # Audio Format
            if ($extension == 'mp3') {
                $audio_type = 'audio/mpeg';
            } elseif ($extension == 'mp4') {
                $audio_type = 'audio/mp4';
            } elseif ($extension == 'flac') {
                $audio_type = 'audio/flac';
            } elseif ($extension == 'ogg') {
                $audio_type = 'audio/ogg';
            } elseif ($extension == 'wav') {
                $audio_type = 'audio/wav';
            } elseif ($extension == 'webm') {
                $audio_type = 'audio/webm';
            }

            Log::info($file);
            Log::info(file_get_contents($file));
            if ($language->vendor === 'aws_audio') {
                Storage::disk('s3')->put('aws/' . $file_name, file_get_contents($file));
                $file_url = Storage::disk('s3')->url('aws/' . $file_name);
            } elseif ($language->vendor == 'gcp_audio') {
                $file_url = $file;
                Log::info($file);
                Log::info($file_url);
            }


            $response = $this->processAudio($language, $job_name, $extension, request('audiolength'), $audio_type, request('taskType'), $file_url, $file, request('identify'), request('speakers'),);


            if ($language->vendor == 'aws_audio') {
                if ($response != 'success') {
                    return response()->json(["error" => __("Transcribe Task was not created properly. Please try again")], 422);
                }
            } elseif ($language->vendor == 'gcp_audio') {
                if ($response['status'] != 'success') {
                    return response()->json(["error" => __("Transcribe Task was not created properly. ") . $response['message']], 422);
                }
            }


            if ($language->vendor == 'aws_audio') {
                $result = new TranscribeResult([
                    'user_id' => Auth::user()->id,
                    'language' => $language->language,
                    'language_flag' => $language->language_flag,
                    'file_url' => $file_url,
                    'file_size' => $file_size,
                    'file_name' => $original_name,
                    'format' => $extension,
                    'storage' => $language->vendor,
                    'task_id' => $job_name,
                    'vendor_img' => $language->vendor_img,
                    'vendor' => $language->vendor,
                    'length' => request('audiolength'),
                    'plan_type' => $plan_type,
                    'audio_type' => $audio_type,
                    'status' => 'IN_PROGRESS',
                    'mode' => $mode,
                    'project' => request('project'),
                    'speaker_identity' => request('identify')
                ]);

            } elseif ($language->vendor == 'gcp_audio') {

                $words = count(preg_split('/\s+/', strip_tags($response['transcript'])));
                $supported = [67, 78, 85, 88, 89, 95, 100, 109, 111, 129, 133, 154];

                if (in_array($language->id, $supported)) {
                    $showSpeakers = request('identify');
                } else {
                    $showSpeakers = 'false';
                }

                $raw = ($response['raw']) ? $response['raw'] : '';

                $result = new TranscribeResult([
                    'user_id' => Auth::user()->id,
                    'language' => $language->language,
                    'language_flag' => $language->language_flag,
                    'file_url' => $response['url'],
                    'file_size' => $file_size,
                    'file_name' => $original_name,
                    'format' => $extension,
                    'storage' => $language->vendor,
                    'task_id' => $job_name,
                    'vendor_img' => $language->vendor_img,
                    'vendor' => $language->vendor,
                    'length' => request('audiolength'),
                    'plan_type' => $plan_type,
                    'audio_type' => $audio_type,
                    'status' => $response['job_status'],
                    'text' => $response['transcript'],
                    'words' => $words,
                    'raw' => $raw,
                    'gcp_task' => $response['gcp_task'],
                    'mode' => $mode,
                    'project' => request('project'),
                    'speaker_identity' => $showSpeakers,
                ]);
            }

            $result->save();

            return response()->json(["success" => __("Transcribe task was submitted successfully")], 200);

        }
    }


    /**
     * Process live transcribe request.
     *
     * @param Request $request
     * @return Response
     */
    public function transcribeLive(Request $request)
    {

        if (!$request->hasFile('audiofile')) {
            return response()->json(["error" => __("No audio file was selected. Please try again")], 422);
        }

        $file = $request->file('audiofile');
        $extension = ($request->input('extension')) ? 'wav' : $file->extension();
        $image = Image::where('id', $request->input('imageId'))->first();
        $job_name = ($image) ? pathinfo(basename($image->image), PATHINFO_FILENAME) : strtoupper(Str::random(10));


        $file_name = $job_name . '.' . $extension;

        # Audio Format
        $audio_types = [
            'mp3' => 'audio/mpeg',
            'mp4' => 'audio/mp4',
            'flac' => 'audio/flac',
            'ogg' => 'audio/ogg',
            'wav' => 'audio/wav',
            'webm' => 'audio/webm'
        ];
        $audio_type = $audio_types[$extension];

        if ($request->ajax()) {
            # Save the audio file in a temporary directory
            $path = $request->file('audiofile')->store('temp');
            # Dispatch the job to upload and store the file in S3
            AWSStorageAudioJob::dispatch($path, $file_name);

            $file_url = Storage::disk('s3')->url('aws/' . $file_name);

            // Dispatch the job to transcribe the audio
            TranscribeAudioJob::dispatch(
                Auth::user()->id,
                'English (USA)',
                $file_url,
                '/img/flags/us.svg',
                $job_name,
                '/img/csp/aws-sm.png',
                'aws_audio',
                0,
                request('text'),
                request('audiolength'),
                $audio_type,
                'free',
                'IN_PROGRESS',
                'live',
                request('project'),
                request('imageId'),
                'image'
            );

            return response()->json(["success" => __("Success! Transcribe task was stored successfully")], 200);
        }

    }

    /**
     * Process live transcribe request.
     *
     * @param Request $request
     * @return Response
     */
    public function transcribeLiveText(Request $request)
    {

        if (request()->hasFile('audiofile')) {
            $file = request()->file('audiofile');
            $extension = ($request->input('extension')) ? 'wav' : $file->extension();
            $text = TextModel::where('id', $request->input('textId'))->first();
            $transcribeResult = TranscribeResult::where('text_model_id', $text->id)->first();


            if ($transcribeResult) {
                if ($transcribeResult->file_url) {
                    $s3Client = new S3Client([
                        'region' => env('AWS_DEFAULT_REGION'),
                        'version' => 'latest',
                    ]);
                    $url = $transcribeResult->file_url;
                    $parsedUrl = parse_url($url);
                    $key = ltrim($parsedUrl['path'], '/');
                    try {
                        $result = $s3Client->deleteObject([
                            'Bucket' => 'gtsdashbucket',
                            'Key' => $key,
                        ]);
                    } catch (AwsException $e) {
                        // Output error message if fails
                        echo $e->getMessage();
                        echo "\n";
                    }
                }
            }
            $job_name = ($text) ? $text->name . '-' . $text->id . strtoupper(Str::random(10)) : strtoupper(Str::random(10));
            $file_name = $job_name . '.' . $extension;

            # Audio Format
            $audio_types = [
                'mp3' => 'audio/mpeg',
                'mp4' => 'audio/mp4',
                'flac' => 'audio/flac',
                'ogg' => 'audio/ogg',
                'wav' => 'audio/wav',
                'webm' => 'audio/webm'
            ];
            $audio_type = $audio_types[$extension];


            if ($request->ajax()) {
                # Save the audio file in a temporary directory
                $path = $request->file('audiofile')->store('temp');

                # Dispatch the job to upload and store the file in S3
                AWSStorageAudioJob::dispatch($path, $file_name);

                $file_url = Storage::disk('s3')->url('aws/' . $file_name);

                // Dispatch the job to transcribe the audio
                TranscribeAudioJob::dispatch(
                    Auth::user()->id,
                    'English (USA)',
                    $file_url,
                    '/img/flags/us.svg',
                    $job_name,
                    '/img/csp/aws-sm.png',
                    'aws_audio',
                    0,
                    request('text'),
                    request('audiolength'),
                    $audio_type,
                    'free',
                    'IN_PROGRESS',
                    'live',
                    request('project'),
                    request('textId'),
                    'text'
                );


                return response()->json(["success" => __("Success! Transcribe task was stored successfully")], 200);

            }
        }
    }

    public function convertToMP3($audioFile)
    {
        // Save the audio data to a temporary file
        $tmpFile = tempnam(sys_get_temp_dir(), 'audio');
        file_put_contents($tmpFile, file_get_contents($audioFile));

        // Convert the audio file to MP3 format using FFmpeg
        $mp3File = tempnam(sys_get_temp_dir(), 'audio') . '.mp3';
        exec("ffmpeg -i $tmpFile -codec:a libmp3lame -qscale:a 2 $mp3File");

        // Load the converted MP3 file as a Laravel UploadedFile object
        $mp3UploadedFile = new UploadedFile($mp3File, 'audio.mp3', 'audio/mp3', null, true);

        return $mp3UploadedFile;
    }

    public function liveTranscribeEdit($id)
    {

        $transcribe = TranscribeResult::where('id', $id)->where('mode', 'live')->latest()->first();

        # Show Languages
        $languages = DB::table('transcribe_languages')
            ->join('vendors', 'transcribe_languages.vendor', '=', 'vendors.vendor_id')
            ->where('vendors.enabled', 1)
            ->where('transcribe_languages.status', 'active')
            ->where('type', 'both')
            ->select('transcribe_languages.id', 'transcribe_languages.language', 'transcribe_languages.language_code', 'transcribe_languages.language_flag', 'transcribe_languages.vendor_img')
            ->orderBy('id', 'asc')
            ->get();

        $projects = Project::where('user_id', auth()->user()->id)->orderBy('name', 'asc')->get();


        $images = Image::where('id', $transcribe->image_id)->get();

        $folder = Folder::where('assign_user_id', auth()->user()->id)->first();
        return view('user.transcribe.liveEdit', compact('languages', 'projects', 'images', 'folder', 'transcribe'));
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function showFile(TranscribeResult $id)
    {
        if ($id->user_id == auth()->user()->id) {

            $end_time = gmdate("H:i:s", $id->length);

            $data['type'] = json_encode($id->speaker_identity);
            $data['raw'] = json_encode($id->raw);
            $data['text'] = json_encode($id->text);
            $data['url'] = json_encode($id->file_url);
            $data['vendor'] = json_encode($id->vendor);
            $data['end_time'] = json_encode($end_time);

            $task_type = ($id->speaker_identity == 'true') ? 'Speaker Identification' : 'Standard';

            return view('user.transcribe.show-file', compact('id', 'data', 'task_type'));
        }

        return redirect()->route('user.transcribe.file');
    }


    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function showRecord(TranscribeResult $id)
    {
        if ($id->user_id == auth()->user()->id) {

            $end_time = gmdate("H:i:s", $id->length);

            $data['type'] = json_encode($id->speaker_identity);
            $data['raw'] = json_encode($id->raw);
            $data['text'] = json_encode($id->text);
            $data['url'] = json_encode($id->file_url);
            $data['vendor'] = json_encode($id->vendor);
            $data['end_time'] = json_encode($end_time);

            $task_type = ($id->speaker_identity == 'true') ? 'Speaker Identification' : 'Standard';

            return view('user.transcribe.show-record', compact('id', 'data', 'task_type'));
        }

        return redirect()->route('user.transcribe.record');
    }


    /**
     * Update user minutes
     */
    private function updateAvailableMinutes($minutes)
    {
        $user = User::find(Auth::user()->id);

        if (Auth::user()->available_minutes > $minutes) {

            $total_minutes = Auth::user()->available_minutes - $minutes;
            $user->available_minutes = $total_minutes;

        } elseif (Auth::user()->available_minutes_prepaid > $minutes) {

            $total_minutes_prepaid = Auth::user()->available_minutes_prepaid - $minutes;
            $user->available_minutes_prepaid = $total_minutes_prepaid;

        } elseif ((Auth::user()->available_minutes + Auth::user()->available_minutes_prepaid) == $minutes) {

            $user->available_minutes = 0;
            $user->available_minutes_prepaid = 0;

        } else {

            $remaining = $minutes - Auth::user()->available_minutes;
            $user->available_minutes = 0;

            $user->available_minutes_prepaid = Auth::user()->available_minutes_prepaid - $remaining;

        }

        $user->update();
    }


    /**
     * Process audio files based on the vendor language selected
     */
    private function processAudio(TranscribeLanguage $language, $job_name, $extension, $duration, $audio_type, $task_type, $file_url = null, $file = null, $identify = null, $speakers = null)
    {
        switch ($language->vendor) {
            case 'aws_audio':
                return $this->aws->startTask($language, $job_name, $identify, $speakers, $extension, $file_url);
                break;
            case 'gcp_audio':
                return $this->gcp->startTask($language, $job_name, $extension, $file, $duration, $audio_type, $task_type, $identify, $speakers);
                break;
        }
    }


    public function settings(Request $request)
    {
        $formats = explode(',', config('stt.file_format'));
        $string = [];
        $list = '';

        foreach ($formats as $format) {
            $value = trim($format);
            switch ($value) {
                case 'mp3':
                    array_push($string, "audio/mpeg");
                    $list .= ' MP3,';
                    break;
                case 'mp4':
                    array_push($string, "audio/mp4");
                    $list .= ' MP4,';
                    break;
                case 'flac':
                    array_push($string, "audio/flac");
                    $list .= ' FLAC,';
                    break;
                case 'ogg':
                    array_push($string, "audio/ogg");
                    $list .= ' Ogg,';
                    break;
                case 'wav':
                    array_push($string, "audio/wav");
                    $list .= ' WAV';
                    break;
                case 'webm':
                    array_push($string, "audio/webm");
                    $list .= ' WebM,';
                    break;
                default:
                    break;
            }
        }

        if ($request->ajax()) {
            $data['size'] = config('stt.max_size_limit');
            $data['length_file'] = (auth()->user()->group == 'user') ? config('stt.max_length_limit_file_none') : config('stt.max_length_limit_file');
            $data['length_live'] = (auth()->user()->group == 'user') ? config('stt.max_length_limit_live_none') : config('stt.max_length_limit_live');
            $data['type'] = $string;
            $data['type_show'] = $list;
            return $data;
        }
    }


    public function settingsLive(Request $request)
    {
        if ($request->ajax()) {
            $data['region'] = config('services.aws.region');
            $data['ak'] = config('services.aws.key');
            $data['sak'] = config('services.aws.secret');
            $data['limit'] = auth()->user()->available_minutes;

            return $data;
        }
    }


    public function settingsLiveLimits(Request $request)
    {
        if ($request->ajax()) {
            $data['limits'] = auth()->user()->available_minutes;

            return $data;
        }
    }


    public function formatBytes($bytes, $precision = 2)
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= pow(1024, $pow);

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
