<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryAuditItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'inventory_audit_id',
        'audit_id',
        'product_id',
        'location_id',
        'batch_number',
        'expiry_date',
        'system_quantity',
        'expected_quantity',
        'counted_quantity',
        'difference_quantity',
        'variance',
        'unit_cost',
        'value_difference',
        'variance_value',
        'variance_percentage',
        'status',
        'count_method',
        'condition_status',
        'action',
        'discrepancy_reason',
        'reason_description',
        'counted_by',
        'counted_at',
        'verified_by',
        'verified_at',
        'adjustment_required',
        'adjustment_processed',
        'notes',
        'serial_numbers',
    ];

    protected $casts = [
        'expiry_date' => 'date',
        'system_quantity' => 'decimal:3',
        'expected_quantity' => 'decimal:3',
        'counted_quantity' => 'decimal:3',
        'difference_quantity' => 'decimal:3',
        'variance' => 'decimal:3',
        'unit_cost' => 'decimal:2',
        'value_difference' => 'decimal:2',
        'variance_value' => 'decimal:2',
        'variance_percentage' => 'decimal:2',
        'counted_at' => 'datetime',
        'verified_at' => 'datetime',
        'adjustment_required' => 'boolean',
        'adjustment_processed' => 'boolean',
        'serial_numbers' => 'array',
    ];

    // Relationships
    public function inventoryAudit(): BelongsTo
    {
        return $this->belongsTo(InventoryAudit::class);
    }

    public function audit(): BelongsTo
    {
        return $this->belongsTo(InventoryAudit::class, 'audit_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(WarehouseLocation::class, 'location_id');
    }

    public function countedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'counted_by');
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    // Scopes
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeWithDiscrepancies($query)
    {
        return $query->whereRaw('counted_quantity != system_quantity');
    }

    public function scopeCounted($query)
    {
        return $query->whereNotNull('counted_quantity');
    }

    public function scopeVerified($query)
    {
        return $query->whereNotNull('verified_at');
    }

    public function scopeRequiringAdjustment($query)
    {
        return $query->where('adjustment_required', true)
                    ->where('adjustment_processed', false);
    }

    // Methods
    public function calculateDifference()
    {
        if ($this->counted_quantity !== null && $this->expected_quantity !== null) {
            $this->difference_quantity = number_format($this->counted_quantity - $this->expected_quantity, 3, '.', '');
            $this->variance = $this->difference_quantity;

            if ($this->unit_cost > 0) {
                $this->value_difference = number_format($this->difference_quantity * $this->unit_cost, 2, '.', '');
                $this->variance_value = $this->value_difference;
            }

            if ($this->expected_quantity > 0) {
                $this->variance_percentage = number_format(($this->difference_quantity / $this->expected_quantity) * 100, 2, '.', '');
            }

            // Determine if adjustment is required
            $this->adjustment_required = abs((float) $this->difference_quantity) > 0;
        }
    }

    public function getStatusLabel()
    {
        $statuses = [
            'pending' => 'في الانتظار',
            'counted' => 'تم العد',
            'verified' => 'تم التحقق',
            'adjusted' => 'تم التعديل',
        ];

        return $statuses[$this->status] ?? $this->status;
    }

    public function getStatusColor()
    {
        $colors = [
            'pending' => 'warning',
            'counted' => 'info',
            'verified' => 'success',
            'adjusted' => 'primary',
        ];

        return $colors[$this->status] ?? 'secondary';
    }

    public function getDiscrepancyReasonLabel()
    {
        $reasons = [
            'theft' => 'سرقة',
            'damage' => 'تلف',
            'expired' => 'منتهي الصلاحية',
            'misplaced' => 'في غير مكانه',
            'system_error' => 'خطأ في النظام',
            'counting_error' => 'خطأ في العد',
            'other' => 'أخرى',
        ];

        return $reasons[$this->discrepancy_reason] ?? $this->discrepancy_reason;
    }

    public function hasDiscrepancy()
    {
        return $this->difference_quantity != 0;
    }

    public function isOverage()
    {
        return $this->difference_quantity > 0;
    }

    public function isShortage()
    {
        return $this->difference_quantity < 0;
    }

    public function getVarianceType()
    {
        if ($this->difference_quantity > 0) {
            return 'overage';
        } elseif ($this->difference_quantity < 0) {
            return 'shortage';
        }
        return 'match';
    }

    public function getVarianceTypeLabel()
    {
        $types = [
            'overage' => 'زيادة',
            'shortage' => 'نقص',
            'match' => 'مطابق',
        ];

        return $types[$this->getVarianceType()] ?? 'غير محدد';
    }

    public function getAbsoluteVariance()
    {
        return abs((float) $this->difference_quantity);
    }

    public function getAbsoluteVarianceValue()
    {
        return abs((float) $this->value_difference);
    }

    public function markAsCounted($quantity, $userId = null)
    {
        $this->counted_quantity = $quantity;
        $this->counted_by = $userId;
        $this->counted_at = now();
        $this->status = 'counted';
        
        $this->calculateDifference();
        $this->save();
    }

    public function markAsVerified($userId = null)
    {
        $this->verified_by = $userId;
        $this->verified_at = now();
        $this->status = 'verified';
        $this->save();
    }

    public function processAdjustment()
    {
        $this->adjustment_processed = true;
        $this->status = 'adjusted';
        $this->save();
    }

    // Boot method to auto-calculate differences
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $model->calculateDifference();
        });
    }
}
