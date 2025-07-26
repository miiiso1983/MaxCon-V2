@extends('layouts.modern')

@section('page-title', 'تفاصيل العقد')
@section('page-description', 'عرض تفاصيل العقد')

@section('content')
<!-- Page Header -->
<div style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); border-radius: 20px; padding: 30px; margin-bottom: 30px; color: white; position: relative; overflow: hidden;">
    <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
    <div style="position: absolute; bottom: -30px; left: -30px; width: 150px; height: 150px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>
    
    <div style="position: relative; z-index: 2;">
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    <div style="background: rgba(255,255,255,0.2); border-radius: 15px; padding: 15px; margin-left: 20px;">
                        <i class="fas fa-file-contract" style="font-size: 32px;"></i>
                    </div>
                    <div>
                        <h1 style="font-size: 32px; font-weight: 800; margin: 0; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                            {{ $contract->title }} 📄
                        </h1>
                        <p style="font-size: 18px; margin: 5px 0 0 0; opacity: 0.9;">
                            رقم العقد: {{ $contract->contract_number }}
                        </p>
                    </div>
                </div>
                
                <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-truck" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px; font-weight: 600;">{{ $contract->supplier->name ?? 'غير محدد' }}</span>
                    </div>
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-tag" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px;">{{ $contract->type_text }}</span>
                    </div>
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-calendar" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px;">{{ $contract->start_date->format('Y/m/d') }} - {{ $contract->end_date->format('Y/m/d') }}</span>
                    </div>
                </div>
            </div>
            
            <div style="display: flex; gap: 15px;">
                <a href="{{ route('tenant.purchasing.contracts.edit', $contract) }}" style="background: rgba(255,255,255,0.2); color: white; padding: 15px 25px; border-radius: 15px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3);">
                    <i class="fas fa-edit"></i>
                    تعديل العقد
                </a>
                <a href="{{ route('tenant.purchasing.contracts.index') }}" style="background: rgba(255,255,255,0.2); color: white; padding: 15px 25px; border-radius: 15px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3);">
                    <i class="fas fa-arrow-right"></i>
                    العودة للقائمة
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Contract Status -->
<div style="margin-bottom: 30px;">
    @php
        $statusClass = match($contract->status) {
            'active' => 'status-active',
            'expired' => 'status-expired',
            default => 'status-default'
        };
    @endphp
    <div class="contract-status-banner {{ $statusClass }}" style="border-radius: 15px; padding: 20px; color: white; text-align: center;">
        <div style="font-size: 24px; margin-bottom: 10px;">
            @if($contract->status === 'active')
                <i class="fas fa-check-circle"></i>
            @elseif($contract->status === 'expired')
                <i class="fas fa-times-circle"></i>
            @else
                <i class="fas fa-clock"></i>
            @endif
        </div>
        <h3 style="margin: 0 0 5px 0; font-size: 20px; font-weight: 700;">{{ $contract->status_text }}</h3>
        @if($contract->is_expiring_soon)
            <p style="margin: 0; opacity: 0.9;">ينتهي خلال {{ abs($contract->days_until_expiry) }} يوم</p>
        @elseif($contract->is_expired)
            <p style="margin: 0; opacity: 0.9;">انتهى منذ {{ abs($contract->days_until_expiry) }} يوم</p>
        @endif
    </div>
</div>

<!-- Contract Details -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 30px;">
    
    <!-- Basic Information -->
    <div class="content-card">
        <h3 style="color: #2d3748; font-size: 20px; font-weight: 700; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-info-circle" style="color: #8b5cf6; margin-left: 10px;"></i>
            المعلومات الأساسية
        </h3>
        
        <div>
            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: 600; color: #6b7280; font-size: 12px; margin-bottom: 5px;">رقم العقد</label>
                <div style="color: #111827; font-weight: 600;">{{ $contract->contract_number }}</div>
            </div>
            
            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: 600; color: #6b7280; font-size: 12px; margin-bottom: 5px;">عنوان العقد</label>
                <div style="color: #111827; font-weight: 600;">{{ $contract->title }}</div>
            </div>
            
            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: 600; color: #6b7280; font-size: 12px; margin-bottom: 5px;">المورد</label>
                <div style="color: #111827; font-weight: 600;">{{ $contract->supplier->name ?? 'غير محدد' }}</div>
            </div>
            
            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: 600; color: #6b7280; font-size: 12px; margin-bottom: 5px;">نوع العقد</label>
                <div style="color: #111827; font-weight: 600;">{{ $contract->type_text }}</div>
            </div>
            
            @if($contract->description)
            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: 600; color: #6b7280; font-size: 12px; margin-bottom: 5px;">الوصف</label>
                <div style="color: #374151; line-height: 1.6;">{{ $contract->description }}</div>
            </div>
            @endif
        </div>
    </div>
    
    <!-- Financial Information -->
    <div class="content-card">
        <h3 style="color: #2d3748; font-size: 20px; font-weight: 700; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-dollar-sign" style="color: #8b5cf6; margin-left: 10px;"></i>
            المعلومات المالية
        </h3>
        
        <div>
            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: 600; color: #6b7280; font-size: 12px; margin-bottom: 5px;">قيمة العقد</label>
                <div style="color: #059669; font-weight: 700; font-size: 18px;">
                    {{ number_format($contract->contract_value, 2) }} {{ $contract->currency }}
                </div>
            </div>
            
            @if($contract->minimum_order_value)
            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: 600; color: #6b7280; font-size: 12px; margin-bottom: 5px;">الحد الأدنى للطلب</label>
                <div style="color: #111827; font-weight: 600;">
                    {{ number_format($contract->minimum_order_value, 2) }} {{ $contract->currency }}
                </div>
            </div>
            @endif
            
            @if($contract->maximum_order_value)
            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: 600; color: #6b7280; font-size: 12px; margin-bottom: 5px;">الحد الأقصى للطلب</label>
                <div style="color: #111827; font-weight: 600;">
                    {{ number_format($contract->maximum_order_value, 2) }} {{ $contract->currency }}
                </div>
            </div>
            @endif
            
            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: 600; color: #6b7280; font-size: 12px; margin-bottom: 5px;">العملة</label>
                <div style="color: #111827; font-weight: 600;">{{ $contract->currency }}</div>
            </div>
        </div>
    </div>
    
    <!-- Dates Information -->
    <div class="content-card">
        <h3 style="color: #2d3748; font-size: 20px; font-weight: 700; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-calendar-alt" style="color: #8b5cf6; margin-left: 10px;"></i>
            التواريخ المهمة
        </h3>
        
        <div>
            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: 600; color: #6b7280; font-size: 12px; margin-bottom: 5px;">تاريخ البداية</label>
                <div style="color: #111827; font-weight: 600;">{{ $contract->start_date->format('Y/m/d') }}</div>
            </div>
            
            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: 600; color: #6b7280; font-size: 12px; margin-bottom: 5px;">تاريخ الانتهاء</label>
                @php
                    $isExpired = $contract->end_date->isPast();
                    $isExpiringSoon = !$isExpired && $contract->end_date->diffInDays(now()) <= 30;
                    $dateClass = $isExpired ? 'text-danger' : ($isExpiringSoon ? 'text-warning' : 'text-dark');
                @endphp
                <div class="{{ $dateClass }}" style="font-weight: 600;">
                    {{ $contract->end_date->format('Y/m/d') }}
                    @if($isExpiringSoon)
                        <span style="color: #f59e0b; font-size: 12px;">(ينتهي قريباً)</span>
                    @elseif($isExpired)
                        <span style="color: #ef4444; font-size: 12px;">(منتهي)</span>
                    @endif
                </div>
            </div>
            
            @if($contract->signed_date)
            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: 600; color: #6b7280; font-size: 12px; margin-bottom: 5px;">تاريخ التوقيع</label>
                <div style="color: #111827; font-weight: 600;">{{ $contract->signed_date->format('Y/m/d') }}</div>
            </div>
            @endif
            
            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: 600; color: #6b7280; font-size: 12px; margin-bottom: 5px;">تاريخ الإنشاء</label>
                <div style="color: #111827; font-weight: 600;">{{ $contract->created_at->format('Y/m/d H:i') }}</div>
            </div>
        </div>
    </div>
    
    <!-- Additional Information -->
    <div class="content-card">
        <h3 style="color: #2d3748; font-size: 20px; font-weight: 700; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-cog" style="color: #8b5cf6; margin-left: 10px;"></i>
            معلومات إضافية
        </h3>
        
        <div>
            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: 600; color: #6b7280; font-size: 12px; margin-bottom: 5px;">أنشأ بواسطة</label>
                <div style="color: #111827; font-weight: 600;">{{ $contract->createdBy->name ?? 'غير محدد' }}</div>
            </div>
            
            @if($contract->approvedBy)
            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: 600; color: #6b7280; font-size: 12px; margin-bottom: 5px;">وافق عليه</label>
                <div style="color: #111827; font-weight: 600;">{{ $contract->approvedBy->name }}</div>
            </div>
            @endif
            
            @if($contract->notes)
            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: 600; color: #6b7280; font-size: 12px; margin-bottom: 5px;">ملاحظات</label>
                <div style="color: #374151; line-height: 1.6;">{{ $contract->notes }}</div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Contract status banners */
.contract-status-banner.status-active {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
}
.contract-status-banner.status-expired {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
}
.contract-status-banner.status-default {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
}

/* Text colors */
.text-danger {
    color: #ef4444 !important;
}
.text-warning {
    color: #f59e0b !important;
}
.text-dark {
    color: #111827 !important;
}
</style>
@endpush
