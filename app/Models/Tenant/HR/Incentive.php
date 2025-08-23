<?php

namespace App\Models\Tenant\HR;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Incentive extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'hr_incentives';

    protected $fillable = [
        'tenant_id','employee_id','type','amount','reason','date','created_by','updated_by'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'date' => 'date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}

