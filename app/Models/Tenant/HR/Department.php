<?php

namespace App\Models\Tenant\HR;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'hr_departments';

    protected $fillable = [
        'tenant_id',
        'name',
        'name_english',
        'code',
        'description',
        'parent_id',
        'manager_id',
        'budget',
        'location',
        'phone',
        'email',
        'is_active',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'budget' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    /**
     * Get the parent department
     */
    public function parent()
    {
        return $this->belongsTo(Department::class, 'parent_id');
    }

    /**
     * Get child departments
     */
    public function children()
    {
        return $this->hasMany(Department::class, 'parent_id');
    }

    /**
     * Get all descendants recursively
     */
    public function descendants()
    {
        return $this->children()->with('descendants');
    }

    /**
     * Get department manager
     */
    public function manager()
    {
        return $this->belongsTo(Employee::class, 'manager_id');
    }

    /**
     * Get department employees
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
     * Get department positions
     */
    public function positions()
    {
        return $this->hasMany(Position::class);
    }

    /**
     * Scope for active departments
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for root departments (no parent)
     */
    public function scopeRoot($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Get department hierarchy path
     */
    public function getHierarchyPathAttribute()
    {
        $path = [$this->name];
        $parent = $this->parent;
        
        while ($parent) {
            array_unshift($path, $parent->name);
            $parent = $parent->parent;
        }
        
        return implode(' > ', $path);
    }

    /**
     * Generate unique department code
     */
    public static function generateDepartmentCode($tenantId)
    {
        $lastDepartment = self::where('tenant_id', $tenantId)
                             ->orderBy('id', 'desc')
                             ->first();
        
        $nextNumber = $lastDepartment ? (int)substr($lastDepartment->code, -3) + 1 : 1;
        
        return 'DEPT' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($department) {
            if (!$department->code) {
                $department->code = self::generateDepartmentCode($department->tenant_id);
            }
        });
    }
}
