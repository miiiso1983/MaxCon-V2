<?php

namespace App\Models\Tenant\Regulatory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Certificate extends Model
{
    use HasFactory;

    protected $table = 'certificates';

    protected $fillable = [
        'tenant_id',
        'certificate_name',
        'certificate_type',
        'certificate_number',
        'issuing_authority',
        'issue_date',
        'expiry_date',
        'certificate_status',
        'product_name',
        'facility_name',
        'status',
        'scope_of_certification',
        'audit_date',
        'next_audit_date',
        'certification_body',
        'accreditation_number',
        'renewal_reminder_days',
        'notes'
    ];

    protected $casts = [
        'issue_date' => 'date',
        'expiry_date' => 'date',
        'audit_date' => 'date',
        'next_audit_date' => 'date',
        'renewal_reminder_days' => 'integer'
    ];

    /**
     * Get the certificate type label
     */
    public function getCertificateTypeLabel()
    {
        $types = [
            'gmp' => 'GMP',
            'iso' => 'ISO',
            'haccp' => 'HACCP',
            'halal' => 'حلال',
            'organic' => 'عضوي',
            'fda' => 'FDA',
            'ce_marking' => 'CE Marking',
            'other' => 'أخرى'
        ];
        
        return $types[$this->certificate_type] ?? $this->certificate_type;
    }

    /**
     * Get the certificate status label
     */
    public function getCertificateStatusLabel()
    {
        $statuses = [
            'active' => 'نشط',
            'expired' => 'منتهي الصلاحية',
            'suspended' => 'معلق',
            'revoked' => 'ملغي'
        ];
        
        $statusValue = $this->certificate_status ?? $this->status;
        return $statuses[$statusValue] ?? $statusValue;
    }

    /**
     * Get the certificate status color
     */
    public function getCertificateStatusColor()
    {
        $colors = [
            'active' => '#48bb78',
            'expired' => '#f56565',
            'suspended' => '#ed8936',
            'revoked' => '#718096'
        ];
        
        return $colors[$this->certificate_status] ?? '#718096';
    }

    /**
     * Get the certificate type color
     */
    public function getCertificateTypeColor()
    {
        $colors = [
            'gmp' => '#4ecdc4',
            'iso' => '#667eea',
            'haccp' => '#f093fb',
            'halal' => '#48bb78',
            'organic' => '#38a169',
            'fda' => '#4299e1',
            'ce_marking' => '#ed8936',
            'other' => '#718096'
        ];
        
        return $colors[$this->certificate_type] ?? '#718096';
    }

    /**
     * Check if certificate is expired
     */
    public function isExpired()
    {
        if (!$this->expiry_date) {
            return false;
        }
        
        return $this->expiry_date->isPast();
    }

    /**
     * Check if certificate expires soon
     */
    public function expiresSoon()
    {
        if (!$this->expiry_date) {
            return false;
        }
        
        $reminderDays = $this->renewal_reminder_days ?? 30;
        return $this->expiry_date->diffInDays(now()) <= $reminderDays && $this->expiry_date->isFuture();
    }

    /**
     * Get days until expiry
     */
    public function getDaysUntilExpiry()
    {
        if (!$this->expiry_date) {
            return null;
        }
        
        return $this->expiry_date->diffInDays(now(), false);
    }

    /**
     * Check if audit is due soon
     */
    public function auditDueSoon()
    {
        if (!$this->next_audit_date) {
            return false;
        }
        
        return $this->next_audit_date->diffInDays(now()) <= 30 && $this->next_audit_date->isFuture();
    }

    /**
     * Scope for active certificates
     */
    public function scopeActive($query)
    {
        $hasCertStatus = Schema::hasColumn('certificates', 'certificate_status');
        $hasStatus = Schema::hasColumn('certificates', 'status');

        return $query->where(function ($q) use ($hasCertStatus, $hasStatus) {
            if ($hasCertStatus) { $q->orWhere('certificate_status', 'active'); }
            if ($hasStatus) { $q->orWhere('status', 'active'); }
        });
    }

    /**
     * Scope for expired certificates
     */
    public function scopeExpired($query)
    {
        $hasCertStatus = Schema::hasColumn('certificates', 'certificate_status');
        $hasStatus = Schema::hasColumn('certificates', 'status');

        return $query->where(function ($q) use ($hasCertStatus, $hasStatus) {
                    if ($hasCertStatus) { $q->orWhere('certificate_status', 'expired'); }
                    if ($hasStatus) { $q->orWhere('status', 'expired'); }
                })
                ->orWhere('expiry_date', '<', now());
    }

    /**
     * Scope for certificates expiring soon
     */
    public function scopeExpiringSoon($query, $days = 30)
    {
        $hasCertStatus = Schema::hasColumn('certificates', 'certificate_status');
        $hasStatus = Schema::hasColumn('certificates', 'status');

        return $query->where('expiry_date', '>', now())
                    ->where('expiry_date', '<=', now()->addDays($days))
                    ->when($hasCertStatus || $hasStatus, function ($q) use ($hasCertStatus, $hasStatus) {
                        $q->where(function ($qq) use ($hasCertStatus, $hasStatus) {
                            if ($hasCertStatus) { $qq->orWhere('certificate_status', 'active'); }
                            if ($hasStatus) { $qq->orWhere('status', 'active'); }
                        });
                    });
    }

    /**
     * Scope for specific certificate type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('certificate_type', $type);
    }

    /**
     * Scope for certificates by issuing authority
     */
    public function scopeByAuthority($query, $authority)
    {
        return $query->where('issuing_authority', $authority);
    }
}
