<?php

namespace App\Http\Controllers;

use App\Models\Prescription;
use App\Models\Patient;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PharmacyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:pharmacy');
    }

    public function dashboard()
    {
        $prescriptions = Prescription::where('cStatus', 'Active')
            ->orderBy('dPrescriptionDate', 'desc')
            ->take(20)
            ->get();

        return view('pharmacy.dashboard', compact('prescriptions'));
    }

    public function prescriptions()
    {
        $prescriptions = Prescription::orderBy('dPrescriptionDate', 'desc')
            ->get();

        return view('pharmacy.prescriptions', compact('prescriptions'));
    }

    public function prescriptionDetail($cPrescriptionID)
    {
        $prescription = Prescription::findOrFail($cPrescriptionID);
        $patient = $prescription->patient;
        $doctor = $prescription->doctor;

        return view('pharmacy.prescription-detail', compact('prescription', 'patient', 'doctor'));
    }

    public function markAsDispensed($cPrescriptionID)
    {
        $prescription = Prescription::findOrFail($cPrescriptionID);
        $prescription->cStatus = 'Dispensed';
        $prescription->save();

        return redirect()->route('pharmacy.prescriptions')
            ->with('success', 'Prescription marked as dispensed!');
    }

    public function markAsCollected($cPrescriptionID)
    {
        $prescription = Prescription::findOrFail($cPrescriptionID);
        $prescription->cStatus = 'Collected';
        $prescription->save();

        return redirect()->route('pharmacy.prescriptions')
            ->with('success', 'Prescription marked as collected!');
    }

    public function createPrescription()
    {
        $patients = Patient::all();
        $doctors = Doctor::all();
        return view('pharmacy.prescriptions.create', compact('patients', 'doctors'));
    }

    public function storePrescription(Request $request)
    {
        $request->validate([
            'cPatientID' => 'required|exists:tblpatient,cPatientID',
            'cDoctorID' => 'required|exists:tbldoctor,cDoctorID',
            'dPrescriptionDate' => 'required|date',
            'cMedication' => 'required|string',
            'cDosage' => 'required|string',
            'cInstructions' => 'required|string',
        ]);

        Prescription::create([
            'cPrescriptionID' => 'P-' . str_pad(Prescription::count() + 1, 4, '0', STR_PAD_LEFT),
            'cPatientID' => $request->cPatientID,
            'cDoctorID' => $request->cDoctorID,
            'dPrescriptionDate' => $request->dPrescriptionDate,
            'cMedication' => $request->cMedication,
            'cDosage' => $request->cDosage,
            'cInstructions' => $request->cInstructions,
            'cStatus' => 'Issued',
        ]);

        return redirect()->route('pharmacy.prescriptions')->with('success', 'Prescription created successfully!');
    }
}
