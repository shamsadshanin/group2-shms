<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiseasePrediction extends Model
{
    use HasFactory;

    protected $table = 'Disease_Prediction';
    protected $primaryKey = 'PredictionID';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'PredictionID',
        'InputID',
        'DiseaseName',
        'Confidence_Score',
        'Prediction_TimeStamp'
    ];

    public function input()
    {
        return $this->belongsTo(SymptomInput::class, 'InputID', 'InputID');
    }
}
