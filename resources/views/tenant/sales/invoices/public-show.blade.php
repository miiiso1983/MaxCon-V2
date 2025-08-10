@php
    $company = $invoice->tenant?->name ?? 'MaxCon';
    $invoiceNo = $invoice->invoice_number ?? ('INV-' . $invoice->id);
@endphp
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>التحقق من الفاتورة {{ $invoiceNo }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body{margin:0;background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);font-family:'Cairo',sans-serif;color:#1f2937}
        .wrap{max-width:900px;margin:40px auto;padding:0 16px}
        .card{background:#fff;border-radius:16px;box-shadow:0 10px 25px rgba(0,0,0,.08);overflow:hidden}
        .header{background:linear-gradient(135deg,#4f46e5,#7c3aed);color:#fff;padding:20px 24px}
        .header h1{margin:0;font-size:22px}
        .grid{display:grid;grid-template-columns:1fr 1fr;gap:16px;padding:20px}
        .section h3{margin:0 0 8px 0;color:#4f46e5;font-size:16px}
        .row{display:flex;justify-content:space-between;padding:6px 0;border-bottom:1px dashed #e5e7eb}
        .row:last-child{border-bottom:none}
        .footer{padding:14px 20px;background:#f9fafb;border-top:1px solid #f3f4f6;font-size:12px;color:#6b7280}
        .total{font-weight:700;color:#111827}
        .badge{display:inline-block;padding:4px 10px;border-radius:999px;font-size:12px;background:#eef2ff;color:#4338ca}
    </style>
</head>
<body>
<div class="wrap">
    <div class="card">
        <div class="header">
            <h1>التحقق من الفاتورة • {{ $company }}</h1>
        </div>
        <div class="grid">
            <div class="section">
                <h3>بيانات الفاتورة</h3>
                <div class="row"><span>رقم الفاتورة</span><span class="badge">{{ $invoiceNo }}</span></div>
                <div class="row"><span>تاريخ الفاتورة</span><span>{{ optional($invoice->invoice_date)->format('Y/m/d') ?? '-' }}</span></div>
                <div class="row"><span>تاريخ الاستحقاق</span><span>{{ optional($invoice->due_date)->format('Y/m/d') ?? '-' }}</span></div>
                <div class="row"><span>الحالة</span><span>{{ $invoice->payment_status ?? $invoice->status }}</span></div>
            </div>
            <div class="section">
                <h3>العميل</h3>
                <div class="row"><span>الاسم</span><span>{{ optional($invoice->customer)->name ?? '-' }}</span></div>
                <div class="row"><span>الهاتف</span><span>{{ optional($invoice->customer)->phone ?? '-' }}</span></div>
                <div class="row"><span>البريد</span><span>{{ optional($invoice->customer)->email ?? '-' }}</span></div>
            </div>
        </div>
        <div style="padding: 0 20px 12px 20px;">
            <h3 style="color:#4f46e5;font-size:16px;margin:6px 0 8px;">العناصر</h3>
            <div style="border:1px solid #f3f4f6;border-radius:10px;overflow:hidden">
                <table style="width:100%;border-collapse:collapse;font-size:14px">
                    <thead>
                    <tr style="background:#f9fafb;color:#374151;text-align:right">
                        <th style="padding:10px;">المنتج</th>
                        <th style="padding:10px;">الكمية</th>
                        <th style="padding:10px;">السعر</th>
                        <th style="padding:10px;">الإجمالي</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($invoice->items as $item)
                        <tr style="border-top:1px solid #f3f4f6">
                            <td style="padding:10px;">{{ $item->product_name ?? optional($item->product)->name }}</td>
                            <td style="padding:10px;">{{ $item->quantity }}</td>
                            <td style="padding:10px;">{{ number_format((float)($item->unit_price ?? 0),2) }}</td>
                            <td style="padding:10px;">{{ number_format((float)($item->line_total ?? $item->quantity * $item->unit_price),2) }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="footer">
            <div class="row"><span>المجموع قبل الخصم</span><span>{{ number_format((float)($invoice->subtotal_amount ?? $invoice->subtotal ?? 0),2) }}</span></div>
            <div class="row"><span>الخصم</span><span>{{ number_format((float)($invoice->discount_amount ?? 0),2) }}</span></div>
            <div class="row"><span>الضريبة</span><span>{{ number_format((float)($invoice->tax_amount ?? 0),2) }}</span></div>
            <div class="row total"><span>إجمالي الفاتورة</span><span>{{ number_format((float)($invoice->total_amount ?? 0),2) }}</span></div>
            <div style="margin-top:8px">هذه صفحة تحقق عامة. قد تختلف عن نسخة النظام الداخلية.</div>
        </div>
    </div>
</div>
</body>
</html>

