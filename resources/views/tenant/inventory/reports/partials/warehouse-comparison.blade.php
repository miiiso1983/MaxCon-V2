<!-- Warehouse Comparison Cards -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-bottom: 30px;">
    @foreach($data['data'] as $comparison)
        <div style="background: white; border-radius: 15px; padding: 20px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); border: 1px solid #e2e8f0; position: relative; overflow: hidden;">
            <!-- Utilization indicator -->
            <div style="position: absolute; top: 0; left: 0; right: 0; height: 4px; background: #e5e7eb;">
                <div style="height: 100%; background: {{ $comparison['utilization'] >= 80 ? '#ef4444' : ($comparison['utilization'] >= 60 ? '#f59e0b' : '#10b981') }}; width: {{ $comparison['utilization'] }}%; transition: width 0.3s ease;"></div>
            </div>
            
            <div style="margin-top: 10px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                    <h3 style="margin: 0; font-size: 18px; font-weight: 700; color: #2d3748;">{{ $comparison['warehouse']->name }}</h3>
                    <span style="background: {{ $comparison['utilization'] >= 80 ? '#fee2e2' : ($comparison['utilization'] >= 60 ? '#fef3c7' : '#d1fae5') }}; color: {{ $comparison['utilization'] >= 80 ? '#991b1b' : ($comparison['utilization'] >= 60 ? '#92400e' : '#065f46') }}; padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                        {{ number_format($comparison['utilization'], 1) }}%
                    </span>
                </div>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                    <div style="text-align: center; padding: 10px; background: #f8fafc; border-radius: 8px;">
                        <div style="font-size: 20px; font-weight: 700; color: #3b82f6;">{{ number_format($comparison['total_products']) }}</div>
                        <div style="font-size: 12px; color: #6b7280;">منتج</div>
                    </div>
                    <div style="text-align: center; padding: 10px; background: #f8fafc; border-radius: 8px;">
                        <div style="font-size: 20px; font-weight: 700; color: #10b981;">{{ number_format($comparison['total_quantity']) }}</div>
                        <div style="font-size: 12px; color: #6b7280;">وحدة</div>
                    </div>
                </div>
                
                <div style="text-align: center; padding: 15px; background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); border-radius: 10px; color: white;">
                    <div style="font-size: 18px; font-weight: 700;">{{ number_format($comparison['total_value'], 0) }}</div>
                    <div style="font-size: 12px; opacity: 0.9;">دينار عراقي</div>
                </div>
                
                <!-- Status breakdown -->
                @if(isset($comparison['by_status']) && count($comparison['by_status']) > 0)
                    <div style="margin-top: 15px;">
                        <div style="font-size: 14px; font-weight: 600; color: #4a5568; margin-bottom: 8px;">توزيع الحالات:</div>
                        <div style="display: flex; flex-wrap: wrap; gap: 5px;">
                            @foreach($comparison['by_status'] as $status => $count)
                                @php
                                    $statusColors = [
                                        'active' => ['bg' => '#d1fae5', 'text' => '#065f46'],
                                        'quarantine' => ['bg' => '#fef3c7', 'text' => '#92400e'],
                                        'damaged' => ['bg' => '#fee2e2', 'text' => '#991b1b'],
                                        'expired' => ['bg' => '#fee2e2', 'text' => '#991b1b'],
                                    ];
                                    $statusInfo = $statusColors[$status] ?? ['bg' => '#f1f5f9', 'text' => '#64748b'];
                                @endphp
                                <span style="background: {{ $statusInfo['bg'] }}; color: {{ $statusInfo['text'] }}; padding: 2px 6px; border-radius: 8px; font-size: 11px; font-weight: 600;">
                                    {{ $count }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @endforeach
</div>

<!-- Summary Statistics -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px;">
    <div style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); border-radius: 15px; padding: 20px; color: white; position: relative; overflow: hidden;">
        <div style="position: absolute; top: -10px; right: -10px; width: 60px; height: 60px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
        <div style="position: relative; z-index: 2;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                <i class="fas fa-warehouse" style="font-size: 24px; opacity: 0.8;"></i>
                <span style="font-size: 12px; opacity: 0.8;">إجمالي المستودعات</span>
            </div>
            <div style="font-size: 28px; font-weight: 700;">{{ $data['summary']['total_warehouses'] }}</div>
            <div style="font-size: 12px; opacity: 0.8;">مستودع</div>
        </div>
    </div>
    
    <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius: 15px; padding: 20px; color: white; position: relative; overflow: hidden;">
        <div style="position: absolute; top: -10px; right: -10px; width: 60px; height: 60px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
        <div style="position: relative; z-index: 2;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                <i class="fas fa-expand-arrows-alt" style="font-size: 24px; opacity: 0.8;"></i>
                <span style="font-size: 12px; opacity: 0.8;">إجمالي السعة</span>
            </div>
            <div style="font-size: 28px; font-weight: 700;">{{ number_format($data['summary']['total_capacity']) }}</div>
            <div style="font-size: 12px; opacity: 0.8;">وحدة</div>
        </div>
    </div>
    
    <div style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); border-radius: 15px; padding: 20px; color: white; position: relative; overflow: hidden;">
        <div style="position: absolute; top: -10px; right: -10px; width: 60px; height: 60px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
        <div style="position: relative; z-index: 2;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                <i class="fas fa-percentage" style="font-size: 24px; opacity: 0.8;"></i>
                <span style="font-size: 12px; opacity: 0.8;">متوسط الاستغلال</span>
            </div>
            <div style="font-size: 28px; font-weight: 700;">{{ number_format($data['summary']['total_utilization'], 1) }}%</div>
            <div style="font-size: 12px; opacity: 0.8;">من السعة</div>
        </div>
    </div>
</div>

<!-- Best Performers -->
<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 25px; margin-bottom: 30px;">
    <!-- Most Utilized -->
    <div class="content-card">
        <h3 style="font-size: 18px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-trophy" style="color: #f59e0b; margin-left: 10px;"></i>
            الأكثر استغلالاً
        </h3>
        
        @if($data['summary']['most_utilized'])
            <div style="padding: 20px; background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); border-radius: 12px; border-right: 4px solid #f59e0b;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                    <h4 style="margin: 0; font-size: 16px; font-weight: 600; color: #92400e;">{{ $data['summary']['most_utilized']['warehouse']->name }}</h4>
                    <span style="background: #f59e0b; color: white; padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                        {{ number_format($data['summary']['most_utilized']['utilization'], 1) }}%
                    </span>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 10px; text-align: center;">
                    <div>
                        <div style="font-weight: 600; color: #92400e;">{{ number_format($data['summary']['most_utilized']['total_products']) }}</div>
                        <div style="font-size: 12px; color: #6b7280;">منتج</div>
                    </div>
                    <div>
                        <div style="font-weight: 600; color: #92400e;">{{ number_format($data['summary']['most_utilized']['total_quantity']) }}</div>
                        <div style="font-size: 12px; color: #6b7280;">وحدة</div>
                    </div>
                    <div>
                        <div style="font-weight: 600; color: #92400e;">{{ number_format($data['summary']['most_utilized']['total_value'], 0) }}</div>
                        <div style="font-size: 12px; color: #6b7280;">د.ع</div>
                    </div>
                </div>
            </div>
        @else
            <div style="text-align: center; padding: 20px; color: #6b7280;">
                <i class="fas fa-info-circle" style="font-size: 24px; margin-bottom: 10px; opacity: 0.5;"></i>
                <p style="margin: 0;">لا توجد بيانات كافية</p>
            </div>
        @endif
    </div>
    
    <!-- Highest Value -->
    <div class="content-card">
        <h3 style="font-size: 18px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-gem" style="color: #8b5cf6; margin-left: 10px;"></i>
            الأعلى قيمة
        </h3>
        
        @if($data['summary']['highest_value'])
            <div style="padding: 20px; background: linear-gradient(135deg, #ede9fe 0%, #ddd6fe 100%); border-radius: 12px; border-right: 4px solid #8b5cf6;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                    <h4 style="margin: 0; font-size: 16px; font-weight: 600; color: #6b46c1;">{{ $data['summary']['highest_value']['warehouse']->name }}</h4>
                    <span style="background: #8b5cf6; color: white; padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                        {{ number_format($data['summary']['highest_value']['total_value'], 0) }} د.ع
                    </span>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 10px; text-align: center;">
                    <div>
                        <div style="font-weight: 600; color: #6b46c1;">{{ number_format($data['summary']['highest_value']['total_products']) }}</div>
                        <div style="font-size: 12px; color: #6b7280;">منتج</div>
                    </div>
                    <div>
                        <div style="font-weight: 600; color: #6b46c1;">{{ number_format($data['summary']['highest_value']['total_quantity']) }}</div>
                        <div style="font-size: 12px; color: #6b7280;">وحدة</div>
                    </div>
                    <div>
                        <div style="font-weight: 600; color: #6b46c1;">{{ number_format($data['summary']['highest_value']['utilization'], 1) }}%</div>
                        <div style="font-size: 12px; color: #6b7280;">استغلال</div>
                    </div>
                </div>
            </div>
        @else
            <div style="text-align: center; padding: 20px; color: #6b7280;">
                <i class="fas fa-info-circle" style="font-size: 24px; margin-bottom: 10px; opacity: 0.5;"></i>
                <p style="margin: 0;">لا توجد بيانات كافية</p>
            </div>
        @endif
    </div>
</div>

<!-- Detailed Comparison Table -->
<div class="content-card" id="comparisonTable">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin: 0; display: flex; align-items: center;">
            <i class="fas fa-table" style="color: #8b5cf6; margin-left: 10px;"></i>
            مقارنة تفصيلية للمستودعات
        </h3>
        
        <div style="display: flex; gap: 10px;">
            <button onclick="exportComparisonToCSV()" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 10px 15px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-file-excel"></i>
                تصدير Excel
            </button>
            <button onclick="printComparison()" style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); color: white; padding: 10px 15px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px;">
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
                        <th style="padding: 15px; text-align: right; font-weight: 600; color: #4a5568;">المستودع</th>
                        <th style="padding: 15px; text-align: center; font-weight: 600; color: #4a5568;">عدد المنتجات</th>
                        <th style="padding: 15px; text-align: center; font-weight: 600; color: #4a5568;">إجمالي الكمية</th>
                        <th style="padding: 15px; text-align: center; font-weight: 600; color: #4a5568;">الكمية المتاحة</th>
                        <th style="padding: 15px; text-align: center; font-weight: 600; color: #4a5568;">نسبة الاستغلال</th>
                        <th style="padding: 15px; text-align: center; font-weight: 600; color: #4a5568;">القيمة الإجمالية</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data['data'] as $comparison)
                        <tr style="border-bottom: 1px solid #e2e8f0; transition: background-color 0.3s ease;" 
                            onmouseover="this.style.backgroundColor='#f8fafc'" 
                            onmouseout="this.style.backgroundColor='transparent'">
                            <td style="padding: 15px;">
                                <div style="font-weight: 600; color: #2d3748;">{{ $comparison['warehouse']->name }}</div>
                                <div style="font-size: 12px; color: #6b7280;">{{ $comparison['warehouse']->code }}</div>
                                @if($comparison['warehouse']->location)
                                    <div style="font-size: 12px; color: #6b7280;">{{ $comparison['warehouse']->location }}</div>
                                @endif
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <div style="font-weight: 600; color: #2d3748; font-size: 16px;">{{ number_format($comparison['total_products']) }}</div>
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <div style="font-weight: 600; color: #059669; font-size: 16px;">{{ number_format($comparison['total_quantity']) }}</div>
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <div style="font-weight: 600; color: #3b82f6; font-size: 16px;">{{ number_format($comparison['total_available']) }}</div>
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                @php
                                    $utilization = $comparison['utilization'];
                                    $utilizationColor = $utilization >= 80 ? '#ef4444' : ($utilization >= 60 ? '#f59e0b' : '#10b981');
                                @endphp
                                <div style="font-weight: 600; color: {{ $utilizationColor }}; font-size: 16px;">{{ number_format($utilization, 1) }}%</div>
                                <div style="width: 60px; height: 6px; background: #e5e7eb; border-radius: 3px; overflow: hidden; margin: 5px auto;">
                                    <div style="width: {{ $utilization }}%; height: 100%; background: {{ $utilizationColor }}; transition: width 0.3s ease;"></div>
                                </div>
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <div style="font-weight: 600; color: #1e40af; font-size: 16px;">{{ number_format($comparison['total_value'], 2) }}</div>
                                <div style="font-size: 12px; color: #6b7280;">د.ع</div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div style="text-align: center; padding: 40px; color: #6b7280;">
            <i class="fas fa-warehouse" style="font-size: 48px; margin-bottom: 15px; opacity: 0.5;"></i>
            <h3 style="margin: 0 0 10px 0;">لا توجد مستودعات</h3>
            <p style="margin: 0;">لم يتم العثور على أي مستودعات تطابق معايير التقرير</p>
        </div>
    @endif
</div>

<script>
function exportComparisonToCSV() {
    let csv = 'المستودع,الكود,عدد المنتجات,إجمالي الكمية,الكمية المتاحة,نسبة الاستغلال,القيمة الإجمالية\n';
    
    @foreach($data['data'] as $comparison)
        csv += '"{{ $comparison['warehouse']->name }}","{{ $comparison['warehouse']->code }}",{{ $comparison['total_products'] }},{{ $comparison['total_quantity'] }},{{ $comparison['total_available'] }},{{ $comparison['utilization'] }},{{ $comparison['total_value'] }}\n';
    @endforeach
    
    const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    const url = URL.createObjectURL(blob);
    link.setAttribute('href', url);
    link.setAttribute('download', 'warehouse_comparison_report.csv');
    link.style.visibility = 'hidden';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

function printComparison() {
    const printContent = document.getElementById('detailTable').outerHTML;
    const printWindow = window.open('', '', 'height=600,width=800');
    printWindow.document.write('<html><head><title>تقرير مقارنة المستودعات</title>');
    printWindow.document.write('<style>table { width: 100%; border-collapse: collapse; font-family: Arial, sans-serif; } th, td { border: 1px solid #ddd; padding: 8px; text-align: right; } th { background-color: #f2f2f2; font-weight: bold; } @media print { body { margin: 0; } }</style>');
    printWindow.document.write('</head><body>');
    printWindow.document.write('<h1 style="text-align: center; margin-bottom: 20px;">تقرير مقارنة المستودعات</h1>');
    printWindow.document.write('<p style="text-align: center; margin-bottom: 20px;">تاريخ التقرير: ' + new Date().toLocaleDateString('ar-SA') + '</p>');
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
