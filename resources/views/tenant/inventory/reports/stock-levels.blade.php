@extends('layouts.modern')

@section('page-title', 'تقرير مستويات المخزون')
@section('page-description', 'تقرير شامل لمستويات المخزون الحالية')

@section('content')
<!-- Page Header -->
<div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 20px; padding: 30px; margin-bottom: 30px; color: white; position: relative; overflow: hidden;">
    <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
    <div style="position: absolute; bottom: -30px; left: -30px; width: 150px; height: 150px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>
    
    <div style="position: relative; z-index: 2;">
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    <div style="background: rgba(255,255,255,0.2); border-radius: 15px; padding: 15px; margin-left: 20px;">
                        <i class="fas fa-boxes" style="font-size: 32px;"></i>
                    </div>
                    <div>
                        <h1 style="font-size: 32px; font-weight: 800; margin: 0; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                            مستويات المخزون 📦
                        </h1>
                        <p style="font-size: 18px; margin: 5px 0 0 0; opacity: 0.9;">
                            تقرير شامل لمستويات المخزون الحالية
                        </p>
                    </div>
                </div>
            </div>
            
            <div style="display: flex; gap: 15px;">
                <a href="{{ route('tenant.inventory.reports.index') }}" style="background: rgba(255,255,255,0.2); color: white; padding: 15px 25px; border-radius: 15px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3);">
                    <i class="fas fa-arrow-right"></i>
                    العودة للتقارير
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="content-card" style="margin-bottom: 25px;">
    <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
        <i class="fas fa-filter" style="color: #667eea; margin-left: 10px;"></i>
        فلترة التقرير
    </h3>
    
    <form method="GET" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; align-items: end;">
        <div>
            <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #4a5568;">المستودع</label>
            <select name="warehouse_id" data-custom-select data-placeholder="اختر المستودع..." data-searchable="true" style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 8px;">
                <option value="">جميع المستودعات</option>
                @foreach($warehouses as $warehouse)
                    <option value="{{ $warehouse->id }}" {{ request('warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                        {{ $warehouse->name }}
                    </option>
                @endforeach
            </select>
        </div>
        
        <div>
            <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #4a5568;">المنتج</label>
            <select name="product_id" style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 8px;">
                <option value="">جميع المنتجات</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                        {{ $product->name }}
                    </option>
                @endforeach
            </select>
        </div>
        
        <div>
            <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #4a5568;">الحالة</label>
            <select name="status" style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 8px;">
                <option value="">جميع الحالات</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>نشط</option>
                <option value="quarantine" {{ request('status') === 'quarantine' ? 'selected' : '' }}>حجر صحي</option>
                <option value="damaged" {{ request('status') === 'damaged' ? 'selected' : '' }}>تالف</option>
                <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>منتهي الصلاحية</option>
            </select>
        </div>
        
        <div style="display: flex; gap: 10px;">
            <button type="submit" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 10px 20px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-search"></i>
                تطبيق الفلتر
            </button>
            <a href="{{ route('tenant.inventory.reports.stock-levels') }}" style="background: #6b7280; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-times"></i>
                إلغاء
            </a>
        </div>
    </form>
</div>

<!-- Stock Levels Report -->
<div class="content-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin: 0; display: flex; align-items: center;">
            <i class="fas fa-list" style="color: #667eea; margin-left: 10px;"></i>
            مستويات المخزون ({{ $stockData->count() }} منتج)
        </h3>
        
        <div style="display: flex; gap: 10px;">
            <button onclick="exportToExcel()" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 10px 15px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-file-excel"></i>
                تصدير Excel
            </button>
            <button onclick="printReport()" style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); color: white; padding: 10px 15px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-print"></i>
                طباعة
            </button>
        </div>
    </div>
    
    @if($stockData->count() > 0)
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;" id="stockTable">
                <thead>
                    <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                        <th style="padding: 15px; text-align: right; font-weight: 600; color: #4a5568;">المنتج</th>
                        <th style="padding: 15px; text-align: center; font-weight: 600; color: #4a5568;">الكمية الإجمالية</th>
                        <th style="padding: 15px; text-align: center; font-weight: 600; color: #4a5568;">الكمية المتاحة</th>
                        <th style="padding: 15px; text-align: center; font-weight: 600; color: #4a5568;">الكمية المحجوزة</th>
                        <th style="padding: 15px; text-align: center; font-weight: 600; color: #4a5568;">القيمة الإجمالية</th>
                        <th style="padding: 15px; text-align: center; font-weight: 600; color: #4a5568;">المستودعات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($stockData as $data)
                        <tr style="border-bottom: 1px solid #e2e8f0; transition: background-color 0.3s ease;" 
                            onmouseover="this.style.backgroundColor='#f8fafc'" 
                            onmouseout="this.style.backgroundColor='transparent'">
                            <td style="padding: 15px;">
                                <div style="font-weight: 600; color: #2d3748;">{{ $data['product']->name }}</div>
                                <div style="font-size: 12px; color: #6b7280;">{{ $data['product']->code }}</div>
                                @if($data['product']->category)
                                    <div style="font-size: 12px; color: #6b7280;">{{ $data['product']->category }}</div>
                                @endif
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <div style="font-weight: 600; color: #2d3748; font-size: 16px;">{{ number_format($data['total_quantity'], 0) }}</div>
                                <div style="font-size: 12px; color: #6b7280;">{{ $data['product']->unit ?? 'وحدة' }}</div>
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <div style="font-weight: 600; color: #059669; font-size: 16px;">{{ number_format($data['available_quantity'], 0) }}</div>
                                @if($data['available_quantity'] <= ($data['product']->min_stock_level ?? 0))
                                    <div style="font-size: 12px; color: #ef4444; font-weight: 600;">
                                        <i class="fas fa-exclamation-triangle"></i> مخزون منخفض
                                    </div>
                                @endif
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <div style="font-weight: 600; color: #f59e0b; font-size: 16px;">{{ number_format($data['reserved_quantity'], 0) }}</div>
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <div style="font-weight: 600; color: #3b82f6; font-size: 16px;">{{ number_format($data['total_value'], 0) }} د.ع</div>
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <div style="display: flex; flex-direction: column; gap: 5px;">
                                    @foreach($data['warehouses'] as $warehouseData)
                                        <div style="background: #f8fafc; padding: 5px 8px; border-radius: 6px; font-size: 12px;">
                                            <div style="font-weight: 600; color: #2d3748;">{{ $warehouseData['warehouse']->name }}</div>
                                            <div style="color: #6b7280;">{{ number_format($warehouseData['quantity'], 0) }} ({{ number_format($warehouseData['available'], 0) }} متاح)</div>
                                        </div>
                                    @endforeach
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr style="background: #f8fafc; border-top: 2px solid #e2e8f0; font-weight: 600;">
                        <td style="padding: 15px; color: #2d3748;">الإجمالي</td>
                        <td style="padding: 15px; text-align: center; color: #2d3748;">{{ number_format($stockData->sum('total_quantity'), 0) }}</td>
                        <td style="padding: 15px; text-align: center; color: #059669;">{{ number_format($stockData->sum('available_quantity'), 0) }}</td>
                        <td style="padding: 15px; text-align: center; color: #f59e0b;">{{ number_format($stockData->sum('reserved_quantity'), 0) }}</td>
                        <td style="padding: 15px; text-align: center; color: #3b82f6;">{{ number_format($stockData->sum('total_value'), 0) }} د.ع</td>
                        <td style="padding: 15px; text-align: center; color: #6b7280;">-</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    @else
        <div style="text-align: center; padding: 40px; color: #6b7280;">
            <i class="fas fa-boxes" style="font-size: 48px; margin-bottom: 15px; opacity: 0.5;"></i>
            <h3 style="margin: 0 0 10px 0;">لا توجد بيانات مخزون</h3>
            <p style="margin: 0;">لم يتم العثور على أي بيانات مخزون تطابق معايير البحث</p>
        </div>
    @endif
</div>

@push('scripts')
<script>
function exportToExcel() {
    // Create a simple CSV export
    let csv = 'المنتج,الكود,الكمية الإجمالية,الكمية المتاحة,الكمية المحجوزة,القيمة الإجمالية\n';

    // Get data from table
    const table = document.querySelector('.table tbody');
    const rows = table.querySelectorAll('tr');

    rows.forEach(row => {
        const cells = row.querySelectorAll('td');
        if (cells.length >= 6) {
            const productName = cells[0].textContent.trim();
            const productCode = cells[1].textContent.trim();
            const totalQty = cells[2].textContent.trim();
            const availableQty = cells[3].textContent.trim();
            const reservedQty = cells[4].textContent.trim();
            const totalValue = cells[5].textContent.trim();

            csv += `"${productName}","${productCode}","${totalQty}","${availableQty}","${reservedQty}","${totalValue}"\n`;
        }
    });
    
    const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    const url = URL.createObjectURL(blob);
    link.setAttribute('href', url);
    link.setAttribute('download', 'stock_levels_report.csv');
    link.style.visibility = 'hidden';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

function printReport() {
    const printContent = document.getElementById('stockTable').outerHTML;
    const printWindow = window.open('', '', 'height=600,width=800');
    printWindow.document.write('<html><head><title>تقرير مستويات المخزون</title>');
    printWindow.document.write('<style>table { width: 100%; border-collapse: collapse; font-family: Arial, sans-serif; } th, td { border: 1px solid #ddd; padding: 8px; text-align: right; } th { background-color: #f2f2f2; font-weight: bold; } @media print { body { margin: 0; } }</style>');
    printWindow.document.write('</head><body>');
    printWindow.document.write('<h1 style="text-align: center; margin-bottom: 20px;">تقرير مستويات المخزون</h1>');
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
@endpush
@endsection
