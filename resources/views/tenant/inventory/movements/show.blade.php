@extends('layouts.modern')

@section('page-title', 'حركة المخزون ' . $movement->movement_number)
@section('page-description', 'تفاصيل حركة المخزون')

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
                            {{ $movement->movement_number }} 📋
                        </h1>
                        <p style="font-size: 18px; margin: 5px 0 0 0; opacity: 0.9;">
                            {{ $movement->getMovementTypeLabel() }} - {{ $movement->getMovementReasonLabel() }}
                        </p>
                    </div>
                </div>
                
                <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-calendar" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px; font-weight: 600;">{{ $movement->movement_date->format('Y-m-d H:i') }}</span>
                    </div>
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-warehouse" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px;">{{ $movement->warehouse->name }}</span>
                    </div>
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-box" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px;">{{ $movement->product->name }}</span>
                    </div>
                </div>
            </div>
            
            <div style="display: flex; gap: 15px;">
                <a href="{{ route('tenant.inventory.movements.index') }}" style="background: rgba(255,255,255,0.2); color: white; padding: 15px 25px; border-radius: 15px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3);">
                    <i class="fas fa-arrow-right"></i>
                    العودة للقائمة
                </a>
            </div>
        </div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 25px;">
    <!-- Movement Details -->
    <div class="content-card">
        <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-info-circle" style="color: #667eea; margin-left: 10px;"></i>
            تفاصيل الحركة
        </h3>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            <div>
                <label style="font-weight: 600; color: #4a5568; margin-bottom: 5px; display: block;">رقم الحركة</label>
                <div style="padding: 10px; background: #f8fafc; border-radius: 6px; font-weight: 600;">{{ $movement->movement_number }}</div>
            </div>
            <div>
                <label style="font-weight: 600; color: #4a5568; margin-bottom: 5px; display: block;">نوع الحركة</label>
                <div style="padding: 10px; background: #f8fafc; border-radius: 6px;">
                    <span style="background: {{ $movement->getMovementTypeColor() === 'success' ? '#d1fae5' : ($movement->getMovementTypeColor() === 'danger' ? '#fee2e2' : '#fef3c7') }}; 
                                 color: {{ $movement->getMovementTypeColor() === 'success' ? '#065f46' : ($movement->getMovementTypeColor() === 'danger' ? '#991b1b' : '#92400e') }}; 
                                 padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                        {{ $movement->getMovementTypeLabel() }}
                    </span>
                </div>
            </div>
            <div>
                <label style="font-weight: 600; color: #4a5568; margin-bottom: 5px; display: block;">سبب الحركة</label>
                <div style="padding: 10px; background: #f8fafc; border-radius: 6px;">{{ $movement->getMovementReasonLabel() }}</div>
            </div>
            <div>
                <label style="font-weight: 600; color: #4a5568; margin-bottom: 5px; display: block;">تاريخ الحركة</label>
                <div style="padding: 10px; background: #f8fafc; border-radius: 6px;">{{ $movement->movement_date->format('Y-m-d H:i') }}</div>
            </div>
            <div>
                <label style="font-weight: 600; color: #4a5568; margin-bottom: 5px; display: block;">المستودع</label>
                <div style="padding: 10px; background: #f8fafc; border-radius: 6px;">{{ $movement->warehouse->name }} ({{ $movement->warehouse->code }})</div>
            </div>
            <div>
                <label style="font-weight: 600; color: #4a5568; margin-bottom: 5px; display: block;">المنتج</label>
                <div style="padding: 10px; background: #f8fafc; border-radius: 6px;">{{ $movement->product->name }} ({{ $movement->product->code }})</div>
            </div>
        </div>
        
        @if($movement->reference_number)
        <div style="margin-bottom: 20px;">
            <label style="font-weight: 600; color: #4a5568; margin-bottom: 5px; display: block;">رقم المرجع</label>
            <div style="padding: 15px; background: #f8fafc; border-radius: 6px; font-weight: 600;">{{ $movement->reference_number }}</div>
        </div>
        @endif
        
        @if($movement->notes)
        <div>
            <label style="font-weight: 600; color: #4a5568; margin-bottom: 5px; display: block;">الملاحظات</label>
            <div style="padding: 15px; background: #f8fafc; border-radius: 6px; line-height: 1.6;">{{ $movement->notes }}</div>
        </div>
        @endif
    </div>
    
    <!-- Movement Summary -->
    <div class="content-card">
        <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-chart-bar" style="color: #667eea; margin-left: 10px;"></i>
            ملخص الحركة
        </h3>
        
        <!-- Quantity Info -->
        <div style="margin-bottom: 20px; padding: 15px; background: #f0f9ff; border-radius: 8px;">
            <h4 style="margin: 0 0 10px 0; color: #1e40af; font-size: 14px; font-weight: 600;">معلومات الكمية</h4>
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                <span style="font-size: 12px; color: #374151;">الكمية</span>
                <span style="font-size: 16px; font-weight: 700; color: {{ $movement->isInMovement() ? '#059669' : '#dc2626' }};">
                    {{ $movement->isInMovement() ? '+' : '-' }}{{ number_format($movement->quantity, 3) }}
                </span>
            </div>
            @if($movement->unit_cost > 0)
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                <span style="font-size: 12px; color: #374151;">تكلفة الوحدة</span>
                <span style="font-size: 14px; font-weight: 600; color: #374151;">{{ number_format($movement->unit_cost, 2) }} د.ع</span>
            </div>
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span style="font-size: 12px; color: #374151;">التكلفة الإجمالية</span>
                <span style="font-size: 16px; font-weight: 700; color: #1e40af;">{{ number_format($movement->total_cost, 2) }} د.ع</span>
            </div>
            @endif
        </div>
        
        <!-- Balance Info -->
        <div style="margin-bottom: 20px; padding: 15px; background: #fef7ff; border-radius: 8px;">
            <h4 style="margin: 0 0 10px 0; color: #7c3aed; font-size: 14px; font-weight: 600;">معلومات الرصيد</h4>
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                <span style="font-size: 12px; color: #374151;">الرصيد قبل الحركة</span>
                <span style="font-size: 14px; font-weight: 600; color: #374151;">{{ number_format($movement->balance_before, 3) }}</span>
            </div>
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span style="font-size: 12px; color: #374151;">الرصيد بعد الحركة</span>
                <span style="font-size: 16px; font-weight: 700; color: #7c3aed;">{{ number_format($movement->balance_after, 3) }}</span>
            </div>
        </div>
        
        <!-- Created By -->
        @if($movement->createdBy)
        <div style="padding: 15px; background: #f0fdf4; border-radius: 8px;">
            <h4 style="margin: 0 0 10px 0; color: #166534; font-size: 14px; font-weight: 600;">معلومات الإنشاء</h4>
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px;">
                <i class="fas fa-user" style="color: #059669;"></i>
                <span style="font-size: 14px; font-weight: 600; color: #374151;">{{ $movement->createdBy->name }}</span>
            </div>
            <div style="display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-clock" style="color: #059669;"></i>
                <span style="font-size: 12px; color: #6b7280;">{{ $movement->created_at->format('Y-m-d H:i') }}</span>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Related Inventory -->
@if($movement->inventory)
<div class="content-card" style="margin-top: 25px;">
    <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
        <i class="fas fa-boxes" style="color: #667eea; margin-left: 10px;"></i>
        معلومات المخزون المرتبط
    </h3>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
        <div style="text-align: center; padding: 15px; background: #f8fafc; border-radius: 8px;">
            <div style="font-size: 20px; font-weight: 700; color: #3b82f6;">{{ number_format($movement->inventory->quantity, 3) }}</div>
            <div style="font-size: 12px; color: #6b7280;">الكمية الإجمالية</div>
        </div>
        
        <div style="text-align: center; padding: 15px; background: #f8fafc; border-radius: 8px;">
            <div style="font-size: 20px; font-weight: 700; color: #10b981;">{{ number_format($movement->inventory->available_quantity, 3) }}</div>
            <div style="font-size: 12px; color: #6b7280;">الكمية المتاحة</div>
        </div>
        
        <div style="text-align: center; padding: 15px; background: #f8fafc; border-radius: 8px;">
            <div style="font-size: 20px; font-weight: 700; color: #f59e0b;">{{ number_format($movement->inventory->reserved_quantity, 3) }}</div>
            <div style="font-size: 12px; color: #6b7280;">الكمية المحجوزة</div>
        </div>
        
        <div style="text-align: center; padding: 15px; background: #f8fafc; border-radius: 8px;">
            <div style="font-size: 20px; font-weight: 700; color: #8b5cf6;">{{ $movement->inventory->getStatusLabel() }}</div>
            <div style="font-size: 12px; color: #6b7280;">حالة المخزون</div>
        </div>
    </div>
</div>
@endif
@endsection
