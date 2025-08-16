@extends('layouts.modern')

@section('page-title', 'تحصيل الفاتورة')
@section('page-description', 'تسجيل الدفعات وإنشاء سند الاستلام')

@section('content')
<div style="display:grid; gap:20px;">
  <div class="content-card">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px;">
      <div>
        <div style="font-weight:800; font-size:18px;">فاتورة: {{ $invoice->invoice_number }}</div>
        <div style="color:#6b7280;">العميل: {{ optional($invoice->customer)->name ?? '-' }} | المندوب: {{ optional($invoice->salesRep)->name ?? '-' }}</div>
      </div>
      <div>
        <div>الإجمالي: <strong>{{ number_format($invoice->total_amount,2) }} د.ع</strong></div>
        <div>المدفوع: <strong>{{ number_format($invoice->paid_amount,2) }} د.ع</strong></div>
        <div>المتبقي: <strong style="color:#ef4444;">{{ number_format($invoice->remaining_amount,2) }} د.ع</strong></div>
      </div>
    </div>

    @php
      $storeRouteNamePreferred = 'tenant.inventory.accounting.receivables.invoice.payments.store';
      $storeRouteNameFallback  = 'tenant.accounting.receivables.invoice.payments.store';
      if (\Illuminate\Support\Facades\Route::has($storeRouteNamePreferred)) {
        $storeAction = route($storeRouteNamePreferred, $invoice, false);
      } elseif (\Illuminate\Support\Facades\Route::has($storeRouteNameFallback)) {
        $storeAction = route($storeRouteNameFallback, $invoice, false);
      } else {
        $storeAction = url('/tenant/inventory/accounting/receivables/invoice/'.$invoice->id.'/payments');
      }
    @endphp
    <form method="POST" action="{{ $storeAction }}" style="display:grid; gap:10px;">
      @csrf
      <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(180px,1fr)); gap:10px;">
        <input type="number" step="0.01" min="0.01" max="{{ $invoice->remaining_amount }}" name="amount" placeholder="المبلغ" style="padding:10px; border:1px solid #e5e7eb; border-radius:8px;" required>
        <select name="payment_method" style="padding:10px; border:1px solid #e5e7eb; border-radius:8px;">
          <option value="cash">نقداً</option>
          <option value="bank_transfer">تحويل بنكي</option>
          <option value="check">شيك</option>
          <option value="credit_card">بطاقة ائتمان</option>
          <option value="other">أخرى</option>
        </select>
        <input type="text" name="reference_number" placeholder="رقم المرجع (اختياري)" style="padding:10px; border:1px solid #e5e7eb; border-radius:8px;">
        <input type="text" name="notes" placeholder="ملاحظات" style="padding:10px; border:1px solid #e5e7eb; border-radius:8px;">
      </div>
      <div>
        <button class="btn btn-primary" style="background:#10b981; color:#fff; padding:10px 16px; border:none; border-radius:8px;">تسجيل الدفعة وإنشاء سند الاستلام</button>
      </div>
    </form>
  </div>

  <div class="content-card">
    <div style="font-weight:700; margin-bottom:8px;">سجل المدفوعات</div>
    <div style="display:grid; gap:8px;">
      @forelse($invoice->payments as $p)
        <div style="display:flex; justify-content:space-between; align-items:center; padding:10px; border:1px solid #e5e7eb; border-radius:8px;">
          <div>
            <div>المبلغ: <strong>{{ number_format($p->amount,2) }} د.ع</strong> — الطريقة: {{ $p->getPaymentMethodLabel() }}</div>
            <div style="color:#6b7280;">التاريخ: {{ optional($p->payment_date)->format('Y-m-d') }} — سند: {{ $p->receipt_number ?? '-' }}</div>
          </div>
          <div style="display:flex; gap:8px;">
            @if($p->pdf_path)
              <a href="{{ Storage::disk('public')->url($p->pdf_path) }}" target="_blank" class="btn" style="background:#3b82f6; color:#fff; padding:6px 10px; border-radius:8px; text-decoration:none;">عرض السند</a>
            @endif
            <button type="button" onclick="sendReceiptWhatsApp({{ $p->id }}, '{{ addslashes(optional($invoice->customer)->phone) }}')" class="btn" style="background: linear-gradient(135deg, #25d366 0%, #128c7e 100%); color:#fff; padding:6px 10px; border-radius:8px; border:none; cursor:pointer;">إرسال واتساب</button>
          </div>
        </div>
      @empty
        <div style="color:#6b7280;">لا توجد مدفوعات مسجلة.</div>
      @endforelse
    </div>
  </div>
</div>
@endsection



@php
  $whatsTpl = null;
  if (\Illuminate\Support\Facades\Route::has('tenant.inventory.accounting.receivables.payments.send-whatsapp')) {
      $whatsTpl = route('tenant.inventory.accounting.receivables.payments.send-whatsapp', ['payment' => 'PAYMENT_ID'], false);
  } elseif (\Illuminate\Support\Facades\Route::has('tenant.accounting.receivables.payments.send-whatsapp')) {
      $whatsTpl = route('tenant.accounting.receivables.payments.send-whatsapp', ['payment' => 'PAYMENT_ID'], false);
  } else {
      $whatsTpl = url('/tenant/inventory/accounting/receivables/payments/PAYMENT_ID/send-whatsapp');
  }
@endphp


@push('scripts')
<script>
function sendReceiptWhatsApp(paymentId, phone) {
  try {
    var meta = document.querySelector('meta[name="csrf-token"]');
    var token = meta ? meta.getAttribute('content') : '';
    var baseUrlTemplate = @json($whatsTpl);

    fetch(baseUrlTemplate.replace('PAYMENT_ID', paymentId), {
      method: 'POST',
      headers: { 'X-CSRF-TOKEN': token, 'Accept': 'application/json', 'Content-Type': 'application/x-www-form-urlencoded' },
      body: new URLSearchParams({ phone: phone || '' }).toString()
    }).then(function(r){ return r.json(); }).then(function(json){
      if (json.success && json.whatsapp_url) {
        window.open(json.whatsapp_url, '_blank');
      } else {
        alert(json.message || 'تعذر إنشاء رابط واتساب');
      }
    }).catch(function(err){ console.error(err); alert('خطأ أثناء الإرسال عبر واتساب'); });
  } catch (e) { console.error(e); alert('خطأ غير متوقع'); }
}
</script>
@endpush