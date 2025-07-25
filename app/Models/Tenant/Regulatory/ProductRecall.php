<?php

namespace App\Models\Tenant\Regulatory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use App\Traits\HasTenant;

class ProductRecall extends Model
{
    use HasFactory, SoftDeletes, HasTenant;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'tenant_id',
        'product_id',
        'product_name',
        'recall_number',
        'recall_type',
        'recall_class',
        'reason',
        'description',
        'affected_batches',
        'quantity_affected',
        'distribution_level',
        'countries_affected',
        'health_hazard',
        'risk_assessment',
        'initiated_date',
        'notification_date',
        'completion_date',
        'status',
        'regulatory_authority',
        'authority_notification',
        'public_notification',
        'media_release',
        'customer_notification',
        'healthcare_notification',
        'recall_strategy',
        'effectiveness_checks',
        'quantity_recovered',
        'recovery_percentage',
        'disposal_method',
        'root_cause_analysis',
        'corrective_actions',
        'preventive_actions',
        'follow_up_required',
        'follow_up_date',
        'closure_date',
        'lessons_learned',
        'attachments',
        'notes'
    ];

    protected $casts = [
        'initiated_date' => 'datetime',
        'notification_date' => 'datetime',
        'completion_date' => 'datetime',
        'follow_up_date' => 'datetime',
        'closure_date' => 'datetime',
        'affected_batches' => 'array',
        'countries_affected' => 'array',
        'recall_strategy' => 'array',
        'effectiveness_checks' => 'array',
        'corrective_actions' => 'array',
        'preventive_actions' => 'array',
        'lessons_learned' => 'array',
        'attachments' => 'array',
        'authority_notification' => 'boolean',
        'public_notification' => 'boolean',
        'media_release' => 'boolean',
        'customer_notification' => 'boolean',
        'healthcare_notification' => 'boolean',
        'follow_up_required' => 'boolean'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }

    // Recall Types
    const RECALL_TYPES = [
        'voluntary' => 'طوعي',
        'mandatory' => 'إجباري',
        'fda_requested' => 'بطلب من الهيئة',
        'market_withdrawal' => 'سحب من السوق',
        'stock_recovery' => 'استرداد المخزون'
    ];

    // Recall Classes
    const RECALL_CLASSES = [
        'class_i' => 'الفئة الأولى - خطر صحي جدي',
        'class_ii' => 'الفئة الثانية - خطر صحي محتمل',
        'class_iii' => 'الفئة الثالثة - خطر صحي منخفض',
        'market_withdrawal' => 'سحب من السوق'
    ];

    // Status Types
    const STATUS_TYPES = [
        'initiated' => 'بدء',
        'in_progress' => 'قيد التنفيذ',
        'ongoing' => 'مستمر',
        'completed' => 'مكتمل',
        'closed' => 'مغلق',
        'terminated' => 'منتهي',
        'on_hold' => 'معلق'
    ];

    // Distribution Levels
    const DISTRIBUTION_LEVELS = [
        'consumer' => 'مستهلك',
        'retail' => 'تجزئة',
        'wholesale' => 'جملة',
        'hospital' => 'مستشفى',
        'pharmacy' => 'صيدلية',
        'clinic' => 'عيادة',
        'distributor' => 'موزع'
    ];

    /**
     * Get recall type in Arabic
     */
    public function getRecallTypeNameAttribute()
    {
        return self::RECALL_TYPES[$this->recall_type] ?? $this->recall_type;
    }

    /**
     * Get recall class in Arabic
     */
    public function getRecallClassNameAttribute()
    {
        return self::RECALL_CLASSES[$this->recall_class] ?? $this->recall_class;
    }

    /**
     * Get status in Arabic
     */
    public function getStatusNameAttribute()
    {
        return self::STATUS_TYPES[$this->status] ?? $this->status;
    }

    /**
     * Get distribution level in Arabic
     */
    public function getDistributionLevelNameAttribute()
    {
        return self::DISTRIBUTION_LEVELS[$this->distribution_level] ?? $this->distribution_level;
    }

    /**
     * Calculate recovery percentage
     */
    public function getRecoveryPercentageCalculatedAttribute()
    {
        if (!$this->quantity_affected || $this->quantity_affected == 0) return 0;
        
        return round(($this->quantity_recovered / $this->quantity_affected) * 100, 2);
    }

    /**
     * Check if recall is overdue
     */
    public function getIsOverdueAttribute()
    {
        return $this->completion_date && $this->completion_date->isPast() &&
               !in_array($this->status, ['completed', 'closed', 'terminated']);
    }

    /**
     * Check if follow-up is due
     */
    public function getFollowUpDueAttribute()
    {
        return $this->follow_up_required && $this->follow_up_date &&
               $this->follow_up_date->isPast() && $this->status !== 'closed';
    }

    /**
     * Get recall duration
     */
    public function getRecallDurationAttribute()
    {
        if (!$this->initiated_date) return null;
        
        $endDate = $this->closure_date ?? $this->completion_date ?? now();
        return $this->initiated_date->diffInDays($endDate);
    }

    /**
     * Check if high priority
     */
    public function getHighPriorityAttribute()
    {
        return in_array($this->recall_class, ['class_i', 'class_ii']);
    }

    /**
     * Scope for active recalls
     */
    public function scopeActive($query)
    {
        return $query->whereIn('status', ['initiated', 'in_progress', 'ongoing']);
    }

    /**
     * Scope for completed recalls
     */
    public function scopeCompleted($query)
    {
        return $query->whereIn('status', ['completed', 'closed', 'terminated']);
    }

    /**
     * Scope for high priority recalls
     */
    public function scopeHighPriority($query)
    {
        return $query->whereIn('recall_class', ['class_i', 'class_ii']);
    }

    /**
     * Scope for overdue recalls
     */
    public function scopeOverdue($query)
    {
        return $query->where('completion_date', '<', now())
                    ->whereNotIn('status', ['completed', 'closed', 'terminated']);
    }

    /**
     * Get related product
     */
    public function product()
    {
        return $this->belongsTo(ProductRegistration::class);
    }

    /**
     * Get related regulatory reports
     */
    public function regulatoryReports()
    {
        return $this->hasMany(RegulatoryReport::class, 'recall_id');
    }
}
