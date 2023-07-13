<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckProfileCompletion
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse) $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        // Check if the user is logged in and their profile is not complete
        if ($user && $user->hasRole('user') && !$this->isProfileComplete($user)) {
            // Redirect to the profile update page with a message
            return redirect()->route('user.dashboard.edit')->with('warning', 'Please complete your profile before accessing other pages.');
        }

        return $next($request);
    }

    private function isProfileComplete($user)
    {
        if ($user->country === 'China') {
            return $user->phone_number !== null && $user->language !== null && $user->address !== null && $user->city !== null && $user->postal_code !== null;
        } else {
            return $user->phone_number !== null && $user->phone_number_verified_at !== null && $user->language !== null && $user->address !== null && $user->city !== null && $user->postal_code !== null && $user->country !== null;
        }
    }
}
