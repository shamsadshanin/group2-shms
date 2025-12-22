<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReceptionController;
use App\Http\Controllers\LabTechnicianController;
use App\Http\Controllers\PharmacyController;
use App\Http\Controllers\LandingPageController;

// Landing page route
Route::get('/', [LandingPageController::class, 'index'])->name('landing');

// Authentication routes
Auth::routes(['verify' => true]);

// Custom Authentication routes
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login'])->name('login.submit');
Route::get('register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [AuthController::class, 'register'])->name('register.submit');
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

// Dashboard dispatcher route
Route::get('/dashboard', [HomeController::class, 'redirectToDashboard'])->name('dashboard')->middleware('auth');

Route::get('/home', [HomeController::class, 'index'])->name('home');

// Patient routes
Route::middleware(['auth', 'role:patient'])->prefix('patient')->name('patient.')->group(function () {
    Route::get('dashboard', [PatientController::class, 'dashboard'])->name('dashboard');
    Route::get('book-appointment', [PatientController::class, 'bookAppointment'])->name('book-appointment');
    Route::post('store-appointment', [PatientController::class, 'storeAppointment'])->name('store-appointment');
    Route::get('medical-history', [PatientController::class, 'medicalHistory'])->name('medical-history');
    Route::get('prescriptions', [PatientController::class, 'prescriptions'])->name('prescriptions');
    Route::get('billing', [PatientController::class, 'billing'])->name('billing');
    Route::post('pay-bill/{cBillingID}', [PatientController::class, 'payBill'])->name('pay-bill');
    Route::get('symptom-checker', [PatientController::class, 'symptomChecker'])->name('symptom-checker');
    Route::post('check-symptoms', [PatientController::class, 'checkSymptoms'])->name('check-symptoms');
});

// Doctor routes
Route::middleware(['auth', 'role:doctor'])->prefix('doctor')->name('doctor.')->group(function () {
    Route::get('dashboard', [DoctorController::class, 'dashboard'])->name('dashboard');

    // ... Existing Appointment & Prescription Routes ...
    Route::get('appointments', [DoctorController::class, 'appointments'])->name('appointments');
    Route::get('appointments/create', [DoctorController::class, 'createAppointment'])->name('appointments.create');
    Route::post('appointments', [DoctorController::class, 'storeAppointment'])->name('appointments.store');

    Route::get('prescriptions', [DoctorController::class, 'prescriptions'])->name('prescriptions');
    Route::get('prescriptions/create', [DoctorController::class, 'createPrescription'])->name('prescriptions.create');
    Route::post('prescriptions', [DoctorController::class, 'storePrescription'])->name('prescriptions.store');
    Route::get('prescriptions/{id}', [DoctorController::class, 'showPrescription'])->name('prescriptions.show');

    // Patient History & Editing
    Route::get('patients/{patient}/history', [DoctorController::class, 'patientHistory'])->name('patients.history');
    Route::get('patients/{patient}/edit', [DoctorController::class, 'editPatient'])->name('patients.edit');
    Route::put('patients/{patient}', [DoctorController::class, 'updatePatient'])->name('patients.update');

    // Medical Records
    Route::get('patients/{patient}/medical-record/create', [DoctorController::class, 'createMedicalRecord'])->name('medical-records.create');
    Route::post('patients/{patient}/medical-record', [DoctorController::class, 'storeMedicalRecord'])->name('medical-records.store');
});

// Admin routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Doctor management
    Route::get('doctors', [AdminController::class, 'doctors'])->name('doctors.index');
    Route::get('doctors/create', [AdminController::class, 'createDoctor'])->name('doctors.create');
    Route::post('doctors', [AdminController::class, 'storeDoctor'])->name('doctors.store');
    Route::get('doctors/{id}/edit', [AdminController::class, 'editDoctor'])->name('doctors.edit');
    Route::put('doctors/{id}', [AdminController::class, 'updateDoctor'])->name('doctors.update');
    Route::delete('doctors/{id}', [AdminController::class, 'destroyDoctor'])->name('doctors.destroy');

    // Patient management
    Route::get('patients', [AdminController::class, 'patients'])->name('patients.index');
    Route::get('patients/create', [AdminController::class, 'createPatient'])->name('patients.create');
    Route::post('patients', [AdminController::class, 'storePatient'])->name('patients.store');
    Route::get('patients/{id}/edit', [AdminController::class, 'editPatient'])->name('patients.edit');
    Route::put('patients/{id}', [AdminController::class, 'updatePatient'])->name('patients.update');
    Route::delete('patients/{id}', [AdminController::class, 'destroyPatient'])->name('patients.destroy');
    // Appointments Page
    Route::get('appointments', [PatientController::class, 'appointments'])->name('appointments');

    // API Routes for AJAX (View & Cancel)
    Route::get('api/appointments/{id}', [PatientController::class, 'showAppointmentApi']);
    Route::post('api/appointments/{id}/cancel', [PatientController::class, 'cancelAppointmentApi']);

    // Appointment management
    Route::get('appointments', [AdminController::class, 'appointments'])->name('appointments.index');
    Route::get('appointments/create', [AdminController::class, 'createAppointment'])->name('appointments.create');
    Route::post('appointments', [AdminController::class, 'storeAppointment'])->name('appointments.store');
    Route::get('appointments/{id}/edit', [AdminController::class, 'editAppointment'])->name('appointments.edit');
    Route::put('appointments/{id}', [AdminController::class, 'updateAppointment'])->name('appointments.update');
    Route::delete('appointments/{id}', [AdminController::class, 'destroyAppointment'])->name('appointments.destroy');

    // Billing management
    Route::get('billing', [AdminController::class, 'billing'])->name('billing.index');
    Route::get('billing/create', [AdminController::class, 'createBilling'])->name('billing.create');
    Route::post('billing', [AdminController::class, 'storeBilling'])->name('billing.store');
    Route::get('billing/{billing}/edit', [AdminController::class, 'editBilling'])->name('billing.edit');
    Route::put('billing/{billing}', [AdminController::class, 'updateBilling'])->name('billing.update');
    Route::delete('billing/{billing}', [AdminController::class, 'destroyBilling'])->name('billing.destroy');

    // User management
    Route::get('users', [AdminController::class, 'users'])->name('users.index');
    Route::get('users/create', [AdminController::class, 'createUser'])->name('users.create');
    Route::post('users', [AdminController::class, 'storeUser'])->name('users.store');
    Route::get('users/{id}/edit', [AdminController::class, 'editUser'])->name('users.edit');
    Route::put('users/{id}', [AdminController::class, 'updateUser'])->name('users.update');
    Route::delete('users/{id}', [AdminController::class, 'destroyUser'])->name('users.destroy');

    Route::get('analytics', [AdminController::class, 'analytics'])->name('analytics');
    Route::get('reports', [AdminController::class, 'reports'])->name('reports');
    Route::post('reports', [AdminController::class, 'reports'])->name('reports.post');
});

// Reception routes
Route::middleware(['auth', 'role:reception'])->prefix('reception')->name('reception.')->group(function () {
    Route::get('dashboard', [ReceptionController::class, 'dashboard'])->name('dashboard');
    Route::patch('appointments/{appointment}/check-in', [ReceptionController::class, 'checkIn'])->name('appointments.check-in');
    Route::get('appointments', [ReceptionController::class, 'appointments'])->name('appointments');
    Route::get('patients', [ReceptionController::class, 'patients'])->name('patients');
    Route::get('patients/create', [ReceptionController::class, 'createPatient'])->name('patients.create');
    Route::post('patients', [ReceptionController::class, 'storePatient'])->name('patients.store');
});

// Lab Technician routes
Route::middleware(['auth', 'role:lab'])->prefix('lab')->name('lab.')->group(function () {
    Route::get('dashboard', [LabTechnicianController::class, 'dashboard'])->name('dashboard');
    Route::get('tests', [LabTechnicianController::class, 'tests'])->name('tests');
    Route::get('tests/create', [LabTechnicianController::class, 'create'])->name('tests.create');
    Route::post('tests', [LabTechnicianController::class, 'store'])->name('tests.store');
    Route::get('tests/{cLabTestID}', [LabTechnicianController::class, 'show'])->name('tests.show');
    // In web.php under lab routes
    Route::patch('tests/{id}/accept', [LabTechnicianController::class, 'acceptTest'])->name('tests.accept');
});

// Pharmacy routes
Route::middleware(['auth', 'role:pharmacy'])->prefix('pharmacy')->name('pharmacy.')->group(function () {
    Route::get('dashboard', [PharmacyController::class, 'dashboard'])->name('dashboard');
    Route::get('prescriptions', [PharmacyController::class, 'prescriptions'])->name('prescriptions');
    Route::get('prescriptions/create', [PharmacyController::class, 'createPrescription'])->name('prescriptions.create');
    Route::post('prescriptions', [PharmacyController::class, 'storePrescription'])->name('prescriptions.store');
    Route::get('prescriptions/{prescription}', [PharmacyController::class, 'prescriptionDetail'])->name('prescription-detail');
    Route::patch('prescriptions/{prescription}/dispensed', [PharmacyController::class, 'markAsDispensed'])->name('mark-as-dispensed');
    Route::patch('prescriptions/{prescription}/collected', [PharmacyController::class, 'markAsCollected'])->name('mark-as-collected');
});


// Profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
