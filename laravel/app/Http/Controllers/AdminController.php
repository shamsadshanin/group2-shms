<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Appointment;
use App\Models\Billing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminController extends Controller
{
    // ==========================================
    // Dashboard & Analytics
    // ==========================================

    public function dashboard()
    {
        // 1. Totals & Growth
        $totalPatients = Patient::count();
        $patientGrowth = $this->calculateGrowth(Patient::class);

        $activeDoctors = Doctor::count();

        // Revenue
        $totalRevenue = Billing::where('Payment_Status', 'Paid')->sum('Total_Amount');
        $revenueGrowth = $this->calculateGrowth(Billing::class, 'Total_Amount', 'IssueDate');

        // Appointments
        $totalAppointments = Appointment::count();
        $todayAppointments = Appointment::whereDate('Date', Carbon::today())->count();
        $pendingAppointments = Appointment::whereIn('Status', ['Scheduled', 'Pending'])->count();

        // 2. Revenue Chart Data
        $revenueQuery = Billing::selectRaw('DATE(IssueDate) as date, SUM(Total_Amount) as total')
            ->where('Payment_Status', 'Paid')
            ->where('IssueDate', '>=', Carbon::now()->subDays(6))
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        $revenueLabels = [];
        $revenueValues = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $record = $revenueQuery->firstWhere('date', $date);
            $revenueLabels[] = Carbon::parse($date)->format('M d');
            $revenueValues[] = $record ? $record->total : 0;
        }
        $revenueChartData = ['labels' => $revenueLabels, 'data' => $revenueValues];

        // 3. Demographics Chart Data
        $demographics = Patient::select('Gender', DB::raw('count(*) as count'))
            ->groupBy('Gender')
            ->get();
        $demographicsChartData = ['labels' => $demographics->pluck('Gender'), 'data' => $demographics->pluck('count')];

        // 4. Department Performance
        $deptStats = Appointment::join('Doctor', 'Appointment.DoctorID', '=', 'Doctor.DoctorID')
            ->select('Doctor.Specialization', DB::raw('count(*) as count'))
            ->groupBy('Doctor.Specialization')
            ->orderByDesc('count')
            ->take(5)
            ->get();

        $maxCount = $deptStats->max('count') ?: 1;
        $departmentStats = $deptStats->map(function ($item) use ($maxCount) {
            return [
                'name' => $item->Specialization,
                'count' => $item->count,
                'percentage' => round(($item->count / $maxCount) * 100),
            ];
        });

        // 5. Recent Activities
        $recentActivities = Appointment::with(['patient', 'doctor'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($app) {
                return (object) [
                    'icon' => 'calendar-check',
                    'description' => 'Appointment: ' . ($app->patient->First_Name ?? 'Guest') . ' with Dr. ' . ($app->doctor->Last_Name ?? 'Unknown'),
                    'time' => Carbon::parse($app->created_at)->diffForHumans(),
                ];
            });

        return view('admin.dashboard', compact(
            'totalPatients', 'patientGrowth', 'activeDoctors', 'totalRevenue', 'revenueGrowth',
            'todayAppointments', 'pendingAppointments', 'revenueChartData', 'demographicsChartData',
            'departmentStats', 'recentActivities'
        ));
    }

    private function calculateGrowth($modelClass, $sumColumn = null, $dateColumn = 'created_at')
    {
        $now = Carbon::now();
        $thisMonthStart = $now->copy()->startOfMonth();
        $lastMonthStart = $now->copy()->subMonth()->startOfMonth();
        $lastMonthEnd = $now->copy()->subMonth()->endOfMonth();

        $currentQuery = $modelClass::where($dateColumn, '>=', $thisMonthStart);
        $currentValue = $sumColumn ? $currentQuery->sum($sumColumn) : $currentQuery->count();

        $lastQuery = $modelClass::whereBetween($dateColumn, [$lastMonthStart, $lastMonthEnd]);
        $lastValue = $sumColumn ? $lastQuery->sum($sumColumn) : $lastQuery->count();

        if ($lastValue == 0) return $currentValue > 0 ? 100 : 0;
        return (($currentValue - $lastValue) / $lastValue) * 100;
    }

    // ==========================================
    // Analytics & Reports
    // ==========================================

    public function analytics()
    {
        $totalPatients = Patient::count();
        $totalAppointments = Appointment::count();
        $totalRevenue = Billing::where('Payment_Status', 'Paid')->sum('Total_Amount');
        $insuredCount = DB::table('Insured_Patient')->count();
        $nonInsuredCount = $totalPatients - $insuredCount;

        $analyticsData = [
            'patients' => number_format($totalPatients),
            'appointments' => number_format($totalAppointments),
            'revenue' => number_format($totalRevenue, 2),
            'insured' => number_format($insuredCount),
            'non_insured' => number_format($nonInsuredCount)
        ];

        return view('admin.analytics', compact('analyticsData'));
    }

    public function reports(Request $request)
    {
        $reportData = null;
        $reportType = null;
        $startDate = null;
        $endDate = null;
        $dateRange = null;

        if ($request->isMethod('post')) {
            $request->validate([
                'report_type' => 'required|in:billing_summary,appointment_trends,patient_demographics',
                'date_range' => 'required'
            ]);

            // Parse Date Range (Format: "YYYY-MM-DD to YYYY-MM-DD")
            $dates = explode(' to ', $request->date_range);
            $startDate = $dates[0];
            $endDate = count($dates) > 1 ? $dates[1] : $dates[0];

            $reportType = $request->report_type;
            $dateRange = $request->date_range;

            if ($reportType == 'billing_summary') {
                $reportData = Billing::whereBetween('IssueDate', [$startDate, $endDate])
                    ->with('patient')
                    ->orderBy('IssueDate', 'desc')
                    ->get();
            } elseif ($reportType == 'appointment_trends') {
                $reportData = Appointment::whereBetween('Date', [$startDate, $endDate])
                    ->with(['patient', 'doctor'])
                    ->orderBy('Date', 'desc')
                    ->get();
            } elseif ($reportType == 'patient_demographics') {
                $reportData = Patient::whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                    ->orderBy('created_at', 'desc')
                    ->get();
            }
        }

        return view('admin.reports', compact('reportData', 'reportType', 'startDate', 'endDate', 'dateRange'));
    }

    // ==========================================
    // Doctor Management
    // ==========================================

    public function doctors()
    {
        $doctors = Doctor::leftJoin('Doctor_Number', 'Doctor.DoctorID', '=', 'Doctor_Number.DoctorID')
            ->select('Doctor.*', 'Doctor_Number.Contact_Number')
            ->get();
        return view('admin.doctors.index', compact('doctors'));
    }

    public function createDoctor()
    {
        return view('admin.doctors.create');
    }

    public function storeDoctor(Request $request)
    {
        $request->validate([
            'DoctorID' => 'required|string|unique:Doctor,DoctorID',
            'First_Name' => 'required|string',
            'Last_Name' => 'required|string',
            'Email' => 'required|email|unique:Doctor,Email',
            'Contact_Number' => 'required|string',
            'Available_Days' => 'required|string',
            'Start_Time' => 'required', 'End_Time' => 'required',
        ]);

        $doctor = Doctor::create($request->only(['DoctorID', 'First_Name', 'Last_Name', 'Specialization', 'Email']));

        DB::table('Doctor_Number')->insert([
            'DoctorID' => $doctor->DoctorID, 'Contact_Number' => $request->Contact_Number
        ]);

        DB::table('Doctor_Availability')->insert([
            'DoctorID' => $doctor->DoctorID,
            'Available_Days' => $request->Available_Days,
            'Start_Time' => $request->Start_Time,
            'End_Time' => $request->End_Time
        ]);

        return redirect()->route('admin.doctors.index')->with('success', 'Doctor created successfully.');
    }

    public function editDoctor($id)
    {
        $doctor = Doctor::where('DoctorID', $id)->firstOrFail();
        $contact = DB::table('Doctor_Number')->where('DoctorID', $id)->first();
        $availability = DB::table('Doctor_Availability')->where('DoctorID', $id)->first();
        return view('admin.doctors.edit', compact('doctor', 'contact', 'availability'));
    }

    public function updateDoctor(Request $request, $id)
    {
        $doctor = Doctor::where('DoctorID', $id)->firstOrFail();

        $request->validate([
            'First_Name' => 'required', 'Last_Name' => 'required',
            'Email' => 'required|email|unique:Doctor,Email,'.$id.',DoctorID',
            'Contact_Number' => 'required',
        ]);

        $doctor->update($request->only(['First_Name', 'Last_Name', 'Specialization', 'Email']));

        DB::table('Doctor_Number')->updateOrInsert(['DoctorID' => $id], ['Contact_Number' => $request->Contact_Number]);
        DB::table('Doctor_Availability')->updateOrInsert(['DoctorID' => $id], [
            'Available_Days' => $request->Available_Days,
            'Start_Time' => $request->Start_Time,
            'End_Time' => $request->End_Time
        ]);

        return redirect()->route('admin.doctors.index')->with('success', 'Doctor updated successfully.');
    }

    public function destroyDoctor($id)
    {
        Doctor::where('DoctorID', $id)->firstOrFail()->delete();
        return redirect()->route('admin.doctors.index')->with('success', 'Doctor deleted successfully.');
    }

    // ==========================================
    // Patient Management
    // ==========================================

    public function patients()
    {
        $patients = Patient::leftJoin('Patient_Number', 'Patient.PatientID', '=', 'Patient_Number.PatientID')
            ->leftJoin('Insured_Patient', 'Patient.PatientID', '=', 'Insured_Patient.PatientID')
            ->select('Patient.*', 'Patient_Number.Contact_Number', DB::raw('CASE WHEN Insured_Patient.InsPatientID IS NOT NULL THEN 1 ELSE 0 END as is_insured'))
            ->get();
        return view('admin.patients.index', compact('patients'));
    }

    public function createPatient()
    {
        $lastPatient = Patient::orderBy('PatientID', 'desc')->first();
        if (!$lastPatient) {
            $nextId = 'PT00001';
        } else {
            $number = (int) str_replace('PT', '', $lastPatient->PatientID);
            $nextId = 'PT' . str_pad($number + 1, 5, '0', STR_PAD_LEFT);
        }
        return view('admin.patients.create', compact('nextId'));
    }

    public function storePatient(Request $request)
    {
        $request->validate([
            'PatientID' => 'required|unique:Patient,PatientID',
            'First_Name' => 'required', 'Last_Name' => 'required',
            'Email' => 'required|unique:Patient,Email',
            'Contact_Number' => 'required',
            'patient_type' => 'required|in:Insured,Non-Insured',
        ]);

        $user = User::create([
            'name' => $request->First_Name . ' ' . $request->Last_Name,
            'email' => $request->Email,
            'password' => Hash::make($request->Contact_Number),
            'role' => 'patient',
        ]);

        $patient = Patient::create(array_merge($request->all(), ['user_id' => $user->id]));

        DB::table('Patient_Number')->insert([
            'PatientID' => $patient->PatientID, 'Contact_Number' => $request->Contact_Number
        ]);

        if ($request->patient_type === 'Insured') {
            DB::table('Insured_Patient')->insert([
                'InsPatientID' => 'INS-' . rand(1000, 9999),
                'PatientID' => $patient->PatientID,
                'Provider_Name' => $request->Provider_Name,
                'Policy_Number' => $request->Policy_Number,
                'Coverage_Limit' => $request->Coverage_Limit,
            ]);
        }

        return redirect()->route('admin.patients.index')->with('success', 'Patient created successfully.');
    }

    public function editPatient($id)
    {
        $patient = Patient::where('PatientID', $id)->firstOrFail();
        $contact = DB::table('Patient_Number')->where('PatientID', $id)->first();
        $insurance = DB::table('Insured_Patient')->where('PatientID', $id)->first();
        return view('admin.patients.edit', compact('patient', 'contact', 'insurance'));
    }

    public function updatePatient(Request $request, $id)
    {
        $patient = Patient::where('PatientID', $id)->firstOrFail();

        $request->validate([
            'First_Name' => 'required', 'Last_Name' => 'required',
            'Email' => 'required|unique:Patient,Email,'.$id.',PatientID',
            'Contact_Number' => 'required',
        ]);

        $patient->update($request->all());

        DB::table('Patient_Number')->updateOrInsert(['PatientID' => $id], ['Contact_Number' => $request->Contact_Number]);

        if ($request->filled('Provider_Name')) {
            DB::table('Insured_Patient')->updateOrInsert(['PatientID' => $id], [
                'Provider_Name' => $request->Provider_Name,
                'Policy_Number' => $request->Policy_Number,
                'Coverage_Limit' => $request->Coverage_Limit
            ]);
        }

        return redirect()->route('admin.patients.index')->with('success', 'Patient updated successfully.');
    }

    public function destroyPatient($id)
    {
        Patient::where('PatientID', $id)->firstOrFail()->delete();
        return redirect()->route('admin.patients.index')->with('success', 'Patient deleted successfully.');
    }

    // ==========================================
    // Appointment Management
    // ==========================================

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
            'AppointmentID' => 'required|unique:Appointment,AppointmentID',
            'PatientID' => 'required|exists:Patient,PatientID',
            'DoctorID' => 'required|exists:Doctor,DoctorID',
            'Date' => 'required|date',
            'Time' => 'required',
            'Status' => 'required'
        ]);
        Appointment::create($request->all());
        return redirect()->route('admin.appointments.index')->with('success', 'Appointment created.');
    }

    public function editAppointment($id)
    {
        $appointment = Appointment::where('AppointmentID', $id)->firstOrFail();
        $patients = Patient::all();
        $doctors = Doctor::all();
        return view('admin.appointments.edit', compact('appointment', 'patients', 'doctors'));
    }

    public function updateAppointment(Request $request, $id)
    {
        $appointment = Appointment::where('AppointmentID', $id)->firstOrFail();
        $appointment->update($request->all());
        return redirect()->route('admin.appointments.index')->with('success', 'Appointment updated.');
    }

    public function destroyAppointment($id)
    {
        Appointment::where('AppointmentID', $id)->firstOrFail()->delete();
        return redirect()->route('admin.appointments.index')->with('success', 'Appointment deleted.');
    }

    // ==========================================
    // Billing Management
    // ==========================================

    public function billing()
    {
        $billings = Billing::with('patient')->get();
        return view('admin.billing.index', compact('billings'));
    }

    public function createBilling()
    {
        $patients = Patient::with('insured_patient')->get();
        $lastBilling = Billing::orderBy('InvoicedID', 'desc')->first();
        if (!$lastBilling) {
            $nextId = 'INV-0001';
        } else {
            $parts = explode('-', $lastBilling->InvoicedID);
            $number = isset($parts[1]) ? (int)$parts[1] : 0;
            $nextId = 'INV-' . str_pad($number + 1, 4, '0', STR_PAD_LEFT);
        }
        return view('admin.billing.create', compact('patients', 'nextId'));
    }

    public function storeBilling(Request $request)
    {
        $request->validate([
            'InvoicedID' => 'required|unique:Billing,InvoicedID',
            'PatientID' => 'required',
            'Total_Amount' => 'required',
            'tests' => 'required|array'
        ]);

        $billing = Billing::create($request->only(['InvoicedID', 'PatientID', 'Total_Amount', 'IssueDate', 'Payment_Status']));

        foreach ($request->tests as $test) {
            DB::table('Billing_Test')->insert([
                'InvoicedID' => $billing->InvoicedID,
                'Name' => $test['Name'],
                'Quantity' => $test['Quantity'],
                'Amount' => $test['Amount']
            ]);
        }
        return redirect()->route('admin.billing.index')->with('success', 'Billing created.');
    }

    public function editBilling($id)
    {
        $billing = Billing::where('InvoicedID', $id)->firstOrFail();
        $patients = Patient::all();
        return view('admin.billing.edit', compact('billing', 'patients'));
    }

    public function updateBilling(Request $request, $id)
    {
        $billing = Billing::where('InvoicedID', $id)->firstOrFail();
        $billing->update($request->all());
        return redirect()->route('admin.billing.index')->with('success', 'Billing updated.');
    }

    public function destroyBilling($id)
    {
        Billing::where('InvoicedID', $id)->firstOrFail()->delete();
        return redirect()->route('admin.billing.index')->with('success', 'Billing deleted.');
    }

    // ==========================================
    // User Management
    // ==========================================

    public function users()
    {
        $users = User::orderBy('created_at', 'desc')->get();
        return view('admin.users.index', compact('users'));
    }

    public function createUser()
    {
        return view('admin.users.create');
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'first_name' => 'required', 'last_name' => 'required', 'email' => 'required|unique:users', 'role' => 'required'
        ]);

        $user = User::create([
            'name' => $request->first_name . ' ' . $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        // Only create domain records if table exists in SQL Schema
        if ($request->role === 'doctor') {
            $id = 'DR' . str_pad($user->id, 5, '0', STR_PAD_LEFT);
            Doctor::create(['DoctorID' => $id, 'First_Name' => $request->first_name, 'Last_Name' => $request->last_name, 'Specialization' => $request->specialization, 'Email' => $request->email]);
            DB::table('Doctor_Number')->insert(['DoctorID' => $id, 'Contact_Number' => $request->doctor_contact]);
            DB::table('Doctor_Availability')->insert(['DoctorID' => $id, 'Available_Days' => $request->available_days, 'Start_Time' => $request->start_time, 'End_Time' => $request->end_time]);

        } elseif ($request->role === 'patient') {
            $id = 'PT' . str_pad($user->id, 5, '0', STR_PAD_LEFT);
            Patient::create(['PatientID' => $id, 'user_id' => $user->id, 'First_Name' => $request->first_name, 'Last_Name' => $request->last_name, 'Age' => $request->age, 'Gender' => $request->gender, 'Email' => $request->email, 'Street' => $request->street, 'City' => $request->city, 'Zip' => $request->zip]);
            DB::table('Patient_Number')->insert(['PatientID' => $id, 'Contact_Number' => $request->patient_contact]);

        } elseif ($request->role === 'lab') {
            $id = 'LT' . str_pad($user->id, 5, '0', STR_PAD_LEFT);
            DB::table('Lab_Technician')->insert(['StaffID' => $id, 'First_Name' => $request->first_name, 'Last_Name' => $request->last_name, 'Department' => $request->department, 'Email' => $request->email]);
        }

        // For Pharmacy and Reception, we just create the User login account since tables don't exist.

        return redirect()->route('admin.users.index')->with('success', 'User created.');
    }

    public function editUser($id)
    {
        $user = User::findOrFail($id);
        $profile = null; $contact = null; $availability = null;

        if ($user->role === 'doctor') {
            $profile = Doctor::where('Email', $user->email)->first();
            if ($profile) {
                $contact = DB::table('Doctor_Number')->where('DoctorID', $profile->DoctorID)->first();
                $availability = DB::table('Doctor_Availability')->where('DoctorID', $profile->DoctorID)->first();
            }
        } elseif ($user->role === 'patient') {
            $profile = Patient::where('Email', $user->email)->first();
            if ($profile) $contact = DB::table('Patient_Number')->where('PatientID', $profile->PatientID)->first();
        } elseif ($user->role === 'lab') {
            $profile = DB::table('Lab_Technician')->where('Email', $user->email)->first();
        }

        return view('admin.users.edit', compact('user', 'profile', 'contact', 'availability'));
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $request->validate(['first_name' => 'required', 'last_name' => 'required', 'email' => 'required|unique:users,email,'.$id]);

        $user->update([
            'name' => $request->first_name . ' ' . $request->last_name,
            'email' => $request->email,
        ]);
        if ($request->filled('password')) $user->update(['password' => Hash::make($request->password)]);

        // Sync Profile Data
        if ($user->role === 'doctor') {
            $doctor = Doctor::where('Email', $user->getOriginal('email'))->first();
            if ($doctor) {
                $doctor->update(['First_Name' => $request->first_name, 'Last_Name' => $request->last_name, 'Email' => $request->email, 'Specialization' => $request->specialization]);
                DB::table('Doctor_Number')->updateOrInsert(['DoctorID' => $doctor->DoctorID], ['Contact_Number' => $request->doctor_contact]);
                DB::table('Doctor_Availability')->updateOrInsert(['DoctorID' => $doctor->DoctorID], ['Available_Days' => $request->available_days, 'Start_Time' => $request->start_time, 'End_Time' => $request->end_time]);
            }
        } elseif ($user->role === 'patient') {
            $patient = Patient::where('Email', $user->getOriginal('email'))->first();
            if ($patient) {
                $patient->update(array_merge($request->only(['First_Name', 'Last_Name', 'Age', 'Gender', 'Street', 'City', 'Zip']), ['Email' => $request->email]));
                DB::table('Patient_Number')->updateOrInsert(['PatientID' => $patient->PatientID], ['Contact_Number' => $request->patient_contact]);
            }
        } elseif ($user->role === 'lab') {
            DB::table('Lab_Technician')->where('Email', $user->getOriginal('email'))->update(['First_Name' => $request->first_name, 'Last_Name' => $request->last_name, 'Email' => $request->email, 'Department' => $request->department]);
        }

        return redirect()->route('admin.users.index')->with('success', 'User updated.');
    }

    public function destroyUser($id)
    {
        if (auth()->id() == $id) return back()->withErrors('Cannot delete self.');
        User::findOrFail($id)->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted.');
    }
}
