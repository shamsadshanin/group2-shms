<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    use HasFactory;

    protected $table = 'Prescription';
    protected $primaryKey = 'PrescriptionID';
    public $incrementing = false;
    protected $keyType = 'string';

    // FIX: Disable Timestamps (created_at, updated_at)
    public $timestamps = false;

    protected $fillable = [
        'PrescriptionID',
        'IssueDate',
        'PatientID',
        'DoctorID',
        // 'Status', // REMOVED THIS
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'PatientID', 'PatientID');
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'DoctorID', 'DoctorID');
    }

    public function medicines()
        {
            // Relationship to the child table 'Prescription_Medicine'
            return $this->hasMany(PrescriptionMedicine::class, 'PrescriptionID', 'PrescriptionID');
        }
}
