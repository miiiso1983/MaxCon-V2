<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Tenant;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a demo tenant
        $tenant = Tenant::create([
            'name' => 'Demo Company',
            'slug' => 'demo-company',
            'subdomain' => 'demo',
            'status' => 'active',
            'plan' => 'premium',
            'max_users' => 50,
            'storage_limit' => 5368709120, // 5GB
            'trial_ends_at' => now()->addDays(30),
            'contact_info' => [
                'email' => 'admin@demo.com',
                'phone' => '+1234567890',
                'address' => '123 Demo Street, Demo City'
            ]
        ]);

        // Create Super Admin user
        $superAdmin = User::create([
            'name' => 'Super Administrator',
            'email' => 'admin@maxcon.com',
            'password' => Hash::make('password123'),
            'is_active' => true,
        ]);

        $superAdmin->assignRole('super-admin');

        // Create Tenant Admin user
        $tenantAdmin = User::create([
            'tenant_id' => $tenant->id,
            'name' => 'Demo Admin',
            'email' => 'admin@demo.com',
            'password' => Hash::make('password123'),
            'is_active' => true,
        ]);

        $tenantAdmin->assignRole('tenant-admin');

        // Create some demo users
        $manager = User::create([
            'tenant_id' => $tenant->id,
            'name' => 'John Manager',
            'email' => 'manager@demo.com',
            'password' => Hash::make('password123'),
            'is_active' => true,
        ]);

        $manager->assignRole('manager');

        $employee = User::create([
            'tenant_id' => $tenant->id,
            'name' => 'Jane Employee',
            'email' => 'employee@demo.com',
            'password' => Hash::make('password123'),
            'is_active' => true,
        ]);

        $employee->assignRole('employee');

        $customer = User::create([
            'tenant_id' => $tenant->id,
            'name' => 'Bob Customer',
            'email' => 'customer@demo.com',
            'password' => Hash::make('password123'),
            'is_active' => true,
        ]);

        $customer->assignRole('customer');

        $this->command->info('Super Admin and demo users created successfully!');
        $this->command->info('Super Admin: admin@maxcon.com / password123');
        $this->command->info('Tenant Admin: admin@demo.com / password123');
        $this->command->info('Demo Tenant: demo.localhost:8000');
    }
}
