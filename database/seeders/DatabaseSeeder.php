<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        User::factory()->create([
            'name' => 'مدير النظام',
            'email' => 'admin@maxcon.app',
            'password' => bcrypt('password'),
        ]);

        // Run basic data seeder
        $this->call([
            BasicDataSeeder::class,
            ComprehensivePermissionsSeeder::class,
        ]);
    }
}
