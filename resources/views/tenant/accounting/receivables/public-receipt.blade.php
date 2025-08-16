<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>التحقق من سند الاستلام - {{ $payment->receipt_number }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px 0;
        }
        
        .receipt-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .receipt-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .receipt-header h1 {
            margin: 0;
            font-size: 2rem;
            font-weight: bold;
        }
        
        .receipt-header .subtitle {
            margin-top: 10px;
            opacity: 0.9;
            font-size: 1.1rem;
        }
        
        .receipt-body {
            padding: 40px;
        }
        
        .info-section {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 25px;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #e9ecef;
        }
        
        .info-row:last-child {
            border-bottom: none;
        }
        
        .info-label {
            font-weight: 600;
            color: #495057;
            font-size: 1rem;
        }
        
        .info-value {
            font-weight: 500;
            color: #212529;
            font-size: 1rem;
        }
        
        .amount-highlight {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            padding: 20px;
            border-radius: 15px;
            text-align: center;
            margin: 25px 0;
        }
        
        .amount-highlight .amount {
            font-size: 2.5rem;
            font-weight: bold;
            margin: 0;
        }
        
        .amount-highlight .currency {
            font-size: 1.2rem;
            opacity: 0.9;
            margin-top: 5px;
        }
        
        .verification-badge {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            padding: 15px 25px;
            border-radius: 50px;
            text-align: center;
            margin: 30px 0;
            font-weight: 600;
            font-size: 1.1rem;
        }
        
        .company-info {
            text-align: center;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 15px;
            margin-top: 30px;
        }
        
        .company-logo {
            max-width: 120px;
            max-height: 80px;
            margin-bottom: 15px;
        }
        
        .print-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            padding: 12px 30px;
            border-radius: 25px;
            font-weight: 600;
            margin: 20px 10px;
            transition: all 0.3s ease;
        }
        
        .print-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        @media print {
            body {
                background: white;
                padding: 0;
            }
            .receipt-container {
                box-shadow: none;
                border-radius: 0;
            }
            .print-btn {
                display: none;
            }
        }
        
        @media (max-width: 768px) {
            .receipt-body {
                padding: 20px;
            }
            .receipt-header {
                padding: 20px;
            }
            .receipt-header h1 {
                font-size: 1.5rem;
            }
            .amount-highlight .amount {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="receipt-container">
            <!-- Header -->
            <div class="receipt-header">
                <h1><i class="fas fa-receipt me-3"></i>سند استلام معتمد</h1>
                <div class="subtitle">{{ $payment->invoice->tenant->name ?? 'نظام ماكس كون للإدارة الصيدلانية' }}</div>
            </div>

            <!-- Body -->
            <div class="receipt-body">
                <!-- Receipt Info -->
                <div class="info-section">
                    <h4 class="mb-3"><i class="fas fa-info-circle me-2"></i>بيانات السند</h4>
                    <div class="info-row">
                        <span class="info-label">رقم السند</span>
                        <span class="info-value">{{ $payment->receipt_number }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">رقم الفاتورة</span>
                        <span class="info-value">{{ $payment->invoice->invoice_number }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">تاريخ الاستلام</span>
                        <span class="info-value">{{ $payment->payment_date ? $payment->payment_date->format('Y-m-d') : 'غير محدد' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">طريقة الدفع</span>
                        <span class="info-value">
                            @switch($payment->payment_method)
                                @case('cash') نقداً @break
                                @case('bank_transfer') تحويل بنكي @break
                                @case('check') شيك @break
                                @case('credit_card') بطاقة ائتمان @break
                                @default {{ $payment->payment_method }}
                            @endswitch
                        </span>
                    </div>
                    @if($payment->reference_number)
                    <div class="info-row">
                        <span class="info-label">رقم المرجع</span>
                        <span class="info-value">{{ $payment->reference_number }}</span>
                    </div>
                    @endif
                </div>

                <!-- Customer Info -->
                <div class="info-section">
                    <h4 class="mb-3"><i class="fas fa-user me-2"></i>بيانات العميل</h4>
                    <div class="info-row">
                        <span class="info-label">اسم العميل</span>
                        <span class="info-value">{{ $payment->invoice->customer->name ?? 'عميل' }}</span>
                    </div>
                    @if($payment->invoice->customer && $payment->invoice->customer->phone)
                    <div class="info-row">
                        <span class="info-label">رقم الهاتف</span>
                        <span class="info-value">{{ $payment->invoice->customer->phone }}</span>
                    </div>
                    @endif
                    @if($payment->invoice->salesRep)
                    <div class="info-row">
                        <span class="info-label">المندوب</span>
                        <span class="info-value">{{ $payment->invoice->salesRep->name }}</span>
                    </div>
                    @endif
                </div>

                <!-- Amount Highlight -->
                <div class="amount-highlight">
                    <div class="amount">{{ number_format((float)$payment->amount, 2) }}</div>
                    <div class="currency">دينار عراقي</div>
                </div>

                <!-- Verification Badge -->
                <div class="verification-badge">
                    <i class="fas fa-check-circle me-2"></i>
                    سند صحيح ومعتمد من نظام ماكس كون
                </div>

                @if($payment->notes)
                <!-- Notes -->
                <div class="info-section">
                    <h4 class="mb-3"><i class="fas fa-sticky-note me-2"></i>ملاحظات</h4>
                    <p class="mb-0">{{ $payment->notes }}</p>
                </div>
                @endif

                <!-- Company Info -->
                <div class="company-info">
                    @if($payment->invoice->tenant->logo)
                        <img src="{{ Storage::url($payment->invoice->tenant->logo) }}" alt="شعار الشركة" class="company-logo">
                    @endif
                    <h5>{{ $payment->invoice->tenant->name ?? 'نظام ماكس كون للإدارة الصيدلانية' }}</h5>
                    <p class="text-muted mb-0">نظام إدارة صيدلاني متكامل</p>
                </div>

                <!-- Action Buttons -->
                <div class="text-center">
                    <button onclick="window.print()" class="btn print-btn">
                        <i class="fas fa-print me-2"></i>طباعة السند
                    </button>
                    <button onclick="window.history.back()" class="btn print-btn">
                        <i class="fas fa-arrow-right me-2"></i>العودة
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
