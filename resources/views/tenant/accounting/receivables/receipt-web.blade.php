<!doctype html>
<html lang="ar" dir="rtl">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>سند استلام</title>
<style>
  body { font-family: 'Tajawal', DejaVu Sans, Arial, sans-serif; color:#0f172a; background:#f8fafc; margin:16px; }
  .wrap { max-width:900px; margin:0 auto; }
  .brand { border-radius: 16px; padding: 18px; margin-bottom: 14px; background: linear-gradient(135deg, #0ea5e9 0%, #2563eb 60%, #1e40af 100%); color:#fff; box-shadow: 0 6px 16px rgba(30,64,175,.25); display:flex; align-items:center; justify-content:space-between; gap:12px; }
  .brand h1 { margin:0; font-size: 24px; font-weight: 900; letter-spacing: .2px; }
  .brand h2 { margin:6px 0 0 0; font-size: 14px; font-weight:700; opacity:.95 }
  .brand img { height:48px; width:auto; border-radius:8px; background:#fff; padding:4px; }
  .card { background:#fff; border:1px solid #e5e7eb; border-radius:14px; padding:16px; margin-bottom:12px; box-shadow: 0 2px 8px rgba(2,6,23,.04); }
  .row { display:flex; justify-content:space-between; gap:18px; }
  .col { flex:1; }
  .label { color:#64748b; font-size: 12px; margin-bottom:4px; }
  .val { font-weight: 800; font-size: 14px; color:#0f172a; }
  .qr { display:flex; align-items:center; gap:14px; }
  .footer { margin-top:16px; text-align:center; color:#64748b; font-size:12px; }
  @media print { body { background:#fff; margin:0; } .wrap{ max-width:100%; margin:0; } }
</style>
</head>
<body>
<div class="wrap">
  <div class="brand">
    <div>
      <h1>سند استلام</h1>
      <h2>{{ $companyName }}</h2>
    </div>
    @if(!empty($logoUrl))
      <img src="{{ $logoUrl }}" alt="Logo"/>
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
        <div class="val">{{ $paymentMethod }}</div>
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

  <!-- QR Code Section -->
  <div class="card qr">
    <div>
      <div class="label">رمز QR</div>
      <div class="val" style="font-weight:600; font-size:12px; color:#334155;">يحمل كافة بيانات سند الاستلام</div>
    </div>
    @if(!empty($qrUrl))
      <img src="{{ $qrUrl }}" alt="QR Code" style="height:120px; width:120px; border:1px solid #e5e7eb; border-radius:8px;"
           onerror="this.style.display='none'; this.nextElementSibling.style.display='block';" />
      <div style="display:none; width:120px; height:120px; border:2px dashed #d1d5db; border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:10px; color:#6b7280; text-align:center;">
        QR غير متوفر
      </div>
    @else
      <!-- Fallback: Generate QR using JavaScript -->
      <div id="qr-fallback-{{ $payment->id }}" style="width:120px; height:120px; border:1px solid #e5e7eb; border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:10px; color:#6b7280; text-align:center;">
        جاري تحميل QR...
      </div>
    @endif
  </div>

  @if(empty($qrUrl))
  <!-- JavaScript fallback for QR generation -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      var qrData = {
        type: 'payment_receipt',
        receipt_number: '{{ $payment->receipt_number }}',
        payment_id: {{ $payment->id }},
        invoice_number: '{{ $invoice->invoice_number }}',
        amount: {{ (float)$payment->amount }},
        currency: 'IQD',
        payment_method: '{{ $payment->payment_method }}',
        payment_date: '{{ optional($payment->payment_date)->format('Y-m-d') ?? now()->format('Y-m-d') }}',
        tenant: '{{ $companyName }}',
        customer: '{{ $customerName }}'
      };

      var fallbackContainer = document.getElementById('qr-fallback-{{ $payment->id }}');
      var qrDataString = JSON.stringify(qrData);

      // Try to generate QR using external API
      var qrApiUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=120x120&format=png&data=' + encodeURIComponent(qrDataString);

      var img = document.createElement('img');
      img.src = qrApiUrl;
      img.style.cssText = 'width:120px; height:120px; border-radius:8px;';
      img.onload = function() {
        fallbackContainer.innerHTML = '';
        fallbackContainer.appendChild(img);
      };
      img.onerror = function() {
        fallbackContainer.innerHTML = '<div style="font-size:10px; color:#ef4444; text-align:center;">QR غير متوفر</div>';
      };
    });
  </script>
  @endif

  <div class="footer">تم عرض المستند بواسطة نظام {{ config('app.name', 'MaxCon') }} — {{ now()->format('Y-m-d H:i') }}</div>
</div>
</body>
</html>

