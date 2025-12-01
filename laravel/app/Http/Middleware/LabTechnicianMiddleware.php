<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LabTechnicianMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Check if user has lab technician role or lab technician profile
        $user = Auth::user();
        if (!$user->labTechnician) {
            return redirect()->route('home')->with('error', 'Access denied. Lab Technician role required.');
        }

        // Check if lab technician is active
        if (!$user->labTechnician->IsActive) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Your account has been deactivated. Please contact administration.');
        }

        return $next($request);
    }
}
