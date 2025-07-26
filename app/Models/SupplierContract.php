<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class SupplierContract extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'supplier_id',
        'contract_number',
        'title',
        'description',
        'type',
        'status',
        'start_date',
        'end_date',
        'signed_date',
        'renewal_date',
        'renewal_period_months',
        'auto_renewal',
        'contract_value',
        'minimum_order_value',
        'maximum_order_value',
        'currency',
        'payment_terms',
        'delivery_terms',
        'quality_requirements',
        'penalty_terms',
        'termination_conditions',
        'special_conditions',
        'attachments',
        'created_by',
        'approved_by',
        'approved_at',
        'notes',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'signed_date' => 'datetime',
        'renewal_date' => 'datetime',
        'approved_at' => 'datetime',
        'auto_renewal' => 'boolean',
        'contract_value' => 'decimal:2',
        'minimum_order_value' => 'decimal:2',
        'maximum_order_value' => 'decimal:2',
        'attachments' => 'array',
    ];

    protected $dates = [
        'start_date',
        'end_date',
        'signed_date',
        'renewal_date',
        'approved_at',
    ];

    // Relationships
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
                    ->where('start_date', '<=', now())
                    ->where('end_date', '>=', now());
    }

    public function scopeExpired($query)
    {
        return $query->where('end_date', '<', now());
    }

    public function scopeExpiringSoon($query, $days = 30)
    {
        return $query->where('end_date', '>', now())
                    ->where('end_date', '<=', now()->addDays($days));
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // Accessors
    public function getIsActiveAttribute()
    {
        return $this->status === 'active' &&
               $this->start_date <= now() &&
               $this->end_date >= now();
    }

    public function getIsExpiredAttribute()
    {
        return $this->end_date < now();
    }

    public function getIsExpiringSoonAttribute()
    {
        $endDate = \Carbon\Carbon::parse($this->end_date);
        return $endDate > now() && $endDate <= now()->addDays(30);
    }

    public function getDaysUntilExpiryAttribute()
    {
        return \Carbon\Carbon::parse($this->end_date)->diffInDays(now(), false);
    }

    public function getStatusTextAttribute()
    {
        return match($this->status) {
            'draft' => 'مسودة',
            'pending' => 'في الانتظار',
            'active' => 'نشط',
            'expired' => 'منتهي',
            'terminated' => 'مُنهى',
            'cancelled' => 'ملغي',
            default => 'غير محدد'
        };
    }

    public function getTypeTextAttribute()
    {
        return match($this->type) {
            'supply' => 'عقد توريد',
            'service' => 'عقد خدمة',
            'maintenance' => 'عقد صيانة',
            'consulting' => 'عقد استشاري',
            'framework' => 'عقد إطاري',
            default => 'غير محدد'
        };
    }
}
