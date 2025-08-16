<!doctype html>
<html lang="ar" dir="rtl">
<head>
<meta charset="utf-8">
<style>
  @page { margin: 16mm; }
  body { font-family: DejaVu Sans, Arial, sans-serif; color:#0f172a; }
  .brand {
    border-radius: 14px; padding: 14px 16px; margin-bottom: 12px;
    background: linear-gradient(135deg, #0ea5e9 0%, #2563eb 100%);
    color:#fff;
  }
  .brand h1 { margin:0; font-size: 20px; font-weight: 800; letter-spacing: .3px; }
  .brand .meta { margin-top:6px; font-size: 12px; opacity:.95 }
  .card { border:1px solid #e5e7eb; border-radius:12px; padding:14px; margin-bottom:10px; }
  .row { display:flex; justify-content:space-between; gap:18px; }
  .col { flex:1; }
  .label { color:#64748b; font-size: 12px; margin-bottom:4px; }
  .val { font-weight: 700; font-size: 13px; }
  .footer { margin-top:14px; text-align:center; color:#64748b; font-size:11px; }
</style>
</head>
<body>
  @php
    $companyName = $invoice->tenant->company_name ?? $invoice->tenant->name ?? config('app.name', 'MaxCon');
    $salesRepName = optional($invoice->salesRep)->name ?? '-';
    $customerName = optional($invoice->customer)->name ?? '-';
    $receiptNo = $payment->receipt_number ?? ('RC-' . str_pad((string)$payment->id, 6, '0', STR_PAD_LEFT));
    $invNo = $invoice->invoice_number ?? ('INV-' . $invoice->id);
  @endphp

  <div class="brand">
    <h1>سند استلام</h1>
    <div class="meta">{{ $companyName }}</div>
  </div>

  <div class="card">
    <div class="row" style="margin-bottom:10px;">
      <div class="col">
        <div class="label">رقم السند</div>
        <div class="val">{{ $receiptNo }}</div>
      </div>
      <div class="col">
        <div class="label">التاريخ</div>
        <div class="val">{{ optional($payment->payment_date)->format('Y-m-d') ?? now()->format('Y-m-d') }}</div>
      </div>
      <div class="col">
        <div class="label">رقم الفاتورة</div>
        <div class="val">{{ $invNo }}</div>
      </div>
    </div>

    <div class="row" style="margin-bottom:10px;">
      <div class="col">
        <div class="label">اسم العميل</div>
        <div class="val">{{ $customerName }}</div>
      </div>
      <div class="col">
        <div class="label">اسم المندوب</div>
        <div class="val">{{ $salesRepName }}</div>
      </div>
      <div class="col">
        <div class="label">طريقة الدفع</div>
        <div class="val">{{ method_exists($payment,'getPaymentMethodLabel') ? $payment->getPaymentMethodLabel() : ($payment->payment_method ?? '-') }}</div>
      </div>
    </div>

    <div class="row">
      <div class="col">
        <div class="label">المبلغ المستلم</div>
        <div class="val">{{ number_format((float)$payment->amount, 2) }} د.ع</div>
      </div>
      <div class="col">
        <div class="label">المرجع</div>
        <div class="val">{{ $payment->reference_number ?? '-' }}</div>
      </div>
      <div class="col">
        <div class="label">ملاحظات</div>
        <div class="val">{{ $payment->notes ?? '-' }}</div>
      </div>
    </div>
  </div>

  <div class="footer">تم توليد المستند بواسطة نظام {{ config('app.name', 'MaxCon') }} — {{ now()->format('Y-m-d H:i') }}</div>
</body>
</html>

