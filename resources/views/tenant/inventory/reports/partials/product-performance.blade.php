<!-- Performance Summary -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px;">
    <div style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); border-radius: 15px; padding: 20px; color: white; position: relative; overflow: hidden;">
        <div style="position: absolute; top: -10px; right: -10px; width: 60px; height: 60px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
        <div style="position: relative; z-index: 2;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                <i class="fas fa-star" style="font-size: 24px; opacity: 0.8;"></i>
                <span style="font-size: 12px; opacity: 0.8;">أفضل المنتجات</span>
            </div>
            <div style="font-size: 28px; font-weight: 700;">{{ $data['summary']['top_products']->count() }}</div>
            <div style="font-size: 12px; opacity: 0.8;">منتج نشط</div>
        </div>
    </div>
    
    <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius: 15px; padding: 20px; color: white; position: relative; overflow: hidden;">
        <div style="position: absolute; top: -10px; right: -10px; width: 60px; height: 60px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
        <div style="position: relative; z-index: 2;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                <i class="fas fa-chart-line" style="font-size: 24px; opacity: 0.8;"></i>
                <span style="font-size: 12px; opacity: 0.8;">أعلى نشاط</span>
            </div>
            <div style="font-size: 28px; font-weight: 700;">{{ $data['summary']['most_active']->first()['total_movements'] ?? 0 }}</div>
            <div style="font-size: 12px; opacity: 0.8;">حركة</div>
        </div>
    </div>
    
    <div style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); border-radius: 15px; padding: 20px; color: white; position: relative; overflow: hidden;">
        <div style="position: absolute; top: -10px; right: -10px; width: 60px; height: 60px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
        <div style="position: relative; z-index: 2;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                <i class="fas fa-dollar-sign" style="font-size: 24px; opacity: 0.8;"></i>
                <span style="font-size: 12px; opacity: 0.8;">أعلى قيمة</span>
            </div>
            <div style="font-size: 28px; font-weight: 700;">{{ number_format($data['summary']['highest_value']->first()['total_value'] ?? 0, 0) }}</div>
            <div style="font-size: 12px; opacity: 0.8;">دينار عراقي</div>
        </div>
    </div>
</div>

<!-- Top Performers -->
<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 25px; margin-bottom: 30px;">
    <!-- Most Active Products -->
    <div class="content-card">
        <h3 style="font-size: 18px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-fire" style="color: #f59e0b; margin-left: 10px;"></i>
            المنتجات الأكثر نشاطاً
        </h3>
        
        <div style="display: flex; flex-direction: column; gap: 10px;">
            @foreach($data['summary']['most_active']->take(5) as $index => $product)
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px; background: #f8fafc; border-radius: 8px; border-right: 4px solid #f59e0b;">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <div style="background: #f59e0b; color: white; border-radius: 50%; width: 24px; height: 24px; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 600;">
                            {{ $index + 1 }}
                        </div>
                        <div>
                            <div style="font-weight: 600; color: #2d3748;">{{ $product['product']->name }}</div>
                            <div style="font-size: 12px; color: #6b7280;">{{ $product['product']->code }}</div>
                        </div>
                    </div>
                    <div style="text-align: left;">
                        <div style="font-weight: 600; color: #f59e0b;">{{ number_format($product['total_movements']) }}</div>
                        <div style="font-size: 12px; color: #6b7280;">حركة</div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    
    <!-- Highest Value Products -->
    <div class="content-card">
        <h3 style="font-size: 18px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-gem" style="color: #8b5cf6; margin-left: 10px;"></i>
            المنتجات الأعلى قيمة
        </h3>
        
        <div style="display: flex; flex-direction: column; gap: 10px;">
            @foreach($data['summary']['highest_value']->take(5) as $index => $product)
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px; background: #f8fafc; border-radius: 8px; border-right: 4px solid #8b5cf6;">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <div style="background: #8b5cf6; color: white; border-radius: 50%; width: 24px; height: 24px; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 600;">
                            {{ $index + 1 }}
                        </div>
                        <div>
                            <div style="font-weight: 600; color: #2d3748;">{{ $product['product']->name }}</div>
                            <div style="font-size: 12px; color: #6b7280;">{{ $product['product']->code }}</div>
                        </div>
                    </div>
                    <div style="text-align: left;">
                        <div style="font-weight: 600; color: #8b5cf6;">{{ number_format($product['total_value'], 0) }}</div>
                        <div style="font-size: 12px; color: #6b7280;">د.ع</div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Detailed Performance Table -->
<div class="content-card" id="performanceTable">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin: 0; display: flex; align-items: center;">
            <i class="fas fa-table" style="color: #8b5cf6; margin-left: 10px;"></i>
            تفاصيل أداء المنتجات
        </h3>
        
        <div style="display: flex; gap: 10px;">
            <button onclick="exportPerformanceToCSV()" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 10px 15px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-file-excel"></i>
                تصدير Excel
            </button>
            <button onclick="printPerformance()" style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); color: white; padding: 10px 15px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-print"></i>
                طباعة
            </button>
        </div>
    </div>
    
    @if($data['data']->count() > 0)
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;" id="detailTable">
                <thead>
                    <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                        <th style="padding: 15px; text-align: right; font-weight: 600; color: #4a5568;">المنتج</th>
                        <th style="padding: 15px; text-align: center; font-weight: 600; color: #4a5568;">عدد الحركات</th>
                        <th style="padding: 15px; text-align: center; font-weight: 600; color: #4a5568;">الكمية الواردة</th>
                        <th style="padding: 15px; text-align: center; font-weight: 600; color: #4a5568;">الكمية الصادرة</th>
                        <th style="padding: 15px; text-align: center; font-weight: 600; color: #4a5568;">صافي الحركة</th>
                        <th style="padding: 15px; text-align: center; font-weight: 600; color: #4a5568;">معدل الدوران</th>
                        <th style="padding: 15px; text-align: center; font-weight: 600; color: #4a5568;">القيمة الإجمالية</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data['data'] as $performance)
                        <tr style="border-bottom: 1px solid #e2e8f0; transition: background-color 0.3s ease;" 
                            onmouseover="this.style.backgroundColor='#f8fafc'" 
                            onmouseout="this.style.backgroundColor='transparent'">
                            <td style="padding: 15px;">
                                <div style="font-weight: 600; color: #2d3748;">{{ $performance['product']->name }}</div>
                                <div style="font-size: 12px; color: #6b7280;">{{ $performance['product']->code }}</div>
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <div style="font-weight: 600; color: #2d3748; font-size: 16px;">{{ number_format($performance['total_movements']) }}</div>
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <div style="font-weight: 600; color: #059669; font-size: 16px;">+{{ number_format($performance['total_in']) }}</div>
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <div style="font-weight: 600; color: #ef4444; font-size: 16px;">-{{ number_format($performance['total_out']) }}</div>
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                @php
                                    $netMovement = $performance['net_movement'];
                                    $color = $netMovement > 0 ? '#059669' : ($netMovement < 0 ? '#ef4444' : '#6b7280');
                                    $sign = $netMovement > 0 ? '+' : '';
                                @endphp
                                <div style="font-weight: 600; color: {{ $color }}; font-size: 16px;">{{ $sign }}{{ number_format($netMovement) }}</div>
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                @php
                                    $turnoverRate = $performance['turnover_rate'];
                                    $rateColor = $turnoverRate > 2 ? '#059669' : ($turnoverRate > 0.5 ? '#f59e0b' : '#ef4444');
                                    $rateLabel = $turnoverRate > 2 ? 'سريع' : ($turnoverRate > 0.5 ? 'متوسط' : 'بطيء');
                                @endphp
                                <div style="font-weight: 600; color: {{ $rateColor }}; font-size: 16px;">{{ number_format($turnoverRate, 2) }}</div>
                                <div style="font-size: 12px; color: {{ $rateColor }};">{{ $rateLabel }}</div>
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <div style="font-weight: 600; color: #1e40af; font-size: 16px;">{{ number_format($performance['total_value'], 2) }}</div>
                                <div style="font-size: 12px; color: #6b7280;">د.ع</div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div style="text-align: center; padding: 40px; color: #6b7280;">
            <i class="fas fa-chart-line" style="font-size: 48px; margin-bottom: 15px; opacity: 0.5;"></i>
            <h3 style="margin: 0 0 10px 0;">لا توجد بيانات أداء</h3>
            <p style="margin: 0;">لم يتم العثور على أي بيانات أداء تطابق معايير التقرير</p>
        </div>
    @endif
</div>

<script>
function exportPerformanceToCSV() {
    let csv = 'المنتج,الكود,عدد الحركات,الكمية الواردة,الكمية الصادرة,صافي الحركة,معدل الدوران,القيمة الإجمالية\n';
    
    @foreach($data['data'] as $performance)
        csv += '"{{ $performance['product']->name }}","{{ $performance['product']->code }}",{{ $performance['total_movements'] }},{{ $performance['total_in'] }},{{ $performance['total_out'] }},{{ $performance['net_movement'] }},{{ $performance['turnover_rate'] }},{{ $performance['total_value'] }}\n';
    @endforeach
    
    const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    const url = URL.createObjectURL(blob);
    link.setAttribute('href', url);
    link.setAttribute('download', 'product_performance_report.csv');
    link.style.visibility = 'hidden';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

function printPerformance() {
    const printContent = document.getElementById('detailTable').outerHTML;
    const printWindow = window.open('', '', 'height=600,width=800');
    printWindow.document.write('<html><head><title>تقرير أداء المنتجات</title>');
    printWindow.document.write('<style>table { width: 100%; border-collapse: collapse; font-family: Arial, sans-serif; } th, td { border: 1px solid #ddd; padding: 8px; text-align: right; } th { background-color: #f2f2f2; font-weight: bold; } @media print { body { margin: 0; } }</style>');
    printWindow.document.write('</head><body>');
    printWindow.document.write('<h1 style="text-align: center; margin-bottom: 20px;">تقرير أداء المنتجات</h1>');
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
