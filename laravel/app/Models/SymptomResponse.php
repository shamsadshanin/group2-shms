<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SymptomResponse extends Model
{
    use HasFactory;

    protected $table = 'tblsymptomresponse';
    protected $primaryKey = 'cResponseID';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'cResponseID',
        'cInputID',
        'cResponse',
        'dDateTime'
    ];

    protected $casts = [
        'dDateTime' => 'datetime'
    ];

    public function symptomInput(): BelongsTo
    {
        return $this->belongsTo(SymptomInput::class, 'cInputID', 'cInputID');
    }
}