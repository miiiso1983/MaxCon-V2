<?php

namespace App\Models\Tenant\HR;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Position extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'hr_positions';

    protected $fillable = [
        'tenant_id',
        'title',
        'title_english',
        'code',
        'department_id',
        'description',
        'responsibilities',
        'requirements',
        'min_salary',
        'max_salary',
        'level',
        'reports_to_position_id',
        'is_active',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'min_salary' => 'decimal:2',
        'max_salary' => 'decimal:2',
        'responsibilities' => 'array',
        'requirements' => 'array',
        'is_active' => 'boolean'
    ];

    // Position Levels
    const POSITION_LEVELS = [
        'entry' => 'مبتدئ',
        'junior' => 'مبتدئ متقدم',
        'mid' => 'متوسط',
        'senior' => 'كبير',
        'lead' => 'قائد فريق',
        'manager' => 'مدير',
        'director' => 'مدير عام',
        'executive' => 'تنفيذي'
    ];

    /**
     * Get the department this position belongs to
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get the position this reports to
     */
    public function reportsTo()
    {
        return $this->belongsTo(Position::class, 'reports_to_position_id');
    }

    /**
     * Get positions that report to this position
     */
    public function subordinatePositions()
    {
        return $this->hasMany(Position::class, 'reports_to_position_id');
    }

    /**
     * Get employees in this position
     */
    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    /**
     * Get active employees count
     */
    public function getActiveEmployeesCountAttribute()
    {
        return $this->employees()->active()->count();
    }

    /**
     * Get position level label
     */
    public function getLevelLabelAttribute()
    {
        return self::POSITION_LEVELS[$this->level] ?? $this->level;
    }

    /**
     * Scope for active positions
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for positions by department
     */
    public function scopeByDepartment($query, $departmentId)
    {
        return $query->where('department_id', $departmentId);
    }

    /**
     * Scope for positions by level
     */
    public function scopeByLevel($query, $level)
    {
        return $query->where('level', $level);
    }

    /**
     * Generate unique position code
     */
    public static function generatePositionCode($tenantId)
    {
        $lastPosition = self::where('tenant_id', $tenantId)
                           ->orderBy('id', 'desc')
                           ->first();
        
        $nextNumber = $lastPosition ? (int)substr($lastPosition->code, -3) + 1 : 1;
        
        return 'POS' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($position) {
            if (!$position->code) {
                $position->code = self::generatePositionCode($position->tenant_id);
            }
        });
    }
}
