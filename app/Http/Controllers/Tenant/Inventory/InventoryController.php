<?php

namespace App\Http\Controllers\Tenant\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\Warehouse;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\InventoryTemplateExport;
use App\Exports\InventoryExport;
use App\Imports\InventoryImport;

class InventoryController extends Controller
{
    /**
     * Display a listing of inventory items
     */
    public function index(Request $request): View
    {
        $user = Auth::user();
        $tenantId = $user->tenant_id;

        if (!$tenantId) {
            abort(403, 'No tenant access');
        }

        $query = Inventory::with(['product', 'warehouse', 'location'])
            ->where('tenant_id', $tenantId);

        // Apply filters
        if ($request->filled('warehouse_id')) {
            $query->where('warehouse_id', $request->warehouse_id);
        }

        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('location')) {
            $query->where('location_code', 'like', '%' . $request->location . '%');
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('product', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        $inventory = $query->orderBy('warehouse_id')
            ->orderBy('product_id')
            ->paginate(20);

        // Get filter options
        $warehouses = Warehouse::where('tenant_id', $tenantId)->orderBy('name')->get();
        $products = Product::where('tenant_id', $tenantId)->orderBy('name')->get();

        // Get statistics
        $stats = [
            'total_items' => Inventory::where('tenant_id', $tenantId)->count(),
            'total_quantity' => Inventory::where('tenant_id', $tenantId)->sum('quantity'),
            'available_quantity' => Inventory::where('tenant_id', $tenantId)->sum('available_quantity'),
            'reserved_quantity' => Inventory::where('tenant_id', $tenantId)->sum('reserved_quantity'),
            'low_stock_items' => Inventory::where('tenant_id', $tenantId)
                ->whereRaw('available_quantity <= (SELECT min_stock_level FROM products WHERE products.id = inventory.product_id)')
                ->count(),
            'out_of_stock' => Inventory::where('tenant_id', $tenantId)->where('available_quantity', '<=', 0)->count(),
        ];

        return view('tenant.inventory.index', compact(
            'inventory', 'warehouses', 'products', 'stats'
        ));
    }

    /**
     * Show the form for creating a new inventory item
     */
    public function create(): View
    {
        $user = Auth::user();
        $tenantId = $user->tenant_id;

        if (!$tenantId) {
            abort(403, 'No tenant access');
        }

        $warehouses = Warehouse::where('tenant_id', $tenantId)->orderBy('name')->get();
        $products = Product::where('tenant_id', $tenantId)->orderBy('name')->get();

        // Log for debugging
        \Log::info('Inventory create page loaded', [
            'tenant_id' => $tenantId,
            'warehouses_count' => $warehouses->count(),
            'products_count' => $products->count()
        ]);

        // If no products exist, create some sample products
        if ($products->count() === 0) {
            $this->createSampleProducts($tenantId);
            $products = Product::where('tenant_id', $tenantId)->orderBy('name')->get();
        }

        // If no warehouses exist, create a sample warehouse
        if ($warehouses->count() === 0) {
            $this->createSampleWarehouse($tenantId);
            $warehouses = Warehouse::where('tenant_id', $tenantId)->orderBy('name')->get();
        }

        return view('tenant.inventory.create', compact('warehouses', 'products'));
    }

    /**
     * Create sample products for testing
     */
    private function createSampleProducts($tenantId)
    {
        $sampleProducts = [
            [
                'tenant_id' => $tenantId,
                'name' => 'منتج تجريبي 1',
                'code' => 'PROD001',
                'category' => 'أدوية',
                'unit' => 'قطعة',
                'description' => 'منتج تجريبي للاختبار',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tenant_id' => $tenantId,
                'name' => 'منتج تجريبي 2',
                'code' => 'PROD002',
                'category' => 'مستلزمات',
                'unit' => 'علبة',
                'description' => 'منتج تجريبي آخر للاختبار',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        foreach ($sampleProducts as $productData) {
            Product::create($productData);
        }
    }

    /**
     * Create sample warehouse for testing
     */
    private function createSampleWarehouse($tenantId)
    {
        Warehouse::create([
            'tenant_id' => $tenantId,
            'name' => 'المستودع الرئيسي',
            'code' => 'WH001',
            'location' => 'المكتب الرئيسي',
            'type' => 'main',
            'status' => 'active',
            'description' => 'المستودع الرئيسي للشركة',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Store a newly created inventory item
     */
    public function store(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $tenantId = $user->tenant_id;

        if (!$tenantId) {
            abort(403, 'No tenant access');
        }

        // تنظيف البيانات أولاً - إزالة الصفوف الفارغة
        $products = collect($request->products ?? [])->filter(function ($product) {
            return !empty($product['product_id']) && !empty($product['quantity']) && $product['quantity'] > 0;
        })->values()->toArray();

        // التحقق من وجود منتجات صحيحة
        if (empty($products)) {
            return back()->withErrors(['products' => 'يرجى إضافة منتج واحد على الأقل مع كمية صحيحة'])->withInput();
        }

        // تحديث البيانات المنظفة
        $request->merge(['products' => $products]);

        $request->validate([
            'warehouse_id' => 'required|exists:warehouses,id',
            'default_status' => 'required|in:active,quarantine,damaged,expired',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|numeric|min:0.001',
            'products.*.cost_price' => 'nullable|numeric|min:0',
            'products.*.location_code' => 'nullable|string|max:50',
            'products.*.batch_number' => 'nullable|string|max:100',
            'products.*.expiry_date' => 'nullable|date',
            'products.*.status' => 'required|in:active,quarantine,damaged,expired',
        ]);

        $createdItems = [];
        $updatedItems = [];
        $totalQuantity = 0;
        $totalValue = 0;

        // Process each product
        foreach ($request->products as $productData) {
            if (empty($productData['product_id']) || empty($productData['quantity'])) {
                continue; // Skip empty rows
            }

            $quantity = $productData['quantity'];
            $costPrice = $productData['cost_price'] ?? 0;
            $totalQuantity += $quantity;
            $totalValue += $quantity * $costPrice;

            // Check if inventory item already exists (same warehouse, product, and batch)
            $existingInventory = Inventory::where('tenant_id', $tenantId)
                ->where('warehouse_id', $request->warehouse_id)
                ->where('product_id', $productData['product_id'])
                ->where('batch_number', $productData['batch_number'] ?? null)
                ->first();

            if ($existingInventory) {
                // Update existing inventory
                $existingInventory->update([
                    'quantity' => $existingInventory->quantity + $quantity,
                    'available_quantity' => $existingInventory->available_quantity + $quantity,
                    'cost_price' => $costPrice > 0 ? $costPrice : $existingInventory->cost_price,
                    'location_code' => $productData['location_code'] ?? $existingInventory->location_code,
                    'expiry_date' => $productData['expiry_date'] ?? $existingInventory->expiry_date,
                    'status' => $productData['status'],
                ]);
                $updatedItems[] = $existingInventory;
            } else {
                // Create new inventory item
                $newInventory = Inventory::create([
                    'tenant_id' => $tenantId,
                    'warehouse_id' => $request->warehouse_id,
                    'product_id' => $productData['product_id'],
                    'quantity' => $quantity,
                    'available_quantity' => $quantity,
                    'reserved_quantity' => 0,
                    'cost_price' => $costPrice,
                    'location_code' => $productData['location_code'],
                    'batch_number' => $productData['batch_number'],
                    'expiry_date' => $productData['expiry_date'],
                    'status' => $productData['status'],
                ]);
                $createdItems[] = $newInventory;
            }
        }

        $newProducts = count($createdItems);
        $updatedProducts = count($updatedItems);

        $message = "تم إضافة المخزون بنجاح: ";
        if ($newProducts > 0) {
            $message .= "{$newProducts} منتج جديد";
        }
        if ($updatedProducts > 0) {
            if ($newProducts > 0) $message .= "، ";
            $message .= "{$updatedProducts} منتج محدث";
        }
        $message .= " (إجمالي الكمية: " . number_format($totalQuantity, 0) . "، القيمة: " . number_format($totalValue, 2) . " د.ع)";

        return redirect()->route('tenant.inventory.index')
            ->with('success', $message);
    }

    /**
     * Display the specified inventory item
     */
    public function show(Inventory $inventory): View
    {
        $user = Auth::user();

        if ($inventory->tenant_id !== $user->tenant_id) {
            abort(403, 'Unauthorized access');
        }

        $inventory->load(['product', 'warehouse', 'location']);

        return view('tenant.inventory.show', compact('inventory'));
    }

    /**
     * Show the form for editing the specified inventory item
     */
    public function edit(Inventory $inventory): View
    {
        $user = Auth::user();

        if ($inventory->tenant_id !== $user->tenant_id) {
            abort(403, 'Unauthorized access');
        }

        $warehouses = Warehouse::where('tenant_id', $user->tenant_id)->active()->orderBy('name')->get();
        $products = Product::where('tenant_id', $user->tenant_id)->orderBy('name')->get();

        return view('tenant.inventory.edit', compact('inventory', 'warehouses', 'products'));
    }

    /**
     * Update the specified inventory item
     */
    public function update(Request $request, Inventory $inventory): RedirectResponse
    {
        $user = Auth::user();

        if ($inventory->tenant_id !== $user->tenant_id) {
            abort(403, 'Unauthorized access');
        }

        $request->validate([
            'cost_price' => 'nullable|numeric|min:0',
            'location_code' => 'nullable|string|max:50',
            'batch_number' => 'nullable|string|max:100',
            'expiry_date' => 'nullable|date',
            'status' => 'required|in:active,quarantine,damaged,expired',
        ]);

        $inventory->update($request->only([
            'cost_price', 'location_code', 'batch_number', 'expiry_date', 'status'
        ]));

        return redirect()->route('tenant.inventory.index')
            ->with('success', 'تم تحديث المخزون بنجاح');
    }

    /**
     * Download Excel template for inventory import
     */
    public function downloadTemplate()
    {
        try {
            $user = Auth::user();
            $tenantId = $user->tenant_id ?? 1;

            if (!$tenantId) {
                return response()->json(['error' => 'No tenant access'], 403);
            }

            // Create a simple CSV template instead of complex Excel
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="inventory_template.csv"',
            ];

            $csvData = [
                ['كود المنتج', 'اسم المنتج', 'كود المستودع', 'الكمية', 'سعر التكلفة', 'سعر البيع', 'رمز الموقع', 'رقم الدفعة', 'تاريخ الانتهاء', 'الحالة', 'ملاحظات'],
                ['PRD000001', 'منتج تجريبي 1', 'MAIN-004', '100', '25.50', '35.00', 'A-01-01', 'BATCH001', '2025-12-31', 'active', 'منتج تجريبي'],
                ['PRD000002', 'منتج تجريبي 2', 'MAIN-004', '50', '15.75', '22.50', 'A-01-02', 'BATCH002', '2026-06-30', 'active', 'منتج تجريبي آخر'],
                ['PRD000003', 'منتج جديد', '005', '25', '10.00', '15.00', 'B-02-01', 'BATCH003', '2026-12-31', 'active', 'منتج جديد سيتم إنشاؤه تلقائي<|im_start|>'],
            ];

            $callback = function() use ($csvData) {
                $file = fopen('php://output', 'w');
                // Add BOM for UTF-8
                fwrite($file, "\xEF\xBB\xBF");

                foreach ($csvData as $row) {
                    fputcsv($file, $row);
                }
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);

        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Template download error: ' . $e->getMessage());

            // Return a simple error response
            return response()->json([
                'error' => 'حدث خطأ أثناء تحميل القالب: ' . $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    /**
     * Import inventory from Excel file
     */
    public function importExcel(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $tenantId = $user->tenant_id;

        if (!$tenantId) {
            abort(403, 'No tenant access');
        }

        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls,csv|max:10240', // 10MB max
            'skip_duplicates' => 'nullable|boolean',
            'update_existing' => 'nullable|boolean',
        ]);

        try {
            \Log::info('Starting Excel import for tenant: ' . $tenantId);
            \Log::info('File info: ', [
                'name' => $request->file('excel_file')->getClientOriginalName(),
                'size' => $request->file('excel_file')->getSize(),
                'mime' => $request->file('excel_file')->getMimeType()
            ]);

            $import = new InventoryImport(
                $tenantId,
                $request->boolean('skip_duplicates', true),
                $request->boolean('update_existing', false)
            );

            Excel::import($import, $request->file('excel_file'));

            $stats = $import->getStats();
            \Log::info('Import completed with stats: ', $stats);

            // تحقق من وجود نتائج
            $totalProcessed = $stats['created'] + $stats['updated'];

            if ($totalProcessed === 0 && $stats['errors'] === 0) {
                return redirect()->back()
                    ->with('warning', 'لم يتم العثور على بيانات صالحة للاستيراد. يرجى التحقق من تنسيق الملف.')
                    ->withInput();
            }

            if ($stats['errors'] > 0 && $totalProcessed === 0) {
                return redirect()->back()
                    ->with('error', "فشل في استيراد الملف. حدثت {$stats['errors']} أخطاء. يرجى مراجعة تنسيق البيانات.")
                    ->withInput();
            }

            // رسالة نجاح مفصلة
            $message = "✅ تم استيراد الملف بنجاح!\n\n";

            if ($stats['created'] > 0) {
                $message .= "🆕 تم إنشاء {$stats['created']} منتج جديد\n";
            }

            if ($stats['updated'] > 0) {
                $message .= "🔄 تم تحديث {$stats['updated']} سجل مخزون\n";
            }

            if ($stats['skipped'] > 0) {
                $message .= "⏭️ تم تجاهل {$stats['skipped']} عنصر مكرر\n";
            }

            if ($stats['errors'] > 0) {
                $message .= "⚠️ حدثت {$stats['errors']} أخطاء (تم تجاهلها)\n";
            }

            $message .= "\n📊 إجمالي العناصر المعالجة: {$totalProcessed}";

            return redirect()->route('tenant.inventory.create')
                ->with('success', $message);

        } catch (\Exception $e) {
            \Log::error('Excel import failed: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());

            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء استيراد الملف: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Export inventory to Excel
     */
    public function exportExcel(Request $request): Response
    {
        $user = Auth::user();
        $tenantId = $user->tenant_id;

        if (!$tenantId) {
            abort(403, 'No tenant access');
        }

        // Apply same filters as index method
        $query = Inventory::with(['product', 'warehouse'])
            ->where('tenant_id', $tenantId);

        if ($request->filled('warehouse_id')) {
            $query->where('warehouse_id', $request->warehouse_id);
        }

        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $inventoryItems = $query->get();

        return Excel::download(
            new InventoryExport($inventoryItems),
            'inventory_export_' . date('Y-m-d_H-i-s') . '.xlsx'
            );
        }


    /**
     * Diagnostics endpoint: returns counts and sample data
     */
    public function diagnostics(Request $request)
    {
        $user = Auth::user();
        $tenantId = $user->tenant_id;

        $products = Product::where('tenant_id', $tenantId)->orderBy('name')->limit(10)->get(['id','name','code','product_code']);
        $warehouses = Warehouse::where('tenant_id', $tenantId)->orderBy('name')->limit(10)->get(['id','name','code']);

        return response()->json([
            'tenant_id' => $tenantId,
            'products_count' => Product::where('tenant_id', $tenantId)->count(),
            'warehouses_count' => Warehouse::where('tenant_id', $tenantId)->count(),
            'products_sample' => $products,
            'warehouses_sample' => $warehouses,
        ]);
    }

    /**
     * Dry-run import: parse the uploaded file and return parsed rows without saving
     */
    public function importDryRun(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls,csv,txt|max:10240',
        ]);

        try {
            $file = $request->file('excel_file');
            $mime = $file->getMimeType();
            $name = $file->getClientOriginalName();

            // Try to read CSV manually for diagnostics
            $rows = [];
            if (preg_match('/csv|plain/', $mime) || preg_match('/\.csv$/i', $name)) {
                $handle = fopen($file->getRealPath(), 'r');
                // Skip BOM
                $bom = fread($handle, 3);
                if ($bom !== "\xEF\xBB\xBF") {
                    // rewind if no BOM
                    fseek($handle, 0);
                }
                $header = null;
                while (($data = fgetcsv($handle, 0, ',')) !== false) {
                    if ($header === null) {
                        $header = $data;
                        continue;
                    }
                    $rows[] = array_combine($header, $data);
                    if (count($rows) >= 10) break; // limit preview
                }
                fclose($handle);
            }

            return response()->json([
                'status' => 'ok',
                'file' => [
                    'name' => $name,
                    'size' => $file->getSize(),
                    'mime' => $mime,
                ],
                'preview_rows' => $rows,
            ]);

        } catch (\Throwable $e) {
            \Log::error('Import dry run failed: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}

