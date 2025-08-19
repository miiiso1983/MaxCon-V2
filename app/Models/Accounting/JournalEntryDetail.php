<?php

namespace App\Models\Accounting;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\BelongsToTenant;

class JournalEntryDetail extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'journal_entry_id',
        'account_id',
        'description',
        'debit_amount',
        'credit_amount',
        'currency_code',
        'exchange_rate',
        'debit_amount_local',
        'credit_amount_local',
        'cost_center_id',
        'reference_number',
        'line_number'
    ];

    protected $casts = [
        'debit_amount' => 'decimal:2',
        'credit_amount' => 'decimal:2',
        'exchange_rate' => 'decimal:4',
        'debit_amount_local' => 'decimal:2',
        'credit_amount_local' => 'decimal:2',
        'line_number' => 'integer'
    ];

    /**
     * Journal entry relationship
     */
    public function journalEntry(): BelongsTo
    {
        return $this->belongsTo(JournalEntry::class);
    }

    /**
     * Account relationship
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(ChartOfAccount::class, 'account_id');
    }

    /**
     * Cost center relationship
     */
    public function costCenter(): BelongsTo
    {
        return $this->belongsTo(CostCenter::class);
    }

    /**
     * Get the net amount (debit - credit)
     */
    public function getNetAmount(): float
    {
        return $this->debit_amount - $this->credit_amount;
    }

    /**
     * Get the net amount in local currency
     */
    public function getNetAmountLocal(): float
    {
        return $this->debit_amount_local - $this->credit_amount_local;
    }

    /**
     * Check if this is a debit entry
     */
    public function isDebit(): bool
    {
        return $this->debit_amount > 0;
    }

    /**
     * Check if this is a credit entry
     */
    public function isCredit(): bool
    {
        return $this->credit_amount > 0;
    }

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($detail) {
            // Set line number if not provided
            if (!$detail->line_number) {
                $maxLine = static::where('journal_entry_id', $detail->journal_entry_id)
                               ->max('line_number');
                $detail->line_number = ($maxLine ?? 0) + 1;
            }

            // Calculate local currency amounts
            if (!$detail->exchange_rate) {
                $detail->exchange_rate = $detail->journalEntry->exchange_rate ?? 1.0000;
            }

            $detail->debit_amount_local = $detail->debit_amount * $detail->exchange_rate;
            $detail->credit_amount_local = $detail->credit_amount * $detail->exchange_rate;

            // Set currency code from journal entry if not provided
            if (!$detail->currency_code) {
                $detail->currency_code = $detail->journalEntry->currency_code ?? 'IQD';
            }
        });

        static::updating(function ($detail) {
            // Recalculate local currency amounts
            $detail->debit_amount_local = $detail->debit_amount * $detail->exchange_rate;
            $detail->credit_amount_local = $detail->credit_amount * $detail->exchange_rate;
        });

        static::saved(function ($detail) {
            // Update journal entry totals without firing events to avoid recursion
            $detail->journalEntry->calculateTotals();
        });

        static::deleted(function ($detail) {
            // Update journal entry totals without firing events to avoid recursion
            $detail->journalEntry->calculateTotals();
        });
    }

    /**
     * Scope for debit entries
     */
    public function scopeDebits($query)
    {
        return $query->where('debit_amount', '>', 0);
    }

    /**
     * Scope for credit entries
     */
    public function scopeCredits($query)
    {
        return $query->where('credit_amount', '>', 0);
    }

    /**
     * Scope by account
     */
    public function scopeByAccount($query, $accountId)
    {
        return $query->where('account_id', $accountId);
    }

    /**
     * Scope by cost center
     */
    public function scopeByCostCenter($query, $costCenterId)
    {
        return $query->where('cost_center_id', $costCenterId);
    }
}
