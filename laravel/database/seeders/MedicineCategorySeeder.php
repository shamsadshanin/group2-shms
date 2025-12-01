<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MedicineCategory;

class MedicineCategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['CategoryName' => 'Analgesics', 'Description' => 'Pain relievers'],
            ['CategoryName' => 'Antibiotics', 'Description' => 'Anti-bacterial medications'],
            ['CategoryName' => 'Antihistamines', 'Description' => 'Allergy medications'],
            ['CategoryName' => 'Antacids', 'Description' => 'Acid reducers'],
            ['CategoryName' => 'Antidepressants', 'Description' => 'Mood disorder medications'],
            ['CategoryName' => 'Vitamins', 'Description' => 'Nutritional supplements'],
            ['CategoryName' => 'Cardiovascular', 'Description' => 'Heart and blood pressure medications'],
            ['CategoryName' => 'Diabetes', 'Description' => 'Blood sugar control medications'],
            ['CategoryName' => 'Respiratory', 'Description' => 'Asthma and breathing medications'],
            ['CategoryName' => 'Topical', 'Description' => 'Creams and ointments'],
        ];

        foreach ($categories as $category) {
            MedicineCategory::create($category);
        }
    }
}
