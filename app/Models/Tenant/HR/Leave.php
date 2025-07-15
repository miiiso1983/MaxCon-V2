<?php

namespace App\Models\Tenant\HR;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Leave extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'hr_leaves';

    protected $fillable = [
        'tenant_id',
        'employee_id',
        'leave_type_id',
        'start_date',
        'end_date',
        'days_requested',
        'days_approved',
        'reason',
        'status',
        'applied_date',
        'approved_by',
        'approved_date',
        'rejected_reason',
        'is_paid',
        'replacement_employee_id',
        'emergency_contact',
        'attachments',
        'notes',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'applied_date' => 'date',
        'approved_date' => 'date',
        'days_requested' => 'integer',
        'days_approved' => 'integer',
        'is_paid' => 'boolean',
        'attachments' => 'array'
    ];

    // Leave Status
    const STATUS_OPTIONS = [
        'pending' => 'في انتظار الموافقة',
        'approved' => 'موافق عليها',
        'rejected' => 'مرفوضة',
        'cancelled' => 'ملغية',
        'completed' => 'مكتملة'
    ];

    /**
     * Get the employee this leave belongs to
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Get the leave type
     */
    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class);
    }

    /**
     * Get the approver
     */
    public function approver()
    {
        return $this->belongsTo(Employee::class, 'approved_by');
    }

    /**
     * Get the replacement employee
     */
    public function replacementEmployee()
    {
        return $this->belongsTo(Employee::class, 'replacement_employee_id');
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute()
    {
        return self::STATUS_OPTIONS[$this->status] ?? $this->status;
    }

    /**
     * Calculate working days between start and end date
     */
    public function calculateWorkingDays()
    {
        if (!$this->start_date || !$this->end_date) {
            return 0;
        }

        $startDate = Carbon::parse($this->start_date);
        $endDate = Carbon::parse($this->end_date);
        
        $workingDays = 0;
        $currentDate = $startDate->copy();
        
        while ($currentDate->lte($endDate)) {
            // Skip weekends (Friday and Saturday in Iraq)
            if (!$currentDate->isFriday() && !$currentDate->isSaturday()) {
                $workingDays++;
            }
            $currentDate->addDay();
        }
        
        return $workingDays;
    }

    /**
     * Check if leave overlaps with another leave
     */
    public function hasOverlap($employeeId = null)
    {
        $employeeId = $employeeId ?: $this->employee_id;
        
        return self::where('employee_id', $employeeId)
                  ->where('id', '!=', $this->id)
                  ->where('status', '!=', 'rejected')
                  ->where('status', '!=', 'cancelled')
                  ->where(function ($query) {
                      $query->whereBetween('start_date', [$this->start_date, $this->end_date])
                            ->orWhereBetween('end_date', [$this->start_date, $this->end_date])
                            ->orWhere(function ($q) {
                                $q->where('start_date', '<=', $this->start_date)
                                  ->where('end_date', '>=', $this->end_date);
                            });
                  })
                  ->exists();
    }

    /**
     * Check if employee has sufficient leave balance
     */
    public function hasSufficientBalance()
    {
        if (!$this->leaveType) {
            return false;
        }

        $usedDays = self::where('employee_id', $this->employee_id)
                       ->where('leave_type_id', $this->leave_type_id)
                       ->where('status', 'approved')
                       ->whereYear('start_date', now()->year)
                       ->sum('days_approved');

        $availableDays = $this->leaveType->days_per_year - $usedDays;
        
        return $availableDays >= $this->days_requested;
    }

    /**
     * Approve leave
     */
    public function approve($approvedBy, $daysApproved = null)
    {
        $this->status = 'approved';
        $this->approved_by = $approvedBy;
        $this->approved_date = now();
        $this->days_approved = $daysApproved ?: $this->days_requested;
        $this->save();
        
        return $this;
    }

    /**
     * Reject leave
     */
    public function reject($approvedBy, $reason)
    {
        $this->status = 'rejected';
        $this->approved_by = $approvedBy;
        $this->approved_date = now();
        $this->rejected_reason = $reason;
        $this->save();
        
        return $this;
    }

    /**
     * Scope for leaves by status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope for pending leaves
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for approved leaves
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope for leaves by date range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->where(function ($q) use ($startDate, $endDate) {
            $q->whereBetween('start_date', [$startDate, $endDate])
              ->orWhereBetween('end_date', [$startDate, $endDate])
              ->orWhere(function ($query) use ($startDate, $endDate) {
                  $query->where('start_date', '<=', $startDate)
                        ->where('end_date', '>=', $endDate);
              });
        });
    }

    /**
     * Scope for current year leaves
     */
    public function scopeCurrentYear($query)
    {
        return $query->whereYear('start_date', now()->year);
    }

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($leave) {
            $leave->days_requested = $leave->calculateWorkingDays();
            $leave->applied_date = now();
        });

        static::updating(function ($leave) {
            if ($leave->isDirty(['start_date', 'end_date'])) {
                $leave->days_requested = $leave->calculateWorkingDays();
            }
        });
    }
}
