<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tenant;
use App\Models\Tenant\Regulatory\Inspection;
use Carbon\Carbon;

class InspectionSeeder extends Seeder
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

        $this->command->info("Creating inspections for tenant: {$tenant->name}");

        // Create sample inspections
        $inspections = [
            [
                'inspection_title' => 'تفتيش دوري للجودة - مصنع الأدوية المتقدمة',
                'inspection_type' => 'routine',
                'inspector_name' => 'د. أحمد محمد علي',
                'inspection_authority' => 'وزارة الصحة العراقية',
                'scheduled_date' => Carbon::now()->addDays(7),
                'completion_date' => null,
                'inspection_status' => 'scheduled',
                'facility_name' => 'مصنع الأدوية المتقدمة',
                'facility_address' => 'بغداد - المنطقة الصناعية - شارع الصناعة رقم 15',
                'scope_of_inspection' => 'فحص شامل لخطوط الإنتاج، مراقبة الجودة، التخزين، والتوثيق',
                'findings' => null,
                'recommendations' => null,
                'compliance_rating' => null,
                'follow_up_required' => false,
                'follow_up_date' => null,
                'notes' => 'تفتيش دوري مجدول ضمن خطة التفتيش السنوية'
            ],
            [
                'inspection_title' => 'تفتيش متابعة - مختبر التحليل الدوائي',
                'inspection_type' => 'follow_up',
                'inspector_name' => 'د. فاطمة حسن الزهراء',
                'inspection_authority' => 'هيئة الدواء والرقابة الصحية',
                'scheduled_date' => Carbon::now()->subDays(5),
                'completion_date' => Carbon::now()->subDays(3),
                'inspection_status' => 'completed',
                'facility_name' => 'مختبر التحليل الدوائي المركزي',
                'facility_address' => 'بغداد - الكرادة - مجمع المختبرات الطبية',
                'scope_of_inspection' => 'متابعة تنفيذ التوصيات من التفتيش السابق وفحص الأجهزة الجديدة',
                'findings' => 'تم تنفيذ جميع التوصيات السابقة بنجاح. الأجهزة الجديدة مطابقة للمواصفات.',
                'recommendations' => 'الاستمرار في تطبيق إجراءات الجودة الحالية مع مراجعة دورية كل 6 أشهر',
                'compliance_rating' => 'excellent',
                'follow_up_required' => false,
                'follow_up_date' => null,
                'notes' => 'تفتيش ناجح - تحسن ملحوظ في الأداء'
            ],
            [
                'inspection_title' => 'تفتيش شكوى - مستودع الأدوية الباردة',
                'inspection_type' => 'complaint',
                'inspector_name' => 'م. علي حسين الكعبي',
                'inspection_authority' => 'وزارة الصحة العراقية',
                'scheduled_date' => Carbon::now()->addDays(2),
                'completion_date' => null,
                'inspection_status' => 'in_progress',
                'facility_name' => 'مستودع الأدوية الباردة المركزي',
                'facility_address' => 'بغداد - الدورة - المنطقة الصناعية الجنوبية',
                'scope_of_inspection' => 'فحص أنظمة التبريد والتحكم في درجة الحرارة بناءً على شكوى واردة',
                'findings' => 'جاري الفحص - تم اكتشاف خلل في نظام الإنذار المبكر لدرجة الحرارة',
                'recommendations' => 'إصلاح نظام الإنذار فوراً وإجراء فحص شامل لجميع أجهزة التبريد',
                'compliance_rating' => null,
                'follow_up_required' => true,
                'follow_up_date' => Carbon::now()->addDays(14),
                'notes' => 'تفتيش عاجل بناءً على شكوى - يتطلب متابعة دقيقة'
            ],
            [
                'inspection_title' => 'تفتيش ما قبل الموافقة - خط إنتاج المضادات الحيوية',
                'inspection_type' => 'pre_approval',
                'inspector_name' => 'د. سارة أحمد الجبوري',
                'inspection_authority' => 'هيئة الدواء والرقابة الصحية',
                'scheduled_date' => Carbon::now()->addDays(14),
                'completion_date' => null,
                'inspection_status' => 'scheduled',
                'facility_name' => 'مصنع الأدوية الحديثة - خط المضادات الحيوية',
                'facility_address' => 'البصرة - المنطقة الصناعية - مجمع الصناعات الدوائية',
                'scope_of_inspection' => 'فحص خط الإنتاج الجديد للمضادات الحيوية قبل منح ترخيص التشغيل',
                'findings' => null,
                'recommendations' => null,
                'compliance_rating' => null,
                'follow_up_required' => false,
                'follow_up_date' => null,
                'notes' => 'تفتيش حاسم لمنح ترخيص تشغيل خط إنتاج جديد'
            ],
            [
                'inspection_title' => 'تفتيش ما بعد التسويق - أدوية القلب والأوعية',
                'inspection_type' => 'post_market',
                'inspector_name' => 'د. محمد عبد الرحمن',
                'inspection_authority' => 'المركز الوطني لليقظة الدوائية',
                'scheduled_date' => Carbon::now()->subDays(10),
                'completion_date' => Carbon::now()->subDays(8),
                'inspection_status' => 'completed',
                'facility_name' => 'شركة الأدوية العراقية - قسم أدوية القلب',
                'facility_address' => 'بغداد - الجادرية - مجمع الشركات الدوائية',
                'scope_of_inspection' => 'مراجعة تقارير الأمان وفعالية أدوية القلب المسوقة حديثاً',
                'findings' => 'تم رصد 3 تقارير أحداث سلبية طفيفة. جميع الإجراءات متوافقة مع المعايير.',
                'recommendations' => 'تعزيز نظام مراقبة الأحداث السلبية وتحديث معلومات السلامة',
                'compliance_rating' => 'good',
                'follow_up_required' => true,
                'follow_up_date' => Carbon::now()->addDays(30),
                'notes' => 'مراقبة مستمرة مطلوبة للأدوية الجديدة'
            ],
            [
                'inspection_title' => 'تفتيش طارئ - مشكلة في التعبئة والتغليف',
                'inspection_type' => 'complaint',
                'inspector_name' => 'م. زينب علي الحسني',
                'inspection_authority' => 'وزارة الصحة العراقية',
                'scheduled_date' => Carbon::now()->subDays(2),
                'completion_date' => null,
                'inspection_status' => 'postponed',
                'facility_name' => 'مصنع التعبئة والتغليف الدوائي',
                'facility_address' => 'النجف - المنطقة الصناعية - شارع الصناعات الطبية',
                'scope_of_inspection' => 'فحص عاجل لخط التعبئة بعد تقارير عن مشاكل في الختم',
                'findings' => null,
                'recommendations' => null,
                'compliance_rating' => null,
                'follow_up_required' => false,
                'follow_up_date' => null,
                'notes' => 'تم تأجيل التفتيش بناءً على طلب المصنع لإجراء صيانة طارئة'
            ]
        ];

        foreach ($inspections as $inspectionData) {
            Inspection::create(array_merge($inspectionData, [
                'tenant_id' => $tenant->id
            ]));
        }

        $this->command->info('Inspections created successfully!');
        $this->command->info('Created 6 sample inspections:');
        $this->command->info('- 2 Scheduled inspections');
        $this->command->info('- 2 Completed inspections');
        $this->command->info('- 1 In progress inspection');
        $this->command->info('- 1 Postponed inspection');
    }
}
