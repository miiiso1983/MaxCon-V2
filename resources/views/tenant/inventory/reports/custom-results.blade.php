@extends('layouts.modern')

@section('page-title', 'نتائج التقرير المخصص')
@section('page-description', $reportData['title'] ?? 'نتائج التقرير')

@section('content')
<!-- Page Header -->
<div style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); border-radius: 20px; padding: 30px; margin-bottom: 30px; color: white; position: relative; overflow: hidden;">
    <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
    <div style="position: absolute; bottom: -30px; left: -30px; width: 150px; height: 150px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>
    
    <div style="position: relative; z-index: 2;">
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    <div style="background: rgba(255,255,255,0.2); border-radius: 15px; padding: 15px; margin-left: 20px;">
                        <i class="fas fa-chart-bar" style="font-size: 32px;"></i>
                    </div>
                    <div>
                        <h1 style="font-size: 32px; font-weight: 800; margin: 0; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                            {{ $reportData['title'] ?? 'التقرير المخصص' }} 📊
                        </h1>
                        @if(isset($reportData['period']))
                            <p style="font-size: 18px; margin: 5px 0 0 0; opacity: 0.9;">
                                {{ $reportData['period'] }}
                            </p>
                        @endif
                    </div>
                </div>
                
                <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-calendar" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px; font-weight: 600;">{{ now()->format('Y-m-d H:i') }}</span>
                    </div>
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-chart-line" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px;">{{ ucfirst($reportData['type']) }}</span>
                    </div>
                </div>
            </div>
            
            <div style="display: flex; gap: 15px;">
                <button onclick="exportReport()" style="background: rgba(255,255,255,0.2); color: white; padding: 15px 25px; border: none; border-radius: 15px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3);">
                    <i class="fas fa-download"></i>
                    تصدير
                </button>
                <a href="{{ route('tenant.inventory.custom-reports.index') }}" style="background: rgba(255,255,255,0.2); color: white; padding: 15px 25px; border-radius: 15px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3);">
                    <i class="fas fa-arrow-right"></i>
                    تقرير جديد
                </a>
            </div>
        </div>
    </div>
</div>

@if($reportData['type'] === 'inventory_summary')
    @include('tenant.inventory.reports.partials.inventory-summary', ['data' => $reportData])
@elseif($reportData['type'] === 'movement_analysis')
    @include('tenant.inventory.reports.partials.movement-analysis', ['data' => $reportData])
@elseif($reportData['type'] === 'product_performance')
    @include('tenant.inventory.reports.partials.product-performance', ['data' => $reportData])
@elseif($reportData['type'] === 'warehouse_comparison')
    @include('tenant.inventory.reports.partials.warehouse-comparison', ['data' => $reportData])
@elseif($reportData['type'] === 'cost_analysis')
    @include('tenant.inventory.reports.partials.cost-analysis', ['data' => $reportData])
@elseif($reportData['type'] === 'expiry_tracking')
    @include('tenant.inventory.reports.partials.expiry-tracking', ['data' => $reportData])
@endif

@push('scripts')
<script>
function exportReport() {
    // Create CSV content based on report type
    let csvContent = '';
    const reportType = '{{ $reportData["type"] }}';
    
    switch(reportType) {
        case 'inventory_summary':
            csvContent = generateInventorySummaryCSV();
            break;
        case 'movement_analysis':
            csvContent = generateMovementAnalysisCSV();
            break;
        case 'product_performance':
            csvContent = generateProductPerformanceCSV();
            break;
        case 'warehouse_comparison':
            csvContent = generateWarehouseComparisonCSV();
            break;
        case 'cost_analysis':
            csvContent = generateCostAnalysisCSV();
            break;
        case 'expiry_tracking':
            csvContent = generateExpiryTrackingCSV();
            break;
    }
    
    // Download CSV
    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    const url = URL.createObjectURL(blob);
    link.setAttribute('href', url);
    link.setAttribute('download', `${reportType}_report_${new Date().toISOString().split('T')[0]}.csv`);
    link.style.visibility = 'hidden';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

function generateInventorySummaryCSV() {
    let csv = 'المنتج,المستودع,الكمية الإجمالية,الكمية المتاحة,الكمية المحجوزة,الحالة,القيمة\n';
    
    @if($reportData['type'] === 'inventory_summary')
        @foreach($reportData['data'] as $item)
            csv += '"{{ $item->product->name }}","{{ $item->warehouse->name }}",{{ $item->quantity }},{{ $item->available_quantity }},{{ $item->reserved_quantity }},"{{ $item->status }}",{{ $item->quantity * $item->cost_price }}\n';
        @endforeach
    @endif
    
    return csv;
}

function generateMovementAnalysisCSV() {
    let csv = 'التاريخ,المنتج,المستودع,نوع الحركة,السبب,الكمية,التكلفة\n';
    
    @if($reportData['type'] === 'movement_analysis')
        @foreach($reportData['data'] as $movement)
            csv += '"{{ $movement->movement_date->format('Y-m-d') }}","{{ $movement->product->name }}","{{ $movement->warehouse->name }}","{{ $movement->movement_type }}","{{ $movement->movement_reason }}",{{ $movement->quantity }},{{ $movement->total_cost }}\n';
        @endforeach
    @endif
    
    return csv;
}

function generateProductPerformanceCSV() {
    let csv = 'المنتج,عدد الحركات,الكمية الواردة,الكمية الصادرة,صافي الحركة,القيمة الإجمالية\n';
    
    @if($reportData['type'] === 'product_performance')
        @foreach($reportData['data'] as $performance)
            csv += '"{{ $performance['product']->name }}",{{ $performance['total_movements'] }},{{ $performance['total_in'] }},{{ $performance['total_out'] }},{{ $performance['net_movement'] }},{{ $performance['total_value'] }}\n';
        @endforeach
    @endif
    
    return csv;
}

function generateWarehouseComparisonCSV() {
    let csv = 'المستودع,عدد المنتجات,إجمالي الكمية,الكمية المتاحة,القيمة الإجمالية,نسبة الاستغلال\n';
    
    @if($reportData['type'] === 'warehouse_comparison')
        @foreach($reportData['data'] as $comparison)
            csv += '"{{ $comparison['warehouse']->name }}",{{ $comparison['total_products'] }},{{ $comparison['total_quantity'] }},{{ $comparison['total_available'] }},{{ $comparison['total_value'] }},{{ number_format($comparison['utilization'], 2) }}%\n';
        @endforeach
    @endif
    
    return csv;
}

function generateCostAnalysisCSV() {
    let csv = 'الشهر,عدد الحركات,إجمالي التكلفة\n';
    
    @if($reportData['type'] === 'cost_analysis')
        @foreach($reportData['data']['monthly_trend'] as $month => $trend)
            csv += '"{{ $month }}",{{ $trend['count'] }},{{ $trend['total_cost'] }}\n';
        @endforeach
    @endif
    
    return csv;
}

function generateExpiryTrackingCSV() {
    let csv = 'المنتج,المستودع,الكمية,تاريخ الانتهاء,الحالة\n';
    
    @if($reportData['type'] === 'expiry_tracking')
        @foreach($reportData['data']['expired'] as $item)
            csv += '"{{ $item->product->name }}","{{ $item->warehouse->name }}",{{ $item->quantity }},"{{ $item->expiry_date ? $item->expiry_date->format('Y-m-d') : '' }}","منتهي الصلاحية"\n';
        @endforeach
        @foreach($reportData['data']['expiring_soon'] as $item)
            csv += '"{{ $item->product->name }}","{{ $item->warehouse->name }}",{{ $item->quantity }},"{{ $item->expiry_date ? $item->expiry_date->format('Y-m-d') : '' }}","ينتهي قريباً"\n';
        @endforeach
    @endif
    
    return csv;
}

// Print functionality
function printReport() {
    window.print();
}
</script>
@endpush
@endsection
