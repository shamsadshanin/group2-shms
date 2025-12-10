<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Prescription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DoctorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:doctor');
    }

    private function getDoctor()
    {
        return Doctor::where('cEmail', Auth::user()->email)->firstOrFail();
    }

    public function dashboard()
    {
        $doctor = $this->getDoctor();
        $patients = Patient::whereHas('appointments', function ($query) use ($doctor) {
            $query->where('cDoctorID', $doctor->cDoctorID);
        })->withCount('appointments')->orderByDesc('appointments_count')->paginate(10);

        return view('doctor.dashboard', [
            'doctor' => $doctor,
            'patients' => $patients,
        ]);
    }

    public function appointments()
    {
        $doctor = $this->getDoctor();
        $appointments = Appointment::where('cDoctorID', $doctor->cDoctorID)
            ->with('patient')
            ->orderBy('dAppointmentDateTime', 'desc')
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
            'cPatientID' => 'required|exists:tblpatient,cPatientID',
            'dAppointmentDateTime' => 'required|date',
            'cPurpose' => 'required|string',
        ]);

        $doctor = $this->getDoctor();

        Appointment::create([
            'cAppointmentID' => 'A-' . str_pad(Appointment::count() + 1, 4, '0', STR_PAD_LEFT),
            'cPatientID' => $request->cPatientID,
            'cDoctorID' => $doctor->cDoctorID,
            'dAppointmentDateTime' => $request->dAppointmentDateTime,
            'cPurpose' => $request->cPurpose,
            'cStatus' => 'Scheduled',
        ]);

        return redirect()->route('doctor.appointments')->with('success', 'Appointment created successfully!');
    }

    public function prescriptions()
    {
        $doctor = $this->getDoctor();
        $prescriptions = Prescription::where('cDoctorID', $doctor->cDoctorID)
            ->with('patient')
            ->orderBy('dPrescriptionDate', 'desc')
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
            'cPatientID' => 'required|exists:tblpatient,cPatientID',
            'dPrescriptionDate' => 'required|date',
            'cMedication' => 'required|string',
            'cDosage' => 'required|string',
            'cInstructions' => 'required|string',
        ]);

        $doctor = $this->getDoctor();

        Prescription::create([
            'cPrescriptionID' => 'P-' . str_pad(Prescription::count() + 1, 4, '0', STR_PAD_LEFT),
            'cPatientID' => $request->cPatientID,
            'cDoctorID' => $doctor->cDoctorID,
            'dPrescriptionDate' => $request->dPrescriptionDate,
            'cMedication' => $request->cMedication,
            'cDosage' => $request->cDosage,
            'cInstructions' => $request->cInstructions,
            'cStatus' => 'Issued',
        ]);

        return redirect()->route('doctor.prescriptions')->with('success', 'Prescription created successfully!');
    }
}
