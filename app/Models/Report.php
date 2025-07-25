<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Report extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'type',
        'category',
        'query_builder',
        'filters',
        'columns',
        'settings',
        'is_active',
        'is_public',
        'created_by',
        'tenant_id'
    ];

    protected $casts = [
        'query_builder' => 'array',
        'filters' => 'array',
        'columns' => 'array',
        'settings' => 'array',
        'is_active' => 'boolean',
        'is_public' => 'boolean',
    ];

    // Report Categories
    const CATEGORY_SALES = 'sales';
    const CATEGORY_FINANCIAL = 'financial';
    const CATEGORY_INVENTORY = 'inventory';
    const CATEGORY_CUSTOMERS = 'customers';
    const CATEGORY_PRODUCTS = 'products';
    const CATEGORY_EMPLOYEES = 'employees';

    // Report Types
    const TYPE_SUMMARY = 'summary';
    const TYPE_DETAILED = 'detailed';
    const TYPE_ANALYTICAL = 'analytical';
    const TYPE_COMPARATIVE = 'comparative';
    const TYPE_ALERT = 'alert';

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function executions()
    {
        return $this->hasMany(ReportExecution::class);
    }

    // public function schedules()
    // {
    //     return $this->hasMany(ReportSchedule::class);
    // }

    public function getFormattedFiltersAttribute()
    {
        return collect($this->filters)->map(function ($filter) {
            return [
                'field' => $filter['field'],
                'operator' => $filter['operator'],
                'value' => $filter['value'],
                'label' => $filter['label'] ?? $filter['field']
            ];
        });
    }

    public function getFormattedColumnsAttribute()
    {
        return collect($this->columns)->map(function ($column) {
            return [
                'field' => $column['field'],
                'label' => $column['label'] ?? $column['field'],
                'type' => $column['type'] ?? 'text',
                'format' => $column['format'] ?? null,
                'aggregation' => $column['aggregation'] ?? null
            ];
        });
    }

    public static function getCategories()
    {
        return [
            self::CATEGORY_SALES => 'تقارير المبيعات',
            self::CATEGORY_FINANCIAL => 'التقارير المالية',
            self::CATEGORY_INVENTORY => 'تقارير المخزون',
            self::CATEGORY_CUSTOMERS => 'تقارير العملاء',
            self::CATEGORY_PRODUCTS => 'تقارير المنتجات',
            self::CATEGORY_EMPLOYEES => 'تقارير الموظفين',
        ];
    }

    public static function getTypes()
    {
        return [
            self::TYPE_SUMMARY => 'تقرير ملخص',
            self::TYPE_DETAILED => 'تقرير مفصل',
            self::TYPE_ANALYTICAL => 'تقرير تحليلي',
            self::TYPE_COMPARATIVE => 'تقرير مقارن',
            self::TYPE_ALERT => 'تقرير تنبيهات',
        ];
    }
}
