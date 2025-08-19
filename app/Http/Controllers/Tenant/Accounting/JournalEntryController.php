<?php

namespace App\Http\Controllers\Tenant\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Accounting\JournalEntry;
use App\Models\Accounting\JournalEntryDetail;
use App\Models\Accounting\ChartOfAccount;
use App\Models\Accounting\CostCenter;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

class JournalEntryController extends Controller
{
    /**
     * Display a listing of journal entries
     */
    public function index(Request $request): View
    {
        $user = Auth::user();
        $tenantId = $user->tenant_id;

        $query = JournalEntry::where('tenant_id', $tenantId)
            ->with(['costCenter', 'creator', 'approver']);

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('entry_type')) {
            $query->where('entry_type', $request->entry_type);
        }

        if ($request->filled('date_from')) {
            $query->where('entry_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('entry_date', '<=', $request->date_to);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('journal_number', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('reference_number', 'like', "%{$search}%");
            });
        }

        $entries = $query->orderBy('entry_date', 'desc')
                        ->orderBy('journal_number', 'desc')
                        ->paginate(50);

        $statuses = JournalEntry::getStatuses();
        $types = JournalEntry::getTypes();

        return view('tenant.accounting.journal-entries.index', compact(
            'entries', 'statuses', 'types'
        ));
    }

    /**
     * Show the form for creating a new journal entry
     */
    public function create(): View
    {
        $user = Auth::user();
        $tenantId = $user->tenant_id;

        $accounts = ChartOfAccount::where('tenant_id', $tenantId)
            ->where('is_active', true)
            ->where('is_parent', false) // Only leaf accounts
            ->orderBy('account_code')
            ->get();

        $costCenters = CostCenter::where('tenant_id', $tenantId)
            ->where('is_active', true)
            ->orderBy('code')
            ->get();

        $types = JournalEntry::getTypes();

        return view('tenant.accounting.journal-entries.create', compact(
            'accounts', 'costCenters', 'types'
        ));
    }

    /**
     * Store a newly created journal entry
     */
    public function store(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $tenantId = $user->tenant_id;

        $request->validate([
            'entry_date' => 'required|date',
            'description' => 'required|string',
            'reference_number' => 'nullable|string|max:255',
            'entry_type' => 'required|in:manual,automatic,adjustment,closing,opening',
            'currency_code' => 'required|string|size:3',
            'exchange_rate' => 'required|numeric|min:0.0001',
            'cost_center_id' => 'nullable|exists:cost_centers,id',
            'notes' => 'nullable|string',
            'details' => 'required|array|min:2',
            'details.*.account_id' => 'required|exists:chart_of_accounts,id',
            'details.*.description' => 'nullable|string',
            'details.*.debit_amount' => 'required|numeric|min:0',
            'details.*.credit_amount' => 'required|numeric|min:0',
            'details.*.cost_center_id' => 'nullable|exists:cost_centers,id'
        ]);

        // Validate that each detail has either debit or credit (not both)
        foreach ($request->details as $index => $detail) {
            if (($detail['debit_amount'] > 0 && $detail['credit_amount'] > 0) ||
                ($detail['debit_amount'] == 0 && $detail['credit_amount'] == 0)) {
                return back()->withInput()
                    ->withErrors(["details.{$index}" => 'كل سطر يجب أن يحتوي على مدين أو دائن فقط']);
            }
        }

        // Validate that total debits equal total credits
        $totalDebits = collect($request->details)->sum('debit_amount');
        $totalCredits = collect($request->details)->sum('credit_amount');

        if (abs($totalDebits - $totalCredits) > 0.01) {
            return back()->withInput()
                ->withErrors(['details' => 'مجموع المدين يجب أن يساوي مجموع الدائن']);
        }

        try {
            DB::transaction(function () use ($request, $tenantId, $user) {
                // Create journal entry
                $entry = JournalEntry::create([
                    'tenant_id' => $tenantId,
                    'entry_date' => $request->entry_date,
                    'description' => $request->description,
                    'reference_number' => $request->reference_number,
                    'entry_type' => $request->entry_type,
                    'currency_code' => $request->currency_code,
                    'exchange_rate' => $request->exchange_rate,
                    'cost_center_id' => $request->cost_center_id,
                    'notes' => $request->notes,
                    'total_debit' => $totalDebits,
                    'total_credit' => $totalCredits,
                    'created_by' => $user->id,
                    'status' => JournalEntry::STATUS_DRAFT
                ]);

                // Create journal entry details
                foreach ($request->details as $index => $detail) {
                    JournalEntryDetail::create([
                        'tenant_id' => $tenantId,
                        'journal_entry_id' => $entry->id,
                        'account_id' => $detail['account_id'],
                        'description' => $detail['description'],
                        'debit_amount' => $detail['debit_amount'],
                        'credit_amount' => $detail['credit_amount'],
                        'currency_code' => $request->currency_code,
                        'exchange_rate' => $request->exchange_rate,
                        'cost_center_id' => $detail['cost_center_id'],
                        'line_number' => $index + 1
                    ]);
                }
            });

            foreach (['tenant.accounting.journal-entries.index', 'tenant.inventory.accounting.journal-entries.index', 'accounting.journal-entries.index'] as $routeName) {
                if (Route::has($routeName)) {
                    return redirect()->route($routeName)->with('success', 'تم إنشاء القيد المحاسبي بنجاح');
                }
            }
            return redirect('/tenant/accounting/journal-entries')->with('success', 'تم إنشاء القيد المحاسبي بنجاح');

        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'حدث خطأ أثناء إنشاء القيد المحاسبي: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified journal entry
     */
    public function show(JournalEntry $journalEntry): View
    {
        $user = Auth::user();
        
        if ($journalEntry->tenant_id !== $user->tenant_id) {
            abort(403);
        }

        $journalEntry->load([
            'details.account',
            'details.costCenter',
            'costCenter',
            'creator',
            'approver'
        ]);

        return view('tenant.accounting.journal-entries.show', compact('journalEntry'));
    }

    /**
     * Show the form for editing the specified journal entry
     */
    public function edit(JournalEntry $journalEntry): View
    {
        $user = Auth::user();
        
        if ($journalEntry->tenant_id !== $user->tenant_id) {
            abort(403);
        }

        if (!$journalEntry->canBeEdited()) {
            return redirect()->route('tenant.accounting.journal-entries.show', $journalEntry)
                ->with('error', 'لا يمكن تعديل هذا القيد في حالته الحالية');
        }

        $tenantId = $user->tenant_id;

        $accounts = ChartOfAccount::where('tenant_id', $tenantId)
            ->where('is_active', true)
            ->where('is_parent', false)
            ->orderBy('account_code')
            ->get();

        $costCenters = CostCenter::where('tenant_id', $tenantId)
            ->where('is_active', true)
            ->orderBy('code')
            ->get();

        $types = JournalEntry::getTypes();

        $journalEntry->load('details.account', 'details.costCenter');

        return view('tenant.accounting.journal-entries.edit', compact(
            'journalEntry', 'accounts', 'costCenters', 'types'
        ));
    }

    /**
     * Update the specified journal entry
     */
    public function update(Request $request, JournalEntry $journalEntry): RedirectResponse
    {
        $user = Auth::user();
        
        if ($journalEntry->tenant_id !== $user->tenant_id) {
            abort(403);
        }

        if (!$journalEntry->canBeEdited()) {
            return back()->with('error', 'لا يمكن تعديل هذا القيد في حالته الحالية');
        }

        $request->validate([
            'entry_date' => 'required|date',
            'description' => 'required|string',
            'reference_number' => 'nullable|string|max:255',
            'entry_type' => 'required|in:manual,automatic,adjustment,closing,opening',
            'currency_code' => 'required|string|size:3',
            'exchange_rate' => 'required|numeric|min:0.0001',
            'cost_center_id' => 'nullable|exists:cost_centers,id',
            'notes' => 'nullable|string',
            'details' => 'required|array|min:2',
            'details.*.account_id' => 'required|exists:chart_of_accounts,id',
            'details.*.description' => 'nullable|string',
            'details.*.debit_amount' => 'required|numeric|min:0',
            'details.*.credit_amount' => 'required|numeric|min:0',
            'details.*.cost_center_id' => 'nullable|exists:cost_centers,id'
        ]);

        // Validate balance
        $totalDebits = collect($request->details)->sum('debit_amount');
        $totalCredits = collect($request->details)->sum('credit_amount');

        if (abs($totalDebits - $totalCredits) > 0.01) {
            return back()->withInput()
                ->withErrors(['details' => 'مجموع المدين يجب أن يساوي مجموع الدائن']);
        }

        try {
            DB::transaction(function () use ($request, $journalEntry, $user) {
                // Update journal entry
                $journalEntry->update([
                    'entry_date' => $request->entry_date,
                    'description' => $request->description,
                    'reference_number' => $request->reference_number,
                    'entry_type' => $request->entry_type,
                    'currency_code' => $request->currency_code,
                    'exchange_rate' => $request->exchange_rate,
                    'cost_center_id' => $request->cost_center_id,
                    'notes' => $request->notes,
                    'total_debit' => $totalDebits,
                    'total_credit' => $totalCredits,
                    'updated_by' => $user->id
                ]);

                // Delete existing details
                $journalEntry->details()->delete();

                // Create new details
                foreach ($request->details as $index => $detail) {
                    JournalEntryDetail::create([
                        'tenant_id' => $journalEntry->tenant_id,
                        'journal_entry_id' => $journalEntry->id,
                        'account_id' => $detail['account_id'],
                        'description' => $detail['description'],
                        'debit_amount' => $detail['debit_amount'],
                        'credit_amount' => $detail['credit_amount'],
                        'currency_code' => $request->currency_code,
                        'exchange_rate' => $request->exchange_rate,
                        'cost_center_id' => $detail['cost_center_id'],
                        'line_number' => $index + 1
                    ]);
                }
            });

            foreach (['tenant.accounting.journal-entries.show', 'tenant.inventory.accounting.journal-entries.show', 'accounting.journal-entries.show'] as $routeName) {
                if (Route::has($routeName)) {
                    return redirect()->route($routeName, $journalEntry)->with('success', 'تم تحديث القيد المحاسبي بنجاح');
                }
            }
            return redirect('/tenant/accounting/journal-entries')->with('success', 'تم تحديث القيد المحاسبي بنجاح');

        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'حدث خطأ أثناء تحديث القيد المحاسبي: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified journal entry
     */
    public function destroy(JournalEntry $journalEntry): RedirectResponse
    {
        $user = Auth::user();

        if ($journalEntry->tenant_id !== $user->tenant_id) {
            abort(403);
        }

        if (!$journalEntry->canBeEdited()) {
            return back()->with('error', 'لا يمكن حذف هذا القيد في حالته الحالية');
        }

        try {
            $journalEntry->delete();

            foreach (['tenant.accounting.journal-entries.index', 'tenant.inventory.accounting.journal-entries.index', 'accounting.journal-entries.index'] as $routeName) {
                if (Route::has($routeName)) {
                    return redirect()->route($routeName)->with('success', 'تم حذف القيد المحاسبي بنجاح');
                }
            }
            return redirect('/tenant/accounting/journal-entries')->with('success', 'تم حذف القيد المحاسبي بنجاح');

        } catch (\Exception $e) {
            return back()->with('error', 'حدث خطأ أثناء حذف القيد المحاسبي: ' . $e->getMessage());
        }
    }

    /**
     * Submit journal entry for approval
     */
    public function submit(JournalEntry $journalEntry): RedirectResponse
    {
        $user = Auth::user();

        if ($journalEntry->tenant_id !== $user->tenant_id) {
            abort(403);
        }

        if ($journalEntry->status !== JournalEntry::STATUS_DRAFT) {
            return back()->with('error', 'يمكن إرسال المسودات فقط للاعتماد');
        }

        if (!$journalEntry->isBalanced()) {
            return back()->with('error', 'القيد غير متوازن - مجموع المدين يجب أن يساوي مجموع الدائن');
        }

        try {
            $journalEntry->update(['status' => JournalEntry::STATUS_PENDING]);

            return back()->with('success', 'تم إرسال القيد للاعتماد بنجاح');

        } catch (\Exception $e) {
            return back()->with('error', 'حدث خطأ أثناء إرسال القيد للاعتماد: ' . $e->getMessage());
        }
    }

    /**
     * Approve journal entry
     */
    public function approve(JournalEntry $journalEntry): RedirectResponse
    {
        $user = Auth::user();

        if ($journalEntry->tenant_id !== $user->tenant_id) {
            abort(403);
        }

        if (!$journalEntry->canBeApproved()) {
            return back()->with('error', 'لا يمكن اعتماد هذا القيد في حالته الحالية');
        }

        try {
            $journalEntry->approve($user->id);

            return back()->with('success', 'تم اعتماد القيد المحاسبي بنجاح');

        } catch (\Exception $e) {
            return back()->with('error', 'حدث خطأ أثناء اعتماد القيد المحاسبي: ' . $e->getMessage());
        }
    }

    /**
     * Reject journal entry
     */
    public function reject(JournalEntry $journalEntry): RedirectResponse
    {
        $user = Auth::user();

        if ($journalEntry->tenant_id !== $user->tenant_id) {
            abort(403);
        }

        if ($journalEntry->status !== JournalEntry::STATUS_PENDING) {
            return back()->with('error', 'يمكن رفض القيود المعلقة فقط');
        }

        try {
            $journalEntry->reject();

            return back()->with('success', 'تم رفض القيد المحاسبي');

        } catch (\Exception $e) {
            return back()->with('error', 'حدث خطأ أثناء رفض القيد المحاسبي: ' . $e->getMessage());
        }
    }

    /**
     * Post journal entry
     */
    public function post(JournalEntry $journalEntry): RedirectResponse
    {
        $user = Auth::user();

        if ($journalEntry->tenant_id !== $user->tenant_id) {
            abort(403);
        }

        if (!$journalEntry->canBePosted()) {
            return back()->with('error', 'لا يمكن ترحيل هذا القيد في حالته الحالية');
        }

        try {
            $journalEntry->post();

            return back()->with('success', 'تم ترحيل القيد المحاسبي بنجاح');

        } catch (\Exception $e) {
            return back()->with('error', 'حدث خطأ أثناء ترحيل القيد المحاسبي: ' . $e->getMessage());
        }
    }

    /**
     * Get account details for AJAX
     */
    public function getAccountDetails(Request $request): JsonResponse
    {
        $user = Auth::user();
        $tenantId = $user->tenant_id;

        $accountId = $request->account_id;

        $account = ChartOfAccount::where('tenant_id', $tenantId)
            ->where('id', $accountId)
            ->first();

        if (!$account) {
            return response()->json(['error' => 'Account not found'], 404);
        }

        return response()->json([
            'id' => $account->id,
            'code' => $account->account_code,
            'name' => $account->account_name,
            'type' => $account->account_type,
            'category' => $account->account_category,
            'currency' => $account->currency_code,
            'balance' => $account->current_balance
        ]);
    }
}
