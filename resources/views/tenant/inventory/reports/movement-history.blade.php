@extends('layouts.modern')

@section('page-title', 'تقرير تاريخ الحركات')
@section('page-description', 'تقرير مفصل لجميع حركات المخزون')

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
                        <i class="fas fa-exchange-alt" style="font-size: 32px;"></i>
                    </div>
                    <div>
                        <h1 style="font-size: 32px; font-weight: 800; margin: 0; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                            تاريخ الحركات 📈
                        </h1>
                        <p style="font-size: 18px; margin: 5px 0 0 0; opacity: 0.9;">
                            تقرير مفصل لجميع حركات المخزون
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
            <select name="warehouse_id" style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 8px;">
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
            <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #4a5568;">نوع الحركة</label>
            <select name="movement_type" style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 8px;">
                <option value="">جميع الأنواع</option>
                <option value="in" {{ request('movement_type') === 'in' ? 'selected' : '' }}>إدخال</option>
                <option value="out" {{ request('movement_type') === 'out' ? 'selected' : '' }}>إخراج</option>
                <option value="transfer_in" {{ request('movement_type') === 'transfer_in' ? 'selected' : '' }}>تحويل وارد</option>
                <option value="transfer_out" {{ request('movement_type') === 'transfer_out' ? 'selected' : '' }}>تحويل صادر</option>
                <option value="adjustment_in" {{ request('movement_type') === 'adjustment_in' ? 'selected' : '' }}>تعديل زيادة</option>
                <option value="adjustment_out" {{ request('movement_type') === 'adjustment_out' ? 'selected' : '' }}>تعديل نقص</option>
            </select>
        </div>
        
        <div>
            <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #4a5568;">من تاريخ</label>
            <input type="date" name="date_from" value="{{ request('date_from') }}" style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 8px;">
        </div>
        
        <div>
            <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #4a5568;">إلى تاريخ</label>
            <input type="date" name="date_to" value="{{ request('date_to') }}" style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 8px;">
        </div>
        
        <div style="display: flex; gap: 10px;">
            <button type="submit" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 10px 20px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-search"></i>
                تطبيق الفلتر
            </button>
            <a href="{{ route('tenant.inventory.reports.movement-history') }}" style="background: #6b7280; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-times"></i>
                إلغاء
            </a>
        </div>
    </form>
</div>

<!-- Movement History Report -->
<div class="content-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin: 0; display: flex; align-items: center;">
            <i class="fas fa-list" style="color: #667eea; margin-left: 10px;"></i>
            تاريخ الحركات ({{ $movements->total() }} حركة)
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
    
    @if($movements->count() > 0)
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;" id="movementsTable">
                <thead>
                    <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                        <th style="padding: 15px; text-align: right; font-weight: 600; color: #4a5568;">رقم الحركة</th>
                        <th style="padding: 15px; text-align: center; font-weight: 600; color: #4a5568;">التاريخ</th>
                        <th style="padding: 15px; text-align: center; font-weight: 600; color: #4a5568;">النوع</th>
                        <th style="padding: 15px; text-align: right; font-weight: 600; color: #4a5568;">المستودع</th>
                        <th style="padding: 15px; text-align: right; font-weight: 600; color: #4a5568;">المنتج</th>
                        <th style="padding: 15px; text-align: center; font-weight: 600; color: #4a5568;">الكمية</th>
                        <th style="padding: 15px; text-align: center; font-weight: 600; color: #4a5568;">التكلفة</th>
                        <th style="padding: 15px; text-align: right; font-weight: 600; color: #4a5568;">المسؤول</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($movements as $movement)
                        <tr style="border-bottom: 1px solid #e2e8f0; transition: background-color 0.3s ease;" 
                            onmouseover="this.style.backgroundColor='#f8fafc'" 
                            onmouseout="this.style.backgroundColor='transparent'">
                            <td style="padding: 15px;">
                                <div style="font-weight: 600; color: #2d3748;">{{ $movement->movement_number }}</div>
                                @if($movement->reference_number)
                                    <div style="font-size: 12px; color: #6b7280;">مرجع: {{ $movement->reference_number }}</div>
                                @endif
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <div style="font-weight: 600; color: #2d3748;">{{ $movement->movement_date->format('Y-m-d') }}</div>
                                <div style="font-size: 12px; color: #6b7280;">{{ $movement->movement_date->format('H:i') }}</div>
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <span style="background: {{ $movement->getMovementTypeColor() === 'success' ? '#d1fae5' : ($movement->getMovementTypeColor() === 'danger' ? '#fee2e2' : '#fef3c7') }}; 
                                             color: {{ $movement->getMovementTypeColor() === 'success' ? '#065f46' : ($movement->getMovementTypeColor() === 'danger' ? '#991b1b' : '#92400e') }}; 
                                             padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                                    {{ $movement->getMovementTypeLabel() }}
                                </span>
                            </td>
                            <td style="padding: 15px;">
                                <div style="font-weight: 600; color: #2d3748;">{{ $movement->warehouse->name }}</div>
                                <div style="font-size: 12px; color: #6b7280;">{{ $movement->warehouse->code }}</div>
                            </td>
                            <td style="padding: 15px;">
                                <div style="font-weight: 600; color: #2d3748;">{{ $movement->product->name }}</div>
                                <div style="font-size: 12px; color: #6b7280;">{{ $movement->product->code }}</div>
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <div style="font-weight: 600; color: {{ $movement->isInMovement() ? '#059669' : '#dc2626' }}; font-size: 16px;">
                                    {{ $movement->isInMovement() ? '+' : '-' }}{{ number_format($movement->quantity, 3) }}
                                </div>
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                @if($movement->total_cost > 0)
                                    <div style="font-weight: 600; color: #2d3748;">{{ number_format($movement->total_cost, 2) }} د.ع</div>
                                    <div style="font-size: 12px; color: #6b7280;">{{ number_format($movement->unit_cost, 2) }} / وحدة</div>
                                @else
                                    <span style="color: #6b7280;">-</span>
                                @endif
                            </td>
                            <td style="padding: 15px;">
                                @if($movement->createdBy)
                                    <div style="font-weight: 600; color: #2d3748;">{{ $movement->createdBy->name }}</div>
                                    <div style="font-size: 12px; color: #6b7280;">{{ $movement->created_at->format('H:i') }}</div>
                                @else
                                    <span style="color: #6b7280;">-</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div style="margin-top: 30px;">
            {{ $movements->links() }}
        </div>
    @else
        <div style="text-align: center; padding: 40px; color: #6b7280;">
            <i class="fas fa-exchange-alt" style="font-size: 48px; margin-bottom: 15px; opacity: 0.5;"></i>
            <h3 style="margin: 0 0 10px 0;">لا توجد حركات</h3>
            <p style="margin: 0;">لم يتم العثور على أي حركات تطابق معايير البحث</p>
        </div>
    @endif
</div>

@push('scripts')
<script>
function exportToExcel() {
    // Create a simple CSV export
    let csv = 'رقم الحركة,التاريخ,النوع,المستودع,المنتج,الكمية,التكلفة,المسؤول\n';
    
    @foreach($movements as $movement)
        csv += '"{{ $movement->movement_number }}","{{ $movement->movement_date->format('Y-m-d H:i') }}","{{ $movement->getMovementTypeLabel() }}","{{ $movement->warehouse->name }}","{{ $movement->product->name }}",{{ $movement->quantity }},{{ $movement->total_cost }},"{{ $movement->createdBy ? $movement->createdBy->name : '' }}"\n';
    @endforeach
    
    const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    const url = URL.createObjectURL(blob);
    link.setAttribute('href', url);
    link.setAttribute('download', 'movement_history_report.csv');
    link.style.visibility = 'hidden';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

function printReport() {
    const printContent = document.getElementById('movementsTable').outerHTML;
    const printWindow = window.open('', '', 'height=600,width=800');
    printWindow.document.write('<html><head><title>تقرير تاريخ الحركات</title>');
    printWindow.document.write('<style>table { width: 100%; border-collapse: collapse; font-family: Arial, sans-serif; } th, td { border: 1px solid #ddd; padding: 8px; text-align: right; } th { background-color: #f2f2f2; font-weight: bold; } @media print { body { margin: 0; } }</style>');
    printWindow.document.write('</head><body>');
    printWindow.document.write('<h1 style="text-align: center; margin-bottom: 20px;">تقرير تاريخ الحركات</h1>');
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
