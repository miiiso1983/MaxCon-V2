<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tenant;
use App\Models\Tenant\Regulatory\ProductRecall;
use Carbon\Carbon;

class ProductRecallSeeder extends Seeder
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

        $this->command->info("Creating product recalls for tenant: {$tenant->name}");

        // Create high priority recalls for testing
        $recalls = [
            [
                'recall_number' => 'RCL-2025-001',
                'recall_type' => 'mandatory',
                'recall_class' => 'class_i',
                'reason' => 'تلوث ميكروبيولوجي خطير قد يسبب عدوى مهددة للحياة',
                'description' => 'تم اكتشاف تلوث بكتيريا E.coli في عدة دفعات من المنتج',
                'affected_batches' => ['LOT-2024-A001', 'LOT-2024-A002', 'LOT-2024-A003'],
                'quantity_affected' => 50000,
                'quantity_recovered' => 42000,
                'distribution_level' => 'consumer',
                'countries_affected' => ['العراق', 'الأردن', 'لبنان'],
                'health_hazard' => 'خطر عالي - قد يسبب عدوى خطيرة',
                'risk_assessment' => 'خطر مرتفع جداً على الصحة العامة',
                'initiated_date' => Carbon::now()->subDays(10),
                'notification_date' => Carbon::now()->subDays(9),
                'completion_date' => Carbon::now()->addDays(5),
                'status' => 'in_progress',
                'regulatory_authority' => 'وزارة الصحة العراقية',
                'authority_notification' => true,
                'public_notification' => true,
                'media_release' => true,
                'customer_notification' => true,
                'healthcare_notification' => true,
                'recall_strategy' => ['إشعار فوري للمستشفيات', 'سحب من الصيدليات', 'إعلان إعلامي'],
                'effectiveness_checks' => ['زيارات ميدانية', 'تقارير يومية', 'متابعة مع الموزعين'],
                'recovery_percentage' => 84.0,
                'disposal_method' => 'إتلاف تحت إشراف السلطات',
                'root_cause_analysis' => 'فشل في نظام التعقيم أثناء التصنيع',
                'corrective_actions' => ['إصلاح نظام التعقيم', 'تدريب العمال', 'تحسين الرقابة'],
                'preventive_actions' => ['فحص يومي للمعدات', 'اختبارات إضافية', 'مراجعة الإجراءات'],
                'follow_up_required' => true,
                'follow_up_date' => Carbon::now()->addDays(30),
                'notes' => 'استدعاء عاجل وحرج - أولوية قصوى'
            ],
            [
                'recall_number' => 'RCL-2025-002',
                'recall_type' => 'voluntary',
                'recall_class' => 'class_ii',
                'reason' => 'خطأ في تركيز المادة الفعالة قد يؤثر على فعالية العلاج',
                'description' => 'تم اكتشاف انخفاض في تركيز المادة الفعالة بنسبة 15%',
                'affected_batches' => ['LOT-2024-B010', 'LOT-2024-B011'],
                'quantity_affected' => 25000,
                'quantity_recovered' => 18500,
                'distribution_level' => 'pharmacy',
                'countries_affected' => ['العراق'],
                'health_hazard' => 'خطر متوسط - قد يقلل فعالية العلاج',
                'risk_assessment' => 'خطر متوسط على فعالية العلاج',
                'initiated_date' => Carbon::now()->subDays(7),
                'notification_date' => Carbon::now()->subDays(6),
                'completion_date' => Carbon::now()->addDays(10),
                'status' => 'ongoing',
                'regulatory_authority' => 'هيئة الدواء والرقابة الصحية',
                'authority_notification' => true,
                'public_notification' => false,
                'media_release' => false,
                'customer_notification' => true,
                'healthcare_notification' => true,
                'recall_strategy' => ['إشعار الصيدليات', 'استبدال المنتج', 'تقرير للأطباء'],
                'effectiveness_checks' => ['متابعة مع الصيدليات', 'تقارير أسبوعية'],
                'recovery_percentage' => 74.0,
                'disposal_method' => 'إعادة تصنيع بعد التصحيح',
                'root_cause_analysis' => 'خطأ في معايرة معدات القياس',
                'corrective_actions' => ['معايرة المعدات', 'فحص إضافي للجودة'],
                'preventive_actions' => ['معايرة دورية', 'تدريب الفنيين'],
                'follow_up_required' => true,
                'follow_up_date' => Carbon::now()->addDays(21),
                'notes' => 'استدعاء احترازي لضمان فعالية العلاج'
            ],
            [
                'recall_number' => 'RCL-2025-003',
                'recall_type' => 'mandatory',
                'recall_class' => 'class_i',
                'reason' => 'وجود جسيمات معدنية غريبة في المحلول الوريدي',
                'description' => 'تم العثور على جسيمات معدنية صغيرة في عدة قوارير من المحلول الوريدي',
                'affected_batches' => ['LOT-2024-C005', 'LOT-2024-C006', 'LOT-2024-C007', 'LOT-2024-C008'],
                'quantity_affected' => 15000,
                'quantity_recovered' => 14200,
                'distribution_level' => 'hospital',
                'countries_affected' => ['العراق', 'الكويت'],
                'health_hazard' => 'خطر عالي جداً - قد يسبب انسداد الأوعية الدموية',
                'risk_assessment' => 'خطر مهدد للحياة في حالة الحقن الوريدي',
                'initiated_date' => Carbon::now()->subDays(5),
                'notification_date' => Carbon::now()->subDays(4),
                'completion_date' => Carbon::now()->addDays(2),
                'status' => 'in_progress',
                'regulatory_authority' => 'وزارة الصحة العراقية',
                'authority_notification' => true,
                'public_notification' => true,
                'media_release' => true,
                'customer_notification' => true,
                'healthcare_notification' => true,
                'recall_strategy' => ['إيقاف فوري للاستخدام', 'سحب من المستشفيات', 'إشعار عاجل للأطباء'],
                'effectiveness_checks' => ['زيارات فورية للمستشفيات', 'تقارير كل 6 ساعات'],
                'recovery_percentage' => 94.7,
                'disposal_method' => 'إتلاف فوري تحت إشراف مشدد',
                'root_cause_analysis' => 'تآكل في خط الإنتاج المعدني',
                'corrective_actions' => ['استبدال خط الإنتاج', 'فحص شامل للمعدات'],
                'preventive_actions' => ['فحص دوري للمعدات', 'مرشحات إضافية'],
                'follow_up_required' => true,
                'follow_up_date' => Carbon::now()->addDays(14),
                'notes' => 'استدعاء طارئ - خطر مهدد للحياة'
            ],
            [
                'recall_number' => 'RCL-2025-004',
                'recall_type' => 'voluntary',
                'recall_class' => 'class_ii',
                'reason' => 'مشكلة في التغليف قد تؤثر على استقرار المنتج',
                'description' => 'عيب في الختم قد يسمح بدخول الرطوبة والهواء',
                'affected_batches' => ['LOT-2024-D020', 'LOT-2024-D021'],
                'quantity_affected' => 30000,
                'quantity_recovered' => 21000,
                'distribution_level' => 'pharmacy',
                'countries_affected' => ['العراق'],
                'health_hazard' => 'خطر منخفض إلى متوسط - قد يقلل فعالية المنتج',
                'risk_assessment' => 'خطر متوسط على استقرار المنتج',
                'initiated_date' => Carbon::now()->subDays(12),
                'notification_date' => Carbon::now()->subDays(11),
                'completion_date' => Carbon::now()->addDays(8),
                'status' => 'ongoing',
                'regulatory_authority' => 'هيئة الدواء والرقابة الصحية',
                'authority_notification' => true,
                'public_notification' => false,
                'media_release' => false,
                'customer_notification' => true,
                'healthcare_notification' => false,
                'recall_strategy' => ['إشعار الموزعين', 'فحص المخزون', 'استبدال المنتج'],
                'effectiveness_checks' => ['متابعة مع الموزعين', 'تقارير أسبوعية'],
                'recovery_percentage' => 70.0,
                'disposal_method' => 'إعادة تغليف بعد الفحص',
                'root_cause_analysis' => 'خلل في آلة الختم',
                'corrective_actions' => ['إصلاح آلة الختم', 'فحص جودة الختم'],
                'preventive_actions' => ['صيانة دورية للآلات', 'فحص عشوائي للختم'],
                'follow_up_required' => true,
                'follow_up_date' => Carbon::now()->addDays(28),
                'notes' => 'استدعاء احترازي لضمان جودة المنتج'
            ],
            [
                'recall_number' => 'RCL-2025-005',
                'recall_type' => 'mandatory',
                'recall_class' => 'class_i',
                'reason' => 'خطأ في وضع العلامات - منتج خاطئ في عبوة خاطئة',
                'description' => 'تم وضع أقراص بتركيز 10mg في عبوات مكتوب عليها 5mg',
                'affected_batches' => ['LOT-2024-E030'],
                'quantity_affected' => 8000,
                'quantity_recovered' => 7600,
                'distribution_level' => 'pharmacy',
                'countries_affected' => ['العراق'],
                'health_hazard' => 'خطر عالي - جرعة زائدة قد تسبب تسمم',
                'risk_assessment' => 'خطر عالي من الجرعة الزائدة',
                'initiated_date' => Carbon::now()->subDays(3),
                'notification_date' => Carbon::now()->subDays(2),
                'completion_date' => Carbon::now()->addDays(3),
                'status' => 'in_progress',
                'regulatory_authority' => 'وزارة الصحة العراقية',
                'authority_notification' => true,
                'public_notification' => true,
                'media_release' => true,
                'customer_notification' => true,
                'healthcare_notification' => true,
                'recall_strategy' => ['سحب فوري من الصيدليات', 'إشعار عاجل للأطباء', 'تحذير للمرضى'],
                'effectiveness_checks' => ['زيارات يومية للصيدليات', 'تقارير كل 12 ساعة'],
                'recovery_percentage' => 95.0,
                'disposal_method' => 'إعادة تعبئة وتصحيح العلامات',
                'root_cause_analysis' => 'خطأ بشري في خط التعبئة',
                'corrective_actions' => ['تدريب العمال', 'نظام فحص مزدوج'],
                'preventive_actions' => ['نظام باركود', 'فحص آلي للعلامات'],
                'follow_up_required' => true,
                'follow_up_date' => Carbon::now()->addDays(7),
                'notes' => 'استدعاء عاجل - خطر الجرعة الزائدة'
            ]
        ];

        foreach ($recalls as $recallData) {
            ProductRecall::create(array_merge($recallData, [
                'tenant_id' => $tenant->id,
                'product_id' => null, // Make it nullable for now
                'product_name' => $recallData['description'] ?? 'منتج غير محدد'
            ]));
        }

        $this->command->info('Product recalls created successfully!');
        $this->command->info('Created 5 high priority product recalls:');
        $this->command->info('- 3 Class I recalls (critical)');
        $this->command->info('- 2 Class II recalls (serious)');
        $this->command->info('- Average recovery rate: 83.5%');
    }
}
