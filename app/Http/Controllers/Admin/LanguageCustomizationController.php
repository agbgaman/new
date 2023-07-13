<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\URL;
use Illuminate\Http\Request;
use App\Models\TranscribeLanguage;
use DataTables;

class LanguageCustomizationController extends Controller
{
    /**
     * List all transcribe studio languages
     */
    public function languages(Request $request)
    {
        if ($request->ajax()) {
            $data = TranscribeLanguage::all();
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('actions', function($row){
                        $actionBtn = '<div>      
                                        <a class="changeLanguageNameButton" id="' . $row["id"] . '" href="#"><i class="fa fa-edit table-action-buttons view-action-button" title="Rename Language"></i></a>      
                                        <a class="activateLanguageButton" id="' . $row["id"] . '" href="#"><i class="fa fa-check table-action-buttons request-action-button" title="Activate Language"></i></a>
                                        <a class="deactivateLanguageButton" id="' . $row["id"] . '" href="#"><i class="fa fa-close table-action-buttons delete-action-button" title="Deactivate Language"></i></a>  
                                    </div>';
                        return $actionBtn;
                    })
                    ->addColumn('created-on', function($row){
                        $created_on = '<span>'.date_format($row["updated_at"], 'd M Y').'</span>';
                        return $created_on;
                    })
                    ->addColumn('custom-type', function($row){
                        $custom_voice = '<span class="cell-box type-'.strtolower($row["type"]).'">'.ucfirst($row["type"]).'</span>';
                        return $custom_voice;
                    })
                    ->addColumn('custom-status', function($row){
                        $custom_voice = '<span class="cell-box status-'.strtolower($row["status"]).'">'.ucfirst($row["status"]).'</span>';
                        return $custom_voice;
                    })
                    ->addColumn('speaker', function($row){
                        $speaker = ucfirst($row["speaker_identity"]);
                        return $speaker;
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
                    ->rawColumns(['actions', 'created-on', 'custom-type', 'vendor', 'custom-status', 'speaker', 'custom-language'])
                    ->make(true);
                    
        }

        return view('admin.studio.languages.index');
    }


    /**
     * Update the specified language name.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function languageUpdate(Request $request)
    {   
        if ($request->ajax()) {

            $language = TranscribeLanguage::where('id', request('id'))->firstOrFail(); 
            
            $language->update(['language' => request('name')]);
            return  response()->json('success');
        } 
    }


    /**
     * Enable the specified language.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function languageActivate(Request $request)
    {
        if ($request->ajax()) {

            $language = TranscribeLanguage::where('id', request('id'))->firstOrFail();  

            if ($language->status == 'active') {
                return  response()->json('active');
            }

            $language->update(['status' => 'active']);

            return  response()->json('success');
        }
    }


    /**
     * Enable all languages.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function languagesActivateAll(Request $request)
    {
        if ($request->ajax()) {

            TranscribeLanguage::query()->update(['status' => 'active']);

            return  response()->json('success');
        } 
    }


    /**
     * Disable the specified language.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function languageDeactivate(Request $request)
    {
        if ($request->ajax()) {

            $language = TranscribeLanguage::where('id', request('id'))->firstOrFail();  

            if ($language->status == 'deactive') {
                return  response()->json('deactive');
            }

            $language->update(['status' => 'deactive']);

            return  response()->json('success');
        }
    }


    /**
     * Disable all languages.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function languagesDeactivateAll(Request $request)
    {
        if ($request->ajax()) {

            TranscribeLanguage::query()->update(['status' => 'deactive']);

            return  response()->json('success');
        }         
    }

}
