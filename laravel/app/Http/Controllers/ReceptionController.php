<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ReceptionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:reception');
    }

    public function dashboard()
    {
        $appointments = Appointment::with('patient', 'doctor')
            ->where('cStatus', 'Scheduled')
            ->orderBy('dAppointmentDateTime', 'asc')
            ->get();

        return view('reception.dashboard', compact('appointments'));
    }

    public function checkIn(Appointment $appointment)
    {
        $appointment->cStatus = 'Checked-in';
        $appointment->save();

        return redirect()->route('reception.dashboard')->with('success', 'Patient checked in successfully.');
    }

    public function appointments()
    {
        $appointments = Appointment::with('patient', 'doctor')
            ->orderBy('dAppointmentDateTime', 'desc')
            ->paginate(15);

        return view('reception.appointments', compact('appointments'));
    }

    public function patients()
    {
        $patients = Patient::orderBy('cName', 'asc')->paginate(15);

        return view('reception.patients', compact('patients'));
    }

    /**
     * Show the form for creating a new patient.
     *
     * @return \Illuminate\View\View
     */
    public function createPatient()
    {
        return view('reception.patients.create');
    }

    /**
     * Store a newly created patient in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storePatient(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:20'],
            'nAge' => ['required', 'integer', 'min:0'],
            'gender' => ['required', 'string', 'in:Male,Female,Other'],
            'address' => ['required', 'string', 'max:255'],
        ]);

        $email = $request->email;
        if (!$email) {
            $baseEmail = strtolower(str_replace(' ', '.', $request->name));
            $generatedEmail = $baseEmail . '@clinic.com';
            $counter = 1;
            // Ensure generated email is unique
            while (User::where('email', $generatedEmail)->exists()) {
                $generatedEmail = $baseEmail . $counter . '@clinic.com';
                $counter++;
            }
            $email = $generatedEmail;
        }

        // Create a new User
        $user = User::create([
            'name' => $request->name,
            'email' => $email,
            'password' => Hash::make(Str::random(12)), // Generate a random password
            'role' => 'patient',
        ]);

        // Create a new Patient
        Patient::create([
            'cPatientID' => $this->generatePatientId(),
            'cUserID' => $user->id,
            'cName' => $request->name,
            'cAddress' => $request->address,
            'cPhone' => $request->phone,
            'cEmail' => $email,
            'nAge' => $request->nAge,
            'cGender' => $request->gender,
        ]);

        return redirect()->route('reception.patients')->with('success', 'Patient registered successfully.');
    }

    /**
     * Generate a unique Patient ID.
     *
     * @return string
     */
    private function generatePatientId()
    {
        do {
            $id = 'P-' . str_pad(random_int(1, 99999), 5, '0', STR_PAD_LEFT);
        } while (Patient::where('cPatientID', $id)->exists());

        return $id;
    }
}
