<?php

namespace App\Models\Tenant\Regulatory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasTenant;

class ProductRegistration extends Model
{
    use HasFactory, SoftDeletes, HasTenant;

    protected $fillable = [
        'tenant_id',
        'company_id',
        'product_name',
        'product_name_en',
        'generic_name',
        'brand_name',
        'registration_number',
        'batch_number',
        'product_type',
        'therapeutic_class',
        'dosage_form',
        'strength',
        'pack_size',
        'manufacturer',
        'country_of_origin',
        'regulatory_authority',
        'registration_date',
        'approval_date',
        'expiry_date',
        'renewal_date',
        'status',
        'approval_conditions',
        'contraindications',
        'side_effects',
        'storage_conditions',
        'shelf_life',
        'quality_specifications',
        'clinical_trial_data',
        'marketing_authorization',
        'price_approval',
        'import_permit_required',
        'controlled_substance',
        'prescription_only',
        'notes',
        'documents'
    ];

    protected $casts = [
        'registration_date' => 'date',
        'approval_date' => 'date',
        'expiry_date' => 'date',
        'renewal_date' => 'date',
        'approval_conditions' => 'array',
        'contraindications' => 'array',
        'side_effects' => 'array',
        'quality_specifications' => 'array',
        'clinical_trial_data' => 'array',
        'documents' => 'array',
        'import_permit_required' => 'boolean',
        'controlled_substance' => 'boolean',
        'prescription_only' => 'boolean'
    ];

    // Product Types
    const PRODUCT_TYPES = [
        'pharmaceutical' => 'دواء',
        'vaccine' => 'لقاح',
        'medical_device' => 'جهاز طبي',
        'supplement' => 'مكمل غذائي',
        'cosmetic' => 'مستحضر تجميل',
        'herbal' => 'دواء عشبي',
        'biological' => 'منتج بيولوجي'
    ];

    // Status Types
    const STATUS_TYPES = [
        'registered' => 'مسجل',
        'pending' => 'قيد المراجعة',
        'approved' => 'معتمد',
        'rejected' => 'مرفوض',
        'suspended' => 'معلق',
        'withdrawn' => 'مسحوب',
        'expired' => 'منتهي الصلاحية'
    ];

    // Dosage Forms
    const DOSAGE_FORMS = [
        'tablet' => 'قرص',
        'capsule' => 'كبسولة',
        'syrup' => 'شراب',
        'injection' => 'حقنة',
        'cream' => 'كريم',
        'ointment' => 'مرهم',
        'drops' => 'قطرة',
        'inhaler' => 'بخاخ',
        'suppository' => 'تحميلة',
        'powder' => 'بودرة'
    ];

    /**
     * Get product type in Arabic
     */
    public function getProductTypeNameAttribute()
    {
        return self::PRODUCT_TYPES[$this->product_type] ?? $this->product_type;
    }

    /**
     * Get status in Arabic
     */
    public function getStatusNameAttribute()
    {
        return self::STATUS_TYPES[$this->status] ?? $this->status;
    }

    /**
     * Get dosage form in Arabic
     */
    public function getDosageFormNameAttribute()
    {
        return self::DOSAGE_FORMS[$this->dosage_form] ?? $this->dosage_form;
    }

    /**
     * Check if registration is expired
     */
    public function getIsExpiredAttribute()
    {
        return $this->expiry_date && $this->expiry_date->isPast();
    }

    /**
     * Check if registration expires soon
     */
    public function getExpiresSoonAttribute()
    {
        return $this->expiry_date && 
               $this->expiry_date->isFuture() && 
               $this->expiry_date->diffInDays(now()) <= 60;
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
        return $this->renewal_date && $this->renewal_date->isPast();
    }

    /**
     * Scope for active products
     */
    public function scopeActive($query)
    {
        return $query->whereIn('status', ['registered', 'approved']);
    }

    /**
     * Scope for expired products
     */
    public function scopeExpired($query)
    {
        return $query->where('expiry_date', '<', now());
    }

    /**
     * Scope for expiring soon
     */
    public function scopeExpiringSoon($query, $days = 60)
    {
        return $query->where('expiry_date', '>', now())
                    ->where('expiry_date', '<=', now()->addDays($days));
    }

    /**
     * Scope for controlled substances
     */
    public function scopeControlled($query)
    {
        return $query->where('controlled_substance', true);
    }

    /**
     * Scope for prescription only medicines
     */
    public function scopePrescriptionOnly($query)
    {
        return $query->where('prescription_only', true);
    }

    /**
     * Get company
     */
    public function company()
    {
        return $this->belongsTo(CompanyRegistration::class);
    }

    /**
     * Get related laboratory tests
     */
    public function laboratoryTests()
    {
        return $this->hasMany(LaboratoryTest::class, 'product_id');
    }

    /**
     * Get related quality certificates
     */
    public function qualityCertificates()
    {
        return $this->hasMany(QualityCertificate::class, 'product_id');
    }

    /**
     * Get related recalls
     */
    public function recalls()
    {
        return $this->hasMany(ProductRecall::class, 'product_id');
    }
}
