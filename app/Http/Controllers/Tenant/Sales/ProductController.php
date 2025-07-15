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

        if (!$tenantId) {
            abort(403, 'No tenant access');
        }

        $query = Product::forTenant($tenantId);

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
                  ->orWhere('generic_name', 'like', "%{$search}%");
            });
        }

        $products = $query->orderBy('created_at', 'desc')->paginate(15);

        $stats = [
            'total' => Product::forTenant($tenantId)->count(),
            'active' => Product::forTenant($tenantId)->active()->count(),
            'low_stock' => Product::forTenant($tenantId)->lowStock()->count(),
            'expired' => Product::forTenant($tenantId)->expired()->count(),
        ];

        $categories = Product::forTenant($tenantId)
            ->distinct()
            ->pluck('category')
            ->filter()
            ->sort();

        return view('tenant.sales.products.index', compact('products', 'stats', 'categories'));
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
        ]);

        try {
            $import = new ProductsImport(auth()->user()->tenant_id);
            Excel::import($import, $request->file('excel_file'));

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

        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'حدث خطأ أثناء استيراد الملف: ' . $e->getMessage());
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
