<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\BelongsToTenant;

class CostCenter extends Model
{
    use SoftDeletes, BelongsToTenant;

    protected $table = 'cost_centers';

    protected $fillable = [
        'tenant_id',
        'code',
        'name',
        'name_en',
        'description',
        'parent_id',
        'level',
        'is_active',
        'is_parent',
        'manager_id',
        'budget_amount',
        'actual_amount',
        'variance_amount',
        'notes',
        'sort_order',
        'created_by'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'budget_amount' => 'decimal:2',
        'actual_amount' => 'decimal:2',
        'level' => 'integer'
    ];

    /**
     * Parent cost center relationship
     */
    public function parentCostCenter(): BelongsTo
    {
        return $this->belongsTo(CostCenter::class, 'parent_id');
    }

    /**
     * Child cost centers relationship
     */
    public function childCostCenters(): HasMany
    {
        return $this->hasMany(CostCenter::class, 'parent_id');
    }

    /**
     * All descendants (recursive)
     */
    public function descendants(): HasMany
    {
        return $this->childCostCenters()->with('descendants');
    }

    /**
     * Accounts relationship
     */
    public function accounts(): HasMany
    {
        return $this->hasMany(ChartOfAccount::class);
    }

    /**
     * Journal entries relationship
     */
    public function journalEntries(): HasMany
    {
        return $this->hasMany(JournalEntry::class);
    }

    /**
     * Get full cost center path
     */
    public function getFullPath(): string
    {
        $path = $this->name;
        $parent = $this->parentCostCenter;
        
        while ($parent) {
            $path = $parent->name . ' > ' . $path;
            $parent = $parent->parentCostCenter;
        }
        
        return $path;
    }

    /**
     * Calculate actual amount from journal entries
     */
    public function calculateActualAmount($startDate = null, $endDate = null): float
    {
        $query = $this->journalEntries();
        
        if ($startDate) {
            $query->where('entry_date', '>=', $startDate);
        }
        
        if ($endDate) {
            $query->where('entry_date', '<=', $endDate);
        }

        return $query->sum('debit_amount') - $query->sum('credit_amount');
    }

    /**
     * Get budget variance
     */
    public function getBudgetVariance(): float
    {
        return $this->budget_amount - $this->actual_amount;
    }

    /**
     * Get budget variance percentage
     */
    public function getBudgetVariancePercentage(): float
    {
        if ($this->budget_amount == 0) {
            return 0;
        }
        
        return (($this->actual_amount - $this->budget_amount) / $this->budget_amount) * 100;
    }

    /**
     * Scope for active cost centers
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for parent cost centers
     */
    public function scopeParents($query)
    {
        return $query->whereNotNull('parent_cost_center_id');
    }

    /**
     * Scope for root cost centers
     */
    public function scopeRoots($query)
    {
        return $query->whereNull('parent_cost_center_id');
    }

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($costCenter) {
            if (!$costCenter->code) {
                $costCenter->code = static::generateCode($costCenter);
            }
            
            // Set level based on parent
            if ($costCenter->parent_cost_center_id) {
                $parent = static::find($costCenter->parent_cost_center_id);
                $costCenter->level = $parent ? $parent->level + 1 : 1;
            } else {
                $costCenter->level = 1;
            }
        });

        static::updating(function ($costCenter) {
            // Update actual amount
            $costCenter->actual_amount = $costCenter->calculateActualAmount();
        });
    }

    /**
     * Generate cost center code
     */
    private static function generateCode($costCenter): string
    {
        if ($costCenter->parent_cost_center_id) {
            $parent = static::find($costCenter->parent_cost_center_id);
            $parentCode = $parent->code;
            $childCount = static::where('parent_cost_center_id', $costCenter->parent_cost_center_id)->count();
            return $parentCode . '.' . str_pad($childCount + 1, 2, '0', STR_PAD_LEFT);
        }
        
        $rootCount = static::whereNull('parent_cost_center_id')->count();
        return 'CC' . str_pad($rootCount + 1, 3, '0', STR_PAD_LEFT);
    }
}
