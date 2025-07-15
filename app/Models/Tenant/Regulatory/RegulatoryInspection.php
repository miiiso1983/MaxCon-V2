<?php

namespace App\Models\Tenant\Regulatory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasTenant;

class RegulatoryInspection extends Model
{
    use HasFactory, SoftDeletes, HasTenant;

    protected $fillable = [
        'tenant_id',
        'company_id',
        'inspection_number',
        'inspection_type',
        'regulatory_authority',
        'inspector_name',
        'inspector_credentials',
        'scheduled_date',
        'actual_date',
        'completion_date',
        'duration_hours',
        'inspection_scope',
        'areas_inspected',
        'findings',
        'observations',
        'non_conformities',
        'critical_findings',
        'major_findings',
        'minor_findings',
        'recommendations',
        'corrective_actions_required',
        'corrective_actions_taken',
        'follow_up_required',
        'follow_up_date',
        'status',
        'overall_rating',
        'compliance_score',
        'certificate_issued',
        'certificate_number',
        'certificate_validity',
        'next_inspection_date',
        'inspection_report',
        'attachments',
        'notes'
    ];

    protected $casts = [
        'scheduled_date' => 'datetime',
        'actual_date' => 'datetime',
        'completion_date' => 'datetime',
        'follow_up_date' => 'date',
        'certificate_validity' => 'date',
        'next_inspection_date' => 'date',
        'areas_inspected' => 'array',
        'findings' => 'array',
        'observations' => 'array',
        'non_conformities' => 'array',
        'critical_findings' => 'array',
        'major_findings' => 'array',
        'minor_findings' => 'array',
        'recommendations' => 'array',
        'corrective_actions_required' => 'array',
        'corrective_actions_taken' => 'array',
        'attachments' => 'array',
        'certificate_issued' => 'boolean',
        'follow_up_required' => 'boolean'
    ];

    // Inspection Types
    const INSPECTION_TYPES = [
        'routine' => 'تفتيش روتيني',
        'pre_approval' => 'تفتيش ما قبل الموافقة',
        'post_market' => 'تفتيش ما بعد التسويق',
        'complaint_based' => 'تفتيش بناء على شكوى',
        'follow_up' => 'تفتيش متابعة',
        'surveillance' => 'تفتيش مراقبة',
        'special' => 'تفتيش خاص',
        'gmp' => 'تفتيش ممارسات التصنيع الجيدة',
        'gcp' => 'تفتيش ممارسات الأبحاث السريرية',
        'gdp' => 'تفتيش ممارسات التوزيع الجيدة'
    ];

    // Status Types
    const STATUS_TYPES = [
        'scheduled' => 'مجدول',
        'in_progress' => 'قيد التنفيذ',
        'completed' => 'مكتمل',
        'report_pending' => 'تقرير معلق',
        'closed' => 'مغلق',
        'follow_up_required' => 'متابعة مطلوبة',
        'cancelled' => 'ملغي',
        'postponed' => 'مؤجل'
    ];

    // Overall Ratings
    const RATINGS = [
        'excellent' => 'ممتاز',
        'good' => 'جيد',
        'satisfactory' => 'مرضي',
        'needs_improvement' => 'يحتاج تحسين',
        'unsatisfactory' => 'غير مرضي',
        'critical' => 'حرج'
    ];

    /**
     * Get inspection type in Arabic
     */
    public function getInspectionTypeNameAttribute()
    {
        return self::INSPECTION_TYPES[$this->inspection_type] ?? $this->inspection_type;
    }

    /**
     * Get status in Arabic
     */
    public function getStatusNameAttribute()
    {
        return self::STATUS_TYPES[$this->status] ?? $this->status;
    }

    /**
     * Get rating in Arabic
     */
    public function getOverallRatingNameAttribute()
    {
        return self::RATINGS[$this->overall_rating] ?? $this->overall_rating;
    }

    /**
     * Check if inspection is overdue
     */
    public function getIsOverdueAttribute()
    {
        return $this->scheduled_date && $this->scheduled_date->isPast() && 
               !in_array($this->status, ['completed', 'closed', 'cancelled']);
    }

    /**
     * Check if follow-up is overdue
     */
    public function getFollowUpOverdueAttribute()
    {
        return $this->follow_up_required && $this->follow_up_date && 
               $this->follow_up_date->isPast() && $this->status !== 'closed';
    }

    /**
     * Get total findings count
     */
    public function getTotalFindingsAttribute()
    {
        return count($this->critical_findings ?? []) + 
               count($this->major_findings ?? []) + 
               count($this->minor_findings ?? []);
    }

    /**
     * Get inspection duration
     */
    public function getInspectionDurationAttribute()
    {
        if (!$this->actual_date || !$this->completion_date) return null;
        
        return $this->actual_date->diffInHours($this->completion_date);
    }

    /**
     * Check if certificate is valid
     */
    public function getCertificateValidAttribute()
    {
        return $this->certificate_issued && $this->certificate_validity && 
               $this->certificate_validity->isFuture();
    }

    /**
     * Scope for upcoming inspections
     */
    public function scopeUpcoming($query, $days = 30)
    {
        return $query->where('scheduled_date', '>', now())
                    ->where('scheduled_date', '<=', now()->addDays($days))
                    ->where('status', 'scheduled');
    }

    /**
     * Scope for overdue inspections
     */
    public function scopeOverdue($query)
    {
        return $query->where('scheduled_date', '<', now())
                    ->whereNotIn('status', ['completed', 'closed', 'cancelled']);
    }

    /**
     * Scope for completed inspections
     */
    public function scopeCompleted($query)
    {
        return $query->whereIn('status', ['completed', 'closed']);
    }

    /**
     * Scope for follow-up required
     */
    public function scopeFollowUpRequired($query)
    {
        return $query->where('follow_up_required', true)
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
     * Get related regulatory reports
     */
    public function regulatoryReports()
    {
        return $this->hasMany(RegulatoryReport::class, 'inspection_id');
    }
}
