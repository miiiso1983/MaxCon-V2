<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WarehouseStock extends Model
{
    use HasFactory;

    protected $table = 'warehouse_stock';

    protected $fillable = [
        'warehouse_id',
        'product_id',
        'quantity',
        'reserved_quantity',
        'available_quantity',
        'min_stock_level',
        'max_stock_level',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'reserved_quantity' => 'decimal:2',
        'available_quantity' => 'decimal:2',
        'min_stock_level' => 'decimal:2',
        'max_stock_level' => 'decimal:2',
    ];

    // Relationships
    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    // Helper Methods
    public function updateQuantity($quantity, $operation = 'subtract')
    {
        if ($operation === 'subtract') {
            $this->quantity -= $quantity;
            $this->available_quantity -= $quantity;
        } else {
            $this->quantity += $quantity;
            $this->available_quantity += $quantity;
        }

        $this->save();
    }

    public function reserveQuantity($quantity)
    {
        if ($this->available_quantity >= $quantity) {
            $this->reserved_quantity += $quantity;
            $this->available_quantity -= $quantity;
            $this->save();
            return true;
        }
        
        return false;
    }

    public function releaseReservedQuantity($quantity)
    {
        $releaseAmount = min($quantity, $this->reserved_quantity);
        $this->reserved_quantity -= $releaseAmount;
        $this->available_quantity += $releaseAmount;
        $this->save();
        
        return $releaseAmount;
    }

    public function isLowStock()
    {
        return $this->quantity <= $this->min_stock_level;
    }

    public function isOverStock()
    {
        return $this->max_stock_level && $this->quantity > $this->max_stock_level;
    }

    public function getStockStatus()
    {
        if ($this->quantity <= 0) {
            return 'out_of_stock';
        } elseif ($this->isLowStock()) {
            return 'low_stock';
        } elseif ($this->isOverStock()) {
            return 'over_stock';
        } else {
            return 'normal';
        }
    }

    public function getStockStatusLabel()
    {
        $statuses = [
            'out_of_stock' => 'نفد المخزون',
            'low_stock' => 'مخزون منخفض',
            'over_stock' => 'مخزون زائد',
            'normal' => 'طبيعي',
        ];

        return $statuses[$this->getStockStatus()] ?? 'غير محدد';
    }

    public function getStockStatusColor()
    {
        $colors = [
            'out_of_stock' => 'danger',
            'low_stock' => 'warning',
            'over_stock' => 'info',
            'normal' => 'success',
        ];

        return $colors[$this->getStockStatus()] ?? 'secondary';
    }

    public function getFormattedQuantity()
    {
        return number_format($this->quantity, 2);
    }

    public function getFormattedAvailableQuantity()
    {
        return number_format($this->available_quantity, 2);
    }

    public function getFormattedReservedQuantity()
    {
        return number_format($this->reserved_quantity, 2);
    }
}
