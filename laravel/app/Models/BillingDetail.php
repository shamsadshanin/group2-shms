<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillingDetail extends Model
{
    use HasFactory;

    // আপনার SQL এ টেবিলের নাম tblbilling_details
    protected $table = 'tblbilling_details';

    // গণহারে ডাটা সেভ করার অনুমতি দেওয়া হলো
    protected $fillable = [
        'cBillingID',
        'cTestName',
        'nQuantity',
        'fUnitPrice',
        'fSubTotal'
    ];

    /**
     * মূল বিলিং টেবিলের সাথে সম্পর্ক
     */
    public function billing()
    {
        return $this->belongsTo(Billing::class, 'cBillingID', 'cBillingID');
    }
}
