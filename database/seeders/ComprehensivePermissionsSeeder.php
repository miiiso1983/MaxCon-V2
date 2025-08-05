<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class ComprehensivePermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define comprehensive permissions for all project modules
        $permissions = [
            // 📊 إدارة المبيعات (Sales Management)
            'sales.dashboard.view' => 'عرض لوحة تحكم المبيعات',
            'sales.orders.view' => 'عرض طلبات المبيعات',
            'sales.orders.create' => 'إنشاء طلبات المبيعات',
            'sales.orders.edit' => 'تعديل طلبات المبيعات',
            'sales.orders.delete' => 'حذف طلبات المبيعات',
            'sales.invoices.view' => 'عرض الفواتير',
            'sales.invoices.create' => 'إنشاء الفواتير',
            'sales.invoices.edit' => 'تعديل الفواتير',
            'sales.invoices.delete' => 'حذف الفواتير',
            'sales.invoices.print' => 'طباعة الفواتير',
            'sales.customers.view' => 'عرض العملاء',
            'sales.customers.create' => 'إضافة عملاء جدد',
            'sales.customers.edit' => 'تعديل بيانات العملاء',
            'sales.customers.delete' => 'حذف العملاء',
            'sales.products.view' => 'عرض المنتجات',
            'sales.products.create' => 'إضافة منتجات جديدة',
            'sales.products.edit' => 'تعديل المنتجات',
            'sales.products.delete' => 'حذف المنتجات',
            'sales.products.import' => 'استيراد المنتجات',
            'sales.products.export' => 'تصدير المنتجات',
            'sales.returns.view' => 'عرض المرتجعات',
            'sales.returns.create' => 'إنشاء مرتجعات',
            'sales.returns.process' => 'معالجة المرتجعات',
            'sales.targets.view' => 'عرض أهداف المبيعات',
            'sales.targets.create' => 'إنشاء أهداف المبيعات',
            'sales.targets.edit' => 'تعديل أهداف المبيعات',
            'sales.reports.view' => 'عرض تقارير المبيعات',
            'sales.reports.export' => 'تصدير تقارير المبيعات',

            // 📦 إدارة المخزون (Inventory Management)
            'inventory.dashboard.view' => 'عرض لوحة تحكم المخزون',
            'inventory.products.view' => 'عرض منتجات المخزون',
            'inventory.products.create' => 'إضافة منتجات للمخزون',
            'inventory.products.edit' => 'تعديل منتجات المخزون',
            'inventory.products.delete' => 'حذف منتجات من المخزون',
            'inventory.movements.view' => 'عرض حركات المخزون',
            'inventory.movements.create' => 'إنشاء حركات مخزون',
            'inventory.movements.edit' => 'تعديل حركات المخزون',
            'inventory.adjustments.view' => 'عرض تسويات المخزون',
            'inventory.adjustments.create' => 'إنشاء تسويات مخزون',
            'inventory.adjustments.approve' => 'اعتماد تسويات المخزون',
            'inventory.stocktaking.view' => 'عرض جرد المخزون',
            'inventory.stocktaking.create' => 'إنشاء جرد مخزون',
            'inventory.stocktaking.process' => 'معالجة جرد المخزون',
            'inventory.alerts.view' => 'عرض تنبيهات المخزون',
            'inventory.alerts.manage' => 'إدارة تنبيهات المخزون',
            'inventory.reports.view' => 'عرض تقارير المخزون',
            'inventory.reports.export' => 'تصدير تقارير المخزون',
            'inventory.categories.view' => 'عرض فئات المنتجات',
            'inventory.categories.create' => 'إنشاء فئات منتجات',
            'inventory.categories.edit' => 'تعديل فئات المنتجات',
            'inventory.categories.delete' => 'حذف فئات المنتجات',

            // 💰 النظام المحاسبي (Accounting System)
            'accounting.dashboard.view' => 'عرض لوحة تحكم المحاسبة',
            'accounting.accounts.view' => 'عرض دليل الحسابات',
            'accounting.accounts.create' => 'إنشاء حسابات جديدة',
            'accounting.accounts.edit' => 'تعديل الحسابات',
            'accounting.accounts.delete' => 'حذف الحسابات',
            'accounting.journals.view' => 'عرض القيود المحاسبية',
            'accounting.journals.create' => 'إنشاء قيود محاسبية',
            'accounting.journals.edit' => 'تعديل القيود المحاسبية',
            'accounting.journals.approve' => 'اعتماد القيود المحاسبية',
            'accounting.journals.delete' => 'حذف القيود المحاسبية',
            'accounting.reports.trial-balance' => 'عرض ميزان المراجعة',
            'accounting.reports.income-statement' => 'عرض قائمة الدخل',
            'accounting.reports.balance-sheet' => 'عرض الميزانية العمومية',
            'accounting.reports.cash-flow' => 'عرض قائمة التدفقات النقدية',
            'accounting.reports.export' => 'تصدير التقارير المحاسبية',
            'accounting.cost-centers.view' => 'عرض مراكز التكلفة',
            'accounting.cost-centers.create' => 'إنشاء مراكز تكلفة',
            'accounting.cost-centers.edit' => 'تعديل مراكز التكلفة',
            'accounting.fiscal-year.manage' => 'إدارة السنة المالية',

            // 👥 الموارد البشرية (Human Resources)
            'hr.dashboard.view' => 'عرض لوحة تحكم الموارد البشرية',
            'hr.employees.view' => 'عرض الموظفين',
            'hr.employees.create' => 'إضافة موظفين جدد',
            'hr.employees.edit' => 'تعديل بيانات الموظفين',
            'hr.employees.delete' => 'حذف الموظفين',
            'hr.departments.view' => 'عرض الأقسام',
            'hr.departments.create' => 'إنشاء أقسام جديدة',
            'hr.departments.edit' => 'تعديل الأقسام',
            'hr.departments.delete' => 'حذف الأقسام',
            'hr.positions.view' => 'عرض المناصب',
            'hr.positions.create' => 'إنشاء مناصب جديدة',
            'hr.positions.edit' => 'تعديل المناصب',
            'hr.attendance.view' => 'عرض الحضور والانصراف',
            'hr.attendance.manage' => 'إدارة الحضور والانصراف',
            'hr.leaves.view' => 'عرض الإجازات',
            'hr.leaves.create' => 'إنشاء طلبات إجازة',
            'hr.leaves.approve' => 'اعتماد الإجازات',
            'hr.payroll.view' => 'عرض كشوف الرواتب',
            'hr.payroll.create' => 'إنشاء كشوف رواتب',
            'hr.payroll.process' => 'معالجة الرواتب',
            'hr.reports.view' => 'عرض تقارير الموارد البشرية',
            'hr.reports.export' => 'تصدير تقارير الموارد البشرية',

            // 🚚 إدارة المشتريات (Procurement Management)
            'procurement.dashboard.view' => 'عرض لوحة تحكم المشتريات',
            'procurement.suppliers.view' => 'عرض الموردين',
            'procurement.suppliers.create' => 'إضافة موردين جدد',
            'procurement.suppliers.edit' => 'تعديل بيانات الموردين',
            'procurement.suppliers.delete' => 'حذف الموردين',
            'procurement.purchase-orders.view' => 'عرض أوامر الشراء',
            'procurement.purchase-orders.create' => 'إنشاء أوامر شراء',
            'procurement.purchase-orders.edit' => 'تعديل أوامر الشراء',
            'procurement.purchase-orders.approve' => 'اعتماد أوامر الشراء',
            'procurement.purchase-orders.delete' => 'حذف أوامر الشراء',
            'procurement.requests.view' => 'عرض طلبات الشراء',
            'procurement.requests.create' => 'إنشاء طلبات شراء',
            'procurement.requests.approve' => 'اعتماد طلبات الشراء',
            'procurement.receipts.view' => 'عرض إيصالات الاستلام',
            'procurement.receipts.create' => 'إنشاء إيصالات استلام',
            'procurement.contracts.view' => 'عرض العقود',
            'procurement.contracts.create' => 'إنشاء عقود جديدة',
            'procurement.contracts.manage' => 'إدارة العقود',
            'procurement.reports.view' => 'عرض تقارير المشتريات',
            'procurement.reports.export' => 'تصدير تقارير المشتريات',

            // 🛡️ الشؤون التنظيمية (Regulatory Affairs)
            'regulatory.dashboard.view' => 'عرض لوحة تحكم الشؤون التنظيمية',
            'regulatory.licenses.view' => 'عرض التراخيص',
            'regulatory.licenses.create' => 'إنشاء تراخيص جديدة',
            'regulatory.licenses.edit' => 'تعديل التراخيص',
            'regulatory.licenses.renew' => 'تجديد التراخيص',
            'regulatory.inspections.view' => 'عرض التفتيشات',
            'regulatory.inspections.create' => 'إنشاء تفتيشات',
            'regulatory.inspections.manage' => 'إدارة التفتيشات',
            'regulatory.certificates.view' => 'عرض الشهادات',
            'regulatory.certificates.create' => 'إنشاء شهادات',
            'regulatory.certificates.manage' => 'إدارة الشهادات',
            'regulatory.reports.view' => 'عرض التقارير التنظيمية',
            'regulatory.reports.create' => 'إنشاء تقارير تنظيمية',
            'regulatory.reports.submit' => 'تقديم التقارير التنظيمية',
            'regulatory.compliance.view' => 'عرض الامتثال التنظيمي',
            'regulatory.compliance.manage' => 'إدارة الامتثال التنظيمي',
            'regulatory.documents.view' => 'عرض الوثائق التنظيمية',
            'regulatory.documents.upload' => 'رفع الوثائق التنظيمية',
            'regulatory.documents.manage' => 'إدارة الوثائق التنظيمية',

            // 🧠 الذكاء الاصطناعي والتحليلات (AI & Analytics)
            'ai.dashboard.view' => 'عرض لوحة تحكم الذكاء الاصطناعي',
            'ai.analytics.view' => 'عرض التحليلات الذكية',
            'ai.analytics.create' => 'إنشاء تحليلات مخصصة',
            'ai.predictions.view' => 'عرض التنبؤات',
            'ai.predictions.generate' => 'إنتاج تنبؤات جديدة',
            'ai.reports.view' => 'عرض التقارير الذكية',
            'ai.reports.generate' => 'إنتاج تقارير ذكية',
            'ai.insights.view' => 'عرض الرؤى التحليلية',
            'ai.insights.export' => 'تصدير الرؤى التحليلية',
            'ai.models.view' => 'عرض النماذج الذكية',
            'ai.models.train' => 'تدريب النماذج الذكية',
            'ai.models.deploy' => 'نشر النماذج الذكية',

            // 📚 دليل النظام (System Guide)
            'guide.view' => 'عرض دليل النظام',
            'guide.modules.view' => 'عرض أدلة الوحدات',
            'guide.videos.view' => 'عرض الفيديوهات التعليمية',
            'guide.faq.view' => 'عرض الأسئلة الشائعة',
            'guide.download' => 'تحميل الأدلة',
            'guide.search' => 'البحث في الدليل',

            // 📊 التقارير الديناميكية (Dynamic Reports)
            'reports.dashboard.view' => 'عرض لوحة تحكم التقارير',
            'reports.sales.view' => 'عرض تقارير المبيعات',
            'reports.financial.view' => 'عرض التقارير المالية',
            'reports.inventory.view' => 'عرض تقارير المخزون',
            'reports.custom.create' => 'إنشاء تقارير مخصصة',
            'reports.custom.edit' => 'تعديل التقارير المخصصة',
            'reports.export.excel' => 'تصدير التقارير إلى Excel',
            'reports.export.pdf' => 'تصدير التقارير إلى PDF',
            'reports.schedule' => 'جدولة التقارير',
            'reports.email' => 'إرسال التقارير بالبريد الإلكتروني',

            // ⚙️ إدارة النظام (System Management)
            'system.dashboard.view' => 'عرض لوحة تحكم النظام',
            'system.users.view' => 'عرض المستخدمين',
            'system.users.create' => 'إنشاء مستخدمين جدد',
            'system.users.edit' => 'تعديل المستخدمين',
            'system.users.delete' => 'حذف المستخدمين',
            'system.roles.view' => 'عرض الأدوار',
            'system.roles.create' => 'إنشاء أدوار جديدة',
            'system.roles.edit' => 'تعديل الأدوار',
            'system.roles.delete' => 'حذف الأدوار',
            'system.permissions.view' => 'عرض الصلاحيات',
            'system.permissions.assign' => 'تعيين الصلاحيات',
            'system.settings.view' => 'عرض إعدادات النظام',
            'system.settings.edit' => 'تعديل إعدادات النظام',
            'system.backup.create' => 'إنشاء نسخ احتياطية',
            'system.backup.restore' => 'استعادة النسخ الاحتياطية',
            'system.logs.view' => 'عرض سجلات النظام',
            'system.maintenance.access' => 'الوصول لوضع الصيانة',

            // 🌍 التوطين العراقي (Iraqi Localization)
            'localization.currency.manage' => 'إدارة العملة العراقية',
            'localization.tax.manage' => 'إدارة النظام الضريبي العراقي',
            'localization.government-reports.view' => 'عرض التقارير الحكومية',
            'localization.government-reports.generate' => 'إنتاج التقارير الحكومية',
            'localization.banks.manage' => 'إدارة البنوك العراقية',
            'localization.legal.view' => 'عرض المتطلبات القانونية',
        ];

        // Create permissions
        foreach ($permissions as $name => $description) {
            Permission::firstOrCreate(
                ['name' => $name, 'guard_name' => 'web'],
                ['description' => $description]
            );
        }

        $this->command->info('تم إنشاء ' . count($permissions) . ' صلاحية شاملة بنجاح!');
    }
}
