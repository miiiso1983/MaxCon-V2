<!-- Cost Summary -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px;">
    <div style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); border-radius: 15px; padding: 20px; color: white; position: relative; overflow: hidden;">
        <div style="position: absolute; top: -10px; right: -10px; width: 60px; height: 60px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
        <div style="position: relative; z-index: 2;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                <i class="fas fa-dollar-sign" style="font-size: 24px; opacity: 0.8;"></i>
                <span style="font-size: 12px; opacity: 0.8;">إجمالي التكلفة</span>
            </div>
            <div style="font-size: 28px; font-weight: 700;">{{ number_format($data['data']['total_cost'], 0) }}</div>
            <div style="font-size: 12px; opacity: 0.8;">دينار عراقي</div>
        </div>
    </div>
    
    <div style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); border-radius: 15px; padding: 20px; color: white; position: relative; overflow: hidden;">
        <div style="position: absolute; top: -10px; right: -10px; width: 60px; height: 60px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
        <div style="position: relative; z-index: 2;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                <i class="fas fa-calculator" style="font-size: 24px; opacity: 0.8;"></i>
                <span style="font-size: 12px; opacity: 0.8;">متوسط التكلفة</span>
            </div>
            <div style="font-size: 28px; font-weight: 700;">{{ number_format($data['data']['avg_cost'], 2) }}</div>
            <div style="font-size: 12px; opacity: 0.8;">د.ع / وحدة</div>
        </div>
    </div>
</div>

<!-- Cost by Type -->
<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 25px; margin-bottom: 30px;">
    <div class="content-card">
        <h3 style="font-size: 18px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-chart-pie" style="color: #8b5cf6; margin-left: 10px;"></i>
            التكلفة حسب نوع الحركة
        </h3>
        
        <div style="display: flex; flex-direction: column; gap: 15px;">
            @foreach($data['data']['by_type'] as $type => $typeData)
                @php
                    $typeColors = [
                        'in' => ['bg' => '#d1fae5', 'text' => '#065f46', 'label' => 'وارد'],
                        'out' => ['bg' => '#fee2e2', 'text' => '#991b1b', 'label' => 'صادر'],
                        'transfer_in' => ['bg' => '#dbeafe', 'text' => '#1e40af', 'label' => 'تحويل وارد'],
                        'transfer_out' => ['bg' => '#fef3c7', 'text' => '#92400e', 'label' => 'تحويل صادر'],
                    ];
                    $typeInfo = $typeColors[$type] ?? ['bg' => '#f1f5f9', 'text' => '#64748b', 'label' => $type];
                    $percentage = $data['data']['total_cost'] > 0 ? ($typeData['total_cost'] / $data['data']['total_cost']) * 100 : 0;
                @endphp
                <div style="padding: 15px; background: {{ $typeInfo['bg'] }}; border-radius: 10px; border-right: 4px solid {{ $typeInfo['text'] }};">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                        <h4 style="margin: 0; font-size: 16px; font-weight: 600; color: {{ $typeInfo['text'] }};">{{ $typeInfo['label'] }}</h4>
                        <span style="background: {{ $typeInfo['text'] }}; color: white; padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                            {{ number_format($percentage, 1) }}%
                        </span>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 10px; text-align: center;">
                        <div>
                            <div style="font-weight: 600; color: {{ $typeInfo['text'] }};">{{ number_format($typeData['count']) }}</div>
                            <div style="font-size: 12px; color: #6b7280;">حركة</div>
                        </div>
                        <div>
                            <div style="font-weight: 600; color: {{ $typeInfo['text'] }};">{{ number_format($typeData['total_cost'], 0) }}</div>
                            <div style="font-size: 12px; color: #6b7280;">د.ع</div>
                        </div>
                        <div>
                            <div style="font-weight: 600; color: {{ $typeInfo['text'] }};">{{ number_format($typeData['avg_cost'], 2) }}</div>
                            <div style="font-size: 12px; color: #6b7280;">متوسط</div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    
    <!-- Top Products by Cost -->
    <div class="content-card">
        <h3 style="font-size: 18px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-crown" style="color: #f59e0b; margin-left: 10px;"></i>
            أعلى المنتجات تكلفة
        </h3>
        
        <div style="display: flex; flex-direction: column; gap: 10px;">
            @foreach($data['data']['by_product']->take(5) as $productName => $productData)
                @php
                    $percentage = $data['data']['total_cost'] > 0 ? ($productData['total_cost'] / $data['data']['total_cost']) * 100 : 0;
                @endphp
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px; background: #f8fafc; border-radius: 8px; border-right: 4px solid #f59e0b;">
                    <div>
                        <div style="font-weight: 600; color: #2d3748;">{{ $productName }}</div>
                        <div style="font-size: 12px; color: #6b7280;">{{ $productData['count'] }} حركة</div>
                    </div>
                    <div style="text-align: left;">
                        <div style="font-weight: 600; color: #f59e0b;">{{ number_format($productData['total_cost'], 0) }}</div>
                        <div style="font-size: 12px; color: #6b7280;">{{ number_format($percentage, 1) }}%</div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Monthly Trend Chart -->
<div class="content-card" style="margin-bottom: 30px;">
    <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
        <i class="fas fa-chart-line" style="color: #8b5cf6; margin-left: 10px;"></i>
        اتجاه التكاليف الشهرية
    </h3>
    
    <div style="height: 300px; position: relative;">
        <canvas id="monthlyTrendChart"></canvas>
    </div>
</div>

<!-- Detailed Cost Table -->
<div class="content-card" id="costTable">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin: 0; display: flex; align-items: center;">
            <i class="fas fa-table" style="color: #8b5cf6; margin-left: 10px;"></i>
            تفاصيل التكاليف
        </h3>
        
        <div style="display: flex; gap: 10px;">
            <button onclick="exportCostToCSV()" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 10px 15px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-file-excel"></i>
                تصدير Excel
            </button>
            <button onclick="printCost()" style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); color: white; padding: 10px 15px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-print"></i>
                طباعة
            </button>
        </div>
    </div>
    
    @if($data['movements']->count() > 0)
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;" id="detailTable">
                <thead>
                    <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                        <th style="padding: 15px; text-align: right; font-weight: 600; color: #4a5568;">التاريخ</th>
                        <th style="padding: 15px; text-align: right; font-weight: 600; color: #4a5568;">المنتج</th>
                        <th style="padding: 15px; text-align: center; font-weight: 600; color: #4a5568;">المستودع</th>
                        <th style="padding: 15px; text-align: center; font-weight: 600; color: #4a5568;">النوع</th>
                        <th style="padding: 15px; text-align: center; font-weight: 600; color: #4a5568;">الكمية</th>
                        <th style="padding: 15px; text-align: center; font-weight: 600; color: #4a5568;">تكلفة الوحدة</th>
                        <th style="padding: 15px; text-align: center; font-weight: 600; color: #4a5568;">إجمالي التكلفة</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data['movements']->sortByDesc('movement_date') as $movement)
                        <tr style="border-bottom: 1px solid #e2e8f0; transition: background-color 0.3s ease;" 
                            onmouseover="this.style.backgroundColor='#f8fafc'" 
                            onmouseout="this.style.backgroundColor='transparent'">
                            <td style="padding: 15px;">
                                <div style="font-weight: 600; color: #2d3748;">{{ $movement->movement_date->format('Y-m-d') }}</div>
                                <div style="font-size: 12px; color: #6b7280;">{{ $movement->movement_date->format('H:i') }}</div>
                            </td>
                            <td style="padding: 15px;">
                                <div style="font-weight: 600; color: #2d3748;">{{ $movement->product->name }}</div>
                                <div style="font-size: 12px; color: #6b7280;">{{ $movement->product->code }}</div>
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <div style="font-weight: 600; color: #2d3748;">{{ $movement->warehouse->name }}</div>
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                @php
                                    $typeColors = [
                                        'in' => ['bg' => '#d1fae5', 'text' => '#065f46', 'label' => 'وارد'],
                                        'out' => ['bg' => '#fee2e2', 'text' => '#991b1b', 'label' => 'صادر'],
                                        'transfer_in' => ['bg' => '#dbeafe', 'text' => '#1e40af', 'label' => 'تحويل وارد'],
                                        'transfer_out' => ['bg' => '#fef3c7', 'text' => '#92400e', 'label' => 'تحويل صادر'],
                                    ];
                                    $type = $typeColors[$movement->movement_type] ?? ['bg' => '#f1f5f9', 'text' => '#64748b', 'label' => $movement->movement_type];
                                @endphp
                                <span style="background: {{ $type['bg'] }}; color: {{ $type['text'] }}; padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                                    {{ $type['label'] }}
                                </span>
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <div style="font-weight: 600; color: #2d3748; font-size: 16px;">{{ number_format($movement->quantity, 0) }}</div>
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <div style="font-weight: 600; color: #6b7280; font-size: 16px;">{{ number_format($movement->unit_cost, 2) }}</div>
                                <div style="font-size: 12px; color: #6b7280;">د.ع</div>
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <div style="font-weight: 600; color: #ef4444; font-size: 16px;">{{ number_format($movement->total_cost, 2) }}</div>
                                <div style="font-size: 12px; color: #6b7280;">د.ع</div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div style="text-align: center; padding: 40px; color: #6b7280;">
            <i class="fas fa-dollar-sign" style="font-size: 48px; margin-bottom: 15px; opacity: 0.5;"></i>
            <h3 style="margin: 0 0 10px 0;">لا توجد بيانات تكلفة</h3>
            <p style="margin: 0;">لم يتم العثور على أي حركات بتكلفة تطابق معايير التقرير</p>
        </div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Monthly Trend Chart
const ctx = document.getElementById('monthlyTrendChart').getContext('2d');
const monthlyData = @json($data['data']['monthly_trend']);

const chart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: Object.keys(monthlyData),
        datasets: [{
            label: 'إجمالي التكلفة',
            data: Object.values(monthlyData).map(item => item.total_cost),
            backgroundColor: 'rgba(239, 68, 68, 0.8)',
            borderColor: '#ef4444',
            borderWidth: 1
        }, {
            label: 'عدد الحركات',
            data: Object.values(monthlyData).map(item => item.count),
            type: 'line',
            borderColor: '#8b5cf6',
            backgroundColor: 'rgba(139, 92, 246, 0.1)',
            yAxisID: 'y1',
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
                type: 'linear',
                display: true,
                position: 'left',
                beginAtZero: true,
                title: {
                    display: true,
                    text: 'التكلفة (د.ع)'
                }
            },
            y1: {
                type: 'linear',
                display: true,
                position: 'right',
                beginAtZero: true,
                title: {
                    display: true,
                    text: 'عدد الحركات'
                },
                grid: {
                    drawOnChartArea: false,
                },
            }
        }
    }
});

function exportCostToCSV() {
    let csv = 'التاريخ,المنتج,الكود,المستودع,النوع,الكمية,تكلفة الوحدة,إجمالي التكلفة\n';
    
    @foreach($data['movements'] as $movement)
        csv += '"{{ $movement->movement_date->format('Y-m-d') }}","{{ $movement->product->name }}","{{ $movement->product->code }}","{{ $movement->warehouse->name }}","{{ $movement->movement_type }}",{{ $movement->quantity }},{{ $movement->unit_cost }},{{ $movement->total_cost }}\n';
    @endforeach
    
    const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    const url = URL.createObjectURL(blob);
    link.setAttribute('href', url);
    link.setAttribute('download', 'cost_analysis_report.csv');
    link.style.visibility = 'hidden';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

function printCost() {
    const printContent = document.getElementById('detailTable').outerHTML;
    const printWindow = window.open('', '', 'height=600,width=800');
    printWindow.document.write('<html><head><title>تقرير تحليل التكاليف</title>');
    printWindow.document.write('<style>table { width: 100%; border-collapse: collapse; font-family: Arial, sans-serif; } th, td { border: 1px solid #ddd; padding: 8px; text-align: right; } th { background-color: #f2f2f2; font-weight: bold; } @media print { body { margin: 0; } }</style>');
    printWindow.document.write('</head><body>');
    printWindow.document.write('<h1 style="text-align: center; margin-bottom: 20px;">تقرير تحليل التكاليف</h1>');
    printWindow.document.write('<p style="text-align: center; margin-bottom: 20px;">الفترة: {{ $data['period'] }}</p>');
    printWindow.document.write(printContent);
    printWindow.document.write('</body></html>');
    printWindow.document.close();
    printWindow.focus();
    setTimeout(() => {
        printWindow.print();
        printWindow.close();
    }, 250);
}
</script>
