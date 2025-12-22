<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Prescription;
use App\Models\PrescriptionMedicine; // Make sure to import this
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
// ... Imports update করুন
use Illuminate\Support\Str;
use App\Models\LabTest; // Investigation Model

class DoctorController extends Controller
{
    public function __construct()
    {
        // Ensure you have role middleware set up, or remove if not using spatie/permission
        // $this->middleware('role:doctor');
    }

    /**
     * Helper to get current logged in Doctor
     */
    /**
     * Helper to get current logged in Doctor based on Email
     */
    private function getDoctor()
    {
        // SQL Schema অনুযায়ী Doctor টেবিলের 'Email' কলামের সাথে লগইন করা ইউজার এর ইমেইল চেক করা হচ্ছে
        return \App\Models\Doctor::where('Email', \Illuminate\Support\Facades\Auth::user()->email)->firstOrFail();
    }

    public function dashboard()
    {
        $doctor = $this->getDoctor();

        // ড্যাশবোর্ডের স্ট্যাটাস কাউন্টের জন্য রিলেশনশিপ লোড করা হচ্ছে (Optimization)
        $doctor->load('appointments');

        // যে সব রোগীদের এই ডাক্তারের সাথে অ্যাপয়েন্টমেন্ট আছে তাদের লিস্ট এবং অ্যাপয়েন্টমেন্ট সংখ্যা
        $patients = \App\Models\Patient::whereHas('appointments', function ($query) use ($doctor) {
            $query->where('DoctorID', $doctor->DoctorID);
        })
        ->withCount(['appointments' => function ($query) use ($doctor) {
            $query->where('DoctorID', $doctor->DoctorID);
        }])
        ->orderByDesc('appointments_count')
        ->paginate(10);

        return view('doctor.dashboard', [
            'doctor' => $doctor,
            'patients' => $patients,
        ]);
    }

    // ... Inside DoctorController ...

        public function patientHistory($patientID)
        {
            $patient = \App\Models\Patient::where('PatientID', $patientID)
                ->with([
                    'contactNumbers',
                    // FIX: Order by 'Treatment_Start_Date' (Your SQL date column)
                    'medicalRecords' => function($q) {
                        $q->orderBy('Treatment_Start_Date', 'desc');
                    },
                    'prescriptions' => function($q) {
                        $q->orderBy('IssueDate', 'desc');
                    },
                    'prescriptions.medicines',
                    'prescriptions.doctor',
                    'appointments' => function($q) {
                        $q->orderBy('Date', 'desc');
                    },
                    'appointments.doctor'
                ])
                ->firstOrFail();

            return view('doctor.patients.history', compact('patient'));
        }

        // ... DoctorController এর ভেতরে ...

        public function storeMedicalRecord(Request $request, $patientID)
        {
            // 1. ফর্ম ভ্যালিডেশন (View এর input name অনুযায়ী)
            $request->validate([
                'Date'      => 'required|date',
                'Diagnosis' => 'required|string',
                'Treatment' => 'required|string', // ফর্মে input name="Treatment" দেওয়া আছে
            ]);

            // 2. ID জেনারেট করা (MED-0001)
            $count = \App\Models\MedicalRecord::count() + 1;
            $recordID = 'MED-' . str_pad($count, 4, '0', STR_PAD_LEFT);

            // 3. ডাটাবেসে সেভ করা (সঠিক কলামের নাম ব্যবহার করে)
            \App\Models\MedicalRecord::create([
                'RecordID'             => $recordID,
                'PatientID'            => $patientID,
                // 'DoctorID'          => $doctor->DoctorID, // এই লাইনটি মুছে ফেলা হয়েছে কারণ টেবিলে এই কলাম নেই

                'Disease_Name'         => $request->Diagnosis, // DB: Disease_Name <- Form: Diagnosis
                'Symptoms'             => $request->Treatment, // DB: Symptoms     <- Form: Treatment
                'Treatment_Start_Date' => $request->Date,      // DB: Treatment_Start_Date <- Form: Date
                'Follow_Up'            => $request->FollowUpDate ?? null, // DB: Follow_Up <- Form: FollowUpDate
            ]);

            return redirect()->route('doctor.patients.history', $patientID)->with('success', 'Medical record added successfully.');
        }

    // ==========================================
    // Appointments
    // ==========================================

    public function appointments()
    {
        // 1. Get Current Doctor
        $doctor = $this->getDoctor();

        // 2. Fetch Appointments
        // Matches SQL Schema: Appointment table uses 'DoctorID', 'Date', 'Time'
        $appointments = \App\Models\Appointment::where('DoctorID', $doctor->DoctorID)
            ->with('patient') // Eager load Patient details
            ->orderBy('Date', 'desc')
            ->orderBy('Time', 'desc')
            ->paginate(10);

        return view('doctor.appointments', compact('appointments'));
    }

    public function createAppointment()
    {
        $patients = Patient::all();
        return view('doctor.appointments.create', compact('patients'));
    }

    public function storeAppointment(Request $request)
    {
        $request->validate([
            'PatientID' => 'required|exists:Patient,PatientID',
            'Date'      => 'required|date',
            'Time'      => 'required',
        ]);

        $doctor = $this->getDoctor();

        // Generate ID: AP-0001
        $count = Appointment::count() + 1;
        $appointmentID = 'AP-' . str_pad($count, 4, '0', STR_PAD_LEFT);

        Appointment::create([
            'AppointmentID' => $appointmentID,
            'PatientID'     => $request->PatientID,
            'DoctorID'      => $doctor->DoctorID,
            'Date'          => $request->Date,
            'Time'          => $request->Time,
            'Status'        => 'Scheduled',
        ]);

        return redirect()->route('doctor.appointments')->with('success', 'Appointment scheduled successfully!');
    }

    // ==========================================
    // Prescriptions
    // ==========================================

    public function prescriptions()
    {
        $doctor = $this->getDoctor();

        // 1. Fetch Prescriptions
        // 2. Eager Load 'patient' and 'medicines' relationships
        // 3. Sort by 'IssueDate' (SQL Schema Column)
        $prescriptions = \App\Models\Prescription::where('DoctorID', $doctor->DoctorID)
            ->with(['patient', 'medicines'])
            ->orderBy('IssueDate', 'desc')
            ->paginate(10);

        return view('doctor.prescriptions', compact('prescriptions'));
    }

    public function createPrescription()
    {
        $patients = Patient::all();
        return view('doctor.prescriptions.create', compact('patients'));
    }

    public function storePrescription(Request $request)
    {
        $request->validate([
            'PatientID'            => 'required|exists:Patient,PatientID',
            'IssueDate'            => 'required|date',
            'medicines'            => 'required|array|min:1',
            'medicines.*.name'     => 'required|string',
            // Lab Tests Optional but if present must have name
            'lab_tests'            => 'nullable|array',
            'lab_tests.*.name'     => 'required_with:lab_tests|string',
        ]);

        $doctor = $this->getDoctor();

        // 1. Create Prescription
        $count = \App\Models\Prescription::count() + 1;
        $prescriptionID = 'P-' . str_pad($count, 4, '0', STR_PAD_LEFT);

        $prescription = \App\Models\Prescription::create([
            'PrescriptionID' => $prescriptionID,
            'PatientID'      => $request->PatientID,
            'DoctorID'       => $doctor->DoctorID,
            'IssueDate'      => $request->IssueDate,
        ]);

        // 2. Save Medicines
        foreach ($request->medicines as $med) {
            \App\Models\PrescriptionMedicine::create([
                'PrescriptionID' => $prescription->PrescriptionID,
                'Medicine_Name'  => $med['name'],
                'Dosage'         => $med['dosage'],
                'Frequency'      => $med['frequency'],
                'Duration'       => '3 Days',
            ]);
        }

        // 3. Save Lab Tests (AUTO CREATE INVESTIGATION)
        if ($request->has('lab_tests')) {
            foreach ($request->lab_tests as $test) {
                if (!empty($test['name'])) {
                    // Generate Unique Lab ID
                    $labID = 'LT-' . strtoupper(Str::random(8));

                    \App\Models\LabTest::create([
                        'InvestigationID' => $labID,
                        'PatientID'       => $request->PatientID,
                        'StaffID'         => null, // এখনো কোনো টেকনিশিয়ান অ্যাসাইন হয়নি
                        'Test'            => $test['name'],
                        'TestType'        => $test['type'] ?? 'General',
                        'Result_Summary'  => null, // Pending Status
                        'DigitalReport'   => null
                    ]);
                }
            }
        }

        return redirect()->route('doctor.prescriptions')->with('success', 'Prescription and Lab Orders placed successfully!');
    }
    // Add this method to App\Http\Controllers\DoctorController.php

    public function showPrescription($id)
    {
        $doctor = $this->getDoctor();

        // Fetch prescription with Patient and Medicines
        // Ensure the doctor owns this prescription for security
        $prescription = \App\Models\Prescription::where('PrescriptionID', $id)
            ->where('DoctorID', $doctor->DoctorID)
            ->with(['patient', 'medicines'])
            ->firstOrFail();

        return view('doctor.prescriptions.show', compact('prescription'));
    }

    // ... inside DoctorController ...

        // ==========================================
        // Edit Patient Profile
        // ==========================================
        public function editPatient($id)
        {
            $patient = \App\Models\Patient::where('PatientID', $id)->firstOrFail();
            // Fetch contact number if available
            $contact = \App\Models\PatientNumber::where('PatientID', $id)->first();
            return view('doctor.patients.edit', compact('patient', 'contact'));
        }

        public function updatePatient(Request $request, $id)
        {
            $request->validate([
                'First_Name' => 'required',
                'Last_Name'  => 'required',
                'Age'        => 'required|integer',
                'Gender'     => 'required',
                'Contact_Number' => 'required'
            ]);

            $patient = \App\Models\Patient::where('PatientID', $id)->firstOrFail();

            // Update Patient Table
            $patient->update($request->only(['First_Name', 'Last_Name', 'Age', 'Gender']));

            // Update Patient Number Table
            \App\Models\PatientNumber::updateOrInsert(
                ['PatientID' => $id],
                ['Contact_Number' => $request->Contact_Number]
            );

            return redirect()->route('doctor.patients.history', $id)->with('success', 'Patient profile updated.');
        }

        // ==========================================
        // Medical Records
        // ==========================================
        public function createMedicalRecord($patientID)
        {
            $patient = \App\Models\Patient::where('PatientID', $patientID)->firstOrFail();
            return view('doctor.medical-records.create', compact('patient'));
        }


}
