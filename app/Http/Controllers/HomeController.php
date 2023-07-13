<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Services\AzureTTSService;
use App\Services\AWSTTSService;
use App\Services\GCPTTSService;
use App\Services\IBMTTSService;
use App\Models\VoiceoverResult;
use App\Models\User;
use App\Models\VoiceoverLanguage;
use App\Models\Voice;
use App\Mail\ContactFormEmail;
use App\Models\SubscriptionPlan;
use App\Models\PrepaidPlan;
use App\Models\Setting;
use App\Models\Blog;
use App\Models\UseCase;
use App\Models\Review;
use App\Models\Page;
use App\Models\Faq;
use DB;

class HomeController extends Controller
{
    /**
     * Show home page
     */
    public function index()
    {
         # Set Voice Types as Listed in TTS Config
         if (config('tts.voice_type') == 'standard') {
            $languages = DB::table('voices')
                ->join('voiceover_languages', 'voices.language_code', '=', 'voiceover_languages.language_code')
                ->where('voices.status', 'active')
                ->where('voices.voice_type', 'standard')
                ->select('voiceover_languages.id', 'voiceover_languages.language', 'voices.language_code', 'voiceover_languages.language_flag')
                ->distinct()
                ->orderBy('voiceover_languages.language', 'asc')
                ->get();

            $voices = DB::table('voices')
                ->where('status', 'active')
                ->where('voice_type', 'standard')
                ->get();

        } elseif (config('tts.voice_type') == 'neural') {
            $languages = DB::table('voices')
                ->join('voiceover_languages', 'voices.language_code', '=', 'voiceover_languages.language_code')
                ->where('voices.status', 'active')
                ->where('voices.voice_type', 'neural')
                ->select('voiceover_languages.id', 'voiceover_languages.language', 'voices.language_code', 'voiceover_languages.language_flag')
                ->distinct()
                ->orderBy('languages.language', 'asc')
                ->get();

            $voices = DB::table('voices')
                ->where('status', 'active')
                ->where('voice_type', 'neural')
                ->get();

        } else {
            $languages = DB::table('voices')
                ->join('voiceover_languages', 'voices.language_code', '=', 'voiceover_languages.language_code')
                ->where('voices.status', 'active')
                ->select('voiceover_languages.id', 'voiceover_languages.language', 'voices.language_code', 'voiceover_languages.language_flag')
                ->distinct()
                ->orderBy('voiceover_languages.language', 'asc')
                ->get();

            $voices = DB::table('voices')
                ->where('status', 'active')
                ->get();
        }

        # Max Chars for Textarea and Textarea Counter
        $max_chars = config('tts.frontend.max_chars');

        $case_exists = UseCase::count();
        $cases = UseCase::all();

        $review_exists = Review::count();
        $reviews = Review::all();

        $information = $this->metadataInformation();

        $faq_exists = Faq::count();
        $faqs = Faq::where('status', 'visible')->get();

        $blog_exists = Blog::count();
        $blogs = Blog::where('status', 'published')->get();

        $monthly = SubscriptionPlan::where('status', 'active')->where('pricing_plan', 'monthly')->count();
        $yearly = SubscriptionPlan::where('status', 'active')->where('pricing_plan', 'yearly')->count();
        $prepaid = PrepaidPlan::where('status', 'active')->count();

        $monthly_subscriptions = SubscriptionPlan::where('status', 'active')->where('pricing_plan', 'monthly')->get();
        $yearly_subscriptions = SubscriptionPlan::where('status', 'active')->where('pricing_plan', 'yearly')->get();
        $prepaids = PrepaidPlan::where('status', 'active')->get();

        return view('frontend.index', compact('information', 'blog_exists', 'blogs', 'faq_exists', 'faqs', 'review_exists', 'reviews', 'monthly', 'yearly', 'prepaid', 'monthly_subscriptions', 'yearly_subscriptions', 'prepaids', 'languages', 'voices', 'max_chars', 'case_exists', 'cases'));
    }

    public function contactPage(){
        return view('frontend.contact');
    }
    public function gts(){
        return view('frontend.gts');
    }
    public function faq(){
        return view('frontend.faq');
    }
    public function legal(){
        return view('frontend.legal');
    }
    public function adminLogin(){
        return view('frontend.login');
    }
    public function service(){
        return view('frontend.service');
    }
    public function antiCorruptionPolicy(){
        return view('frontend.anti-corruption-policy');
    }
    public function cookiePolicy(){
        return view('frontend.cookie-policy');
    }
    public function slaveryPolicy(){
        return view('frontend.global-ethical-sourcing-modern-slavery-policy');
    }
    public function groupWhistleBlower(){
        return view('frontend.group-whistleblower');
    }
    public function privacyPolicies(){
        return view('frontend.privacy-policy');
    }
    public function privacyStatement(){
        return view('frontend.privacy-statement');
    }
    public function engineRelevance(){
        return view('frontend.ser');
    }
    public function surveys(){
        return view('frontend.surveys');
    }
    public function transcription(){
        return view('frontend.transcription');
    }
    public function translations(){
        return view('frontend.translations');
    }
    /**
     * Display terms & conditions page
     *
     */
    public function termsAndConditions()
    {
        $information = $this->metadataInformation();

        $pages_rows = ['terms'];
        $pages = [];
        $page = Page::all();

        foreach ($page as $row) {
            if (in_array($row['name'], $pages_rows)) {
                $pages[$row['name']] = $row['value'];
            }
        }

        return view('service-terms', compact('information', 'pages'));
    }


    /**
     * Display privacy policy page
     *
     */
    public function privacyPolicy()
    {
        $information = $this->metadataInformation();

        $pages_rows = ['privacy'];
        $pages = [];
        $page = Page::all();

        foreach ($page as $row) {
            if (in_array($row['name'], $pages_rows)) {
                $pages[$row['name']] = $row['value'];
            }
        }

        return view('privacy-policy', compact('information', 'pages'));
    }


    /**
     * Frontend show blog
     *
     */
    public function blogShow($slug)
    {
        $blog = Blog::where('url', $slug)->firstOrFail();

        $information_rows = ['js', 'css'];
        $information = [];
        $settings = Setting::all();

        foreach ($settings as $row) {
            if (in_array($row['name'], $information_rows)) {
                $information[$row['name']] = $row['value'];
            }
        }

        $information['author'] = $blog->created_by;
        $information['title'] = $blog->title;
        $information['keywords'] = $blog->keywords;
        $information['description'] = $blog->title;

        return view('blog-show', compact('information', 'blog'));
    }


    /**
     * Frontend contact us form record
     *
     */
    public function contact(Request $request)
    {
        request()->validate([
            'name' => 'required|string',
            'lastname' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required',
            'message' => 'required',
        ]);

        if (config('services.google.recaptcha.enable') == 'on') {

            $recaptchaResult = $this->reCaptchaCheck(request('recaptcha'));

            if ($recaptchaResult->success != true) {
                return redirect()->back()->with('error', 'Google reCaptcha Validation has Failed');
            }

            if ($recaptchaResult->score >= 0.5) {

                try {

                    Mail::to(config('mail.from.address'))->send(new ContactFormEmail($request));

                    if (Mail::flushMacros()) {
                        return redirect()->back()->with('error', 'Sending email failed, please try again.');
                    }

                } catch (\Exception $e) {
                    return redirect()->back()->with('error', 'SMTP settings were not set yet, please contact support team. ' . $e->getMessage());
                }

                return redirect()->back()->with('success', 'Email was successfully sent');

            } else {
                return redirect()->back()->with('error', __('Google reCaptcha Validation has Failed'));
            }

        } else {

            try {

                Mail::to(config('mail.from.address'))->send(new ContactFormEmail($request));

                if (Mail::flushMacros()) {
                    return redirect()->back()->with('error', __('Sending email failed, please try again.'));
                }

            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'SMTP settings were not set yet, please contact support team. ' . $e->getMessage());
            }

            return redirect()->back()->with('success', __('Email was sent successfully'));
        }
    }


    /**
     * Verify reCaptch for frontend contact us page (if enabled)
     *
     */
    private function reCaptchaCheck($recaptcha)
    {
        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $remoteip = $_SERVER['REMOTE_ADDR'];

        $data = [
                'secret' => config('services.google.recaptcha.secret_key'),
                'response' => $recaptcha,
                'remoteip' => $remoteip
        ];

        $options = [
                'http' => [
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data)
                ]
        ];

        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        $resultJson = json_decode($result);

        return $resultJson;
    }


    public function metadataInformation()
    {
        $information_rows = ['title', 'author', 'keywords', 'description', 'js', 'css'];
        $information = [];
        $settings = Setting::all();

        foreach ($settings as $row) {
            if (in_array($row['name'], $information_rows)) {
                $information[$row['name']] = $row['value'];
            }
        }

        return $information;
    }


    /**
     * Process listen synthesize request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function listen(Request $request)
    {
        if ($request->ajax()) {

            request()->validate([
                'voice' => 'required',
                'textarea' => 'required',
                'language' => 'required',
            ]);

            $voice = Voice::where('voice_id', request('voice'))->first();
            $language = VoiceoverLanguage::where('language_code', request('language'))->first();


            # Count characters based on vendor requirements
            $total_characters = mb_strlen(request('textarea'), 'UTF-8');

            # Protection from overusage of credits
            if ($total_characters > config('tts.frontend.max_chars')) {
                return response()->json(["error" => "Total characters of your text is more than allowed, maximum " . config('tts.frontend.max_chars') . " characters allowed. Please decrese the length of your text."], 422);
            }

            # Check if user has characters available to proceed
            $user = User::where('group', 'admin')->firstOrFail();

            if ($user->available_chars < $total_characters) {
                return response()->json(["error" => "Not enough available characters to process. Please notify support team."], 422);
            } else {
                $this->updateAvailableCharacters($total_characters, $user);
            }


            # Name and extention of the audio file
            $file_name = 'LISTEN--' . Str::random(20) . '.mp3';


            $response = $this->processText($voice, request('textarea'), 'mp3', $file_name);


            $result = new VoiceoverResult([
                'user_id' => $user->id,
                'language' => $language->language,
                'voice' => $voice->voice,
                'voice_id' => $voice->voice_id,
                'characters' => $total_characters,
                'text_raw' => request('textarea'),
                'voice_type' => $voice->voice_type,
                'plan_type' => 'free',
                'vendor' => $voice->vendor,
                'vendor_id' => $voice->vendor_id,
                'mode' => 'live',
            ]);

            $result->save();

            $data = [];

            if (config('tts.default_storage') == 'local')
                $data['url'] = URL::asset($response['result_url']);
            else
                $data['url'] = $response['result_url'];

            return $data;
        }
    }


    /**
     * Update user characters number
     */
    private function updateAvailableCharacters($characters, User $user)
    {
        $total_chars = $user->available_chars - $characters;

        $user = User::find($user->id);
        $user->available_chars = $total_chars;
        $user->update();
    }


    /**
     * Process text synthesizes based on the vendor/voice selected
     */
    private function processText(Voice $voice, $text, $format, $file_name)
    {
        $aws = new AWSTTSService();
        $gcp = new GCPTTSService();
        $ibm = new IBMTTSService();
        $azure = new AzureTTSService();

        switch($voice->vendor) {
            case 'aws':
                return $aws->synthesizeSpeech($voice, $text, $format, $file_name);
                break;
            case 'azure':
                return $azure->synthesizeSpeech($voice, $text, $format, $file_name);
                break;
            case 'gcp':
                return $gcp->synthesizeSpeech($voice, $text, $format, $file_name);
                break;
            case 'ibm':
                return $ibm->synthesizeSpeech($voice, $text, $format, $file_name);
                break;
        }
    }

}
