<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use App\Traits\BelongsToTenant;
use App\Models\Accounting\JournalEntry;
use App\Models\Accounting\JournalEntryDetail;
use App\Models\Accounting\CostCenter;

class ChartOfAccount extends Model
{
    use SoftDeletes, BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'account_code',
        'account_name',
        'account_name_en',
        'account_type',
        'account_category',
        'parent_account_id',
        'level',
        'is_parent',
        'is_active',
        'description',
        'opening_balance',
        'current_balance',
        'currency_code',
        'cost_center_id',
        'is_system_account',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'is_parent' => 'boolean',
        'is_active' => 'boolean',
        'is_system_account' => 'boolean',
        'opening_balance' => 'decimal:2',
        'current_balance' => 'decimal:2',
        'level' => 'integer'
    ];

    // Account Types
    const TYPE_ASSET = 'asset';
    const TYPE_LIABILITY = 'liability';
    const TYPE_EQUITY = 'equity';
    const TYPE_REVENUE = 'revenue';
    const TYPE_EXPENSE = 'expense';

    // Account Categories
    const CATEGORY_CURRENT_ASSET = 'current_asset';
    const CATEGORY_NON_CURRENT_ASSET = 'non_current_asset';
    const CATEGORY_CURRENT_LIABILITY = 'current_liability';
    const CATEGORY_NON_CURRENT_LIABILITY = 'non_current_liability';
    const CATEGORY_OWNERS_EQUITY = 'owners_equity';
    const CATEGORY_OPERATING_REVENUE = 'operating_revenue';
    const CATEGORY_NON_OPERATING_REVENUE = 'non_operating_revenue';
    const CATEGORY_OPERATING_EXPENSE = 'operating_expense';
    const CATEGORY_NON_OPERATING_EXPENSE = 'non_operating_expense';

    /**
     * Get account types
     */
    public static function getAccountTypes(): array
    {
        return [
            self::TYPE_ASSET => 'الأصول',
            self::TYPE_LIABILITY => 'الخصوم',
            self::TYPE_EQUITY => 'حقوق الملكية',
            self::TYPE_REVENUE => 'الإيرادات',
            self::TYPE_EXPENSE => 'المصروفات'
        ];
    }

    /**
     * Get account categories
     */
    public static function getAccountCategories(): array
    {
        return [
            self::CATEGORY_CURRENT_ASSET => 'الأصول المتداولة',
            self::CATEGORY_NON_CURRENT_ASSET => 'الأصول غير المتداولة',
            self::CATEGORY_CURRENT_LIABILITY => 'الخصوم المتداولة',
            self::CATEGORY_NON_CURRENT_LIABILITY => 'الخصوم غير المتداولة',
            self::CATEGORY_OWNERS_EQUITY => 'حقوق الملكية',
            self::CATEGORY_OPERATING_REVENUE => 'الإيرادات التشغيلية',
            self::CATEGORY_NON_OPERATING_REVENUE => 'الإيرادات غير التشغيلية',
            self::CATEGORY_OPERATING_EXPENSE => 'المصروفات التشغيلية',
            self::CATEGORY_NON_OPERATING_EXPENSE => 'المصروفات غير التشغيلية'
        ];
    }

    /**
     * Parent account relationship
     */
    public function parentAccount(): BelongsTo
    {
        return $this->belongsTo(ChartOfAccount::class, 'parent_account_id');
    }

    /**
     * Child accounts relationship
     */
    public function childAccounts(): HasMany
    {
        return $this->hasMany(ChartOfAccount::class, 'parent_account_id');
    }

    /**
     * All descendants (recursive)
     */
    public function descendants(): HasMany
    {
        return $this->childAccounts()->with('descendants');
    }

    /**
     * Cost center relationship
     */
    public function costCenter(): BelongsTo
    {
        return $this->belongsTo(CostCenter::class);
    }

    /**
     * Journal entry details relationship
     */
    public function journalEntryDetails(): HasMany
    {
        return $this->hasMany(JournalEntryDetail::class, 'account_id');
    }

    /**
     * Get recent journal entry details for this account
     */
    public function getRecentEntries($limit = 10)
    {
        return $this->journalEntryDetails()
            ->with(['journalEntry' => function($query) {
                $query->where('status', 'posted');
            }])
            ->whereHas('journalEntry', function($query) {
                $query->where('status', 'posted');
            })
            ->orderByDesc('id')
            ->limit($limit)
            ->get();
    }

    /**
     * Get account balance
     */
    public function getBalance($startDate = null, $endDate = null): float
    {
        // Start with opening balance if no start date specified
        $balance = $startDate ? 0 : ($this->opening_balance ?? 0);

        // Get journal entry details with explicit table prefixes
        $details = \DB::table('journal_entry_details as jed')
            ->join('journal_entries as je', 'jed.journal_entry_id', '=', 'je.id')
            ->where('jed.account_id', $this->id)
            ->where('je.status', 'posted')
            ->where('jed.tenant_id', $this->tenant_id);

        if ($startDate) {
            $details->where('je.entry_date', '>=', $startDate);
        }

        if ($endDate) {
            $details->where('je.entry_date', '<=', $endDate);
        }

        $result = $details->selectRaw('
            COALESCE(SUM(jed.debit_amount), 0) as total_debit,
            COALESCE(SUM(jed.credit_amount), 0) as total_credit
        ')->first();

        $debitTotal = $result->total_debit ?? 0;
        $creditTotal = $result->total_credit ?? 0;

        // Calculate balance based on account type
        if (in_array($this->account_type, [self::TYPE_ASSET, self::TYPE_EXPENSE])) {
            return $balance + $debitTotal - $creditTotal;
        } else {
            return $balance + $creditTotal - $debitTotal;
        }
    }

    /**
     * Get full account path
     */
    public function getFullAccountPath(): string
    {
        $path = $this->account_name;
        $parent = $this->parentAccount;
        
        while ($parent) {
            $path = $parent->account_name . ' > ' . $path;
            $parent = $parent->parentAccount;
        }
        
        return $path;
    }

    /**
     * Check if account can be deleted
     */
    public function canBeDeleted(): bool
    {
        return !$this->is_system_account &&
               $this->journalEntryDetails()->count() === 0 &&
               $this->childAccounts()->count() === 0;
    }

    /**
     * Scope for active accounts
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for parent accounts
     */
    public function scopeParents($query)
    {
        return $query->where('is_parent', true);
    }

    /**
     * Scope for leaf accounts (no children)
     */
    public function scopeLeaf($query)
    {
        return $query->where('is_parent', false);
    }

    /**
     * Scope by account type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('account_type', $type);
    }

    /**
     * Scope by account category
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('account_category', $category);
    }

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($account) {
            if (!$account->account_code) {
                $account->account_code = static::generateAccountCode($account);
            }
            
            // Set level based on parent
            if ($account->parent_account_id) {
                $parent = static::find($account->parent_account_id);
                $account->level = $parent ? $parent->level + 1 : 1;
            } else {
                $account->level = 1;
            }
        });

        static::updating(function ($account) {
            // Update current balance when needed
            $account->current_balance = $account->getBalance();
        });
    }

    /**
     * Generate account code
     */
    private static function generateAccountCode($account): string
    {
        $typePrefix = [
            self::TYPE_ASSET => '1',
            self::TYPE_LIABILITY => '2',
            self::TYPE_EQUITY => '3',
            self::TYPE_REVENUE => '4',
            self::TYPE_EXPENSE => '5'
        ];

        $prefix = $typePrefix[$account->account_type] ?? '9';
        
        if ($account->parent_account_id) {
            $parent = static::find($account->parent_account_id);
            $parentCode = $parent->account_code;
            $childCount = static::where('parent_account_id', $account->parent_account_id)->count();
            return $parentCode . str_pad($childCount + 1, 2, '0', STR_PAD_LEFT);
        }
        
        $mainCount = static::where('account_type', $account->account_type)
                          ->whereNull('parent_account_id')
                          ->count();
        
        return $prefix . str_pad($mainCount + 1, 3, '0', STR_PAD_LEFT);
    }
}
