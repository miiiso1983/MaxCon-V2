@extends('layouts.modern')

@section('page-title', 'تفاصيل المنتج - ' . $product->name)
@section('page-description', 'عرض تفاصيل المنتج الدوائي والمخزون')

@section('content')
<!-- Page Header -->
<div style="background: linear-gradient(135deg, #9f7aea 0%, #805ad5 100%); border-radius: 20px; padding: 30px; margin-bottom: 30px; color: white; position: relative; overflow: hidden;">
    <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
    <div style="position: absolute; bottom: -30px; left: -30px; width: 150px; height: 150px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>
    
    <div style="position: relative; z-index: 2;">
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    <div style="width: 80px; height: 80px; border-radius: 15px; background: rgba(255,255,255,0.2); color: white; display: flex; align-items: center; justify-content: center; margin-left: 20px; font-size: 32px; backdrop-filter: blur(10px);">
                        💊
                    </div>
                    <div>
                        <h1 style="font-size: 32px; font-weight: 800; margin: 0; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                            {{ $product->name }}
                        </h1>
                        <p style="font-size: 18px; margin: 5px 0 0 0; opacity: 0.9;">
                            {{ $product->product_code }} • {{ $product->category }}
                        </p>
                        @if($product->generic_name)
                            <p style="font-size: 16px; margin: 5px 0 0 0; opacity: 0.8;">
                                الاسم العلمي: {{ $product->generic_name }}
                            </p>
                        @endif
                    </div>
                </div>
                
                <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-boxes" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px; font-weight: 600;">مخزون: {{ $product->current_stock }} {{ $product->unit }}</span>
                    </div>
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-dollar-sign" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px;">سعر: {{ number_format($product->selling_price, 2) }} ر.س</span>
                    </div>
                    @if($product->is_active)
                        <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                            <i class="fas fa-check-circle" style="margin-left: 8px; color: #4ade80;"></i>
                            <span style="font-size: 14px;">نشط</span>
                        </div>
                    @else
                        <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                            <i class="fas fa-times-circle" style="margin-left: 8px; color: #f87171;"></i>
                            <span style="font-size: 14px;">غير نشط</span>
                        </div>
                    @endif
                    
                    @if($product->current_stock <= $product->min_stock_level)
                        <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                            <i class="fas fa-exclamation-triangle" style="margin-left: 8px; color: #fbbf24;"></i>
                            <span style="font-size: 14px;">مخزون منخفض</span>
                        </div>
                    @endif
                    
                    @if($product->expiry_date && $product->expiry_date->isPast())
                        <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                            <i class="fas fa-clock" style="margin-left: 8px; color: #f87171;"></i>
                            <span style="font-size: 14px;">منتهي الصلاحية</span>
                        </div>
                    @elseif($product->expiry_date && $product->expiry_date->diffInDays() <= 30)
                        <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                            <i class="fas fa-exclamation-triangle" style="margin-left: 8px; color: #fbbf24;"></i>
                            <span style="font-size: 14px;">ينتهي قريباً</span>
                        </div>
                    @endif
                </div>
            </div>
            
            <div style="display: flex; gap: 15px;">
                <a href="{{ route('tenant.sales.products.edit', $product) }}" style="background: rgba(255,255,255,0.2); color: white; padding: 15px 25px; border-radius: 15px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3); transition: all 0.3s ease;"
                   onmouseover="this.style.background='rgba(255,255,255,0.3)'; this.style.transform='translateY(-2px)';"
                   onmouseout="this.style.background='rgba(255,255,255,0.2)'; this.style.transform='translateY(0)';">
                    <i class="fas fa-edit"></i>
                    تعديل البيانات
                </a>
                <a href="{{ route('tenant.sales.products.index') }}" style="background: rgba(255,255,255,0.2); color: white; padding: 15px 25px; border-radius: 15px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3); transition: all 0.3s ease;"
                   onmouseover="this.style.background='rgba(255,255,255,0.3)'; this.style.transform='translateY(-2px)';"
                   onmouseout="this.style.background='rgba(255,255,255,0.2)'; this.style.transform='translateY(0)';">
                    <i class="fas fa-arrow-right"></i>
                    العودة للقائمة
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Product Information Cards -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 25px; margin-bottom: 30px;">
    <!-- Basic Information -->
    <div class="content-card">
        <h3 style="font-size: 18px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-info-circle" style="color: #9f7aea; margin-left: 10px;"></i>
            المعلومات الأساسية
        </h3>
        
        <div style="space-y: 15px;">
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px; background: #f8fafc; border-radius: 8px; margin-bottom: 10px;">
                <span style="color: #4a5568; font-weight: 600;">اسم المنتج</span>
                <span style="color: #2d3748; font-weight: 700;">{{ $product->name }}</span>
            </div>
            
            @if($product->generic_name)
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px; background: #f0f9ff; border-radius: 8px; margin-bottom: 10px;">
                    <span style="color: #1e40af; font-weight: 600;">الاسم العلمي</span>
                    <span style="color: #1e3a8a;">{{ $product->generic_name }}</span>
                </div>
            @endif
            
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px; background: #f3e8ff; border-radius: 8px; margin-bottom: 10px;">
                <span style="color: #7c3aed; font-weight: 600;">الفئة</span>
                <span style="color: #6b21a8;">{{ $product->category }}</span>
            </div>
            
            @if($product->manufacturer)
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px; background: #fef3c7; border-radius: 8px; margin-bottom: 10px;">
                    <span style="color: #d97706; font-weight: 600;">الشركة المصنعة</span>
                    <span style="color: #92400e;">{{ $product->manufacturer }}</span>
                </div>
            @endif
            
            @if($product->barcode)
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px; background: #ecfdf5; border-radius: 8px; margin-bottom: 10px;">
                    <span style="color: #059669; font-weight: 600;">الباركود</span>
                    <span style="color: #047857; font-family: monospace;">{{ $product->barcode }}</span>
                </div>
            @endif
            
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px; background: #fdf4ff; border-radius: 8px;">
                <span style="color: #a855f7; font-weight: 600;">الوحدة</span>
                <span style="color: #7c2d12;">{{ $product->unit }}</span>
            </div>
        </div>
    </div>

    <!-- Pricing Information -->
    <div class="content-card">
        <h3 style="font-size: 18px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-dollar-sign" style="color: #059669; margin-left: 10px;"></i>
            معلومات التسعير
        </h3>
        
        <div style="space-y: 15px;">
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px; background: #fef2f2; border-radius: 8px; margin-bottom: 10px;">
                <span style="color: #dc2626; font-weight: 600;">سعر الشراء</span>
                <span style="color: #b91c1c; font-weight: 700;">{{ number_format($product->purchase_price, 2) }} ر.س</span>
            </div>
            
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px; background: #f0fdf4; border-radius: 8px; margin-bottom: 10px;">
                <span style="color: #16a34a; font-weight: 600;">سعر البيع</span>
                <span style="color: #15803d; font-weight: 700;">{{ number_format($product->selling_price, 2) }} ر.س</span>
            </div>
            
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px; background: #f0f9ff; border-radius: 8px;">
                <span style="color: #0369a1; font-weight: 600;">هامش الربح</span>
                <span style="color: #1e40af; font-weight: 700;">{{ number_format($product->selling_price - $product->purchase_price, 2) }} ر.س</span>
            </div>
        </div>
    </div>

    <!-- Stock Information -->
    <div class="content-card">
        <h3 style="font-size: 18px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-boxes" style="color: #f59e0b; margin-left: 10px;"></i>
            معلومات المخزون
        </h3>
        
        <div style="space-y: 15px;">
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px; background: {{ $product->current_stock <= $product->min_stock_level ? '#fef2f2' : '#f0fdf4' }}; border-radius: 8px; margin-bottom: 10px;">
                <span style="color: {{ $product->current_stock <= $product->min_stock_level ? '#dc2626' : '#16a34a' }}; font-weight: 600;">المخزون الحالي</span>
                <span style="color: {{ $product->current_stock <= $product->min_stock_level ? '#b91c1c' : '#15803d' }}; font-weight: 700;">{{ $product->current_stock }} {{ $product->unit }}</span>
            </div>
            
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px; background: #fffbeb; border-radius: 8px; margin-bottom: 10px;">
                <span style="color: #d97706; font-weight: 600;">الحد الأدنى</span>
                <span style="color: #92400e; font-weight: 700;">{{ $product->min_stock_level }} {{ $product->unit }}</span>
            </div>
            
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px; background: #f8fafc; border-radius: 8px;">
                <span style="color: #4a5568; font-weight: 600;">قيمة المخزون</span>
                <span style="color: #2d3748; font-weight: 700;">{{ number_format($product->current_stock * $product->purchase_price, 2) }} ر.س</span>
            </div>
        </div>
    </div>

    <!-- Batch and Expiry Information -->
    @if($product->batch_number || $product->expiry_date || $product->manufacturing_date)
    <div class="content-card">
        <h3 style="font-size: 18px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-calendar-alt" style="color: #dc2626; margin-left: 10px;"></i>
            معلومات الدفعة والصلاحية
        </h3>
        
        <div style="space-y: 15px;">
            @if($product->batch_number)
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px; background: #f8fafc; border-radius: 8px; margin-bottom: 10px;">
                    <span style="color: #4a5568; font-weight: 600;">رقم الدفعة</span>
                    <span style="color: #2d3748; font-family: monospace;">{{ $product->batch_number }}</span>
                </div>
            @endif
            
            @if($product->manufacturing_date)
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px; background: #f0f9ff; border-radius: 8px; margin-bottom: 10px;">
                    <span style="color: #0369a1; font-weight: 600;">تاريخ التصنيع</span>
                    <span style="color: #1e40af;">{{ $product->manufacturing_date->format('Y/m/d') }}</span>
                </div>
            @endif
            
            @if($product->expiry_date)
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px; background: {{ $product->expiry_date->isPast() ? '#fef2f2' : ($product->expiry_date->diffInDays() <= 30 ? '#fffbeb' : '#f0fdf4') }}; border-radius: 8px;">
                    <span style="color: {{ $product->expiry_date->isPast() ? '#dc2626' : ($product->expiry_date->diffInDays() <= 30 ? '#d97706' : '#16a34a') }}; font-weight: 600;">تاريخ انتهاء الصلاحية</span>
                    <div style="text-align: left;">
                        <div style="color: {{ $product->expiry_date->isPast() ? '#b91c1c' : ($product->expiry_date->diffInDays() <= 30 ? '#92400e' : '#15803d') }}; font-weight: 700;">{{ $product->expiry_date->format('Y/m/d') }}</div>
                        @if($product->expiry_date->isPast())
                            <div style="font-size: 12px; color: #dc2626;">منتهي الصلاحية</div>
                        @elseif($product->expiry_date->diffInDays() <= 30)
                            <div style="font-size: 12px; color: #d97706;">ينتهي خلال {{ $product->expiry_date->diffInDays() }} يوم</div>
                        @else
                            <div style="font-size: 12px; color: #16a34a;">صالح لمدة {{ $product->expiry_date->diffInDays() }} يوم</div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
    @endif
</div>

<!-- Storage Conditions -->
@if($product->storage_conditions)
<div class="content-card" style="margin-bottom: 30px;">
    <h3 style="font-size: 18px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
        <i class="fas fa-thermometer-half" style="color: #0ea5e9; margin-left: 10px;"></i>
        شروط التخزين
    </h3>
    
    <div style="background: #f0f9ff; border-radius: 8px; padding: 20px; border-right: 4px solid #0ea5e9;">
        <p style="color: #1e40af; margin: 0; line-height: 1.6;">{{ $product->storage_conditions }}</p>
    </div>
</div>
@endif

<!-- Description -->
@if($product->description)
<div class="content-card" style="margin-bottom: 30px;">
    <h3 style="font-size: 18px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
        <i class="fas fa-file-alt" style="color: #6366f1; margin-left: 10px;"></i>
        وصف المنتج
    </h3>
    
    <div style="background: #f8fafc; border-radius: 8px; padding: 20px; border-right: 4px solid #6366f1;">
        <p style="color: #374151; margin: 0; line-height: 1.6;">{{ $product->description }}</p>
    </div>
</div>
@endif

<!-- Notes -->
@if($product->notes)
<div class="content-card" style="margin-bottom: 30px;">
    <h3 style="font-size: 18px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
        <i class="fas fa-sticky-note" style="color: #f59e0b; margin-left: 10px;"></i>
        ملاحظات
    </h3>
    
    <div style="background: #fffbeb; border-radius: 8px; padding: 20px; border-right: 4px solid #f59e0b;">
        <p style="color: #92400e; margin: 0; line-height: 1.6;">{{ $product->notes }}</p>
    </div>
</div>
@endif

<!-- Recent Activity Placeholder -->
<div class="content-card">
    <h3 style="font-size: 18px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
        <i class="fas fa-history" style="color: #8b5cf6; margin-left: 10px;"></i>
        النشاط الأخير
    </h3>
    
    <div style="text-align: center; color: #9ca3af; padding: 40px;">
        <i class="fas fa-chart-line" style="font-size: 48px; margin-bottom: 15px;"></i>
        <p style="font-size: 16px; margin: 0;">سيتم عرض حركات المخزون والمبيعات هنا قريباً</p>
    </div>
</div>
@endsection
