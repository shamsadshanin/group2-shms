<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\LabController;

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



    // Doctor Routes
    Route::middleware(['auth', 'doctor'])->prefix('doctor')->name('doctor.')->group(function () {
        Route::get('/dashboard', [DoctorController::class, 'dashboard'])->name('dashboard');
        Route::post('/appointment/{id}/status', [DoctorController::class, 'updateAppointmentStatus'])->name('appointment.status');
        Route::post('/prescription', [DoctorController::class, 'storePrescription'])->name('prescription.store');
        Route::get('/patient/{id}/history', [DoctorController::class, 'patientHistory'])->name('patient.history');
        Route::post('/availability', [DoctorController::class, 'updateAvailability'])->name('availability.update');
    });

    // Admin Routes
    Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
        Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('users.destroy');
        Route::get('/financials', [AdminController::class, 'financials'])->name('financials');
        Route::post('/reports/generate', [AdminController::class, 'generateReport'])->name('reports.generate');

    // Pharmacy Routes
    Route::middleware(['auth', 'pharmacy'])->prefix('pharmacy')->name('pharmacy.')->group(function () {
        Route::get('/dashboard', [PharmacyController::class, 'dashboard'])->name('dashboard');
        Route::post('/dispense', [PharmacyController::class, 'dispense'])->name('dispense');
        Route::get('/inventory', [PharmacyController::class, 'inventory'])->name('inventory');
        Route::post('/medicine', [PharmacyController::class, 'storeMedicine'])->name('medicine.store');
        Route::put('/medicine/{medicine}', [PharmacyController::class, 'updateMedicine'])->name('medicine.update');
        Route::post('/medicine/{medicine}/stock', [PharmacyController::class, 'updateStock'])->name('medicine.stock.update');
        Route::get('/history', [PharmacyController::class, 'dispensingHistory'])->name('history');
        Route::get('/prescription/{id}/details', [PharmacyController::class, 'getPrescriptionDetails'])->name('prescription.details');
    });

    // == ADMINISTRATOR (Arti Moni) ==
        Route::prefix('admin')->name('admin.')->group(function () {
            Route::get('/dashboard', function() {
                return view('admin.dashboard');
            })->name('dashboard');
        });

        // Lab Technician Routes
        Route::middleware(['auth', 'lab.technician'])->prefix('lab')->name('lab.')->group(function () {
            Route::get('/dashboard', [LabController::class, 'dashboard'])->name('dashboard');
            Route::post('/investigation/{id}', [LabController::class, 'updateInvestigation'])->name('investigation.update');
            Route::post('/investigation/{id}/assign', [LabController::class, 'assignToMe'])->name('investigation.assign');
            Route::get('/history', [LabController::class, 'investigationHistory'])->name('history');
            Route::get('/report/{id}/download', [LabController::class, 'downloadReport'])->name('report.download');
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


