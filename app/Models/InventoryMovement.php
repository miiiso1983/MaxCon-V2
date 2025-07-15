<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'movement_number',
        'warehouse_id',
        'product_id',
        'inventory_id',
        'movement_type',
        'movement_reason',
        'quantity',
        'unit_cost',
        'total_cost',
        'balance_before',
        'balance_after',
        'movement_date',
        'reference_type',
        'reference_id',
        'reference_number',
        'batch_number',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'movement_date' => 'datetime',
        'quantity' => 'decimal:3',
        'unit_cost' => 'decimal:2',
        'total_cost' => 'decimal:2',
        'balance_before' => 'decimal:3',
        'balance_after' => 'decimal:3',
    ];

    // Relationships
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function inventory(): BelongsTo
    {
        return $this->belongsTo(Inventory::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Scopes
    public function scopeForTenant($query, $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('movement_type', $type);
    }

    public function scopeByWarehouse($query, $warehouseId)
    {
        return $query->where('warehouse_id', $warehouseId);
    }

    public function scopeByProduct($query, $productId)
    {
        return $query->where('product_id', $productId);
    }

    public function scopeInMovements($query)
    {
        return $query->whereIn('movement_type', ['in', 'transfer_in', 'adjustment_in', 'return_in']);
    }

    public function scopeOutMovements($query)
    {
        return $query->whereIn('movement_type', ['out', 'transfer_out', 'adjustment_out', 'return_out']);
    }

    // Methods
    public function getMovementTypeLabel()
    {
        $types = [
            'in' => 'إدخال',
            'out' => 'إخراج',
            'transfer_in' => 'تحويل وارد',
            'transfer_out' => 'تحويل صادر',
            'adjustment_in' => 'تعديل زيادة',
            'adjustment_out' => 'تعديل نقص',
            'return_in' => 'إرجاع وارد',
            'return_out' => 'إرجاع صادر',
            'damage' => 'تلف',
            'expired' => 'انتهاء صلاحية',
            'theft' => 'سرقة',
        ];

        return $types[$this->movement_type] ?? $this->movement_type;
    }

    public function getMovementReasonLabel()
    {
        $reasons = [
            'purchase' => 'شراء',
            'sale' => 'بيع',
            'transfer' => 'تحويل',
            'adjustment' => 'تعديل',
            'return' => 'إرجاع',
            'damage' => 'تلف',
            'expired' => 'انتهاء صلاحية',
            'theft' => 'سرقة',
            'audit' => 'جرد',
            'initial' => 'رصيد افتتاحي',
        ];

        return $reasons[$this->movement_reason] ?? $this->movement_reason;
    }

    public function isInMovement()
    {
        return in_array($this->movement_type, ['in', 'transfer_in', 'adjustment_in', 'return_in']);
    }

    public function isOutMovement()
    {
        return in_array($this->movement_type, ['out', 'transfer_out', 'adjustment_out', 'return_out']);
    }

    public function getMovementTypeColor()
    {
        if ($this->isInMovement()) {
            return 'success';
        } elseif ($this->isOutMovement()) {
            return 'danger';
        } else {
            return 'warning';
        }
    }
}
