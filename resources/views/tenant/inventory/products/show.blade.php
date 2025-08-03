@extends('layouts.modern')

@section('page-title', 'عرض المنتج: ' . $product->name)
@section('page-description', 'تفاصيل المنتج الكاملة')

@section('content')
<!-- Page Header -->
<div style="background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%); border-radius: 20px; padding: 30px; margin-bottom: 30px; color: white; position: relative; overflow: hidden;">
    <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
    <div style="position: absolute; bottom: -30px; left: -30px; width: 150px; height: 150px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>
    
    <div style="position: relative; z-index: 2;">
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    @if($product->primary_image_url)
                        <img src="{{ $product->primary_image_url }}" alt="{{ $product->name }}" 
                             style="width: 80px; height: 80px; border-radius: 15px; object-fit: cover; margin-left: 20px; border: 3px solid rgba(255,255,255,0.3);">
                    @else
                        <div style="background: rgba(255,255,255,0.2); border-radius: 15px; padding: 20px; margin-left: 20px; border: 3px solid rgba(255,255,255,0.3);">
                            <i class="fas fa-cube" style="font-size: 40px;"></i>
                        </div>
                    @endif
                    <div>
                        <h1 style="font-size: 32px; font-weight: 800; margin: 0; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                            {{ $product->name }} 📦
                        </h1>
                        <p style="font-size: 18px; margin: 5px 0 0 0; opacity: 0.9;">
                            {{ $product->short_description ?? $product->description ?? 'لا يوجد وصف' }}
                        </p>
                        @if($product->brand)
                            <div style="margin-top: 10px;">
                                <span style="background: rgba(255,255,255,0.2); padding: 4px 12px; border-radius: 15px; font-size: 14px;">
                                    <i class="fas fa-tag" style="margin-left: 5px;"></i>
                                    {{ $product->brand }}
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
                
                <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-code" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px;">{{ $product->code ?? $product->product_code }}</span>
                    </div>
                    @if($product->barcode)
                        <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                            <i class="fas fa-barcode" style="margin-left: 8px;"></i>
                            <span style="font-size: 14px;">{{ $product->barcode }}</span>
                        </div>
                    @endif
                    <div style="background: rgba(16, 185, 129, 0.2); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3);">
                        <i class="fas fa-dollar-sign" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px;">{{ number_format($product->selling_price, 2) }} {{ $product->currency ?? 'IQD' }}</span>
                    </div>
                    @php
                        $currentStock = $product->current_stock ?? $product->stock_quantity ?? 0;
                        $stockStatus = $product->stock_status;
                    @endphp
                    <div style="background: rgba({{ $stockStatus === 'out_of_stock' ? '239, 68, 68' : ($stockStatus === 'low_stock' ? '245, 158, 11' : '16, 185, 129') }}, 0.2); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3);">
                        <i class="fas fa-boxes" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px;">{{ number_format($currentStock, 2) }} {{ $product->base_unit ?? $product->unit_of_measure ?? 'قطعة' }}</span>
                    </div>
                </div>
            </div>
            
            <div style="display: flex; gap: 15px;">
                <a href="{{ route('tenant.inventory.inventory-products.edit', $product) }}" style="background: rgba(245, 158, 11, 0.2); color: white; padding: 15px 25px; border-radius: 15px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3);">
                    <i class="fas fa-edit"></i>
                    تعديل
                </a>
                <a href="{{ route('tenant.inventory.inventory-products.index') }}" style="background: rgba(255,255,255,0.2); color: white; padding: 15px 25px; border-radius: 15px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3);">
                    <i class="fas fa-arrow-right"></i>
                    العودة للقائمة
                </a>
            </div>
        </div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 30px;">
    <!-- Product Details -->
    <div class="content-card">
        <h3 style="color: #2d3748; margin-bottom: 20px; font-size: 20px; font-weight: 700; display: flex; align-items: center; gap: 10px; border-bottom: 2px solid #e2e8f0; padding-bottom: 10px;">
            <i class="fas fa-info-circle" style="color: #3b82f6;"></i>
            تفاصيل المنتج
        </h3>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px;">
            <div>
                <h4 style="color: #4a5568; margin-bottom: 15px; font-size: 16px; font-weight: 600;">المعلومات الأساسية</h4>
                <div style="space-y: 10px;">
                    <div style="margin-bottom: 10px;">
                        <span style="color: #6b7280; font-size: 14px;">الاسم:</span>
                        <span style="color: #2d3748; font-weight: 600; margin-right: 10px;">{{ $product->name }}</span>
                    </div>
                    <div style="margin-bottom: 10px;">
                        <span style="color: #6b7280; font-size: 14px;">الرمز:</span>
                        <span style="color: #2d3748; font-weight: 600; margin-right: 10px;">{{ $product->code ?? $product->product_code }}</span>
                    </div>
                    @if($product->category && is_object($product->category))
                        <div style="margin-bottom: 10px;">
                            <span style="color: #6b7280; font-size: 14px;">الفئة:</span>
                            <a href="{{ route('tenant.inventory.categories.show', $product->category) }}" 
                               style="color: #3b82f6; font-weight: 600; margin-right: 10px; text-decoration: none;">
                                {{ $product->category->name }}
                            </a>
                        </div>
                    @elseif($product->category && is_string($product->category))
                        <div style="margin-bottom: 10px;">
                            <span style="color: #6b7280; font-size: 14px;">الفئة:</span>
                            <span style="color: #2d3748; font-weight: 600; margin-right: 10px;">{{ $product->category }}</span>
                        </div>
                    @endif
                    @if($product->brand)
                        <div style="margin-bottom: 10px;">
                            <span style="color: #6b7280; font-size: 14px;">العلامة التجارية:</span>
                            <span style="color: #2d3748; font-weight: 600; margin-right: 10px;">{{ $product->brand }}</span>
                        </div>
                    @endif
                    @if($product->manufacturer)
                        <div style="margin-bottom: 10px;">
                            <span style="color: #6b7280; font-size: 14px;">الشركة المصنعة:</span>
                            <span style="color: #2d3748; font-weight: 600; margin-right: 10px;">{{ $product->manufacturer }}</span>
                        </div>
                    @endif
                    @if($product->country_of_origin)
                        <div style="margin-bottom: 10px;">
                            <span style="color: #6b7280; font-size: 14px;">بلد المنشأ:</span>
                            <span style="color: #2d3748; font-weight: 600; margin-right: 10px;">{{ $product->country_of_origin }}</span>
                        </div>
                    @endif
                </div>
            </div>
            
            <div>
                <h4 style="color: #4a5568; margin-bottom: 15px; font-size: 16px; font-weight: 600;">الأسعار والوحدات</h4>
                <div style="space-y: 10px;">
                    <div style="margin-bottom: 10px;">
                        <span style="color: #6b7280; font-size: 14px;">سعر التكلفة:</span>
                        <span style="color: #ef4444; font-weight: 600; margin-right: 10px;">{{ number_format($product->cost_price, 2) }} {{ $product->currency ?? 'IQD' }}</span>
                    </div>
                    <div style="margin-bottom: 10px;">
                        <span style="color: #6b7280; font-size: 14px;">سعر البيع:</span>
                        <span style="color: #059669; font-weight: 600; margin-right: 10px;">{{ number_format($product->selling_price, 2) }} {{ $product->currency ?? 'IQD' }}</span>
                    </div>
                    @if($product->wholesale_price)
                        <div style="margin-bottom: 10px;">
                            <span style="color: #6b7280; font-size: 14px;">سعر الجملة:</span>
                            <span style="color: #2d3748; font-weight: 600; margin-right: 10px;">{{ number_format($product->wholesale_price, 2) }} {{ $product->currency ?? 'IQD' }}</span>
                        </div>
                    @endif
                    <div style="margin-bottom: 10px;">
                        <span style="color: #6b7280; font-size: 14px;">وحدة القياس:</span>
                        <span style="color: #2d3748; font-weight: 600; margin-right: 10px;">{{ $product->base_unit ?? $product->unit_of_measure ?? 'قطعة' }}</span>
                    </div>
                    @if($product->unit_weight)
                        <div style="margin-bottom: 10px;">
                            <span style="color: #6b7280; font-size: 14px;">الوزن:</span>
                            <span style="color: #2d3748; font-weight: 600; margin-right: 10px;">{{ $product->unit_weight }} كجم</span>
                        </div>
                    @endif
                    @if($product->dimensions)
                        <div style="margin-bottom: 10px;">
                            <span style="color: #6b7280; font-size: 14px;">الأبعاد:</span>
                            <span style="color: #2d3748; font-weight: 600; margin-right: 10px;">{{ $product->dimensions }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        @if($product->description)
            <div style="margin-bottom: 20px;">
                <h4 style="color: #4a5568; margin-bottom: 10px; font-size: 16px; font-weight: 600;">الوصف</h4>
                <p style="color: #6b7280; line-height: 1.6; background: #f8fafc; padding: 15px; border-radius: 8px; border-right: 4px solid #3b82f6;">
                    {{ $product->description }}
                </p>
            </div>
        @endif
        
        @if($product->barcode || $product->qr_code)
            <div style="margin-bottom: 20px;">
                <h4 style="color: #4a5568; margin-bottom: 15px; font-size: 16px; font-weight: 600;">رموز التتبع</h4>
                <div style="display: flex; gap: 20px;">
                    @if($product->barcode)
                        <div style="text-align: center;">
                            <div style="background: #f8fafc; padding: 15px; border-radius: 8px; border: 1px solid #e2e8f0;">
                                <i class="fas fa-barcode" style="font-size: 32px; color: #3b82f6; margin-bottom: 10px;"></i>
                                <p style="margin: 0; font-size: 12px; color: #6b7280;">باركود</p>
                                <p style="margin: 5px 0 0 0; font-weight: 600; color: #2d3748;">{{ $product->barcode }}</p>
                            </div>
                        </div>
                    @endif
                    @if($product->qr_code)
                        <div style="text-align: center;">
                            <div style="background: #f8fafc; padding: 15px; border-radius: 8px; border: 1px solid #e2e8f0;">
                                <i class="fas fa-qrcode" style="font-size: 32px; color: #3b82f6; margin-bottom: 10px;"></i>
                                <p style="margin: 0; font-size: 12px; color: #6b7280;">QR كود</p>
                                <p style="margin: 5px 0 0 0; font-weight: 600; color: #2d3748; font-size: 10px; word-break: break-all;">{{ Str::limit($product->qr_code, 30) }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>
    
    <!-- Stock and Status -->
    <div>
        <!-- Stock Information -->
        <div class="content-card" style="margin-bottom: 20px;">
            <h3 style="color: #2d3748; margin-bottom: 20px; font-size: 18px; font-weight: 700; display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-boxes" style="color: #8b5cf6;"></i>
                معلومات المخزون
            </h3>
            
            <div style="space-y: 15px;">
                <div style="background: #f8fafc; padding: 15px; border-radius: 8px; border-right: 4px solid #3b82f6; margin-bottom: 15px;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="color: #6b7280; font-size: 14px;">الكمية الحالية</span>
                        <span style="color: #2d3748; font-weight: 700; font-size: 18px;">{{ number_format($currentStock, 2) }}</span>
                    </div>
                </div>
                
                <div style="background: #f8fafc; padding: 15px; border-radius: 8px; border-right: 4px solid #f59e0b; margin-bottom: 15px;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="color: #6b7280; font-size: 14px;">الحد الأدنى</span>
                        <span style="color: #2d3748; font-weight: 600;">{{ number_format($product->minimum_stock ?? $product->min_stock_level ?? 0, 2) }}</span>
                    </div>
                </div>
                
                @if($product->maximum_stock || $product->max_stock_level)
                    <div style="background: #f8fafc; padding: 15px; border-radius: 8px; border-right: 4px solid #10b981; margin-bottom: 15px;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span style="color: #6b7280; font-size: 14px;">الحد الأقصى</span>
                            <span style="color: #2d3748; font-weight: 600;">{{ number_format($product->maximum_stock ?? $product->max_stock_level, 2) }}</span>
                        </div>
                    </div>
                @endif
                
                <div style="text-align: center; margin-top: 20px;">
                    @if($stockStatus === 'out_of_stock')
                        <span style="background: #fee2e2; color: #991b1b; padding: 8px 16px; border-radius: 20px; font-size: 14px; font-weight: 600;">
                            <i class="fas fa-times-circle" style="margin-left: 5px;"></i>
                            نفد المخزون
                        </span>
                    @elseif($stockStatus === 'low_stock')
                        <span style="background: #fef3c7; color: #92400e; padding: 8px 16px; border-radius: 20px; font-size: 14px; font-weight: 600;">
                            <i class="fas fa-exclamation-triangle" style="margin-left: 5px;"></i>
                            مخزون منخفض
                        </span>
                    @else
                        <span style="background: #d1fae5; color: #065f46; padding: 8px 16px; border-radius: 20px; font-size: 14px; font-weight: 600;">
                            <i class="fas fa-check-circle" style="margin-left: 5px;"></i>
                            متوفر
                        </span>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Product Status -->
        <div class="content-card">
            <h3 style="color: #2d3748; margin-bottom: 20px; font-size: 18px; font-weight: 700; display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-info-circle" style="color: #3b82f6;"></i>
                حالة المنتج
            </h3>
            
            <div style="space-y: 15px;">
                @php
                    $status = $product->status ?? ($product->is_active ? 'active' : 'inactive');
                @endphp
                
                <div style="text-align: center; margin-bottom: 20px;">
                    @if($status === 'active')
                        <span style="background: #d1fae5; color: #065f46; padding: 10px 20px; border-radius: 20px; font-size: 16px; font-weight: 600;">
                            <i class="fas fa-check-circle" style="margin-left: 5px;"></i>
                            نشط
                        </span>
                    @elseif($status === 'inactive')
                        <span style="background: #fee2e2; color: #991b1b; padding: 10px 20px; border-radius: 20px; font-size: 16px; font-weight: 600;">
                            <i class="fas fa-times-circle" style="margin-left: 5px;"></i>
                            غير نشط
                        </span>
                    @elseif($status === 'discontinued')
                        <span style="background: #f3f4f6; color: #374151; padding: 10px 20px; border-radius: 20px; font-size: 16px; font-weight: 600;">
                            <i class="fas fa-ban" style="margin-left: 5px;"></i>
                            متوقف
                        </span>
                    @endif
                </div>
                
                @if($product->expiry_date)
                    <div style="background: #f8fafc; padding: 15px; border-radius: 8px; border-right: 4px solid #ef4444; margin-bottom: 15px;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span style="color: #6b7280; font-size: 14px;">تاريخ الانتهاء</span>
                            <span style="color: #ef4444; font-weight: 600;">{{ $product->expiry_date->format('Y-m-d') }}</span>
                        </div>
                    </div>
                @endif
                
                @if($product->manufacturing_date)
                    <div style="background: #f8fafc; padding: 15px; border-radius: 8px; border-right: 4px solid #6b7280; margin-bottom: 15px;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span style="color: #6b7280; font-size: 14px;">تاريخ الإنتاج</span>
                            <span style="color: #2d3748; font-weight: 600;">{{ $product->manufacturing_date->format('Y-m-d') }}</span>
                        </div>
                    </div>
                @endif
                
                <div style="background: #f8fafc; padding: 15px; border-radius: 8px; border-right: 4px solid #6b7280;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="color: #6b7280; font-size: 14px;">تاريخ الإنشاء</span>
                        <span style="color: #2d3748; font-weight: 600;">{{ $product->created_at->format('Y-m-d') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
