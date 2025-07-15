<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>فاتورة رقم {{ $invoice->invoice_number }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            direction: rtl;
            text-align: right;
            margin: 0;
            padding: 15px;
            background: #f8fafc;
            color: #2d3748;
            line-height: 1.6;
            font-size: 12px;
        }
        
        .invoice-container {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            max-width: 100%;
            margin: 0 auto;
        }
        
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-align: center;
            padding: 30px 20px;
        }
        
        .company-logo {
            width: 60px;
            height: 60px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            margin: 0 auto 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            font-weight: bold;
        }
        
        .company-name {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 8px;
        }
        
        .invoice-title {
            font-size: 18px;
            margin-bottom: 5px;
            opacity: 0.9;
        }
        
        .invoice-number {
            font-size: 14px;
            opacity: 0.8;
        }
        
        .content {
            padding: 25px;
        }
        
        .info-grid {
            width: 100%;
            margin-bottom: 25px;
        }
        
        .info-row-container {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        
        .info-column {
            display: table-cell;
            width: 48%;
            vertical-align: top;
            padding: 15px;
            background: #f8fafc;
            border-radius: 8px;
            margin: 0 1%;
        }
        
        .info-column h4 {
            color: #ed8936;
            font-size: 16px;
            font-weight: bold;
            margin: 0 0 12px 0;
            border-bottom: 2px solid #ed8936;
            padding-bottom: 5px;
        }
        
        .info-row {
            margin-bottom: 8px;
            display: table;
            width: 100%;
        }
        
        .info-label {
            font-weight: bold;
            color: #4a5568;
            display: table-cell;
            width: 40%;
            font-size: 11px;
        }
        
        .info-value {
            display: table-cell;
            color: #2d3748;
            font-weight: 500;
            font-size: 11px;
        }
        
        .items-section {
            margin-bottom: 25px;
        }
        
        .section-title {
            font-size: 18px;
            font-weight: bold;
            color: #2d3748;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 3px solid #ed8936;
        }
        
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        .items-table th {
            background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%);
            color: white;
            padding: 12px 8px;
            text-align: center;
            font-weight: bold;
            font-size: 12px;
        }
        
        .items-table td {
            padding: 10px 8px;
            border-bottom: 1px solid #e2e8f0;
            text-align: center;
            font-size: 11px;
        }
        
        .items-table tbody tr:nth-child(even) {
            background: #f7fafc;
        }
        
        .product-info {
            text-align: right;
            padding-right: 12px;
        }
        
        .product-name {
            font-weight: bold;
            color: #2d3748;
            font-size: 12px;
            margin-bottom: 3px;
        }
        
        .product-code {
            font-size: 10px;
            color: #718096;
            background: #edf2f7;
            padding: 2px 5px;
            border-radius: 6px;
            display: inline-block;
        }
        
        .totals-section {
            background: linear-gradient(135deg, #f7fafc 0%, #edf2f7 100%);
            padding: 20px;
            border-radius: 8px;
            margin-top: 15px;
            border: 2px solid #e2e8f0;
        }
        
        .totals-title {
            font-size: 16px;
            font-weight: bold;
            color: #2d3748;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 2px solid #ed8936;
        }
        
        .total-row {
            margin-bottom: 10px;
            display: table;
            width: 100%;
            padding-bottom: 6px;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .total-label {
            display: table-cell;
            font-weight: bold;
            color: #4a5568;
            width: 70%;
            font-size: 12px;
        }
        
        .total-value {
            display: table-cell;
            font-weight: bold;
            color: #2d3748;
            text-align: left;
            font-size: 12px;
        }
        
        .total-final {
            background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%);
            color: white;
            padding: 12px;
            font-weight: bold;
            text-align: center;
            border-radius: 6px;
            margin-top: 12px;
            font-size: 16px;
        }
        
        .qr-section {
            text-align: center;
            margin-top: 25px;
            padding: 15px;
            background: #e6fffa;
            border-radius: 8px;
            border: 2px solid #38b2ac;
        }
        
        .qr-section h4 {
            margin: 0 0 10px 0;
            color: #2d3748;
            font-size: 14px;
        }
        
        .qr-section p {
            margin: 8px 0 0 0;
            color: #4a5568;
            font-size: 11px;
        }
        
        .notes-section {
            background: #fff5f5;
            padding: 15px;
            border-radius: 8px;
            border-left: 5px solid #f56565;
            margin-top: 15px;
        }
        
        .notes-section h4 {
            margin: 0 0 8px 0;
            color: #2d3748;
            font-size: 14px;
        }
        
        .notes-section p {
            margin: 0;
            color: #4a5568;
            font-size: 11px;
        }
        
        .footer {
            text-align: center;
            margin-top: 25px;
            padding-top: 15px;
            border-top: 2px solid #e2e8f0;
            color: #718096;
            font-size: 10px;
        }
        
        /* PDF specific styles */
        @page {
            margin: 15mm;
            size: A4;
        }

        .page-break {
            page-break-before: always;
        }

        /* Ensure good printing */
        * {
            -webkit-print-color-adjust: exact !important;
            color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        .header {
            -webkit-print-color-adjust: exact;
            color-adjust: exact;
            print-color-adjust: exact;
        }

        .items-table th {
            -webkit-print-color-adjust: exact;
            color-adjust: exact;
            print-color-adjust: exact;
        }

        .total-final {
            -webkit-print-color-adjust: exact;
            color-adjust: exact;
            print-color-adjust: exact;
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <!-- Header -->
        <div class="header">
            <div class="company-logo">{{ substr($invoice->tenant->name ?? 'ماكس', 0, 2) }}</div>
            <div class="company-name">{{ $invoice->tenant->name ?? 'شركة ماكس كون' }}</div>
            <div class="invoice-title">فاتورة {{ $invoice->type === 'sales' ? 'مبيعات' : 'أولية' }}</div>
            <div class="invoice-number">رقم الفاتورة: {{ $invoice->invoice_number }}</div>
        </div>
        
        <div class="content">
            <!-- Invoice Info -->
            <div class="info-grid">
                <div class="info-row-container">
                    <div class="info-column">
                        <h4>معلومات العميل</h4>
                        <div class="info-row">
                            <span class="info-label">العميل:</span>
                            <span class="info-value">{{ $invoice->customer->name }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">الهاتف:</span>
                            <span class="info-value">{{ $invoice->customer->phone ?? 'غير محدد' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">البريد:</span>
                            <span class="info-value">{{ $invoice->customer->email ?? 'غير محدد' }}</span>
                        </div>
                        @if($invoice->customer->address)
                        <div class="info-row">
                            <span class="info-label">العنوان:</span>
                            <span class="info-value">{{ $invoice->customer->address }}</span>
                        </div>
                        @endif
                    </div>
                    <div class="info-column">
                        <h4>تفاصيل الفاتورة</h4>
                        <div class="info-row">
                            <span class="info-label">تاريخ الفاتورة:</span>
                            <span class="info-value">{{ $invoice->invoice_date->format('Y/m/d') }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">تاريخ الاستحقاق:</span>
                            <span class="info-value">{{ $invoice->due_date->format('Y/m/d') }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">العملة:</span>
                            <span class="info-value">{{ $invoice->currency }}</span>
                        </div>
                        @if($invoice->sales_representative)
                        <div class="info-row">
                            <span class="info-label">المندوب:</span>
                            <span class="info-value">{{ $invoice->sales_representative }}</span>
                        </div>
                        @endif
                        <div class="info-row">
                            <span class="info-label">الحالة:</span>
                            <span class="info-value">
                                @if($invoice->status === 'draft') مسودة
                                @elseif($invoice->status === 'sent') مرسلة
                                @elseif($invoice->status === 'paid') مدفوعة
                                @elseif($invoice->status === 'partial_paid') مدفوعة جزئياً
                                @elseif($invoice->status === 'overdue') متأخرة
                                @elseif($invoice->status === 'cancelled') ملغية
                                @else {{ $invoice->status }}
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Items Table -->
            <div class="items-section">
                <h3 class="section-title">عناصر الفاتورة</h3>
                
                <table class="items-table">
                    <thead>
                        <tr>
                            <th style="text-align: right; width: 35%;">المنتج</th>
                            <th style="width: 12%;">الكمية</th>
                            <th style="width: 18%;">سعر الوحدة</th>
                            <th style="width: 15%;">الخصم</th>
                            <th style="width: 20%;">الإجمالي</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoice->items as $item)
                        <tr>
                            <td class="product-info">
                                <div class="product-name">{{ $item->product_name }}</div>
                                <div class="product-code">{{ $item->product_code }}</div>
                                @if($item->notes)
                                    <div style="font-size: 10px; color: #718096; margin-top: 3px;">{{ $item->notes }}</div>
                                @endif
                            </td>
                            <td style="font-weight: 600;">{{ $item->quantity }}</td>
                            <td style="font-weight: 600;">{{ number_format($item->unit_price, 2) }} {{ $invoice->currency }}</td>
                            <td style="color: #e53e3e; font-weight: 600;">
                                @if($item->discount_amount > 0)
                                    {{ number_format($item->discount_amount, 2) }} {{ $invoice->currency }}
                                @else
                                    -
                                @endif
                            </td>
                            <td style="font-weight: 700; color: #2d3748;">{{ number_format($item->total_amount, 2) }} {{ $invoice->currency }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Totals -->
            <div class="totals-section">
                <h3 class="totals-title">إجماليات الفاتورة</h3>
                
                <div class="total-row">
                    <span class="total-label">المجموع الفرعي:</span>
                    <span class="total-value">{{ number_format($invoice->subtotal_amount, 2) }} {{ $invoice->currency }}</span>
                </div>
                
                @if($invoice->discount_amount > 0)
                <div class="total-row">
                    <span class="total-label">الخصم:</span>
                    <span class="total-value" style="color: #e53e3e;">{{ number_format($invoice->discount_amount, 2) }} {{ $invoice->currency }}</span>
                </div>
                @endif
                
                @if($invoice->shipping_cost > 0 || $invoice->additional_charges > 0)
                <div class="total-row">
                    <span class="total-label">الشحن والرسوم الإضافية:</span>
                    <span class="total-value">{{ number_format($invoice->shipping_cost + $invoice->additional_charges, 2) }} {{ $invoice->currency }}</span>
                </div>
                @endif
                
                <div class="total-row">
                    <span class="total-label">ضريبة القيمة المضافة (15%):</span>
                    <span class="total-value">{{ number_format($invoice->tax_amount, 2) }} {{ $invoice->currency }}</span>
                </div>
                
                @if($invoice->previous_balance > 0)
                <div class="total-row">
                    <span class="total-label">المديونية السابقة:</span>
                    <span class="total-value" style="color: #dc2626;">{{ number_format($invoice->previous_balance, 2) }} {{ $invoice->currency }}</span>
                </div>
                
                <div class="total-row">
                    <span class="total-label">إجمالي الفاتورة:</span>
                    <span class="total-value">{{ number_format($invoice->total_amount, 2) }} {{ $invoice->currency }}</span>
                </div>
                
                <div class="total-final">
                    إجمالي المديونية: {{ number_format($invoice->total_amount + $invoice->previous_balance, 2) }} {{ $invoice->currency }}
                </div>
                @else
                <div class="total-final">
                    الإجمالي النهائي: {{ number_format($invoice->total_amount, 2) }} {{ $invoice->currency }}
                </div>
                @endif
            </div>

            <!-- QR Code -->
            @if($invoice->qr_code)
            <div class="qr-section">
                <h4>رمز QR للتحقق من الفاتورة</h4>
                <img src="data:image/png;base64,{{ $invoice->qr_code }}" style="width: 120px; height: 120px;">
                <p>امسح الرمز للحصول على معلومات الفاتورة كاملة</p>
                <div style="font-size: 10px; color: #718096; margin-top: 8px;">
                    يحتوي على: اسم الشركة • رقم الفاتورة • معلومات العميل • المبالغ • المندوب
                </div>
            </div>
            @endif

            <!-- Notes -->
            @if($invoice->notes)
            <div class="notes-section">
                <h4>ملاحظات</h4>
                <p>{{ $invoice->notes }}</p>
            </div>
            @endif
            
            <!-- Payment Information -->
            @if($invoice->paid_amount > 0 || $invoice->remaining_amount > 0)
            <div style="background: #f0fff4; padding: 15px; border-radius: 8px; border-left: 5px solid #48bb78; margin-top: 15px;">
                <h4 style="margin: 0 0 10px 0; color: #2d3748; font-size: 14px;">معلومات الدفع</h4>
                <div style="display: table; width: 100%;">
                    <div style="display: table-row;">
                        <span style="display: table-cell; font-weight: bold; color: #4a5568; font-size: 11px; width: 30%;">المبلغ المدفوع:</span>
                        <span style="display: table-cell; color: #48bb78; font-weight: 600; font-size: 11px;">{{ number_format($invoice->paid_amount, 2) }} {{ $invoice->currency }}</span>
                    </div>
                    <div style="display: table-row;">
                        <span style="display: table-cell; font-weight: bold; color: #4a5568; font-size: 11px; padding-top: 5px;">المبلغ المتبقي:</span>
                        <span style="display: table-cell; color: #e53e3e; font-weight: 600; font-size: 11px; padding-top: 5px;">{{ number_format($invoice->remaining_amount, 2) }} {{ $invoice->currency }}</span>
                    </div>
                </div>
            </div>
            @endif

            <!-- Company Information -->
            @if($invoice->tenant->address || $invoice->tenant->phone || $invoice->tenant->email)
            <div style="background: #f7fafc; padding: 15px; border-radius: 8px; margin-top: 15px; border: 1px solid #e2e8f0;">
                <h4 style="margin: 0 0 10px 0; color: #2d3748; font-size: 14px;">معلومات الشركة</h4>
                @if($invoice->tenant->address)
                <div style="margin-bottom: 5px; font-size: 11px; color: #4a5568;">
                    <strong>العنوان:</strong> {{ $invoice->tenant->address }}
                </div>
                @endif
                @if($invoice->tenant->phone)
                <div style="margin-bottom: 5px; font-size: 11px; color: #4a5568;">
                    <strong>الهاتف:</strong> {{ $invoice->tenant->phone }}
                </div>
                @endif
                @if($invoice->tenant->email)
                <div style="margin-bottom: 5px; font-size: 11px; color: #4a5568;">
                    <strong>البريد الإلكتروني:</strong> {{ $invoice->tenant->email }}
                </div>
                @endif
            </div>
            @endif

            <div class="footer">
                تم إنشاء هذه الفاتورة إلكترونياً بواسطة نظام ماكس كون • {{ now()->format('Y/m/d H:i') }}
                <br>
                <small style="font-size: 9px; color: #a0aec0;">
                    هذه فاتورة إلكترونية معتمدة ولا تحتاج إلى توقيع أو ختم
                </small>
            </div>
        </div>
    </div>
</body>
</html>
