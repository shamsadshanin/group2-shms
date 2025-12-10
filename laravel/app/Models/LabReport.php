<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabReport extends Model
{
    use HasFactory;

    protected $table = 'tbllabtest';
    protected $primaryKey = 'cTestID';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'cTestID',
        'cPatientID',
        'cDoctorID',
        'cLabTechnicianID',
        'dTestDate',
        'cTestType',
        'cTestResults',
        'cStatus',
    ];
}
