@extends('layouts.modern')

@section('title', 'لوحة تحكم أهداف البيع')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); border-radius: 20px; padding: 30px; margin-bottom: 30px; color: white;">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px;">
            <div>
                <h1 style="margin: 0; font-size: 28px; font-weight: 700;">
                    <i class="fas fa-chart-line" style="margin-left: 10px;"></i>
                    لوحة تحكم أهداف البيع
                </h1>
                <p style="margin: 5px 0 0 0; opacity: 0.9; font-size: 16px;">
                    نظرة شاملة على أداء الأهداف والتقدم المحرز
                </p>
            </div>
            <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                <a href="{{ route('tenant.sales.targets.create') }}" 
                   style="background: rgba(255,255,255,0.2); color: white; padding: 12px 20px; border-radius: 10px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 8px; backdrop-filter: blur(10px);">
                    <i class="fas fa-plus"></i>
                    هدف جديد
                </a>
                <a href="{{ route('tenant.sales.targets.index') }}" 
                   style="background: rgba(255,255,255,0.2); color: white; padding: 12px 20px; border-radius: 10px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 8px; backdrop-filter: blur(10px);">
                    <i class="fas fa-list"></i>
                    جميع الأهداف
                </a>
                <a href="{{ route('tenant.sales.targets.reports') }}" 
                   style="background: rgba(255,255,255,0.2); color: white; padding: 12px 20px; border-radius: 10px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 8px; backdrop-filter: blur(10px);">
                    <i class="fas fa-chart-bar"></i>
                    التقارير
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics Overview -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px;">
        <div style="background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%); color: white; padding: 25px; border-radius: 15px; text-align: center;">
            <div style="font-size: 36px; font-weight: 700; margin-bottom: 5px;">{{ $stats['total'] }}</div>
            <div style="opacity: 0.9;">إجمالي الأهداف</div>
        </div>
        <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 25px; border-radius: 15px; text-align: center;">
            <div style="font-size: 36px; font-weight: 700; margin-bottom: 5px;">{{ $stats['active'] }}</div>
            <div style="opacity: 0.9;">الأهداف النشطة</div>
        </div>
        <div style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; padding: 25px; border-radius: 15px; text-align: center;">
            <div style="font-size: 36px; font-weight: 700; margin-bottom: 5px;">{{ $stats['completed'] }}</div>
            <div style="opacity: 0.9;">الأهداف المكتملة</div>
        </div>
        <div style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; padding: 25px; border-radius: 15px; text-align: center;">
            <div style="font-size: 36px; font-weight: 700; margin-bottom: 5px;">{{ $stats['overdue'] }}</div>
            <div style="opacity: 0.9;">الأهداف المتأخرة</div>
        </div>
        <div style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); color: white; padding: 25px; border-radius: 15px; text-align: center;">
            <div style="font-size: 36px; font-weight: 700; margin-bottom: 5px;">{{ number_format($performanceSummary['avg_progress'], 1) }}%</div>
            <div style="opacity: 0.9;">متوسط التقدم</div>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 30px;">
        <!-- Main Content -->
        <div>
            <!-- Active Targets -->
            <div style="background: white; border-radius: 15px; padding: 25px; margin-bottom: 25px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                <h3 style="color: #1f2937; margin: 0 0 20px 0; font-size: 20px; font-weight: 600;">
                    <i class="fas fa-bullseye" style="margin-left: 8px; color: #4299e1;"></i>
                    الأهداف النشطة الحالية
                </h3>
                
                @if($activeTargets->count() > 0)
                    <div style="space-y: 20px;">
                        @foreach($activeTargets as $target)
                            <div style="border: 1px solid #e5e7eb; border-radius: 12px; padding: 20px; transition: all 0.3s ease; hover: box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 15px;">
                                    <div>
                                        <h4 style="margin: 0 0 5px 0; color: #1f2937; font-size: 16px; font-weight: 600;">
                                            <a href="{{ route('tenant.sales.targets.show', $target) }}" style="color: #1f2937; text-decoration: none;">
                                                {{ $target->title }}
                                            </a>
                                        </h4>
                                        <p style="margin: 0; color: #6b7280; font-size: 14px;">{{ $target->target_entity_name }}</p>
                                    </div>
                                    <div style="text-align: center;">
                                        <div style="font-size: 24px; font-weight: 700; color: #059669; margin-bottom: 5px;">
                                            {{ $target->progress_percentage }}%
                                        </div>
                                        <div style="font-size: 12px; color: #6b7280;">{{ $target->remaining_days }} يوم متبقي</div>
                                    </div>
                                </div>
                                
                                <!-- Progress Bar -->
                                <div style="background: #e5e7eb; border-radius: 10px; height: 8px; margin-bottom: 15px; overflow: hidden;">
                                    <div style="background: {{ $target->progress_percentage >= 100 ? '#10b981' : ($target->progress_percentage >= 80 ? '#f59e0b' : '#3b82f6') }}; height: 100%; width: {{ min(100, $target->progress_percentage) }}%; transition: width 0.3s ease;"></div>
                                </div>
                                
                                <!-- Target Details -->
                                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 15px; font-size: 14px;">
                                    @if($target->measurement_type === 'quantity' || $target->measurement_type === 'both')
                                    <div>
                                        <div style="color: #6b7280; margin-bottom: 3px;">الكمية</div>
                                        <div style="color: #374151; font-weight: 600;">
                                            {{ number_format($target->achieved_quantity) }} / {{ number_format($target->target_quantity) }} {{ $target->unit }}
                                        </div>
                                    </div>
                                    @endif
                                    
                                    @if($target->measurement_type === 'value' || $target->measurement_type === 'both')
                                    <div>
                                        <div style="color: #6b7280; margin-bottom: 3px;">القيمة</div>
                                        <div style="color: #374151; font-weight: 600;">
                                            {{ number_format($target->achieved_value) }} / {{ number_format($target->target_value) }} {{ $target->currency }}
                                        </div>
                                    </div>
                                    @endif
                                    
                                    <div>
                                        <div style="color: #6b7280; margin-bottom: 3px;">الفترة</div>
                                        <div style="color: #374151; font-weight: 600;">
                                            {{ $target->start_date->format('m/d') }} - {{ $target->end_date->format('m/d') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div style="text-align: center; padding: 40px; color: #6b7280;">
                        <i class="fas fa-bullseye" style="font-size: 48px; margin-bottom: 15px; opacity: 0.5;"></i>
                        <h4 style="margin: 0 0 10px 0; color: #374151;">لا توجد أهداف نشطة</h4>
                        <p style="margin: 0 0 20px 0;">لم يتم إنشاء أي أهداف نشطة حالياً</p>
                        <a href="{{ route('tenant.sales.targets.create') }}" 
                           style="background: #4299e1; color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600;">
                            <i class="fas fa-plus"></i> إنشاء هدف جديد
                        </a>
                    </div>
                @endif
            </div>

            <!-- Performance Chart -->
            <div style="background: white; border-radius: 15px; padding: 25px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                <h3 style="color: #1f2937; margin: 0 0 20px 0; font-size: 20px; font-weight: 600;">
                    <i class="fas fa-chart-area" style="margin-left: 8px; color: #10b981;"></i>
                    أداء الأهداف الشهرية
                </h3>
                <div id="performanceChart" style="height: 300px;"></div>
            </div>
        </div>

        <!-- Sidebar -->
        <div>
            <!-- Monthly Summary -->
            <div style="background: white; border-radius: 15px; padding: 25px; margin-bottom: 25px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                <h3 style="color: #1f2937; margin: 0 0 20px 0; font-size: 18px; font-weight: 600;">
                    <i class="fas fa-calendar-alt" style="margin-left: 8px; color: #f59e0b;"></i>
                    ملخص الشهر الحالي
                </h3>
                
                <div style="space-y: 15px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                        <span style="color: #6b7280; font-size: 14px;">الأهداف الشهرية</span>
                        <span style="font-weight: 600; color: #374151;">{{ $performanceSummary['monthly_targets'] }}</span>
                    </div>
                    
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                        <span style="color: #6b7280; font-size: 14px;">المكتملة</span>
                        <span style="font-weight: 600; color: #10b981;">{{ $performanceSummary['monthly_completed'] }}</span>
                    </div>
                    
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                        <span style="color: #6b7280; font-size: 14px;">معدل الإنجاز</span>
                        <span style="font-weight: 600; color: #374151;">
                            {{ $performanceSummary['monthly_targets'] > 0 ? number_format(($performanceSummary['monthly_completed'] / $performanceSummary['monthly_targets']) * 100, 1) : 0 }}%
                        </span>
                    </div>
                </div>
                
                <!-- Progress Ring -->
                <div style="text-align: center; margin-top: 20px;">
                    <div style="position: relative; display: inline-block;">
                        <svg width="120" height="120" style="transform: rotate(-90deg);">
                            <circle cx="60" cy="60" r="50" fill="none" stroke="#e5e7eb" stroke-width="8"/>
                            <circle cx="60" cy="60" r="50" fill="none" stroke="#10b981" stroke-width="8" 
                                    stroke-dasharray="{{ 2 * 3.14159 * 50 }}" 
                                    stroke-dashoffset="{{ 2 * 3.14159 * 50 * (1 - ($performanceSummary['monthly_targets'] > 0 ? ($performanceSummary['monthly_completed'] / $performanceSummary['monthly_targets']) : 0)) }}"
                                    stroke-linecap="round"/>
                        </svg>
                        <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); font-size: 18px; font-weight: 700; color: #374151;">
                            {{ $performanceSummary['monthly_targets'] > 0 ? number_format(($performanceSummary['monthly_completed'] / $performanceSummary['monthly_targets']) * 100, 0) : 0 }}%
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Achievements -->
            <div style="background: white; border-radius: 15px; padding: 25px; margin-bottom: 25px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                <h3 style="color: #1f2937; margin: 0 0 20px 0; font-size: 18px; font-weight: 600;">
                    <i class="fas fa-trophy" style="margin-left: 8px; color: #f59e0b;"></i>
                    الإنجازات الأخيرة
                </h3>
                
                @if($recentAchievements->count() > 0)
                    <div style="space-y: 15px;">
                        @foreach($recentAchievements as $achievement)
                            <div style="border-right: 3px solid #10b981; padding-right: 15px; margin-bottom: 15px;">
                                <div style="font-weight: 600; color: #374151; margin-bottom: 3px; font-size: 14px;">
                                    {{ $achievement->title }}
                                </div>
                                <div style="color: #6b7280; font-size: 12px; margin-bottom: 5px;">
                                    {{ $achievement->target_entity_name }}
                                </div>
                                <div style="color: #10b981; font-size: 12px; font-weight: 600;">
                                    مكتمل {{ $achievement->updated_at->diffForHumans() }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div style="text-align: center; padding: 20px; color: #6b7280;">
                        <i class="fas fa-trophy" style="font-size: 32px; margin-bottom: 10px; opacity: 0.5;"></i>
                        <p style="margin: 0; font-size: 14px;">لا توجد إنجازات حديثة</p>
                    </div>
                @endif
            </div>

            <!-- Quick Actions -->
            <div style="background: white; border-radius: 15px; padding: 25px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                <h3 style="color: #1f2937; margin: 0 0 20px 0; font-size: 18px; font-weight: 600;">
                    <i class="fas fa-bolt" style="margin-left: 8px; color: #8b5cf6;"></i>
                    إجراءات سريعة
                </h3>
                
                <div style="space-y: 10px;">
                    <a href="{{ route('tenant.sales.targets.create') }}" 
                       style="display: block; background: #f3f4f6; color: #374151; padding: 12px 15px; border-radius: 8px; text-decoration: none; font-weight: 600; margin-bottom: 10px;">
                        <i class="fas fa-plus" style="margin-left: 8px; color: #10b981;"></i>
                        إنشاء هدف جديد
                    </a>
                    
                    <a href="{{ route('tenant.sales.targets.reports') }}" 
                       style="display: block; background: #f3f4f6; color: #374151; padding: 12px 15px; border-radius: 8px; text-decoration: none; font-weight: 600; margin-bottom: 10px;">
                        <i class="fas fa-chart-bar" style="margin-left: 8px; color: #4299e1;"></i>
                        عرض التقارير
                    </a>
                    
                    <a href="{{ route('tenant.sales.targets.index', ['status' => 'active']) }}" 
                       style="display: block; background: #f3f4f6; color: #374151; padding: 12px 15px; border-radius: 8px; text-decoration: none; font-weight: 600; margin-bottom: 10px;">
                        <i class="fas fa-list" style="margin-left: 8px; color: #f59e0b;"></i>
                        الأهداف النشطة
                    </a>
                    
                    <a href="{{ route('tenant.sales.targets.index', ['status' => 'overdue']) }}" 
                       style="display: block; background: #f3f4f6; color: #374151; padding: 12px 15px; border-radius: 8px; text-decoration: none; font-weight: 600;">
                        <i class="fas fa-exclamation-triangle" style="margin-left: 8px; color: #ef4444;"></i>
                        الأهداف المتأخرة
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart Script -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Performance Chart
    const options = {
        series: [{
            name: 'الأهداف المكتملة',
            data: [12, 15, 18, 22, 25, 28]
        }, {
            name: 'الأهداف النشطة',
            data: [8, 12, 14, 16, 18, 20]
        }],
        chart: {
            type: 'area',
            height: 300,
            fontFamily: 'Cairo, sans-serif',
            toolbar: {
                show: false
            }
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'smooth',
            width: 2
        },
        xaxis: {
            categories: ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو'],
            labels: {
                style: {
                    fontFamily: 'Cairo, sans-serif'
                }
            }
        },
        yaxis: {
            title: {
                text: 'عدد الأهداف',
                style: {
                    fontFamily: 'Cairo, sans-serif'
                }
            }
        },
        colors: ['#10b981', '#4299e1'],
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.7,
                opacityTo: 0.3
            }
        },
        grid: {
            borderColor: '#e5e7eb'
        },
        legend: {
            fontFamily: 'Cairo, sans-serif'
        }
    };

    const chart = new ApexCharts(document.querySelector("#performanceChart"), options);
    chart.render();
});
</script>
@endsection
