<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LabTechnician extends Model
{
    use HasFactory;

    protected $table = 'tbllabtechnician';
    protected $primaryKey = 'cLabTechnicianID';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'cLabTechnicianID',
        'cName',
        'cEmail',
        'cContactNumber',
        'cSpecialization'
    ];

    public function labTests(): HasMany
    {
        return $this->hasMany(LabTest::class, 'cLabTechnicianID', 'cLabTechnicianID');
    }
}