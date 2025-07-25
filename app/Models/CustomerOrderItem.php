<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Customer Order Item Model
 * 
 * نموذج عناصر طلبيات العملاء
 */
class CustomerOrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_order_id',
        'product_id',
        'product_name',
        'product_code',
        'quantity',
        'unit_price',
        'total_price',
        'notes',
        'discount_percentage',
        'discount_amount',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'discount_percentage' => 'decimal:2',
        'discount_amount' => 'decimal:2',
    ];

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($item) {
            // Calculate total price
            $subtotal = $item->quantity * $item->unit_price;
            $discountAmount = $item->discount_amount ?? 0;
            
            if ($item->discount_percentage > 0) {
                $discountAmount = $subtotal * ($item->discount_percentage / 100);
            }
            
            $item->discount_amount = $discountAmount;
            $item->total_price = $subtotal - $discountAmount;
        });

        static::saved(function ($item) {
            // Recalculate order totals
            $item->order->calculateTotals();
        });

        static::deleted(function ($item) {
            // Recalculate order totals
            $item->order->calculateTotals();
        });
    }

    /**
     * Order relationship
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(CustomerOrder::class, 'customer_order_id');
    }

    /**
     * Product relationship
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get formatted unit price
     */
    public function getFormattedUnitPriceAttribute(): string
    {
        return number_format((float) $this->unit_price, 2) . ' د.ع';
    }

    /**
     * Get formatted total price
     */
    public function getFormattedTotalPriceAttribute(): string
    {
        return number_format((float) $this->total_price, 2) . ' د.ع';
    }

    /**
     * Get subtotal before discount
     */
    public function getSubtotalAttribute(): float
    {
        return $this->quantity * $this->unit_price;
    }

    /**
     * Get discount amount
     */
    public function getCalculatedDiscountAttribute(): float
    {
        if ($this->discount_percentage > 0) {
            return $this->subtotal * ($this->discount_percentage / 100);
        }
        
        return $this->discount_amount ?? 0;
    }
}
