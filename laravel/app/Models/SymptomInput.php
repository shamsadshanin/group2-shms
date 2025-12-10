<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SymptomInput extends Model
{
    use HasFactory;

    protected $table = 'tblsymptominput';
    protected $primaryKey = 'cInputID';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'cInputID',
        'cPatientID',
        'cDescription',
        'dDate',
        'dTimestamp'
    ];

    protected $casts = [
        'dDate' => 'date',
        'dTimestamp' => 'datetime'
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class, 'cPatientID', 'cPatientID');
    }

    public function symptomResponses(): HasMany
    {
        return $this->hasMany(SymptomResponse::class, 'cInputID', 'cInputID');
    }
}