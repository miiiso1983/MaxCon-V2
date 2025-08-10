@extends('layouts.modern')

@section('page-title', 'فاتورة رقم ' . $invoice->invoice_number)
@section('page-description', 'تفاصيل الفاتورة')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
<style>
    body {
        font-family: 'Cairo', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif !important;
        background: #f8fafc;
    }
    
    .invoice-container {
        max-width: 900px;
        margin: 20px auto;
        background: white;
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        border-radius: 20px;
        overflow: hidden;
    }
    
    .invoice-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 40px;
        text-align: center;
        position: relative;
    }
    
    .invoice-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="20" cy="20" r="2" fill="rgba(255,255,255,0.1)"/><circle cx="80" cy="80" r="2" fill="rgba(255,255,255,0.1)"/><circle cx="40" cy="60" r="1" fill="rgba(255,255,255,0.1)"/></svg>');
    }
    
    .company-logo {
        width: 100px;
        height: 100px;
        background: rgba(255,255,255,0.2);
        border-radius: 50%;
        margin: 0 auto 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 36px;
        font-weight: bold;
        position: relative;
        z-index: 2;
    }
    
    .invoice-content {
        padding: 40px;
    }
    
    .invoice-details-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 30px;
        margin-bottom: 40px;
    }
    
    .detail-card {
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        border-radius: 15px;
        padding: 25px;
        border-left: 5px solid #ed8936;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    }
    
    .detail-card h4 {
        color: #2d3748;
        font-size: 18px;
        font-weight: 700;
        margin: 0 0 15px 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .detail-item {
        margin-bottom: 12px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .detail-label {
        font-weight: 600;
        color: #4a5568;
    }
    
    .detail-value {
        color: #2d3748;
        font-weight: 500;
    }
    
    .items-section {
        margin-bottom: 40px;
    }
    
    .section-title {
        font-size: 24px;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 15px;
    }
    
    .items-table {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        border: 1px solid #e2e8f0;
    }
    
    .items-table thead {
        background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%);
    }
    
    .items-table th {
        padding: 20px 15px;
        color: white;
        font-weight: 700;
        text-align: center;
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    
    .items-table td {
        padding: 20px 15px;
        border-bottom: 1px solid #e2e8f0;
        text-align: center;
    }
    
    .items-table tbody tr:hover {
        background: linear-gradient(135deg, #f7fafc 0%, #edf2f7 100%);
        transition: all 0.3s ease;
    }
    
    .product-info {
        text-align: right;
    }
    
    .product-name {
        font-weight: 700;
        color: #2d3748;
        font-size: 16px;
        margin-bottom: 5px;
    }
    
    .product-code {
        font-size: 12px;
        color: #718096;
        background: #edf2f7;
        padding: 2px 8px;
        border-radius: 12px;
        display: inline-block;
    }
    
    .totals-section {
        background: linear-gradient(135deg, #f7fafc 0%, #edf2f7 100%);
        border-radius: 15px;
        padding: 30px;
        margin-bottom: 30px;
        border: 2px solid #e2e8f0;
    }
    
    .total-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 1px solid #e2e8f0;
    }
    
    .total-row:last-child {
        border-bottom: none;
        margin-bottom: 0;
    }
    
    .total-final {
        background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%);
        color: white;
        padding: 20px;
        border-radius: 12px;
        font-size: 20px;
        font-weight: 700;
        text-align: center;
        margin-top: 20px;
        box-shadow: 0 4px 15px rgba(237, 137, 54, 0.3);
    }
    
    .qr-section {
        background: linear-gradient(135deg, #e6fffa 0%, #f0fff4 100%);
        border-radius: 15px;
        padding: 30px;
        text-align: center;
        border: 3px solid #38b2ac;
        margin-bottom: 30px;
    }
    
    .qr-code-container {
        display: inline-block;
        padding: 20px;
        background: white;
        border-radius: 15px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        margin: 20px 0;
    }
    
    .status-badge {
        position: absolute;
        top: 20px;
        left: 20px;
        padding: 10px 20px;
        border-radius: 25px;
        font-weight: 700;
        font-size: 14px;
        z-index: 3;
    }
    
    .status-draft {
        background: rgba(255,193,7,0.9);
        color: white;
    }
    
    .status-pending {
        background: rgba(23,162,184,0.9);
        color: white;
    }
    
    .status-paid {
        background: rgba(40,167,69,0.9);
        color: white;
    }
    
    .actions-section {
        padding: 30px;
        background: #f8fafc;
        text-align: center;
        border-top: 1px solid #e2e8f0;
    }
    
    .action-btn {
        display: inline-block;
        padding: 15px 30px;
        margin: 0 10px;
        border-radius: 12px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    
    .btn-secondary {
        background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%);
        color: white;
    }
    
    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.2);
    }
</style>
@endpush

@section('content')
<div class="invoice-container">
    <!-- Invoice Header -->
    <div class="invoice-header">
        <div class="company-logo">
            {{ substr($invoice->tenant?->name ?? 'ماكس', 0, 2) }}
        </div>
        <h1 style="font-size: 42px; font-weight: 700; margin: 0; position: relative; z-index: 2;">
            {{ $invoice->tenant?->name ?? 'شركة ماكس كون' }}
        </h1>
        <p style="font-size: 20px; margin: 15px 0 0 0; opacity: 0.9; position: relative; z-index: 2;">
            فاتورة رقم {{ $invoice->invoice_number }}
        </p>
        
        <div class="status-badge 
            @if($invoice->status === 'draft') status-draft
            @elseif($invoice->status === 'pending') status-pending  
            @elseif($invoice->status === 'paid') status-paid
            @endif">
            @if($invoice->status === 'draft') مسودة
            @elseif($invoice->status === 'pending') في الانتظار
            @elseif($invoice->status === 'paid') مدفوعة
            @endif
        </div>
    </div>

    <!-- Invoice Content -->
    <div class="invoice-content">
        <!-- Invoice Details Grid -->
        <div class="invoice-details-grid">
            <!-- Customer Information -->
            <div class="detail-card">
                <h4><i class="fas fa-user-tie" style="color: #ed8936;"></i> معلومات العميل</h4>
                <div class="detail-item">
                    <span class="detail-label">اسم العميل:</span>
                    <span class="detail-value">{{ $invoice->customer->name }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">رقم العميل:</span>
                    <span class="detail-value">{{ $invoice->customer->customer_code }}</span>
                </div>
                @if($invoice->customer->phone)
                <div class="detail-item">
                    <span class="detail-label">الهاتف:</span>
                    <span class="detail-value">{{ $invoice->customer->phone }}</span>
                </div>
                @endif
                @if($invoice->customer->email)
                <div class="detail-item">
                    <span class="detail-label">البريد الإلكتروني:</span>
                    <span class="detail-value">{{ $invoice->customer->email }}</span>
                </div>
                @endif
            </div>

            <!-- Invoice Information -->
            <div class="detail-card">
                <h4><i class="fas fa-file-invoice" style="color: #667eea;"></i> تفاصيل الفاتورة</h4>
                <div class="detail-item">
                    <span class="detail-label">تاريخ الفاتورة:</span>
                    <span class="detail-value">{{ optional($invoice->invoice_date)->format('Y/m/d') ?? '-' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">تاريخ الاستحقاق:</span>
                    <span class="detail-value">{{ optional($invoice->due_date)->format('Y/m/d') ?? '-' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">العملة:</span>
                    <span class="detail-value">دينار عراقي</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">النوع:</span>
                    <span class="detail-value">
                        @if($invoice->type === 'sales') فاتورة مبيعات
                        @elseif($invoice->type === 'proforma') فاتورة أولية
                        @endif
                    </span>
                </div>
                @if($invoice->sales_representative)
                <div class="detail-item">
                    <span class="detail-label">المندوب:</span>
                    <span class="detail-value">{{ $invoice->sales_representative }}</span>
                </div>
                @endif
            </div>
        </div>

        <!-- Invoice Items -->
        <div class="items-section">
            <h3 class="section-title">
                <i class="fas fa-list" style="color: #9f7aea;"></i>
                عناصر الفاتورة
            </h3>

            <div class="items-table">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr>
                            <th style="text-align: right;">المنتج</th>
                            <th>الكمية</th>
                            <th>سعر الوحدة</th>
                            <th>الخصم</th>
                            <th>الإجمالي</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoice->items as $item)
                        <tr>
                            <td class="product-info">
                                <div class="product-name">{{ $item->product_name }}</div>
                                <div class="product-code">{{ $item->product_code }}</div>
                                @if($item->notes)
                                    <div style="font-size: 12px; color: #718096; margin-top: 5px;">{{ $item->notes }}</div>
                                @endif
                            </td>
                            <td style="font-weight: 600;">{{ $item->quantity }}</td>
                            <td style="font-weight: 600;">{{ number_format($item->unit_price, 2) }} دينار عراقي</td>
                            <td style="color: #e53e3e; font-weight: 600;">
                                @if($item->discount_amount > 0)
                                    {{ number_format($item->discount_amount, 2) }} دينار عراقي
                                @else
                                    -
                                @endif
                            </td>
                            <td style="font-weight: 700; color: #2d3748; font-size: 16px;">{{ number_format($item->total_amount, 2) }} دينار عراقي</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Invoice Totals -->
        <div class="totals-section">
            <h3 class="section-title">
                <i class="fas fa-calculator" style="color: #38b2ac;"></i>
                إجماليات الفاتورة
            </h3>

            <div class="total-row">
                <span class="detail-label">المجموع الفرعي:</span>
                <span class="detail-value" style="font-weight: 700;">{{ number_format($invoice->subtotal_amount, 2) }} دينار عراقي</span>
            </div>

            @if($invoice->discount_amount > 0)
            <div class="total-row">
                <span class="detail-label">الخصم:</span>
                <span class="detail-value" style="color: #e53e3e; font-weight: 700;">{{ number_format($invoice->discount_amount, 2) }} دينار عراقي</span>
            </div>
            @endif

            @if($invoice->shipping_cost > 0 || $invoice->additional_charges > 0)
            <div class="total-row">
                <span class="detail-label">الشحن والرسوم الإضافية:</span>
                <span class="detail-value" style="font-weight: 700;">{{ number_format($invoice->shipping_cost + $invoice->additional_charges, 2) }} دينار عراقي</span>
            </div>
            @endif

            <div class="total-row">
                <span class="detail-label">ضريبة القيمة المضافة (15%):</span>
                <span class="detail-value" style="font-weight: 700;">{{ number_format($invoice->tax_amount, 2) }} دينار عراقي</span>
            </div>

            @if($invoice->previous_balance > 0)
            <div class="total-row">
                <span class="detail-label">المديونية السابقة:</span>
                <span class="detail-value" style="color: #dc2626; font-weight: 700;">{{ number_format($invoice->previous_balance, 2) }} دينار عراقي</span>
            </div>

            <div class="total-row">
                <span class="detail-label">إجمالي الفاتورة:</span>
                <span class="detail-value" style="font-weight: 700;">{{ number_format($invoice->total_amount, 2) }} دينار عراقي</span>
            </div>

            <div class="total-final">
                إجمالي المديونية: {{ number_format($invoice->total_amount + $invoice->previous_balance, 2) }} دينار عراقي
            </div>
            @else
            <div class="total-final">
                الإجمالي النهائي: {{ number_format($invoice->total_amount, 2) }} دينار عراقي
            </div>
            @endif
        </div>

        <!-- QR Code Section -->
        @php
            $qrCode = $invoice->qr_code;
            // Force generate QR code if not exists
            if (empty($qrCode)) {
                $qrCode = $invoice->generateQrCode();
            }
        @endphp

        @if($qrCode)
        <div class="qr-section">
            <h3 style="margin: 0 0 20px 0; color: #2d3748; font-weight: 700; font-size: 24px;">
                <i class="fas fa-qrcode" style="color: #38b2ac; margin-left: 10px;"></i>
                رمز QR للتحقق من الفاتورة
            </h3>
            <div class="qr-code-container">
                @if(str_contains(base64_decode($qrCode), '<svg'))
                    <!-- SVG QR Code -->
                    <div style="width: 200px; height: 200px; border: 2px solid #38b2ac; border-radius: 10px; overflow: hidden; display: flex; align-items: center; justify-content: center;">
                        {!! base64_decode($qrCode) !!}
                    </div>
                @else
                    <!-- PNG QR Code -->
                    <img src="data:image/png;base64,{{ $qrCode }}" alt="QR Code" style="width: 200px; height: 200px; border: 2px solid #38b2ac; border-radius: 10px;">
                @endif
            </div>
            <p style="margin: 15px 0 0 0; color: #4a5568; font-size: 16px; font-weight: 500;">
                امسح الرمز للحصول على معلومات الفاتورة كاملة
            </p>
            <div style="margin-top: 15px; font-size: 14px; color: #718096;">
                <p style="margin: 5px 0;">يحتوي على: اسم الشركة • رقم الفاتورة • معلومات العميل • المبالغ • المندوب • الرابط</p>
            </div>
            <div style="margin-top: 15px; text-align: center;">
                <button onclick="regenerateQrCode({{ $invoice->id }})"
                        style="background: linear-gradient(135deg, #38b2ac 0%, #319795 100%); color: white; padding: 8px 16px; border: none; border-radius: 6px; font-size: 12px; cursor: pointer; transition: all 0.3s ease;"
                        onmouseover="this.style.transform='translateY(-1px)'"
                        onmouseout="this.style.transform='translateY(0)'">
                    🔄 إعادة إنشاء QR Code
                </button>
                <a href="{{ route('tenant.sales.invoices.qr-test', $invoice) }}"
                   style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 8px 16px; border-radius: 6px; text-decoration: none; font-size: 12px; margin-left: 10px; display: inline-block; transition: all 0.3s ease;"
                   target="_blank"
                   onmouseover="this.style.transform='translateY(-1px)'"
                   onmouseout="this.style.transform='translateY(0)'">
                    🔍 اختبار QR Code
                </a>
            </div>
        </div>
        @else
        <div class="qr-section" style="background: linear-gradient(135deg, #fff5f5 0%, #fed7d7 100%); border: 2px solid #f56565;">
            <h3 style="margin: 0 0 15px 0; color: #e53e3e; font-weight: 700; font-size: 20px;">
                <i class="fas fa-exclamation-triangle" style="color: #f56565; margin-left: 10px;"></i>
                خطأ في إنشاء رمز QR
            </h3>
            <p style="margin: 0; color: #e53e3e; font-size: 16px;">
                لم يتم إنشاء رمز QR لهذه الفاتورة. يرجى المحاولة مرة أخرى.
            </p>
        </div>
        @endif

        <!-- Notes -->
        @if($invoice->notes)
        <div style="background: linear-gradient(135deg, #fff5f5 0%, #fed7d7 100%); border-radius: 15px; padding: 25px; margin-bottom: 30px; border-left: 5px solid #f56565;">
            <h4 style="margin: 0 0 15px 0; color: #2d3748; font-weight: 700; display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-sticky-note" style="color: #f56565;"></i>
                ملاحظات
            </h4>
            <p style="margin: 0; color: #4a5568; line-height: 1.6;">{{ $invoice->notes }}</p>
        </div>
        @endif

        <!-- Free Samples -->
        @if(!empty($invoice->free_samples))
        <div style="background: linear-gradient(135deg, #f0fff4 0%, #c6f6d5 100%); border-radius: 15px; padding: 25px; margin-bottom: 30px; border-left: 5px solid #10b981;">
            <h4 style="margin: 0 0 15px 0; color: #2d3748; font-weight: 700; display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-gift" style="color: #10b981;"></i>
                العينات المجانية المرفقة
            </h4>
            <div style="background: white; border-radius: 10px; padding: 15px; border: 1px solid #d1fae5;">
                <p style="margin: 0; color: #4a5568; line-height: 1.6; white-space: pre-line;">{{ $invoice->free_samples }}</p>
            </div>
            <div style="margin-top: 10px; font-size: 12px; color: #059669; display: flex; align-items: center; gap: 5px;">
                <i class="fas fa-info-circle"></i>
                <span>هذه العينات مجانية ولا تحتسب ضمن قيمة الفاتورة</span>
            </div>
        </div>
        @endif
    </div>

    <!-- Actions Section -->
    <div class="actions-section">
        <a href="{{ route('tenant.sales.invoices.index') }}" class="action-btn btn-primary">
            <i class="fas fa-arrow-right" style="margin-left: 8px;"></i>
            العودة للقائمة
        </a>
        <a href="{{ route('tenant.sales.invoices.view-pdf', $invoice) }}" class="action-btn btn-secondary" target="_blank">
            <i class="fas fa-eye" style="margin-left: 8px;"></i>
            عرض PDF
        </a>
        <a href="{{ route('tenant.sales.invoices.pdf', $invoice) }}" class="action-btn" style="background: linear-gradient(135deg, #e53e3e 0%, #c53030 100%); color: white;">
            <i class="fas fa-download" style="margin-left: 8px;"></i>
            تحميل PDF
        </a>
        @if(optional($invoice->customer)->phone)
        <button onclick="sendWhatsAppFromShow({{ $invoice->id }}, '{{ $invoice->customer->phone }}', '{{ addslashes($invoice->invoice_number) }}')"
                class="action-btn"
                style="background: linear-gradient(135deg, #25d366 0%, #128c7e 100%); color: white;">
            <i class="fab fa-whatsapp" style="margin-left: 8px;"></i>
            إرسال واتساب
        </button>
        @endif
        @if(optional($invoice->customer)->email)
        <button onclick="showEmailModalFromShow({{ $invoice->id }}, '{{ $invoice->customer->email }}')"
                class="action-btn"
                style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); color: white;">
            <i class="fas fa-envelope" style="margin-left: 8px;"></i>
            إرسال إيميل
        </button>
        @endif
        <a href="{{ route('tenant.sales.returns.create', ['invoice_id' => $invoice->id]) }}"
           class="action-btn"
           style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white;">
            <i class="fas fa-undo-alt" style="margin-left: 8px;"></i>
            إنشاء مرتجع
        </a>
    </div>
</div>

@push('scripts')
<script>
// إرسال الفاتورة عبر واتساب من صفحة العرض
function sendWhatsAppFromShow(invoiceId, phone, invoiceNumber) {
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
    const invoiceUrl = window.location.href;

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

    // إظهار رسالة نجاح
    alert('تم فتح واتساب بنجاح! يمكنك الآن إرسال الرسالة.');
}

// إظهار نافذة الإيميل من صفحة العرض
function showEmailModalFromShow(invoiceId, email) {
    // TODO: Implement email modal
    alert(`سيتم إرسال الفاتورة إلى: ${email}`);
    console.log('Send email to:', email, 'for invoice:', invoiceId);
}

// إعادة إنشاء QR Code
function regenerateQrCode(invoiceId) {
    if (confirm('هل تريد إعادة إنشاء رمز QR لهذه الفاتورة؟')) {
        const button = event.target;
        const originalText = button.innerHTML;
        button.innerHTML = '🔄 جاري الإنشاء...';
        button.disabled = true;

        fetch(`/tenant/sales/invoices/${invoiceId}/qr-test`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.qr_code_exists) {
                alert('تم إعادة إنشاء رمز QR بنجاح!');
                location.reload(); // Refresh to show new QR code
            } else {
                alert('فشل في إنشاء رمز QR');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('حدث خطأ أثناء إنشاء رمز QR');
        })
        .finally(() => {
            button.innerHTML = originalText;
            button.disabled = false;
        });
    }
}
</script>
@endpush

@endsection
