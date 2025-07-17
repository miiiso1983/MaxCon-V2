<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tenant;
use App\Models\Tenant\Regulatory\RegulatoryReport;
use Carbon\Carbon;

class RegulatoryReportSeeder extends Seeder
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

        $this->command->info("Creating regulatory reports for tenant: {$tenant->name}");

        // Create overdue reports for testing
        $reports = [
            [
                'report_title' => 'تقرير الامتثال الربعي - الربع الثالث 2024',
                'report_type' => 'compliance',
                'report_period' => 'quarterly',
                'submission_authority' => 'وزارة الصحة العراقية',
                'due_date' => Carbon::now()->subDays(45), // 45 days overdue
                'submission_date' => null,
                'report_status' => 'draft',
                'prepared_by' => 'أحمد محمد علي',
                'reviewed_by' => null,
                'approved_by' => null,
                'report_summary' => 'تقرير شامل عن حالة الامتثال للوائح الصيدلانية خلال الربع الثالث من عام 2024',
                'key_findings' => 'تم تحديد عدة مجالات تحتاج إلى تحسين في نظام إدارة الجودة',
                'recommendations' => 'تحديث إجراءات مراقبة الجودة وتدريب الموظفين على اللوائح الجديدة',
                'follow_up_actions' => 'مراجعة شهرية لتقدم تنفيذ التوصيات',
                'regulatory_reference' => 'قانون الأدوية العراقي رقم 13 لسنة 2009',
                'priority_level' => 'high',
                'notes' => 'تقرير متأخر - يحتاج إنجاز عاجل'
            ],
            [
                'report_title' => 'تقرير الأحداث السلبية - أكتوبر 2024',
                'report_type' => 'adverse_event',
                'report_period' => 'monthly',
                'submission_authority' => 'المركز الوطني لليقظة الدوائية',
                'due_date' => Carbon::now()->subDays(20), // 20 days overdue
                'submission_date' => null,
                'report_status' => 'pending_review',
                'prepared_by' => 'د. فاطمة حسن',
                'reviewed_by' => 'د. علي أحمد',
                'approved_by' => null,
                'report_summary' => 'تقرير شهري عن الأحداث السلبية المرتبطة بالأدوية المسجلة خلال شهر أكتوبر',
                'key_findings' => 'تم تسجيل 15 حدث سلبي، منها 3 أحداث جدية تتطلب متابعة',
                'recommendations' => 'تعزيز نظام الإبلاغ عن الأحداث السلبية وتدريب الكوادر الطبية',
                'follow_up_actions' => 'متابعة الحالات الجدية وإعداد تقارير تفصيلية',
                'regulatory_reference' => 'دليل اليقظة الدوائية العراقي 2023',
                'priority_level' => 'critical',
                'notes' => 'يحتوي على أحداث جدية تتطلب إبلاغ فوري'
            ],
            [
                'report_title' => 'تقرير تدقيق نظام الجودة السنوي 2024',
                'report_type' => 'audit',
                'report_period' => 'annual',
                'submission_authority' => 'هيئة الدواء والرقابة الصحية',
                'due_date' => Carbon::now()->subDays(60), // 60 days overdue
                'submission_date' => null,
                'report_status' => 'draft',
                'prepared_by' => 'فريق التدقيق الداخلي',
                'reviewed_by' => null,
                'approved_by' => null,
                'report_summary' => 'تدقيق شامل لنظام إدارة الجودة وفقاً لمعايير GMP الدولية',
                'key_findings' => 'تم تحديد 12 ملاحظة، منها 4 ملاحظات رئيسية تحتاج إجراءات تصحيحية',
                'recommendations' => 'تحديث إجراءات التصنيع وتحسين نظام التوثيق',
                'follow_up_actions' => 'وضع خطة زمنية لتنفيذ الإجراءات التصحيحية',
                'regulatory_reference' => 'دليل الممارسات التصنيعية الجيدة GMP',
                'priority_level' => 'high',
                'notes' => 'تقرير حرج - مطلوب للحفاظ على ترخيص التصنيع'
            ],
            [
                'report_title' => 'تقرير التفتيش الدوري - نوفمبر 2024',
                'report_type' => 'inspection',
                'report_period' => 'monthly',
                'submission_authority' => 'إدارة التفتيش الصيدلاني',
                'due_date' => Carbon::now()->subDays(10), // 10 days overdue
                'submission_date' => null,
                'report_status' => 'pending_review',
                'prepared_by' => 'مفتش الجودة الرئيسي',
                'reviewed_by' => 'مدير ضمان الجودة',
                'approved_by' => null,
                'report_summary' => 'تقرير التفتيش الدوري لمرافق التصنيع والتخزين',
                'key_findings' => 'جميع المرافق تلتزم بالمعايير المطلوبة مع ملاحظات طفيفة',
                'recommendations' => 'تحسين نظام التهوية في منطقة التخزين',
                'follow_up_actions' => 'تنفيذ التحسينات المطلوبة خلال 30 يوم',
                'regulatory_reference' => 'لائحة التفتيش الصيدلاني رقم 5 لسنة 2020',
                'priority_level' => 'medium',
                'notes' => 'تفتيش روتيني - نتائج مرضية عموماً'
            ],
            [
                'report_title' => 'تقرير الامتثال الدوري - ديسمبر 2024',
                'report_type' => 'periodic',
                'report_period' => 'monthly',
                'submission_authority' => 'وزارة الصحة العراقية',
                'due_date' => Carbon::now()->subDays(5), // 5 days overdue
                'submission_date' => null,
                'report_status' => 'draft',
                'prepared_by' => 'مسؤول الشؤون التنظيمية',
                'reviewed_by' => null,
                'approved_by' => null,
                'report_summary' => 'تقرير شهري عن حالة الامتثال للوائح والقوانين الصيدلانية',
                'key_findings' => 'التزام كامل بجميع اللوائح المطلوبة خلال الشهر',
                'recommendations' => 'الاستمرار في تطبيق نفس المعايير',
                'follow_up_actions' => 'مراجعة دورية للإجراءات',
                'regulatory_reference' => 'قانون الأدوية العراقي',
                'priority_level' => 'medium',
                'notes' => 'تقرير روتيني - حالة جيدة'
            ],
            [
                'report_title' => 'تقرير حادث تلوث خط الإنتاج',
                'report_type' => 'incident',
                'report_period' => null,
                'submission_authority' => 'وزارة الصحة العراقية - إدارة الطوارئ',
                'due_date' => Carbon::now()->subDays(35), // 35 days overdue
                'submission_date' => null,
                'report_status' => 'pending_review',
                'prepared_by' => 'مدير الإنتاج',
                'reviewed_by' => 'مدير ضمان الجودة',
                'approved_by' => null,
                'report_summary' => 'تقرير عن حادث تلوث في خط إنتاج المضادات الحيوية',
                'key_findings' => 'تم اكتشاف تلوث ميكروبي في خط الإنتاج رقم 3',
                'recommendations' => 'إيقاف الخط فوراً وتطهير شامل وإعادة التأهيل',
                'follow_up_actions' => 'تحقيق شامل في أسباب التلوث ووضع إجراءات وقائية',
                'regulatory_reference' => 'إجراءات الطوارئ للتلوث الميكروبي',
                'priority_level' => 'critical',
                'notes' => 'حادث خطير - يتطلب إبلاغ فوري للسلطات'
            ],
            [
                'report_title' => 'تقرير مراجعة نظام إدارة المخاطر',
                'report_type' => 'audit',
                'report_period' => 'semi_annual',
                'submission_authority' => 'هيئة الدواء والرقابة الصحية',
                'due_date' => Carbon::now()->subDays(25), // 25 days overdue
                'submission_date' => null,
                'report_status' => 'draft',
                'prepared_by' => 'مستشار إدارة المخاطر',
                'reviewed_by' => null,
                'approved_by' => null,
                'report_summary' => 'مراجعة نصف سنوية لفعالية نظام إدارة المخاطر',
                'key_findings' => 'النظام فعال بشكل عام مع حاجة لتحديثات في بعض الإجراءات',
                'recommendations' => 'تحديث مصفوفة المخاطر وتدريب الموظفين',
                'follow_up_actions' => 'تنفيذ التحديثات المطلوبة خلال 60 يوم',
                'regulatory_reference' => 'دليل إدارة المخاطر الصيدلانية',
                'priority_level' => 'high',
                'notes' => 'مراجعة مهمة لضمان فعالية النظام'
            ]
        ];

        foreach ($reports as $reportData) {
            // Map the data to match the model's fillable fields
            $mappedData = [
                'tenant_id' => $tenant->id,
                'report_number' => 'RPT-' . date('Y') . '-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT),
                'report_type' => $this->mapReportType($reportData['report_type']),
                'report_category' => 'regulatory_submission', // Default category
                'title' => $reportData['report_title'],
                'description' => $reportData['report_summary'],
                'regulatory_authority' => $reportData['submission_authority'],
                'submission_date' => $reportData['submission_date'],
                'due_date' => $reportData['due_date'],
                'status' => $this->mapStatus($reportData['report_status']),
                'priority' => $reportData['priority_level'],
                'findings' => $reportData['key_findings'],
                'recommendations' => $reportData['recommendations'],
                'action_items' => $reportData['follow_up_actions'],
                'follow_up_required' => !empty($reportData['follow_up_actions']),
                'prepared_by' => $reportData['prepared_by'],
                'reviewed_by' => $reportData['reviewed_by'],
                'approved_by' => $reportData['approved_by'],
                'notes' => $reportData['notes']
            ];

            RegulatoryReport::create($mappedData);
        }

        $this->command->info('Regulatory reports created successfully!');
        $this->command->info('Created 7 overdue regulatory reports:');
        $this->command->info('- 2 Critical priority reports');
        $this->command->info('- 3 High priority reports');
        $this->command->info('- 2 Medium priority reports');
        $this->command->info('- Average delay: 28.6 days');
    }

    /**
     * Map old report type values to new ones
     */
    private function mapReportType($oldType)
    {
        $typeMap = [
            'compliance' => 'compliance',
            'adverse_event' => 'adverse_event',
            'audit' => 'inspection', // Map audit to inspection
            'inspection' => 'inspection',
            'periodic' => 'periodic_safety',
            'incident' => 'incident'
        ];

        return $typeMap[$oldType] ?? 'compliance';
    }

    /**
     * Map old status values to new ones
     */
    private function mapStatus($oldStatus)
    {
        $statusMap = [
            'draft' => 'draft',
            'pending_review' => 'under_review',
            'submitted' => 'submitted',
            'approved' => 'approved',
            'rejected' => 'rejected',
            'closed' => 'closed'
        ];

        return $statusMap[$oldStatus] ?? 'draft';
    }
}
