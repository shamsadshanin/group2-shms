<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Appointment;

class AppointmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Appointment::create([
            'cAppointmentID' => 'A-1001',
            'cPatientID' => 'P-001',
            'cDoctorID' => 'D-001',
            'dAppointmentDateTime' => '2023-11-01 10:00:00',
            'cStatus' => 'Completed',
            'cPurpose' => 'Chest Pain'
        ]);

        Appointment::create([
            'cAppointmentID' => 'A-1002',
            'cPatientID' => 'P-002',
            'cDoctorID' => 'D-002',
            'dAppointmentDateTime' => '2023-11-01 11:00:00',
            'cStatus' => 'Completed',
            'cPurpose' => 'Annual Checkup'
        ]);
    }
}
