<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MedicalRecord;

class MedicalRecordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MedicalRecord::create([
            'cMedicalRecordID' => 'MR001',
            'cPatientID' => 'P001',
            'cPrimaryDoctorID' => 'D001',
            'tMedicalHistory' => 'Asthma since childhood',
            'tAllergies' => 'Pollen, dust mites',
            'tCurrentMedications' => 'Inhaler as needed',
        ]);

        MedicalRecord::create([
            'cMedicalRecordID' => 'MR002',
            'cPatientID' => 'P002',
            'cPrimaryDoctorID' => 'D002',
            'tMedicalHistory' => 'None',
            'tAllergies' => 'None',
            'tCurrentMedications' => 'None',
        ]);
    }
}