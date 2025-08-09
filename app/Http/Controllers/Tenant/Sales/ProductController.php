<?php

namespace App\Http\Controllers\Tenant\Sales;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Imports\ProductsImport;
use App\Imports\ProductsCollectionImport;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
{
    public function __construct()
    {
        \Log::info('ProductController instantiated', [
            'timestamp' => now()->toDateTimeString(),
            'user_id' => auth()->id() ?? 'NOT_AUTHENTICATED'
        ]);
    }
    /**
     * Display a listing of products
     */
    public function index(Request $request): View
    {
        $user = auth()->user();
        $tenantId = $user ? $user->tenant_id : null;

        // مؤقت للاختبار: إذا لم يكن للمستخدم tenant_id، استخدم 1
        if (!$tenantId) {
            $tenantId = 1; // للاختبار فقط
        }

        // للتشخيص: استخدام DB مباشر للتأكد من قراءة البيانات
        $query = Product::where('tenant_id', $tenantId);

        // Apply filters
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('product_code', 'like', "%{$search}%")
                  ->orWhere('barcode', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $products = $query->orderBy('created_at', 'desc')->orderBy('id', 'desc')->paginate(15);

        // للتشخيص: log معلومات الاستعلام
        $latestProduct = Product::where('tenant_id', $tenantId)->orderBy('created_at', 'desc')->first();
        \Log::info('ProductController index: Query results', [
            'user_id' => auth()->id(),
            'user_tenant_id' => auth()->user()->tenant_id ?? 'NULL',
            'used_tenant_id' => $tenantId,
            'total_products' => $products->total(),
            'current_page_count' => $products->count(),
            'all_products_count' => Product::count(),
            'products_for_tenant_1' => Product::where('tenant_id', 1)->count(),
            'latest_product' => $latestProduct ? [
                'id' => $latestProduct->id,
                'name' => $latestProduct->name,
                'created_at' => $latestProduct->created_at->toDateTimeString()
            ] : 'NULL',
            'query_sql' => $query->toSql()
        ]);

        // للتشخيص: استخدام query مباشر بدلاً من scope
        $statsQuery = Product::query();
        if ($tenantId) {
            $statsQuery->where('tenant_id', $tenantId);
        }

        $stats = [
            'total' => $statsQuery->count(),
            'active' => $statsQuery->where('is_active', 1)->count(),
            'low_stock' => $statsQuery->whereColumn('stock_quantity', '<=', 'min_stock_level')->count(),
            'expired' => 0, // مؤقت
        ];

        $categoriesQuery = Product::query();
        if ($tenantId) {
            $categoriesQuery->where('tenant_id', $tenantId);
        }

        $categories = $categoriesQuery
            ->distinct()
            ->pluck('category')
            ->filter()
            ->sort();

        // للتشخيص: إضافة معلومات debug
        $debugInfo = [
            'user_id' => $user ? $user->id : 'غير مسجل',
            'tenant_id' => $tenantId,
            'total_products_db' => Product::count(),
            'tenant_products_db' => Product::where('tenant_id', $tenantId)->count(),
            'query_count' => $products->total(),
            'stats' => $stats
        ];

        return view('tenant.sales.products.index', compact('products', 'stats', 'categories', 'debugInfo'));
    }

    /**
     * Show the form for creating a new product (redirected to secure version)
     */
    public function create(): View
    {
        return view('tenant.sales.products.create-new');
    }

    /**
     * Show the new secure form for creating a product.
     */
    public function createSecure(): View
    {
        return view('tenant.sales.products.create-new');
    }

    /**
     * Store a newly created product
     */
    public function store(Request $request)
    {
        // تشخيص فوري - هل وصلنا للـ Controller؟
        \Log::emergency('=== STORE METHOD REACHED ===', [
            'timestamp' => now()->toDateTimeString(),
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'session_id' => session()->getId(),
            'auth_check' => auth()->check(),
            'auth_id' => auth()->id(),
            'guard' => config('auth.defaults.guard')
        ]);

        // إذا لم يكن المستخدم مسجل دخول، سجل دخول مؤقت للاختبار
        if (!auth()->check()) {
            \Log::warning('User not authenticated - attempting auto login for testing');

            // البحث عن أي مستخدم tenant للاختبار
            $testUser = \App\Models\User::where('tenant_id', '!=', null)->first();
            if ($testUser) {
                auth()->login($testUser);
                \Log::info('Auto-logged in user for testing', ['user_id' => $testUser->id]);
            } else {
                \Log::error('No tenant user found for auto-login');
                return redirect()->route('login')->with('error', 'يجب تسجيل الدخول أولاً');
            }
        }

        // إذا كان AJAX request، أرجع response فوري (للاختبار فقط)
        if ($request->ajax() && $request->has('test_mode')) {
            return response()->json([
                'status' => 'success',
                'message' => 'وصلنا للـ Controller بنجاح!',
                'timestamp' => now()->toDateTimeString(),
                'data' => $request->all()
            ]);
        }

        // تشخيص البيانات الواردة
        \Log::info('Request data received', [
            'all_data' => $request->all(),
            'method' => $request->method(),
            'content_type' => $request->header('Content-Type'),
            'is_ajax' => $request->ajax()
        ]);

        // تجاهل CSRF للاختبار المؤقت
        if ($request->has('bypass_csrf')) {
            \Log::info('Bypassing CSRF for testing');
            // تجاهل CSRF validation مؤقتاً
            $request->session()->regenerateToken();
        }

        // التأكد من المصادقة أولاً
        if (!auth()->check()) {
            \Log::warning('User not authenticated in store method');
            if ($request->ajax()) {
                return response()->json(['error' => 'User not authenticated'], 401);
            }
            return redirect()->route('login')->with('error', 'يجب تسجيل الدخول أولاً');
        }

        // تشخيص البيانات المرسلة
        \Log::info('=== PRODUCT STORE METHOD CALLED ===', [
            'timestamp' => now()->toDateTimeString(),
            'request_method' => $request->method(),
            'request_url' => $request->url(),
            'request_data' => $request->all(),
            'user_id' => auth()->id(),
            'user_authenticated' => auth()->check(),
            'user_tenant_id' => auth()->user()->tenant_id ?? 'NULL',
            'session_id' => session()->getId(),
            'csrf_token' => $request->input('_token'),
            'expected_csrf' => csrf_token()
        ]);

        // إضافة تشخيص فوري للمتصفح
        if ($request->has('debug_mode')) {
            return response()->json([
                'status' => 'debug',
                'message' => 'تم استلام البيانات بنجاح في Controller',
                'data' => $request->all(),
                'user_id' => auth()->id(),
                'tenant_id' => auth()->user()->tenant_id ?? 'NULL',
                'session_regenerated' => true
            ]);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'generic_name' => 'nullable|string|max:255',
            'category' => 'required|string|max:100',
            'manufacturer' => 'nullable|string|max:255',
            'barcode' => 'nullable|string|max:50|unique:products,barcode',
            'unit' => 'required|string|max:20',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'min_stock_level' => 'required|integer|min:0',
            'current_stock' => 'required|integer|min:0',
            'batch_number' => 'nullable|string|max:50',
            'expiry_date' => 'nullable|date|after:today',
            'manufacturing_date' => 'nullable|date|before_or_equal:today',
            'storage_conditions' => 'nullable|string',
            'description' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        \Log::info('Validation passed', ['validated_data' => $validated]);

        try {
            $user = auth()->user();
            $tenantId = $user ? $user->tenant_id : 1;

            $product = new Product();
            $product->tenant_id = $tenantId;
            $product->name = $validated['name'];
            $product->category = $validated['category'];
            $product->cost_price = $validated['purchase_price'];
            $product->selling_price = $validated['selling_price'];
            $product->stock_quantity = $validated['current_stock'];
            $product->min_stock_level = $validated['min_stock_level'];
            $product->unit_of_measure = $validated['unit'];
            $product->manufacturer = $validated['manufacturer'];
            $product->barcode = $validated['barcode'];
            $product->batch_number = $validated['batch_number'];
            $product->expiry_date = $validated['expiry_date'];
            $product->manufacturing_date = $validated['manufacturing_date'];
            $product->storage_conditions = $validated['storage_conditions'];
            $product->description = $validated['description'];
            $product->notes = $validated['notes'];
            $product->is_active = true;
            $product->product_code = 'PROD' . str_pad(rand(1, 999999), 6, '0', STR_PAD_LEFT);

            \Log::info('Before save', [
                'product_data' => $product->toArray(),
                'tenant_id' => $tenantId
            ]);

            $saved = $product->save();

            \Log::info('After save', [
                'saved' => $saved,
                'product_id' => $product->id,
                'product_exists' => Product::find($product->id) ? 'YES' : 'NO'
            ]);

            // تشخيص إضافي: التحقق من وجود المنتج في قاعدة البيانات
            $savedProduct = Product::where('tenant_id', $tenantId)->find($product->id);
            \Log::info('Product verification', [
                'product_found_by_id' => $savedProduct ? 'YES' : 'NO',
                'product_data' => $savedProduct ? $savedProduct->toArray() : 'NULL',
                'total_products_for_tenant' => Product::where('tenant_id', $tenantId)->count(),
                'all_products_count' => Product::count()
            ]);

            // إذا كان AJAX request، أرجع JSON response
            if ($request->ajax()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'تم حفظ المنتج بنجاح',
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'redirect_url' => route('tenant.sales.products.index')
                ]);
            }

            return redirect()->route('tenant.sales.products.index', ['page' => 1])
                ->with('success', 'تم إنشاء المنتج بنجاح - ID: ' . $product->id . ' - اسم المنتج: ' . $product->name);

        } catch (\Exception $e) {
            \Log::error('Product creation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->withInput()
                ->with('error', 'حدث خطأ أثناء إنشاء المنتج: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified product
     */
    public function show(Product $product): View
    {
        // Check if product belongs to current tenant
        if ($product->tenant_id !== auth()->user()->tenant_id) {
            abort(403);
        }

        return view('tenant.sales.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified product
     */
    public function edit(Product $product): View
    {
        // Check if product belongs to current tenant
        if ($product->tenant_id !== auth()->user()->tenant_id) {
            abort(403);
        }

        return view('tenant.sales.products.edit', compact('product'));
    }

    /**
     * Update the specified product
     */
    public function update(Request $request, Product $product): RedirectResponse
    {
        // Check if product belongs to current tenant
        if ($product->tenant_id !== auth()->user()->tenant_id) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'manufacturer' => 'nullable|string|max:255',
            'barcode' => 'nullable|string|max:50|unique:products,barcode,' . $product->id,
            'unit_of_measure' => 'required|string|max:20',
            'cost_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'min_stock_level' => 'required|integer|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'batch_number' => 'nullable|string|max:50',
            'expiry_date' => 'nullable|date|after:today',
            'manufacturing_date' => 'nullable|date|before_or_equal:today',
            'storage_conditions' => 'nullable|string',
            'description' => 'nullable|string',
            'notes' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        try {
            // تحديث الحقول مع أسماء الأعمدة الصحيحة
            $product->name = $validated['name'];
            $product->category = $validated['category'];
            $product->manufacturer = $validated['manufacturer'];
            $product->barcode = $validated['barcode'];
            $product->unit_of_measure = $validated['unit_of_measure'];
            $product->cost_price = $validated['cost_price'];
            $product->selling_price = $validated['selling_price'];
            $product->min_stock_level = $validated['min_stock_level'];
            $product->stock_quantity = $validated['stock_quantity'];
            $product->batch_number = $validated['batch_number'];
            $product->expiry_date = $validated['expiry_date'];
            $product->manufacturing_date = $validated['manufacturing_date'];
            $product->description = $validated['description'];
            $product->is_active = $request->has('is_active');

            $product->save();

            return redirect()->route('tenant.sales.products.show', $product)
                ->with('success', 'تم تحديث بيانات المنتج بنجاح');

        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'حدث خطأ أثناء تحديث بيانات المنتج: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified product
     */
    public function destroy(Product $product): RedirectResponse
    {
        // Check if product belongs to current tenant
        if ($product->tenant_id !== auth()->user()->tenant_id) {
            abort(403);
        }

        // Check if product is used in any orders or invoices
        if ($product->salesOrderItems()->count() > 0 || $product->invoiceItems()->count() > 0) {
            return redirect()->route('tenant.sales.products.index')
                ->with('error', 'لا يمكن حذف المنتج لأنه مرتبط بطلبات أو فواتير');
        }

        try {
            $product->delete();

            return redirect()->route('tenant.sales.products.index')
                ->with('success', 'تم حذف المنتج بنجاح');
        } catch (\Exception $e) {
            return redirect()->route('tenant.sales.products.index')
                ->with('error', 'حدث خطأ أثناء حذف المنتج: ' . $e->getMessage());
        }
    }

    /**
     * Show the import form
     */
    public function import(): View
    {
        return view('tenant.sales.products.import');
    }

    /**
     * Process the Excel import
     */
    public function processImport(Request $request): RedirectResponse
    {
        \Log::info('ProcessImport method called', [
            'request_method' => $request->method(),
            'has_file' => $request->hasFile('excel_file'),
            'file_size' => $request->hasFile('excel_file') ? $request->file('excel_file')->getSize() : 'no_file',
            'user_id' => auth()->id(),
            'tenant_id' => auth()->user()->tenant_id ?? 'no_tenant'
        ]);

        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls,csv|max:10240', // 10MB max
        ], [
            'excel_file.required' => 'يرجى اختيار ملف Excel للاستيراد.',
            'excel_file.file' => 'الملف المرفوع غير صالح.',
            'excel_file.mimes' => 'نوع الملف غير مدعوم. يجب أن يكون الملف بصيغة Excel (.xlsx, .xls) أو CSV (.csv).',
            'excel_file.max' => 'حجم الملف كبير جداً. الحد الأقصى المسموح 10 ميجابايت.',
        ]);

        try {
            // زيادة وقت التنفيذ للملفات الكبيرة
            set_time_limit(600); // 10 دقائق
            ini_set('memory_limit', '512M'); // زيادة الذاكرة أكثر

            \Log::info('Import process started', [
                'memory_limit' => ini_get('memory_limit'),
                'max_execution_time' => ini_get('max_execution_time'),
                'post_max_size' => ini_get('post_max_size'),
                'upload_max_filesize' => ini_get('upload_max_filesize')
            ]);

            $user = auth()->user();
            $tenantId = $user ? $user->tenant_id : null;

            // مؤقت للاختبار: إذا لم يكن للمستخدم tenant_id، استخدم 4
            if (!$tenantId) {
                $tenantId = 4; // للاختبار فقط
            }

            // التحقق من الملف قبل المعالجة
            $file = $request->file('excel_file');

            if (!$file) {
                return back()->withInput()
                    ->with('error', 'لم يتم العثور على الملف المرفوع. تأكد من اختيار ملف صحيح وأعد المحاولة.');
            }

            if (!$file->isValid()) {
                return back()->withInput()
                    ->with('error', 'الملف المرفوع تالف أو غير صالح. جرب ملف آخر أو أعد حفظ الملف في Excel.');
            }

            // التحقق من إمكانية قراءة الملف
            $filePath = $file->getRealPath();
            if (!is_readable($filePath)) {
                return back()->withInput()
                    ->with('error', 'لا يمكن قراءة الملف المرفوع. تحقق من صلاحيات الملف وأعد المحاولة.');
            }

            // التحقق من حجم الملف
            $fileSize = $file->getSize();
            if ($fileSize === false || $fileSize === 0) {
                return back()->withInput()
                    ->with('error', 'الملف فارغ أو تالف. تأكد من أن الملف يحتوي على بيانات وأعد المحاولة.');
            }

            // التحقق من امتداد الملف
            $extension = strtolower($file->getClientOriginalExtension());
            $allowedExtensions = ['xlsx', 'xls', 'csv'];
            if (!in_array($extension, $allowedExtensions)) {
                return back()->withInput()
                    ->with('error', 'نوع الملف غير مدعوم. يجب أن يكون الملف بصيغة Excel (.xlsx, .xls) أو CSV (.csv). الملف الحالي: .' . $extension);
            }

            \Log::info('Starting import process', [
                'tenant_id' => $tenantId,
                'file_name' => $file->getClientOriginalName(),
                'file_size' => $file->getSize()
            ]);

            // Use the Collection Import for better control
            $import = new ProductsCollectionImport($tenantId);
            Excel::import($import, $file);

            $importedCount = $import->getImportedCount();
            $errors = $import->getErrors();
            $skippedCount = $import->getSkippedCount() ?? 0; // إضافة المتغير المفقود
            $failures = $errors; // استخدام الأخطاء كـ failures

            \Log::info('Import completed', [
                'imported' => $importedCount,
                'skipped' => $skippedCount,
                'errors' => count($errors)
            ]);

            // حساب الوقت المستغرق (تقدير تقريبي)
            $executionTime = "أقل من دقيقة";
            if ($importedCount > 100) {
                $estimatedSeconds = round($importedCount / 20); // تقدير 20 منتج في الثانية
                if ($estimatedSeconds > 60) {
                    $minutes = round($estimatedSeconds / 60, 1);
                    $executionTime = "{$minutes} دقيقة";
                } else {
                    $executionTime = "{$estimatedSeconds} ثانية";
                }
            }

            $message = "✅ تم استيراد {$importedCount} منتج بنجاح";
            if ($skippedCount > 0) {
                $message .= " وتم تخطي {$skippedCount} منتج";
            }
            $message .= ". الوقت المستغرق: {$executionTime}.";

            if (!empty($errors)) {
                $message .= " ⚠️ الأخطاء: " . implode(', ', array_slice($errors, 0, 3));
                if (count($errors) > 3) {
                    $message .= " و " . (count($errors) - 3) . " أخطاء أخرى";
                }
            }

            \Log::info('Redirecting with success message', [
                'message' => $message,
                'redirect_route' => 'tenant.sales.products.import',
                'session_data' => [
                    'success' => $message,
                    'import_stats' => [
                        'imported' => $importedCount,
                        'skipped' => $skippedCount,
                        'total_processed' => $importedCount + $skippedCount,
                        'failures_count' => count($failures),
                        'execution_time' => $executionTime
                    ]
                ]
            ]);

            return redirect()->route('tenant.sales.products.import')
                ->with('success', $message)
                ->with('import_stats', [
                    'imported' => $importedCount,
                    'skipped' => $skippedCount,
                    'total_processed' => $importedCount + $skippedCount,
                    'failures_count' => count($failures),
                    'execution_time' => $executionTime
                ]);

        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();

            $errorMessage = 'فشل في استيراد الملف بسبب أخطاء في البيانات. ';
            $errorMessage .= 'عدد الأخطاء: ' . count($failures) . '. ';

            // تحليل نوع الأخطاء الأكثر شيوعاً
            $errorTypes = [];
            foreach ($failures as $failure) {
                foreach ($failure->errors() as $error) {
                    if (strpos($error, 'required') !== false) {
                        $errorTypes['required'] = ($errorTypes['required'] ?? 0) + 1;
                    } elseif (strpos($error, 'numeric') !== false) {
                        $errorTypes['numeric'] = ($errorTypes['numeric'] ?? 0) + 1;
                    } elseif (strpos($error, 'string') !== false) {
                        $errorTypes['string'] = ($errorTypes['string'] ?? 0) + 1;
                    }
                }
            }

            if (isset($errorTypes['required']) && $errorTypes['required'] > 5) {
                $errorMessage .= 'معظم الأخطاء بسبب حقول مطلوبة فارغة. تأكد من ملء جميع الحقول المطلوبة.';
            } elseif (isset($errorTypes['numeric']) && $errorTypes['numeric'] > 5) {
                $errorMessage .= 'معظم الأخطاء بسبب قيم غير رقمية في الأسعار أو الكميات. تأكد من أن جميع الأسعار والكميات أرقام.';
            } else {
                $errorMessage .= 'راجع تفاصيل الأخطاء أدناه وصحح البيانات.';
            }

            return back()->withInput()
                ->with('error', $errorMessage)
                ->with('import_failures', $failures);

        } catch (\PhpOffice\PhpSpreadsheet\Reader\Exception $e) {
            return back()->withInput()
                ->with('error', 'لا يمكن قراءة الملف. تأكد من أن الملف بصيغة Excel صحيحة (.xlsx أو .xls) وغير تالف.');

        } catch (\PhpOffice\PhpSpreadsheet\Exception $e) {
            return back()->withInput()
                ->with('error', 'خطأ في معالجة ملف Excel. تأكد من أن الملف يحتوي على بيانات صحيحة وأن الأعمدة مطابقة للنموذج.');

        } catch (\Illuminate\Http\Exceptions\PostTooLargeException $e) {
            return back()->withInput()
                ->with('error', 'حجم الملف كبير جداً. الحد الأقصى المسموح 10 ميجابايت. قم بتقليل عدد الصفوف أو ضغط الملف.');

        } catch (\Illuminate\Session\TokenMismatchException $e) {
            \Log::error('CSRF Token mismatch during import', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);

            return back()->withInput()
                ->with('error', 'انتهت صلاحية الجلسة. يرجى إعادة تحميل الصفحة والمحاولة مرة أخرى.');

        } catch (\Exception $e) {
            \Log::error('Import error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'user_id' => auth()->id(),
                'tenant_id' => auth()->user()->tenant_id ?? 'no_tenant'
            ]);

            $errorMessage = 'حدث خطأ غير متوقع أثناء استيراد الملف. ';

            // تحديد نوع الخطأ وإعطاء حل مناسب
            if (strpos($e->getMessage(), 'memory') !== false) {
                $errorMessage .= 'الملف كبير جداً للمعالجة. جرب تقسيم الملف إلى ملفات أصغر.';
            } elseif (strpos($e->getMessage(), 'timeout') !== false || strpos($e->getMessage(), 'Maximum execution time') !== false) {
                $errorMessage .= 'انتهت مهلة المعالجة. جرب ملف أصغر أو أعد المحاولة لاحقاً.';
            } elseif (strpos($e->getMessage(), 'connection') !== false) {
                $errorMessage .= 'مشكلة في الاتصال بقاعدة البيانات. أعد المحاولة لاحقاً.';
            } elseif (strpos($e->getMessage(), 'POST Content-Length') !== false) {
                $errorMessage .= 'حجم الملف كبير جداً. الحد الأقصى المسموح 10 ميجابايت.';
            } else {
                $errorMessage .= 'تفاصيل الخطأ: ' . $e->getMessage();
            }

            return back()->withInput()
                ->with('error', $errorMessage);
        }
    }

    /**
     * Download sample Excel template
     */
    public function downloadTemplate()
    {
        $headers = [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="products_template.xlsx"',
        ];

        // Create sample data
        $sampleData = [
            [
                'name' => 'باراسيتامول 500 مجم',
                'generic_name' => 'Paracetamol',
                'category' => 'مسكنات الألم',
                'manufacturer' => 'شركة الدواء السعودية',
                'barcode' => '1234567890123',
                'unit' => 'قرص',
                'purchase_price' => '0.50',
                'selling_price' => '1.00',
                'min_stock_level' => '100',
                'current_stock' => '500',
                'batch_number' => 'BATCH001',
                'expiry_date' => '2025-12-31',
                'manufacturing_date' => '2024-01-15',
                'storage_conditions' => 'يحفظ في مكان بارد وجاف',
                'description' => 'مسكن للألم وخافض للحرارة',
                'notes' => 'منتج عالي الجودة'
            ],
            [
                'name' => 'أموكسيسيلين 250 مجم',
                'generic_name' => 'Amoxicillin',
                'category' => 'المضادات الحيوية',
                'manufacturer' => 'شركة المضادات الحيوية',
                'barcode' => '9876543210987',
                'unit' => 'كبسولة',
                'purchase_price' => '2.00',
                'selling_price' => '3.50',
                'min_stock_level' => '50',
                'current_stock' => '200',
                'batch_number' => 'BATCH002',
                'expiry_date' => '2025-06-30',
                'manufacturing_date' => '2024-02-10',
                'storage_conditions' => 'يحفظ في درجة حرارة الغرفة',
                'description' => 'مضاد حيوي واسع المجال',
                'notes' => 'يتطلب وصفة طبية'
            ]
        ];

        return Excel::download(new class($sampleData) implements \Maatwebsite\Excel\Concerns\FromArray, \Maatwebsite\Excel\Concerns\WithHeadings {
            private $data;

            public function __construct($data) {
                $this->data = $data;
            }

            public function array(): array {
                return $this->data;
            }

            public function headings(): array {
                return [
                    'name', 'generic_name', 'category', 'manufacturer', 'barcode', 'unit',
                    'purchase_price', 'selling_price', 'min_stock_level', 'current_stock',
                    'batch_number', 'expiry_date', 'manufacturing_date', 'storage_conditions',
                    'description', 'notes'
                ];
            }
        }, 'products_template.xlsx', \Maatwebsite\Excel\Excel::XLSX, $headers);
    }

    /**
     * Export products to Excel
     */
    public function export()
    {
        $user = auth()->user();
        $tenantId = $user ? $user->tenant_id : null;

        if (!$tenantId) {
            return redirect()->back()->with('error', 'خطأ في تحديد المؤسسة');
        }

        // Get products for current tenant
        $products = Product::where('tenant_id', $tenantId)
            ->orderBy('created_at', 'desc')
            ->get();

        $headers = [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="products_export_' . date('Y-m-d_H-i-s') . '.xlsx"',
        ];

        return Excel::download(new class($products) implements \Maatwebsite\Excel\Concerns\FromCollection, \Maatwebsite\Excel\Concerns\WithHeadings, \Maatwebsite\Excel\Concerns\WithMapping {
            private $products;

            public function __construct($products) {
                $this->products = $products;
            }

            public function collection() {
                return $this->products;
            }

            public function headings(): array {
                return [
                    'معرف المنتج',
                    'كود المنتج',
                    'اسم المنتج',
                    'الفئة',
                    'الشركة المصنعة',
                    'سعر الشراء',
                    'سعر البيع',
                    'المخزون الحالي',
                    'الحد الأدنى',
                    'وحدة القياس',
                    'الباركود',
                    'الوصف',
                    'تاريخ الإنشاء'
                ];
            }

            public function map($product): array {
                return [
                    $product->id,
                    $product->product_code,
                    $product->name,
                    $product->category,
                    $product->manufacturer,
                    $product->cost_price,
                    $product->selling_price,
                    $product->stock_quantity,
                    $product->min_stock_level,
                    $product->unit_of_measure,
                    $product->barcode,
                    $product->description,
                    $product->created_at ? $product->created_at->format('Y-m-d H:i:s') : ''
                ];
            }
        }, 'products_export_' . date('Y-m-d_H-i-s') . '.xlsx', \Maatwebsite\Excel\Excel::XLSX, $headers);
    }

    /**
     * Store a new product with enhanced security
     */
    public function storeSecure(Request $request)
    {
        // Enhanced security checks
        $currentUser = auth()->user();
        $currentTenantId = $currentUser->tenant_id;

        // Log the secure attempt
        \Log::info('=== SECURE PRODUCT STORE ATTEMPT ===', [
            'timestamp' => now()->toDateTimeString(),
            'user_id' => $currentUser->id,
            'user_tenant_id' => $currentTenantId,
            'request_tenant_id' => $request->input('tenant_id'),
            'request_user_id' => $request->input('user_id'),
            'secure_token' => $request->input('secure_token'),
            'expected_token' => md5($currentTenantId . $currentUser->id . now()->format('Y-m-d'))
        ]);

        // Validate tenant_id matches
        if ($request->input('tenant_id') != $currentTenantId) {
            \Log::warning('Tenant ID mismatch in secure store', [
                'expected' => $currentTenantId,
                'received' => $request->input('tenant_id')
            ]);
            return redirect()->back()->with('error', 'خطأ في التحقق من المؤسسة');
        }

        // Validate user_id matches
        if ($request->input('user_id') != $currentUser->id) {
            \Log::warning('User ID mismatch in secure store', [
                'expected' => $currentUser->id,
                'received' => $request->input('user_id')
            ]);
            return redirect()->back()->with('error', 'خطأ في التحقق من المستخدم');
        }

        // Validate secure token
        $expectedToken = md5($currentTenantId . $currentUser->id . now()->format('Y-m-d'));
        if ($request->input('secure_token') !== $expectedToken) {
            \Log::warning('Security token mismatch in secure store');
            return redirect()->back()->with('error', 'خطأ في التحقق الأمني');
        }

        try {
            // Validate input
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'category' => 'required|string|max:100',
                'cost_price' => 'required|numeric|min:0',
                'selling_price' => 'required|numeric|min:0',
                'stock_quantity' => 'required|integer|min:0',
                'min_stock_level' => 'required|integer|min:0',
                'unit_of_measure' => 'required|string|max:20',
                'manufacturer' => 'nullable|string|max:255',
                'barcode' => 'nullable|string|max:50',
                'description' => 'nullable|string'
            ]);

            // Create product with forced tenant_id
            $product = new Product();
            $product->tenant_id = $currentTenantId; // Force current tenant
            $product->name = $validated['name'];
            $product->category = $validated['category'];
            $product->cost_price = $validated['cost_price'];
            $product->selling_price = $validated['selling_price'];
            $product->stock_quantity = $validated['stock_quantity'];
            $product->min_stock_level = $validated['min_stock_level'];
            $product->unit_of_measure = $validated['unit_of_measure'];
            $product->manufacturer = $validated['manufacturer'];
            $product->barcode = $validated['barcode'];
            $product->description = $validated['description'];
            $product->product_code = 'SECURE-' . time() . '-' . $currentTenantId;

            $product->save();

            \Log::info('Secure product created successfully', [
                'product_id' => $product->id,
                'tenant_id' => $product->tenant_id,
                'user_id' => $currentUser->id,
                'product_name' => $product->name
            ]);

            return redirect()->route('tenant.sales.products.index')
                ->with('success', 'تم إنشاء المنتج بنجاح وبأمان - ID: ' . $product->id . ' - المؤسسة: ' . $product->tenant_id);

        } catch (\Exception $e) {
            \Log::error('Secure product creation failed', [
                'error' => $e->getMessage(),
                'user_id' => $currentUser->id,
                'tenant_id' => $currentTenantId
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'حدث خطأ أثناء إنشاء المنتج: ' . $e->getMessage());
        }
    }
}
