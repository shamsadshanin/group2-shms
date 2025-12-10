<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NonInsuredPatient extends Model
{
    use HasFactory;

    protected $table = 'tblnoninsuredpatient';
    protected $primaryKey = 'cPatientID';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'cPatientID',
        'dSubmissionDate',
        'cApprovalStatus'
    ];

    protected $casts = [
        'dSubmissionDate' => 'date'
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class, 'cPatientID', 'cPatientID');
    }
}