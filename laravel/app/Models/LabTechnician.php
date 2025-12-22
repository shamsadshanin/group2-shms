<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LabTechnician extends Model
{
    use HasFactory;

    // SQL টেবিলের নাম
    protected $table = 'Lab_Technician';

    // SQL প্রাইমারি কী
    protected $primaryKey = 'StaffID';

    public $incrementing = false;
    protected $keyType = 'string';

    // টেবিলে created_at/updated_at নেই
    public $timestamps = false;

    // কলামের নাম (SQL অনুযায়ী)
    protected $fillable = [
        'StaffID',
        'First_Name',
        'Last_Name',
        'Department',
        'Email'
    ];

    // রিলেশনশিপ: Investigation টেবিলের সাথে (LabTest মডেলের মাধ্যমে)
    public function labTests(): HasMany
    {
        return $this->hasMany(LabTest::class, 'StaffID', 'StaffID');
    }

    // নাম একসাথে দেখানোর জন্য হেল্পার (Optional)
    public function getFullNameAttribute()
    {
        return "{$this->First_Name} {$this->Last_Name}";
    }
}
