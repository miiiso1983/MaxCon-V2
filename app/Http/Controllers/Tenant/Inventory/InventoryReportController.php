<?php

namespace App\Http\Controllers\Tenant\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\InventoryMovement;
use App\Models\Warehouse;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class InventoryReportController extends Controller
{
    /**
     * Display inventory reports dashboard
     */
    public function index(): View
    {
        $user = Auth::user();
        $tenantId = $user->tenant_id;

        if (!$tenantId) {
            abort(403, 'No tenant access');
        }

        return view('tenant.inventory.reports.index');
    }

    public function customIndex(): View
    {
        $user = Auth::user();
        $tenantId = $user->tenant_id;
        if (!$tenantId) { abort(403, 'No tenant access'); }
        $warehouses = Warehouse::where('tenant_id', $tenantId)->orderBy('name')->get(['id','name']);
        $products = Product::where('tenant_id', $tenantId)->orderBy('name')->limit(500)->get(['id','name']);
        return view('tenant.inventory.reports.custom-index', compact('warehouses','products'));
    }

    public function runCustom(Request $request): View
    {
        $user = Auth::user();
        $tenantId = $user->tenant_id;
        if (!$tenantId) { abort(403, 'No tenant access'); }

        $type = $request->input('type', 'inventory_summary');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');
        $warehouseId = $request->input('warehouse_id');
        $productId = $request->input('product_id');

        $reportData = [ 'type' => $type, 'title' => 'تقرير مخصص', 'period' => ($dateFrom && $dateTo) ? ($dateFrom.' → '.$dateTo) : null ];

        switch ($type) {
            case 'inventory_summary':
                $q = Inventory::with(['product','warehouse'])
                    ->where('tenant_id', $tenantId);
                if ($warehouseId) { $q->where('warehouse_id', $warehouseId); }
                if ($productId) { $q->where('product_id', $productId); }
                $items = $q->get();
                $reportData['data'] = $items;
                $reportData['summary'] = [
                    'total_items' => $items->count(),
                    'total_quantity' => (float) $items->sum('quantity'),
                    'total_available' => (float) $items->sum('available_quantity'),
                    'total_value' => (float) $items->sum(function($i){ return $i->quantity * ($i->cost_price ?? 0); }),
                    'by_status' => $items->groupBy('status')->map->count(),
                    'by_warehouse' => $items->groupBy(fn($i) => optional($i->warehouse)->name ?: ('#'.$i->warehouse_id))->map->count(),
                ];
                break;

            case 'movement_analysis':
                $q = InventoryMovement::with(['product','warehouse'])
                    ->where('tenant_id', $tenantId);
                if ($warehouseId) { $q->where('warehouse_id', $warehouseId); }
                if ($productId) { $q->where('product_id', $productId); }
                if ($dateFrom) { $q->whereDate('movement_date', '>=', $dateFrom); }
                if ($dateTo) { $q->whereDate('movement_date', '<=', $dateTo); }
                $mv = $q->orderBy('movement_date','asc')->get();
                $reportData['data'] = $mv;
                $reportData['summary'] = [
                    'daily_movements' => $mv->groupBy(fn($m) => optional($m->movement_date)->format('Y-m-d'))->map->count(),
                    'by_type' => $mv->groupBy('movement_type')->map->count(),
                ];
                break;

            case 'cost_analysis':
                $q = InventoryMovement::where('tenant_id', $tenantId);
                if ($dateFrom) { $q->whereDate('movement_date', '>=', $dateFrom); }
                if ($dateTo) { $q->whereDate('movement_date', '<=', $dateTo); }
                $mv = $q->get();
                $monthly = $mv->groupBy(fn($m) => optional($m->movement_date)->format('Y-m'));
                $reportData['data'] = [
                    'monthly_trend' => $monthly->map(function($grp){
                        return [ 'count' => $grp->count(), 'total_cost' => (float) $grp->sum('total_cost') ];
                    })
                ];
                break;

            case 'expiry_tracking':
                $expiring = Inventory::with(['product','warehouse'])
                    ->where('tenant_id', $tenantId)
                    ->whereNotNull('expiry_date')
                    ->orderBy('expiry_date','asc')
                    ->get();
                $reportData['data'] = [
                    'expired' => $expiring->where('expiry_date', '<', now()),
                    'expiring_soon' => $expiring->whereBetween('expiry_date', [now(), now()->addDays(90)])
                ];
                break;

            default:
                // Fallback to inventory summary
                return $this->runCustom($request->merge(['type' => 'inventory_summary']));
        }

        return view('tenant.inventory.reports.custom-results', compact('reportData'));
    }

    public function analytics(): View
    {
        $user = Auth::user();
        $tenantId = $user->tenant_id;
        if (!$tenantId) { abort(403, 'No tenant access'); }

        $totalProducts = Product::where('tenant_id', $tenantId)->count();
        $totalItems = Inventory::where('tenant_id', $tenantId)->count();
        $lowStock = Inventory::where('tenant_id', $tenantId)
            ->whereRaw('available_quantity <= (SELECT min_stock_level FROM products WHERE products.id = inventory.product_id)')->count();
        $expired = Inventory::where('tenant_id', $tenantId)->whereNotNull('expiry_date')->where('expiry_date','<', now())->count();

        // Movements last 7 days
        $recent = InventoryMovement::where('tenant_id', $tenantId)
            ->whereDate('movement_date', '>=', now()->subDays(6)->toDateString())
            ->get()
            ->groupBy(fn($m) => optional($m->movement_date)->format('Y-m-d'))
            ->map->count();

        return view('tenant.inventory.reports.analytics', [
            'metrics' => [
                'products' => $totalProducts,
                'items' => $totalItems,
                'low_stock' => $lowStock,
                'expired' => $expired,
            ],
            'recent' => $recent,
        ]);
    }

    /**
     * Stock levels report
     */
    public function stockLevels(Request $request): View
    {
        $user = Auth::user();
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
        $user = Auth::user();
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
        $user = Auth::user();
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
        $user = Auth::user();
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
