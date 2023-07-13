<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\URL;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\TranscribeResult;
use App\Models\User;
use DataTables;

class TranscribeStudioResultController extends Controller
{
    /**
     * List transcribe studio results
     */
    public function listResults(Request $request)
    {
        if ($request->ajax()) {
            $data = TranscribeResult::all()->where('mode', '<>', 'live')->sortByDesc("created_at");
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('actions', function($row){
                    $actionBtn = '<div>
                                            <a id="'.$row["id"].'" href="'. route('admin.transcribe.result.show', $row['id']) .'" class="transcribeResult"><i class="fa fa-clipboard table-action-buttons view-action-button" title="View Transcript Result"></i></a>
                                            <a class="deleteResultButton" id="'. $row["id"] .'" href="#"><i class="fa-solid fa-trash-xmark table-action-buttons delete-action-button" title="Delete Result"></i></a>
                                        </div>';
                    return $actionBtn;
                })
                ->addColumn('created-on', function($row){
                    $created_on = '<span>'.date_format($row["created_at"], 'd M Y').'</span>';
                    return $created_on;
                })
                ->addColumn('custom-plan-type', function($row){
                    $custom_plan = '<span class="cell-box plan-'.strtolower($row["plan_type"]).'">'.ucfirst($row["plan_type"]).'</span>';
                    return $custom_plan;
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
                    }
                    $custom_voice = '<span class="cell-box transcribe-'.strtolower($row["status"]).'">'.$value.'</span>';
                    return $custom_voice;
                })
                ->addColumn('custom-mode', function($row){
                    switch ($row['mode']) {
                        case 'file':
                            $value = 'Audio File';
                            break;
                        case 'record':
                            $value = 'Recording';
                            break;
                    }
                    $custom_mode = $value;
                    return $custom_mode;
                })
                ->addColumn('username', function($row){
                    if ($row["user_id"]) {
                        $username = '<span>'.User::find($row["user_id"])->name.'</span>';
                        return $username;
                    } else {
                        return $row["user_id"];
                    }
                })
                ->addColumn('custom-length', function($row){
                    $custom_voice = '<span>'.gmdate("H:i:s", $row['length']).'</span>';
                    return $custom_voice;
                })
                ->addColumn('download', function($row){
                    $result = '<a class="result-download" href="' . $row['file_url'] . '" download title="Download Audio"><i class="fa fa-cloud-download table-action-buttons download-action-button"></i></a>';
                    return $result;
                })
                ->addColumn('single', function($row){
                    $result = '<button type="button" class="result-play pl-0" title="Play Audio" onclick="resultPlay(this)" src="' . $row['file_url'] . '" type="'. $row['audio_type'].'" id="'. $row['id'] .'"><i class="fa fa-play table-action-buttons view-action-button"></i></button>';
                    return $result;
                })
                ->addColumn('result', function($row){
                    $result = $row['file_url'];
                    return $result;
                })
                ->addColumn('custom-language', function($row) {
                    $language = '<span class="vendor-image-sm overflow-hidden"><img class="mr-2" src="' . URL::asset($row['language_flag']) . '">'. $row['language'] .'<img alt="vendor" class="rounded-circle ml-2" src="' . URL::asset($row['vendor_img']) . '"></span> ';
                    return $language;
                })
                ->rawColumns(['actions', 'custom-plan-type', 'created-on', 'username', 'custom-status', 'custom-mode', 'custom-length', 'result', 'custom-language', 'download', 'single'])
                ->make(true);

        }

        return view('admin.studio.results.transcribe.index');
    }


    /**
     * Display selected result details
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(TranscribeResult $id)
    {
        $end_time = gmdate("H:i:s", $id->length);

        $data['type'] = json_encode($id->speaker_identity);
        $data['raw'] = json_encode($id->raw);
        $data['text'] = json_encode($id->text);
        $data['url'] = json_encode($id->file_url);
        $data['vendor'] = json_encode($id->vendor);
        $data['end_time'] = json_encode($end_time);

        $task_type = ($id->speaker_identity == 'true') ? 'Speaker Identification' : 'Standard';

        return view('admin.studio.results.transcribe.show', compact('id', 'data', 'task_type'));
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

            return response()->json('success');
        }
    }


}
