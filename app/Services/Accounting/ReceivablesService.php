<?php

namespace App\Services\Accounting;

use App\Models\Invoice;
use App\Models\InvoicePayment;
use App\Models\Customer;
use App\Models\Accounting\JournalEntry;
use App\Models\Accounting\JournalEntryDetail;
use App\Models\Accounting\ChartOfAccount;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReceivablesService
{
    /**
     * Post accounting journal entry for an invoice payment.
     * - Debit: Cash/Bank
     * - Credit: Accounts Receivable
     *
     * Returns created JournalEntry or null if posting is skipped.
     */
    public function postPaymentEntry(Invoice $invoice, InvoicePayment $payment): ?JournalEntry
    {
        $tenantId = $invoice->tenant_id;
        $amount = (float) $payment->amount;
        if ($amount <= 0) return null;

        // Resolve accounts (config override -> auto-detect)
        $cashAccount = $this->resolveAccountFromConfigOrAuto($tenantId, 'cash');
        $arAccount   = $this->resolveAccountFromConfigOrAuto($tenantId, 'ar');

        if (!$cashAccount || !$arAccount) {
            Log::warning('ReceivablesService: Missing COA mapping for posting payment', [
                'tenant_id' => $tenantId,
                'cash_found' => (bool) $cashAccount,
                'ar_found' => (bool) $arAccount,
            ]);
            return null; // Skip posting if we cannot find accounts
        }

        return DB::transaction(function () use ($tenantId, $invoice, $payment, $cashAccount, $arAccount, $amount) {
            $entry = JournalEntry::create([
                'tenant_id' => $tenantId,
                'journal_number' => null,
                'entry_date' => $payment->payment_date ?? now()->toDateString(),
                'reference_number' => $payment->reference_number,
                'description' => 'تحصيل فاتورة رقم ' . ($invoice->invoice_number ?? $invoice->id),
                'total_debit' => $amount,
                'total_credit' => $amount,
                'currency_code' => $invoice->currency ?? 'IQD',
                'exchange_rate' => $invoice->exchange_rate ?? 1.0000,
                'status' => JournalEntry::STATUS_APPROVED,
                'entry_type' => JournalEntry::TYPE_AUTOMATIC,
                'source_document_type' => 'invoice_payment',
                'source_document_id' => $payment->id,
                'created_by' => $payment->created_by,
            ]);

            // Debit Cash/Bank
            JournalEntryDetail::create([
                'tenant_id' => $tenantId,
                'journal_entry_id' => $entry->id,
                'account_id' => $cashAccount->id,
                'description' => 'تحصيل نقدي/بنكي',
                'debit_amount' => $amount,
                'credit_amount' => 0,
                'currency_code' => $entry->currency_code,
                'exchange_rate' => $entry->exchange_rate,
            ]);

            // Credit Accounts Receivable
            JournalEntryDetail::create([
                'tenant_id' => $tenantId,
                'journal_entry_id' => $entry->id,
                'account_id' => $arAccount->id,
                'description' => 'ذمم مدينة - ' . ($invoice->customer->name ?? 'عميل'),
                'debit_amount' => 0,
                'credit_amount' => $amount,
                'currency_code' => $entry->currency_code,
                'exchange_rate' => $entry->exchange_rate,
            ]);

            $entry->refresh();
            $entry->calculateTotals();

            return $entry;
        });
    }

    /**
     * Reduce customer balance upon payment
     */
    public function applyPaymentToCustomer(Customer $customer, float $amount): void
    {
        try {
            $customer->updateBalance($amount, 'subtract');
        } catch (\Throwable $e) {
            Log::warning('ReceivablesService: failed to update customer balance', [
                'customer_id' => $customer->id ?? null,
                'error' => $e->getMessage(),
            ]);
        }
    }

    protected function resolveAccountFromConfigOrAuto(int $tenantId, string $type)
    {
        $code = null;
        if ($type === 'cash') $code = config('receivables.cash_account_code');
        if ($type === 'ar') $code = config('receivables.ar_account_code');

        if ($code) {
            $acc = ChartOfAccount::where('tenant_id', $tenantId)->where('account_code', $code)->first();
            if ($acc) return $acc;
        }

        // Auto-detect
        $query = ChartOfAccount::where('tenant_id', $tenantId)->where('is_active', true)->orderBy('account_code');
        if ($type === 'cash') {
            $acc = (clone $query)->whereIn('account_category', ['cash', 'bank', 'cash_and_bank'])->first();
            if ($acc) return $acc;
            return (clone $query)->where(function($q){
                $q->where('account_name', 'like', '%Cash%')
                  ->orWhere('account_name', 'like', '%Bank%')
                  ->orWhere('account_name', 'like', '%الصندوق%')
                  ->orWhere('account_name', 'like', '%البنك%');
            })->first();
        } else {
            $acc = (clone $query)->whereIn('account_category', ['accounts_receivable', 'trade_receivables'])->first();
            if ($acc) return $acc;
            return (clone $query)->where(function($q){
                $q->where('account_name', 'like', '%Receivable%')
                  ->orWhere('account_name', 'like', '%ذمم%')
                  ->orWhere('account_name', 'like', '%ذمم مدينة%');
            })->first();
        }
    }
}

