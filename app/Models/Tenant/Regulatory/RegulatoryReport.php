<?php

namespace App\Models\Tenant\Regulatory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use App\Traits\HasTenant;

class RegulatoryReport extends Model
{
    use HasFactory, SoftDeletes, HasTenant;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'tenant_id',
        'company_id',
        'inspection_id',
        'test_id',
        'recall_id',
        'report_number',
        'report_type',
        'report_category',
        'title',
        'description',
        'regulatory_authority',
        'submission_date',
        'due_date',
        'approval_date',
        'status',
        'priority',
        'reporting_period_start',
        'reporting_period_end',
        'data_sources',
        'methodology',
        'findings',
        'conclusions',
        'recommendations',
        'action_items',
        'follow_up_required',
        'follow_up_date',
        'prepared_by',
        'reviewed_by',
        'approved_by',
        'distribution_list',
        'confidentiality_level',
        'version',
        'revision_history',
        'supporting_documents',
        'attachments',
        'notes'
    ];

    protected $casts = [
        'submission_date' => 'date',
        'due_date' => 'date',
        'approval_date' => 'date',
        'reporting_period_start' => 'date',
        'reporting_period_end' => 'date',
        'follow_up_date' => 'date',
        'data_sources' => 'array',
        'findings' => 'array',
        'conclusions' => 'array',
        'recommendations' => 'array',
        'action_items' => 'array',
        'distribution_list' => 'array',
        'revision_history' => 'array',
        'supporting_documents' => 'array',
        'attachments' => 'array',
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

    // Report Types
    const REPORT_TYPES = [
        'inspection' => 'تقرير تفتيش',
        'laboratory' => 'تقرير مخبري',
        'adverse_event' => 'تقرير حدث ضار',
        'recall' => 'تقرير سحب منتج',
        'compliance' => 'تقرير امتثال',
        'periodic_safety' => 'تقرير سلامة دوري',
        'annual' => 'تقرير سنوي',
        'quarterly' => 'تقرير ربع سنوي',
        'monthly' => 'تقرير شهري',
        'incident' => 'تقرير حادثة',
        'deviation' => 'تقرير انحراف',
        'change_control' => 'تقرير مراقبة التغيير'
    ];

    // Report Categories
    const REPORT_CATEGORIES = [
        'regulatory_submission' => 'تقديم تنظيمي',
        'compliance_monitoring' => 'مراقبة الامتثال',
        'safety_surveillance' => 'مراقبة السلامة',
        'quality_assurance' => 'ضمان الجودة',
        'risk_management' => 'إدارة المخاطر',
        'post_market' => 'ما بعد التسويق',
        'clinical_trial' => 'تجربة سريرية',
        'manufacturing' => 'تصنيع',
        'distribution' => 'توزيع'
    ];

    // Status Types
    const STATUS_TYPES = [
        'draft' => 'مسودة',
        'under_review' => 'قيد المراجعة',
        'approved' => 'معتمد',
        'submitted' => 'مقدم',
        'accepted' => 'مقبول',
        'rejected' => 'مرفوض',
        'revision_required' => 'مراجعة مطلوبة',
        'closed' => 'مغلق'
    ];

    // Priority Levels
    const PRIORITY_LEVELS = [
        'low' => 'منخفض',
        'medium' => 'متوسط',
        'high' => 'عالي',
        'urgent' => 'عاجل',
        'critical' => 'حرج'
    ];

    // Confidentiality Levels
    const CONFIDENTIALITY_LEVELS = [
        'public' => 'عام',
        'internal' => 'داخلي',
        'confidential' => 'سري',
        'restricted' => 'مقيد',
        'classified' => 'مصنف'
    ];

    /**
     * Get report type in Arabic
     */
    public function getReportTypeNameAttribute()
    {
        return self::REPORT_TYPES[$this->getAttribute('report_type')] ?? $this->getAttribute('report_type');
    }

    /**
     * Get report category in Arabic
     */
    public function getReportCategoryNameAttribute()
    {
        return self::REPORT_CATEGORIES[$this->getAttribute('report_category')] ?? $this->getAttribute('report_category');
    }

    /**
     * Get status in Arabic
     */
    public function getStatusNameAttribute()
    {
        return self::STATUS_TYPES[$this->getAttribute('status')] ?? $this->getAttribute('status');
    }

    /**
     * Get priority in Arabic
     */
    public function getPriorityNameAttribute()
    {
        return self::PRIORITY_LEVELS[$this->getAttribute('priority')] ?? $this->getAttribute('priority');
    }

    /**
     * Get confidentiality level in Arabic
     */
    public function getConfidentialityLevelNameAttribute()
    {
        return self::CONFIDENTIALITY_LEVELS[$this->confidentiality_level] ?? $this->confidentiality_level;
    }

    /**
     * Check if report is overdue
     */
    public function getIsOverdueAttribute()
    {
        return $this->due_date && $this->due_date->isPast() && 
               !in_array($this->status, ['submitted', 'accepted', 'closed']);
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
     * Get days until due
     */
    public function getDaysUntilDueAttribute()
    {
        if (!$this->due_date) return null;
        
        return $this->due_date->diffInDays(now(), false);
    }

    /**
     * Check if high priority
     */
    public function getHighPriorityAttribute()
    {
        return in_array($this->priority, ['high', 'urgent', 'critical']);
    }

    /**
     * Scope for pending reports
     */
    public function scopePending($query)
    {
        return $query->whereIn('status', ['draft', 'under_review']);
    }

    /**
     * Scope for overdue reports
     */
    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now())
                    ->whereNotIn('status', ['submitted', 'accepted', 'closed']);
    }

    /**
     * Scope for high priority reports
     */
    public function scopeHighPriority($query)
    {
        return $query->whereIn('priority', ['high', 'urgent', 'critical']);
    }

    /**
     * Scope for follow-up due
     */
    public function scopeFollowUpDue($query)
    {
        return $query->where('follow_up_required', true)
                    ->where('follow_up_date', '<=', now())
                    ->where('status', '!=', 'closed');
    }

    /**
     * Get related company
     */
    public function company()
    {
        return $this->belongsTo(CompanyRegistration::class);
    }

    /**
     * Get related inspection
     */
    public function inspection()
    {
        return $this->belongsTo(RegulatoryInspection::class);
    }

    /**
     * Get related laboratory test
     */
    public function laboratoryTest()
    {
        return $this->belongsTo(LaboratoryTest::class, 'test_id');
    }

    /**
     * Get related product recall
     */
    public function productRecall()
    {
        return $this->belongsTo(ProductRecall::class, 'recall_id');
    }
}
