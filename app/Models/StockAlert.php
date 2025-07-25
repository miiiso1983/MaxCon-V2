<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property \Carbon\Carbon $expiry_date
 * @property \Carbon\Carbon $triggered_at
 * @property \Carbon\Carbon $acknowledged_at
 */
class StockAlert extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'product_id',
        'warehouse_id',
        'alert_type',
        'current_quantity',
        'threshold_quantity',
        'severity',
        'message',
        'status',
        'expiry_date',
        'days_to_expiry',
        'batch_number',
        'location_id',
        'triggered_at',
        'acknowledged_by',
        'acknowledged_at',
        'resolved_by',
        'resolved_at',
        'resolution_notes',
        'auto_generated',
        'notification_sent',
        'notification_sent_at',
    ];

    protected $casts = [
        'current_quantity' => 'decimal:3',
        'threshold_quantity' => 'decimal:3',
        'expiry_date' => 'datetime',
        'triggered_at' => 'datetime',
        'acknowledged_at' => 'datetime',
        'resolved_at' => 'datetime',
        'notification_sent_at' => 'datetime',
        'auto_generated' => 'boolean',
        'notification_sent' => 'boolean',
    ];

    // Relationships
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(WarehouseLocation::class, 'location_id');
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

    public function scopeByType($query, $type)
    {
        return $query->where('alert_type', $type);
    }

    public function scopeBySeverity($query, $severity)
    {
        return $query->where('severity', $severity);
    }

    public function scopeUnacknowledged($query)
    {
        return $query->whereNull('acknowledged_at');
    }

    public function scopeUnresolved($query)
    {
        return $query->whereNull('resolved_at');
    }

    public function scopeCritical($query)
    {
        return $query->where('severity', 'critical');
    }

    public function scopeUrgent($query)
    {
        return $query->where('severity', 'urgent');
    }

    // Methods
    public function getAlertTypeLabel()
    {
        $types = [
            'low_stock' => 'مخزون منخفض',
            'out_of_stock' => 'نفاد المخزون',
            'overstock' => 'مخزون زائد',
            'expiry_warning' => 'تحذير انتهاء صلاحية',
            'expired' => 'منتهي الصلاحية',
            'damaged' => 'تالف',
        ];

        return $types[$this->alert_type] ?? $this->alert_type;
    }

    public function getSeverityLabel()
    {
        $severities = [
            'info' => 'معلومات',
            'warning' => 'تحذير',
            'critical' => 'حرج',
            'urgent' => 'عاجل',
        ];

        return $severities[$this->severity] ?? $this->severity;
    }

    public function getSeverityColor()
    {
        $colors = [
            'info' => 'info',
            'warning' => 'warning',
            'critical' => 'danger',
            'urgent' => 'dark',
        ];

        return $colors[$this->severity] ?? 'secondary';
    }

    public function getStatusLabel()
    {
        $statuses = [
            'active' => 'نشط',
            'acknowledged' => 'تم الإقرار',
            'resolved' => 'تم الحل',
            'dismissed' => 'تم التجاهل',
        ];

        return $statuses[$this->status] ?? $this->status;
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

    public function isActive()
    {
        return $this->status === 'active';
    }

    public function isAcknowledged()
    {
        return $this->status === 'acknowledged';
    }

    public function isResolved()
    {
        return $this->status === 'resolved';
    }

    public function isDismissed()
    {
        return $this->status === 'dismissed';
    }

    public function acknowledge($userId = null, $notes = null)
    {
        $this->status = 'acknowledged';
        $this->acknowledged_by = $userId;
        $this->acknowledged_at = now();
        if ($notes) {
            $this->resolution_notes = $notes;
        }
        $this->save();
    }

    public function resolve($userId = null, $notes = null)
    {
        $this->status = 'resolved';
        $this->resolved_by = $userId;
        $this->resolved_at = now();
        if ($notes) {
            $this->resolution_notes = $notes;
        }
        $this->save();
    }

    public function dismiss($userId = null, $notes = null)
    {
        $this->status = 'dismissed';
        $this->resolved_by = $userId;
        $this->resolved_at = now();
        if ($notes) {
            $this->resolution_notes = $notes;
        }
        $this->save();
    }

    public function getTimeSinceTriggered()
    {
        return $this->created_at ? $this->triggered_at->diffForHumans() : '';
    }

    public function getDaysToExpiry()
    {
        if ($this->expiry_date) {
            return now()->diffInDays($this->expiry_date, false);
        }
        return null;
    }

    public function isExpired()
    {
        return $this->expiry_date && $this->expiry_date->isPast();
    }

    public function isExpiringToday()
    {
        return $this->expiry_date && $this->expiry_date->isToday();
    }

    public function isExpiringThisWeek()
    {
        return $this->expiry_date && $this->expiry_date->isBetween(now(), now()->addWeek());
    }

    public function markNotificationSent()
    {
        $this->notification_sent = true;
        $this->notification_sent_at = now();
        $this->save();
    }

    // Static methods for creating alerts
    public static function createLowStockAlert($product, $warehouse, $currentQuantity, $threshold)
    {
        return static::create([
            'tenant_id' => $product->tenant_id,
            'product_id' => $product->id,
            'warehouse_id' => $warehouse->id,
            'alert_type' => 'low_stock',
            'current_quantity' => $currentQuantity,
            'threshold_quantity' => $threshold,
            'severity' => $currentQuantity <= 0 ? 'critical' : 'warning',
            'message' => "المنتج {$product->name} في المستودع {$warehouse->name} وصل إلى الحد الأدنى للمخزون",
            'status' => 'active',
            'triggered_at' => now(),
            'auto_generated' => true,
        ]);
    }

    public static function createExpiryAlert($product, $warehouse, $expiryDate, $batchNumber = null)
    {
        $daysToExpiry = now()->diffInDays($expiryDate, false);
        $severity = $daysToExpiry <= 0 ? 'critical' : ($daysToExpiry <= 7 ? 'urgent' : 'warning');
        
        return static::create([
            'tenant_id' => $product->tenant_id,
            'product_id' => $product->id,
            'warehouse_id' => $warehouse->id,
            'alert_type' => $daysToExpiry <= 0 ? 'expired' : 'expiry_warning',
            'severity' => $severity,
            'message' => $daysToExpiry <= 0 
                ? "المنتج {$product->name} منتهي الصلاحية"
                : "المنتج {$product->name} سينتهي خلال {$daysToExpiry} أيام",
            'status' => 'active',
            'expiry_date' => $expiryDate,
            'days_to_expiry' => $daysToExpiry,
            'batch_number' => $batchNumber,
            'triggered_at' => now(),
            'auto_generated' => true,
        ]);
    }
}
