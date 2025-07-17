<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tenant;
use App\Models\Tenant\Regulatory\LaboratoryTest;
use Carbon\Carbon;

class LaboratoryTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get first active tenant
        $tenant = Tenant::where('status', 'active')->first();
        if (!$tenant) {
            $this->command->info('No active tenant found. Please create a tenant first.');
            return;
        }

        $this->command->info("Creating laboratory tests for tenant: {$tenant->name}");

        // Create overdue tests for testing
        $tests = [
            [
                'test_name' => 'فحص مراقبة الجودة - أملوديبين',
                'test_type' => 'quality_control',
                'product_name' => 'أملوديبين 5 ملغ',
                'batch_number' => 'LOT-2024-001',
                'test_date' => Carbon::now()->subDays(15), // 15 days overdue
                'expected_completion_date' => Carbon::now()->subDays(10),
                'status' => 'in_progress',
                'laboratory_name' => 'مختبر الفحوصات المتقدمة',
                'technician_name' => 'أحمد محمد',
                'supervisor_name' => 'د. فاطمة علي',
                'test_method' => 'HPLC',
                'specifications' => 'USP 43',
                'cost' => 500000,
                'priority' => 'high',
                'notes' => 'فحص متأخر - يحتاج متابعة عاجلة'
            ],
            [
                'test_name' => 'اختبار الثبات - أموكسيسيلين',
                'test_type' => 'stability',
                'product_name' => 'أموكسيسيلين 500 ملغ',
                'batch_number' => 'LOT-2024-010',
                'test_date' => Carbon::now()->subDays(8), // 8 days overdue
                'expected_completion_date' => Carbon::now()->subDays(3),
                'status' => 'pending',
                'laboratory_name' => 'مختبرات الشرق الأوسط',
                'technician_name' => 'سعد أحمد',
                'supervisor_name' => 'د. زينب كريم',
                'test_method' => 'Stability Chamber',
                'specifications' => 'ICH Q1A',
                'cost' => 750000,
                'priority' => 'medium',
                'notes' => 'اختبار ثبات طويل المدى'
            ],
            [
                'test_name' => 'فحص ميكروبيولوجي - شراب السعال',
                'test_type' => 'microbiological',
                'product_name' => 'شراب السعال للأطفال',
                'batch_number' => 'LOT-2024-020',
                'test_date' => Carbon::now()->subDays(20), // 20 days overdue
                'expected_completion_date' => Carbon::now()->subDays(17),
                'status' => 'in_progress',
                'laboratory_name' => 'مختبر الأحياء الدقيقة',
                'technician_name' => 'ليلى حسن',
                'supervisor_name' => 'د. عمر صالح',
                'test_method' => 'USP <61>',
                'specifications' => 'USP Microbial Limits',
                'cost' => 300000,
                'priority' => 'high',
                'notes' => 'فحص حرج - تأخير كبير'
            ],
            [
                'test_name' => 'تحليل كيميائي - ديكلوفيناك',
                'test_type' => 'chemical',
                'product_name' => 'ديكلوفيناك 50 ملغ',
                'batch_number' => 'LOT-2024-040',
                'test_date' => Carbon::now()->subDays(5), // 5 days overdue
                'expected_completion_date' => Carbon::now()->subDays(2),
                'status' => 'pending',
                'laboratory_name' => 'مختبر التحليل الكيميائي',
                'technician_name' => 'هدى محمود',
                'supervisor_name' => 'د. طارق أحمد',
                'test_method' => 'UV Spectrophotometry',
                'specifications' => 'BP 2023',
                'cost' => 400000,
                'priority' => 'medium',
                'notes' => 'تحليل تركيز المادة الفعالة'
            ],
            [
                'test_name' => 'فحص فيزيائي - فيتامين د',
                'test_type' => 'physical',
                'product_name' => 'فيتامين د 1000 وحدة',
                'batch_number' => 'LOT-2024-030',
                'test_date' => Carbon::now()->subDays(12), // 12 days overdue
                'expected_completion_date' => Carbon::now()->subDays(9),
                'status' => 'in_progress',
                'laboratory_name' => 'مختبر الفحوصات الفيزيائية',
                'technician_name' => 'أمجد حسن',
                'supervisor_name' => 'د. نور الدين',
                'test_method' => 'Dissolution Test',
                'specifications' => 'USP <711>',
                'cost' => 250000,
                'priority' => 'low',
                'notes' => 'فحص انحلال الأقراص'
            ],
            [
                'test_name' => 'اختبار التكافؤ الحيوي - إيبوبروفين',
                'test_type' => 'bioequivalence',
                'product_name' => 'إيبوبروفين 400 ملغ',
                'batch_number' => 'LOT-2024-050',
                'test_date' => Carbon::now()->subDays(25), // 25 days overdue
                'expected_completion_date' => Carbon::now()->subDays(18),
                'status' => 'pending',
                'laboratory_name' => 'مختبر التكافؤ الحيوي',
                'technician_name' => 'رنا عبدالله',
                'supervisor_name' => 'د. محمد علي',
                'test_method' => 'LC-MS/MS',
                'specifications' => 'FDA Guidance',
                'cost' => 2000000,
                'priority' => 'high',
                'notes' => 'دراسة تكافؤ حيوي - تأخير خطير'
            ],
            [
                'test_name' => 'فحص جودة - باراسيتامول',
                'test_type' => 'quality_control',
                'product_name' => 'باراسيتامول 500 ملغ',
                'batch_number' => 'LOT-2024-060',
                'test_date' => Carbon::now()->subDays(3), // 3 days overdue
                'expected_completion_date' => Carbon::now()->addDays(2),
                'status' => 'in_progress',
                'laboratory_name' => 'مختبر الفحوصات المتقدمة',
                'technician_name' => 'علي حسين',
                'supervisor_name' => 'د. سارة أحمد',
                'test_method' => 'HPLC',
                'specifications' => 'USP 43',
                'cost' => 350000,
                'priority' => 'medium',
                'notes' => 'فحص روتيني'
            ],
            [
                'test_name' => 'اختبار ثبات مسرع - أسبرين',
                'test_type' => 'stability',
                'product_name' => 'أسبرين 100 ملغ',
                'batch_number' => 'LOT-2024-070',
                'test_date' => Carbon::now()->subDays(18), // 18 days overdue
                'expected_completion_date' => Carbon::now()->subDays(14),
                'status' => 'pending',
                'laboratory_name' => 'مختبرات الثبات',
                'technician_name' => 'مريم خالد',
                'supervisor_name' => 'د. يوسف محمد',
                'test_method' => 'Accelerated Stability',
                'specifications' => 'ICH Q1A',
                'cost' => 600000,
                'priority' => 'high',
                'notes' => 'اختبار ثبات مسرع - تأخير كبير'
            ]
        ];

        foreach ($tests as $index => $testData) {
            LaboratoryTest::create(array_merge($testData, [
                'tenant_id' => $tenant->id,
                'product_id' => null, // Make it nullable for now
                'test_number' => 'TEST-' . date('Y') . '-' . str_pad(($index + 1), 3, '0', STR_PAD_LEFT),
                'test_category' => 'finished_product',
                'sample_batch' => $testData['batch_number'],
                'sample_date' => $testData['test_date']
            ]));
        }

        $this->command->info('Laboratory tests created successfully!');
        $this->command->info('Created 8 overdue laboratory tests with different delay periods.');
        $this->command->info('- 3 tests overdue by more than 14 days');
        $this->command->info('- 3 tests overdue by 7-14 days');
        $this->command->info('- 2 tests overdue by less than 7 days');
    }
}
