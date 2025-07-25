<?php

namespace App\Services;

use App\Models\Report;
use App\Models\ReportExecution;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ReportService
{
    /**
     * Execute a report with given parameters
     */
    public function executeReport(Report $report, array $parameters = [], string $format = 'html')
    {
        $execution = ReportExecution::create([
            'report_id' => $report->getAttribute('id'),
            'user_id' => auth()->id(),
            'parameters' => $parameters,
            'status' => ReportExecution::STATUS_RUNNING,
            'export_format' => $format,
            'started_at' => now(),
        ]);

        try {
            $startTime = microtime(true);
            
            // Build and execute query
            $query = $this->buildQuery($report, $parameters);
            $data = $query->get();
            
            $endTime = microtime(true);
            $executionTime = round($endTime - $startTime, 2);

            // Update execution record
            $execution->update([
                'status' => ReportExecution::STATUS_COMPLETED,
                'result_data' => $data->toArray(),
                'execution_time' => $executionTime,
                'row_count' => $data->count(),
                'completed_at' => now(),
            ]);

            return [
                'success' => true,
                'data' => $data,
                'execution' => $execution,
                'metadata' => [
                    'execution_time' => $executionTime,
                    'row_count' => $data->count(),
                    'parameters' => $parameters,
                ]
            ];

        } catch (\Exception $e) {
            $execution->update([
                'status' => ReportExecution::STATUS_FAILED,
                'error_message' => $e->getMessage(),
                'completed_at' => now(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
                'execution' => $execution,
            ];
        }
    }

    /**
     * Build query based on report configuration
     */
    protected function buildQuery(Report $report, array $parameters = [])
    {
        $queryBuilder = $report->query_builder;
        $baseTable = $queryBuilder['table'];
        
        $query = DB::table($baseTable);

        // Apply joins
        if (isset($queryBuilder['joins'])) {
            foreach ($queryBuilder['joins'] as $join) {
                $query->join($join['table'], $join['first'], $join['operator'], $join['second']);
            }
        }

        // Apply filters
        if (isset($queryBuilder['filters'])) {
            foreach ($queryBuilder['filters'] as $filter) {
                $this->applyFilter($query, $filter, $parameters);
            }
        }

        // Apply parameter filters
        foreach ($parameters as $key => $value) {
            if (!empty($value)) {
                $this->applyParameterFilter($query, $key, $value);
            }
        }

        // Apply grouping
        if (isset($queryBuilder['group_by'])) {
            $query->groupBy($queryBuilder['group_by']);
        }

        // Apply ordering
        if (isset($queryBuilder['order_by'])) {
            foreach ($queryBuilder['order_by'] as $order) {
                $query->orderBy($order['column'], $order['direction'] ?? 'asc');
            }
        }

        // Select columns
        if (isset($queryBuilder['select'])) {
            $query->select($queryBuilder['select']);
        }

        return $query;
    }

    /**
     * Apply filter to query
     */
    protected function applyFilter($query, array $filter, array $parameters)
    {
        $field = $filter['field'];
        $operator = $filter['operator'];
        $value = $filter['value'];

        // Replace parameter placeholders
        if (is_string($value) && strpos($value, ':') === 0) {
            $paramKey = substr($value, 1);
            $value = $parameters[$paramKey] ?? null;
        }

        if ($value === null) {
            return;
        }

        switch ($operator) {
            case '=':
                $query->where($field, $value);
                break;
            case '!=':
                $query->where($field, '!=', $value);
                break;
            case '>':
                $query->where($field, '>', $value);
                break;
            case '>=':
                $query->where($field, '>=', $value);
                break;
            case '<':
                $query->where($field, '<', $value);
                break;
            case '<=':
                $query->where($field, '<=', $value);
                break;
            case 'like':
                $query->where($field, 'like', "%{$value}%");
                break;
            case 'in':
                $query->whereIn($field, is_array($value) ? $value : [$value]);
                break;
            case 'between':
                if (is_array($value) && count($value) === 2) {
                    $query->whereBetween($field, $value);
                }
                break;
            case 'date_range':
                if (is_array($value) && count($value) === 2) {
                    $query->whereBetween($field, [
                        Carbon::parse($value[0])->startOfDay(),
                        Carbon::parse($value[1])->endOfDay(),
                    ]);
                }
                break;
        }
    }

    /**
     * Apply parameter filter
     */
    protected function applyParameterFilter($query, string $key, $value)
    {
        switch ($key) {
            case 'date_from':
                $query->where('created_at', '>=', Carbon::parse($value)->startOfDay());
                break;
            case 'date_to':
                $query->where('created_at', '<=', Carbon::parse($value)->endOfDay());
                break;
            case 'customer_id':
                $query->where('customer_id', $value);
                break;
            case 'product_id':
                $query->where('product_id', $value);
                break;
            case 'category_id':
                $query->where('category_id', $value);
                break;
            case 'status':
                $query->where('status', $value);
                break;
        }
    }

    /**
     * Get predefined reports by category
     */
    public function getPredefinedReports()
    {
        return [
            'sales' => $this->getSalesReports(),
            'financial' => $this->getFinancialReports(),
            'inventory' => $this->getInventoryReports(),
            'customers' => $this->getCustomerReports(),
            'products' => $this->getProductReports(),
        ];
    }

    /**
     * Get sales reports configuration
     */
    protected function getSalesReports()
    {
        return [
            [
                'name' => 'تقرير المبيعات اليومية',
                'description' => 'ملخص المبيعات اليومية مع التفاصيل',
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
                ]
            ],
            [
                'name' => 'تقرير أداء المندوبين',
                'description' => 'تقرير أداء مندوبي المبيعات',
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
                ]
            ]
        ];
    }

    /**
     * Get financial reports configuration
     */
    protected function getFinancialReports()
    {
        return [
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
                ]
            ],
            [
                'name' => 'تقرير الذمم المدينة',
                'description' => 'تقرير الذمم المدينة والمستحقات على العملاء',
                'type' => Report::TYPE_SUMMARY,
                'category' => Report::CATEGORY_FINANCIAL,
                'query_builder' => [
                    'table' => 'invoices',
                    'joins' => [
                        ['table' => 'customers', 'first' => 'invoices.customer_id', 'operator' => '=', 'second' => 'customers.id'],
                    ],
                    'select' => [
                        'customers.name as customer_name',
                        'customers.phone',
                        'invoices.invoice_number',
                        'invoices.total_amount',
                        'invoices.paid_amount',
                        DB::raw('(invoices.total_amount - COALESCE(invoices.paid_amount, 0)) as remaining_amount'),
                        'invoices.due_date',
                        'invoices.created_at',
                        DB::raw('DATEDIFF(NOW(), invoices.due_date) as days_overdue'),
                    ],
                    'filters' => [
                        ['field' => 'invoices.status', 'operator' => '!=', 'value' => 'paid'],
                    ],
                    'order_by' => [
                        ['column' => 'days_overdue', 'direction' => 'desc']
                    ]
                ],
                'columns' => [
                    ['field' => 'customer_name', 'label' => 'اسم العميل', 'type' => 'text'],
                    ['field' => 'phone', 'label' => 'الهاتف', 'type' => 'text'],
                    ['field' => 'invoice_number', 'label' => 'رقم الفاتورة', 'type' => 'text'],
                    ['field' => 'total_amount', 'label' => 'إجمالي المبلغ', 'type' => 'currency'],
                    ['field' => 'paid_amount', 'label' => 'المبلغ المدفوع', 'type' => 'currency'],
                    ['field' => 'remaining_amount', 'label' => 'المبلغ المتبقي', 'type' => 'currency'],
                    ['field' => 'due_date', 'label' => 'تاريخ الاستحقاق', 'type' => 'date'],
                    ['field' => 'days_overdue', 'label' => 'أيام التأخير', 'type' => 'number'],
                ]
            ],
            [
                'name' => 'تقرير الميزانية العمومية',
                'description' => 'تقرير الميزانية العمومية والمركز المالي',
                'type' => Report::TYPE_SUMMARY,
                'category' => Report::CATEGORY_FINANCIAL,
                'query_builder' => [
                    'table' => 'accounts',
                    'select' => [
                        'accounts.account_code',
                        'accounts.account_name',
                        'accounts.account_type',
                        DB::raw('COALESCE(SUM(journal_entries.debit_amount), 0) as total_debit'),
                        DB::raw('COALESCE(SUM(journal_entries.credit_amount), 0) as total_credit'),
                        DB::raw('(COALESCE(SUM(journal_entries.debit_amount), 0) - COALESCE(SUM(journal_entries.credit_amount), 0)) as balance'),
                    ],
                    'joins' => [
                        ['table' => 'journal_entries', 'first' => 'accounts.id', 'operator' => '=', 'second' => 'journal_entries.account_id', 'type' => 'left'],
                    ],
                    'group_by' => ['accounts.id', 'accounts.account_code', 'accounts.account_name', 'accounts.account_type'],
                    'order_by' => [
                        ['column' => 'accounts.account_code', 'direction' => 'asc']
                    ]
                ],
                'columns' => [
                    ['field' => 'account_code', 'label' => 'رمز الحساب', 'type' => 'text'],
                    ['field' => 'account_name', 'label' => 'اسم الحساب', 'type' => 'text'],
                    ['field' => 'account_type', 'label' => 'نوع الحساب', 'type' => 'text'],
                    ['field' => 'total_debit', 'label' => 'إجمالي المدين', 'type' => 'currency'],
                    ['field' => 'total_credit', 'label' => 'إجمالي الدائن', 'type' => 'currency'],
                    ['field' => 'balance', 'label' => 'الرصيد', 'type' => 'currency'],
                ]
            ]
        ];
    }

    /**
     * Get inventory reports configuration
     */
    protected function getInventoryReports()
    {
        return [
            [
                'name' => 'تقرير مستويات المخزون',
                'description' => 'تقرير مستويات المخزون الحالية',
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
                ]
            ],
            [
                'name' => 'تقرير حركات المخزون',
                'description' => 'تقرير حركات المخزون الداخلة والخارجة',
                'type' => Report::TYPE_SUMMARY,
                'category' => Report::CATEGORY_INVENTORY,
                'query_builder' => [
                    'table' => 'inventory_movements',
                    'joins' => [
                        ['table' => 'products', 'first' => 'inventory_movements.product_id', 'operator' => '=', 'second' => 'products.id'],
                        ['table' => 'warehouses', 'first' => 'inventory_movements.warehouse_id', 'operator' => '=', 'second' => 'warehouses.id'],
                    ],
                    'select' => [
                        'products.name as product_name',
                        'products.sku',
                        'warehouses.name as warehouse_name',
                        'inventory_movements.movement_type',
                        'inventory_movements.quantity',
                        'inventory_movements.unit_cost',
                        DB::raw('(inventory_movements.quantity * inventory_movements.unit_cost) as total_value'),
                        'inventory_movements.reference_number',
                        'inventory_movements.notes',
                        'inventory_movements.created_at',
                    ],
                    'filters' => [
                        ['field' => 'inventory_movements.created_at', 'operator' => 'date_range', 'value' => ':date_range']
                    ],
                    'order_by' => [
                        ['column' => 'inventory_movements.created_at', 'direction' => 'desc']
                    ]
                ],
                'columns' => [
                    ['field' => 'product_name', 'label' => 'اسم المنتج', 'type' => 'text'],
                    ['field' => 'sku', 'label' => 'رمز المنتج', 'type' => 'text'],
                    ['field' => 'warehouse_name', 'label' => 'المستودع', 'type' => 'text'],
                    ['field' => 'movement_type', 'label' => 'نوع الحركة', 'type' => 'text'],
                    ['field' => 'quantity', 'label' => 'الكمية', 'type' => 'number'],
                    ['field' => 'unit_cost', 'label' => 'تكلفة الوحدة', 'type' => 'currency'],
                    ['field' => 'total_value', 'label' => 'القيمة الإجمالية', 'type' => 'currency'],
                    ['field' => 'reference_number', 'label' => 'رقم المرجع', 'type' => 'text'],
                    ['field' => 'created_at', 'label' => 'التاريخ', 'type' => 'datetime'],
                ]
            ],
            [
                'name' => 'تقرير تنبيهات النفاد',
                'description' => 'تقرير المنتجات التي وصلت للحد الأدنى أو نفدت',
                'type' => Report::TYPE_ALERT,
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
                        DB::raw('(products.min_quantity - products.quantity) as shortage_quantity'),
                        DB::raw('CASE
                            WHEN products.quantity = 0 THEN "نفد"
                            WHEN products.quantity <= products.min_quantity THEN "حد أدنى"
                            ELSE "طبيعي"
                        END as status'),
                    ],
                    'filters' => [
                        ['field' => 'products.quantity', 'operator' => '<=', 'value' => 'products.min_quantity']
                    ],
                    'order_by' => [
                        ['column' => 'products.quantity', 'direction' => 'asc']
                    ]
                ],
                'columns' => [
                    ['field' => 'name', 'label' => 'اسم المنتج', 'type' => 'text'],
                    ['field' => 'sku', 'label' => 'رمز المنتج', 'type' => 'text'],
                    ['field' => 'category_name', 'label' => 'الفئة', 'type' => 'text'],
                    ['field' => 'quantity', 'label' => 'الكمية المتاحة', 'type' => 'number'],
                    ['field' => 'min_quantity', 'label' => 'الحد الأدنى', 'type' => 'number'],
                    ['field' => 'shortage_quantity', 'label' => 'كمية النقص', 'type' => 'number'],
                    ['field' => 'status', 'label' => 'الحالة', 'type' => 'text'],
                    ['field' => 'price', 'label' => 'السعر', 'type' => 'currency'],
                ]
            ]
        ];
    }

    /**
     * Get customer reports configuration
     */
    protected function getCustomerReports()
    {
        return [
            [
                'name' => 'تقرير العملاء الأكثر شراءً',
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
                ]
            ]
        ];
    }

    /**
     * Get product reports configuration
     */
    protected function getProductReports()
    {
        return [
            [
                'name' => 'تقرير المنتجات الأكثر مبيعاً',
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
                ]
            ],
            [
                'name' => 'تقرير تحليل ربحية المنتجات',
                'description' => 'تقرير تحليل ربحية المنتجات والهوامش',
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
                        'products.cost_price',
                        DB::raw('AVG(invoice_items.unit_price) as avg_selling_price'),
                        DB::raw('SUM(invoice_items.quantity) as total_sold'),
                        DB::raw('SUM(invoice_items.total_price) as total_revenue'),
                        DB::raw('SUM(invoice_items.quantity * products.cost_price) as total_cost'),
                        DB::raw('(SUM(invoice_items.total_price) - SUM(invoice_items.quantity * products.cost_price)) as total_profit'),
                        DB::raw('((SUM(invoice_items.total_price) - SUM(invoice_items.quantity * products.cost_price)) / SUM(invoice_items.total_price) * 100) as profit_margin'),
                    ],
                    'group_by' => ['products.id', 'products.name', 'products.sku', 'categories.name', 'products.cost_price'],
                    'order_by' => [
                        ['column' => 'total_profit', 'direction' => 'desc']
                    ]
                ],
                'columns' => [
                    ['field' => 'name', 'label' => 'اسم المنتج', 'type' => 'text'],
                    ['field' => 'sku', 'label' => 'رمز المنتج', 'type' => 'text'],
                    ['field' => 'category_name', 'label' => 'الفئة', 'type' => 'text'],
                    ['field' => 'cost_price', 'label' => 'سعر التكلفة', 'type' => 'currency'],
                    ['field' => 'avg_selling_price', 'label' => 'متوسط سعر البيع', 'type' => 'currency'],
                    ['field' => 'total_sold', 'label' => 'الكمية المباعة', 'type' => 'number'],
                    ['field' => 'total_revenue', 'label' => 'إجمالي الإيرادات', 'type' => 'currency'],
                    ['field' => 'total_cost', 'label' => 'إجمالي التكلفة', 'type' => 'currency'],
                    ['field' => 'total_profit', 'label' => 'إجمالي الربح', 'type' => 'currency'],
                    ['field' => 'profit_margin', 'label' => 'هامش الربح %', 'type' => 'percentage'],
                ]
            ]
        ];
    }
}
