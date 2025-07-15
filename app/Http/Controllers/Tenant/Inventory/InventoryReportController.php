<?php

namespace App\Http\Controllers\Tenant\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\InventoryMovement;
use App\Models\Warehouse;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InventoryReportController extends Controller
{
    /**
     * Display inventory reports dashboard
     */
    public function index(): View
    {
        $user = auth()->user();
        $tenantId = $user->tenant_id;

        if (!$tenantId) {
            abort(403, 'No tenant access');
        }

        return view('tenant.inventory.reports.index');
    }

    /**
     * Stock levels report
     */
    public function stockLevels(Request $request): View
    {
        $user = auth()->user();
        $tenantId = $user->tenant_id;

        if (!$tenantId) {
            abort(403, 'No tenant access');
        }

        $query = Inventory::with(['product', 'warehouse', 'location'])
            ->where('tenant_id', $tenantId)
            ->where('quantity', '>', 0);

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

        $inventory = $query->orderBy('product_id')->get();

        // Group by product
        $stockData = $inventory->groupBy('product_id')->map(function ($items) {
            $product = $items->first()->product;
            return [
                'product' => $product,
                'total_quantity' => $items->sum('quantity'),
                'available_quantity' => $items->sum('available_quantity'),
                'reserved_quantity' => $items->sum('reserved_quantity'),
                'total_value' => $items->sum(function ($item) {
                    return $item->quantity * $item->cost_price;
                }),
                'warehouses' => $items->groupBy('warehouse_id')->map(function ($warehouseItems) {
                    return [
                        'warehouse' => $warehouseItems->first()->warehouse,
                        'quantity' => $warehouseItems->sum('quantity'),
                        'available' => $warehouseItems->sum('available_quantity'),
                    ];
                }),
            ];
        });

        $warehouses = Warehouse::where('tenant_id', $tenantId)->orderBy('name')->get();
        $products = Product::where('tenant_id', $tenantId)->orderBy('name')->get();

        return view('tenant.inventory.reports.stock-levels', compact('stockData', 'warehouses', 'products'));
    }

    /**
     * Movement history report
     */
    public function movementHistory(Request $request): View
    {
        $user = auth()->user();
        $tenantId = $user->tenant_id;

        if (!$tenantId) {
            abort(403, 'No tenant access');
        }

        $query = InventoryMovement::with(['product', 'warehouse', 'createdBy'])
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

        $movements = $query->orderBy('movement_date', 'desc')->paginate(50);

        $warehouses = Warehouse::where('tenant_id', $tenantId)->orderBy('name')->get();
        $products = Product::where('tenant_id', $tenantId)->orderBy('name')->get();

        return view('tenant.inventory.reports.movement-history', compact('movements', 'warehouses', 'products'));
    }

    /**
     * Low stock report
     */
    public function lowStock(): View
    {
        $user = auth()->user();
        $tenantId = $user->tenant_id;

        if (!$tenantId) {
            abort(403, 'No tenant access');
        }

        $lowStockItems = Inventory::with(['product', 'warehouse'])
            ->where('tenant_id', $tenantId)
            ->whereRaw('available_quantity <= (SELECT min_stock_level FROM products WHERE products.id = inventory.product_id)')
            ->orderBy('available_quantity', 'asc')
            ->get();

        return view('tenant.inventory.reports.low-stock', compact('lowStockItems'));
    }

    /**
     * Expiring items report
     */
    public function expiringItems(): View
    {
        $user = auth()->user();
        $tenantId = $user->tenant_id;

        if (!$tenantId) {
            abort(403, 'No tenant access');
        }

        $expiringItems = Inventory::with(['product', 'warehouse'])
            ->where('tenant_id', $tenantId)
            ->whereNotNull('expiry_date')
            ->where('expiry_date', '<=', now()->addDays(90))
            ->orderBy('expiry_date', 'asc')
            ->get();

        return view('tenant.inventory.reports.expiring-items', compact('expiringItems'));
    }
}
