<?php

namespace App\Http\Controllers\Tenant\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Accounting\ChartOfAccount;
use App\Models\Accounting\CostCenter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ChartOfAccountController extends Controller
{
    /**
     * Display a listing of accounts
     */
    public function index(Request $request): View
    {
        $user = Auth::user();
        $tenantId = $user->tenant_id;

        $query = ChartOfAccount::where('tenant_id', $tenantId)
            ->with(['parentAccount', 'costCenter']);

        // Apply filters
        if ($request->filled('account_type')) {
            $query->where('account_type', $request->account_type);
        }

        if ($request->filled('account_category')) {
            $query->where('account_category', $request->account_category);
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('account_name', 'like', "%{$search}%")
                  ->orWhere('account_code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $accounts = $query->orderBy('account_code')->paginate(50);

        $accountTypes = ChartOfAccount::getAccountTypes();
        $accountCategories = ChartOfAccount::getAccountCategories();

        return view('tenant.accounting.chart-of-accounts.index', compact(
            'accounts', 'accountTypes', 'accountCategories'
        ));
    }

    /**
     * Show the form for creating a new account
     */
    public function create(): View
    {
        $user = Auth::user();
        $tenantId = $user->tenant_id;

        $parentAccounts = ChartOfAccount::where('tenant_id', $tenantId)
            ->where('is_active', true)
            ->orderBy('account_code')
            ->get();

        $costCenters = CostCenter::where('tenant_id', $tenantId)
            ->where('is_active', true)
            ->orderBy('code')
            ->get();

        $accountTypes = ChartOfAccount::getAccountTypes();
        $accountCategories = ChartOfAccount::getAccountCategories();

        return view('tenant.accounting.chart-of-accounts.create', compact(
            'parentAccounts', 'costCenters', 'accountTypes', 'accountCategories'
        ));
    }

    /**
     * Store a newly created account
     */
    public function store(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $tenantId = $user->tenant_id;

        $request->validate([
            'account_name' => 'required|string|max:255',
            'account_name_en' => 'nullable|string|max:255',
            'account_type' => 'required|in:asset,liability,equity,revenue,expense',
            'account_category' => 'required|string',
            'parent_account_id' => 'nullable|exists:chart_of_accounts,id',
            'description' => 'nullable|string',
            'opening_balance' => 'nullable|numeric',
            'currency_code' => 'required|string|size:3',
            'cost_center_id' => 'nullable|exists:cost_centers,id',
            'is_active' => 'boolean'
        ]);

        try {
            DB::transaction(function () use ($request, $tenantId, $user) {
                $account = ChartOfAccount::create([
                    'tenant_id' => $tenantId,
                    'account_name' => $request->account_name,
                    'account_name_en' => $request->account_name_en,
                    'account_type' => $request->account_type,
                    'account_category' => $request->account_category,
                    'parent_account_id' => $request->parent_account_id,
                    'description' => $request->description,
                    'opening_balance' => $request->opening_balance ?? 0,
                    'current_balance' => $request->opening_balance ?? 0,
                    'currency_code' => $request->currency_code,
                    'cost_center_id' => $request->cost_center_id,
                    'is_active' => $request->boolean('is_active', true),
                    'created_by' => $user->id
                ]);

                // Update parent account to be a parent if it has children
                if ($account->parent_account_id) {
                    $parent = ChartOfAccount::find($account->parent_account_id);
                    if ($parent && !$parent->is_parent) {
                        $parent->update(['is_parent' => true]);
                    }
                }
            });

            // Use resolved prefix: if current route is within 'tenant.' group then prefix with 'tenant.'
            $name = Route::currentRouteName();
            $prefix = str_starts_with($name, 'tenant.') ? 'tenant.' : '';
            return redirect()->route($prefix . 'accounting.chart-of-accounts.index')
                ->with('success', 'تم إنشاء الحساب بنجاح');

        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'حدث خطأ أثناء إنشاء الحساب: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified account
     */
    public function show(ChartOfAccount $chartOfAccount): View
    {
        $user = Auth::user();
        
        if ($chartOfAccount->tenant_id !== $user->tenant_id) {
            abort(403);
        }

        $chartOfAccount->load(['parentAccount', 'childAccounts', 'costCenter']);

        // Get recent journal entry details
        $recentEntries = $chartOfAccount->getRecentEntries(10);

        // Calculate balance for different periods
        $currentBalance = $chartOfAccount->getBalance();
        $monthlyBalance = $chartOfAccount->getBalance(now()->startOfMonth(), now());
        $yearlyBalance = $chartOfAccount->getBalance(now()->startOfYear(), now());

        return view('tenant.accounting.chart-of-accounts.show', compact(
            'chartOfAccount', 'recentEntries', 'currentBalance', 'monthlyBalance', 'yearlyBalance'
        ));
    }

    /**
     * Show the form for editing the specified account
     */
    public function edit(ChartOfAccount $chartOfAccount): View
    {
        $user = Auth::user();
        
        if ($chartOfAccount->tenant_id !== $user->tenant_id) {
            abort(403);
        }

        $tenantId = $user->tenant_id;

        $parentAccounts = ChartOfAccount::where('tenant_id', $tenantId)
            ->where('id', '!=', $chartOfAccount->id)
            ->where('is_active', true)
            ->orderBy('account_code')
            ->get();

        $costCenters = CostCenter::where('tenant_id', $tenantId)
            ->where('is_active', true)
            ->orderBy('code')
            ->get();

        $accountTypes = ChartOfAccount::getAccountTypes();
        $accountCategories = ChartOfAccount::getAccountCategories();

        return view('tenant.accounting.chart-of-accounts.edit', compact(
            'chartOfAccount', 'parentAccounts', 'costCenters', 'accountTypes', 'accountCategories'
        ));
    }

    /**
     * Update the specified account
     */
    public function update(Request $request, ChartOfAccount $chartOfAccount): RedirectResponse
    {
        $user = Auth::user();
        
        if ($chartOfAccount->tenant_id !== $user->tenant_id) {
            abort(403);
        }

        $request->validate([
            'account_name' => 'required|string|max:255',
            'account_name_en' => 'nullable|string|max:255',
            'account_type' => 'required|in:asset,liability,equity,revenue,expense',
            'account_category' => 'required|string',
            'parent_account_id' => 'nullable|exists:chart_of_accounts,id',
            'description' => 'nullable|string',
            'currency_code' => 'required|string|size:3',
            'cost_center_id' => 'nullable|exists:cost_centers,id',
            'is_active' => 'boolean'
        ]);

        try {
            $chartOfAccount->update([
                'account_name' => $request->account_name,
                'account_name_en' => $request->account_name_en,
                'account_type' => $request->account_type,
                'account_category' => $request->account_category,
                'parent_account_id' => $request->parent_account_id,
                'description' => $request->description,
                'currency_code' => $request->currency_code,
                'cost_center_id' => $request->cost_center_id,
                'is_active' => $request->boolean('is_active', true),
                'updated_by' => $user->id
            ]);

            $name = Route::currentRouteName();
            $prefix = str_starts_with($name, 'tenant.') ? 'tenant.' : '';
            return redirect()->route($prefix . 'accounting.chart-of-accounts.index')
                ->with('success', 'تم تحديث الحساب بنجاح');

        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'حدث خطأ أثناء تحديث الحساب: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified account
     */
    public function destroy(ChartOfAccount $chartOfAccount): RedirectResponse
    {
        $user = Auth::user();
        
        if ($chartOfAccount->tenant_id !== $user->tenant_id) {
            abort(403);
        }

        if (!$chartOfAccount->canBeDeleted()) {
            return back()->with('error', 'لا يمكن حذف هذا الحساب لأنه يحتوي على قيود محاسبية أو حسابات فرعية');
        }

        try {
            $chartOfAccount->delete();

            $name = Route::currentRouteName();
            $prefix = str_starts_with($name, 'tenant.') ? 'tenant.' : '';
            return redirect()->route($prefix . 'accounting.chart-of-accounts.index')
                ->with('success', 'تم حذف الحساب بنجاح');

        } catch (\Exception $e) {
            return back()->with('error', 'حدث خطأ أثناء حذف الحساب: ' . $e->getMessage());
        }
    }

    /**
     * Get accounts tree structure (AJAX)
     */
    public function getAccountsTree(): JsonResponse
    {
        $user = Auth::user();
        $tenantId = $user->tenant_id;

        $accounts = ChartOfAccount::where('tenant_id', $tenantId)
            ->where('is_active', true)
            ->orderBy('account_code')
            ->get();

        $tree = $this->buildAccountsTree($accounts);

        return response()->json($tree);
    }

    /**
     * Build accounts tree structure
     */
    private function buildAccountsTree($accounts, $parentId = null): array
    {
        $tree = [];

        foreach ($accounts as $account) {
            if ($account->parent_account_id == $parentId) {
                $node = [
                    'id' => $account->id,
                    'code' => $account->account_code,
                    'name' => $account->account_name,
                    'type' => $account->account_type,
                    'balance' => $account->current_balance,
                    'children' => $this->buildAccountsTree($accounts, $account->id)
                ];
                $tree[] = $node;
            }
        }

        return $tree;
    }

    /**
     * Get account balance (AJAX)
     */
    public function getAccountBalance(Request $request, ChartOfAccount $chartOfAccount): JsonResponse
    {
        $user = Auth::user();

        if ($chartOfAccount->tenant_id !== $user->tenant_id) {
            abort(403);
        }

        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $balance = $chartOfAccount->getBalance($startDate, $endDate);

        return response()->json([
            'balance' => $balance,
            'formatted_balance' => number_format($balance, 2),
            'currency' => $chartOfAccount->currency_code
        ]);
    }
}
