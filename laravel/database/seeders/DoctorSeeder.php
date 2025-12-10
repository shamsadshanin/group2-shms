<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Doctor;

class DoctorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Doctor::create([
            'cDoctorID' => 'D-001',
            'cName' => 'Dr. Ahmed Khan',
            'cSpecialization' => 'Cardiology',
            'cEmail' => 'ahmed@hospital.com',
            'cContactNumber' => '01711000000',
            'cAvailability' => 'Mon-Wed 10am-2pm',
        ]);

        Doctor::create([
            'cDoctorID' => 'D-002',
            'cName' => 'Dr. Sarah Rahman',
            'cSpecialization' => 'Neurology',
            'cEmail' => 'sarah@hospital.com',
            'cContactNumber' => '01711000001',
            'cAvailability' => 'Thu-Sat 4pm-8pm',
        ]);

        Doctor::create([
            'cDoctorID' => 'D-003',
            'cName' => 'Dr. Rafiqul Islam',
            'cSpecialization' => 'General Medicine',
            'cEmail' => 'rafiq@hospital.com',
            'cContactNumber' => '01711000002',
            'cAvailability' => 'Sun-Thu 9am-5pm',
        ]);
    }
}
