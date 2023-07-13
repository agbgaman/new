<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use DB;


class TranscribeStudioSettingsController extends Controller
{
    /**
     * Display TTS configuration settings
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $languages_file = DB::table('transcribe_languages')
                ->where('type', 'file')
                ->orWhere('type', 'both')
                ->where('status', 'active')
                ->orderBy('language', 'asc')
                ->get();

        $languages_live = DB::table('transcribe_languages')
                ->where('type', 'both')
                ->where('status', 'active')
                ->orderBy('language', 'asc')
                ->get();

        return view('admin.studio.settings.transcribe.index', compact('languages_file', 'languages_live'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        request()->validate([
            'set-language-file' => 'required',
            'set-language-live' => 'required',
            'set-max-size' => 'required|integer|max:2000|min:0',
            'set-file-format' => 'required',
            'set-max-length-file' => 'required|numeric|max:480|min:0',
            'set-max-length-live' => 'required|numeric|max:240|min:0',
            'set-max-length-file-none' => 'required|numeric|max:480|min:0',
            'set-max-length-live-none' => 'required|numeric|max:240|min:0',
            'free-minutes' => 'required',

            'enable-aws' => 'sometimes|required',
            'set-aws-access-key' => 'required_if:enable-aws,on',
            'set-aws-secret-access-key' => 'required_if:enable-aws,on',
            'set-aws-region' => 'required_if:enable-aws,on',
            'set-aws-bucket' => 'required_if:enable-aws,on',

            'enable-gcp' => 'sometimes|required',
            'gcp-configuration-path' => 'required_if:enable-gcp,on',
            'gcp-bucket' => 'required_if:enable-gcp,on',
        ]);

        $this->storeConfiguration('CONFIG_DEFAULT_LANGUAGE_FILE', request('set-language-file'));
        $this->storeConfiguration('CONFIG_DEFAULT_LANGUAGE_LIVE', request('set-language-live'));
        $this->storeConfiguration('CONFIG_MAX_SIZE_LIMIT', request('set-max-size'));
        $this->storeConfiguration('CONFIG_MAX_LENGTH_LIMIT_FILE', request('set-max-length-file'));
        $this->storeConfiguration('CONFIG_MAX_LENGTH_LIMIT_LIVE', request('set-max-length-live'));
        $this->storeConfiguration('CONFIG_MAX_LENGTH_LIMIT_FILE_NONE', request('set-max-length-file-none'));
        $this->storeConfiguration('CONFIG_MAX_LENGTH_LIMIT_LIVE_NONE', request('set-max-length-live-none'));
        $this->storeConfiguration('CONFIG_FREE_MINUTES', request('free-minutes'));
        $this->storeConfiguration('CONFIG_VENDOR_LOGOS', request('vendor-logo'));
        $this->storeConfiguration('CONFIG_SPEAKER_IDENTIFICATION', request('speaker-identification'));
        $this->storeConfiguration('CONFIG_LIVE_TRANSCRIPTION_TEXT_AREA', request('live-transcription-text-area'));

        if (request('set-file-format')) {
            $newName = "'". request('set-file-format') . "'";
            $this->storeWithQuotes('CONFIG_FILE_FORMAT', $newName);
        }

        $this->storeConfiguration('AWS_ACCESS_KEY_ID', request('set-aws-access-key'));
        $this->storeConfiguration('AWS_SECRET_ACCESS_KEY', request('set-aws-secret-access-key'));
        $this->storeConfiguration('AWS_DEFAULT_REGION', request('set-aws-region'));
        $this->storeConfiguration('AWS_BUCKET', request('set-aws-bucket'));

        $this->storeConfiguration('CONFIG_ENABLE_AWS_AUDIO', request('enable-aws'));
        $this->storeConfiguration('CONFIG_ENABLE_AWS_AUDIO_LIVE', request('enable-aws-live'));
        $this->storeConfiguration('CONFIG_ENABLE_GCP_AUDIO', request('enable-gcp'));

        $this->storeConfiguration('GOOGLE_APPLICATION_CREDENTIALS', request('gcp-configuration-path'));
        $this->storeConfiguration('GOOGLE_STORAGE_BUCKET', request('gcp-bucket'));

        # Enable/Disable AWS
        if (request('enable-aws') == 'on') {
            $aws = Vendor::where('vendor_id', 'aws_audio')->first();
            $aws->enabled = 1;
            $aws->save();

        } else {
            $aws = Vendor::where('vendor_id', 'aws_audio')->first();
            $aws->enabled = 0;
            $aws->save();
        }

        # Enable/Disable GCP
        if (request('enable-gcp') == 'on') {
            $aws = Vendor::where('vendor_id', 'gcp_audio')->first();
            $aws->enabled = 1;
            $aws->save();

        } else {
            $aws = Vendor::where('vendor_id', 'gcp_audio')->first();
            $aws->enabled = 0;
            $aws->save();
        }

        return redirect()->back()->with('success', __('Settings were successfully updated'));
    }


    /**
     * Record in .env file
     */
    private function storeConfiguration($key, $value)
    {
        $path = base_path('.env');

        if (file_exists($path)) {

            file_put_contents($path, str_replace(
                $key . '=' . env($key), $key . '=' . $value, file_get_contents($path)
            ));

        }
    }

    private function storeWithQuotes($key, $value)
    {
        $path = base_path('.env');

        if (file_exists($path)) {

            file_put_contents($path, str_replace(
                $key . '=' . '\'' . env($key) . '\'', $key . '=' . $value, file_get_contents($path)
            ));

        }
    }
}
