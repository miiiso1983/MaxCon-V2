@extends('layouts.modern')

@section('page-title', 'لوحة التحليلات')
@section('page-description', 'تحليلات متقدمة وذكية لإدارة المخزون')

@section('content')
<!-- Page Header -->
<div style="background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%); border-radius: 20px; padding: 30px; margin-bottom: 30px; color: white; position: relative; overflow: hidden;">
    <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
    <div style="position: absolute; bottom: -30px; left: -30px; width: 150px; height: 150px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>
    
    <div style="position: relative; z-index: 2;">
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    <div style="background: rgba(255,255,255,0.2); border-radius: 15px; padding: 15px; margin-left: 20px;">
                        <i class="fas fa-analytics" style="font-size: 32px;"></i>
                    </div>
                    <div>
                        <h1 style="font-size: 32px; font-weight: 800; margin: 0; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                            لوحة التحليلات 📈
                        </h1>
                        <p style="font-size: 18px; margin: 5px 0 0 0; opacity: 0.9;">
                            تحليلات متقدمة وذكية لإدارة المخزون
                        </p>
                    </div>
                </div>
                
                <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-brain" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px; font-weight: 600;">ذكاء اصطناعي</span>
                    </div>
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-chart-line" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px;">تنبؤات مستقبلية</span>
                    </div>
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-sync" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px;">تحديث مباشر</span>
                    </div>
                </div>
            </div>
            
            <div style="display: flex; gap: 15px;">
                <button onclick="refreshAnalytics()" style="background: rgba(255,255,255,0.2); color: white; padding: 15px 25px; border: none; border-radius: 15px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3);">
                    <i class="fas fa-sync"></i>
                    تحديث
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Overview Metrics -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px;">
    <div style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); border-radius: 15px; padding: 20px; color: white; position: relative; overflow: hidden;">
        <div style="position: absolute; top: -10px; right: -10px; width: 60px; height: 60px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
        <div style="position: relative; z-index: 2;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                <i class="fas fa-cubes" style="font-size: 24px; opacity: 0.8;"></i>
                <span style="font-size: 12px; opacity: 0.8;">إجمالي المنتجات</span>
            </div>
            <div style="font-size: 28px; font-weight: 700;">{{ number_format($analytics['overview']['total_products']) }}</div>
            <div style="font-size: 12px; opacity: 0.8;">منتج مختلف</div>
        </div>
    </div>
    
    <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius: 15px; padding: 20px; color: white; position: relative; overflow: hidden;">
        <div style="position: absolute; top: -10px; right: -10px; width: 60px; height: 60px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
        <div style="position: relative; z-index: 2;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                <i class="fas fa-check-circle" style="font-size: 24px; opacity: 0.8;"></i>
                <span style="font-size: 12px; opacity: 0.8;">الكمية المتاحة</span>
            </div>
            <div style="font-size: 28px; font-weight: 700;">{{ number_format($analytics['overview']['available_quantity']) }}</div>
            <div style="font-size: 12px; opacity: 0.8;">وحدة متاحة</div>
        </div>
    </div>
    
    <div style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); border-radius: 15px; padding: 20px; color: white; position: relative; overflow: hidden;">
        <div style="position: absolute; top: -10px; right: -10px; width: 60px; height: 60px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
        <div style="position: relative; z-index: 2;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                <i class="fas fa-dollar-sign" style="font-size: 24px; opacity: 0.8;"></i>
                <span style="font-size: 12px; opacity: 0.8;">القيمة الإجمالية</span>
            </div>
            <div style="font-size: 28px; font-weight: 700;">{{ number_format($analytics['overview']['total_value'], 0) }}</div>
            <div style="font-size: 12px; opacity: 0.8;">دينار عراقي</div>
        </div>
    </div>
    
    <div style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); border-radius: 15px; padding: 20px; color: white; position: relative; overflow: hidden;">
        <div style="position: absolute; top: -10px; right: -10px; width: 60px; height: 60px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
        <div style="position: relative; z-index: 2;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                <i class="fas fa-exclamation-triangle" style="font-size: 24px; opacity: 0.8;"></i>
                <span style="font-size: 12px; opacity: 0.8;">تنبيهات نشطة</span>
            </div>
            <div style="font-size: 28px; font-weight: 700;">{{ $analytics['alerts']['low_stock']['count'] + $analytics['alerts']['expiring']['count'] + $analytics['alerts']['expired']['count'] }}</div>
            <div style="font-size: 12px; opacity: 0.8;">تحتاج إجراء</div>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 25px; margin-bottom: 30px;">
    <!-- Movement Trends Chart -->
    <div class="content-card">
        <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-chart-line" style="color: #06b6d4; margin-left: 10px;"></i>
            اتجاهات الحركة (آخر 30 يوم)
        </h3>
        <div style="height: 300px; position: relative;">
            <canvas id="movementTrendsChart"></canvas>
        </div>
    </div>
    
    <!-- Alerts Summary -->
    <div class="content-card">
        <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-bell" style="color: #06b6d4; margin-left: 10px;"></i>
            ملخص التنبيهات
        </h3>
        
        <div style="display: flex; flex-direction: column; gap: 15px;">
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 15px; background: #fee2e2; border-radius: 10px; border-right: 4px solid #ef4444;">
                <div>
                    <div style="font-weight: 600; color: #991b1b;">مخزون منخفض</div>
                    <div style="font-size: 12px; color: #6b7280;">{{ number_format($analytics['alerts']['low_stock']['total_value'], 0) }} د.ع</div>
                </div>
                <div style="font-size: 24px; font-weight: 700; color: #ef4444;">{{ $analytics['alerts']['low_stock']['count'] }}</div>
            </div>
            
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 15px; background: #fef3c7; border-radius: 10px; border-right: 4px solid #f59e0b;">
                <div>
                    <div style="font-weight: 600; color: #92400e;">ينتهي قريباً</div>
                    <div style="font-size: 12px; color: #6b7280;">{{ number_format($analytics['alerts']['expiring']['total_value'], 0) }} د.ع</div>
                </div>
                <div style="font-size: 24px; font-weight: 700; color: #f59e0b;">{{ $analytics['alerts']['expiring']['count'] }}</div>
            </div>
            
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 15px; background: #fee2e2; border-radius: 10px; border-right: 4px solid #dc2626;">
                <div>
                    <div style="font-weight: 600; color: #991b1b;">منتهي الصلاحية</div>
                    <div style="font-size: 12px; color: #6b7280;">{{ number_format($analytics['alerts']['expired']['total_value'], 0) }} د.ع</div>
                </div>
                <div style="font-size: 24px; font-weight: 700; color: #dc2626;">{{ $analytics['alerts']['expired']['count'] }}</div>
            </div>
        </div>
    </div>
</div>

<!-- Performance & Efficiency -->
<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 25px; margin-bottom: 30px;">
    <!-- Top Products -->
    <div class="content-card">
        <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-star" style="color: #06b6d4; margin-left: 10px;"></i>
            أفضل المنتجات أداءً
        </h3>
        
        <div style="display: flex; flex-direction: column; gap: 10px;">
            @foreach($analytics['performance']['top_products']->take(5) as $product)
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px; background: #f8fafc; border-radius: 8px;">
                    <div>
                        <div style="font-weight: 600; color: #2d3748;">{{ $product['product']->name }}</div>
                        <div style="font-size: 12px; color: #6b7280;">{{ $product['movements_count'] }} حركة</div>
                    </div>
                    <div style="text-align: left;">
                        <div style="font-weight: 600; color: #059669;">{{ number_format($product['value'], 0) }} د.ع</div>
                        <div style="font-size: 12px; color: #6b7280;">معدل الدوران: {{ number_format($product['turnover_rate'], 2) }}</div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    
    <!-- Warehouse Efficiency -->
    <div class="content-card">
        <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-warehouse" style="color: #06b6d4; margin-left: 10px;"></i>
            كفاءة المستودعات
        </h3>
        
        <div style="display: flex; flex-direction: column; gap: 10px;">
            @foreach($analytics['efficiency']['warehouse_efficiency'] as $warehouse)
                <div style="padding: 12px; background: #f8fafc; border-radius: 8px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                        <div style="font-weight: 600; color: #2d3748;">{{ $warehouse['warehouse']->name }}</div>
                        <div style="font-weight: 600; color: {{ $warehouse['efficiency_score'] >= 80 ? '#059669' : ($warehouse['efficiency_score'] >= 60 ? '#f59e0b' : '#ef4444') }};">
                            {{ number_format($warehouse['efficiency_score'], 1) }}%
                        </div>
                    </div>
                    <div style="background: #e5e7eb; border-radius: 4px; height: 6px; overflow: hidden;">
                        <div style="background: {{ $warehouse['efficiency_score'] >= 80 ? '#10b981' : ($warehouse['efficiency_score'] >= 60 ? '#f59e0b' : '#ef4444') }}; height: 100%; width: {{ $warehouse['efficiency_score'] }}%; transition: width 0.3s ease;"></div>
                    </div>
                    <div style="font-size: 12px; color: #6b7280; margin-top: 5px;">
                        استغلال: {{ number_format($warehouse['utilization'], 1) }}% | حركات: {{ $warehouse['movements_count'] }}
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Forecasting & Insights -->
<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 25px; margin-bottom: 30px;">
    <!-- Demand Forecast -->
    <div class="content-card">
        <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-crystal-ball" style="color: #06b6d4; margin-left: 10px;"></i>
            توقعات الطلب
        </h3>
        
        <div style="padding: 20px; background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%); border-radius: 12px; margin-bottom: 15px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                <span style="font-weight: 600; color: #1e40af;">متوسط الاستهلاك الشهري</span>
                <span style="font-size: 20px; font-weight: 700; color: #1e40af;">{{ number_format($analytics['forecasting']['avg_consumption'], 0) }}</span>
            </div>
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span style="font-weight: 600; color: #1e40af;">أيام التغطية المتبقية</span>
                <span style="font-size: 20px; font-weight: 700; color: {{ $analytics['forecasting']['stock_days'] < 30 ? '#ef4444' : '#10b981' }};">
                    {{ number_format($analytics['forecasting']['stock_days'], 0) }} يوم
                </span>
            </div>
        </div>
        
        <div style="height: 200px; position: relative;">
            <canvas id="demandForecastChart"></canvas>
        </div>
    </div>
    
    <!-- Optimization Opportunities -->
    <div class="content-card">
        <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-lightbulb" style="color: #06b6d4; margin-left: 10px;"></i>
            فرص التحسين
        </h3>
        
        @if(count($analytics['efficiency']['optimization_opportunities']) > 0)
            <div style="display: flex; flex-direction: column; gap: 15px;">
                @foreach($analytics['efficiency']['optimization_opportunities'] as $opportunity)
                    <div style="padding: 15px; background: {{ $opportunity['priority'] === 'عالية' ? '#fef3c7' : '#f0f9ff' }}; border-radius: 10px; border-right: 4px solid {{ $opportunity['priority'] === 'عالية' ? '#f59e0b' : '#3b82f6' }};">
                        <div style="font-weight: 600; color: #2d3748; margin-bottom: 8px;">
                            {{ $opportunity['warehouse']->name }}
                            <span style="background: {{ $opportunity['priority'] === 'عالية' ? '#f59e0b' : '#3b82f6' }}; color: white; padding: 2px 8px; border-radius: 12px; font-size: 10px; margin-right: 8px;">
                                {{ $opportunity['priority'] }}
                            </span>
                        </div>
                        <ul style="margin: 0; padding-right: 20px; color: #4a5568; font-size: 14px;">
                            @foreach($opportunity['opportunities'] as $opp)
                                <li>{{ $opp }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>
        @else
            <div style="text-align: center; padding: 40px; color: #6b7280;">
                <i class="fas fa-check-circle" style="font-size: 48px; margin-bottom: 15px; opacity: 0.5; color: #10b981;"></i>
                <h3 style="margin: 0 0 10px 0; color: #10b981;">ممتاز!</h3>
                <p style="margin: 0;">جميع المستودعات تعمل بكفاءة عالية</p>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Movement Trends Chart
const movementCtx = document.getElementById('movementTrendsChart').getContext('2d');
const movementChart = new Chart(movementCtx, {
    type: 'line',
    data: {
        labels: {!! json_encode(array_keys($analytics['charts']['movement_trends'])) !!},
        datasets: [{
            label: 'وارد',
            data: {!! json_encode(array_column($analytics['charts']['movement_trends'], 'in')) !!},
            borderColor: '#10b981',
            backgroundColor: 'rgba(16, 185, 129, 0.1)',
            tension: 0.4
        }, {
            label: 'صادر',
            data: {!! json_encode(array_column($analytics['charts']['movement_trends'], 'out')) !!},
            borderColor: '#ef4444',
            backgroundColor: 'rgba(239, 68, 68, 0.1)',
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'top',
            }
        },
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// Demand Forecast Chart
const forecastCtx = document.getElementById('demandForecastChart').getContext('2d');
const forecastChart = new Chart(forecastCtx, {
    type: 'bar',
    data: {
        labels: ['الشهر القادم', 'الشهر الثاني', 'الشهر الثالث'],
        datasets: [{
            label: 'توقع الطلب',
            data: {!! json_encode($analytics['forecasting']['demand_forecast']) !!},
            backgroundColor: 'rgba(6, 182, 212, 0.8)',
            borderColor: '#06b6d4',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

function refreshAnalytics() {
    // Show loading state
    const btn = event.target;
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري التحديث...';
    btn.disabled = true;
    
    // Simulate refresh (in real implementation, this would make an AJAX call)
    setTimeout(() => {
        location.reload();
    }, 1000);
}
</script>
@endpush
@endsection
