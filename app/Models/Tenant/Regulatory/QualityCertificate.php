<?php

namespace App\Models\Tenant\Regulatory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasTenant;

class QualityCertificate extends Model
{
    use HasFactory, SoftDeletes, HasTenant;

    protected $fillable = [
        'tenant_id',
        'product_id',
        'certificate_number',
        'certificate_type',
        'issuing_authority',
        'issue_date',
        'expiry_date',
        'validity_period',
        'batch_number',
        'manufacturing_date',
        'expiry_date_product',
        'quality_parameters',
        'test_results',
        'specifications_met',
        'deviations',
        'approved_by',
        'status',
        'renewal_required',
        'renewal_date',
        'storage_conditions',
        'handling_instructions',
        'distribution_restrictions',
        'recall_information',
        'certificate_file',
        'supporting_documents',
        'notes'
    ];

    protected $casts = [
        'issue_date' => 'date',
        'expiry_date' => 'date',
        'manufacturing_date' => 'date',
        'expiry_date_product' => 'date',
        'renewal_date' => 'date',
        'quality_parameters' => 'array',
        'test_results' => 'array',
        'specifications_met' => 'array',
        'deviations' => 'array',
        'storage_conditions' => 'array',
        'handling_instructions' => 'array',
        'distribution_restrictions' => 'array',
        'recall_information' => 'array',
        'supporting_documents' => 'array',
        'renewal_required' => 'boolean'
    ];

    // Certificate Types
    const CERTIFICATE_TYPES = [
        'coa' => 'شهادة تحليل',
        'gmp' => 'شهادة ممارسات التصنيع الجيدة',
        'iso' => 'شهادة ISO',
        'halal' => 'شهادة حلال',
        'organic' => 'شهادة عضوي',
        'stability' => 'شهادة ثبات',
        'bioequivalence' => 'شهادة تكافؤ حيوي',
        'sterility' => 'شهادة عقامة',
        'batch_release' => 'شهادة إطلاق دفعة',
        'import' => 'شهادة استيراد'
    ];

    // Status Types
    const STATUS_TYPES = [
        'valid' => 'صالح',
        'expired' => 'منتهي الصلاحية',
        'suspended' => 'معلق',
        'revoked' => 'ملغي',
        'pending_renewal' => 'قيد التجديد',
        'under_review' => 'قيد المراجعة'
    ];

    /**
     * Get certificate type in Arabic
     */
    public function getCertificateTypeNameAttribute()
    {
        return self::CERTIFICATE_TYPES[$this->certificate_type] ?? $this->certificate_type;
    }

    /**
     * Get status in Arabic
     */
    public function getStatusNameAttribute()
    {
        return self::STATUS_TYPES[$this->status] ?? $this->status;
    }

    /**
     * Check if certificate is expired
     */
    public function getIsExpiredAttribute()
    {
        return $this->expiry_date && $this->expiry_date->isPast();
    }

    /**
     * Check if certificate expires soon
     */
    public function getExpiresSoonAttribute()
    {
        return $this->expiry_date && 
               $this->expiry_date->isFuture() && 
               $this->expiry_date->diffInDays(now()) <= 30;
    }

    /**
     * Get days until expiry
     */
    public function getDaysUntilExpiryAttribute()
    {
        if (!$this->expiry_date) return null;
        
        return $this->expiry_date->diffInDays(now(), false);
    }

    /**
     * Check if renewal is due
     */
    public function getRenewalDueAttribute()
    {
        return $this->renewal_required && $this->renewal_date && 
               $this->renewal_date->isPast();
    }

    /**
     * Check if product is expired
     */
    public function getProductExpiredAttribute()
    {
        return $this->expiry_date_product && $this->expiry_date_product->isPast();
    }

    /**
     * Scope for valid certificates
     */
    public function scopeValid($query)
    {
        return $query->where('status', 'valid')
                    ->where('expiry_date', '>', now());
    }

    /**
     * Scope for expired certificates
     */
    public function scopeExpired($query)
    {
        return $query->where('expiry_date', '<', now())
                    ->orWhere('status', 'expired');
    }

    /**
     * Scope for expiring soon
     */
    public function scopeExpiringSoon($query, $days = 30)
    {
        return $query->where('expiry_date', '>', now())
                    ->where('expiry_date', '<=', now()->addDays($days))
                    ->where('status', 'valid');
    }

    /**
     * Scope for renewal due
     */
    public function scopeRenewalDue($query)
    {
        return $query->where('renewal_required', true)
                    ->where('renewal_date', '<=', now())
                    ->where('status', 'valid');
    }

    /**
     * Get related product
     */
    public function product()
    {
        return $this->belongsTo(ProductRegistration::class);
    }
}
