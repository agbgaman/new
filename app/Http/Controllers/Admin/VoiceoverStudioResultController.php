<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\URL;
use Illuminate\Http\Request;
use App\Services\Statistics\CostsService;
use App\Models\VoiceoverResult;
use App\Models\User;
use DataTables;

class VoiceoverStudioResultController extends Controller
{
    /**
     * List voiceover studio synthesize results
     */
    public function listResults(Request $request)
    {
        if ($request->ajax()) {
            $data = VoiceoverResult::all()->where('mode', 'file')->sortByDesc("created_at");
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('actions', function($row){
                        $actionBtn = '<div>
                                        <a href="'. route("admin.voiceover.result.show", $row["id"] ). '"><i class="fa-solid fa-list-music table-action-buttons edit-action-button" title="View Result"></i></a>
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
                    ->addColumn('custom-voice-type', function($row){
                        $custom_voice = '<span class="cell-box voice-'.strtolower($row["voice_type"]).'">'.ucfirst($row["voice_type"]).'</span>';
                        return $custom_voice;
                    })
                    ->addColumn('username', function($row){
                        if ($row["user_id"]) {
                            $username = '<span>'.User::find($row["user_id"])->name.'</span>';
                            return $username;
                        } else {
                            return $row["user_id"];
                        }
                       
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
                    ->addColumn('result', function($row){
                        $result = ($row['storage'] == 'local') ? URL::asset($row['result_url']) : $row['result_url'];
                        return $result;
                    })
                    ->addColumn('vendor', function($row){
                        $path = URL::asset($row['vendor_img']);
                        $vendor = '<div class="vendor-image-sm overflow-hidden"><img alt="vendor" class="rounded-circle" src="' . $path . '"></div>';
                        return $vendor;
                    })
                    ->addColumn('custom-language', function($row) {
                        $language = '<span class="vendor-image-sm overflow-hidden"><img class="mr-2" src="' . URL::asset($row['language_flag']) . '">'. $row['language'] .'</span> ';            
                        return $language;
                    })
                    ->rawColumns(['actions', 'custom-plan-type', 'created-on', 'username', 'custom-voice-type', 'result', 'vendor', 'download', 'single', 'custom-language'])
                    ->make(true);
                    
        }

        return view('admin.studio.results.voiceover.index');
    }


    /**
     * Display selected result details
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(VoiceoverResult $id)
    {   
        $name = User::find($id->user_id)->name;
        $email = User::find($id->user_id)->email;

        $cost = new CostsService();

        $json_data['cost'] = json_encode($cost->getCostPerText($id->id));

        return view('admin.studio.results.voiceover.show', compact('id', 'email', 'json_data'));
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

            $result->delete();

            return response()->json('success');    
        }     
    }

}
