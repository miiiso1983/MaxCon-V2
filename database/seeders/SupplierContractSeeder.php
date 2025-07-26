<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SupplierContract;
use App\Models\Supplier;
use App\Models\User;
use Carbon\Carbon;

class SupplierContractSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get first tenant and user
        $user = User::where('tenant_id', '!=', null)->first();
        if (!$user) {
            $this->command->info('No tenant users found. Skipping contract seeding.');
            return;
        }

        $tenantId = $user->tenant_id;

        // Get suppliers for this tenant
        $suppliers = Supplier::where('tenant_id', $tenantId)->get();
        if ($suppliers->isEmpty()) {
            $this->command->info('No suppliers found for tenant. Skipping contract seeding.');
            return;
        }

        $contracts = [
            [
                'contract_number' => 'CON-2024-001',
                'title' => 'عقد توريد الأدوية الأساسية',
                'description' => 'عقد توريد الأدوية الأساسية والمستلزمات الطبية للعام 2024',
                'type' => 'supply',
                'status' => 'active',
                'start_date' => Carbon::now()->subMonths(2),
                'end_date' => Carbon::now()->addMonths(10),
                'signed_date' => Carbon::now()->subMonths(2)->addDays(5),
                'contract_value' => 500000.00,
                'minimum_order_value' => 10000.00,
                'maximum_order_value' => 50000.00,
                'currency' => 'IQD',
                'payment_terms' => 'دفع خلال 30 يوم من تاريخ التسليم',
                'delivery_terms' => 'التسليم خلال 7 أيام عمل',
                'quality_requirements' => 'جميع المنتجات يجب أن تكون مطابقة للمواصفات الدولية',
            ],
            [
                'contract_number' => 'CON-2024-002',
                'title' => 'عقد صيانة المعدات الطبية',
                'description' => 'عقد صيانة دورية للمعدات الطبية والأجهزة المختبرية',
                'type' => 'maintenance',
                'status' => 'active',
                'start_date' => Carbon::now()->subMonth(),
                'end_date' => Carbon::now()->addMonths(11),
                'signed_date' => Carbon::now()->subMonth()->addDays(3),
                'contract_value' => 120000.00,
                'minimum_order_value' => 5000.00,
                'currency' => 'USD',
                'payment_terms' => 'دفع شهري مقدماً',
                'delivery_terms' => 'استجابة خلال 24 ساعة للطوارئ',
                'quality_requirements' => 'فنيين معتمدين مع ضمان قطع الغيار',
            ],
            [
                'contract_number' => 'CON-2023-015',
                'title' => 'عقد توريد المستلزمات المكتبية',
                'description' => 'عقد توريد المستلزمات المكتبية والقرطاسية',
                'type' => 'supply',
                'status' => 'expired',
                'start_date' => Carbon::now()->subYear(),
                'end_date' => Carbon::now()->subMonths(2),
                'signed_date' => Carbon::now()->subYear()->addDays(10),
                'contract_value' => 25000.00,
                'minimum_order_value' => 1000.00,
                'currency' => 'IQD',
                'payment_terms' => 'دفع عند التسليم',
                'delivery_terms' => 'التسليم خلال 3 أيام عمل',
            ],
            [
                'contract_number' => 'CON-2024-003',
                'title' => 'عقد خدمات التنظيف',
                'description' => 'عقد خدمات التنظيف اليومي للمرافق',
                'type' => 'service',
                'status' => 'active',
                'start_date' => Carbon::now()->subDays(15),
                'end_date' => Carbon::now()->addDays(15), // ينتهي قريباً
                'signed_date' => Carbon::now()->subDays(20),
                'contract_value' => 36000.00,
                'minimum_order_value' => 3000.00,
                'currency' => 'IQD',
                'payment_terms' => 'دفع شهري',
                'delivery_terms' => 'خدمة يومية من السبت إلى الخميس',
                'quality_requirements' => 'استخدام مواد تنظيف صديقة للبيئة',
            ],
            [
                'contract_number' => 'CON-2024-004',
                'title' => 'عقد استشاري تطوير النظم',
                'description' => 'عقد استشاري لتطوير وتحسين أنظمة المعلومات',
                'type' => 'consulting',
                'status' => 'draft',
                'start_date' => Carbon::now()->addMonth(),
                'end_date' => Carbon::now()->addMonths(6),
                'contract_value' => 80000.00,
                'currency' => 'USD',
                'payment_terms' => 'دفع على مراحل حسب التقدم',
                'delivery_terms' => 'تسليم المراحل حسب الجدول الزمني المتفق عليه',
            ],
        ];

        foreach ($contracts as $index => $contractData) {
            $supplier = $suppliers->random();

            SupplierContract::create(array_merge($contractData, [
                'tenant_id' => $tenantId,
                'supplier_id' => $supplier->id,
                'created_by' => $user->id,
                'auto_renewal' => rand(0, 1) == 1,
                'renewal_period_months' => rand(6, 24),
            ]));
        }

        $this->command->info('Created ' . count($contracts) . ' sample contracts.');
    }
}
