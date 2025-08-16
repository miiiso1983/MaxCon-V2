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
      <div style="display:flex; gap:20px; align-items:center;">
        <div>
          <div>الإجمالي: <strong>{{ number_format($invoice->total_amount,2) }} د.ع</strong></div>
          <div>المدفوع: <strong>{{ number_format($invoice->paid_amount,2) }} د.ع</strong></div>
          <div>المتبقي: <strong style="color:#ef4444;">{{ number_format($invoice->remaining_amount,2) }} د.ع</strong></div>
        </div>

        <!-- Invoice Summary QR Code -->
        <div style="text-align:center; padding:10px; border:1px solid #e5e7eb; border-radius:8px; background:#f9fafb;">
          <div style="font-size:12px; color:#6b7280; margin-bottom:5px;">QR معلومات الفاتورة</div>
          <div id="invoiceQrCode" style="display:flex; justify-content:center;">
            <div id="invoice-qr-container" style="width:80px; height:80px; display:flex; align-items:center; justify-content:center; font-size:10px; color:#6b7280;">
              جاري التحميل...
            </div>
          </div>
        </div>
      </div>
    </div>

@php
  $user = auth()->user();
  $isAdminMaint = $user && (
    (method_exists($user, 'isSuperAdmin') && $user->isSuperAdmin()) ||
    (method_exists($user, 'isTenantAdmin') && $user->isTenantAdmin()) ||
    (method_exists($user, 'hasRole') && ($user->hasRole('super-admin') || $user->hasRole('tenant-admin'))) ||
    in_array($user->role ?? null, ['super_admin','tenant_admin'])
  );
  $migrateReceiptsUrl = url('/tenant/maintenance/migrate-receipts');
  if (\Illuminate\Support\Facades\Route::has('tenant.maintenance.migrate-receipts')) {
      $migrateReceiptsUrl = route('tenant.maintenance.migrate-receipts', [], false);
  }
@endphp

@if($isAdminMaint)
  <div style="margin-top:6px;">
    <button type="button" onclick="fixReceipts()" class="btn" style="background:#2563eb; color:#fff; padding:6px 10px; border-radius:8px; border:none; cursor:pointer;">إصلاح الإيصالات</button>
  </div>
@endif


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
            <a href="{{ route('tenant.receipts.payment.show', ['payment' => $p->id], false) }}" target="_blank" class="btn" style="background:#3b82f6; color:#fff; padding:6px 10px; border-radius:8px; text-decoration:none;">PDF</a>
            <a href="{{ route('tenant.receipts.payment.web', ['payment' => $p->id], false) }}" target="_blank" class="btn" style="background: linear-gradient(135deg, #0ea5e9 0%, #2563eb 60%, #1e40af 100%); color:#fff; padding:6px 10px; border-radius:8px; text-decoration:none;">عرض ويب</a>
            <button type="button" data-payment-id="{{ $p->id }}" data-phone="{{ optional($invoice->customer)->phone ?? '' }}" onclick="sendReceiptWhatsApp(this.dataset.paymentId, this.dataset.phone)" class="btn" style="background: linear-gradient(135deg, #25d366 0%, #128c7e 100%); color:#fff; padding:6px 10px; border-radius:8px; border:none; cursor:pointer;">إرسال واتساب</button>
          </div>
        </div>
      @empty
        <div style="color:#6b7280;">لا توجد مدفوعات مسجلة.</div>
      @endforelse
    </div>
  </div>

  <!-- Products Catalog QR Code -->
  <div class="content-card">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:15px;">
      <div>
        <div style="font-weight:700; margin-bottom:4px;">كتالوج المنتجات المتوفرة</div>
        <div style="color:#6b7280; font-size:14px;">امسح الكود للاطلاع على المنتجات المتوفرة</div>
      </div>
      <div style="text-align:center;">
        <div id="productsCount" style="font-size:12px; color:#6b7280; margin-bottom:5px;">جاري التحميل...</div>
        <button onclick="generateProductsQR()" style="background:#3b82f6; color:#fff; padding:6px 12px; border:none; border-radius:6px; font-size:12px; cursor:pointer;">تحديث الكتالوج</button>
      </div>
    </div>

    <div style="display:flex; justify-content:center; align-items:center; padding:20px; border:2px dashed #e5e7eb; border-radius:12px; background:#f9fafb;">
      <div id="productsCatalogQR" style="display:flex; flex-direction:column; align-items:center; gap:10px;">
        <div style="color:#6b7280; font-size:14px;">انقر على "تحديث الكتالوج" لإنشاء QR كود المنتجات</div>
      </div>
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
<script type="application/json" id="whatsapp-config">
{!! json_encode([
    'urlTemplate' => $whatsTpl,
    'migrateReceiptsUrl' => $migrateReceiptsUrl ?? '',
    'tenantId' => auth()->user()->tenant_id ?? 0,
    'tenantName' => auth()->user()->tenant->name ?? 'شركة ماكس كون',
    'tenantPhone' => auth()->user()->tenant->phone ?? '',
    'qrRoute' => \Illuminate\Support\Facades\Route::has('tenant.inventory.qr.generate.invoice') ? route('tenant.inventory.qr.generate.invoice', [], false) : null,
    'invoice' => [
        'id' => $invoice->id,
        'invoice_number' => $invoice->invoice_number,
        'customer' => optional($invoice->customer)->name ?? 'عميل',
        'sales_rep' => optional($invoice->salesRep)->name ?? '-',
        'total_amount' => (float)$invoice->total_amount,
        'paid_amount' => (float)$invoice->paid_amount,
        'remaining_amount' => (float)$invoice->remaining_amount,
        'invoice_date' => $invoice->invoice_date ? $invoice->invoice_date->format('Y-m-d') : now()->format('Y-m-d'),
        'payment_status' => $invoice->payment_status ?? 'pending'
    ]
]) !!}
</script>
<script>

function sendReceiptWhatsApp(paymentId, phone) {
  try {
    var meta = document.querySelector('meta[name="csrf-token"]');
    var token = meta ? meta.getAttribute('content') : '';
    var config = JSON.parse(document.getElementById('whatsapp-config').textContent);
    var baseUrlTemplate = config.urlTemplate;

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

function fixReceipts() {
  try {
    var meta = document.querySelector('meta[name="csrf-token"]');
    var token = meta ? meta.getAttribute('content') : '';
    var config = JSON.parse(document.getElementById('whatsapp-config').textContent);
    var url = config.migrateReceiptsUrl;

    if (!url) {
      alert('رابط الإصلاح غير متوفر');
      return;
    }

    fetch(url, {
      method: 'POST',
      headers: { 'X-CSRF-TOKEN': token, 'Accept': 'application/json', 'Content-Type': 'application/json' },
      body: JSON.stringify({})
    }).then(function(r){ return r.json(); }).then(function(json){
      alert(
        'نتيجة الإصلاح:\n' +
        'نُقلت: ' + (json.moved || 0) + '\n' +
        'تحديث مسارات: ' + (json.updated || 0) + '\n' +
        'أُعيد توليدها: ' + (json.regenerated || 0) + '\n' +
        'مفقودة بعد المحاولة: ' + (json.still_missing_after_regen || 0) + '\n' +
        ((json.errors && json.errors.length) ? ('أخطاء: ' + json.errors.join(', ')) : 'بدون أخطاء')
      );
    }).catch(function(err){ console.error(err); alert('فشل طلب الإصلاح'); });
  } catch (e) { console.error(e); alert('خطأ غير متوقع'); }
}

// Generate Products Catalog QR Code
function generateProductsQR() {
  try {
    var meta = document.querySelector('meta[name="csrf-token"]');
    var token = meta ? meta.getAttribute('content') : '';
    var config = JSON.parse(document.getElementById('whatsapp-config').textContent);

    // Show loading state
    document.getElementById('productsCatalogQR').innerHTML = '<div style="color:#6b7280; font-size:14px;">جاري إنشاء QR كود المنتجات...</div>';

    // Check if we have a route for generating QR data
    var qrRoute = config.qrRoute;
    if (!qrRoute) {
      // Fallback: generate simple QR with tenant info
      var fallbackData = {
        type: 'catalog',
        tenant: config.tenantName,
        message: 'للاطلاع على كتالوج المنتجات المتوفرة',
        contact: config.tenantPhone,
        generated_at: new Date().toISOString()
      };

      generateQRFromData(JSON.stringify(fallbackData), 'كتالوج المنتجات');
      document.getElementById('productsCount').textContent = 'كتالوج عام';
      return;
    }

    // Make request to generate QR data
    fetch(qrRoute, {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': token,
        'Accept': 'application/json',
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        type: 'featured',
        limit: 20
      })
    })
    .then(function(response) { return response.json(); })
    .then(function(data) {
      if (data.success && data.qr_data) {
        generateQRFromData(data.qr_data, 'امسح الكود للاطلاع على ' + (data.products_count || 0) + ' منتج متوفر');
        document.getElementById('productsCount').textContent = (data.products_count || 0) + ' منتج متوفر';
      } else if (data.qr_data) {
        // Handle case where success field might not be present but qr_data exists
        generateQRFromData(data.qr_data, 'امسح الكود للاطلاع على ' + (data.products_count || 0) + ' منتج متوفر');
        document.getElementById('productsCount').textContent = (data.products_count || 0) + ' منتج متوفر';
      } else {
        document.getElementById('productsCatalogQR').innerHTML = '<div style="color: #ef4444; font-size: 14px;">فشل في إنشاء QR كود: ' + (data.message || data.error || 'خطأ غير معروف') + '</div>';
      }
    })
    .catch(function(error) {
      console.error('Error:', error);
      // Fallback on error
      var fallbackData = {
        type: 'catalog',
        tenant: config.tenantName,
        message: 'للاطلاع على كتالوج المنتجات المتوفرة',
        contact: config.tenantPhone,
        generated_at: new Date().toISOString()
      };
      generateQRFromData(JSON.stringify(fallbackData), 'كتالوج المنتجات');
      document.getElementById('productsCount').textContent = 'كتالوج عام';
    });
  } catch (e) {
    console.error(e);
    document.getElementById('productsCatalogQR').innerHTML = '<div style="color: #ef4444; font-size: 14px;">خطأ غير متوقع</div>';
  }
}

function generateQRFromData(qrData, description) {
  var qrContainer = document.getElementById('productsCatalogQR');
  qrContainer.innerHTML = '';

  // Try using qrcode.js library if available
  if (typeof QRCode !== 'undefined' && QRCode.toCanvas) {
    var canvas = document.createElement('canvas');
    qrContainer.appendChild(canvas);

    QRCode.toCanvas(canvas, qrData, {
      width: 200,
      height: 200,
      margin: 2,
      color: {
        dark: '#2d3748',
        light: '#ffffff'
      }
    }, function (error) {
      if (error) {
        console.error('Error generating QR code:', error);
        fallbackQRGeneration(qrData, description);
        return;
      }

      // Add description
      var desc = document.createElement('div');
      desc.style.cssText = 'font-size:12px; color:#6b7280; text-align:center; margin-top:8px;';
      desc.textContent = description;
      qrContainer.appendChild(desc);
    });
  } else {
    fallbackQRGeneration(qrData, description);
  }
}

function fallbackQRGeneration(qrData, description) {
  var qrContainer = document.getElementById('productsCatalogQR');
  qrContainer.innerHTML = '';

  // Fallback: use QR Server API
  var img = document.createElement('img');
  img.src = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' + encodeURIComponent(qrData);
  img.style.cssText = 'width:200px; height:200px; border:1px solid #e5e7eb;';
  img.onerror = function() {
    qrContainer.innerHTML = '<div style="color: #ef4444; font-size: 14px;">خطأ في إنشاء QR كود</div>';
  };
  qrContainer.appendChild(img);

  var desc = document.createElement('div');
  desc.style.cssText = 'font-size:12px; color:#6b7280; text-align:center; margin-top:8px;';
  desc.textContent = description;
  qrContainer.appendChild(desc);
}

// Generate Invoice Summary QR Code
function generateInvoiceQR() {
  try {
    var config = JSON.parse(document.getElementById('whatsapp-config').textContent);

    // Create invoice summary data
    var invoiceData = {
      type: 'invoice_summary',
      invoice_number: config.invoice.invoice_number,
      invoice_id: config.invoice.id,
      customer: config.invoice.customer,
      sales_rep: config.invoice.sales_rep,
      tenant: config.tenantName,
      total_amount: config.invoice.total_amount,
      paid_amount: config.invoice.paid_amount,
      remaining_amount: config.invoice.remaining_amount,
      currency: 'IQD',
      invoice_date: config.invoice.invoice_date,
      payment_status: config.invoice.payment_status,
      generated_at: new Date().toISOString()
    };

    var qrContainer = document.getElementById('invoice-qr-container');
    var qrDataString = JSON.stringify(invoiceData);

    // Try to generate QR using qrcode.js library if available
    if (typeof QRCode !== 'undefined' && QRCode.toCanvas) {
      var canvas = document.createElement('canvas');
      QRCode.toCanvas(canvas, qrDataString, {
        width: 80,
        height: 80,
        margin: 1,
        color: {
          dark: '#2d3748',
          light: '#ffffff'
        }
      }, function (error) {
        if (error) {
          console.error('Error generating invoice QR code:', error);
          fallbackInvoiceQR(qrDataString);
          return;
        }

        qrContainer.innerHTML = '';
        qrContainer.appendChild(canvas);
      });
    } else {
      fallbackInvoiceQR(qrDataString);
    }
  } catch (e) {
    console.error('Invoice QR generation error:', e);
    document.getElementById('invoice-qr-container').innerHTML = '<div style="font-size:10px; color:#ef4444;">QR غير متوفر</div>';
  }
}

function fallbackInvoiceQR(qrData) {
  var qrContainer = document.getElementById('invoice-qr-container');
  var qrApiUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=80x80&format=png&data=' + encodeURIComponent(qrData);

  var img = document.createElement('img');
  img.src = qrApiUrl;
  img.style.cssText = 'width:80px; height:80px; border-radius:4px;';
  img.onload = function() {
    qrContainer.innerHTML = '';
    qrContainer.appendChild(img);
  };
  img.onerror = function() {
    qrContainer.innerHTML = '<div style="font-size:10px; color:#ef4444; text-align:center;">QR غير متوفر</div>';
  };
}

// Auto-generate QR on page load
document.addEventListener('DOMContentLoaded', function() {
  generateInvoiceQR();
  generateProductsQR();
});

</script>

<!-- QR Code Library -->
<script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js"></script>
@endpush