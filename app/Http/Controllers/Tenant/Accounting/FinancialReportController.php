<?php

namespace App\Http\Controllers\Tenant\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Accounting\ChartOfAccount;
use App\Models\Accounting\JournalEntry;
use App\Models\Accounting\JournalEntryDetail;
use App\Models\Accounting\CostCenter;
use App\Traits\DatabaseAgnostic;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\Tenant\Accounting\TrialBalanceExport;
use App\Exports\Tenant\Accounting\IncomeStatementExport;
use App\Exports\Tenant\Accounting\BalanceSheetExport;
use App\Exports\Tenant\Accounting\CashFlowExport;
use App\Models\Warehouse;
use App\Models\Invoice;
use App\Models\InvoicePayment;
use Illuminate\Support\Str;


class FinancialReportController extends Controller
{
    use DatabaseAgnostic;
    /**
     * Display financial reports dashboard
     */
    public function index(): View
    {
        $user = Auth::user();
        $tenantId = $user->tenant_id;

        // Get summary statistics
        $totalAccounts = ChartOfAccount::where('tenant_id', $tenantId)->count();
        $activeAccounts = ChartOfAccount::where('tenant_id', $tenantId)->where('is_active', true)->count();
        $totalEntries = JournalEntry::where('tenant_id', $tenantId)->count();
        $pendingEntries = JournalEntry::where('tenant_id', $tenantId)->where('status', 'pending')->count();

        // Get monthly entries count for chart (Database agnostic)
        $monthlyEntries = $this->getMonthlyEntriesData($tenantId);

        return view('tenant.accounting.reports.index', compact(
            'totalAccounts', 'activeAccounts', 'totalEntries', 'pendingEntries', 'monthlyEntries'
        ));
    }

    /**
     * Trial Balance Report
     */
    public function trialBalance(Request $request): View
    {
        $user = Auth::user();
        $tenantId = $user->tenant_id;

        $dateFrom = $request->date_from ?? now()->startOfYear()->format('Y-m-d');
        $dateTo = $request->date_to ?? now()->format('Y-m-d');
        $costCenterId = $request->cost_center_id;
        $accountType = $request->account_type;

        $query = ChartOfAccount::where('tenant_id', $tenantId)
            ->where('is_active', true)
            ->where('is_parent', false); // Only leaf accounts

        if ($costCenterId) {
            $query->where('cost_center_id', $costCenterId);
        }

        if ($accountType) {
            $query->where('account_type', $accountType);
        }

        $accounts = $query->orderBy('account_code')->get();

        $trialBalanceData = [];
        $totalDebits = 0;
        $totalCredits = 0;

        foreach ($accounts as $account) {
            $balance = $account->getBalance($dateFrom, $dateTo);

            if ($balance != 0) {
                $debitBalance = 0;
                $creditBalance = 0;

                // Determine if balance is debit or credit based on account type
                if (in_array($account->account_type, ['asset', 'expense'])) {
                    $debitBalance = $balance > 0 ? $balance : 0;
                    $creditBalance = $balance < 0 ? abs($balance) : 0;
                } else {
                    $creditBalance = $balance > 0 ? $balance : 0;
                    $debitBalance = $balance < 0 ? abs($balance) : 0;
                }

                $trialBalanceData[] = [
                    'account' => $account,
                    'debit_balance' => $debitBalance,
                    'credit_balance' => $creditBalance
                ];

                $totalDebits += $debitBalance;
                $totalCredits += $creditBalance;
            }
        }

        $costCenters = CostCenter::where('tenant_id', $tenantId)
            ->where('is_active', true)
            ->orderBy('code')
            ->get();

        // Account types for filtering
        $accountTypes = [
            'asset' => 'الأصول',
            'liability' => 'الخصوم',
            'equity' => 'حقوق الملكية',
            'revenue' => 'الإيرادات',
            'expense' => 'المصروفات'
        ];

        return view('tenant.accounting.reports.trial-balance', compact(
            'trialBalanceData', 'totalDebits', 'totalCredits', 'dateFrom', 'dateTo',
            'costCenters', 'costCenterId', 'accountTypes', 'accountType'
        ));
    }

    /**
     * Income Statement Report
     */
    public function incomeStatement(Request $request): View
    {
        $user = Auth::user();
        $tenantId = $user->tenant_id;

        $startDate = $request->start_date ?? now()->startOfYear()->format('Y-m-d');
        $endDate = $request->end_date ?? now()->format('Y-m-d');
        $costCenterId = $request->cost_center_id;

        $dateFrom = $request->date_from ?? now()->startOfYear()->format('Y-m-d');
        $dateTo = $request->date_to ?? now()->format('Y-m-d');
        $costCenterId = $request->cost_center_id;

        // Get revenue accounts with balances
        $revenueAccounts = ChartOfAccount::where('tenant_id', $tenantId)
            ->where('account_type', 'revenue')
            ->where('is_active', true)
            ->orderBy('account_code')
            ->get()
            ->map(function ($account) use ($dateFrom, $dateTo) {
                $account->balance = abs($account->getBalance($dateFrom, $dateTo));
                return $account;
            })
            ->filter(function ($account) {
                return $account->balance > 0;
            });

        // Get expense accounts with balances
        $expenseAccounts = ChartOfAccount::where('tenant_id', $tenantId)
            ->where('account_type', 'expense')
            ->where('is_active', true)
            ->orderBy('account_code')
            ->get()
            ->map(function ($account) use ($dateFrom, $dateTo) {
                $account->balance = abs($account->getBalance($dateFrom, $dateTo));
                return $account;
            })
            ->filter(function ($account) {
                return $account->balance > 0;
            });

        $costCenters = CostCenter::where('tenant_id', $tenantId)
            ->where('is_active', true)
            ->orderBy('code')
            ->get();

        return view('tenant.accounting.reports.income-statement', compact(
            'revenueAccounts', 'expenseAccounts', 'dateFrom', 'dateTo', 'costCenters', 'costCenterId'
        ));
    }

    /**
     * Balance Sheet Report
     */
    public function balanceSheet(Request $request): View
    {
        $user = Auth::user();
        $tenantId = $user->tenant_id;

        $asOfDate = $request->as_of_date ?? now()->format('Y-m-d');
        $costCenterId = $request->cost_center_id;

        // Get current assets
        $currentAssets = ChartOfAccount::where('tenant_id', $tenantId)
            ->where('account_type', 'asset')
            ->where('account_category', 'current_asset')
            ->where('is_active', true)
            ->orderBy('account_code')
            ->get()
            ->map(function ($account) use ($asOfDate) {
                $account->balance = $account->getBalance(null, $asOfDate);
                return $account;
            })
            ->filter(function ($account) {
                return $account->balance != 0;
            });

        // Get non-current assets
        $nonCurrentAssets = ChartOfAccount::where('tenant_id', $tenantId)
            ->where('account_type', 'asset')
            ->where('account_category', 'non_current_asset')
            ->where('is_active', true)
            ->orderBy('account_code')
            ->get()
            ->map(function ($account) use ($asOfDate) {
                $account->balance = $account->getBalance(null, $asOfDate);
                return $account;
            })
            ->filter(function ($account) {
                return $account->balance != 0;
            });

        // Get current liabilities
        $currentLiabilities = ChartOfAccount::where('tenant_id', $tenantId)
            ->where('account_type', 'liability')
            ->where('account_category', 'current_liability')
            ->where('is_active', true)
            ->orderBy('account_code')
            ->get()
            ->map(function ($account) use ($asOfDate) {
                $account->balance = abs($account->getBalance(null, $asOfDate));
                return $account;
            })
            ->filter(function ($account) {
                return $account->balance > 0;
            });

        // Get non-current liabilities
        $nonCurrentLiabilities = ChartOfAccount::where('tenant_id', $tenantId)
            ->where('account_type', 'liability')
            ->where('account_category', 'non_current_liability')
            ->where('is_active', true)
            ->orderBy('account_code')
            ->get()
            ->map(function ($account) use ($asOfDate) {
                $account->balance = abs($account->getBalance(null, $asOfDate));
                return $account;
            })
            ->filter(function ($account) {
                return $account->balance > 0;
            });

        // Get equity accounts
        $equityAccounts = ChartOfAccount::where('tenant_id', $tenantId)
            ->where('account_type', 'equity')
            ->where('is_active', true)
            ->orderBy('account_code')
            ->get()
            ->map(function ($account) use ($asOfDate) {
                $account->balance = abs($account->getBalance(null, $asOfDate));
                return $account;
            })
            ->filter(function ($account) {
                return $account->balance > 0;
            });

        $costCenters = CostCenter::where('tenant_id', $tenantId)
            ->where('is_active', true)
            ->orderBy('code')
            ->get();

        return view('tenant.accounting.reports.balance-sheet', compact(
            'currentAssets', 'nonCurrentAssets', 'currentLiabilities', 'nonCurrentLiabilities',
            'equityAccounts', 'asOfDate', 'costCenters', 'costCenterId'
        ));
    }

    /**
     * Cash Flow Statement Report
     */
    public function cashFlow(Request $request): View
    {
        $user = Auth::user();
        $tenantId = $user->tenant_id;

        $dateFrom = $request->date_from ?? now()->startOfYear()->format('Y-m-d');
        $dateTo = $request->date_to ?? now()->format('Y-m-d');

        // Sample cash flow data (in real implementation, this would be calculated from journal entries)
        $operatingCashFlows = [
            'customer_receipts' => 500000,
            'supplier_payments' => 300000,
            'employee_payments' => 100000,
            'other_operating_payments' => 50000,
        ];

        $investingCashFlows = [
            'asset_purchases' => 200000,
            'asset_sales' => 50000,
            'investments' => 100000,
        ];

        $financingCashFlows = [
            'loans_received' => 300000,
            'loan_payments' => 150000,
            'capital_contributions' => 200000,
            'dividends_paid' => 75000,
        ];

        $cashBalances = [
            'beginning' => 100000,
        ];

        return view('tenant.accounting.reports.cash-flow', compact(
            'dateFrom', 'dateTo', 'operatingCashFlows', 'investingCashFlows',
            'financingCashFlows', 'cashBalances'
        ));

    }

    /**
     * Account Ledger Report
     */
    public function accountLedger(Request $request): View
    {
        $user = Auth::user();
        $tenantId = $user->tenant_id;

        $accountId = $request->account_id;
        $dateFrom = $request->date_from ?? now()->startOfMonth()->format('Y-m-d');
        $dateTo = $request->date_to ?? now()->format('Y-m-d');

        $account = null;
        $ledgerEntries = [];
        $openingBalance = 0;
        $totalDebits = 0;
        $totalCredits = 0;
        $closingBalance = 0;

        if ($accountId) {
            $account = ChartOfAccount::where('tenant_id', $tenantId)
                ->where('id', $accountId)
                ->first();

            if ($account) {
                // Get opening balance
                $openingBalance = $account->getBalance(null, $dateFrom);

                // Get ledger entries
                $entries = JournalEntryDetail::where('tenant_id', $tenantId)
                    ->where('account_id', $accountId)
                    ->whereHas('journalEntry', function($q) use ($dateFrom, $dateTo) {
                        $q->where('status', 'posted')
                          ->whereBetween('entry_date', [$dateFrom, $dateTo]);
                    })
                    ->with(['journalEntry'])
                    ->orderBy('created_at')
                    ->get();

                foreach ($entries as $entry) {
                    $ledgerEntries[] = [
                        'date' => $entry->journalEntry->entry_date,
                        'journal_number' => $entry->journalEntry->journal_number,
                        'journal_entry_id' => $entry->journal_entry_id,
                        'description' => $entry->description ?: $entry->journalEntry->description,
                        'debit_amount' => $entry->debit_amount,
                        'credit_amount' => $entry->credit_amount,
                    ];

                    $totalDebits += $entry->debit_amount;
                    $totalCredits += $entry->credit_amount;
                }

                // Calculate closing balance
                if ($account->account_type == 'asset' || $account->account_type == 'expense') {
                    $closingBalance = $openingBalance + $totalDebits - $totalCredits;
                } else {
                    $closingBalance = $openingBalance + $totalCredits - $totalDebits;
                }
            }
        }

        $accounts = ChartOfAccount::where('tenant_id', $tenantId)
            ->where('is_active', true)
            ->where('is_parent', false)
            ->orderBy('account_code')
            ->get();

        return view('tenant.accounting.reports.account-ledger', compact(
            'account', 'ledgerEntries', 'accounts', 'dateFrom', 'dateTo', 'accountId',
            'openingBalance', 'totalDebits', 'totalCredits', 'closingBalance'
        ));
    }

    /**
     * Get monthly entries data (database agnostic)
     */
    private function getMonthlyEntriesData($tenantId)
    {
        $yearFunction = $this->getYearFunction('entry_date');
        $monthFunction = $this->getMonthFunction('entry_date');

        return JournalEntry::where('tenant_id', $tenantId)
            ->where('entry_date', '>=', now()->subMonths(12))
            ->selectRaw("{$yearFunction} as year, {$monthFunction} as month, COUNT(*) as count")
            ->groupBy(DB::raw($yearFunction), DB::raw($monthFunction))
            ->orderBy(DB::raw($yearFunction), 'desc')
            ->orderBy(DB::raw($monthFunction), 'desc')
            ->get();
    }

    /**
     * Export Trial Balance to Excel
     */
    public function trialBalanceExcel(Request $request)
    {
        $user = Auth::user();
        $tenantId = $user->tenant_id;

        $dateFrom = $request->date_from ?? now()->startOfYear()->format('Y-m-d');
        $dateTo = $request->date_to ?? now()->format('Y-m-d');
        $costCenterId = $request->cost_center_id;
        $accountType = $request->account_type;

        $query = ChartOfAccount::where('tenant_id', $tenantId)
            ->where('is_active', true)
            ->where('is_parent', false);

        if ($costCenterId) {
            $query->where('cost_center_id', $costCenterId);
        }
        if ($accountType) {
            $query->where('account_type', $accountType);
        }

        $accounts = $query->orderBy('account_code')->get();

        $trialBalanceData = [];
        $totalDebits = 0;
        $totalCredits = 0;

        foreach ($accounts as $account) {
            $balance = $account->getBalance($dateFrom, $dateTo);
            if ($balance != 0) {
                $debitBalance = 0; $creditBalance = 0;
                if (in_array($account->account_type, ['asset','expense'])) {
                    $debitBalance = $balance > 0 ? $balance : 0;
                    $creditBalance = $balance < 0 ? abs($balance) : 0;
                } else {
                    $creditBalance = $balance > 0 ? $balance : 0;
                    $debitBalance = $balance < 0 ? abs($balance) : 0;
                }
                $trialBalanceData[] = [
                    'account' => $account,
                    'debit_balance' => $debitBalance,
                    'credit_balance' => $creditBalance,
                ];
                $totalDebits += $debitBalance; $totalCredits += $creditBalance;
            }
        }

        $costCenterName = null;
        if ($costCenterId) {
            $cc = CostCenter::where('tenant_id', $tenantId)->find($costCenterId);
            $costCenterName = $cc?->name;
        }

        $export = new TrialBalanceExport($trialBalanceData, $totalDebits, $totalCredits, $dateFrom, $dateTo, $costCenterName);
        $fileName = 'trial_balance_' . now()->format('Ymd_His') . '.xlsx';
        return Excel::download($export, $fileName);
    }

    /**
     * Export Income Statement to Excel
     */
    public function incomeStatementExcel(Request $request)
    {
        $user = Auth::user();
        $tenantId = $user->tenant_id;

        $dateFrom = $request->date_from ?? now()->startOfYear()->format('Y-m-d');
        $dateTo = $request->date_to ?? now()->format('Y-m-d');

        $revenueAccounts = \App\Models\Accounting\ChartOfAccount::where('tenant_id', $tenantId)
            ->where('account_type', 'revenue')
            ->where('is_active', true)
            ->orderBy('account_code')
            ->get()
            ->map(function ($account) use ($dateFrom, $dateTo) {
                $account->balance = abs($account->getBalance($dateFrom, $dateTo));
                return $account;
            })
            ->filter(function ($account) {
                return $account->balance > 0;
            });

        $expenseAccounts = \App\Models\Accounting\ChartOfAccount::where('tenant_id', $tenantId)
            ->where('account_type', 'expense')
            ->where('is_active', true)
            ->orderBy('account_code')
            ->get()
            ->map(function ($account) use ($dateFrom, $dateTo) {
                $account->balance = abs($account->getBalance($dateFrom, $dateTo));
                return $account;
            })
            ->filter(function ($account) {
                return $account->balance > 0;
            });

        $export = new \App\Exports\Tenant\Accounting\IncomeStatementExport($revenueAccounts, $expenseAccounts, $dateFrom, $dateTo);
        $fileName = 'income_statement_' . now()->format('Ymd_His') . '.xlsx';
        return \Maatwebsite\Excel\Facades\Excel::download($export, $fileName);
    }

    /**
     * Export Balance Sheet to Excel
     */
    public function balanceSheetExcel(Request $request)
    {
        $user = Auth::user();
        $tenantId = $user->tenant_id;

        $asOfDate = $request->as_of_date ?? now()->format('Y-m-d');

        $currentAssets = \App\Models\Accounting\ChartOfAccount::where('tenant_id', $tenantId)
            ->where('account_type', 'asset')->where('account_category', 'current_asset')
            ->where('is_active', true)->orderBy('account_code')->get()
            ->map(function ($acc) use ($asOfDate) { $acc->balance = $acc->getBalance(null, $asOfDate); return $acc; })
            ->filter(function ($acc) { return $acc->balance != 0; });

        $nonCurrentAssets = \App\Models\Accounting\ChartOfAccount::where('tenant_id', $tenantId)
            ->where('account_type', 'asset')->where('account_category', 'non_current_asset')
            ->where('is_active', true)->orderBy('account_code')->get()
            ->map(function ($acc) use ($asOfDate) { $acc->balance = $acc->getBalance(null, $asOfDate); return $acc; })
            ->filter(function ($acc) { return $acc->balance != 0; });

        $currentLiabilities = \App\Models\Accounting\ChartOfAccount::where('tenant_id', $tenantId)
            ->where('account_type', 'liability')->where('account_category', 'current_liability')
            ->where('is_active', true)->orderBy('account_code')->get()
            ->map(function ($acc) use ($asOfDate) { $acc->balance = $acc->getBalance(null, $asOfDate); return $acc; })
            ->filter(function ($acc) { return $acc->balance != 0; });

        $nonCurrentLiabilities = \App\Models\Accounting\ChartOfAccount::where('tenant_id', $tenantId)
            ->where('account_type', 'liability')->where('account_category', 'non_current_liability')
            ->where('is_active', true)->orderBy('account_code')->get()
            ->map(function ($acc) use ($asOfDate) { $acc->balance = $acc->getBalance(null, $asOfDate); return $acc; })
            ->filter(function ($acc) { return $acc->balance != 0; });

        $equityAccounts = \App\Models\Accounting\ChartOfAccount::where('tenant_id', $tenantId)
            ->where('account_type', 'equity')->where('is_active', true)
            ->orderBy('account_code')->get()
            ->map(function ($acc) use ($asOfDate) { $acc->balance = $acc->getBalance(null, $asOfDate); return $acc; })
            ->filter(function ($acc) { return $acc->balance != 0; });

        $export = new \App\Exports\Tenant\Accounting\BalanceSheetExport($asOfDate, $currentAssets, $nonCurrentAssets, $currentLiabilities, $nonCurrentLiabilities, $equityAccounts);
        $fileName = 'balance_sheet_' . now()->format('Ymd_His') . '.xlsx';
        return \Maatwebsite\Excel\Facades\Excel::download($export, $fileName);
    }

    /**
     * Export Cash Flow to Excel
     */
    public function cashFlowExcel(Request $request)
    {
        $user = Auth::user();
        $tenantId = $user->tenant_id;

        $dateFrom = $request->date_from ?? now()->startOfYear()->format('Y-m-d');
        $dateTo = $request->date_to ?? now()->format('Y-m-d');
        $method = $request->method ?? 'direct';

        // NOTE: current implementation uses sample figures already prepared in cashFlow()
        // For export, we replicate the same structure. In the next iteration, we'll compute from posted journal entries.
        $operating = [
            'customer_receipts' => (float) ($request->customer_receipts ?? 500000),
            'supplier_payments' => (float) ($request->supplier_payments ?? 300000),
            'employee_payments' => (float) ($request->employee_payments ?? 100000),
            'other_operating_payments' => (float) ($request->other_operating_payments ?? 50000),
        ];
        $investing = [
            'asset_purchases' => (float) ($request->asset_purchases ?? 200000),
            'asset_sales' => (float) ($request->asset_sales ?? 50000),
            'investments' => (float) ($request->investments ?? 100000),
        ];
        $financing = [
            'loans_received' => (float) ($request->loans_received ?? 300000),
            'loan_payments' => (float) ($request->loan_payments ?? 150000),
            'capital_contributions' => (float) ($request->capital_contributions ?? 200000),
            'dividends_paid' => (float) ($request->dividends_paid ?? 75000),
        ];

        $netOperating = $operating['customer_receipts'] - $operating['supplier_payments'] - $operating['employee_payments'] - $operating['other_operating_payments'];
        $netInvesting = $investing['asset_sales'] - $investing['asset_purchases'] - $investing['investments'];
        $netFinancing = $financing['loans_received'] + $financing['capital_contributions'] - $financing['loan_payments'] - $financing['dividends_paid'];

        $beginningCash = (float) ($request->beginning_cash ?? 100000);
        $endingCash = $beginningCash + $netOperating + $netInvesting + $netFinancing;

        $export = new \App\Exports\Tenant\Accounting\CashFlowExport($dateFrom, $dateTo, $method, $operating, $investing, $financing, $netOperating, $netInvesting, $netFinancing, $beginningCash, $endingCash);
        $fileName = 'cash_flow_' . now()->format('Ymd_His') . '.xlsx';
        return \Maatwebsite\Excel\Facades\Excel::download($export, $fileName);
    }

    /**
     * Custom Reports index: shows filters and report type selector
     */
    public function customReports(Request $request): View
    {
        $tenantId = Auth::user()->tenant_id;
        $dateFrom = $request->date_from ?? now()->startOfYear()->format('Y-m-d');
        $dateTo = $request->date_to ?? now()->format('Y-m-d');

        $costCenters = CostCenter::where('tenant_id', $tenantId)->where('is_active', true)->orderBy('code')->get();
        $warehouses = Warehouse::where('tenant_id', $tenantId)->where('is_active', true)->orderBy('code')->get();

        $reportTypes = [
            'revenue_by_cc_branch' => 'إيرادات حسب مركز تكلفة/فرع',
            'expense_by_cc_branch' => 'مصروفات حسب مركز تكلفة/فرع',
            'profitability' => 'الربحية (إيراد - مصروف)',
            'monthly_series' => 'تحليل شهري لإيرادات/مصروفات'
        ];

        $filters = [
            'costCenterId' => $request->cost_center_id,
            'warehouseId' => $request->warehouse_id,
        ];
        $type = $request->report_type ?? null;

        return view('tenant.accounting.reports.custom.index', compact(
            'dateFrom','dateTo','costCenters','warehouses','reportTypes','filters','type'
        ));
    }

    /**
     * Generate Custom Report (and optionally export to Excel)
     */
    public function generateCustomReport(Request $request)
    {
        $tenantId = Auth::user()->tenant_id;
        $type = $request->input('report_type');
        $dateFrom = $request->input('date_from') ?? now()->startOfYear()->format('Y-m-d');
        $dateTo = $request->input('date_to') ?? now()->format('Y-m-d');
        $costCenterId = $request->input('cost_center_id');
        $warehouseId = $request->input('warehouse_id');
        $export = $request->input('export') === 'excel';

        // Build base query on journal details joined to entries and accounts
        $details = \DB::table('journal_entry_details as d')
            ->join('journal_entries as e', 'd.journal_entry_id', '=', 'e.id')
            ->join('chart_of_accounts as a', 'd.account_id', '=', 'a.id')
            ->where('d.tenant_id', $tenantId)
            ->where('e.status', 'posted')
            ->whereBetween('e.entry_date', [$dateFrom, $dateTo]);

        if ($costCenterId) {
            $details->where('d.cost_center_id', $costCenterId);
        }
        $hasWarehouse = \Schema::hasColumn('journal_entries','warehouse_id');
        if ($warehouseId && $hasWarehouse) {
            $details->where('e.warehouse_id', $warehouseId);
        }

        $data = [];
        $meta = compact('dateFrom','dateTo','costCenterId','warehouseId','type');

        if ($type === 'revenue_by_cc_branch') {
            $selectWarehouse = $hasWarehouse ? 'e.warehouse_id' : 'NULL as warehouse_id';
            $rowsQuery = (clone $details)
                ->where('a.account_type', 'revenue')
                ->selectRaw('d.cost_center_id, ' . $selectWarehouse . ', SUM(d.credit_amount - d.debit_amount) as amount');
            if ($hasWarehouse) {
                $rowsQuery->groupBy('d.cost_center_id', 'e.warehouse_id');
            } else {
                $rowsQuery->groupBy('d.cost_center_id');
            }
            $rows = $rowsQuery->get();
            $data = ['rows' => $rows, 'label' => 'الإيرادات'];
        } elseif ($type === 'expense_by_cc_branch') {
            $selectWarehouse = $hasWarehouse ? 'e.warehouse_id' : 'NULL as warehouse_id';
            $rowsQuery = (clone $details)
                ->where('a.account_type', 'expense')
                ->selectRaw('d.cost_center_id, ' . $selectWarehouse . ', SUM(d.debit_amount - d.credit_amount) as amount');
            if ($hasWarehouse) {
                $rowsQuery->groupBy('d.cost_center_id', 'e.warehouse_id');
            } else {
                $rowsQuery->groupBy('d.cost_center_id');
            }
            $rows = $rowsQuery->get();
            $data = ['rows' => $rows, 'label' => 'المصروفات'];
        } elseif ($type === 'profitability') {
            $revenue = (clone $details)
                ->where('a.account_type', 'revenue')
                ->selectRaw('SUM(d.credit_amount - d.debit_amount) as amount')
                ->value('amount') ?? 0;
            $expense = (clone $details)
                ->where('a.account_type', 'expense')
                ->selectRaw('SUM(d.debit_amount - d.credit_amount) as amount')
                ->value('amount') ?? 0;
            $data = ['revenue' => (float)$revenue, 'expense' => (float)$expense, 'profit' => (float)$revenue - (float)$expense];
        } elseif ($type === 'monthly_series') {
            $series = (clone $details)
                ->selectRaw("DATE_FORMAT(e.entry_date, '%Y-%m') as ym, SUM(CASE WHEN a.account_type='revenue' THEN (d.credit_amount - d.debit_amount) ELSE 0 END) as revenue, SUM(CASE WHEN a.account_type='expense' THEN (d.debit_amount - d.credit_amount) ELSE 0 END) as expense")
                ->groupBy('ym')
                ->orderBy('ym')
                ->get();
            $data = ['series' => $series];
        } else {
            return back()->with('error', 'نوع التقرير غير مدعوم');
        }

        if ($export) {
            // Simple view-based export for now per type
            $view = 'tenant.accounting.reports.exports.custom-generic';
            return \Maatwebsite\Excel\Facades\Excel::download(new class($type, $data, $meta) implements \Maatwebsite\Excel\Concerns\FromView {
                public function __construct(public $type, public $data, public $meta) {}
                public function view(): \Illuminate\Contracts\View\View {
                    return view('tenant.accounting.reports.exports.custom-generic', [
                        'type' => $this->type,
                        'data' => $this->data,
                        'meta' => $this->meta,
                    ]);
                }
            }, 'custom_report_' . now()->format('Ymd_His') . '.xlsx');
        }

        // Render HTML view
        $costCenters = CostCenter::where('tenant_id', $tenantId)->where('is_active', true)->orderBy('code')->get();
        $warehouses = Warehouse::where('tenant_id', $tenantId)->where('is_active', true)->orderBy('code')->get();
        $reportTypes = [
            'revenue_by_cc_branch' => 'إيرادات حسب مركز تكلفة/فرع',
            'expense_by_cc_branch' => 'مصروفات حسب مركز تكلفة/فرع',
            'profitability' => 'الربحية (إيراد - مصروف)',
            'monthly_series' => 'تحليل شهري لإيرادات/مصروفات'
        ];

        return view('tenant.accounting.reports.custom.index', array_merge(
            compact('dateFrom','dateTo','costCenters','warehouses','reportTypes','type'),
            ['result' => $data, 'filters' => compact('costCenterId','warehouseId')]
        ));
    }

    /**
     * Export Account Ledger to Excel
     */
    public function accountLedgerExcel(Request $request)
    {
        $user = Auth::user();
        $tenantId = $user->tenant_id;

        $accountId = $request->account_id;
        $dateFrom = $request->date_from ?? now()->startOfMonth()->format('Y-m-d');
        $dateTo = $request->date_to ?? now()->format('Y-m-d');

        if (!$accountId) {
            return back()->with('error', 'يرجى اختيار الحساب أولاً');
        }

        $account = ChartOfAccount::where('tenant_id', $tenantId)->find($accountId);
        if (!$account) {
            return back()->with('error', 'لم يتم العثور على الحساب');
        }

        $openingBalance = $account->getBalance(null, $dateFrom);

        $entries = JournalEntryDetail::where('tenant_id', $tenantId)
            ->where('account_id', $accountId)
            ->whereHas('journalEntry', function($q) use ($dateFrom, $dateTo) {
                $q->where('status', 'posted')->whereBetween('entry_date', [$dateFrom, $dateTo]);
            })
            ->with(['journalEntry'])
            ->orderBy('created_at')
            ->get();

        $ledgerEntries = [];
        $totalDebits = 0; $totalCredits = 0;
        foreach ($entries as $entry) {
            $ledgerEntries[] = [
                'date' => $entry->journalEntry->entry_date,
                'journal_number' => $entry->journalEntry->journal_number,
                'journal_entry_id' => $entry->journal_entry_id,
                'description' => $entry->description ?: $entry->journalEntry->description,
                'debit_amount' => $entry->debit_amount,
                'credit_amount' => $entry->credit_amount,
            ];
            $totalDebits += $entry->debit_amount; $totalCredits += $entry->credit_amount;
        }

        if ($account->account_type == 'asset' || $account->account_type == 'expense') {
            $closingBalance = $openingBalance + $totalDebits - $totalCredits;
        } else {
            $closingBalance = $openingBalance + $totalCredits - $totalDebits;
        }

        $export = new \App\Exports\Tenant\Accounting\AccountLedgerExport($account, $ledgerEntries, $openingBalance, $totalDebits, $totalCredits, $closingBalance, $dateFrom, $dateTo);
        $fileName = 'account_ledger_' . ($account->account_code ?? 'acc') . '_' . now()->format('Ymd_His') . '.xlsx';
        return \Maatwebsite\Excel\Facades\Excel::download($export, $fileName);
    }

    /**
     * Export Trial Balance to PDF
     */
    public function trialBalancePdf(Request $request)
    {
        return redirect()->back()->with('info', 'تصدير PDF قيد التطوير - سيتم إضافته قريباً');
    }

    /**
     * Export Income Statement to PDF
     */
    public function incomeStatementPdf(Request $request)
    {
        return redirect()->back()->with('info', 'تصدير PDF قيد التطوير - سيتم إضافته قريباً');
    }

    /**
     * Export Balance Sheet to PDF
     */
    public function balanceSheetPdf(Request $request)
    {
        return redirect()->back()->with('info', 'تصدير PDF قيد التطوير - سيتم إضافته قريباً');
    }

    /**
     * Export Cash Flow to PDF
     */
    public function cashFlowPdf(Request $request)
    {
        return redirect()->back()->with('info', 'تصدير PDF قيد التطوير - سيتم إضافته قريباً');
    }

    /**
     * Export Account Ledger to PDF
     */
    public function accountLedgerPdf(Request $request)
    {
        return redirect()->back()->with('info', 'تصدير PDF قيد التطوير - سيتم إضافته قريباً');
    }
}
