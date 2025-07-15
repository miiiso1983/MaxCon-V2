<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\TenantController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\LicenseController;
use App\Http\Controllers\Tenant\RoleController;
use App\Http\Controllers\Tenant\Sales\SalesOrderController;
use App\Http\Controllers\Tenant\Sales\CustomerController;
use App\Http\Controllers\Tenant\Sales\ProductController;
use App\Http\Controllers\Tenant\Sales\InvoiceController;
use App\Http\Controllers\Tenant\Sales\ReturnController;
use App\Http\Controllers\Tenant\Inventory\WarehouseController;
use App\Http\Controllers\Tenant\Inventory\InventoryController;
use App\Http\Controllers\Tenant\Inventory\InventoryMovementController;
use App\Http\Controllers\Tenant\Inventory\InventoryAuditController;
use App\Http\Controllers\Tenant\Inventory\InventoryAlertController;
use App\Http\Controllers\Tenant\Inventory\InventoryReportController;
use App\Http\Controllers\Tenant\Inventory\CustomReportController;
use App\Http\Controllers\Tenant\Inventory\AnalyticsDashboardController;
use App\Http\Controllers\Tenant\Inventory\ProductCategoryController;
use App\Http\Controllers\Tenant\Inventory\ProductController as InventoryProductController;
use App\Http\Controllers\Tenant\Inventory\ProductQRController;
use App\Http\Controllers\Tenant\Purchasing\SupplierController;
use App\Http\Controllers\Tenant\Purchasing\PurchaseRequestController;
use App\Http\Controllers\Tenant\Purchasing\PurchaseOrderController;
use App\Http\Controllers\Tenant\Purchasing\QuotationController;
use App\Http\Controllers\Tenant\Purchasing\SupplierContractController;
use App\Http\Controllers\Tenant\SalesTargetController;
use App\Http\Controllers\Tenant\ReportsController;
use App\Http\Controllers\Tenant\SettingsController;
use App\Http\Controllers\Tenant\AnalyticsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Public routes
Route::get('/', function () {
    return view('welcome-maxcon');
})->name('home');

// Temporary route for testing design (remove in production)
Route::get('/demo-maxcon', function () {
    return view('admin.tenants.maxcon-index');
})->name('demo.maxcon');

// Test route for system guide (remove in production)
Route::get('/test-system-guide-direct', function () {
    $modules = [
        'sales' => [
            'name' => 'إدارة المبيعات',
            'icon' => 'fas fa-shopping-bag',
            'description' => 'إدارة العملاء، الطلبات، الفواتير، والمرتجعات',
            'color' => '#10b981',
            'features' => ['إدارة العملاء', 'إنشاء الطلبات', 'إصدار الفواتير', 'معالجة المرتجعات', 'تتبع المدفوعات'],
            'video_duration' => '8:30',
            'difficulty' => 'مبتدئ'
        ],
        'inventory' => [
            'name' => 'إدارة المخزون',
            'icon' => 'fas fa-warehouse',
            'description' => 'تتبع المنتجات، المستودعات، وحركات المخزون',
            'color' => '#3b82f6',
            'features' => ['كتالوج المنتجات', 'إدارة المستودعات', 'تتبع حركات المخزون', 'الجرد الدوري', 'تنبيهات المخزون'],
            'video_duration' => '12:15',
            'difficulty' => 'متوسط'
        ],
        'targets' => [
            'name' => 'أهداف البيع',
            'icon' => 'fas fa-bullseye',
            'description' => 'تحديد ومتابعة أهداف المبيعات والأداء',
            'color' => '#f59e0b',
            'features' => ['تحديد الأهداف', 'تتبع التقدم', 'تقارير الأداء', 'الإشعارات التلقائية', 'لوحة تحكم الأهداف'],
            'video_duration' => '6:20',
            'difficulty' => 'مبتدئ'
        ],
        'accounting' => [
            'name' => 'النظام المحاسبي',
            'icon' => 'fas fa-calculator',
            'description' => 'إدارة الحسابات، القيود، والتقارير المالية',
            'color' => '#ef4444',
            'features' => ['دليل الحسابات', 'القيود المحاسبية', 'التقارير المالية', 'مراكز التكلفة', 'الميزانيات'],
            'video_duration' => '15:30',
            'difficulty' => 'متقدم'
        ]
    ];

    $quickStats = [
        'total_modules' => count($modules),
        'video_tutorials' => 24,
        'faq_items' => 45,
        'user_manual_pages' => 120
    ];

    return view('tenant.system-guide.index', compact('modules', 'quickStats'));
});

// Test route for system guide with sidebar (remove in production)
Route::get('/test-system-guide-with-sidebar', function () {
    return view('tenant.roles.index', [
        'tenantUsers' => collect([]),
        'roles' => collect([]),
        'permissions' => collect([])
    ]);
});

// Test route for roles page with system guide (remove in production)
Route::get('/test-roles-with-system-guide', function () {
    // Get or create a test user
    $user = \App\Models\User::first();
    if (!$user) {
        $user = \App\Models\User::create([
            'name' => 'Test Admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'tenant_id' => 1,
            'is_active' => true
        ]);
    }

    // Login the user
    auth()->login($user);

    // Redirect to roles page
    return redirect()->route('tenant.roles.index');
});

// Test route for roles page with system guide (remove in production)
Route::get('/test-roles-with-system-guide', function () {
    return view('tenant.roles.index', [
        'tenantUsers' => new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10),
        'roles' => collect([
            (object)[
                'id' => 1,
                'name' => 'مدير النظام',
                'description' => 'صلاحيات كاملة',
                'users' => collect([])
            ]
        ]),
        'permissions' => collect([
            (object)['name' => 'إدارة المستخدمين'],
            (object)['name' => 'إدارة الأدوار']
        ])
    ]);
});

// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

// Logout confirmation page
Route::middleware('auth')->get('/logout-confirm', function () {
    return view('auth.logout-confirm');
})->name('logout.confirm');

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/dashboard', function () {
        if (auth()->user()->isSuperAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        // Use modern dashboard for all users
        return view('modern-dashboard');
    })->name('dashboard');
});

// Admin routes (Super Admin only)
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.maxcon-dashboard');
    })->name('dashboard');

    // Tenant management
    Route::resource('tenants', TenantController::class);
    Route::get('/tenants-maxcon', function () {
        return view('admin.tenants.maxcon-index');
    })->name('tenants.maxcon');
    Route::post('/tenants/{tenant}/suspend', [TenantController::class, 'suspend'])->name('tenants.suspend');
    Route::post('/tenants/{tenant}/activate', [TenantController::class, 'activate'])->name('tenants.activate');

    // User management (عرض فقط للـ Super Admin)
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users-export', [UserController::class, 'export'])->name('users.export');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::post('/users/{user}/activate', [UserController::class, 'activate'])->name('users.activate');
    Route::post('/users/{user}/deactivate', [UserController::class, 'deactivate'])->name('users.deactivate');

    // Tenant export
    Route::get('/tenants-export', [TenantController::class, 'export'])->name('tenants.export');

    // License management
    Route::get('/licenses/expired', [LicenseController::class, 'expired'])->name('licenses.expired');
    Route::get('/licenses/expiring-soon', [LicenseController::class, 'expiringSoon'])->name('licenses.expiring-soon');
    Route::post('/licenses/{tenant}/extend', [LicenseController::class, 'extend'])->name('licenses.extend');
});

// Test routes without auth (temporary)
Route::prefix('test')->name('test.')->group(function () {
    Route::get('settings', [SettingsController::class, 'index'])->name('settings');
    Route::get('reports', [ReportsController::class, 'index'])->name('reports');
    Route::get('analytics', [AnalyticsController::class, 'index'])->name('analytics');
});

// Tenant-specific routes (للـ Tenant Admin)
Route::middleware(['auth'])->prefix('tenant')->name('tenant.')->group(function () {
    // Tenant dashboard
    Route::get('/dashboard', function () {
        return view('tenant.dashboard');
    })->name('dashboard');

    // User management (للـ Tenant Admin فقط)
    Route::get('/users', [UserController::class, 'tenantUsers'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'tenantStore'])->name('users.store');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::post('/users/{user}/activate', [UserController::class, 'activate'])->name('users.activate');
    Route::post('/users/{user}/deactivate', [UserController::class, 'deactivate'])->name('users.deactivate');
    Route::get('/users-export', [UserController::class, 'tenantExport'])->name('users.export');

    // Roles and Permissions Management
    Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
    Route::post('/roles', [RoleController::class, 'createRole'])->name('roles.create');
    Route::post('/users/{user}/assign-role', [RoleController::class, 'assignRole'])->name('users.assign-role');
    Route::post('/users/{user}/assign-permission', [RoleController::class, 'assignPermission'])->name('users.assign-permission');
    Route::delete('/users/{user}/roles/{role}', [RoleController::class, 'removeRole'])->name('users.remove-role');
    Route::get('/users/{user}/details', [RoleController::class, 'getUserDetails'])->name('users.details');

    // Purchasing Management Routes
    Route::prefix('purchasing')->name('purchasing.')->group(function () {
        // Suppliers
        Route::get('suppliers/export-template', [SupplierController::class, 'exportTemplate'])->name('suppliers.export-template');
        Route::post('suppliers/import', [SupplierController::class, 'import'])->name('suppliers.import');
        Route::resource('suppliers', SupplierController::class);

        // Purchase Requests
        Route::resource('purchase-requests', PurchaseRequestController::class);
        Route::post('purchase-requests/{purchaseRequest}/approve', [PurchaseRequestController::class, 'approve'])->name('purchase-requests.approve');
        Route::post('purchase-requests/{purchaseRequest}/reject', [PurchaseRequestController::class, 'reject'])->name('purchase-requests.reject');

        // Purchase Orders
        Route::resource('purchase-orders', PurchaseOrderController::class);

        // Quotations
        Route::resource('quotations', QuotationController::class);

        // Supplier Contracts
        Route::resource('contracts', SupplierContractController::class);
    });

    // Sales Management Routes
    Route::prefix('sales')->name('sales.')->group(function () {
        // Sales Orders
        Route::resource('orders', SalesOrderController::class);
        Route::patch('orders/{salesOrder}/status', [SalesOrderController::class, 'updateStatus'])->name('orders.update-status');

        // Customers
        Route::resource('customers', CustomerController::class);
        Route::get('customers-import', [CustomerController::class, 'import'])->name('customers.import');
        Route::post('customers-import', [CustomerController::class, 'processImport'])->name('customers.process-import');
        Route::get('customers-template', [CustomerController::class, 'downloadTemplate'])->name('customers.template');

        // Products
        Route::resource('products', ProductController::class);
        Route::get('products-import', [ProductController::class, 'import'])->name('products.import');
        Route::post('products-import', [ProductController::class, 'processImport'])->name('products.process-import');
        Route::get('products-template', [ProductController::class, 'downloadTemplate'])->name('products.template');

        // Invoices
        Route::resource('invoices', InvoiceController::class);
        Route::get('invoices/{invoice}/pdf', [InvoiceController::class, 'downloadPdf'])->name('invoices.pdf');
        Route::get('invoices/{invoice}/view-pdf', [InvoiceController::class, 'viewPdf'])->name('invoices.view-pdf');
        Route::get('invoices/{invoice}/qr-test', [InvoiceController::class, 'testQrCode'])->name('invoices.qr-test');

        // Returns
        Route::resource('returns', ReturnController::class);
        Route::post('returns/{return}/approve', [ReturnController::class, 'approve'])->name('returns.approve');
        Route::post('returns/{return}/complete', [ReturnController::class, 'complete'])->name('returns.complete');
        Route::post('returns/{return}/reject', [ReturnController::class, 'reject'])->name('returns.reject');

        Route::get('test-free-samples', function() { return view('test-free-samples'); })->name('test.free-samples');
        Route::get('test-products', function() {
            $user = auth()->user();
            $tenantId = $user ? $user->tenant_id : null;
            $products = \App\Models\Product::where('tenant_id', $tenantId)->get();
            return response()->json([
                'user_id' => $user ? $user->id : null,
                'tenant_id' => $tenantId,
                'products_count' => $products->count(),
                'products' => $products->map(function($p) {
                    return [
                        'id' => $p->id,
                        'name' => $p->name,
                        'tenant_id' => $p->tenant_id
                    ];
                })
            ]);
        })->name('test.products');
        Route::post('invoices/{invoice}/send-email', [InvoiceController::class, 'sendEmail'])->name('invoices.send-email');
        Route::post('invoices/{invoice}/send-whatsapp', [InvoiceController::class, 'sendWhatsApp'])->name('invoices.send-whatsapp');
        Route::patch('invoices/{invoice}/status', [InvoiceController::class, 'updateStatus'])->name('invoices.update-status');
        Route::post('orders/{salesOrder}/create-invoice', [InvoiceController::class, 'createFromOrder'])->name('orders.create-invoice');

        // Sales Targets
        Route::prefix('targets')->name('targets.')->group(function () {
            Route::get('/', [SalesTargetController::class, 'index'])->name('index');
            Route::get('create', [SalesTargetController::class, 'create'])->name('create');
            Route::post('/', [SalesTargetController::class, 'store'])->name('store');
            Route::get('{target}', [SalesTargetController::class, 'show'])->name('show');
            Route::get('{target}/edit', [SalesTargetController::class, 'edit'])->name('edit');
            Route::put('{target}', [SalesTargetController::class, 'update'])->name('update');
            Route::delete('{target}', [SalesTargetController::class, 'destroy'])->name('destroy');
            Route::post('{target}/progress', [SalesTargetController::class, 'updateProgress'])->name('update-progress');
            Route::get('dashboard/overview', [SalesTargetController::class, 'dashboard'])->name('dashboard');
            Route::get('reports/analytics', [SalesTargetController::class, 'reports'])->name('reports');
        });
    });

    // Inventory Management
    Route::prefix('inventory')->name('inventory.')->group(function () {
        // Warehouses
        Route::resource('warehouses', WarehouseController::class);
        Route::get('warehouses/{warehouse}/capacity-report', [WarehouseController::class, 'capacityReport'])->name('warehouses.capacity-report');
        Route::get('warehouses/{warehouse}/inventory-report', [WarehouseController::class, 'inventoryReport'])->name('warehouses.inventory-report');

        // Inventory Movements
        Route::resource('movements', InventoryMovementController::class);

        // Inventory Audits
        Route::resource('audits', InventoryAuditController::class);

        // Inventory Alerts
        Route::resource('alerts', InventoryAlertController::class);
        Route::post('alerts/{alert}/acknowledge', [InventoryAlertController::class, 'acknowledge'])->name('alerts.acknowledge');
        Route::post('alerts/{alert}/resolve', [InventoryAlertController::class, 'resolve'])->name('alerts.resolve');
        Route::post('alerts/{alert}/dismiss', [InventoryAlertController::class, 'dismiss'])->name('alerts.dismiss');

        // Inventory Reports
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/', [InventoryReportController::class, 'index'])->name('index');
            Route::get('stock-levels', [InventoryReportController::class, 'stockLevels'])->name('stock-levels');
            Route::get('movement-history', [InventoryReportController::class, 'movementHistory'])->name('movement-history');
            Route::get('low-stock', [InventoryReportController::class, 'lowStock'])->name('low-stock');
            Route::get('expiring-items', [InventoryReportController::class, 'expiringItems'])->name('expiring-items');
        });

        // Custom Reports
        Route::prefix('custom-reports')->name('custom-reports.')->group(function () {
            Route::get('/', [CustomReportController::class, 'index'])->name('index');
            Route::post('generate', [CustomReportController::class, 'generate'])->name('generate');
        });

        // Analytics Dashboard
        Route::get('analytics', [AnalyticsDashboardController::class, 'index'])->name('analytics.dashboard');

        // Product Categories
        Route::resource('categories', ProductCategoryController::class);

        // Products
        Route::resource('products', InventoryProductController::class);

        // QR Code Generator
        Route::prefix('qr')->name('qr.')->group(function () {
            Route::get('/', [ProductQRController::class, 'index'])->name('index');
            Route::post('/generate/all', [ProductQRController::class, 'generateAvailableProductsQR'])->name('generate.all');
            Route::post('/generate/category/{category}', [ProductQRController::class, 'generateCategoryProductsQR'])->name('generate.category');
            Route::post('/generate/invoice', [ProductQRController::class, 'generateInvoiceQR'])->name('generate.invoice');
        });

        // Invoice QR Example
        Route::get('/invoice-qr-example', function() {
            $productsCount = \App\Models\Product::where('tenant_id', auth()->user()->tenant_id)
                ->where(function($q) {
                    $q->where('status', 'active')->orWhere('is_active', true);
                })
                ->count();
            return view('tenant.invoices.qr-example', compact('productsCount'));
        })->name('invoice.qr.example');

        // QR Guide
        Route::get('/qr-guide', function() {
            return view('tenant.sales.invoices.qr-guide');
        })->name('qr.guide');

        // Accounting Module
        Route::prefix('accounting')->name('accounting.')->group(function () {
            // Chart of Accounts
            Route::resource('chart-of-accounts', \App\Http\Controllers\Tenant\Accounting\ChartOfAccountController::class);
            Route::get('chart-of-accounts/{chartOfAccount}/balance', [\App\Http\Controllers\Tenant\Accounting\ChartOfAccountController::class, 'getAccountBalance'])->name('chart-of-accounts.balance');
            Route::get('accounts-tree', [\App\Http\Controllers\Tenant\Accounting\ChartOfAccountController::class, 'getAccountsTree'])->name('accounts-tree');

            // Cost Centers
            Route::resource('cost-centers', \App\Http\Controllers\Tenant\Accounting\CostCenterController::class);
            Route::get('cost-centers/{costCenter}/budget-analysis', [\App\Http\Controllers\Tenant\Accounting\CostCenterController::class, 'getBudgetAnalysis'])->name('cost-centers.budget-analysis');
            Route::get('cost-centers-tree', [\App\Http\Controllers\Tenant\Accounting\CostCenterController::class, 'getCostCentersTree'])->name('cost-centers-tree');

            // Journal Entries
            Route::resource('journal-entries', \App\Http\Controllers\Tenant\Accounting\JournalEntryController::class);
            Route::post('journal-entries/{journalEntry}/submit', [\App\Http\Controllers\Tenant\Accounting\JournalEntryController::class, 'submit'])->name('journal-entries.submit');
            Route::post('journal-entries/{journalEntry}/approve', [\App\Http\Controllers\Tenant\Accounting\JournalEntryController::class, 'approve'])->name('journal-entries.approve');
            Route::post('journal-entries/{journalEntry}/reject', [\App\Http\Controllers\Tenant\Accounting\JournalEntryController::class, 'reject'])->name('journal-entries.reject');
            Route::post('journal-entries/{journalEntry}/post', [\App\Http\Controllers\Tenant\Accounting\JournalEntryController::class, 'post'])->name('journal-entries.post');
            Route::get('account-details', [\App\Http\Controllers\Tenant\Accounting\JournalEntryController::class, 'getAccountDetails'])->name('account-details');

            // Financial Reports
            Route::prefix('reports')->name('reports.')->group(function () {
                Route::get('/', [\App\Http\Controllers\Tenant\Accounting\FinancialReportController::class, 'index'])->name('index');
                Route::get('trial-balance', [\App\Http\Controllers\Tenant\Accounting\FinancialReportController::class, 'trialBalance'])->name('trial-balance');
                Route::get('income-statement', [\App\Http\Controllers\Tenant\Accounting\FinancialReportController::class, 'incomeStatement'])->name('income-statement');
                Route::get('balance-sheet', [\App\Http\Controllers\Tenant\Accounting\FinancialReportController::class, 'balanceSheet'])->name('balance-sheet');
                Route::get('cash-flow', [\App\Http\Controllers\Tenant\Accounting\FinancialReportController::class, 'cashFlow'])->name('cash-flow');
                Route::get('account-ledger', [\App\Http\Controllers\Tenant\Accounting\FinancialReportController::class, 'accountLedger'])->name('account-ledger');
            });
        });

        // Regulatory Affairs Module
        Route::prefix('regulatory')->name('regulatory.')->group(function () {
            require __DIR__ . '/tenant/regulatory.php';
        });

        // Main Inventory Management (must be last to avoid conflicts)
        Route::get('/', [InventoryController::class, 'index'])->name('index');
        Route::get('create', [InventoryController::class, 'create'])->name('create');
        Route::post('/', [InventoryController::class, 'store'])->name('store');
        Route::get('{inventory}', [InventoryController::class, 'show'])->name('show');
        Route::get('{inventory}/edit', [InventoryController::class, 'edit'])->name('edit');
        Route::put('{inventory}', [InventoryController::class, 'update'])->name('update');
        Route::delete('{inventory}', [InventoryController::class, 'destroy'])->name('destroy');
    });

    // Human Resources Module
    Route::prefix('hr')->name('hr.')->group(function () {
        require __DIR__ . '/tenant/hr.php';
    });

    // Analytics and AI Module
    Route::prefix('analytics')->name('analytics.')->group(function () {
        require __DIR__ . '/tenant/analytics.php';
    });

    // System Guide Module
    require __DIR__ . '/tenant/system-guide.php';

    // Dynamic Reports Module
    Route::prefix('reports')->name('reports.')->group(function () {
        // Main dashboard
        Route::get('/', [ReportsController::class, 'index'])->name('index');

        // Report execution and management
        Route::post('execute/{report}', [ReportsController::class, 'execute'])->name('execute');
        Route::get('generate/{reportType}', [ReportsController::class, 'generate'])->name('generate');
        Route::post('generate/{reportType}', [ReportsController::class, 'generate']);

        // Export functionality
        Route::post('export/{execution}', [ReportsController::class, 'export'])->name('export');
        Route::get('download/{execution}', [ReportsController::class, 'download'])->name('download');
        Route::post('email/{execution}', [ReportsController::class, 'sendEmail'])->name('email');
        Route::get('print/{execution}', [ReportsController::class, 'print'])->name('print');

        // Report builder and management
        Route::get('builder', [ReportsController::class, 'builder'])->name('builder');
        Route::post('store', [ReportsController::class, 'store'])->name('store');
        Route::get('history', [ReportsController::class, 'history'])->name('history');

        // API endpoints for dynamic data
        Route::get('api/executions', [ReportsController::class, 'history'])->name('api.executions');
    });

    // Settings Module
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [SettingsController::class, 'index'])->name('index');
        Route::get('{settingType}', [SettingsController::class, 'show'])->name('show');
        Route::put('{settingType}', [SettingsController::class, 'update'])->name('update');
    });

    // Analytics Module (Additional routes)
    Route::prefix('analytics')->name('analytics.')->group(function () {
        Route::get('/', [AnalyticsController::class, 'index'])->name('dashboard');
        Route::get('view/{analyticsType}', [AnalyticsController::class, 'show'])->name('view');
        Route::get('api/kpis', [AnalyticsController::class, 'getKPIs'])->name('api.kpis');
        Route::get('api/chart/{chartType}', [AnalyticsController::class, 'getChartData'])->name('api.chart');
    });
});
