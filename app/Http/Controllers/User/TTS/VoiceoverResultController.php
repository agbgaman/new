<?php

namespace App\Http\Controllers\User\TTS;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\VoiceoverResult;
use DataTables;

class VoiceoverResultController extends Controller
{

    /** 
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   
        if ($request->ajax()) {
            $data = VoiceoverResult::where('user_id', Auth::user()->id)->where('mode', 'file')->latest()->get();
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('actions', function($row){
                        $actionBtn = '<div>                                            
                                            <a href="'. route("user.voiceover.results.show", $row["id"] ). '"><i class="fa-solid fa-list-music table-action-buttons edit-action-button" title="View Result"></i></a>
                                            <a class="deleteResultButton" id="'. $row["id"] .'" href="#"><i class="fa-solid fa-trash-xmark table-action-buttons delete-action-button" title="Delete Result"></i></a>
                                        </div>';
                        return $actionBtn;
                    })
                    ->addColumn('created-on', function($row){
                        $created_on = '<span>'.date_format($row["created_at"], 'd M Y').'</span>';
                        return $created_on;
                    })
                    ->addColumn('custom-voice-type', function($row){
                        $custom_voice = '<span class="cell-box voice-'.strtolower($row["voice_type"]).'">'.ucfirst($row["voice_type"]).'</span>';
                        return $custom_voice;
                    })                   
                    ->addColumn('result', function($row){
                        $result = ($row['storage'] == 'local') ? URL::asset($row['result_url']) : $row['result_url'];
                        return $result;
                    })
                    ->addColumn('download', function($row){
                        $url = ($row['storage'] == 'local') ? URL::asset($row['result_url']) : $row['result_url'];
                        $result = '<a class="" href="' . $url . '" download><i class="fa fa-cloud-download table-action-buttons download-action-button" title="Download Result"></i></a>';
                        return $result;
                    })
                    ->addColumn('single', function($row){
                        $url = ($row['storage'] == 'local') ? URL::asset($row['result_url']) : $row['result_url'];
                        $result = '<button type="button" class="result-play p-0" onclick="resultPlay(this)" src="' . $url . '" type="'. $row['audio_type'].'" id="'. $row['id'] .'"><i class="fa fa-play table-action-buttons view-action-button" title="Play Result"></i></button>';
                        return $result;
                    })
                    ->addColumn('custom-language', function($row) {
                        $language = '<span class="vendor-image-sm overflow-hidden"><img class="mr-2" src="' . URL::asset($row['language_flag']) . '">'. $row['language'] .'</span> ';            
                        return $language;
                    })
                    ->addColumn('vendor', function($row) {
                        $path = URL::asset($row['vendor_img']);
                        $vendor = '<div class="vendor-image-sm overflow-hidden"><img alt="vendor" src="' . $path . '"></div>';
                        return $vendor;
                    })
                    ->rawColumns(['actions', 'created-on', 'custom-voice-type', 'result', 'vendor', 'download', 'single', 'custom-language'])
                    ->make(true);
                    
        }

        $config = config('tts.vendor_logos');

        return view('user.voiceover.results.index', compact('config'));
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(VoiceoverResult $id)
    {
        if ($id->user_id == Auth::user()->id){

            return view('user.voiceover.results.show', compact('id'));     

        } else{
            return redirect()->route('user.voiceover.results');
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

            $result = VoiceoverResult::where('id', request('id'))->firstOrFail();  

            if ($result->user_id == Auth::user()->id){

                $result->delete();

                return response()->json('success');    
    
            } else{
                return response()->json('error');
            } 
        }              
    }
}
