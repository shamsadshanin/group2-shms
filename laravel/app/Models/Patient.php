<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable; // If using as User, otherwise Model

class Patient extends Model
{
    use HasFactory;

    protected $table = 'Patient';
    protected $primaryKey = 'PatientID';
    public $incrementing = false;
    protected $keyType = 'string';

    // Schema has no created_at/updated_at columns in Patient table?
    // Checking SQL: "created_at" timestamp NULL DEFAULT NULL. Yes it has.
    public $timestamps = true;

    protected $fillable = [
        'PatientID',
        'user_id',
        'First_Name',
        'Last_Name',
        'Age',
        'Gender',
        'Email',
        'Street',
        'City',
        'Zip'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'PatientID', 'PatientID');
    }

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class, 'PatientID', 'PatientID');
    }

    public function medicalRecords()
    {
        // Map to Medical_Record table
        return $this->hasMany(MedicalRecord::class, 'PatientID', 'PatientID');
    }

    public function billings()
    {
        // Map to Billing table
        return $this->hasMany(Billing::class, 'PatientID', 'PatientID');
    }

    public function contactNumbers()
    {
        return $this->hasMany(PatientNumber::class, 'PatientID', 'PatientID');
    }

    // Helper for Name
    public function getFullNameAttribute()
    {
        return "{$this->First_Name} {$this->Last_Name}";
    }
/**
     * FIX: Add this relationship to solve the error.
     * Maps to the 'insured_patient' table.
     */
    public function insured_patient()
    {
        return $this->hasOne(InsuredPatient::class, 'PatientID', 'PatientID');
    }
}
