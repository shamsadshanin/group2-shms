<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\PatientNumber; // New Model for phone numbers
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ReceptionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:reception');
    }

    public function dashboard()
        {
            // Matches SQL Schema: Status, Date, Time
            $appointments = Appointment::with(['patient', 'doctor'])
                ->where('Status', 'Scheduled')
                ->orderBy('Date', 'asc')
                ->orderBy('Time', 'asc')
                ->get();

            return view('reception.dashboard', compact('appointments'));
        }

        public function checkIn($id)
        {
            // Find using AppointmentID
            $appointment = Appointment::where('AppointmentID', $id)->firstOrFail();

            $appointment->Status = 'Checked-in';
            $appointment->save();

            return redirect()->route('reception.dashboard')->with('success', 'Patient checked in successfully.');
        }

    public function appointments()
    {
        $appointments = Appointment::with(['patient', 'doctor'])
            ->orderBy('Date', 'desc')
            ->orderBy('Time', 'desc')
            ->paginate(15);

        return view('reception.appointments', compact('appointments'));
    }

    public function patients()
    {
        $patients = Patient::orderBy('First_Name', 'asc')->paginate(15);
        return view('reception.patients', compact('patients'));
    }

    public function createPatient()
    {
        return view('reception.patients.create');
    }

    public function storePatient(Request $request)
        {
            // 1. Validation
            $request->validate([
                // Basic Info
                'First_Name' => 'required|string|max:255',
                'Last_Name'  => 'required|string|max:255',
                'Email'      => 'nullable|email|unique:users,email',
                'Contact_Number' => 'required|string|max:20',
                'Age'        => 'required|integer|min:0',
                'Gender'     => 'required|string|in:Male,Female,Other',
                'Street'     => 'required|string',
                'City'       => 'required|string',
                'Zip'        => 'required|string',

                // Insurance Logic
                'PatientType'    => 'required|in:General,Insured',
                'Provider_Name'  => 'required_if:PatientType,Insured',
                'Policy_Number'  => 'required_if:PatientType,Insured',
                'Coverage_Limit' => 'required_if:PatientType,Insured|numeric',
            ]);

            $email = $request->Email;
            if (!$email) {
                $baseEmail = strtolower($request->First_Name . '.' . $request->Last_Name);
                $generatedEmail = $baseEmail . '@clinic.com';
                $counter = 1;
                while (User::where('email', $generatedEmail)->exists()) {
                    $generatedEmail = $baseEmail . $counter . '@clinic.com';
                    $counter++;
                }
                $email = $generatedEmail;
            }

            DB::transaction(function () use ($request, $email) {
                // A. Create User
                $user = User::create([
                    'name'     => $request->First_Name . ' ' . $request->Last_Name,
                    'email'    => $email,
                    'password' => Hash::make('password123'),
                    'role'     => 'patient',
                ]);

                // B. Generate Patient ID (PTXXXXX)
                $count = Patient::count() + 1;
                $patientID = 'PT' . str_pad($count, 5, '0', STR_PAD_LEFT);

                // C. Create Patient Record
                $patient = Patient::create([
                    'PatientID'  => $patientID,
                    'user_id'    => $user->id,
                    'First_Name' => $request->First_Name,
                    'Last_Name'  => $request->Last_Name,
                    'Age'        => $request->Age,
                    'Gender'     => $request->Gender,
                    'Email'      => $email,
                    'Street'     => $request->Street,
                    'City'       => $request->City,
                    'Zip'        => $request->Zip,
                ]);

                // D. Save Phone Number
                PatientNumber::create([
                    'PatientID'      => $patientID,
                    'Contact_Number' => $request->Contact_Number,
                ]);

                // E. IF INSURED -> Save to Insured_Patient Table
                if ($request->PatientType === 'Insured') {
                    // Generate InsPatientID (INS-XXXX)
                    $insCount = \App\Models\InsuredPatient::count() + 1;
                    $insID = 'INS-' . str_pad($insCount, 4, '0', STR_PAD_LEFT);

                    \App\Models\InsuredPatient::create([
                        'InsPatientID'   => $insID,
                        'PatientID'      => $patientID,
                        'Provider_Name'  => $request->Provider_Name,
                        'Policy_Number'  => $request->Policy_Number,
                        'Coverage_Limit' => $request->Coverage_Limit,
                    ]);
                }
            });

            return redirect()->route('reception.patients')->with('success', 'Patient registered successfully.');
        }
}
