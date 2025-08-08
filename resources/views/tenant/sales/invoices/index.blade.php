@extends('layouts.modern')

@section('page-title', 'إدارة الفواتير')
@section('page-description', 'إدارة شاملة للفواتير والمدفوعات')

@push('head')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
<!-- Page Header -->
<div style="background: linear-gradient(135deg, #9f7aea 0%, #805ad5 100%); border-radius: 20px; padding: 30px; margin-bottom: 30px; color: white; position: relative; overflow: hidden;">
    <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
    <div style="position: absolute; bottom: -30px; left: -30px; width: 150px; height: 150px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>
    
    <div style="position: relative; z-index: 2;">
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    <div style="background: rgba(255,255,255,0.2); border-radius: 15px; padding: 15px; margin-left: 20px;">
                        <i class="fas fa-file-invoice" style="font-size: 32px;"></i>
                    </div>
                    <div>
                        <h1 style="font-size: 32px; font-weight: 800; margin: 0; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                            إدارة الفواتير 📄
                        </h1>
                        <p style="font-size: 18px; margin: 5px 0 0 0; opacity: 0.9;">
                            إدارة شاملة للفواتير والمدفوعات مع QR Code
                        </p>
                    </div>
                </div>
                
                <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-file-invoice" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px; font-weight: 600;">{{ $invoices->total() ?? 0 }} فاتورة</span>
                    </div>
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-clock" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px;">{{ $statusCounts['sent'] ?? 0 }} مرسلة</span>
                    </div>
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-check-circle" style="margin-left: 8px; color: #4ade80;"></i>
                        <span style="font-size: 14px;">{{ $statusCounts['paid'] ?? 0 }} مدفوعة</span>
                    </div>
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-exclamation-triangle" style="margin-left: 8px; color: #fbbf24;"></i>
                        <span style="font-size: 14px;">{{ $statusCounts['overdue'] ?? 0 }} متأخرة</span>
                    </div>
                </div>
            </div>
            
            <div style="display: flex; gap: 15px; align-items: center;">
                <a href="/tenant/sales/invoices/professional" style="background: rgba(255,255,255,0.2); color: white; padding: 15px 25px; border-radius: 15px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3); transition: all 0.3s ease;"
                   onmouseover="this.style.background='rgba(255,255,255,0.3)'; this.style.transform='translateY(-2px)';"
                   onmouseout="this.style.background='rgba(255,255,255,0.2)'; this.style.transform='translateY(0)';">
                    <i class="fas fa-plus-circle"></i>
                    إنشاء فاتورة احترافية
                </a>
                <a href="{{ route('tenant.sales.invoices.create') }}" style="background: rgba(255,255,255,0.15); color: white; padding: 12px 20px; border-radius: 12px; text-decoration: none; font-weight: 500; display: flex; align-items: center; gap: 8px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.2); transition: all 0.3s ease; font-size: 14px;"
                   onmouseover="this.style.background='rgba(255,255,255,0.25)'; this.style.transform='translateY(-1px)';"
                   onmouseout="this.style.background='rgba(255,255,255,0.15)'; this.style.transform='translateY(0)';">
                    <i class="fas fa-plus"></i>
                    الفاتورة العادية
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="content-card" style="margin-bottom: 25px;">
    <form method="GET" action="{{ route('tenant.sales.invoices.index') }}">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 20px;">
            <!-- Search -->
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">البحث</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                       style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;" 
                       placeholder="رقم الفاتورة أو اسم العميل...">
            </div>
            
            <!-- Status Filter -->
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">الحالة</label>
                <select name="status" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;">
                    <option value="">جميع الحالات</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>مسودة</option>
                    <option value="sent" {{ request('status') === 'sent' ? 'selected' : '' }}>مرسلة</option>
                    <option value="viewed" {{ request('status') === 'viewed' ? 'selected' : '' }}>تم عرضها</option>
                    <option value="partial_paid" {{ request('status') === 'partial_paid' ? 'selected' : '' }}>مدفوعة جزئياً</option>
                    <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>مدفوعة</option>
                    <option value="overdue" {{ request('status') === 'overdue' ? 'selected' : '' }}>متأخرة</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>ملغية</option>
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
            <button type="submit" class="btn-purple" style="padding: 12px 24px;">
                <i class="fas fa-search"></i>
                بحث
            </button>
            <a href="{{ route('tenant.sales.invoices.index') }}" class="btn-gray" style="padding: 12px 24px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
                <i class="fas fa-times"></i>
                إعادة تعيين
            </a>
        </div>
    </form>
</div>

<!-- Invoices Table -->
<div class="content-card">
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f7fafc;">
                    <th style="padding: 12px; text-align: right; font-weight: 600; color: #4a5568; border-bottom: 1px solid #e2e8f0;">رقم الفاتورة</th>
                    <th style="padding: 12px; text-align: right; font-weight: 600; color: #4a5568; border-bottom: 1px solid #e2e8f0;">العميل</th>
                    <th style="padding: 12px; text-align: right; font-weight: 600; color: #4a5568; border-bottom: 1px solid #e2e8f0;">تاريخ الفاتورة</th>
                    <th style="padding: 12px; text-align: right; font-weight: 600; color: #4a5568; border-bottom: 1px solid #e2e8f0;">تاريخ الاستحقاق</th>
                    <th style="padding: 12px; text-align: right; font-weight: 600; color: #4a5568; border-bottom: 1px solid #e2e8f0;">المبلغ الإجمالي</th>
                    <th style="padding: 12px; text-align: right; font-weight: 600; color: #4a5568; border-bottom: 1px solid #e2e8f0;">المبلغ المدفوع</th>
                    <th style="padding: 12px; text-align: right; font-weight: 600; color: #4a5568; border-bottom: 1px solid #e2e8f0;">العينات المجانية</th>
                    <th style="padding: 12px; text-align: right; font-weight: 600; color: #4a5568; border-bottom: 1px solid #e2e8f0;">الحالة</th>
                    <th style="padding: 12px; text-align: right; font-weight: 600; color: #4a5568; border-bottom: 1px solid #e2e8f0;">الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($invoices as $invoice)
                <tr style="transition: all 0.3s ease;" onmouseover="this.style.background='#f7fafc';" onmouseout="this.style.background='white';">
                    <td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">
                        <div style="font-weight: 600; color: #2d3748;">{{ $invoice->invoice_number }}</div>
                        <div style="font-size: 12px; color: #718096;">{{ $invoice->type === 'sales' ? 'فاتورة مبيعات' : 'فاتورة أولية' }}</div>
                    </td>
                    <td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">
                        <div style="font-weight: 600; color: #2d3748;">{{ $invoice->customer->name }}</div>
                        <div style="font-size: 12px; color: #718096;">{{ $invoice->customer->customer_code }}</div>
                    </td>
                    <td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">
                        <div style="color: #4a5568;">{{ $invoice->invoice_date->format('Y/m/d') }}</div>
                    </td>
                    <td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">
                        <div style="color: #4a5568;">{{ $invoice->due_date->format('Y/m/d') }}</div>
                        @if($invoice->isOverdue())
                            <div style="font-size: 12px; color: #f56565;">متأخر {{ $invoice->days_overdue }} يوم</div>
                        @endif
                    </td>
                    <td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">
                        <div style="font-weight: 600; color: #2d3748;">{{ number_format($invoice->total_amount, 2) }} {{ $invoice->currency }}</div>
                    </td>
                    <td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">
                        <div style="font-weight: 600; color: #48bb78;">{{ number_format($invoice->paid_amount, 2) }} {{ $invoice->currency }}</div>
                        @if($invoice->remaining_amount > 0)
                            <div style="font-size: 12px; color: #f56565;">متبقي: {{ number_format($invoice->remaining_amount, 2) }}</div>
                        @endif
                    </td>
                    <td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">
                        @if($invoice->free_samples)
                            <div style="display: flex; align-items: center; gap: 5px;">
                                <i class="fas fa-gift" style="color: #10b981; font-size: 14px;"></i>
                                <span style="font-size: 12px; color: #10b981; font-weight: 600;">متوفرة</span>
                            </div>
                            <div style="font-size: 11px; color: #718096; margin-top: 2px; max-width: 120px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $invoice->free_samples }}">
                                {{ Str::limit($invoice->free_samples, 30) }}
                            </div>
                        @else
                            <span style="font-size: 12px; color: #a0aec0;">لا توجد</span>
                        @endif
                    </td>
                    <td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">
                        <span class="status-badge status-{{ $invoice->status_color }}">
                            {{ match($invoice->status) {
                                'draft' => 'مسودة',
                                'sent' => 'مرسلة',
                                'viewed' => 'تم عرضها',
                                'partial_paid' => 'مدفوعة جزئياً',
                                'paid' => 'مدفوعة',
                                'overdue' => 'متأخرة',
                                'cancelled' => 'ملغية',
                                'refunded' => 'مسترد',
                                default => $invoice->status
                            } }}
                        </span>
                    </td>
                    <td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">
                        <div style="display: flex; gap: 8px; align-items: center; flex-wrap: wrap;">
                            <!-- عرض الفاتورة -->
                            <a href="{{ route('tenant.sales.invoices.show', $invoice) }}"
                               style="background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%); color: white; padding: 8px 12px; border-radius: 8px; text-decoration: none; font-size: 12px; font-weight: 600; display: inline-flex; align-items: center; gap: 5px; transition: all 0.3s ease; box-shadow: 0 2px 4px rgba(66, 153, 225, 0.3); margin: 2px;"
                               title="عرض التفاصيل"
                               onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 8px rgba(66, 153, 225, 0.4)';"
                               onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(66, 153, 225, 0.3)';">
                                <i class="fas fa-eye"></i>
                                <span>عرض</span>
                            </a>

                            <!-- عرض PDF -->
                            <a href="{{ route('tenant.sales.invoices.view-pdf', $invoice) }}"
                               style="background: linear-gradient(135deg, #f56565 0%, #e53e3e 100%); color: white; padding: 8px 12px; border-radius: 8px; text-decoration: none; font-size: 12px; font-weight: 600; display: inline-flex; align-items: center; gap: 5px; transition: all 0.3s ease; box-shadow: 0 2px 4px rgba(245, 101, 101, 0.3); margin: 2px;"
                               title="عرض PDF"
                               target="_blank"
                               onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 8px rgba(245, 101, 101, 0.4)';"
                               onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(245, 101, 101, 0.3)';">
                                <i class="fas fa-file-pdf"></i>
                                <span>PDF</span>
                            </a>

                            <!-- تحميل PDF -->
                            <a href="{{ route('tenant.sales.invoices.pdf', $invoice) }}"
                               style="background: linear-gradient(135deg, #e53e3e 0%, #c53030 100%); color: white; padding: 8px 12px; border-radius: 8px; text-decoration: none; font-size: 12px; font-weight: 600; display: inline-flex; align-items: center; gap: 5px; transition: all 0.3s ease; box-shadow: 0 2px 4px rgba(229, 62, 62, 0.3); margin: 2px;"
                               title="تحميل PDF"
                               onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 8px rgba(229, 62, 62, 0.4)';"
                               onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(229, 62, 62, 0.3)';">
                                <i class="fas fa-download"></i>
                                <span>تحميل</span>
                            </a>

                            <!-- إرسال واتساب -->
                            @if($invoice->customer->phone)
                            <button onclick="sendWhatsApp({{ $invoice->id }}, '{{ addslashes($invoice->customer->phone) }}', '{{ addslashes($invoice->invoice_number) }}')"
                                    style="background: linear-gradient(135deg, #25d366 0%, #128c7e 100%); color: white; padding: 8px 12px; border-radius: 8px; border: none; font-size: 12px; font-weight: 600; display: flex; align-items: center; gap: 5px; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 2px 4px rgba(37, 211, 102, 0.3);"
                                    title="إرسال واتساب"
                                    onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 8px rgba(37, 211, 102, 0.4)';"
                                    onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(37, 211, 102, 0.3)';">
                                <i class="fab fa-whatsapp"></i>
                                <span>واتساب</span>
                            </button>
                            @endif

                            <!-- إرسال إيميل -->
                            @if($invoice->customer->email)
                            <button onclick="showEmailModal({{ $invoice->id }}, '{{ $invoice->customer->email }}')"
                                    style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); color: white; padding: 8px 12px; border-radius: 8px; border: none; font-size: 12px; font-weight: 600; display: flex; align-items: center; gap: 5px; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 2px 4px rgba(72, 187, 120, 0.3);"
                                    title="إرسال بالإيميل"
                                    onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 8px rgba(72, 187, 120, 0.4)';"
                                    onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(72, 187, 120, 0.3)';">
                                <i class="fas fa-envelope"></i>
                                <span>إيميل</span>
                            </button>
                            @endif

                            <!-- تغيير الحالة -->
                            <button onclick="showStatusModal({{ $invoice->id }}, '{{ $invoice->status }}')"
                                    style="background: linear-gradient(135deg, #9f7aea 0%, #805ad5 100%); color: white; padding: 8px 12px; border-radius: 8px; border: none; font-size: 12px; font-weight: 600; display: flex; align-items: center; gap: 5px; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 2px 4px rgba(159, 122, 234, 0.3);"
                                    title="تغيير الحالة"
                                    onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 8px rgba(159, 122, 234, 0.4)';"
                                    onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(159, 122, 234, 0.3)';">
                                <i class="fas fa-exchange-alt"></i>
                                <span>حالة</span>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="padding: 40px; text-align: center; color: #718096;">
                        <div style="display: flex; flex-direction: column; align-items: center;">
                            <i class="fas fa-file-invoice" style="font-size: 48px; color: #e2e8f0; margin-bottom: 15px;"></i>
                            <p style="font-size: 18px; font-weight: 600; margin: 0 0 5px 0;">لا توجد فواتير</p>
                            <p style="font-size: 14px; margin: 0;">ابدأ بإنشاء فاتورة جديدة</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($invoices->hasPages())
    <div style="margin-top: 20px;">
        {{ $invoices->appends(request()->query())->links() }}
    </div>
    @endif
</div>

@push('scripts')
<script>
// Setup CSRF token
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

function showEmailModal(invoiceId, customerEmail) {
    // Implementation for email modal
    if (confirm('هل تريد إرسال الفاتورة بالإيميل إلى: ' + customerEmail + '؟')) {
        sendEmail(invoiceId, customerEmail);
    }
}

function sendEmail(invoiceId, email) {
    // Show loading
    const button = event.target;
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الإرسال...';
    button.disabled = true;

    fetch(`/tenant/sales/invoices/${invoiceId}/send-email`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({
            email: email
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('تم إرسال الفاتورة بالإيميل بنجاح!');
        } else {
            alert('فشل في إرسال الإيميل: ' + (data.message || 'خطأ غير معروف'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('حدث خطأ أثناء إرسال الإيميل: ' + error.message);
    })
    .finally(() => {
        button.innerHTML = originalText;
        button.disabled = false;
    });
}

function showStatusModal(invoiceId, currentStatus) {
    const statuses = {
        'draft': 'مسودة',
        'sent': 'مرسلة',
        'paid': 'مدفوعة',
        'partial_paid': 'مدفوعة جزئياً',
        'overdue': 'متأخرة',
        'cancelled': 'ملغية'
    };

    let options = '';
    for (const [key, value] of Object.entries(statuses)) {
        const selected = key === currentStatus ? 'selected' : '';
        options += `<option value="${key}" ${selected}>${value}</option>`;
    }

    const newStatus = prompt(`الحالة الحالية: ${statuses[currentStatus]}\nاختر الحالة الجديدة:\n\n${Object.values(statuses).join('\n')}`);

    if (newStatus && newStatus !== currentStatus) {
        updateStatus(invoiceId, newStatus);
    }
}

function updateStatus(invoiceId, newStatus) {
    fetch(`/tenant/sales/invoices/${invoiceId}/status`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({
            status: newStatus
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('تم تحديث حالة الفاتورة بنجاح!');
            location.reload(); // Refresh the page to show updated status
        } else {
            alert('فشل في تحديث الحالة: ' + (data.message || 'خطأ غير معروف'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('حدث خطأ أثناء تحديث الحالة: ' + error.message);
    });
}

// إرسال الفاتورة عبر واتساب
function sendWhatsApp(invoiceId, phone, invoiceNumber) {
    console.log('SendWhatsApp called with:', {invoiceId, phone, invoiceNumber});

    // تنظيف رقم الهاتف
    let cleanPhone = phone.replace(/[^\d+]/g, '');

    // إضافة رمز الدولة إذا لم يكن موجوداً
    if (!cleanPhone.startsWith('+')) {
        if (cleanPhone.startsWith('00')) {
            cleanPhone = '+' + cleanPhone.substring(2);
        } else if (cleanPhone.startsWith('0')) {
            cleanPhone = '+964' + cleanPhone.substring(1); // العراق
        } else {
            cleanPhone = '+' + cleanPhone;
        }
    }

    // إنشاء رسالة الواتساب
    const companyName = '{{ auth()->user()->tenant->name ?? "شركة ماكس كون" }}';
    const invoiceUrl = `{{ url('/tenant/sales/invoices') }}/${invoiceId}`;

    const message = `مرحباً،

تم إصدار فاتورة جديدة لكم من ${companyName}

📄 رقم الفاتورة: ${invoiceNumber}
🔗 رابط الفاتورة: ${invoiceUrl}

يمكنكم عرض تفاصيل الفاتورة وتحميلها من الرابط أعلاه.

شكراً لتعاملكم معنا.`;

    // ترميز الرسالة للـ URL
    const encodedMessage = encodeURIComponent(message);

    // إنشاء رابط واتساب
    const whatsappUrl = `https://wa.me/${cleanPhone}?text=${encodedMessage}`;

    // فتح واتساب في نافذة جديدة
    window.open(whatsappUrl, '_blank');

    // تسجيل الإرسال (اختياري)
    console.log('WhatsApp message sent to:', cleanPhone, 'for invoice:', invoiceNumber);
}
</script>
@endpush
@endsection
