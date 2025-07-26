<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'po_number',
        'supplier_id',
        'purchase_request_id',
        'status',
        'order_date',
        'expected_delivery_date',
        'actual_delivery_date',
        'due_date',
        'subtotal',
        'tax_amount',
        'discount_amount',
        'shipping_cost',
        'total_amount',
        'currency',
        'exchange_rate',
        'payment_terms',
        'payment_days',
        'payment_status',
        'paid_amount',
        'delivery_address',
        'delivery_contact',
        'delivery_phone',
        'delivery_instructions',
        'created_by',
        'approved_by',
        'approved_at',
        'notes',
        'terms_conditions',
        'attachments',
        'received_percentage',
        'is_urgent',
        'reference_number',
    ];

    protected $casts = [
        'order_date' => 'date',
        'expected_delivery_date' => 'date',
        'actual_delivery_date' => 'date',
        'due_date' => 'date',
        'approved_at' => 'datetime',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'exchange_rate' => 'decimal:4',
        'paid_amount' => 'decimal:2',
        'received_percentage' => 'decimal:2',
        'is_urgent' => 'boolean',
        'attachments' => 'array',
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

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    // Accessors
    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'draft' => 'مسودة',
            'sent' => 'مرسل',
            'confirmed' => 'مؤكد',
            'partially_received' => 'مستلم جزئياً',
            'completed' => 'مكتمل',
            'cancelled' => 'ملغي',
            default => 'غير محدد'
        };
    }

    public function getPaymentStatusLabelAttribute()
    {
        return match($this->payment_status) {
            'pending' => 'في الانتظار',
            'partial' => 'دفع جزئي',
            'paid' => 'مدفوع',
            'overdue' => 'متأخر',
            default => 'غير محدد'
        };
    }

    public function getPriorityLabelAttribute()
    {
        return match($this->priority ?? 'medium') {
            'low' => 'منخفضة',
            'medium' => 'متوسطة',
            'high' => 'عالية',
            'urgent' => 'عاجل',
            default => 'متوسطة'
        };
    }

    // Generate PO Number
    public static function generatePoNumber($tenantId)
    {
        $year = date('Y');
        $month = date('m');

        $lastOrder = static::where('tenant_id', $tenantId)
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->orderBy('id', 'desc')
            ->first();

        $sequence = $lastOrder ? (int)substr($lastOrder->po_number, -4) + 1 : 1;

        return 'PO-' . $year . $month . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }
}
