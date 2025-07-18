<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Customer Order Model
 * 
 * نموذج طلبيات العملاء
 */
class CustomerOrder extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'customer_id',
        'tenant_id',
        'order_number',
        'status',
        'order_date',
        'required_date',
        'notes',
        'subtotal',
        'tax_amount',
        'discount_amount',
        'total_amount',
        'currency',
        'payment_status',
        'delivery_address',
        'delivery_city',
        'delivery_phone',
        'priority',
        'approved_by',
        'approved_at',
        'cancelled_reason',
        'cancelled_at',
    ];

    protected $casts = [
        'order_date' => 'datetime',
        'required_date' => 'datetime',
        'approved_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    protected $dates = [
        'deleted_at',
        'order_date',
        'required_date',
        'approved_at',
        'cancelled_at',
    ];

    // Order statuses
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_PROCESSING = 'processing';
    const STATUS_SHIPPED = 'shipped';
    const STATUS_DELIVERED = 'delivered';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_COMPLETED = 'completed';

    // Payment statuses
    const PAYMENT_PENDING = 'pending';
    const PAYMENT_PARTIAL = 'partial';
    const PAYMENT_PAID = 'paid';

    // Priority levels
    const PRIORITY_LOW = 'low';
    const PRIORITY_NORMAL = 'normal';
    const PRIORITY_HIGH = 'high';
    const PRIORITY_URGENT = 'urgent';

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (empty($order->order_number)) {
                $order->order_number = static::generateOrderNumber();
            }
            if (empty($order->order_date)) {
                $order->order_date = now();
            }
        });
    }

    /**
     * Generate unique order number
     */
    public static function generateOrderNumber(): string
    {
        $prefix = 'ORD';
        $year = date('Y');
        $month = date('m');
        
        $lastOrder = static::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->orderBy('id', 'desc')
            ->first();

        $number = $lastOrder ? (int)substr($lastOrder->order_number, -4) + 1 : 1;
        
        return $prefix . $year . $month . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Customer relationship
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Tenant relationship
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Order items relationship
     */
    public function items(): HasMany
    {
        return $this->hasMany(CustomerOrderItem::class);
    }

    /**
     * Approved by user relationship
     */
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            self::STATUS_PENDING => 'معلق',
            self::STATUS_APPROVED => 'موافق عليه',
            self::STATUS_PROCESSING => 'قيد التجهيز',
            self::STATUS_SHIPPED => 'تم الشحن',
            self::STATUS_DELIVERED => 'تم التسليم',
            self::STATUS_CANCELLED => 'ملغي',
            self::STATUS_COMPLETED => 'مكتمل',
            default => 'غير محدد',
        };
    }

    /**
     * Get payment status label
     */
    public function getPaymentStatusLabelAttribute(): string
    {
        return match($this->payment_status) {
            self::PAYMENT_PENDING => 'معلق',
            self::PAYMENT_PARTIAL => 'جزئي',
            self::PAYMENT_PAID => 'مدفوع',
            default => 'غير محدد',
        };
    }

    /**
     * Get priority label
     */
    public function getPriorityLabelAttribute(): string
    {
        return match($this->priority) {
            self::PRIORITY_LOW => 'منخفض',
            self::PRIORITY_NORMAL => 'عادي',
            self::PRIORITY_HIGH => 'عالي',
            self::PRIORITY_URGENT => 'عاجل',
            default => 'عادي',
        };
    }

    /**
     * Check if order can be cancelled
     */
    public function canBeCancelled(): bool
    {
        return in_array($this->status, [
            self::STATUS_PENDING,
            self::STATUS_APPROVED
        ]);
    }

    /**
     * Check if order can be modified
     */
    public function canBeModified(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Cancel order
     */
    public function cancel(string $reason = null): bool
    {
        if (!$this->canBeCancelled()) {
            return false;
        }

        $this->update([
            'status' => self::STATUS_CANCELLED,
            'cancelled_reason' => $reason,
            'cancelled_at' => now(),
        ]);

        return true;
    }

    /**
     * Approve order
     */
    public function approve(int $userId): bool
    {
        if ($this->status !== self::STATUS_PENDING) {
            return false;
        }

        $this->update([
            'status' => self::STATUS_APPROVED,
            'approved_by' => $userId,
            'approved_at' => now(),
        ]);

        return true;
    }

    /**
     * Calculate totals
     */
    public function calculateTotals(): void
    {
        $subtotal = $this->items()->sum(\DB::raw('quantity * unit_price'));
        $taxAmount = $subtotal * 0.1; // 10% tax
        $totalAmount = $subtotal + $taxAmount - $this->discount_amount;

        $this->update([
            'subtotal' => $subtotal,
            'tax_amount' => $taxAmount,
            'total_amount' => $totalAmount,
        ]);
    }

    /**
     * Scope for customer orders
     */
    public function scopeForCustomer($query, $customerId)
    {
        return $query->where('customer_id', $customerId);
    }

    /**
     * Scope for status
     */
    public function scopeWithStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope for date range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('order_date', [$startDate, $endDate]);
    }
}
