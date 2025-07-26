<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuotationItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'quotation_id',
        'product_id',
        'purchase_request_item_id',
        'item_name',
        'item_code',
        'description',
        'unit',
        'quantity',
        'unit_price',
        'total_price',
        'specifications',
        'brand',
        'model',
        'technical_specs',
        'origin_country',
        'warranty_months',
        'warranty_terms',
        'delivery_days',
        'delivery_terms',
        'availability_percentage',
        'technical_score',
        'commercial_score',
        'evaluation_notes',
        'notes',
        'sort_order',
        'is_alternative',
        'alternative_reason',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'availability_percentage' => 'decimal:2',
        'technical_score' => 'decimal:2',
        'commercial_score' => 'decimal:2',
        'is_alternative' => 'boolean',
    ];

    // Relationships
    public function quotation(): BelongsTo
    {
        return $this->belongsTo(Quotation::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function purchaseRequestItem(): BelongsTo
    {
        return $this->belongsTo(PurchaseRequestItem::class);
    }

    // Calculate total price
    public function calculateTotal()
    {
        return $this->quantity * $this->unit_price;
    }

    // Update total price
    public function updateTotal()
    {
        $this->setAttribute('total_price', round($this->calculateTotal(), 2));
        $this->save();
    }
}
