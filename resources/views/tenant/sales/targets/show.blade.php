@extends('layouts.modern')

@section('title', 'تفاصيل الهدف - ' . $target->title)

@push('styles')
<link rel="stylesheet" href="{{ asset('css/sales-targets.css') }}">
@endpush



@push('styles')
<style>
/* Responsive two-column layout for show page */
.targets-two-col {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 30px;
}
@media (max-width: 1200px) {
    .targets-two-col {
        grid-template-columns: 1fr;
        gap: 20px;
    }
}
/* Ensure cards expand nicely on small screens */
.targets-two-col > div {
    min-width: 0;
}
/* Make any wide tables scroll horizontally on small screens */
.responsive-table-wrapper {
    overflow-x: auto;
}
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 20px; padding: 30px; margin-bottom: 30px; color: white;">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px;">
            <div>
                <h1 style="margin: 0; font-size: 28px; font-weight: 700;">
                    <i class="fas fa-bullseye" style="margin-left: 10px;"></i>
                    {{ $target->title }}
                </h1>
                <p style="margin: 5px 0 0 0; opacity: 0.9; font-size: 16px;">
                    {{ $target->target_entity_name }} -
                    @switch($target->target_type)
                        @case('product') منتج @break
                        @case('vendor') شركة @break
                        @case('sales_team') فريق مبيعات @break
                        @case('department') قسم @break
                        @case('sales_rep') مندوب مبيعات @break
                    @endswitch
                </p>
            </div>
            <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                <a href="{{ route('tenant.sales.targets.edit', $target) }}"
                   style="background: rgba(255,255,255,0.2); color: white; padding: 12px 20px; border-radius: 10px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 8px; backdrop-filter: blur(10px);">
                    <i class="fas fa-edit"></i>
                    تعديل
                </a>
                <a href="{{ route('tenant.sales.targets.index') }}"
                   style="background: rgba(255,255,255,0.2); color: white; padding: 12px 20px; border-radius: 10px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 8px; backdrop-filter: blur(10px);">
                    <i class="fas fa-arrow-right"></i>
                    العودة للقائمة
                </a>
            </div>
        </div>
    </div>

    <!-- Progress Overview -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px;">
        <!-- Progress Percentage -->
        <div style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); color: white; padding: 25px; border-radius: 15px; text-align: center;">
            <div style="font-size: 48px; font-weight: 700; margin-bottom: 10px;">{{ $target->progress_percentage }}%</div>
            <div style="opacity: 0.9; font-size: 16px;">نسبة التقدم</div>
            <div class="progress-bar" style="margin-top: 15px;">
                <div class="progress-fill" style="--w: {{ min(100, $target->progress_percentage) }}%;"></div>
            </div>
        </div>

        <!-- Target vs Achieved -->
        @if($target->measurement_type === 'quantity' || $target->measurement_type === 'both')
        <div style="background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%); color: white; padding: 25px; border-radius: 15px; text-align: center;">
            <div style="font-size: 24px; font-weight: 700; margin-bottom: 5px;">{{ number_format($target->achieved_quantity) }}</div>
            <div style="opacity: 0.9; margin-bottom: 10px;">من {{ number_format($target->target_quantity) }} {{ $target->unit }}</div>
            <div style="opacity: 0.8; font-size: 14px;">الكمية المحققة</div>
        </div>
        @endif

        @if($target->measurement_type === 'value' || $target->measurement_type === 'both')
        <div style="background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%); color: white; padding: 25px; border-radius: 15px; text-align: center;">
            <div style="font-size: 24px; font-weight: 700; margin-bottom: 5px;">{{ number_format($target->achieved_value) }}</div>
            <div style="opacity: 0.9; margin-bottom: 10px;">من {{ number_format($target->target_value) }} {{ $target->currency }}</div>
            <div style="opacity: 0.8; font-size: 14px;">القيمة المحققة</div>
        </div>
        @endif

        <!-- Time Progress -->
        <div style="background: linear-gradient(135deg, #9f7aea 0%, #805ad5 100%); color: white; padding: 25px; border-radius: 15px; text-align: center;">
            <div style="font-size: 24px; font-weight: 700; margin-bottom: 5px;">{{ $target->remaining_days }}</div>
            <div style="opacity: 0.9; margin-bottom: 10px;">من {{ $target->total_days }} يوم</div>
            <div style="opacity: 0.8; font-size: 14px;">الأيام المتبقية</div>
        </div>
    </div>

    <div class="targets-two-col">
        <!-- Main Content -->
        <div>
            <!-- Progress Chart -->
            <div style="background: white; border-radius: 15px; padding: 25px; margin-bottom: 25px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                <h3 style="color: #1f2937; margin: 0 0 20px 0; font-size: 20px; font-weight: 600;">
                    <i class="fas fa-chart-line" style="margin-left: 8px; color: #4299e1;"></i>
                    تطور التقدم
                </h3>
                <div id="progressChart" style="height: 300px;"></div>
            </div>

            <!-- Recent Progress -->
            <div style="background: white; border-radius: 15px; padding: 25px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                <h3 style="color: #1f2937; margin: 0 0 20px 0; font-size: 20px; font-weight: 600;">
                    <i class="fas fa-history" style="margin-left: 8px; color: #10b981;"></i>
                    التقدم الأخير
                </h3>

                @if($recentProgress->count() > 0)
                    <div class="responsive-table-wrapper">
                        <table style="width: 100%; border-collapse: collapse;">
                            <thead style="background: #f8fafc;">
                                <tr>
                                    <th style="padding: 12px; text-align: right; font-weight: 600; color: #374151; border-bottom: 1px solid #e5e7eb;">التاريخ</th>
                                    <th style="padding: 12px; text-align: center; font-weight: 600; color: #374151; border-bottom: 1px solid #e5e7eb;">الكمية اليومية</th>
                                    <th style="padding: 12px; text-align: center; font-weight: 600; color: #374151; border-bottom: 1px solid #e5e7eb;">القيمة اليومية</th>
                                    <th style="padding: 12px; text-align: center; font-weight: 600; color: #374151; border-bottom: 1px solid #e5e7eb;">التقدم التراكمي</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentProgress as $progress)
                                    <tr style="border-bottom: 1px solid #f3f4f6;">
                                        <td style="padding: 12px; color: #374151;">
                                            {{ $progress->progress_date->format('Y-m-d') }}
                                        </td>
                                        <td style="padding: 12px; text-align: center; color: #059669;">
                                            {{ number_format($progress->daily_quantity) }} {{ $target->unit }}
                                        </td>
                                        <td style="padding: 12px; text-align: center; color: #059669;">
                                            {{ number_format($progress->daily_value) }} {{ $target->currency }}
                                        </td>
                                        <td style="padding: 12px; text-align: center;">
                                            <span style="background: #e0f2fe; color: #0277bd; padding: 4px 8px; border-radius: 6px; font-size: 12px; font-weight: 600;">
                                                {{ $progress->progress_percentage }}%
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div style="text-align: center; padding: 40px; color: #6b7280;">
                        <i class="fas fa-chart-line" style="font-size: 48px; margin-bottom: 15px; opacity: 0.5;"></i>
                        <p style="margin: 0;">لا يوجد تقدم مسجل بعد</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div>
            <!-- Target Details -->
            <div style="background: white; border-radius: 15px; padding: 25px; margin-bottom: 25px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                <h3 style="color: #1f2937; margin: 0 0 20px 0; font-size: 18px; font-weight: 600;">
                    <i class="fas fa-info-circle" style="margin-left: 8px; color: #6366f1;"></i>
                    تفاصيل الهدف
                </h3>

                <div style="display:flex; flex-direction:column; gap:15px;">
                    <div style="margin-bottom: 15px;">
                        <div style="font-size: 12px; color: #6b7280; margin-bottom: 3px;">نوع الفترة</div>
                        <div style="font-weight: 600; color: #374151;">
                            @switch($target->period_type)
                                @case('monthly') شهرية @break
                                @case('quarterly') فصلية @break
                                @case('yearly') سنوية @break
                            @endswitch
                        </div>
                    </div>

                    <div style="margin-bottom: 15px;">
                        <div style="font-size: 12px; color: #6b7280; margin-bottom: 3px;">فترة الهدف</div>
                        <div style="font-weight: 600; color: #374151;">
                            {{ $target->start_date->format('Y-m-d') }} - {{ $target->end_date->format('Y-m-d') }}
                        </div>
                    </div>

                    <div style="margin-bottom: 15px;">
                        <div style="font-size: 12px; color: #6b7280; margin-bottom: 3px;">الحالة</div>
                        <span style="background: {{ $target->status_color === 'success' ? '#dcfce7' : ($target->status_color === 'danger' ? '#fef2f2' : ($target->status_color === 'warning' ? '#fef3c7' : '#f3f4f6')) }};
                                     color: {{ $target->status_color === 'success' ? '#166534' : ($target->status_color === 'danger' ? '#dc2626' : ($target->status_color === 'warning' ? '#d97706' : '#374151')) }};
                                     padding: 4px 8px; border-radius: 6px; font-size: 12px; font-weight: 600;">
                            {{ $target->status_text }}
                        </span>
                    </div>

                    <div style="margin-bottom: 15px;">
                        <div style="font-size: 12px; color: #6b7280; margin-bottom: 3px;">آخر تحديث</div>
                        <div style="font-weight: 600; color: #374151;">
                            {{ $target->last_updated_at ? $target->last_updated_at->diffForHumans() : 'لم يتم التحديث بعد' }}
                        </div>
                    </div>

                    @if($target->description)
                    <div style="margin-bottom: 15px;">
                        <div style="font-size: 12px; color: #6b7280; margin-bottom: 3px;">الوصف</div>
                        <div style="color: #374151; line-height: 1.5;">{{ $target->description }}</div>
                    </div>
                    @endif

                    @if($target->notes)
                    <div style="margin-bottom: 15px;">
                        <div style="font-size: 12px; color: #6b7280; margin-bottom: 3px;">ملاحظات</div>
                        <div style="color: #374151; line-height: 1.5;">{{ $target->notes }}</div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Manual Progress Update -->
            @if($target->status === 'active')
            <div style="background: white; border-radius: 15px; padding: 25px; margin-bottom: 25px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                <h3 style="color: #1f2937; margin: 0 0 20px 0; font-size: 18px; font-weight: 600;">
                    <i class="fas fa-plus-circle" style="margin-left: 8px; color: #10b981;"></i>
                    تحديث التقدم
                </h3>

                <form method="POST" action="{{ route('tenant.sales.targets.update-progress', $target) }}">
                    @csrf

                    @if($target->measurement_type === 'quantity' || $target->measurement_type === 'both')
                    <div style="margin-bottom: 15px;">
                        <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #374151; font-size: 14px;">الكمية</label>
                        <input type="number" name="quantity" step="0.01" min="0"
                               style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px;"
                               placeholder="0.00">
                    </div>
                    @endif

                    @if($target->measurement_type === 'value' || $target->measurement_type === 'both')
                    <div style="margin-bottom: 15px;">
                        <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #374151; font-size: 14px;">القيمة</label>
                        <input type="number" name="value" step="0.01" min="0"
                               style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px;"
                               placeholder="0.00">
                    </div>
                    @endif

                    <div style="margin-bottom: 15px;">
                        <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #374151; font-size: 14px;">ملاحظات</label>
                        <textarea name="notes" rows="2"
                                  style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; resize: vertical;"
                                  placeholder="ملاحظات حول التحديث..."></textarea>
                    </div>

                    <button type="submit"
                            style="background: #10b981; color: white; padding: 10px 20px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; width: 100%;">
                        <i class="fas fa-save"></i> تحديث التقدم
                    </button>
                </form>
            </div>
            @endif

            <!-- Statistics -->
            <div style="background: white; border-radius: 15px; padding: 25px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                <h3 style="color: #1f2937; margin: 0 0 20px 0; font-size: 18px; font-weight: 600;">
                    <i class="fas fa-chart-bar" style="margin-left: 8px; color: #f59e0b;"></i>
                    إحصائيات
                </h3>

                <div style="display:flex; flex-direction:column; gap:12px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                        <span style="color: #6b7280; font-size: 14px;">أيام التتبع</span>
                        <span style="font-weight: 600; color: #374151;">{{ $progressStats['total_days_tracked'] }}</span>
                    </div>

                    @if($target->measurement_type === 'quantity' || $target->measurement_type === 'both')
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                        <span style="color: #6b7280; font-size: 14px;">متوسط يومي (كمية)</span>
                        <span style="font-weight: 600; color: #374151;">{{ number_format($progressStats['avg_daily_quantity'], 2) }}</span>
                    </div>
                    @endif

                    @if($target->measurement_type === 'value' || $target->measurement_type === 'both')
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                        <span style="color: #6b7280; font-size: 14px;">متوسط يومي (قيمة)</span>
                        <span style="font-weight: 600; color: #374151;">{{ number_format($progressStats['avg_daily_value'], 2) }}</span>
                    </div>
                    @endif

                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                        <span style="color: #6b7280; font-size: 14px;">تقدم الوقت</span>
                        <span style="font-weight: 600; color: #374151;">{{ $progressStats['time_progress'] }}%</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart Script -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Progress Chart
    const chartData = @json($chartData);

    const options = {
        series: [{
            name: 'التقدم التراكمي (%)',
            data: chartData.progress_percentage
        }],
        chart: {
            type: 'line',
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
            width: 3
        },
        xaxis: {
            categories: chartData.dates,
            labels: {
                style: {
                    fontFamily: 'Cairo, sans-serif'
                }
            }
        },
        yaxis: {
            title: {
                text: 'نسبة التقدم (%)',
                style: {
                    fontFamily: 'Cairo, sans-serif'
                }
            },
            min: 0,
            max: 100
        },
        colors: ['#4299e1'],
        grid: {
            borderColor: '#e5e7eb'
        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return val + "%"
                }
            }
        }
    };

    const chart = new ApexCharts(document.querySelector("#progressChart"), options);
    chart.render();
});
</script>
@endsection
