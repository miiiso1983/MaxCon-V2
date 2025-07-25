<?php

namespace App\Models\Tenant\HR;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

/**
 * @property \Carbon\Carbon $probation_end_date
 * @property \Carbon\Carbon $contract_end_date
 * @property \Carbon\Carbon $hire_date
 * @property \Carbon\Carbon $birth_date
 */
class Employee extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'hr_employees';

    protected $fillable = [
        'tenant_id',
        'employee_code',
        'first_name',
        'last_name',
        'full_name_arabic',
        'full_name_english',
        'national_id',
        'passport_number',
        'date_of_birth',
        'gender',
        'marital_status',
        'nationality',
        'religion',
        'blood_type',
        'phone',
        'mobile',
        'email',
        'emergency_contact_name',
        'emergency_contact_phone',
        'current_address',
        'permanent_address',
        'department_id',
        'position_id',
        'direct_manager_id',
        'employment_type',
        'employment_status',
        'hire_date',
        'probation_end_date',
        'contract_start_date',
        'contract_end_date',
        'basic_salary',
        'hourly_rate',
        'bank_name',
        'bank_account_number',
        'iban',
        'social_security_number',
        'tax_number',
        'education_level',
        'university',
        'major',
        'graduation_year',
        'experience_years',
        'previous_company',
        'skills',
        'certifications',
        'languages',
        'profile_photo',
        'cv_file',
        'id_copy',
        'passport_copy',
        'certificates_files',
        'contract_file',
        'notes',
        'is_active',
        'termination_date',
        'termination_reason',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'date_of_birth' => 'datetime',
        'hire_date' => 'datetime',
        'probation_end_date' => 'datetime',
        'contract_start_date' => 'datetime',
        'contract_end_date' => 'datetime',
        'termination_date' => 'date',
        'basic_salary' => 'decimal:2',
        'hourly_rate' => 'decimal:2',
        'skills' => 'array',
        'certifications' => 'array',
        'languages' => 'array',
        'certificates_files' => 'array',
        'is_active' => 'boolean'
    ];

    // Employment Types
    const EMPLOYMENT_TYPES = [
        'full_time' => 'دوام كامل',
        'part_time' => 'دوام جزئي',
        'contract' => 'عقد مؤقت',
        'internship' => 'تدريب',
        'consultant' => 'استشاري'
    ];

    // Employment Status
    const EMPLOYMENT_STATUS = [
        'active' => 'نشط',
        'probation' => 'تحت التجربة',
        'suspended' => 'موقوف',
        'terminated' => 'منتهي الخدمة',
        'resigned' => 'مستقيل'
    ];

    // Gender Options
    const GENDER_OPTIONS = [
        'male' => 'ذكر',
        'female' => 'أنثى'
    ];

    // Marital Status
    const MARITAL_STATUS = [
        'single' => 'أعزب',
        'married' => 'متزوج',
        'divorced' => 'مطلق',
        'widowed' => 'أرمل'
    ];

    // Education Levels
    const EDUCATION_LEVELS = [
        'high_school' => 'ثانوية عامة',
        'diploma' => 'دبلوم',
        'bachelor' => 'بكالوريوس',
        'master' => 'ماجستير',
        'phd' => 'دكتوراه'
    ];

    /**
     * Get the department that the employee belongs to
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get the position of the employee
     */
    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    /**
     * Get the direct manager
     */
    public function manager()
    {
        return $this->belongsTo(Employee::class, 'direct_manager_id');
    }

    /**
     * Get employees managed by this employee
     */
    public function subordinates()
    {
        return $this->hasMany(Employee::class, 'direct_manager_id');
    }

    /**
     * Get employee attendances
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Get employee leaves
     */
    public function leaves()
    {
        return $this->hasMany(Leave::class);
    }

    /**
     * Get employee payrolls
     */
    public function payrolls()
    {
        return $this->hasMany(Payroll::class);
    }

    /**
     * Get employee shifts
     */
    public function shifts()
    {
        return $this->hasMany(EmployeeShift::class);
    }

    /**
     * Get employee overtime records
     */
    public function overtimes()
    {
        return $this->hasMany(Overtime::class);
    }

    /**
     * Get full name attribute
     */
    public function getFullNameAttribute()
    {
        return $this->full_name_arabic ?: $this->first_name . ' ' . $this->last_name;
    }

    /**
     * Get age attribute
     */
    public function getAgeAttribute()
    {
        return $this->date_of_birth ? Carbon::parse($this->date_of_birth)->age : null;
    }

    /**
     * Get years of service attribute
     */
    public function getYearsOfServiceAttribute()
    {
        return $this->hire_date ? Carbon::parse($this->hire_date)->diffInYears(now()) : 0;
    }

    /**
     * Check if employee is on probation
     */
    public function isOnProbation()
    {
        return $this->employment_status === 'probation' && 
               $this->probation_end_date && 
               $this->probation_end_date->isFuture();
    }

    /**
     * Check if contract is expiring soon
     */
    public function isContractExpiringSoon($days = 30)
    {
        return $this->contract_end_date && 
               $this->contract_end_date->diffInDays(now()) <= $days;
    }

    /**
     * Get employment type label
     */
    public function getEmploymentTypeLabel()
    {
        return self::EMPLOYMENT_TYPES[$this->employment_type] ?? $this->employment_type;
    }

    /**
     * Get employment status label
     */
    public function getEmploymentStatusLabel()
    {
        return self::EMPLOYMENT_STATUS[$this->employment_status] ?? $this->employment_status;
    }

    /**
     * Scope for active employees
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->where('employment_status', 'active');
    }

    /**
     * Scope for employees by department
     */
    public function scopeByDepartment($query, $departmentId)
    {
        return $query->where('department_id', $departmentId);
    }

    /**
     * Scope for employees by position
     */
    public function scopeByPosition($query, $positionId)
    {
        return $query->where('position_id', $positionId);
    }

    /**
     * Generate unique employee code
     */
    public static function generateEmployeeCode($tenantId)
    {
        $lastEmployee = self::where('tenant_id', $tenantId)
                           ->orderBy('id', 'desc')
                           ->first();
        
        $nextNumber = $lastEmployee ? (int)substr($lastEmployee->employee_code, -4) + 1 : 1;
        
        return 'EMP' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($employee) {
            if (!$employee->employee_code) {
                $employee->employee_code = self::generateEmployeeCode($employee->tenant_id);
            }
        });
    }
}
