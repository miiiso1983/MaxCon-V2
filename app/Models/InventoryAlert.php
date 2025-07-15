<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryAlert extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'warehouse_id',
        'product_id',
        'alert_type',
        'priority',
        'status',
        'title',
        'message',
        'triggered_at',
        'acknowledged_at',
        'acknowledged_by',
        'resolved_at',
        'resolved_by',
        'resolution_notes',
        'alert_data',
    ];

    protected $casts = [
        'triggered_at' => 'datetime',
        'acknowledged_at' => 'datetime',
        'resolved_at' => 'datetime',
        'alert_data' => 'array',
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

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function acknowledgedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'acknowledged_by');
    }

    public function resolvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'resolved_by');
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

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('alert_type', $type);
    }

    public function scopeCritical($query)
    {
        return $query->where('priority', 'critical');
    }

    // Methods
    public function getAlertTypeLabel()
    {
        $types = [
            'low_stock' => 'مخزون منخفض',
            'out_of_stock' => 'نفاد المخزون',
            'expiring_soon' => 'انتهاء صلاحية قريب',
            'expired' => 'منتهي الصلاحية',
            'damaged' => 'تلف',
            'overstock' => 'مخزون زائد',
            'movement_anomaly' => 'حركة غير طبيعية',
        ];

        return $types[$this->alert_type] ?? $this->alert_type;
    }

    public function getPriorityLabel()
    {
        $priorities = [
            'low' => 'منخفض',
            'medium' => 'متوسط',
            'high' => 'عالي',
            'critical' => 'حرج',
        ];

        return $priorities[$this->priority] ?? $this->priority;
    }

    public function getStatusLabel()
    {
        $statuses = [
            'active' => 'نشط',
            'acknowledged' => 'مؤكد',
            'resolved' => 'محلول',
            'dismissed' => 'مرفوض',
        ];

        return $statuses[$this->status] ?? $this->status;
    }

    public function getPriorityColor()
    {
        $colors = [
            'low' => 'info',
            'medium' => 'warning',
            'high' => 'danger',
            'critical' => 'danger',
        ];

        return $colors[$this->priority] ?? 'secondary';
    }

    public function getStatusColor()
    {
        $colors = [
            'active' => 'danger',
            'acknowledged' => 'warning',
            'resolved' => 'success',
            'dismissed' => 'secondary',
        ];

        return $colors[$this->status] ?? 'secondary';
    }

    public function getAlertIcon()
    {
        $icons = [
            'low_stock' => 'fas fa-exclamation-triangle',
            'out_of_stock' => 'fas fa-times-circle',
            'expiring_soon' => 'fas fa-clock',
            'expired' => 'fas fa-ban',
            'damaged' => 'fas fa-broken-heart',
            'overstock' => 'fas fa-arrow-up',
            'movement_anomaly' => 'fas fa-question-circle',
        ];

        return $icons[$this->alert_type] ?? 'fas fa-bell';
    }

    public function isActive()
    {
        return $this->status === 'active';
    }

    public function isResolved()
    {
        return $this->status === 'resolved';
    }

    public function getTimeSinceTriggered()
    {
        return $this->triggered_at->diffForHumans();
    }
}
