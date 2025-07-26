@extends('layouts.modern')

@section('page-title', 'أوامر الشراء')
@section('page-description', 'إدارة أوامر الشراء الرسمية والمتابعة')

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
                        <i class="fas fa-clipboard-list" style="font-size: 32px;"></i>
                    </div>
                    <div>
                        <h1 style="font-size: 32px; font-weight: 800; margin: 0; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                            أوامر الشراء 📋
                        </h1>
                        <p style="font-size: 18px; margin: 5px 0 0 0; opacity: 0.9;">
                            إدارة أوامر الشراء الرسمية والمتابعة
                        </p>
                    </div>
                </div>
                
                <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-truck" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px; font-weight: 600;">تتبع التسليم</span>
                    </div>
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-dollar-sign" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px;">إدارة المدفوعات</span>
                    </div>
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-file-invoice" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px;">ربط الفواتير</span>
                    </div>
                </div>
            </div>
            
            <div style="display: flex; gap: 15px;">
                <a href="{{ route('tenant.purchasing.purchase-orders.create') }}" style="background: rgba(255,255,255,0.2); color: white; padding: 15px 25px; border-radius: 15px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3);">
                    <i class="fas fa-plus"></i>
                    أمر شراء جديد
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px;">
    <div style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); border-radius: 15px; padding: 20px; color: white; position: relative; overflow: hidden;">
        <div style="position: absolute; top: -10px; right: -10px; width: 60px; height: 60px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
        <div style="position: relative; z-index: 2;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                <i class="fas fa-list" style="font-size: 24px; opacity: 0.8;"></i>
                <span style="font-size: 12px; opacity: 0.8;">إجمالي الأوامر</span>
            </div>
            <div style="font-size: 28px; font-weight: 700;">{{ number_format($stats['total']) }}</div>
            <div style="font-size: 12px; opacity: 0.8;">أمر شراء</div>
        </div>
    </div>
    
    <div style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); border-radius: 15px; padding: 20px; color: white; position: relative; overflow: hidden;">
        <div style="position: absolute; top: -10px; right: -10px; width: 60px; height: 60px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
        <div style="position: relative; z-index: 2;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                <i class="fas fa-clock" style="font-size: 24px; opacity: 0.8;"></i>
                <span style="font-size: 12px; opacity: 0.8;">في الانتظار</span>
            </div>
            <div style="font-size: 28px; font-weight: 700;">{{ number_format($stats['pending']) }}</div>
            <div style="font-size: 12px; opacity: 0.8;">أمر معلق</div>
        </div>
    </div>
    
    <div style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); border-radius: 15px; padding: 20px; color: white; position: relative; overflow: hidden;">
        <div style="position: absolute; top: -10px; right: -10px; width: 60px; height: 60px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
        <div style="position: relative; z-index: 2;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                <i class="fas fa-check-circle" style="font-size: 24px; opacity: 0.8;"></i>
                <span style="font-size: 12px; opacity: 0.8;">مؤكد</span>
            </div>
            <div style="font-size: 28px; font-weight: 700;">{{ number_format($stats['confirmed']) }}</div>
            <div style="font-size: 12px; opacity: 0.8;">أمر مؤكد</div>
        </div>
    </div>
    
    <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius: 15px; padding: 20px; color: white; position: relative; overflow: hidden;">
        <div style="position: absolute; top: -10px; right: -10px; width: 60px; height: 60px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
        <div style="position: relative; z-index: 2;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                <i class="fas fa-check-double" style="font-size: 24px; opacity: 0.8;"></i>
                <span style="font-size: 12px; opacity: 0.8;">مكتمل</span>
            </div>
            <div style="font-size: 28px; font-weight: 700;">{{ number_format($stats['completed']) }}</div>
            <div style="font-size: 12px; opacity: 0.8;">أمر مكتمل</div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="content-card" style="margin-bottom: 25px;">
    <form method="GET" action="{{ route('tenant.purchasing.purchase-orders.index') }}" style="display: grid; grid-template-columns: 2fr 1fr 1fr 1fr auto; gap: 15px; align-items: end;">
        <div>
            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">البحث</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="البحث برقم الأمر، اسم المورد..." style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;">
        </div>

        <div>
            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">الحالة</label>
            <select name="status" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;">
                <option value="">جميع الحالات</option>
                <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>مسودة</option>
                <option value="sent" {{ request('status') === 'sent' ? 'selected' : '' }}>مرسل</option>
                <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>مؤكد</option>
                <option value="partially_received" {{ request('status') === 'partially_received' ? 'selected' : '' }}>مستلم جزئياً</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>مكتمل</option>
                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>ملغي</option>
            </select>
        </div>

        <div>
            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">المورد</label>
            <select name="supplier_id" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;">
                <option value="">جميع الموردين</option>
                @foreach($suppliers as $supplier)
                    <option value="{{ $supplier->id }}" {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>
                        {{ $supplier->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">حالة الدفع</label>
            <select name="payment_status" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;">
                <option value="">جميع حالات الدفع</option>
                <option value="pending" {{ request('payment_status') === 'pending' ? 'selected' : '' }}>في الانتظار</option>
                <option value="partial" {{ request('payment_status') === 'partial' ? 'selected' : '' }}>دفع جزئي</option>
                <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>مدفوع</option>
                <option value="overdue" {{ request('payment_status') === 'overdue' ? 'selected' : '' }}>متأخر</option>
            </select>
        </div>

        <div style="display: flex; gap: 10px;">
            <button type="submit" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 12px 20px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px; transition: all 0.3s ease; box-shadow: 0 2px 4px rgba(16, 185, 129, 0.3);" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 8px rgba(16, 185, 129, 0.4)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(16, 185, 129, 0.3)'">
                <i class="fas fa-search"></i>
                <span>بحث</span>
            </button>
            <a href="{{ route('tenant.purchasing.purchase-orders.index') }}" style="background: #6b7280; color: white; padding: 12px 20px; border-radius: 8px; text-decoration: none; display: flex; align-items: center; gap: 8px; font-weight: 600; transition: all 0.3s ease; box-shadow: 0 2px 4px rgba(107, 114, 128, 0.3);" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 8px rgba(107, 114, 128, 0.4)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(107, 114, 128, 0.3)'">
                <i class="fas fa-times"></i>
                <span>إلغاء</span>
            </a>
        </div>
    </form>
</div>

<!-- Purchase Orders Table -->
<div class="content-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin: 0;">قائمة أوامر الشراء</h3>
        <div style="display: flex; gap: 10px;">
            <button onclick="exportOrders()" style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); color: white; padding: 12px 20px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px; transition: all 0.3s ease; box-shadow: 0 2px 4px rgba(59, 130, 246, 0.3);" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 8px rgba(59, 130, 246, 0.4)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(59, 130, 246, 0.3)'">
                <i class="fas fa-file-excel"></i>
                <span>تصدير Excel</span>
            </button>
        </div>
    </div>

    @if($purchaseOrders->count() > 0)
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                        <th style="padding: 15px; text-align: right; font-weight: 600; color: #4a5568;">رقم الأمر</th>
                        <th style="padding: 15px; text-align: right; font-weight: 600; color: #4a5568;">المورد</th>
                        <th style="padding: 15px; text-align: center; font-weight: 600; color: #4a5568;">الحالة</th>
                        <th style="padding: 15px; text-align: center; font-weight: 600; color: #4a5568;">حالة الدفع</th>
                        <th style="padding: 15px; text-align: center; font-weight: 600; color: #4a5568;">تاريخ الأمر</th>
                        <th style="padding: 15px; text-align: center; font-weight: 600; color: #4a5568;">التسليم المتوقع</th>
                        <th style="padding: 15px; text-align: center; font-weight: 600; color: #4a5568;">القيمة الإجمالية</th>
                        <th style="padding: 15px; text-align: center; font-weight: 600; color: #4a5568;">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($purchaseOrders as $order)
                        <tr style="border-bottom: 1px solid #e2e8f0; transition: background-color 0.3s ease;"
                            onmouseover="this.style.backgroundColor='#f8fafc'"
                            onmouseout="this.style.backgroundColor='transparent'">
                            <td style="padding: 15px;">
                                <div style="font-weight: 600; color: #2d3748; display: flex; align-items: center; gap: 8px;">
                                    {{ $order->po_number }}
                                    @if($order->is_urgent)
                                        <span style="background: #fee2e2; color: #991b1b; padding: 2px 6px; border-radius: 8px; font-size: 10px; font-weight: 600;">
                                            عاجل
                                        </span>
                                    @endif
                                </div>
                                <div style="font-size: 12px; color: #6b7280;">{{ $order->created_at->format('Y-m-d') }}</div>
                            </td>
                            <td style="padding: 15px;">
                                <div style="font-weight: 600; color: #2d3748;">{{ $order->supplier->name }}</div>
                                <div style="font-size: 12px; color: #6b7280;">{{ $order->supplier->supplier_code }}</div>
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                @php
                                    $statusColors = [
                                        'draft' => ['bg' => '#f3f4f6', 'text' => '#374151'],
                                        'sent' => ['bg' => '#dbeafe', 'text' => '#1e40af'],
                                        'confirmed' => ['bg' => '#d1fae5', 'text' => '#065f46'],
                                        'partially_received' => ['bg' => '#fef3c7', 'text' => '#92400e'],
                                        'completed' => ['bg' => '#dcfce7', 'text' => '#166534'],
                                        'cancelled' => ['bg' => '#fee2e2', 'text' => '#991b1b'],
                                    ];
                                    $status = $statusColors[$order->status] ?? ['bg' => '#f3f4f6', 'text' => '#374151'];
                                @endphp
                                @php
                                    $statusLabels = [
                                        'draft' => 'مسودة',
                                        'pending' => 'في الانتظار',
                                        'approved' => 'معتمد',
                                        'sent' => 'مُرسل',
                                        'received' => 'مُستلم',
                                        'completed' => 'مكتمل',
                                        'cancelled' => 'ملغي'
                                    ];
                                @endphp
                                <span class="status-badge status-{{ $order->status }}" style="padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                                    {{ $statusLabels[$order->status] ?? $order->status }}
                                </span>
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                @php
                                    $paymentColors = [
                                        'pending' => ['bg' => '#f3f4f6', 'text' => '#374151'],
                                        'partial' => ['bg' => '#fef3c7', 'text' => '#92400e'],
                                        'paid' => ['bg' => '#dcfce7', 'text' => '#166534'],
                                        'overdue' => ['bg' => '#fee2e2', 'text' => '#991b1b'],
                                    ];
                                    $payment = $paymentColors[$order->payment_status] ?? ['bg' => '#f3f4f6', 'text' => '#374151'];
                                @endphp
                                @php
                                    $paymentLabels = [
                                        'pending' => 'في الانتظار',
                                        'partial' => 'جزئي',
                                        'paid' => 'مدفوع',
                                        'overdue' => 'متأخر'
                                    ];
                                @endphp
                                <span class="payment-badge payment-{{ $order->payment_status }}" style="padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                                    {{ $paymentLabels[$order->payment_status] ?? $order->payment_status }}
                                </span>
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <div style="font-weight: 600; color: #2d3748;">{{ $order->order_date->format('Y-m-d') }}</div>
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <div style="font-weight: 600; color: #2d3748;">{{ $order->expected_delivery_date->format('Y-m-d') }}</div>
                                @if($order->expected_delivery_date->isPast() && !in_array($order->status, ['completed', 'cancelled']))
                                    <div style="font-size: 10px; color: #dc2626; font-weight: 600;">متأخر</div>
                                @endif
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <div style="font-weight: 600; color: #1e40af;">{{ number_format($order->total_amount, 0) }}</div>
                                <div style="font-size: 12px; color: #6b7280;">{{ $order->currency }}</div>
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <div style="display: flex; justify-content: center; gap: 8px;">
                                    <a href="{{ route('tenant.purchasing.purchase-orders.show', $order) }}"
                                       style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); color: white; padding: 10px 14px; border-radius: 8px; text-decoration: none; font-size: 14px; font-weight: 600; display: flex; align-items: center; gap: 6px; transition: all 0.3s ease; box-shadow: 0 2px 4px rgba(59, 130, 246, 0.3);"
                                       onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 8px rgba(59, 130, 246, 0.4)'"
                                       onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(59, 130, 246, 0.3)'"
                                       title="عرض التفاصيل">
                                        <i class="fas fa-eye"></i>
                                        <span>عرض</span>
                                    </a>
                                    @if(in_array($order->status, ['draft']))
                                        <a href="{{ route('tenant.purchasing.purchase-orders.edit', $order) }}"
                                           style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; padding: 10px 14px; border-radius: 8px; text-decoration: none; font-size: 14px; font-weight: 600; display: flex; align-items: center; gap: 6px; transition: all 0.3s ease; box-shadow: 0 2px 4px rgba(245, 158, 11, 0.3);"
                                           onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 8px rgba(245, 158, 11, 0.4)'"
                                           onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(245, 158, 11, 0.3)'"
                                           title="تعديل الأمر">
                                            <i class="fas fa-edit"></i>
                                            <span>تعديل</span>
                                        </a>
                                    @endif
                                    @if(in_array($order->status, ['draft', 'cancelled']))
                                        <form method="POST" action="{{ route('tenant.purchasing.purchase-orders.destroy', $order) }}" style="display: inline;" onsubmit="return confirm('هل أنت متأكد من حذف هذا الأمر؟')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; padding: 10px 14px; border: none; border-radius: 8px; cursor: pointer; font-size: 14px; font-weight: 600; display: flex; align-items: center; gap: 6px; transition: all 0.3s ease; box-shadow: 0 2px 4px rgba(239, 68, 68, 0.3);"
                                                    onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 8px rgba(239, 68, 68, 0.4)'"
                                                    onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(239, 68, 68, 0.3)'"
                                                    title="حذف الأمر">
                                                <i class="fas fa-trash"></i>
                                                <span>حذف</span>
                                            </button>
                                        </form>
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
            {{ $purchaseOrders->links() }}
        </div>
    @else
        <div style="text-align: center; padding: 40px; color: #6b7280;">
            <i class="fas fa-clipboard-list" style="font-size: 48px; margin-bottom: 15px; opacity: 0.5;"></i>
            <h3 style="margin: 0 0 10px 0;">لا توجد أوامر شراء</h3>
            <p style="margin: 0 0 20px 0;">ابدأ بإنشاء أول أمر شراء</p>
            <a href="{{ route('tenant.purchasing.purchase-orders.create') }}" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600;">
                <i class="fas fa-plus" style="margin-left: 8px;"></i>
                أمر شراء جديد
            </a>
        </div>
    @endif
</div>

@push('scripts')
<script>
function exportOrders() {
    // Create CSV content
    let csv = 'رقم الأمر,المورد,الحالة,حالة الدفع,تاريخ الأمر,التسليم المتوقع,القيمة الإجمالية,العملة\n';

    // Get data from table rows
    const rows = document.querySelectorAll('tbody tr');
    rows.forEach(row => {
        if (row.style.display !== 'none') {
            const cells = row.querySelectorAll('td');
            if (cells.length >= 8) {
                const poNumber = cells[0].querySelector('div').textContent.trim();
                const supplier = cells[1].querySelector('div').textContent.trim();
                const status = cells[2].querySelector('span').textContent.trim();
                const paymentStatus = cells[3].querySelector('span').textContent.trim();
                const orderDate = cells[4].querySelector('div').textContent.trim();
                const deliveryDate = cells[5].querySelector('div').textContent.trim();
                const amount = cells[6].querySelector('div').textContent.trim();
                const currency = cells[7].querySelector('div').textContent.trim();

                csv += `"${poNumber}","${supplier}","${status}","${paymentStatus}","${orderDate}","${deliveryDate}","${amount}","${currency}"\n`;
            }
        }
    });

    // Download CSV
    const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    const url = URL.createObjectURL(blob);
    link.setAttribute('href', url);
    link.setAttribute('download', 'purchase_orders_' + new Date().toISOString().split('T')[0] + '.csv');
    link.style.visibility = 'hidden';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}
</script>

<style>
/* Status badges for purchase orders */
.status-badge.status-draft {
    background: #f3f4f6;
    color: #374151;
}
.status-badge.status-pending {
    background: #fef3c7;
    color: #92400e;
}
.status-badge.status-approved {
    background: #d1fae5;
    color: #065f46;
}
.status-badge.status-sent {
    background: #dbeafe;
    color: #1e40af;
}
.status-badge.status-received {
    background: #e0e7ff;
    color: #3730a3;
}
.status-badge.status-completed {
    background: #dcfce7;
    color: #166534;
}
.status-badge.status-cancelled {
    background: #fee2e2;
    color: #991b1b;
}

/* Payment badges */
.payment-badge.payment-pending {
    background: #fef3c7;
    color: #92400e;
}
.payment-badge.payment-partial {
    background: #fef3c7;
    color: #92400e;
}
.payment-badge.payment-paid {
    background: #dcfce7;
    color: #166534;
}
.payment-badge.payment-overdue {
    background: #fee2e2;
    color: #991b1b;
}
</style>
@endpush
@endsection
