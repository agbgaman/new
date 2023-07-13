<?php

namespace App\Http\Controllers\User\TTS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class VoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $languages = DB::table('voices')
                ->join('vendors', 'voices.vendor_id', '=', 'vendors.vendor_id')
                ->join('voiceover_languages', 'voices.language_code', '=', 'voiceover_languages.language_code')
                ->where('vendors.enabled', '1')
                ->where('voices.status', 'active')
                ->select('voiceover_languages.id', 'voiceover_languages.language', 'voices.language_code', 'voiceover_languages.language_flag')                
                ->distinct()
                ->orderBy('voiceover_languages.language', 'asc')
                ->get();

        $voices = DB::table('voices')
                ->join('vendors', 'voices.vendor_id', '=', 'vendors.vendor_id')
                ->where('vendors.enabled', '1')
                ->where('voices.status', 'active')
                ->where('language_code', 'en-US')
                ->get();

        $data['data'] = json_encode($voices);

        return view('user.voiceover.voices.index', compact('languages', 'data'));
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function change(Request $request)
    {
        $voices = DB::table('voices')
                ->where('language_code', request('code'))
                ->where('status', 'active')
                ->get();

        return response()->json($voices);
    }

}
