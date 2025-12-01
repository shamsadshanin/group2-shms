<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\LabController;
use App\Http\Controllers\PharmacyController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Default homepage → login page
Route::get('/', function () {
    return view('auth.login');
});

// Laravel default auth (login, register, logout, etc.)
Auth::routes();

// Home after login
Route::get('/home', [HomeController::class, 'index'])->name('home');


/*
|--------------------------------------------------------------------------
| AUTHENTICATED ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | PATIENT ROUTES
    |--------------------------------------------------------------------------
    */
    Route::prefix('patient')->name('patient.')->group(function () {

        Route::get('/dashboard', function () {
            return view('patient.dashboard');
        })->name('dashboard');

        // Add more patient routes later...
        // Route::get('/appointments', ... );
    });



    /*
    |--------------------------------------------------------------------------
    | DOCTOR ROUTES
    |--------------------------------------------------------------------------
    */
    Route::middleware(['doctor'])
        ->prefix('doctor')
        ->name('doctor.')
        ->group(function () {

        Route::get('/dashboard', [DoctorController::class, 'dashboard'])->name('dashboard');
        Route::post('/appointment/{id}/status', [DoctorController::class, 'updateAppointmentStatus'])->name('appointment.status');
        Route::post('/prescription', [DoctorController::class, 'storePrescription'])->name('prescription.store');
        Route::get('/patient/{id}/history', [DoctorController::class, 'patientHistory'])->name('patient.history');
        Route::post('/availability', [DoctorController::class, 'updateAvailability'])->name('availability.update');
    });



    /*
    |--------------------------------------------------------------------------
    | ADMIN ROUTES (Controller)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['admin'])
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {

        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
        Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('users.destroy');
        Route::get('/financials', [AdminController::class, 'financials'])->name('financials');
        Route::post('/reports/generate', [AdminController::class, 'generateReport'])->name('reports.generate');
    });



    /*
    |--------------------------------------------------------------------------
    | PHARMACY ROUTES
    |--------------------------------------------------------------------------
    */
    Route::middleware(['pharmacy'])
        ->prefix('pharmacy')
        ->name('pharmacy.')
        ->group(function () {

        Route::get('/dashboard', [PharmacyController::class, 'dashboard'])->name('dashboard');
        Route::post('/dispense', [PharmacyController::class, 'dispense'])->name('dispense');
        Route::get('/inventory', [PharmacyController::class, 'inventory'])->name('inventory');
        Route::post('/medicine', [PharmacyController::class, 'storeMedicine'])->name('medicine.store');
        Route::put('/medicine/{medicine}', [PharmacyController::class, 'updateMedicine'])->name('medicine.update');
        Route::post('/medicine/{medicine}/stock', [PharmacyController::class, 'updateStock'])->name('medicine.stock.update');
        Route::get('/history', [PharmacyController::class, 'dispensingHistory'])->name('history');
        Route::get('/prescription/{id}/details', [PharmacyController::class, 'getPrescriptionDetails'])->name('prescription.details');
    });



    /*
    |--------------------------------------------------------------------------
    | LAB TECHNICIAN ROUTES
    |--------------------------------------------------------------------------
    */
    Route::middleware(['auth', 'lab.technician'])->prefix('lab')->name('lab.')->group(function () {
        Route::get('/dashboard', [LabController::class, 'dashboard'])->name('dashboard');
        Route::get('/investigations', [LabController::class, 'investigations'])->name('investigations');
        Route::post('/investigation/{id}', [LabController::class, 'updateInvestigation'])->name('investigation.update');
        Route::post('/investigation/{id}/assign', [LabController::class, 'assignToMe'])->name('investigation.assign');
        Route::get('/history', [LabController::class, 'investigationHistory'])->name('history');
        Route::get('/report/{id}/download', [LabController::class, 'downloadReport'])->name('report.download');
    });



    /*
    |--------------------------------------------------------------------------
    | RECEPTIONIST ROUTES
    |--------------------------------------------------------------------------
    */
    Route::prefix('reception')->name('reception.')->group(function () {

        Route::get('/dashboard', function () {
            return view('reception.dashboard');
        })->name('dashboard');
    });

});
