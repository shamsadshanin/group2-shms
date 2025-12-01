<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;

    protected $primaryKey = 'DoctorID';
    public $incrementing = true;

    protected $fillable = [
        'user_id', 'Name', 'Specialization', 'Email',
        'ContactNumber', 'Qualifications', 'ExperienceYears',
        'Availability', 'IsActive'
    ];

    protected $casts = [
        'Availability' => 'array',
        'IsActive' => 'boolean',
    ];

    // Relationship with User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship with Appointments
    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'DoctorID');
    }

    // Relationship with Prescriptions
    public function prescriptions()
    {
        return $this->hasMany(Prescription::class, 'DoctorID');
    }

    // Get today's appointments
    public function todaysAppointments()
    {
        return $this->appointments()
            ->where('Date', today()->format('Y-m-d'))
            ->whereIn('Status', ['Pending', 'Confirmed'])
            ->orderBy('Time', 'asc')
            ->with('patient');
    }

    // Get upcoming appointments
    public function upcomingAppointments()
    {
        return $this->appointments()
            ->where('Date', '>=', today()->format('Y-m-d'))
            ->whereIn('Status', ['Pending', 'Confirmed'])
            ->orderBy('Date', 'asc')
            ->orderBy('Time', 'asc')
            ->with('patient');
    }

    // Check if doctor is available at specific time
    public function isAvailable($date, $time)
    {
        $conflictingAppointment = $this->appointments()
            ->where('Date', $date)
            ->where('Time', $time)
            ->whereIn('Status', ['Pending', 'Confirmed'])
            ->exists();

        return !$conflictingAppointment;
    }
}
