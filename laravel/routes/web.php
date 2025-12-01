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
});
