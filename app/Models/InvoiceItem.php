<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvoiceItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'product_id',
        'sales_order_item_id',
        'product_name',
        'product_code',
        'batch_number',
        'expiry_date',
        'quantity',
        'unit_price',
        'discount_percentage',
        'discount_amount',
        'discount_type',
        'tax_rate',
        'tax_amount',
        'line_total',
        'total_amount',
        'description',
        'notes'
    ];

    protected $casts = [
        'expiry_date' => 'date',
        'unit_price' => 'decimal:2',
        'discount_percentage' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'line_total' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    // Relationships
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function salesOrderItem(): BelongsTo
    {
        return $this->belongsTo(SalesOrderItem::class);
    }

    // Helper Methods
    public function calculateTotals()
    {
        $subtotal = $this->quantity * $this->unit_price;

        // Calculate discount
        if ($this->discount_percentage > 0) {
            $this->discount_amount = ($subtotal * $this->discount_percentage) / 100;
        }

        $afterDiscount = $subtotal - $this->discount_amount;

        // Calculate tax
        $this->tax_amount = ($afterDiscount * $this->tax_rate) / 100;

        // Calculate line total
        $this->line_total = $afterDiscount + $this->tax_amount;

        $this->save();
    }

    public function getSubtotalAttribute()
    {
        return $this->quantity * $this->unit_price;
    }
}
