<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Billing extends Model
{
    use HasFactory;

    protected $table = 'Billing';

    protected $primaryKey = 'InvoicedID';

    public $incrementing = false;
    protected $keyType = 'string';

    // FIX: Disable automatic timestamps
    public $timestamps = false;

    protected $fillable = [
        'InvoicedID',
        'PatientID',
        'Total_Amount',
        'IssueDate',
        'Payment_Status',
    ];

    /**
     * Relationship: Get the patient that owns the billing record.
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class, 'PatientID', 'PatientID');
    }

    /**
     * Relationship: Get the tests/items associated with this billing record.
     */
    public function tests()
    {
        return $this->hasMany(BillingTest::class, 'InvoicedID', 'InvoicedID');
    }
}
