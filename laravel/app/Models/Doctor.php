<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Doctor extends Model
{
    use HasFactory;

    protected $table = 'tbldoctor';
    protected $primaryKey = 'cDoctorID';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = true;

    protected $fillable = [
        'cDoctorID',
        'cName',
        'cSpecialization',
        'cEmail',
        'cContactNumber',
        'cAvailability'
    ];

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class, 'cDoctorID', 'cDoctorID');
    }

    public function prescriptions(): HasMany
    {
        return $this->hasMany(Prescription::class, 'cDoctorID', 'cDoctorID');
    }

    public function medicalRecords(): HasManyThrough
    {
        return $this->hasManyThrough(MedicalRecord::class, Appointment::class, 'cDoctorID', 'cPatientID', 'cDoctorID', 'cPatientID');
    }

    public function labTests(): HasMany
    {
        return $this->hasMany(LabTest::class, 'cDoctorID', 'cDoctorID');
    }
}
