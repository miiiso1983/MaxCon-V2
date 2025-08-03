<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'product_code',
        'name',
        'description',
        'category',
        'brand',
        'manufacturer',
        'batch_number',
        'expiry_date',
        'unit_of_measure',
        'cost_price',
        'selling_price',
        'min_selling_price',
        'stock_quantity',
        'min_stock_level',
        'max_stock_level',
        'tax_rate',
        'is_taxable',
        'is_active',
        'track_expiry',
        'track_batch',
        'barcode',
        'image_url',
        'specifications',
        'notes',
        // Virtual attributes handled by accessors/mutators
        'purchase_price', // maps to cost_price
        'current_stock', // maps to stock_quantity
        'unit', // maps to unit_of_measure
        'generic_name', // if exists in DB
        'manufacturing_date', // if exists in DB
        'storage_conditions', // if exists in DB
    ];

    protected $casts = [
        'cost_price' => 'decimal:2',
        'purchase_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'wholesale_price' => 'decimal:2',
        'retail_price' => 'decimal:2',
        'unit_weight' => 'decimal:3',
        'unit_volume' => 'decimal:3',
        'current_stock' => 'decimal:2',
        'stock_quantity' => 'decimal:2',
        'minimum_stock' => 'decimal:2',
        'maximum_stock' => 'decimal:2',
        'min_stock_level' => 'integer',
        'max_stock_level' => 'integer',
        'reorder_level' => 'decimal:2',
        'reorder_quantity' => 'decimal:2',
        'track_stock' => 'boolean',
        'allow_backorder' => 'boolean',
        'is_featured' => 'boolean',
        'manufacturing_date' => 'date',
        'expiry_date' => 'date',
        'shelf_life_days' => 'integer',
        'images' => 'array',
        'tags' => 'array',
        'attributes' => 'array',
        'variations' => 'array',
    ];

    // Accessors and Mutators for field compatibility

    /**
     * Get purchase_price attribute (maps to cost_price since purchase_price doesn't exist in DB)
     */
    public function getPurchasePriceAttribute()
    {
        return $this->attributes['cost_price'] ?? 0;
    }

    /**
     * Set purchase_price attribute (save to cost_price since purchase_price doesn't exist in DB)
     */
    public function setPurchasePriceAttribute($value)
    {
        $this->attributes['cost_price'] = $value;
    }

    /**
     * Get current_stock attribute (maps to stock_quantity since current_stock doesn't exist in DB)
     */
    public function getCurrentStockAttribute()
    {
        return $this->attributes['stock_quantity'] ?? 0;
    }

    /**
     * Set current_stock attribute (save to stock_quantity since current_stock doesn't exist in DB)
     */
    public function setCurrentStockAttribute($value)
    {
        $this->attributes['stock_quantity'] = $value;
    }

    /**
     * Get unit attribute (maps to unit_of_measure since unit doesn't exist in DB)
     */
    public function getUnitAttribute()
    {
        return $this->attributes['unit_of_measure'] ?? 'قطعة';
    }

    /**
     * Set unit attribute (save to unit_of_measure since unit doesn't exist in DB)
     */
    public function setUnitAttribute($value)
    {
        $this->attributes['unit_of_measure'] = $value;
        $this->attributes['stock_quantity'] = $value; // للتوافق مع الحقول القديمة
    }

    // Relationships
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    public function salesOrderItems(): HasMany
    {
        return $this->hasMany(SalesOrderItem::class);
    }

    public function invoiceItems(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeForTenant($query, $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }

    public function scopeLowStock($query)
    {
        return $query->whereColumn('stock_quantity', '<=', 'min_stock_level');
    }

    public function scopeExpiringSoon($query, $days = 30)
    {
        return $query->where('track_expiry', true)
            ->where('expiry_date', '<=', now()->addDays($days))
            ->where('expiry_date', '>', now());
    }

    public function scopeExpired($query)
    {
        return $query->where('track_expiry', true)
            ->where('expiry_date', '<', now());
    }

    // Helper Methods
    public function generateProductCode()
    {
        $prefix = 'PROD';
        $lastProduct = static::where('tenant_id', $this->tenant_id)
            ->where('product_code', 'like', "{$prefix}%")
            ->orderBy('product_code', 'desc')
            ->first();

        if ($lastProduct) {
            $lastNumber = (int) substr($lastProduct->product_code, strlen($prefix));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . str_pad($newNumber, 6, '0', STR_PAD_LEFT);
    }

    public function updateStock($quantity, $operation = 'subtract')
    {
        if ($operation === 'subtract') {
            $this->stock_quantity -= $quantity;
        } else {
            $this->stock_quantity += $quantity;
        }

        $this->save();
    }

    public function isLowStock(): bool
    {
        return $this->stock_quantity <= $this->min_stock_level;
    }

    public function isExpiringSoon($days = 30): bool
    {
        if (!$this->track_expiry || !$this->expiry_date) {
            return false;
        }

        return $this->expiry_date <= now()->addDays($days) && $this->expiry_date > now();
    }

    public function isExpired(): bool
    {
        if (!$this->track_expiry || !$this->expiry_date) {
            return false;
        }

        return $this->expiry_date < now();
    }

    public function calculateTaxAmount($price): float
    {
        if (!$this->is_taxable) {
            return 0;
        }

        return ($price * $this->tax_rate) / 100;
    }

    public function getPriceWithTax(): float
    {
        return $this->selling_price + $this->calculateTaxAmount($this->selling_price);
    }

    /**
     * Generate unique product code for tenant
     */
    public static function generateCode($tenantId): string
    {
        $prefix = 'PRD';
        $lastProduct = self::where('tenant_id', $tenantId)
            ->where('code', 'like', $prefix . '%')
            ->orderBy('code', 'desc')
            ->first();

        if ($lastProduct && preg_match('/PRD(\d+)/', $lastProduct->code, $matches)) {
            $number = intval($matches[1]) + 1;
        } else {
            $number = 1;
        }

        return $prefix . str_pad($number, 6, '0', STR_PAD_LEFT);
    }

    // New methods for enhanced product management
    public function getStatusLabelAttribute(): string
    {
        $status = $this->status ?? ($this->is_active ? 'active' : 'inactive');
        return match($status) {
            'active' => 'نشط',
            'inactive' => 'غير نشط',
            'discontinued' => 'متوقف',
            'out_of_stock' => 'نفد المخزون',
            default => 'غير محدد'
        };
    }

    public function getStockStatusAttribute(): string
    {
        $currentStock = $this->current_stock ?? $this->stock_quantity ?? 0;
        $minimumStock = $this->minimum_stock ?? $this->min_stock_level ?? 0;

        if ($currentStock <= 0) {
            return 'out_of_stock';
        } elseif ($currentStock <= $minimumStock) {
            return 'low_stock';
        } else {
            return 'in_stock';
        }
    }

    public function getStockStatusLabelAttribute(): string
    {
        return match($this->stock_status) {
            'out_of_stock' => 'نفد المخزون',
            'low_stock' => 'مخزون منخفض',
            'in_stock' => 'متوفر',
            default => 'غير محدد'
        };
    }

    public function getPrimaryImageUrlAttribute(): ?string
    {
        if ($this->primary_image) {
            return asset('storage/' . $this->primary_image);
        }

        if ($this->images && count($this->images) > 0) {
            return asset('storage/' . $this->images[0]);
        }

        return asset('images/no-image.png');
    }

    public function generateBarcode(): string
    {
        if ($this->barcode) {
            return $this->barcode;
        }

        // Generate EAN-13 barcode
        $code = str_pad($this->id, 12, '0', STR_PAD_LEFT);
        $checksum = $this->calculateEAN13Checksum($code);

        return $code . $checksum;
    }

    public function generateQRCode(): string
    {
        return json_encode([
            'type' => 'product',
            'id' => $this->id,
            'code' => $this->code ?? $this->product_code,
            'name' => $this->name,
            'price' => $this->selling_price,
            'currency' => $this->currency ?? 'IQD',
        ]);
    }

    private function calculateEAN13Checksum(string $code): int
    {
        $sum = 0;
        for ($i = 0; $i < 12; $i++) {
            $sum += (int)$code[$i] * (($i % 2 === 0) ? 1 : 3);
        }
        return (10 - ($sum % 10)) % 10;
    }
}
