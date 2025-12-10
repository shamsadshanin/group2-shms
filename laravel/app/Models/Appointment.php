<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $table = 'tblappointment';

    protected $primaryKey = 'cAppointmentID';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'cAppointmentID',
        'cPatientID',
        'cDoctorID',
        'dAppointmentDateTime',
        'cPurpose', // Added cPurpose
        'cStatus',
    ];

    protected $casts = [
        'dAppointmentDateTime' => 'datetime',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'cPatientID', 'cPatientID');
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'cDoctorID', 'cDoctorID');
    }

    public function labTests()
    {
        return $this->hasMany(LabTest::class, 'cAppointmentID', 'cAppointmentID');
    }

    public function prescription()
    {
        return $this->hasOne(Prescription::class, 'cAppointmentID', 'cAppointmentID');
    }

    public function symptomInputs()
    {
        return $this->hasMany(SymptomInput::class, 'cAppointmentID', 'cAppointmentID');
    }
}
