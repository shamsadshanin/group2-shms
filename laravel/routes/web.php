<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Default homepage (redirects to login)
Route::get('/', function () {
    return view('auth.login');
});

// Laravel's default auth routes (login, register, etc.)
Auth::routes();

// A 'home' route after login (we can change this later)
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


/*
|--------------------------------------------------------------------------
| ROLE-BASED UI ROUTES
|--------------------------------------------------------------------------
| These are the pages for your UI deliverable.
*/

// Group all routes that need a user to be logged in
Route::middleware(['auth'])->group(function () {

    // == PATIENT (Kazi Ismat Nahar Epthi) ==
    Route::prefix('patient')->name('patient.')->group(function () {
        Route::get('/dashboard', function() {
            // Later, you will get data from the database
            // For now, $dummy_data = [...]
            return view('patient.dashboard');
        })->name('dashboard');

        // Add more patient routes here
        // Route::get('/appointments', [PatientController::class, 'appointments'])->name('appointments');
    });

    use App\Http\Controllers\DoctorController;
    use Illuminate\Support\Facades\Route;

    // Doctor Routes
    Route::middleware(['auth', 'doctor'])->prefix('doctor')->name('doctor.')->group(function () {
        Route::get('/dashboard', [DoctorController::class, 'dashboard'])->name('dashboard');
        Route::post('/appointment/{id}/status', [DoctorController::class, 'updateAppointmentStatus'])->name('appointment.status');
        Route::post('/prescription', [DoctorController::class, 'storePrescription'])->name('prescription.store');
        Route::get('/patient/{id}/history', [DoctorController::class, 'patientHistory'])->name('patient.history');
        Route::post('/availability', [DoctorController::class, 'updateAvailability'])->name('availability.update');
    });

    // == ADMINISTRATOR (Arti Moni) ==
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', function() {
            return view('admin.dashboard');
        })->name('dashboard');
    });

    // == LAB TECHNICIAN (Nowshin Nawar) ==
    Route::prefix('lab')->name('lab.')->group(function () {
        Route::get('/dashboard', function() {
            return view('lab.dashboard');
        })->name('dashboard');
    });

    // == PHARMACIST (Md. Mustain Bellah) ==
    Route::prefix('pharmacy')->name('pharmacy.')->group(function () {
        Route::get('/dashboard', function() {
            return view('pharmacy.dashboard');
        })->name('dashboard');
    });

    // == RECEPTIONIST (MD ABDUS SADIK) ==
    Route::prefix('reception')->name('reception.')->group(function () {
        Route::get('/dashboard', function() {
            return view('reception.dashboard');
        })->name('dashboard');
    });

});
