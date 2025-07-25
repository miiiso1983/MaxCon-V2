<?php

namespace App\Models\Tenant\Regulatory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use App\Traits\HasTenant;

class LaboratoryTest extends Model
{
    use HasFactory, SoftDeletes, HasTenant;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'tenant_id',
        'product_id',
        'test_number',
        'test_name',
        'product_name',
        'batch_number',
        'test_type',
        'test_category',
        'laboratory_name',
        'laboratory_accreditation',
        'sample_batch',
        'sample_date',
        'test_date',
        'completion_date',
        'expected_completion_date',
        'test_method',
        'test_parameters',
        'specifications',
        'results',
        'conclusion',
        'status',
        'technician_name',
        'supervisor_name',
        'approved_by',
        'certificate_number',
        'validity_period',
        'retest_date',
        'storage_conditions',
        'environmental_conditions',
        'equipment_used',
        'calibration_status',
        'deviation_notes',
        'corrective_actions',
        'attachments',
        'notes',
        'cost',
        'priority'
    ];

    protected $casts = [
        'sample_date' => 'date',
        'test_date' => 'date',
        'completion_date' => 'date',
        'expected_completion_date' => 'date',
        'retest_date' => 'date',
        'test_parameters' => 'array',
        'specifications' => 'array',
        'results' => 'array',
        'equipment_used' => 'array',
        'corrective_actions' => 'array',
        'attachments' => 'array'
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

    // Test Types
    const TEST_TYPES = [
        'quality_control' => 'مراقبة الجودة',
        'stability' => 'اختبار الثبات',
        'bioequivalence' => 'التكافؤ الحيوي',
        'dissolution' => 'اختبار الذوبان',
        'microbiological' => 'اختبار ميكروبيولوجي',
        'chemical' => 'اختبار كيميائي',
        'physical' => 'اختبار فيزيائي',
        'toxicological' => 'اختبار سمية',
        'sterility' => 'اختبار العقامة',
        'endotoxin' => 'اختبار السموم الداخلية'
    ];

    // Test Categories
    const TEST_CATEGORIES = [
        'raw_material' => 'مادة خام',
        'in_process' => 'أثناء التصنيع',
        'finished_product' => 'منتج نهائي',
        'stability_study' => 'دراسة الثبات',
        'method_validation' => 'التحقق من الطريقة',
        'reference_standard' => 'معيار مرجعي'
    ];

    // Status Types
    const STATUS_TYPES = [
        'scheduled' => 'مجدول',
        'in_progress' => 'قيد التنفيذ',
        'completed' => 'مكتمل',
        'passed' => 'نجح',
        'failed' => 'فشل',
        'retest_required' => 'إعادة اختبار مطلوبة',
        'cancelled' => 'ملغي',
        'on_hold' => 'معلق'
    ];

    /**
     * Get test type in Arabic
     */
    public function getTestTypeNameAttribute()
    {
        return self::TEST_TYPES[$this->test_type] ?? $this->test_type;
    }

    /**
     * Get test category in Arabic
     */
    public function getTestCategoryNameAttribute()
    {
        return self::TEST_CATEGORIES[$this->test_category] ?? $this->test_category;
    }

    /**
     * Get status in Arabic
     */
    public function getStatusNameAttribute()
    {
        return self::STATUS_TYPES[$this->status] ?? $this->status;
    }

    /**
     * Check if test is overdue
     */
    public function getIsOverdueAttribute()
    {
        return $this->test_date && $this->test_date->isPast() &&
               !in_array($this->status, ['completed', 'passed', 'failed', 'cancelled']);
    }

    /**
     * Check if retest is due
     */
    public function getRetestDueAttribute()
    {
        return $this->retest_date && $this->retest_date->isPast();
    }

    /**
     * Get test duration in days
     */
    public function getTestDurationAttribute()
    {
        if (!$this->test_date || !$this->completion_date) return null;
        
        return $this->test_date->diffInDays($this->completion_date);
    }

    /**
     * Check if test passed
     */
    public function getPassedAttribute()
    {
        return in_array($this->status, ['passed', 'completed']) && 
               $this->conclusion === 'passed';
    }

    /**
     * Scope for pending tests
     */
    public function scopePending($query)
    {
        return $query->whereIn('status', ['scheduled', 'in_progress']);
    }

    /**
     * Scope for completed tests
     */
    public function scopeCompleted($query)
    {
        return $query->whereIn('status', ['completed', 'passed', 'failed']);
    }

    /**
     * Scope for failed tests
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Scope for overdue tests
     */
    public function scopeOverdue($query)
    {
        return $query->where('test_date', '<', now())
                    ->whereNotIn('status', ['completed', 'passed', 'failed', 'cancelled']);
    }

    /**
     * Scope for retest due
     */
    public function scopeRetestDue($query)
    {
        return $query->where('retest_date', '<=', now())
                    ->whereNotNull('retest_date');
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
        return $this->hasMany(RegulatoryReport::class, 'test_id');
    }
}
