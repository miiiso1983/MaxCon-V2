@extends('layouts.modern')

@section('page-title', 'إدارة المنتجات')
@section('page-description', 'إدارة شاملة لكتالوج المنتجات الدوائية')

@section('content')
<!-- Page Header -->
<div style="background: linear-gradient(135deg, #9f7aea 0%, #805ad5 100%); border-radius: 20px; padding: 30px; margin-bottom: 30px; color: white; position: relative; overflow: hidden;">
    <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
    <div style="position: absolute; bottom: -30px; left: -30px; width: 150px; height: 150px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>
    
    <div style="position: relative; z-index: 2;">
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    <div style="background: rgba(255,255,255,0.2); border-radius: 15px; padding: 15px; margin-left: 20px;">
                        <i class="fas fa-pills" style="font-size: 32px;"></i>
                    </div>
                    <div>
                        <h1 style="font-size: 32px; font-weight: 800; margin: 0; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                            إدارة المنتجات 💊
                        </h1>
                        <p style="font-size: 18px; margin: 5px 0 0 0; opacity: 0.9;">
                            كتالوج شامل للمنتجات الدوائية مع تتبع المخزون
                        </p>
                    </div>
                </div>
                
                <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-pills" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px; font-weight: 600;">{{ $stats['total'] }} منتج</span>
                    </div>
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-check-circle" style="margin-left: 8px; color: #4ade80;"></i>
                        <span style="font-size: 14px;">{{ $stats['active'] }} نشط</span>
                    </div>
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-exclamation-triangle" style="margin-left: 8px; color: #fbbf24;"></i>
                        <span style="font-size: 14px;">{{ $stats['low_stock'] }} مخزون منخفض</span>
                    </div>
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-clock" style="margin-left: 8px; color: #f87171;"></i>
                        <span style="font-size: 14px;">{{ $stats['expired'] }} منتهي الصلاحية</span>
                    </div>
                </div>
            </div>
            
            <div style="display: flex; gap: 15px;">
                <a href="{{ route('tenant.sales.products.import') }}" style="background: rgba(255,255,255,0.2); color: white; padding: 15px 25px; border-radius: 15px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3); transition: all 0.3s ease;"
                   onmouseover="this.style.background='rgba(255,255,255,0.3)'; this.style.transform='translateY(-2px)';"
                   onmouseout="this.style.background='rgba(255,255,255,0.2)'; this.style.transform='translateY(0)';">
                    <i class="fas fa-file-excel"></i>
                    استيراد من Excel
                </a>
                <a href="{{ route('tenant.sales.products.create') }}" style="background: rgba(255,255,255,0.2); color: white; padding: 15px 25px; border-radius: 15px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3); transition: all 0.3s ease;"
                   onmouseover="this.style.background='rgba(255,255,255,0.3)'; this.style.transform='translateY(-2px)';"
                   onmouseout="this.style.background='rgba(255,255,255,0.2)'; this.style.transform='translateY(0)';">
                    <i class="fas fa-plus"></i>
                    إضافة منتج جديد
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="content-card" style="margin-bottom: 25px;">
    <form method="GET" action="{{ route('tenant.sales.products.index') }}">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 20px;">
            <!-- Search -->
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">البحث</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                       style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;" 
                       placeholder="اسم المنتج، الكود، الباركود...">
            </div>
            
            <!-- Category Filter -->
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">الفئة</label>
                <select name="category" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;">
                    <option value="">جميع الفئات</option>
                    @foreach($categories as $category)
                        <option value="{{ $category }}" {{ request('category') === $category ? 'selected' : '' }}>
                            {{ $category }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <!-- Status Filter -->
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">الحالة</label>
                <select name="is_active" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;">
                    <option value="">جميع الحالات</option>
                    <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>نشط</option>
                    <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>غير نشط</option>
                </select>
            </div>
        </div>
        
        <div style="display: flex; gap: 15px;">
            <button type="submit" class="btn-purple" style="padding: 12px 24px;">
                <i class="fas fa-search"></i>
                بحث
            </button>
            <a href="{{ route('tenant.sales.products.index') }}" class="btn-gray" style="padding: 12px 24px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
                <i class="fas fa-times"></i>
                إعادة تعيين
            </a>
        </div>
    </form>
</div>

<!-- Products Table -->
<div class="content-card">
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f7fafc;">
                    <th style="padding: 12px; text-align: right; font-weight: 600; color: #4a5568; border-bottom: 1px solid #e2e8f0;">المنتج</th>
                    <th style="padding: 12px; text-align: right; font-weight: 600; color: #4a5568; border-bottom: 1px solid #e2e8f0;">الفئة</th>
                    <th style="padding: 12px; text-align: right; font-weight: 600; color: #4a5568; border-bottom: 1px solid #e2e8f0;">الأسعار</th>
                    <th style="padding: 12px; text-align: right; font-weight: 600; color: #4a5568; border-bottom: 1px solid #e2e8f0;">المخزون</th>
                    <th style="padding: 12px; text-align: right; font-weight: 600; color: #4a5568; border-bottom: 1px solid #e2e8f0;">الدفعة والصلاحية</th>
                    <th style="padding: 12px; text-align: right; font-weight: 600; color: #4a5568; border-bottom: 1px solid #e2e8f0;">الحالة</th>
                    <th style="padding: 12px; text-align: right; font-weight: 600; color: #4a5568; border-bottom: 1px solid #e2e8f0;">الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                <tr style="transition: all 0.3s ease;" onmouseover="this.style.background='#f7fafc';" onmouseout="this.style.background='white';">
                    <td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">
                        <div style="display: flex; align-items: center;">
                            <div style="width: 50px; height: 50px; border-radius: 12px; background: linear-gradient(135deg, #9f7aea 0%, #805ad5 100%); color: white; display: flex; align-items: center; justify-content: center; margin-left: 15px; font-weight: 700; font-size: 18px;">
                                💊
                            </div>
                            <div>
                                <div style="font-weight: 600; color: #2d3748; margin-bottom: 2px;">{{ $product->name }}</div>
                                <div style="font-size: 12px; color: #718096;">{{ $product->product_code }}</div>
                                @if($product->description)
                                    <div style="font-size: 11px; color: #9f7aea;">{{ Str::limit($product->description, 50) }}</div>
                                @endif
                                @if($product->barcode)
                                    <div style="font-size: 10px; color: #6b7280;">{{ $product->barcode }}</div>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">
                        <span style="padding: 4px 8px; border-radius: 12px; font-size: 11px; font-weight: 600; background: #f3e8ff; color: #7c3aed;">
                            {{ $product->category }}
                        </span>
                        @if($product->manufacturer)
                            <div style="font-size: 11px; color: #6b7280; margin-top: 4px;">{{ $product->manufacturer }}</div>
                        @endif
                    </td>
                    <td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">
                        <div style="font-weight: 600; color: #059669; margin-bottom: 2px;">
                            {{ number_format($product->selling_price, 2) }} ر.س
                        </div>
                        <div style="font-size: 12px; color: #6b7280;">
                            تكلفة: {{ number_format($product->cost_price ?? 0, 2) }} ر.س
                        </div>
                        <div style="font-size: 11px; color: #9f7aea;">
                            ربح: {{ number_format(($product->selling_price ?? 0) - ($product->cost_price ?? 0), 2) }} ر.س
                        </div>
                    </td>
                    <td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">
                        <div style="font-weight: 600; color: {{ ($product->stock_quantity ?? 0) <= ($product->min_stock_level ?? 0) ? '#f56565' : '#059669' }};">
                            {{ $product->stock_quantity ?? 0 }} {{ $product->unit_of_measure ?? 'قطعة' }}
                        </div>
                        <div style="font-size: 12px; color: #6b7280;">
                            الحد الأدنى: {{ $product->min_stock_level ?? 0 }} {{ $product->unit_of_measure ?? 'قطعة' }}
                        </div>
                        @if(($product->stock_quantity ?? 0) <= ($product->min_stock_level ?? 0))
                            <div style="font-size: 11px; color: #f56565; font-weight: 600;">
                                <i class="fas fa-exclamation-triangle"></i> مخزون منخفض
                            </div>
                        @endif
                    </td>
                    <td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">
                        @if($product->batch_number)
                            <div style="font-size: 12px; color: #4a5568; margin-bottom: 2px;">
                                دفعة: {{ $product->batch_number }}
                            </div>
                        @endif
                        @if($product->expiry_date)
                            <div style="font-size: 12px; color: {{ $product->expiry_date->isPast() ? '#f56565' : '#4a5568' }};">
                                انتهاء: {{ $product->expiry_date->format('Y/m/d') }}
                            </div>
                            @if($product->expiry_date->isPast())
                                <div style="font-size: 11px; color: #f56565; font-weight: 600;">
                                    <i class="fas fa-clock"></i> منتهي الصلاحية
                                </div>
                            @elseif($product->expiry_date->diffInDays() <= 30)
                                <div style="font-size: 11px; color: #f59e0b; font-weight: 600;">
                                    <i class="fas fa-exclamation-triangle"></i> ينتهي قريباً
                                </div>
                            @endif
                        @endif
                    </td>
                    <td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">
                        @if($product->is_active)
                            <span class="status-badge status-active">نشط</span>
                        @else
                            <span class="status-badge status-inactive">غير نشط</span>
                        @endif
                    </td>
                    <td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">
                        <div style="display: flex; gap: 8px; align-items: center;">
                            <a href="{{ route('tenant.sales.products.show', $product) }}" 
                               style="background: none; border: none; color: #4299e1; cursor: pointer; padding: 4px;" 
                               title="عرض التفاصيل">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('tenant.sales.products.edit', $product) }}" 
                               style="background: none; border: none; color: #9f7aea; cursor: pointer; padding: 4px;" 
                               title="تعديل">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button onclick="confirmDelete({{ $product->id }}, '{{ $product->name }}')" 
                                    style="background: none; border: none; color: #f56565; cursor: pointer; padding: 4px;" 
                                    title="حذف">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="padding: 40px; text-align: center; color: #718096;">
                        <div style="display: flex; flex-direction: column; align-items: center;">
                            <i class="fas fa-pills" style="font-size: 48px; color: #e2e8f0; margin-bottom: 15px;"></i>
                            <p style="font-size: 18px; font-weight: 600; margin: 0 0 5px 0;">لا توجد منتجات</p>
                            <p style="font-size: 14px; margin: 0;">ابدأ بإضافة منتج جديد</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($products->hasPages())
    <div style="margin-top: 20px;">
        {{ $products->appends(request()->query())->links() }}
    </div>
    @endif
</div>

@push('scripts')
<script>
function confirmDelete(productId, productName) {
    if (confirm(`هل أنت متأكد من حذف المنتج "${productName}"؟`)) {
        // Create and submit delete form
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/tenant/sales/products/${productId}`;
        
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        
        const tokenInput = document.createElement('input');
        tokenInput.type = 'hidden';
        tokenInput.name = '_token';
        tokenInput.value = '{{ csrf_token() }}';
        
        form.appendChild(methodInput);
        form.appendChild(tokenInput);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endpush
@endsection
