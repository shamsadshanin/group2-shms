<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InsuredPatient extends Model
{
    use HasFactory;

    protected $table = 'tblinsuredpatient';
    protected $primaryKey = 'cInsuranceID'; // SQL অনুযায়ী PK হলো cInsuranceID
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'cInsuranceID',
        'cPatientID',
        'cInsuranceCompany',
        'cPolicyNumber',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'cPatientID', 'cPatientID');
    }
}
