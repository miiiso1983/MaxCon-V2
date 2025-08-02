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
        'name',
        'code',
        'barcode',
        'qr_code',
        'description',
        'short_description',
        'category_id',
        'brand',
        'manufacturer',
        'country_of_origin',
        'cost_price',
        'selling_price',
        'wholesale_price',
        'retail_price',
        'currency',
        'base_unit',
        'unit_weight',
        'unit_volume',
        'dimensions',
        'current_stock',
        'minimum_stock',
        'maximum_stock',
        'reorder_level',
        'reorder_quantity',
        'status',
        'type',
        'track_stock',
        'allow_backorder',
        'is_featured',
        'manufacturing_date',
        'expiry_date',
        'shelf_life_days',
        'images',
        'primary_image',
        'slug',
        'meta_title',
        'meta_description',
        'tags',
        'attributes',
        'variations',
        'notes'
    ];

    protected $casts = [
        'cost_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'wholesale_price' => 'decimal:2',
        'retail_price' => 'decimal:2',
        'unit_weight' => 'decimal:3',
        'unit_volume' => 'decimal:3',
        'current_stock' => 'decimal:2',
        'minimum_stock' => 'decimal:2',
        'maximum_stock' => 'decimal:2',
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
