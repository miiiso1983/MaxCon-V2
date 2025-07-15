<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class SalesTargetProgress extends Model
{
    use HasFactory;

    protected $table = 'sales_target_progress';

    protected $fillable = [
        'tenant_id',
        'sales_target_id',
        'progress_date',
        'daily_quantity',
        'daily_value',
        'cumulative_quantity',
        'cumulative_value',
        'progress_percentage',
        'source_type',
        'source_id',
        'source_details',
        'updated_by',
        'notes'
    ];

    protected $casts = [
        'progress_date' => 'date',
        'daily_quantity' => 'decimal:2',
        'daily_value' => 'decimal:2',
        'cumulative_quantity' => 'decimal:2',
        'cumulative_value' => 'decimal:2',
        'progress_percentage' => 'decimal:2',
        'source_details' => 'array'
    ];

    // Relationships
    public function salesTarget(): BelongsTo
    {
        return $this->belongsTo(SalesTarget::class);
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Scopes
    public function scopeForTenant($query, $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }

    public function scopeForTarget($query, $targetId)
    {
        return $query->where('sales_target_id', $targetId);
    }

    public function scopeForDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('progress_date', [$startDate, $endDate]);
    }

    public function scopeForMonth($query, $year, $month)
    {
        return $query->whereYear('progress_date', $year)
                    ->whereMonth('progress_date', $month);
    }

    public function scopeForWeek($query, $startOfWeek)
    {
        $endOfWeek = Carbon::parse($startOfWeek)->endOfWeek();
        return $query->whereBetween('progress_date', [$startOfWeek, $endOfWeek]);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('progress_date', Carbon::today());
    }

    public function scopeYesterday($query)
    {
        return $query->whereDate('progress_date', Carbon::yesterday());
    }

    public function scopeThisWeek($query)
    {
        return $query->whereBetween('progress_date', [
            Carbon::now()->startOfWeek(),
            Carbon::now()->endOfWeek()
        ]);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('progress_date', Carbon::now()->month)
                    ->whereYear('progress_date', Carbon::now()->year);
    }

    // Accessors
    public function getFormattedProgressDateAttribute()
    {
        return $this->progress_date->format('Y-m-d');
    }

    public function getFormattedProgressDateArabicAttribute()
    {
        return $this->progress_date->locale('ar')->isoFormat('dddd، D MMMM YYYY');
    }

    public function getDailyGrowthQuantityAttribute()
    {
        $yesterday = static::where('sales_target_id', $this->sales_target_id)
                          ->where('progress_date', $this->progress_date->subDay())
                          ->first();
        
        if (!$yesterday) {
            return 0;
        }
        
        return $this->daily_quantity - $yesterday->daily_quantity;
    }

    public function getDailyGrowthValueAttribute()
    {
        $yesterday = static::where('sales_target_id', $this->sales_target_id)
                          ->where('progress_date', $this->progress_date->subDay())
                          ->first();
        
        if (!$yesterday) {
            return 0;
        }
        
        return $this->daily_value - $yesterday->daily_value;
    }

    // Static Methods
    public static function getProgressSummary($targetId, $startDate = null, $endDate = null)
    {
        $query = static::where('sales_target_id', $targetId);
        
        if ($startDate && $endDate) {
            $query->whereBetween('progress_date', [$startDate, $endDate]);
        }
        
        return $query->selectRaw('
            SUM(daily_quantity) as total_quantity,
            SUM(daily_value) as total_value,
            AVG(daily_quantity) as avg_daily_quantity,
            AVG(daily_value) as avg_daily_value,
            MAX(progress_percentage) as max_progress,
            COUNT(*) as total_days
        ')->first();
    }

    public static function getWeeklyProgress($targetId, $year, $week)
    {
        $startOfWeek = Carbon::now()->setISODate($year, $week)->startOfWeek();
        $endOfWeek = Carbon::now()->setISODate($year, $week)->endOfWeek();
        
        return static::where('sales_target_id', $targetId)
                    ->whereBetween('progress_date', [$startOfWeek, $endOfWeek])
                    ->orderBy('progress_date')
                    ->get();
    }

    public static function getMonthlyProgress($targetId, $year, $month)
    {
        return static::where('sales_target_id', $targetId)
                    ->whereYear('progress_date', $year)
                    ->whereMonth('progress_date', $month)
                    ->orderBy('progress_date')
                    ->get();
    }

    public static function getTopPerformingDays($targetId, $limit = 5)
    {
        return static::where('sales_target_id', $targetId)
                    ->orderByDesc('daily_value')
                    ->limit($limit)
                    ->get();
    }

    public static function getProgressTrend($targetId, $days = 30)
    {
        $endDate = Carbon::today();
        $startDate = $endDate->copy()->subDays($days);
        
        return static::where('sales_target_id', $targetId)
                    ->whereBetween('progress_date', [$startDate, $endDate])
                    ->orderBy('progress_date')
                    ->get()
                    ->map(function ($progress) {
                        return [
                            'date' => $progress->progress_date->format('Y-m-d'),
                            'quantity' => $progress->cumulative_quantity,
                            'value' => $progress->cumulative_value,
                            'percentage' => $progress->progress_percentage
                        ];
                    });
    }

    // Methods
    public function getGrowthRate($period = 'daily')
    {
        $previousProgress = null;
        
        switch ($period) {
            case 'daily':
                $previousProgress = static::where('sales_target_id', $this->sales_target_id)
                                        ->where('progress_date', $this->progress_date->copy()->subDay())
                                        ->first();
                break;
            case 'weekly':
                $previousProgress = static::where('sales_target_id', $this->sales_target_id)
                                        ->where('progress_date', $this->progress_date->copy()->subWeek())
                                        ->first();
                break;
            case 'monthly':
                $previousProgress = static::where('sales_target_id', $this->sales_target_id)
                                        ->where('progress_date', $this->progress_date->copy()->subMonth())
                                        ->first();
                break;
        }
        
        if (!$previousProgress || $previousProgress->cumulative_value == 0) {
            return 0;
        }
        
        return (($this->cumulative_value - $previousProgress->cumulative_value) / $previousProgress->cumulative_value) * 100;
    }

    public function isWeekend()
    {
        return $this->progress_date->isWeekend();
    }

    public function isHoliday()
    {
        // يمكن إضافة منطق للتحقق من العطل الرسمية
        // هذا مثال بسيط
        $holidays = [
            '01-01', // رأس السنة
            '05-01', // عيد العمال
            // يمكن إضافة المزيد من العطل
        ];
        
        return in_array($this->progress_date->format('m-d'), $holidays);
    }
}
