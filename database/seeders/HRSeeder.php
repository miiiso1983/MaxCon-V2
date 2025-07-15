<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tenant\HR\Department;
use App\Models\Tenant\HR\Position;
use App\Models\Tenant\HR\LeaveType;

class HRSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Departments
        $departments = [
            [
                'name' => 'الإدارة العامة',
                'code' => 'DEPT001',
                'description' => 'الإدارة العليا والتخطيط الاستراتيجي',
                'is_active' => true,
                'tenant_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'الموارد البشرية',
                'code' => 'DEPT002',
                'description' => 'إدارة شؤون الموظفين والتوظيف',
                'is_active' => true,
                'tenant_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'المالية والمحاسبة',
                'code' => 'DEPT003',
                'description' => 'إدارة الشؤون المالية والمحاسبية',
                'is_active' => true,
                'tenant_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'المبيعات والتسويق',
                'code' => 'DEPT004',
                'description' => 'إدارة المبيعات والأنشطة التسويقية',
                'is_active' => true,
                'tenant_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'تقنية المعلومات',
                'code' => 'DEPT005',
                'description' => 'إدارة الأنظمة والتقنيات',
                'is_active' => true,
                'tenant_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($departments as $department) {
            Department::create($department);
        }

        // Create Positions
        $positions = [
            [
                'title' => 'مدير عام',
                'code' => 'POS001',
                'description' => 'المدير العام للشركة',
                'level' => 'executive',
                'department_id' => 1,
                'is_active' => true,
                'tenant_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'مدير الموارد البشرية',
                'code' => 'POS002',
                'description' => 'مدير قسم الموارد البشرية',
                'level' => 'manager',
                'department_id' => 2,
                'is_active' => true,
                'tenant_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'مدير المالية',
                'code' => 'POS003',
                'description' => 'مدير الشؤون المالية',
                'level' => 'manager',
                'department_id' => 3,
                'is_active' => true,
                'tenant_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'مدير المبيعات',
                'code' => 'POS004',
                'description' => 'مدير قسم المبيعات',
                'level' => 'manager',
                'department_id' => 4,
                'is_active' => true,
                'tenant_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'مدير تقنية المعلومات',
                'code' => 'POS005',
                'description' => 'مدير قسم تقنية المعلومات',
                'level' => 'manager',
                'department_id' => 5,
                'is_active' => true,
                'tenant_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'محاسب',
                'code' => 'POS006',
                'description' => 'محاسب في قسم المالية',
                'level' => 'mid',
                'department_id' => 3,
                'is_active' => true,
                'tenant_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'مطور برمجيات',
                'code' => 'POS007',
                'description' => 'مطور تطبيقات وأنظمة',
                'level' => 'senior',
                'department_id' => 5,
                'is_active' => true,
                'tenant_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'مندوب مبيعات',
                'code' => 'POS008',
                'description' => 'مندوب مبيعات ميداني',
                'level' => 'mid',
                'department_id' => 4,
                'is_active' => true,
                'tenant_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'أخصائي موارد بشرية',
                'code' => 'POS009',
                'description' => 'أخصائي في شؤون الموظفين',
                'level' => 'mid',
                'department_id' => 2,
                'is_active' => true,
                'tenant_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'مساعد إداري',
                'code' => 'POS010',
                'description' => 'مساعد في الأعمال الإدارية',
                'level' => 'junior',
                'department_id' => 1,
                'is_active' => true,
                'tenant_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($positions as $position) {
            Position::create($position);
        }

        // Create Leave Types
        $leaveTypes = [
            [
                'name' => 'إجازة سنوية',
                'code' => 'ANNUAL',
                'description' => 'الإجازة السنوية العادية',
                'days_per_year' => 30,
                'is_paid' => true,
                'requires_approval' => true,
                'max_consecutive_days' => 15,
                'is_active' => true,
                'tenant_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'إجازة مرضية',
                'code' => 'SICK',
                'description' => 'إجازة للحالات المرضية',
                'days_per_year' => 15,
                'is_paid' => true,
                'requires_approval' => true,
                'max_consecutive_days' => 7,
                'is_active' => true,
                'tenant_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'إجازة طارئة',
                'code' => 'EMERGENCY',
                'description' => 'إجازة للحالات الطارئة',
                'days_per_year' => 7,
                'is_paid' => true,
                'requires_approval' => true,
                'max_consecutive_days' => 3,
                'is_active' => true,
                'tenant_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'إجازة أمومة',
                'code' => 'MATERNITY',
                'description' => 'إجازة الأمومة',
                'days_per_year' => 90,
                'is_paid' => true,
                'requires_approval' => true,
                'max_consecutive_days' => 90,
                'is_active' => true,
                'tenant_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'إجازة بدون راتب',
                'code' => 'UNPAID',
                'description' => 'إجازة بدون راتب',
                'days_per_year' => 365,
                'is_paid' => false,
                'requires_approval' => true,
                'max_consecutive_days' => 30,
                'is_active' => true,
                'tenant_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($leaveTypes as $leaveType) {
            LeaveType::create($leaveType);
        }
    }
}
