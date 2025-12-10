<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MedicalRecord extends Model
{
    use HasFactory;

    protected $table = 'tblmedicalrecord';
    protected $primaryKey = 'cRecordID';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'cRecordID',
        'cPatientID',
        'cDoctorID',
        'cDiagnosisDetails',
        'cTreatmentNotes',
        'cSymptoms',
        'dRecordDate',
        'cFollowUpRequired'
    ];

    protected $casts = [
        'dRecordDate' => 'date'
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