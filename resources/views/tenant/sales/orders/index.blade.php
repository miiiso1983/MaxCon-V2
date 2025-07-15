@extends('layouts.modern')

@section('page-title', 'إدارة طلبات المبيعات')
@section('page-description', 'إدارة شاملة لطلبات المبيعات ومتابعة حالتها')

@section('content')
<!-- Page Header -->
<div style="background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%); border-radius: 20px; padding: 30px; margin-bottom: 30px; color: white; position: relative; overflow: hidden;">
    <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
    <div style="position: absolute; bottom: -30px; left: -30px; width: 150px; height: 150px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>
    
    <div style="position: relative; z-index: 2;">
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    <div style="background: rgba(255,255,255,0.2); border-radius: 15px; padding: 15px; margin-left: 20px;">
                        <i class="fas fa-shopping-cart" style="font-size: 32px;"></i>
                    </div>
                    <div>
                        <h1 style="font-size: 32px; font-weight: 800; margin: 0; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                            إدارة طلبات المبيعات 🛒
                        </h1>
                        <p style="font-size: 18px; margin: 5px 0 0 0; opacity: 0.9;">
                            إدارة شاملة لطلبات المبيعات ومتابعة حالتها
                        </p>
                    </div>
                </div>
                
                <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-list" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px; font-weight: 600;">{{ $salesOrders->total() ?? 0 }} طلب</span>
                    </div>
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-clock" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px;">{{ $statusCounts['pending'] ?? 0 }} قيد الانتظار</span>
                    </div>
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-check-circle" style="margin-left: 8px; color: #4ade80;"></i>
                        <span style="font-size: 14px;">{{ $statusCounts['delivered'] ?? 0 }} مكتمل</span>
                    </div>
                </div>
            </div>
            
            <div>
                <a href="{{ route('tenant.sales.orders.create') }}" style="background: rgba(255,255,255,0.2); color: white; padding: 15px 25px; border-radius: 15px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3); transition: all 0.3s ease;"
                   onmouseover="this.style.background='rgba(255,255,255,0.3)'; this.style.transform='translateY(-2px)';"
                   onmouseout="this.style.background='rgba(255,255,255,0.2)'; this.style.transform='translateY(0)';">
                    <i class="fas fa-plus"></i>
                    إنشاء طلب جديد
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="content-card" style="margin-bottom: 25px;">
    <form method="GET" action="{{ route('tenant.sales.orders.index') }}">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 20px;">
            <!-- Search -->
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">البحث</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                       style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;" 
                       placeholder="رقم الطلب أو اسم العميل...">
            </div>
            
            <!-- Status Filter -->
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">الحالة</label>
                <select name="status" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;">
                    <option value="">جميع الحالات</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>مسودة</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>قيد الانتظار</option>
                    <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>مؤكد</option>
                    <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>قيد المعالجة</option>
                    <option value="shipped" {{ request('status') === 'shipped' ? 'selected' : '' }}>تم الشحن</option>
                    <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>تم التسليم</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>ملغي</option>
                </select>
            </div>
            
            <!-- Customer Filter -->
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">العميل</label>
                <select name="customer_id" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;">
                    <option value="">جميع العملاء</option>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->id }}" {{ request('customer_id') == $customer->id ? 'selected' : '' }}>
                            {{ $customer->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <!-- Date From -->
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">من تاريخ</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" 
                       style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;">
            </div>
            
            <!-- Date To -->
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">إلى تاريخ</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" 
                       style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;">
            </div>
        </div>
        
        <div style="display: flex; gap: 15px;">
            <button type="submit" class="btn-blue" style="padding: 12px 24px;">
                <i class="fas fa-search"></i>
                بحث
            </button>
            <a href="{{ route('tenant.sales.orders.index') }}" class="btn-gray" style="padding: 12px 24px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
                <i class="fas fa-times"></i>
                إعادة تعيين
            </a>
        </div>
    </form>
</div>

<!-- Orders Table -->
<div class="content-card">
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f7fafc;">
                    <th style="padding: 12px; text-align: right; font-weight: 600; color: #4a5568; border-bottom: 1px solid #e2e8f0;">رقم الطلب</th>
                    <th style="padding: 12px; text-align: right; font-weight: 600; color: #4a5568; border-bottom: 1px solid #e2e8f0;">العميل</th>
                    <th style="padding: 12px; text-align: right; font-weight: 600; color: #4a5568; border-bottom: 1px solid #e2e8f0;">تاريخ الطلب</th>
                    <th style="padding: 12px; text-align: right; font-weight: 600; color: #4a5568; border-bottom: 1px solid #e2e8f0;">المبلغ الإجمالي</th>
                    <th style="padding: 12px; text-align: right; font-weight: 600; color: #4a5568; border-bottom: 1px solid #e2e8f0;">الحالة</th>
                    <th style="padding: 12px; text-align: right; font-weight: 600; color: #4a5568; border-bottom: 1px solid #e2e8f0;">الأولوية</th>
                    <th style="padding: 12px; text-align: right; font-weight: 600; color: #4a5568; border-bottom: 1px solid #e2e8f0;">الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($salesOrders as $order)
                <tr style="transition: all 0.3s ease;" onmouseover="this.style.background='#f7fafc';" onmouseout="this.style.background='white';">
                    <td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">
                        <div style="font-weight: 600; color: #2d3748;">{{ $order->order_number }}</div>
                        <div style="font-size: 12px; color: #718096;">{{ $order->created_at->format('Y/m/d H:i') }}</div>
                    </td>
                    <td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">
                        <div style="font-weight: 600; color: #2d3748;">{{ $order->customer->name }}</div>
                        <div style="font-size: 12px; color: #718096;">{{ $order->customer->customer_code }}</div>
                    </td>
                    <td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">
                        <div style="color: #4a5568;">{{ $order->order_date->format('Y/m/d') }}</div>
                        @if($order->required_date)
                            <div style="font-size: 12px; color: #718096;">مطلوب: {{ $order->required_date->format('Y/m/d') }}</div>
                        @endif
                    </td>
                    <td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">
                        <div style="font-weight: 600; color: #2d3748;">{{ number_format($order->total_amount, 2) }} {{ $order->currency }}</div>
                    </td>
                    <td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">
                        <span class="status-badge status-{{ $order->status_color }}">
                            {{ match($order->status) {
                                'draft' => 'مسودة',
                                'pending' => 'قيد الانتظار',
                                'confirmed' => 'مؤكد',
                                'processing' => 'قيد المعالجة',
                                'shipped' => 'تم الشحن',
                                'delivered' => 'تم التسليم',
                                'cancelled' => 'ملغي',
                                'returned' => 'مرتجع',
                                default => $order->status
                            } }}
                        </span>
                    </td>
                    <td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">
                        <span style="padding: 4px 8px; border-radius: 12px; font-size: 11px; font-weight: 600; 
                                     background: {{ match($order->priority) {
                                         'low' => '#f0f9ff',
                                         'normal' => '#f7fafc',
                                         'high' => '#fef2f2',
                                         'urgent' => '#7c2d12',
                                         default => '#f7fafc'
                                     } }};
                                     color: {{ match($order->priority) {
                                         'low' => '#0369a1',
                                         'normal' => '#4a5568',
                                         'high' => '#dc2626',
                                         'urgent' => '#ffffff',
                                         default => '#4a5568'
                                     } }};">
                            {{ match($order->priority) {
                                'low' => 'منخفضة',
                                'normal' => 'عادية',
                                'high' => 'عالية',
                                'urgent' => 'عاجلة',
                                default => $order->priority
                            } }}
                        </span>
                    </td>
                    <td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">
                        <div style="display: flex; gap: 8px; align-items: center;">
                            <a href="{{ route('tenant.sales.orders.show', $order) }}" 
                               style="background: none; border: none; color: #4299e1; cursor: pointer; padding: 4px;" 
                               title="عرض التفاصيل">
                                <i class="fas fa-eye"></i>
                            </a>
                            @if($order->canBeEdited())
                                <a href="{{ route('tenant.sales.orders.edit', $order) }}" 
                                   style="background: none; border: none; color: #48bb78; cursor: pointer; padding: 4px;" 
                                   title="تعديل">
                                    <i class="fas fa-edit"></i>
                                </a>
                            @endif
                            <button onclick="showStatusModal({{ $order->id }}, '{{ $order->status }}')" 
                                    style="background: none; border: none; color: #9f7aea; cursor: pointer; padding: 4px;" 
                                    title="تغيير الحالة">
                                <i class="fas fa-exchange-alt"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="padding: 40px; text-align: center; color: #718096;">
                        <div style="display: flex; flex-direction: column; align-items: center;">
                            <i class="fas fa-shopping-cart" style="font-size: 48px; color: #e2e8f0; margin-bottom: 15px;"></i>
                            <p style="font-size: 18px; font-weight: 600; margin: 0 0 5px 0;">لا توجد طلبات مبيعات</p>
                            <p style="font-size: 14px; margin: 0;">ابدأ بإنشاء طلب مبيعات جديد</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($salesOrders->hasPages())
    <div style="margin-top: 20px;">
        {{ $salesOrders->appends(request()->query())->links() }}
    </div>
    @endif
</div>

@push('scripts')
<script>
function showStatusModal(orderId, currentStatus) {
    // Implementation for status change modal
    console.log('Change status for order:', orderId, 'Current:', currentStatus);
}
</script>
@endpush
@endsection
