<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>فاتورة حرارية - {{ $invoice->invoice_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            line-height: 1.4;
            color: #000;
            direction: rtl;
            width: 80mm;
            margin: 0 auto;
            padding: 5mm;
        }

        .thermal-container {
            width: 100%;
            max-width: 70mm;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
            border-bottom: 1px dashed #000;
            padding-bottom: 8px;
        }

        .company-name {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 3px;
        }

        .company-info {
            font-size: 10px;
            line-height: 1.2;
        }

        .invoice-title {
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            margin: 8px 0;
            border-top: 1px dashed #000;
            border-bottom: 1px dashed #000;
            padding: 5px 0;
        }

        .invoice-details {
            margin-bottom: 10px;
            font-size: 11px;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2px;
        }

        .qr-section {
            text-align: center;
            margin: 10px 0;
            border: 1px dashed #000;
            padding: 8px;
        }

        .qr-code {
            margin-bottom: 5px;
        }

        .qr-text {
            font-size: 9px;
        }

        .items-section {
            margin: 10px 0;
        }

        .items-header {
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
            padding: 3px 0;
            font-weight: bold;
            font-size: 10px;
            text-align: center;
        }

        .item-row {
            padding: 3px 0;
            border-bottom: 1px dotted #ccc;
            font-size: 10px;
        }

        .item-name {
            font-weight: bold;
            margin-bottom: 1px;
        }

        .item-details {
            display: flex;
            justify-content: space-between;
            font-size: 9px;
        }

        .totals-section {
            margin-top: 10px;
            border-top: 1px solid #000;
            padding-top: 5px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2px;
            font-size: 11px;
        }

        .total-row.final {
            font-weight: bold;
            font-size: 12px;
            border-top: 1px solid #000;
            padding-top: 3px;
            margin-top: 3px;
        }

        .debt-section {
            margin: 8px 0;
            border: 1px dashed #000;
            padding: 5px;
            background: #f9f9f9;
        }

        .debt-title {
            font-weight: bold;
            text-align: center;
            margin-bottom: 3px;
            font-size: 10px;
        }

        .debt-row {
            display: flex;
            justify-content: space-between;
            font-size: 9px;
            margin-bottom: 1px;
        }

        .footer {
            text-align: center;
            margin-top: 10px;
            border-top: 1px dashed #000;
            padding-top: 5px;
            font-size: 9px;
        }

        .status-badges {
            text-align: center;
            margin: 5px 0;
        }

        .status-badge {
            display: inline-block;
            padding: 2px 6px;
            border: 1px solid #000;
            margin: 0 2px;
            font-size: 9px;
            font-weight: bold;
        }

        .warehouse-info {
            text-align: center;
            margin: 5px 0;
            font-size: 10px;
            border: 1px dotted #000;
            padding: 3px;
        }

        @media print {
            body {
                margin: 0;
                padding: 2mm;
            }
            
            .thermal-container {
                max-width: none;
            }
            
            .no-print {
                display: none !important;
            }
        }

        .separator {
            text-align: center;
            margin: 5px 0;
            font-size: 14px;
        }

        .notes-section {
            margin: 8px 0;
            font-size: 9px;
            border: 1px dotted #000;
            padding: 3px;
        }

        .notes-title {
            font-weight: bold;
            margin-bottom: 2px;
        }
    </style>
</head>
<body>
    <div class="thermal-container">
        <!-- Header -->
        <div class="header">
            <div class="company-name">{{ auth()->user()->tenant->name ?? 'اسم الشركة' }}</div>
            <div class="company-info">
                بغداد - العراق<br>
                +964 770 123 4567<br>
                info@company.com
            </div>
        </div>

        <!-- Invoice Title -->
        <div class="invoice-title">
            فاتورة مبيعات<br>
            {{ $invoice->invoice_number }}
        </div>

        <!-- Invoice Details -->
        <div class="invoice-details">
            <div class="detail-row">
                <span>التاريخ:</span>
                <span>{{ $invoice->invoice_date->format('Y-m-d') }}</span>
            </div>
            <div class="detail-row">
                <span>الوقت:</span>
                <span>{{ $invoice->created_at->format('H:i') }}</span>
            </div>
            <div class="detail-row">
                <span>العميل:</span>
                <span>{{ $invoice->customer->name }}</span>
            </div>
            @if($invoice->customer->phone)
            <div class="detail-row">
                <span>الهاتف:</span>
                <span>{{ $invoice->customer->phone }}</span>
            </div>
            @endif
            <div class="detail-row">
                <span>المندوب:</span>
                <span>{{ $invoice->salesRep->name ?? 'غير محدد' }}</span>
            </div>
        </div>

        <!-- Warehouse Info -->
        <div class="warehouse-info">
            المخزن: {{ $invoice->warehouse->name }}
        </div>

        <!-- Status Badges -->
        <div class="status-badges">
            <span class="status-badge">{{ $invoice->getPaymentStatusLabel() }}</span>
            <span class="status-badge">{{ $invoice->getStatusLabel() }}</span>
        </div>

        <!-- QR Code -->
        <div class="qr-section">
            <div class="qr-code">
                {!! QrCode::size(60)->generate(json_encode($invoice->qr_code_data)) !!}
            </div>
            <div class="qr-text">امسح للتفاصيل</div>
        </div>

        <!-- Items -->
        <div class="items-section">
            <div class="items-header">
                المنتجات
            </div>
            
            @foreach($invoice->items as $index => $item)
            <div class="item-row">
                <div class="item-name">{{ $index + 1 }}. {{ $item->product_name }}</div>
                <div class="item-details">
                    <span>{{ number_format($item->quantity, 1) }} {{ $item->unit }}</span>
                    <span>{{ number_format($item->selling_price, 2) }} د.ع</span>
                    <span>{{ number_format($item->line_total, 2) }} د.ع</span>
                </div>
                @if($item->discount_percentage > 0 || $item->discount_amount > 0)
                <div class="item-details" style="font-size: 8px; color: #666;">
                    <span>خصم: 
                        @if($item->discount_percentage > 0)
                            {{ $item->discount_percentage }}%
                        @else
                            {{ number_format($item->discount_amount, 2) }} د.ع
                        @endif
                    </span>
                </div>
                @endif
            </div>
            @endforeach
        </div>

        <!-- Totals -->
        <div class="totals-section">
            <div class="total-row">
                <span>المجموع الفرعي:</span>
                <span>{{ number_format($invoice->subtotal, 2) }} د.ع</span>
            </div>
            
            @if($invoice->discount_amount > 0)
            <div class="total-row">
                <span>الخصم:</span>
                <span>-{{ number_format($invoice->discount_amount, 2) }} د.ع</span>
            </div>
            @endif
            
            @if($invoice->tax_amount > 0)
            <div class="total-row">
                <span>الضريبة ({{ $invoice->tax_percentage }}%):</span>
                <span>{{ number_format($invoice->tax_amount, 2) }} د.ع</span>
            </div>
            @endif
            
            <div class="total-row final">
                <span>المجموع الكلي:</span>
                <span>{{ number_format($invoice->total_amount, 2) }} د.ع</span>
            </div>
            
            <div class="total-row">
                <span>المدفوع:</span>
                <span>{{ number_format($invoice->paid_amount, 2) }} د.ع</span>
            </div>
            
            <div class="total-row">
                <span>المتبقي:</span>
                <span>{{ number_format($invoice->remaining_amount, 2) }} د.ع</span>
            </div>
        </div>

        <!-- Debt Information -->
        <div class="debt-section">
            <div class="debt-title">معلومات المديونية</div>
            <div class="debt-row">
                <span>المديونية السابقة:</span>
                <span>{{ number_format($invoice->previous_debt, 2) }} د.ع</span>
            </div>
            <div class="debt-row">
                <span>إجمالي المديونية:</span>
                <span>{{ number_format($invoice->current_debt, 2) }} د.ع</span>
            </div>
            <div class="debt-row">
                <span>سقف المديونية:</span>
                <span>{{ number_format($invoice->credit_limit, 2) }} د.ع</span>
            </div>
        </div>

        <!-- Notes -->
        @if($invoice->notes)
        <div class="notes-section">
            <div class="notes-title">ملاحظات:</div>
            <div>{{ $invoice->notes }}</div>
        </div>
        @endif

        <!-- Separator -->
        <div class="separator">
            ═══════════════════════
        </div>

        <!-- Footer -->
        <div class="footer">
            <div>شكراً لتعاملكم معنا</div>
            <div>{{ now()->format('Y-m-d H:i') }}</div>
            <div>نظام MaxCon الصيدلاني</div>
        </div>

        <!-- Cut Line -->
        <div class="separator">
            ┄┄┄┄┄┄┄┄┄┄┄┄┄┄┄┄┄┄┄┄┄┄┄
        </div>
    </div>

    <script>
        // Auto print when page loads
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
