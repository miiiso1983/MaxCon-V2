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

class AnalyticsDashboardController extends Controller
{
    /**
     * Display analytics dashboard
     */
    public function index(): View
    {
        $user = auth()->user();
        $tenantId = $user->tenant_id;

        if (!$tenantId) {
            abort(403, 'No tenant access');
        }

        $analytics = $this->generateAnalytics($tenantId);

        return view('tenant.inventory.analytics.dashboard', compact('analytics'));
    }

    /**
     * Generate comprehensive analytics data
     */
    private function generateAnalytics($tenantId): array
    {
        $now = Carbon::now();
        $lastMonth = $now->copy()->subMonth();
        $lastWeek = $now->copy()->subWeek();
        $lastYear = $now->copy()->subYear();

        return [
            'overview' => $this->getOverviewMetrics($tenantId),
            'trends' => $this->getTrendAnalysis($tenantId, $lastMonth, $now),
            'performance' => $this->getPerformanceMetrics($tenantId, $lastMonth, $now),
            'alerts' => $this->getAlertMetrics($tenantId),
            'forecasting' => $this->getForecastingData($tenantId),
            'efficiency' => $this->getEfficiencyMetrics($tenantId, $lastMonth, $now),
            'charts' => $this->getChartData($tenantId, $lastMonth, $now),
        ];
    }

    private function getOverviewMetrics($tenantId): array
    {
        $inventory = Inventory::where('tenant_id', $tenantId)->get();
        $movements = InventoryMovement::where('tenant_id', $tenantId)
            ->whereBetween('movement_date', [Carbon::now()->subMonth(), Carbon::now()])
            ->get();

        return [
            'total_products' => $inventory->count(),
            'total_quantity' => $inventory->sum('quantity'),
            'total_value' => $inventory->sum(fn($item) => $item->quantity * $item->cost_price),
            'available_quantity' => $inventory->sum('available_quantity'),
            'reserved_quantity' => $inventory->sum('reserved_quantity'),
            'monthly_movements' => $movements->count(),
            'monthly_in' => $movements->where('movement_type', 'in')->sum('quantity'),
            'monthly_out' => $movements->where('movement_type', 'out')->sum('quantity'),
            'warehouses_count' => Warehouse::where('tenant_id', $tenantId)->count(),
            'products_count' => Product::where('tenant_id', $tenantId)->count(),
        ];
    }

    private function getTrendAnalysis($tenantId, $from, $to): array
    {
        $movements = InventoryMovement::where('tenant_id', $tenantId)
            ->whereBetween('movement_date', [$from, $to])
            ->get();

        $dailyTrends = $movements->groupBy(fn($item) => $item->movement_date->format('Y-m-d'))
            ->map(function($dayMovements) {
                return [
                    'total' => $dayMovements->count(),
                    'in' => $dayMovements->where('movement_type', 'in')->sum('quantity'),
                    'out' => $dayMovements->where('movement_type', 'out')->sum('quantity'),
                    'value' => $dayMovements->sum('total_cost'),
                ];
            });

        $weeklyTrends = $movements->groupBy(fn($item) => $item->movement_date->format('Y-W'))
            ->map(function($weekMovements) {
                return [
                    'total' => $weekMovements->count(),
                    'in' => $weekMovements->where('movement_type', 'in')->sum('quantity'),
                    'out' => $weekMovements->where('movement_type', 'out')->sum('quantity'),
                    'value' => $weekMovements->sum('total_cost'),
                ];
            });

        return [
            'daily' => $dailyTrends,
            'weekly' => $weeklyTrends,
            'growth_rate' => $this->calculateGrowthRate($movements),
            'velocity' => $this->calculateInventoryVelocity($tenantId),
        ];
    }

    private function getPerformanceMetrics($tenantId, $from, $to): array
    {
        $movements = InventoryMovement::with('product')
            ->where('tenant_id', $tenantId)
            ->whereBetween('movement_date', [$from, $to])
            ->get();

        $productPerformance = $movements->groupBy('product_id')
            ->map(function($productMovements) {
                $product = $productMovements->first()->product;
                return [
                    'product' => $product,
                    'movements_count' => $productMovements->count(),
                    'total_in' => $productMovements->where('movement_type', 'in')->sum('quantity'),
                    'total_out' => $productMovements->where('movement_type', 'out')->sum('quantity'),
                    'turnover_rate' => $this->calculateTurnoverRate($productMovements),
                    'value' => $productMovements->sum('total_cost'),
                ];
            })
            ->sortByDesc('movements_count');

        return [
            'top_products' => $productPerformance->take(10),
            'slow_moving' => $productPerformance->sortBy('movements_count')->take(10),
            'high_value' => $productPerformance->sortByDesc('value')->take(10),
            'turnover_analysis' => $this->getTurnoverAnalysis($productPerformance),
        ];
    }

    private function getAlertMetrics($tenantId): array
    {
        $inventory = Inventory::with('product')->where('tenant_id', $tenantId)->get();
        $now = Carbon::now();

        $lowStock = $inventory->filter(function($item) {
            $minLevel = $item->product->min_stock_level ?? 0;
            return $item->available_quantity <= $minLevel;
        });

        $expiring = $inventory->filter(function($item) use ($now) {
            return $item->expiry_date && $item->expiry_date <= $now->copy()->addDays(30);
        });

        $expired = $inventory->filter(function($item) use ($now) {
            return $item->expiry_date && $item->expiry_date < $now;
        });

        return [
            'low_stock' => [
                'count' => $lowStock->count(),
                'items' => $lowStock->take(5),
                'total_value' => $lowStock->sum(fn($item) => $item->quantity * $item->cost_price),
            ],
            'expiring' => [
                'count' => $expiring->count(),
                'items' => $expiring->take(5),
                'total_value' => $expiring->sum(fn($item) => $item->quantity * $item->cost_price),
            ],
            'expired' => [
                'count' => $expired->count(),
                'items' => $expired->take(5),
                'total_value' => $expired->sum(fn($item) => $item->quantity * $item->cost_price),
            ],
        ];
    }

    private function getForecastingData($tenantId): array
    {
        $movements = InventoryMovement::where('tenant_id', $tenantId)
            ->where('movement_date', '>=', Carbon::now()->subMonths(6))
            ->get();

        $monthlyConsumption = $movements->where('movement_type', 'out')
            ->groupBy(fn($item) => $item->movement_date->format('Y-m'))
            ->map(fn($group) => $group->sum('quantity'));

        $avgMonthlyConsumption = $monthlyConsumption->avg();
        $currentStock = Inventory::where('tenant_id', $tenantId)->sum('available_quantity');

        return [
            'monthly_consumption' => $monthlyConsumption,
            'avg_consumption' => $avgMonthlyConsumption,
            'stock_days' => $avgMonthlyConsumption > 0 ? ($currentStock / $avgMonthlyConsumption) * 30 : 0,
            'reorder_suggestions' => $this->getReorderSuggestions($tenantId, $avgMonthlyConsumption),
            'demand_forecast' => $this->generateDemandForecast($monthlyConsumption),
        ];
    }

    private function getEfficiencyMetrics($tenantId, $from, $to): array
    {
        $warehouses = Warehouse::with('inventory')->where('tenant_id', $tenantId)->get();
        $movements = InventoryMovement::where('tenant_id', $tenantId)
            ->whereBetween('movement_date', [$from, $to])
            ->get();

        $warehouseEfficiency = $warehouses->map(function($warehouse) use ($movements) {
            $warehouseMovements = $movements->where('warehouse_id', $warehouse->id);
            $inventory = $warehouse->inventory;

            return [
                'warehouse' => $warehouse,
                'utilization' => $warehouse->capacity > 0 ?
                    ($inventory->sum('quantity') / $warehouse->capacity) * 100 : 0,
                'movements_count' => $warehouseMovements->count(),
                'efficiency_score' => $this->calculateEfficiencyScore($warehouse, $warehouseMovements),
                'cost_per_movement' => $warehouseMovements->count() > 0 ?
                    $warehouseMovements->sum('total_cost') / $warehouseMovements->count() : 0,
            ];
        });

        return [
            'warehouse_efficiency' => $warehouseEfficiency,
            'overall_efficiency' => $warehouseEfficiency->avg('efficiency_score'),
            'best_performing' => $warehouseEfficiency->sortByDesc('efficiency_score')->first(),
            'optimization_opportunities' => $this->getOptimizationOpportunities($warehouseEfficiency),
        ];
    }

    private function getChartData($tenantId, $from, $to): array
    {
        $movements = InventoryMovement::where('tenant_id', $tenantId)
            ->whereBetween('movement_date', [$from, $to])
            ->get();

        return [
            'movement_trends' => $this->prepareMovementTrendsChart($movements),
            'product_distribution' => $this->prepareProductDistributionChart($tenantId),
            'warehouse_comparison' => $this->prepareWarehouseComparisonChart($tenantId),
            'cost_analysis' => $this->prepareCostAnalysisChart($movements),
            'expiry_timeline' => $this->prepareExpiryTimelineChart($tenantId),
        ];
    }

    // Helper methods
    private function calculateGrowthRate($movements): float
    {
        $thisMonth = $movements->where('movement_date', '>=', Carbon::now()->startOfMonth())->count();
        $lastMonth = $movements->where('movement_date', '<', Carbon::now()->startOfMonth())
            ->where('movement_date', '>=', Carbon::now()->subMonth()->startOfMonth())->count();

        return $lastMonth > 0 ? (($thisMonth - $lastMonth) / $lastMonth) * 100 : 0;
    }

    private function calculateInventoryVelocity($tenantId): float
    {
        $totalValue = Inventory::where('tenant_id', $tenantId)
            ->get()
            ->sum(fn($item) => $item->quantity * $item->cost_price);

        $monthlyCost = InventoryMovement::where('tenant_id', $tenantId)
            ->where('movement_type', 'out')
            ->where('movement_date', '>=', Carbon::now()->subMonth())
            ->sum('total_cost');

        return $totalValue > 0 ? $monthlyCost / $totalValue : 0;
    }

    private function calculateTurnoverRate($productMovements): float
    {
        $outMovements = $productMovements->where('movement_type', 'out')->sum('quantity');
        $avgInventory = $productMovements->avg('balance_after') ?: 1;

        return $avgInventory > 0 ? $outMovements / $avgInventory : 0;
    }

    private function getTurnoverAnalysis($productPerformance): array
    {
        return [
            'fast_moving' => $productPerformance->where('turnover_rate', '>', 2)->count(),
            'medium_moving' => $productPerformance->whereBetween('turnover_rate', [0.5, 2])->count(),
            'slow_moving' => $productPerformance->where('turnover_rate', '<', 0.5)->count(),
        ];
    }

    private function getReorderSuggestions($tenantId, $avgConsumption): array
    {
        $inventory = Inventory::with('product')->where('tenant_id', $tenantId)->get();

        return $inventory->filter(function($item) use ($avgConsumption) {
            $monthsOfStock = $avgConsumption > 0 ? ($item->available_quantity / $avgConsumption) * 30 : 999;
            return $monthsOfStock < 30; // Less than 30 days of stock
        })->take(10)->values()->all();
    }

    private function generateDemandForecast($monthlyConsumption): array
    {
        $trend = $monthlyConsumption->values()->toArray();
        $forecast = [];

        // Simple linear trend forecast for next 3 months
        if (count($trend) >= 2) {
            $slope = (end($trend) - $trend[0]) / (count($trend) - 1);
            $lastValue = end($trend);

            for ($i = 1; $i <= 3; $i++) {
                $forecast[] = max(0, $lastValue + ($slope * $i));
            }
        }

        return $forecast;
    }

    private function calculateEfficiencyScore($warehouse, $movements): float
    {
        $utilization = $warehouse->capacity > 0 ?
            ($warehouse->inventory->sum('quantity') / $warehouse->capacity) * 100 : 0;
        $movementFrequency = $movements->count();
        $avgCost = $movements->avg('total_cost') ?: 0;

        // Weighted efficiency score (0-100)
        return min(100, ($utilization * 0.4) + (min($movementFrequency, 100) * 0.4) + (max(0, 100 - $avgCost) * 0.2));
    }

    private function getOptimizationOpportunities($warehouseEfficiency): array
    {
        return $warehouseEfficiency->filter(function($warehouse) {
            return $warehouse['efficiency_score'] < 70;
        })->map(function($warehouse) {
            $opportunities = [];

            if ($warehouse['utilization'] < 50) {
                $opportunities[] = 'زيادة الاستفادة من المساحة';
            }
            if ($warehouse['movements_count'] < 10) {
                $opportunities[] = 'تحسين تدفق البضائع';
            }
            if ($warehouse['cost_per_movement'] > 100) {
                $opportunities[] = 'تقليل تكلفة العمليات';
            }

            return [
                'warehouse' => $warehouse['warehouse'],
                'opportunities' => $opportunities,
                'priority' => count($opportunities) > 2 ? 'عالية' : 'متوسطة',
            ];
        })->values()->all();
    }

    private function prepareMovementTrendsChart($movements): array
    {
        return $movements->groupBy(fn($item) => $item->movement_date->format('Y-m-d'))
            ->map(function($dayMovements, $date) {
                return [
                    'date' => $date,
                    'in' => $dayMovements->where('movement_type', 'in')->sum('quantity'),
                    'out' => $dayMovements->where('movement_type', 'out')->sum('quantity'),
                ];
            })->values()->all();
    }

    private function prepareProductDistributionChart($tenantId): array
    {
        return Inventory::with('product')
            ->where('tenant_id', $tenantId)
            ->get()
            ->groupBy('product.name')
            ->map(fn($group) => $group->sum('quantity'))
            ->sortDesc()
            ->take(10)
            ->map(fn($quantity, $name) => ['name' => $name, 'value' => $quantity])
            ->values()
            ->all();
    }

    private function prepareWarehouseComparisonChart($tenantId): array
    {
        return Warehouse::with('inventory')
            ->where('tenant_id', $tenantId)
            ->get()
            ->map(function($warehouse) {
                return [
                    'name' => $warehouse->name,
                    'quantity' => $warehouse->inventory->sum('quantity'),
                    'value' => $warehouse->inventory->sum(fn($item) => $item->quantity * $item->cost_price),
                ];
            })
            ->values()
            ->all();
    }

    private function prepareCostAnalysisChart($movements): array
    {
        return $movements->groupBy(fn($item) => $item->movement_date->format('Y-m'))
            ->map(function($monthMovements, $month) {
                return [
                    'month' => $month,
                    'cost' => $monthMovements->sum('total_cost'),
                    'count' => $monthMovements->count(),
                ];
            })->values()->all();
    }

    private function prepareExpiryTimelineChart($tenantId): array
    {
        $inventory = Inventory::with('product')
            ->where('tenant_id', $tenantId)
            ->whereNotNull('expiry_date')
            ->get();

        $now = Carbon::now();

        return [
            'expired' => $inventory->filter(fn($item) => $item->expiry_date < $now)->count(),
            'expiring_30_days' => $inventory->filter(fn($item) =>
                $item->expiry_date >= $now && $item->expiry_date <= $now->copy()->addDays(30)
            )->count(),
            'expiring_90_days' => $inventory->filter(fn($item) =>
                $item->expiry_date > $now->copy()->addDays(30) && $item->expiry_date <= $now->copy()->addDays(90)
            )->count(),
            'long_term' => $inventory->filter(fn($item) => $item->expiry_date > $now->copy()->addDays(90))->count(),
        ];
    }
}
