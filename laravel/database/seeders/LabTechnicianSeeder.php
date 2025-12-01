<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LabTechnician;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LabTechnicianSeeder extends Seeder
{
    public function run()
    {
        $technicians = [
            [
                'name' => 'Sarah Wilson',
                'email' => 'sarah.wilson@hospital.com',
                'department' => 'Pathology',
                'qualification' => 'MLT, ASCP Certified',
                'contact' => '+1234567893',
            ],
            [
                'name' => 'Michael Chen',
                'email' => 'michael.chen@hospital.com',
                'department' => 'Radiology',
                'qualification' => 'RT(R), ARRT Certified',
                'contact' => '+1234567894',
            ],
            [
                'name' => 'Emily Rodriguez',
                'email' => 'emily.rodriguez@hospital.com',
                'department' => 'Microbiology',
                'qualification' => 'MT, AMT Certified',
                'contact' => '+1234567895',
            ],
        ];

        foreach ($technicians as $techData) {
            // Create user account
            $user = User::create([
                'name' => $techData['name'],
                'email' => $techData['email'],
                'password' => Hash::make('password123'),
                'role' => 'lab_technician',
            ]);

            // Create lab technician profile
            LabTechnician::create([
                'user_id' => $user->id,
                'Name' => $techData['name'],
                'Department' => $techData['department'],
                'Qualification' => $techData['qualification'],
                'ContactNumber' => $techData['contact'],
                'Email' => $techData['email'],
                'IsActive' => true,
            ]);
        }
    }
}
