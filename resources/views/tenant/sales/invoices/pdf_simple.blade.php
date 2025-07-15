<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>فاتورة رقم {{ $invoice->invoice_number }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap');
        
        body {
            font-family: 'Cairo', 'DejaVu Sans', Arial, sans-serif;
            direction: rtl;
            text-align: right;
            margin: 0;
            padding: 20px;
            background: #f8fafc;
            color: #2d3748;
            line-height: 1.6;
        }
        
        .invoice-container {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            max-width: 800px;
            margin: 0 auto;
        }
        
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-align: center;
            padding: 40px 20px;
        }
        
        .company-name {
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        .invoice-title {
            font-size: 20px;
            margin-bottom: 5px;
            opacity: 0.9;
        }
        
        .invoice-number {
            font-size: 16px;
            opacity: 0.8;
        }
        
        .content {
            padding: 30px;
        }
        
        .info-grid {
            display: table;
            width: 100%;
            margin-bottom: 30px;
        }
        
        .info-column {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding: 20px;
            background: #f8fafc;
            border-radius: 10px;
        }
        
        .info-column:first-child {
            margin-left: 10px;
        }
        
        .info-column h4 {
            color: #ed8936;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 15px;
            border-bottom: 2px solid #ed8936;
            padding-bottom: 5px;
        }
        
        .info-row {
            margin-bottom: 12px;
            display: table;
            width: 100%;
        }
        
        .info-label {
            font-weight: bold;
            color: #4a5568;
            display: table-cell;
            width: 40%;
        }
        
        .info-value {
            display: table-cell;
            color: #2d3748;
            font-weight: 500;
        }
        
        .items-section {
            margin-bottom: 30px;
        }
        
        .section-title {
            font-size: 22px;
            font-weight: bold;
            color: #2d3748;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 3px solid #ed8936;
        }
        
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        .items-table th {
            background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%);
            color: white;
            padding: 15px 10px;
            text-align: center;
            font-weight: bold;
            font-size: 14px;
        }
        
        .items-table td {
            padding: 12px 10px;
            border-bottom: 1px solid #e2e8f0;
            text-align: center;
        }
        
        .items-table tbody tr:nth-child(even) {
            background: #f7fafc;
        }
        
        .product-info {
            text-align: right;
            padding-right: 15px;
        }
        
        .product-name {
            font-weight: bold;
            color: #2d3748;
            font-size: 14px;
            margin-bottom: 3px;
        }
        
        .product-code {
            font-size: 11px;
            color: #718096;
            background: #edf2f7;
            padding: 2px 6px;
            border-radius: 8px;
            display: inline-block;
        }
        
        .totals-section {
            background: linear-gradient(135deg, #f7fafc 0%, #edf2f7 100%);
            padding: 25px;
            border-radius: 10px;
            margin-top: 20px;
            border: 2px solid #e2e8f0;
        }
        
        .total-row {
            margin-bottom: 12px;
            display: table;
            width: 100%;
            padding-bottom: 8px;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .total-label {
            display: table-cell;
            font-weight: bold;
            color: #4a5568;
            width: 70%;
        }
        
        .total-value {
            display: table-cell;
            font-weight: bold;
            color: #2d3748;
            text-align: left;
        }
        
        .total-final {
            background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%);
            color: white;
            padding: 15px;
            font-weight: bold;
            text-align: center;
            border-radius: 8px;
            margin-top: 15px;
            font-size: 18px;
        }
        
        .qr-section {
            text-align: center;
            margin-top: 30px;
            padding: 20px;
            background: #e6fffa;
            border-radius: 10px;
            border: 2px solid #38b2ac;
        }
        
        .notes-section {
            background: #fff5f5;
            padding: 20px;
            border-radius: 10px;
            border-left: 5px solid #f56565;
            margin-top: 20px;
        }

        .free-samples-section {
            background: #f0fff4;
            padding: 20px;
            border-radius: 10px;
            border-left: 5px solid #10b981;
            margin-top: 20px;
        }
        
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #e2e8f0;
            color: #718096;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <!-- Header -->
        <div class="header">
            <div class="company-name">{{ $invoice->tenant->name ?? 'شركة ماكس كون' }}</div>
            <div class="invoice-title">فاتورة {{ $invoice->type === 'sales' ? 'مبيعات' : 'أولية' }}</div>
            <div class="invoice-number">رقم الفاتورة: {{ $invoice->invoice_number }}</div>
        </div>
        
        <div class="content">
            <!-- Invoice Info -->
            <div class="info-grid">
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
                </div>
            </div>

            <!-- Items Table -->
            <div class="items-section">
                <h3 class="section-title">عناصر الفاتورة</h3>
                
                <table class="items-table">
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
                            </td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ number_format($item->unit_price, 2) }} {{ $invoice->currency }}</td>
                            <td>{{ number_format($item->discount_amount, 2) }} {{ $invoice->currency }}</td>
                            <td>{{ number_format($item->total_amount, 2) }} {{ $invoice->currency }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Totals -->
            <div class="totals-section">
                <h3 class="section-title">إجماليات الفاتورة</h3>
                
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
                    <span class="total-label">الشحن والرسوم:</span>
                    <span class="total-value">{{ number_format($invoice->shipping_cost + $invoice->additional_charges, 2) }} {{ $invoice->currency }}</span>
                </div>
                @endif
                
                <div class="total-row">
                    <span class="total-label">ضريبة القيمة المضافة:</span>
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
            @php
                $qrCode = $invoice->qr_code;
                // Debug: Check if QR code exists
                if (empty($qrCode)) {
                    $qrCode = $invoice->generateQrCode();
                }
            @endphp

            @if($qrCode)
            <div class="qr-section">
                <h4 style="margin: 0 0 15px 0; color: #2d3748;">رمز QR للتحقق من الفاتورة</h4>
                @if(str_contains(base64_decode($qrCode), '<svg'))
                    <!-- SVG QR Code -->
                    <div style="width: 150px; height: 150px; border: 1px solid #ddd; margin: 0 auto; display: flex; align-items: center; justify-content: center;">
                        {!! base64_decode($qrCode) !!}
                    </div>
                @else
                    <!-- PNG QR Code -->
                    <img src="data:image/png;base64,{{ $qrCode }}" style="width: 150px; height: 150px; border: 1px solid #ddd;">
                @endif
                <p style="margin: 10px 0 0 0; color: #4a5568; font-size: 14px;">
                    امسح الرمز للحصول على معلومات الفاتورة كاملة
                </p>
                <div style="font-size: 12px; color: #718096; margin-top: 8px;">
                    يحتوي على: اسم الشركة • رقم الفاتورة • معلومات العميل • المبالغ • المندوب
                </div>
            </div>
            @else
            <div class="qr-section" style="background: #fff5f5; border: 1px solid #f56565;">
                <h4 style="margin: 0 0 15px 0; color: #e53e3e;">خطأ في إنشاء رمز QR</h4>
                <p style="margin: 0; color: #e53e3e; font-size: 14px;">
                    لم يتم إنشاء رمز QR لهذه الفاتورة
                </p>
            </div>
            @endif

            <!-- Notes -->
            @if($invoice->notes)
            <div class="notes-section">
                <h4 style="margin: 0 0 10px 0; color: #2d3748;">ملاحظات</h4>
                <p style="margin: 0; color: #4a5568;">{{ $invoice->notes }}</p>
            </div>
            @endif

            <!-- Free Samples -->
            @if($invoice->free_samples)
            <div class="free-samples-section">
                <h4 style="margin: 0 0 10px 0; color: #2d3748; display: flex; align-items: center; gap: 8px;">
                    <span style="color: #10b981;">🎁</span>
                    العينات المجانية المرفقة
                </h4>
                <div style="background: white; padding: 15px; border-radius: 8px; border: 1px solid #d1fae5;">
                    <p style="margin: 0; color: #4a5568; line-height: 1.6; white-space: pre-line;">{{ $invoice->free_samples }}</p>
                </div>
                <div style="margin-top: 8px; font-size: 12px; color: #059669;">
                    ℹ️ هذه العينات مجانية ولا تحتسب ضمن قيمة الفاتورة
                </div>
            </div>
            @endif
            
            <div class="footer">
                تم إنشاء هذه الفاتورة إلكترونياً بواسطة نظام ماكس كون
            </div>
        </div>
    </div>

    <!-- Print and Action Buttons -->
    <div id="action-buttons" style="text-align: center; margin: 20px 0; padding: 20px; background: white; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); max-width: 800px; margin: 20px auto;">
        <button onclick="window.print()"
                style="background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%); color: white; padding: 12px 24px; border: none; border-radius: 8px; font-size: 16px; font-weight: 600; cursor: pointer; margin: 0 10px; transition: all 0.3s ease;"
                onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 12px rgba(66, 153, 225, 0.4)';"
                onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
            🖨️ طباعة الفاتورة
        </button>

        <button onclick="downloadPDF()"
                style="background: linear-gradient(135deg, #e53e3e 0%, #c53030 100%); color: white; padding: 12px 24px; border: none; border-radius: 8px; font-size: 16px; font-weight: 600; cursor: pointer; margin: 0 10px; transition: all 0.3s ease;"
                onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 12px rgba(229, 62, 62, 0.4)';"
                onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
            📥 تحميل PDF
        </button>

        <a href="{{ route('tenant.sales.invoices.index') }}"
           style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-size: 16px; font-weight: 600; margin: 0 10px; display: inline-block; transition: all 0.3s ease;"
           onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 12px rgba(72, 187, 120, 0.4)';"
           onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
            ↩️ العودة للقائمة
        </a>
    </div>

    <script>
    function downloadPDF() {
        // Create a form to submit for PDF download
        const form = document.createElement('form');
        form.method = 'GET';
        form.action = '{{ route("tenant.sales.invoices.pdf", $invoice) }}';
        form.style.display = 'none';
        document.body.appendChild(form);
        form.submit();
        document.body.removeChild(form);
    }
    </script>

    <style>
        @media print {
            #action-buttons {
                display: none !important;
            }
            body {
                background: white !important;
                margin: 0 !important;
                padding: 0 !important;
            }
            .invoice-container {
                box-shadow: none !important;
                border-radius: 0 !important;
                margin: 0 !important;
            }
            .header {
                -webkit-print-color-adjust: exact !important;
                color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            .items-table th {
                -webkit-print-color-adjust: exact !important;
                color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            .total-final {
                -webkit-print-color-adjust: exact !important;
                color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
        }
    </style>
</body>
</html>
