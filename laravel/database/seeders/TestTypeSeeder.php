<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TestType;

class TestTypeSeeder extends Seeder
{
    public function run()
    {
        $testTypes = [
            // Hematology
            ['TestName' => 'Complete Blood Count', 'Category' => 'Hematology', 'Price' => 25.00, 'ProcessingTime' => 2],
            ['TestName' => 'Hemoglobin A1c', 'Category' => 'Hematology', 'Price' => 35.00, 'ProcessingTime' => 4],
            ['TestName' => 'Coagulation Profile', 'Category' => 'Hematology', 'Price' => 45.00, 'ProcessingTime' => 6],

            // Biochemistry
            ['TestName' => 'Liver Function Test', 'Category' => 'Biochemistry', 'Price' => 40.00, 'ProcessingTime' => 8],
            ['TestName' => 'Renal Function Test', 'Category' => 'Biochemistry', 'Price' => 35.00, 'ProcessingTime' => 6],
            ['TestName' => 'Lipid Profile', 'Category' => 'Biochemistry', 'Price' => 30.00, 'ProcessingTime' => 4],

            // Microbiology
            ['TestName' => 'Blood Culture', 'Category' => 'Microbiology', 'Price' => 75.00, 'ProcessingTime' => 48],
            ['TestName' => 'Urine Culture', 'Category' => 'Microbiology', 'Price' => 45.00, 'ProcessingTime' => 24],
            ['TestName' => 'Stool Examination', 'Category' => 'Microbiology', 'Price' => 35.00, 'ProcessingTime' => 8],

            // Radiology
            ['TestName' => 'Chest X-Ray', 'Category' => 'Radiology', 'Price' => 60.00, 'ProcessingTime' => 2],
            ['TestName' => 'Abdominal Ultrasound', 'Category' => 'Radiology', 'Price' => 120.00, 'ProcessingTime' => 4],
            ['TestName' => 'CT Scan Head', 'Category' => 'Radiology', 'Price' => 250.00, 'ProcessingTime' => 24],
        ];

        foreach ($testTypes as $testType) {
            TestType::create($testType);
        }
    }
}
