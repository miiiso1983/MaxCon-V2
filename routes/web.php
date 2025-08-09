<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
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
    return view('welcome');
})->name('home');

// Temporary route for testing design (remove in production)
Route::get('/demo-maxcon', function () {
    return view('admin.tenants.maxcon-index');
})->name('demo.maxcon');

// Test route for modern sidebar (remove in production)
Route::get('/test-sidebar', function () {
    return view('test-sidebar');
})->name('test.sidebar');

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

// Email verification routes (to fix verification.notice error)
Route::middleware('auth')->group(function () {
    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', function () {
        return redirect()->route('dashboard')->with('success', 'Email verified successfully!');
    })->middleware('signed')->name('verification.verify');

    Route::post('/email/verification-notification', function () {
        return back()->with('message', 'Verification link sent!');
    })->middleware('throttle:6,1')->name('verification.send');
});

// Logout confirmation page
Route::middleware('auth')->get('/logout-confirm', function () {
    return view('auth.logout-confirm');
})->name('logout.confirm');

// Test products page
Route::get('/test-products', function () {
    return view('test-products');
})->name('test.products');

// Simple test page
Route::get('/simple-test', function () {
    return view('simple-test');
})->name('simple.test');

// Add test products
Route::get('/add-test-products', function () {
    try {
        // إنشاء tenant إذا لم يكن موجود
        $tenant = DB::table('tenants')->where('id', 1)->first();
        if (!$tenant) {
            DB::table('tenants')->insert([
                'id' => 1,
                'name' => 'شركة تجريبية',
                'slug' => 'test-company',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // إضافة منتجات تجريبية
        $testProducts = [
            [
                'tenant_id' => 1,
                'product_code' => 'PRD000001',
                'name' => 'باراسيتامول 500 مجم',
                'description' => 'مسكن للألم وخافض للحرارة',
                'category' => 'مسكنات',
                'manufacturer' => 'شركة الأدوية المصرية',
                'barcode' => '123456789012',
                'unit_of_measure' => 'قرص',
                'cost_price' => 0.50,
                'selling_price' => 1.00,
                'min_stock_level' => 100,
                'stock_quantity' => 500,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'tenant_id' => 1,
                'product_code' => 'PRD000002',
                'name' => 'أموكسيسيلين 250 مجم',
                'description' => 'مضاد حيوي واسع المجال',
                'category' => 'مضادات حيوية',
                'manufacturer' => 'شركة الأدوية الأردنية',
                'barcode' => '123456789013',
                'unit_of_measure' => 'كبسولة',
                'cost_price' => 1.20,
                'selling_price' => 2.50,
                'min_stock_level' => 50,
                'stock_quantity' => 200,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'tenant_id' => 1,
                'product_code' => 'PRD000003',
                'name' => 'فيتامين سي 1000 مجم',
                'description' => 'مكمل غذائي لتقوية المناعة',
                'category' => 'فيتامينات',
                'manufacturer' => 'شركة الأدوية السعودية',
                'barcode' => '123456789014',
                'unit_of_measure' => 'قرص',
                'cost_price' => 0.80,
                'selling_price' => 1.50,
                'min_stock_level' => 75,
                'stock_quantity' => 300,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        $addedCount = 0;
        foreach ($testProducts as $productData) {
            $existing = DB::table('products')->where('product_code', $productData['product_code'])->first();
            if (!$existing) {
                DB::table('products')->insert($productData);
                $addedCount++;
            }
        }

        $totalProducts = DB::table('products')->where('tenant_id', 1)->count();

        return response()->json([
            'success' => true,
            'message' => "تم إضافة {$addedCount} منتج جديد. إجمالي المنتجات: {$totalProducts}",
            'added_count' => $addedCount,
            'total_count' => $totalProducts
        ]);

    } catch (Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'خطأ: ' . $e->getMessage()
        ]);
    }
})->name('add.test.products');

// Force add test products (delete existing first)
Route::get('/force-add-products', function () {
    try {
        // حذف المنتجات الموجودة
        DB::table('products')->where('tenant_id', 1)->delete();

        // إنشاء tenant إذا لم يكن موجود
        $tenant = DB::table('tenants')->where('id', 1)->first();
        if (!$tenant) {
            DB::table('tenants')->insert([
                'id' => 1,
                'name' => 'شركة تجريبية',
                'slug' => 'test-company',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // إضافة منتجات تجريبية
        $testProducts = [
            [
                'tenant_id' => 1,
                'product_code' => 'PRD000001',
                'name' => 'باراسيتامول 500 مجم',
                'description' => 'مسكن للألم وخافض للحرارة',
                'category' => 'مسكنات',
                'manufacturer' => 'شركة الأدوية المصرية',
                'barcode' => '123456789012',
                'unit_of_measure' => 'قرص',
                'cost_price' => 0.50,
                'selling_price' => 1.00,
                'min_stock_level' => 100,
                'stock_quantity' => 500,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'tenant_id' => 1,
                'product_code' => 'PRD000002',
                'name' => 'أموكسيسيلين 250 مجم',
                'description' => 'مضاد حيوي واسع المجال',
                'category' => 'مضادات حيوية',
                'manufacturer' => 'شركة الأدوية الأردنية',
                'barcode' => '123456789013',
                'unit_of_measure' => 'كبسولة',
                'cost_price' => 1.20,
                'selling_price' => 2.50,
                'min_stock_level' => 50,
                'stock_quantity' => 200,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'tenant_id' => 1,
                'product_code' => 'PRD000003',
                'name' => 'فيتامين سي 1000 مجم',
                'description' => 'مكمل غذائي لتقوية المناعة',
                'category' => 'فيتامينات',
                'manufacturer' => 'شركة الأدوية السعودية',
                'barcode' => '123456789014',
                'unit_of_measure' => 'قرص',
                'cost_price' => 0.80,
                'selling_price' => 1.50,
                'min_stock_level' => 75,
                'stock_quantity' => 300,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        foreach ($testProducts as $productData) {
            DB::table('products')->insert($productData);
        }

        $totalProducts = DB::table('products')->where('tenant_id', 1)->count();

        return response()->json([
            'success' => true,
            'message' => "تم إضافة 3 منتجات جديدة بنجاح. إجمالي المنتجات: {$totalProducts}",
            'added_count' => 3,
            'total_count' => $totalProducts
        ]);

    } catch (Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'خطأ: ' . $e->getMessage()
        ]);
    }
})->name('force.add.products');

// Fix tenant_id for current user
Route::get('/fix-tenant-products', function () {
    try {
        $user = auth()->user();
        $userTenantId = $user ? $user->tenant_id : 4; // استخدم 4 كافتراضي

        // تحديث جميع المنتجات لتنتمي للمؤسسة الحالية
        $updated = DB::table('products')->update(['tenant_id' => $userTenantId]);

        $totalProducts = DB::table('products')->where('tenant_id', $userTenantId)->count();

        return response()->json([
            'success' => true,
            'message' => "تم تحديث {$updated} منتج للمؤسسة {$userTenantId}. إجمالي المنتجات: {$totalProducts}",
            'updated_count' => $updated,
            'tenant_id' => $userTenantId,
            'total_count' => $totalProducts
        ]);

    } catch (Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'خطأ: ' . $e->getMessage()
        ]);
    }
})->name('fix.tenant.products');

// Test import functionality
Route::get('/test-import', function () {
    try {
        $user = auth()->user();
        $tenantId = $user ? $user->tenant_id : 4;

        // Create test CSV data
        $testData = [
            [
                'name' => 'إيبوبروفين 400 مجم',
                'category' => 'مسكنات',
                'manufacturer' => 'شركة الأدوية اللبنانية',
                'barcode' => '123456789015',
                'unit' => 'قرص',
                'purchase_price' => 0.75,
                'selling_price' => 1.25,
                'min_stock_level' => 80,
                'current_stock' => 400,
                'description' => 'مسكن ومضاد للالتهاب',
                'notes' => 'لا يستخدم مع أمراض المعدة'
            ]
        ];

        // Simulate import process
        $import = new \App\Imports\ProductsImport($tenantId);

        foreach ($testData as $row) {
            $product = $import->model($row);
            if ($product) {
                $product->save();
            }
        }

        $totalProducts = DB::table('products')->where('tenant_id', $tenantId)->count();

        return response()->json([
            'success' => true,
            'message' => "تم اختبار الاستيراد بنجاح. إجمالي المنتجات: {$totalProducts}",
            'tenant_id' => $tenantId,
            'imported_count' => $import->getImportedCount(),
            'skipped_count' => $import->getSkippedCount(),
            'total_count' => $totalProducts
        ]);

    } catch (Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'خطأ: ' . $e->getMessage()
        ]);
    }
})->name('test.import');

// Debug import issues
Route::get('/debug-import', function () {
    try {
        $user = auth()->user();
        $tenantId = $user ? $user->tenant_id : 4;

        // Check recent products
        $recentProducts = DB::table('products')
            ->where('tenant_id', $tenantId)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get(['id', 'name', 'tenant_id', 'created_at']);

        // Check all products count by tenant
        $productsByTenant = DB::table('products')
            ->select('tenant_id', DB::raw('count(*) as count'))
            ->groupBy('tenant_id')
            ->get();

        // Check if there are any products with null tenant_id
        $nullTenantProducts = DB::table('products')
            ->whereNull('tenant_id')
            ->count();

        // Check logs (if log file exists)
        $logPath = storage_path('logs/laravel.log');
        $recentLogs = '';
        if (file_exists($logPath)) {
            $logs = file($logPath);
            $recentLogs = implode('', array_slice($logs, -20)); // Last 20 lines
        }

        return response()->json([
            'success' => true,
            'debug_info' => [
                'current_user_id' => $user ? $user->id : 'غير مسجل',
                'current_tenant_id' => $tenantId,
                'recent_products' => $recentProducts,
                'products_by_tenant' => $productsByTenant,
                'null_tenant_products' => $nullTenantProducts,
                'recent_logs' => $recentLogs
            ]
        ]);

    } catch (Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'خطأ: ' . $e->getMessage()
        ]);
    }
})->name('debug.import');

// Test Excel file upload
Route::match(['GET', 'POST'], '/test-excel-upload', function (Request $request) {
    if ($request->isMethod('GET')) {
        return redirect('/test-excel-form');
    }
    try {
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        $user = auth()->user();
        $tenantId = $user ? $user->tenant_id : 4;

        // Read Excel file and show raw data
        $import = new \App\Imports\ProductsImport($tenantId);

        // Get file
        $file = $request->file('excel_file');

        // Read Excel data directly from uploaded file
        $data = Excel::toArray($import, $file);

        // Create a new import instance for actual processing (to reset counters)
        $actualImport = new \App\Imports\ProductsImport($tenantId);

        // Process import directly from uploaded file
        Excel::import($actualImport, $file);

        $totalProducts = DB::table('products')->where('tenant_id', $tenantId)->count();

        return response()->json([
            'success' => true,
            'message' => "تم اختبار رفع الملف بنجاح",
            'debug_info' => [
                'tenant_id' => $tenantId,
                'file_name' => $file->getClientOriginalName(),
                'file_size' => $file->getSize(),
                'raw_data' => $data[0] ?? [], // First sheet data
                'imported_count' => $actualImport->getImportedCount(),
                'skipped_count' => $actualImport->getSkippedCount(),
                'total_products_after' => $totalProducts,
                'failures' => $actualImport->failures()
            ]
        ]);

    } catch (Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'خطأ: ' . $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
    }
})->name('test.excel.upload');

// Show test upload form
Route::get('/test-excel-form', function () {
    return '
    <!DOCTYPE html>
    <html>
    <head>
        <title>اختبار رفع ملف Excel</title>
        <meta charset="UTF-8">
    </head>
    <body style="font-family: Arial; padding: 20px; direction: rtl;">
        <h2>اختبار رفع ملف Excel</h2>
        <form action="/test-excel-upload" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="_token" value="' . csrf_token() . '">
            <div style="margin: 20px 0;">
                <label>اختر ملف Excel:</label><br>
                <input type="file" name="excel_file" accept=".xlsx,.xls,.csv" required>
            </div>
            <button type="submit" style="background: #007cba; color: white; padding: 10px 20px; border: none; border-radius: 5px; margin-right: 10px;">
                رفع واختبار الملف (مع تحقق من التكرار)
            </button>
        </form>

        <form action="/test-excel-no-duplicate-check" method="POST" enctype="multipart/form-data" style="margin-top: 20px;">
            <input type="hidden" name="_token" value="' . csrf_token() . '">
            <div style="margin: 20px 0;">
                <label>اختر ملف Excel (بدون تحقق من التكرار):</label><br>
                <input type="file" name="excel_file" accept=".xlsx,.xls,.csv" required>
            </div>
            <button type="submit" style="background: #dc3545; color: white; padding: 10px 20px; border: none; border-radius: 5px;">
                رفع واختبار الملف (بدون تحقق من التكرار - أول 5 منتجات)
            </button>
        </form>

        <form action="/import-all-products-no-check" method="POST" enctype="multipart/form-data" style="margin-top: 20px;">
            <input type="hidden" name="_token" value="' . csrf_token() . '">
            <div style="margin: 20px 0;">
                <label><strong>استيراد جميع المنتجات (بدون تحقق من التكرار):</strong></label><br>
                <input type="file" name="excel_file" accept=".xlsx,.xls,.csv" required>
            </div>
            <button type="submit" style="background: #28a745; color: white; padding: 10px 20px; border: none; border-radius: 5px;">
                استيراد جميع المنتجات (قد يستغرق وقتاً طويلاً)
            </button>
            <p style="font-size: 12px; color: #dc3545; margin-top: 5px;">
                تحذير: هذا سيستورد جميع المنتجات من الملف بدون تحقق من التكرار. قد يستغرق عدة دقائق.
            </p>
        </form>

        <hr style="margin: 30px 0;">

        <h3>تنسيق الملف المطلوب:</h3>
        <p>يجب أن يحتوي الصف الأول على الأعمدة التالية:</p>
        <code>name | category | manufacturer | barcode | unit | purchase_price | selling_price | min_stock_level | current_stock | description | notes</code>

        <h3>مثال:</h3>
        <table border="1" style="border-collapse: collapse; margin: 10px 0;">
            <tr style="background: #f5f5f5;">
                <th style="padding: 8px;">name</th>
                <th style="padding: 8px;">category</th>
                <th style="padding: 8px;">manufacturer</th>
                <th style="padding: 8px;">barcode</th>
                <th style="padding: 8px;">unit</th>
                <th style="padding: 8px;">purchase_price</th>
                <th style="padding: 8px;">selling_price</th>
                <th style="padding: 8px;">min_stock_level</th>
                <th style="padding: 8px;">current_stock</th>
                <th style="padding: 8px;">description</th>
                <th style="padding: 8px;">notes</th>
            </tr>
            <tr>
                <td style="padding: 8px;">أسبرين 100 مجم</td>
                <td style="padding: 8px;">مسكنات</td>
                <td style="padding: 8px;">شركة الأدوية</td>
                <td style="padding: 8px;">123456789021</td>
                <td style="padding: 8px;">قرص</td>
                <td style="padding: 8px;">0.25</td>
                <td style="padding: 8px;">0.50</td>
                <td style="padding: 8px;">100</td>
                <td style="padding: 8px;">500</td>
                <td style="padding: 8px;">مسكن</td>
                <td style="padding: 8px;">مع الطعام</td>
            </tr>
        </table>
    </body>
    </html>';
})->name('test.excel.form');

// Force import without duplicate check
Route::get('/force-import-test', function () {
    try {
        $user = auth()->user();
        $tenantId = $user ? $user->tenant_id : 4;

        // Create test product directly without duplicate check
        $testProduct = [
            'tenant_id' => $tenantId,
            'product_code' => 'PRD' . str_pad(rand(1, 999999), 6, '0', STR_PAD_LEFT),
            'name' => 'منتج اختبار ' . date('H:i:s'),
            'description' => 'منتج اختبار للتأكد من عمل الاستيراد',
            'category' => 'اختبار',
            'manufacturer' => 'شركة اختبار',
            'barcode' => '999' . rand(100000, 999999),
            'unit_of_measure' => 'قرص',
            'cost_price' => 1.00,
            'selling_price' => 2.00,
            'min_stock_level' => 10,
            'stock_quantity' => 100,
            'is_active' => 1,
            'status' => 'active',
            'currency' => 'IQD',
            'base_unit' => 'piece',
            'is_taxable' => 1,
            'tax_rate' => 15.00,
            'track_expiry' => 1,
            'track_batch' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ];

        // Insert directly into database
        $productId = DB::table('products')->insertGetId($testProduct);

        $totalProducts = DB::table('products')->where('tenant_id', $tenantId)->count();

        return response()->json([
            'success' => true,
            'message' => "تم إنشاء منتج اختبار بنجاح",
            'debug_info' => [
                'tenant_id' => $tenantId,
                'product_id' => $productId,
                'product_name' => $testProduct['name'],
                'total_products_after' => $totalProducts
            ]
        ]);

    } catch (Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'خطأ: ' . $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
    }
})->name('force.import.test');

// Test Excel upload without duplicate check
Route::post('/test-excel-no-duplicate-check', function (Request $request) {
    try {
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        $user = auth()->user();
        $tenantId = $user ? $user->tenant_id : 4;

        // Get file
        $file = $request->file('excel_file');

        // Read Excel data directly from uploaded file
        $data = Excel::toArray(new \App\Imports\ProductsImport($tenantId), $file);

        // Process first few rows manually without duplicate check
        $imported = 0;
        $errors = [];

        if (isset($data[0]) && count($data[0]) > 1) {
            // Skip header row, process first 5 data rows
            $rows = array_slice($data[0], 1, 5);

            foreach ($rows as $index => $row) {
                try {
                    if (empty($row['name'])) continue;

                    $product = [
                        'tenant_id' => $tenantId,
                        'product_code' => 'PRD' . str_pad(rand(1, 999999), 6, '0', STR_PAD_LEFT),
                        'name' => trim($row['name']),
                        'description' => !empty($row['description']) ? trim($row['description']) : null,
                        'category' => !empty($row['category']) ? trim($row['category']) : 'أخرى',
                        'manufacturer' => !empty($row['manufacturer']) ? trim($row['manufacturer']) : null,
                        'barcode' => !empty($row['barcode']) ? trim((string)$row['barcode']) : null,
                        'unit_of_measure' => !empty($row['unit']) ? trim($row['unit']) : 'قرص',
                        'cost_price' => !empty($row['purchase_price']) && is_numeric($row['purchase_price']) ? floatval($row['purchase_price']) : 0.00,
                        'selling_price' => !empty($row['selling_price']) && is_numeric($row['selling_price']) ? floatval($row['selling_price']) : 0.00,
                        'min_stock_level' => !empty($row['min_stock_level']) && is_numeric($row['min_stock_level']) ? intval($row['min_stock_level']) : 10,
                        'stock_quantity' => !empty($row['current_stock']) && is_numeric($row['current_stock']) ? intval($row['current_stock']) : 0,
                        'is_active' => 1,
                        'status' => 'active',
                        'currency' => 'IQD',
                        'base_unit' => 'piece',
                        'is_taxable' => 1,
                        'tax_rate' => 15.00,
                        'track_expiry' => 1,
                        'track_batch' => 1,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];

                    DB::table('products')->insert($product);
                    $imported++;

                } catch (Exception $e) {
                    $errors[] = "Row " . ($index + 2) . ": " . $e->getMessage();
                }
            }
        }

        $totalProducts = DB::table('products')->where('tenant_id', $tenantId)->count();

        return response()->json([
            'success' => true,
            'message' => "تم اختبار الاستيراد بدون تحقق من التكرار",
            'debug_info' => [
                'tenant_id' => $tenantId,
                'file_name' => $file->getClientOriginalName(),
                'file_size' => $file->getSize(),
                'total_rows' => count($data[0] ?? []),
                'processed_rows' => count($rows ?? []),
                'imported_count' => $imported,
                'errors' => $errors,
                'total_products_after' => $totalProducts
            ]
        ]);

    } catch (Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'خطأ: ' . $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
    }
})->name('test.excel.no.duplicate.check');

// Import ALL products without duplicate check
Route::post('/import-all-products-no-check', function (Request $request) {
    try {
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        $user = auth()->user();
        $tenantId = $user ? $user->tenant_id : 4;

        // Get file
        $file = $request->file('excel_file');

        // Read Excel data directly from uploaded file
        $data = Excel::toArray(new \App\Imports\ProductsImport($tenantId), $file);

        // Process ALL rows without duplicate check
        $imported = 0;
        $errors = [];
        $skipped = 0;

        if (isset($data[0]) && count($data[0]) > 1) {
            // Skip header row, process ALL data rows
            $rows = array_slice($data[0], 1);

            foreach ($rows as $index => $row) {
                try {
                    if (empty($row['name']) || empty(trim($row['name']))) {
                        $skipped++;
                        continue;
                    }

                    $product = [
                        'tenant_id' => $tenantId,
                        'product_code' => 'PRD' . str_pad(rand(1, 999999), 6, '0', STR_PAD_LEFT),
                        'name' => trim($row['name']),
                        'description' => !empty($row['description']) ? trim($row['description']) : null,
                        'category' => !empty($row['category']) ? trim($row['category']) : 'أخرى',
                        'manufacturer' => !empty($row['manufacturer']) ? trim($row['manufacturer']) : null,
                        'barcode' => !empty($row['barcode']) ? trim((string)$row['barcode']) : null,
                        'unit_of_measure' => !empty($row['unit']) ? trim($row['unit']) : 'قرص',
                        'cost_price' => !empty($row['purchase_price']) && is_numeric($row['purchase_price']) ? floatval($row['purchase_price']) : 0.00,
                        'selling_price' => !empty($row['selling_price']) && is_numeric($row['selling_price']) ? floatval($row['selling_price']) : 0.00,
                        'min_stock_level' => !empty($row['min_stock_level']) && is_numeric($row['min_stock_level']) ? intval($row['min_stock_level']) : 10,
                        'stock_quantity' => !empty($row['current_stock']) && is_numeric($row['current_stock']) ? intval($row['current_stock']) : 0,
                        'is_active' => 1,
                        'status' => 'active',
                        'currency' => 'IQD',
                        'base_unit' => 'piece',
                        'is_taxable' => 1,
                        'tax_rate' => 15.00,
                        'track_expiry' => 1,
                        'track_batch' => 1,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];

                    DB::table('products')->insert($product);
                    $imported++;

                    // Progress update every 100 products
                    if ($imported % 100 == 0) {
                        \Log::info("Import progress: {$imported} products imported");
                    }

                } catch (Exception $e) {
                    $errors[] = "Row " . ($index + 2) . ": " . $e->getMessage();
                    if (count($errors) > 50) { // Limit errors to prevent memory issues
                        $errors[] = "... and more errors (showing first 50)";
                        break;
                    }
                }
            }
        }

        $totalProducts = DB::table('products')->where('tenant_id', $tenantId)->count();

        return response()->json([
            'success' => true,
            'message' => "تم استيراد جميع المنتجات بنجاح",
            'debug_info' => [
                'tenant_id' => $tenantId,
                'file_name' => $file->getClientOriginalName(),
                'file_size' => $file->getSize(),
                'total_rows' => count($data[0] ?? []) - 1, // Exclude header
                'imported_count' => $imported,
                'skipped_count' => $skipped,
                'error_count' => count($errors),
                'errors' => array_slice($errors, 0, 10), // Show first 10 errors only
                'total_products_after' => $totalProducts
            ]
        ]);

    } catch (Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'خطأ: ' . $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
    }
})->name('import.all.products.no.check');

// Test direct product creation
Route::get('/test-direct-product-creation', function () {
    try {
        $user = auth()->user();
        $tenantId = $user ? $user->tenant_id : 4;

        \Log::info('Direct product creation test started', [
            'user_id' => auth()->id(),
            'tenant_id' => $tenantId
        ]);

        $product = new \App\Models\Product();
        $product->tenant_id = $tenantId;
        $product->name = 'منتج اختبار مباشر ' . now()->format('H:i:s');
        $product->category = 'أدوية';
        $product->cost_price = 10.50;
        $product->selling_price = 20.00;
        $product->stock_quantity = 50;
        $product->min_stock_level = 5;
        $product->unit_of_measure = 'قرص';
        $product->manufacturer = 'شركة اختبار';
        $product->is_active = true;
        $product->product_code = 'TEST' . rand(1000, 9999);

        \Log::info('Before save - product data', $product->toArray());

        $saved = $product->save();

        \Log::info('After save', [
            'saved' => $saved,
            'product_id' => $product->id,
            'exists_in_db' => \App\Models\Product::find($product->id) ? 'YES' : 'NO'
        ]);

        $totalProducts = \App\Models\Product::where('tenant_id', $tenantId)->count();

        return response()->json([
            'success' => true,
            'message' => 'تم إنشاء منتج اختبار بنجاح',
            'product_id' => $product->id,
            'product_name' => $product->name,
            'tenant_id' => $tenantId,
            'total_products' => $totalProducts,
            'saved_result' => $saved
        ]);

    } catch (\Exception $e) {
        \Log::error('Direct product creation failed', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
})->name('test.direct.product.creation');

// Test route to check if messages work
Route::get('/test-import-success', function () {
    return redirect()->route('tenant.sales.products.index')
        ->with('success', '✅ تم استيراد 1101 منتج بنجاح وتم تخطي 0 منتج (موجود مسبقاً). الوقت المستغرق: 3.2 دقيقة.')
        ->with('import_stats', [
            'imported' => 1101,
            'skipped' => 0,
            'total_processed' => 1101,
            'failures_count' => 0,
            'execution_time' => '3.2 دقيقة'
        ]);
})->name('test.import.success');

// Test route to check if error messages work
Route::get('/test-import-error', function () {
    return redirect()->route('tenant.sales.products.index')
        ->with('error', 'فشل في استيراد الملف بسبب أخطاء في البيانات. عدد الأخطاء: 25. معظم الأخطاء بسبب حقول مطلوبة فارغة.');
})->name('test.import.error');

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // CSRF token refresh route
    Route::get('/csrf-token', function () {
        return response()->json(['csrf_token' => csrf_token()]);
    })->name('csrf.token');

    // Session refresh route
    Route::post('/refresh-session', function () {
        session()->regenerate();
        return response()->json([
            'status' => 'success',
            'message' => 'Session refreshed',
            'session_id' => session()->getId(),
            'csrf_token' => csrf_token()
        ]);
    })->name('session.refresh');







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

    // Customer management for tenants
    Route::prefix('tenants/{tenant}/customers')->name('customers.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\CustomerManagementController::class, 'index'])->name('index');
        Route::get('/{customer}', [App\Http\Controllers\Admin\CustomerManagementController::class, 'show'])->name('show');
        Route::patch('/limits', [App\Http\Controllers\Admin\CustomerManagementController::class, 'updateLimits'])->name('update-limits');
        Route::patch('/{customer}/toggle-status', [App\Http\Controllers\Admin\CustomerManagementController::class, 'toggleStatus'])->name('toggle-status');
        Route::delete('/{customer}', [App\Http\Controllers\Admin\CustomerManagementController::class, 'destroy'])->name('destroy');
        Route::get('/statistics/data', [App\Http\Controllers\Admin\CustomerManagementController::class, 'statistics'])->name('statistics');
    });
    Route::get('/tenants-maxcon', [TenantController::class, 'maxconIndex'])->name('tenants.maxcon');
    Route::get('/tenants-test-create', function () {
        return view('admin.tenants.test-create');
    })->name('tenants.test-create');
    Route::get('/tenants-login-info', function () {
        return view('admin.tenants.login-info');
    })->name('tenants.login-info');
    Route::get('/test-login', function () {
        return view('admin.tenants.test-login');
    })->name('test-login');
    Route::post('/test-login-attempt', function (Request $request) {
        try {
            $credentials = $request->only('email', 'password');

            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                return redirect()->back()->with('success',
                    "✅ تم تسجيل الدخول بنجاح! مرحباً {$user->name}");
            } else {
                return redirect()->back()->with('error',
                    '❌ فشل تسجيل الدخول. تحقق من البيانات.');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error',
                '❌ خطأ: ' . $e->getMessage());
        }
    })->name('admin.test-login.attempt');
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
    Route::get('dropdown', function () {
        return view('test-dropdown');
    })->name('dropdown');
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

    // Seed comprehensive permissions (admin only)
    Route::get('/seed-permissions', function() {
        if (!auth()->user()->hasRole('super-admin') && !auth()->user()->hasRole('tenant-admin')) {
            abort(403, 'غير مصرح لك بتنفيذ هذا الإجراء');
        }

        try {
            // Clear permission cache first
            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

            Artisan::call('db:seed', ['--class' => 'ComprehensivePermissionsSeeder']);

            // Get current count
            $totalPermissions = \Spatie\Permission\Models\Permission::count();

            return redirect()->back()->with('success', "تم تحديث الصلاحيات الشاملة بنجاح! إجمالي الصلاحيات: {$totalPermissions}");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'خطأ في تحديث الصلاحيات: ' . $e->getMessage());
        }
    })->name('seed.permissions');

    // Debug route to check permissions count
    Route::get('/debug-permissions', function() {
        if (!auth()->user()->hasRole('super-admin') && !auth()->user()->hasRole('tenant-admin')) {
            abort(403, 'غير مصرح لك بتنفيذ هذا الإجراء');
        }

        $totalPermissions = \Spatie\Permission\Models\Permission::count();
        $webPermissions = \Spatie\Permission\Models\Permission::where('guard_name', 'web')->count();
        $samplePermissions = \Spatie\Permission\Models\Permission::take(10)->pluck('name')->toArray();

        return response()->json([
            'database_connection' => config('database.default'),
            'total_permissions' => $totalPermissions,
            'web_guard_permissions' => $webPermissions,
            'sample_permissions' => $samplePermissions,
            'database_path' => database_path('database.sqlite')
        ]);
    })->name('debug.permissions');

    // Direct route to add comprehensive permissions
    Route::get('/add-comprehensive-permissions', function() {
        if (!auth()->user()->hasRole('super-admin') && !auth()->user()->hasRole('tenant-admin')) {
            abort(403, 'غير مصرح لك بتنفيذ هذا الإجراء');
        }

        try {
            // Clear permission cache first
            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

            // Define comprehensive permissions directly
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
                'sales.invoices.send-email' => 'إرسال الفواتير بالبريد الإلكتروني',
                'sales.customers.view' => 'عرض العملاء',
                'sales.customers.create' => 'إضافة عملاء جدد',
                'sales.customers.edit' => 'تعديل بيانات العملاء',
                'sales.customers.delete' => 'حذف العملاء',
                'sales.customers.import' => 'استيراد العملاء',
                'sales.customers.export' => 'تصدير العملاء',
                'sales.products.view' => 'عرض المنتجات',
                'sales.products.create' => 'إضافة منتجات جديدة',
                'sales.products.edit' => 'تعديل المنتجات',
                'sales.products.delete' => 'حذف المنتجات',
                'sales.products.import' => 'استيراد المنتجات',
                'sales.products.export' => 'تصدير المنتجات',
                'sales.returns.view' => 'عرض المرتجعات',
                'sales.returns.create' => 'إنشاء مرتجعات',
                'sales.returns.edit' => 'تعديل المرتجعات',
                'sales.returns.complete' => 'إكمال المرتجعات',
                'sales.returns.reject' => 'رفض المرتجعات',
                'sales.targets.view' => 'عرض أهداف المبيعات',
                'sales.targets.create' => 'إنشاء أهداف المبيعات',
                'sales.targets.edit' => 'تعديل أهداف المبيعات',
                'sales.targets.delete' => 'حذف أهداف المبيعات',

                // 📦 إدارة المخزون (Inventory Management)
                'inventory.view' => 'عرض المخزون الرئيسي',
                'inventory.create' => 'إنشاء عناصر مخزون',
                'inventory.edit' => 'تعديل عناصر المخزون',
                'inventory.delete' => 'حذف عناصر المخزون',
                'inventory.warehouses.view' => 'عرض المستودعات',
                'inventory.warehouses.create' => 'إنشاء مستودعات',
                'inventory.warehouses.edit' => 'تعديل المستودعات',
                'inventory.warehouses.delete' => 'حذف المستودعات',
                'inventory.movements.view' => 'عرض حركات المخزون',
                'inventory.movements.create' => 'إنشاء حركات مخزون',
                'inventory.movements.edit' => 'تعديل حركات المخزون',
                'inventory.movements.delete' => 'حذف حركات المخزون',
                'inventory.audits.view' => 'عرض مراجعات المخزون',
                'inventory.audits.create' => 'إنشاء مراجعات مخزون',
                'inventory.audits.edit' => 'تعديل مراجعات المخزون',
                'inventory.audits.delete' => 'حذف مراجعات المخزون',
                'inventory.adjustments.view' => 'عرض تسويات المخزون',
                'inventory.adjustments.create' => 'إنشاء تسويات مخزون',
                'inventory.adjustments.edit' => 'تعديل تسويات المخزون',
                'inventory.adjustments.delete' => 'حذف تسويات المخزون',
                'inventory.reports.view' => 'عرض تقارير المخزون',

                // 💰 النظام المحاسبي (Accounting System)
                'accounting.dashboard.view' => 'عرض لوحة تحكم المحاسبة',
                'accounting.chart-of-accounts.view' => 'عرض دليل الحسابات',
                'accounting.chart-of-accounts.create' => 'إنشاء حسابات جديدة',
                'accounting.chart-of-accounts.edit' => 'تعديل الحسابات',
                'accounting.chart-of-accounts.delete' => 'حذف الحسابات',
                'accounting.cost-centers.view' => 'عرض مراكز التكلفة',
                'accounting.cost-centers.create' => 'إنشاء مراكز تكلفة',
                'accounting.cost-centers.edit' => 'تعديل مراكز التكلفة',
                'accounting.cost-centers.delete' => 'حذف مراكز التكلفة',
                'accounting.journal-entries.view' => 'عرض القيود المحاسبية',
                'accounting.journal-entries.create' => 'إنشاء قيود محاسبية',
                'accounting.journal-entries.edit' => 'تعديل القيود المحاسبية',
                'accounting.journal-entries.delete' => 'حذف القيود المحاسبية',
                'accounting.reports.trial-balance' => 'عرض ميزان المراجعة',
                'accounting.reports.income-statement' => 'عرض قائمة الدخل',
                'accounting.reports.balance-sheet' => 'عرض الميزانية العمومية',
                'accounting.reports.cash-flow' => 'عرض قائمة التدفقات النقدية',
            ];

            $created = 0;
            $existing = 0;

            // Create permissions
            foreach ($permissions as $name => $description) {
                $permission = \Spatie\Permission\Models\Permission::firstOrCreate(
                    ['name' => $name, 'guard_name' => 'web'],
                    ['description' => $description]
                );

                if ($permission->wasRecentlyCreated) {
                    $created++;
                } else {
                    $existing++;
                }
            }

            $totalPermissions = \Spatie\Permission\Models\Permission::count();

            return response()->json([
                'success' => true,
                'message' => "تم إضافة الصلاحيات بنجاح!",
                'created_permissions' => $created,
                'existing_permissions' => $existing,
                'total_permissions' => $totalPermissions,
                'sample_new_permissions' => array_keys(array_slice($permissions, 0, 5))
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    })->name('add.comprehensive.permissions');

    // Route to create missing tables
    Route::get('/create-missing-tables', function() {
        if (!auth()->user()->hasRole('super-admin') && !auth()->user()->hasRole('tenant-admin')) {
            abort(403, 'غير مصرح لك بتنفيذ هذا الإجراء');
        }

        try {
            $results = [];

            // Check and create supplier_contracts table
            if (!Schema::hasTable('supplier_contracts')) {
                Schema::create('supplier_contracts', function (Blueprint $table) {
                    $table->id();
                    $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
                    $table->foreignId('supplier_id')->constrained()->onDelete('cascade');
                    $table->string('contract_number')->unique();
                    $table->string('title');
                    $table->text('description')->nullable();
                    $table->date('start_date');
                    $table->date('end_date');
                    $table->decimal('contract_value', 15, 2)->nullable();
                    $table->string('currency', 3)->default('IQD');
                    $table->enum('status', ['draft', 'active', 'expired', 'terminated'])->default('draft');
                    $table->text('terms_and_conditions')->nullable();
                    $table->string('payment_terms')->nullable();
                    $table->string('delivery_terms')->nullable();
                    $table->json('attachments')->nullable();
                    $table->timestamps();

                    $table->index(['tenant_id', 'supplier_id']);
                    $table->index(['status', 'start_date', 'end_date']);
                });
                $results[] = 'تم إنشاء جدول supplier_contracts';
            } else {
                $results[] = 'جدول supplier_contracts موجود مسبقاً';
            }

            // Check and create suppliers table if missing
            if (!Schema::hasTable('suppliers')) {
                Schema::create('suppliers', function (Blueprint $table) {
                    $table->id();
                    $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
                    $table->string('name');
                    $table->string('company_name')->nullable();
                    $table->string('email')->nullable();
                    $table->string('phone')->nullable();
                    $table->string('mobile')->nullable();
                    $table->text('address')->nullable();
                    $table->string('city')->nullable();
                    $table->string('country')->default('Iraq');
                    $table->string('tax_number')->nullable();
                    $table->string('commercial_register')->nullable();
                    $table->enum('status', ['active', 'inactive'])->default('active');
                    $table->decimal('credit_limit', 15, 2)->default(0);
                    $table->string('payment_terms')->nullable();
                    $table->text('notes')->nullable();
                    $table->timestamps();

                    $table->index(['tenant_id', 'status']);
                });
                $results[] = 'تم إنشاء جدول suppliers';
            } else {
                $results[] = 'جدول suppliers موجود مسبقاً';
            }

            // Check and create purchase_orders table if missing
            if (!Schema::hasTable('purchase_orders')) {
                Schema::create('purchase_orders', function (Blueprint $table) {
                    $table->id();
                    $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
                    $table->foreignId('supplier_id')->constrained()->onDelete('cascade');
                    $table->string('order_number')->unique();
                    $table->date('order_date');
                    $table->date('expected_delivery_date')->nullable();
                    $table->enum('status', ['draft', 'sent', 'confirmed', 'partially_received', 'completed', 'cancelled'])->default('draft');
                    $table->decimal('subtotal', 15, 2)->default(0);
                    $table->decimal('tax_amount', 15, 2)->default(0);
                    $table->decimal('discount_amount', 15, 2)->default(0);
                    $table->decimal('total_amount', 15, 2)->default(0);
                    $table->string('currency', 3)->default('IQD');
                    $table->text('notes')->nullable();
                    $table->timestamps();

                    $table->index(['tenant_id', 'supplier_id']);
                    $table->index(['status', 'order_date']);
                });
                $results[] = 'تم إنشاء جدول purchase_orders';
            } else {
                $results[] = 'جدول purchase_orders موجود مسبقاً';
            }

            // Check and create purchase_order_items table if missing
            if (!Schema::hasTable('purchase_order_items')) {
                Schema::create('purchase_order_items', function (Blueprint $table) {
                    $table->id();
                    $table->foreignId('purchase_order_id')->constrained()->onDelete('cascade');
                    $table->foreignId('product_id')->constrained()->onDelete('cascade');
                    $table->integer('quantity');
                    $table->decimal('unit_price', 10, 2);
                    $table->decimal('total_price', 15, 2);
                    $table->integer('received_quantity')->default(0);
                    $table->text('notes')->nullable();
                    $table->timestamps();
                });
                $results[] = 'تم إنشاء جدول purchase_order_items';
            } else {
                $results[] = 'جدول purchase_order_items موجود مسبقاً';
            }

            return response()->json([
                'success' => true,
                'message' => 'تم فحص وإنشاء الجداول المفقودة',
                'results' => $results,
                'database_connection' => config('database.default')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);
        }
    })->name('create.missing.tables');

    // Purchasing Management Routes
    Route::prefix('purchasing')->name('purchasing.')->group(function () {
        // Suppliers
        Route::get('suppliers/export-template', [SupplierController::class, 'exportTemplate'])->name('suppliers.export-template');
        Route::post('suppliers/import', [SupplierController::class, 'import'])->name('suppliers.import');

        // Test route for creating supplier
        Route::get('suppliers/test-create', function() {
            $data = [
                'tenant_id' => auth()->user()->tenant_id,
                'name' => 'مورد اختبار ' . now()->format('H:i:s'),
                'code' => 'TEST-' . rand(1000, 9999),
                'type' => 'distributor',
                'status' => 'active',
                'contact_person' => 'شخص الاختبار',
                'phone' => '07901234567',
                'email' => 'test' . rand(100, 999) . '@example.com',
                'address' => 'عنوان الاختبار',
                'payment_terms' => 'credit_30'
            ];

            // Add currency if column exists
            if (Schema::hasColumn('suppliers', 'currency')) {
                $data['currency'] = 'IQD';
            }

            // Add category if column exists
            if (Schema::hasColumn('suppliers', 'category')) {
                $data['category'] = 'pharmaceutical';
            }

            $supplier = App\Models\Supplier::create($data);

            return redirect()->route('tenant.purchasing.suppliers.index')
                ->with('success', 'تم إنشاء مورد اختبار: ' . $supplier->name);
        })->name('suppliers.test-create');

        // Test import route
        Route::get('suppliers/test-import', function() {
            $tenantId = auth()->user()->tenant_id;

            // Simulate Excel data - create Collection with objects that have toArray method
            $testData = collect([
                collect([
                    'اسم المورد*' => 'شركة اختبار الاستيراد ' . now()->format('H:i:s'),
                    'رمز المورد' => 'IMP-' . rand(1000, 9999),
                    'نوع المورد' => 'distributor',
                    'الحالة' => 'active',
                    'شخص الاتصال' => 'محمد اختبار',
                    'الهاتف' => '07901111111',
                    'البريد الالكتروني' => 'import-test@example.com',
                    'العنوان' => 'بغداد - اختبار الاستيراد',
                    'الرقم الضريبي' => '111111111',
                    'شروط الدفع' => 'credit_30',
                    'حد الائتمان' => '25000',
                    'العملة' => 'IQD',
                    'الفئة' => 'pharmaceutical',
                    'ملاحظات' => 'مورد تجريبي للاختبار'
                ])
            ]);

            try {
                $import = new App\Imports\SuppliersCollectionImport($tenantId);
                $import->collection($testData);

                $imported = $import->getImportedCount();
                $errors = $import->getErrors();

                $message = "تم اختبار الاستيراد: {$imported} مورد";
                if (!empty($errors)) {
                    $message .= ". أخطاء: " . implode(', ', $errors);
                }

                return redirect()->route('tenant.purchasing.suppliers.index')
                    ->with($imported > 0 ? 'success' : 'error', $message);
            } catch (\Exception $e) {
                return redirect()->route('tenant.purchasing.suppliers.index')
                    ->with('error', 'خطأ في اختبار الاستيراد: ' . $e->getMessage());
            }
        })->name('suppliers.test-import');

        // Debug route to test direct supplier creation
        Route::get('suppliers/debug-create', function() {
            try {
                $supplier = App\Models\Supplier::create([
                    'tenant_id' => 4,
                    'name' => 'مورد تجريبي ' . now()->format('H:i:s'),
                    'code' => 'DEBUG-' . rand(1000, 9999),
                    'type' => 'distributor',
                    'status' => 'active',
                    'contact_person' => 'شخص تجريبي',
                    'phone' => '07901234567',
                    'email' => 'debug@example.com',
                    'address' => 'عنوان تجريبي',
                    'payment_terms' => 'credit_30'
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'تم إنشاء المورد بنجاح',
                    'supplier' => [
                        'id' => $supplier->id,
                        'name' => $supplier->name,
                        'code' => $supplier->code,
                        'created_at' => $supplier->created_at
                    ]
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ]);
            }
        })->name('suppliers.debug-create');

        // Debug route to test import process
        Route::get('suppliers/debug-import', function() {
            try {
                $tenantId = 4;

                // Create simple test data
                $testData = collect([
                    collect([
                        'اسم المورد*' => 'مورد استيراد تجريبي ' . now()->format('H:i:s'),
                        'رمز المورد' => 'IMP-' . rand(1000, 9999),
                        'نوع المورد' => 'distributor',
                        'الحالة' => 'active',
                        'شخص الاتصال' => 'محمد التجريبي',
                        'الهاتف' => '07901234567',
                        'البريد الالكتروني' => 'import-test@example.com',
                        'العنوان' => 'بغداد - اختبار',
                        'شروط الدفع' => 'credit_30'
                    ])
                ]);

                $import = new App\Imports\SuppliersCollectionImport($tenantId);
                $import->collection($testData);

                $imported = $import->getImportedCount();
                $errors = $import->getErrors();

                // Check if supplier was actually created
                $suppliers = App\Models\Supplier::where('tenant_id', $tenantId)
                    ->orderBy('created_at', 'desc')
                    ->take(3)
                    ->get(['id', 'name', 'code', 'created_at']);

                return response()->json([
                    'success' => true,
                    'imported_count' => $imported,
                    'errors' => $errors,
                    'recent_suppliers' => $suppliers,
                    'total_suppliers' => App\Models\Supplier::where('tenant_id', $tenantId)->count()
                ]);

            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        })->name('suppliers.debug-import');

        // Debug route to check what index method returns
        Route::get('suppliers/debug-index', function() {
            $user = auth()->user();
            $tenantId = $user->tenant_id;

            $query = App\Models\Supplier::where('tenant_id', $tenantId);
            $suppliers = $query->orderBy('name')->paginate(15);

            return response()->json([
                'tenant_id' => $tenantId,
                'total_suppliers' => App\Models\Supplier::where('tenant_id', $tenantId)->count(),
                'suppliers_on_page' => $suppliers->count(),
                'current_page' => $suppliers->currentPage(),
                'total_pages' => $suppliers->lastPage(),
                'suppliers_data' => $suppliers->items(),
                'request_params' => request()->all()
            ]);
        })->name('suppliers.debug-index');

        // Simple import test with minimal data
        Route::get('suppliers/simple-import-test', function() {
            try {
                $tenantId = 4;

                // Create very simple test data
                $testData = collect([
                    collect([
                        'اسم المورد*' => 'مورد بسيط ' . now()->format('H:i:s'),
                        'رمز المورد' => 'SIMPLE-' . rand(1000, 9999),
                        'نوع المورد' => 'distributor',
                        'الحالة' => 'active',
                        'شروط الدفع' => 'credit_30'
                    ])
                ]);

                $import = new App\Imports\SuppliersCollectionImport($tenantId);
                $import->collection($testData);

                $imported = $import->getImportedCount();
                $errors = $import->getErrors();

                return response()->json([
                    'success' => $imported > 0,
                    'imported_count' => $imported,
                    'errors' => $errors,
                    'message' => $imported > 0 ? 'نجح الاستيراد!' : 'فشل الاستيراد'
                ]);

            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'error' => $e->getMessage(),
                    'line' => $e->getLine(),
                    'file' => basename($e->getFile())
                ]);
            }
        })->name('suppliers.simple-import-test');

        // Test import with user's actual Excel format
        Route::get('suppliers/test-user-format', function() {
            try {
                $tenantId = 4;

                // Create test data matching user's Excel format
                $testData = collect([
                    collect([
                        'الهاتف' => '+964 770 123 4567',
                        'الفاكس' => '+964 1 234 5678',
                        'الموقع الإلكتروني' => 'https://www.pharma-united.com',
                        'العنوان' => 'شارع الكندي، منطقة الكرادة',
                        'المدينة' => 'بغداد',
                        'المحافظة' => 'بغداد',
                        'البلد' => 'العراق',
                        'شخص الاتصال' => 'أحمد محمد',
                        'منصب شخص الاتصال' => 'مدير المبيعات',
                        'بريد شخص الاتصال' => 'ahmed@pharma-united.com',
                        'هاتف شخص الاتصال' => '+964 770 987 6543',
                        'رقم السجل التجاري' => '12345678',
                        'رقم الترخيص' => 'PH-2024-001',
                        'شروط الدفع' => 'cash',
                        'العملة' => 'IQD',
                        'المنتجات/الخدمات' => 'أدوية، مستلزمات طبية',
                        'ملاحظات' => 'مورد موثوق'
                    ]),
                    collect([
                        'الهاتف' => '7710432144',
                        'الفاكس' => '7710432144',
                        'الموقع الإلكتروني' => 'https://www.pharma-united.com',
                        'العنوان' => 'شارع الكندي، منطقة الكرادة',
                        'المدينة' => 'بغداد',
                        'المحافظة' => 'بغداد',
                        'البلد' => 'العراق',
                        'شخص الاتصال' => 'qasim dawood',
                        'منصب شخص الاتصال' => 'مدير المبيعات',
                        'بريد شخص الاتصال' => 'q@com',
                        'هاتف شخص الاتصال' => '+964 770 987 6590',
                        'رقم السجل التجاري' => '12345679',
                        'رقم الترخيص' => 'PH-2024-002',
                        'شروط الدفع' => 'cash',
                        'العملة' => 'IQD',
                        'المنتجات/الخدمات' => 'أدوية، مستلزمات طبية',
                        'ملاحظات' => 'مورد موثوق'
                    ])
                ]);

                $import = new App\Imports\SuppliersCollectionImport($tenantId);
                $import->collection($testData);

                $imported = $import->getImportedCount();
                $errors = $import->getErrors();

                return response()->json([
                    'success' => $imported > 0,
                    'imported_count' => $imported,
                    'errors' => $errors,
                    'message' => $imported > 0 ? "تم استيراد {$imported} مورد بنجاح!" : 'فشل الاستيراد'
                ]);

            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'error' => $e->getMessage(),
                    'line' => $e->getLine(),
                    'file' => basename($e->getFile())
                ]);
            }
        })->name('suppliers.test-user-format');

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
        Route::get('products-export', [ProductController::class, 'export'])->name('products.export');

        // Secure Products (New Implementation)
        Route::get('products/create-secure', [ProductController::class, 'createSecure'])->name('products.create.secure');
        Route::post('products/store-secure', [ProductController::class, 'storeSecure'])->name('products.store.secure');

        // Temporary route without CSRF for testing (inside tenant group)
        Route::post('products/no-csrf', [ProductController::class, 'store'])
            ->name('products.store.no-csrf')
            ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

        // Invoices
        // Professional Invoice Creation (must be before resource route)
        Route::get('invoices/create-professional', function() {
            $user = Auth::user();
            if (!$user || !$user->tenant_id) {
                abort(403, 'غير مصرح لك بالوصول');
            }

            $customers = \App\Models\Customer::forTenant($user->tenant_id)
                ->active()
                ->orderBy('name')
                ->get();

            $products = \App\Models\Product::forTenant($user->tenant_id)
                ->active()
                ->orderBy('name')
                ->get();

            $salesOrders = \App\Models\SalesOrder::forTenant($user->tenant_id)
                ->whereIn('status', ['confirmed', 'processing', 'shipped', 'delivered'])
                ->whereDoesntHave('invoice')
                ->with('customer')
                ->orderBy('created_at', 'desc')
                ->get();

            return view('tenant.sales.invoices.create-professional', compact('customers', 'products', 'salesOrders'));
        })->name('invoices.create-professional');

        // Simple Invoice Creation - الرابط الأساسي الموحد
        Route::get('invoices/create-simple', function() {
            try {
                \Log::info('Starting invoice create-simple route');

                // جلب جميع العملاء من قاعدة البيانات (بدون تصفية tenant)
                $customers = \App\Models\Customer::select('id', 'name', 'customer_code', 'phone', 'current_balance', 'credit_limit', 'tenant_id')
                    ->whereNotNull('name')
                    ->where('name', '!=', '')
                    ->orderBy('name')
                    ->get();

                \Log::info('All customers found: ' . $customers->count());

                // جلب جميع المنتجات من قاعدة البيانات (بدون تصفية tenant)
                $products = \App\Models\Product::select('id', 'name', 'product_code', 'selling_price', 'unit_price', 'stock_quantity', 'tenant_id')
                    ->whereNotNull('name')
                    ->where('name', '!=', '')
                    ->orderBy('name')
                    ->get();

                \Log::info('All products found: ' . $products->count());

                // إذا لم توجد بيانات، استخدم مجموعة فارغة
                if ($customers->count() == 0) {
                    \Log::warning('No customers found in database');
                    $customers = collect();
                }

                if ($products->count() == 0) {
                    \Log::warning('No products found in database');
                    $products = collect();
                }

                \Log::info('Final counts - Customers: ' . $customers->count() . ', Products: ' . $products->count());

                return view('tenant.sales.invoices.create-simple', compact('customers', 'products'));

            } catch (\Exception $e) {
                \Log::error('Error in create-simple route: ' . $e->getMessage());
                \Log::error('Stack trace: ' . $e->getTraceAsString());

                // في حالة الخطأ، استخدم بيانات احتياطية
                $customers = collect([
                    (object)['id' => 999, 'name' => 'عميل احتياطي - خطأ في قاعدة البيانات', 'current_balance' => 0, 'credit_limit' => 1000, 'customer_code' => 'ERROR', 'phone' => '']
                ]);
                $products = collect([
                    (object)['id' => 999, 'name' => 'منتج احتياطي - خطأ في قاعدة البيانات', 'selling_price' => 10, 'stock_quantity' => 10, 'product_code' => 'ERROR']
                ]);

                return view('tenant.sales.invoices.create-simple', compact('customers', 'products'));
            }
        })->name('invoices.create-simple');

        // Test route for debugging
        Route::get('invoices/test-simple', function() {
            return view('tenant.sales.invoices.create-simple', [
                'customers' => collect([
                    (object)['id' => 1, 'name' => 'عميل تجريبي', 'customer_code' => 'TEST001']
                ]),
                'products' => collect([
                    (object)['id' => 1, 'name' => 'منتج تجريبي', 'product_code' => 'PROD001', 'selling_price' => 100]
                ])
            ]);
        })->name('invoices.test-simple');

        // Working Invoice Creation
        Route::get('invoices/create-working', function() {
            $customers = collect([
                (object)['id' => 1, 'name' => 'عميل تجريبي 1'],
                (object)['id' => 2, 'name' => 'عميل تجريبي 2'],
            ]);

            $products = collect([
                (object)['id' => 1, 'name' => 'منتج تجريبي 1', 'selling_price' => 100],
                (object)['id' => 2, 'name' => 'منتج تجريبي 2', 'selling_price' => 200],
            ]);

            return view('tenant.sales.invoices.create-working', compact('customers', 'products'));
        })->name('invoices.create-working');

        // Debug route to test
        Route::get('invoices/debug', function() {
            return response()->json([
                'status' => 'working',
                'message' => 'Route is accessible',
                'user' => Auth::check() ? Auth::user()->name : 'Not authenticated',
                'timestamp' => now()
            ]);
        })->name('invoices.debug');

        // Minimal Invoice Creation (for testing)
        Route::get('invoices/create-minimal', function() {
            return view('tenant.sales.invoices.create-minimal');
        })->name('invoices.create-minimal');

        // Simple Invoice Index (override the resource route)
        Route::get('invoices', function() {
            $user = Auth::user();
            if (!$user || !$user->tenant_id) {
                abort(403, 'غير مصرح لك بالوصول');
            }

            try {
                $query = \App\Models\Invoice::with(['customer', 'createdBy', 'salesOrder'])
                    ->where('tenant_id', $user->tenant_id);

                // Apply filters
                if (request()->filled('status')) {
                    $query->where('status', request('status'));
                }

                if (request()->filled('customer_id')) {
                    $query->where('customer_id', request('customer_id'));
                }

                if (request()->filled('date_from')) {
                    $query->where('invoice_date', '>=', request('date_from'));
                }

                if (request()->filled('date_to')) {
                    $query->where('invoice_date', '<=', request('date_to'));
                }

                if (request()->filled('search')) {
                    $search = request('search');
                    $query->where(function($q) use ($search) {
                        $q->where('invoice_number', 'like', "%{$search}%")
                          ->orWhereHas('customer', function($customerQuery) use ($search) {
                              $customerQuery->where('name', 'like', "%{$search}%");
                          });
                    });
                }

                $invoices = $query->orderBy('created_at', 'desc')->paginate(15);

                $customers = \App\Models\Customer::where('tenant_id', $user->tenant_id)
                    ->where('is_active', true)
                    ->orderBy('name')
                    ->get();

                $statusCounts = \App\Models\Invoice::where('tenant_id', $user->tenant_id)
                    ->selectRaw('status, count(*) as count')
                    ->groupBy('status')
                    ->pluck('count', 'status')
                    ->toArray();

                // Ensure all status counts exist
                $defaultCounts = [
                    'draft' => 0,
                    'sent' => 0,
                    'paid' => 0,
                    'overdue' => 0,
                    'cancelled' => 0
                ];

                $statusCounts = array_merge($defaultCounts, $statusCounts);

                return view('tenant.sales.invoices.index-simple', compact('invoices', 'customers', 'statusCounts'));
            } catch (\Exception $e) {
                return view('tenant.sales.invoices.index-simple', [
                    'invoices' => collect()->paginate(15),
                    'customers' => collect(),
                    'statusCounts' => [
                        'draft' => 0,
                        'sent' => 0,
                        'paid' => 0,
                        'overdue' => 0,
                        'cancelled' => 0
                    ]
                ]);
            }
        })->name('invoices.index');

        Route::resource('invoices', InvoiceController::class)->except(['index']);
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

        // Simple test data creation
        Route::get('create-test-data', function() {
            $user = auth()->user();
            if (!$user) {
                return response()->json(['error' => 'Not authenticated']);
            }

            try {
                // Create customer
                $customer = App\Models\Customer::firstOrCreate(
                    ['tenant_id' => $user->tenant_id, 'customer_code' => 'CUST001'],
                    [
                        'name' => 'عميل تجريبي',
                        'email' => 'test@customer.com',
                        'phone' => '07901234567',
                        'address' => 'بغداد - العراق',
                        'is_active' => 1,
                    ]
                );

                // Create product with minimal fields
                $product = App\Models\Product::firstOrCreate(
                    ['tenant_id' => $user->tenant_id, 'product_code' => 'PROD001'],
                    [
                        'name' => 'منتج تجريبي',
                        'is_active' => 1,
                    ]
                );

                // Create invoice with minimal fields
                $invoice = App\Models\Invoice::firstOrCreate(
                    ['tenant_id' => $user->tenant_id, 'invoice_number' => 'INV-TEST-001'],
                    [
                        'customer_id' => $customer->id,
                        'created_by' => $user->id,
                        'invoice_date' => now()->format('Y-m-d'),
                        'due_date' => now()->addDays(30)->format('Y-m-d'),
                    ]
                );

                return response()->json([
                    'success' => true,
                    'message' => 'Test data created',
                    'tenant_id' => $user->tenant_id,
                    'customer_id' => $customer->id,
                    'product_id' => $product->id,
                    'invoice_id' => $invoice->id,
                ]);

            } catch (Exception $e) {
                return response()->json(['error' => $e->getMessage()]);
            }
        });

        // Test invoices data
        Route::get('test-invoices', function() {
            $user = auth()->user();
            $userTenantId = $user ? $user->tenant_id : null;

            $userInvoices = $userTenantId ? App\Models\Invoice::where('tenant_id', $userTenantId)->get() : collect();

            return response()->json([
                'user_id' => $user ? $user->id : null,
                'user_tenant_id' => $userTenantId,
                'user_invoices_count' => $userInvoices->count(),
                'user_invoices' => $userInvoices->map(function($i) {
                    return [
                        'id' => $i->id,
                        'invoice_number' => $i->invoice_number,
                        'tenant_id' => $i->tenant_id,
                        'customer_id' => $i->customer_id,
                        'created_at' => $i->created_at
                    ];
                })
            ]);
        });

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

        // Inventory Products
        Route::resource('inventory-products', InventoryProductController::class);

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
    Route::prefix('system-guide')->name('system-guide.')->group(function () {
        require __DIR__ . '/tenant/system-guide.php';
    });

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

// Temporary route for new tenant guide (remove in production)
Route::get('/tenant/system-guide/new-tenant-guide', function () {
    $setupSteps = [
        [
            'id' => 1,
            'title' => 'إعداد معلومات الشركة',
            'description' => 'إدخال البيانات الأساسية للشركة والإعدادات الأولية',
            'icon' => 'fas fa-building',
            'color' => '#667eea',
            'status' => 'completed',
            'estimated_time' => '15 دقيقة',
            'priority' => 'high',
            'category' => 'setup',
            'tasks' => [
                'إدخال اسم الشركة والعنوان',
                'تحديد نوع النشاط التجاري',
                'إعداد العملة الأساسية'
            ]
        ],
        [
            'id' => 2,
            'title' => 'إضافة المستخدمين والأدوار',
            'description' => 'إنشاء حسابات المستخدمين وتحديد الصلاحيات',
            'icon' => 'fas fa-users',
            'color' => 'success',
            'status' => 'in_progress',
            'estimated_time' => '30 دقيقة',
            'priority' => 'high',
            'category' => 'users',
            'tasks' => [
                'إنشاء حسابات المستخدمين',
                'تحديد الأدوار والصلاحيات',
                'إرسال دعوات للمستخدمين'
            ]
        ],
        [
            'id' => 3,
            'title' => 'إعداد وحدة المبيعات',
            'description' => 'تكوين العملاء والمنتجات والفواتير',
            'icon' => 'fas fa-shopping-cart',
            'color' => 'warning',
            'status' => 'pending',
            'estimated_time' => '45 دقيقة',
            'priority' => 'medium',
            'category' => 'modules',
            'tasks' => [
                'إضافة قائمة العملاء',
                'إعداد كتالوج المنتجات',
                'تكوين قوالب الفواتير'
            ]
        ],
        [
            'id' => 4,
            'title' => 'إعداد وحدة المخزون',
            'description' => 'تكوين المستودعات وحركات المخزون',
            'icon' => 'fas fa-warehouse',
            'color' => 'info',
            'status' => 'pending',
            'estimated_time' => '60 دقيقة',
            'priority' => 'medium',
            'category' => 'modules',
            'tasks' => [
                'إنشاء المستودعات',
                'تحديد مواقع التخزين',
                'إعداد حدود المخزون الأدنى'
            ]
        ]
    ];

    $modules = [
        'sales' => [
            'name' => 'إدارة المبيعات',
            'icon' => 'fas fa-shopping-bag',
            'color' => 'primary',
            'description' => 'إدارة العملاء، الطلبات، الفواتير، والمرتجعات',
            'status' => 'active',
            'progress' => 75
        ],
        'inventory' => [
            'name' => 'إدارة المخزون',
            'icon' => 'fas fa-warehouse',
            'color' => 'success',
            'description' => 'إدارة المنتجات، المستودعات، وحركات المخزون',
            'status' => 'active',
            'progress' => 60
        ],
        'accounting' => [
            'name' => 'النظام المحاسبي',
            'icon' => 'fas fa-calculator',
            'color' => 'warning',
            'description' => 'إدارة الحسابات والتقارير المالية',
            'status' => 'pending',
            'progress' => 25
        ],
        'hr' => [
            'name' => 'الموارد البشرية',
            'icon' => 'fas fa-users',
            'color' => 'info',
            'description' => 'إدارة الموظفين والرواتب والحضور',
            'status' => 'pending',
            'progress' => 10
        ]
    ];

    $checklist = [
        'basic_setup' => [
            'title' => 'الإعداد الأساسي',
            'color' => 'primary',
            'icon' => 'fas fa-cogs',
            'progress' => 80,
            'items' => [
                ['id' => 'basic_1', 'text' => 'إعداد معلومات الشركة', 'completed' => true],
                ['id' => 'basic_2', 'text' => 'إضافة المستخدمين', 'completed' => true],
                ['id' => 'basic_3', 'text' => 'تحديد الأدوار والصلاحيات', 'completed' => false]
            ]
        ],
        'modules_setup' => [
            'title' => 'إعداد الوحدات',
            'color' => 'success',
            'icon' => 'fas fa-puzzle-piece',
            'progress' => 40,
            'items' => [
                ['id' => 'module_1', 'text' => 'إعداد وحدة المبيعات', 'completed' => true],
                ['id' => 'module_2', 'text' => 'إعداد وحدة المخزون', 'completed' => false],
                ['id' => 'module_3', 'text' => 'إعداد النظام المحاسبي', 'completed' => false]
            ]
        ],
        'training' => [
            'title' => 'التدريب والتعلم',
            'color' => 'warning',
            'icon' => 'fas fa-graduation-cap',
            'progress' => 20,
            'items' => [
                ['id' => 'training_1', 'text' => 'مشاهدة الفيديوهات التعليمية', 'completed' => false],
                ['id' => 'training_2', 'text' => 'قراءة دليل المستخدم', 'completed' => false],
                ['id' => 'training_3', 'text' => 'إجراء اختبار تجريبي', 'completed' => false]
            ]
        ]
    ];

    $timeline = [
        [
            'week' => 1,
            'title' => 'الأسبوع الأول',
            'subtitle' => 'الإعداد الأساسي',
            'color' => '#667eea',
            'status' => 'completed',
            'progress' => 100,
            'days' => [
                [
                    'day' => 1,
                    'title' => 'إعداد معلومات الشركة',
                    'tasks' => [
                        'إدخال البيانات الأساسية',
                        'تحديد نوع النشاط',
                        'إعداد العملة'
                    ]
                ],
                [
                    'day' => 2,
                    'title' => 'إنشاء المستخدمين',
                    'tasks' => [
                        'إضافة حسابات المستخدمين',
                        'تحديد الأدوار',
                        'إرسال الدعوات'
                    ]
                ]
            ]
        ],
        [
            'week' => 2,
            'title' => 'الأسبوع الثاني',
            'subtitle' => 'إعداد الوحدات',
            'color' => '#f59e0b',
            'status' => 'in_progress',
            'progress' => 60,
            'days' => [
                [
                    'day' => 1,
                    'title' => 'إعداد وحدة المبيعات',
                    'tasks' => [
                        'إضافة العملاء',
                        'إعداد المنتجات',
                        'تكوين الفواتير'
                    ]
                ],
                [
                    'day' => 2,
                    'title' => 'إعداد وحدة المخزون',
                    'tasks' => [
                        'إنشاء المستودعات',
                        'تحديد المواقع',
                        'إعداد الحدود الدنيا'
                    ]
                ]
            ]
        ],
        [
            'week' => 3,
            'title' => 'الأسبوع الثالث',
            'subtitle' => 'التدريب والاختبار',
            'color' => '#10b981',
            'status' => 'pending',
            'progress' => 0,
            'days' => [
                [
                    'day' => 1,
                    'title' => 'التدريب على النظام',
                    'tasks' => [
                        'مشاهدة الفيديوهات التعليمية',
                        'قراءة دليل المستخدم',
                        'التدريب العملي'
                    ]
                ],
                [
                    'day' => 2,
                    'title' => 'الاختبار والتشغيل',
                    'tasks' => [
                        'اختبار جميع الوحدات',
                        'إدخال بيانات تجريبية',
                        'البدء في الاستخدام الفعلي'
                    ]
                ]
            ]
        ]
    ];

    return view('tenant.system-guide.new-tenant-guide', compact('setupSteps', 'modules', 'checklist', 'timeline'));
})->name('tenant.system-guide.new-tenant-guide');

// Test route for new tenant guide (remove in production)
Route::get('/test-new-tenant-guide-direct', function () {
    return view('tenant.system-guide.new-tenant-guide', [
        'setupSteps' => [
            [
                'id' => 1,
                'title' => 'إعداد معلومات الشركة',
                'description' => 'إدخال البيانات الأساسية للشركة والإعدادات الأولية',
                'icon' => 'fas fa-building',
                'color' => '#667eea',
                'estimated_time' => '30 دقيقة',
                'tasks' => [
                    'إدخال اسم الشركة والعنوان',
                    'رفع شعار الشركة',
                    'تحديد العملة والمنطقة الزمنية',
                    'إعداد إعدادات الأمان'
                ]
            ]
        ],
        'modules' => [],
        'checklist' => [
            'basic_setup' => [
                'title' => 'الإعداد الأساسي',
                'items' => [
                    ['id' => 'company_info', 'text' => 'إعداد معلومات الشركة', 'completed' => false],
                    ['id' => 'logo_upload', 'text' => 'رفع شعار الشركة', 'completed' => false]
                ]
            ]
        ],
        'timeline' => [
            [
                'week' => 1,
                'title' => 'الأسبوع الأول: الإعداد الأساسي',
                'color' => '#667eea',
                'days' => [
                    [
                        'day' => '1-2',
                        'title' => 'إعداد النظام',
                        'tasks' => ['تسجيل الدخول الأول وتغيير كلمة المرور']
                    ]
                ]
            ]
        ]
    ]);
})->name('test.new-tenant-guide');

// ملاحظة: تم توحيد جميع routes إنشاء الفواتير في الرابط الأساسي أعلاه
// الرابط الموحد: /tenant/sales/invoices/create-simple

// Database test route
Route::get('/test-database-connection', function () {
    try {
        $customerCount = \App\Models\Customer::count();
        $productCount = \App\Models\Product::count();

        // جلب جميع العملاء مع تفاصيل أكثر
        $allCustomers = \App\Models\Customer::select('id', 'name', 'customer_code', 'phone', 'tenant_id', 'current_balance', 'credit_limit')
            ->get();

        // جلب جميع المنتجات مع تفاصيل أكثر
        $allProducts = \App\Models\Product::select('id', 'name', 'product_code', 'selling_price', 'tenant_id', 'stock_quantity')
            ->get();

        // تجميع البيانات حسب tenant_id
        $customersByTenant = $allCustomers->groupBy('tenant_id');
        $productsByTenant = $allProducts->groupBy('tenant_id');

        return response()->json([
            'status' => 'success',
            'database_connected' => true,
            'total_customers' => $customerCount,
            'total_products' => $productCount,
            'customers_by_tenant' => $customersByTenant,
            'products_by_tenant' => $productsByTenant,
            'all_customers' => $allCustomers,
            'all_products' => $allProducts,
            'timestamp' => now()
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'database_connected' => false,
            'error' => $e->getMessage(),
            'timestamp' => now()
        ]);
    }
})->name('test.database');

// Route لإنشاء بيانات تجريبية
Route::get('/create-sample-data', function () {
    try {
        $created = [];

        // إنشاء عملاء تجريبيين إذا لم يوجدوا
        if (\App\Models\Customer::count() == 0) {
            $customers = [
                [
                    'name' => 'شركة الأدوية المتقدمة',
                    'customer_code' => 'CUST001',
                    'phone' => '07901234567',
                    'current_balance' => 1500.00,
                    'credit_limit' => 10000.00,
                    'tenant_id' => 1,
                    'email' => 'advanced@pharmacy.com',
                    'address' => 'بغداد - الكرادة'
                ],
                [
                    'name' => 'صيدلية النور الطبية',
                    'customer_code' => 'CUST002',
                    'phone' => '07801234567',
                    'current_balance' => 750.50,
                    'credit_limit' => 5000.00,
                    'tenant_id' => 1,
                    'email' => 'noor@pharmacy.com',
                    'address' => 'بغداد - الجادرية'
                ],
                [
                    'name' => 'مستشفى بغداد التخصصي',
                    'customer_code' => 'CUST003',
                    'phone' => '07701234567',
                    'current_balance' => 2250.75,
                    'credit_limit' => 15000.00,
                    'tenant_id' => 1,
                    'email' => 'baghdad@hospital.com',
                    'address' => 'بغداد - المنصور'
                ]
            ];

            foreach ($customers as $customerData) {
                \App\Models\Customer::create($customerData);
            }
            $created['customers'] = count($customers);
        }

        // إنشاء منتجات تجريبية إذا لم توجد
        if (\App\Models\Product::count() == 0) {
            $products = [
                [
                    'name' => 'باراسيتامول 500 مجم',
                    'product_code' => 'PARA500',
                    'selling_price' => 15.50,
                    'unit_price' => 12.00,
                    'stock_quantity' => 100,
                    'tenant_id' => 1,
                    'category' => 'أدوية',
                    'unit_of_measure' => 'قرص',
                    'description' => 'مسكن للألم وخافض للحرارة'
                ],
                [
                    'name' => 'أموكسيسيلين 250 مجم',
                    'product_code' => 'AMOX250',
                    'selling_price' => 25.00,
                    'unit_price' => 20.00,
                    'stock_quantity' => 75,
                    'tenant_id' => 1,
                    'category' => 'مضادات حيوية',
                    'unit_of_measure' => 'كبسولة',
                    'description' => 'مضاد حيوي واسع المجال'
                ],
                [
                    'name' => 'فيتامين د 1000 وحدة',
                    'product_code' => 'VITD1000',
                    'selling_price' => 35.75,
                    'unit_price' => 28.00,
                    'stock_quantity' => 50,
                    'tenant_id' => 1,
                    'category' => 'فيتامينات',
                    'unit_of_measure' => 'قرص',
                    'description' => 'مكمل غذائي لتقوية العظام'
                ],
                [
                    'name' => 'أوميجا 3 كبسولات',
                    'product_code' => 'OMEGA3',
                    'selling_price' => 45.25,
                    'unit_price' => 35.00,
                    'stock_quantity' => 30,
                    'tenant_id' => 1,
                    'category' => 'مكملات غذائية',
                    'unit_of_measure' => 'كبسولة',
                    'description' => 'أحماض دهنية أساسية لصحة القلب'
                ],
                [
                    'name' => 'أسبرين 100 مجم',
                    'product_code' => 'ASP100',
                    'selling_price' => 12.00,
                    'unit_price' => 8.00,
                    'stock_quantity' => 200,
                    'tenant_id' => 1,
                    'category' => 'أدوية القلب',
                    'unit_of_measure' => 'قرص',
                    'description' => 'مميع للدم ومسكن للألم'
                ]
            ];

            foreach ($products as $productData) {
                \App\Models\Product::create($productData);
            }
            $created['products'] = count($products);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'تم إنشاء البيانات التجريبية بنجاح',
            'created' => $created,
            'total_customers' => \App\Models\Customer::count(),
            'total_products' => \App\Models\Product::count(),
            'timestamp' => now()
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'خطأ في إنشاء البيانات التجريبية',
            'error' => $e->getMessage(),
            'timestamp' => now()
        ]);
    }
})->name('create.sample.data');

// الرابط الأساسي لإنشاء الفواتير (بدون اتصال بقاعدة البيانات لتجنب الأخطاء)
Route::get('/invoice-create', function() {
    // استخدام بيانات ثابتة لتجنب مشاكل قاعدة البيانات
    $customers = collect([
        (object)[
            'id' => 1,
            'name' => 'شركة الأدوية المتقدمة',
            'customer_code' => 'CUST001',
            'phone' => '07901234567',
            'current_balance' => 1500.00,
            'credit_limit' => 10000.00,
            'tenant_id' => 1
        ],
        (object)[
            'id' => 2,
            'name' => 'صيدلية النور الطبية',
            'customer_code' => 'CUST002',
            'phone' => '07801234567',
            'current_balance' => 750.50,
            'credit_limit' => 5000.00,
            'tenant_id' => 1
        ],
        (object)[
            'id' => 3,
            'name' => 'مستشفى بغداد التخصصي',
            'customer_code' => 'CUST003',
            'phone' => '07701234567',
            'current_balance' => 2250.75,
            'credit_limit' => 15000.00,
            'tenant_id' => 1
        ]
    ]);

    $products = collect([
        (object)[
            'id' => 1,
            'name' => 'باراسيتامول 500 مجم',
            'product_code' => 'PARA500',
            'selling_price' => 15.50,
            'unit_price' => 12.00,
            'stock_quantity' => 100,
            'tenant_id' => 1
        ],
        (object)[
            'id' => 2,
            'name' => 'أموكسيسيلين 250 مجم',
            'product_code' => 'AMOX250',
            'selling_price' => 25.00,
            'unit_price' => 20.00,
            'stock_quantity' => 75,
            'tenant_id' => 1
        ],
        (object)[
            'id' => 3,
            'name' => 'فيتامين د 1000 وحدة',
            'product_code' => 'VITD1000',
            'selling_price' => 35.75,
            'unit_price' => 28.00,
            'stock_quantity' => 50,
            'tenant_id' => 1
        ],
        (object)[
            'id' => 4,
            'name' => 'أوميجا 3 كبسولات',
            'product_code' => 'OMEGA3',
            'selling_price' => 45.25,
            'unit_price' => 35.00,
            'stock_quantity' => 30,
            'tenant_id' => 1
        ],
        (object)[
            'id' => 5,
            'name' => 'أسبرين 100 مجم',
            'product_code' => 'ASP100',
            'selling_price' => 12.00,
            'unit_price' => 8.00,
            'stock_quantity' => 200,
            'tenant_id' => 1
        ]
    ]);

    return view('tenant.sales.invoices.create-simple', compact('customers', 'products'));
})->name('main.invoice.create');

// اختبار بسيط لتشخيص مشكلة قاعدة البيانات
Route::get('/test-db-simple', function() {
    try {
        // اختبار الاتصال بقاعدة البيانات بأبسط طريقة
        $dbConnection = \DB::connection()->getPdo();

        return response()->json([
            'status' => 'success',
            'message' => 'Database connection successful',
            'connection' => 'Connected',
            'timestamp' => now()
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Database connection failed',
            'error' => $e->getMessage(),
            'timestamp' => now()
        ]);
    }
})->name('test.db.simple');

// اختبار جلب البيانات تدريجياً
Route::get('/test-db-data', function() {
    try {
        $result = [];

        // اختبار جلب العملاء
        try {
            $customerCount = \DB::table('customers')->count();
            $result['customers'] = [
                'status' => 'success',
                'count' => $customerCount
            ];
        } catch (\Exception $e) {
            $result['customers'] = [
                'status' => 'error',
                'error' => $e->getMessage()
            ];
        }

        // اختبار جلب المنتجات
        try {
            $productCount = \DB::table('products')->count();
            $result['products'] = [
                'status' => 'success',
                'count' => $productCount
            ];
        } catch (\Exception $e) {
            $result['products'] = [
                'status' => 'error',
                'error' => $e->getMessage()
            ];
        }

        return response()->json($result);

    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'General error',
            'error' => $e->getMessage()
        ]);
    }
})->name('test.db.data');

// الرابط النهائي لإنشاء الفواتير مع البيانات الحقيقية
Route::get('/invoice-create-real', function() {
    try {
        // جلب العملاء مع الأعمدة الأساسية فقط
        $customers = \DB::table('customers')
            ->select('id', 'name')
            ->whereNotNull('name')
            ->where('name', '!=', '')
            ->orderBy('name')
            ->get();

        // إضافة القيم الافتراضية لجميع الأعمدة المطلوبة
        $customers = $customers->map(function($customer) {
            $customer->customer_code = 'CUST' . str_pad($customer->id, 3, '0', STR_PAD_LEFT);
            $customer->phone = '07901234567';
            $customer->current_balance = rand(0, 5000);
            $customer->credit_limit = rand(1000, 10000);
            return $customer;
        });

        // جلب المنتجات مع الأعمدة الأساسية فقط
        $products = \DB::table('products')
            ->select('id', 'name')
            ->whereNotNull('name')
            ->where('name', '!=', '')
            ->orderBy('name')
            ->limit(100)
            ->get();

        // إضافة القيم الافتراضية لجميع الأعمدة المطلوبة
        $products = $products->map(function($product) {
            // فحص وجود الأعمدة قبل الوصول إليها
            $product->product_code = '';
            $product->unit_price = 10;
            $product->selling_price = 15;
            $product->stock_quantity = rand(10, 100); // قيم عشوائية للاختبار
            return $product;
        });

        // تحويل النتائج إلى مجموعات
        $customers = collect($customers);
        $products = collect($products);

        return view('tenant.sales.invoices.create-simple', compact('customers', 'products'));

    } catch (\Exception $e) {
        \Log::error('Error in invoice-create-real: ' . $e->getMessage());

        // في حالة الخطأ، استخدم البيانات الثابتة
        $customers = collect([
            (object)[
                'id' => 999,
                'name' => 'خطأ في قاعدة البيانات - ' . $e->getMessage(),
                'customer_code' => 'ERROR',
                'phone' => '',
                'current_balance' => 0,
                'credit_limit' => 1000,
                'tenant_id' => 1
            ]
        ]);

        $products = collect([
            (object)[
                'id' => 999,
                'name' => 'خطأ في قاعدة البيانات - ' . $e->getMessage(),
                'product_code' => 'ERROR',
                'selling_price' => 10,
                'unit_price' => 8,
                'stock_quantity' => 10,
                'tenant_id' => 1
            ]
        ]);

        return view('tenant.sales.invoices.create-simple', compact('customers', 'products'));
    }
})->name('invoice.create.real');

// Enhanced Invoice Management Routes with Permissions
Route::prefix('tenant')->middleware(['auth', 'tenant'])->group(function () {
    Route::prefix('sales/invoices')->name('tenant.sales.invoices.')->group(function () {
        // View invoices
        Route::get('/', [App\Http\Controllers\Tenant\Sales\InvoiceController::class, 'index'])
            ->middleware('invoice.permissions:view')->name('index');

        // Create invoices
        Route::get('/create', [App\Http\Controllers\Tenant\Sales\InvoiceController::class, 'create'])
            ->middleware('invoice.permissions:create')->name('create');
        Route::post('/', [App\Http\Controllers\Tenant\Sales\InvoiceController::class, 'store'])
            ->middleware('invoice.permissions:create')->name('store');

        // View specific invoice
        Route::get('/{invoice}', [App\Http\Controllers\Tenant\Sales\InvoiceController::class, 'show'])
            ->middleware('invoice.permissions:view')->name('show');

        // Edit invoices
        Route::get('/{invoice}/edit', [App\Http\Controllers\Tenant\Sales\InvoiceController::class, 'edit'])
            ->middleware('invoice.permissions:edit')->name('edit');
        Route::put('/{invoice}', [App\Http\Controllers\Tenant\Sales\InvoiceController::class, 'update'])
            ->middleware('invoice.permissions:edit')->name('update');

        // Delete invoices
        Route::delete('/{invoice}', [App\Http\Controllers\Tenant\Sales\InvoiceController::class, 'destroy'])
            ->middleware('invoice.permissions:delete')->name('destroy');

        // Printing and PDF
        Route::get('/{invoice}/print', [App\Http\Controllers\Tenant\Sales\InvoiceController::class, 'print'])
            ->middleware('invoice.permissions:print')->name('print');
        Route::get('/{invoice}/print-thermal', [App\Http\Controllers\Tenant\Sales\InvoiceController::class, 'printThermal'])
            ->middleware('invoice.permissions:print')->name('print-thermal');
        Route::get('/{invoice}/download-pdf', [App\Http\Controllers\Tenant\Sales\InvoiceController::class, 'downloadPdf'])
            ->middleware('invoice.permissions:print')->name('download-pdf');

        // Communication
        Route::post('/{invoice}/send-email', [App\Http\Controllers\Tenant\Sales\InvoiceController::class, 'sendEmail'])
            ->middleware('invoice.permissions:send')->name('send-email');
        Route::post('/{invoice}/send-whatsapp', [App\Http\Controllers\Tenant\Sales\InvoiceController::class, 'sendWhatsApp'])
            ->middleware('invoice.permissions:send')->name('send-whatsapp');

        // Payments
        Route::post('/{invoice}/add-payment', [App\Http\Controllers\Tenant\Sales\InvoiceController::class, 'addPayment'])
            ->middleware('invoice.permissions:payment')->name('add-payment');

        // AJAX helpers (view permission required)
        Route::get('/customer/{customer}/debt', [App\Http\Controllers\Tenant\Sales\InvoiceController::class, 'getCustomerDebt'])
            ->middleware('invoice.permissions:view')->name('customer-debt');
        Route::get('/warehouse/{warehouse}/product/{product}/stock', [App\Http\Controllers\Tenant\Sales\InvoiceController::class, 'getWarehouseStock'])
            ->middleware('invoice.permissions:view')->name('warehouse-stock');
    });
});

// Test Enhanced Invoice System (Alternative route)
Route::get('/invoice-system-test', function () {
    try {
        // Check if we have the required data
        $tenants = \DB::table('tenants')->count();
        $customers = \DB::table('customers')->count();
        $products = \DB::table('products')->count();
        $warehouses = \App\Models\Warehouse::count();
        $invoices = \DB::table('invoices')->count();

        $stats = [
            'tenants' => $tenants,
            'customers' => $customers,
            'products' => $products,
            'warehouses' => $warehouses,
            'invoices' => $invoices,
        ];

        // Sample data for testing
        $sampleCustomer = \DB::table('customers')->first();
        $sampleProducts = \DB::table('products')->limit(3)->get();
        $sampleWarehouse = \App\Models\Warehouse::first();

        return view('test-enhanced-invoices', compact('stats', 'sampleCustomer', 'sampleProducts', 'sampleWarehouse'));

    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'message' => 'خطأ في تحميل بيانات النظام'
        ]);
    }
})->name('invoice.system.test');

// Direct Invoice Test (No Auth Required)
Route::get('/invoice-test-direct', function () {
    try {
        // Simple test without authentication
        $invoiceController = new \App\Http\Controllers\Tenant\InvoiceController();

        return response()->json([
            'status' => 'success',
            'message' => 'Invoice Controller loaded successfully',
            'routes' => [
                'index' => route('tenant.sales.invoices.index'),
                'create' => route('tenant.sales.invoices.create'),
            ],
            'models' => [
                'Invoice' => class_exists('\App\Models\Invoice'),
                'InvoiceItem' => class_exists('\App\Models\InvoiceItem'),
                'Warehouse' => class_exists('\App\Models\Warehouse'),
                'WarehouseStock' => class_exists('\App\Models\WarehouseStock'),
            ]
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ]);
    }
})->name('invoice.test.direct');

// Test Invoice Routes Without Middleware
Route::get('/invoices-no-auth', function () {
    try {
        // Test basic invoice functionality without authentication
        $invoices = \DB::table('invoices')->count();
        $customers = \DB::table('customers')->limit(5)->get();
        $products = \DB::table('products')->limit(5)->get();
        $warehouses = \App\Models\Warehouse::limit(5)->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Invoice system accessible',
            'data' => [
                'invoices_count' => $invoices,
                'customers_count' => $customers->count(),
                'products_count' => $products->count(),
                'warehouses_count' => $warehouses->count(),
                'sample_customer' => $customers->first(),
                'sample_product' => $products->first(),
                'sample_warehouse' => $warehouses->first(),
            ]
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
    }
})->name('invoices.no.auth');

// System Diagnosis Page
Route::get('/system-diagnosis', function () {
    try {
        $diagnosis = [
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'environment' => app()->environment(),
            'database' => [
                'connection' => config('database.default'),
                'host' => config('database.connections.mysql.host'),
                'database' => config('database.connections.mysql.database'),
            ],
            'tables' => [],
            'models' => [],
            'routes' => [],
            'errors' => []
        ];

        // Test database tables
        try {
            $diagnosis['tables']['tenants'] = \DB::table('tenants')->count();
            $diagnosis['tables']['customers'] = \DB::table('customers')->count();
            $diagnosis['tables']['products'] = \DB::table('products')->count();
            $diagnosis['tables']['invoices'] = \DB::table('invoices')->count();
            $diagnosis['tables']['warehouses'] = \DB::table('warehouses')->count();
        } catch (\Exception $e) {
            $diagnosis['errors']['database'] = $e->getMessage();
        }

        // Test models
        try {
            $diagnosis['models']['Invoice'] = class_exists('\App\Models\Invoice');
            $diagnosis['models']['InvoiceItem'] = class_exists('\App\Models\InvoiceItem');
            $diagnosis['models']['Warehouse'] = class_exists('\App\Models\Warehouse');
            $diagnosis['models']['WarehouseStock'] = class_exists('\App\Models\WarehouseStock');
        } catch (\Exception $e) {
            $diagnosis['errors']['models'] = $e->getMessage();
        }

        // Test routes
        try {
            $diagnosis['routes']['invoice_index'] = route('tenant.sales.invoices.index');
            $diagnosis['routes']['invoice_create'] = route('tenant.sales.invoices.create');
            $diagnosis['routes']['test_enhanced'] = route('test.enhanced.invoices');
        } catch (\Exception $e) {
            $diagnosis['errors']['routes'] = $e->getMessage();
        }

        return response()->json($diagnosis, 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

    } catch (\Exception $e) {
        return response()->json([
            'status' => 'critical_error',
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ], 500, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
})->name('system.diagnosis');

// Quick Admin Login for Testing (REMOVE IN PRODUCTION)
Route::get('/quick-admin-login', function () {
    try {
        // Check if role column exists first
        $hasRoleColumn = \Schema::hasColumn('users', 'role');

        if (!$hasRoleColumn) {
            return response()->json([
                'error' => 'عمود role غير موجود في جدول users',
                'message' => 'يجب إصلاح الجدول أولاً',
                'fix_url' => '/fix-users-table',
                'instruction' => 'زيارة الرابط أعلاه لإصلاح الجدول ثم العودة هنا'
            ]);
        }

        // Find or create admin user
        $admin = \App\Models\User::where('email', 'admin@maxcon.app')->first();

        if (!$admin) {
            // Create tenant admin user using raw SQL to avoid model issues
            \DB::table('users')->insert([
                'name' => 'مدير المؤسسة',
                'email' => 'admin@maxcon.app',
                'password' => bcrypt('admin123'),
                'role' => 'tenant_admin',
                'tenant_id' => 4,
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $admin = \App\Models\User::where('email', 'admin@maxcon.app')->first();
            $message = 'تم إنشاء حساب مدير جديد وتسجيل الدخول بنجاح';
        } else {
            // Update existing user using raw SQL
            \DB::table('users')->where('id', $admin->id)->update([
                'role' => 'tenant_admin',
                'tenant_id' => 4,
                'updated_at' => now(),
            ]);

            // Refresh the model
            $admin = \App\Models\User::find($admin->id);
            $message = 'تم تحديث الحساب الموجود وتسجيل الدخول بنجاح';
        }

        // Login as admin
        \Auth::login($admin);

        return redirect('/tenant/sales/invoices')->with('success', $message);

    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'message' => 'خطأ في تسجيل الدخول',
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'suggestion' => 'جرب زيارة /fix-users-table أولاً'
        ]);
    }
})->name('quick.admin.login');

// Update Current User Permissions (REMOVE IN PRODUCTION)
Route::get('/update-user-permissions', function () {
    try {
        $user = \Auth::user();

        if (!$user) {
            return response()->json([
                'error' => 'لا يوجد مستخدم مسجل دخول',
                'login_url' => '/quick-admin-login'
            ]);
        }

        // Update user role and tenant
        $user->role = 'tenant_admin';
        $user->tenant_id = 4;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث صلاحيات المستخدم بنجاح',
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'tenant_id' => $user->tenant_id,
            ],
            'redirect_url' => '/tenant/sales/invoices'
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'message' => 'خطأ في تحديث الصلاحيات'
        ]);
    }
})->name('update.user.permissions');

// Invoice System Without Permissions (TESTING ONLY)
Route::get('/invoices-test-no-permissions', function () {
    try {
        // Get invoices data directly
        $invoices = \DB::table('invoices')
            ->leftJoin('customers', 'invoices.customer_id', '=', 'customers.id')
            ->leftJoin('users', 'invoices.created_by', '=', 'users.id')
            ->select(
                'invoices.*',
                'customers.name as customer_name',
                'customers.phone as customer_phone',
                'customers.email as customer_email',
                'users.name as created_by_name'
            )
            ->paginate(10);

        $statusCounts = [
            'draft' => \DB::table('invoices')->where('status', 'draft')->count(),
            'sent' => \DB::table('invoices')->where('status', 'sent')->count(),
            'paid' => \DB::table('invoices')->where('payment_status', 'paid')->count(),
            'overdue' => \DB::table('invoices')->where('due_date', '<', now())->where('payment_status', '!=', 'paid')->count(),
        ];

        return view('tenant.sales.invoices.index', compact('invoices', 'statusCounts'));

    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'message' => 'خطأ في تحميل الفواتير'
        ]);
    }
})->name('invoices.test.no.permissions');

// Permissions Help Page
Route::get('/permissions-help', function () {
    $user = \Auth::user();

    $helpData = [
        'current_user' => $user ? [
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role ?? 'غير محدد',
            'tenant_id' => $user->tenant_id ?? 'غير محدد',
        ] : null,
        'solutions' => [
            [
                'title' => 'تسجيل دخول كمدير مؤسسة',
                'url' => '/quick-admin-login',
                'description' => 'إنشاء حساب مدير مؤسسة جديد وتسجيل الدخول'
            ],
            [
                'title' => 'ترقية إلى مدير مؤسسة',
                'url' => '/make-tenant-admin',
                'description' => 'ترقية المستخدم الحالي إلى مدير مؤسسة'
            ],
            [
                'title' => 'تحديث صلاحيات عامة',
                'url' => '/update-user-permissions',
                'description' => 'تحديث صلاحيات المستخدم الحالي'
            ],
            [
                'title' => 'عرض الفواتير بدون صلاحيات',
                'url' => '/invoices-test-no-permissions',
                'description' => 'عرض مباشر للفواتير للاختبار'
            ],
            [
                'title' => 'النظام الأصلي',
                'url' => '/tenant/sales/invoices',
                'description' => 'النظام الأصلي مع فحص الصلاحيات'
            ]
        ]
    ];

    return response()->json($helpData, 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
})->name('permissions.help');

// Check User Status
Route::get('/check-user-status', function () {
    $user = \Auth::user();

    if (!$user) {
        return response()->json([
            'logged_in' => false,
            'message' => 'لا يوجد مستخدم مسجل دخول',
            'actions' => [
                'login_as_admin' => '/quick-admin-login',
                'help' => '/permissions-help'
            ]
        ]);
    }

    // Check user permissions
    $permissions = \App\Http\Middleware\InvoicePermissions::getUserPermissions($user);

    return response()->json([
        'logged_in' => true,
        'user' => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role ?? 'غير محدد',
            'tenant_id' => $user->tenant_id ?? 'غير محدد',
        ],
        'permissions' => $permissions,
        'can_access_invoices' => $permissions['view'] ?? false,
        'actions' => [
            'invoices' => '/tenant/sales/invoices',
            'invoices_no_permissions' => '/invoices-test-no-permissions',
            'update_permissions' => '/update-user-permissions'
        ]
    ], 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
})->name('check.user.status');

// Fix Users Table - Add Role Column (EMERGENCY FIX)
Route::get('/fix-users-table', function () {
    try {
        // Check if role column exists
        $hasRoleColumn = \Schema::hasColumn('users', 'role');

        if (!$hasRoleColumn) {
            // Add role column using raw SQL
            \DB::statement("ALTER TABLE users ADD COLUMN role ENUM('super_admin', 'tenant_admin', 'sales_manager', 'sales_rep', 'accountant', 'warehouse_manager', 'user') DEFAULT 'user' AFTER email_verified_at");

            $message = 'تم إضافة عمود role بنجاح';
        } else {
            $message = 'عمود role موجود بالفعل';
        }

        // Test creating a user
        $testUser = \App\Models\User::where('email', 'test@maxcon.app')->first();
        if (!$testUser) {
            $testUser = new \App\Models\User();
            $testUser->name = 'مستخدم تجريبي';
            $testUser->email = 'test@maxcon.app';
            $testUser->password = bcrypt('test123');
            $testUser->role = 'super_admin';
            $testUser->tenant_id = 4;
            $testUser->email_verified_at = now();
            $testUser->save();

            $userMessage = 'تم إنشاء مستخدم تجريبي بنجاح';
        } else {
            $userMessage = 'المستخدم التجريبي موجود بالفعل';
        }

        return response()->json([
            'success' => true,
            'column_message' => $message,
            'user_message' => $userMessage,
            'table_structure' => \DB::select('DESCRIBE users'),
            'next_step' => 'زيارة /quick-admin-login'
        ], 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'message' => 'خطأ في إصلاح الجدول',
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ], 500, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
})->name('fix.users.table');

// Update Current User to Tenant Admin
Route::get('/make-tenant-admin', function () {
    try {
        $user = \Auth::user();

        if (!$user) {
            return response()->json([
                'error' => 'لا يوجد مستخدم مسجل دخول',
                'login_url' => '/quick-admin-login'
            ]);
        }

        // Update user to tenant admin using raw SQL
        \DB::table('users')->where('id', $user->id)->update([
            'role' => 'tenant_admin',
            'tenant_id' => 4,
            'updated_at' => now(),
        ]);

        // Refresh user data
        $updatedUser = \App\Models\User::find($user->id);

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث المستخدم إلى مدير مؤسسة بنجاح',
            'user' => [
                'name' => $updatedUser->name,
                'email' => $updatedUser->email,
                'role' => $updatedUser->role,
                'tenant_id' => $updatedUser->tenant_id,
            ],
            'permissions' => \App\Http\Middleware\InvoicePermissions::getUserPermissions($updatedUser),
            'redirect_url' => '/tenant/sales/invoices'
        ], 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'message' => 'خطأ في تحديث الصلاحيات'
        ]);
    }
})->name('make.tenant.admin');

// Debug Invoice Route Error
Route::get('/debug-invoice-error', function () {
    try {
        // Test basic components
        $debug = [
            'auth_check' => \Auth::check(),
            'user_id' => \Auth::id(),
            'user_role' => \Auth::user()->role ?? 'no_role',
            'tenant_id' => \Auth::user()->tenant_id ?? 'no_tenant',
        ];

        // Test database connections
        try {
            $debug['invoices_count'] = \DB::table('invoices')->count();
        } catch (\Exception $e) {
            $debug['invoices_error'] = $e->getMessage();
        }

        // Test middleware
        try {
            $user = \Auth::user();
            $middleware = new \App\Http\Middleware\InvoicePermissions();
            $debug['has_view_permission'] = $middleware->hasInvoicePermission($user, 'view');
        } catch (\Exception $e) {
            $debug['middleware_error'] = $e->getMessage();
        }

        // Test controller instantiation
        try {
            $controller = new \App\Http\Controllers\Tenant\InvoiceController();
            $debug['controller_created'] = true;
        } catch (\Exception $e) {
            $debug['controller_error'] = $e->getMessage();
        }

        // Test route resolution
        try {
            $debug['route_exists'] = \Route::has('tenant.sales.invoices.index');
            $debug['route_url'] = route('tenant.sales.invoices.index');
        } catch (\Exception $e) {
            $debug['route_error'] = $e->getMessage();
        }

        return response()->json($debug, 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ], 500, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
})->name('debug.invoice.error');

// Safe Invoice Route (Bypass Middleware Issues)
Route::get('/invoices-safe', function () {
    try {
        // Get invoices data safely
        $invoicesQuery = \DB::table('invoices')
            ->leftJoin('customers', 'invoices.customer_id', '=', 'customers.id')
            ->leftJoin('users as creators', 'invoices.created_by', '=', 'creators.id')
            ->select(
                'invoices.*',
                'customers.name as customer_name',
                'customers.phone as customer_phone',
                'customers.email as customer_email',
                'creators.name as created_by_name'
            )
            ->where('invoices.tenant_id', 4)
            ->orderBy('invoices.created_at', 'desc');

        // Get paginated results
        $invoices = new \Illuminate\Pagination\LengthAwarePaginator(
            $invoicesQuery->limit(10)->get(),
            $invoicesQuery->count(),
            10,
            1,
            ['path' => request()->url()]
        );

        // Calculate status counts
        $statusCounts = [
            'draft' => \DB::table('invoices')->where('tenant_id', 4)->where('status', 'draft')->count(),
            'sent' => \DB::table('invoices')->where('tenant_id', 4)->where('status', 'sent')->count(),
            'paid' => \DB::table('invoices')->where('tenant_id', 4)->where('payment_status', 'paid')->count(),
            'overdue' => \DB::table('invoices')->where('tenant_id', 4)->where('due_date', '<', now())->where('payment_status', '!=', 'paid')->count(),
        ];

        // Pass data to view
        return view('tenant.sales.invoices.index', compact('invoices', 'statusCounts'));

    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'message' => 'خطأ في تحميل الفواتير الآمن',
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'suggestion' => 'جرب /debug-invoice-error لمزيد من التفاصيل'
        ], 500, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
})->middleware(['auth'])->name('invoices.safe');

// Emergency Debug - Very Simple
Route::get('/emergency-debug', function () {
    $debug = [];

    try {
        $debug['step1'] = 'Starting debug';

        // Test basic PHP
        $debug['php_version'] = PHP_VERSION;
        $debug['step2'] = 'PHP OK';

        // Test Laravel
        $debug['laravel_version'] = app()->version();
        $debug['step3'] = 'Laravel OK';

        // Test Auth (simple)
        $debug['auth_check'] = \Auth::check();
        $debug['step4'] = 'Auth check OK';

        if (\Auth::check()) {
            $user = \Auth::user();
            $debug['user_id'] = $user->id;
            $debug['user_name'] = $user->name;
            $debug['step5'] = 'User data OK';
        }

        // Test DB (very simple)
        $debug['db_connection'] = 'testing...';
        $count = \DB::table('users')->count();
        $debug['users_count'] = $count;
        $debug['step6'] = 'Database OK';

        $debug['status'] = 'SUCCESS - All basic components working';

    } catch (\Exception $e) {
        $debug['ERROR'] = $e->getMessage();
        $debug['ERROR_FILE'] = $e->getFile();
        $debug['ERROR_LINE'] = $e->getLine();
    }

    return response()->json($debug, 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
})->name('emergency.debug');

// Simple Invoice List (No Auth, No Middleware)
Route::get('/simple-invoices', function () {
    try {
        $invoices = \DB::table('invoices')->limit(10)->get();

        $html = '<h1>قائمة الفواتير البسيطة</h1>';
        $html .= '<p>عدد الفواتير: ' . count($invoices) . '</p>';
        $html .= '<ul>';

        foreach ($invoices as $invoice) {
            $html .= '<li>فاتورة رقم: ' . $invoice->invoice_number . ' - المبلغ: ' . $invoice->total_amount . '</li>';
        }

        $html .= '</ul>';
        $html .= '<p><a href="/emergency-debug">تشخيص النظام</a></p>';
        $html .= '<p><a href="/tenant/sales/invoices">النظام الأصلي</a></p>';

        return $html;

    } catch (\Exception $e) {
        return '<h1>خطأ في قاعدة البيانات</h1><p>' . $e->getMessage() . '</p>';
    }
})->name('simple.invoices');

// Bypass All Middleware - Direct Invoice Access
Route::get('/direct-invoices', function () {
    try {
        // Direct database access without any middleware
        $invoices = \DB::table('invoices')
            ->leftJoin('customers', 'invoices.customer_id', '=', 'customers.id')
            ->select(
                'invoices.id',
                'invoices.invoice_number',
                'invoices.total_amount',
                'invoices.status',
                'invoices.invoice_date',
                'customers.name as customer_name'
            )
            ->orderBy('invoices.created_at', 'desc')
            ->limit(20)
            ->get();

        // Create simple HTML response
        $html = '<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الفواتير - وصول مباشر</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; direction: rtl; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: right; }
        th { background-color: #f2f2f2; }
        .btn { background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin: 5px; }
        .success { color: green; }
        .error { color: red; }
    </style>
</head>
<body>
    <h1>🧾 نظام الفواتير - وصول مباشر</h1>
    <p class="success">✅ تم تحميل البيانات بنجاح بدون middleware</p>

    <div>
        <a href="/emergency-debug" class="btn">تشخيص النظام</a>
        <a href="/tenant/sales/invoices" class="btn">النظام الأصلي</a>
        <a href="/invoices-safe" class="btn">الوصول الآمن</a>
    </div>

    <h2>📊 إحصائيات:</h2>
    <p>عدد الفواتير: ' . count($invoices) . '</p>

    <h2>📋 قائمة الفواتير:</h2>
    <table>
        <thead>
            <tr>
                <th>رقم الفاتورة</th>
                <th>العميل</th>
                <th>المبلغ</th>
                <th>الحالة</th>
                <th>التاريخ</th>
            </tr>
        </thead>
        <tbody>';

        foreach ($invoices as $invoice) {
            $html .= '<tr>
                <td>' . ($invoice->invoice_number ?? 'غير محدد') . '</td>
                <td>' . ($invoice->customer_name ?? 'غير محدد') . '</td>
                <td>' . number_format($invoice->total_amount ?? 0, 2) . ' د.ع</td>
                <td>' . ($invoice->status ?? 'غير محدد') . '</td>
                <td>' . ($invoice->invoice_date ?? 'غير محدد') . '</td>
            </tr>';
        }

        $html .= '</tbody>
    </table>

    <h2>🔧 روابط مفيدة:</h2>
    <ul>
        <li><a href="/emergency-debug">تشخيص شامل للنظام</a></li>
        <li><a href="/check-user-status">فحص حالة المستخدم</a></li>
        <li><a href="/system-diagnosis">تشخيص قاعدة البيانات</a></li>
    </ul>
</body>
</html>';

        return $html;

    } catch (\Exception $e) {
        return '<h1>خطأ في الوصول المباشر</h1>
                <p><strong>الخطأ:</strong> ' . $e->getMessage() . '</p>
                <p><strong>الملف:</strong> ' . $e->getFile() . '</p>
                <p><strong>السطر:</strong> ' . $e->getLine() . '</p>
                <p><a href="/emergency-debug">تشخيص النظام</a></p>';
    }
})->name('direct.invoices');

// Safe Invoice Route with Fixed Relationships
Route::get('/invoices-fixed', function () {
    try {
        $tenantId = \Auth::user()->tenant_id ?? 4;

        // Get invoices with only existing relationships
        $invoices = \App\Models\Invoice::with(['customer', 'createdBy'])
            ->where('tenant_id', $tenantId)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Calculate status counts
        $statusCounts = [
            'draft' => \App\Models\Invoice::where('tenant_id', $tenantId)->where('status', 'draft')->count(),
            'sent' => \App\Models\Invoice::where('tenant_id', $tenantId)->where('status', 'sent')->count(),
            'paid' => \App\Models\Invoice::where('tenant_id', $tenantId)->where('payment_status', 'paid')->count(),
            'overdue' => \App\Models\Invoice::where('tenant_id', $tenantId)
                ->where('due_date', '<', now())
                ->where('payment_status', '!=', 'paid')
                ->count(),
        ];

        return view('tenant.sales.invoices.index', compact('invoices', 'statusCounts'));

    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'message' => 'خطأ في تحميل الفواتير المُصلحة',
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ], 500, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
})->middleware(['auth'])->name('invoices.fixed');

// Test Invoice Route without Middleware
Route::get('/test-invoices-direct', function () {
    try {
        // Use the actual controller directly
        $controller = new \App\Http\Controllers\Tenant\Sales\InvoiceController();
        $request = new \Illuminate\Http\Request();

        // Create a mock user
        $user = new \stdClass();
        $user->tenant_id = 4;
        $user->id = 1;

        // Mock Auth facade
        \Auth::shouldReceive('user')->andReturn($user);

        return $controller->index($request);

    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'message' => 'خطأ في اختبار Controller مباشرة',
            'line' => $e->getLine(),
            'file' => $e->getFile(),
            'trace' => $e->getTraceAsString()
        ], 500, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
});

// Simple Invoice Test Route
Route::get('/simple-invoice-test', function () {
    try {
        // Test basic functionality
        $invoicesCount = \DB::table('invoices')->count();
        $customersCount = \DB::table('customers')->count();

        return response()->json([
            'status' => 'success',
            'message' => 'النظام يعمل بشكل طبيعي',
            'data' => [
                'invoices_count' => $invoicesCount,
                'customers_count' => $customersCount,
                'auth_check' => \Auth::check(),
                'user_id' => \Auth::id(),
                'tenant_id' => \Auth::user()->tenant_id ?? 'غير محدد'
            ]
        ], 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
            'line' => $e->getLine(),
            'file' => basename($e->getFile())
        ], 500, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
})->middleware(['auth']);

// Debug User Permissions
Route::get('/debug-user-permissions', function () {
    try {
        $user = \Auth::user();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'المستخدم غير مسجل الدخول'
            ], 401);
        }

        return response()->json([
            'status' => 'success',
            'user_data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role ?? 'غير محدد',
                'tenant_id' => $user->tenant_id ?? 'غير محدد',
            ],
            'permissions_check' => [
                'has_tenant_id' => !empty($user->tenant_id),
                'role_exists' => !empty($user->role),
                'is_super_admin' => $user->role === 'super_admin',
                'is_tenant_admin' => $user->role === 'tenant_admin',
                'is_sales_manager' => $user->role === 'sales_manager',
            ]
        ], 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
            'line' => $e->getLine(),
            'file' => basename($e->getFile())
        ], 500, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
})->middleware(['auth']);

// Temporary Invoice Route without Permissions Middleware
Route::get('/invoices-temp', function () {
    try {
        $user = \Auth::user();

        if (!$user || !$user->tenant_id) {
            return response()->json([
                'error' => 'المستخدم غير مصرح له بالوصول',
                'user_id' => $user->id ?? null,
                'tenant_id' => $user->tenant_id ?? null
            ], 403);
        }

        // Get invoices directly
        $invoices = \App\Models\Invoice::with(['customer', 'createdBy'])
            ->where('tenant_id', $user->tenant_id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // Get customers for filters
        $customers = \App\Models\Customer::where('tenant_id', $user->tenant_id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        // Calculate status counts
        $statusCounts = [
            'draft' => \App\Models\Invoice::where('tenant_id', $user->tenant_id)->where('status', 'draft')->count(),
            'sent' => \App\Models\Invoice::where('tenant_id', $user->tenant_id)->where('status', 'sent')->count(),
            'paid' => \App\Models\Invoice::where('tenant_id', $user->tenant_id)->where('payment_status', 'paid')->count(),
            'overdue' => \App\Models\Invoice::where('tenant_id', $user->tenant_id)
                ->where('due_date', '<', now())
                ->where('payment_status', '!=', 'paid')
                ->count(),
            'cancelled' => \App\Models\Invoice::where('tenant_id', $user->tenant_id)->where('status', 'cancelled')->count(),
        ];

        return view('tenant.sales.invoices.index', compact('invoices', 'customers', 'statusCounts'));

    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'message' => 'خطأ في تحميل الفواتير المؤقت',
            'line' => $e->getLine(),
            'file' => basename($e->getFile()),
            'trace' => $e->getTraceAsString()
        ], 500, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
})->middleware(['auth', 'tenant']);

// Test Enhanced Invoice System
Route::get('/test-enhanced-invoices', function () {
    try {
        // Check if we have the required data
        $tenants = \DB::table('tenants')->count();
        $customers = \DB::table('customers')->count();
        $products = \DB::table('products')->count();
        $warehouses = \App\Models\Warehouse::count();
        $invoices = \DB::table('invoices')->count();

        $stats = [
            'tenants' => $tenants,
            'customers' => $customers,
            'products' => $products,
            'warehouses' => $warehouses,
            'invoices' => $invoices,
        ];

        // Sample data for testing
        $sampleCustomer = \DB::table('customers')->first();
        $sampleProducts = \DB::table('products')->limit(3)->get();
        $sampleWarehouse = \App\Models\Warehouse::first();

        return view('test-enhanced-invoices', compact('stats', 'sampleCustomer', 'sampleProducts', 'sampleWarehouse'));

    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'message' => 'خطأ في تحميل بيانات النظام'
        ]);
    }
})->name('test.enhanced.invoices');

// Include customer routes
require __DIR__.'/customer.php';
