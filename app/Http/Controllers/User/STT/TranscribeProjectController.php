<?php

namespace App\Http\Controllers\User\STT;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Storage;
use App\Services\GCPSTTService;
use App\Models\Project;
use App\Models\TranscribeResult;
use App\Models\User;
use Yajra\DataTables\DataTables;
use DB;

class TranscribeProjectController extends Controller
{

    private $gcp;

    public function __construct()
    {
        $this->gcp = new GCPSTTService();
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        # Today's Transcribe Results for Datatable
        if ($request->ajax()) {
            $data = TranscribeResult::where('project', Auth::user()->default_project)->where('mode', '<>', 'live')->latest()->get();
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('actions', function($row){
                        $actionBtn = '<div class="dropdown">
                                        <a href="'. route("user.transcribe.projects.show", $row["id"]) .'"><i class="fa fa-clipboard table-action-buttons edit-action-button" title=__("View Result")></i></a>
                                        <a class="deleteResultButton" id="'. $row["id"] .'" href="#"><i class="fa-solid fa-trash-xmark table-action-buttons delete-action-button" title=__("Delete Result")></i></a>
                                    </div>';
                        return $actionBtn;
                    })
                    ->addColumn('created-on', function($row){
                        $created_on = '<span>'.date_format($row["created_at"], 'd M Y').'</span>';
                        return $created_on;
                    })
                    ->addColumn('custom-status', function($row){
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
                        $custom_status = '<span class="cell-box transcribe-'.strtolower($row["status"]).'">'.$value.'</span>';
                        return $custom_status;
                    })
                    ->addColumn('custom-mode', function($row){
                        switch ($row['mode']) {
                            case 'file':
                                $value = 'Uploaded File';
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
                    ->addColumn('custom-length', function($row){
                        $custom_voice = '<span>'.gmdate("H:i:s", $row['length']).'</span>';
                        return $custom_voice;
                    })
                    ->addColumn('custom-language', function($row) {
                        if (config('stt.vendor_logos') == 'show') {
                            $language = '<span class="vendor-image-sm overflow-hidden"><img alt="vendor" class="mr-2" src="' . URL::asset($row['language_flag']) . '">'. $row['language'] .'<img alt="vendor" class="rounded-circle ml-2" src="' . URL::asset($row['vendor_img']) . '"></span> ';
                        } else {
                            $language = '<span class="vendor-image-sm overflow-hidden"><img alt="vendor" class="mr-2" src="' . URL::asset($row['language_flag']) . '">'. $row['language'] .'</span> ';
                        }
                        return $language;
                    })
                    ->addColumn('download', function($row){
                        $result = '<a class="result-download" href="' . $row['file_url'] . '" download title="Download Audio"><i class="fa fa-cloud-download table-action-buttons download-action-button"></i></a>';
                        return $result;
                    })
                    ->addColumn('single', function($row){
                        $result = '<button type="button" class="result-play" title="Play Audio" onclick="resultPlay(this)" src="' . $row['file_url'] . '" type="'. $row['audio_type'].'" id="'. $row['id'] .'"><i class="fa fa-play table-action-buttons view-action-button"></i></button>';
                        return $result;
                    })
                    ->addColumn('result', function($row){
                        $result = $row['file_url'];
                        return $result;
                    })
                    ->addColumn('type', function($row){
                        $result = ($row['speaker_identity'] == 'true') ? 'Speaker Identification' : 'Standard';
                        return $result;
                    })
                    ->rawColumns(['actions', 'created-on', 'download', 'custom-status', 'custom-name', 'custom-mode', 'custom-length', 'single', 'result', 'type', 'custom-language'])
                    ->make(true);

        }

        $projects = Project::where('user_id', auth()->user()->id)->get();

        $data_results = DB::table('transcribe_results')->where('project', auth()->user()->default_project)->where('user_id', auth()->user()->id)
                        ->where('mode', '<>', 'live')
                        ->select(DB::raw('count(id) as total'))
                        ->get();
        $data_results = get_object_vars($data_results[0]);

        $data_time = DB::table('transcribe_results')->where('project', auth()->user()->default_project)->where('user_id', auth()->user()->id)
                        ->where('mode', '<>', 'live')
                        ->select(DB::raw('sum(length) as total'))
                        ->get();
        $data_time = get_object_vars($data_time[0]);

        $data_words = DB::table('transcribe_results')->where('project', auth()->user()->default_project)->where('user_id', auth()->user()->id)
                        ->where('mode', '<>', 'live')
                        ->select(DB::raw('sum(words) as total'))
                        ->get();
        $data_words = get_object_vars($data_words[0]);

        return view('user.transcribe.projects.index', compact('projects', 'data_results', 'data_time', 'data_words'));
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function change(Request $request)
    {
        if ($request->project == 'all') {
            $data = TranscribeResult::where('user_id', Auth::user()->id)->where('mode', '<>', 'live')->latest()->get();
        } else {
            $data = TranscribeResult::where('project', $request->project)->where('user_id', Auth::user()->id)->where('mode', '<>', 'live')->get();
        }

        # Today's TTS Results for Datatable
        if ($request->ajax()) {
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('actions', function($row){
                        $actionBtn = '<div class="dropdown">
                                        <a href="'. route("user.transcribe.projects.show", $row["id"]) .'"><i class="fa fa-clipboard table-action-buttons edit-action-button" title=__("View Result")></i></a>
                                        <a class="deleteResultButton" id="'. $row["id"] .'" href="#"><i class="fa-solid fa-trash-xmark table-action-buttons delete-action-button" title=__("Delete Result")></i></a>
                                    </div>';
                        return $actionBtn;
                    })
                    ->addColumn('created-on', function($row){
                        $created_on = '<span>'.date_format($row["created_at"], 'Y-m-d H:i:s').'</span>';
                        return $created_on;
                    })
                    ->addColumn('custom-status', function($row){
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
                        $custom_status = '<span class="cell-box transcribe-'.strtolower($row["status"]).'">'.$value.'</span>';
                        return $custom_status;
                    })
                    ->addColumn('custom-mode', function($row){
                        switch ($row['mode']) {
                            case 'file':
                                $value = 'Uploaded File';
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
                    ->addColumn('custom-length', function($row){
                        $custom_voice = '<span>'.gmdate("H:i:s", $row['length']).'</span>';
                        return $custom_voice;
                    })
                    ->addColumn('custom-language', function($row) {
                        if (config('stt.vendor_logos') == 'show') {
                            $language = '<span class="vendor-image-sm overflow-hidden"><img alt="vendor" class="mr-2" src="' . URL::asset($row['language_flag']) . '">'. $row['language'] .'<img alt="vendor" class="rounded-circle ml-2" src="' . URL::asset($row['vendor_img']) . '"></span> ';
                        } else {
                            $language = '<span class="vendor-image-sm overflow-hidden"><img alt="vendor" class="mr-2" src="' . URL::asset($row['language_flag']) . '">'. $row['language'] .'</span> ';
                        }
                        return $language;
                    })
                    ->addColumn('download', function($row){
                        $result = '<a class="result-download" href="' . $row['file_url'] . '" download title="Download Audio"><i class="fa fa-cloud-download table-action-buttons download-action-button"></i></a>';
                        return $result;
                    })
                    ->addColumn('single', function($row){
                        $result = '<button type="button" class="result-play" title="Play Audio" onclick="resultPlay(this)" src="' . $row['file_url'] . '" type="'. $row['audio_type'].'" id="'. $row['id'] .'"><i class="fa fa-play table-action-buttons view-action-button"></i></button>';
                        return $result;
                    })
                    ->addColumn('result', function($row){
                        $result = $row['file_url'];
                        return $result;
                    })
                    ->addColumn('type', function($row){
                        $result = ($row['speaker_identity'] == 'true') ? 'Speaker Identification' : 'Standard';
                        return $result;
                    })
                    ->rawColumns(['actions', 'created-on', 'download', 'custom-status', 'custom-name', 'custom-mode', 'custom-length', 'single', 'result', 'type', 'custom-language'])
                    ->make(true);

        }
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function changeStatus(Request $request)
    {

        if ($request->project == 'all') {
            $data_results = DB::table('transcribe_results')->where('user_id', auth()->user()->id)
                            ->where('mode', '<>', 'live')
                            ->select(DB::raw('count(id) as total'))
                            ->get();
            $data_results = get_object_vars($data_results[0]);

            $data_time = DB::table('transcribe_results')->where('user_id', auth()->user()->id)
                            ->where('mode', '<>', 'live')
                            ->select(DB::raw('sum(length) as total'))
                            ->get();
            $data_time = get_object_vars($data_time[0]);

            $data_words = DB::table('transcribe_results')->where('user_id', auth()->user()->id)
                            ->where('mode', '<>', 'live')
                            ->select(DB::raw('sum(words) as total'))
                            ->get();
            $data_words = get_object_vars($data_words[0]);

        } else {
            $data_results = DB::table('transcribe_results')->where('project', $request->project)->where('user_id', auth()->user()->id)
                            ->where('mode', '<>', 'live')
                            ->select(DB::raw('count(id) as total'))
                            ->get();
            $data_results = get_object_vars($data_results[0]);

            $data_time = DB::table('transcribe_results')->where('project', $request->project)->where('user_id', auth()->user()->id)
                            ->where('mode', '<>', 'live')
                            ->select(DB::raw('sum(length) as total'))
                            ->get();
            $data_time = get_object_vars($data_time[0]);

            $data_words = DB::table('transcribe_results')->where('project', $request->project)->where('user_id', auth()->user()->id)
                            ->where('mode', '<>', 'live')
                            ->select(DB::raw('sum(words) as total'))
                            ->get();
            $data_words = get_object_vars($data_words[0]);
        }

        if ($request->ajax()) {
            $data['results'] = $data_results;
            $data['time'] = $data_time;
            $data['words'] = $data_words;
            return $data;
        }
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->ajax()) {
            request()->validate([
                'new-project' => 'required'
            ]);

            if (strtolower(request('new-project') == 'all')) {
                return response()->json(['status' => 'error', 'message' => __('Project Name is reserved and is already created, please create another one')]);
            }

            $check = Project::where('user_id', auth()->user()->id)->where('name', request('new-project'))->first();

            if (!isset($check)) {
                $project = new Project([
                    'user_id' => auth()->user()->id,
                    'name' =>  htmlspecialchars(request('new-project'))
                ]);

                $project->save();

                return response()->json(['status' => 'success', 'message' => __('Project has been successfully created')]);

            } else {
                return response()->json(['status' => 'error', 'message' => __('Project name already exists')]);
            }
        }

    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update()
    {
        request()->validate([
            'project' => 'required'
        ]);

        $check = Project::where('user_id', auth()->user()->id)->where('name', request('project'))->first();

        if (isset($check)) {
            $user = User::where('id', auth()->user()->id)->first();
            $user->default_project = request('project');
            $user->save();

            return redirect()->back()->with('success', __('Default Project has been successfully updated'));

        } else {
            return redirect()->back()->with('error', __('Default Project has not been updated. Please try again'));
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(TranscribeResult $id)
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

            return view('user.transcribe.projects.show', compact('id', 'data', 'task_type'));
        }

        return view('user.transcribe.projects');

    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        request()->validate([
            'project' => 'required'
        ]);

        $project = Project::where('user_id', auth()->user()->id)->where('name', request('project'))->first();


        if (isset($project)) {

            $project->delete();

            TranscribeResult::where('project', request('project'))->where('user_id', auth()->user()->id)->delete();

            $user = User::where('id', auth()->user()->id)->first();
            $user->default_project = ($user->default_project == request('project'))? '' : $user->default_project;
            $user->save();

            return redirect()->back()->with('success', __('Selected Project was deleted successfully'));

        } else {
            return redirect()->back()->with('error', __('Selected Project was not deleted properly. Please try again'));
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyResult($id)
    {
        $result = TranscribeResult::where('id', $id)->firstOrFail();

        if ($result->user_id == Auth::user()->id){

            if ($result->mode != 'live') {
                if ($result->vendor == 'aws') {
                    $object = 'aws/' . $result->task_id . '.' . $result->format;
                    Storage::disk('s3')->delete($object);

                    $response = $result->task_id . '.json';
                    $exists = Storage::disk('s3')->has($response);
                    if ($exists) {
                        Storage::disk('s3')->delete($response);
                    }

                } elseif ($result->vendor == 'gcp') {
                    $this->gcp->deleteObject($result->task_id, $result->format);
                }
            }

            $result->delete();

            return redirect()->route('user.projects')->with('success', __('Transcribe result was deleted successfully'));

        } else{
            return redirect()->route('user.projects');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        if ($request->ajax()) {

            $result = TranscribeResult::where('id', request('id'))->firstOrFail();

            if ($result->user_id == Auth::user()->id){

                $result->delete();

                return response()->json('success');

            } else{
                return response()->json('error');
            }
        }
    }
}
