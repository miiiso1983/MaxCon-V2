<?php

namespace App\Http\Controllers\Tenant\Sales;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Imports\ProductsImport;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
{
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

        $products = $query->orderBy('created_at', 'desc')->paginate(15);

        // للتشخيص: log معلومات الاستعلام
        \Log::info('ProductController index: Query results', [
            'tenant_id' => $tenantId,
            'total_products' => $products->total(),
            'current_page_count' => $products->count(),
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
     * Show the form for creating a new product
     */
    public function create(): View
    {
        return view('tenant.sales.products.create');
    }

    /**
     * Store a newly created product
     */
    public function store(Request $request): RedirectResponse
    {
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

        try {
            $product = new Product();
            $product->tenant_id = auth()->user()->tenant_id;
            $product->product_code = $product->generateProductCode();
            $product->fill($validated);
            $product->is_active = true;
            $product->save();

            return redirect()->route('tenant.sales.products.index')
                ->with('success', 'تم إنشاء المنتج بنجاح');

        } catch (\Exception $e) {
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
            'generic_name' => 'nullable|string|max:255',
            'category' => 'required|string|max:100',
            'manufacturer' => 'nullable|string|max:255',
            'barcode' => 'nullable|string|max:50|unique:products,barcode,' . $product->id,
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
            'is_active' => 'boolean',
        ]);

        try {
            $product->update($validated);

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
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls,csv|max:10240', // 10MB max
        ], [
            'excel_file.required' => 'يرجى اختيار ملف Excel للاستيراد.',
            'excel_file.file' => 'الملف المرفوع غير صالح.',
            'excel_file.mimes' => 'نوع الملف غير مدعوم. يجب أن يكون الملف بصيغة Excel (.xlsx, .xls) أو CSV (.csv).',
            'excel_file.max' => 'حجم الملف كبير جداً. الحد الأقصى المسموح 10 ميجابايت.',
        ]);

        try {
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

            $import = new ProductsImport($tenantId);
            Excel::import($import, $file);

            $importedCount = $import->getImportedCount();
            $skippedCount = $import->getSkippedCount();
            $failures = $import->failures();

            $message = "تم استيراد {$importedCount} منتج بنجاح";
            if ($skippedCount > 0) {
                $message .= " وتم تخطي {$skippedCount} منتج (مكرر أو بيانات ناقصة)";
            }

            if (count($failures) > 0) {
                $message .= ". يوجد " . count($failures) . " خطأ في البيانات";

                // Store failures in session for display
                session()->flash('import_failures', $failures);
            }

            return redirect()->route('tenant.sales.products.index')
                ->with('success', $message);

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

        } catch (\Exception $e) {
            \Log::error('Import error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            $errorMessage = 'حدث خطأ غير متوقع أثناء استيراد الملف. ';

            // تحديد نوع الخطأ وإعطاء حل مناسب
            if (strpos($e->getMessage(), 'memory') !== false) {
                $errorMessage .= 'الملف كبير جداً للمعالجة. جرب تقسيم الملف إلى ملفات أصغر.';
            } elseif (strpos($e->getMessage(), 'timeout') !== false) {
                $errorMessage .= 'انتهت مهلة المعالجة. جرب ملف أصغر أو أعد المحاولة لاحقاً.';
            } elseif (strpos($e->getMessage(), 'connection') !== false) {
                $errorMessage .= 'مشكلة في الاتصال بقاعدة البيانات. أعد المحاولة لاحقاً.';
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
}
