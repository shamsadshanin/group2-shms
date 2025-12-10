<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Billing extends Model
{
    use HasFactory;

    protected $table = 'tblbilling';
    protected $primaryKey = 'cBillingID';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'cBillingID',
        'cPatientID',
        'fAmount',
        'dBillingDate',
        'cStatus',
    ];

    /**
     * Get the patient that owns the billing record.
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class, 'cPatientID', 'cPatientID');
    }
}
