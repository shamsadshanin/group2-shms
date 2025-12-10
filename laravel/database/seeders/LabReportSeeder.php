<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\LabReport;

class LabReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        LabReport::create([
            'cTestID' => 'L-3001',
            'cPatientID' => 'P-001',
            'cDoctorID' => 'D-001',
            'cTestType' => 'Blood Test',
            'dTestDate' => '2023-11-01',
            'cStatus' => 'Completed',
        ]);

        LabReport::create([
            'cTestID' => 'L-3002',
            'cPatientID' => 'P-002',
            'cDoctorID' => 'D-002',
            'cTestType' => 'X-Ray',
            'dTestDate' => '2023-11-02',
            'cStatus' => 'Pending',
        ]);
    }
}
