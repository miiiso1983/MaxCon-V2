@extends('layouts.modern')

@section('page-title', 'تفاصيل المورد: ' . $supplier->name)
@section('page-description', 'عرض تفاصيل المورد')

@section('content')
<!-- Page Header -->
<div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius: 20px; padding: 30px; margin-bottom: 30px; color: white; position: relative; overflow: hidden;">
    <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
    <div style="position: absolute; bottom: -30px; left: -30px; width: 150px; height: 150px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>
    
    <div style="position: relative; z-index: 2;">
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    <div style="background: rgba(255,255,255,0.2); border-radius: 15px; padding: 15px; margin-left: 20px;">
                        <i class="fas fa-truck" style="font-size: 32px;"></i>
                    </div>
                    <div>
                        <h1 style="font-size: 32px; font-weight: 800; margin: 0; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                            {{ $supplier->name }} 🚚
                        </h1>
                        <p style="font-size: 18px; margin: 5px 0 0 0; opacity: 0.9;">
                            {{ $supplier->code }} - {{ $supplier->type_label }}
                        </p>
                    </div>
                </div>
                
                <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                    @php
                        $statusColors = [
                            'active' => ['bg' => 'rgba(16, 185, 129, 0.2)', 'text' => 'white'],
                            'inactive' => ['bg' => 'rgba(107, 114, 128, 0.2)', 'text' => 'white'],
                            'pending' => ['bg' => 'rgba(245, 158, 11, 0.2)', 'text' => 'white'],
                            'suspended' => ['bg' => 'rgba(239, 68, 68, 0.2)', 'text' => 'white'],
                        ];
                        $status = $statusColors[$supplier->status] ?? ['bg' => 'rgba(107, 114, 128, 0.2)', 'text' => 'white'];
                    @endphp
                    <div style="background: {{ $status['bg'] }}; border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3);">
                        <span style="font-size: 14px; font-weight: 600; color: {{ $status['text'] }};">{{ $supplier->status_label }}</span>
                    </div>
                    
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-calendar" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px;">{{ $supplier->created_at->format('Y-m-d') }}</span>
                    </div>
                    
                    @if($supplier->rating)
                        <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                            <i class="fas fa-star" style="margin-left: 8px;"></i>
                            <span style="font-size: 14px;">{{ $supplier->rating }}/5</span>
                        </div>
                    @endif
                </div>
            </div>
            
            <div style="display: flex; gap: 15px;">
                <a href="{{ route('tenant.purchasing.suppliers.edit', $supplier) }}" style="background: rgba(245, 158, 11, 0.2); color: white; padding: 15px 25px; border-radius: 15px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3);">
                    <i class="fas fa-edit"></i>
                    تعديل
                </a>
                <a href="{{ route('tenant.purchasing.suppliers.index') }}" style="background: rgba(255,255,255,0.2); color: white; padding: 15px 25px; border-radius: 15px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3);">
                    <i class="fas fa-arrow-right"></i>
                    العودة للقائمة
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Supplier Details -->
<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 25px; margin-bottom: 30px;">
    <!-- Main Details -->
    <div class="content-card">
        <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-info-circle" style="color: #10b981; margin-left: 10px;"></i>
            المعلومات الأساسية
        </h3>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            <div>
                <label style="font-size: 14px; font-weight: 600; color: #6b7280; margin-bottom: 5px; display: block;">اسم المورد</label>
                <div style="font-size: 16px; font-weight: 600; color: #2d3748;">{{ $supplier->name }}</div>
            </div>
            
            <div>
                <label style="font-size: 14px; font-weight: 600; color: #6b7280; margin-bottom: 5px; display: block;">رمز المورد</label>
                <div style="font-size: 16px; color: #2d3748;">{{ $supplier->code }}</div>
            </div>
        </div>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            <div>
                <label style="font-size: 14px; font-weight: 600; color: #6b7280; margin-bottom: 5px; display: block;">نوع المورد</label>
                <div style="font-size: 16px; color: #2d3748;">{{ $supplier->type_label }}</div>
            </div>
            
            <div>
                <label style="font-size: 14px; font-weight: 600; color: #6b7280; margin-bottom: 5px; display: block;">تاريخ التسجيل</label>
                <div style="font-size: 16px; color: #2d3748;">{{ $supplier->created_at->format('Y-m-d H:i') }}</div>
            </div>
        </div>
        
        @if($supplier->description)
            <div style="margin-bottom: 20px;">
                <label style="font-size: 14px; font-weight: 600; color: #6b7280; margin-bottom: 5px; display: block;">الوصف</label>
                <div style="font-size: 16px; color: #2d3748; line-height: 1.6;">{{ $supplier->description }}</div>
            </div>
        @endif
        
        @if($supplier->products_services)
            <div>
                <label style="font-size: 14px; font-weight: 600; color: #6b7280; margin-bottom: 5px; display: block;">المنتجات/الخدمات</label>
                <div style="font-size: 16px; color: #2d3748; line-height: 1.6;">{{ $supplier->products_services }}</div>
            </div>
        @endif
    </div>
    
    <!-- Quick Stats -->
    <div class="content-card">
        <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-chart-bar" style="color: #10b981; margin-left: 10px;"></i>
            إحصائيات سريعة
        </h3>
        
        <div style="display: grid; gap: 15px;">
            <div style="background: #f8fafc; padding: 15px; border-radius: 10px; border-right: 4px solid #10b981;">
                <div style="font-size: 12px; color: #6b7280; margin-bottom: 5px;">إجمالي الطلبات</div>
                <div style="font-size: 24px; font-weight: 700; color: #2d3748;">0</div>
            </div>
            
            <div style="background: #f8fafc; padding: 15px; border-radius: 10px; border-right: 4px solid #3b82f6;">
                <div style="font-size: 12px; color: #6b7280; margin-bottom: 5px;">إجمالي المشتريات</div>
                <div style="font-size: 24px; font-weight: 700; color: #2d3748;">0 د.ع</div>
            </div>
            
            <div style="background: #f8fafc; padding: 15px; border-radius: 10px; border-right: 4px solid #f59e0b;">
                <div style="font-size: 12px; color: #6b7280; margin-bottom: 5px;">آخر طلب</div>
                <div style="font-size: 14px; font-weight: 600; color: #2d3748;">لا يوجد</div>
            </div>
            
            @if($supplier->rating)
                <div style="background: #f8fafc; padding: 15px; border-radius: 10px; border-right: 4px solid #8b5cf6;">
                    <div style="font-size: 12px; color: #6b7280; margin-bottom: 5px;">التقييم</div>
                    <div style="font-size: 24px; font-weight: 700; color: #2d3748;">{{ $supplier->rating }}/5</div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Contact Information -->
<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 25px; margin-bottom: 30px;">
    <!-- Contact Details -->
    <div class="content-card">
        <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-address-book" style="color: #10b981; margin-left: 10px;"></i>
            معلومات الاتصال
        </h3>
        
        @if($supplier->email)
            <div style="margin-bottom: 15px;">
                <label style="font-size: 14px; font-weight: 600; color: #6b7280; margin-bottom: 5px; display: block;">البريد الإلكتروني</label>
                <div style="font-size: 16px; color: #2d3748;">
                    <a href="mailto:{{ $supplier->email }}" style="color: #3b82f6; text-decoration: none;">{{ $supplier->email }}</a>
                </div>
            </div>
        @endif
        
        @if($supplier->phone)
            <div style="margin-bottom: 15px;">
                <label style="font-size: 14px; font-weight: 600; color: #6b7280; margin-bottom: 5px; display: block;">رقم الهاتف</label>
                <div style="font-size: 16px; color: #2d3748;">
                    <a href="tel:{{ $supplier->phone }}" style="color: #3b82f6; text-decoration: none;">{{ $supplier->phone }}</a>
                </div>
            </div>
        @endif
        
        @if($supplier->fax)
            <div style="margin-bottom: 15px;">
                <label style="font-size: 14px; font-weight: 600; color: #6b7280; margin-bottom: 5px; display: block;">الفاكس</label>
                <div style="font-size: 16px; color: #2d3748;">{{ $supplier->fax }}</div>
            </div>
        @endif
        
        @if($supplier->website)
            <div style="margin-bottom: 15px;">
                <label style="font-size: 14px; font-weight: 600; color: #6b7280; margin-bottom: 5px; display: block;">الموقع الإلكتروني</label>
                <div style="font-size: 16px; color: #2d3748;">
                    <a href="{{ $supplier->website }}" target="_blank" style="color: #3b82f6; text-decoration: none;">{{ $supplier->website }}</a>
                </div>
            </div>
        @endif
        
        @if($supplier->address)
            <div>
                <label style="font-size: 14px; font-weight: 600; color: #6b7280; margin-bottom: 5px; display: block;">العنوان</label>
                <div style="font-size: 16px; color: #2d3748; line-height: 1.6;">{{ $supplier->address }}</div>
                @if($supplier->city || $supplier->state || $supplier->country)
                    <div style="font-size: 14px; color: #6b7280; margin-top: 5px;">
                        {{ collect([$supplier->city, $supplier->state, $supplier->country])->filter()->implode(', ') }}
                    </div>
                @endif
            </div>
        @endif
    </div>
    
    <!-- Contact Person -->
    <div class="content-card">
        <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-user" style="color: #10b981; margin-left: 10px;"></i>
            شخص الاتصال
        </h3>
        
        @if($supplier->contact_person)
            <div style="margin-bottom: 15px;">
                <label style="font-size: 14px; font-weight: 600; color: #6b7280; margin-bottom: 5px; display: block;">الاسم</label>
                <div style="font-size: 16px; font-weight: 600; color: #2d3748;">{{ $supplier->contact_person }}</div>
                @if($supplier->contact_title)
                    <div style="font-size: 14px; color: #6b7280;">{{ $supplier->contact_title }}</div>
                @endif
            </div>
        @endif
        
        @if($supplier->contact_email)
            <div style="margin-bottom: 15px;">
                <label style="font-size: 14px; font-weight: 600; color: #6b7280; margin-bottom: 5px; display: block;">البريد الإلكتروني</label>
                <div style="font-size: 16px; color: #2d3748;">
                    <a href="mailto:{{ $supplier->contact_email }}" style="color: #3b82f6; text-decoration: none;">{{ $supplier->contact_email }}</a>
                </div>
            </div>
        @endif
        
        @if($supplier->contact_phone)
            <div>
                <label style="font-size: 14px; font-weight: 600; color: #6b7280; margin-bottom: 5px; display: block;">رقم الهاتف</label>
                <div style="font-size: 16px; color: #2d3748;">
                    <a href="tel:{{ $supplier->contact_phone }}" style="color: #3b82f6; text-decoration: none;">{{ $supplier->contact_phone }}</a>
                </div>
            </div>
        @endif
        
        @if(!$supplier->contact_person && !$supplier->contact_email && !$supplier->contact_phone)
            <div style="text-align: center; padding: 20px; color: #6b7280;">
                <i class="fas fa-user-slash" style="font-size: 32px; margin-bottom: 10px; opacity: 0.5;"></i>
                <p style="margin: 0;">لم يتم تحديد شخص اتصال</p>
            </div>
        @endif
    </div>
</div>

<!-- Business Information -->
<div class="content-card">
    <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
        <i class="fas fa-building" style="color: #10b981; margin-left: 10px;"></i>
        المعلومات التجارية
    </h3>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 20px;">
        @if($supplier->tax_number)
            <div>
                <label style="font-size: 14px; font-weight: 600; color: #6b7280; margin-bottom: 5px; display: block;">رقم السجل التجاري</label>
                <div style="font-size: 16px; color: #2d3748;">{{ $supplier->tax_number }}</div>
            </div>
        @endif
        
        @if($supplier->license_number)
            <div>
                <label style="font-size: 14px; font-weight: 600; color: #6b7280; margin-bottom: 5px; display: block;">رقم الترخيص</label>
                <div style="font-size: 16px; color: #2d3748;">{{ $supplier->license_number }}</div>
            </div>
        @endif
        
        @if($supplier->payment_terms)
            <div>
                <label style="font-size: 14px; font-weight: 600; color: #6b7280; margin-bottom: 5px; display: block;">شروط الدفع</label>
                <div style="font-size: 16px; color: #2d3748;">{{ $supplier->payment_terms_label }}</div>
            </div>
        @endif
        
        @if($supplier->currency)
            <div>
                <label style="font-size: 14px; font-weight: 600; color: #6b7280; margin-bottom: 5px; display: block;">العملة المفضلة</label>
                <div style="font-size: 16px; color: #2d3748;">{{ $supplier->currency }}</div>
            </div>
        @endif
    </div>
    
    @if($supplier->notes)
        <div>
            <label style="font-size: 14px; font-weight: 600; color: #6b7280; margin-bottom: 5px; display: block;">ملاحظات</label>
            <div style="font-size: 16px; color: #2d3748; line-height: 1.6; background: #f8fafc; padding: 15px; border-radius: 8px; border-right: 4px solid #10b981;">{{ $supplier->notes }}</div>
        </div>
    @endif
</div>
@endsection
