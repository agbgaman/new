<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\UserInformation;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Http\UploadedFile;
use App\Services\Statistics\UserPaymentsService;
use App\Services\Statistics\UserUsageYearlyService;
use App\Services\Statistics\UserUsageMonthlyService;
use App\Models\Subscriber;
use App\Models\SubscriptionPlan;
use App\Models\User;
use App\Models\Project;
use App\Models\Voice;
use App\Models\VoiceoverLanguage;
use App\Models\TranscribeLanguage;
use DB;


class UserDashboardController extends Controller
{
    use Notifiable;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $year = $request->input('year', date('Y'));
        $month = $request->input('month', date('m'));

        $payments_yearly = new UserPaymentsService($year);
        $usage_yearly = new UserUsageYearlyService($year);
        $usage_monthly = new UserUsageMonthlyService($month, $year);

        $user_data_month = [
            'total_standard_chars' => $usage_monthly->getTotalStandardCharsUsage(),
            'total_earning' => $usage_monthly->getEarning(),
            'total_minutes' => $usage_monthly->getTotalMinutes(),
            'referrer' => $usage_monthly->getTotalTranscribeInputs()
        ];

        $user_data_year = [
            'total_payments' => $payments_yearly->getTotalPayments(auth()->user()->id),
            'total_standard_chars' => $usage_yearly->getTotalStandardCharsUsage(auth()->user()->id),
            'total_neural_chars' => $usage_yearly->getTotalNeuralCharsUsage(auth()->user()->id),
            'total_audio_files' => $usage_yearly->getTotalAudioFiles(auth()->user()->id),
            'total_listen_modes' => $usage_yearly->getTotalListenModes(auth()->user()->id),
            'total_minutes' => $usage_yearly->getTotalMinutes(auth()->user()->id),
            'total_words' => $usage_yearly->getTotalWords(auth()->user()->id),
            'total_file_transcribe' => $usage_yearly->getTotalFileTranscribe(auth()->user()->id),
            'total_recording_transcribe' => $usage_yearly->getTotalRecordingTranscribe(auth()->user()->id),
            'total_live_transcribe' => $usage_yearly->getTotalLiveTranscribe(auth()->user()->id),
        ];

        $chart_data['payments'] = json_encode($payments_yearly->getPayments(auth()->user()->id));
        $chart_data['standard_chars'] = json_encode($usage_yearly->getStandardCharsUsage(auth()->user()->id));
        $chart_data['neural_chars'] = json_encode($usage_yearly->getNeuralCharsUsage(auth()->user()->id));
        $chart_data['file_minutes'] = json_encode($usage_yearly->getFileMinutesUsage(auth()->user()->id));
        $chart_data['record_minutes'] = json_encode($usage_yearly->getRecordMinutesUsage(auth()->user()->id));
        $chart_data['live_minutes'] = json_encode($usage_yearly->getLiveMinutesUsage(auth()->user()->id));

        if (auth()->user()->hasActiveSubscription()) {
            $subscription = Subscriber::where('user_id', auth()->user()->id)->where('status', 'Active')->first();
        } else {
            $subscription = false;
        }

        $user_subscription = ($subscription) ? SubscriptionPlan::where('id', auth()->user()->plan_id)->first() : '';

        $characters = auth()->user()->available_chars;
        $minutes = auth()->user()->available_minutes;

        if ($subscription) {
            $plan = SubscriptionPlan::where('id', auth()->user()->plan_id)->first();
            $total_characters = $plan->characters;
            $total_minutes = $plan->minutes;
        } else {
            $total_characters = config('tts.free_chars');
            $total_minutes = config('tts.free_minutes');
        }

        $voiceover_voice = Voice::where('voice_id', auth()->user()->voice)->select('voice')->get();
        $voiceover_language = VoiceoverLanguage::where('language_code', auth()->user()->language)->select('language')->get();
        $transcribe_language = TranscribeLanguage::where('id', auth()->user()->language_file)->select('language')->get();

        $progress = [
            'characters' => ($total_characters > 0) ? ((auth()->user()->available_chars / $total_characters) * 100) : 0,
            'minutes' => ($total_minutes > 0) ? ((auth()->user()->available_minutes / $total_minutes) * 100) : 0,
        ];
        $completionPercentage = $usage_yearly->userInformationPercentage(auth()->user()->id);

        return view('user.dashboard.index', compact('subscription', 'chart_data', 'user_data_year', 'user_subscription', 'characters', 'total_characters', 'progress', 'minutes', 'total_minutes', 'user_data_month', 'voiceover_voice', 'voiceover_language', 'transcribe_language','completionPercentage'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id = null)
    {
        # Show Languages
        $languages = \Illuminate\Support\Facades\DB::table('transcribe_languages')
            ->select('transcribe_languages.id', 'transcribe_languages.language', 'transcribe_languages.language_code', 'transcribe_languages.language_flag', 'transcribe_languages.vendor_img')
            ->orderBy('id', 'asc')
            ->get();
        return view('user.dashboard.edit',compact('languages'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editDefaults($id = null)
    {
        # Set Voice Types as Listed in TTS Config
        if (config('tts.voice_type') == 'standard') {
            $languages = DB::table('voices')
                ->join('vendors', 'voices.vendor_id', '=', 'vendors.vendor_id')
                ->join('voiceover_languages', 'voices.language_code', '=', 'voiceover_languages.language_code')
                ->where('vendors.enabled', '1')
                ->where('voices.status', 'active')
                ->where('voices.voice_type', 'standard')
                ->select('voiceover_languages.id', 'voiceover_languages.language', 'voices.language_code', 'voiceover_languages.language_flag')
                ->distinct()
                ->orderBy('voiceover_languages.language', 'asc')
                ->get();

            $voices = DB::table('voices')
                ->join('vendors', 'voices.vendor_id', '=', 'vendors.vendor_id')
                ->where('vendors.enabled', '1')
                ->where('voices.voice_type', 'standard')
                ->where('voices.status', 'active')
                ->orderBy('voices.vendor', 'asc')
                ->get();

        } elseif (config('tts.voice_type') == 'neural') {
            $languages = DB::table('voices')
                ->join('vendors', 'voices.vendor_id', '=', 'vendors.vendor_id')
                ->join('voiceover_languages', 'voices.language_code', '=', 'voiceover_languages.language_code')
                ->where('vendors.enabled', '1')
                ->where('voices.status', 'active')
                ->where('voices.voice_type', 'neural')
                ->select('voiceover_languages.id', 'voiceover_languages.language', 'voices.language_code', 'voiceover_languages.language_flag')
                ->distinct()
                ->orderBy('voiceover_languages.language', 'asc')
                ->get();

            $voices = DB::table('voices')
                ->join('vendors', 'voices.vendor_id', '=', 'vendors.vendor_id')
                ->where('vendors.enabled', '1')
                ->where('voices.voice_type', 'neural')
                ->where('voices.status', 'active')
                ->orderBy('voices.vendor', 'asc')
                ->get();

        } else {
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
                ->orderBy('voices.vendor', 'asc')
                ->get();
        }

        $languages_file = DB::table('transcribe_languages')
                ->where('type', 'file')
                ->orWhere('type', 'both')
                ->where('status', 'active')
                ->orderBy('language', 'asc')
                ->get();

        $languages_live = DB::table('transcribe_languages')
                ->orWhere('type', 'both')
                ->where('status', 'active')
                ->orderBy('language', 'asc')
                ->get();

        $projects = Project::where('user_id', auth()->user()->id)->get();

        return view('user.dashboard.update', compact('languages', 'voices', 'projects', 'languages_live', 'languages_file'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(User $user)
    {
        $user->update(request()->validate([
            'name'      => 'required|string|max:255',
            'email'     => ['required','string','email','max:255',Rule::unique('users')->ignore($user)],
            'job_role'  => 'nullable|string|max:255',
            'company'   => 'nullable|string|max:255',
            'website' => 'nullable|string|max:255',
            'phone_number' => 'nullable|max:20',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'language' => 'nullable',
            'timezone' => 'required|string|max:255',
        ]));

        if (request()->has('profile_photo')) {

            try {
                request()->validate([
                    'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5048'
                ]);

            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'PHP FileInfo: ' . $e->getMessage());
            }

            $image = request()->file('profile_photo');

            $name = Str::random(20);

            $folder = '/uploads/img/users/';

            $filePath = $folder . $name . '.' . $image->getClientOriginalExtension();

            $this->uploadImage($image, $folder, 'public', $name);

            $user->profile_photo_path = $filePath;

            $user->save();
        }

        return redirect()->route('user.dashboard.edit', compact('user'))->with('success',__('Profile was successfully updated'));

    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateDefaults(User $user)
    {
        $user->update(request()->validate([
            'voice' => 'nullable|string|max:255',
            'language' => 'nullable|string|max:255',
            'project' => 'nullable|string|max:255',
            'language_file' => 'nullable',
            'language_live' => 'nullable',
        ]));


        $user->save();


        return redirect()->route('user.dashboard.edit.defaults', compact('user'))->with('success',__('Default settings successfully updated'));

    }


    /**
     * Upload user profile image
     */
    public function uploadImage(UploadedFile $file, $folder = null, $disk = 'public', $filename = null)
    {
        $name = !is_null($filename) ? $filename : Str::random(25);

        $image = $file->storeAs($folder, $name .'.'. $file->getClientOriginalExtension(), $disk);

        return $image;
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function project(Request $request)
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
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function completeProfile()
    {
        # Show Languages
        $languages = \Illuminate\Support\Facades\DB::table('transcribe_languages')
            ->select('transcribe_languages.id', 'transcribe_languages.language', 'transcribe_languages.language_code', 'transcribe_languages.language_flag', 'transcribe_languages.vendor_img')
            ->orderBy('id', 'asc')
            ->get();
        $information = UserInformation::where('user_id',auth()->id())->first();
        if ($information ){

        } else {
            $information = new UserInformation();
            $information->user_id = auth()->id();
            $information->save();
        }
        return view('user.dashboard.complete-profile',compact('languages','information'));
    }
    public function completeProfileStore(Request $request)
    {
        $user = User::updateOrCreate(
            [
                'id' => auth()->id(),
            ],
            [
                'city'              => $request->city,
                'country'           => $request->country,
                'language'          => $request->language,
                'postal_code'       => $request->zip,
                'phone_number'      => $request->phone_number,

            ]
        );
        $information = UserInformation::updateOrCreate(
            [
                'user_id' => auth()->id(),
            ],
            [
                'gender'                            => $request->gender,
                'hasPet'                            => $request->hasPet,
                'date'                              => $request->date,
                'hasTranslationExperience'          => $request->hasTranslationExperience,
                'state_province'                    => $request->state_province,
                'englishLearningAge'                => $request->englishLearningAge,
                'spent_time_country'                => $request->spent_time_country,
                'born_city'                         => $request->born_city,
                'familyParticipation'               => $request->familyParticipation,
                'education'                         => $request->education,
                'linguistics'                       => $request->linguistics,
                'experienceSearchEngineEvaluator'   => $request->experienceSearchEngineEvaluator,
                'experienceProofreading'            => $request->experienceProofreading,
                'experienceTranscription'           => $request->experienceTranscription,
                'residency_years'                   => $request->residency_years,
                'address'                           => $request->address,
                'primary_language'                  => $request->primary_language,
                'race_and_ethnicity'                => $request->race_and_ethnicity,
                'android_functionality'             => $request->android_functionality,
                'country_you_lived'                 => $request->country_you_lived,
                'working_company'                   => $request->working_company,
                'english_skills'                    => $request->english_skills
            ]
        );
        return redirect()->back()->with('success',__('Profile has been updated successfully'));
    }
}
