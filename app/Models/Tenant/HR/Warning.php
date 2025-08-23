<?php

namespace App\Models\Tenant\HR;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Warning extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'hr_warnings';

    protected $fillable = [
        'tenant_id','employee_id','type','reason','severity','date','escalated','warning_count','created_by','updated_by'
    ];

    protected $casts = [
        'date' => 'date',
        'escalated' => 'boolean',
        'warning_count' => 'integer',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}

