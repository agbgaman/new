<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Folder;
use App\Models\Image;
use App\Models\TextModel;
use App\Models\TranscribeResult;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Yajra\DataTables\DataTables;
use Aws\S3\S3Client;
use Illuminate\Support\Facades\Storage;
use Aws\S3\Exception\S3Exception;

class LiveTrancriptionController extends Controller
{
    public function liveTranscriptionResults(Request $request)
    {
        if ($request->ajax()) {
            if ($request->date) {
                $data = TranscribeResult::whereDate('created_at', $request->date);
            } elseif ($request->user) {
                $data = TranscribeResult::where('user_id', $request->user);
            } else {
                $data = TranscribeResult::query();
            }

            $start_date = $request->input('created_on_from');
            $end_date = $request->input('created_on_to');

            $data->when(!empty($start_date), function ($query) use ($start_date) {
                return $query->where('created_at', '>=', $start_date);
            })
                ->when(!empty($end_date), function ($query) use ($end_date) {
                    return $query->where('created_at', '<=', $end_date);
                })
                ->whereNotNull('image_id')
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
                    $created_on = '<a href="' . route("admin.liveTranscription.liveTranscriptionResultsByDates", date_format($row["created_at"], 'd M Y')) . '"><span>' . date_format($row["created_at"], 'd M Y') . '</span></a>';
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
                            $value = 'Complete';
                            break;
                        default:
                            $value = '';
                            break;
                    }
                    $custom_voice = '<span class="cell-box transcribe-' . strtolower($row["status"]) . '">' . $value . '</span>';
                    return $custom_voice;
                })
                ->addColumn('username', function ($row) {
                    if ($row["user_id"]) {
                        $username = '<a href="' . route("admin.liveTranscription.liveTranscriptionResultsByUser", $row["user_id"]) . '"> <span>' . User::find($row["user_id"])->name . '</span></a>';
                        return $username;
                    } else {
                        return $row["user_id"];
                    }
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
                ->addColumn('image', function ($row) {
                    if ($row['image_id']) {
                        $image = Image::where('id', $row["image_id"])->first();
                        if ($image) {
                            return Image::find($row["image_id"])->image;
                        } else {
                            return $row["image_id"];
                        }
                    } else {
                        return $row["image_id"];
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
                ->addColumn('duration', function ($row) {
                    $duration = gmdate("H:i:s", $row['length']);
                    return $duration;
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
                ->rawColumns(['actions', 'created-on', 'custom-status', 'custom-length', 'custom-language', 'custom-mode', 'result', 'download', 'single', 'type', 'image_id', 'username', 'image', 'duration'])
                ->make(true);

        }

        return view('admin.Images.liveTranscribe.index');
    }

    public function liveTranscriptionResultsByDates($date)
    {

        return view('admin.Images.liveTranscribe.indexDate', compact('date'));
    }

    public function liveTranscriptionResultsByUser($user)
    {
        $transcribeResultsTotal = TranscribeResult::select('id', 'file_url')
            ->whereNotNull('file_url')
            ->where('user_id', $user)
            ->count();

        return view('admin.Images.liveTranscribe.indexUser', compact('user', 'transcribeResultsTotal'));
    }

    public function textListUser(Request $request,$id)
    {

        if ($request->ajax()) {
            $start_date = $request->input('created_on_from');
            $end_date = $request->input('created_on_to');

            $data = Folder::query();

            if (!empty($start_date)) {
                $data->whereHas('assignUser', function ($query) use ($start_date) {
                    $query->where('last_seen', '>=', $start_date);
                });
            }

            if (!empty($end_date)) {
                $data->whereHas('assignUser', function ($query) use ($end_date) {
                    $query->where('last_seen', '<=', $end_date);
                });
            }

            $data = $data->whereNotNull('assign_user_id')
                ->where('project_id', $request->project_id)
                ->whereHas('text')
                ->get();

            // Group folders by assign_user_id
            $groupedFolders = $data->groupBy('assign_user_id');
            $groupedFolders->project_id = $request->project_id;
            return Datatables::of($groupedFolders)
                ->addIndexColumn()
                ->addColumn('user', function ($group) {
                    // Use the first folder in the group to get user information
                    $user = $group->first()->assignUser;

                    if (!$user) {
                        return 'User not found';
                    }

                    $hasUnreadText = $group->filter(function ($folder) {
                        return $folder->text->whereNull('read_at')->isNotEmpty();
                    })->isNotEmpty();
                    $badge = $hasUnreadText ? '<span class="badge badge-success"><i class="fas fa-bell"></i> new</span>' : '';

                    if ($user->profile_photo_path) {
                        $path = asset($user->profile_photo_path);
                    } else {
                        $path = URL::asset('img/users/avatar.png');
                    }

                    return '<div class="d-flex">
                                <div class="widget-user-image-sm overflow-hidden mr-4"><img alt="Avatar" src="' . $path . '"></div>
                                <div class="widget-user-name"><span class="font-weight-bold">' . $user->name . '</span><br><span class="text-muted">' . $user->email . '</span>' . $badge . '</div>
                            </div>';
                })

                ->addColumn('assigned_folders', function ($group) {

                    return '<span class="font-weight-bold">' . $group->where('status', 'complete')->count() . '</span>';
                })
                ->addColumn('complete', function ($group) {
                    return '<span class="font-weight-bold">' . $group->where('status', 'complete')->count() . '</span>';
                })
                ->addColumn('last-seen-on', function ($group) {
                    $user = $group->first()->assignUser;
                    if (!$user) {
                        return '';
                    }

                    return '<span class="font-weight-bold">' . \Carbon\Carbon::parse($user->last_seen)->format('d M Y h:i:s A') . '</span>';
                })
                ->addColumn('created-on', function ($group) {
                    return '<span class="font-weight-bold">' . date_format($group->first()->created_at, 'd M Y') . '</span>';
                })
                ->addColumn('custom-status', function ($group) {
                    $status = $group->first()->status;

                    return '<span class="cell-box user-' . $status . '">' . ucfirst($status) . '</span>';
                })
                ->addColumn('custom-country', function ($group) {
                    $user = $group->first()->assignUser;
                    if (!$user) {
                        return '';
                    }
                    return '<span class="font-weight-bold">' . $user->country . '</span>';
                })
                ->addColumn('folder_count', function ($group) use ($request) {
                    $assignUserId = $group->first()->assign_user_id;
                    if (!$assignUserId) {
                        return '';
                    }
                    $textCountFolder = Folder::where('assign_user_id', $assignUserId)
                        ->where('project_id', $request->project_id)
                        ->whereHas('text')
                        ->count();
                    $images_count = '<a href="' . route("admin.text.list.user.folder",  ['projectID' => $request->project_id, 'userID' => $assignUserId]) . '" class="folder-link all-images"><i class="fas fa-folder folder-icon-list "></i><span class="font-weight-bold-' . $textCountFolder . '"">' . $textCountFolder . '</span></a>';
                    return $images_count;
                })
                ->rawColumns(['actions', 'custom-status', 'folderCount', 'assigned_folders', 'complete', 'custom-group', 'custom-currency', 'created-on', 'user', 'custom-country', 'folder_count', 'custom-characters', 'custom-minutes', 'last-seen-on'])
                ->make(true);

        }

            return view('admin.Images.liveTranscribeText.user',compact('id'));
    }

    public function textListUserFolder(Request $request, $projectID,$userID)
    {

        if ($request->ajax()) {
            $data = Folder::withCount('text')
                ->where('assign_user_id', $userID)
                ->where('project_id', $projectID)
                ->having('text_count', '>', 0)
                ->latest()
                ->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('checkbox', function ($row) {
                    return '<input type="checkbox" class="folder-checkbox" data-folder-id="' . $row['id'] . '">';
                })
                ->addColumn('actions', function ($row) {
                    if ($row['status'] == 'paid') {
                        $actionBtn = '<div>
                                         <a class="downloadButton" href="' . route("admin.coco.userImageDownload", $row['id']) . '" ><i class="fa fa-download table-action-buttons download-action-button" title="Download"></i></a>
                                         <a class="deleteUserButton" id="' . $row["id"] . '" href="#"><i class="fa-solid fa-user-slash table-action-buttons delete-action-button" title="Delete User"></i></a>
                                      </div>';
                        return $actionBtn;
                    } else {
                        $actionBtn = '<div>
                                           <a class="downloadButton" href="' . route("admin.coco.userImageDownload", $row['id']) . '" ><i class="fa fa-download table-action-buttons download-action-button" title="Download"></i></a>
                                           <a class="deleteUserButton" id="' . $row["id"] . '" href="#"><i class="fa-solid fa-user-slash table-action-buttons delete-action-button" title="Delete User"></i></a>
                                           <a class="agreePayment" id="' . $row["id"] . '" href="#"><i class="fa fa-check table-action-buttons request-action-button" title="Payment"></i></a>

                                      </div>';
                        return $actionBtn;
                    }
                })
                ->addColumn('name', function ($row) {
                    $hasUnreadImages = $row->text()->whereNull('read_at')->exists();
                    $badge = $hasUnreadImages ? '<span class="badge badge-success"><i class="fas fa-bell"></i> new</span>' : '';
                    $name = '<div class="d-flex align-items-center"><span class="mr-2 font-weight-bold-' . $row["name"] . '">' . ucfirst($row["name"]) . '</span>' . $badge . '</div>';
                    return $name;
                })
                ->addColumn('qualityAssurance', function ($row) {
                    if ($row["quality_assurance_id"]) {
                        $user = User::find($row["quality_assurance_id"]);
                        if ($user) {
                            $qualityAssurance = '<span>' . $user->name . '</span>';
                            return $qualityAssurance;
                        } else {
                            return $row["quality_assurance_id"];
                        }
                    } else {
                        return $row["quality_assurance_id"];
                    }
                })
                ->addColumn('text_count', function ($row) {
                    $textCount = TextModel::where('folder_id', $row["id"])->whereNull('type')->count();
                    $text_count = '<a href="' . route("admin.text.list.user.folder.text", $row["id"]) . '"><span class="font-weight-bold-' . $textCount . '"">' . $textCount . '</span></a>';
                    return $text_count;
                })
                ->addColumn('accepted_text', function ($row) {
                    $total_count = TranscribeResult::query()
                        ->join('text_models', 'transcribe_results.text_model_id', '=', 'text_models.id')
                        ->where('text_models.folder_id', $row['id'])
                        ->where('transcribe_results.status', 'active')
                        ->whereNotNull('text_model_id')
                        ->count();
                    $accepted_image = '<span class="font-weight-bold-' . $total_count . '"">' . ucfirst($total_count) . '</span>';
                    return $accepted_image;
                })
                ->addColumn('rejected_text', function ($row) {
                    $rejected = TranscribeResult::query()
                        ->join('text_models', 'transcribe_results.text_model_id', '=', 'text_models.id')
                        ->where('text_models.folder_id', $row['id'])
                        ->where('transcribe_results.status', 'inactive')
                        ->whereNotNull('text_model_id')
                        ->count();
                    $rejected_image = '<span class="font-weight-bold-' . $rejected . '"">' . $rejected . '</span>';
                    return $rejected_image;
                })
                ->addColumn('custom-status', function ($row) {
                    switch ($row['status']) {
                        case 'active':
                            $value = 'Active';
                            break;
                        case 'in_progress':
                            $value = 'Not Assigned';
                            break;
                        case 'inactive':
                            $value = 'Inactive';
                            break;
                        case 'complete':
                            $value = 'Completed';
                            break;
                        case 'In QC':
                            $value = 'In QC';
                            break;
                        case 'paid':
                            $value = 'Paid';
                            break;
                        default:
                            $value = '';
                            break;
                    }

                    $status = '<span class="cell-box transcribe-' . strtolower(str_replace(' ', '_', $value)) . '">' . $value . '</span>';
                    return $status;
                })
                ->rawColumns(['actions', 'name', 'checkbox', 'assign_user', 'custom-status', 'qualityAssurance', 'status', 'assignUser', 'images_count', 'language', 'text_count', 'accepted_text', 'rejected_text'])
                ->make(true);
        }
        $users = User::whereHas('roles', function ($query) {
            $query->where('name', 'quality_assurance');
        })->withCount('folders')->latest()->get();

        return view('admin.Images.liveTranscribeText.folder', compact('projectID','userID', 'users'));
    }

    public function textListUserFolderText($id)
    {
        $texts = TextModel::where('folder_id', $id)->get();
        foreach ($texts as $text) {
            $text->read_at = now();
            $text->save();
        }
        return view('admin.Images.liveTranscribeText.index', compact('id'));

    }

    public function liveTranscriptionResultsText(Request $request)
    {

        if ($request->ajax()) {
            $start_date = $request->input('created_on_from');
            $end_date = $request->input('created_on_to');

            $data = TranscribeResult::select('transcribe_results.*')
                ->join('text_models', 'transcribe_results.text_model_id', '=', 'text_models.id')
                ->when($request->folder_id, function ($query) use ($request) {
                    return $query->where('text_models.folder_id', $request->folder_id);
                })
                ->when($request->date, function ($query) use ($request) {
                    return $query->whereDate('transcribe_results.created_at', $request->date);
                })
                ->when($request->user, function ($query) use ($request) {
                    return $query->where('transcribe_results.user_id', $request->user);
                })
                ->when(!empty($start_date), function ($query) use ($start_date) {
                    return $query->where('transcribe_results.created_at', '>=', $start_date);
                })
                ->when(!empty($end_date), function ($query) use ($end_date) {
                    return $query->where('transcribe_results.created_at', '<=', $end_date);
                })
                ->where('transcribe_results.mode', 'live')
                ->whereNotNull('transcribe_results.text_model_id')
                ->with('csv_text.folder.qualityAssurance')
                ->latest('transcribe_results.created_at')
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
                    $created_on = '<a href="' . route("admin.liveTranscription.liveTranscriptionResultsByDatesText", $row["created_at"]) . '"><span>' . \Carbon\Carbon::parse($row['created_at'])->format('d M Y h:i:s A') . '</span></a>';
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
                        case 'inactive':
                            $value = 'Failed';
                            break;
                        case 'active':
                            $value = 'Complete';
                            break;
                        default:
                            $value = '';
                            break;
                    }
                    $custom_voice = '<span class="cell-box transcribe-' . strtolower($row["status"]) . '">' . $value . '</span>';
                    return $custom_voice;
                })
                ->addColumn('username', function ($row) {
                    if ($row["user_id"]) {
                        $username = '<a href="' . route("admin.liveTranscription.liveTranscriptionResultsByUserText", $row["user_id"]) . '"> <span>' . User::find($row["user_id"])->email . '</span></a>';
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
//                ->addColumn('custom-language', function ($row) {
//                    if (config('stt.vendor_logos') == 'show') {
//                        $language = '<span class="vendor-image-sm overflow-hidden"><img alt="vendor" class="mr-2" src="' . URL::asset($row['language_flag']) . '">' . $row['language'] . '<img alt="vendor" class="rounded-circle ml-2" src="' . URL::asset($row['vendor_img']) . '"></span> ';
//                    } else {
//                        $language = '<span class="vendor-image-sm overflow-hidden"><img alt="vendor" class="mr-2" src="' . URL::asset($row['language_flag']) . '">' . $row['language'] . '</span> ';
//                    }
//                    return $language;
//                })
                ->addColumn('type', function ($row) {
                    $result = ($row['speaker_identity'] == 'true') ? 'Speaker Identification' : 'Standard';
                    return $result;
                })
                ->addColumn('assigned_user', function ($row) {
                    $result = ($row['speaker_identity'] == 'true') ? 'Speaker Identification' : 'Standard';
                    return $result;
                })
                ->rawColumns(['actions', 'created-on', 'custom-status', 'created-column', 'name', 'custom-length', 'custom-mode', 'result', 'download', 'single', 'type', 'text_data', 'username', 'name'])
                ->make(true);

        }

        return view('admin.Images.liveTranscribeText.index');
    }


    public function liveTranscriptionResultsByDatesText($date)
    {
        return view('admin.Images.liveTranscribeText.indexDate', compact('date'));
    }

    public function liveTranscriptionResultsByUserText($user)
    {
        return view('admin.Images.liveTranscribeText.indexUser', compact('user'));
    }

    public function liveTranscriptionResultsAgree(Request $request)
    {
        if ($request->ajax()) {

            $transcription = TranscribeResult::where('id', request('id'))->firstOrFail();

            if ($transcription->status == 'active') {
                return response()->json('active');
            }

            $transcription->update(['status' => 'active']);

            return response()->json('success');
        }
    }

    public function liveTranscriptionResultsDisAgree(Request $request)
    {
        if ($request->ajax()) {

            $transcription = TranscribeResult::where('id', request('id'))->firstOrFail();
            if ($transcription->status == 'inactive') {
                return response()->json('inactive');
            }

            $transcription->update(['status' => 'inactive']);

            return response()->json('success');
        }
    }

    public function liveTranscriptionResultsDownloadAudio($id)
    {
        $transcribe = TranscribeResult::select('file_url')->where('id', $id)->first();
        $fileURL = $transcribe->file_url;

        $client = new S3Client([
            'credentials' => [
                'key' => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ],
            'region' => env('AWS_DEFAULT_REGION'),
            'version' => 'latest',
        ]);
        try {

            $result = $client->putObject([
                'Bucket' => env('AWS_BUCKET'),
                'Key' => 'aws/' . $fileURL,
                'ACL' => 'public-read'
            ]);
            return redirect($fileURL);

        } catch (S3Exception $e) {
            // Handle the exception
            dd($e->getMessage());
        }
    }

    public function liveTranscriptionResultsDownloadAllAudio(Request $request)
    {

        $requestNumber = $request->requestNumber - 1;

        $skip = $request->batch * $requestNumber;
        $take = $request->batch;
        $transcribeResults = TranscribeResult::select('id', 'file_url')
            ->whereNotNull('file_url')
            ->take($take)
            ->skip($skip)
            ->where('user_id', $request->user)
            ->get();

        $fileUrls = [];
        $client = new S3Client([
            'credentials' => [
                'key' => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ],
            'region' => env('AWS_DEFAULT_REGION'),
            'version' => 'latest',
        ]);

        foreach ($transcribeResults as $result) {

            $awsResult = $client->putObject([
                'Bucket' => env('AWS_BUCKET'),
                'Key' => 'aws/' . $result->file_url,
                'ACL' => 'public-read'
            ]);

            $fileUrls[] = $result->file_url;
        }

        $zipFilename = 'audios_' . $request->user . '_' . time() . '.zip';
        $zip = new \ZipArchive();
        $zip->open($zipFilename, \ZipArchive::CREATE);

        foreach ($fileUrls as $fileUrl) {

            try {
                // Get the audio file contents
                $audioContents = file_get_contents($fileUrl);

                // Extract the filename from the URL
                $filename = basename($fileUrl);

                // Add the file to the zip archive
                $zip->addFromString($filename, $audioContents);
            } catch (\Exception $e) {
                continue;
            }

        }

        $zip->close();
        $zipContents = file_get_contents($zipFilename);
        $zipFileSize = filesize($zipFilename); // Store the file size before deleting the file
        unlink($zipFilename);

        return response($zipContents)
            ->header('Content-Type', 'application/zip')
            ->header('Content-Disposition', 'attachment; filename=' . $zipFilename)
            ->header('Content-Length', $zipFileSize);
    }

    public function nextText(Request $request)
    {
        $direction = $request->direction === 'next' ? 'desc' : 'asc';
        $operator = $direction === 'desc' ? '<' : '>';

        $query = TranscribeResult::query();
        if ($request->date) {
            $query->whereDate('created_at', $request->date);
        } elseif ($request->user) {
            $query->where('user_id', $request->user);
        }
        $data = $query->where('id', $operator, $request->text_id)
            ->whereNotNull('text_model_id')
            ->orderBy('id', $direction)
            ->with('csv_text.folder.qualityAssurance', 'user')
            ->first();
        return response()->json($data);

    }

    public function saveFeedbackResult(Request $request)
    {
        if ($request->feedback == 'correct') {
            $status = 'active';
        } else {
            $status = 'inactive';
        }
        $image = TranscribeResult::where('id', $request->image_id)->update([
            'comment' => $request->comment,
            'remark_id' => $request->remark,
            'status' => $status
        ]);
        return;
    }
}
