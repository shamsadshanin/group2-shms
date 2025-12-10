<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\InsuredPatient;

class InsuredPatientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        InsuredPatient::create([
            'cPatientID' => 'P001',
            'cInsuranceID' => 'INS001',
            'cInsuranceProvider' => 'MediCare',
            'cPolicyNumber' => 'POL987654',
        ]);

        InsuredPatient::create([
            'cPatientID' => 'P002',
            'cInsuranceID' => 'INS002',
            'cInsuranceProvider' => 'HealthFirst',
            'cPolicyNumber' => 'POL123456',
        ]);
    }
}