<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    use HasFactory;

    protected $primaryKey = 'MedicineID';

    protected $fillable = [
        'CategoryID', 'SupplierID', 'Name', 'GenericName', 'BrandName',
        'SKU', 'Description', 'DosageForm', 'Strength', 'StockQuantity',
        'ReorderLevel', 'UnitPrice', 'CostPrice', 'ExpiryDate',
        'StorageConditions', 'Manufacturer', 'RequiresPrescription', 'IsActive'
    ];

    protected $casts = [
        'UnitPrice' => 'decimal:2',
        'CostPrice' => 'decimal:2',
        'ExpiryDate' => 'date',
        'RequiresPrescription' => 'boolean',
        'IsActive' => 'boolean',
    ];

    // Relationship with Category
    public function category()
    {
        return $this->belongsTo(MedicineCategory::class, 'CategoryID');
    }

    // Relationship with Supplier
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'SupplierID');
    }

    // Relationship with Prescriptions
    public function prescriptions()
    {
        return $this->hasMany(Prescription::class, 'MedicineID');
    }

    // Relationship with Dispensings
    public function dispensings()
    {
        return $this->hasMany(Dispensing::class, 'MedicineID');
    }

    // Scope for active medicines
    public function scopeActive($query)
    {
        return $query->where('IsActive', true);
    }

    // Scope for low stock medicines
    public function scopeLowStock($query)
    {
        return $query->where('StockQuantity', '<=', DB::raw('ReorderLevel'))
                    ->where('IsActive', true);
    }

    // Scope for expired medicines
    public function scopeExpired($query)
    {
        return $query->where('ExpiryDate', '<', now())
                    ->where('IsActive', true);
    }

    // Scope for expiring soon (within 30 days)
    public function scopeExpiringSoon($query)
    {
        return $query->whereBetween('ExpiryDate', [now(), now()->addDays(30)])
                    ->where('IsActive', true);
    }

    // Check if medicine is low in stock
    public function isLowStock()
    {
        return $this->StockQuantity <= $this->ReorderLevel;
    }

    // Check if medicine is expired
    public function isExpired()
    {
        return $this->ExpiryDate < now();
    }

    // Check if medicine is expiring soon (within 30 days)
    public function isExpiringSoon()
    {
        return $this->ExpiryDate >= now() && $this->ExpiryDate <= now()->addDays(30);
    }
// Get stock status
    public function getStockStatus()
    {
        if ($this->isExpired()) {
            return 'Expired';
        } elseif ($this->StockQuantity == 0) {
            return 'Out of Stock';
        } elseif ($this->isLowStock()) {
            return 'Low Stock';
        } else {
            return 'In Stock';
        }
    }

    // Get stock status color
    public function getStockStatusColor()
    {
        switch ($this->getStockStatus()) {
            case 'Expired':
                return 'red';
            case 'Out of Stock':
                return 'red';
            case 'Low Stock':
                return 'yellow';
            default:
                return 'green';
        }
    }

    // Update stock quantity
    public function updateStock($quantity)
    {
        $this->StockQuantity += $quantity;
        $this->save();
    }

}
