<?php

namespace App\Models\Tenant\Regulatory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasTenant;
use Illuminate\Support\Str;

class CompanyRegistration extends Model
{
    use HasFactory, SoftDeletes, HasTenant;

    /**
     * Indicates if the model's ID is auto-incrementing.
     */
    public $incrementing = false;

    /**
     * The data type of the auto-incrementing ID.
     */
    protected $keyType = 'string';

    protected $fillable = [
        'tenant_id',
        'company_name',
        'company_name_en',
        'registration_number',
        'license_number',
        'license_type',
        'regulatory_authority',
        'registration_date',
        'license_issue_date',
        'license_expiry_date',
        'status',
        'company_address',
        'contact_person',
        'contact_email',
        'contact_phone',
        'business_activities',
        'authorized_products',
        'compliance_status',
        'last_inspection_date',
        'next_inspection_date',
        'notes',
        'documents'
    ];

    protected $casts = [
        'registration_date' => 'datetime',
        'license_issue_date' => 'datetime',
        'license_expiry_date' => 'datetime',
        'last_inspection_date' => 'datetime',
        'next_inspection_date' => 'datetime',
        'business_activities' => 'array',
        'authorized_products' => 'array',
        'documents' => 'array'
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }

    // License Types
    const LICENSE_TYPES = [
        'manufacturing' => 'ترخيص تصنيع',
        'import' => 'ترخيص استيراد',
        'export' => 'ترخيص تصدير',
        'distribution' => 'ترخيص توزيع',
        'wholesale' => 'ترخيص جملة',
        'retail' => 'ترخيص تجزئة',
        'research' => 'ترخيص بحث وتطوير'
    ];

    // Status Types
    const STATUS_TYPES = [
        'active' => 'نشط',
        'suspended' => 'معلق',
        'expired' => 'منتهي الصلاحية',
        'under_review' => 'قيد المراجعة',
        'cancelled' => 'ملغي'
    ];

    // Compliance Status
    const COMPLIANCE_STATUS = [
        'compliant' => 'ملتزم',
        'non_compliant' => 'غير ملتزم',
        'under_investigation' => 'قيد التحقيق',
        'corrective_action' => 'إجراء تصحيحي مطلوب'
    ];

    /**
     * Get license type in Arabic
     */
    public function getLicenseTypeNameAttribute()
    {
        return self::LICENSE_TYPES[$this->license_type] ?? $this->license_type;
    }

    /**
     * Get status in Arabic
     */
    public function getStatusNameAttribute()
    {
        return self::STATUS_TYPES[$this->status] ?? $this->status;
    }

    /**
     * Get compliance status in Arabic
     */
    public function getComplianceStatusNameAttribute()
    {
        return self::COMPLIANCE_STATUS[$this->compliance_status] ?? $this->compliance_status;
    }

    /**
     * Check if license is expired
     */
    public function getIsExpiredAttribute()
    {
        return $this->license_expiry_date && $this->license_expiry_date->isPast();
    }

    /**
     * Check if license expires soon (within 30 days)
     */
    public function getExpiresSoonAttribute()
    {
        return $this->license_expiry_date && 
               $this->license_expiry_date->isFuture() && 
               $this->license_expiry_date->diffInDays(now()) <= 30;
    }

    /**
     * Get days until expiry
     */
    public function getDaysUntilExpiryAttribute()
    {
        if (!$this->license_expiry_date) return null;
        
        return $this->license_expiry_date->diffInDays(now(), false);
    }

    /**
     * Scope for active licenses
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for expired licenses
     */
    public function scopeExpired($query)
    {
        return $query->where('license_expiry_date', '<', now());
    }

    /**
     * Scope for expiring soon
     */
    public function scopeExpiringSoon($query, $days = 30)
    {
        return $query->where('license_expiry_date', '>', now())
                    ->where('license_expiry_date', '<=', now()->addDays($days));
    }

    /**
     * Get related inspections
     */
    public function inspections()
    {
        return $this->hasMany(RegulatoryInspection::class, 'company_id');
    }

    /**
     * Get related products
     */
    public function products()
    {
        return $this->hasMany(ProductRegistration::class, 'company_id');
    }

    /**
     * Get related documents
     */
    public function regulatoryDocuments()
    {
        return $this->hasMany(RegulatoryDocument::class, 'company_id');
    }
}
