<?php

namespace App\Models\Tenant\HR;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Overtime extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'hr_overtimes';

    protected $fillable = [
        'tenant_id',
        'employee_id',
        'date',
        'start_time',
        'end_time',
        'hours_requested',
        'hours_approved',
        'hourly_rate',
        'overtime_rate',
        'total_amount',
        'reason',
        'status',
        'requested_by',
        'approved_by',
        'approved_date',
        'rejected_reason',
        'is_holiday_overtime',
        'is_night_overtime',
        'notes',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'hours_requested' => 'decimal:2',
        'hours_approved' => 'decimal:2',
        'hourly_rate' => 'decimal:2',
        'overtime_rate' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'approved_date' => 'date',
        'is_holiday_overtime' => 'boolean',
        'is_night_overtime' => 'boolean'
    ];

    // Overtime Status
    const STATUS_OPTIONS = [
        'pending' => 'في انتظار الموافقة',
        'approved' => 'موافق عليها',
        'rejected' => 'مرفوضة',
        'cancelled' => 'ملغية',
        'completed' => 'مكتملة'
    ];

    /**
     * Get the employee this overtime belongs to
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Get the requester
     */
    public function requester()
    {
        return $this->belongsTo(Employee::class, 'requested_by');
    }

    /**
     * Get the approver
     */
    public function approver()
    {
        return $this->belongsTo(Employee::class, 'approved_by');
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute()
    {
        return self::STATUS_OPTIONS[$this->status] ?? $this->status;
    }

    /**
     * Calculate overtime hours
     */
    public function calculateOvertimeHours()
    {
        if (!$this->start_time || !$this->end_time) {
            return 0;
        }

        $start = \Carbon\Carbon::parse($this->start_time);
        $end = \Carbon\Carbon::parse($this->end_time);
        
        $totalMinutes = $end->diffInMinutes($start);
        $this->hours_requested = round($totalMinutes / 60, 2);
        
        return $this->hours_requested;
    }

    /**
     * Calculate overtime amount
     */
    public function calculateOvertimeAmount()
    {
        if (!$this->hours_approved || !$this->hourly_rate) {
            return 0;
        }

        $rate = $this->overtime_rate ?: 1.5; // Default 1.5x rate
        
        // Holiday overtime might have higher rate
        if ($this->is_holiday_overtime) {
            $rate = 2.0;
        }
        
        // Night overtime might have additional allowance
        if ($this->is_night_overtime) {
            $rate += 0.25;
        }
        
        $this->total_amount = $this->hours_approved * $this->hourly_rate * $rate;
        
        return $this->total_amount;
    }

    /**
     * Check if overtime is on holiday
     */
    public function checkIfHolidayOvertime()
    {
        $date = \Carbon\Carbon::parse($this->date);
        
        // Check if it's weekend (Friday/Saturday in Iraq)
        if ($date->isFriday() || $date->isSaturday()) {
            $this->is_holiday_overtime = true;
            return true;
        }
        
        // TODO: Check against company holidays table
        
        return false;
    }

    /**
     * Check if overtime is during night hours
     */
    public function checkIfNightOvertime()
    {
        $start = \Carbon\Carbon::parse($this->start_time);
        $end = \Carbon\Carbon::parse($this->end_time);
        
        // Night hours: 10 PM to 6 AM
        $nightStart = $start->copy()->setTime(22, 0);
        $nightEnd = $start->copy()->addDay()->setTime(6, 0);
        
        // Check if any part of overtime is during night hours
        if ($start->between($nightStart, $nightEnd) || $end->between($nightStart, $nightEnd)) {
            $this->is_night_overtime = true;
            return true;
        }
        
        return false;
    }

    /**
     * Approve overtime
     */
    public function approve($approvedBy, $hoursApproved = null)
    {
        $this->status = 'approved';
        $this->approved_by = $approvedBy;
        $this->approved_date = now();
        $this->hours_approved = $hoursApproved ?: $this->hours_requested;
        $this->calculateOvertimeAmount();
        $this->save();
        
        return $this;
    }

    /**
     * Reject overtime
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
     * Scope for overtime by status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope for pending overtime
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for approved overtime
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope for overtime by date range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }

    /**
     * Scope for holiday overtime
     */
    public function scopeHolidayOvertime($query)
    {
        return $query->where('is_holiday_overtime', true);
    }

    /**
     * Scope for night overtime
     */
    public function scopeNightOvertime($query)
    {
        return $query->where('is_night_overtime', true);
    }

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($overtime) {
            $overtime->calculateOvertimeHours();
            $overtime->checkIfHolidayOvertime();
            $overtime->checkIfNightOvertime();
        });

        static::updating(function ($overtime) {
            if ($overtime->isDirty(['start_time', 'end_time'])) {
                $overtime->calculateOvertimeHours();
                $overtime->checkIfHolidayOvertime();
                $overtime->checkIfNightOvertime();
            }
            
            if ($overtime->isDirty(['hours_approved', 'hourly_rate', 'overtime_rate'])) {
                $overtime->calculateOvertimeAmount();
            }
        });
    }
}
