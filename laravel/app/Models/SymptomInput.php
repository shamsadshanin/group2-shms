<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SymptomInput extends Model
{
    use HasFactory;

    protected $table = 'Symptom_Input';
    protected $primaryKey = 'InputID';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false; // Based on your SQL

    protected $fillable = [
        'InputID',
        'PatientID',
        'Description',
        'InputDate'
    ];

    public function disease()
    {
        // One Input has One Prediction
        return $this->hasOne(DiseasePrediction::class, 'InputID', 'InputID');
    }
}
