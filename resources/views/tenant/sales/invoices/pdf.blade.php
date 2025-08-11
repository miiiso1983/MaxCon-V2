<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="utf-8">
    <title>فاتورة رقم {{ $invoice->invoice_number }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
            direction: rtl;
            text-align: right;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #ed8936;
            padding-bottom: 20px;
        }

        .company-name {
            font-size: 28px;
            font-weight: 700;
            color: #ed8936;
            margin-bottom: 10px;
        }

        .invoice-title {
            font-size: 24px;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 5px;
        }

        .invoice-number {
            font-size: 18px;
            color: #718096;
        }

        .invoice-info {
            display: table;
            width: 100%;
            margin-bottom: 30px;
        }

        .invoice-info-left,
        .invoice-info-right {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }

        .info-section {
            background: #f7fafc;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        .info-title {
            font-size: 16px;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 10px;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 5px;
        }

        .info-item {
            margin-bottom: 8px;
            font-size: 14px;
        }

        .info-label {
            font-weight: 600;
            color: #4a5568;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            border: 1px solid #e2e8f0;
        }

        .items-table th {
            background: #ed8936;
            color: white;
            padding: 12px 8px;
            font-weight: 600;
            border: 1px solid #dd6b20;
            text-align: center;
        }

        .items-table td {
            padding: 10px 8px;
            border: 1px solid #e2e8f0;
            text-align: center;
        }

        .items-table tr:nth-child(even) {
            background: #f7fafc;
        }

        .product-name {
            text-align: right;
            font-weight: 600;
        }

        .totals-section {
            float: left;
            width: 300px;
            margin-top: 20px;
        }

        .totals-table {
            width: 100%;
            border-collapse: collapse;
        }

        .totals-table td {
            padding: 8px 12px;
            border-bottom: 1px solid #e2e8f0;
        }

        .totals-label {
            font-weight: 600;
            color: #4a5568;
        }

        .totals-amount {
            text-align: left;
            font-weight: 600;
        }

        .total-final {
            background: #ed8936;
            color: white;
            font-weight: 700;
            font-size: 16px;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-align: center;
        }

        .status-draft {
            background: #fed7d7;
            color: #c53030;
        }

        .status-pending {
            background: #fef5e7;
            color: #d69e2e;
        }

        .status-paid {
            background: #c6f6d5;
            color: #38a169;
        }

        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
            color: #718096;
            border-top: 1px solid #e2e8f0;
            padding-top: 20px;
        }

        .qr-code {
            text-align: center;
            margin: 20px 0;
        }

        .notes {
            background: #f7fafc;
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
            border-right: 4px solid #6366f1;
        }

        .notes-title {
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 10px;
        }
    </style>
        .free-samples {
            background: #f0fff4;
            padding: 15px;
            border-radius: 8px;
            border-right: 4px solid #10b981;
            margin-top: 16px;
        }

</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="company-name">{{ $invoice->tenant->name ?? 'شركة ماكس كون' }}</div>
        <div class="invoice-title">فاتورة {{ $invoice->type === 'sales' ? 'مبيعات' : 'أولية' }}</div>
        <div class="invoice-number">رقم الفاتورة: {{ $invoice->invoice_number }}</div>
    </div>

    <!-- Invoice Information -->
    <div class="invoice-info">
        <div class="invoice-info-right">
            <!-- Customer Information -->
            <div class="info-section">
                <div class="info-title">معلومات العميل</div>
                <div class="info-item">
                    <span class="info-label">الاسم:</span> {{ $invoice->customer->name }}
                </div>
                <div class="info-item">
                    <span class="info-label">رقم العميل:</span> {{ $invoice->customer->customer_code }}
                </div>
                @if($invoice->customer->email)
                <div class="info-item">
                    <span class="info-label">البريد الإلكتروني:</span> {{ $invoice->customer->email }}
                </div>
                @endif
                @if($invoice->customer->phone)
                <div class="info-item">
                    <span class="info-label">الهاتف:</span> {{ $invoice->customer->phone }}
                </div>
                @endif
            </div>
        </div>

        <div class="invoice-info-left">
            <!-- Invoice Details -->
            <div class="info-section">
                <div class="info-title">تفاصيل الفاتورة</div>
                <div class="info-item">
                    <span class="info-label">تاريخ الفاتورة:</span> {{ $invoice->invoice_date->format('Y/m/d') }}
                </div>
                <div class="info-item">
                    <span class="info-label">تاريخ الاستحقاق:</span> {{ $invoice->due_date->format('Y/m/d') }}
                </div>
                <div class="info-item">
                    <span class="info-label">العملة:</span> {{ $invoice->currency }}
                </div>
                @if($invoice->sales_representative)
                <div class="info-item">
                    <span class="info-label">المندوب:</span> {{ $invoice->sales_representative }}
                </div>
                @endif
                <div class="info-item">
                    <span class="info-label">الحالة:</span>
                    @if($invoice->status === 'draft')
                        <span class="status-badge status-draft">مسودة</span>
                    @elseif($invoice->status === 'pending')
                        <span class="status-badge status-pending">في الانتظار</span>
                    @elseif($invoice->status === 'paid')
                        <span class="status-badge status-paid">مدفوعة</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Items Table -->
    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 40%;">المنتج</th>
                <th style="width: 10%;">الكمية</th>
                <th style="width: 15%;">سعر الوحدة</th>
                <th style="width: 15%;">الخصم</th>
                <th style="width: 20%;">الإجمالي</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->items as $item)
            <tr>
                    @if($invoice->previous_debt || $invoice->current_debt)
                    <div style="font-size: 11px; color: #4b5563; margin-top: 6px;">
                        <span>المديونية السابقة: {{ number_format((float)($invoice->previous_debt ?? 0), 2) }} د.ع</span>
                        <span style="margin-right: 12px;">المديونية الحالية: {{ number_format((float)($invoice->current_debt ?? 0), 2) }} د.ع</span>
                    </div>
                    @endif

                <td class="product-name">
                    <div style="font-weight: 600;">{{ $item->product_name }}</div>
                    <div style="font-size: 12px; color: #718096;">{{ $item->product_code }}</div>
                    @if($item->notes)
                        <div style="font-size: 11px; color: #a0aec0; margin-top: 3px;">{{ $item->notes }}</div>
                    @endif
                </td>
                <td>{{ number_format($item->quantity, 2) }}</td>
                <td>{{ number_format($item->unit_price, 2) }}</td>
                <td>
                    @if(($item->discount_type ?? '') === 'percentage' && ($item->discount_percentage ?? 0) > 0)
                        {{ rtrim(rtrim(number_format($item->discount_percentage, 2), '0'), '.') }}%
                    @elseif(($item->discount_amount ?? 0) > 0)
                        {{ number_format($item->discount_amount, 2) }}
                    @else
                        -
                    @endif
                </td>
                <td style="font-weight: 600;">{{ number_format($item->total_amount ?? ($item->quantity * $item->unit_price - ($item->discount_amount ?? 0) + ($item->tax_amount ?? 0)), 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Totals -->
    <div class="totals-section">
        <table class="totals-table">
            <tr>
                <td class="totals-label">المجموع الفرعي:</td>
                <td class="totals-amount">{{ number_format($invoice->subtotal_amount, 2) }} {{ $invoice->currency }}</td>
            </tr>
            @if($invoice->discount_amount > 0)
            <tr>
                <td class="totals-label">الخصم:</td>
                <td class="totals-amount">{{ number_format($invoice->discount_amount, 2) }} {{ $invoice->currency }}</td>
            </tr>
            @endif
            @if($invoice->shipping_cost > 0 || $invoice->additional_charges > 0)
            <tr>
                <td class="totals-label">الشحن والرسوم:</td>
                <td class="totals-amount">{{ number_format($invoice->shipping_cost + $invoice->additional_charges, 2) }} {{ $invoice->currency }}</td>
            </tr>
            @endif
            <tr>
                <td class="totals-label">ضريبة القيمة المضافة (15%):</td>
                <td class="totals-amount">{{ number_format($invoice->tax_amount, 2) }} {{ $invoice->currency }}</td>
            </tr>
            @if($invoice->previous_balance > 0)
            <tr>
                <td class="totals-label">المديونية السابقة:</td>
                <td class="totals-amount" style="color: #dc2626;">{{ number_format($invoice->previous_balance, 2) }} {{ $invoice->currency }}</td>
            </tr>
            <tr style="border-top: 2px solid #ed8936;">
                <td class="totals-label">إجمالي الفاتورة:</td>
                <td class="totals-amount">{{ number_format($invoice->total_amount, 2) }} {{ $invoice->currency }}</td>
            </tr>
            <tr class="total-final">
                <td class="totals-label">إجمالي المديونية:</td>
                <td class="totals-amount">{{ number_format($invoice->total_amount + $invoice->previous_balance, 2) }} {{ $invoice->currency }}</td>
            </tr>
            @else
            <tr class="total-final">
                <td class="totals-label">الإجمالي النهائي:</td>
                <td class="totals-amount">{{ number_format($invoice->total_amount, 2) }} {{ $invoice->currency }}</td>
            </tr>
            @endif
        </table>
    </div>

    <div style="clear: both;"></div>

    <!-- QR Codes Section -->
    <div style="display: table; width: 100%; margin-top: 30px;">
        <!-- Invoice QR Code -->
        @if($invoice->qr_code)
        <div style="display: table-cell; width: 50%; text-align: center; vertical-align: top;">
            <div style="background: #f7fafc; padding: 15px; border-radius: 8px; border: 1px solid #e2e8f0;">
                <div style="font-size: 14px; font-weight: 600; color: #2d3748; margin-bottom: 10px;">
                    🔍 رمز التحقق من الفاتورة
                </div>
                <img src="data:image/png;base64,{{ base64_encode($invoice->qr_code) }}" alt="QR Code" style="width: 80px; height: 80px; margin-bottom: 8px;">
                <div style="font-size: 10px; color: #718096;">للتحقق من صحة الفاتورة</div>
            </div>
        </div>
        @endif

        <!-- Products Catalog QR Code -->
        <div style="display: table-cell; width: 50%; text-align: center; vertical-align: top;">
            <div style="background: #f0fdf4; padding: 15px; border-radius: 8px; border: 1px solid #d1fae5;">
                <div style="font-size: 14px; font-weight: 600; color: #2d3748; margin-bottom: 10px;">
                    🛍️ جميع المنتجات المتوفرة
                </div>
                @if(isset($productsQRCode))
                    <img src="data:image/png;base64,{{ $productsQRCode }}" alt="Products QR" style="width: 80px; height: 80px; margin-bottom: 8px;">
                @else
                    <div style="width: 80px; height: 80px; margin: 0 auto 8px; background: #e5e7eb; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 24px; color: #6b7280;">
                        📦
                    </div>
                @endif
                <div style="font-size: 10px; color: #059669; line-height: 1.3;">
                    امسح لعرض جميع المنتجات<br>
                    مع الأسعار والتفاصيل
                </div>
                @if(isset($productsCount))
                    <div style="font-size: 9px; color: #6b7280; margin-top: 3px;">
                        {{ $productsCount }} منتج متوفر
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Free Samples --}}
    @if($invoice->free_samples)
    <div class="free-samples">
        <div class="notes-title">العينات المجانية:</div>
        <div>{{ $invoice->free_samples }}</div>
        <div style="margin-top:6px;color:#059669;font-size:12px;">هذه العينات مجانية ولا تحتسب ضمن قيمة الفاتورة</div>
    </div>
    @endif


    <!-- Notes -->
    @if($invoice->notes)
    <div class="notes">
        <div class="notes-title">ملاحظات:</div>
        <div>{{ $invoice->notes }}</div>
    </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <div>تم إنشاء هذه الفاتورة إلكترونياً بواسطة نظام ماكس كون</div>
        <div>تاريخ الطباعة: {{ now()->format('Y/m/d H:i') }}</div>
    </div>
</body>
</html>
