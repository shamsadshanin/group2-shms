<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DoctorMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Check if user has doctor role or doctor profile
        $user = Auth::user();
        if (!$user->doctor) {
            return redirect()->route('home')->with('error', 'Access denied. Doctor role required.');
        }

        // Check if doctor is active
        if (!$user->doctor->IsActive) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Your account has been deactivated. Please contact administration.');
        }

        return $next($request);
    }
}
