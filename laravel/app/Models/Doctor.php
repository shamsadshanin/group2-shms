<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Doctor extends Model
{
    use HasFactory;

    // Updated Table Name
    protected $table = 'Doctor';

    // Updated Primary Key
    protected $primaryKey = 'DoctorID';

    // ID is a string (e.g., DR00001), so incrementing is false
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = true;

    // Updated Fillable Attributes
    protected $fillable = [
        'DoctorID',
        'First_Name',
        'Last_Name',
        'Specialization',
        'Email',
    ];

    /**
     * Relationship: A Doctor has many Appointments.
     */
    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class, 'DoctorID', 'DoctorID');
    }

    /**
     * Relationship: A Doctor has many Prescriptions.
     */
    public function prescriptions(): HasMany
    {
        return $this->hasMany(Prescription::class, 'DoctorID', 'DoctorID');
    }

    /**
     * Relationship: A Doctor has contact numbers (stored in Doctor_Number table).
     * Optional: Added helper relationship for the separate contact table.
     */
    public function contacts(): HasMany
    {
        // Assuming you create a DoctorNumber model, otherwise use query builder in controller
        // This maps to the 'Doctor_Number' table
        return $this->hasMany(DoctorNumber::class, 'DoctorID', 'DoctorID');
    }

    /**
     * Relationship: A Doctor has availability (stored in Doctor_Availability table).
     * Optional: Added helper relationship for the separate availability table.
     */
    public function availability(): HasOne
    {
        // This maps to the 'Doctor_Availability' table
        return $this->hasOne(DoctorAvailability::class, 'DoctorID', 'DoctorID');
    }

    /**
     * Accessor: Get the full name of the doctor.
     * Usage: $doctor->full_name
     */
    public function getFullNameAttribute()
    {
        return "{$this->First_Name} {$this->Last_Name}";
    }
}
