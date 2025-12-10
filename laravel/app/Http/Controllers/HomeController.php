<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Redirect the user to the appropriate dashboard based on their role.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirectToDashboard(): RedirectResponse
    {
        $role = Auth::user()->role;

        switch ($role) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'doctor':
                return redirect()->route('doctor.dashboard');
            case 'patient':
                return redirect()->route('patient.dashboard');
            case 'reception':
                return redirect()->route('reception.dashboard');
            case 'lab':
                return redirect()->route('lab.dashboard');
            case 'pharmacy':
                return redirect()->route('pharmacy.dashboard');
            default:
                // Fallback for any other roles or if role is not set
                return redirect('/'); 
        }
    }
    
    /**
     * Show the application home page.
     *
     * This is a fallback and can be customized or removed.
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // You can keep a generic home view or redirect as well.
        // For now, it will also redirect to the role-based dashboard.
        return $this->redirectToDashboard();
    }
}
