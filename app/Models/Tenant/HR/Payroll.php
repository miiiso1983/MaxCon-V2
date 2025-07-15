<?php

namespace App\Models\Tenant\HR;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Payroll extends Model
{
    use HasFactory;

    protected $table = 'hr_payrolls';

    protected $fillable = [
        'tenant_id',
        'employee_id',
        'payroll_period',
        'month',
        'year',
        'basic_salary',
        'allowances',
        'overtime_amount',
        'bonus',
        'gross_salary',
        'deductions',
        'tax_amount',
        'social_security',
        'insurance_deduction',
        'loan_deduction',
        'other_deductions',
        'total_deductions',
        'net_salary',
        'working_days',
        'present_days',
        'absent_days',
        'leave_days',
        'overtime_hours',
        'late_hours',
        'status',
        'payment_date',
        'payment_method',
        'bank_reference',
        'notes',
        'generated_by',
        'approved_by',
        'approved_at',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'basic_salary' => 'decimal:2',
        'allowances' => 'json',
        'overtime_amount' => 'decimal:2',
        'bonus' => 'decimal:2',
        'gross_salary' => 'decimal:2',
        'deductions' => 'json',
        'tax_amount' => 'decimal:2',
        'social_security' => 'decimal:2',
        'insurance_deduction' => 'decimal:2',
        'loan_deduction' => 'decimal:2',
        'other_deductions' => 'decimal:2',
        'total_deductions' => 'decimal:2',
        'net_salary' => 'decimal:2',
        'working_days' => 'integer',
        'present_days' => 'integer',
        'absent_days' => 'integer',
        'leave_days' => 'integer',
        'overtime_hours' => 'decimal:2',
        'late_hours' => 'decimal:2',
        'payment_date' => 'date',
        'approved_at' => 'datetime'
    ];

    // Payroll Status
    const STATUS_OPTIONS = [
        'draft' => 'مسودة',
        'calculated' => 'محسوب',
        'approved' => 'معتمد',
        'paid' => 'مدفوع',
        'cancelled' => 'ملغي'
    ];

    // Payment Methods
    const PAYMENT_METHODS = [
        'bank_transfer' => 'تحويل بنكي',
        'cash' => 'نقداً',
        'check' => 'شيك'
    ];

    /**
     * Get the employee this payroll belongs to
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Get the approver
     */
    public function approver()
    {
        return $this->belongsTo(Employee::class, 'approved_by');
    }

    /**
     * Get the generator
     */
    public function generator()
    {
        return $this->belongsTo(Employee::class, 'generated_by');
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute()
    {
        return self::STATUS_OPTIONS[$this->status] ?? $this->status;
    }

    /**
     * Get payment method label
     */
    public function getPaymentMethodLabelAttribute()
    {
        return self::PAYMENT_METHODS[$this->payment_method] ?? $this->payment_method;
    }

    /**
     * Get payroll period label
     */
    public function getPayrollPeriodLabelAttribute()
    {
        $months = [
            1 => 'يناير', 2 => 'فبراير', 3 => 'مارس', 4 => 'أبريل',
            5 => 'مايو', 6 => 'يونيو', 7 => 'يوليو', 8 => 'أغسطس',
            9 => 'سبتمبر', 10 => 'أكتوبر', 11 => 'نوفمبر', 12 => 'ديسمبر'
        ];
        
        return $months[$this->month] . ' ' . $this->year;
    }

    /**
     * Calculate gross salary
     */
    public function calculateGrossSalary()
    {
        $gross = $this->basic_salary;
        
        // Add allowances
        if ($this->allowances) {
            foreach ($this->allowances as $allowance) {
                $gross += $allowance['amount'] ?? 0;
            }
        }
        
        // Add overtime
        $gross += $this->overtime_amount;
        
        // Add bonus
        $gross += $this->bonus;
        
        $this->gross_salary = $gross;
        return $gross;
    }

    /**
     * Calculate total deductions
     */
    public function calculateTotalDeductions()
    {
        $total = $this->tax_amount + $this->social_security + 
                $this->insurance_deduction + $this->loan_deduction + 
                $this->other_deductions;
        
        // Add custom deductions
        if ($this->deductions) {
            foreach ($this->deductions as $deduction) {
                $total += $deduction['amount'] ?? 0;
            }
        }
        
        $this->total_deductions = $total;
        return $total;
    }

    /**
     * Calculate net salary
     */
    public function calculateNetSalary()
    {
        $this->calculateGrossSalary();
        $this->calculateTotalDeductions();
        
        $this->net_salary = $this->gross_salary - $this->total_deductions;
        return $this->net_salary;
    }

    /**
     * Calculate attendance data
     */
    public function calculateAttendanceData()
    {
        $startDate = Carbon::create($this->year, $this->month, 1);
        $endDate = $startDate->copy()->endOfMonth();
        
        // Get working days (excluding weekends)
        $workingDays = 0;
        $currentDate = $startDate->copy();
        while ($currentDate->lte($endDate)) {
            if (!$currentDate->isFriday() && !$currentDate->isSaturday()) {
                $workingDays++;
            }
            $currentDate->addDay();
        }
        $this->working_days = $workingDays;
        
        // Get attendance records
        $attendances = Attendance::where('employee_id', $this->employee_id)
                                ->whereBetween('date', [$startDate, $endDate])
                                ->get();
        
        $this->present_days = $attendances->where('status', 'present')->count();
        $this->absent_days = $attendances->where('status', 'absent')->count();
        $this->overtime_hours = $attendances->sum('overtime_hours');
        $this->late_hours = $attendances->sum('late_minutes') / 60;
        
        // Get leave days
        $leaves = Leave::where('employee_id', $this->employee_id)
                      ->where('status', 'approved')
                      ->dateRange($startDate, $endDate)
                      ->sum('days_approved');
        $this->leave_days = $leaves;
        
        return $this;
    }

    /**
     * Calculate overtime amount
     */
    public function calculateOvertimeAmount($hourlyRate = null)
    {
        if (!$hourlyRate) {
            // Calculate hourly rate from basic salary (assuming 8 hours/day, 22 working days/month)
            $hourlyRate = $this->basic_salary / (8 * 22);
        }
        
        // Overtime rate is usually 1.5x regular rate
        $overtimeRate = $hourlyRate * 1.5;
        $this->overtime_amount = $this->overtime_hours * $overtimeRate;
        
        return $this->overtime_amount;
    }

    /**
     * Calculate tax amount (simplified calculation)
     */
    public function calculateTaxAmount($taxRate = 0.15)
    {
        // Tax on gross salary minus exemptions
        $taxableAmount = max(0, $this->gross_salary - 250000); // 250k exemption
        $this->tax_amount = $taxableAmount * $taxRate;
        
        return $this->tax_amount;
    }

    /**
     * Calculate social security (simplified)
     */
    public function calculateSocialSecurity($rate = 0.12)
    {
        $this->social_security = $this->basic_salary * $rate;
        return $this->social_security;
    }

    /**
     * Approve payroll
     */
    public function approve($approvedBy)
    {
        $this->status = 'approved';
        $this->approved_by = $approvedBy;
        $this->approved_at = now();
        $this->save();
        
        return $this;
    }

    /**
     * Mark as paid
     */
    public function markAsPaid($paymentMethod, $bankReference = null)
    {
        $this->status = 'paid';
        $this->payment_date = now();
        $this->payment_method = $paymentMethod;
        $this->bank_reference = $bankReference;
        $this->save();
        
        return $this;
    }

    /**
     * Scope for payrolls by period
     */
    public function scopeByPeriod($query, $month, $year)
    {
        return $query->where('month', $month)->where('year', $year);
    }

    /**
     * Scope for payrolls by status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope for approved payrolls
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope for paid payrolls
     */
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($payroll) {
            $payroll->calculateNetSalary();
        });
    }
}
