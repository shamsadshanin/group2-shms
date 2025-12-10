<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $table = 'tblpatient';

    protected $primaryKey = 'cPatientID';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'cPatientID',
        'cUserID',
        'cName',
        'nAge',
        'cGender',
        'cEmail',
        'cAddress',
        'cPhone',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'cUserID');
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'cPatientID', 'cPatientID');
    }

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class, 'cPatientID', 'cPatientID');
    }

    public function medicalRecords()
    {
        return $this->hasMany(MedicalRecord::class, 'cPatientID', 'cPatientID');
    }

    public function labTests()
    {
        return $this->hasMany(LabTest::class, 'cPatientID', 'cPatientID');
    }

    public function billings()
    {
        return $this->hasMany(Billing::class, 'cPatientID', 'cPatientID');
    }

    public function symptomInputs()
    {
        return $this->hasMany(SymptomInput::class, 'cPatientID', 'cPatientID');
    }

    public function insuredPatient()
    {
        return $this->hasOne(InsuredPatient::class, 'cPatientID', 'cPatientID');
    }

    public function nonInsuredPatient()
    {
        return $this->hasOne(NonInsuredPatient::class, 'cPatientID', 'cPatientID');
    }
}
