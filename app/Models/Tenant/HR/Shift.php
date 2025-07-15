<?php

namespace App\Models\Tenant\HR;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shift extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'hr_shifts';

    protected $fillable = [
        'tenant_id',
        'name',
        'code',
        'start_time',
        'end_time',
        'break_duration',
        'total_hours',
        'is_night_shift',
        'night_shift_allowance',
        'overtime_threshold',
        'late_tolerance',
        'early_leave_tolerance',
        'is_active',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'break_duration' => 'integer',
        'total_hours' => 'decimal:2',
        'night_shift_allowance' => 'decimal:2',
        'overtime_threshold' => 'decimal:2',
        'late_tolerance' => 'integer',
        'early_leave_tolerance' => 'integer',
        'is_night_shift' => 'boolean',
        'is_active' => 'boolean'
    ];

    /**
     * Get employees assigned to this shift
     */
    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'hr_employee_shifts')
                    ->withPivot(['start_date', 'end_date', 'is_active'])
                    ->withTimestamps();
    }

    /**
     * Get employee shifts
     */
    public function employeeShifts()
    {
        return $this->hasMany(EmployeeShift::class);
    }

    /**
     * Calculate total hours
     */
    public function calculateTotalHours()
    {
        if (!$this->start_time || !$this->end_time) {
            return 0;
        }

        $start = \Carbon\Carbon::parse($this->start_time);
        $end = \Carbon\Carbon::parse($this->end_time);
        
        // Handle overnight shifts
        if ($end->lt($start)) {
            $end->addDay();
        }
        
        $totalMinutes = $end->diffInMinutes($start);
        $totalMinutes -= $this->break_duration;
        
        $this->total_hours = round($totalMinutes / 60, 2);
        return $this->total_hours;
    }

    /**
     * Check if time is within shift hours
     */
    public function isWithinShiftHours($time)
    {
        $checkTime = \Carbon\Carbon::parse($time);
        $start = \Carbon\Carbon::parse($this->start_time);
        $end = \Carbon\Carbon::parse($this->end_time);
        
        // Handle overnight shifts
        if ($end->lt($start)) {
            return $checkTime->gte($start) || $checkTime->lte($end);
        }
        
        return $checkTime->between($start, $end);
    }

    /**
     * Check if employee is late
     */
    public function isLate($checkInTime)
    {
        $checkIn = \Carbon\Carbon::parse($checkInTime);
        $shiftStart = \Carbon\Carbon::parse($this->start_time);
        $tolerance = $this->late_tolerance; // minutes
        
        return $checkIn->gt($shiftStart->addMinutes($tolerance));
    }

    /**
     * Check if employee left early
     */
    public function isEarlyLeave($checkOutTime)
    {
        $checkOut = \Carbon\Carbon::parse($checkOutTime);
        $shiftEnd = \Carbon\Carbon::parse($this->end_time);
        $tolerance = $this->early_leave_tolerance; // minutes
        
        return $checkOut->lt($shiftEnd->subMinutes($tolerance));
    }

    /**
     * Scope for active shifts
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for night shifts
     */
    public function scopeNightShift($query)
    {
        return $query->where('is_night_shift', true);
    }

    /**
     * Generate unique shift code
     */
    public static function generateShiftCode($tenantId)
    {
        $lastShift = self::where('tenant_id', $tenantId)
                        ->orderBy('id', 'desc')
                        ->first();
        
        $nextNumber = $lastShift ? (int)substr($lastShift->code, -3) + 1 : 1;
        
        return 'SH' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($shift) {
            if (!$shift->code) {
                $shift->code = self::generateShiftCode($shift->tenant_id);
            }
        });

        static::saving(function ($shift) {
            $shift->calculateTotalHours();
        });
    }
}
