<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Accounting\ChartOfAccount;
use App\Models\Accounting\CostCenter;

class BasicChartOfAccountsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // This seeder creates a basic chart of accounts for pharmaceutical companies
        // It should be run for each tenant

        $tenantId = 1; // You can modify this or pass it as parameter

        // Create basic cost centers first
        $this->createBasicCostCenters($tenantId);

        // Create main account categories
        $this->createAssetAccounts($tenantId);
        $this->createLiabilityAccounts($tenantId);
        $this->createEquityAccounts($tenantId);
        $this->createRevenueAccounts($tenantId);
        $this->createExpenseAccounts($tenantId);
    }

    private function createBasicCostCenters($tenantId)
    {
        $costCenters = [
            [
                'tenant_id' => $tenantId,
                'code' => 'CC001',
                'name' => 'الإدارة العامة',
                'name_en' => 'General Administration',
                'description' => 'مركز تكلفة الإدارة العامة',
                'is_active' => true,
                'currency_code' => 'IQD'
            ],
            [
                'tenant_id' => $tenantId,
                'code' => 'CC002',
                'name' => 'المبيعات',
                'name_en' => 'Sales',
                'description' => 'مركز تكلفة المبيعات',
                'is_active' => true,
                'currency_code' => 'IQD'
            ],
            [
                'tenant_id' => $tenantId,
                'code' => 'CC003',
                'name' => 'المخازن',
                'name_en' => 'Inventory',
                'description' => 'مركز تكلفة المخازن',
                'is_active' => true,
                'currency_code' => 'IQD'
            ]
        ];

        foreach ($costCenters as $costCenter) {
            CostCenter::create($costCenter);
        }
    }

    private function createAssetAccounts($tenantId)
    {
        $assets = [
            // Current Assets
            [
                'account_code' => '1001',
                'account_name' => 'النقدية في الصندوق',
                'account_name_en' => 'Cash on Hand',
                'account_type' => 'asset',
                'account_category' => 'current_asset',
                'is_system_account' => true
            ],
            [
                'account_code' => '1002',
                'account_name' => 'النقدية في البنك',
                'account_name_en' => 'Cash in Bank',
                'account_type' => 'asset',
                'account_category' => 'current_asset',
                'is_system_account' => true
            ],
            [
                'account_code' => '1101',
                'account_name' => 'العملاء',
                'account_name_en' => 'Accounts Receivable',
                'account_type' => 'asset',
                'account_category' => 'current_asset',
                'is_system_account' => true
            ],
            [
                'account_code' => '1201',
                'account_name' => 'مخزون الأدوية',
                'account_name_en' => 'Pharmaceutical Inventory',
                'account_type' => 'asset',
                'account_category' => 'current_asset',
                'is_system_account' => true
            ],
            [
                'account_code' => '1202',
                'account_name' => 'مخزون المستلزمات الطبية',
                'account_name_en' => 'Medical Supplies Inventory',
                'account_type' => 'asset',
                'account_category' => 'current_asset'
            ],
            [
                'account_code' => '1301',
                'account_name' => 'المصروفات المدفوعة مقدماً',
                'account_name_en' => 'Prepaid Expenses',
                'account_type' => 'asset',
                'account_category' => 'current_asset'
            ],

            // Non-Current Assets
            [
                'account_code' => '1501',
                'account_name' => 'الأثاث والمعدات',
                'account_name_en' => 'Furniture and Equipment',
                'account_type' => 'asset',
                'account_category' => 'non_current_asset'
            ],
            [
                'account_code' => '1502',
                'account_name' => 'أجهزة الكمبيوتر',
                'account_name_en' => 'Computer Equipment',
                'account_type' => 'asset',
                'account_category' => 'non_current_asset'
            ],
            [
                'account_code' => '1503',
                'account_name' => 'السيارات',
                'account_name_en' => 'Vehicles',
                'account_type' => 'asset',
                'account_category' => 'non_current_asset'
            ],
            [
                'account_code' => '1601',
                'account_name' => 'مجمع إهلاك الأثاث والمعدات',
                'account_name_en' => 'Accumulated Depreciation - Furniture',
                'account_type' => 'asset',
                'account_category' => 'non_current_asset'
            ]
        ];

        foreach ($assets as $asset) {
            ChartOfAccount::create(array_merge($asset, [
                'tenant_id' => $tenantId,
                'is_active' => true,
                'currency_code' => 'IQD'
            ]));
        }
    }

    private function createLiabilityAccounts($tenantId)
    {
        $liabilities = [
            // Current Liabilities
            [
                'account_code' => '2001',
                'account_name' => 'الموردون',
                'account_name_en' => 'Accounts Payable',
                'account_type' => 'liability',
                'account_category' => 'current_liability',
                'is_system_account' => true
            ],
            [
                'account_code' => '2101',
                'account_name' => 'رواتب مستحقة',
                'account_name_en' => 'Accrued Salaries',
                'account_type' => 'liability',
                'account_category' => 'current_liability'
            ],
            [
                'account_code' => '2102',
                'account_name' => 'ضرائب مستحقة',
                'account_name_en' => 'Accrued Taxes',
                'account_type' => 'liability',
                'account_category' => 'current_liability'
            ],
            [
                'account_code' => '2201',
                'account_name' => 'قروض قصيرة الأجل',
                'account_name_en' => 'Short-term Loans',
                'account_type' => 'liability',
                'account_category' => 'current_liability'
            ],

            // Non-Current Liabilities
            [
                'account_code' => '2501',
                'account_name' => 'قروض طويلة الأجل',
                'account_name_en' => 'Long-term Loans',
                'account_type' => 'liability',
                'account_category' => 'non_current_liability'
            ]
        ];

        foreach ($liabilities as $liability) {
            ChartOfAccount::create(array_merge($liability, [
                'tenant_id' => $tenantId,
                'is_active' => true,
                'currency_code' => 'IQD'
            ]));
        }
    }

    private function createEquityAccounts($tenantId)
    {
        $equity = [
            [
                'account_code' => '3001',
                'account_name' => 'رأس المال',
                'account_name_en' => 'Capital',
                'account_type' => 'equity',
                'account_category' => 'owners_equity',
                'is_system_account' => true
            ],
            [
                'account_code' => '3101',
                'account_name' => 'الأرباح المحتجزة',
                'account_name_en' => 'Retained Earnings',
                'account_type' => 'equity',
                'account_category' => 'owners_equity',
                'is_system_account' => true
            ],
            [
                'account_code' => '3201',
                'account_name' => 'أرباح السنة الحالية',
                'account_name_en' => 'Current Year Earnings',
                'account_type' => 'equity',
                'account_category' => 'owners_equity',
                'is_system_account' => true
            ]
        ];

        foreach ($equity as $equityAccount) {
            ChartOfAccount::create(array_merge($equityAccount, [
                'tenant_id' => $tenantId,
                'is_active' => true,
                'currency_code' => 'IQD'
            ]));
        }
    }

    private function createRevenueAccounts($tenantId)
    {
        $revenues = [
            [
                'account_code' => '4001',
                'account_name' => 'مبيعات الأدوية',
                'account_name_en' => 'Pharmaceutical Sales',
                'account_type' => 'revenue',
                'account_category' => 'operating_revenue',
                'is_system_account' => true
            ],
            [
                'account_code' => '4002',
                'account_name' => 'مبيعات المستلزمات الطبية',
                'account_name_en' => 'Medical Supplies Sales',
                'account_type' => 'revenue',
                'account_category' => 'operating_revenue'
            ],
            [
                'account_code' => '4101',
                'account_name' => 'خصومات مسموحة',
                'account_name_en' => 'Sales Discounts',
                'account_type' => 'revenue',
                'account_category' => 'operating_revenue'
            ],
            [
                'account_code' => '4201',
                'account_name' => 'إيرادات أخرى',
                'account_name_en' => 'Other Income',
                'account_type' => 'revenue',
                'account_category' => 'non_operating_revenue'
            ],
            [
                'account_code' => '4202',
                'account_name' => 'إيرادات فوائد',
                'account_name_en' => 'Interest Income',
                'account_type' => 'revenue',
                'account_category' => 'non_operating_revenue'
            ]
        ];

        foreach ($revenues as $revenue) {
            ChartOfAccount::create(array_merge($revenue, [
                'tenant_id' => $tenantId,
                'is_active' => true,
                'currency_code' => 'IQD'
            ]));
        }
    }

    private function createExpenseAccounts($tenantId)
    {
        $expenses = [
            // Cost of Goods Sold
            [
                'account_code' => '5001',
                'account_name' => 'تكلفة البضاعة المباعة - أدوية',
                'account_name_en' => 'Cost of Goods Sold - Pharmaceuticals',
                'account_type' => 'expense',
                'account_category' => 'operating_expense',
                'is_system_account' => true
            ],
            [
                'account_code' => '5002',
                'account_name' => 'تكلفة البضاعة المباعة - مستلزمات',
                'account_name_en' => 'Cost of Goods Sold - Supplies',
                'account_type' => 'expense',
                'account_category' => 'operating_expense'
            ],

            // Operating Expenses
            [
                'account_code' => '5101',
                'account_name' => 'رواتب وأجور',
                'account_name_en' => 'Salaries and Wages',
                'account_type' => 'expense',
                'account_category' => 'operating_expense'
            ],
            [
                'account_code' => '5102',
                'account_name' => 'إيجار المحل',
                'account_name_en' => 'Rent Expense',
                'account_type' => 'expense',
                'account_category' => 'operating_expense'
            ],
            [
                'account_code' => '5103',
                'account_name' => 'فواتير الكهرباء',
                'account_name_en' => 'Electricity Expense',
                'account_type' => 'expense',
                'account_category' => 'operating_expense'
            ],
            [
                'account_code' => '5104',
                'account_name' => 'فواتير الماء',
                'account_name_en' => 'Water Expense',
                'account_type' => 'expense',
                'account_category' => 'operating_expense'
            ],
            [
                'account_code' => '5105',
                'account_name' => 'فواتير الهاتف والإنترنت',
                'account_name_en' => 'Telephone and Internet',
                'account_type' => 'expense',
                'account_category' => 'operating_expense'
            ],
            [
                'account_code' => '5201',
                'account_name' => 'مصروفات التسويق والإعلان',
                'account_name_en' => 'Marketing and Advertising',
                'account_type' => 'expense',
                'account_category' => 'operating_expense'
            ],
            [
                'account_code' => '5202',
                'account_name' => 'مصروفات التوصيل',
                'account_name_en' => 'Delivery Expenses',
                'account_type' => 'expense',
                'account_category' => 'operating_expense'
            ],
            [
                'account_code' => '5301',
                'account_name' => 'إهلاك الأثاث والمعدات',
                'account_name_en' => 'Depreciation Expense',
                'account_type' => 'expense',
                'account_category' => 'operating_expense'
            ],
            [
                'account_code' => '5302',
                'account_name' => 'صيانة وإصلاح',
                'account_name_en' => 'Maintenance and Repairs',
                'account_type' => 'expense',
                'account_category' => 'operating_expense'
            ],
            [
                'account_code' => '5401',
                'account_name' => 'مصروفات إدارية أخرى',
                'account_name_en' => 'Other Administrative Expenses',
                'account_type' => 'expense',
                'account_category' => 'operating_expense'
            ],

            // Non-Operating Expenses
            [
                'account_code' => '5501',
                'account_name' => 'فوائد القروض',
                'account_name_en' => 'Interest Expense',
                'account_type' => 'expense',
                'account_category' => 'non_operating_expense'
            ],
            [
                'account_code' => '5502',
                'account_name' => 'خسائر أخرى',
                'account_name_en' => 'Other Losses',
                'account_type' => 'expense',
                'account_category' => 'non_operating_expense'
            ]
        ];

        foreach ($expenses as $expense) {
            ChartOfAccount::create(array_merge($expense, [
                'tenant_id' => $tenantId,
                'is_active' => true,
                'currency_code' => 'IQD'
            ]));
        }
    }
}
