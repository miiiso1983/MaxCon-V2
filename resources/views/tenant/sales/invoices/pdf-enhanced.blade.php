<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>فاتورة {{ $invoice->invoice_number }}</title>
    <style>
        @page {
            margin: 15mm;
            size: A4;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            line-height: 1.5;
            color: #333;
            direction: rtl;
        }

        .invoice-container {
            width: 100%;
            max-width: 210mm;
            margin: 0 auto;
        }

        .header {
            display: table;
            width: 100%;
            margin-bottom: 20px;
            border-bottom: 3px solid #2563eb;
            padding-bottom: 15px;
        }

        .header-left, .header-center, .header-right {
            display: table-cell;
            vertical-align: top;
            width: 33.33%;
        }

        .company-info {
            text-align: right;
        }

        .company-name {
            font-size: 20px;
            font-weight: bold;
            color: #2563eb;
            margin-bottom: 8px;
        }

        .company-details {
            font-size: 11px;
            color: #666;
            line-height: 1.4;
        }

        .invoice-title {
            text-align: center;
        }

        .invoice-title h1 {
            font-size: 24px;
            color: #2563eb;
            margin-bottom: 5px;
        }

        .invoice-number {
            font-size: 16px;
            font-weight: bold;
            color: #333;
        }

        .qr-section {
            text-align: left;
        }

        .qr-code {
            margin-bottom: 8px;
        }

        .qr-text {
            font-size: 10px;
            color: #666;
            text-align: center;
        }

        .invoice-details {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }

        .customer-info, .invoice-info {
            display: table-cell;
            width: 48%;
            padding: 15px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            vertical-align: top;
        }

        .customer-info {
            margin-left: 4%;
        }

        .info-title {
            font-size: 14px;
            font-weight: bold;
            color: #2563eb;
            margin-bottom: 10px;
            border-bottom: 2px solid #2563eb;
            padding-bottom: 3px;
        }

        .info-row {
            margin-bottom: 6px;
            font-size: 11px;
        }

        .info-label {
            font-weight: bold;
            color: #374151;
            display: inline-block;
            width: 40%;
        }

        .info-value {
            color: #111827;
        }

        .status-section {
            text-align: center;
            margin: 15px 0;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            margin: 0 5px;
            border-radius: 15px;
            font-size: 11px;
            font-weight: bold;
        }

        .status-paid {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        .status-pending {
            background: #fef3c7;
            color: #92400e;
            border: 1px solid #fde68a;
        }

        .status-overdue {
            background: #fecaca;
            color: #991b1b;
            border: 1px solid #fca5a5;
        }

        .warehouse-info {
            background: #eff6ff;
            padding: 10px;
            text-align: center;
            margin: 15px 0;
            border: 1px solid #dbeafe;
            border-radius: 5px;
        }

        .warehouse-title {
            font-weight: bold;
            color: #1d4ed8;
            margin-bottom: 3px;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 11px;
        }

        .items-table th {
            background: #2563eb;
            color: white;
            padding: 10px 6px;
            text-align: center;
            font-weight: bold;
            border: 1px solid #1d4ed8;
        }

        .items-table td {
            padding: 8px 6px;
            text-align: center;
            border: 1px solid #d1d5db;
            vertical-align: middle;
        }

        .items-table tbody tr:nth-child(even) {
            background: #f9fafb;
        }

        .product-name {
            text-align: right;
            font-weight: bold;
        }

        .product-code {
            font-size: 10px;
            color: #6b7280;
        }

        .totals-section {
            display: table;
            width: 100%;
            margin-top: 20px;
        }

        .debt-info, .invoice-totals {
            display: table-cell;
            width: 48%;
            padding: 15px;
            vertical-align: top;
        }

        .debt-info {
            background: #fffbeb;
            border: 1px solid #fbbf24;
            margin-left: 4%;
        }

        .invoice-totals {
            background: #f0fdf4;
            border: 1px solid #22c55e;
        }

        .section-title {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 2px solid;
        }

        .debt-info .section-title {
            color: #92400e;
            border-color: #92400e;
        }

        .invoice-totals .section-title {
            color: #166534;
            border-color: #166534;
        }

        .total-row {
            display: table;
            width: 100%;
            margin-bottom: 5px;
            font-size: 11px;
        }

        .total-label, .total-value {
            display: table-cell;
            padding: 3px 0;
        }

        .total-label {
            text-align: right;
            width: 60%;
        }

        .total-value {
            text-align: left;
            font-weight: bold;
        }

        .total-row.final {
            border-top: 2px solid #166534;
            padding-top: 8px;
            margin-top: 8px;
            font-size: 13px;
            font-weight: bold;
        }

        .debt-row.total {
            border-top: 2px solid #92400e;
            padding-top: 8px;
            margin-top: 8px;
            font-weight: bold;
        }

        .notes-section {
            margin-top: 20px;
            padding: 15px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-right: 4px solid #2563eb;
        }

        .notes-title {
            font-weight: bold;
            color: #2563eb;
            margin-bottom: 8px;
        }


        .free-samples-section {
            margin-top: 20px;
            padding: 15px;
            background: #f0fff4;
            border: 1px solid #bbf7d0;
            border-right: 4px solid #10b981;
        }
        .free-samples-title { font-weight: bold; color: #0ea5e9; margin-bottom: 8px; }

        .footer {
            margin-top: 30px;
            text-align: center;
            padding-top: 15px;
            border-top: 2px solid #2563eb;
            color: #6b7280;
            font-size: 11px;
        }

        .footer-line {
            margin-bottom: 3px;
        }

        .highlight {
            background: #fef3c7;
            padding: 2px 4px;
            border-radius: 3px;
        }

        .currency {
            font-weight: bold;
            color: #2563eb;
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <!-- Header -->
        <div class="header">
            <div class="header-right">
                <div class="company-info">
                    <div class="company-name">{{ auth()->user()->tenant->name ?? 'اسم الشركة' }}</div>
                    <div class="company-details">
                        العنوان: بغداد - العراق<br>
                        الهاتف: +964 770 123 4567<br>
                        البريد: info@company.com<br>
                        الموقع: www.company.com
                    </div>
                </div>
            </div>

            <div class="header-center">
                <div class="invoice-title">
                    <h1>فاتورة مبيعات</h1>
                    <div class="invoice-number">رقم: {{ $invoice->invoice_number }}</div>
                </div>
            </div>

            <div class="header-left">
                <div class="qr-section">
                    <div class="qr-code">
                        {!! $qrCode !!}
                    </div>
                    <div class="qr-text">امسح للتفاصيل</div>
                </div>
            </div>
        </div>

        <!-- Status Badges -->
        <div class="status-section">
            <span class="status-badge status-{{ $invoice->payment_status == 'paid' ? 'paid' : ($invoice->payment_status == 'partial' ? 'pending' : 'overdue') }}">
                {{ $invoice->getPaymentStatusLabel() }}
            </span>
            <span class="status-badge status-{{ $invoice->getStatusColor() }}">
                {{ $invoice->getStatusLabel() }}
            </span>
        </div>

        <!-- Invoice Details -->
        <div class="invoice-details">
            <div class="customer-info">
                <div class="info-title">معلومات العميل</div>
                <div class="info-row">
                    <span class="info-label">اسم العميل:</span>
                    <span class="info-value">{{ $invoice->customer->name }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">رقم الهاتف:</span>
                    <span class="info-value">{{ $invoice->customer->phone ?? 'غير محدد' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">كود العميل:</span>
                    <span class="info-value">{{ $invoice->customer->customer_code ?? 'غير محدد' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">العنوان:</span>
                    <span class="info-value">{{ $invoice->customer->address ?? 'غير محدد' }}</span>
                </div>
            </div>


                <div class="info-row">
                    <span class="info-label">المديونية السابقة:</span>
                    <span class="info-value">{{ number_format((float)($invoice->previous_debt ?? 0), 2) }} د.ع</span>
                </div>
                <div class="info-row">
                    <span class="info-label">المديونية الحالية:</span>
                    <span class="info-value">{{ number_format((float)($invoice->current_debt ?? 0), 2) }} د.ع</span>
                </div>

            <div class="invoice-info">
                <div class="info-title">تفاصيل الفاتورة</div>
                <div class="info-row">
                    <span class="info-label">تاريخ الفاتورة:</span>
                    <span class="info-value">{{ $invoice->invoice_date->format('Y-m-d') }}</span>
                </div>
                @if($invoice->due_date)
                <div class="info-row">
                    <span class="info-label">تاريخ الاستحقاق:</span>
                    <span class="info-value">{{ $invoice->due_date->format('Y-m-d') }}</span>
                </div>
                @endif
                <div class="info-row">
                    <span class="info-label">مندوب المبيعات:</span>
                    <span class="info-value">{{ $invoice->salesRep->name ?? 'غير محدد' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">العملة:</span>
                    <span class="info-value currency">{{ $invoice->currency }}</span>
                </div>
            </div>
        </div>

        <!-- Warehouse Info -->
        <div class="warehouse-info">
            <div class="warehouse-title">المخزن المصدر</div>
            <div>{{ $invoice->warehouse->name }} - {{ $invoice->warehouse->location ?? 'الموقع غير محدد' }}</div>
        </div>

        <!-- Items Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 5%">#</th>
                    <th style="width: 30%">اسم المنتج</th>
                    <th style="width: 10%">الكمية</th>
                    <th style="width: 8%">الوحدة</th>
                    <th style="width: 12%">سعر الوحدة</th>
                    <th style="width: 10%">الخصم</th>
                    <th style="width: 10%">الضريبة</th>
                    <th style="width: 15%">الإجمالي</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->items as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="product-name">
                        <div>{{ $item->product_name }}</div>
                        @if($item->product_code)
                        <div class="product-code">كود: {{ $item->product_code }}</div>
                        @endif
                    </td>
                    <td>{{ number_format($item->quantity, 2) }}</td>
                    <td>{{ $item->unit }}</td>
                    <td>{{ number_format($item->selling_price, 2) }}</td>
                    <td>
                        @if($item->discount_percentage > 0)
                            {{ $item->discount_percentage }}%
                        @else
                            {{ number_format($item->discount_amount, 2) }}
                        @endif
                    </td>
                    <td>{{ $item->tax_percentage }}%</td>
                    <td class="highlight">{{ number_format($item->line_total, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totals Section -->
        <div class="totals-section">
            <!-- Debt Information -->
            <div class="debt-info">
                <div class="section-title">معلومات المديونية</div>
                <div class="total-row">
                    <div class="total-label">المديونية السابقة:</div>
                    <div class="total-value">{{ number_format($invoice->previous_debt, 2) }} د.ع</div>
                </div>
                <div class="total-row">
                    <div class="total-label">مبلغ هذه الفاتورة:</div>
                    <div class="total-value">{{ number_format($invoice->total_amount, 2) }} د.ع</div>
                </div>
                <div class="total-row debt-row total">
                    <div class="total-label">إجمالي المديونية:</div>
                    <div class="total-value">{{ number_format($invoice->current_debt, 2) }} د.ع</div>
                </div>
                <div class="total-row">
                    <div class="total-label">سقف المديونية:</div>
                    <div class="total-value">{{ number_format($invoice->credit_limit, 2) }} د.ع</div>
                </div>
            </div>

            <!-- Invoice Totals -->
            <div class="invoice-totals">
                <div class="section-title">إجماليات الفاتورة</div>
                <div class="total-row">
                    <div class="total-label">المجموع الفرعي:</div>
                    <div class="total-value">{{ number_format($invoice->subtotal, 2) }} د.ع</div>
                </div>
                @if($invoice->discount_amount > 0)
                <div class="total-row">
                    <div class="total-label">الخصم:</div>
                    <div class="total-value">-{{ number_format($invoice->discount_amount, 2) }} د.ع</div>
                </div>
                @endif
                @if($invoice->tax_amount > 0)
                <div class="total-row">
                    <div class="total-label">الضريبة:</div>
                    <div class="total-value">{{ number_format($invoice->tax_amount, 2) }} د.ع</div>
                </div>
                @endif
                <div class="total-row final">
                    <div class="total-label">المجموع الكلي:</div>
                    <div class="total-value">{{ number_format($invoice->total_amount, 2) }} د.ع</div>
                </div>
                <div class="total-row">
                    <div class="total-label">المبلغ المدفوع:</div>
                    <div class="total-value">{{ number_format($invoice->paid_amount, 2) }} د.ع</div>
                </div>
                <div class="total-row">
                    <div class="total-label">المبلغ المتبقي:</div>
                    <div class="total-value">{{ number_format($invoice->remaining_amount, 2) }} د.ع</div>
                </div>

        <!-- Free Samples -->
        @if($invoice->free_samples)
        <div class="free-samples-section">
            <div class="free-samples-title">العينات المجانية:</div>
            <div>{{ $invoice->free_samples }}</div>
            <div style="margin-top:6px;color:#059669;font-size:12px;">هذه العينات مجانية ولا تحتسب ضمن قيمة الفاتورة</div>
        </div>
        @endif

            </div>
        </div>

        <!-- Notes -->
        @if($invoice->notes)
        <div class="notes-section">
            <div class="notes-title">ملاحظات:</div>
            <div>{{ $invoice->notes }}</div>
        </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            <div class="footer-line">شكراً لتعاملكم معنا</div>
            <div class="footer-line">تم إنشاء هذه الفاتورة بتاريخ: {{ now()->format('Y-m-d H:i') }}</div>
            <div class="footer-line">نظام MaxCon للإدارة الصيدلانية</div>
        </div>
    </div>
</body>
</html>
