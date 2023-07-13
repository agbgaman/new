<?php

namespace App\Http\Controllers\User\STT;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Services\GCPSTTService;
use Illuminate\Http\Request;
use App\Models\TranscribeResult;
use DataTables;

class TranscribeResultController extends Controller
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
        if ($request->ajax()) {
            $data = TranscribeResult::where('user_id', Auth::user()->id)->latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('actions', function($row){
                    $actionBtn = '<div>
                                            <a id="'.$row["id"].'" href="'. route('user.transcribe.results.show', $row['id']) .'" class="transcribeResult"><i class="fa fa-clipboard table-action-buttons edit-action-button" title="View Result"></i></a>
                                            <a class="deleteResultButton" id="'. $row["id"] .'" href="#"><i class="fa-solid fa-trash-xmark table-action-buttons delete-action-button" title="Delete Result"></i></a>
                                        </div>';
                    return $actionBtn;
                })
                ->addColumn('created-on', function($row){
                    $created_on = '<span>'.date_format($row["created_at"], 'd M Y').'</span>';
                    return $created_on;
                })
                ->addColumn('format-type', function($row){
                    if ($row['image_id']){
                        $formatType = '<span>'.'Image'.'</span>';
                        return $formatType;
                    }
                    elseif($row['text_model_id']) {
                        $formatType = '<span>'.'Text'.'</span>';
                        return $formatType;
                    }
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
                    $custom_voice = '<span class="cell-box transcribe-'.strtolower($row["status"]).'">'.$value.'</span>';
                    return $custom_voice;
                })
                ->addColumn('custom-mode', function($row) {
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
                ->addColumn('custom-length', function($row) {
                    $custom_voice = '<span>'.gmdate("H:i:s", $row['length']).'</span>';
                    return $custom_voice;
                })
                ->addColumn('result', function($row) {
                    $result = $row['file_url'];
                    return $result;
                })
                ->addColumn('download', function($row){
                    $result = '<a class="result-download" href="' . $row['file_url'] . '" download title="Download Audio"><i class="fa fa-cloud-download table-action-buttons download-action-button"></i></a>';
                    return $result;
                })
                ->addColumn('single', function($row){
                    $result = '<button type="button" class="result-play pl-0" title="Play Audio" onclick="resultPlay(this)" src="' . $row['file_url'] . '" type="'. $row['audio_type'].'" id="'. $row['id'] .'"><i class="fa fa-play table-action-buttons view-action-button"></i></button>';
                    return $result;
                })
                ->addColumn('custom-language', function($row) {
                    if (config('stt.vendor_logos') == 'show') {
                        $language = '<span class="vendor-image-sm overflow-hidden"><img alt="vendor" class="mr-2" src="' . URL::asset($row['language_flag']) . '">'. $row['language'] .'<img alt="vendor" class="rounded-circle ml-2" src="' . URL::asset($row['vendor_img']) . '"></span> ';
                    } else {
                        $language = '<span class="vendor-image-sm overflow-hidden"><img alt="vendor" class="mr-2" src="' . URL::asset($row['language_flag']) . '">'. $row['language'] .'</span> ';
                    }
                    return $language;
                })
                ->addColumn('type', function($row){
                    $result = ($row['speaker_identity'] == 'true') ? 'Speaker Identification' : 'Standard';
                    return $result;
                })
                ->rawColumns(['actions', 'created-on', 'custom-status', 'custom-length', 'custom-language', 'custom-mode', 'result', 'download', 'single', 'type','format-type'])
                ->make(true);

        }

        return view('user.transcribe.results.index');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(TranscribeResult $id)
    {
        if ($id->user_id == Auth::user()->id){

            $end_time = gmdate("H:i:s", $id->length);

            $data['type'] = json_encode($id->speaker_identity);
            $data['raw'] = json_encode($id->raw);
            $data['text'] = json_encode($id->text);
            $data['url'] = json_encode($id->file_url);
            $data['vendor'] = json_encode($id->vendor);
            $data['end_time'] = json_encode($end_time);

            $task_type = ($id->speaker_identity == 'true') ? 'Speaker Identification' : 'Standard';

            return view('user.transcribe.results.show', compact('id', 'data', 'task_type'));

        } else{
            return redirect()->route('user.transcribe.results');
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

            } else{
                return response()->json('error');
            }
        }
    }


    public function transcript(Request $request)
    {
        if ($request->ajax()) {

            if ($request->json && !$request->speaker) {
                $data = TranscribeResult::where('id', $request->id)->pluck('raw');
            } elseif ($request->json && $request->speaker) {
                $data['raw'] = TranscribeResult::where('id', $request->id)->pluck('raw');
                $data['speaker'] = TranscribeResult::where('id', $request->id)->pluck('speaker_identity');
                $data['vendor'] = TranscribeResult::where('id', $request->id)->pluck('vendor');
            } else {
                if ($request->transcript == 'text'){
                    $data = TranscribeResult::where('id', $request->id)->with('csv_text')->first();

                } elseif($request->transcript == 'image') {
                    $data = TranscribeResult::where('id', $request->id)->with('image')->first();
                }

            }

            return $data;
        }
    }


    public function transcriptSave(Request $request)
    {
        if ($request->ajax()) {

            $data = TranscribeResult::where('id', $request->id)->first();
            $data->update(['text' => $request->text]);

            return __('Transcript was successfully updated and saved');
        }
    }
}
