<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        // Create admin user
        $admin = User::create([
            'name' => 'System Administrator',
            'email' => 'admin@smarthealth.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'is_active' => true,
            'timezone' => 'UTC',
        ]);

        // Log the admin creation (you might need to handle this differently in seeder)
        \Log::info('Admin user created successfully', ['email' => $admin->email]);

        $this->command->info('Admin user created:');
        $this->command->info('Email: admin@smarthealth.com');
        $this->command->info('Password: admin123');
    }
}
