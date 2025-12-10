<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Prescription extends Model
{
    use HasFactory;

    protected $table = 'tblprescription';
    protected $primaryKey = 'cPrescriptionID';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = true;

    protected $fillable = [
        'cPrescriptionID',
        'cPatientID',
        'cDoctorID',
        'cMedication',
        'cDosage',
        'cInstructions',
        'dPrescriptionDate',
    ];

    protected $casts = [
        'dPrescriptionDate' => 'date'
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class, 'cPatientID', 'cPatientID');
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class, 'cDoctorID', 'cDoctorID');
    }
}
