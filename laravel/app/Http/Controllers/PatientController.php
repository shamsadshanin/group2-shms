<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Appointment;
use App\Models\Prescription;
use App\Models\MedicalRecord;
use App\Models\Billing;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        return Patient::where('cEmail', Auth::user()->email)->firstOrFail();
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
        $request->validate([
            'cDoctorID' => 'required|exists:tbldoctor,cDoctorID',
            'dAppointmentDateTime' => 'required|date',
            'cSymptoms' => 'nullable|string',
        ]);

        $patient = $this->getPatient();
        
        Appointment::create([
            'cAppointmentID' => 'A-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT),
            'cPatientID' => $patient->cPatientID,
            'cDoctorID' => $request->cDoctorID,
            'dAppointmentDateTime' => $request->dAppointmentDateTime,
            'cStatus' => 'Scheduled',
            'cPurpose' => $request->cSymptoms,
        ]);

        return redirect()->route('patient.dashboard')->with('success', 'Appointment booked successfully!');
    }

    public function medicalHistory()
    {
        $patient = $this->getPatient();
        $medicalHistory = MedicalRecord::where('cPatientID', $patient->cPatientID)
            ->orderBy('dDate', 'desc')
            ->get();

        return view('patient.medical-history', compact('medicalHistory'));
    }

    public function prescriptions()
    {
        $patient = $this->getPatient();
        $prescriptions = Prescription::where('cPatientID', $patient->cPatientID)
            ->orderBy('dDate', 'desc')
            ->get();

        return view('patient.prescriptions', compact('prescriptions'));
    }

    public function billing()
    {
        $patient = $this->getPatient();
        $billings = Billing::where('cPatientID', $patient->cPatientID)
            ->orderBy('dBillingDate', 'desc')
            ->get();

        return view('patient.billing', compact('billings'));
    }

    public function payBill($cBillingID)
    {
        $patient = $this->getPatient();
        $billing = Billing::where('cBillingID', $cBillingID)
            ->where('cPatientID', $patient->cPatientID)
            ->firstOrFail();

        $billing->cStatus = 'Paid';
        $billing->save();

        return redirect()->route('patient.billing')->with('success', 'Payment completed successfully!');
    }

    public function symptomChecker()
    {
        return view('patient.symptom-checker');
    }

    public function checkSymptoms(Request $request)
    {
        $request->validate([
            'symptoms' => 'required|string|max:255',
        ]);

        $diagnosis = $this->mockAIPrediction($request->symptoms);
        
        return view('patient.symptom-checker', compact('diagnosis'));
    }

    private function mockAIPrediction($description)
    {
        $keywords = strtolower($description);
        $diseases = [
            'fever' => 'Based on your symptoms, you might have Viral Influenza. It is recommended to consult a doctor.',
            'cough' => 'Based on your symptoms, you might have a Common Cold. Rest and hydration are recommended.',
            'headache' => 'Based on your symptoms, you might be experiencing a Migraine. Rest in a quiet, dark room.',
            'chest pain' => 'Chest pain can be serious. Please seek immediate medical attention.',
            'stomach pain' => 'Based on your symptoms, you might have Gastritis. Avoid spicy and oily food.',
        ];

        foreach ($diseases as $keyword => $disease) {
            if (strpos($keywords, $keyword) !== false) {
                return $disease;
            }
        }

        return 'Your symptoms are unclear. Please consult a doctor for a proper diagnosis.';
    }

    /**
     * Show the patient history for a specific patient.
     *
     * @param  \App\Models\Patient  $patient
     * @return \Illuminate\View\View
     */
    public function patientHistory(Patient $patient)
    {
        $patient->load(['appointments.doctor', 'prescriptions.doctor', 'medicalRecords']);

        return view('doctor.patient-history', compact('patient'));
    }
}
