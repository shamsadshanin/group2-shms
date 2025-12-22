<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrescriptionMedicine extends Model
{
    use HasFactory;

    protected $table = 'Prescription_Medicine';

    public $timestamps = false; // Usually pivot/detail tables don't have timestamps

    protected $fillable = [
        'PrescriptionID',
        'Medicine_Name',
        'Dosage',
        'Frequency',
        'Duration',
    ];

    public function prescription()
    {
        return $this->belongsTo(Prescription::class, 'PrescriptionID', 'PrescriptionID');
    }
}
