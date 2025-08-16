<!doctype html>
<html lang="ar" dir="rtl">
<head>
<meta charset="utf-8">
<style>
  @page { margin: 16mm; }
  /* mPDF direction fix */
  body { direction: rtl; unicode-bidi: plaintext; }
  /* modern layout tweaks */
  .badge { display:inline-block; padding:4px 8px; border-radius:9999px; background:#eef2ff; color:#3730a3; font-size:11px; margin-right:6px; }
  .amount { font-size:16px; font-weight:800; color:#111827; }

  body { font-family: DejaVu Sans, Arial, sans-serif; color:#0f172a; }
  .brand {
    border-radius: 16px; padding: 18px 18px; margin-bottom: 14px;
    background: linear-gradient(135deg, #0ea5e9 0%, #2563eb 60%, #1e40af 100%);
    color:#fff;
    box-shadow: 0 6px 16px rgba(30,64,175,.25);
  }
  .brand h1 { margin:0; font-size: 22px; font-weight: 900; letter-spacing: .2px; }
  .brand h2 { margin:6px 0 0 0; font-size: 14px; font-weight:700; opacity:.95 }
  .brand .meta { margin-top:6px; font-size: 12px; opacity:.9 }
  .card { border:1px solid #e5e7eb; border-radius:14px; padding:16px; margin-bottom:12px; box-shadow: 0 2px 8px rgba(2,6,23,.04); }
  .row { display:flex; justify-content:space-between; gap:18px; }
  .col { flex:1; }
  .label { color:#64748b; font-size: 12px; margin-bottom:4px; }
  .val { font-weight: 800; font-size: 13.5px; color:#0f172a; }
  .footer { margin-top:16px; text-align:center; color:#64748b; font-size:11px; }
</style>
</head>
<body>
  @php
    $companyName = optional($invoice->tenant)->company_name ?? optional($invoice->tenant)->name ?? config('app.name', 'MaxCon');
    $salesRepName = optional($invoice->salesRep)->name ?? '-';
    $customerName = optional($invoice->customer)->name ?? '-';
    $receiptNo = $payment->receipt_number ?? ('RC-' . str_pad((string)$payment->id, 6, '0', STR_PAD_LEFT));
    $invNo = $invoice->invoice_number ?? ('INV-' . $invoice->id);
    $dateStr = $payment->payment_date ? \Illuminate\Support\Carbon::parse($payment->payment_date)->format('Y-m-d') : now()->format('Y-m-d');
  @endphp

  <div class="brand" style="display:flex; align-items:center; justify-content:space-between; gap:12px;">
    <div>
      <h1>سند استلام</h1>
      <h2>{{ $companyName }}</h2>
    </div>
    @if(!empty($logoB64))
      <img src="{{ $logoB64 }}" alt="Logo" style="height:48px; width:auto; border-radius:8px; background:#fff; padding:4px;"/>
    @endif
  </div>

  <div class="card">
    <div class="row" style="margin-bottom:10px;">
      <div class="col">
        <div class="label">رقم السند</div>
        <div class="val">{{ $receiptNo }}</div>
      </div>
      <div class="col">
        <div class="label">التاريخ</div>
        <div class="val">{{ $dateStr }}</div>
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

  @if(!empty($qrPng))
    <div class="card" style="display:flex; align-items:center; gap:14px;">
      <div>
        <div class="label">رمز QR</div>
        <div class="val" style="font-weight:600; font-size:12px; color:#334155;">يحمل كافة بيانات سند الاستلام</div>
      </div>
      <img src="data:image/png;base64,{{ $qrPng }}" alt="QR" style="height:96px; width:96px;" />
    </div>
  @endif

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

