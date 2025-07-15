@extends('layouts.modern')

@section('page-title', 'تفاصيل العميل - ' . $customer->name)
@section('page-description', 'عرض تفاصيل العميل والمعاملات المالية')

@section('content')
<!-- Page Header -->
<div style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); border-radius: 20px; padding: 30px; margin-bottom: 30px; color: white; position: relative; overflow: hidden;">
    <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
    <div style="position: absolute; bottom: -30px; left: -30px; width: 150px; height: 150px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>
    
    <div style="position: relative; z-index: 2;">
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    <div style="width: 80px; height: 80px; border-radius: 50%; background: rgba(255,255,255,0.2); color: white; display: flex; align-items: center; justify-content: center; margin-left: 20px; font-weight: 700; font-size: 32px; backdrop-filter: blur(10px);">
                        {{ substr($customer->name, 0, 1) }}
                    </div>
                    <div>
                        <h1 style="font-size: 32px; font-weight: 800; margin: 0; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                            {{ $customer->name }}
                        </h1>
                        <p style="font-size: 18px; margin: 5px 0 0 0; opacity: 0.9;">
                            {{ $customer->customer_code }} • {{ $customer->customer_type === 'company' ? 'شركة' : 'فرد' }}
                        </p>
                    </div>
                </div>
                
                <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-credit-card" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px; font-weight: 600;">حد ائتماني: {{ number_format($customer->credit_limit, 2) }} {{ $customer->currency }}</span>
                    </div>
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-wallet" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px;">رصيد: {{ number_format($customer->current_balance, 2) }} {{ $customer->currency }}</span>
                    </div>
                    @if($customer->is_active)
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
                </div>
            </div>
            
            <div style="display: flex; gap: 15px;">
                <a href="{{ route('tenant.sales.customers.edit', $customer) }}" style="background: rgba(255,255,255,0.2); color: white; padding: 15px 25px; border-radius: 15px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3); transition: all 0.3s ease;"
                   onmouseover="this.style.background='rgba(255,255,255,0.3)'; this.style.transform='translateY(-2px)';"
                   onmouseout="this.style.background='rgba(255,255,255,0.2)'; this.style.transform='translateY(0)';">
                    <i class="fas fa-edit"></i>
                    تعديل البيانات
                </a>
                <a href="{{ route('tenant.sales.customers.index') }}" style="background: rgba(255,255,255,0.2); color: white; padding: 15px 25px; border-radius: 15px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3); transition: all 0.3s ease;"
                   onmouseover="this.style.background='rgba(255,255,255,0.3)'; this.style.transform='translateY(-2px)';"
                   onmouseout="this.style.background='rgba(255,255,255,0.2)'; this.style.transform='translateY(0)';">
                    <i class="fas fa-arrow-right"></i>
                    العودة للقائمة
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Customer Information Cards -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 25px; margin-bottom: 30px;">
    <!-- Contact Information -->
    <div class="content-card">
        <h3 style="font-size: 18px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-address-card" style="color: #4299e1; margin-left: 10px;"></i>
            معلومات الاتصال
        </h3>
        
        <div style="space-y: 15px;">
            @if($customer->email)
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    <div style="width: 40px; height: 40px; border-radius: 50%; background: #dbeafe; color: #1e40af; display: flex; align-items: center; justify-content: center; margin-left: 15px;">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div>
                        <div style="font-size: 12px; color: #6b7280; margin-bottom: 2px;">البريد الإلكتروني</div>
                        <div style="font-weight: 600; color: #1f2937;">{{ $customer->email }}</div>
                    </div>
                </div>
            @endif
            
            @if($customer->mobile)
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    <div style="width: 40px; height: 40px; border-radius: 50%; background: #dcfce7; color: #16a34a; display: flex; align-items: center; justify-content: center; margin-left: 15px;">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <div>
                        <div style="font-size: 12px; color: #6b7280; margin-bottom: 2px;">الجوال</div>
                        <div style="font-weight: 600; color: #1f2937;">{{ $customer->mobile }}</div>
                    </div>
                </div>
            @endif
            
            @if($customer->phone)
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    <div style="width: 40px; height: 40px; border-radius: 50%; background: #fef3c7; color: #d97706; display: flex; align-items: center; justify-content: center; margin-left: 15px;">
                        <i class="fas fa-phone"></i>
                    </div>
                    <div>
                        <div style="font-size: 12px; color: #6b7280; margin-bottom: 2px;">الهاتف</div>
                        <div style="font-weight: 600; color: #1f2937;">{{ $customer->phone }}</div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Address Information -->
    <div class="content-card">
        <h3 style="font-size: 18px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-map-marker-alt" style="color: #9f7aea; margin-left: 10px;"></i>
            معلومات العنوان
        </h3>
        
        @if($customer->address || $customer->city || $customer->state)
            <div style="background: #f8fafc; border-radius: 8px; padding: 15px;">
                @if($customer->address)
                    <div style="margin-bottom: 10px;">
                        <div style="font-size: 12px; color: #6b7280; margin-bottom: 2px;">العنوان</div>
                        <div style="color: #374151;">{{ $customer->address }}</div>
                    </div>
                @endif
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(120px, 1fr)); gap: 15px;">
                    @if($customer->city)
                        <div>
                            <div style="font-size: 12px; color: #6b7280; margin-bottom: 2px;">المدينة</div>
                            <div style="font-weight: 600; color: #374151;">{{ $customer->city }}</div>
                        </div>
                    @endif
                    
                    @if($customer->state)
                        <div>
                            <div style="font-size: 12px; color: #6b7280; margin-bottom: 2px;">المنطقة</div>
                            <div style="font-weight: 600; color: #374151;">{{ $customer->state }}</div>
                        </div>
                    @endif
                    
                    @if($customer->postal_code)
                        <div>
                            <div style="font-size: 12px; color: #6b7280; margin-bottom: 2px;">الرمز البريدي</div>
                            <div style="font-weight: 600; color: #374151;">{{ $customer->postal_code }}</div>
                        </div>
                    @endif
                </div>
            </div>
        @else
            <div style="text-align: center; color: #9ca3af; padding: 20px;">
                <i class="fas fa-map-marker-alt" style="font-size: 24px; margin-bottom: 10px;"></i>
                <p style="margin: 0;">لا توجد معلومات عنوان</p>
            </div>
        @endif
    </div>

    <!-- Financial Information -->
    <div class="content-card">
        <h3 style="font-size: 18px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-chart-line" style="color: #059669; margin-left: 10px;"></i>
            المعلومات المالية
        </h3>
        
        <div style="space-y: 15px;">
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px; background: #f0f9ff; border-radius: 8px; margin-bottom: 10px;">
                <span style="color: #0369a1; font-weight: 600;">شروط الدفع</span>
                <span style="color: #1e40af;">
                    {{ match($customer->payment_terms) {
                        'cash' => 'نقداً',
                        'credit_7' => 'آجل 7 أيام',
                        'credit_15' => 'آجل 15 يوم',
                        'credit_30' => 'آجل 30 يوم',
                        'credit_60' => 'آجل 60 يوم',
                        'credit_90' => 'آجل 90 يوم',
                        default => $customer->payment_terms
                    } }}
                </span>
            </div>
            
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px; background: #f0fdf4; border-radius: 8px; margin-bottom: 10px;">
                <span style="color: #16a34a; font-weight: 600;">الحد الائتماني</span>
                <span style="color: #15803d; font-weight: 700;">{{ number_format($customer->credit_limit, 2) }} {{ $customer->currency }}</span>
            </div>
            
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px; background: {{ $customer->current_balance > 0 ? '#fef2f2' : '#f0fdf4' }}; border-radius: 8px;">
                <span style="color: {{ $customer->current_balance > 0 ? '#dc2626' : '#16a34a' }}; font-weight: 600;">الرصيد الحالي</span>
                <span style="color: {{ $customer->current_balance > 0 ? '#b91c1c' : '#15803d' }}; font-weight: 700;">{{ number_format($customer->current_balance, 2) }} {{ $customer->currency }}</span>
            </div>
        </div>
    </div>

    <!-- Business Information -->
    @if($customer->tax_number || $customer->commercial_register)
    <div class="content-card">
        <h3 style="font-size: 18px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-building" style="color: #f59e0b; margin-left: 10px;"></i>
            المعلومات التجارية
        </h3>
        
        <div style="space-y: 15px;">
            @if($customer->tax_number)
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px; background: #fffbeb; border-radius: 8px; margin-bottom: 10px;">
                    <span style="color: #d97706; font-weight: 600;">الرقم الضريبي</span>
                    <span style="color: #92400e; font-family: monospace;">{{ $customer->tax_number }}</span>
                </div>
            @endif
            
            @if($customer->commercial_register)
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px; background: #fdf4ff; border-radius: 8px;">
                    <span style="color: #a855f7; font-weight: 600;">السجل التجاري</span>
                    <span style="color: #7c2d12; font-family: monospace;">{{ $customer->commercial_register }}</span>
                </div>
            @endif
        </div>
    </div>
    @endif
</div>

<!-- Notes Section -->
@if($customer->notes)
<div class="content-card" style="margin-bottom: 30px;">
    <h3 style="font-size: 18px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
        <i class="fas fa-sticky-note" style="color: #6366f1; margin-left: 10px;"></i>
        ملاحظات
    </h3>
    
    <div style="background: #f8fafc; border-radius: 8px; padding: 20px; border-right: 4px solid #6366f1;">
        <p style="color: #374151; margin: 0; line-height: 1.6;">{{ $customer->notes }}</p>
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
        <i class="fas fa-clock" style="font-size: 48px; margin-bottom: 15px;"></i>
        <p style="font-size: 16px; margin: 0;">سيتم عرض الطلبات والفواتير هنا قريباً</p>
    </div>
</div>
@endsection
