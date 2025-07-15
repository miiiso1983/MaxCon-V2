<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\SalesTarget;
use App\Models\SalesTargetProgress;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SalesTargetController extends Controller
{
    public function __construct()
    {
        // Remove middleware from constructor - will be handled by routes
    }

    /**
     * Display a listing of sales targets
     */
    public function index(Request $request)
    {
        $query = SalesTarget::forTenant(auth()->user()->tenant_id)
                           ->with(['creator', 'updater']);

        // Filters
        if ($request->filled('target_type')) {
            $query->where('target_type', $request->target_type);
        }

        if ($request->filled('period_type')) {
            $query->where('period_type', $request->period_type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('target_entity_name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $targets = $query->orderBy('created_at', 'desc')->paginate(15);

        // Statistics
        $stats = $this->getTargetsStatistics();

        return view('tenant.sales.targets.index', compact('targets', 'stats'));
    }

    /**
     * Show the form for creating a new sales target
     */
    public function create()
    {
        $products = Product::forTenant(auth()->user()->tenant_id)->active()->get();
        $vendors = collect(); // Empty collection since Vendor model doesn't exist yet
        $salesReps = User::forTenant(auth()->user()->tenant_id)->get(); // Remove role filter for now

        return view('tenant.sales.targets.create', compact('products', 'vendors', 'salesReps'));
    }

    /**
     * Store a newly created sales target
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'target_type' => 'required|in:product,vendor,sales_team,department,sales_rep',
            'target_entity_id' => 'required|integer',
            'target_entity_name' => 'required|string|max:255',
            'period_type' => 'required|in:monthly,quarterly,yearly',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'measurement_type' => 'required|in:quantity,value,both',
            'target_quantity' => 'nullable|numeric|min:0',
            'target_value' => 'nullable|numeric|min:0',
            'currency' => 'required|string|size:3',
            'unit' => 'nullable|string|max:50',
            'notification_settings' => 'nullable|array',
            'notes' => 'nullable|string'
        ]);

        // Set year, month, quarter based on dates
        $startDate = Carbon::parse($validated['start_date']);
        $validated['year'] = $startDate->year;
        
        if ($validated['period_type'] === 'monthly') {
            $validated['month'] = $startDate->month;
        } elseif ($validated['period_type'] === 'quarterly') {
            $validated['quarter'] = $startDate->quarter;
        }

        $validated['tenant_id'] = auth()->user()->tenant_id;
        $validated['created_by'] = auth()->id();

        $target = SalesTarget::create($validated);

        return redirect()->route('tenant.sales.targets.show', $target)
                        ->with('success', 'تم إنشاء الهدف بنجاح');
    }

    /**
     * Display the specified sales target
     */
    public function show(SalesTarget $target)
    {
        $target->load(['progress' => function($query) {
            $query->orderBy('progress_date', 'desc')->limit(30);
        }, 'creator', 'updater']);

        // Progress statistics
        $progressStats = $this->getTargetProgressStats($target);
        
        // Recent progress
        $recentProgress = $target->progress()
                                ->orderBy('progress_date', 'desc')
                                ->limit(7)
                                ->get();

        // Chart data for progress trend
        $chartData = $this->getProgressChartData($target);

        return view('tenant.sales.targets.show', compact(
            'target', 
            'progressStats', 
            'recentProgress', 
            'chartData'
        ));
    }

    /**
     * Show the form for editing the specified sales target
     */
    public function edit(SalesTarget $target)
    {
        $products = Product::forTenant(auth()->user()->tenant_id)->active()->get();
        $vendors = collect(); // Empty collection since Vendor model doesn't exist yet
        $salesReps = User::forTenant(auth()->user()->tenant_id)->get(); // Remove role filter for now

        return view('tenant.sales.targets.edit', compact('target', 'products', 'vendors', 'salesReps'));
    }

    /**
     * Update the specified sales target
     */
    public function update(Request $request, SalesTarget $target)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'target_type' => 'required|in:product,vendor,sales_team,department,sales_rep',
            'target_entity_id' => 'required|integer',
            'target_entity_name' => 'required|string|max:255',
            'period_type' => 'required|in:monthly,quarterly,yearly',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'measurement_type' => 'required|in:quantity,value,both',
            'target_quantity' => 'nullable|numeric|min:0',
            'target_value' => 'nullable|numeric|min:0',
            'currency' => 'required|string|size:3',
            'unit' => 'nullable|string|max:50',
            'status' => 'required|in:active,completed,paused,cancelled',
            'notification_settings' => 'nullable|array',
            'notes' => 'nullable|string'
        ]);

        // Update year, month, quarter if dates changed
        $startDate = Carbon::parse($validated['start_date']);
        $validated['year'] = $startDate->year;
        
        if ($validated['period_type'] === 'monthly') {
            $validated['month'] = $startDate->month;
        } elseif ($validated['period_type'] === 'quarterly') {
            $validated['quarter'] = $startDate->quarter;
        }

        $validated['updated_by'] = auth()->id();

        $target->update($validated);

        return redirect()->route('tenant.sales.targets.show', $target)
                        ->with('success', 'تم تحديث الهدف بنجاح');
    }

    /**
     * Remove the specified sales target
     */
    public function destroy(SalesTarget $target)
    {
        $target->delete();

        return redirect()->route('tenant.sales.targets.index')
                        ->with('success', 'تم حذف الهدف بنجاح');
    }

    /**
     * Update target progress manually
     */
    public function updateProgress(Request $request, SalesTarget $target)
    {
        $validated = $request->validate([
            'quantity' => 'nullable|numeric|min:0',
            'value' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string'
        ]);

        $quantity = $validated['quantity'] ?? 0;
        $value = $validated['value'] ?? 0;

        $source = [
            'type' => 'manual',
            'id' => null,
            'details' => [
                'updated_by' => auth()->user()->name,
                'notes' => $validated['notes'] ?? null
            ]
        ];

        $target->updateProgress($quantity, $value, $source);

        return redirect()->back()->with('success', 'تم تحديث التقدم بنجاح');
    }

    /**
     * Get targets statistics
     */
    private function getTargetsStatistics()
    {
        $tenantId = auth()->user()->tenant_id;

        // Get all active targets for on_track calculation
        $activeTargets = SalesTarget::forTenant($tenantId)
                                   ->where('status', 'active')
                                   ->get();

        $onTrackCount = 0;
        foreach ($activeTargets as $target) {
            $totalDays = $target->start_date->diffInDays($target->end_date) + 1;
            $daysPassed = $target->start_date->diffInDays(Carbon::today()) + 1;
            $expectedProgress = ($daysPassed / $totalDays) * 100;

            if ($target->progress_percentage >= $expectedProgress) {
                $onTrackCount++;
            }
        }

        return [
            'total' => SalesTarget::forTenant($tenantId)->count(),
            'active' => SalesTarget::forTenant($tenantId)->where('status', 'active')->count(),
            'completed' => SalesTarget::forTenant($tenantId)->where('status', 'completed')->count(),
            'overdue' => SalesTarget::forTenant($tenantId)
                                   ->where('status', 'active')
                                   ->where('end_date', '<', Carbon::today())
                                   ->count(),
            'on_track' => $onTrackCount
        ];
    }

    /**
     * Get target progress statistics
     */
    private function getTargetProgressStats(SalesTarget $target)
    {
        $summary = SalesTargetProgress::getProgressSummary($target->id);
        
        return [
            'total_days_tracked' => $summary->total_days ?? 0,
            'avg_daily_quantity' => $summary->avg_daily_quantity ?? 0,
            'avg_daily_value' => $summary->avg_daily_value ?? 0,
            'best_day_quantity' => $target->progress()->orderByDesc('daily_quantity')->first()?->daily_quantity ?? 0,
            'best_day_value' => $target->progress()->orderByDesc('daily_value')->first()?->daily_value ?? 0,
            'days_remaining' => $target->remaining_days,
            'time_progress' => $target->time_progress_percentage
        ];
    }

    /**
     * Get progress chart data
     */
    private function getProgressChartData(SalesTarget $target)
    {
        $progress = $target->progress()
                          ->orderBy('progress_date')
                          ->get();

        return [
            'dates' => $progress->pluck('progress_date')->map(fn($date) => $date->format('Y-m-d')),
            'cumulative_quantity' => $progress->pluck('cumulative_quantity'),
            'cumulative_value' => $progress->pluck('cumulative_value'),
            'daily_quantity' => $progress->pluck('daily_quantity'),
            'daily_value' => $progress->pluck('daily_value'),
            'progress_percentage' => $progress->pluck('progress_percentage')
        ];
    }

    /**
     * Dashboard view for sales targets
     */
    public function dashboard()
    {
        $tenantId = auth()->user()->tenant_id;

        // Current active targets
        $activeTargets = SalesTarget::forTenant($tenantId)
                                   ->active()
                                   ->current()
                                   ->with(['progress' => function($query) {
                                       $query->latest('progress_date')->limit(1);
                                   }])
                                   ->get();

        // Statistics
        $stats = $this->getTargetsStatistics();

        // Performance summary
        $performanceSummary = $this->getPerformanceSummary();

        // Recent achievements
        $recentAchievements = SalesTarget::forTenant($tenantId)
                                        ->where('status', 'completed')
                                        ->orderBy('updated_at', 'desc')
                                        ->limit(5)
                                        ->get();

        return view('tenant.sales.targets.dashboard', compact(
            'activeTargets',
            'stats',
            'performanceSummary',
            'recentAchievements'
        ));
    }

    /**
     * Get performance summary
     */
    private function getPerformanceSummary()
    {
        $tenantId = auth()->user()->tenant_id;
        $currentMonth = Carbon::now();

        return [
            'monthly_targets' => SalesTarget::forTenant($tenantId)
                                           ->where('period_type', 'monthly')
                                           ->where('year', $currentMonth->year)
                                           ->where('month', $currentMonth->month)
                                           ->count(),
            'monthly_completed' => SalesTarget::forTenant($tenantId)
                                             ->where('period_type', 'monthly')
                                             ->where('year', $currentMonth->year)
                                             ->where('month', $currentMonth->month)
                                             ->where('status', 'completed')
                                             ->count(),
            'avg_progress' => SalesTarget::forTenant($tenantId)
                                        ->active()
                                        ->avg('progress_percentage') ?? 0
        ];
    }

    /**
     * Reports view
     */
    public function reports(Request $request)
    {
        $tenantId = auth()->user()->tenant_id;

        // Default to current year
        $year = $request->get('year', Carbon::now()->year);
        $targetType = $request->get('target_type');
        $periodType = $request->get('period_type');

        $query = SalesTarget::forTenant($tenantId)->where('year', $year);

        if ($targetType) {
            $query->where('target_type', $targetType);
        }

        if ($periodType) {
            $query->where('period_type', $periodType);
        }

        $targets = $query->with(['progress'])->get();

        // Generate report data
        $reportData = $this->generateReportData($targets);

        return view('tenant.sales.targets.reports', compact(
            'targets',
            'reportData',
            'year',
            'targetType',
            'periodType'
        ));
    }

    /**
     * Generate report data
     */
    private function generateReportData($targets)
    {
        $data = [
            'by_type' => [],
            'by_period' => [],
            'by_status' => [],
            'performance_metrics' => []
        ];

        foreach ($targets as $target) {
            // By type
            $type = $target->target_type;
            if (!isset($data['by_type'][$type])) {
                $data['by_type'][$type] = [
                    'count' => 0,
                    'total_target_value' => 0,
                    'total_achieved_value' => 0,
                    'avg_progress' => 0
                ];
            }

            $data['by_type'][$type]['count']++;
            $data['by_type'][$type]['total_target_value'] += $target->target_value ?? 0;
            $data['by_type'][$type]['total_achieved_value'] += $target->achieved_value ?? 0;
            $data['by_type'][$type]['avg_progress'] += $target->progress_percentage;

            // By period
            $period = $target->period_type;
            if (!isset($data['by_period'][$period])) {
                $data['by_period'][$period] = [
                    'count' => 0,
                    'completed' => 0,
                    'avg_progress' => 0
                ];
            }

            $data['by_period'][$period]['count']++;
            if ($target->status === 'completed') {
                $data['by_period'][$period]['completed']++;
            }
            $data['by_period'][$period]['avg_progress'] += $target->progress_percentage;

            // By status
            $status = $target->status;
            $data['by_status'][$status] = ($data['by_status'][$status] ?? 0) + 1;
        }

        // Calculate averages
        foreach ($data['by_type'] as $type => &$typeData) {
            $typeData['avg_progress'] = $typeData['count'] > 0 ?
                round($typeData['avg_progress'] / $typeData['count'], 2) : 0;
        }

        foreach ($data['by_period'] as $period => &$periodData) {
            $periodData['avg_progress'] = $periodData['count'] > 0 ?
                round($periodData['avg_progress'] / $periodData['count'], 2) : 0;
            $periodData['completion_rate'] = $periodData['count'] > 0 ?
                round(($periodData['completed'] / $periodData['count']) * 100, 2) : 0;
        }

        return $data;
    }
}
