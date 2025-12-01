<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    use HasFactory;

    protected $primaryKey = 'PrescriptionID';

    protected $fillable = [
        'AppointmentID', 'DoctorID', 'PatientID', 'IssueDate',
        'MedicineName', 'Dosage', 'Frequency', 'Duration',
        'Instructions', 'Notes', 'IsActive'
    ];

    protected $casts = [
        'IssueDate' => 'date',
        'IsActive' => 'boolean',
    ];

    // Relationship with Appointment
    public function appointment()
    {
        return $this->belongsTo(Appointment::class, 'AppointmentID');
    }

    // Relationship with Doctor
    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'DoctorID');
    }

    // Relationship with Patient
    public function patient()
    {
        return $this->belongsTo(Patient::class, 'PatientID');
    }

    // Scope for active prescriptions
    public function scopeActive($query)
    {
        return $query->where('IsActive', true);
    }

    // Get formatted prescription
    public function getFormattedPrescription()
    {
        return "{$this->MedicineName} - {$this->Dosage}, {$this->Frequency} for {$this->Duration}";
    }
}
