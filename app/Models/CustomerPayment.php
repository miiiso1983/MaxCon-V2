<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Customer Payment Model
 * 
 * نموذج دفعات العملاء
 */
class CustomerPayment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'customer_id',
        'tenant_id',
        'invoice_id',
        'payment_number',
        'amount',
        'payment_date',
        'payment_method',
        'reference_number',
        'bank_name',
        'check_number',
        'check_date',
        'status',
        'notes',
        'processed_by',
        'processed_at',
        'currency',
        'exchange_rate',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'exchange_rate' => 'decimal:4',
        'payment_date' => 'datetime',
        'check_date' => 'datetime',
        'processed_at' => 'datetime',
    ];

    protected $dates = [
        'deleted_at',
        'payment_date',
        'check_date',
        'processed_at',
    ];

    // Payment methods
    const METHOD_CASH = 'cash';
    const METHOD_CHECK = 'check';
    const METHOD_BANK_TRANSFER = 'bank_transfer';
    const METHOD_CREDIT_CARD = 'credit_card';
    const METHOD_ONLINE = 'online';

    // Payment statuses
    const STATUS_PENDING = 'pending';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_REJECTED = 'rejected';

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($payment) {
            if (empty($payment->payment_number)) {
                $payment->payment_number = static::generatePaymentNumber();
            }
            if (empty($payment->payment_date)) {
                $payment->payment_date = now();
            }
        });
    }

    /**
     * Generate unique payment number
     */
    public static function generatePaymentNumber(): string
    {
        $prefix = 'PAY';
        $year = date('Y');
        $month = date('m');
        
        $lastPayment = static::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->orderBy('id', 'desc')
            ->first();

        $number = $lastPayment ? (int)substr($lastPayment->payment_number, -4) + 1 : 1;
        
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
     * Invoice relationship
     */
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    /**
     * Processed by user relationship
     */
    public function processedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    /**
     * Get payment method label
     */
    public function getPaymentMethodLabelAttribute(): string
    {
        return match($this->payment_method) {
            self::METHOD_CASH => 'نقدي',
            self::METHOD_CHECK => 'شيك',
            self::METHOD_BANK_TRANSFER => 'تحويل بنكي',
            self::METHOD_CREDIT_CARD => 'بطاقة ائتمان',
            self::METHOD_ONLINE => 'دفع إلكتروني',
            default => 'غير محدد',
        };
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            self::STATUS_PENDING => 'معلق',
            self::STATUS_CONFIRMED => 'مؤكد',
            self::STATUS_CANCELLED => 'ملغي',
            self::STATUS_REJECTED => 'مرفوض',
            default => 'غير محدد',
        };
    }

    /**
     * Get formatted amount
     */
    public function getFormattedAmountAttribute(): string
    {
        $amount = $this->amount;
        return number_format((float) $amount, 2) . ' ' . ($this->currency ?? 'د.ع');
    }

    /**
     * Confirm payment
     */
    public function confirm(int $userId): bool
    {
        if ($this->status !== self::STATUS_PENDING) {
            return false;
        }

        $this->update([
            'status' => self::STATUS_CONFIRMED,
            'processed_by' => $userId,
            'processed_at' => now(),
        ]);

        // Update customer balance
        $this->customer->updateBalance($this->amount, 'subtract');

        return true;
    }

    /**
     * Cancel payment
     */
    public function cancel(string $reason = null): bool
    {
        if ($this->status === self::STATUS_CONFIRMED) {
            // Reverse customer balance update
            $this->customer->updateBalance($this->amount, 'add');
        }

        $this->update([
            'status' => self::STATUS_CANCELLED,
            'notes' => $this->notes . "\nسبب الإلغاء: " . $reason,
        ]);

        return true;
    }

    /**
     * Scope for customer payments
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
     * Scope for payment method
     */
    public function scopeWithMethod($query, $method)
    {
        return $query->where('payment_method', $method);
    }

    /**
     * Scope for date range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('payment_date', [$startDate, $endDate]);
    }
}
