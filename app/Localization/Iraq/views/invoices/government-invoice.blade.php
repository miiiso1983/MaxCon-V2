<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>فاتورة حكومية - {{ $invoice->invoice_number }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&display=swap');
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Amiri', serif;
            font-size: 14px;
            line-height: 1.6;
            color: #333;
            background: #fff;
        }
        
        .invoice-container {
            max-width: 210mm;
            margin: 0 auto;
            padding: 20px;
            background: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        
        .header {
            border-bottom: 3px solid #2c5aa0;
            padding-bottom: 20px;
            margin-bottom: 30px;
            position: relative;
        }
        
        .header::after {
            content: '';
            position: absolute;
            bottom: -3px;
            left: 0;
            width: 100%;
            height: 1px;
            background: linear-gradient(to right, #2c5aa0, #1e3a8a);
        }
        
        .company-info {
            text-align: center;
            margin-bottom: 20px;
        }
        
        .company-name {
            font-size: 24px;
            font-weight: 700;
            color: #2c5aa0;
            margin-bottom: 5px;
        }
        
        .company-details {
            font-size: 12px;
            color: #666;
            line-height: 1.4;
        }
        
        .invoice-title {
            text-align: center;
            font-size: 20px;
            font-weight: 700;
            color: #1e3a8a;
            margin: 20px 0;
            padding: 10px;
            border: 2px solid #2c5aa0;
            border-radius: 8px;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        }
        
        .invoice-meta {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }
        
        .meta-section {
            padding: 15px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            background: #f8fafc;
        }
        
        .meta-title {
            font-weight: 700;
            color: #2c5aa0;
            margin-bottom: 10px;
            font-size: 16px;
            border-bottom: 1px solid #cbd5e1;
            padding-bottom: 5px;
        }
        
        .meta-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            padding: 3px 0;
        }
        
        .meta-label {
            font-weight: 600;
            color: #475569;
        }
        
        .meta-value {
            color: #1e293b;
            font-weight: 500;
        }
        
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 30px 0;
            border: 2px solid #2c5aa0;
            border-radius: 8px;
            overflow: hidden;
        }
        
        .items-table th {
            background: linear-gradient(135deg, #2c5aa0 0%, #1e3a8a 100%);
            color: white;
            padding: 12px 8px;
            text-align: center;
            font-weight: 700;
            font-size: 14px;
        }
        
        .items-table td {
            padding: 10px 8px;
            text-align: center;
            border-bottom: 1px solid #e2e8f0;
            vertical-align: middle;
        }
        
        .items-table tr:nth-child(even) {
            background: #f8fafc;
        }
        
        .items-table tr:hover {
            background: #e2e8f0;
        }
        
        .item-name {
            text-align: right !important;
            font-weight: 600;
            color: #1e293b;
        }
        
        .amount {
            font-weight: 600;
            color: #059669;
        }
        
        .totals-section {
            margin-top: 30px;
            display: grid;
            grid-template-columns: 1fr 400px;
            gap: 30px;
        }
        
        .totals-table {
            border: 2px solid #2c5aa0;
            border-radius: 8px;
            overflow: hidden;
        }
        
        .totals-table tr {
            border-bottom: 1px solid #e2e8f0;
        }
        
        .totals-table tr:last-child {
            border-bottom: none;
            background: linear-gradient(135deg, #2c5aa0 0%, #1e3a8a 100%);
            color: white;
            font-weight: 700;
            font-size: 16px;
        }
        
        .totals-table td {
            padding: 12px 15px;
        }
        
        .total-label {
            text-align: right;
            font-weight: 600;
            background: #f8fafc;
        }
        
        .total-value {
            text-align: left;
            font-weight: 600;
            color: #059669;
        }
        
        .government-info {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            border: 2px solid #f59e0b;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
        }
        
        .government-title {
            font-weight: 700;
            color: #92400e;
            margin-bottom: 10px;
            text-align: center;
        }
        
        .tax-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-top: 10px;
        }
        
        .qr-section {
            text-align: center;
            margin: 20px 0;
            padding: 15px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            background: #f8fafc;
        }
        
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #2c5aa0;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
        
        .signature-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 50px;
            margin: 30px 0;
        }
        
        .signature-box {
            text-align: center;
            padding: 20px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            background: #f8fafc;
        }
        
        .signature-line {
            border-bottom: 2px solid #2c5aa0;
            margin: 20px 0 10px 0;
            height: 40px;
        }
        
        .amount-words {
            background: #e0f2fe;
            border: 1px solid #0284c7;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
            text-align: center;
            font-weight: 600;
            color: #0c4a6e;
        }
        
        @media print {
            .invoice-container {
                box-shadow: none;
                margin: 0;
                padding: 15px;
            }
            
            body {
                background: white;
            }
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <!-- Header -->
        <div class="header">
            <div class="company-info">
                <div class="company-name">{{ $company->name_ar ?? $company->name }}</div>
                <div class="company-details">
                    {{ $company->address_ar ?? $company->address }}<br>
                    هاتف: {{ $company->phone }} | البريد الإلكتروني: {{ $company->email }}<br>
                    رقم التسجيل: {{ $company->registration_number }} | الرقم الضريبي: {{ $company->tax_id }}
                </div>
            </div>
            
            <div class="invoice-title">
                فاتورة ضريبية - Tax Invoice
            </div>
        </div>

        <!-- Invoice Meta Information -->
        <div class="invoice-meta">
            <div class="meta-section">
                <div class="meta-title">معلومات الفاتورة</div>
                <div class="meta-item">
                    <span class="meta-label">رقم الفاتورة:</span>
                    <span class="meta-value">{{ $invoice->invoice_number }}</span>
                </div>
                <div class="meta-item">
                    <span class="meta-label">تاريخ الإصدار:</span>
                    <span class="meta-value">{{ $invoice->created_at->format('Y/m/d') }}</span>
                </div>
                <div class="meta-item">
                    <span class="meta-label">تاريخ الاستحقاق:</span>
                    <span class="meta-value">{{ $invoice->due_date ? $invoice->due_date->format('Y/m/d') : 'فوري' }}</span>
                </div>
                <div class="meta-item">
                    <span class="meta-label">العملة:</span>
                    <span class="meta-value">دينار عراقي (IQD)</span>
                </div>
            </div>

            <div class="meta-section">
                <div class="meta-title">معلومات العميل</div>
                <div class="meta-item">
                    <span class="meta-label">اسم العميل:</span>
                    <span class="meta-value">{{ $invoice->customer->name }}</span>
                </div>
                <div class="meta-item">
                    <span class="meta-label">العنوان:</span>
                    <span class="meta-value">{{ $invoice->customer->address ?? 'غير محدد' }}</span>
                </div>
                <div class="meta-item">
                    <span class="meta-label">الهاتف:</span>
                    <span class="meta-value">{{ $invoice->customer->phone ?? 'غير محدد' }}</span>
                </div>
                @if($invoice->customer->tax_id)
                <div class="meta-item">
                    <span class="meta-label">الرقم الضريبي:</span>
                    <span class="meta-value">{{ $invoice->customer->tax_id }}</span>
                </div>
                @endif
            </div>
        </div>

        <!-- Government Tax Information -->
        <div class="government-info">
            <div class="government-title">معلومات ضريبية حكومية</div>
            <div class="tax-info">
                <div>
                    <strong>نوع الضريبة:</strong> ضريبة المبيعات<br>
                    <strong>معدل الضريبة:</strong> {{ $taxRate ?? '0' }}%
                </div>
                <div>
                    <strong>رقم التسجيل الضريبي:</strong> {{ $company->tax_registration ?? 'غير محدد' }}<br>
                    <strong>فترة الإقرار:</strong> {{ date('Y/m') }}
                </div>
            </div>
        </div>

        <!-- Items Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 5%">#</th>
                    <th style="width: 35%">اسم المنتج</th>
                    <th style="width: 10%">الكمية</th>
                    <th style="width: 15%">سعر الوحدة</th>
                    <th style="width: 10%">الخصم</th>
                    <th style="width: 15%">المجموع الفرعي</th>
                    <th style="width: 10%">الضريبة</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->items as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="item-name">{{ $item->product->name }}</td>
                    <td>{{ number_format($item->quantity) }}</td>
                    <td class="amount">{{ number_format($item->unit_price) }} د.ع</td>
                    <td class="amount">{{ number_format($item->discount_amount ?? 0) }} د.ع</td>
                    <td class="amount">{{ number_format($item->subtotal) }} د.ع</td>
                    <td class="amount">{{ number_format($item->tax_amount ?? 0) }} د.ع</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totals Section -->
        <div class="totals-section">
            <div class="qr-section">
                <h4>رمز الاستجابة السريعة</h4>
                @if(isset($qrCode))
                    {!! $qrCode !!}
                @else
                    <div style="width: 150px; height: 150px; border: 2px dashed #ccc; margin: 10px auto; display: flex; align-items: center; justify-content: center; color: #666;">
                        QR Code
                    </div>
                @endif
                <p style="font-size: 12px; margin-top: 10px;">
                    يحتوي على معلومات الفاتورة للتحقق الحكومي
                </p>
            </div>

            <table class="totals-table">
                <tr>
                    <td class="total-label">المجموع الفرعي:</td>
                    <td class="total-value">{{ number_format($invoice->subtotal) }} د.ع</td>
                </tr>
                <tr>
                    <td class="total-label">إجمالي الخصم:</td>
                    <td class="total-value">{{ number_format($invoice->discount_amount ?? 0) }} د.ع</td>
                </tr>
                <tr>
                    <td class="total-label">إجمالي الضريبة:</td>
                    <td class="total-value">{{ number_format($invoice->tax_amount ?? 0) }} د.ع</td>
                </tr>
                <tr>
                    <td class="total-label">المجموع الكلي:</td>
                    <td class="total-value">{{ number_format($invoice->total_amount) }} د.ع</td>
                </tr>
            </table>
        </div>

        <!-- Amount in Words -->
        <div class="amount-words">
            <strong>المبلغ بالكلمات:</strong>
            {{ app('iraq.currency')->toWords($invoice->total_amount) }}
        </div>

        <!-- Signature Section -->
        <div class="signature-section">
            <div class="signature-box">
                <div>توقيع المحاسب</div>
                <div class="signature-line"></div>
                <div>الاسم: _______________</div>
                <div>التاريخ: {{ date('Y/m/d') }}</div>
            </div>
            
            <div class="signature-box">
                <div>ختم الشركة</div>
                <div class="signature-line"></div>
                <div>مدير عام</div>
                <div>التاريخ: {{ date('Y/m/d') }}</div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>هذه فاتورة ضريبية صادرة وفقاً للقوانين العراقية النافذة</p>
            <p>This is a tax invoice issued in accordance with applicable Iraqi laws</p>
            <p style="margin-top: 10px; font-size: 11px;">
                تم إنشاء هذه الفاتورة إلكترونياً بواسطة نظام MaxCon ERP | Generated electronically by MaxCon ERP System
            </p>
        </div>
    </div>
</body>
</html>
