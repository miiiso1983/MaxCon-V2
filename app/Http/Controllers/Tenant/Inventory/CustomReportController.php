<?php

namespace App\Http\Controllers\Tenant\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\InventoryMovement;
use App\Models\Warehouse;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Carbon\Carbon;

class CustomReportController extends Controller
{
    /**
     * Display custom reports page
     */
    public function index(): View
    {
        $user = auth()->user();
        $tenantId = $user->tenant_id;

        if (!$tenantId) {
            abort(403, 'No tenant access');
        }

        $warehouses = Warehouse::where('tenant_id', $tenantId)->orderBy('name')->get();
        $products = Product::where('tenant_id', $tenantId)->orderBy('name')->get();

        return view('tenant.inventory.reports.custom', compact('warehouses', 'products'));
    }

    /**
     * Generate custom report based on filters
     */
    public function generate(Request $request): View
    {
        $user = auth()->user();
        $tenantId = $user->tenant_id;

        if (!$tenantId) {
            abort(403, 'No tenant access');
        }

        $request->validate([
            'report_type' => 'required|in:inventory_summary,movement_analysis,product_performance,warehouse_comparison,cost_analysis,expiry_tracking',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
            'warehouse_ids' => 'nullable|array',
            'warehouse_ids.*' => 'exists:warehouses,id',
            'product_ids' => 'nullable|array',
            'product_ids.*' => 'exists:products,id',
            'movement_types' => 'nullable|array',
            'status_filter' => 'nullable|string',
        ]);

        $reportData = $this->generateReportData($request, $tenantId);
        $warehouses = Warehouse::where('tenant_id', $tenantId)->orderBy('name')->get();
        $products = Product::where('tenant_id', $tenantId)->orderBy('name')->get();

        return view('tenant.inventory.reports.custom-results', compact('reportData', 'warehouses', 'products'));
    }

    /**
     * Generate report data based on type and filters
     */
    private function generateReportData(Request $request, $tenantId): array
    {
        $reportType = $request->report_type;
        $dateFrom = $request->date_from ? Carbon::parse($request->date_from) : Carbon::now()->subMonth();
        $dateTo = $request->date_to ? Carbon::parse($request->date_to) : Carbon::now();

        switch ($reportType) {
            case 'inventory_summary':
                return $this->generateInventorySummary($request, $tenantId);
            case 'movement_analysis':
                return $this->generateMovementAnalysis($request, $tenantId, $dateFrom, $dateTo);
            case 'product_performance':
                return $this->generateProductPerformance($request, $tenantId, $dateFrom, $dateTo);
            case 'warehouse_comparison':
                return $this->generateWarehouseComparison($request, $tenantId);
            case 'cost_analysis':
                return $this->generateCostAnalysis($request, $tenantId, $dateFrom, $dateTo);
            case 'expiry_tracking':
                return $this->generateExpiryTracking($request, $tenantId);
            default:
                return [];
        }
    }

    private function generateInventorySummary(Request $request, $tenantId): array
    {
        $query = Inventory::with(['product', 'warehouse'])
            ->where('tenant_id', $tenantId);

        if ($request->warehouse_ids) {
            $query->whereIn('warehouse_id', $request->warehouse_ids);
        }

        if ($request->product_ids) {
            $query->whereIn('product_id', $request->product_ids);
        }

        if ($request->status_filter) {
            $query->where('status', $request->status_filter);
        }

        $inventory = $query->get();

        return [
            'type' => 'inventory_summary',
            'title' => 'ملخص المخزون',
            'data' => $inventory,
            'summary' => [
                'total_items' => $inventory->count(),
                'total_quantity' => $inventory->sum('quantity'),
                'total_available' => $inventory->sum('available_quantity'),
                'total_reserved' => $inventory->sum('reserved_quantity'),
                'total_value' => $inventory->sum(function($item) {
                    return $item->quantity * $item->cost_price;
                }),
                'by_status' => $inventory->groupBy('status')->map->count(),
                'by_warehouse' => $inventory->groupBy('warehouse.name')->map->count(),
            ]
        ];
    }

    private function generateMovementAnalysis(Request $request, $tenantId, $dateFrom, $dateTo): array
    {
        $query = InventoryMovement::with(['product', 'warehouse', 'createdBy'])
            ->where('tenant_id', $tenantId)
            ->whereBetween('movement_date', [$dateFrom, $dateTo]);

        if ($request->warehouse_ids) {
            $query->whereIn('warehouse_id', $request->warehouse_ids);
        }

        if ($request->product_ids) {
            $query->whereIn('product_id', $request->product_ids);
        }

        if ($request->movement_types) {
            $query->whereIn('movement_type', $request->movement_types);
        }

        $movements = $query->get();

        return [
            'type' => 'movement_analysis',
            'title' => 'تحليل حركات المخزون',
            'period' => $dateFrom->format('Y-m-d') . ' إلى ' . $dateTo->format('Y-m-d'),
            'data' => $movements,
            'summary' => [
                'total_movements' => $movements->count(),
                'total_in' => $movements->where('movement_type', 'in')->sum('quantity'),
                'total_out' => $movements->where('movement_type', 'out')->sum('quantity'),
                'total_value' => $movements->sum('total_cost'),
                'by_type' => $movements->groupBy('movement_type')->map->count(),
                'by_reason' => $movements->groupBy('movement_reason')->map->count(),
                'daily_movements' => $movements->groupBy(function($item) {
                    return $item->movement_date->format('Y-m-d');
                })->map->count(),
            ]
        ];
    }

    private function generateProductPerformance(Request $request, $tenantId, $dateFrom, $dateTo): array
    {
        $query = InventoryMovement::with(['product'])
            ->where('tenant_id', $tenantId)
            ->whereBetween('movement_date', [$dateFrom, $dateTo]);

        if ($request->product_ids) {
            $query->whereIn('product_id', $request->product_ids);
        }

        $movements = $query->get();
        $productPerformance = $movements->groupBy('product_id')->map(function($productMovements) {
            $product = $productMovements->first()->product;
            return [
                'product' => $product,
                'total_movements' => $productMovements->count(),
                'total_in' => $productMovements->where('movement_type', 'in')->sum('quantity'),
                'total_out' => $productMovements->where('movement_type', 'out')->sum('quantity'),
                'net_movement' => $productMovements->where('movement_type', 'in')->sum('quantity') -
                                $productMovements->where('movement_type', 'out')->sum('quantity'),
                'total_value' => $productMovements->sum('total_cost'),
                'avg_cost' => $productMovements->where('total_cost', '>', 0)->avg('unit_cost'),
            ];
        })->sortByDesc('total_movements');

        return [
            'type' => 'product_performance',
            'title' => 'أداء المنتجات',
            'period' => $dateFrom->format('Y-m-d') . ' إلى ' . $dateTo->format('Y-m-d'),
            'data' => $productPerformance,
            'summary' => [
                'top_products' => $productPerformance->take(10),
                'most_active' => $productPerformance->sortByDesc('total_movements')->take(5),
                'highest_value' => $productPerformance->sortByDesc('total_value')->take(5),
            ]
        ];
    }

    private function generateWarehouseComparison(Request $request, $tenantId): array
    {
        $query = Warehouse::with(['inventory.product'])
            ->where('tenant_id', $tenantId);

        if ($request->warehouse_ids) {
            $query->whereIn('id', $request->warehouse_ids);
        }

        $warehouses = $query->get();
        $comparison = $warehouses->map(function($warehouse) {
            $inventory = $warehouse->inventory;
            return [
                'warehouse' => $warehouse,
                'total_products' => $inventory->count(),
                'total_quantity' => $inventory->sum('quantity'),
                'total_available' => $inventory->sum('available_quantity'),
                'total_value' => $inventory->sum(function($item) {
                    return $item->quantity * $item->cost_price;
                }),
                'utilization' => $warehouse->capacity > 0 ?
                    ($inventory->sum('quantity') / $warehouse->capacity) * 100 : 0,
                'by_status' => $inventory->groupBy('status')->map->count(),
            ];
        });

        return [
            'type' => 'warehouse_comparison',
            'title' => 'مقارنة المستودعات',
            'data' => $comparison,
            'summary' => [
                'total_warehouses' => $warehouses->count(),
                'total_capacity' => $warehouses->sum('capacity'),
                'total_utilization' => $comparison->avg('utilization'),
                'most_utilized' => $comparison->sortByDesc('utilization')->first(),
                'highest_value' => $comparison->sortByDesc('total_value')->first(),
            ]
        ];
    }

    private function generateCostAnalysis(Request $request, $tenantId, $dateFrom, $dateTo): array
    {
        $movements = InventoryMovement::with(['product', 'warehouse'])
            ->where('tenant_id', $tenantId)
            ->whereBetween('movement_date', [$dateFrom, $dateTo])
            ->where('total_cost', '>', 0)
            ->get();

        $costAnalysis = [
            'total_cost' => $movements->sum('total_cost'),
            'avg_cost' => $movements->avg('unit_cost'),
            'by_type' => $movements->groupBy('movement_type')->map(function($group) {
                return [
                    'count' => $group->count(),
                    'total_cost' => $group->sum('total_cost'),
                    'avg_cost' => $group->avg('unit_cost'),
                ];
            }),
            'by_product' => $movements->groupBy('product.name')->map(function($group) {
                return [
                    'count' => $group->count(),
                    'total_cost' => $group->sum('total_cost'),
                    'avg_cost' => $group->avg('unit_cost'),
                ];
            })->sortByDesc('total_cost'),
            'monthly_trend' => $movements->groupBy(function($item) {
                return $item->movement_date->format('Y-m');
            })->map(function($group) {
                return [
                    'count' => $group->count(),
                    'total_cost' => $group->sum('total_cost'),
                ];
            }),
        ];

        return [
            'type' => 'cost_analysis',
            'title' => 'تحليل التكاليف',
            'period' => $dateFrom->format('Y-m-d') . ' إلى ' . $dateTo->format('Y-m-d'),
            'data' => $costAnalysis,
            'movements' => $movements,
        ];
    }

    private function generateExpiryTracking(Request $request, $tenantId): array
    {
        $query = Inventory::with(['product', 'warehouse'])
            ->where('tenant_id', $tenantId)
            ->whereNotNull('expiry_date');

        if ($request->warehouse_ids) {
            $query->whereIn('warehouse_id', $request->warehouse_ids);
        }

        if ($request->product_ids) {
            $query->whereIn('product_id', $request->product_ids);
        }

        $inventory = $query->get();
        $now = Carbon::now();

        $expiryAnalysis = [
            'expired' => $inventory->filter(function($item) use ($now) {
                return $item->expiry_date < $now;
            }),
            'expiring_soon' => $inventory->filter(function($item) use ($now) {
                return $item->expiry_date >= $now && $item->expiry_date <= $now->copy()->addDays(30);
            }),
            'expiring_this_quarter' => $inventory->filter(function($item) use ($now) {
                return $item->expiry_date > $now->copy()->addDays(30) &&
                       $item->expiry_date <= $now->copy()->addDays(90);
            }),
            'long_term' => $inventory->filter(function($item) use ($now) {
                return $item->expiry_date > $now->copy()->addDays(90);
            }),
        ];

        return [
            'type' => 'expiry_tracking',
            'title' => 'تتبع انتهاء الصلاحية',
            'data' => $expiryAnalysis,
            'summary' => [
                'total_items' => $inventory->count(),
                'expired_count' => $expiryAnalysis['expired']->count(),
                'expiring_soon_count' => $expiryAnalysis['expiring_soon']->count(),
                'expired_value' => $expiryAnalysis['expired']->sum(function($item) {
                    return $item->quantity * $item->cost_price;
                }),
                'at_risk_value' => $expiryAnalysis['expiring_soon']->sum(function($item) {
                    return $item->quantity * $item->cost_price;
                }),
            ]
        ];
    }
}
