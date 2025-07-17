<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tenant;
use App\Models\Tenant\Regulatory\CompanyRegistration;
use Carbon\Carbon;

class CompanyRegistrationSeeder extends Seeder
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

        $this->command->info("Creating company registrations for tenant: {$tenant->name}");

        // Create companies with different expiry dates for testing
        $companies = [
            [
                'company_name' => 'شركة الأدوية العراقية المحدودة',
                'company_name_en' => 'Iraqi Pharmaceutical Company Ltd',
                'registration_number' => 'REG-001-2024',
                'license_number' => 'LIC-001-2024',
                'license_type' => 'manufacturing',
                'regulatory_authority' => 'وزارة الصحة العراقية',
                'registration_date' => Carbon::now()->subYears(2),
                'license_issue_date' => Carbon::now()->subYears(2),
                'license_expiry_date' => Carbon::now()->addDays(15), // Expires in 15 days
                'status' => 'active',
                'company_address' => 'المنطقة الصناعية - بغداد - العراق',
                'contact_person' => 'د. أحمد محمد علي',
                'contact_email' => 'ahmed@iraqi-pharma.com',
                'contact_phone' => '+964-1-7123456',
                'business_activities' => ['تصنيع الأدوية', 'البحث والتطوير', 'مراقبة الجودة'],
                'authorized_products' => ['المضادات الحيوية', 'مسكنات الألم', 'أدوية القلب'],
                'compliance_status' => 'compliant',
                'last_inspection_date' => Carbon::now()->subMonths(6),
                'next_inspection_date' => Carbon::now()->addMonths(6),
                'notes' => 'شركة رائدة في تصنيع الأدوية في العراق'
            ],
            [
                'company_name' => 'مختبرات الشرق الأوسط للأدوية',
                'company_name_en' => 'Middle East Pharmaceutical Labs',
                'registration_number' => 'REG-002-2024',
                'license_number' => 'LIC-002-2024',
                'license_type' => 'manufacturing',
                'regulatory_authority' => 'وزارة الصحة العراقية',
                'registration_date' => Carbon::now()->subYears(3),
                'license_issue_date' => Carbon::now()->subYears(3),
                'license_expiry_date' => Carbon::now()->addDays(45), // Expires in 45 days
                'status' => 'active',
                'company_address' => 'المنطقة الصناعية - البصرة - العراق',
                'contact_person' => 'د. فاطمة حسن',
                'contact_email' => 'fatima@me-labs.com',
                'contact_phone' => '+964-40-1234567',
                'business_activities' => ['تصنيع الأدوية', 'التحليل المختبري', 'ضمان الجودة'],
                'authorized_products' => ['أدوية الجهاز الهضمي', 'الفيتامينات', 'المكملات الغذائية'],
                'compliance_status' => 'compliant',
                'last_inspection_date' => Carbon::now()->subMonths(4),
                'next_inspection_date' => Carbon::now()->addMonths(8),
                'notes' => 'متخصصة في الأدوية العشبية والطبيعية'
            ],
            [
                'company_name' => 'شركة الدواء الحديث',
                'company_name_en' => 'Modern Medicine Company',
                'registration_number' => 'REG-003-2024',
                'license_number' => 'LIC-003-2024',
                'license_type' => 'distribution',
                'regulatory_authority' => 'وزارة الصحة العراقية',
                'registration_date' => Carbon::now()->subYears(1),
                'license_issue_date' => Carbon::now()->subYears(1),
                'license_expiry_date' => Carbon::now()->addDays(75), // Expires in 75 days
                'status' => 'active',
                'company_address' => 'شارع الكندي - بغداد - العراق',
                'contact_person' => 'م. سعد الدين محمود',
                'contact_email' => 'saad@modern-medicine.com',
                'contact_phone' => '+964-1-9876543',
                'business_activities' => ['توزيع الأدوية', 'التخزين', 'اللوجستيات'],
                'authorized_products' => ['جميع أنواع الأدوية', 'المستلزمات الطبية', 'الأجهزة الطبية'],
                'compliance_status' => 'compliant',
                'last_inspection_date' => Carbon::now()->subMonths(3),
                'next_inspection_date' => Carbon::now()->addMonths(9),
                'notes' => 'أكبر موزع للأدوية في العراق'
            ],
            [
                'company_name' => 'مستودعات الصحة المتقدمة',
                'company_name_en' => 'Advanced Health Warehouses',
                'registration_number' => 'REG-004-2024',
                'license_number' => 'LIC-004-2024',
                'license_type' => 'wholesale',
                'regulatory_authority' => 'وزارة الصحة العراقية',
                'registration_date' => Carbon::now()->subMonths(8),
                'license_issue_date' => Carbon::now()->subMonths(8),
                'license_expiry_date' => Carbon::now()->subDays(5), // Expired 5 days ago
                'status' => 'expired',
                'company_address' => 'المنطقة الحرة - أربيل - العراق',
                'contact_person' => 'أ. زينب كريم',
                'contact_email' => 'zeinab@advanced-health.com',
                'contact_phone' => '+964-66-1122334',
                'business_activities' => ['تخزين الأدوية', 'التوزيع بالجملة', 'إدارة المخزون'],
                'authorized_products' => ['الأدوية المبردة', 'الأدوية الخاضعة للرقابة', 'اللقاحات'],
                'compliance_status' => 'corrective_action',
                'last_inspection_date' => Carbon::now()->subMonths(2),
                'next_inspection_date' => Carbon::now()->addDays(30),
                'notes' => 'تحتاج لتجديد الترخيص فوراً'
            ],
            [
                'company_name' => 'شركة الاستيراد الطبي',
                'company_name_en' => 'Medical Import Company',
                'registration_number' => 'REG-005-2024',
                'license_number' => 'LIC-005-2024',
                'license_type' => 'import',
                'regulatory_authority' => 'وزارة الصحة العراقية',
                'registration_date' => Carbon::now()->subMonths(6),
                'license_issue_date' => Carbon::now()->subMonths(6),
                'license_expiry_date' => Carbon::now()->addDays(25), // Expires in 25 days
                'status' => 'active',
                'company_address' => 'منطقة الكرادة - بغداد - العراق',
                'contact_person' => 'د. عمر صالح',
                'contact_email' => 'omar@medical-import.com',
                'contact_phone' => '+964-1-5544332',
                'business_activities' => ['استيراد الأدوية', 'التخليص الجمركي', 'التوزيع'],
                'authorized_products' => ['الأدوية المستوردة', 'الأجهزة الطبية', 'المستلزمات الطبية'],
                'compliance_status' => 'compliant',
                'last_inspection_date' => Carbon::now()->subMonths(1),
                'next_inspection_date' => Carbon::now()->addMonths(11),
                'notes' => 'متخصصة في استيراد الأدوية الأوروبية'
            ],
            [
                'company_name' => 'صيدليات الرحمة',
                'company_name_en' => 'Al-Rahma Pharmacies',
                'registration_number' => 'REG-006-2024',
                'license_number' => 'LIC-006-2024',
                'license_type' => 'retail',
                'regulatory_authority' => 'وزارة الصحة العراقية',
                'registration_date' => Carbon::now()->subMonths(4),
                'license_issue_date' => Carbon::now()->subMonths(4),
                'license_expiry_date' => Carbon::now()->addDays(85), // Expires in 85 days
                'status' => 'active',
                'company_address' => 'شارع الرشيد - بغداد - العراق',
                'contact_person' => 'صيدلي أحمد حسين',
                'contact_email' => 'ahmed@alrahma-pharmacy.com',
                'contact_phone' => '+964-1-3344556',
                'business_activities' => ['بيع الأدوية بالتجزئة', 'الاستشارة الصيدلانية', 'خدمات المرضى'],
                'authorized_products' => ['جميع الأدوية', 'مستحضرات التجميل', 'المكملات الغذائية'],
                'compliance_status' => 'compliant',
                'last_inspection_date' => Carbon::now()->subWeeks(2),
                'next_inspection_date' => Carbon::now()->addMonths(10),
                'notes' => 'سلسلة صيدليات موثوقة'
            ]
        ];

        foreach ($companies as $companyData) {
            CompanyRegistration::create(array_merge($companyData, [
                'tenant_id' => $tenant->id
            ]));
        }

        $this->command->info('Company registrations created successfully!');
        $this->command->info('Created 6 companies with different expiry dates for testing.');
        $this->command->info('- 1 expired company (5 days ago)');
        $this->command->info('- 2 companies expiring within 30 days');
        $this->command->info('- 3 companies expiring within 90 days');
    }
}
