<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabTechnician extends Model
{
    use HasFactory;

    protected $primaryKey = 'StaffID';
    public $incrementing = true;

    protected $fillable = [
        'user_id', 'Name', 'Department', 'Qualification',
        'LicenseNumber', 'ContactNumber', 'Email', 'IsActive'
    ];

    protected $casts = [
        'IsActive' => 'boolean',
    ];

    // Relationship with User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship with Investigations
    public function investigations()
    {
        return $this->hasMany(Investigation::class, 'StaffID');
    }

    // Get pending investigations count
    public function getPendingInvestigationsCount()
    {
        return $this->investigations()
            ->where('Status', 'Pending')
            ->orWhere('Status', 'Assigned')
            ->count();
    }

    // Get completed investigations count
    public function getCompletedInvestigationsCount()
    {
        return $this->investigations()
            ->where('Status', 'Completed')
            ->count();
    }

    // Check if technician is available (less than 10 pending tests)
    public function isAvailable()
    {
        return $this->getPendingInvestigationsCount() < 10;
    }
}
