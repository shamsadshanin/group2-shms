<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalRecord extends Model
{
    use HasFactory;

    protected $table = 'Medical_Record';

    protected $primaryKey = 'RecordID';

    public $incrementing = false;
    protected $keyType = 'string';

    // SQL টেবিলে created_at/updated_at নেই, তাই এটি false রাখতে হবে
    public $timestamps = false;

    // SQL ফাইলের কলাম অনুযায়ী নামগুলো ঠিক করা হয়েছে
    protected $fillable = [
        'RecordID',
        'PatientID',
        'Disease_Name',        // আগে ছিল Diagnosis/cDiagnosisDetails
        'Symptoms',            // আগে ছিল Treatment/cTreatmentNotes
        'Follow_Up',           // আগে ছিল FollowUpDate
        'Treatment_Start_Date',// আগে ছিল Date/dRecordDate
        'Treatment_End_Date'
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'PatientID', 'PatientID');
    }
}
