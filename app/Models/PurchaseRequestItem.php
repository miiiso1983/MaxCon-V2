<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PurchaseRequestItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_request_id',
        'product_id',
        'item_name',
        'item_code',
        'description',
        'unit',
        'quantity',
        'estimated_price',
        'total_estimated',
        'specifications',
        'brand_preference',
        'model_preference',
        'technical_requirements',
        'status',
        'notes',
        'sort_order',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'estimated_price' => 'decimal:2',
        'total_estimated' => 'decimal:2',
    ];

    // Relationships
    public function purchaseRequest(): BelongsTo
    {
        return $this->belongsTo(PurchaseRequest::class);
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
            'approved' => 'معتمد',
            'rejected' => 'مرفوض',
            'ordered' => 'تم الطلب',
            default => $this->status
        };
    }
}
