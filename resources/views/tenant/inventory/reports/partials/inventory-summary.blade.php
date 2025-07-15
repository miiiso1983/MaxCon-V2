<!-- Summary Statistics -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px;">
    <div style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); border-radius: 15px; padding: 20px; color: white; position: relative; overflow: hidden;">
        <div style="position: absolute; top: -10px; right: -10px; width: 60px; height: 60px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
        <div style="position: relative; z-index: 2;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                <i class="fas fa-list" style="font-size: 24px; opacity: 0.8;"></i>
                <span style="font-size: 12px; opacity: 0.8;">إجمالي العناصر</span>
            </div>
            <div style="font-size: 28px; font-weight: 700;">{{ number_format($data['summary']['total_items']) }}</div>
            <div style="font-size: 12px; opacity: 0.8;">عنصر مخزون</div>
        </div>
    </div>
    
    <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius: 15px; padding: 20px; color: white; position: relative; overflow: hidden;">
        <div style="position: absolute; top: -10px; right: -10px; width: 60px; height: 60px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
        <div style="position: relative; z-index: 2;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                <i class="fas fa-cubes" style="font-size: 24px; opacity: 0.8;"></i>
                <span style="font-size: 12px; opacity: 0.8;">إجمالي الكمية</span>
            </div>
            <div style="font-size: 28px; font-weight: 700;">{{ number_format($data['summary']['total_quantity']) }}</div>
            <div style="font-size: 12px; opacity: 0.8;">وحدة</div>
        </div>
    </div>
    
    <div style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); border-radius: 15px; padding: 20px; color: white; position: relative; overflow: hidden;">
        <div style="position: absolute; top: -10px; right: -10px; width: 60px; height: 60px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
        <div style="position: relative; z-index: 2;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                <i class="fas fa-check-circle" style="font-size: 24px; opacity: 0.8;"></i>
                <span style="font-size: 12px; opacity: 0.8;">الكمية المتاحة</span>
            </div>
            <div style="font-size: 28px; font-weight: 700;">{{ number_format($data['summary']['total_available']) }}</div>
            <div style="font-size: 12px; opacity: 0.8;">وحدة متاحة</div>
        </div>
    </div>
    
    <div style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); border-radius: 15px; padding: 20px; color: white; position: relative; overflow: hidden;">
        <div style="position: absolute; top: -10px; right: -10px; width: 60px; height: 60px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
        <div style="position: relative; z-index: 2;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                <i class="fas fa-dollar-sign" style="font-size: 24px; opacity: 0.8;"></i>
                <span style="font-size: 12px; opacity: 0.8;">القيمة الإجمالية</span>
            </div>
            <div style="font-size: 28px; font-weight: 700;">{{ number_format($data['summary']['total_value'], 0) }}</div>
            <div style="font-size: 12px; opacity: 0.8;">دينار عراقي</div>
        </div>
    </div>
</div>

<!-- Distribution Charts -->
<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 25px; margin-bottom: 30px;">
    <!-- By Status -->
    <div class="content-card">
        <h3 style="font-size: 18px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-chart-pie" style="color: #8b5cf6; margin-left: 10px;"></i>
            التوزيع حسب الحالة
        </h3>
        
        <div style="display: flex; flex-direction: column; gap: 10px;">
            @foreach($data['summary']['by_status'] as $status => $count)
                @php
                    $statusColors = [
                        'active' => ['bg' => '#d1fae5', 'text' => '#065f46', 'label' => 'نشط'],
                        'quarantine' => ['bg' => '#fef3c7', 'text' => '#92400e', 'label' => 'حجر صحي'],
                        'damaged' => ['bg' => '#fee2e2', 'text' => '#991b1b', 'label' => 'تالف'],
                        'expired' => ['bg' => '#fee2e2', 'text' => '#991b1b', 'label' => 'منتهي الصلاحية'],
                    ];
                    $statusInfo = $statusColors[$status] ?? ['bg' => '#f1f5f9', 'text' => '#64748b', 'label' => $status];
                    $percentage = $data['summary']['total_items'] > 0 ? ($count / $data['summary']['total_items']) * 100 : 0;
                @endphp
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px; background: {{ $statusInfo['bg'] }}; border-radius: 8px;">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <span style="background: {{ $statusInfo['text'] }}; color: white; padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                            {{ $statusInfo['label'] }}
                        </span>
                        <span style="color: {{ $statusInfo['text'] }}; font-weight: 600;">{{ number_format($count) }}</span>
                    </div>
                    <span style="color: {{ $statusInfo['text'] }}; font-weight: 600;">{{ number_format($percentage, 1) }}%</span>
                </div>
            @endforeach
        </div>
    </div>
    
    <!-- By Warehouse -->
    <div class="content-card">
        <h3 style="font-size: 18px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-warehouse" style="color: #8b5cf6; margin-left: 10px;"></i>
            التوزيع حسب المستودع
        </h3>
        
        <div style="display: flex; flex-direction: column; gap: 10px;">
            @foreach($data['summary']['by_warehouse'] as $warehouse => $count)
                @php
                    $percentage = $data['summary']['total_items'] > 0 ? ($count / $data['summary']['total_items']) * 100 : 0;
                @endphp
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px; background: #f8fafc; border-radius: 8px;">
                    <div style="font-weight: 600; color: #2d3748;">{{ $warehouse }}</div>
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <span style="color: #6b7280;">{{ number_format($count) }}</span>
                        <div style="width: 60px; height: 6px; background: #e5e7eb; border-radius: 3px; overflow: hidden;">
                            <div style="width: {{ $percentage }}%; height: 100%; background: #8b5cf6; transition: width 0.3s ease;"></div>
                        </div>
                        <span style="color: #8b5cf6; font-weight: 600; min-width: 40px;">{{ number_format($percentage, 1) }}%</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Detailed Inventory Table -->
<div class="content-card" id="inventoryTable">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin: 0; display: flex; align-items: center;">
            <i class="fas fa-table" style="color: #8b5cf6; margin-left: 10px;"></i>
            تفاصيل المخزون
        </h3>
        
        <div style="display: flex; gap: 10px;">
            <button onclick="exportTableToCSV()" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 10px 15px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-file-excel"></i>
                تصدير Excel
            </button>
            <button onclick="printTable()" style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); color: white; padding: 10px 15px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px;">
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
                        <th style="padding: 15px; text-align: center; font-weight: 600; color: #4a5568;">المستودع</th>
                        <th style="padding: 15px; text-align: center; font-weight: 600; color: #4a5568;">الكمية الإجمالية</th>
                        <th style="padding: 15px; text-align: center; font-weight: 600; color: #4a5568;">الكمية المتاحة</th>
                        <th style="padding: 15px; text-align: center; font-weight: 600; color: #4a5568;">الكمية المحجوزة</th>
                        <th style="padding: 15px; text-align: center; font-weight: 600; color: #4a5568;">الحالة</th>
                        <th style="padding: 15px; text-align: center; font-weight: 600; color: #4a5568;">القيمة</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data['data'] as $item)
                        <tr style="border-bottom: 1px solid #e2e8f0; transition: background-color 0.3s ease;" 
                            onmouseover="this.style.backgroundColor='#f8fafc'" 
                            onmouseout="this.style.backgroundColor='transparent'">
                            <td style="padding: 15px;">
                                <div style="font-weight: 600; color: #2d3748;">{{ $item->product->name }}</div>
                                <div style="font-size: 12px; color: #6b7280;">{{ $item->product->code }}</div>
                                @if($item->batch_number)
                                    <div style="font-size: 12px; color: #6b7280;">دفعة: {{ $item->batch_number }}</div>
                                @endif
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <div style="font-weight: 600; color: #2d3748;">{{ $item->warehouse->name }}</div>
                                <div style="font-size: 12px; color: #6b7280;">{{ $item->warehouse->code }}</div>
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <div style="font-weight: 600; color: #2d3748; font-size: 16px;">{{ number_format($item->quantity, 0) }}</div>
                                <div style="font-size: 12px; color: #6b7280;">{{ $item->product->unit ?? 'وحدة' }}</div>
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <div style="font-weight: 600; color: #059669; font-size: 16px;">{{ number_format($item->available_quantity, 0) }}</div>
                                @if($item->available_quantity <= ($item->product->min_stock_level ?? 0))
                                    <div style="font-size: 12px; color: #ef4444; font-weight: 600;">
                                        <i class="fas fa-exclamation-triangle"></i> منخفض
                                    </div>
                                @endif
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <div style="font-weight: 600; color: #f59e0b; font-size: 16px;">{{ number_format($item->reserved_quantity, 0) }}</div>
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                @php
                                    $statusColors = [
                                        'active' => ['bg' => '#d1fae5', 'text' => '#065f46', 'label' => 'نشط'],
                                        'quarantine' => ['bg' => '#fef3c7', 'text' => '#92400e', 'label' => 'حجر صحي'],
                                        'damaged' => ['bg' => '#fee2e2', 'text' => '#991b1b', 'label' => 'تالف'],
                                        'expired' => ['bg' => '#fee2e2', 'text' => '#991b1b', 'label' => 'منتهي الصلاحية'],
                                    ];
                                    $status = $statusColors[$item->status] ?? ['bg' => '#f1f5f9', 'text' => '#64748b', 'label' => $item->status];
                                @endphp
                                <span style="background: {{ $status['bg'] }}; color: {{ $status['text'] }}; padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                                    {{ $status['label'] }}
                                </span>
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <div style="font-weight: 600; color: #1e40af; font-size: 16px;">{{ number_format($item->quantity * $item->cost_price, 2) }}</div>
                                <div style="font-size: 12px; color: #6b7280;">د.ع</div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div style="text-align: center; padding: 40px; color: #6b7280;">
            <i class="fas fa-boxes" style="font-size: 48px; margin-bottom: 15px; opacity: 0.5;"></i>
            <h3 style="margin: 0 0 10px 0;">لا توجد بيانات مخزون</h3>
            <p style="margin: 0;">لم يتم العثور على أي عناصر تطابق معايير التقرير</p>
        </div>
    @endif
</div>

<script>
function exportTableToCSV() {
    let csv = 'المنتج,الكود,المستودع,الكمية الإجمالية,الكمية المتاحة,الكمية المحجوزة,الحالة,القيمة\n';
    
    @foreach($data['data'] as $item)
        csv += '"{{ $item->product->name }}","{{ $item->product->code }}","{{ $item->warehouse->name }}",{{ $item->quantity }},{{ $item->available_quantity }},{{ $item->reserved_quantity }},"{{ $item->status }}",{{ $item->quantity * $item->cost_price }}\n';
    @endforeach
    
    const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    const url = URL.createObjectURL(blob);
    link.setAttribute('href', url);
    link.setAttribute('download', 'inventory_summary_report.csv');
    link.style.visibility = 'hidden';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

function printTable() {
    const printContent = document.getElementById('detailTable').outerHTML;
    const printWindow = window.open('', '', 'height=600,width=800');
    printWindow.document.write('<html><head><title>تقرير ملخص المخزون</title>');
    printWindow.document.write('<style>table { width: 100%; border-collapse: collapse; font-family: Arial, sans-serif; } th, td { border: 1px solid #ddd; padding: 8px; text-align: right; } th { background-color: #f2f2f2; font-weight: bold; } @media print { body { margin: 0; } }</style>');
    printWindow.document.write('</head><body>');
    printWindow.document.write('<h1 style="text-align: center; margin-bottom: 20px;">تقرير ملخص المخزون</h1>');
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
