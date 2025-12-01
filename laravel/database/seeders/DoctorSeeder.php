<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DoctorSeeder extends Seeder
{
    public function run()
    {
        $doctors = [
            [
                'name' => 'Dr. John Smith',
                'email' => 'john.smith@hospital.com',
                'specialization' => 'Cardiology',
                'contact' => '+1234567890',
                'qualifications' => 'MD, Board Certified Cardiologist',
                'experience' => 15,
            ],
            [
                'name' => 'Dr. Sarah Johnson',
                'email' => 'sarah.johnson@hospital.com',
                'specialization' => 'Neurology',
                'contact' => '+1234567891',
                'qualifications' => 'MD, PhD Neurology',
                'experience' => 12,
            ],
            [
                'name' => 'Dr. Michael Brown',
                'email' => 'michael.brown@hospital.com',
                'specialization' => 'Pediatrics',
                'contact' => '+1234567892',
                'qualifications' => 'MD, Pediatric Specialist',
                'experience' => 8,
            ],
        ];

        foreach ($doctors as $doctorData) {
            // Create user account
            $user = User::create([
                'name' => $doctorData['name'],
                'email' => $doctorData['email'],
                'password' => Hash::make('password123'),
                'role' => 'doctor',
            ]);

            // Create doctor profile
            Doctor::create([
                'user_id' => $user->id,
                'Name' => $doctorData['name'],
                'Specialization' => $doctorData['specialization'],
                'Email' => $doctorData['email'],
                'ContactNumber' => $doctorData['contact'],
                'Qualifications' => $doctorData['qualifications'],
                'ExperienceYears' => $doctorData['experience'],
                'Availability' => json_encode([
                    'monday' => ['09:00-17:00'],
                    'tuesday' => ['09:00-17:00'],
                    'wednesday' => ['09:00-17:00'],
                    'thursday' => ['09:00-17:00'],
                    'friday' => ['09:00-17:00'],
                ]),
                'IsActive' => true,
            ]);
        }
    }
}
