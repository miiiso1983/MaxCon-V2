<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InventoryAdjustment extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'adjustment_number',
        'warehouse_id',
        'adjustment_date',
        'adjustment_type',
        'reason',
        'reason_description',
        'reference_type',
        'reference_id',
        'reference_number',
        'total_items',
        'total_value_impact',
        'status',
        'notes',
        'internal_notes',
        'created_by',
        'approved_by',
        'approved_at',
        'processed_by',
        'processed_at',
        'attachments',
    ];

    protected $casts = [
        'adjustment_date' => 'date',
        'total_value_impact' => 'decimal:2',
        'approved_at' => 'datetime',
        'processed_at' => 'datetime',
        'attachments' => 'array',
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

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function processedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function adjustmentItems(): HasMany
    {
        return $this->hasMany(InventoryAdjustmentItem::class);
    }

    // Scopes
    public function scopeForTenant($query, $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('adjustment_type', $type);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending_approval');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    // Methods
    public function getStatusLabel()
    {
        $statuses = [
            'draft' => 'مسودة',
            'pending_approval' => 'في انتظار الموافقة',
            'approved' => 'موافق عليه',
            'rejected' => 'مرفوض',
            'processed' => 'تم المعالجة',
        ];

        return $statuses[$this->status] ?? $this->status;
    }

    public function getStatusColor()
    {
        $colors = [
            'draft' => 'secondary',
            'pending_approval' => 'warning',
            'approved' => 'success',
            'rejected' => 'danger',
            'processed' => 'primary',
        ];

        return $colors[$this->status] ?? 'secondary';
    }

    public function getTypeLabel()
    {
        $types = [
            'increase' => 'زيادة',
            'decrease' => 'نقص',
            'transfer' => 'نقل',
            'correction' => 'تصحيح',
            'audit_adjustment' => 'تعديل جرد',
        ];

        return $types[$this->adjustment_type] ?? $this->adjustment_type;
    }

    public function getReasonLabel()
    {
        $reasons = [
            'audit_discrepancy' => 'اختلاف في الجرد',
            'damage' => 'تلف',
            'theft' => 'سرقة',
            'expired' => 'منتهي الصلاحية',
            'found' => 'عثور على مخزون',
            'system_error' => 'خطأ في النظام',
            'manual_correction' => 'تصحيح يدوي',
            'other' => 'أخرى',
        ];

        return $reasons[$this->reason] ?? $this->reason;
    }

    public function calculateTotalItems()
    {
        $this->total_items = $this->adjustmentItems()->count();
        return $this->total_items;
    }

    public function calculateTotalValueImpact()
    {
        $this->total_value_impact = $this->adjustmentItems()->sum('total_cost_impact');
        return $this->total_value_impact;
    }

    public function canBeApproved()
    {
        return $this->status === 'pending_approval';
    }

    public function canBeProcessed()
    {
        return $this->status === 'approved';
    }

    public function approve($userId = null)
    {
        $this->status = 'approved';
        $this->approved_by = $userId;
        $this->approved_at = now();
        $this->save();
    }

    public function reject($userId = null, $reason = null)
    {
        $this->status = 'rejected';
        $this->approved_by = $userId;
        $this->approved_at = now();
        if ($reason) {
            $this->internal_notes = $reason;
        }
        $this->save();
    }

    public function process($userId = null)
    {
        $this->status = 'processed';
        $this->processed_by = $userId;
        $this->processed_at = now();
        $this->save();

        // Process each adjustment item
        foreach ($this->adjustmentItems as $item) {
            $item->processAdjustment();
        }
    }

    public function generateAdjustmentNumber()
    {
        $prefix = 'ADJ';
        $date = now()->format('Ymd');
        $sequence = static::where('tenant_id', $this->tenant_id)
            ->whereDate('created_at', now())
            ->count() + 1;

        return $prefix . '-' . $date . '-' . str_pad($sequence, 3, '0', STR_PAD_LEFT);
    }

    // Boot method
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->adjustment_number)) {
                $model->adjustment_number = $model->generateAdjustmentNumber();
            }
        });

        static::saved(function ($model) {
            $model->calculateTotalItems();
            $model->calculateTotalValueImpact();
        });
    }
}
