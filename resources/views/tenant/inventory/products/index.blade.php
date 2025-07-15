@extends('layouts.modern')

@section('page-title', 'كتالوج المنتجات')
@section('page-description', 'إدارة شاملة لجميع المنتجات مع دعم الباركود والصور')

@section('content')
<!-- Page Header -->
<div style="background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%); border-radius: 20px; padding: 30px; margin-bottom: 30px; color: white; position: relative; overflow: hidden;">
    <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
    <div style="position: absolute; bottom: -30px; left: -30px; width: 150px; height: 150px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>
    
    <div style="position: relative; z-index: 2;">
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    <div style="background: rgba(255,255,255,0.2); border-radius: 15px; padding: 15px; margin-left: 20px;">
                        <i class="fas fa-cube" style="font-size: 32px;"></i>
                    </div>
                    <div>
                        <h1 style="font-size: 32px; font-weight: 800; margin: 0; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                            كتالوج المنتجات 📦
                        </h1>
                        <p style="font-size: 18px; margin: 5px 0 0 0; opacity: 0.9;">
                            إدارة شاملة للمنتجات مع تتبع المخزون والباركود
                        </p>
                    </div>
                </div>
                
                <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-boxes" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px;">{{ $stats['total'] }} منتج</span>
                    </div>
                    <div style="background: rgba(16, 185, 129, 0.2); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3);">
                        <i class="fas fa-check-circle" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px;">{{ $stats['active'] }} نشط</span>
                    </div>
                    <div style="background: rgba(239, 68, 68, 0.2); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3);">
                        <i class="fas fa-exclamation-triangle" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px;">{{ $stats['low_stock'] }} مخزون منخفض</span>
                    </div>
                    <div style="background: rgba(245, 158, 11, 0.2); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3);">
                        <i class="fas fa-times-circle" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px;">{{ $stats['out_of_stock'] }} نفد المخزون</span>
                    </div>
                </div>
            </div>
            
            <div style="display: flex; gap: 15px;">
                <a href="{{ route('tenant.inventory.products.create') }}" style="background: rgba(255,255,255,0.2); color: white; padding: 15px 25px; border-radius: 15px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3);">
                    <i class="fas fa-plus"></i>
                    منتج جديد
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Filters and Search -->
<div class="content-card" style="margin-bottom: 25px;">
    <form method="GET" action="{{ route('tenant.inventory.products.index') }}">
        <div style="display: grid; grid-template-columns: 2fr 1fr 1fr 1fr 1fr auto; gap: 15px; align-items: end;">
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">البحث</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                       style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 16px;"
                       placeholder="البحث في الاسم، الرمز، الباركود...">
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">الحالة</label>
                <select name="status" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 16px;">
                    <option value="">جميع الحالات</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>نشط</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>غير نشط</option>
                    <option value="discontinued" {{ request('status') === 'discontinued' ? 'selected' : '' }}>متوقف</option>
                    <option value="out_of_stock" {{ request('status') === 'out_of_stock' ? 'selected' : '' }}>نفد المخزون</option>
                </select>
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">الفئة</label>
                <select name="category_id" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 16px;">
                    <option value="">جميع الفئات</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">حالة المخزون</label>
                <select name="stock_status" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 16px;">
                    <option value="">جميع الحالات</option>
                    <option value="in_stock" {{ request('stock_status') === 'in_stock' ? 'selected' : '' }}>متوفر</option>
                    <option value="low_stock" {{ request('stock_status') === 'low_stock' ? 'selected' : '' }}>مخزون منخفض</option>
                    <option value="out_of_stock" {{ request('stock_status') === 'out_of_stock' ? 'selected' : '' }}>نفد المخزون</option>
                </select>
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">النوع</label>
                <select name="type" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 16px;">
                    <option value="">جميع الأنواع</option>
                    <option value="simple" {{ request('type') === 'simple' ? 'selected' : '' }}>بسيط</option>
                    <option value="variable" {{ request('type') === 'variable' ? 'selected' : '' }}>متغير</option>
                    <option value="grouped" {{ request('type') === 'grouped' ? 'selected' : '' }}>مجموعة</option>
                    <option value="external" {{ request('type') === 'external' ? 'selected' : '' }}>خارجي</option>
                </select>
            </div>
            
            <div style="display: flex; gap: 10px;">
                <button type="submit" style="background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%); color: white; padding: 12px 20px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                    <i class="fas fa-search"></i>
                </button>
                <a href="{{ route('tenant.inventory.products.index') }}" style="background: #6b7280; color: white; padding: 12px 20px; border-radius: 8px; text-decoration: none; display: flex; align-items: center;">
                    <i class="fas fa-redo"></i>
                </a>
            </div>
        </div>
    </form>
</div>

<!-- Products List -->
<div class="content-card">
    @if($products->count() > 0)
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                        <th style="padding: 15px; text-align: right; font-weight: 600; color: #4a5568;">المنتج</th>
                        <th style="padding: 15px; text-align: center; font-weight: 600; color: #4a5568;">الرمز/الباركود</th>
                        <th style="padding: 15px; text-align: center; font-weight: 600; color: #4a5568;">الفئة</th>
                        <th style="padding: 15px; text-align: center; font-weight: 600; color: #4a5568;">السعر</th>
                        <th style="padding: 15px; text-align: center; font-weight: 600; color: #4a5568;">المخزون</th>
                        <th style="padding: 15px; text-align: center; font-weight: 600; color: #4a5568;">الحالة</th>
                        <th style="padding: 15px; text-align: center; font-weight: 600; color: #4a5568;">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                        <tr style="border-bottom: 1px solid #e2e8f0;">
                            <td style="padding: 15px;">
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    @if($product->primary_image_url)
                                        <img src="{{ $product->primary_image_url }}" alt="{{ $product->name }}" 
                                             style="width: 50px; height: 50px; border-radius: 8px; object-fit: cover;">
                                    @else
                                        <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 18px;">
                                            {{ substr($product->name, 0, 1) }}
                                        </div>
                                    @endif
                                    <div>
                                        <div style="font-weight: 600; color: #2d3748; margin-bottom: 2px;">{{ $product->name }}</div>
                                        @if($product->brand)
                                            <div style="font-size: 12px; color: #6b7280;">{{ $product->brand }}</div>
                                        @endif
                                        @if($product->short_description)
                                            <div style="font-size: 11px; color: #9ca3af; margin-top: 2px;">{{ Str::limit($product->short_description, 40) }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <div style="margin-bottom: 4px;">
                                    <span style="background: #f3f4f6; color: #374151; padding: 4px 8px; border-radius: 6px; font-size: 12px; font-weight: 600;">
                                        {{ $product->code ?? $product->product_code }}
                                    </span>
                                </div>
                                @if($product->barcode)
                                    <div style="font-size: 11px; color: #6b7280;">
                                        <i class="fas fa-barcode" style="margin-left: 4px;"></i>
                                        {{ $product->barcode }}
                                    </div>
                                @endif
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                @if($product->category && is_object($product->category))
                                    <span style="background: #dbeafe; color: #1e40af; padding: 4px 8px; border-radius: 6px; font-size: 12px; font-weight: 600;">
                                        {{ $product->category->name }}
                                    </span>
                                @elseif($product->category && is_string($product->category))
                                    <span style="background: #f3f4f6; color: #374151; padding: 4px 8px; border-radius: 6px; font-size: 12px; font-weight: 600;">
                                        {{ $product->category }}
                                    </span>
                                @else
                                    <span style="color: #9ca3af; font-size: 12px;">غير محدد</span>
                                @endif
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <div style="font-weight: 600; color: #059669; margin-bottom: 2px;">
                                    {{ number_format($product->selling_price, 2) }} {{ $product->currency ?? 'IQD' }}
                                </div>
                                @if($product->cost_price)
                                    <div style="font-size: 11px; color: #6b7280;">
                                        التكلفة: {{ number_format($product->cost_price, 2) }}
                                    </div>
                                @endif
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                @php
                                    $currentStock = $product->current_stock ?? $product->stock_quantity ?? 0;
                                    $minimumStock = $product->minimum_stock ?? $product->min_stock_level ?? 0;
                                    $stockStatus = $product->stock_status;
                                @endphp
                                
                                <div style="margin-bottom: 4px;">
                                    <span style="font-weight: 600; color: #2d3748;">{{ number_format($currentStock, 2) }}</span>
                                    <span style="font-size: 11px; color: #6b7280;">{{ $product->base_unit ?? $product->unit_of_measure ?? 'قطعة' }}</span>
                                </div>
                                
                                @if($stockStatus === 'out_of_stock')
                                    <span style="background: #fee2e2; color: #991b1b; padding: 2px 6px; border-radius: 4px; font-size: 10px; font-weight: 600;">
                                        نفد المخزون
                                    </span>
                                @elseif($stockStatus === 'low_stock')
                                    <span style="background: #fef3c7; color: #92400e; padding: 2px 6px; border-radius: 4px; font-size: 10px; font-weight: 600;">
                                        مخزون منخفض
                                    </span>
                                @else
                                    <span style="background: #d1fae5; color: #065f46; padding: 2px 6px; border-radius: 4px; font-size: 10px; font-weight: 600;">
                                        متوفر
                                    </span>
                                @endif
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                @php
                                    $status = $product->status ?? ($product->is_active ? 'active' : 'inactive');
                                @endphp
                                
                                @if($status === 'active')
                                    <span style="background: #d1fae5; color: #065f46; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                                        <i class="fas fa-check-circle" style="margin-left: 4px;"></i>
                                        نشط
                                    </span>
                                @elseif($status === 'inactive')
                                    <span style="background: #fee2e2; color: #991b1b; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                                        <i class="fas fa-times-circle" style="margin-left: 4px;"></i>
                                        غير نشط
                                    </span>
                                @elseif($status === 'discontinued')
                                    <span style="background: #f3f4f6; color: #374151; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                                        <i class="fas fa-ban" style="margin-left: 4px;"></i>
                                        متوقف
                                    </span>
                                @else
                                    <span style="background: #fef3c7; color: #92400e; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                                        <i class="fas fa-exclamation-triangle" style="margin-left: 4px;"></i>
                                        نفد المخزون
                                    </span>
                                @endif
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <div style="display: flex; gap: 8px; justify-content: center;">
                                    <a href="{{ route('tenant.inventory.products.show', $product) }}" 
                                       style="background: #3b82f6; color: white; padding: 6px 10px; border-radius: 6px; text-decoration: none; font-size: 12px;"
                                       title="عرض">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('tenant.inventory.products.edit', $product) }}" 
                                       style="background: #f59e0b; color: white; padding: 6px 10px; border-radius: 6px; text-decoration: none; font-size: 12px;"
                                       title="تعديل">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="{{ route('tenant.inventory.products.destroy', $product) }}" style="display: inline;" 
                                          onsubmit="return confirm('هل أنت متأكد من حذف هذا المنتج؟')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                style="background: #ef4444; color: white; padding: 6px 10px; border: none; border-radius: 6px; cursor: pointer; font-size: 12px;"
                                                title="حذف">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($products->hasPages())
            <div style="margin-top: 20px; display: flex; justify-content: center;">
                {{ $products->links() }}
            </div>
        @endif
    @else
        <div style="text-align: center; padding: 60px 40px; color: #6b7280;">
            <div style="background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%); color: white; border-radius: 50%; width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; font-size: 32px;">
                <i class="fas fa-cube"></i>
            </div>
            <h3 style="margin: 0 0 10px 0; color: #2d3748; font-size: 20px; font-weight: 700;">لا توجد منتجات</h3>
            <p style="margin: 0 0 20px 0; font-size: 16px;">ابدأ بإضافة منتجات جديدة إلى الكتالوج</p>
            <a href="{{ route('tenant.inventory.products.create') }}" style="background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%); color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 8px;">
                <i class="fas fa-plus"></i>
                إضافة منتج جديد
            </a>
        </div>
    @endif
</div>
@endsection
