<?php

namespace App\Models\Tenant\Regulatory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;


class Inspection extends Model
{
    use HasFactory;

    protected $table = 'inspections';

    protected $fillable = [
        'tenant_id',
        'inspection_number',
        'inspection_title',
        'inspection_type',
        'inspector_name',
        'inspection_authority',
        'scheduled_date',
        'completion_date',
        'inspection_status',
        'facility_name',
        'facility_address',
        'scope_of_inspection',
        'findings',
        'recommendations',
        'compliance_rating',
        'follow_up_required',
        'follow_up_date',
        'notes'
    ];

    protected $casts = [
        'scheduled_date' => 'date',
        'completion_date' => 'date',
        'follow_up_date' => 'date',
        'follow_up_required' => 'boolean'
    ];

    /**
     * Get the inspection type label
     */
    public function getInspectionTypeLabel()
    {
        $types = [
            'routine' => 'روتيني',
            'complaint' => 'شكوى',
            'follow_up' => 'متابعة',
            'pre_approval' => 'ما قبل الموافقة',
            'post_market' => 'ما بعد التسويق'
        ];

        return $types[$this->inspection_type] ?? $this->inspection_type;
    }

    /**
     * Get the inspection status label
     */
    public function getInspectionStatusLabel()
    {
        $statuses = [
            'scheduled' => 'مجدول',
            'in_progress' => 'قيد التنفيذ',
            'completed' => 'مكتمل',
            'cancelled' => 'ملغي',
            'postponed' => 'مؤجل'
        ];

        return $statuses[$this->inspection_status] ?? $this->inspection_status;
    }

    /**
     * Get the compliance rating label
     */
    public function getComplianceRatingLabel()
    {
        $ratings = [
            'excellent' => 'ممتاز',
            'good' => 'جيد',
            'satisfactory' => 'مرضي',
            'needs_improvement' => 'يحتاج تحسين',
            'non_compliant' => 'غير ملتزم'
        ];

        return $ratings[$this->compliance_rating] ?? $this->compliance_rating;
    }

    /**
     * Get the inspection status color
     */
    public function getInspectionStatusColor()
    {
        $colors = [
            'scheduled' => '#ed8936',
            'in_progress' => '#4299e1',
            'completed' => '#48bb78',
            'cancelled' => '#718096',
            'postponed' => '#f56565'
        ];

        return $colors[$this->inspection_status] ?? '#718096';
    }

    /**
     * Get the compliance rating color
     */
    public function getComplianceRatingColor()
    {
        $colors = [
            'excellent' => '#48bb78',
            'good' => '#4299e1',
            'satisfactory' => '#ed8936',
            'needs_improvement' => '#f56565',
            'non_compliant' => '#e53e3e'
        ];

        return $colors[$this->compliance_rating] ?? '#718096';
    }

    /**
     * Check if inspection is overdue
     */
    public function isOverdue()
    {
        if (!$this->scheduled_date || $this->inspection_status === 'completed') {
            return false;
        }

        return $this->scheduled_date->isPast();
    }

    /**
     * Check if inspection is due soon (within 7 days)
     */
    public function isDueSoon()
    {
        if (!$this->scheduled_date || $this->inspection_status === 'completed') {
            return false;
        }

        return $this->scheduled_date->diffInDays(now()) <= 7 && $this->scheduled_date->isFuture();
    }

    /**
     * Scope for scheduled inspections
     */
    public function scopeScheduled($query)
    {
        return $query->where('inspection_status', 'scheduled');
    }

    /**
     * Scope for completed inspections
     */
    public function scopeCompleted($query)
    {
        return $query->where('inspection_status', 'completed');
    }

    /**
     * Scope for overdue inspections
     */
    public function scopeOverdue($query)
    {
        return $query->where('scheduled_date', '<', now())
                    ->whereNotIn('inspection_status', ['completed', 'cancelled']);
    }
}
