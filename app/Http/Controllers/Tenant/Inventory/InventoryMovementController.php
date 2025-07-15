<?php

namespace App\Http\Controllers\Tenant\Inventory;

use App\Http\Controllers\Controller;
use App\Models\InventoryMovement;
use App\Models\Warehouse;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

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
     * Store a newly created movement
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        $tenantId = $user->tenant_id;

        if (!$tenantId) {
            abort(403, 'No tenant access');
        }

        $request->validate([
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

        // Generate movement number
        $movementNumber = 'MOV-' . date('Ymd') . '-' . str_pad(
            InventoryMovement::where('tenant_id', $tenantId)
                ->whereDate('created_at', today())
                ->count() + 1,
            4, '0', STR_PAD_LEFT
        );

        $createdMovements = [];
        $totalMovementCost = 0;

        // Create a movement for each product
        foreach ($request->products as $productData) {
            if (empty($productData['product_id']) || empty($productData['quantity'])) {
                continue; // Skip empty rows
            }

            $unitCost = $productData['unit_cost'] ?? 0;
            $totalCost = $productData['quantity'] * $unitCost;
            $totalMovementCost += $totalCost;

            $movement = InventoryMovement::create([
                'tenant_id' => $tenantId,
                'movement_number' => $movementNumber,
                'warehouse_id' => $request->warehouse_id,
                'product_id' => $productData['product_id'],
                'movement_type' => $request->movement_type,
                'movement_reason' => $request->movement_reason,
                'quantity' => $productData['quantity'],
                'unit_cost' => $unitCost,
                'total_cost' => $totalCost,
                'movement_date' => $request->movement_date,
                'reference_number' => $request->reference_number,
                'batch_number' => $productData['batch_number'] ?? null,
                'notes' => $request->notes . ($productData['notes'] ? ' | ' . $productData['notes'] : ''),
                'created_by' => $user->id,
                'balance_before' => 0, // Will be calculated in real implementation
                'balance_after' => 0,  // Will be calculated in real implementation
            ]);

            $createdMovements[] = $movement;
        }

        $productCount = count($createdMovements);
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
