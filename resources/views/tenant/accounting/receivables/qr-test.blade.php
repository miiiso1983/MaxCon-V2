@extends('layouts.tenant')

@section('title', 'اختبار QR كود سند الاستلام')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">اختبار QR كود سند الاستلام</h4>
                    <p class="text-muted">هذه الصفحة لاختبار كيفية عمل QR كود في سند الاستلام</p>
                </div>
                <div class="card-body">
                    
                    <!-- QR Code Test Section -->
                    <div class="row">
                        <div class="col-md-6">
                            <h5>QR كود بيانات JSON</h5>
                            <div class="border p-3 text-center" style="background: #f8f9fa;">
                                <div id="json-qr-container" style="min-height: 200px; display: flex; align-items: center; justify-content: center;">
                                    <div class="text-muted">جاري تحميل QR كود...</div>
                                </div>
                                <button onclick="generateJSONQR()" class="btn btn-primary btn-sm mt-2">إنشاء QR كود JSON</button>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <h5>QR كود نص بسيط</h5>
                            <div class="border p-3 text-center" style="background: #f8f9fa;">
                                <div id="text-qr-container" style="min-height: 200px; display: flex; align-items: center; justify-content: center;">
                                    <div class="text-muted">جاري تحميل QR كود...</div>
                                </div>
                                <button onclick="generateTextQR()" class="btn btn-success btn-sm mt-2">إنشاء QR كود نص</button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Data Preview -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5>بيانات QR كود</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>بيانات JSON:</h6>
                                    <pre id="json-data" class="bg-light p-3" style="font-size: 12px; max-height: 300px; overflow-y: auto;"></pre>
                                </div>
                                <div class="col-md-6">
                                    <h6>نص بسيط:</h6>
                                    <pre id="text-data" class="bg-light p-3" style="font-size: 12px; max-height: 300px; overflow-y: auto;"></pre>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Instructions -->
                    <div class="alert alert-info mt-4">
                        <h6>كيفية الاختبار:</h6>
                        <ol>
                            <li>انقر على أزرار "إنشاء QR كود" أعلاه</li>
                            <li>امسح QR كود باستخدام تطبيق قارئ QR على هاتفك</li>
                            <li>تحقق من أن البيانات المعروضة تحتوي على معلومات سند الاستلام وليس رابط الموقع</li>
                            <li>QR كود JSON يحتوي على بيانات مفصلة، بينما QR كود النص يحتوي على معلومات مبسطة</li>
                        </ol>
                    </div>
                    
                    <!-- Sample Receipt Link -->
                    <div class="alert alert-warning">
                        <h6>لاختبار سند استلام حقيقي:</h6>
                        <p>تحتاج أولاً لإنشاء فاتورة ودفعة في النظام، ثم يمكنك الوصول لسند الاستلام عبر:</p>
                        <code>/tenant/receipts/payment/{payment_id}/web</code>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js"></script>
<script>
// Sample receipt data for testing
var sampleReceiptData = {
    type: 'payment_receipt',
    receipt_number: 'RCPT-2024-0001',
    payment_id: 123,
    invoice_id: 456,
    invoice_number: 'INV-2024-0001',
    tenant: 'شركة ماكس كون للأدوية',
    customer: 'صيدلية الشفاء',
    sales_rep: 'أحمد محمد',
    amount: 150000,
    currency: 'IQD',
    payment_method: 'نقداً',
    payment_date: '2024-01-15',
    generated_at: new Date().toISOString(),
    verification_url: window.location.origin + '/tenant/receipts/payment/123/web'
};

var sampleTextData = `سند استلام
رقم السند: RCPT-2024-0001
رقم الفاتورة: INV-2024-0001
العميل: صيدلية الشفاء
المبلغ المستلم: 150,000.00 د.ع
طريقة الدفع: نقداً
التاريخ: 2024-01-15
الشركة: شركة ماكس كون للأدوية
المندوب: أحمد محمد`;

function generateJSONQR() {
    var container = document.getElementById('json-qr-container');
    var dataContainer = document.getElementById('json-data');
    
    container.innerHTML = '<div class="text-muted">جاري إنشاء QR كود...</div>';
    
    var jsonString = JSON.stringify(sampleReceiptData, null, 2);
    dataContainer.textContent = jsonString;
    
    // Try using qrcode.js library
    if (typeof QRCode !== 'undefined' && QRCode.toCanvas) {
        var canvas = document.createElement('canvas');
        QRCode.toCanvas(canvas, JSON.stringify(sampleReceiptData), {
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
                fallbackQR(container, JSON.stringify(sampleReceiptData));
                return;
            }
            
            container.innerHTML = '';
            container.appendChild(canvas);
            
            var desc = document.createElement('div');
            desc.className = 'text-muted mt-2';
            desc.style.fontSize = '12px';
            desc.textContent = 'QR كود يحتوي على بيانات JSON كاملة';
            container.appendChild(desc);
        });
    } else {
        fallbackQR(container, JSON.stringify(sampleReceiptData));
    }
}

function generateTextQR() {
    var container = document.getElementById('text-qr-container');
    var dataContainer = document.getElementById('text-data');
    
    container.innerHTML = '<div class="text-muted">جاري إنشاء QR كود...</div>';
    dataContainer.textContent = sampleTextData;
    
    // Try using qrcode.js library
    if (typeof QRCode !== 'undefined' && QRCode.toCanvas) {
        var canvas = document.createElement('canvas');
        QRCode.toCanvas(canvas, sampleTextData, {
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
                fallbackQR(container, sampleTextData);
                return;
            }
            
            container.innerHTML = '';
            container.appendChild(canvas);
            
            var desc = document.createElement('div');
            desc.className = 'text-muted mt-2';
            desc.style.fontSize = '12px';
            desc.textContent = 'QR كود يحتوي على نص بسيط مقروء';
            container.appendChild(desc);
        });
    } else {
        fallbackQR(container, sampleTextData);
    }
}

function fallbackQR(container, data) {
    var img = document.createElement('img');
    img.src = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&format=png&data=' + encodeURIComponent(data);
    img.style.cssText = 'width:200px; height:200px; border:1px solid #e5e7eb;';
    img.onload = function() {
        container.innerHTML = '';
        container.appendChild(img);
        
        var desc = document.createElement('div');
        desc.className = 'text-muted mt-2';
        desc.style.fontSize = '12px';
        desc.textContent = 'QR كود تم إنشاؤه عبر External API';
        container.appendChild(desc);
    };
    img.onerror = function() {
        container.innerHTML = '<div class="text-danger">فشل في إنشاء QR كود</div>';
    };
}

// Auto-generate on page load
document.addEventListener('DOMContentLoaded', function() {
    generateJSONQR();
    generateTextQR();
});
</script>
@endpush
