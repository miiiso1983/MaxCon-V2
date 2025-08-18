<?php

namespace App\Models\Tenant\Regulatory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasTenant;

class RegulatoryDocument extends Model
{
    use HasFactory, SoftDeletes, HasTenant;

    protected $fillable = [
        'tenant_id',
        'company_id',
        'product_id',
        'document_number',
        'document_type',
        'document_category',
        'title',
        'document_title',
        'description',
        'version',
        'language',
        'regulatory_authority',
        'issuing_authority',
        'issue_date',
        'submission_date',
        'approval_date',
        'effective_date',
        'expiry_date',
        'review_date',
        'next_review_date',
        'status',
        'confidentiality_level',
        'access_level',
        'author',
        'reviewer',
        'approver',
        'file_path',
        'file_name',
        'file_size',
        'file_type',
        'checksum',
        'digital_signature',
        'retention_period',
        'disposal_date',
        'archive_location',
        'related_documents',
        'keywords',
        'tags',
        'notes'
    ];

    protected $casts = [
        'issue_date' => 'date',
        'submission_date' => 'date',
        'approval_date' => 'date',
        'effective_date' => 'date',
        'expiry_date' => 'date',
        'review_date' => 'date',
        'next_review_date' => 'date',
        'disposal_date' => 'date',
        'related_documents' => 'array',
        'keywords' => 'array',
        'tags' => 'array'
    ];

    // Document Types
    const DOCUMENT_TYPES = [
        'license' => 'ترخيص',
        'certificate' => 'شهادة',
        'permit' => 'تصريح',
        'registration' => 'تسجيل',
        'approval' => 'موافقة',
        'sop' => 'إجراء تشغيل معياري',
        'policy' => 'سياسة',
        'guideline' => 'دليل إرشادي',
        'specification' => 'مواصفة',
        'protocol' => 'بروتوكول',
        'report' => 'تقرير',
        'correspondence' => 'مراسلة',
        'submission' => 'تقديم',
        'amendment' => 'تعديل',
        'withdrawal' => 'سحب'
    ];

    // Document Categories
    const DOCUMENT_CATEGORIES = [
        'regulatory_submission' => 'تقديم تنظيمي',
        'quality_documentation' => 'وثائق الجودة',
        'manufacturing' => 'تصنيع',
        'clinical' => 'سريري',
        'safety' => 'سلامة',
        'labeling' => 'وسم',
        'advertising' => 'إعلان',
        'import_export' => 'استيراد وتصدير',
        'inspection' => 'تفتيش',
        'compliance' => 'امتثال',
        'legal' => 'قانوني',
        'administrative' => 'إداري'
    ];

    // Status Types
    const STATUS_TYPES = [
        'draft' => 'مسودة',
        'under_review' => 'قيد المراجعة',
        'approved' => 'معتمد',
        'effective' => 'نافذ',
        'expired' => 'منتهي الصلاحية',
        'superseded' => 'محل بوثيقة أخرى',
        'withdrawn' => 'مسحوب',
        'archived' => 'مؤرشف'
    ];

    // Confidentiality Levels
    const CONFIDENTIALITY_LEVELS = [
        'public' => 'عام',
        'internal' => 'داخلي',
        'confidential' => 'سري',
        'restricted' => 'مقيد',
        'classified' => 'مصنف'
    ];

    // Access Levels
    const ACCESS_LEVELS = [
        'all_users' => 'جميع المستخدمين',
        'authorized_only' => 'المخولين فقط',
        'management_only' => 'الإدارة فقط',
        'regulatory_team' => 'فريق الشؤون التنظيمية',
        'quality_team' => 'فريق الجودة'
    ];

    /**
     * Get document type in Arabic
     */
    public function getDocumentTypeNameAttribute()
    {
        return self::DOCUMENT_TYPES[$this->document_type] ?? $this->document_type;
    }

    /**
     * Get document category in Arabic
     */
    public function getDocumentCategoryNameAttribute()
    {
        return self::DOCUMENT_CATEGORIES[$this->document_category] ?? $this->document_category;
    }

    /**
     * Get status in Arabic
     */
    public function getStatusNameAttribute()
    {
        return self::STATUS_TYPES[$this->status] ?? $this->status;
    }

    /**
     * Get confidentiality level in Arabic
     */
    public function getConfidentialityLevelNameAttribute()
    {
        return self::CONFIDENTIALITY_LEVELS[$this->confidentiality_level] ?? $this->confidentiality_level;
    }

    /**
     * Get access level in Arabic
     */
    public function getAccessLevelNameAttribute()
    {
        return self::ACCESS_LEVELS[$this->access_level] ?? $this->access_level;
    }

    /**
     * Check if document is expired
     */
    public function getIsExpiredAttribute()
    {
        return $this->expiry_date && $this->expiry_date->isPast();
    }

    /**
     * Check if document expires soon
     */
    public function getExpiresSoonAttribute()
    {
        return $this->expiry_date && 
               $this->expiry_date->isFuture() && 
               $this->expiry_date->diffInDays(now()) <= 30;
    }

    /**
     * Check if review is due
     */
    public function getReviewDueAttribute()
    {
        return $this->next_review_date && $this->next_review_date->isPast();
    }

    /**
     * Check if disposal is due
     */
    public function getDisposalDueAttribute()
    {
        return $this->disposal_date && $this->disposal_date->isPast();
    }

    /**
     * Get file size in human readable format
     */
    public function getFileSizeHumanAttribute()
    {
        if (!$this->file_size) return null;
        
        $units = ['B', 'KB', 'MB', 'GB'];
        $size = $this->file_size;
        $unit = 0;
        
        while ($size >= 1024 && $unit < count($units) - 1) {
            $size /= 1024;
            $unit++;
        }
        
        return round($size, 2) . ' ' . $units[$unit];
    }

    /**
     * Scope for active documents
     */
    public function scopeActive($query)
    {
        return $query->whereIn('status', ['approved', 'effective']);
    }

    /**
     * Scope for expired documents
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
                    ->whereIn('status', ['approved', 'effective']);
    }

    /**
     * Scope for review due
     */
    public function scopeReviewDue($query)
    {
        return $query->where('next_review_date', '<=', now())
                    ->whereNotNull('next_review_date')
                    ->whereIn('status', ['approved', 'effective']);
    }

    /**
     * Scope for disposal due
     */
    public function scopeDisposalDue($query)
    {
        return $query->where('disposal_date', '<=', now())
                    ->whereNotNull('disposal_date');
    }

    /**
     * Get related company
     */
    public function company()
    {
        return $this->belongsTo(CompanyRegistration::class);
    }

    /**
     * Get related product
     */
    public function product()
    {
        return $this->belongsTo(ProductRegistration::class);
    }
}
