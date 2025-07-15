@extends('layouts.modern')

@section('page-title', 'تقرير المخزون المنخفض')
@section('page-description', 'المنتجات التي تحتاج إعادة تموين')

@section('content')
<!-- Page Header -->
<div style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); border-radius: 20px; padding: 30px; margin-bottom: 30px; color: white; position: relative; overflow: hidden;">
    <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
    <div style="position: absolute; bottom: -30px; left: -30px; width: 150px; height: 150px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>
    
    <div style="position: relative; z-index: 2;">
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    <div style="background: rgba(255,255,255,0.2); border-radius: 15px; padding: 15px; margin-left: 20px;">
                        <i class="fas fa-exclamation-triangle" style="font-size: 32px;"></i>
                    </div>
                    <div>
                        <h1 style="font-size: 32px; font-weight: 800; margin: 0; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                            المخزون المنخفض ⚠️
                        </h1>
                        <p style="font-size: 18px; margin: 5px 0 0 0; opacity: 0.9;">
                            المنتجات التي تحتاج إعادة تموين
                        </p>
                    </div>
                </div>
                
                <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-exclamation-triangle" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px; font-weight: 600;">{{ $lowStockItems->count() }} منتج</span>
                    </div>
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-shopping-cart" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px;">يحتاج إعادة طلب</span>
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

<!-- Low Stock Items -->
<div class="content-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin: 0; display: flex; align-items: center;">
            <i class="fas fa-list" style="color: #f59e0b; margin-left: 10px;"></i>
            المنتجات منخفضة المخزون ({{ $lowStockItems->count() }})
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
    
    @if($lowStockItems->count() > 0)
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;" id="lowStockTable">
                <thead>
                    <tr style="background: #fef3c7; border-bottom: 2px solid #f59e0b;">
                        <th style="padding: 15px; text-align: right; font-weight: 600; color: #92400e;">المنتج</th>
                        <th style="padding: 15px; text-align: center; font-weight: 600; color: #92400e;">المستودع</th>
                        <th style="padding: 15px; text-align: center; font-weight: 600; color: #92400e;">الكمية المتاحة</th>
                        <th style="padding: 15px; text-align: center; font-weight: 600; color: #92400e;">الحد الأدنى</th>
                        <th style="padding: 15px; text-align: center; font-weight: 600; color: #92400e;">النقص</th>
                        <th style="padding: 15px; text-align: center; font-weight: 600; color: #92400e;">مستوى التحذير</th>
                        <th style="padding: 15px; text-align: center; font-weight: 600; color: #92400e;">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($lowStockItems as $item)
                        @php
                            $minStock = $item->product->min_stock_level ?? 0;
                            $shortage = $minStock - $item->available_quantity;
                            $warningLevel = $item->available_quantity <= 0 ? 'critical' : ($item->available_quantity <= $minStock * 0.5 ? 'high' : 'medium');
                        @endphp
                        <tr style="border-bottom: 1px solid #fde68a; transition: background-color 0.3s ease;" 
                            onmouseover="this.style.backgroundColor='#fef3c7'" 
                            onmouseout="this.style.backgroundColor='transparent'">
                            <td style="padding: 15px;">
                                <div style="font-weight: 600; color: #2d3748;">{{ $item->product->name }}</div>
                                <div style="font-size: 12px; color: #6b7280;">{{ $item->product->code }}</div>
                                @if($item->product->category)
                                    <div style="font-size: 12px; color: #6b7280;">{{ $item->product->category }}</div>
                                @endif
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <div style="font-weight: 600; color: #2d3748;">{{ $item->warehouse->name }}</div>
                                <div style="font-size: 12px; color: #6b7280;">{{ $item->warehouse->code }}</div>
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <div style="font-weight: 600; color: {{ $item->available_quantity <= 0 ? '#dc2626' : '#f59e0b' }}; font-size: 16px;">
                                    {{ number_format($item->available_quantity, 0) }}
                                </div>
                                <div style="font-size: 12px; color: #6b7280;">{{ $item->product->unit ?? 'وحدة' }}</div>
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <div style="font-weight: 600; color: #2d3748;">{{ number_format($minStock, 0) }}</div>
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <div style="font-weight: 600; color: #dc2626; font-size: 16px;">{{ number_format($shortage, 0) }}</div>
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                @if($warningLevel === 'critical')
                                    <span style="background: #fee2e2; color: #991b1b; padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                                        <i class="fas fa-times-circle"></i> نفاد مخزون
                                    </span>
                                @elseif($warningLevel === 'high')
                                    <span style="background: #fef3c7; color: #92400e; padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                                        <i class="fas fa-exclamation-triangle"></i> عالي
                                    </span>
                                @else
                                    <span style="background: #fef3c7; color: #92400e; padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                                        <i class="fas fa-exclamation"></i> متوسط
                                    </span>
                                @endif
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <div style="display: flex; gap: 5px; justify-content: center;">
                                    <button onclick="createPurchaseOrder({{ $item->product->id }})" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 6px 10px; border: none; border-radius: 6px; font-size: 12px; font-weight: 600; cursor: pointer;">
                                        <i class="fas fa-shopping-cart"></i> طلب
                                    </button>
                                    <button onclick="transferStock({{ $item->product->id }})" style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); color: white; padding: 6px 10px; border: none; border-radius: 6px; font-size: 12px; font-weight: 600; cursor: pointer;">
                                        <i class="fas fa-exchange-alt"></i> تحويل
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Summary Statistics -->
        <div style="margin-top: 30px; display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
            @php
                $criticalItems = $lowStockItems->filter(function($item) {
                    return $item->available_quantity <= 0;
                })->count();
                
                $highWarningItems = $lowStockItems->filter(function($item) {
                    $minStock = $item->product->min_stock_level ?? 0;
                    return $item->available_quantity > 0 && $item->available_quantity <= $minStock * 0.5;
                })->count();
                
                $totalShortage = $lowStockItems->sum(function($item) {
                    $minStock = $item->product->min_stock_level ?? 0;
                    return max(0, $minStock - $item->available_quantity);
                });
            @endphp
            
            <div style="text-align: center; padding: 20px; background: #fee2e2; border-radius: 12px; border-left: 4px solid #dc2626;">
                <div style="font-size: 28px; font-weight: 700; color: #dc2626;">{{ $criticalItems }}</div>
                <div style="font-size: 14px; color: #991b1b; font-weight: 600;">نفاد مخزون</div>
            </div>
            
            <div style="text-align: center; padding: 20px; background: #fef3c7; border-radius: 12px; border-left: 4px solid #f59e0b;">
                <div style="font-size: 28px; font-weight: 700; color: #f59e0b;">{{ $highWarningItems }}</div>
                <div style="font-size: 14px; color: #92400e; font-weight: 600;">تحذير عالي</div>
            </div>
            
            <div style="text-align: center; padding: 20px; background: #dbeafe; border-radius: 12px; border-left: 4px solid #3b82f6;">
                <div style="font-size: 28px; font-weight: 700; color: #3b82f6;">{{ number_format($totalShortage, 0) }}</div>
                <div style="font-size: 14px; color: #1e40af; font-weight: 600;">إجمالي النقص</div>
            </div>
            
            <div style="text-align: center; padding: 20px; background: #f0fdf4; border-radius: 12px; border-left: 4px solid #10b981;">
                <div style="font-size: 28px; font-weight: 700; color: #10b981;">{{ $lowStockItems->count() }}</div>
                <div style="font-size: 14px; color: #065f46; font-weight: 600;">إجمالي المنتجات</div>
            </div>
        </div>
    @else
        <div style="text-align: center; padding: 40px; color: #6b7280;">
            <i class="fas fa-check-circle" style="font-size: 48px; margin-bottom: 15px; opacity: 0.5; color: #10b981;"></i>
            <h3 style="margin: 0 0 10px 0; color: #10b981;">ممتاز! لا توجد منتجات منخفضة المخزون</h3>
            <p style="margin: 0;">جميع المنتجات لديها مخزون كافي</p>
        </div>
    @endif
</div>

@push('scripts')
<script>
function exportToExcel() {
    let csv = 'المنتج,الكود,المستودع,الكمية المتاحة,الحد الأدنى,النقص\n';
    
    @foreach($lowStockItems as $item)
        @php
            $minStock = $item->product->min_stock_level ?? 0;
            $shortage = $minStock - $item->available_quantity;
        @endphp
        csv += '"{{ $item->product->name }}","{{ $item->product->code }}","{{ $item->warehouse->name }}",{{ $item->available_quantity }},{{ $minStock }},{{ $shortage }}\n';
    @endforeach
    
    const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    const url = URL.createObjectURL(blob);
    link.setAttribute('href', url);
    link.setAttribute('download', 'low_stock_report.csv');
    link.style.visibility = 'hidden';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

function printReport() {
    const printContent = document.getElementById('lowStockTable').outerHTML;
    const printWindow = window.open('', '', 'height=600,width=800');
    printWindow.document.write('<html><head><title>تقرير المخزون المنخفض</title>');
    printWindow.document.write('<style>table { width: 100%; border-collapse: collapse; font-family: Arial, sans-serif; } th, td { border: 1px solid #ddd; padding: 8px; text-align: right; } th { background-color: #fef3c7; font-weight: bold; } @media print { body { margin: 0; } }</style>');
    printWindow.document.write('</head><body>');
    printWindow.document.write('<h1 style="text-align: center; margin-bottom: 20px;">تقرير المخزون المنخفض</h1>');
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

function createPurchaseOrder(productId) {
    alert('سيتم إنشاء أمر شراء للمنتج رقم: ' + productId);
    // In real implementation, this would redirect to purchase order creation
}

function transferStock(productId) {
    alert('سيتم تحويل مخزون للمنتج رقم: ' + productId);
    // In real implementation, this would open transfer modal
}
</script>
@endpush
@endsection
