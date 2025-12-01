<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $primaryKey = 'AppointmentID';

    protected $fillable = [
        'PatientID', 'DoctorID', 'Date', 'Time',
        'Purpose', 'Status', 'Notes'
    ];

    protected $casts = [
        'Date' => 'date',
    ];

    // Relationship with Patient
    public function patient()
    {
        return $this->belongsTo(Patient::class, 'PatientID');
    }

    // Relationship with Doctor
    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'DoctorID');
    }

    // Relationship with Prescription
    public function prescription()
    {
        return $this->hasOne(Prescription::class, 'AppointmentID');
    }

    // Check if appointment has prescription
    public function hasPrescription()
    {
        return $this->prescription !== null;
    }

    // Scope for today's appointments
    public function scopeToday($query)
    {
        return $query->where('Date', today()->format('Y-m-d'));
    }

    // Scope for upcoming appointments
    public function scopeUpcoming($query)
    {
        return $query->where('Date', '>=', today()->format('Y-m-d'))
                    ->whereIn('Status', ['Pending', 'Confirmed']);
    }

    // Check if appointment can be updated
    public function canBeUpdated()
    {
        return in_array($this->Status, ['Pending', 'Confirmed']) &&
               now()->lt($this->Date);
    }
}
