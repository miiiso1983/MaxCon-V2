<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>فاتورة رقم {{ $invoice->invoice_number }}</title>
    <style>
        body {
            font-family: 'dejavusans', Arial, sans-serif;
            direction: rtl;
            text-align: right;
            margin: 0;
            padding: 0;
            color: #2d3748;
            line-height: 1.6;
            font-size: 12px;
        }
        
        .invoice-container {
            background: white;
            margin: 0;
            padding: 0;
        }
        
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-align: center;
            padding: 25px 20px;
            margin-bottom: 20px;
        }
        
        .company-logo {
            width: 50px;
            height: 50px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            margin: 0 auto 15px;
            display: inline-block;
            line-height: 50px;
            font-size: 20px;
            font-weight: bold;
        }
        
        .company-name {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 8px;
        }
        
        .invoice-title {
            font-size: 16px;
            margin-bottom: 5px;
            opacity: 0.9;
        }
        
        .invoice-number {
            font-size: 14px;
            opacity: 0.8;
        }
        
        .content {
            padding: 0 15px;
        }
        
        .info-section {
            margin-bottom: 20px;
        }
        
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        .info-column {
            width: 48%;
            vertical-align: top;
            padding: 15px;
            background: #f8fafc;
            border-radius: 8px;
        }
        
        .info-column h4 {
            color: #ed8936;
            font-size: 14px;
            font-weight: bold;
            margin: 0 0 10px 0;
            border-bottom: 2px solid #ed8936;
            padding-bottom: 5px;
        }
        
        .info-row {
            margin-bottom: 6px;
            font-size: 11px;
        }
        
        .info-label {
            font-weight: bold;
            color: #4a5568;
            display: inline-block;
            width: 35%;
        }
        
        .info-value {
            color: #2d3748;
            font-weight: 500;
        }
        
        .items-section {
            margin-bottom: 20px;
        }
        
        .section-title {
            font-size: 16px;
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
            border: 1px solid #e2e8f0;
        }
        
        .items-table th {
            background: #ed8936;
            color: white;
            padding: 10px 6px;
            text-align: center;
            font-weight: bold;
            font-size: 11px;
            border: 1px solid #dd6b20;
        }
        
        .items-table td {
            padding: 8px 6px;
            border: 1px solid #e2e8f0;
            text-align: center;
            font-size: 10px;
        }
        
        .items-table tbody tr:nth-child(even) {
            background: #f7fafc;
        }
        
        .product-info {
            text-align: right;
            padding-right: 10px;
        }
        
        .product-name {
            font-weight: bold;
            color: #2d3748;
            font-size: 11px;
            margin-bottom: 2px;
        }
        
        .product-code {
            font-size: 9px;
            color: #718096;
            background: #edf2f7;
            padding: 1px 4px;
            border-radius: 4px;
            display: inline-block;
        }
        
        .totals-section {
            background: #f7fafc;
            padding: 15px;
            border-radius: 8px;
            margin-top: 15px;
            border: 1px solid #e2e8f0;
        }
        
        .totals-title {
            font-size: 14px;
            font-weight: bold;
            color: #2d3748;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 2px solid #ed8936;
        }
        
        .total-row {
            margin-bottom: 8px;
            font-size: 11px;
        }
        
        .total-label {
            font-weight: bold;
            color: #4a5568;
            display: inline-block;
            width: 70%;
        }
        
        .total-value {
            font-weight: bold;
            color: #2d3748;
            float: left;
        }
        
        .total-final {
            background: #ed8936;
            color: white;
            padding: 10px;
            font-weight: bold;
            text-align: center;
            border-radius: 6px;
            margin-top: 10px;
            font-size: 14px;
            clear: both;
        }
        
        .qr-section {
            text-align: center;
            margin-top: 20px;
            padding: 15px;
            background: #e6fffa;
            border-radius: 8px;
            border: 1px solid #38b2ac;
        }
        
        .qr-section h4 {
            margin: 0 0 10px 0;
            color: #2d3748;
            font-size: 12px;
        }
        
        .notes-section {
            background: #fff5f5;
            padding: 15px;
            border-radius: 8px;
            border-right: 4px solid #f56565;
            margin-top: 15px;
        }
        
        .notes-section h4 {
            margin: 0 0 8px 0;
            color: #2d3748;
            font-size: 12px;
        }
        
        .footer {
            text-align: center;
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid #e2e8f0;
            color: #718096;
            font-size: 9px;
        }
        
        .clearfix::after {
            content: "";
            display: table;
            clear: both;
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
            <div class="info-section">
                <table class="info-table">
                    <tr>
                        <td class="info-column">
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
                        </td>
                        <td style="width: 4%;"></td>
                        <td class="info-column">
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
                                <span class="info-value">دينار عراقي</span>
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
                        </td>
                    </tr>
                </table>
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
                                    <div style="font-size: 9px; color: #718096; margin-top: 2px;">{{ $item->notes }}</div>
                                @endif
                            </td>
                            <td style="font-weight: 600;">{{ $item->quantity }}</td>
                            <td style="font-weight: 600;">{{ number_format($item->unit_price, 2) }}</td>
                            <td style="color: #e53e3e; font-weight: 600;">
                                @if($item->discount_amount > 0)
                                    {{ number_format($item->discount_amount, 2) }}
                                @else
                                    -
                                @endif
                            </td>
                            <td style="font-weight: 700; color: #2d3748;">{{ number_format($item->total_amount, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Totals -->
            <div class="totals-section">
                <h3 class="totals-title">إجماليات الفاتورة</h3>
                
                <div class="total-row clearfix">
                    <span class="total-label">المجموع الفرعي:</span>
                    <span class="total-value">{{ number_format($invoice->subtotal_amount, 2) }} دينار عراقي</span>
                </div>
                
                @if($invoice->discount_amount > 0)
                <div class="total-row clearfix">
                    <span class="total-label">الخصم:</span>
                    <span class="total-value" style="color: #e53e3e;">{{ number_format($invoice->discount_amount, 2) }} دينار عراقي</span>
                </div>
                @endif
                
                @if($invoice->shipping_cost > 0 || $invoice->additional_charges > 0)
                <div class="total-row clearfix">
                    <span class="total-label">الشحن والرسوم الإضافية:</span>
                    <span class="total-value">{{ number_format($invoice->shipping_cost + $invoice->additional_charges, 2) }} دينار عراقي</span>
                </div>
                @endif
                
                <div class="total-row clearfix">
                    <span class="total-label">ضريبة القيمة المضافة (15%):</span>
                    <span class="total-value">{{ number_format($invoice->tax_amount, 2) }} دينار عراقي</span>
                </div>
                
                @if($invoice->previous_balance > 0)
                <div class="total-row clearfix">
                    <span class="total-label">المديونية السابقة:</span>
                    <span class="total-value" style="color: #dc2626;">{{ number_format($invoice->previous_balance, 2) }} دينار عراقي</span>
                </div>
                
                <div class="total-row clearfix">
                    <span class="total-label">إجمالي الفاتورة:</span>
                    <span class="total-value">{{ number_format($invoice->total_amount, 2) }} دينار عراقي</span>
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

            <!-- QR Code -->
            @if($invoice->qr_code)
            <div class="qr-section">
                <h4>رمز QR للتحقق من الفاتورة</h4>
                <img src="data:image/png;base64,{{ $invoice->qr_code }}" style="width: 100px; height: 100px;">
                <div style="font-size: 10px; color: #4a5568; margin-top: 8px;">
                    امسح الرمز للحصول على معلومات الفاتورة كاملة
                </div>
            </div>
            @endif

            <!-- Notes -->
            @if($invoice->notes)
            <div class="notes-section">
                <h4>ملاحظات</h4>
                <div style="font-size: 11px; color: #4a5568;">{{ $invoice->notes }}</div>
            </div>
            @endif
            
            <!-- Payment Information -->
            @if($invoice->paid_amount > 0 || $invoice->remaining_amount > 0)
            <div style="background: #f0fff4; padding: 15px; border-radius: 8px; border-right: 4px solid #48bb78; margin-top: 15px;">
                <h4 style="margin: 0 0 10px 0; color: #2d3748; font-size: 12px;">معلومات الدفع</h4>
                <div style="font-size: 11px;">
                    <div style="margin-bottom: 5px;">
                        <span style="font-weight: bold; color: #4a5568;">المبلغ المدفوع:</span>
                        <span style="color: #48bb78; font-weight: 600;">{{ number_format($invoice->paid_amount, 2) }} دينار عراقي</span>
                    </div>
                    <div>
                        <span style="font-weight: bold; color: #4a5568;">المبلغ المتبقي:</span>
                        <span style="color: #e53e3e; font-weight: 600;">{{ number_format($invoice->remaining_amount, 2) }} دينار عراقي</span>
                    </div>
                </div>
            </div>
            @endif

            <div class="footer">
                تم إنشاء هذه الفاتورة إلكترونياً بواسطة نظام ماكس كون • {{ now()->format('Y/m/d H:i') }}
                <br>
                هذه فاتورة إلكترونية معتمدة ولا تحتاج إلى توقيع أو ختم
            </div>
        </div>
    </div>
</body>
</html>
