<?php

namespace App\Models\Tenant\HR;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeShift extends Model
{
    use HasFactory;

    protected $table = 'hr_employee_shifts';

    protected $fillable = [
        'tenant_id',
        'employee_id',
        'shift_id',
        'date',
        'start_date',
        'end_date',
        'is_active',
        'notes',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'date' => 'date',
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean'
    ];

    /**
     * Get the employee
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Get the shift
     */
    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }

    /**
     * Scope for active employee shifts
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for employee shifts by date
     */
    public function scopeByDate($query, $date)
    {
        return $query->where('date', $date);
    }

    /**
     * Scope for employee shifts by date range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }

    /**
     * Scope for current shifts
     */
    public function scopeCurrent($query)
    {
        $today = now()->toDateString();
        return $query->where(function ($q) use ($today) {
            $q->where('date', $today)
              ->orWhere(function ($query) use ($today) {
                  $query->where('start_date', '<=', $today)
                        ->where(function ($q) use ($today) {
                            $q->where('end_date', '>=', $today)
                              ->orWhereNull('end_date');
                        });
              });
        });
    }
}
