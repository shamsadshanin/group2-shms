<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabTest extends Model
{
    use HasFactory;

    // Mapping to SQL table 'Investigation'
    protected $table = 'Investigation';
    protected $primaryKey = 'InvestigationID';

    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'InvestigationID',
        'PatientID',
        'StaffID',
        'Test',
        'TestType',
        'Result_Summary',
        'DigitalReport'
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'PatientID', 'PatientID');
    }

    public function technician()
    {
        return $this->belongsTo(LabTechnician::class, 'StaffID', 'StaffID');
    }
}
