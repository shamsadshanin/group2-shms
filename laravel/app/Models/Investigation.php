<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Investigation extends Model
{
    use HasFactory;

    protected $primaryKey = 'InvestigationID';

    protected $fillable = [
        'PatientID', 'StaffID', 'TestTypeID', 'DoctorID',
        'TestNotes', 'ResultSummary', 'DetailedResults',
        'TestParameters', 'DigitalReport', 'Priority',
        'Status', 'CollectionDate', 'ProcessingDate', 'CompletedDate'
    ];

    protected $casts = [
        'TestParameters' => 'array',
        'CollectionDate' => 'datetime',
        'ProcessingDate' => 'datetime',
        'CompletedDate' => 'datetime',
    ];

    // Relationship with Patient
    public function patient()
    {
        return $this->belongsTo(Patient::class, 'PatientID');
    }

    // Relationship with Lab Technician
    public function technician()
    {
        return $this->belongsTo(LabTechnician::class, 'StaffID');
    }

    // Relationship with Test Type
    public function testType()
    {
        return $this->belongsTo(TestType::class, 'TestTypeID');
    }

    // Relationship with Doctor
    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'DoctorID');
    }

    // Scope for pending investigations
    public function scopePending($query)
    {
        return $query->where('Status', 'Pending');
    }

    // Scope for assigned investigations
    public function scopeAssigned($query)
    {
        return $query->where('Status', 'Assigned');
    }

    // Scope for completed investigations
    public function scopeCompleted($query)
    {
        return $query->where('Status', 'Completed');
    }

    // Scope for high priority investigations
    public function scopeHighPriority($query)
    {
        return $query->where('Priority', 'High')->orWhere('Priority', 'Critical');
    }

    // Check if investigation can be updated
    public function canBeUpdated()
    {
        return in_array($this->Status, ['Pending', 'Assigned', 'Processing']);
    }

    // Get formatted investigation ID
    public function getFormattedId()
    {
        return 'INV-' . str_pad($this->InvestigationID, 6, '0', STR_PAD_LEFT);
    }

    // Calculate turnaround time
    public function getTurnaroundTime()
    {
        if ($this->CompletedDate && $this->CollectionDate) {
            return $this->CollectionDate->diffInHours($this->CompletedDate);
        }
        return null;
    }
}
