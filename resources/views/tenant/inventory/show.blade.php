@extends('layouts.modern')

@section('page-title', 'تفاصيل المخزون')
@section('page-description', 'عرض تفاصيل عنصر المخزون')

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
                        <i class="fas fa-box" style="font-size: 32px;"></i>
                    </div>
                    <div>
                        <h1 style="font-size: 32px; font-weight: 800; margin: 0; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                            {{ $inventory->product->name }} 📦
                        </h1>
                        <p style="font-size: 18px; margin: 5px 0 0 0; opacity: 0.9;">
                            {{ $inventory->warehouse->name }} - {{ $inventory->product->code }}
                        </p>
                    </div>
                </div>
                
                <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        @php
                            $statusColors = [
                                'active' => ['bg' => '#d1fae5', 'text' => '#065f46', 'label' => 'نشط'],
                                'quarantine' => ['bg' => '#fef3c7', 'text' => '#92400e', 'label' => 'حجر صحي'],
                                'damaged' => ['bg' => '#fee2e2', 'text' => '#991b1b', 'label' => 'تالف'],
                                'expired' => ['bg' => '#fee2e2', 'text' => '#991b1b', 'label' => 'منتهي الصلاحية'],
                            ];
                            $status = $statusColors[$inventory->status] ?? ['bg' => '#f1f5f9', 'text' => '#64748b', 'label' => $inventory->status];
                        @endphp
                        <span style="background: {{ $status['bg'] }}; color: {{ $status['text'] }}; padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                            {{ $status['label'] }}
                        </span>
                    </div>
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-cubes" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px; font-weight: 600;">{{ number_format($inventory->quantity, 0) }} إجمالي</span>
                    </div>
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-check-circle" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px;">{{ number_format($inventory->available_quantity, 0) }} متاح</span>
                    </div>
                    @if($inventory->location_code)
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-map-marker-alt" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px;">{{ $inventory->location_code }}</span>
                    </div>
                    @endif
                </div>
            </div>
            
            <div style="display: flex; gap: 15px;">
                <a href="{{ route('tenant.inventory.edit', $inventory) }}" style="background: rgba(255,255,255,0.2); color: white; padding: 15px 25px; border-radius: 15px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3);">
                    <i class="fas fa-edit"></i>
                    تعديل
                </a>
                <a href="{{ route('tenant.inventory.index') }}" style="background: rgba(255,255,255,0.2); color: white; padding: 15px 25px; border-radius: 15px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3);">
                    <i class="fas fa-arrow-right"></i>
                    العودة للقائمة
                </a>
            </div>
        </div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 25px;">
    <!-- Inventory Details -->
    <div class="content-card">
        <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-info-circle" style="color: #667eea; margin-left: 10px;"></i>
            تفاصيل المخزون
        </h3>
        
        <!-- Product Information -->
        <div style="margin-bottom: 25px; padding: 20px; background: #f8fafc; border-radius: 12px; border-right: 4px solid #3b82f6;">
            <h4 style="margin: 0 0 15px 0; color: #1e40af; font-size: 16px; font-weight: 600;">معلومات المنتج</h4>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div>
                    <label style="font-weight: 600; color: #4a5568; margin-bottom: 5px; display: block;">اسم المنتج</label>
                    <div style="padding: 10px; background: white; border-radius: 6px; font-weight: 600;">{{ $inventory->product->name }}</div>
                </div>
                <div>
                    <label style="font-weight: 600; color: #4a5568; margin-bottom: 5px; display: block;">كود المنتج</label>
                    <div style="padding: 10px; background: white; border-radius: 6px;">{{ $inventory->product->code }}</div>
                </div>
                @if($inventory->product->category)
                <div>
                    <label style="font-weight: 600; color: #4a5568; margin-bottom: 5px; display: block;">الفئة</label>
                    <div style="padding: 10px; background: white; border-radius: 6px;">{{ $inventory->product->category }}</div>
                </div>
                @endif
                @if($inventory->product->unit)
                <div>
                    <label style="font-weight: 600; color: #4a5568; margin-bottom: 5px; display: block;">الوحدة</label>
                    <div style="padding: 10px; background: white; border-radius: 6px;">{{ $inventory->product->unit }}</div>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Warehouse Information -->
        <div style="margin-bottom: 25px; padding: 20px; background: #f0fdf4; border-radius: 12px; border-right: 4px solid #10b981;">
            <h4 style="margin: 0 0 15px 0; color: #065f46; font-size: 16px; font-weight: 600;">معلومات المستودع</h4>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div>
                    <label style="font-weight: 600; color: #4a5568; margin-bottom: 5px; display: block;">اسم المستودع</label>
                    <div style="padding: 10px; background: white; border-radius: 6px; font-weight: 600;">{{ $inventory->warehouse->name }}</div>
                </div>
                <div>
                    <label style="font-weight: 600; color: #4a5568; margin-bottom: 5px; display: block;">كود المستودع</label>
                    <div style="padding: 10px; background: white; border-radius: 6px;">{{ $inventory->warehouse->code }}</div>
                </div>
                @if($inventory->location_code)
                <div>
                    <label style="font-weight: 600; color: #4a5568; margin-bottom: 5px; display: block;">كود الموقع</label>
                    <div style="padding: 10px; background: white; border-radius: 6px;">{{ $inventory->location_code }}</div>
                </div>
                @endif
                @if($inventory->warehouse->address)
                <div>
                    <label style="font-weight: 600; color: #4a5568; margin-bottom: 5px; display: block;">العنوان</label>
                    <div style="padding: 10px; background: white; border-radius: 6px;">{{ $inventory->warehouse->address }}</div>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Batch Information -->
        @if($inventory->batch_number || $inventory->expiry_date)
        <div style="margin-bottom: 25px; padding: 20px; background: #fef3c7; border-radius: 12px; border-right: 4px solid #f59e0b;">
            <h4 style="margin: 0 0 15px 0; color: #92400e; font-size: 16px; font-weight: 600;">معلومات الدفعة</h4>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                @if($inventory->batch_number)
                <div>
                    <label style="font-weight: 600; color: #4a5568; margin-bottom: 5px; display: block;">رقم الدفعة</label>
                    <div style="padding: 10px; background: white; border-radius: 6px; font-weight: 600;">{{ $inventory->batch_number }}</div>
                </div>
                @endif
                @if($inventory->expiry_date)
                <div>
                    <label style="font-weight: 600; color: #4a5568; margin-bottom: 5px; display: block;">تاريخ انتهاء الصلاحية</label>
                    <div style="padding: 10px; background: white; border-radius: 6px;">
                        {{ $inventory->expiry_date->format('Y-m-d') }}
                        @php
                            $daysToExpiry = now()->diffInDays($inventory->expiry_date, false);
                        @endphp
                        @if($daysToExpiry < 0)
                            <span style="color: #ef4444; font-weight: 600; margin-right: 10px;">(منتهي منذ {{ abs($daysToExpiry) }} يوم)</span>
                        @elseif($daysToExpiry <= 30)
                            <span style="color: #f59e0b; font-weight: 600; margin-right: 10px;">(ينتهي خلال {{ $daysToExpiry }} يوم)</span>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>
        @endif
        
        <!-- Cost Information -->
        @if($inventory->cost_price > 0)
        <div style="padding: 20px; background: #f0f9ff; border-radius: 12px; border-right: 4px solid #3b82f6;">
            <h4 style="margin: 0 0 15px 0; color: #1e40af; font-size: 16px; font-weight: 600;">معلومات التكلفة</h4>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div>
                    <label style="font-weight: 600; color: #4a5568; margin-bottom: 5px; display: block;">سعر التكلفة للوحدة</label>
                    <div style="padding: 10px; background: white; border-radius: 6px; font-weight: 600;">{{ number_format($inventory->cost_price, 2) }} د.ع</div>
                </div>
                <div>
                    <label style="font-weight: 600; color: #4a5568; margin-bottom: 5px; display: block;">إجمالي قيمة المخزون</label>
                    <div style="padding: 10px; background: white; border-radius: 6px; font-weight: 600; color: #1e40af;">{{ number_format($inventory->cost_price * $inventory->quantity, 2) }} د.ع</div>
                </div>
            </div>
        </div>
        @endif
    </div>
    
    <!-- Inventory Summary -->
    <div class="content-card">
        <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-chart-bar" style="color: #667eea; margin-left: 10px;"></i>
            ملخص المخزون
        </h3>
        
        <!-- Quantity Cards -->
        <div style="display: flex; flex-direction: column; gap: 15px; margin-bottom: 25px;">
            <div style="text-align: center; padding: 20px; background: #f0f9ff; border-radius: 12px; border-left: 4px solid #3b82f6;">
                <div style="font-size: 32px; font-weight: 700; color: #3b82f6;">{{ number_format($inventory->quantity, 0) }}</div>
                <div style="font-size: 14px; color: #1e40af; font-weight: 600;">الكمية الإجمالية</div>
                <div style="font-size: 12px; color: #6b7280;">{{ $inventory->product->unit ?? 'وحدة' }}</div>
            </div>
            
            <div style="text-align: center; padding: 20px; background: #f0fdf4; border-radius: 12px; border-left: 4px solid #10b981;">
                <div style="font-size: 32px; font-weight: 700; color: #10b981;">{{ number_format($inventory->available_quantity, 0) }}</div>
                <div style="font-size: 14px; color: #065f46; font-weight: 600;">الكمية المتاحة</div>
                <div style="font-size: 12px; color: #6b7280;">للبيع والاستخدام</div>
            </div>
            
            <div style="text-align: center; padding: 20px; background: #fef3c7; border-radius: 12px; border-left: 4px solid #f59e0b;">
                <div style="font-size: 32px; font-weight: 700; color: #f59e0b;">{{ number_format($inventory->reserved_quantity, 0) }}</div>
                <div style="font-size: 14px; color: #92400e; font-weight: 600;">الكمية المحجوزة</div>
                <div style="font-size: 12px; color: #6b7280;">محجوزة للطلبات</div>
            </div>
        </div>
        
        <!-- Status Information -->
        <div style="margin-bottom: 25px; padding: 15px; background: #f8fafc; border-radius: 8px;">
            <h4 style="margin: 0 0 10px 0; color: #2d3748; font-size: 14px; font-weight: 600;">حالة المخزون</h4>
            <div style="text-align: center;">
                <span style="background: {{ $status['bg'] }}; color: {{ $status['text'] }}; padding: 8px 16px; border-radius: 20px; font-size: 14px; font-weight: 600;">
                    {{ $status['label'] }}
                </span>
            </div>
        </div>
        
        <!-- Stock Level Warning -->
        @if($inventory->product->min_stock_level && $inventory->available_quantity <= $inventory->product->min_stock_level)
        <div style="margin-bottom: 25px; padding: 15px; background: #fee2e2; border-radius: 8px; border-right: 4px solid #ef4444;">
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px;">
                <i class="fas fa-exclamation-triangle" style="color: #ef4444;"></i>
                <span style="font-weight: 600; color: #991b1b;">تحذير: مخزون منخفض</span>
            </div>
            <div style="font-size: 14px; color: #6b7280;">
                الكمية المتاحة ({{ number_format($inventory->available_quantity, 0) }}) أقل من أو تساوي الحد الأدنى ({{ number_format($inventory->product->min_stock_level, 0) }})
            </div>
        </div>
        @endif
        
        <!-- Quick Actions -->
        <div style="display: flex; flex-direction: column; gap: 10px;">
            <a href="{{ route('tenant.inventory.movements.create') }}?product_id={{ $inventory->product_id }}&warehouse_id={{ $inventory->warehouse_id }}" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 12px; border-radius: 8px; text-decoration: none; font-weight: 600; text-align: center;">
                <i class="fas fa-plus"></i> إضافة حركة مخزون
            </a>
            <a href="{{ route('tenant.inventory.edit', $inventory) }}" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; padding: 12px; border-radius: 8px; text-decoration: none; font-weight: 600; text-align: center;">
                <i class="fas fa-edit"></i> تعديل المعلومات
            </a>
        </div>
        
        <!-- Last Updated -->
        <div style="margin-top: 25px; padding: 15px; background: #f1f5f9; border-radius: 8px;">
            <h4 style="margin: 0 0 10px 0; color: #374151; font-size: 14px; font-weight: 600;">آخر تحديث</h4>
            <div style="font-size: 12px; color: #6b7280;">{{ $inventory->updated_at->format('Y-m-d H:i') }}</div>
        </div>
    </div>
</div>
@endsection
