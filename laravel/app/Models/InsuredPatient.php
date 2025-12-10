<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InsuredPatient extends Model
{
    use HasFactory;

    protected $table = 'tblinsuredpatient';
    protected $primaryKey = 'cPatientID';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'cPatientID',
        'cProviderName',
        'cPolicyNumber',
        'nCoverageLimit'
    ];

    protected $casts = [
        'nCoverageLimit' => 'decimal:2'
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class, 'cPatientID', 'cPatientID');
    }
}