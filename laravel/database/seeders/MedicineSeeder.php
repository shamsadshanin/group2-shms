<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Medicine;
use Carbon\Carbon;

class MedicineSeeder extends Seeder
{
    public function run()
    {
        $medicines = [
            [
                'CategoryID' => 1,
                'Name' => 'Paracetamol',
                'GenericName' => 'Acetaminophen',
                'BrandName' => 'Tylenol',
                'SKU' => 'MED-001',
                'Description' => 'Pain reliever and fever reducer',
                'DosageForm' => 'Tablet',
                'Strength' => '500mg',
                'StockQuantity' => 150,
                'ReorderLevel' => 20,
                'UnitPrice' => 0.50,
                'CostPrice' => 0.25,
                'ExpiryDate' => Carbon::now()->addYears(2),
                'StorageConditions' => 'Room temperature',
                'Manufacturer' => 'Johnson & Johnson',
                'RequiresPrescription' => false,
            ],
            [
                'CategoryID' => 2,
                'Name' => 'Amoxicillin',
                'GenericName' => 'Amoxicillin',
                'BrandName' => 'Amoxil',
                'SKU' => 'MED-002',
                'Description' => 'Broad-spectrum antibiotic',
                'DosageForm' => 'Capsule',
                'Strength' => '500mg',
                'StockQuantity' => 80,
                'ReorderLevel' => 15,
                'UnitPrice' => 2.50,
                'CostPrice' => 1.25,
                'ExpiryDate' => Carbon::now()->addYears(3),
                'StorageConditions' => 'Room temperature',
                'Manufacturer' => 'GlaxoSmithKline',
                'RequiresPrescription' => true,
            ],
            [
                'CategoryID' => 3,
                'Name' => 'Loratadine',
                'GenericName' => 'Loratadine',
                'BrandName' => 'Claritin',
                'SKU' => 'MED-003',
                'Description' => 'Non-drowsy allergy relief',
                'DosageForm' => 'Tablet',
                'Strength' => '10mg',
                'StockQuantity' => 45,
                'ReorderLevel' => 10,
                'UnitPrice' => 1.20,
                'CostPrice' => 0.60,
                'ExpiryDate' => Carbon::now()->addYears(2),
                'StorageConditions' => 'Room temperature',
                'Manufacturer' => 'Bayer',
                'RequiresPrescription' => false,
            ],
            // Add more sample medicines as needed
        ];

        foreach ($medicines as $medicine) {
            Medicine::create($medicine);
        }
    }
}
