<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Billing extends Model
{
    use HasFactory;

    protected $primaryKey = 'InvoiceID';

    protected $fillable = [
        'PatientID', 'AppointmentID', 'InvoiceNumber', 'ConsultationFee',
        'TestFees', 'MedicineFees', 'TotalAmount', 'Discount', 'TaxAmount',
        'FinalAmount', 'IssueDate', 'DueDate', 'PaymentStatus', 'PaymentMode',
        'TransactionID', 'Notes'
    ];

    protected $casts = [
        'ConsultationFee' => 'decimal:2',
        'TestFees' => 'decimal:2',
        'MedicineFees' => 'decimal:2',
        'TotalAmount' => 'decimal:2',
        'Discount' => 'decimal:2',
        'TaxAmount' => 'decimal:2',
        'FinalAmount' => 'decimal:2',
        'IssueDate' => 'date',
        'DueDate' => 'date',
    ];

    // Relationship with Patient
    public function patient()
    {
        return $this->belongsTo(Patient::class, 'PatientID');
    }

    // Relationship with Appointment
    public function appointment()
    {
        return $this->belongsTo(Appointment::class, 'AppointmentID');
    }

    // Scope for paid billings
    public function scopePaid($query)
    {
        return $query->where('PaymentStatus', 'Paid');
    }

    // Scope for pending billings
    public function scopePending($query)
    {
        return $query->where('PaymentStatus', 'Pending');
    }

    // Scope for overdue billings
    public function scopeOverdue($query)
    {
        return $query->where('DueDate', '<', now())
                    ->where('PaymentStatus', 'Pending');
    }

    // Check if billing is overdue
    public function isOverdue()
    {
        return $this->DueDate < now() && $this->PaymentStatus === 'Pending';
    }

    // Calculate days overdue
    public function getDaysOverdue()
    {
        if ($this->isOverdue()) {
            return now()->diffInDays($this->DueDate);
        }
        return 0;
    }

    // Generate next invoice number
    public static function generateInvoiceNumber()
    {
        $lastInvoice = self::latest('InvoiceID')->first();
        $nextId = $lastInvoice ? $lastInvoice->InvoiceID + 1 : 1;
        return 'INV-' . date('Y') . '-' . str_pad($nextId, 6, '0', STR_PAD_LEFT);
    }
}
