<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ReturnOrder extends Model
{
    use HasFactory;

    protected $table = 'returns'; // some deployments may have legacy columns; model adapts in controller

    protected $fillable = [
        'tenant_id',
        'return_number',
        'invoice_id',
        'customer_id',
        'return_date',
        'type',
        'status',
        'reason',
        'total_amount',
        'refund_amount',
        'refund_method',
        'notes',
        'processed_by',
        'processed_at',
    ];

    protected $casts = [
        'return_date' => 'date',
        'total_amount' => 'decimal:2',
        'refund_amount' => 'decimal:2',
        'processed_at' => 'datetime',
    ];

    // Relationships
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(ReturnItem::class, 'return_id');
    }

    public function processedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    // Scopes
    public function scopeForTenant($query, $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeReturns($query)
    {
        return $query->where('type', 'return');
    }

    public function scopeExchanges($query)
    {
        return $query->where('type', 'exchange');
    }

    // Methods
    public function generateReturnNumber()
    {
        $prefix = 'RET';
        $year = date('Y');
        $month = date('m');

        $lastReturn = static::where('tenant_id', $this->tenant_id)
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->orderBy('id', 'desc')
            ->first();

        $sequence = $lastReturn ? (int)substr($lastReturn->return_number, -4) + 1 : 1;

        return $prefix . $year . $month . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    public function approve($processedBy = null)
    {
        $this->update([
            'status' => 'approved',
            'processed_by' => $processedBy ?? auth()->id(),
            'processed_at' => now(),
        ]);

        // Update inventory for approved returns
        $this->updateInventory();
    }

    public function complete($processedBy = null)
    {
        $this->update([
            'status' => 'completed',
            'processed_by' => $processedBy ?? auth()->id(),
            'processed_at' => now(),
        ]);
    }

    public function reject($processedBy = null)
    {
        $this->update([
            'status' => 'rejected',
            'processed_by' => $processedBy ?? auth()->id(),
            'processed_at' => now(),
        ]);
    }

    public function updateInventory()
    {
        foreach ($this->items as $item) {
            $product = $item->product;

            // Add returned quantity back to stock
            $product->current_stock += $item->quantity_returned;
            $product->save();

            // Handle exchanges
            if ($this->type === 'exchange' && $item->exchange_product_id) {
                $exchangeProduct = Product::find($item->exchange_product_id);
                if ($exchangeProduct && $item->exchange_quantity) {
                    $exchangeProduct->current_stock -= $item->exchange_quantity;
                    $exchangeProduct->save();
                }
            }
        }
    }

    public function calculateTotals()
    {
        $this->total_amount = $this->items->sum('total_amount');

        if ($this->type === 'exchange') {
            $exchangeTotal = $this->items->sum('exchange_total_amount');
            $this->refund_amount = $this->total_amount - $exchangeTotal;
        } else {
            $this->refund_amount = $this->total_amount;
        }

        $this->save();
    }
}
