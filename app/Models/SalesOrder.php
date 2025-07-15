<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class SalesOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'order_number',
        'customer_id',
        'created_by',
        'assigned_to',
        'order_date',
        'required_date',
        'shipped_date',
        'delivered_date',
        'status',
        'priority',
        'subtotal',
        'discount_amount',
        'tax_amount',
        'shipping_cost',
        'total_amount',
        'currency',
        'exchange_rate',
        'shipping_address',
        'billing_address',
        'shipping_method',
        'payment_method',
        'notes',
        'internal_notes',
        'tracking_info'
    ];

    protected $casts = [
        'order_date' => 'date',
        'required_date' => 'date',
        'shipped_date' => 'date',
        'delivered_date' => 'date',
        'subtotal' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'exchange_rate' => 'decimal:4',
        'tracking_info' => 'array',
    ];

    // Relationships
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function items(): HasMany
    {
        return $this->hasMany(SalesOrderItem::class);
    }

    public function invoice(): HasOne
    {
        return $this->hasOne(Invoice::class);
    }

    public function salesReturns(): HasMany
    {
        return $this->hasMany(SalesReturn::class);
    }

    // Scopes
    public function scopeForTenant($query, $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopePending($query)
    {
        return $query->whereIn('status', ['draft', 'pending', 'confirmed']);
    }

    public function scopeOverdue($query)
    {
        return $query->where('required_date', '<', now())
            ->whereNotIn('status', ['delivered', 'cancelled', 'returned']);
    }

    // Helper Methods
    public function generateOrderNumber()
    {
        $prefix = 'SO';
        $year = now()->year;
        $month = str_pad(now()->month, 2, '0', STR_PAD_LEFT);

        $lastOrder = static::where('tenant_id', $this->tenant_id)
            ->where('order_number', 'like', "{$prefix}-{$year}{$month}%")
            ->orderBy('order_number', 'desc')
            ->first();

        if ($lastOrder) {
            $lastNumber = (int) substr($lastOrder->order_number, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return "{$prefix}-{$year}{$month}" . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    public function calculateTotals()
    {
        $this->subtotal = $this->items->sum('line_total');
        $this->tax_amount = $this->items->sum('tax_amount');
        $this->total_amount = $this->subtotal - $this->discount_amount + $this->tax_amount + $this->shipping_cost;
        $this->save();
    }

    public function canBeEdited(): bool
    {
        return in_array($this->status, ['draft', 'pending']);
    }

    public function canBeCancelled(): bool
    {
        return !in_array($this->status, ['delivered', 'cancelled', 'returned']);
    }

    public function markAsShipped($trackingInfo = null)
    {
        $this->status = 'shipped';
        $this->shipped_date = now();
        if ($trackingInfo) {
            $this->tracking_info = $trackingInfo;
        }
        $this->save();
    }

    public function markAsDelivered()
    {
        $this->status = 'delivered';
        $this->delivered_date = now();
        $this->save();
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'draft' => 'gray',
            'pending' => 'yellow',
            'confirmed' => 'blue',
            'processing' => 'indigo',
            'shipped' => 'purple',
            'delivered' => 'green',
            'cancelled' => 'red',
            'returned' => 'orange',
            default => 'gray'
        };
    }
}
