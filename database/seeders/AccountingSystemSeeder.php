<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Department;
use App\Models\Project;

class AccountingSystemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create departments for each tenant
        $tenants = [1, 2, 3, 4];

        foreach ($tenants as $tenantId) {
            // Create main departments
            $departments = [
                ['code' => 'DEPT001', 'name' => 'الإدارة العامة', 'name_en' => 'General Management'],
                ['code' => 'DEPT002', 'name' => 'قسم المبيعات', 'name_en' => 'Sales Department'],
                ['code' => 'DEPT003', 'name' => 'قسم المشتريات', 'name_en' => 'Purchasing Department'],
                ['code' => 'DEPT004', 'name' => 'قسم المحاسبة', 'name_en' => 'Accounting Department'],
                ['code' => 'DEPT005', 'name' => 'قسم المخازن', 'name_en' => 'Warehouse Department'],
                ['code' => 'DEPT006', 'name' => 'قسم الموارد البشرية', 'name_en' => 'HR Department'],
                ['code' => 'DEPT007', 'name' => 'قسم تقنية المعلومات', 'name_en' => 'IT Department'],
            ];

            foreach ($departments as $dept) {
                Department::firstOrCreate([
                    'tenant_id' => $tenantId,
                    'code' => $dept['code']
                ], [
                    'name' => $dept['name'],
                    'name_en' => $dept['name_en'],
                    'level' => 1,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            // Create projects
            $projects = [
                ['code' => 'PROJ001', 'name' => 'مشروع التطوير الأساسي', 'name_en' => 'Basic Development Project', 'status' => 'active'],
                ['code' => 'PROJ002', 'name' => 'مشروع التوسع', 'name_en' => 'Expansion Project', 'status' => 'planning'],
                ['code' => 'PROJ003', 'name' => 'مشروع التحديث', 'name_en' => 'Upgrade Project', 'status' => 'active'],
                ['code' => 'PROJ004', 'name' => 'مشروع البحث والتطوير', 'name_en' => 'R&D Project', 'status' => 'planning'],
            ];

            foreach ($projects as $proj) {
                Project::firstOrCreate([
                    'tenant_id' => $tenantId,
                    'code' => $proj['code']
                ], [
                    'name' => $proj['name'],
                    'name_en' => $proj['name_en'],
                    'status' => $proj['status'],
                    'budget_amount' => rand(50000, 500000),
                    'actual_amount' => 0,
                    'is_active' => true,
                    'start_date' => now()->subDays(rand(1, 30)),
                    'end_date' => now()->addDays(rand(30, 365)),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }

        $this->command->info('✅ Departments and Projects created successfully for all tenants!');
    }
}
