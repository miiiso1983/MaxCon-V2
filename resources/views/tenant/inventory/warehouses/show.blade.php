@extends('layouts.modern')

@section('page-title', 'مستودع ' . $warehouse->name)
@section('page-description', 'تفاصيل وإحصائيات المستودع')

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
                        <i class="fas fa-warehouse" style="font-size: 32px;"></i>
                    </div>
                    <div>
                        <h1 style="font-size: 32px; font-weight: 800; margin: 0; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                            {{ $warehouse->name }} 🏭
                        </h1>
                        <p style="font-size: 18px; margin: 5px 0 0 0; opacity: 0.9;">
                            {{ $warehouse->code }} - {{ $warehouse->getTypeLabel() }}
                        </p>
                    </div>
                </div>
                
                <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        @if($warehouse->is_active)
                            <i class="fas fa-check-circle" style="margin-left: 8px; color: #4ade80;"></i>
                            <span style="font-size: 14px; font-weight: 600;">نشط</span>
                        @else
                            <i class="fas fa-times-circle" style="margin-left: 8px; color: #f87171;"></i>
                            <span style="font-size: 14px; font-weight: 600;">غير نشط</span>
                        @endif
                    </div>
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-boxes" style="margin-left: 8px; color: #fbbf24;"></i>
                        <span style="font-size: 14px;">{{ $stats['total_products'] }} منتج</span>
                    </div>
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-cubes" style="margin-left: 8px; color: #34d399;"></i>
                        <span style="font-size: 14px;">{{ number_format($stats['total_quantity'], 0) }} وحدة</span>
                    </div>
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-dollar-sign" style="margin-left: 8px; color: #60a5fa;"></i>
                        <span style="font-size: 14px;">{{ number_format($stats['total_value'], 0) }} د.ع</span>
                    </div>
                </div>
            </div>
            
            <div style="display: flex; gap: 15px;">
                <a href="{{ route('tenant.inventory.warehouses.edit', $warehouse) }}" style="background: rgba(255,255,255,0.2); color: white; padding: 15px 25px; border-radius: 15px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3);">
                    <i class="fas fa-edit"></i>
                    تعديل
                </a>
                <a href="{{ route('tenant.inventory.warehouses.index') }}" style="background: rgba(255,255,255,0.2); color: white; padding: 15px 25px; border-radius: 15px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3);">
                    <i class="fas fa-arrow-right"></i>
                    العودة للقائمة
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Warehouse Details -->
<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 25px; margin-bottom: 30px;">
    <!-- Main Details -->
    <div class="content-card">
        <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-info-circle" style="color: #667eea; margin-left: 10px;"></i>
            معلومات المستودع
        </h3>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            <div>
                <label style="font-weight: 600; color: #4a5568; margin-bottom: 5px; display: block;">اسم المستودع</label>
                <div style="padding: 10px; background: #f8fafc; border-radius: 6px; font-weight: 600;">{{ $warehouse->name }}</div>
            </div>
            <div>
                <label style="font-weight: 600; color: #4a5568; margin-bottom: 5px; display: block;">كود المستودع</label>
                <div style="padding: 10px; background: #f8fafc; border-radius: 6px; font-weight: 600;">{{ $warehouse->code }}</div>
            </div>
            <div>
                <label style="font-weight: 600; color: #4a5568; margin-bottom: 5px; display: block;">النوع</label>
                <div style="padding: 10px; background: #f8fafc; border-radius: 6px;">{{ $warehouse->getTypeLabel() }}</div>
            </div>
            <div>
                <label style="font-weight: 600; color: #4a5568; margin-bottom: 5px; display: block;">الحالة</label>
                <div style="padding: 10px; background: #f8fafc; border-radius: 6px;">
                    @if($warehouse->is_active)
                        <span style="color: #059669; font-weight: 600;">
                            <i class="fas fa-check-circle"></i> نشط
                        </span>
                    @else
                        <span style="color: #dc2626; font-weight: 600;">
                            <i class="fas fa-times-circle"></i> غير نشط
                        </span>
                    @endif
                </div>
            </div>
            @if($warehouse->location)
            <div>
                <label style="font-weight: 600; color: #4a5568; margin-bottom: 5px; display: block;">الموقع</label>
                <div style="padding: 10px; background: #f8fafc; border-radius: 6px;">{{ $warehouse->location }}</div>
            </div>
            @endif
            @if($warehouse->manager)
            <div>
                <label style="font-weight: 600; color: #4a5568; margin-bottom: 5px; display: block;">المدير</label>
                <div style="padding: 10px; background: #f8fafc; border-radius: 6px;">{{ $warehouse->manager->name }}</div>
            </div>
            @endif
        </div>
        
        @if($warehouse->description)
        <div style="margin-bottom: 20px;">
            <label style="font-weight: 600; color: #4a5568; margin-bottom: 5px; display: block;">الوصف</label>
            <div style="padding: 15px; background: #f8fafc; border-radius: 6px; line-height: 1.6;">{{ $warehouse->description }}</div>
        </div>
        @endif
        
        @if($warehouse->address)
        <div>
            <label style="font-weight: 600; color: #4a5568; margin-bottom: 5px; display: block;">العنوان</label>
            <div style="padding: 15px; background: #f8fafc; border-radius: 6px; line-height: 1.6;">{{ $warehouse->address }}</div>
        </div>
        @endif
    </div>
    
    <!-- Statistics -->
    <div class="content-card">
        <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-chart-bar" style="color: #667eea; margin-left: 10px;"></i>
            الإحصائيات
        </h3>
        
        <!-- Capacity Usage -->
        @if($warehouse->total_capacity > 0)
        <div style="margin-bottom: 20px; padding: 15px; background: #f0f9ff; border-radius: 8px;">
            <h4 style="margin: 0 0 10px 0; color: #1e40af; font-size: 14px; font-weight: 600;">استخدام السعة</h4>
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                <span style="font-size: 12px; color: #374151;">{{ number_format($warehouse->used_capacity, 0) }} / {{ number_format($warehouse->total_capacity, 0) }} م³</span>
                <span style="font-size: 12px; font-weight: 600; color: #1e40af;">{{ $stats['capacity_usage'] }}%</span>
            </div>
            <div style="background: #e0f2fe; border-radius: 10px; height: 10px; overflow: hidden;">
                <div style="background: linear-gradient(90deg, #0ea5e9, #0284c7); height: 100%; width: {{ $stats['capacity_usage'] }}%; transition: width 0.3s ease;"></div>
            </div>
        </div>
        @endif
        
        <!-- Key Metrics -->
        <div style="display: grid; grid-template-columns: 1fr; gap: 15px;">
            <div style="text-align: center; padding: 15px; background: #f8fafc; border-radius: 8px; border-left: 4px solid #3b82f6;">
                <div style="font-size: 24px; font-weight: 700; color: #3b82f6;">{{ $stats['total_products'] }}</div>
                <div style="font-size: 12px; color: #6b7280;">إجمالي المنتجات</div>
            </div>
            
            <div style="text-align: center; padding: 15px; background: #f8fafc; border-radius: 8px; border-left: 4px solid #059669;">
                <div style="font-size: 24px; font-weight: 700; color: #059669;">{{ number_format($stats['total_quantity'], 0) }}</div>
                <div style="font-size: 12px; color: #6b7280;">إجمالي الكمية</div>
            </div>
            
            <div style="text-align: center; padding: 15px; background: #f8fafc; border-radius: 8px; border-left: 4px solid #10b981;">
                <div style="font-size: 24px; font-weight: 700; color: #10b981;">{{ number_format($stats['available_quantity'], 0) }}</div>
                <div style="font-size: 12px; color: #6b7280;">الكمية المتاحة</div>
            </div>
            
            <div style="text-align: center; padding: 15px; background: #f8fafc; border-radius: 8px; border-left: 4px solid #f59e0b;">
                <div style="font-size: 24px; font-weight: 700; color: #f59e0b;">{{ number_format($stats['reserved_quantity'], 0) }}</div>
                <div style="font-size: 12px; color: #6b7280;">الكمية المحجوزة</div>
            </div>
            
            <div style="text-align: center; padding: 15px; background: #f8fafc; border-radius: 8px; border-left: 4px solid #8b5cf6;">
                <div style="font-size: 20px; font-weight: 700; color: #8b5cf6;">{{ number_format($stats['total_value'], 0) }}</div>
                <div style="font-size: 12px; color: #6b7280;">القيمة الإجمالية (د.ع)</div>
            </div>
            
            <div style="text-align: center; padding: 15px; background: #f8fafc; border-radius: 8px; border-left: 4px solid #6366f1;">
                <div style="font-size: 24px; font-weight: 700; color: #6366f1;">{{ $stats['locations_count'] }}</div>
                <div style="font-size: 12px; color: #6b7280;">عدد المواقع</div>
            </div>
        </div>
        
        <!-- Contact Info -->
        @if($warehouse->phone || $warehouse->email)
        <div style="margin-top: 20px; padding: 15px; background: #fef7ff; border-radius: 8px;">
            <h4 style="margin: 0 0 10px 0; color: #7c3aed; font-size: 14px; font-weight: 600;">معلومات الاتصال</h4>
            @if($warehouse->phone)
                <div style="font-size: 13px; color: #374151; margin-bottom: 5px;">
                    <i class="fas fa-phone" style="color: #10b981; margin-left: 5px;"></i>
                    {{ $warehouse->phone }}
                </div>
            @endif
            @if($warehouse->email)
                <div style="font-size: 13px; color: #374151;">
                    <i class="fas fa-envelope" style="color: #3b82f6; margin-left: 5px;"></i>
                    {{ $warehouse->email }}
                </div>
            @endif
        </div>
        @endif
    </div>
</div>

<!-- Warehouse Locations -->
<div class="content-card" style="margin-bottom: 25px;">
    <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
        <i class="fas fa-map-marked-alt" style="color: #667eea; margin-left: 10px;"></i>
        مواقع المستودع ({{ $warehouse->locations->count() }})
    </h3>
    
    @if($warehouse->locations->count() > 0)
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 15px;">
            @foreach($warehouse->locations as $location)
                <div style="background: #f8fafc; border-radius: 10px; padding: 15px; border: 1px solid #e2e8f0;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                        <h4 style="font-size: 16px; font-weight: 600; color: #2d3748; margin: 0;">{{ $location->code }}</h4>
                        @if($location->is_active)
                            <span style="background: #d1fae5; color: #065f46; padding: 2px 6px; border-radius: 8px; font-size: 10px; font-weight: 600;">نشط</span>
                        @else
                            <span style="background: #fee2e2; color: #991b1b; padding: 2px 6px; border-radius: 8px; font-size: 10px; font-weight: 600;">غير نشط</span>
                        @endif
                    </div>
                    
                    <div style="font-size: 14px; color: #4a5568; margin-bottom: 10px;">{{ $location->name }}</div>
                    
                    @if($location->capacity > 0)
                        <div style="margin-bottom: 8px;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 3px;">
                                <span style="font-size: 11px; color: #6b7280;">السعة</span>
                                <span style="font-size: 11px; font-weight: 600; color: #4a5568;">{{ $location->getCapacityUsagePercentage() }}%</span>
                            </div>
                            <div style="background: #e2e8f0; border-radius: 6px; height: 6px; overflow: hidden;">
                                <div style="background: linear-gradient(90deg, #10b981, #059669); height: 100%; width: {{ $location->getCapacityUsagePercentage() }}%; transition: width 0.3s ease;"></div>
                            </div>
                        </div>
                    @endif
                    
                    <div style="font-size: 12px; color: #6b7280;">{{ $location->getTypeLabel() }}</div>
                </div>
            @endforeach
        </div>
    @else
        <div style="text-align: center; padding: 30px; color: #6b7280;">
            <i class="fas fa-map-marked-alt" style="font-size: 36px; margin-bottom: 10px; opacity: 0.5;"></i>
            <h4 style="margin: 0 0 5px 0;">لا توجد مواقع</h4>
            <p style="margin: 0;">لم يتم إنشاء أي مواقع في هذا المستودع بعد</p>
        </div>
    @endif
</div>

<!-- Quick Actions -->
<div class="content-card">
    <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
        <i class="fas fa-bolt" style="color: #667eea; margin-left: 10px;"></i>
        إجراءات سريعة
    </h3>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
        <a href="#" style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); color: white; padding: 15px; border-radius: 10px; text-decoration: none; text-align: center; font-weight: 600; transition: transform 0.3s ease;"
           onmouseover="this.style.transform='translateY(-2px)'"
           onmouseout="this.style.transform='translateY(0)'">
            <i class="fas fa-plus-circle" style="font-size: 20px; margin-bottom: 8px; display: block;"></i>
            إضافة مخزون
        </a>
        
        <a href="#" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 15px; border-radius: 10px; text-decoration: none; text-align: center; font-weight: 600; transition: transform 0.3s ease;"
           onmouseover="this.style.transform='translateY(-2px)'"
           onmouseout="this.style.transform='translateY(0)'">
            <i class="fas fa-exchange-alt" style="font-size: 20px; margin-bottom: 8px; display: block;"></i>
            تحويل مخزون
        </a>
        
        <a href="#" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; padding: 15px; border-radius: 10px; text-decoration: none; text-align: center; font-weight: 600; transition: transform 0.3s ease;"
           onmouseover="this.style.transform='translateY(-2px)'"
           onmouseout="this.style.transform='translateY(0)'">
            <i class="fas fa-clipboard-list" style="font-size: 20px; margin-bottom: 8px; display: block;"></i>
            جرد المستودع
        </a>
        
        <a href="#" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); color: white; padding: 15px; border-radius: 10px; text-decoration: none; text-align: center; font-weight: 600; transition: transform 0.3s ease;"
           onmouseover="this.style.transform='translateY(-2px)'"
           onmouseout="this.style.transform='translateY(0)'">
            <i class="fas fa-chart-line" style="font-size: 20px; margin-bottom: 8px; display: block;"></i>
            تقرير المخزون
        </a>
    </div>
</div>
@endsection
