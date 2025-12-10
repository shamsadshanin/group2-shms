<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Patient;
use App\Models\User;
use Carbon\Carbon;

class PatientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rahimUser = User::where('email', 'rahim@gmail.com')->first();
        if ($rahimUser) {
            Patient::create([
                'cPatientID' => 'P-001',
                'cUserID' => $rahimUser->id,
                'cName' => 'Rahim Uddin',
                'dDOB' => Carbon::now()->subYears(45)->format('Y-m-d'),
                'cGender' => 'Male',
                'cEmail' => 'rahim@gmail.com',
                'cAddress' => '123 Dhaka St',
                'cPhone' => '01811000000',
            ]);
        }

        $fatimaUser = User::where('email', 'fatima@gmail.com')->first();
        if ($fatimaUser) {
            Patient::create([
                'cPatientID' => 'P-002',
                'cUserID' => $fatimaUser->id,
                'cName' => 'Fatima Begum',
                'dDOB' => Carbon::now()->subYears(32)->format('Y-m-d'),
                'cGender' => 'Female',
                'cEmail' => 'fatima@gmail.com',
                'cAddress' => '456 Sylhet Rd',
                'cPhone' => '01811000001',
            ]);
        }

        $karimUser = User::where('email', 'karim@gmail.com')->first();
        if ($karimUser) {
            Patient::create([
                'cPatientID' => 'P-003',
                'cUserID' => $karimUser->id,
                'cName' => 'Karim Mia',
                'dDOB' => Carbon::now()->subYears(60)->format('Y-m-d'),
                'cGender' => 'Male',
                'cEmail' => 'karim@gmail.com',
                'cAddress' => '789 Chittagong Rd',
                'cPhone' => '01811000002',
            ]);
        }
    }
}
