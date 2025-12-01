<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Check if user has admin role
        $user = Auth::user();
        if (!$user->isAdmin()) {
            return redirect()->route('home')->with('error', 'Access denied. Administrator role required.');
        }

        // Check if admin is active
        if (!$user->is_active) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Your account has been deactivated. Please contact system administrator.');
        }

        return $next($request);
    }
}
