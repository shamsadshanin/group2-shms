<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LabTest extends Model
{
    use HasFactory;

    protected $table = 'tbllabtest';
    protected $primaryKey = 'cLabTestID';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'cLabTestID',
        'cPatientID',
        'cLabTechnicianID',
        'cTestName',
        'dTestDate',
        'cStatus'
    ];

    protected $casts = [
        'dTestDate' => 'datetime'
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class, 'cPatientID', 'cPatientID');
    }

    public function labTechnician(): BelongsTo
    {
        return $this->belongsTo(LabTechnician::class, 'cLabTechnicianID', 'cLabTechnicianID');
    }
}
