<?php

namespace App\Http\Controllers;

use App\Models\User;
use Twilio\Rest\Client;
use Illuminate\Http\Request;
use function GuzzleHttp\Promise\all;

class PhoneNumberVerificationController extends Controller
{
    public function verify(Request $request)
    {
        if ( auth()->user()->phone_number == null) {
            return redirect()->route('user.dashboard')->with('success', __('Please save your phone number first'));

        }
        $accountSid = 'AC1b9ba2a4ec563cff31ffdbc7ff678583';
        $authToken = '4bd8ea4c0bbb20a563487d6126bae273';
        try {


        // Create a new Twilio client object
        $client = new Client($accountSid, $authToken);

        // Generate a random verification code (e.g. using PHP's random_int function)
        $verificationCode = random_int(100000, 999999);

        // Send an SMS message to the user's phone number with the verification code
        $message = $client->messages->create(
            auth()->user()->phone_number, // Replace with the user's phone number
            array(
                'from' => '+12706122949',
                'body' => 'Your verification code is: ' . $verificationCode
            )
        );
        // Store the verification code in the session or database for later verification
        $user = User::where('id', auth()->user()->id)->update([
            'verification_code' => $verificationCode,
        ]);
            return view('auth.verify-phone-number');

        } catch (\Exception $e){
            return redirect()->route('user.dashboard')->with('error', __('Something went wrong, please try again later'));

        }

    }

    public function verifyNumber()
    {
        return view('auth.verify-phone-number');

    }

    public function verifyNumberCode(Request $request)
    {
        $user = User::where('id', auth()->user()->id)->first();
        if ($user->verification_code == $request->verification_code) {
            $user = User::where('id', auth()->user()->id)->update([
                'phone_number_verified_at' => now(),
            ]);
            return redirect()->route('user.dashboard')->with('success', __('Phone number was successfully verified'));

        } else {
            return redirect()->route('user.dashboard.edit', compact('user'))->with('error', __('Phone number was not successfully verified'));

        }
    }
}
