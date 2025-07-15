<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Inventory extends Model
{
    use HasFactory;

    protected $table = 'inventory';

    protected $fillable = [
        'tenant_id',
        'warehouse_id',
        'location_id',
        'product_id',
        'batch_number',
        'serial_number',
        'manufacture_date',
        'expiry_date',
        'quantity',
        'reserved_quantity',
        'available_quantity',
        'cost_price',
        'selling_price',
        'status',
        'notes',
        'properties',
    ];

    protected $casts = [
        'manufacture_date' => 'date',
        'expiry_date' => 'date',
        'quantity' => 'decimal:3',
        'reserved_quantity' => 'decimal:3',
        'available_quantity' => 'decimal:3',
        'cost_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'properties' => 'array',
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

    public function location(): BelongsTo
    {
        return $this->belongsTo(WarehouseLocation::class, 'location_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function movements(): HasMany
    {
        return $this->hasMany(InventoryMovement::class);
    }

    public function auditItems(): HasMany
    {
        return $this->hasMany(InventoryAuditItem::class);
    }

    // Scopes
    public function scopeForTenant($query, $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeAvailable($query)
    {
        return $query->where('available_quantity', '>', 0);
    }

    public function scopeExpiring($query, $days = 30)
    {
        return $query->whereNotNull('expiry_date')
            ->where('expiry_date', '<=', now()->addDays($days));
    }

    public function scopeExpired($query)
    {
        return $query->whereNotNull('expiry_date')
            ->where('expiry_date', '<', now());
    }

    public function scopeByWarehouse($query, $warehouseId)
    {
        return $query->where('warehouse_id', $warehouseId);
    }

    public function scopeByProduct($query, $productId)
    {
        return $query->where('product_id', $productId);
    }

    public function scopeByBatch($query, $batchNumber)
    {
        return $query->where('batch_number', $batchNumber);
    }

    public function scopeLowStock($query)
    {
        return $query->whereRaw('available_quantity <= (SELECT min_stock_level FROM products WHERE products.id = inventory.product_id)');
    }

    // Methods
    public function updateAvailableQuantity()
    {
        $this->available_quantity = $this->quantity - $this->reserved_quantity;
        $this->save();
    }

    public function reserve($quantity)
    {
        if ($this->available_quantity >= $quantity) {
            $this->reserved_quantity += $quantity;
            $this->updateAvailableQuantity();
            return true;
        }
        return false;
    }

    public function unreserve($quantity)
    {
        $unreserveAmount = min($quantity, $this->reserved_quantity);
        $this->reserved_quantity -= $unreserveAmount;
        $this->updateAvailableQuantity();
        return $unreserveAmount;
    }

    public function addQuantity($quantity, $unitCost = null)
    {
        $this->quantity += $quantity;

        if ($unitCost && $unitCost > 0) {
            // Update weighted average cost
            $totalValue = ($this->quantity - $quantity) * $this->cost_price + $quantity * $unitCost;
            $this->cost_price = $totalValue / $this->quantity;
        }

        $this->updateAvailableQuantity();
        return $this;
    }

    public function removeQuantity($quantity)
    {
        $removeAmount = min($quantity, $this->available_quantity);
        $this->quantity -= $removeAmount;
        $this->updateAvailableQuantity();
        return $removeAmount;
    }

    public function getDaysUntilExpiry()
    {
        if (!$this->expiry_date) {
            return null;
        }

        return now()->diffInDays($this->expiry_date, false);
    }

    public function isExpired()
    {
        return $this->expiry_date && $this->expiry_date < now();
    }

    public function isExpiring($days = 30)
    {
        return $this->expiry_date && $this->expiry_date <= now()->addDays($days);
    }

    public function getExpiryStatus()
    {
        if (!$this->expiry_date) {
            return 'no_expiry';
        }

        if ($this->isExpired()) {
            return 'expired';
        }

        if ($this->isExpiring(30)) {
            return 'expiring_soon';
        }

        if ($this->isExpiring(90)) {
            return 'expiring_warning';
        }

        return 'good';
    }

    public function getExpiryStatusLabel()
    {
        $statuses = [
            'no_expiry' => 'لا ينتهي',
            'expired' => 'منتهي الصلاحية',
            'expiring_soon' => 'ينتهي قريباً',
            'expiring_warning' => 'تحذير انتهاء',
            'good' => 'صالح',
        ];

        return $statuses[$this->getExpiryStatus()] ?? 'غير محدد';
    }

    public function getExpiryStatusColor()
    {
        $colors = [
            'no_expiry' => 'secondary',
            'expired' => 'danger',
            'expiring_soon' => 'danger',
            'expiring_warning' => 'warning',
            'good' => 'success',
        ];

        return $colors[$this->getExpiryStatus()] ?? 'secondary';
    }

    public function getTotalValue()
    {
        return $this->quantity * $this->cost_price;
    }

    public function getAvailableValue()
    {
        return $this->available_quantity * $this->cost_price;
    }

    public function getStatusLabel()
    {
        $statuses = [
            'active' => 'نشط',
            'quarantine' => 'حجر صحي',
            'damaged' => 'تالف',
            'expired' => 'منتهي الصلاحية',
            'recalled' => 'مسحوب',
        ];

        return $statuses[$this->status] ?? $this->status;
    }

    public function getStatusColor()
    {
        $colors = [
            'active' => 'success',
            'quarantine' => 'warning',
            'damaged' => 'danger',
            'expired' => 'danger',
            'recalled' => 'danger',
        ];

        return $colors[$this->status] ?? 'secondary';
    }

    public function canBeSold()
    {
        return $this->status === 'active' &&
               $this->available_quantity > 0 &&
               !$this->isExpired();
    }

    public function getLocationPath()
    {
        return $this->location ? $this->location->getFullPath() : 'غير محدد';
    }
}
