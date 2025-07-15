<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class SalesTarget extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'title',
        'description',
        'target_type',
        'target_entity_id',
        'target_entity_name',
        'period_type',
        'start_date',
        'end_date',
        'year',
        'month',
        'quarter',
        'measurement_type',
        'target_quantity',
        'target_value',
        'currency',
        'unit',
        'achieved_quantity',
        'achieved_value',
        'progress_percentage',
        'last_updated_at',
        'status',
        'notification_80_sent',
        'notification_100_sent',
        'notification_settings',
        'created_by',
        'updated_by',
        'additional_data',
        'notes'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'last_updated_at' => 'datetime',
        'target_quantity' => 'decimal:2',
        'target_value' => 'decimal:2',
        'achieved_quantity' => 'decimal:2',
        'achieved_value' => 'decimal:2',
        'progress_percentage' => 'decimal:2',
        'notification_80_sent' => 'boolean',
        'notification_100_sent' => 'boolean',
        'notification_settings' => 'array',
        'additional_data' => 'array'
    ];

    // Relationships
    public function progress(): HasMany
    {
        return $this->hasMany(SalesTargetProgress::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeForTenant($query, $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('target_type', $type);
    }

    public function scopeByPeriod($query, $periodType, $year, $month = null, $quarter = null)
    {
        $query->where('period_type', $periodType)->where('year', $year);
        
        if ($month) {
            $query->where('month', $month);
        }
        
        if ($quarter) {
            $query->where('quarter', $quarter);
        }
        
        return $query;
    }

    public function scopeCurrent($query)
    {
        $today = Carbon::today();
        return $query->where('start_date', '<=', $today)
                    ->where('end_date', '>=', $today);
    }

    // Accessors & Mutators
    public function getProgressPercentageAttribute($value)
    {
        return round($value, 2);
    }

    public function getIsActiveAttribute()
    {
        return $this->status === 'active' && 
               Carbon::today()->between($this->start_date, $this->end_date);
    }

    public function getIsCompletedAttribute()
    {
        return $this->progress_percentage >= 100;
    }

    public function getIsOverdueAttribute()
    {
        return $this->status === 'active' && 
               Carbon::today()->gt($this->end_date) && 
               $this->progress_percentage < 100;
    }

    public function getRemainingDaysAttribute()
    {
        if ($this->end_date->isPast()) {
            return 0;
        }
        
        return Carbon::today()->diffInDays($this->end_date);
    }

    public function getElapsedDaysAttribute()
    {
        return $this->start_date->diffInDays(Carbon::today());
    }

    public function getTotalDaysAttribute()
    {
        return $this->start_date->diffInDays($this->end_date) + 1;
    }

    public function getTimeProgressPercentageAttribute()
    {
        if ($this->total_days <= 0) {
            return 0;
        }
        
        return min(100, round(($this->elapsed_days / $this->total_days) * 100, 2));
    }

    // Methods
    public function updateProgress($quantity = 0, $value = 0, $source = null)
    {
        $this->achieved_quantity += $quantity;
        $this->achieved_value += $value;
        
        // حساب نسبة التقدم
        $this->calculateProgressPercentage();
        
        $this->last_updated_at = now();
        $this->save();
        
        // تسجيل التقدم اليومي
        $this->recordDailyProgress($quantity, $value, $source);
        
        // فحص الإشعارات
        $this->checkNotifications();
        
        return $this;
    }

    public function calculateProgressPercentage()
    {
        $progress = 0;
        
        if ($this->measurement_type === 'quantity' && $this->target_quantity > 0) {
            $progress = ($this->achieved_quantity / $this->target_quantity) * 100;
        } elseif ($this->measurement_type === 'value' && $this->target_value > 0) {
            $progress = ($this->achieved_value / $this->target_value) * 100;
        } elseif ($this->measurement_type === 'both') {
            $quantityProgress = $this->target_quantity > 0 ? 
                ($this->achieved_quantity / $this->target_quantity) * 100 : 0;
            $valueProgress = $this->target_value > 0 ? 
                ($this->achieved_value / $this->target_value) * 100 : 0;
            $progress = ($quantityProgress + $valueProgress) / 2;
        }
        
        $this->progress_percentage = min(100, round($progress, 2));
        
        return $this->progress_percentage;
    }

    public function recordDailyProgress($quantity, $value, $source = null)
    {
        $today = Carbon::today();
        
        $dailyProgress = SalesTargetProgress::firstOrCreate(
            [
                'tenant_id' => $this->tenant_id,
                'sales_target_id' => $this->id,
                'progress_date' => $today
            ],
            [
                'daily_quantity' => 0,
                'daily_value' => 0,
                'cumulative_quantity' => $this->achieved_quantity,
                'cumulative_value' => $this->achieved_value,
                'progress_percentage' => $this->progress_percentage,
                'updated_by' => auth()->id()
            ]
        );
        
        $dailyProgress->daily_quantity += $quantity;
        $dailyProgress->daily_value += $value;
        $dailyProgress->cumulative_quantity = $this->achieved_quantity;
        $dailyProgress->cumulative_value = $this->achieved_value;
        $dailyProgress->progress_percentage = $this->progress_percentage;
        
        if ($source) {
            $dailyProgress->source_type = $source['type'] ?? null;
            $dailyProgress->source_id = $source['id'] ?? null;
            $dailyProgress->source_details = $source['details'] ?? null;
        }
        
        $dailyProgress->save();
        
        return $dailyProgress;
    }

    public function checkNotifications()
    {
        // إشعار 80%
        if ($this->progress_percentage >= 80 && !$this->notification_80_sent) {
            $this->sendProgressNotification(80);
            $this->notification_80_sent = true;
            $this->save();
        }
        
        // إشعار 100%
        if ($this->progress_percentage >= 100 && !$this->notification_100_sent) {
            $this->sendProgressNotification(100);
            $this->notification_100_sent = true;
            $this->status = 'completed';
            $this->save();
        }
    }

    public function sendProgressNotification($percentage)
    {
        // هنا يمكن إضافة منطق الإشعارات
        // مثل إرسال بريد إلكتروني أو إشعار في النظام
        
        // مثال بسيط:
        \Log::info("Target {$this->title} reached {$percentage}% completion");
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'active' => $this->is_overdue ? 'danger' : 'primary',
            'completed' => 'success',
            'paused' => 'warning',
            'cancelled' => 'secondary',
            default => 'primary'
        };
    }

    public function getStatusTextAttribute()
    {
        return match($this->status) {
            'active' => $this->is_overdue ? 'متأخر' : 'نشط',
            'completed' => 'مكتمل',
            'paused' => 'متوقف',
            'cancelled' => 'ملغي',
            default => 'غير محدد'
        };
    }
}
