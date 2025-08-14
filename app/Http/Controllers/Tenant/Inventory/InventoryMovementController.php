<?php

namespace App\Http\Controllers\Tenant\Inventory;

use App\Http\Controllers\Controller;
use App\Models\InventoryMovement;
use App\Models\Warehouse;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\InventoryMovementImport;
use Symfony\Component\HttpFoundation\Response;

class InventoryMovementController extends Controller
{
    /**
     * Display a listing of inventory movements
     */
    public function index(Request $request): View
    {
        $user = auth()->user();
        $tenantId = $user->tenant_id;

        if (!$tenantId) {
            abort(403, 'No tenant access');
        }

        $query = InventoryMovement::with(['warehouse', 'product', 'createdBy'])
            ->where('tenant_id', $tenantId);

        // Apply filters
        if ($request->filled('warehouse_id')) {
            $query->where('warehouse_id', $request->warehouse_id);
        }

        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        if ($request->filled('movement_type')) {
            $query->where('movement_type', $request->movement_type);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('movement_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('movement_date', '<=', $request->date_to);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('movement_number', 'like', "%{$search}%")
                  ->orWhere('reference_number', 'like', "%{$search}%")
                  ->orWhere('notes', 'like', "%{$search}%");
            });
        }

        $movements = $query->orderBy('movement_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Get filter options
        $warehouses = Warehouse::where('tenant_id', $tenantId)->orderBy('name')->get();
        $products = Product::where('tenant_id', $tenantId)->orderBy('name')->get();

        // Get statistics
        $stats = [
            'total_movements' => InventoryMovement::where('tenant_id', $tenantId)->count(),
            'today_movements' => InventoryMovement::where('tenant_id', $tenantId)
                ->whereDate('movement_date', today())->count(),
            'in_movements' => InventoryMovement::where('tenant_id', $tenantId)
                ->whereIn('movement_type', ['in', 'transfer_in', 'adjustment_in', 'return_in'])->count(),
            'out_movements' => InventoryMovement::where('tenant_id', $tenantId)
                ->whereIn('movement_type', ['out', 'transfer_out', 'adjustment_out', 'return_out'])->count(),
        ];

        return view('tenant.inventory.movements.index', compact(
            'movements', 'warehouses', 'products', 'stats'
        ));
    }

    /**
     * Show the form for creating a new movement
     */
    public function create(): View
    {
        $user = auth()->user();
        $tenantId = $user->tenant_id;

        if (!$tenantId) {
            abort(403, 'No tenant access');
        }

        $warehouses = Warehouse::where('tenant_id', $tenantId)->active()->orderBy('name')->get();
        $products = Product::where('tenant_id', $tenantId)->orderBy('name')->get();

        return view('tenant.inventory.movements.create', compact('warehouses', 'products'));
    }

    /**
     * Import movements from Excel
     */
    public function importExcel(Request $request)
    {
        $user = auth()->user();
        $tenantId = $user->tenant_id;

        $request->validate([
            'excel_file' => 'required|file|mimes:csv,xlsx,txt|max:10240',
        ]);

        try {
            \Log::info('Starting Movement Excel import for tenant: ' . $tenantId);
            \Log::info('File info: ', [
                'name' => $request->file('excel_file')->getClientOriginalName(),
                'size' => $request->file('excel_file')->getSize(),
                'mime' => $request->file('excel_file')->getMimeType()
            ]);

            $import = new InventoryMovementImport($tenantId);
            Excel::import($import, $request->file('excel_file'));

            $stats = $import->getStats();
            $totalProcessed = $stats['created'];

            if ($totalProcessed === 0 && $stats['errors'] === 0) {
                return redirect()->back()->with('warning', 'لم يتم العثور على بيانات صالحة للاستيراد. يرجى التحقق من تنسيق الملف.');
            }

            $message = "✅ تم استيراد ملف الحركات بنجاح!\n\n";
            if ($stats['created'] > 0) {
                $message .= "🆕 تم إنشاء {$stats['created']} حركة\n";
            }
            if ($stats['skipped'] > 0) {
                $message .= "⏭️ تم تجاهل {$stats['skipped']} صف\n";
            }
            if ($stats['errors'] > 0) {
                $message .= "⚠️ حدثت {$stats['errors']} أخطاء\n";
            }
            $message .= "\n📊 إجمالي العناصر المعالجة: {$totalProcessed}";

            return redirect()->route('tenant.inventory.movements.create')->with('success', $message);
        } catch (\Exception $e) {
            \Log::error('Movement Excel import failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'حدث خطأ أثناء استيراد الملف: ' . $e->getMessage());
        }
    }

    /**
     * Download Excel template for movements
     */
    public function downloadTemplate(): Response
    {
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="inventory_movements_template.csv"',
        ];

        $csvData = [
            ['كود المنتج','اسم المنتج','كود المستودع','نوع الحركة','الكمية','السبب','التاريخ','ملاحظات'],
            ['NEW001','منتج جديد','MAIN-004','in','25','purchase','2025-12-31','سيتم إنشاء المنتج تلقائيًا إذا لم يكن موجودًا'],
            ['PRD000002','منتج موجود','005','out','10','sale','2025-12-31','تخفيض مخزون منتج موجود'],
        ];

        $content = "\xEF\xBB\xBF"; // UTF-8 BOM
        foreach ($csvData as $row) {
            $content .= implode(',', array_map(function($v){ return '"'.str_replace('"','""',$v).'"'; }, $row)) . "\n";
        }

        return response($content, 200, $headers);
    }

    /**
     * Diagnostics endpoints
     */
    public function diagnostics(): Response
    {
        $user = auth()->user();
        $tenantId = $user->tenant_id;

        return response()->json([
            'tenant_id' => $tenantId,
            'sample' => [
                'product_code' => 'NEW001',
                'warehouse_code' => 'MAIN-004',
                'movement_type' => 'in',
                'quantity' => 10,
                'reason' => 'purchase',
                'date' => now()->toDateString(),
            ]
        ]);
    }

    public function showLogs(): Response
    {
        $logFile = storage_path('logs/laravel.log');
        if (!file_exists($logFile)) {
            return response()->json(['error' => 'Log file not found']);
        }

        $lines = file($logFile);
        $filtered = array_values(array_filter($lines, function($line){
            return str_contains($line, 'Movement Import');
        }));
        $last = array_slice($filtered, -100);
        return response()->json(['logs' => $last, 'total_lines' => count($last)]);
    }

    /**
     * Dry-run import for movements: parse the uploaded file and return parsed rows without saving
     */
    public function importDryRun(Request $request): Response
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls,csv,txt|max:10240',
        ]);

        try {
            $file = $request->file('excel_file');
            $mime = $file->getMimeType();
            $name = $file->getClientOriginalName();

            $rows = [];
            if (preg_match('/csv|plain/', $mime) || preg_match('/\.csv$/i', $name)) {
                $handle = fopen($file->getRealPath(), 'r');
                $bom = fread($handle, 3);
                if ($bom !== "\xEF\xBB\xBF") {
                    fseek($handle, 0);
                }
                $header = null;
                while (($data = fgetcsv($handle, 0, ',')) !== false) {
                    if ($header === null) { $header = $data; continue; }
                    $rows[] = array_combine($header, $data);
                    if (count($rows) >= 10) break;
                }
                fclose($handle);
            }

            return response()->json([
                'status' => 'ok',
                'file' => [ 'name' => $name, 'size' => $file->getSize(), 'mime' => $mime ],
                'preview_rows' => $rows,
            ]);
        } catch (\Throwable $e) {
            \Log::error('Movement Import dry run failed: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created movement
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        $tenantId = $user->tenant_id;

        if (!$tenantId) {
            abort(403, 'No tenant access');
        }

        try {
            $validated = $request->validate([
                'movement_type' => 'required|in:in,out,transfer_in,transfer_out,adjustment_in,adjustment_out,return_in,return_out',
                'movement_reason' => 'required|string',
                'warehouse_id' => 'required|exists:warehouses,id',
                'movement_date' => 'required|date',
                'reference_number' => 'nullable|string|max:255',
                'notes' => 'nullable|string',
                'products' => 'required|array|min:1',
                'products.*.product_id' => 'required|exists:products,id',
                'products.*.quantity' => 'required|numeric|min:0.001',
                'products.*.unit_cost' => 'nullable|numeric|min:0',
                'products.*.batch_number' => 'nullable|string|max:100',
                'products.*.notes' => 'nullable|string|max:255',
            ]);
        } catch (\Throwable $e) {
            \Log::error('Movement store validation failed', ['error' => $e->getMessage(), 'input' => $request->all()]);
            return back()->withErrors(['error' => 'تعذر التحقق من بيانات الحركة: ' . $e->getMessage()])->withInput();
        }

        // Generate movement number
        $movementNumber = 'MOV-' . date('Ymd') . '-' . str_pad(
            InventoryMovement::where('tenant_id', $tenantId)
                ->whereDate('created_at', today())
                ->count() + 1,
            4, '0', STR_PAD_LEFT
        );

        $createdMovements = [];
        $totalMovementCost = 0;

        try {
            $products = is_array($request->products) ? $request->products : [];
            foreach ($products as $productData) {
                if (empty($productData['product_id']) || empty($productData['quantity'])) {
                    continue; // Skip empty rows
                }

                $quantity = (float) ($productData['quantity'] ?? 0);
                if ($quantity <= 0) { continue; }

                $unitCost = (float) ($productData['unit_cost'] ?? 0);
                $totalCost = $quantity * $unitCost;
                $totalMovementCost += $totalCost;

                $baseNotes = (string) ($request->notes ?? '');
                $rowNotes = (string) ($productData['notes'] ?? '');
                $combinedNotes = trim($baseNotes . ($rowNotes !== '' ? ' | ' . $rowNotes : ''));

                $movement = InventoryMovement::create([
                    'tenant_id' => $tenantId,
                    'movement_number' => $movementNumber,
                    'warehouse_id' => $request->warehouse_id,
                    'product_id' => $productData['product_id'],
                    'movement_type' => $request->movement_type,
                    'movement_reason' => $request->movement_reason,
                    'quantity' => $quantity,
                    'unit_cost' => $unitCost,
                    'total_cost' => $totalCost,
                    'movement_date' => $request->movement_date,
                    'reference_number' => $request->reference_number,
                    'batch_number' => $productData['batch_number'] ?? null,
                    'notes' => $combinedNotes,
                    'created_by' => $user->id,
                    'balance_before' => 0,
                    'balance_after' => 0,
                ]);

                $createdMovements[] = $movement;
            }
        } catch (\Throwable $e) {
            \Log::error('Movement store failed during creation', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'input' => $request->all(),
            ]);
            return back()->with('error', 'حدث خطأ أثناء إنشاء حركة المخزون: ' . $e->getMessage())->withInput();
        }

        $productCount = count($createdMovements);
        if ($productCount === 0) {
            return back()->withErrors(['products' => 'لم يتم إنشاء أي حركة. يرجى التأكد من اختيار منتج وإدخال كمية صحيحة.'])->withInput();
        }

        $message = "تم إنشاء حركة المخزون بنجاح ({$productCount} منتج، إجمالي: " . number_format($totalMovementCost, 2) . " د.ع)";

        return redirect()->route('tenant.inventory.movements.index')
            ->with('success', $message);
    }

    /**
     * Display the specified movement
     */
    public function show(InventoryMovement $movement): View
    {
        $user = auth()->user();

        if ($movement->tenant_id !== $user->tenant_id) {
            abort(403, 'Unauthorized access');
        }

        $movement->load(['warehouse', 'product', 'createdBy', 'inventory']);

        return view('tenant.inventory.movements.show', compact('movement'));
    }
}
