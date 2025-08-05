<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'code',
        'name',
        'name_en',
        'description',
        'type',
        'status',
        'contact_person',
        'phone',
        'mobile',
        'email',
        'website',
        'address',
        'city',
        'country',
        'tax_number',
        'commercial_registration',
        'license_number',
        'license_expiry',
        'bank_name',
        'bank_account',
        'iban',
        'rating',
        'total_orders',
        'total_amount',
        'average_delivery_time',
        'quality_score',
        'service_score',
        'payment_terms',
        'credit_days',
        'credit_limit',
        'current_balance',
        'currency',
        'category',
        'categories',
        'certifications',
        'notes',
        'is_preferred',
        'first_order_date',
        'last_order_date',
    ];

    protected $casts = [
        'license_expiry' => 'date',
        'first_order_date' => 'date',
        'last_order_date' => 'date',
        'rating' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'average_delivery_time' => 'decimal:2',
        'quality_score' => 'decimal:2',
        'service_score' => 'decimal:2',
        'credit_limit' => 'decimal:2',
        'current_balance' => 'decimal:2',
        'categories' => 'array',
        'certifications' => 'array',
        'is_preferred' => 'boolean',
    ];

    // Relationships
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function purchaseOrders(): HasMany
    {
        return $this->hasMany(PurchaseOrder::class);
    }

    public function quotations(): HasMany
    {
        return $this->hasMany(Quotation::class);
    }

    public function contracts(): HasMany
    {
        return $this->hasMany(SupplierContract::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopePreferred($query)
    {
        return $query->where('is_preferred', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    // Accessors
    public function getFullNameAttribute()
    {
        return $this->name_en ? "{$this->name} ({$this->name_en})" : $this->name;
    }

    public function getOverallScoreAttribute()
    {
        return ($this->quality_score + $this->service_score) / 2;
    }

    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'active' => 'نشط',
            'inactive' => 'غير نشط',
            'suspended' => 'معلق',
            'blacklisted' => 'قائمة سوداء',
            default => $this->status
        };
    }

    public function getTypeLabelAttribute()
    {
        return match($this->type) {
            'manufacturer' => 'مصنع',
            'distributor' => 'موزع',
            'wholesaler' => 'تاجر جملة',
            'retailer' => 'تاجر تجزئة',
            'service_provider' => 'مقدم خدمة',
            default => $this->type
        };
    }

    // Methods
    public function updateRating()
    {
        $orders = $this->purchaseOrders()->where('status', 'completed')->get();

        if ($orders->count() > 0) {
            $this->update([
                'total_orders' => $orders->count(),
                'total_amount' => $orders->sum('total_amount'),
                'average_delivery_time' => $orders->avg('delivery_days'),
                'last_order_date' => $orders->max('order_date'),
            ]);
        }
    }

    public function canOrder($amount = 0)
    {
        if ($this->status !== 'active') {
            return false;
        }

        if ($this->credit_limit > 0 && ($this->current_balance + $amount) > $this->credit_limit) {
            return false;
        }

        return true;
    }
}
