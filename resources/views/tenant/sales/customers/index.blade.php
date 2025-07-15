@extends('layouts.modern')

@section('page-title', 'إدارة العملاء')
@section('page-description', 'إدارة شاملة لقاعدة بيانات العملاء')

@section('content')
<!-- Page Header -->
<div style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); border-radius: 20px; padding: 30px; margin-bottom: 30px; color: white; position: relative; overflow: hidden;">
    <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
    <div style="position: absolute; bottom: -30px; left: -30px; width: 150px; height: 150px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>
    
    <div style="position: relative; z-index: 2;">
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    <div style="background: rgba(255,255,255,0.2); border-radius: 15px; padding: 15px; margin-left: 20px;">
                        <i class="fas fa-users" style="font-size: 32px;"></i>
                    </div>
                    <div>
                        <h1 style="font-size: 32px; font-weight: 800; margin: 0; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                            إدارة العملاء 👥
                        </h1>
                        <p style="font-size: 18px; margin: 5px 0 0 0; opacity: 0.9;">
                            إدارة شاملة لقاعدة بيانات العملاء والحسابات
                        </p>
                    </div>
                </div>
                
                <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-users" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px; font-weight: 600;">{{ $stats['total'] }} عميل</span>
                    </div>
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-check-circle" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px;">{{ $stats['active'] }} نشط</span>
                    </div>
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-building" style="margin-left: 8px; color: #fbbf24;"></i>
                        <span style="font-size: 14px;">{{ $stats['companies'] }} شركة</span>
                    </div>
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-user" style="margin-left: 8px; color: #60a5fa;"></i>
                        <span style="font-size: 14px;">{{ $stats['individuals'] }} فرد</span>
                    </div>
                </div>
            </div>
            
            <div style="display: flex; gap: 15px;">
                <a href="{{ route('tenant.sales.customers.import') }}" style="background: rgba(255,255,255,0.2); color: white; padding: 15px 25px; border-radius: 15px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3); transition: all 0.3s ease;"
                   onmouseover="this.style.background='rgba(255,255,255,0.3)'; this.style.transform='translateY(-2px)';"
                   onmouseout="this.style.background='rgba(255,255,255,0.2)'; this.style.transform='translateY(0)';">
                    <i class="fas fa-file-excel"></i>
                    استيراد من Excel
                </a>
                <a href="{{ route('tenant.sales.customers.create') }}" style="background: rgba(255,255,255,0.2); color: white; padding: 15px 25px; border-radius: 15px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3); transition: all 0.3s ease;"
                   onmouseover="this.style.background='rgba(255,255,255,0.3)'; this.style.transform='translateY(-2px)';"
                   onmouseout="this.style.background='rgba(255,255,255,0.2)'; this.style.transform='translateY(0)';">
                    <i class="fas fa-plus"></i>
                    إضافة عميل جديد
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="content-card" style="margin-bottom: 25px;">
    <form method="GET" action="{{ route('tenant.sales.customers.index') }}">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 20px;">
            <!-- Search -->
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">البحث</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                       style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;" 
                       placeholder="اسم العميل، الكود، الإيميل، أو الهاتف...">
            </div>
            
            <!-- Customer Type Filter -->
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">نوع العميل</label>
                <select name="customer_type" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;">
                    <option value="">جميع الأنواع</option>
                    <option value="individual" {{ request('customer_type') === 'individual' ? 'selected' : '' }}>فرد</option>
                    <option value="company" {{ request('customer_type') === 'company' ? 'selected' : '' }}>شركة</option>
                </select>
            </div>
            
            <!-- Status Filter -->
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">الحالة</label>
                <select name="is_active" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;">
                    <option value="">جميع الحالات</option>
                    <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>نشط</option>
                    <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>غير نشط</option>
                </select>
            </div>
        </div>
        
        <div style="display: flex; gap: 15px;">
            <button type="submit" class="btn-green" style="padding: 12px 24px;">
                <i class="fas fa-search"></i>
                بحث
            </button>
            <a href="{{ route('tenant.sales.customers.index') }}" class="btn-gray" style="padding: 12px 24px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
                <i class="fas fa-times"></i>
                إعادة تعيين
            </a>
        </div>
    </form>
</div>

<!-- Customers Table -->
<div class="content-card">
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f7fafc;">
                    <th style="padding: 12px; text-align: right; font-weight: 600; color: #4a5568; border-bottom: 1px solid #e2e8f0;">العميل</th>
                    <th style="padding: 12px; text-align: right; font-weight: 600; color: #4a5568; border-bottom: 1px solid #e2e8f0;">معلومات الاتصال</th>
                    <th style="padding: 12px; text-align: right; font-weight: 600; color: #4a5568; border-bottom: 1px solid #e2e8f0;">النوع</th>
                    <th style="padding: 12px; text-align: right; font-weight: 600; color: #4a5568; border-bottom: 1px solid #e2e8f0;">شروط الدفع</th>
                    <th style="padding: 12px; text-align: right; font-weight: 600; color: #4a5568; border-bottom: 1px solid #e2e8f0;">الحد الائتماني</th>
                    <th style="padding: 12px; text-align: right; font-weight: 600; color: #4a5568; border-bottom: 1px solid #e2e8f0;">الرصيد الحالي</th>
                    <th style="padding: 12px; text-align: right; font-weight: 600; color: #4a5568; border-bottom: 1px solid #e2e8f0;">الحالة</th>
                    <th style="padding: 12px; text-align: right; font-weight: 600; color: #4a5568; border-bottom: 1px solid #e2e8f0;">الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $customer)
                <tr style="transition: all 0.3s ease;" onmouseover="this.style.background='#f7fafc';" onmouseout="this.style.background='white';">
                    <td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">
                        <div style="display: flex; align-items: center;">
                            <div style="width: 50px; height: 50px; border-radius: 50%; background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); color: white; display: flex; align-items: center; justify-content: center; margin-left: 15px; font-weight: 700; font-size: 18px;">
                                {{ substr($customer->name, 0, 1) }}
                            </div>
                            <div>
                                <div style="font-weight: 600; color: #2d3748; margin-bottom: 2px;">{{ $customer->name }}</div>
                                <div style="font-size: 12px; color: #718096;">{{ $customer->customer_code }}</div>
                                @if($customer->tax_number)
                                    <div style="font-size: 11px; color: #9f7aea;">ض.ق: {{ $customer->tax_number }}</div>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">
                        @if($customer->email)
                            <div style="font-size: 13px; color: #4a5568; margin-bottom: 2px;">
                                <i class="fas fa-envelope" style="margin-left: 5px; color: #718096;"></i>
                                {{ $customer->email }}
                            </div>
                        @endif
                        @if($customer->mobile)
                            <div style="font-size: 13px; color: #4a5568; margin-bottom: 2px;">
                                <i class="fas fa-mobile-alt" style="margin-left: 5px; color: #718096;"></i>
                                {{ $customer->mobile }}
                            </div>
                        @endif
                        @if($customer->phone)
                            <div style="font-size: 13px; color: #4a5568;">
                                <i class="fas fa-phone" style="margin-left: 5px; color: #718096;"></i>
                                {{ $customer->phone }}
                            </div>
                        @endif
                    </td>
                    <td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">
                        <span style="padding: 4px 8px; border-radius: 12px; font-size: 11px; font-weight: 600; 
                                     background: {{ $customer->customer_type === 'company' ? '#dbeafe' : '#f0f9ff' }};
                                     color: {{ $customer->customer_type === 'company' ? '#1e40af' : '#0369a1' }};">
                            {{ $customer->customer_type === 'company' ? 'شركة' : 'فرد' }}
                        </span>
                    </td>
                    <td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">
                        <span style="font-size: 13px; color: #4a5568;">
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
                    </td>
                    <td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">
                        <div style="font-weight: 600; color: #2d3748;">{{ number_format($customer->credit_limit, 2) }}</div>
                        <div style="font-size: 11px; color: #718096;">{{ $customer->currency }}</div>
                    </td>
                    <td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">
                        <div style="font-weight: 600; color: {{ $customer->current_balance > 0 ? '#f56565' : '#48bb78' }};">
                            {{ number_format($customer->current_balance, 2) }}
                        </div>
                        <div style="font-size: 11px; color: #718096;">{{ $customer->currency }}</div>
                    </td>
                    <td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">
                        @if($customer->is_active)
                            <span class="status-badge status-active">نشط</span>
                        @else
                            <span class="status-badge status-inactive">غير نشط</span>
                        @endif
                    </td>
                    <td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">
                        <div style="display: flex; gap: 8px; align-items: center;">
                            <a href="{{ route('tenant.sales.customers.show', $customer) }}" 
                               style="background: none; border: none; color: #4299e1; cursor: pointer; padding: 4px;" 
                               title="عرض التفاصيل">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('tenant.sales.customers.edit', $customer) }}" 
                               style="background: none; border: none; color: #48bb78; cursor: pointer; padding: 4px;" 
                               title="تعديل">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button onclick="confirmDelete({{ $customer->id }}, '{{ $customer->name }}')" 
                                    style="background: none; border: none; color: #f56565; cursor: pointer; padding: 4px;" 
                                    title="حذف">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="padding: 40px; text-align: center; color: #718096;">
                        <div style="display: flex; flex-direction: column; align-items: center;">
                            <i class="fas fa-users" style="font-size: 48px; color: #e2e8f0; margin-bottom: 15px;"></i>
                            <p style="font-size: 18px; font-weight: 600; margin: 0 0 5px 0;">لا توجد عملاء</p>
                            <p style="font-size: 14px; margin: 0;">ابدأ بإضافة عميل جديد</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($customers->hasPages())
    <div style="margin-top: 20px;">
        {{ $customers->appends(request()->query())->links() }}
    </div>
    @endif
</div>

@push('scripts')
<script>
function confirmDelete(customerId, customerName) {
    if (confirm(`هل أنت متأكد من حذف العميل "${customerName}"؟`)) {
        // Create and submit delete form
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/tenant/sales/customers/${customerId}`;
        
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        
        const tokenInput = document.createElement('input');
        tokenInput.type = 'hidden';
        tokenInput.name = '_token';
        tokenInput.value = '{{ csrf_token() }}';
        
        form.appendChild(methodInput);
        form.appendChild(tokenInput);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endpush
@endsection
