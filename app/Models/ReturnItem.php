<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReturnItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'return_id',
        'invoice_item_id',
        'product_id',
        'product_name',
        'product_code',
        'batch_number',
        'expiry_date',
        'quantity_returned',
        'quantity_original',
        'unit_price',
        'total_amount',
        'condition',
        'reason',
        'notes',
        'exchange_product_id',
        'exchange_quantity',
        'exchange_unit_price',
        'exchange_total_amount',
    ];

    protected $casts = [
        'expiry_date' => 'date',
        'unit_price' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'exchange_unit_price' => 'decimal:2',
        'exchange_total_amount' => 'decimal:2',
    ];

    // Relationships
    public function returnOrder(): BelongsTo
    {
        return $this->belongsTo(ReturnOrder::class, 'return_id');
    }

    public function invoiceItem(): BelongsTo
    {
        return $this->belongsTo(InvoiceItem::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function exchangeProduct(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'exchange_product_id');
    }

    // Methods
    public function calculateTotal()
    {
        $this->total_amount = $this->quantity_returned * $this->unit_price;
        $this->save();
    }

    public function calculateExchangeTotal()
    {
        if ($this->exchange_quantity && $this->exchange_unit_price) {
            $this->exchange_total_amount = $this->exchange_quantity * $this->exchange_unit_price;
            $this->save();
        }
    }

    public function getReturnPercentageAttribute()
    {
        if ($this->quantity_original > 0) {
            return round(($this->quantity_returned / $this->quantity_original) * 100, 2);
        }
        return 0;
    }

    public function getConditionLabelAttribute()
    {
        $conditions = [
            'good' => 'جيد',
            'damaged' => 'تالف',
            'expired' => 'منتهي الصلاحية',
        ];

        return $conditions[$this->condition] ?? $this->condition;
    }
}
