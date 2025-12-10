<?php

use App\Http\Controllers\PatientController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\LabTechnicianController;
use App\Http\Controllers\PharmacyController;
use App\Http\Controllers\ReceptionistController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

// API routes for mobile app integration

// Patient API routes
Route::prefix('patient')->middleware(['auth:api', 'role:patient'])->group(function () {
    Route::get('/appointments', [PatientController::class, 'apiAppointments']);
    Route::get('/medical-history', [PatientController::class, 'apiMedicalHistory']);
    Route::get('/prescriptions', [PatientController::class, 'apiPrescriptions']);
    Route::get('/billing', [PatientController::class, 'apiBilling']);
    Route::post('/book-appointment', [PatientController::class, 'apiBookAppointment']);
    Route::post('/check-symptoms', [PatientController::class, 'apiCheckSymptoms']);
});

// Doctor API routes
Route::prefix('doctor')->middleware(['auth:api', 'role:doctor'])->group(function () {
    Route::get('/appointments', [DoctorController::class, 'apiAppointments']);
    Route::get('/patient-history/{cPatientID}', [DoctorController::class, 'apiPatientHistory']);
    Route::get('/prescriptions', [DoctorController::class, 'apiPrescriptions']);
    Route::post('/create-prescription', [DoctorController::class, 'apiCreatePrescription']);
    Route::post('/request-lab-test', [DoctorController::class, 'apiRequestLabTest']);
});

// Lab Technician API routes
Route::prefix('lab')->middleware(['auth:api', 'role:lab'])->group(function () {
    Route::get('/tests', [LabTechnicianController::class, 'apiTests']);
    Route::get('/test/{cTestID}', [LabTechnicianController::class, 'apiTestDetail']);
    Route::post('/update-status/{cTestID}', [LabTechnicianController::class, 'apiUpdateTestStatus']);
    Route::post('/upload-report/{cTestID}', [LabTechnicianController::class, 'apiUploadReport']);
});

// Pharmacy API routes
Route::prefix('pharmacy')->middleware(['auth:api', 'role:pharmacy'])->group(function () {
    Route::get('/prescriptions', [PharmacyController::class, 'apiPrescriptions']);
    Route::get('/prescription/{cPrescriptionID}', [PharmacyController::class, 'apiPrescriptionDetail']);
    Route::post('/mark-dispensed/{cPrescriptionID}', [PharmacyController::class, 'apiMarkDispensed']);
    Route::post('/mark-collected/{cPrescriptionID}', [PharmacyController::class, 'apiMarkCollected']);
});

// Receptionist API routes
Route::prefix('reception')->middleware(['auth:api', 'role:reception'])->group(function () {
    Route::get('/appointments', [ReceptionistController::class, 'apiAppointments']);
    Route::get('/patients', [ReceptionistController::class, 'apiPatients']);
    Route::post('/book-appointment', [ReceptionistController::class, 'apiBookAppointment']);
    Route::post('/create-patient', [ReceptionistController::class, 'apiCreatePatient']);
    Route::post('/create-billing', [ReceptionistController::class, 'apiCreateBilling']);
});

// Admin API routes
Route::prefix('admin')->middleware(['auth:api', 'role:admin'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'apiDashboard']);
    Route::get('/doctors', [AdminController::class, 'apiDoctors']);
    Route::get('/patients', [AdminController::class, 'apiPatients']);
    Route::get('/appointments', [AdminController::class, 'apiAppointments']);
    Route::get('/billing', [AdminController::class, 'apiBilling']);
    Route::get('/analytics', [AdminController::class, 'apiAnalytics']);
});

// Public API routes (no authentication required)
Route::prefix('public')->group(function () {
    Route::get('/doctors', [AdminController::class, 'apiPublicDoctors']);
    Route::get('/specializations', [AdminController::class, 'apiSpecializations']);
});
