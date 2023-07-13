<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Setting;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function adminCreate()
    {
        $information_rows = ['title', 'author', 'keywords', 'description', 'css', 'js'];
        $information = [];
        $settings = Setting::all();

        foreach ($settings as $row) {
            if (in_array($row['name'], $information_rows)) {
                $information[$row['name']] = $row['value'];
            }
        }

        return view('auth.login', compact('information'));
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param \App\Http\Requests\Auth\LoginRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function adminStore(LoginRequest $request)
    {
        if (config('services.google.recaptcha.enable') == 'on') {

            $recaptchaResult = $this->reCaptchaCheck(request('recaptcha'));

            if ($recaptchaResult->success != true) {
                return redirect()->back()->with('error', __('Google reCaptcha Validation has Failed'));
            }

            if ($recaptchaResult->score >= 0.5) {

                $request->authenticate();

                if (!auth()->user()->hasRole('admin')) {

                    return redirect('/')->with(Auth::logout());

                    $request->session()->regenerate();

                    return redirect()->route('admin.dashboard');
                }

                if (config('frontend.maintenance') == 'on') {
                    if (auth()->user()->group != 'admin') {
                        return redirect('/')->with(Auth::logout());
                    }

                } else {

                    $request->session()->regenerate();

                    return redirect()->intended(RouteServiceProvider::HOME);
                }


            } else {
                return redirect()->back()->with('error', __('Google reCaptcha Validation has Failed'));
            }

        } else {

            $request->authenticate();
            if (!auth()->user()->hasRole('admin')) {
                return redirect('/')->with(Auth::logout());
            }
            if (auth()->user()->google2fa_enabled == true) {
                $google2fa = app('pragmarx.google2fa');


                $registration_data = $request->all();

                $registration_data["google2fa_secret"] = $google2fa->generateSecretKey();

                // Here, instead of flashing the data to the session, we will save it to the database
                $user = auth()->user();
                if (!auth()->user()->google2fa_secret) {

                    $user->google2fa_secret = encrypt($registration_data['google2fa_secret']);
                    $user->save();
                    $request->session()->flash('registration_data', $registration_data);

                    $QR_Image = $google2fa->getQRCodeInline(
                        config('app.name'),
                        $registration_data['email'],
                        $registration_data['google2fa_secret']
                    );

                    return view('auth.google2fa.register', ['QR_Image' => $QR_Image, 'secret' => $registration_data['google2fa_secret']]);
                } else {
                    return view('auth.2fa');
                }


            } else {
                if (auth()->user()->hasRole('admin')) {
                    $request->session()->regenerate();

                    return redirect()->route('admin.dashboard');
                }

                if (config('frontend.maintenance') == 'on') {
                    if (auth()->user()->group != 'admin') {
                        return redirect('/')->with(Auth::logout());
                    }

                } else {

                    $request->session()->regenerate();

                    return redirect()->intended(RouteServiceProvider::HOME);
                }
            }
        }

    }

    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $information_rows = ['title', 'author', 'keywords', 'description', 'css', 'js'];
        $information = [];
        $settings = Setting::all();

        foreach ($settings as $row) {
            if (in_array($row['name'], $information_rows)) {
                $information[$row['name']] = $row['value'];
            }
        }

        return view('frontend.login', compact('information'));
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param \App\Http\Requests\Auth\LoginRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LoginRequest $request)
    {
        if (config('services.google.recaptcha.enable') == 'on') {

            $recaptchaResult = $this->reCaptchaCheck(request('recaptcha'));

            if ($recaptchaResult->success != true) {
                return redirect()->back()->with('error', __('Google reCaptcha Validation has Failed'));
            }

            if ($recaptchaResult->score >= 0.5) {

                $request->authenticate();

                if (auth()->user()->hasRole('admin')) {

                    return redirect('/')->with(Auth::logout());

                    $request->session()->regenerate();

                    return redirect()->route('admin.dashboard');
                }

                if (config('frontend.maintenance') == 'on') {
                    if (auth()->user()->group != 'admin') {
                        return redirect('/')->with(Auth::logout());
                    }

                } else {

                    $request->session()->regenerate();

                    return redirect()->intended(RouteServiceProvider::HOME);
                }


            } else {
                return redirect()->back()->with('error', __('Google reCaptcha Validation has Failed'));
            }

        } else {

            $request->authenticate();
            if (auth()->user()->hasRole('admin')) {
                return redirect('/')->with(Auth::logout());
            }
            if (auth()->user()->google2fa_enabled == true) {
                $google2fa = app('pragmarx.google2fa');


                $registration_data = $request->all();

                $registration_data["google2fa_secret"] = $google2fa->generateSecretKey();

                // Here, instead of flashing the data to the session, we will save it to the database
                $user = auth()->user();
                if (!auth()->user()->google2fa_secret) {

                    $user->google2fa_secret = encrypt($registration_data['google2fa_secret']);
                    $user->save();
                    $request->session()->flash('registration_data', $registration_data);

                    $QR_Image = $google2fa->getQRCodeInline(
                        config('app.name'),
                        $registration_data['email'],
                        $registration_data['google2fa_secret']
                    );

                    return view('auth.google2fa.register', ['QR_Image' => $QR_Image, 'secret' => $registration_data['google2fa_secret']]);
                } else {
                    return view('auth.2fa');
                }


            } else {
                if (auth()->user()->hasRole('admin')) {
                    $request->session()->regenerate();

                    return redirect()->route('admin.dashboard');
                }

                if (config('frontend.maintenance') == 'on') {
                    if (auth()->user()->group != 'admin') {
                        return redirect('/')->with(Auth::logout());
                    }

                } else {

                    $request->session()->regenerate();

                    return redirect()->intended(RouteServiceProvider::HOME);
                }
            }
        }

    }

    public function verify_2fa_code(Request $request)
    {
        return view('auth.2fa');
    }

    /**
     * Handle an incoming 2FA authentication request.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function twoFactorAuthentication(Request $request)
    {
        $google2fa = app('pragmarx.google2fa');

        // If the secret key is encrypted, use the decrypt() function
        $secret = decrypt(auth()->user()->google2fa_secret);

        // Verify the OTP
        $valid = $google2fa->verifyKey($secret, $request->one_time_password, 4);
        if ($valid) {
            $request->session()->put('2fa_passed', true); // Add this line

            if (auth()->user()->hasRole('admin')) {
                $request->session()->regenerate();

                return redirect()->route('admin.dashboard');
            }
            if (config('frontend.maintenance') == 'on') {
                if (auth()->user()->group != 'admin') {
                    return redirect('/')->with(Auth::logout());
                }

            } else {

                $request->session()->regenerate();

                return redirect()->intended(RouteServiceProvider::HOME);
            }
        } else {
            return view('auth.2fa');

//            return redirect()->route('complete.registration')->with('error', 'Google reCaptcha Validation has Failed');
        }

    }

    /**
     * Destroy an authenticated session.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }


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

}
