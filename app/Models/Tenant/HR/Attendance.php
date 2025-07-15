<?php

namespace App\Models\Tenant\HR;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Attendance extends Model
{
    use HasFactory;

    protected $table = 'hr_attendances';

    protected $fillable = [
        'tenant_id',
        'employee_id',
        'date',
        'check_in_time',
        'check_out_time',
        'break_start_time',
        'break_end_time',
        'total_hours',
        'regular_hours',
        'overtime_hours',
        'break_hours',
        'late_minutes',
        'early_leave_minutes',
        'status',
        'notes',
        'approved_by',
        'approved_at',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'date' => 'date',
        'check_in_time' => 'datetime',
        'check_out_time' => 'datetime',
        'break_start_time' => 'datetime',
        'break_end_time' => 'datetime',
        'total_hours' => 'decimal:2',
        'regular_hours' => 'decimal:2',
        'overtime_hours' => 'decimal:2',
        'break_hours' => 'decimal:2',
        'late_minutes' => 'integer',
        'early_leave_minutes' => 'integer',
        'approved_at' => 'datetime'
    ];

    // Attendance Status
    const STATUS_OPTIONS = [
        'present' => 'حاضر',
        'absent' => 'غائب',
        'late' => 'متأخر',
        'early_leave' => 'انصراف مبكر',
        'half_day' => 'نصف يوم',
        'holiday' => 'عطلة',
        'leave' => 'إجازة'
    ];

    /**
     * Get the employee this attendance belongs to
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
     * Get status label
     */
    public function getStatusLabelAttribute()
    {
        return self::STATUS_OPTIONS[$this->status] ?? $this->status;
    }

    /**
     * Calculate total working hours
     */
    public function calculateTotalHours()
    {
        if (!$this->check_in_time || !$this->check_out_time) {
            return 0;
        }

        $checkIn = Carbon::parse($this->check_in_time);
        $checkOut = Carbon::parse($this->check_out_time);
        
        $totalMinutes = $checkOut->diffInMinutes($checkIn);
        
        // Subtract break time if exists
        if ($this->break_start_time && $this->break_end_time) {
            $breakStart = Carbon::parse($this->break_start_time);
            $breakEnd = Carbon::parse($this->break_end_time);
            $breakMinutes = $breakEnd->diffInMinutes($breakStart);
            $totalMinutes -= $breakMinutes;
            $this->break_hours = round($breakMinutes / 60, 2);
        }

        $this->total_hours = round($totalMinutes / 60, 2);
        
        // Calculate regular and overtime hours (assuming 8 hours regular)
        $regularHoursLimit = 8;
        $this->regular_hours = min($this->total_hours, $regularHoursLimit);
        $this->overtime_hours = max(0, $this->total_hours - $regularHoursLimit);
        
        return $this->total_hours;
    }

    /**
     * Calculate late minutes
     */
    public function calculateLateMinutes($expectedCheckIn = '09:00')
    {
        if (!$this->check_in_time) {
            return 0;
        }

        $checkIn = Carbon::parse($this->check_in_time);
        $expected = Carbon::parse($this->date->format('Y-m-d') . ' ' . $expectedCheckIn);
        
        if ($checkIn->gt($expected)) {
            $this->late_minutes = $checkIn->diffInMinutes($expected);
            if ($this->late_minutes > 0) {
                $this->status = 'late';
            }
        } else {
            $this->late_minutes = 0;
        }
        
        return $this->late_minutes;
    }

    /**
     * Calculate early leave minutes
     */
    public function calculateEarlyLeaveMinutes($expectedCheckOut = '17:00')
    {
        if (!$this->check_out_time) {
            return 0;
        }

        $checkOut = Carbon::parse($this->check_out_time);
        $expected = Carbon::parse($this->date->format('Y-m-d') . ' ' . $expectedCheckOut);
        
        if ($checkOut->lt($expected)) {
            $this->early_leave_minutes = $expected->diffInMinutes($checkOut);
            if ($this->early_leave_minutes > 0) {
                $this->status = 'early_leave';
            }
        } else {
            $this->early_leave_minutes = 0;
        }
        
        return $this->early_leave_minutes;
    }

    /**
     * Scope for attendance by date range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }

    /**
     * Scope for attendance by employee
     */
    public function scopeByEmployee($query, $employeeId)
    {
        return $query->where('employee_id', $employeeId);
    }

    /**
     * Scope for attendance by status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope for present attendance
     */
    public function scopePresent($query)
    {
        return $query->whereIn('status', ['present', 'late', 'early_leave']);
    }

    /**
     * Scope for absent attendance
     */
    public function scopeAbsent($query)
    {
        return $query->where('status', 'absent');
    }

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($attendance) {
            $attendance->calculateTotalHours();
            $attendance->calculateLateMinutes();
            $attendance->calculateEarlyLeaveMinutes();
        });
    }
}
