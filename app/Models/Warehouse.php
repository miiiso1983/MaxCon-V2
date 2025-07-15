<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Warehouse extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'code',
        'name',
        'description',
        'location',
        'address',
        'phone',
        'email',
        'manager_id',
        'type',
        'is_active',
        'total_capacity',
        'used_capacity',
        'settings',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'total_capacity' => 'decimal:2',
        'used_capacity' => 'decimal:2',
        'settings' => 'array',
    ];

    // Relationships
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function locations(): HasMany
    {
        return $this->hasMany(WarehouseLocation::class);
    }

    public function inventory(): HasMany
    {
        return $this->hasMany(Inventory::class);
    }

    public function movements(): HasMany
    {
        return $this->hasMany(InventoryMovement::class);
    }

    public function audits(): HasMany
    {
        return $this->hasMany(InventoryAudit::class);
    }

    public function alerts(): HasMany
    {
        return $this->hasMany(InventoryAlert::class);
    }

    // Scopes
    public function scopeForTenant($query, $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    // Methods
    public function generateCode()
    {
        $prefix = strtoupper(substr($this->type, 0, 2));
        $lastWarehouse = static::where('tenant_id', $this->tenant_id)
            ->where('code', 'like', $prefix . '%')
            ->orderBy('code', 'desc')
            ->first();

        $sequence = $lastWarehouse ? (int)substr($lastWarehouse->code, -3) + 1 : 1;

        return $prefix . str_pad($sequence, 3, '0', STR_PAD_LEFT);
    }

    public function getTotalProducts()
    {
        return $this->inventory()->distinct('product_id')->count();
    }

    public function getTotalQuantity()
    {
        return $this->inventory()->sum('quantity');
    }

    public function getAvailableQuantity()
    {
        return $this->inventory()->sum('available_quantity');
    }

    public function getReservedQuantity()
    {
        return $this->inventory()->sum('reserved_quantity');
    }

    public function getTotalValue()
    {
        return $this->inventory()
            ->selectRaw('SUM(quantity * cost_price) as total_value')
            ->value('total_value') ?? 0;
    }

    public function getCapacityUsagePercentage()
    {
        if ($this->total_capacity > 0) {
            return round(($this->used_capacity / $this->total_capacity) * 100, 2);
        }
        return 0;
    }

    public function getLowStockItems($threshold = null)
    {
        $query = $this->inventory()
            ->with('product')
            ->where('status', 'active');

        if ($threshold) {
            $query->where('available_quantity', '<=', $threshold);
        } else {
            $query->whereRaw('available_quantity <= (SELECT min_stock_level FROM products WHERE products.id = inventory.product_id)');
        }

        return $query->get();
    }

    public function getExpiringItems($days = 30)
    {
        return $this->inventory()
            ->with('product')
            ->where('status', 'active')
            ->whereNotNull('expiry_date')
            ->where('expiry_date', '<=', now()->addDays($days))
            ->orderBy('expiry_date')
            ->get();
    }

    public function getExpiredItems()
    {
        return $this->inventory()
            ->with('product')
            ->where('status', 'active')
            ->whereNotNull('expiry_date')
            ->where('expiry_date', '<', now())
            ->orderBy('expiry_date')
            ->get();
    }

    public function updateCapacity()
    {
        $this->used_capacity = $this->getTotalQuantity();
        $this->save();
    }

    public function createLocation($data)
    {
        return $this->locations()->create($data);
    }

    public function getLocationByCode($code)
    {
        return $this->locations()->where('code', $code)->first();
    }

    public function getActiveLocations()
    {
        return $this->locations()->where('is_active', true)->get();
    }

    public function getInventoryByProduct($productId)
    {
        return $this->inventory()
            ->where('product_id', $productId)
            ->where('status', 'active')
            ->get();
    }

    public function getAvailableQuantityForProduct($productId)
    {
        return $this->inventory()
            ->where('product_id', $productId)
            ->where('status', 'active')
            ->sum('available_quantity');
    }

    public function hasProduct($productId)
    {
        return $this->inventory()
            ->where('product_id', $productId)
            ->where('quantity', '>', 0)
            ->exists();
    }

    public function getProductBatches($productId)
    {
        return $this->inventory()
            ->where('product_id', $productId)
            ->where('status', 'active')
            ->where('quantity', '>', 0)
            ->orderBy('expiry_date')
            ->get();
    }

    public function getOldestBatch($productId)
    {
        return $this->inventory()
            ->where('product_id', $productId)
            ->where('status', 'active')
            ->where('quantity', '>', 0)
            ->orderBy('expiry_date')
            ->first();
    }

    public function getNewestBatch($productId)
    {
        return $this->inventory()
            ->where('product_id', $productId)
            ->where('status', 'active')
            ->where('quantity', '>', 0)
            ->orderBy('expiry_date', 'desc')
            ->first();
    }

    public function getAverageUnitCost($productId)
    {
        return $this->inventory()
            ->where('product_id', $productId)
            ->where('status', 'active')
            ->where('quantity', '>', 0)
            ->avg('cost_price') ?? 0;
    }

    public function getTypeLabel()
    {
        $types = [
            'main' => 'مستودع رئيسي',
            'branch' => 'مستودع فرع',
            'storage' => 'مخزن',
            'pharmacy' => 'صيدلية',
        ];

        return $types[$this->type] ?? $this->type;
    }

    public function getStatusLabel()
    {
        return $this->is_active ? 'نشط' : 'غير نشط';
    }

    public function getStatusColor()
    {
        return $this->is_active ? 'success' : 'danger';
    }
}
