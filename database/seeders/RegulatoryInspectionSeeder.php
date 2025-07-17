<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tenant;
use App\Models\Tenant\Regulatory\RegulatoryInspection;
use App\Models\Tenant\Regulatory\CompanyRegistration;
use Carbon\Carbon;
use Illuminate\Support\Str;

class RegulatoryInspectionSeeder extends Seeder
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

        // Get first company or create one
        $company = CompanyRegistration::where('tenant_id', $tenant->id)->first();
        if (!$company) {
            $company = CompanyRegistration::create([
                'id' => Str::uuid(),
                'tenant_id' => $tenant->id,
                'company_name' => 'شركة الأدوية المتقدمة',
                'company_name_en' => 'Advanced Pharmaceuticals Company',
                'registration_number' => 'REG-2024-001',
                'license_number' => 'LIC-2024-001',
                'company_type' => 'manufacturer',
                'registration_date' => Carbon::now()->subYears(2),
                'license_expiry_date' => Carbon::now()->addYears(3),
                'status' => 'active',
                'compliance_status' => 'compliant',
                'contact_person' => 'د. أحمد محمد',
                'email' => 'info@advanced-pharma.com',
                'phone' => '+964-1-234-5678',
                'address' => 'بغداد - المنطقة الصناعية - شارع الصناعة رقم 15'
            ]);
        }

        $this->command->info("Creating regulatory inspections for tenant: {$tenant->name}");

        // Create sample regulatory inspections
        $inspections = [
            [
                'inspection_number' => 'INS-2025-001',
                'inspection_type' => 'routine',
                'regulatory_authority' => 'وزارة الصحة العراقية',
                'inspector_name' => 'د. أحمد محمد علي',
                'inspector_credentials' => 'دكتوراه في الصيدلة - خبرة 15 سنة في التفتيش',
                'scheduled_date' => Carbon::now()->addDays(10),
                'actual_date' => null,
                'completion_date' => null,
                'duration_hours' => 8,
                'inspection_scope' => 'فحص شامل لخطوط الإنتاج ومراقبة الجودة والتخزين',
                'areas_inspected' => ['production', 'quality_control', 'storage', 'documentation'],
                'findings' => null,
                'observations' => null,
                'non_conformities' => null,
                'critical_findings' => null,
                'major_findings' => null,
                'minor_findings' => null,
                'recommendations' => null,
                'corrective_actions_required' => null,
                'corrective_actions_taken' => null,
                'follow_up_required' => false,
                'follow_up_date' => null,
                'status' => 'scheduled',
                'overall_rating' => null,
                'compliance_score' => null,
                'certificate_issued' => false,
                'certificate_number' => null,
                'certificate_validity' => null,
                'next_inspection_date' => Carbon::now()->addYear(),
                'inspection_report' => null,
                'attachments' => null,
                'notes' => 'تفتيش دوري مجدول ضمن خطة التفتيش السنوية'
            ],
            [
                'inspection_number' => 'INS-2025-002',
                'inspection_type' => 'gmp',
                'regulatory_authority' => 'هيئة الدواء والرقابة الصحية',
                'inspector_name' => 'د. فاطمة حسن الزهراء',
                'inspector_credentials' => 'ماجستير في الصيدلة الصناعية - مفتش معتمد GMP',
                'scheduled_date' => Carbon::now()->subDays(15),
                'actual_date' => Carbon::now()->subDays(15),
                'completion_date' => Carbon::now()->subDays(13),
                'duration_hours' => 16,
                'inspection_scope' => 'تفتيش GMP شامل لجميع مرافق التصنيع',
                'areas_inspected' => ['production', 'quality_control', 'storage', 'documentation', 'personnel', 'facilities'],
                'findings' => ['جميع المعايير مطابقة للمواصفات', 'نظام التوثيق ممتاز', 'إجراءات الجودة متقدمة'],
                'observations' => ['التزام عالي بمعايير GMP', 'موظفين مدربين جيداً', 'معدات حديثة ومعايرة'],
                'non_conformities' => [],
                'critical_findings' => [],
                'major_findings' => [],
                'minor_findings' => ['تحديث بعض اللوحات الإرشادية', 'تحسين الإضاءة في منطقة التخزين'],
                'recommendations' => ['الاستمرار في تطبيق نفس المعايير', 'تحديث اللوحات الإرشادية', 'تحسين الإضاءة'],
                'corrective_actions_required' => ['تحديث اللوحات خلال 30 يوم', 'تحسين الإضاءة خلال 60 يوم'],
                'corrective_actions_taken' => null,
                'follow_up_required' => true,
                'follow_up_date' => Carbon::now()->addDays(45),
                'status' => 'completed',
                'overall_rating' => 'excellent',
                'compliance_score' => 95,
                'certificate_issued' => true,
                'certificate_number' => 'GMP-2025-001',
                'certificate_validity' => Carbon::now()->addYears(2),
                'next_inspection_date' => Carbon::now()->addYears(2),
                'inspection_report' => 'تقرير تفتيش GMP مفصل - النتيجة: ممتاز',
                'attachments' => ['inspection_photos.zip', 'compliance_checklist.pdf'],
                'notes' => 'تفتيش ممتاز - الشركة تلتزم بأعلى معايير الجودة'
            ],
            [
                'inspection_number' => 'INS-2025-003',
                'inspection_type' => 'complaint_based',
                'regulatory_authority' => 'وزارة الصحة العراقية',
                'inspector_name' => 'م. علي حسين الكعبي',
                'inspector_credentials' => 'مهندس كيميائي - مفتش معتمد للشكاوى',
                'scheduled_date' => Carbon::now()->subDays(5),
                'actual_date' => Carbon::now()->subDays(5),
                'completion_date' => null,
                'duration_hours' => 6,
                'inspection_scope' => 'فحص عاجل لخط إنتاج المضادات الحيوية بناءً على شكوى',
                'areas_inspected' => ['production_line_3', 'quality_control', 'storage_cold'],
                'findings' => ['تم اكتشاف خلل في نظام التحكم في درجة الحرارة', 'إجراءات الطوارئ غير مفعلة'],
                'observations' => ['نظام الإنذار المبكر لا يعمل', 'بعض المنتجات قد تأثرت'],
                'non_conformities' => ['عدم عمل نظام الإنذار', 'عدم تفعيل إجراءات الطوارئ'],
                'critical_findings' => ['فشل نظام التحكم في درجة الحرارة'],
                'major_findings' => ['عدم تفعيل إجراءات الطوارئ'],
                'minor_findings' => ['تحديث سجلات المراقبة'],
                'recommendations' => ['إصلاح نظام التحكم فوراً', 'تفعيل إجراءات الطوارئ', 'مراجعة المنتجات المتأثرة'],
                'corrective_actions_required' => ['إصلاح النظام خلال 24 ساعة', 'مراجعة جميع المنتجات', 'تدريب الموظفين'],
                'corrective_actions_taken' => ['تم إيقاف الخط فوراً', 'بدء إصلاح النظام'],
                'follow_up_required' => true,
                'follow_up_date' => Carbon::now()->addDays(7),
                'status' => 'in_progress',
                'overall_rating' => null,
                'compliance_score' => null,
                'certificate_issued' => false,
                'certificate_number' => null,
                'certificate_validity' => null,
                'next_inspection_date' => Carbon::now()->addDays(30),
                'inspection_report' => null,
                'attachments' => ['complaint_report.pdf', 'temperature_logs.xlsx'],
                'notes' => 'تفتيش عاجل - يتطلب متابعة فورية'
            ],
            [
                'inspection_number' => 'INS-2025-004',
                'inspection_type' => 'pre_approval',
                'regulatory_authority' => 'هيئة الدواء والرقابة الصحية',
                'inspector_name' => 'د. سارة أحمد الجبوري',
                'inspector_credentials' => 'دكتوراه في الكيمياء الدوائية - خبيرة تقييم المنتجات',
                'scheduled_date' => Carbon::now()->addDays(20),
                'actual_date' => null,
                'completion_date' => null,
                'duration_hours' => 12,
                'inspection_scope' => 'تقييم خط إنتاج جديد للأنسولين قبل منح الترخيص',
                'areas_inspected' => ['new_production_line', 'quality_control_lab', 'cold_storage', 'documentation'],
                'findings' => null,
                'observations' => null,
                'non_conformities' => null,
                'critical_findings' => null,
                'major_findings' => null,
                'minor_findings' => null,
                'recommendations' => null,
                'corrective_actions_required' => null,
                'corrective_actions_taken' => null,
                'follow_up_required' => false,
                'follow_up_date' => null,
                'status' => 'scheduled',
                'overall_rating' => null,
                'compliance_score' => null,
                'certificate_issued' => false,
                'certificate_number' => null,
                'certificate_validity' => null,
                'next_inspection_date' => null,
                'inspection_report' => null,
                'attachments' => null,
                'notes' => 'تفتيش حاسم لمنح ترخيص إنتاج الأنسولين'
            ],
            [
                'inspection_number' => 'INS-2025-005',
                'inspection_type' => 'follow_up',
                'regulatory_authority' => 'وزارة الصحة العراقية',
                'inspector_name' => 'د. محمد عبد الرحمن',
                'inspector_credentials' => 'دكتوراه في الصيدلة - مفتش متابعة معتمد',
                'scheduled_date' => Carbon::now()->subDays(3),
                'actual_date' => Carbon::now()->subDays(3),
                'completion_date' => Carbon::now()->subDays(1),
                'duration_hours' => 4,
                'inspection_scope' => 'متابعة تنفيذ التوصيات من التفتيش السابق',
                'areas_inspected' => ['corrective_actions', 'documentation', 'training_records'],
                'findings' => ['تم تنفيذ 90% من التوصيات', 'تحسن ملحوظ في الإجراءات'],
                'observations' => ['التزام جيد بالتوصيات', 'تدريب الموظفين تم بنجاح'],
                'non_conformities' => [],
                'critical_findings' => [],
                'major_findings' => [],
                'minor_findings' => ['إكمال التوثيق المتبقي'],
                'recommendations' => ['إكمال التوصيات المتبقية', 'الاستمرار في التحسين'],
                'corrective_actions_required' => ['إكمال التوثيق خلال 15 يوم'],
                'corrective_actions_taken' => ['تنفيذ 90% من التوصيات', 'تدريب جميع الموظفين'],
                'follow_up_required' => false,
                'follow_up_date' => null,
                'status' => 'completed',
                'overall_rating' => 'good',
                'compliance_score' => 88,
                'certificate_issued' => false,
                'certificate_number' => null,
                'certificate_validity' => null,
                'next_inspection_date' => Carbon::now()->addMonths(6),
                'inspection_report' => 'تقرير متابعة - تحسن ملحوظ في الأداء',
                'attachments' => ['follow_up_report.pdf', 'training_certificates.pdf'],
                'notes' => 'متابعة ناجحة - تحسن كبير في الأداء'
            ]
        ];

        foreach ($inspections as $inspectionData) {
            RegulatoryInspection::create(array_merge($inspectionData, [
                'id' => Str::uuid(),
                'tenant_id' => $tenant->id,
                'company_id' => $company->id
            ]));
        }

        $this->command->info('Regulatory inspections created successfully!');
        $this->command->info('Created 5 sample regulatory inspections:');
        $this->command->info('- 2 Scheduled inspections');
        $this->command->info('- 2 Completed inspections');
        $this->command->info('- 1 In progress inspection');
        $this->command->info('- Mix of inspection types: routine, GMP, complaint-based, pre-approval, follow-up');
    }
}
