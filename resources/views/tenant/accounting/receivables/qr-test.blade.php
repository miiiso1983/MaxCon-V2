@extends('layouts.tenant')

@section('title', 'اختبار QR كود سند الاستلام')

@section('content')
<div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 main-container">
    <!-- Header Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
        <div class="px-6 py-4 border-b border-gray-200 header-padding">
            <h1 class="text-2xl font-bold text-gray-900 mb-2">اختبار QR كود سند الاستلام</h1>
            <p class="text-gray-600">هذه الصفحة لاختبار كيفية عمل QR كود في سند الاستلام</p>
        </div>

        <!-- QR Code Test Section -->
        <div class="p-6 card-padding">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 qr-grid">
                <!-- JSON QR Code -->
                <div class="bg-gray-50 rounded-lg border border-gray-200 p-4">
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">QR كود بيانات JSON</h3>
                    <div class="bg-white rounded-lg border-2 border-dashed border-gray-300 p-6 text-center">
                        <div id="json-qr-container" class="min-h-[200px] flex items-center justify-center qr-container">
                            <div class="text-gray-500">جاري تحميل QR كود...</div>
                        </div>
                        <button onclick="generateJSONQR()" class="mt-4 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                            إنشاء QR كود JSON
                        </button>
                    </div>
                </div>

                <!-- Text QR Code -->
                <div class="bg-gray-50 rounded-lg border border-gray-200 p-4">
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">QR كود نص بسيط</h3>
                    <div class="bg-white rounded-lg border-2 border-dashed border-gray-300 p-6 text-center">
                        <div id="text-qr-container" class="min-h-[200px] flex items-center justify-center qr-container">
                            <div class="text-gray-500">جاري تحميل QR كود...</div>
                        </div>
                        <button onclick="generateTextQR()" class="mt-4 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                            إنشاء QR كود نص
                        </button>
                    </div>
                </div>

                <!-- Arabic Only QR Code -->
                <div class="bg-gray-50 rounded-lg border border-gray-200 p-4">
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">QR كود عربي فقط</h3>
                    <div class="bg-white rounded-lg border-2 border-dashed border-gray-300 p-6 text-center">
                        <div id="arabic-qr-container" class="min-h-[200px] flex items-center justify-center qr-container">
                            <div class="text-gray-500">جاري تحميل QR كود...</div>
                        </div>
                        <button onclick="generateArabicQR()" class="mt-4 bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                            إنشاء QR كود عربي
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Preview Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">بيانات QR كود</h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-3">بيانات JSON:</h3>
                    <pre id="json-data" class="bg-gray-100 border border-gray-300 rounded-lg p-4 text-xs overflow-auto max-h-80 text-right"></pre>
                </div>
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-3">نص بسيط:</h3>
                    <pre id="text-data" class="bg-gray-100 border border-gray-300 rounded-lg p-4 text-xs overflow-auto max-h-80 text-right"></pre>
                </div>
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-3">نص عربي فقط:</h3>
                    <pre id="arabic-data" class="bg-gray-100 border border-gray-300 rounded-lg p-4 text-xs overflow-auto max-h-80 text-right"></pre>
                </div>
            </div>
        </div>
    </div>

    <!-- Instructions Section -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
        <h3 class="text-lg font-semibold text-blue-900 mb-4">كيفية الاختبار:</h3>
        <ol class="list-decimal list-inside space-y-2 text-blue-800">
            <li>انقر على أزرار "إنشاء QR كود" أعلاه</li>
            <li>امسح QR كود باستخدام تطبيق قارئ QR على هاتفك</li>
            <li>تحقق من أن البيانات المعروضة تحتوي على معلومات سند الاستلام وليس رابط الموقع</li>
            <li>QR كود JSON يحتوي على بيانات مفصلة، بينما QR كود النص يحتوي على معلومات مبسطة</li>
        </ol>
    </div>

    <!-- Sample Receipt Link -->
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-yellow-900 mb-3">لاختبار سند استلام حقيقي:</h3>
        <p class="text-yellow-800 mb-2">تحتاج أولاً لإنشاء فاتورة ودفعة في النظام، ثم يمكنك الوصول لسند الاستلام عبر:</p>
        <code class="bg-yellow-100 text-yellow-900 px-2 py-1 rounded text-sm">/tenant/receipts/payment/{payment_id}/web</code>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js"></script>
<style>
/* Additional responsive styles */
@media (max-width: 1024px) {
    .grid-cols-1.lg\\:grid-cols-3 {
        grid-template-columns: repeat(1, minmax(0, 1fr));
    }
}

@media (max-width: 768px) {
    .qr-grid {
        gap: 1rem;
    }

    .qr-container {
        min-height: 150px;
    }

    pre {
        font-size: 10px;
        max-height: 200px;
    }
}

@media (max-width: 480px) {
    .main-container {
        padding-left: 1rem;
        padding-right: 1rem;
    }

    .card-padding {
        padding: 1rem;
    }

    .header-padding {
        padding: 1rem;
    }
}
</style>
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
    note: 'سند استلام صادر من نظام ماكس كون للإدارة الصيدلانية'
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

var sampleArabicData = `سند استلام
رقم: RCPT-2024-0001
فاتورة: INV-2024-0001
العميل: صيدلية الشفاء
المبلغ: 150,000 دينار عراقي
الدفع: نقداً
التاريخ: 15/1/2024
الشركة: شركة ماكس كون للأدوية`;

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
            desc.className = 'text-gray-600 mt-3 text-sm';
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
            desc.className = 'text-gray-600 mt-3 text-sm';
            desc.textContent = 'QR كود يحتوي على نص بسيط مقروء';
            container.appendChild(desc);
        });
    } else {
        fallbackQR(container, sampleTextData);
    }
}

function generateArabicQR() {
    var container = document.getElementById('arabic-qr-container');
    var dataContainer = document.getElementById('arabic-data');

    container.innerHTML = '<div class="text-gray-500">جاري إنشاء QR كود...</div>';
    dataContainer.textContent = sampleArabicData;

    // Try using qrcode.js library
    if (typeof QRCode !== 'undefined' && QRCode.toCanvas) {
        var canvas = document.createElement('canvas');
        QRCode.toCanvas(canvas, sampleArabicData, {
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
                fallbackQR(container, sampleArabicData);
                return;
            }

            container.innerHTML = '';
            container.appendChild(canvas);

            var desc = document.createElement('div');
            desc.className = 'text-gray-600 mt-3 text-sm';
            desc.textContent = 'QR كود يحتوي على نص عربي مبسط';
            container.appendChild(desc);
        });
    } else {
        fallbackQR(container, sampleArabicData);
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
        desc.className = 'text-gray-600 mt-3 text-sm';
        desc.textContent = 'QR كود تم إنشاؤه عبر External API';
        container.appendChild(desc);
    };
    img.onerror = function() {
        container.innerHTML = '<div class="text-red-600 text-sm">فشل في إنشاء QR كود</div>';
    };
}

// Auto-generate on page load
document.addEventListener('DOMContentLoaded', function() {
    generateJSONQR();
    generateTextQR();
    generateArabicQR();
});
</script>
@endpush
