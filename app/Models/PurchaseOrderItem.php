<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseOrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_order_id',
        'product_id',
        'quantity',
        'unit_price',
        'discount_amount',
        'discount_percentage',
        'tax_amount',
        'tax_percentage',
        'total_amount',
        'received_quantity',
        'remaining_quantity',
        'unit',
        'description',
        'specifications',
        'batch_number',
        'expiry_date',
        'quality_notes',
        'quality_status',
        'notes',
        'sort_order',
        'status',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'discount_percentage' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'tax_percentage' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'received_quantity' => 'decimal:2',
        'remaining_quantity' => 'decimal:2',
        'expiry_date' => 'date',
    ];

    // Relationships
    public function purchaseOrder(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    // Accessors
    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'pending' => 'في الانتظار',
            'confirmed' => 'مؤكد',
            'partially_received' => 'مستلم جزئياً',
            'received' => 'مستلم',
            'cancelled' => 'ملغي',
            default => 'غير محدد'
        };
    }

    public function getQualityStatusLabelAttribute()
    {
        return match($this->quality_status) {
            'pending' => 'في الانتظار',
            'approved' => 'معتمد',
            'rejected' => 'مرفوض',
            'conditional' => 'مشروط',
            default => 'في الانتظار'
        };
    }

    // Calculate total amount
    public function calculateTotal()
    {
        $subtotal = $this->quantity * $this->unit_price;
        $afterDiscount = $subtotal - $this->discount_amount;
        $total = $afterDiscount + $this->tax_amount;
        
        return $total;
    }

    // Update remaining quantity
    public function updateRemainingQuantity()
    {
        $this->remaining_quantity = number_format($this->quantity - $this->received_quantity, 2, '.', '');
        $this->save();
    }
}
