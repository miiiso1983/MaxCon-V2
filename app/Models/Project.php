<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\BelongsToTenant;

/**
 * @property \Carbon\Carbon $start_date
 * @property \Carbon\Carbon $end_date
 */
class Project extends Model
{
    use SoftDeletes, BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'code',
        'name',
        'name_en',
        'description',
        'start_date',
        'end_date',
        'status',
        'budget_amount',
        'actual_amount',
        'manager_id',
        'is_active',
        'created_by'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'budget_amount' => 'decimal:2',
        'actual_amount' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    // Status constants
    const STATUS_PLANNING = 'planning';
    const STATUS_ACTIVE = 'active';
    const STATUS_ON_HOLD = 'on_hold';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    // Relationships
    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function chartOfAccounts(): HasMany
    {
        return $this->hasMany(\App\Models\Accounting\ChartOfAccount::class);
    }

    public function journalEntryDetails(): HasMany
    {
        return $this->hasMany(\App\Models\Accounting\JournalEntryDetail::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeInProgress($query)
    {
        return $query->whereIn('status', [self::STATUS_PLANNING, self::STATUS_ACTIVE]);
    }

    // Methods
    public function getStatusLabel()
    {
        $statuses = [
            self::STATUS_PLANNING => 'تخطيط',
            self::STATUS_ACTIVE => 'نشط',
            self::STATUS_ON_HOLD => 'معلق',
            self::STATUS_COMPLETED => 'مكتمل',
            self::STATUS_CANCELLED => 'ملغي',
        ];

        return $statuses[$this->getAttribute('status')] ?? $this->getAttribute('status');
    }

    public function getStatusColor()
    {
        $colors = [
            self::STATUS_PLANNING => 'warning',
            self::STATUS_ACTIVE => 'success',
            self::STATUS_ON_HOLD => 'secondary',
            self::STATUS_COMPLETED => 'primary',
            self::STATUS_CANCELLED => 'danger',
        ];

        return $colors[$this->getAttribute('status')] ?? 'secondary';
    }

    public function getBudgetVariance()
    {
        return $this->budget_amount - $this->actual_amount;
    }

    public function getBudgetVariancePercentage()
    {
        if ($this->budget_amount > 0) {
            return (($this->actual_amount - $this->budget_amount) / $this->budget_amount) * 100;
        }
        return 0;
    }

    public function isOverBudget()
    {
        return $this->actual_amount > $this->budget_amount;
    }

    public function getProgressPercentage()
    {
        if (!$this->start_date || !$this->end_date) {
            return 0;
        }

        $totalDays = $this->start_date->diffInDays($this->end_date);
        $elapsedDays = $this->start_date->diffInDays(now());

        if ($totalDays <= 0) {
            return 100;
        }

        return min(100, max(0, ($elapsedDays / $totalDays) * 100));
    }

    public function canBeDeleted()
    {
        return $this->chartOfAccounts()->count() === 0 && 
               $this->journalEntryLines()->count() === 0;
    }

    public function updateActualAmount()
    {
        $this->actual_amount = $this->journalEntryLines()
            ->whereHas('journalEntry', function ($q) {
                $q->where('status', 'posted');
            })
            ->sum('debit_amount');
        $this->save();
    }

    // Static methods
    public static function getStatuses()
    {
        return [
            self::STATUS_PLANNING => 'تخطيط',
            self::STATUS_ACTIVE => 'نشط',
            self::STATUS_ON_HOLD => 'معلق',
            self::STATUS_COMPLETED => 'مكتمل',
            self::STATUS_CANCELLED => 'ملغي',
        ];
    }

    public static function generateCode($tenantId)
    {
        $lastProject = static::where('tenant_id', $tenantId)
            ->orderBy('code', 'desc')
            ->first();

        if ($lastProject) {
            $lastNumber = intval(substr($lastProject->code, 4));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return 'PROJ' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }
}
