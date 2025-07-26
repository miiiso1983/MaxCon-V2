<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quotation extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'quotation_number',
        'supplier_id',
        'purchase_request_id',
        'title',
        'description',
        'status',
        'quotation_date',
        'valid_until',
        'response_date',
        'subtotal',
        'tax_amount',
        'discount_amount',
        'total_amount',
        'currency',
        'payment_terms',
        'delivery_days',
        'delivery_terms',
        'warranty_terms',
        'technical_score',
        'commercial_score',
        'overall_score',
        'evaluation_notes',
        'evaluated_by',
        'evaluated_at',
        'requested_by',
        'special_conditions',
        'attachments',
        'notes',
        'is_selected',
        'rejection_reason',
    ];

    protected $casts = [
        'quotation_date' => 'datetime',
        'valid_until' => 'datetime',
        'response_date' => 'datetime',
        'evaluated_at' => 'datetime',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'technical_score' => 'decimal:2',
        'commercial_score' => 'decimal:2',
        'overall_score' => 'decimal:2',
        'attachments' => 'array',
        'is_selected' => 'boolean',
    ];

    protected $dates = [
        'quotation_date',
        'valid_until',
        'response_date',
        'evaluated_at',
    ];

    // Relationships
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function purchaseRequest(): BelongsTo
    {
        return $this->belongsTo(PurchaseRequest::class);
    }

    public function requestedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function evaluatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'evaluated_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(QuotationItem::class);
    }

    // Accessors
    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'draft' => 'مسودة',
            'sent' => 'مرسل',
            'received' => 'مستلم',
            'under_review' => 'قيد المراجعة',
            'accepted' => 'مقبول',
            'rejected' => 'مرفوض',
            'expired' => 'منتهي الصلاحية',
            default => 'غير محدد'
        };
    }

    public function getPaymentTermsLabelAttribute()
    {
        return match($this->payment_terms) {
            'cash' => 'نقداً',
            'credit_7' => 'آجل 7 أيام',
            'credit_15' => 'آجل 15 يوم',
            'credit_30' => 'آجل 30 يوم',
            'credit_45' => 'آجل 45 يوم',
            'credit_60' => 'آجل 60 يوم',
            'credit_90' => 'آجل 90 يوم',
            'custom' => 'مخصص',
            default => 'غير محدد'
        };
    }

    public function getIsExpiredAttribute()
    {
        return $this->valid_until->isPast();
    }

    public function getDaysUntilExpiryAttribute()
    {
        return now()->diffInDays($this->valid_until, false);
    }

    // Static methods
    public static function generateQuotationNumber($tenantId)
    {
        $prefix = 'QUO';
        $year = date('Y');
        $month = date('m');

        $lastQuotation = static::where('tenant_id', $tenantId)
            ->where('quotation_number', 'like', "{$prefix}-{$year}{$month}-%")
            ->orderBy('quotation_number', 'desc')
            ->first();

        if ($lastQuotation) {
            $lastNumber = intval(substr($lastQuotation->quotation_number, -4));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . '-' . $year . $month . '-' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    // Calculate total amount
    public function calculateTotal()
    {
        $subtotal = $this->items->sum('total_price');
        $total = $subtotal - $this->discount_amount + $this->tax_amount;

        return $total;
    }

    // Update totals from items
    public function updateTotals()
    {
        $this->subtotal = $this->items->sum('total_price');
        $this->total_amount = number_format($this->subtotal - $this->discount_amount + $this->tax_amount, 2, '.', '');
        $this->save();
    }
}
