<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PharmacyMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Check if user has pharmacy role
        $user = Auth::user();
        if (!$user->isPharmacy()) {
            return redirect()->route('home')->with('error', 'Access denied. Pharmacy role required.');
        }

        // Check if pharmacy user is active
        if (!$user->is_active) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Your account has been deactivated. Please contact administration.');
        }

        return $next($request);
    }
}
