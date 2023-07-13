<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Illuminate\Http\Request;
use App\Models\VoiceoverResult;
use DataTables;

class SearchController extends Controller
{
    /**
     * Show search results
     */
    public function index(Request $request)
    {
        $results = VoiceoverResult::where('user_id', Auth::user()->id)->where( 'title', 'LIKE', '%' . $request->keyword . '%' )->orWhere( 'text', 'LIKE', '%' . $request->keyword . '%' )->orWhere( 'project', 'LIKE', '%' . $request->keyword . '%' )->latest()->get();

        $data = Datatables::of($results)
            ->addIndexColumn()
            ->addColumn('actions', function($row){
                $actionBtn = '<div>
                                <a href="'. route("user.voiceover.show", $row["id"] ). '"><i class="fa-solid fa-list-music table-action-buttons view-action-button" title="View Result"></i></a>
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
            ->addColumn('custom-language', function($row) {
                $language = '<span class="vendor-image-sm overflow-hidden"><img class="mr-2" src="' . URL::asset($row['language_flag']) . '">'. $row['language'] .'</span> ';            
                return $language;
            })
            ->rawColumns(['actions', 'created-on', 'custom-voice-type', 'result', 'download', 'single', 'custom-language'])
            ->make(true);
        

        $searchValue = $request->keyword;
        $data = json_encode($data);

        return view('user.search.index', compact('searchValue', 'data'));
    }


    /**
     * Format storage space to readable format
     */
    private function formatSize($size, $precision = 2) { 
        $units = array('B', 'KB', 'MB', 'GB', 'TB'); 
    
        $size = max($size, 0); 
        $pow = floor(($size ? log($size) : 0) / log(1024)); 
        $pow = min($pow, count($units) - 1); 
        
        $size /= pow(1024, $pow);

        return round($size, $precision) . $units[$pow]; 
    }
}
