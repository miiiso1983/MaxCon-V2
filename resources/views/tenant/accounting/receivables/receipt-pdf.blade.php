<!doctype html>
<html lang="ar" dir="rtl">
<head>
<meta charset="utf-8">
<style>
  body { font-family: DejaVu Sans, sans-serif; color:#111827; }
  .card { border:1px solid #e5e7eb; border-radius:12px; padding:16px; }
  .row { display:flex; justify-content:space-between; }
  small { color:#6b7280; }
</style>
</head>
<body>
  <div class="card">
    <h2 style="margin:0 0 8px 0;">سند استلام</h2>
    <div class="row" style="margin-bottom:8px;">
      <div>
        <div><small>العميل</small></div>
        <div style="font-weight:700;">{{ $invoice->customer->name ?? '-' }}</div>
      </div>
      <div>
        <div><small>رقم الفاتورة</small></div>
        <div style="font-weight:700;">{{ $invoice->invoice_number }}</div>
      </div>
    </div>
    <div class="row" style="margin-bottom:8px;">
      <div>
        <div><small>المبلغ المستلم</small></div>
        <div style="font-weight:700;">{{ number_format($payment->amount,2) }} د.ع</div>
      </div>
      <div>
        <div><small>التاريخ</small></div>
        <div style="font-weight:700;">{{ optional($payment->payment_date)->format('Y-m-d') }}</div>
      </div>
    </div>
    <div class="row">
      <div>
        <div><small>طريقة الدفع</small></div>
        <div style="font-weight:700;">{{ $payment->getPaymentMethodLabel() }}</div>
      </div>
      <div>
        <div><small>المرجع</small></div>
        <div style="font-weight:700;">{{ $payment->reference_number ?? '-' }}</div>
      </div>
    </div>
  </div>
</body>
</html>

