@extends('layouts.modern')

@section('page-title', 'إدارة المرتجعات والاستبدالات')
@section('page-description', 'إدارة شاملة لطلبات الإرجاع والاستبدال')

@section('content')
<!-- Page Header -->
<div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border-radius: 20px; padding: 30px; margin-bottom: 30px; color: white; position: relative; overflow: hidden;">
    <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
    <div style="position: absolute; bottom: -30px; left: -30px; width: 150px; height: 150px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>
    
    <div style="position: relative; z-index: 2;">
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    <div style="background: rgba(255,255,255,0.2); border-radius: 15px; padding: 15px; margin-left: 20px;">
                        <i class="fas fa-undo-alt" style="font-size: 32px;"></i>
                    </div>
                    <div>
                        <h1 style="font-size: 32px; font-weight: 800; margin: 0; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                            إدارة المرتجعات 🔄
                        </h1>
                        <p style="font-size: 18px; margin: 5px 0 0 0; opacity: 0.9;">
                            معالجة طلبات الإرجاع والاستبدال مع تحديث المخزون تلقائياً
                        </p>
                    </div>
                </div>
                
                <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-undo-alt" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px; font-weight: 600;">{{ $stats['total'] }} طلب</span>
                    </div>
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-clock" style="margin-left: 8px; color: #fbbf24;"></i>
                        <span style="font-size: 14px;">{{ $stats['pending'] }} معلق</span>
                    </div>
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-check-circle" style="margin-left: 8px; color: #4ade80;"></i>
                        <span style="font-size: 14px;">{{ $stats['approved'] }} موافق عليه</span>
                    </div>
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-money-bill-wave" style="margin-left: 8px; color: #34d399;"></i>
                        <span style="font-size: 14px;">{{ number_format($stats['refund_amount'], 0) }} د.ع مسترد</span>
                    </div>
                </div>
            </div>
            
            <div style="display: flex; gap: 15px;">
                <a href="{{ route('tenant.sales.returns.create') }}" style="background: rgba(255,255,255,0.2); color: white; padding: 15px 25px; border-radius: 15px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3); transition: all 0.3s ease;"
                   onmouseover="this.style.background='rgba(255,255,255,0.3)'"
                   onmouseout="this.style.background='rgba(255,255,255,0.2)'">
                    <i class="fas fa-plus"></i>
                    طلب إرجاع جديد
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="content-card" style="margin-bottom: 25px;">
    <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
        <i class="fas fa-filter" style="color: #f093fb; margin-left: 10px;"></i>
        فلترة وبحث
    </h3>
    
    <form method="GET" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; align-items: end;">
        <div>
            <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #4a5568;">البحث</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="رقم الطلب، العميل، أو رقم الفاتورة..." style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 8px;">
        </div>
        
        <div>
            <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #4a5568;">الحالة</label>
            <select name="status" style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 8px;">
                <option value="">جميع الحالات</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>معلق</option>
                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>موافق عليه</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>مكتمل</option>
                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>مرفوض</option>
            </select>
        </div>
        
        <div>
            <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #4a5568;">النوع</label>
            <select name="type" style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 8px;">
                <option value="">جميع الأنواع</option>
                <option value="return" {{ request('type') === 'return' ? 'selected' : '' }}>إرجاع</option>
                <option value="exchange" {{ request('type') === 'exchange' ? 'selected' : '' }}>استبدال</option>
            </select>
        </div>
        
        <div>
            <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #4a5568;">العميل</label>
            <select name="customer_id" style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 8px;">
                <option value="">جميع العملاء</option>
                @foreach($customers as $customer)
                    <option value="{{ $customer->id }}" {{ request('customer_id') == $customer->id ? 'selected' : '' }}>
                        {{ $customer->name }}
                    </option>
                @endforeach
            </select>
        </div>
        
        <div>
            <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #4a5568;">من تاريخ</label>
            <input type="date" name="date_from" value="{{ request('date_from') }}" style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 8px;">
        </div>
        
        <div>
            <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #4a5568;">إلى تاريخ</label>
            <input type="date" name="date_to" value="{{ request('date_to') }}" style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 8px;">
        </div>
        
        <div style="display: flex; gap: 10px;">
            <button type="submit" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; padding: 10px 20px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-search"></i>
                بحث
            </button>
            <a href="{{ route('tenant.sales.returns.index') }}" style="background: #6b7280; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-times"></i>
                إلغاء
            </a>
        </div>
    </form>
</div>

<!-- Returns Table -->
<div class="content-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin: 0; display: flex; align-items: center;">
            <i class="fas fa-list" style="color: #f093fb; margin-left: 10px;"></i>
            قائمة المرتجعات ({{ $returns->total() }})
        </h3>
    </div>
    
    @if($returns->count() > 0)
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8fafc;">
                        <th style="padding: 12px; text-align: right; border-bottom: 2px solid #e2e8f0; font-weight: 700; color: #2d3748;">رقم الطلب</th>
                        <th style="padding: 12px; text-align: right; border-bottom: 2px solid #e2e8f0; font-weight: 700; color: #2d3748;">الفاتورة</th>
                        <th style="padding: 12px; text-align: right; border-bottom: 2px solid #e2e8f0; font-weight: 700; color: #2d3748;">العميل</th>
                        <th style="padding: 12px; text-align: right; border-bottom: 2px solid #e2e8f0; font-weight: 700; color: #2d3748;">النوع</th>
                        <th style="padding: 12px; text-align: right; border-bottom: 2px solid #e2e8f0; font-weight: 700; color: #2d3748;">التاريخ</th>
                        <th style="padding: 12px; text-align: right; border-bottom: 2px solid #e2e8f0; font-weight: 700; color: #2d3748;">المبلغ</th>
                        <th style="padding: 12px; text-align: right; border-bottom: 2px solid #e2e8f0; font-weight: 700; color: #2d3748;">الحالة</th>
                        <th style="padding: 12px; text-align: center; border-bottom: 2px solid #e2e8f0; font-weight: 700; color: #2d3748;">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($returns as $return)
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">
                                <div style="font-weight: 600; color: #2d3748;">{{ $return->return_number }}</div>
                                <div style="font-size: 12px; color: #6b7280;">{{ $return->created_at->format('Y/m/d H:i') }}</div>
                            </td>
                            <td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">
                                <a href="{{ route('tenant.sales.invoices.show', $return->invoice) }}" style="color: #3b82f6; text-decoration: none; font-weight: 600;">
                                    {{ $return->invoice->invoice_number }}
                                </a>
                            </td>
                            <td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">
                                <div style="font-weight: 600; color: #2d3748;">{{ $return->customer->name }}</div>
                                <div style="font-size: 12px; color: #6b7280;">{{ $return->customer->phone ?? 'لا يوجد هاتف' }}</div>
                            </td>
                            <td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">
                                @if($return->type === 'return')
                                    <span style="background: #dbeafe; color: #1e40af; padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                                        <i class="fas fa-undo"></i> إرجاع
                                    </span>
                                @else
                                    <span style="background: #fef3c7; color: #92400e; padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                                        <i class="fas fa-exchange-alt"></i> استبدال
                                    </span>
                                @endif
                            </td>
                            <td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">
                                <div style="font-weight: 600; color: #2d3748;">{{ $return->return_date->format('Y/m/d') }}</div>
                            </td>
                            <td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">
                                <div style="font-weight: 600; color: #059669;">{{ number_format($return->total_amount, 0) }} د.ع</div>
                                @if($return->refund_amount != $return->total_amount)
                                    <div style="font-size: 12px; color: #6b7280;">مسترد: {{ number_format($return->refund_amount, 0) }} د.ع</div>
                                @endif
                            </td>
                            <td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">
                                @switch($return->status)
                                    @case('pending')
                                        <span style="background: #fef3c7; color: #92400e; padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                                            <i class="fas fa-clock"></i> معلق
                                        </span>
                                        @break
                                    @case('approved')
                                        <span style="background: #d1fae5; color: #065f46; padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                                            <i class="fas fa-check"></i> موافق عليه
                                        </span>
                                        @break
                                    @case('completed')
                                        <span style="background: #dbeafe; color: #1e40af; padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                                            <i class="fas fa-check-double"></i> مكتمل
                                        </span>
                                        @break
                                    @case('rejected')
                                        <span style="background: #fee2e2; color: #991b1b; padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                                            <i class="fas fa-times"></i> مرفوض
                                        </span>
                                        @break
                                @endswitch
                            </td>
                            <td style="padding: 12px; border-bottom: 1px solid #e2e8f0; text-align: center;">
                                <div style="display: flex; gap: 5px; justify-content: center;">
                                    <a href="{{ route('tenant.sales.returns.show', $return) }}" style="background: #3b82f6; color: white; padding: 6px 10px; border-radius: 6px; text-decoration: none; font-size: 12px;">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($return->status === 'pending')
                                        <a href="{{ route('tenant.sales.returns.edit', $return) }}" style="background: #f59e0b; color: white; padding: 6px 10px; border-radius: 6px; text-decoration: none; font-size: 12px;">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div style="margin-top: 20px;">
            {{ $returns->links() }}
        </div>
    @else
        <div style="text-align: center; padding: 40px; color: #6b7280;">
            <i class="fas fa-undo-alt" style="font-size: 48px; margin-bottom: 15px; opacity: 0.5;"></i>
            <h3 style="margin: 0 0 10px 0;">لا توجد طلبات إرجاع</h3>
            <p style="margin: 0;">لم يتم العثور على أي طلبات إرجاع تطابق معايير البحث</p>
        </div>
    @endif
</div>
@endsection
