@extends('layouts.modern')

@section('page-title', "تفاصيل العميل: {$customer->name}")
@section('page-description', "عرض تفاصيل العميل في مؤسسة {$tenant->name}")

@push('styles')
<style>
    .customer-status-badge {
        background: rgba(255,255,255,0.15);
        border-radius: 25px;
        padding: 8px 16px;
        backdrop-filter: blur(10px);
    }

    .status-icon-active {
        color: #4ade80;
    }

    .status-icon-inactive {
        color: #f87171;
    }

    .toggle-status-btn {
        color: white;
        padding: 12px 25px;
        border: none;
        border-radius: 25px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: transform 0.2s;
    }

    .toggle-status-btn:hover {
        transform: translateY(-2px);
    }

    .btn-deactivate-customer {
        background: linear-gradient(135deg, #e53e3e 0%, #c53030 100%);
    }

    .btn-activate-customer {
        background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
    }

    /* Remove space-y unknown property warnings */
    .space-y-4 > * + * {
        margin-top: 1rem;
    }

    .space-y-6 > * + * {
        margin-top: 1.5rem;
    }

    /* Responsive improvements */
    @media (max-width: 767px) {
        .customer-status-badge {
            padding: 6px 12px;
            font-size: 12px;
        }

        .toggle-status-btn {
            width: 100%;
            justify-content: center;
            padding: 16px 20px;
            font-size: 16px;
            min-height: 44px;
        }

        .card {
            margin: 8px;
            border-radius: 12px;
        }

        .card-header,
        .card-body {
            padding: 16px;
        }
    }

    @media (min-width: 768px) and (max-width: 1023px) {
        .toggle-status-btn {
            padding: 14px 22px;
        }
    }
</style>
@endpush

@section('content')
<!-- Page Header -->
<div style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); border-radius: 20px; padding: 30px; margin-bottom: 30px; color: white; position: relative; overflow: hidden;">
    <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
    <div style="position: absolute; bottom: -30px; left: -30px; width: 150px; height: 150px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>
    
    <div style="position: relative; z-index: 2;">
        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 20px;">
            <div style="display: flex; align-items: center; gap: 25px;">
                <div style="width: 100px; height: 100px; border-radius: 50%; background: rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center; color: white; font-size: 40px; font-weight: 800; backdrop-filter: blur(10px);">
                    {{ substr($customer->name, 0, 1) }}
                </div>
                <div>
                    <h1 style="font-size: 32px; font-weight: 800; margin: 0; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                        {{ $customer->name }} 👤
                    </h1>
                    <p style="font-size: 18px; margin: 5px 0 15px 0; opacity: 0.9;">
                        عميل في مؤسسة {{ $tenant->name }}
                    </p>
                    
                    <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                        <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                            <i class="fas fa-envelope" style="margin-left: 8px;"></i>
                            <span style="font-size: 14px; font-weight: 600;">{{ $customer->email }}</span>
                        </div>
                        @if($customer->phone)
                        <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                            <i class="fas fa-phone" style="margin-left: 8px;"></i>
                            <span style="font-size: 14px;">{{ $customer->phone }}</span>
                        </div>
                        @endif
                        <div class="customer-status-badge">
                            <i class="fas fa-{{ $customer->is_active ? 'check-circle' : 'times-circle' }} {{ $customer->is_active ? 'status-icon-active' : 'status-icon-inactive' }}" style="margin-left: 8px;"></i>
                            <span style="font-size: 14px;">{{ $customer->is_active ? 'نشط' : 'غير نشط' }}</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div style="text-align: center;">
                <a href="{{ route('admin.customers.index', $tenant) }}" 
                   style="background: rgba(255,255,255,0.2); color: white; padding: 12px 24px; border-radius: 25px; text-decoration: none; font-weight: 600; backdrop-filter: blur(10px); transition: all 0.3s ease;">
                    <i class="fas fa-arrow-right" style="margin-left: 8px;"></i>
                    العودة للعملاء
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Customer Information -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 25px; margin-bottom: 30px;">
    <!-- Basic Information -->
    <div class="content-card">
        <h3 style="font-size: 18px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
            <i class="fas fa-user" style="color: #48bb78;"></i>
            المعلومات الأساسية
        </h3>
        
        <div class="space-y-4">
            <div style="margin-bottom: 15px;">
                <label style="font-weight: 600; color: #4a5568; display: block; margin-bottom: 5px;">الاسم الكامل:</label>
                <p style="color: #2d3748; font-size: 16px; margin: 0; padding: 8px 12px; background: #f7fafc; border-radius: 8px;">
                    {{ $customer->name }}
                </p>
            </div>
            
            <div style="margin-bottom: 15px;">
                <label style="font-weight: 600; color: #4a5568; display: block; margin-bottom: 5px;">البريد الإلكتروني:</label>
                <p style="color: #2d3748; font-size: 16px; margin: 0; padding: 8px 12px; background: #f7fafc; border-radius: 8px;">
                    {{ $customer->email }}
                </p>
            </div>
            
            <div style="margin-bottom: 15px;">
                <label style="font-weight: 600; color: #4a5568; display: block; margin-bottom: 5px;">كود العميل:</label>
                <p style="color: #2d3748; font-size: 16px; margin: 0; padding: 8px 12px; background: #f7fafc; border-radius: 8px; font-family: monospace;">
                    {{ $customer->customer_code ?? 'غير محدد' }}
                </p>
            </div>
            
            @if($customer->phone)
            <div style="margin-bottom: 15px;">
                <label style="font-weight: 600; color: #4a5568; display: block; margin-bottom: 5px;">رقم الهاتف:</label>
                <p style="color: #2d3748; font-size: 16px; margin: 0; padding: 8px 12px; background: #f7fafc; border-radius: 8px;">
                    {{ $customer->phone }}
                </p>
            </div>
            @endif
            
            @if($customer->company_name)
            <div style="margin-bottom: 15px;">
                <label style="font-weight: 600; color: #4a5568; display: block; margin-bottom: 5px;">اسم الشركة:</label>
                <p style="color: #2d3748; font-size: 16px; margin: 0; padding: 8px 12px; background: #f7fafc; border-radius: 8px;">
                    {{ $customer->company_name }}
                </p>
            </div>
            @endif
        </div>
    </div>
    
    <!-- Status and Dates -->
    <div class="content-card">
        <h3 style="font-size: 18px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
            <i class="fas fa-info-circle" style="color: #667eea;"></i>
            الحالة والتواريخ
        </h3>
        
        <div class="space-y-4">
            <div style="margin-bottom: 15px;">
                <label style="font-weight: 600; color: #4a5568; display: block; margin-bottom: 5px;">الحالة:</label>
                <div style="padding: 8px 12px; background: #f7fafc; border-radius: 8px;">
                    @if($customer->is_active)
                        <span style="background: #c6f6d5; color: #22543d; padding: 6px 16px; border-radius: 20px; font-size: 14px; font-weight: 600;">
                            <i class="fas fa-check-circle" style="margin-left: 4px;"></i>
                            نشط
                        </span>
                    @else
                        <span style="background: #fed7d7; color: #742a2a; padding: 6px 16px; border-radius: 20px; font-size: 14px; font-weight: 600;">
                            <i class="fas fa-times-circle" style="margin-left: 4px;"></i>
                            غير نشط
                        </span>
                    @endif
                </div>
            </div>
            
            <div style="margin-bottom: 15px;">
                <label style="font-weight: 600; color: #4a5568; display: block; margin-bottom: 5px;">تاريخ التسجيل:</label>
                <p style="color: #2d3748; font-size: 16px; margin: 0; padding: 8px 12px; background: #f7fafc; border-radius: 8px;">
                    {{ $customer->created_at->format('Y-m-d H:i') }}
                </p>
            </div>
            
            <div style="margin-bottom: 15px;">
                <label style="font-weight: 600; color: #4a5568; display: block; margin-bottom: 5px;">آخر تحديث:</label>
                <p style="color: #2d3748; font-size: 16px; margin: 0; padding: 8px 12px; background: #f7fafc; border-radius: 8px;">
                    {{ $customer->updated_at->format('Y-m-d H:i') }}
                </p>
            </div>
            
            <div style="margin-bottom: 15px;">
                <label style="font-weight: 600; color: #4a5568; display: block; margin-bottom: 5px;">منذ:</label>
                <p style="color: #2d3748; font-size: 16px; margin: 0; padding: 8px 12px; background: #f7fafc; border-radius: 8px;">
                    {{ $customer->created_at->diffForHumans() }}
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Customer Statistics -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px;">
    <!-- Orders Count -->
    <div class="content-card" style="text-align: center;">
        <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; border-radius: 15px; margin-bottom: 15px;">
            <i class="fas fa-shopping-cart" style="font-size: 30px; margin-bottom: 10px;"></i>
            <h4 style="margin: 0; font-size: 18px;">الطلبات</h4>
        </div>
        <p style="font-size: 24px; font-weight: 700; color: #2d3748; margin: 0;">
            {{ $customer->salesOrders->count() ?? 0 }}
        </p>
        <p style="color: #718096; font-size: 14px; margin: 5px 0 0 0;">
            إجمالي الطلبات
        </p>
    </div>
    
    <!-- Invoices Count -->
    <div class="content-card" style="text-align: center;">
        <div style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); color: white; padding: 20px; border-radius: 15px; margin-bottom: 15px;">
            <i class="fas fa-file-invoice" style="font-size: 30px; margin-bottom: 10px;"></i>
            <h4 style="margin: 0; font-size: 18px;">الفواتير</h4>
        </div>
        <p style="font-size: 24px; font-weight: 700; color: #2d3748; margin: 0;">
            {{ $customer->invoices->count() ?? 0 }}
        </p>
        <p style="color: #718096; font-size: 14px; margin: 5px 0 0 0;">
            إجمالي الفواتير
        </p>
    </div>
    
    <!-- Payments Count -->
    <div class="content-card" style="text-align: center;">
        <div style="background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%); color: white; padding: 20px; border-radius: 15px; margin-bottom: 15px;">
            <i class="fas fa-credit-card" style="font-size: 30px; margin-bottom: 10px;"></i>
            <h4 style="margin: 0; font-size: 18px;">المدفوعات</h4>
        </div>
        <p style="font-size: 24px; font-weight: 700; color: #2d3748; margin: 0;">
            {{ $customer->payments->count() ?? 0 }}
        </p>
        <p style="color: #718096; font-size: 14px; margin: 5px 0 0 0;">
            إجمالي المدفوعات
        </p>
    </div>
    
    <!-- Account Balance -->
    <div class="content-card" style="text-align: center;">
        <div style="background: linear-gradient(135deg, #9f7aea 0%, #805ad5 100%); color: white; padding: 20px; border-radius: 15px; margin-bottom: 15px;">
            <i class="fas fa-wallet" style="font-size: 30px; margin-bottom: 10px;"></i>
            <h4 style="margin: 0; font-size: 18px;">الرصيد</h4>
        </div>
        <p style="font-size: 24px; font-weight: 700; color: #2d3748; margin: 0;">
            {{ number_format($customer->balance ?? 0, 2) }}
        </p>
        <p style="color: #718096; font-size: 14px; margin: 5px 0 0 0;">
            دينار عراقي
        </p>
    </div>
</div>

<!-- Action Buttons -->
<div style="display: flex; gap: 15px; justify-content: center; margin-top: 30px; flex-wrap: wrap;">
    <form method="POST" action="{{ route('admin.customers.toggle-status', [$tenant, $customer]) }}" style="display: inline;">
        @csrf
        @method('PATCH')
        <button type="submit"
                class="toggle-status-btn {{ $customer->is_active ? 'btn-deactivate-customer' : 'btn-activate-customer' }}">
            <i class="fas fa-{{ $customer->is_active ? 'ban' : 'check' }}"></i>
            {{ $customer->is_active ? 'إلغاء التفعيل' : 'تفعيل العميل' }}
        </button>
    </form>

    <a href="{{ route('admin.customers.index', $tenant) }}"
       style="background: #f7fafc; color: #4a5568; padding: 12px 25px; border-radius: 25px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 8px; border: 2px solid #e2e8f0; transition: all 0.2s;">
        <i class="fas fa-arrow-right"></i>
        العودة للقائمة
    </a>
</div>
@endsection
