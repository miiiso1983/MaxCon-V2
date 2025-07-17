<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\BelongsToTenant;

class Department extends Model
{
    use SoftDeletes, BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'code',
        'name',
        'name_en',
        'parent_id',
        'level',
        'is_active',
        'manager_id',
        'description',
        'created_by'
    ];

    protected $casts = [
        'level' => 'integer',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Department::class, 'parent_id');
    }

    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function chartOfAccounts(): HasMany
    {
        return $this->hasMany(\App\Models\Accounting\ChartOfAccount::class);
    }

    public function journalEntryLines(): HasMany
    {
        return $this->hasMany(\App\Models\Accounting\JournalEntryLine::class);
    }

    public function employees(): HasMany
    {
        return $this->hasMany(User::class, 'department_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeParents($query)
    {
        return $query->whereNull('parent_id');
    }

    public function scopeByLevel($query, $level)
    {
        return $query->where('level', $level);
    }

    // Methods
    public function getFullName()
    {
        $name = $this->name;
        $parent = $this->parent;
        
        while ($parent) {
            $name = $parent->name . ' > ' . $name;
            $parent = $parent->parent;
        }
        
        return $name;
    }

    public function getIndentedName()
    {
        return str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $this->level - 1) . $this->name;
    }

    public function hasChildren()
    {
        return $this->children()->count() > 0;
    }

    public function canBeDeleted()
    {
        return $this->children()->count() === 0 && 
               $this->employees()->count() === 0 &&
               $this->chartOfAccounts()->count() === 0 && 
               $this->journalEntryLines()->count() === 0;
    }

    public function getAllDescendants()
    {
        $descendants = collect();
        
        foreach ($this->children as $child) {
            $descendants->push($child);
            $descendants = $descendants->merge($child->getAllDescendants());
        }
        
        return $descendants;
    }

    public function getEmployeeCount()
    {
        $count = $this->employees()->count();
        
        foreach ($this->children as $child) {
            $count += $child->getEmployeeCount();
        }
        
        return $count;
    }

    // Static methods
    public static function generateCode($tenantId, $parentId = null)
    {
        if ($parentId) {
            $parent = static::find($parentId);
            $lastChild = static::where('parent_id', $parentId)
                ->orderBy('code', 'desc')
                ->first();
            
            if ($lastChild) {
                $lastNumber = intval(substr($lastChild->code, -2));
                $newNumber = $lastNumber + 1;
            } else {
                $newNumber = 1;
            }
            
            return $parent->code . str_pad($newNumber, 2, '0', STR_PAD_LEFT);
        } else {
            $lastDepartment = static::where('tenant_id', $tenantId)
                ->whereNull('parent_id')
                ->orderBy('code', 'desc')
                ->first();
            
            if ($lastDepartment) {
                $lastNumber = intval(substr($lastDepartment->code, 4));
                $newNumber = $lastNumber + 1;
            } else {
                $newNumber = 1;
            }
            
            return 'DEPT' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
        }
    }

    public static function getDepartmentTree($tenantId)
    {
        return static::where('tenant_id', $tenantId)
            ->where('is_active', true)
            ->orderBy('code')
            ->get()
            ->toTree();
    }

    // Boot method
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->code)) {
                $model->code = static::generateCode($model->tenant_id, $model->parent_id);
            }
            
            // Set level based on parent
            if ($model->parent_id) {
                $parent = static::find($model->parent_id);
                $model->level = $parent ? $parent->level + 1 : 1;
            } else {
                $model->level = 1;
            }
        });
    }
}
