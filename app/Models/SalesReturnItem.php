<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SalesReturnItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'sales_return_id',
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
    public function salesReturn(): BelongsTo
    {
        return $this->belongsTo(SalesReturn::class);
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

    // Helper Methods
    public function calculateTotal()
    {
        return $this->quantity_returned * $this->unit_price;
    }

    public function calculateExchangeTotal()
    {
        return $this->exchange_quantity * $this->exchange_unit_price;
    }

    public function getConditionLabel()
    {
        $conditions = [
            'good' => 'جيد',
            'damaged' => 'تالف',
            'expired' => 'منتهي الصلاحية',
            'defective' => 'معيب',
        ];

        return $conditions[$this->condition] ?? $this->condition;
    }

    public function getReasonLabel()
    {
        $reasons = [
            'customer_request' => 'طلب العميل',
            'damaged_goods' => 'بضاعة تالفة',
            'wrong_item' => 'صنف خاطئ',
            'expired' => 'منتهي الصلاحية',
            'quality_issue' => 'مشكلة في الجودة',
            'other' => 'أخرى',
        ];

        return $reasons[$this->reason] ?? $this->reason;
    }
}
