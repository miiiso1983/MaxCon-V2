<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Warehouse;
use App\Models\WarehouseLocation;
use App\Models\Tenant;
use App\Models\User;

class WarehouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get first tenant
        $tenant = Tenant::first();

        if (!$tenant) {
            $this->command->info('No tenants found. Please create tenants first.');
            return;
        }

        // Get first user as manager
        $manager = User::where('tenant_id', $tenant->id)->first();

        // Create main warehouse
        $mainWarehouse = Warehouse::create([
            'tenant_id' => $tenant->id,
            'code' => 'MA001',
            'name' => 'المستودع الرئيسي',
            'description' => 'المستودع الرئيسي للشركة',
            'location' => 'بغداد - المنطقة الصناعية',
            'address' => 'شارع الصناعة، المنطقة الصناعية، بغداد',
            'phone' => '07901234567',
            'email' => 'warehouse@maxcon.com',
            'manager_id' => $manager ? $manager->id : null,
            'type' => 'main',
            'is_active' => true,
            'total_capacity' => 1000.00,
            'used_capacity' => 250.00,
            'settings' => [
                'temperature_controlled' => true,
                'min_temperature' => 15,
                'max_temperature' => 25,
                'humidity_controlled' => true,
                'security_level' => 'high'
            ]
        ]);

        // Create branch warehouse
        $branchWarehouse = Warehouse::create([
            'tenant_id' => $tenant->id,
            'code' => 'BR001',
            'name' => 'مستودع فرع البصرة',
            'description' => 'مستودع فرع البصرة',
            'location' => 'البصرة - المركز',
            'address' => 'شارع الكورنيش، مركز البصرة',
            'phone' => '07801234567',
            'email' => 'basra@maxcon.com',
            'manager_id' => $manager ? $manager->id : null,
            'type' => 'branch',
            'is_active' => true,
            'total_capacity' => 500.00,
            'used_capacity' => 120.00,
            'settings' => [
                'temperature_controlled' => false,
                'security_level' => 'medium'
            ]
        ]);

        // Create pharmacy warehouse
        $pharmacyWarehouse = Warehouse::create([
            'tenant_id' => $tenant->id,
            'code' => 'PH001',
            'name' => 'صيدلية المركز',
            'description' => 'صيدلية المركز الطبي',
            'location' => 'بغداد - الكرادة',
            'address' => 'شارع الكرادة الداخل، بغداد',
            'phone' => '07701234567',
            'email' => 'pharmacy@maxcon.com',
            'manager_id' => $manager ? $manager->id : null,
            'type' => 'pharmacy',
            'is_active' => true,
            'total_capacity' => 100.00,
            'used_capacity' => 75.00,
            'settings' => [
                'temperature_controlled' => true,
                'min_temperature' => 18,
                'max_temperature' => 22,
                'humidity_controlled' => true,
                'security_level' => 'high',
                'pharmaceutical_grade' => true
            ]
        ]);

        // Create locations for main warehouse
        $this->createWarehouseLocations($mainWarehouse);
        $this->createWarehouseLocations($branchWarehouse);
        $this->createWarehouseLocations($pharmacyWarehouse);

        $this->command->info('Warehouses created successfully!');
    }

    private function createWarehouseLocations(Warehouse $warehouse)
    {
        $locations = [
            // Zone A
            ['code' => 'A-01-01', 'name' => 'المنطقة A - الممر 1 - الرف 1', 'zone' => 'A', 'aisle' => '01', 'shelf' => '01', 'type' => 'shelf', 'capacity' => 50],
            ['code' => 'A-01-02', 'name' => 'المنطقة A - الممر 1 - الرف 2', 'zone' => 'A', 'aisle' => '01', 'shelf' => '02', 'type' => 'shelf', 'capacity' => 50],
            ['code' => 'A-01-03', 'name' => 'المنطقة A - الممر 1 - الرف 3', 'zone' => 'A', 'aisle' => '01', 'shelf' => '03', 'type' => 'shelf', 'capacity' => 50],
            ['code' => 'A-02-01', 'name' => 'المنطقة A - الممر 2 - الرف 1', 'zone' => 'A', 'aisle' => '02', 'shelf' => '01', 'type' => 'shelf', 'capacity' => 50],
            ['code' => 'A-02-02', 'name' => 'المنطقة A - الممر 2 - الرف 2', 'zone' => 'A', 'aisle' => '02', 'shelf' => '02', 'type' => 'shelf', 'capacity' => 50],

            // Zone B
            ['code' => 'B-01-01', 'name' => 'المنطقة B - الممر 1 - الرف 1', 'zone' => 'B', 'aisle' => '01', 'shelf' => '01', 'type' => 'shelf', 'capacity' => 50],
            ['code' => 'B-01-02', 'name' => 'المنطقة B - الممر 1 - الرف 2', 'zone' => 'B', 'aisle' => '01', 'shelf' => '02', 'type' => 'shelf', 'capacity' => 50],
            ['code' => 'B-02-01', 'name' => 'المنطقة B - الممر 2 - الرف 1', 'zone' => 'B', 'aisle' => '02', 'shelf' => '01', 'type' => 'shelf', 'capacity' => 50],

            // Zone C (Cold Storage)
            ['code' => 'C-01-01', 'name' => 'التبريد - الممر 1 - الرف 1', 'zone' => 'C', 'aisle' => '01', 'shelf' => '01', 'type' => 'shelf', 'capacity' => 30, 'properties' => ['temperature_controlled' => true, 'min_temp' => 2, 'max_temp' => 8]],
            ['code' => 'C-01-02', 'name' => 'التبريد - الممر 1 - الرف 2', 'zone' => 'C', 'aisle' => '01', 'shelf' => '02', 'type' => 'shelf', 'capacity' => 30, 'properties' => ['temperature_controlled' => true, 'min_temp' => 2, 'max_temp' => 8]],
        ];

        foreach ($locations as $locationData) {
            WarehouseLocation::create(array_merge($locationData, [
                'warehouse_id' => $warehouse->id,
                'is_active' => true,
                'used_capacity' => rand(0, $locationData['capacity'] * 0.8), // Random usage up to 80%
            ]));
        }
    }
}
