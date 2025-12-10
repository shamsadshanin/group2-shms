<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\InsuranceClaim;

class InsuranceClaimSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        InsuranceClaim::create([
            'cClaimID' => 'CLAIM001',
            'cPatientID' => 'P001',
            'cInsuranceID' => 'INS001',
            'nClaimAmount' => 500.00,
            'dClaimDate' => '2023-10-15',
            'cClaimStatus' => 'Approved',
        ]);

        InsuranceClaim::create([
            'cClaimID' => 'CLAIM002',
            'cPatientID' => 'P002',
            'cInsuranceID' => 'INS002',
            'nClaimAmount' => 1200.00,
            'dClaimDate' => '2023-11-01',
            'cClaimStatus' => 'Pending',
        ]);
    }
}