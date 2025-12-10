<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Appointment;
use App\Models\Billing;
use App\Models\LabTechnician;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        // Growth percentages
        $patientGrowth = $this->calculateGrowth(User::class, null, 'patient');
        $doctorGrowth = $this->calculateGrowth(User::class, null, 'doctor');
        $revenueGrowth = $this->calculateGrowth(Billing::class, 'fAmount', null, 'dBillingDate');
        $appointmentGrowth = $this->calculateGrowth(Appointment::class, null, null, 'dAppointmentDateTime');

        $totalPatients = User::where('role', 'patient')->count();
        $activeDoctors = User::where('role', 'doctor')->count();
        $totalRevenue = Billing::where('cStatus', 'Paid')->sum('fAmount');
        $todayAppointments = Appointment::whereDate('dAppointmentDateTime', Carbon::today())->count();
        $pendingAppointments = Appointment::where('cStatus', 'Scheduled')->count();
        $totalAppointments = Appointment::count();

        $departmentStats = Doctor::select('cSpecialization as name')
            ->withCount('appointments')
            ->get()
            ->map(function ($doctor) {
                $doctor->performance = rand(70, 95); // Mock performance data for now
                return $doctor;
            });

        $recentActivities = Appointment::with(['patient', 'doctor'])
            ->latest('dAppointmentDateTime')
            ->take(5)
            ->get()
            ->map(function ($appointment) {
                return (object)[
                    'icon' => 'calendar-check',
                    'description' => 'Appointment for ' . optional($appointment->patient)->cName . ' with Dr. ' . optional($appointment->doctor)->cName,
                    'time' => $appointment->dAppointmentDateTime->diffForHumans(),
                ];
            });

        // Data for Revenue Chart
        $revenueData = Billing::selectRaw('DATE(dBillingDate) as date, SUM(fAmount) as total')
            ->where('dBillingDate', ' > ', Carbon::now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        $revenueChartData = [
            'labels' => $revenueData->pluck('date')->map(function ($date) {
                return Carbon::parse($date)->format('M d');
            }),
            'data' => $revenueData->pluck('total'),
        ];

        // Data for Patient Demographics Chart
        $demographicsData = Patient::select('cGender')
            ->selectRaw('count(*) as count')
            ->groupBy('cGender')
            ->get();

        $demographicsChartData = [
            'labels' => $demographicsData->pluck('cGender'),
            'data' => $demographicsData->pluck('count'),
        ];

        return view('admin.dashboard', compact(
            'totalPatients',
            'patientGrowth',
            'activeDoctors',
            'doctorGrowth',
            'totalRevenue',
            'revenueGrowth',
            'todayAppointments',
            'pendingAppointments',
            'departmentStats',
            'recentActivities',
            'totalAppointments',
            'appointmentGrowth',
            'revenueChartData',
            'demographicsChartData'
        ));
    }

    private function calculateGrowth($model, $field = null, $role = null, $dateColumn = 'created_at')
    {
        $query = $model::query();

        if ($role) {
            $query->where('role', $role);
        }

        $now = Carbon::now();
        $currentMonthStart = $now->copy()->startOfMonth();
        $previousMonthStart = $now->copy()->subMonthNoOverflow()->startOfMonth();
        $previousMonthEnd = $previousMonthStart->copy()->endOfMonth();

        $currentMonthQuery = (clone $query)->whereBetween($dateColumn, [$currentMonthStart, $now]);
        $previousMonthQuery = (clone $query)->whereBetween($dateColumn, [$previousMonthStart, $previousMonthEnd]);

        $currentMonthValue = $field ? $currentMonthQuery->sum($field) : $currentMonthQuery->count();
        $previousMonthValue = $field ? $previousMonthQuery->sum($field) : $previousMonthQuery->count();

        if ($previousMonthValue == 0) {
            return $currentMonthValue > 0 ? 100.0 : 0.0;
        }

        $growth = (($currentMonthValue - $previousMonthValue) / $previousMonthValue) * 100;

        return round($growth, 2);
    }
    
    // Doctor Management
    public function doctors()
    {
        $doctors = Doctor::all();
        return view('admin.doctors.index', compact('doctors'));
    }

    public function createDoctor()
    {
        return view('admin.doctors.create');
    }

    public function storeDoctor(Request $request)
    {
        $request->validate([
            'cDoctorID' => 'required|string|max:10|unique:tbldoctor,cDoctorID',
            'cName' => 'required|string|max:50',
            'cSpecialization' => 'required|string|max:50',
            'cEmail' => 'required|email|max:50|unique:tbldoctor,cEmail',
            'cContactNumber' => 'required|string|max:15',
            'cAvailability' => 'required|string|max:100',
        ]);

        Doctor::create($request->all());

        return redirect()->route('admin.doctors.index')->with('success', 'Doctor created successfully.');
    }

    public function editDoctor($id)
    {
        $doctor = Doctor::findOrFail($id);
        return view('admin.doctors.edit', compact('doctor'));
    }

    public function updateDoctor(Request $request, $id)
    {
        $request->validate([
            'cName' => 'required|string|max:50',
            'cSpecialization' => 'required|string|max:50',
            'cEmail' => 'required|email|max:50|unique:tbldoctor,cEmail,' . $id . ',cDoctorID',
            'cContactNumber' => 'required|string|max:15',
            'cAvailability' => 'required|string|max:100',
        ]);

        $doctor = Doctor::findOrFail($id);
        $doctor->update($request->all());

        return redirect()->route('admin.doctors.index')->with('success', 'Doctor updated successfully.');
    }

    public function destroyDoctor($id)
    {
        $doctor = Doctor::findOrFail($id);
        $doctor->delete();

        return redirect()->route('admin.doctors.index')->with('success', 'Doctor deleted successfully.');
    }

    // Patient Management
    public function patients()
    {
        $patients = Patient::all();
        return view('admin.patients.index', compact('patients'));
    }

    public function createPatient()
    {
        return view('admin.patients.create');
    }

    public function storePatient(Request $request)
    {
        $request->validate([
            'cPatientID' => 'required|string|max:10|unique:tblpatient,cPatientID',
            'cName' => 'required|string|max:50',
            'cEmail' => 'required|email|max:50|unique:tblpatient,cEmail',
            'cPhone' => 'required|string|max:15',
        ]);

        Patient::create($request->all());

        return redirect()->route('admin.patients.index')->with('success', 'Patient created successfully.');
    }

    public function editPatient($id)
    {
        $patient = Patient::findOrFail($id);
        return view('admin.patients.edit', compact('patient'));
    }

    public function updatePatient(Request $request, $id)
    {
        $request->validate([
            'cName' => 'required|string|max:50',
            'cEmail' => 'required|email|max:50|unique:tblpatient,cEmail,' . $id . ',cPatientID',
            'cPhone' => 'required|string|max:15',
        ]);

        $patient = Patient::findOrFail($id);
        $patient->update($request->all());

        return redirect()->route('admin.patients.index')->with('success', 'Patient updated successfully.');
    }

    public function destroyPatient($id)
    {
        $patient = Patient::findOrFail($id);
        $patient->delete();

        return redirect()->route('admin.patients.index')->with('success', 'Patient deleted successfully.');
    }

    // Appointment Management
    public function appointments()
    {
        $appointments = Appointment::with(['patient', 'doctor'])->get();
        return view('admin.appointments.index', compact('appointments'));
    }

    public function createAppointment()
    {
        $patients = Patient::all();
        $doctors = Doctor::all();
        return view('admin.appointments.create', compact('patients', 'doctors'));
    }

    public function storeAppointment(Request $request)
    {
        $request->validate([
            'cAppointmentID' => 'required|string|max:10|unique:tblappointment,cAppointmentID',
            'cPatientID' => 'required|string|exists:tblpatient,cPatientID',
            'cDoctorID' => 'required|string|exists:tbldoctor,cDoctorID',
            'dAppointmentDateTime' => 'required|date',
            'cPurpose' => 'required|string|max:255', // Added validation for cPurpose
            'cStatus' => 'required|string|max:20',
        ]);

        Appointment::create($request->all());

        return redirect()->route('admin.appointments.index')->with('success', 'Appointment created successfully.');
    }

    public function editAppointment($id)
    {
        $appointment = Appointment::findOrFail($id);
        $patients = Patient::all();
        $doctors = Doctor::all();
        return view('admin.appointments.edit', compact('appointment', 'patients', 'doctors'));
    }

    public function updateAppointment(Request $request, $id)
    {
        $request->validate([
            'cPatientID' => 'required|string|exists:tblpatient,cPatientID',
            'cDoctorID' => 'required|string|exists:tbldoctor,cDoctorID',
            'dAppointmentDateTime' => 'required|date',
            'cPurpose' => 'required|string|max:255', // Added validation for cPurpose
            'cStatus' => 'required|string|max:20',
        ]);

        $appointment = Appointment::findOrFail($id);
        $appointment->update($request->all());

        return redirect()->route('admin.appointments.index')->with('success', 'Appointment updated successfully.');
    }

    public function destroyAppointment($id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->delete();

        return redirect()->route('admin.appointments.index')->with('success', 'Appointment deleted successfully.');
    }

    // Billing Management
    public function billing()
    {
        $billings = Billing::with('patient')->get();
        return view('admin.billing.index', compact('billings'));
    }

    public function createBilling()
    {
        $patients = Patient::all();
        return view('admin.billing.create', compact('patients'));
    }

    public function storeBilling(Request $request)
    {
        $request->validate([
            'cBillingID' => 'required|string|max:10|unique:tblbilling,cBillingID',
            'cPatientID' => 'required|string|exists:tblpatient,cPatientID',
            'fAmount' => 'required|numeric',
            'dBillingDate' => 'required|date',
            'cStatus' => 'required|string|max:20',
        ]);

        Billing::create($request->all());

        return redirect()->route('admin.billing.index')->with('success', 'Billing record created successfully.');
    }

    public function editBilling(Billing $billing)
    {
        $patients = Patient::all();
        return view('admin.billing.edit', compact('billing', 'patients'));
    }

    public function updateBilling(Request $request, Billing $billing)
    {
        $request->validate([
            'cPatientID' => 'required|string|exists:tblpatient,cPatientID',
            'fAmount' => 'required|numeric',
            'dBillingDate' => 'required|date',
            'cStatus' => 'required|string|max:20',
        ]);

        $billing->update($request->all());

        return redirect()->route('admin.billing.index')->with('success', 'Billing record updated successfully.');
    }

    public function destroyBilling(Billing $billing)
    {
        $billing->delete();

        return redirect()->route('admin.billing.index')->with('success', 'Billing record deleted successfully.');
    }

    // User Management
    public function users()
    {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    public function createUser()
    {
        return view('admin.users.create');
    }

    public function storeUser(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string|in:admin,doctor,patient,lab,pharmacy,reception',
        ];

        if ($request->role === 'patient') {
            $rules['nAge'] = 'required|string';
            $rules['cGender'] = 'required|string|in:Male,Female,Other';
            $rules['cAddress'] = 'required|string|max:255';
            $rules['patient_cContactNumber'] = 'required|string|max:20';
        } elseif ($request->role === 'doctor') {
            $rules['cSpecialization'] = 'required|string|max:50';
            $rules['doctor_cContactNumber'] = 'required|string|max:15';
        } elseif ($request->role === 'lab') {
            $rules['lab_cContactNumber'] = 'required|string|max:15';
        }

        $request->validate($rules);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        if ($request->role === 'doctor') {
            Doctor::create([
                'cDoctorID' => 'DR' . str_pad($user->id, 5, '0', STR_PAD_LEFT),
                'cUserID' => $user->id,
                'cName' => $request->name,
                'cEmail' => $request->email,
                'cSpecialization' => $request->cSpecialization,
                'cContactNumber' => $request->doctor_cContactNumber,
                'cAvailability' => 'Not Available',
            ]);
        } elseif ($request->role === 'patient') {
            Patient::create([
                'cPatientID' => 'PT' . str_pad($user->id, 5, '0', STR_PAD_LEFT),
                'cUserID' => $user->id,
                'cName' => $request->name,
                'cEmail' => $request->email,
                'nAge' => $request->nAge,
                'cGender' => $request->cGender,
                'cAddress' => $request->cAddress,
                'cPhone' => $request->patient_cContactNumber,
            ]);
        } elseif ($request->role === 'lab') {
            LabTechnician::create([
                'cLabTechnicianID' => 'LT' . str_pad($user->id, 5, '0', STR_PAD_LEFT),
                'cUserID' => $user->id,
                'cName' => $request->name,
                'cEmail' => $request->email,
                'cContactNumber' => $request->lab_cContactNumber,
            ]);
        }

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    public function editUser($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|string|in:admin,doctor,patient,lab,pharmacy,reception',
        ]);

        $data = $request->except('password');
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function destroyUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }

    public function analytics()
    {
        // Placeholder for analytics data
        $analyticsData = [];
        return view('admin.analytics', compact('analyticsData'));
    }

    public function reports(Request $request)
    {
        $reportData = null;
        if ($request->isMethod('post')) {
            $request->validate([
                'report_type' => 'required|string',
                'date_range' => 'required|string',
            ]);

            list($startDate, $endDate) = explode(' to ', $request->date_range);
            $startDate = Carbon::parse($startDate)->startOfDay();
            $endDate = Carbon::parse($endDate)->endOfDay();

            switch ($request->report_type) {
                case 'patient_demographics':
                    $reportData = Patient::whereBetween('created_at', [$startDate, $endDate])
                        ->selectRaw('cGender, count(*) as count')
                        ->groupBy('cGender')
                        ->get();
                    break;
                case 'appointment_trends':
                    $reportData = Appointment::whereBetween('dAppointmentDateTime', [$startDate, $endDate])
                        ->selectRaw('DATE(dAppointmentDateTime) as date, count(*) as count')
                        ->groupBy('date')
                        ->orderBy('date', 'asc')
                        ->get();
                    break;
                case 'billing_summary':
                    $reportData = Billing::whereBetween('dBillingDate', [$startDate, $endDate])
                        ->selectRaw('cStatus, SUM(fAmount) as total, COUNT(*) as count')
                        ->groupBy('cStatus')
                        ->get();
                    break;
            }
        }

        return view('admin.reports', compact('reportData'));
    }
}
