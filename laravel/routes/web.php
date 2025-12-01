<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

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
