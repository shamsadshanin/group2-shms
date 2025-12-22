<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Appointment;
use App\Models\Prescription;
use App\Models\Billing;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SymptomInput;
use App\Models\DiseasePrediction;
use App\Models\MedicalRecord;
use Illuminate\Support\Str;

class PatientController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:patient')->except('patientHistory');
        $this->middleware('role:doctor')->only('patientHistory');
    }

    private function getPatient()
    {
        // Match using 'Email' column in Patient table
        return Patient::where('Email', Auth::user()->email)->firstOrFail();
    }

    public function dashboard()
    {
        return view('patient.dashboard');
    }

    public function bookAppointment()
    {
        $doctors = Doctor::all();
        return view('patient.book-appointment', compact('doctors'));
    }

    public function storeAppointment(Request $request)
    {
        // Update Validation to match form and SQL
        $request->validate([
            'DoctorID' => 'required|exists:Doctor,DoctorID', // Changed from cDoctorID
            'Date'     => 'required|date',                   // Split Date
            'Time'     => 'required',                        // Split Time
            'Purpose'  => 'nullable|string',                 // Changed from cSymptoms
        ]);

        $patient = $this->getPatient();

        // Generate ID (A-XXXX)
        $count = Appointment::count() + 1;
        $appointmentID = 'A-' . str_pad($count, 4, '0', STR_PAD_LEFT);

        Appointment::create([
            'AppointmentID' => $appointmentID,
            'PatientID'     => $patient->PatientID,
            'DoctorID'      => $request->DoctorID,
            'Date'          => $request->Date,
            'Time'          => $request->Time,
            'Status'        => 'Scheduled',
            'Purpose'       => $request->Purpose,
        ]);

        return redirect()->route('patient.dashboard')->with('success', 'Appointment booked successfully!');
    }

    public function medicalHistory()
    {
        $patient = $this->getPatient();

        // Updated to use 'Treatment_Start_Date' (from SQL)
        $medicalHistory = \App\Models\MedicalRecord::where('PatientID', $patient->PatientID)
            ->orderBy('Treatment_Start_Date', 'desc')
            ->get();

        return view('patient.medical-history', compact('medicalHistory'));
    }

    public function prescriptions()
        {
            $patient = $this->getPatient();

            // 1. Filter by PatientID
            // 2. Load 'doctor' info
            // 3. Load 'medicines' (from Prescription_Medicine table)
            // 4. Sort by IssueDate
            $prescriptions = \App\Models\Prescription::where('PatientID', $patient->PatientID)
                ->with(['doctor', 'medicines'])
                ->orderBy('IssueDate', 'desc')
                ->get();

            return view('patient.prescriptions', compact('prescriptions'));
        }

    public function billing()
    {
        $patient = $this->getPatient();
        // SQL Column: IssueDate
        $billings = Billing::where('PatientID', $patient->PatientID)
            ->orderBy('IssueDate', 'desc')
            ->get();

        return view('patient.billing', compact('billings'));
    }

    public function payBill($invoicedID)
    {
        $patient = $this->getPatient();
        // SQL Columns: InvoicedID, Payment_Status
        $billing = Billing::where('InvoicedID', $invoicedID)
            ->where('PatientID', $patient->PatientID)
            ->firstOrFail();

        $billing->Payment_Status = 'Paid';
        $billing->save();

        return redirect()->route('patient.billing')->with('success', 'Payment completed successfully!');
    }

    // ... Symptom Checker Logic (Unchanged) ...
    public function symptomChecker()
        {
            $patient = $this->getPatient();

            // Fetch recent history from Symptom_Input table
            $history = SymptomInput::where('PatientID', $patient->PatientID)
                ->with('disease') // Eager load the prediction
                ->orderBy('InputDate', 'desc')
                ->take(5)
                ->get();

            return view('patient.symptom-checker', compact('history'));
        }

    public function checkSymptoms(Request $request)
        {
            $request->validate([
                'symptoms' => 'required|string|max:500',
            ]);

            $patient = $this->getPatient();
            $inputSymptoms = strtolower($request->symptoms);

            // --- STEP 1: AI LOGIC (Database Driven) ---
            // We will look at the 'Medical_Record' table to see what doctors
            // diagnosed for similar symptoms in the past.

            // Get all medical records
            $records = MedicalRecord::select('Symptoms', 'Disease_Name')->get();

            $candidates = [];
            $totalMatches = 0;

            $inputTokens = explode(' ', str_replace([',', '.'], '', $inputSymptoms)); // simple tokenizer

            foreach ($records as $record) {
                $dbSymptoms = strtolower($record->Symptoms);
                $matchCount = 0;

                // Check how many keywords match
                foreach ($inputTokens as $token) {
                    if (strlen($token) > 3 && str_contains($dbSymptoms, $token)) {
                        $matchCount++;
                    }
                }

                if ($matchCount > 0) {
                    $disease = $record->Disease_Name;
                    if (!isset($candidates[$disease])) {
                        $candidates[$disease] = 0;
                    }
                    // Weighted score: more matches = higher score
                    $candidates[$disease] += $matchCount;
                    $totalMatches += $matchCount;
                }
            }

            // --- STEP 2: DETERMINE RESULT ---
            $predictedDisease = 'Unknown Condition';
            $confidence = 0;
            $advice = 'Please consult a general physician for a checkup.';

            if (!empty($candidates)) {
                // Sort by highest score
                arsort($candidates);
                $predictedDisease = array_key_first($candidates);
                $score = $candidates[$predictedDisease];

                // Calculate percentage confidence based on share of total matches
                $confidence = ($totalMatches > 0) ? ($score / $totalMatches) * 100 : 0;

                // Cap confidence slightly below 100% because it's AI
                if ($confidence > 95) $confidence = 95;

                $advice = "Based on our medical records, your symptoms are frequently associated with $predictedDisease.";
            } else {
                // Fallback Rule-Based Logic (if database is empty)
                if (str_contains($inputSymptoms, 'fever')) {
                    $predictedDisease = 'Viral Fever'; $confidence = 65;
                } elseif (str_contains($inputSymptoms, 'chest')) {
                    $predictedDisease = 'Angina / Heart Issue'; $confidence = 80; $advice = 'Seek immediate medical attention!';
                } elseif (str_contains($inputSymptoms, 'headache')) {
                    $predictedDisease = 'Migraine'; $confidence = 60;
                }
            }

            // --- STEP 3: SAVE TO DATABASE (Symptom_Input & Disease_Prediction) ---

            // 3a. Save Input
            $inputID = 'SI-' . strtoupper(Str::random(8));
            $symptomInput = SymptomInput::create([
                'InputID'     => $inputID,
                'PatientID'   => $patient->PatientID,
                'Description' => $request->symptoms,
                'InputDate'   => now(),
            ]);

            // 3b. Save Prediction
            $predictionID = 'DP-' . strtoupper(Str::random(8));
            DiseasePrediction::create([
                'PredictionID'     => $predictionID,
                'InputID'          => $inputID,
                'DiseaseName'      => $predictedDisease,
                'Confidence_Score' => $confidence,
                'Prediction_TimeStamp' => now(),
            ]);

            // --- STEP 4: RETURN VIEW ---
            return redirect()->route('patient.symptom-checker')->with('prediction', [
                'disease' => $predictedDisease,
                'score'   => $confidence,
                'advice'  => $advice
            ]);
        }

    private function mockAIPrediction($description)
    {
        // Simple mock logic
        $keywords = strtolower($description);
        $diseases = [
            'fever' => 'Possible Viral Fever',
            'cough' => 'Possible Common Cold',
            'headache' => 'Possible Migraine',
        ];
        foreach ($diseases as $key => $val) {
            if (strpos($keywords, $key) !== false) return $val;
        }
        return 'Consult a Doctor';
    }

    // ... inside PatientController ...

        public function appointments(Request $request)
        {
            $patient = $this->getPatient();

            // Base Query
            $query = Appointment::where('PatientID', $patient->PatientID)
                ->with('doctor')
                ->orderBy('Date', 'desc')
                ->orderBy('Time', 'desc');

            // Optional Filter from View
            if ($request->has('status') && $request->status != '') {
                $query->where('Status', $request->status);
            }

            $appointments = $query->get();

            // Calculate Stats for the Dashboard Cards
            // Note: Using a fresh query for accurate totals ignoring filters
            $allApps = Appointment::where('PatientID', $patient->PatientID)->get();

            $totalAppointments = $allApps->count();
            $completedAppointments = $allApps->where('Status', 'Completed')->count();
            $upcomingAppointments = $allApps->where('Status', 'Scheduled')->count(); // Assuming 'Scheduled' = Upcoming
            $cancelledAppointments = $allApps->where('Status', 'Cancelled')->count();

            return view('patient.appointments', compact(
                'appointments',
                'totalAppointments',
                'completedAppointments',
                'upcomingAppointments',
                'cancelledAppointments'
            ));
        }

        // API Method for Viewing Appointment Details (AJAX)
        public function showAppointmentApi($id)
        {
            $patient = $this->getPatient();

            $appointment = Appointment::where('AppointmentID', $id)
                ->where('PatientID', $patient->PatientID)
                ->with('doctor')
                ->firstOrFail();

            return response()->json([
                'doctor_name' => 'Dr. ' . $appointment->doctor->First_Name . ' ' . $appointment->doctor->Last_Name,
                'doctor_spec' => $appointment->doctor->Specialization,
                'date' => \Carbon\Carbon::parse($appointment->Date)->format('M d, Y'),
                'time' => \Carbon\Carbon::parse($appointment->Time)->format('h:i A'),
                'purpose' => $appointment->Purpose ?? 'No purpose specified',
                'status' => $appointment->Status,
            ]);
        }

        // API Method for Cancelling Appointment (AJAX)
        public function cancelAppointmentApi($id)
        {
            $patient = $this->getPatient();

            $appointment = Appointment::where('AppointmentID', $id)
                ->where('PatientID', $patient->PatientID)
                ->first();

            if (!$appointment) {
                return response()->json(['success' => false, 'message' => 'Appointment not found.']);
            }

            if ($appointment->Status !== 'Scheduled') {
                return response()->json(['success' => false, 'message' => 'Only scheduled appointments can be cancelled.']);
            }

            $appointment->Status = 'Cancelled';
            $appointment->save();

            return response()->json(['success' => true, 'message' => 'Appointment cancelled successfully.']);
        }
}
