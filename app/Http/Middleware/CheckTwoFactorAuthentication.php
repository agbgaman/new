<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckTwoFactorAuthentication
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // Check if 2FA is enabled for the user
        if($user->google2fa_enabled) {
            // Check if 2FA has been passed in this session

            if (!session('2fa_passed', false)) {
                // If not, redirect to 2FA page
                return redirect()->route('complete.registration');
            }
        }

        return $next($request);
    }
}
