<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestType extends Model
{
    use HasFactory;

    protected $primaryKey = 'TestTypeID';

    protected $fillable = [
        'TestName', 'Category', 'Description', 'Price',
        'ProcessingTime', 'NormalRanges', 'IsActive'
    ];

    protected $casts = [
        'NormalRanges' => 'array',
        'IsActive' => 'boolean',
    ];

    // Relationship with Investigations
    public function investigations()
    {
        return $this->hasMany(Investigation::class, 'TestTypeID');
    }

    // Scope for active test types
    public function scopeActive($query)
    {
        return $query->where('IsActive', true);
    }

    // Scope by category
    public function scopeByCategory($query, $category)
    {
        return $query->where('Category', $category);
    }

    // Get processing time in readable format
    public function getProcessingTimeFormatted()
    {
        if ($this->ProcessingTime < 24) {
            return $this->ProcessingTime . ' hours';
        } else {
            return floor($this->ProcessingTime / 24) . ' days';
        }
    }
}
