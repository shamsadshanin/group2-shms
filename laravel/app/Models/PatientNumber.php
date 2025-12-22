<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientNumber extends Model
{
    use HasFactory;

    // Matches SQL Table
    protected $table = 'Patient_Number';

    // This table usually doesn't have a standard 'id' primary key
    // It's a composite of PatientID + Contact_Number, so we disable standard PK handling
    protected $primaryKey = null;
    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'PatientID',
        'Contact_Number',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'PatientID', 'PatientID');
    }
}
