<?php

namespace App\Http\Controllers;

use App\Models\Prescription;
use App\Models\PrescriptionMedicine;
use App\Models\Patient;
use App\Models\Doctor;
use Illuminate\Http\Request;

class PharmacyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:pharmacy');
    }

    public function dashboard()
    {
        // Removed 'Status' check as column does not exist in SQL
        $prescriptions = Prescription::with(['patient', 'doctor'])
            ->orderBy('IssueDate', 'desc')
            ->take(20)
            ->get();

        return view('pharmacy.dashboard', compact('prescriptions'));
    }

    public function prescriptions()
    {
        $prescriptions = Prescription::with(['patient', 'doctor'])
            ->orderBy('IssueDate', 'desc')
            ->get();

        return view('pharmacy.prescriptions', compact('prescriptions'));
    }

    // --- FIX IS HERE ---
        public function prescriptionDetail($id)
        {
            // 1. Fetch Prescription with relationships
            $prescription = Prescription::with(['medicines', 'patient', 'doctor'])
                ->where('PrescriptionID', $id)
                ->firstOrFail();

            // 2. Extract Patient and Doctor variables for the view
            $patient = $prescription->patient;
            $doctor = $prescription->doctor;

            // 3. Pass all variables to the view
            return view('pharmacy.prescription-detail', compact('prescription', 'patient', 'doctor'));
        }

        public function markAsDispensed($id)
        {
            return redirect()->back()->with('success', 'Prescription processed.');
        }

        public function markAsCollected($id)
        {
            return redirect()->back()->with('success', 'Prescription processed.');
        }

    public function createPrescription()
    {
        $patients = Patient::all();
        $doctors = Doctor::all();
        return view('pharmacy.prescriptions.create', compact('patients', 'doctors'));
    }

    public function storePrescription(Request $request)
    {
        // 1. Validate Input
        $request->validate([
            'PatientID'     => 'required|exists:Patient,PatientID',
            'DoctorID'      => 'required|exists:Doctor,DoctorID',
            'IssueDate'     => 'required|date',
            'cMedication'   => 'required|string', // Input name from form
            'cDosage'       => 'required|string',
            'cInstructions' => 'required|string',
        ]);

        // 2. Generate ID (P-XXXX)
        $count = Prescription::count() + 1;
        $prescriptionID = 'P-' . str_pad($count, 4, '0', STR_PAD_LEFT);

        // 3. Create Main Prescription Record (Matches SQL: No Status column)
        $prescription = Prescription::create([
            'PrescriptionID' => $prescriptionID,
            'PatientID'      => $request->PatientID,
            'DoctorID'       => $request->DoctorID,
            'IssueDate'      => $request->IssueDate,
        ]);

        // 4. Create Medicine Detail Record (Matches SQL: Prescription_Medicine)
        PrescriptionMedicine::create([
            'PrescriptionID' => $prescription->PrescriptionID,
            'Medicine_Name'  => $request->cMedication,
            'Dosage'         => $request->cDosage,
            'Frequency'      => $request->cInstructions, // Map Instructions -> Frequency
            'Duration'       => '3 Days', // Default value
        ]);

        return redirect()->route('pharmacy.dashboard')->with('success', 'Prescription created successfully!');
    }
}
