<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InventoryAudit extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'audit_number',
        'warehouse_id',
        'audit_type',
        'status',
        'scheduled_date',
        'started_at',
        'completed_at',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'scheduled_date' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
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

    public function auditItems(): HasMany
    {
        return $this->hasMany(InventoryAuditItem::class);
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
        return $query->where('audit_type', $type);
    }

    // Methods
    public function getAuditTypeLabel()
    {
        $types = [
            'full' => 'جرد شامل',
            'partial' => 'جرد جزئي',
            'cycle' => 'جرد دوري',
            'spot' => 'جرد فوري',
        ];

        return $types[$this->audit_type] ?? $this->audit_type;
    }

    public function getStatusLabel()
    {
        $statuses = [
            'scheduled' => 'مجدول',
            'in_progress' => 'قيد التنفيذ',
            'completed' => 'مكتمل',
            'cancelled' => 'ملغي',
        ];

        return $statuses[$this->status] ?? $this->status;
    }

    public function getStatusColor()
    {
        $colors = [
            'scheduled' => 'info',
            'in_progress' => 'warning',
            'completed' => 'success',
            'cancelled' => 'danger',
        ];

        return $colors[$this->status] ?? 'secondary';
    }

    public function getTotalItems()
    {
        return $this->auditItems()->count();
    }

    public function getCompletedItems()
    {
        return $this->auditItems()->whereNotNull('counted_quantity')->count();
    }

    public function getProgressPercentage()
    {
        $total = $this->getTotalItems();
        if ($total === 0) {
            return 0;
        }

        return round(($this->getCompletedItems() / $total) * 100, 1);
    }

    public function getDiscrepancies()
    {
        return $this->auditItems()
            ->whereRaw('counted_quantity != system_quantity')
            ->count();
    }
}
