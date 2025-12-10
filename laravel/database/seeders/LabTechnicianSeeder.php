<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\LabTechnician;

class LabTechnicianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        LabTechnician::create([
            'cLabTechnicianID' => 'LT-001',
            'cName' => 'Tanvir Hasan',
            'cEmail' => 'tanvir@gmail.com',
            'cContactNumber' => '01711000000',
        ]);
    }
}
