<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CustomerPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // إنشاء صلاحيات العملاء
        $customerPermissions = [
            // صلاحيات الطلبيات
            [
                'name' => 'place_orders',
                'display_name' => 'إنشاء طلبيات',
                'description' => 'إمكانية إنشاء طلبيات جديدة',
                'guard_name' => 'customer'
            ],
            [
                'name' => 'view_own_orders',
                'display_name' => 'عرض الطلبيات الخاصة',
                'description' => 'عرض طلبيات العميل الخاصة',
                'guard_name' => 'customer'
            ],
            [
                'name' => 'cancel_orders',
                'display_name' => 'إلغاء الطلبيات',
                'description' => 'إلغاء الطلبيات المعلقة',
                'guard_name' => 'customer'
            ],
            [
                'name' => 'modify_orders',
                'display_name' => 'تعديل الطلبيات',
                'description' => 'تعديل الطلبيات المعلقة',
                'guard_name' => 'customer'
            ],

            // صلاحيات المعلومات المالية
            [
                'name' => 'view_financial_info',
                'display_name' => 'عرض المعلومات المالية',
                'description' => 'عرض الدفعات والمديونية والرصيد',
                'guard_name' => 'customer'
            ],
            [
                'name' => 'view_payment_history',
                'display_name' => 'عرض تاريخ الدفعات',
                'description' => 'عرض تاريخ جميع الدفعات السابقة',
                'guard_name' => 'customer'
            ],
            [
                'name' => 'view_debt_details',
                'display_name' => 'عرض تفاصيل المديونية',
                'description' => 'عرض تفاصيل المديونية السابقة والحالية',
                'guard_name' => 'customer'
            ],
            [
                'name' => 'view_credit_limit',
                'display_name' => 'عرض الحد الائتماني',
                'description' => 'عرض الحد الائتماني والرصيد المتاح',
                'guard_name' => 'customer'
            ],

            // صلاحيات الفواتير
            [
                'name' => 'view_own_invoices',
                'display_name' => 'عرض الفواتير الخاصة',
                'description' => 'عرض فواتير العميل الخاصة',
                'guard_name' => 'customer'
            ],
            [
                'name' => 'download_invoices',
                'display_name' => 'تحميل الفواتير',
                'description' => 'تحميل الفواتير بصيغة PDF',
                'guard_name' => 'customer'
            ],

            // صلاحيات الملف الشخصي
            [
                'name' => 'view_profile',
                'display_name' => 'عرض الملف الشخصي',
                'description' => 'عرض معلومات الملف الشخصي',
                'guard_name' => 'customer'
            ],
            [
                'name' => 'edit_profile',
                'display_name' => 'تعديل الملف الشخصي',
                'description' => 'تعديل معلومات الملف الشخصي',
                'guard_name' => 'customer'
            ],

            // صلاحيات التقارير
            [
                'name' => 'view_order_reports',
                'display_name' => 'عرض تقارير الطلبيات',
                'description' => 'عرض تقارير الطلبيات الخاصة',
                'guard_name' => 'customer'
            ],
            [
                'name' => 'export_reports',
                'display_name' => 'تصدير التقارير',
                'description' => 'تصدير التقارير بصيغ مختلفة',
                'guard_name' => 'customer'
            ],
        ];

        // إنشاء الصلاحيات
        foreach ($customerPermissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission['name'], 'guard_name' => $permission['guard_name']],
                $permission
            );
        }

        // إنشاء أدوار العملاء
        $customerRoles = [
            [
                'name' => 'basic_customer',
                'display_name' => 'عميل أساسي',
                'description' => 'عميل بصلاحيات أساسية',
                'guard_name' => 'customer',
                'permissions' => [
                    'view_own_orders',
                    'view_profile',
                    'edit_profile',
                    'view_own_invoices',
                ]
            ],
            [
                'name' => 'premium_customer',
                'display_name' => 'عميل مميز',
                'description' => 'عميل بصلاحيات متقدمة',
                'guard_name' => 'customer',
                'permissions' => [
                    'place_orders',
                    'view_own_orders',
                    'cancel_orders',
                    'modify_orders',
                    'view_financial_info',
                    'view_payment_history',
                    'view_debt_details',
                    'view_credit_limit',
                    'view_own_invoices',
                    'download_invoices',
                    'view_profile',
                    'edit_profile',
                    'view_order_reports',
                    'export_reports',
                ]
            ],
            [
                'name' => 'vip_customer',
                'display_name' => 'عميل VIP',
                'description' => 'عميل بجميع الصلاحيات',
                'guard_name' => 'customer',
                'permissions' => [
                    'place_orders',
                    'view_own_orders',
                    'cancel_orders',
                    'modify_orders',
                    'view_financial_info',
                    'view_payment_history',
                    'view_debt_details',
                    'view_credit_limit',
                    'view_own_invoices',
                    'download_invoices',
                    'view_profile',
                    'edit_profile',
                    'view_order_reports',
                    'export_reports',
                ]
            ],
        ];

        // إنشاء الأدوار وربطها بالصلاحيات
        foreach ($customerRoles as $roleData) {
            $role = Role::firstOrCreate(
                ['name' => $roleData['name'], 'guard_name' => $roleData['guard_name']],
                [
                    'display_name' => $roleData['display_name'],
                    'description' => $roleData['description'],
                ]
            );

            // ربط الصلاحيات بالدور
            $permissions = Permission::whereIn('name', $roleData['permissions'])
                ->where('guard_name', 'customer')
                ->get();
            
            $role->syncPermissions($permissions);
        }

        $this->command->info('تم إنشاء صلاحيات وأدوار العملاء بنجاح!');
    }
}
