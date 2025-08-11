<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>فاتورة رقم {{ $invoice->invoice_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 14px;
            line-height: 1.6;
            color: #333;
            direction: rtl;
        }

        .invoice-container {
            max-width: 210mm;
            margin: 0 auto;
            padding: 20px;
            background: white;
        }

        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #007bff;
        }

        .company-info {
            flex: 1;
        }

        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 10px;
        }

        .company-details {
            color: #666;
            line-height: 1.4;
        }

        .invoice-title {
            text-align: center;
            flex: 1;
        }

        .invoice-title h1 {
            font-size: 28px;
            color: #007bff;
            margin-bottom: 5px;
        }

        .invoice-number {
            font-size: 18px;
            color: #333;
            font-weight: bold;
        }

        .qr-section {
            text-align: center;
            flex: 1;
        }

        .qr-code {
            margin-bottom: 10px;
        }

        .qr-text {
            font-size: 12px;
            color: #666;
        }

        .invoice-details {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }

        .customer-info, .invoice-info {
            flex: 1;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
            margin: 0 10px;
        }

        .info-title {
            font-size: 16px;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 15px;
            border-bottom: 2px solid #007bff;
            padding-bottom: 5px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }

        .info-label {
            font-weight: bold;
            color: #555;
        }

        .info-value {
            color: #333;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .items-table th {
            background: #007bff;
            color: white;
            padding: 15px 10px;
            text-align: center;
            font-weight: bold;
            border: 1px solid #0056b3;
        }

        .items-table td {
            padding: 12px 10px;
            text-align: center;
            border: 1px solid #ddd;
            background: white;
        }

        .items-table tbody tr:nth-child(even) {
            background: #f8f9fa;
        }

        .items-table tbody tr:hover {
            background: #e3f2fd;
        }

        .product-name {
            text-align: right;
            font-weight: bold;
        }

        .product-code {
            font-size: 12px;
            color: #666;
        }

        .totals-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }

        .debt-info {
            flex: 1;
            padding: 20px;
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 8px;
            margin-left: 20px;
        }

        .debt-title {
            font-size: 16px;
            font-weight: bold;
            color: #856404;
            margin-bottom: 15px;
        }

        .debt-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            padding: 5px 0;
        }

        .debt-row.total {
            border-top: 2px solid #856404;
            padding-top: 10px;
            font-weight: bold;
            font-size: 16px;
        }

        .invoice-totals {
            flex: 1;
            padding: 20px;
            background: #e8f5e8;
            border: 1px solid #c3e6c3;
            border-radius: 8px;
        }

        .totals-title {
            font-size: 16px;
            font-weight: bold;
            color: #155724;
            margin-bottom: 15px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            padding: 5px 0;
        }

        .total-row.final {
            border-top: 2px solid #155724;
            padding-top: 10px;
            font-weight: bold;
            font-size: 18px;
            color: #155724;
        }

        .warehouse-info {
            background: #e3f2fd;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
        }

        .warehouse-title {
            font-weight: bold;
            color: #1976d2;
            margin-bottom: 5px;
        }

        .notes-section {
            margin-top: 30px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
            border-right: 4px solid #007bff;
        }

        .notes-title {
            font-weight: bold;
            color: #007bff;
            margin-bottom: 10px;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            padding-top: 20px;
            border-top: 2px solid #007bff;
            color: #666;
        }

        .status-badges {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin: 20px 0;
        }

        .status-badge {
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 12px;
        }

        .status-paid {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }

        .status-overdue {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        @media print {
            body {
                margin: 0;
                padding: 0;
            }

            .invoice-container {
                max-width: none;
                margin: 0;
                padding: 15px;
            }

            .no-print {
                display: none !important;
            }
        }

        .currency {
            font-weight: bold;
            color: #007bff;
        }

        .highlight {
            background: #fff3cd;
            padding: 2px 6px;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <!-- Invoice Header -->
        <div class="invoice-header">
            <div class="company-info">
                <div class="company-name">{{ auth()->user()->tenant->name ?? 'اسم الشركة' }}</div>
                <div class="company-details">
                    العنوان: بغداد - العراق<br>
                    الهاتف: +964 770 123 4567<br>
                    البريد: info@company.com<br>
                    الموقع: www.company.com
                </div>
            </div>

            <div class="invoice-title">
                <h1>فاتورة مبيعات</h1>
                <div class="invoice-number">رقم: {{ $invoice->invoice_number }}</div>
            </div>

            <div class="qr-section">
                <div class="qr-code">
                    {!! $qrCode !!}
                </div>
                <div class="qr-text">امسح للتفاصيل</div>
            </div>
        </div>

        <!-- Status Badges -->
        <div class="status-badges">
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

                <div class="info-row">
                    <span class="info-label">المديونية السابقة:</span>
                    <span class="info-value">{{ number_format((float)($invoice->previous_debt ?? 0), 2) }} د.ع</span>
                </div>
                <div class="info-row">
                    <span class="info-label">المديونية الحالية:</span>
                    <span class="info-value">{{ number_format((float)($invoice->current_debt ?? 0), 2) }} د.ع</span>
                </div>

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
                <div class="debt-title">معلومات المديونية</div>
                <div class="debt-row">
                    <span>المديونية السابقة:</span>
                    <span>{{ number_format($invoice->previous_debt, 2) }} د.ع</span>
                </div>
                <div class="debt-row">
                    <span>مبلغ هذه الفاتورة:</span>
                    <span>{{ number_format($invoice->total_amount, 2) }} د.ع</span>
                </div>
                <div class="debt-row total">
                    <span>إجمالي المديونية:</span>
                    <span>{{ number_format($invoice->current_debt, 2) }} د.ع</span>
                </div>
                <div class="debt-row">
                    <span>سقف المديونية:</span>
                    <span>{{ number_format($invoice->credit_limit, 2) }} د.ع</span>
                </div>
            </div>

            <!-- Invoice Totals -->
            <div class="invoice-totals">
                <div class="totals-title">إجماليات الفاتورة</div>
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
                    <span>الضريبة:</span>
                    <span>{{ number_format($invoice->tax_amount, 2) }} د.ع</span>
                </div>
                @endif
                <div class="total-row final">
                    <span>المجموع الكلي:</span>
                    <span>{{ number_format($invoice->total_amount, 2) }} د.ع</span>
                </div>
                <div class="total-row">
                    <span>المبلغ المدفوع:</span>
                    <span>{{ number_format($invoice->paid_amount, 2) }} د.ع</span>
                </div>
                <div class="total-row">
                    <span>المبلغ المتبقي:</span>
                    <span>{{ number_format($invoice->remaining_amount, 2) }} د.ع</span>
                </div>
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
            <p>شكراً لتعاملكم معنا</p>
            <p>تم إنشاء هذه الفاتورة بتاريخ: {{ now()->format('Y-m-d H:i') }}</p>
            <p>نظام MaxCon للإدارة الصيدلانية</p>
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
