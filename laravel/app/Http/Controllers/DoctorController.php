<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Appointment;
use App\Models\Prescription;
use App\Models\Patient;
use App\Http\Requests\PrescriptionRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DoctorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('doctor'); // Custom middleware to ensure user is a doctor
    }

    public function dashboard(Request $request)
    {
        $user = Auth::user();
        $doctor = Doctor::where('user_id', $user->id)->firstOrFail();

        // Base query for appointments
        $query = Appointment::where('DoctorID', $doctor->DoctorID)
                    ->with(['patient', 'prescription'])
                    ->orderBy('Date', 'asc')
                    ->orderBy('Time', 'asc');

        // Apply filters
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->whereHas('patient', function($q) use ($search) {
                $q->where('Name', 'like', "%{$search}%")
                  ->orWhere('Email', 'like', "%{$search}%");
            });
        }

        if ($request->has('status') && $request->status !== 'all') {
            $query->where('Status', $request->status);
        }

        if ($request->has('date')) {
            $query->where('Date', $request->date);
        } else {
            // Default to today and upcoming appointments
            $query->where('Date', '>=', today()->format('Y-m-d'));
        }

        $appointments = $query->paginate(15);

        // Statistics for dashboard
        $stats = [
            'today' => $doctor->todaysAppointments()->count(),
            'upcoming' => $doctor->upcomingAppointments()->count(),
            'pending' => $doctor->appointments()->where('Status', 'Pending')->count(),
            'completed' => $doctor->appointments()->where('Status', 'Completed')->count(),
        ];

        return view('doctor.dashboard', compact('doctor', 'appointments', 'stats'));
    }

    public function updateAppointmentStatus(Request $request, $id)
    {
        try {
            $doctor = Auth::user()->doctor;

            $appointment = Appointment::where('AppointmentID', $id)
                            ->where('DoctorID', $doctor->DoctorID)
                            ->firstOrFail();

            $validated = $request->validate([
                'Status' => 'required|in:Pending,Confirmed,Completed,Cancelled',
                'Notes' => 'nullable|string|max:500',
            ]);

            $appointment->update($validated);

            return redirect()->back()
                           ->with('success', 'Appointment status updated successfully.');

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->back()
                           ->with('error', 'Appointment not found.');
        } catch (\Exception $e) {
            \Log::error('Appointment status update failed: ' . $e->getMessage());

            return redirect()->back()
                           ->with('error', 'Failed to update appointment status. Please try again.');
        }
    }

    public function storePrescription(PrescriptionRequest $request)
    {
        try {
            $doctor = Auth::user()->doctor;

            // Verify the appointment belongs to this doctor
            $appointment = Appointment::where('AppointmentID', $request->AppointmentID)
                            ->where('DoctorID', $doctor->DoctorID)
                            ->firstOrFail();

            $prescription = Prescription::create([
                'AppointmentID' => $request->AppointmentID,
                'DoctorID' => $doctor->DoctorID,
                'PatientID' => $appointment->PatientID,
                'IssueDate' => now(),
                'MedicineName' => $request->MedicineName,
                'Dosage' => $request->Dosage,
                'Frequency' => $request->Frequency,
                'Duration' => $request->Duration,
                'Instructions' => $request->Instructions,
                'Notes' => $request->Notes,
                'IsActive' => true,
            ]);

            // Update appointment status to completed
            $appointment->update([
                'Status' => 'Completed',
                'Notes' => $request->Notes ?: $appointment->Notes
            ]);

            // Here you could trigger notifications (email, SMS, etc.)
            // event(new PrescriptionIssued($prescription));

            return redirect()->route('doctor.dashboard')
                           ->with('success', 'Prescription issued successfully!');

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->back()
                           ->with('error', 'Appointment not found or does not belong to you.')
                           ->withInput();
        } catch (\Exception $e) {
            \Log::error('Prescription creation failed: ' . $e->getMessage());

            return redirect()->back()
                           ->with('error', 'Failed to issue prescription. Please try again.')
                           ->withInput();
        }
    }

    public function patientHistory($patientId)
    {
        try {
            $doctor = Auth::user()->doctor;

            $patient = Patient::where('PatientID', $patientId)->firstOrFail();

            $appointments = Appointment::where('DoctorID', $doctor->DoctorID)
                            ->where('PatientID', $patientId)
                            ->with('prescription')
                            ->orderBy('Date', 'desc')
                            ->get();

            $prescriptions = Prescription::where('DoctorID', $doctor->DoctorID)
                            ->where('PatientID', $patientId)
                            ->with('appointment')
                            ->orderBy('IssueDate', 'desc')
                            ->get();

            return view('doctor.patient-history', compact('patient', 'appointments', 'prescriptions'));

        } catch (\Exception $e) {
            \Log::error('Patient history retrieval failed: ' . $e->getMessage());

            return redirect()->back()
                           ->with('error', 'Failed to load patient history.');
        }
    }

    public function updateAvailability(Request $request)
    {
        try {
            $doctor = Auth::user()->doctor;

            $validated = $request->validate([
                'Availability' => 'nullable|array',
                'IsActive' => 'boolean',
            ]);

            $doctor->update($validated);

            return redirect()->back()
                           ->with('success', 'Availability updated successfully.');

        } catch (\Exception $e) {
            \Log::error('Doctor availability update failed: ' . $e->getMessage());

            return redirect()->back()
                           ->with('error', 'Failed to update availability. Please try again.');
        }
    }
}
