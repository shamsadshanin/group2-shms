<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NonInsuredPatient extends Model
{
    protected $table = 'tblnoninsuredpatient';
    protected $primaryKey = 'cPatientID'; // SQL অনুযায়ী PK
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'cPatientID',
        'cPaymentMethod',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'cPatientID', 'cPatientID');
    }
}
