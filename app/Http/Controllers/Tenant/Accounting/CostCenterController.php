<?php

namespace App\Http\Controllers\Tenant\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Accounting\CostCenter;
use App\Models\Accounting\ChartOfAccount;
use App\Models\Accounting\JournalEntry;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CostCenterController extends Controller
{
    /**
     * Display a listing of cost centers
     */
    public function index(Request $request): View
    {
        $user = Auth::user();
        $tenantId = $user->tenant_id;

        $query = CostCenter::where('tenant_id', $tenantId)
            ->with(['parentCostCenter']);

        // Apply filters
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $costCenters = $query->orderBy('code')->paginate(50);

        // Add safe methods to each cost center for the view
        foreach ($costCenters as $costCenter) {
            $costCenter->safeAccountsCount = $this->getSafeAccountsCount($costCenter);
            $costCenter->safeJournalEntriesCount = $this->getSafeJournalEntriesCount($costCenter);
        }

        return view('tenant.accounting.cost-centers.index', compact('costCenters'));
    }

    /**
     * Safely get accounts count (handles missing column)
     */
    private function getSafeAccountsCount($costCenter)
    {
        try {
            return ChartOfAccount::where('cost_center_id', $costCenter->id)->count();
        } catch (\Exception $e) {
            return 0; // Return 0 if column doesn't exist
        }
    }

    /**
     * Safely get journal entries count (handles missing column)
     */
    private function getSafeJournalEntriesCount($costCenter)
    {
        try {
            return JournalEntry::where('cost_center_id', $costCenter->id)->count();
        } catch (\Exception $e) {
            return 0; // Return 0 if column doesn't exist
        }
    }

    /**
     * Show the form for creating a new cost center
     */
    public function create(): View
    {
        $user = Auth::user();
        $tenantId = $user->tenant_id;

        $parentCostCenters = CostCenter::where('tenant_id', $tenantId)
            ->where('is_active', true)
            ->orderBy('code')
            ->get();

        return view('tenant.accounting.cost-centers.create', compact('parentCostCenters'));
    }

    /**
     * Store a newly created cost center
     */
    public function store(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $tenantId = $user->tenant_id;

        $request->validate([
            'name' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'parent_cost_center_id' => 'nullable|exists:cost_centers,id',
            'manager_name' => 'nullable|string|max:255',
            'manager_email' => 'nullable|email|max:255',
            'budget_amount' => 'nullable|numeric|min:0',
            'currency_code' => 'required|string|size:3',
            'is_active' => 'boolean'
        ]);

        try {
            CostCenter::create([
                'tenant_id' => $tenantId,
                'name' => $request->get('name'),
                'name_en' => $request->get('name_en'),
                'description' => $request->get('description'),
                'parent_id' => $request->get('parent_cost_center_id'),
                'budget_amount' => $request->get('budget_amount', 0),
                'currency_code' => $request->get('currency_code', 'IQD'),
                'is_active' => $request->boolean('is_active', true),
                'created_by' => $user->id
            ]);

            return redirect()->route('tenant.accounting.cost-centers.index')
                ->with('success', 'تم إنشاء مركز التكلفة بنجاح');

        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'حدث خطأ أثناء إنشاء مركز التكلفة: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified cost center
     */
    public function show(CostCenter $costCenter): View
    {
        $user = Auth::user();
        
        if ($costCenter->getAttribute('tenant_id') !== $user->tenant_id) {
            abort(403);
        }

        $costCenter->load(['parentCostCenter', 'childCostCenters', 'accounts']);

        // Get budget analysis
        $actualAmount = $costCenter->calculateActualAmount();
        $budgetVariance = $costCenter->getBudgetVariance();
        $budgetVariancePercentage = $costCenter->getBudgetVariancePercentage();

        // Get recent journal entries
        // Get related accounts (with fallback for missing column)
        try {
            $relatedAccounts = ChartOfAccount::where('cost_center_id', $costCenter->id)
                ->where('is_active', true)
                ->orderBy('account_code')
                ->get();
        } catch (\Exception $e) {
            // Fallback if cost_center_id column doesn't exist yet
            $relatedAccounts = collect();
        }

        // Get recent journal entries
        $recentEntries = JournalEntry::where('cost_center_id', $costCenter->id)
            ->where('status', 'posted')
            ->orderBy('entry_date', 'desc')
            ->limit(10)
            ->get();

        return view('tenant.accounting.cost-centers.show', compact(
            'costCenter', 'actualAmount', 'budgetVariance', 'budgetVariancePercentage', 'recentEntries', 'relatedAccounts'
        ));
    }

    /**
     * Show the form for editing the specified cost center
     */
    public function edit(CostCenter $costCenter): View
    {
        $user = Auth::user();
        
        if ($costCenter->tenant_id !== $user->tenant_id) {
            abort(403);
        }

        $tenantId = $user->tenant_id;

        $parentCostCenters = CostCenter::where('tenant_id', $tenantId)
            ->where('id', '!=', $costCenter->id)
            ->where('is_active', true)
            ->orderBy('code')
            ->get();

        return view('tenant.accounting.cost-centers.edit', compact('costCenter', 'parentCostCenters'));
    }

    /**
     * Update the specified cost center
     */
    public function update(Request $request, CostCenter $costCenter): RedirectResponse
    {
        $user = Auth::user();
        
        if ($costCenter->tenant_id !== $user->tenant_id) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'parent_cost_center_id' => 'nullable|exists:cost_centers,id',
            'manager_name' => 'nullable|string|max:255',
            'manager_email' => 'nullable|email|max:255',
            'budget_amount' => 'nullable|numeric|min:0',
            'currency_code' => 'required|string|size:3',
            'is_active' => 'boolean'
        ]);

        try {
            $costCenter->update([
                'name' => $request->get('name'),
                'name_en' => $request->get('name_en'),
                'description' => $request->get('description'),
                'parent_id' => $request->get('parent_cost_center_id'),
                'budget_amount' => $request->get('budget_amount', 0),
                'currency_code' => $request->get('currency_code', 'IQD'),
                'is_active' => $request->boolean('is_active', true)
            ]);

            return redirect()->route('tenant.accounting.cost-centers.index')
                ->with('success', 'تم تحديث مركز التكلفة بنجاح');

        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'حدث خطأ أثناء تحديث مركز التكلفة: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified cost center
     */
    public function destroy(CostCenter $costCenter): RedirectResponse
    {
        $user = Auth::user();
        
        if ($costCenter->tenant_id !== $user->tenant_id) {
            abort(403);
        }

        // Check if cost center has accounts or journal entries (with fallback)
        try {
            $hasAccounts = ChartOfAccount::where('cost_center_id', $costCenter->id)->count() > 0;
            $hasJournalEntries = JournalEntry::where('cost_center_id', $costCenter->id)->count() > 0;

            if ($hasAccounts || $hasJournalEntries) {
                return back()->with('error', 'لا يمكن حذف مركز التكلفة لأنه مرتبط بحسابات أو قيود محاسبية');
            }
        } catch (\Exception $e) {
            // If columns don't exist yet, allow deletion
        }

        // Check if cost center has child cost centers
        if ($costCenter->childCostCenters()->count() > 0) {
            return back()->with('error', 'لا يمكن حذف مركز التكلفة لأنه يحتوي على مراكز تكلفة فرعية');
        }

        try {
            $costCenter->delete();

            return redirect()->route('tenant.accounting.cost-centers.index')
                ->with('success', 'تم حذف مركز التكلفة بنجاح');

        } catch (\Exception $e) {
            return back()->with('error', 'حدث خطأ أثناء حذف مركز التكلفة: ' . $e->getMessage());
        }
    }

    /**
     * Get cost centers tree structure (AJAX)
     */
    public function getCostCentersTree(): JsonResponse
    {
        $user = Auth::user();
        $tenantId = $user->tenant_id;

        $costCenters = CostCenter::where('tenant_id', $tenantId)
            ->where('is_active', true)
            ->orderBy('code')
            ->get();

        $tree = $this->buildCostCentersTree($costCenters);

        return response()->json($tree);
    }

    /**
     * Build cost centers tree structure
     */
    private function buildCostCentersTree($costCenters, $parentId = null): array
    {
        $tree = [];

        foreach ($costCenters as $costCenter) {
            if ($costCenter->parent_cost_center_id == $parentId) {
                $node = [
                    'id' => $costCenter->id,
                    'code' => $costCenter->code,
                    'name' => $costCenter->name,
                    'budget_amount' => $costCenter->budget_amount,
                    'actual_amount' => $costCenter->actual_amount,
                    'variance' => $costCenter->getBudgetVariance(),
                    'children' => $this->buildCostCentersTree($costCenters, $costCenter->id)
                ];
                $tree[] = $node;
            }
        }

        return $tree;
    }

    /**
     * Get cost center budget analysis (AJAX)
     */
    public function getBudgetAnalysis(Request $request, CostCenter $costCenter): JsonResponse
    {
        $user = Auth::user();
        
        if ($costCenter->tenant_id !== $user->tenant_id) {
            abort(403);
        }

        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        $actualAmount = $costCenter->calculateActualAmount($startDate, $endDate);
        $budgetAmount = $costCenter->budget_amount;
        $variance = $budgetAmount - $actualAmount;
        $variancePercentage = $budgetAmount > 0 ? (($actualAmount - $budgetAmount) / $budgetAmount) * 100 : 0;

        return response()->json([
            'budget_amount' => $budgetAmount,
            'actual_amount' => $actualAmount,
            'variance' => $variance,
            'variance_percentage' => $variancePercentage,
            'formatted_budget' => number_format((float)$budgetAmount, 2),
            'formatted_actual' => number_format((float)$actualAmount, 2),
            'formatted_variance' => number_format((float)$variance, 2),
            'currency' => $costCenter->currency_code ?? 'IQD'
        ]);
    }
}
