<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    // Updated Table Name based on Schema
    protected $table = 'Appointment';

    // Updated Primary Key
    protected $primaryKey = 'AppointmentID';

    // ID is a string (e.g., A-0001), so incrementing is false
    public $incrementing = false;

    protected $keyType = 'string';

    // Updated Fillable columns to match SQL Schema
    protected $fillable = [
        'AppointmentID',
        'PatientID',
        'DoctorID',
        'Date',
        'Time',
        'Purpose',
        'Status',
    ];

    // Updated Casts
    protected $casts = [
        'Date' => 'date',
        // 'Time' is usually best kept as string in Laravel unless you need Carbon instances
    ];

    /**
     * Relationship: An Appointment belongs to a Patient.
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class, 'PatientID', 'PatientID');
    }

    /**
     * Relationship: An Appointment belongs to a Doctor.
     */
    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'DoctorID', 'DoctorID');
    }
}
