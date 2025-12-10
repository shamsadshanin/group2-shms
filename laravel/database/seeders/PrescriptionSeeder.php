<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Prescription;

class PrescriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Prescription::create([
            'cPrescriptionID' => 'RX-500',
            'cPatientID' => 'P-001',
            'cDoctorID' => 'D-001',
            'dPrescriptionDate' => '2023-11-01 00:00:00',
            'cMedication' => 'Napa Extra',
            'cDosage' => '500mg',
            'cInstructions' => '1+0+1 - 5 Days'
        ]);
    }
}
