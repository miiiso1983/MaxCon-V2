<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Report;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ReportsSeeder extends Seeder
{
    public function run()
    {
        // Get first admin user
        $adminUser = User::first();
        if (!$adminUser) {
            $this->command->error('No users found. Please create a user first.');
            return;
        }

        $reports = [
            // Sales Reports
            [
                'name' => 'تقرير المبيعات اليومية',
                'description' => 'ملخص المبيعات اليومية مع تفاصيل العملاء والمبالغ',
                'type' => Report::TYPE_SUMMARY,
                'category' => Report::CATEGORY_SALES,
                'query_builder' => [
                    'table' => 'invoices',
                    'joins' => [
                        ['table' => 'customers', 'first' => 'invoices.customer_id', 'operator' => '=', 'second' => 'customers.id'],
                    ],
                    'select' => [
                        'invoices.id',
                        'invoices.invoice_number',
                        'customers.name as customer_name',
                        'invoices.total_amount',
                        'invoices.created_at',
                        'invoices.status'
                    ],
                    'filters' => [
                        ['field' => 'invoices.created_at', 'operator' => 'date_range', 'value' => ':date_range']
                    ],
                    'order_by' => [
                        ['column' => 'invoices.created_at', 'direction' => 'desc']
                    ]
                ],
                'columns' => [
                    ['field' => 'invoice_number', 'label' => 'رقم الفاتورة', 'type' => 'text'],
                    ['field' => 'customer_name', 'label' => 'العميل', 'type' => 'text'],
                    ['field' => 'total_amount', 'label' => 'المبلغ الإجمالي', 'type' => 'currency'],
                    ['field' => 'created_at', 'label' => 'التاريخ', 'type' => 'datetime'],
                    ['field' => 'status', 'label' => 'الحالة', 'type' => 'text'],
                ],
                'created_by' => $adminUser->id,
            ],
            
            [
                'name' => 'تقرير أداء المندوبين',
                'description' => 'تقرير تحليلي لأداء مندوبي المبيعات',
                'type' => Report::TYPE_ANALYTICAL,
                'category' => Report::CATEGORY_SALES,
                'query_builder' => [
                    'table' => 'invoices',
                    'joins' => [
                        ['table' => 'users', 'first' => 'invoices.sales_rep_id', 'operator' => '=', 'second' => 'users.id'],
                    ],
                    'select' => [
                        'users.name as sales_rep_name',
                        DB::raw('COUNT(invoices.id) as total_invoices'),
                        DB::raw('SUM(invoices.total_amount) as total_sales'),
                        DB::raw('AVG(invoices.total_amount) as avg_invoice_amount'),
                    ],
                    'group_by' => ['users.id', 'users.name'],
                    'order_by' => [
                        ['column' => 'total_sales', 'direction' => 'desc']
                    ]
                ],
                'columns' => [
                    ['field' => 'sales_rep_name', 'label' => 'المندوب', 'type' => 'text'],
                    ['field' => 'total_invoices', 'label' => 'عدد الفواتير', 'type' => 'number'],
                    ['field' => 'total_sales', 'label' => 'إجمالي المبيعات', 'type' => 'currency'],
                    ['field' => 'avg_invoice_amount', 'label' => 'متوسط الفاتورة', 'type' => 'currency'],
                ],
                'created_by' => $adminUser->id,
            ],

            // Financial Reports
            [
                'name' => 'تقرير التدفقات النقدية',
                'description' => 'تقرير التدفقات النقدية الداخلة والخارجة',
                'type' => Report::TYPE_SUMMARY,
                'category' => Report::CATEGORY_FINANCIAL,
                'query_builder' => [
                    'table' => 'transactions',
                    'select' => [
                        'transactions.id',
                        'transactions.description',
                        'transactions.amount',
                        'transactions.type',
                        'transactions.created_at',
                    ],
                    'filters' => [
                        ['field' => 'transactions.created_at', 'operator' => 'date_range', 'value' => ':date_range']
                    ],
                    'order_by' => [
                        ['column' => 'transactions.created_at', 'direction' => 'desc']
                    ]
                ],
                'columns' => [
                    ['field' => 'description', 'label' => 'الوصف', 'type' => 'text'],
                    ['field' => 'amount', 'label' => 'المبلغ', 'type' => 'currency'],
                    ['field' => 'type', 'label' => 'النوع', 'type' => 'text'],
                    ['field' => 'created_at', 'label' => 'التاريخ', 'type' => 'datetime'],
                ],
                'created_by' => $adminUser->id,
            ],

            // Inventory Reports
            [
                'name' => 'تقرير مستويات المخزون',
                'description' => 'تقرير مستويات المخزون الحالية مع التقييم',
                'type' => Report::TYPE_SUMMARY,
                'category' => Report::CATEGORY_INVENTORY,
                'query_builder' => [
                    'table' => 'products',
                    'joins' => [
                        ['table' => 'categories', 'first' => 'products.category_id', 'operator' => '=', 'second' => 'categories.id'],
                    ],
                    'select' => [
                        'products.name',
                        'products.sku',
                        'categories.name as category_name',
                        'products.quantity',
                        'products.min_quantity',
                        'products.price',
                        DB::raw('(products.quantity * products.price) as total_value'),
                    ],
                    'order_by' => [
                        ['column' => 'products.quantity', 'direction' => 'asc']
                    ]
                ],
                'columns' => [
                    ['field' => 'name', 'label' => 'اسم المنتج', 'type' => 'text'],
                    ['field' => 'sku', 'label' => 'رمز المنتج', 'type' => 'text'],
                    ['field' => 'category_name', 'label' => 'الفئة', 'type' => 'text'],
                    ['field' => 'quantity', 'label' => 'الكمية', 'type' => 'number'],
                    ['field' => 'min_quantity', 'label' => 'الحد الأدنى', 'type' => 'number'],
                    ['field' => 'price', 'label' => 'السعر', 'type' => 'currency'],
                    ['field' => 'total_value', 'label' => 'القيمة الإجمالية', 'type' => 'currency'],
                ],
                'created_by' => $adminUser->id,
            ],

            // Customer Reports
            [
                'name' => 'العملاء الأكثر شراءً',
                'description' => 'تقرير العملاء مرتبين حسب حجم المشتريات',
                'type' => Report::TYPE_ANALYTICAL,
                'category' => Report::CATEGORY_CUSTOMERS,
                'query_builder' => [
                    'table' => 'customers',
                    'joins' => [
                        ['table' => 'invoices', 'first' => 'customers.id', 'operator' => '=', 'second' => 'invoices.customer_id'],
                    ],
                    'select' => [
                        'customers.name',
                        'customers.email',
                        'customers.phone',
                        DB::raw('COUNT(invoices.id) as total_orders'),
                        DB::raw('SUM(invoices.total_amount) as total_spent'),
                        DB::raw('AVG(invoices.total_amount) as avg_order_value'),
                    ],
                    'group_by' => ['customers.id', 'customers.name', 'customers.email', 'customers.phone'],
                    'order_by' => [
                        ['column' => 'total_spent', 'direction' => 'desc']
                    ]
                ],
                'columns' => [
                    ['field' => 'name', 'label' => 'اسم العميل', 'type' => 'text'],
                    ['field' => 'email', 'label' => 'البريد الإلكتروني', 'type' => 'text'],
                    ['field' => 'phone', 'label' => 'الهاتف', 'type' => 'text'],
                    ['field' => 'total_orders', 'label' => 'عدد الطلبات', 'type' => 'number'],
                    ['field' => 'total_spent', 'label' => 'إجمالي المشتريات', 'type' => 'currency'],
                    ['field' => 'avg_order_value', 'label' => 'متوسط قيمة الطلب', 'type' => 'currency'],
                ],
                'created_by' => $adminUser->id,
            ],

            // Product Reports
            [
                'name' => 'المنتجات الأكثر مبيعاً',
                'description' => 'تقرير المنتجات مرتبة حسب المبيعات',
                'type' => Report::TYPE_ANALYTICAL,
                'category' => Report::CATEGORY_PRODUCTS,
                'query_builder' => [
                    'table' => 'products',
                    'joins' => [
                        ['table' => 'invoice_items', 'first' => 'products.id', 'operator' => '=', 'second' => 'invoice_items.product_id'],
                        ['table' => 'categories', 'first' => 'products.category_id', 'operator' => '=', 'second' => 'categories.id'],
                    ],
                    'select' => [
                        'products.name',
                        'products.sku',
                        'categories.name as category_name',
                        DB::raw('SUM(invoice_items.quantity) as total_sold'),
                        DB::raw('SUM(invoice_items.total_price) as total_revenue'),
                        DB::raw('AVG(invoice_items.unit_price) as avg_price'),
                    ],
                    'group_by' => ['products.id', 'products.name', 'products.sku', 'categories.name'],
                    'order_by' => [
                        ['column' => 'total_sold', 'direction' => 'desc']
                    ]
                ],
                'columns' => [
                    ['field' => 'name', 'label' => 'اسم المنتج', 'type' => 'text'],
                    ['field' => 'sku', 'label' => 'رمز المنتج', 'type' => 'text'],
                    ['field' => 'category_name', 'label' => 'الفئة', 'type' => 'text'],
                    ['field' => 'total_sold', 'label' => 'الكمية المباعة', 'type' => 'number'],
                    ['field' => 'total_revenue', 'label' => 'إجمالي الإيرادات', 'type' => 'currency'],
                    ['field' => 'avg_price', 'label' => 'متوسط السعر', 'type' => 'currency'],
                ],
                'created_by' => $adminUser->id,
            ],
        ];

        foreach ($reports as $reportData) {
            Report::firstOrCreate(
                ['name' => $reportData['name']],
                $reportData
            );
        }

        $this->command->info('تم إنشاء ' . count($reports) . ' تقرير محدد مسبقاً بنجاح!');
    }
}
