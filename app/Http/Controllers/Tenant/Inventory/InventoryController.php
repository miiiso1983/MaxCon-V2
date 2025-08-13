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

        $warehouses = Warehouse::where('tenant_id', $tenantId)->active()->orderBy('name')->get();
        $products = Product::where('tenant_id', $tenantId)->orderBy('name')->get();

        return view('tenant.inventory.create', compact('warehouses', 'products'));
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
                ['كود المنتج', 'كود المستودع', 'الكمية', 'سعر التكلفة', 'رمز الموقع', 'رقم الدفعة', 'تاريخ الانتهاء', 'الحالة', 'ملاحظات'],
                ['PROD001', 'WH001', '100', '25.50', 'A-01-01', 'BATCH001', '2025-12-31', 'active', 'منتج تجريبي'],
                ['PROD002', 'WH001', '50', '15.75', 'A-01-02', 'BATCH002', '2026-06-30', 'active', 'منتج تجريبي آخر'],
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
            $import = new InventoryImport(
                $tenantId,
                $request->boolean('skip_duplicates', true),
                $request->boolean('update_existing', false)
            );

            Excel::import($import, $request->file('excel_file'));

            $stats = $import->getStats();

            $message = "تم استيراد الملف بنجاح! ";
            $message .= "تم إضافة {$stats['created']} عنصر جديد، ";
            $message .= "تم تحديث {$stats['updated']} عنصر، ";
            $message .= "تم تجاهل {$stats['skipped']} عنصر مكرر.";

            return redirect()->route('tenant.inventory.index')
                ->with('success', $message);

        } catch (\Exception $e) {
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
}
