<?php

namespace App\Services\Accounting;

use App\Models\Invoice;
use App\Models\Accounting\JournalEntry;
use App\Models\Accounting\JournalEntryDetail;
use App\Models\Accounting\ChartOfAccount;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SalesAccountingService
{
    /**
     * Post accounting journal entry for a sales invoice.
     * - Debit: Accounts Receivable
     * - Credit: Sales/Revenue
     * Returns created JournalEntry or null if posting is skipped.
     */
    public function postInvoiceEntry(Invoice $invoice): ?JournalEntry
    {
        $tenantId = $invoice->tenant_id;
        $amount = (float) ($invoice->total_amount ?? 0);
        if ($amount <= 0) return null;

        // Resolve accounts (config override -> auto-detect)
        $arAccount = $this->resolveAccountFromConfigOrAuto($tenantId, 'ar');
        $revAccount = $this->resolveAccountFromConfigOrAuto($tenantId, 'revenue');

        if (!$arAccount || !$revAccount) {
            Log::warning('SalesAccountingService: Missing COA mapping for posting invoice', [
                'tenant_id' => $tenantId,
                'ar_found' => (bool) $arAccount,
                'revenue_found' => (bool) $revAccount,
            ]);
            return null; // Skip posting if we cannot find accounts
        }

        return DB::transaction(function () use ($tenantId, $invoice, $arAccount, $revAccount, $amount) {
            $entry = JournalEntry::create([
                'tenant_id' => $tenantId,
                'journal_number' => null,
                'entry_date' => $invoice->invoice_date ?? now()->toDateString(),
                'reference_number' => $invoice->invoice_number,
                'description' => 'فاتورة مبيعات رقم ' . ($invoice->invoice_number ?? $invoice->id)
                    . ' - عميل: ' . ($invoice->customer->name ?? 'عميل'),
                'total_debit' => $amount,
                'total_credit' => $amount,
                'currency_code' => $invoice->currency ?? 'IQD',
                'exchange_rate' => $invoice->exchange_rate ?? 1.0000,
                'status' => JournalEntry::STATUS_APPROVED,
                'entry_type' => JournalEntry::TYPE_AUTOMATIC,
                'source_document_type' => 'invoice',
                'source_document_id' => $invoice->id,
                'created_by' => $invoice->created_by ?? ($invoice->sales_rep_id ?? null),
            ]);

            // Debit AR
            JournalEntryDetail::create([
                'tenant_id' => $tenantId,
                'journal_entry_id' => $entry->id,
                'account_id' => $arAccount->id,
                'description' => 'ذمم مدينة - ' . ($invoice->customer->name ?? 'عميل'),
                'debit_amount' => $amount,
                'credit_amount' => 0,
                'currency_code' => $entry->currency_code,
                'exchange_rate' => $entry->exchange_rate,
            ]);

            // Credit Revenue
            JournalEntryDetail::create([
                'tenant_id' => $tenantId,
                'journal_entry_id' => $entry->id,
                'account_id' => $revAccount->id,
                'description' => 'إيرادات/مبيعات',
                'debit_amount' => 0,
                'credit_amount' => $amount,
                'currency_code' => $entry->currency_code,
                'exchange_rate' => $entry->exchange_rate,
            ]);

            $entry->refresh();
            $entry->load('details');
            $entry->calculateTotals();

            // Auto-post the entry so it appears in financial reports immediately
            try {
                if ($entry->canBePosted()) {
                    $entry->post();
                }
            } catch (\Throwable $e) {
                Log::warning('SalesAccountingService: auto-post failed', [
                    'entry_id' => $entry->id,
                    'error' => $e->getMessage(),
                ]);
            }

            return $entry;
        });
    }

    protected function resolveAccountFromConfigOrAuto(int $tenantId, string $type)
    {
        $code = null;
        if ($type === 'ar') $code = config('receivables.ar_account_code');
        if ($type === 'revenue') $code = config('sales.revenue_account_code');

        if ($code) {
            $acc = ChartOfAccount::where('tenant_id', $tenantId)->where('account_code', $code)->first();
            if ($acc) return $acc;
        }

        // Auto-detect
        $query = ChartOfAccount::where('tenant_id', $tenantId)->where('is_active', true)->orderBy('account_code');
        if ($type === 'ar') {
            $acc = (clone $query)->whereIn('account_category', ['accounts_receivable', 'trade_receivables'])->first();
            if ($acc) return $acc;
            return (clone $query)->where(function($q){
                $q->where('account_name', 'like', '%Receivable%')
                  ->orWhere('account_name', 'like', '%ذمم%')
                  ->orWhere('account_name', 'like', '%ذمم مدينة%');
            })->first();
        } else { // revenue
            $acc = (clone $query)->whereIn('account_type', ['revenue'])->first();
            if ($acc) return $acc;
            return (clone $query)->where(function($q){
                $q->where('account_name', 'like', '%Revenue%')
                  ->orWhere('account_name', 'like', '%Sales%')
                  ->orWhere('account_name', 'like', '%إيراد%')
                  ->orWhere('account_name', 'like', '%مبيعات%');
            })->first();
        }
    }
}

