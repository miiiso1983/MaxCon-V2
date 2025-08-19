<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\BelongsToTenant;

class JournalEntry extends Model
{
    use SoftDeletes, BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'journal_number',
        'entry_date',
        'reference_number',
        'description',
        'total_debit',
        'total_credit',
        'currency_code',
        'exchange_rate',
        'status',
        'entry_type',
        'source_document_type',
        'source_document_id',
        'cost_center_id',
        'created_by',
        'approved_by',
        'approved_at',
        'notes'
    ];

    protected $casts = [
        'entry_date' => 'date',
        'total_debit' => 'decimal:2',
        'total_credit' => 'decimal:2',
        'exchange_rate' => 'decimal:4',
        'approved_at' => 'datetime'
    ];

    // Entry Status
    const STATUS_DRAFT = 'draft';
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_POSTED = 'posted';

    // Entry Types
    const TYPE_MANUAL = 'manual';
    const TYPE_AUTOMATIC = 'automatic';
    const TYPE_ADJUSTMENT = 'adjustment';
    const TYPE_CLOSING = 'closing';
    const TYPE_OPENING = 'opening';

    /**
     * Get entry statuses
     */
    public static function getStatuses(): array
    {
        return [
            self::STATUS_DRAFT => 'مسودة',
            self::STATUS_PENDING => 'في الانتظار',
            self::STATUS_APPROVED => 'معتمد',
            self::STATUS_REJECTED => 'مرفوض',
            self::STATUS_POSTED => 'مرحل'
        ];
    }

    /**
     * Get entry types
     */
    public static function getTypes(): array
    {
        return [
            self::TYPE_MANUAL => 'يدوي',
            self::TYPE_AUTOMATIC => 'تلقائي',
            self::TYPE_ADJUSTMENT => 'تسوية',
            self::TYPE_CLOSING => 'إقفال',
            self::TYPE_OPENING => 'افتتاحي'
        ];
    }

    /**
     * Journal entry details relationship
     */
    public function details(): HasMany
    {
        return $this->hasMany(JournalEntryDetail::class);
    }

    /**
     * Cost center relationship
     */
    public function costCenter(): BelongsTo
    {
        return $this->belongsTo(CostCenter::class);
    }

    /**
     * Created by user relationship
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    /**
     * Approved by user relationship
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'approved_by');
    }

    /**
     * Check if entry is balanced
     */
    public function isBalanced(): bool
    {
        return abs($this->total_debit - $this->total_credit) < 0.01;
    }

    /**
     * Check if entry can be edited
     */
    public function canBeEdited(): bool
    {
        return in_array($this->status, [self::STATUS_DRAFT, self::STATUS_REJECTED]);
    }

    /**
     * Check if entry can be approved
     */
    public function canBeApproved(): bool
    {
        return $this->status === self::STATUS_PENDING && $this->isBalanced();
    }

    /**
     * Check if entry can be posted
     */
    public function canBePosted(): bool
    {
        return $this->status === self::STATUS_APPROVED && $this->isBalanced();
    }

    /**
     * Approve the journal entry
     */
    public function approve($userId): bool
    {
        if (!$this->canBeApproved()) {
            return false;
        }

        $this->update([
            'status' => self::STATUS_APPROVED,
            'approved_by' => $userId,
            'approved_at' => now()
        ]);

        return true;
    }

    /**
     * Post the journal entry
     */
    public function post(): bool
    {
        if (!$this->canBePosted()) {
            return false;
        }

        \DB::transaction(function () {
            // Update account balances
            foreach ($this->details as $detail) {
                $account = $detail->account;
                $account->current_balance = $account->getBalance();
                $account->save();
            }

            // Update journal entry status
            $this->update(['status' => self::STATUS_POSTED]);
        });

        return true;
    }

    /**
     * Reject the journal entry
     */
    public function reject(): bool
    {
        if ($this->status !== self::STATUS_PENDING) {
            return false;
        }

        $this->update(['status' => self::STATUS_REJECTED]);
        return true;
    }

    /**
     * Calculate totals from details
     */
    public function calculateTotals(): void
    {
        $this->total_debit = $this->details()->sum('debit_amount');
        $this->total_credit = $this->details()->sum('credit_amount');
        $this->save();
    }

    /**
     * Scope for specific status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope for date range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('entry_date', [$startDate, $endDate]);
    }

    /**
     * Scope for specific type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('entry_type', $type);
    }

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($entry) {
            // Guard: ensure journal_number is generated only if column exists
            $hasJournalNumberColumn = Schema::hasColumn('journal_entries', 'journal_number');
            if ($hasJournalNumberColumn && !$entry->journal_number) {
                $entry->journal_number = static::generateJournalNumber($entry);
            }
            
            if (!$entry->status) {
                $entry->status = self::STATUS_DRAFT;
            }
            
            if (!$entry->entry_type) {
                $entry->entry_type = self::TYPE_MANUAL;
            }
            
            if (!$entry->currency_code) {
                $entry->currency_code = 'IQD';
            }
            
            if (!$entry->exchange_rate) {
                $entry->exchange_rate = 1.0000;
            }
        });

        static::updated(function ($entry) {
            // Recalculate totals when details change
            $entry->calculateTotals();
        });
    }

    /**
     * Generate journal number
     */
    private static function generateJournalNumber($entry): string
    {
        $year = $entry->entry_date->format('Y');
        $month = $entry->entry_date->format('m');
        
        $query = static::where('tenant_id', $entry->tenant_id)
                        ->whereYear('entry_date', $year)
                        ->whereMonth('entry_date', $month);
        if (Schema::hasColumn('journal_entries', 'journal_number')) {
            $query->orderBy('journal_number', 'desc');
        } else {
            $query->orderBy('id', 'desc');
        }
        $lastEntry = $query->first();
        
        if ($lastEntry && preg_match('/JE-(\d{4})(\d{2})-(\d+)/', $lastEntry->journal_number, $matches)) {
            $sequence = intval($matches[3]) + 1;
        } else {
            $sequence = 1;
        }
        
        return 'JE-' . $year . $month . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }
}
