<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Schema;
use App\Traits\ConditionalSoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

/**
 * Customer Model with Authentication
 *
 * نموذج العميل مع إمكانية تسجيل الدخول والصلاحيات
 */
class Customer extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, ConditionalSoftDeletes;

    protected $fillable = [
        'tenant_id',
        'customer_code',
        'name',
        'email',
        'phone',
        'mobile',
        'password',
        'address',
        'city',
        'state',
        'country',
        'postal_code',
        'tax_number',
        'commercial_register',
        'customer_type',
        'payment_terms',
        'credit_limit',
        'current_balance',
        'previous_debt',
        'currency',
        'is_active',
        'notes',
        'email_verified_at',
        'last_login_at',
        'last_login_ip',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $dates = [
        'deleted_at',
        'email_verified_at',
        'last_login_at',
    ];

    protected $casts = [
        'credit_limit' => 'decimal:2',
        'current_balance' => 'decimal:2',
        'previous_debt' => 'decimal:2',
        'is_active' => 'boolean',
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relationships
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function salesOrders(): HasMany
    {
        return $this->hasMany(SalesOrder::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function salesReturns(): HasMany
    {
        return $this->hasMany(SalesReturn::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForTenant($query, $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }

    // Helper Methods
    public function generateCustomerCode()
    {
        $prefix = 'CUST';
        $lastCustomer = static::where('tenant_id', $this->tenant_id)
            ->where('customer_code', 'like', $prefix . '%')
            ->orderBy('customer_code', 'desc')
            ->first();

        if ($lastCustomer) {
            $lastNumber = (int) substr($lastCustomer->customer_code, strlen($prefix));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . str_pad($newNumber, 6, '0', STR_PAD_LEFT);
    }

    public function updateBalance($amount, $operation = 'add')
    {
        if ($operation === 'add') {
            $this->current_balance += $amount;
        } else {
            $this->current_balance -= $amount;
        }

        $this->save();
    }

    // Customer Authentication & Permissions Methods

    /**
     * Check if customer can place orders
     */
    public function canPlaceOrders(): bool
    {
        return $this->is_active && $this->hasPermissionTo('place_orders');
    }

    /**
     * Check if customer can view financial info
     */
    public function canViewFinancialInfo(): bool
    {
        return $this->is_active && $this->hasPermissionTo('view_financial_info');
    }

    /**
     * Get total debt (previous + current)
     */
    public function getTotalDebtAttribute(): float
    {
        return ($this->previous_debt ?? 0) + ($this->current_balance ?? 0);
    }

    /**
     * Get available credit
     */
    public function getAvailableCreditAttribute(): float
    {
        return max(0, ($this->credit_limit ?? 0) - $this->total_debt);
    }

    /**
     * Check if customer is over credit limit
     */
    public function isOverCreditLimit(): bool
    {
        return $this->total_debt > ($this->credit_limit ?? 0);
    }

    /**
     * Get customer status
     */
    public function getStatusAttribute(): string
    {
        if (!$this->is_active) {
            return 'معطل';
        }

        if ($this->isOverCreditLimit()) {
            return 'تجاوز الحد الائتماني';
        }

        if ($this->total_debt > 0) {
            return 'مديون';
        }

        return 'نشط';
    }

    /**
     * Get customer dashboard data
     */
    public function getDashboardData(): array
    {
        return [
            'total_orders' => $this->salesOrders()->count(),
            'pending_orders' => $this->salesOrders()->where('status', 'pending')->count(),
            'completed_orders' => $this->salesOrders()->where('status', 'completed')->count(),
            'total_invoices' => $this->invoices()->count(),
            'total_payments' => $this->payments()->sum('amount'),
            'current_debt' => $this->total_debt,
            'available_credit' => $this->available_credit,
            'recent_orders' => $this->salesOrders()->latest()->limit(5)->get(),
            'recent_payments' => $this->payments()->latest()->limit(5)->get(),
        ];
    }

    /**
     * Scope for customers with debt
     */
    public function scopeWithDebt($query)
    {
        return $query->where(function ($q) {
            $q->where('previous_debt', '>', 0)
              ->orWhere('current_balance', '>', 0);
        });
    }

    /**
     * Scope for customers over credit limit
     */
    public function scopeOverCreditLimit($query)
    {
        return $query->whereRaw('(COALESCE(previous_debt, 0) + COALESCE(current_balance, 0)) > COALESCE(credit_limit, 0)');
    }
}
