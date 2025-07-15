@extends('layouts.tenant')

@section('title', 'تقارير أهداف البيع')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); border-radius: 20px; padding: 30px; margin-bottom: 30px; color: white;">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px;">
            <div>
                <h1 style="margin: 0; font-size: 28px; font-weight: 700;">
                    <i class="fas fa-chart-bar" style="margin-left: 10px;"></i>
                    تقارير أهداف البيع
                </h1>
                <p style="margin: 5px 0 0 0; opacity: 0.9; font-size: 16px;">
                    تحليلات شاملة وتقارير متقدمة لأداء الأهداف
                </p>
            </div>
            <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                <a href="{{ route('tenant.sales.targets.dashboard') }}" 
                   style="background: rgba(255,255,255,0.2); color: white; padding: 12px 20px; border-radius: 10px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 8px; backdrop-filter: blur(10px);">
                    <i class="fas fa-chart-line"></i>
                    لوحة التحكم
                </a>
                <a href="{{ route('tenant.sales.targets.index') }}" 
                   style="background: rgba(255,255,255,0.2); color: white; padding: 12px 20px; border-radius: 10px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 8px; backdrop-filter: blur(10px);">
                    <i class="fas fa-list"></i>
                    جميع الأهداف
                </a>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div style="background: white; border-radius: 15px; padding: 25px; margin-bottom: 25px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <form method="GET" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; align-items: end;">
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #374151;">السنة</label>
                <select name="year" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px;">
                    @for($y = date('Y'); $y >= date('Y') - 3; $y--)
                        <option value="{{ $y }}" {{ ($year ?? date('Y')) == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #374151;">نوع الهدف</label>
                <select name="target_type" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px;">
                    <option value="">جميع الأنواع</option>
                    <option value="product" {{ ($targetType ?? '') == 'product' ? 'selected' : '' }}>منتج</option>
                    <option value="vendor" {{ ($targetType ?? '') == 'vendor' ? 'selected' : '' }}>شركة</option>
                    <option value="sales_team" {{ ($targetType ?? '') == 'sales_team' ? 'selected' : '' }}>فريق مبيعات</option>
                    <option value="department" {{ ($targetType ?? '') == 'department' ? 'selected' : '' }}>قسم</option>
                    <option value="sales_rep" {{ ($targetType ?? '') == 'sales_rep' ? 'selected' : '' }}>مندوب مبيعات</option>
                </select>
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #374151;">نوع الفترة</label>
                <select name="period_type" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px;">
                    <option value="">جميع الفترات</option>
                    <option value="monthly" {{ ($periodType ?? '') == 'monthly' ? 'selected' : '' }}>شهرية</option>
                    <option value="quarterly" {{ ($periodType ?? '') == 'quarterly' ? 'selected' : '' }}>فصلية</option>
                    <option value="yearly" {{ ($periodType ?? '') == 'yearly' ? 'selected' : '' }}>سنوية</option>
                </select>
            </div>
            
            <div>
                <button type="submit" style="background: #8b5cf6; color: white; padding: 10px 20px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; width: 100%;">
                    <i class="fas fa-search"></i> تطبيق الفلاتر
                </button>
            </div>
        </form>
    </div>

    <!-- Summary Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px;">
        <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 25px; border-radius: 15px; text-align: center;">
            <div style="font-size: 36px; font-weight: 700; margin-bottom: 5px;">{{ $targets->count() }}</div>
            <div style="opacity: 0.9;">إجمالي الأهداف</div>
        </div>
        <div style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white; padding: 25px; border-radius: 15px; text-align: center;">
            <div style="font-size: 36px; font-weight: 700; margin-bottom: 5px;">{{ $targets->where('status', 'completed')->count() }}</div>
            <div style="opacity: 0.9;">الأهداف المكتملة</div>
        </div>
        <div style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; padding: 25px; border-radius: 15px; text-align: center;">
            <div style="font-size: 36px; font-weight: 700; margin-bottom: 5px;">{{ number_format($targets->avg('progress_percentage') ?? 0, 1) }}%</div>
            <div style="opacity: 0.9;">متوسط التقدم</div>
        </div>
        <div style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; padding: 25px; border-radius: 15px; text-align: center;">
            <div style="font-size: 36px; font-weight: 700; margin-bottom: 5px;">
                {{ $targets->filter(function($t) { return $t->end_date->isPast() && $t->progress_percentage < 100; })->count() }}
            </div>
            <div style="opacity: 0.9;">الأهداف المتأخرة</div>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 30px;">
        <!-- Performance by Type Chart -->
        <div style="background: white; border-radius: 15px; padding: 25px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
            <h3 style="color: #1f2937; margin: 0 0 20px 0; font-size: 20px; font-weight: 600;">
                <i class="fas fa-chart-pie" style="margin-left: 8px; color: #8b5cf6;"></i>
                الأداء حسب نوع الهدف
            </h3>
            <div id="performanceByTypeChart" style="height: 300px;"></div>
        </div>

        <!-- Performance by Period Chart -->
        <div style="background: white; border-radius: 15px; padding: 25px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
            <h3 style="color: #1f2937; margin: 0 0 20px 0; font-size: 20px; font-weight: 600;">
                <i class="fas fa-chart-bar" style="margin-left: 8px; color: #10b981;"></i>
                الأداء حسب الفترة
            </h3>
            <div id="performanceByPeriodChart" style="height: 300px;"></div>
        </div>
    </div>

    <!-- Detailed Reports -->
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 30px;">
        <!-- Performance Table -->
        <div style="background: white; border-radius: 15px; padding: 25px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
            <h3 style="color: #1f2937; margin: 0 0 20px 0; font-size: 20px; font-weight: 600;">
                <i class="fas fa-table" style="margin-left: 8px; color: #4299e1;"></i>
                تفاصيل الأداء
            </h3>
            
            @if($targets->count() > 0)
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead style="background: #f8fafc;">
                            <tr>
                                <th style="padding: 12px; text-align: right; font-weight: 600; color: #374151; border-bottom: 1px solid #e5e7eb;">الهدف</th>
                                <th style="padding: 12px; text-align: center; font-weight: 600; color: #374151; border-bottom: 1px solid #e5e7eb;">النوع</th>
                                <th style="padding: 12px; text-align: center; font-weight: 600; color: #374151; border-bottom: 1px solid #e5e7eb;">الفترة</th>
                                <th style="padding: 12px; text-align: center; font-weight: 600; color: #374151; border-bottom: 1px solid #e5e7eb;">التقدم</th>
                                <th style="padding: 12px; text-align: center; font-weight: 600; color: #374151; border-bottom: 1px solid #e5e7eb;">الحالة</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($targets->take(10) as $target)
                                <tr style="border-bottom: 1px solid #f3f4f6;">
                                    <td style="padding: 12px;">
                                        <div style="font-weight: 600; color: #1f2937;">{{ $target->title }}</div>
                                        <div style="font-size: 12px; color: #6b7280;">{{ $target->target_entity_name }}</div>
                                    </td>
                                    <td style="padding: 12px; text-align: center;">
                                        <span style="background: #e0f2fe; color: #0277bd; padding: 4px 8px; border-radius: 6px; font-size: 12px; font-weight: 600;">
                                            @switch($target->target_type)
                                                @case('product') منتج @break
                                                @case('vendor') شركة @break
                                                @case('sales_team') فريق @break
                                                @case('department') قسم @break
                                                @case('sales_rep') مندوب @break
                                            @endswitch
                                        </span>
                                    </td>
                                    <td style="padding: 12px; text-align: center;">
                                        <span style="font-size: 12px; color: #6b7280;">
                                            @switch($target->period_type)
                                                @case('monthly') شهري @break
                                                @case('quarterly') فصلي @break
                                                @case('yearly') سنوي @break
                                            @endswitch
                                        </span>
                                    </td>
                                    <td style="padding: 12px; text-align: center;">
                                        <div style="margin-bottom: 5px;">
                                            <span style="font-weight: 600; color: #374151;">{{ $target->progress_percentage }}%</span>
                                        </div>
                                        <div style="background: #e5e7eb; border-radius: 10px; height: 6px; overflow: hidden;">
                                            <div style="background: {{ $target->progress_percentage >= 100 ? '#10b981' : ($target->progress_percentage >= 80 ? '#f59e0b' : '#3b82f6') }}; height: 100%; width: {{ min(100, $target->progress_percentage) }}%;"></div>
                                        </div>
                                    </td>
                                    <td style="padding: 12px; text-align: center;">
                                        <span style="background: {{ $target->status === 'completed' ? '#dcfce7' : ($target->status === 'active' ? '#dbeafe' : '#fef3c7') }}; 
                                                     color: {{ $target->status === 'completed' ? '#166534' : ($target->status === 'active' ? '#1e40af' : '#d97706') }}; 
                                                     padding: 4px 8px; border-radius: 6px; font-size: 12px; font-weight: 600;">
                                            {{ $target->status_text }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                @if($targets->count() > 10)
                    <div style="text-align: center; margin-top: 20px;">
                        <a href="{{ route('tenant.sales.targets.index') }}" 
                           style="background: #8b5cf6; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: 600;">
                            عرض جميع الأهداف ({{ $targets->count() }})
                        </a>
                    </div>
                @endif
            @else
                <div style="text-align: center; padding: 40px; color: #6b7280;">
                    <i class="fas fa-chart-bar" style="font-size: 48px; margin-bottom: 15px; opacity: 0.5;"></i>
                    <p style="margin: 0;">لا توجد أهداف للفترة المحددة</p>
                </div>
            @endif
        </div>

        <!-- Statistics Sidebar -->
        <div>
            <!-- Top Performers -->
            <div style="background: white; border-radius: 15px; padding: 25px; margin-bottom: 25px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                <h3 style="color: #1f2937; margin: 0 0 20px 0; font-size: 18px; font-weight: 600;">
                    <i class="fas fa-trophy" style="margin-left: 8px; color: #f59e0b;"></i>
                    أفضل الأداءات
                </h3>
                
                @php
                    $topPerformers = $targets->where('target_type', 'sales_rep')
                                           ->sortByDesc('progress_percentage')
                                           ->take(5);
                @endphp
                
                @if($topPerformers->count() > 0)
                    @foreach($topPerformers as $performer)
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 10px 0; border-bottom: 1px solid #f3f4f6;">
                            <div>
                                <div style="font-weight: 600; color: #374151; font-size: 14px;">{{ $performer->target_entity_name }}</div>
                                <div style="font-size: 12px; color: #6b7280;">{{ $performer->title }}</div>
                            </div>
                            <div style="text-align: center;">
                                <div style="font-weight: 700; color: #10b981;">{{ $performer->progress_percentage }}%</div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div style="text-align: center; padding: 20px; color: #6b7280;">
                        <i class="fas fa-user-tie" style="font-size: 32px; margin-bottom: 10px; opacity: 0.5;"></i>
                        <p style="margin: 0; font-size: 14px;">لا توجد أهداف مندوبين</p>
                    </div>
                @endif
            </div>

            <!-- Status Distribution -->
            <div style="background: white; border-radius: 15px; padding: 25px; margin-bottom: 25px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                <h3 style="color: #1f2937; margin: 0 0 20px 0; font-size: 18px; font-weight: 600;">
                    <i class="fas fa-chart-pie" style="margin-left: 8px; color: #8b5cf6;"></i>
                    توزيع الحالات
                </h3>
                <div id="statusDistributionChart" style="height: 200px;"></div>
            </div>

            <!-- Export Options -->
            <div style="background: white; border-radius: 15px; padding: 25px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                <h3 style="color: #1f2937; margin: 0 0 20px 0; font-size: 18px; font-weight: 600;">
                    <i class="fas fa-download" style="margin-left: 8px; color: #10b981;"></i>
                    تصدير التقارير
                </h3>
                
                <div style="space-y: 10px;">
                    <button onclick="exportReport('pdf')" 
                            style="display: block; width: 100%; background: #ef4444; color: white; padding: 12px 15px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; margin-bottom: 10px;">
                        <i class="fas fa-file-pdf"></i> تصدير PDF
                    </button>
                    
                    <button onclick="exportReport('excel')" 
                            style="display: block; width: 100%; background: #10b981; color: white; padding: 12px 15px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; margin-bottom: 10px;">
                        <i class="fas fa-file-excel"></i> تصدير Excel
                    </button>
                    
                    <button onclick="printReport()" 
                            style="display: block; width: 100%; background: #6b7280; color: white; padding: 12px 15px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                        <i class="fas fa-print"></i> طباعة
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart Scripts -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Performance by Type Chart
    const typeData = @json($reportData['by_type'] ?? []);
    const typeLabels = Object.keys(typeData);
    const typeValues = Object.values(typeData).map(item => item.avg_progress);
    
    const typeOptions = {
        series: typeValues,
        chart: {
            type: 'donut',
            height: 300,
            fontFamily: 'Cairo, sans-serif'
        },
        labels: typeLabels.map(type => {
            const typeNames = {
                'product': 'منتج',
                'vendor': 'شركة',
                'sales_team': 'فريق',
                'department': 'قسم',
                'sales_rep': 'مندوب'
            };
            return typeNames[type] || type;
        }),
        colors: ['#8b5cf6', '#10b981', '#f59e0b', '#ef4444', '#3b82f6'],
        legend: {
            position: 'bottom',
            fontFamily: 'Cairo, sans-serif'
        }
    };
    
    const typeChart = new ApexCharts(document.querySelector("#performanceByTypeChart"), typeOptions);
    typeChart.render();
    
    // Performance by Period Chart
    const periodData = @json($reportData['by_period'] ?? []);
    const periodLabels = Object.keys(periodData);
    const periodValues = Object.values(periodData).map(item => item.avg_progress);
    
    const periodOptions = {
        series: [{
            name: 'متوسط التقدم',
            data: periodValues
        }],
        chart: {
            type: 'bar',
            height: 300,
            fontFamily: 'Cairo, sans-serif'
        },
        xaxis: {
            categories: periodLabels.map(period => {
                const periodNames = {
                    'monthly': 'شهرية',
                    'quarterly': 'فصلية',
                    'yearly': 'سنوية'
                };
                return periodNames[period] || period;
            })
        },
        colors: ['#10b981'],
        dataLabels: {
            enabled: true,
            formatter: function (val) {
                return val.toFixed(1) + "%"
            }
        }
    };
    
    const periodChart = new ApexCharts(document.querySelector("#performanceByPeriodChart"), periodOptions);
    periodChart.render();
    
    // Status Distribution Chart
    const statusData = @json($reportData['by_status'] ?? []);
    const statusLabels = Object.keys(statusData);
    const statusValues = Object.values(statusData);
    
    const statusOptions = {
        series: statusValues,
        chart: {
            type: 'pie',
            height: 200,
            fontFamily: 'Cairo, sans-serif'
        },
        labels: statusLabels.map(status => {
            const statusNames = {
                'active': 'نشط',
                'completed': 'مكتمل',
                'paused': 'متوقف',
                'cancelled': 'ملغي'
            };
            return statusNames[status] || status;
        }),
        colors: ['#3b82f6', '#10b981', '#f59e0b', '#ef4444'],
        legend: {
            show: false
        }
    };
    
    const statusChart = new ApexCharts(document.querySelector("#statusDistributionChart"), statusOptions);
    statusChart.render();
});

// Export functions
function exportReport(format) {
    alert(`سيتم تصدير التقرير بصيغة ${format.toUpperCase()}\n\nيتضمن التقرير:\n• إحصائيات شاملة\n• رسوم بيانية\n• تفاصيل الأهداف\n• تحليلات الأداء`);
}

function printReport() {
    window.print();
}
</script>
@endsection
