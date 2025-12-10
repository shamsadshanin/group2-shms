<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Billing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReceptionistController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:reception');
    }

    public function dashboard()
    {
        $todayAppointments = Appointment::whereDate('dDate', today())
            ->orderBy('dTime')
            ->get();
        
        $pendingAppointments = Appointment::where('cStatus', 'Scheduled')
            ->orderBy('dDate', 'asc')
            ->take(10)
            ->get();

        return view('reception.dashboard', compact('todayAppointments', 'pendingAppointments'));
    }

    public function appointments()
    {
        $appointments = Appointment::orderBy('dDate', 'desc')
            ->get();

        return view('reception.appointments', compact('appointments'));
    }

    public function appointmentDetail($cAppointmentID)
    {
        $appointment = Appointment::findOrFail($cAppointmentID);
        $patient = $appointment->patient;
        $doctor = $appointment->doctor;

        return view('reception.appointment-detail', compact('appointment', 'patient', 'doctor'));
    }

    public function checkInPatient($cAppointmentID)
    {
        $appointment = Appointment::findOrFail($cAppointmentID);
        $appointment->cStatus = 'Checked In';
        $appointment->save();

        return redirect()->route('reception.appointment-detail', $cAppointmentID)
            ->with('success', 'Patient checked in successfully!');
    }

    public function cancelAppointment($cAppointmentID)
    {
        $appointment = Appointment::findOrFail($cAppointmentID);
        $appointment->cStatus = 'Cancelled';
        $appointment->save();

        return redirect()->route('reception.appointments')
            ->with('success', 'Appointment cancelled successfully!');
    }

    public function patients()
    {
        $patients = Patient::orderBy('cName')
            ->get();

        return view('reception.patients', compact('patients'));
    }

    public function patientDetail($cPatientID)
    {
        $patient = Patient::findOrFail($cPatientID);
        
        $appointments = Appointment::where('cPatientID', $cPatientID)
            ->orderBy('dDate', 'desc')
            ->get();
        
        $billings = Billing::where('cPatientID', $cPatientID)
            ->orderBy('dIssueDate', 'desc')
            ->get();

        return view('reception.patient-detail', compact('patient', 'appointments', 'billings'));
    }

    public function createBilling(Request $request)
    {
        $request->validate([
            'cPatientID' => 'required|exists:tblpatient,cPatientID',
            'nTotalAmount' => 'required|numeric|min:0',
            'cDescription' => 'required|string|max:255',
        ]);

        $billing = new Billing();
        $billing->cInvoiceID = 'INV-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        $billing->cPatientID = $request->cPatientID;
        $billing->nTotalAmount = $request->nTotalAmount;
        $billing->dIssueDate = now();
        $billing->cPaymentStatus = 'Unpaid';
        $billing->cDescription = $request->cDescription;
        $billing->save();

        return redirect()->route('reception.patient-detail', $request->cPatientID)
            ->with('success', 'Billing created successfully!');
    }

    public function bookAppointment(Request $request)
    {
        $request->validate([
            'cPatientID' => 'required|exists:tblpatient,cPatientID',
            'cDoctorID' => 'required|exists:tbldoctor,cDoctorID',
            'dDate' => 'required|date',
            'dTime' => 'required',
            'cPurpose' => 'required|string|max:100',
        ]);

        $appointment = new Appointment();
        $appointment->cAppointmentID = 'A-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        $appointment->cPatientID = $request->cPatientID;
        $appointment->cDoctorID = $request->cDoctorID;
        $appointment->dDate = $request->dDate;
        $appointment->dTime = $request->dTime;
        $appointment->cStatus = 'Scheduled';
        $appointment->cPurpose = $request->cPurpose;
        $appointment->save();

        return redirect()->route('reception.appointments')
            ->with('success', 'Appointment booked successfully!');
    }

    public function createPatient(Request $request)
    {
        $request->validate([
            'cName' => 'required|string|max:50',
            'cEmail' => 'required|email|unique:tblpatient,cEmail',
            'cContactNumber' => 'required|string|max:15',
            'cGender' => 'required|string|max:10',
            'cDateOfBirth' => 'required|date',
            'cAddress' => 'required|string|max:100',
        ]);

        $patient = new Patient();
        $patient->cPatientID = 'P-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        $patient->cName = $request->cName;
        $patient->cEmail = $request->cEmail;
        $patient->cContactNumber = $request->cContactNumber;
        $patient->cGender = $request->cGender;
        $patient->cDateOfBirth = $request->cDateOfBirth;
        $patient->cAddress = $request->cAddress;
        $patient->save();

        return redirect()->route('reception.patients')
            ->with('success', 'Patient created successfully!');
    }
}