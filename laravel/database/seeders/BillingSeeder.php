<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Billing;

class BillingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Billing::create([
            'cBillingID' => 'B-2001',
            'cPatientID' => 'P-001',
            'fAmount' => 500,
            'dBillingDate' => '2023-11-01',
            'cStatus' => 'Paid',
        ]);
    }
}
