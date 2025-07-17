<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryAdjustmentItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'inventory_adjustment_id',
        'product_id',
        'location_id',
        'current_quantity',
        'adjustment_quantity',
        'new_quantity',
        'unit_cost',
        'total_cost_impact',
        'batch_number',
        'expiry_date',
        'serial_numbers',
        'reason',
        'notes',
        'sort_order',
    ];

    protected $casts = [
        'current_quantity' => 'decimal:3',
        'adjustment_quantity' => 'decimal:3',
        'new_quantity' => 'decimal:3',
        'unit_cost' => 'decimal:2',
        'total_cost_impact' => 'decimal:2',
        'expiry_date' => 'date',
        'serial_numbers' => 'array',
    ];

    // Relationships
    public function inventoryAdjustment(): BelongsTo
    {
        return $this->belongsTo(InventoryAdjustment::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(WarehouseLocation::class, 'location_id');
    }

    // Methods
    public function calculateNewQuantity()
    {
        $this->new_quantity = $this->current_quantity + $this->adjustment_quantity;
        return $this->new_quantity;
    }

    public function calculateTotalCostImpact()
    {
        $this->total_cost_impact = $this->adjustment_quantity * $this->unit_cost;
        return $this->total_cost_impact;
    }

    public function isIncrease()
    {
        return $this->adjustment_quantity > 0;
    }

    public function isDecrease()
    {
        return $this->adjustment_quantity < 0;
    }

    public function getAdjustmentType()
    {
        if ($this->adjustment_quantity > 0) {
            return 'increase';
        } elseif ($this->adjustment_quantity < 0) {
            return 'decrease';
        }
        return 'no_change';
    }

    public function getAdjustmentTypeLabel()
    {
        $types = [
            'increase' => 'زيادة',
            'decrease' => 'نقص',
            'no_change' => 'لا تغيير',
        ];

        return $types[$this->getAdjustmentType()] ?? 'غير محدد';
    }

    public function getAbsoluteAdjustment()
    {
        return abs($this->adjustment_quantity);
    }

    public function getAbsoluteCostImpact()
    {
        return abs($this->total_cost_impact);
    }

    public function processAdjustment()
    {
        // Update inventory record
        $inventory = Inventory::where('product_id', $this->product_id)
            ->where('warehouse_id', $this->inventoryAdjustment->warehouse_id)
            ->when($this->location_id, function ($query) {
                return $query->where('location_id', $this->location_id);
            })
            ->first();

        if ($inventory) {
            $inventory->quantity = $this->new_quantity;
            $inventory->updateAvailableQuantity();
            $inventory->save();

            // Create inventory movement record
            InventoryMovement::create([
                'tenant_id' => $this->inventoryAdjustment->tenant_id,
                'movement_number' => $this->inventoryAdjustment->adjustment_number,
                'warehouse_id' => $this->inventoryAdjustment->warehouse_id,
                'product_id' => $this->product_id,
                'movement_type' => $this->isIncrease() ? 'in' : 'out',
                'movement_reason' => 'adjustment',
                'quantity' => $this->getAbsoluteAdjustment(),
                'unit_cost' => $this->unit_cost,
                'total_cost' => $this->getAbsoluteCostImpact(),
                'balance_before' => $this->current_quantity,
                'balance_after' => $this->new_quantity,
                'movement_date' => $this->inventoryAdjustment->adjustment_date,
                'reference_type' => 'adjustment',
                'reference_id' => $this->inventoryAdjustment->id,
                'reference_number' => $this->inventoryAdjustment->adjustment_number,
                'batch_number' => $this->batch_number,
                'notes' => $this->notes,
                'created_by' => $this->inventoryAdjustment->created_by,
            ]);
        }
    }

    // Boot method
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $model->calculateNewQuantity();
            $model->calculateTotalCostImpact();
        });
    }
}
