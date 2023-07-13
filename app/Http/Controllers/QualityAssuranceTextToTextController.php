<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use App\Models\TextModel;
use App\Models\TranscribeResult;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Yajra\DataTables\DataTables;

class QualityAssuranceTextToTextController extends Controller
{
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $data = Folder::withCount(['text' => function ($query) {
                $query->where('type', '=', 'text_translation');
            }])
                ->whereHas('text', function ($query) {
                    $query->where('type', 'text_translation');
                })
                ->where('quality_assurance_id', auth()->user()->id)
                ->having('text_count', '>', 0)
                ->latest()
                ->get();

            return Datatables::of($data)
                ->addIndexColumn()
//                    ->addColumn('checkbox', function ($row) {
//                        return '<input type="checkbox" class="folder-checkbox" data-folder-id="' . $row['id'] . '">';
//                    })
                ->addColumn('actions', function ($row) {
                    if ($row['status'] == 'paid') {
                        $actionBtn = '<div>
                                          </div>';
                        return $actionBtn;
                    } else {
                        $actionBtn = '<div>
                                               <a class="agreeStatus" id="' . $row["id"] . '" href="#"><i class="fa fa-check table-action-buttons request-action-button" title="Approve Status"></i></a>
                                          </div>';
                        return $actionBtn;
                    }
                })
                ->addColumn('name', function ($row) {
                    $name = '<span class="font-weight-bold-' . $row["name"] . '"">' . ucfirst($row["name"]) . '</span>';
                    return $name;
                })
                ->addColumn('text_count', function ($row) {
                    $textCount = TextModel::where('folder_id', $row["id"])->whereNull('type')->count();
                    $text_count = '<a href="' . route("qa.text-to-text-folder-by-id", $row["id"]) . '"><span class="font-weight-bold-' . $textCount . '"">' . $textCount . '</span></a>';
                    return $text_count;
                })
                ->addColumn('accepted_image', function ($row) {
                    $acceptedImage = TextModel::where('folder_id', $row["id"])->whereNull('type')->where('status', 'active')->count();
                    $accepted_image = '<span class="font-weight-bold-' . $acceptedImage . '"">' . ucfirst($acceptedImage) . '</span>';
                    return $accepted_image;
                })
                ->addColumn('rejected_image', function ($row) {
                    $rejectedImage = TextModel::where('folder_id', $row["id"])->whereNull('type')->where('status', 'inactive')->count();
                    $rejected_image = '<span class="font-weight-bold-' . $rejectedImage . '"">' . ucfirst($rejectedImage) . '</span>';
                    return $rejected_image;
                })
                ->addColumn('Status', function ($row) {
                    $status = '<span class="cell-box user-' . $row["status"] . '">' . ucfirst($row["status"]) . '</span>';
                    return $status;
                })
                ->addColumn('updated-on', function ($row) {
                    $last_seen = '<span class="font-weight-bold">' . \Carbon\Carbon::parse($row['updated_at'])->format('d M Y h:i:s A') . '</span>';
                    return $last_seen;
                })
                ->rawColumns(['actions', 'name', 'checkbox', 'assign_user', 'updated-on', 'status', 'assignUser', 'text_count', 'language', 'text_count', 'accepted_image', 'rejected_image'])
                ->make(true);
        }
        return view('quality_assurance.text_to_text.folder');
    }
    public function text(Request $request)
    {
        if ($request->ajax()) {
            $data = TranscribeResult::select('transcribe_results.*')
                ->join('text_models', 'transcribe_results.text_model_id', '=', 'text_models.id')
                ->whereNotNull('transcribe_results.text_model_id')
                ->where('text_models.folder_id', $request->folderID)
                ->latest()
                ->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('actions', function ($row) {
                    $actionBtn = '<div>
                                         <a class="agreeTranscriptionButton" id="' . $row["id"] . '" href="#"><i class="fa fa-check table-action-buttons request-action-button" title="Activate Language"></i></a>
                                         <a class="disagreeTranscriptionButton" id="' . $row["id"] . '" href="#"><i class="fa fa-close table-action-buttons delete-action-button" title="Deactivate Language"></i></a>
                                         <a class="deleteResultButton" id="' . $row["id"] . '" href="#"><i class="fa-solid fa-trash-xmark table-action-buttons delete-action-button" title="Delete Result"></i></a>
                                  </div>';
                    return $actionBtn;
                })
                ->addColumn('created-on', function ($row) {
                    $created_on = '<a href="' . route("admin.liveTranscription.liveTranscriptionResultsByDatesText", date_format($row["created_at"], 'd M Y')) . '"><span>' . \Carbon\Carbon::parse($row['created_at'])->format('d M Y h:i:s A') . '</span></a>';
                    return $created_on;
                })
                ->addColumn('created-column', function ($row) {
                    $created_on = '<span>' . \Carbon\Carbon::parse($row['created_at'])->format('d M Y h:i:s A') . '</span>';
                    return $created_on;
                })
                ->addColumn('custom-status', function ($row) {
                    switch ($row['status']) {
                        case 'IN_PROGRESS':
                            $value = 'In Progress';
                            break;
                        case 'active':
                            $value = 'Complete';
                            break;
                        case 'inactive':
                            $value = 'Failed';
                            break;
                        default:
                            $value = '';
                            break;
                    }
                    $custom_voice = '<span class="cell-box transcribe-' . strtolower($row["status"]) . '">' . $value . '</span>';
                    return $custom_voice;
                    if ($row["user_id"]) {
                        $username = '<span>' . User::find($row["user_id"])->name . '</span>';
                        return $username;
                    } else {
                        return $row["user_id"];
                    }
                })
                ->addColumn('username', function ($row) {
                    if ($row["user_id"]) {
                        $username = '<span>' . User::find($row["user_id"])->name . '</span>';
                        return $username;
                    } else {
                        return $row["user_id"];
                    }
                })
                ->addColumn('name', function ($row) {
                    if ($row["user_id"]) {
                        return User::find($row["user_id"])->name;
                    } else {
                        return $row["user_id"];
                    }
                })
                ->addColumn('text_data', function ($row) {
                    if ($row['text_model_id']) {
                        $text = TextModel::where('id', $row["text_model_id"])->first();
                        if ($text) {
                            $text_data = '<span>' . TextModel::find($row["text_model_id"])->text . '</span>';
                            return $text_data;
                        } else {
                            return $row["id"];
                        }
                    } else {
                        return $row["id"];
                    }
                })
                ->addColumn('custom-mode', function ($row) {
                    switch ($row['mode']) {
                        case 'file':
                            $value = 'Audio File';
                            break;
                        case 'record':
                            $value = 'Recording';
                            break;
                        default:
                            $value = '';
                            break;
                    }
                    $custom_mode = $value;
                    return $custom_mode;
                })
                ->addColumn('custom-length', function ($row) {
                    $custom_voice = '<span>' . gmdate("H:i:s", $row['length']) . '</span>';
                    return $custom_voice;
                })
                ->addColumn('result', function ($row) {
                    $result = $row['file_url'];
                    return $result;
                })
                ->addColumn('download', function ($row) {
                    if ($row['file_url']) {
                        $result = '<a class="result-download" href="' . route("admin.liveTranscription.liveTranscriptionResultsDownloadAudio", $row['id']) . '" ><i class="fa fa-cloud-download table-action-buttons download-action-button"></i></a>';

                    } else {
                        $result = '';
                    }
                    return $result;
                })
                ->addColumn('single', function ($row) {
                    $result = '<button type="button" class="result-play pl-0" title="Play Audio" onclick="resultPlay(this)" src="' . $row['file_url'] . '" type="' . $row['audio_type'] . '" id="' . $row['id'] . '"><i class="fa fa-play table-action-buttons view-action-button"></i></button>';
                    return $result;
                })
                ->addColumn('custom-language', function ($row) {
                    if (config('stt.vendor_logos') == 'show') {
                        $language = '<span class="vendor-image-sm overflow-hidden"><img alt="vendor" class="mr-2" src="' . URL::asset($row['language_flag']) . '">' . $row['language'] . '<img alt="vendor" class="rounded-circle ml-2" src="' . URL::asset($row['vendor_img']) . '"></span> ';
                    } else {
                        $language = '<span class="vendor-image-sm overflow-hidden"><img alt="vendor" class="mr-2" src="' . URL::asset($row['language_flag']) . '">' . $row['language'] . '</span> ';
                    }
                    return $language;
                })
                ->addColumn('type', function ($row) {
                    $result = ($row['speaker_identity'] == 'true') ? 'Speaker Identification' : 'Standard';
                    return $result;
                })
                ->addColumn('assigned_user', function ($row) {
                    $result = ($row['speaker_identity'] == 'true') ? 'Speaker Identification' : 'Standard';
                    return $result;
                })
                ->rawColumns(['actions', 'created-on', 'custom-status', 'created-column', 'name', 'custom-length', 'custom-language', 'custom-mode', 'result', 'download', 'single', 'type', 'text_data', 'username', 'name'])
                ->make(true);


        }
        return view('quality_assurance.text.text');
    }
    public function textFolder(Request $request,$id){
        return view('quality_assurance.text_to_text.text',compact('id'));
    }
}
