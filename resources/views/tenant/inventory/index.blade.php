@extends('layouts.modern')

@section('page-title', 'إدارة المخزون')
@section('page-description', 'إدارة شاملة لجميع عناصر المخزون')

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
                            إدارة المخزون 📦
                        </h1>
                        <p style="font-size: 18px; margin: 5px 0 0 0; opacity: 0.9;">
                            إدارة شاملة لجميع عناصر المخزون
                        </p>
                    </div>
                </div>
                
                <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-list" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px; font-weight: 600;">{{ $stats['total_items'] }} عنصر</span>
                    </div>
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-cubes" style="margin-left: 8px; color: #4ade80;"></i>
                        <span style="font-size: 14px;">{{ number_format($stats['total_quantity'], 0) }} إجمالي</span>
                    </div>
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-check-circle" style="margin-left: 8px; color: #34d399;"></i>
                        <span style="font-size: 14px;">{{ number_format($stats['available_quantity'], 0) }} متاح</span>
                    </div>
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-exclamation-triangle" style="margin-left: 8px; color: #f87171;"></i>
                        <span style="font-size: 14px;">{{ $stats['low_stock_items'] }} منخفض</span>
                    </div>
                </div>
            </div>
            
            <div style="display: flex; gap: 15px;">
                <a href="{{ route('tenant.inventory.create') }}" style="background: rgba(255,255,255,0.2); color: white; padding: 15px 25px; border-radius: 15px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3);">
                    <i class="fas fa-plus"></i>
                    إضافة مخزون
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px;">
    <div style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); border-radius: 15px; padding: 20px; color: white; position: relative; overflow: hidden;">
        <div style="position: absolute; top: -10px; right: -10px; width: 60px; height: 60px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
        <div style="position: relative; z-index: 2;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                <i class="fas fa-cubes" style="font-size: 24px; opacity: 0.8;"></i>
                <span style="font-size: 12px; opacity: 0.8;">إجمالي الكمية</span>
            </div>
            <div style="font-size: 28px; font-weight: 700;">{{ number_format($stats['total_quantity'], 0) }}</div>
        </div>
    </div>
    
    <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius: 15px; padding: 20px; color: white; position: relative; overflow: hidden;">
        <div style="position: absolute; top: -10px; right: -10px; width: 60px; height: 60px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
        <div style="position: relative; z-index: 2;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                <i class="fas fa-check-circle" style="font-size: 24px; opacity: 0.8;"></i>
                <span style="font-size: 12px; opacity: 0.8;">الكمية المتاحة</span>
            </div>
            <div style="font-size: 28px; font-weight: 700;">{{ number_format($stats['available_quantity'], 0) }}</div>
        </div>
    </div>
    
    <div style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); border-radius: 15px; padding: 20px; color: white; position: relative; overflow: hidden;">
        <div style="position: absolute; top: -10px; right: -10px; width: 60px; height: 60px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
        <div style="position: relative; z-index: 2;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                <i class="fas fa-lock" style="font-size: 24px; opacity: 0.8;"></i>
                <span style="font-size: 12px; opacity: 0.8;">الكمية المحجوزة</span>
            </div>
            <div style="font-size: 28px; font-weight: 700;">{{ number_format($stats['reserved_quantity'], 0) }}</div>
        </div>
    </div>
    
    <div style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); border-radius: 15px; padding: 20px; color: white; position: relative; overflow: hidden;">
        <div style="position: absolute; top: -10px; right: -10px; width: 60px; height: 60px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
        <div style="position: relative; z-index: 2;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                <i class="fas fa-exclamation-triangle" style="font-size: 24px; opacity: 0.8;"></i>
                <span style="font-size: 12px; opacity: 0.8;">مخزون منخفض</span>
            </div>
            <div style="font-size: 28px; font-weight: 700;">{{ $stats['low_stock_items'] }}</div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="content-card" style="margin-bottom: 25px;">
    <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
        <i class="fas fa-filter" style="color: #667eea; margin-left: 10px;"></i>
        فلترة وبحث
    </h3>
    
    <form method="GET" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; align-items: end;">
        <div>
            <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #4a5568;">البحث</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="اسم المنتج أو الكود..." style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 8px;">
        </div>
        
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
            <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #4a5568;">الحالة</label>
            <select name="status" style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 8px;">
                <option value="">جميع الحالات</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>نشط</option>
                <option value="quarantine" {{ request('status') === 'quarantine' ? 'selected' : '' }}>حجر صحي</option>
                <option value="damaged" {{ request('status') === 'damaged' ? 'selected' : '' }}>تالف</option>
                <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>منتهي الصلاحية</option>
            </select>
        </div>
        
        <div>
            <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #4a5568;">الموقع</label>
            <input type="text" name="location" value="{{ request('location') }}" placeholder="كود الموقع..." style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 8px;">
        </div>
        
        <div style="display: flex; gap: 10px;">
            <button type="submit" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 10px 20px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-search"></i>
                بحث
            </button>
            <a href="{{ route('tenant.inventory.index') }}" style="background: #6b7280; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-times"></i>
                إلغاء
            </a>
        </div>
    </form>
</div>

<!-- Inventory List -->
<div class="content-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin: 0; display: flex; align-items: center;">
            <i class="fas fa-list" style="color: #667eea; margin-left: 10px;"></i>
            قائمة المخزون ({{ $inventory->total() }})
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
    
    @if($inventory->count() > 0)
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;" id="inventoryTable">
                <thead>
                    <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                        <th style="padding: 15px; text-align: right; font-weight: 600; color: #4a5568;">المنتج</th>
                        <th style="padding: 15px; text-align: center; font-weight: 600; color: #4a5568;">المستودع</th>
                        <th style="padding: 15px; text-align: center; font-weight: 600; color: #4a5568;">الموقع</th>
                        <th style="padding: 15px; text-align: center; font-weight: 600; color: #4a5568;">الكمية الإجمالية</th>
                        <th style="padding: 15px; text-align: center; font-weight: 600; color: #4a5568;">الكمية المتاحة</th>
                        <th style="padding: 15px; text-align: center; font-weight: 600; color: #4a5568;">الكمية المحجوزة</th>
                        <th style="padding: 15px; text-align: center; font-weight: 600; color: #4a5568;">الحالة</th>
                        <th style="padding: 15px; text-align: center; font-weight: 600; color: #4a5568;">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($inventory as $item)
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
                                @if($item->location_code)
                                    <span style="background: #f3f4f6; color: #374151; padding: 4px 8px; border-radius: 6px; font-size: 12px; font-weight: 600;">
                                        {{ $item->location_code }}
                                    </span>
                                @else
                                    <span style="color: #6b7280;">-</span>
                                @endif
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
                                <div style="display: flex; gap: 5px; justify-content: center;">
                                    <a href="{{ route('tenant.inventory.show', $item) }}" style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); color: white; padding: 6px 10px; border-radius: 6px; text-decoration: none; font-size: 12px; font-weight: 600;">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('tenant.inventory.edit', $item) }}" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; padding: 6px 10px; border-radius: 6px; text-decoration: none; font-size: 12px; font-weight: 600;">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div style="margin-top: 30px;">
            {{ $inventory->links() }}
        </div>
    @else
        <div style="text-align: center; padding: 40px; color: #6b7280;">
            <i class="fas fa-boxes" style="font-size: 48px; margin-bottom: 15px; opacity: 0.5;"></i>
            <h3 style="margin: 0 0 10px 0;">لا توجد عناصر مخزون</h3>
            <p style="margin: 0;">لم يتم العثور على أي عناصر تطابق معايير البحث</p>
            <a href="{{ route('tenant.inventory.create') }}" style="display: inline-block; margin-top: 15px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600;">
                <i class="fas fa-plus"></i> إضافة مخزون جديد
            </a>
        </div>
    @endif
</div>

@push('scripts')
<script>
function exportToExcel() {
    let csv = 'المنتج,الكود,المستودع,الموقع,الكمية الإجمالية,الكمية المتاحة,الكمية المحجوزة,الحالة\n';
    
    @foreach($inventory as $item)
        csv += '"{{ $item->product->name }}","{{ $item->product->code }}","{{ $item->warehouse->name }}","{{ $item->location_code ?? '' }}",{{ $item->quantity }},{{ $item->available_quantity }},{{ $item->reserved_quantity }},"{{ $item->status }}"\n';
    @endforeach
    
    const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    const url = URL.createObjectURL(blob);
    link.setAttribute('href', url);
    link.setAttribute('download', 'inventory_report.csv');
    link.style.visibility = 'hidden';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

function printReport() {
    const printContent = document.getElementById('inventoryTable').outerHTML;
    const printWindow = window.open('', '', 'height=600,width=800');
    printWindow.document.write('<html><head><title>تقرير المخزون</title>');
    printWindow.document.write('<style>table { width: 100%; border-collapse: collapse; font-family: Arial, sans-serif; } th, td { border: 1px solid #ddd; padding: 8px; text-align: right; } th { background-color: #f2f2f2; font-weight: bold; } @media print { body { margin: 0; } }</style>');
    printWindow.document.write('</head><body>');
    printWindow.document.write('<h1 style="text-align: center; margin-bottom: 20px;">تقرير المخزون</h1>');
    printWindow.document.write('<p style="text-align: center; margin-bottom: 20px;">تاريخ التقرير: ' + new Date().toLocaleDateString('ar-SA') + '</p>');
    printWindow.document.write(printContent);
    printWindow.document.write('</body></html>');
    printWindow.document.close();
    printWindow.print();
}
</script>
@endpush
@endsection
