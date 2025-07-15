<?php

namespace App\Models\Tenant\HR;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeaveType extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'hr_leave_types';

    protected $fillable = [
        'tenant_id',
        'name',
        'name_english',
        'code',
        'description',
        'days_per_year',
        'max_consecutive_days',
        'min_notice_days',
        'is_paid',
        'requires_approval',
        'requires_attachment',
        'carry_forward',
        'gender_specific',
        'applicable_after_months',
        'color',
        'is_active',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'days_per_year' => 'integer',
        'max_consecutive_days' => 'integer',
        'min_notice_days' => 'integer',
        'applicable_after_months' => 'integer',
        'is_paid' => 'boolean',
        'requires_approval' => 'boolean',
        'requires_attachment' => 'boolean',
        'carry_forward' => 'boolean',
        'is_active' => 'boolean'
    ];

    // Gender Specific Options
    const GENDER_SPECIFIC = [
        'all' => 'الجميع',
        'male' => 'ذكور فقط',
        'female' => 'إناث فقط'
    ];

    /**
     * Get leaves of this type
     */
    public function leaves()
    {
        return $this->hasMany(Leave::class);
    }

    /**
     * Get gender specific label
     */
    public function getGenderSpecificLabelAttribute()
    {
        return self::GENDER_SPECIFIC[$this->gender_specific] ?? $this->gender_specific;
    }

    /**
     * Check if leave type is applicable for employee
     */
    public function isApplicableForEmployee(Employee $employee)
    {
        // Check gender restriction
        if ($this->gender_specific !== 'all' && $employee->gender !== $this->gender_specific) {
            return false;
        }

        // Check employment duration
        if ($this->applicable_after_months > 0) {
            $employmentMonths = $employee->hire_date->diffInMonths(now());
            if ($employmentMonths < $this->applicable_after_months) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get remaining days for employee in current year
     */
    public function getRemainingDaysForEmployee(Employee $employee)
    {
        $usedDays = Leave::where('employee_id', $employee->id)
                        ->where('leave_type_id', $this->id)
                        ->where('status', 'approved')
                        ->whereYear('start_date', now()->year)
                        ->sum('days_approved');

        return max(0, $this->days_per_year - $usedDays);
    }

    /**
     * Scope for active leave types
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for paid leave types
     */
    public function scopePaid($query)
    {
        return $query->where('is_paid', true);
    }

    /**
     * Scope for leave types applicable to gender
     */
    public function scopeForGender($query, $gender)
    {
        return $query->where(function ($q) use ($gender) {
            $q->where('gender_specific', 'all')
              ->orWhere('gender_specific', $gender);
        });
    }

    /**
     * Generate unique leave type code
     */
    public static function generateLeaveTypeCode($tenantId)
    {
        $lastLeaveType = self::where('tenant_id', $tenantId)
                            ->orderBy('id', 'desc')
                            ->first();
        
        $nextNumber = $lastLeaveType ? (int)substr($lastLeaveType->code, -3) + 1 : 1;
        
        return 'LT' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($leaveType) {
            if (!$leaveType->code) {
                $leaveType->code = self::generateLeaveTypeCode($leaveType->tenant_id);
            }
        });
    }
}
