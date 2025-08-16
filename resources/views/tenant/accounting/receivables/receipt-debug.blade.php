@extends('layouts.tenant')

@section('title', 'تشخيص سندات الاستلام')

@section('content')
<div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <!-- Header Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <h1 class="text-2xl font-bold text-gray-900 mb-2">تشخيص سندات الاستلام</h1>
            <p class="text-gray-600">صفحة لتشخيص وإنشاء سندات الاستلام للاختبار</p>
        </div>
        
        <div class="p-6">
            <!-- System Status -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <h3 class="font-semibold text-blue-900 mb-2">📊 إحصائيات النظام</h3>
                    <div id="system-stats" class="text-sm text-blue-800">
                        جاري التحميل...
                    </div>
                </div>
                
                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                    <h3 class="font-semibold text-green-900 mb-2">✅ الحالة</h3>
                    <div id="system-status" class="text-sm text-green-800">
                        جاري التحقق...
                    </div>
                </div>
                
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <h3 class="font-semibold text-yellow-900 mb-2">🔧 الإجراءات</h3>
                    <button onclick="createTestData()" class="bg-yellow-600 hover:bg-yellow-700 text-white px-3 py-1 rounded text-sm">
                        إنشاء بيانات اختبار
                    </button>
                </div>
            </div>
            
            <!-- Payments List -->
            <div class="bg-gray-50 rounded-lg border border-gray-200 p-4">
                <h3 class="font-semibold text-gray-900 mb-3">📋 سندات الاستلام المتوفرة</h3>
                <div id="payments-list" class="space-y-2">
                    جاري التحميل...
                </div>
            </div>
        </div>
    </div>
    
    <!-- Instructions -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
        <h3 class="text-lg font-semibold text-blue-900 mb-4">📖 كيفية الاستخدام:</h3>
        <ol class="list-decimal list-inside space-y-2 text-blue-800">
            <li>تحقق من إحصائيات النظام أعلاه</li>
            <li>إذا لم توجد دفعات، انقر على "إنشاء بيانات اختبار"</li>
            <li>استخدم الروابط المعروضة للوصول لسندات الاستلام</li>
            <li>اختبر QR كود في سندات الاستلام</li>
        </ol>
    </div>
    
    <!-- Routes Information -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">🔗 الروابط المتوفرة</h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <h4 class="font-medium text-gray-900 mb-2">سند الاستلام PDF:</h4>
                    <code class="bg-gray-100 text-sm p-2 rounded block">/tenant/receipts/payment/{payment_id}</code>
                </div>
                <div>
                    <h4 class="font-medium text-gray-900 mb-2">سند الاستلام ويب:</h4>
                    <code class="bg-gray-100 text-sm p-2 rounded block">/tenant/receipts/payment/{payment_id}/web</code>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    loadSystemStats();
    loadPaymentsList();
});

function loadSystemStats() {
    fetch('/tenant/accounting/receivables/debug-api/stats', {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('system-stats').innerHTML = `
            <div>الفواتير: ${data.invoices_count}</div>
            <div>الدفعات: ${data.payments_count}</div>
            <div>العملاء: ${data.customers_count}</div>
        `;
        
        document.getElementById('system-status').innerHTML = data.payments_count > 0 
            ? '<div class="text-green-600">✅ يوجد دفعات في النظام</div>'
            : '<div class="text-red-600">❌ لا توجد دفعات في النظام</div>';
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('system-stats').innerHTML = '<div class="text-red-600">خطأ في التحميل</div>';
    });
}

function loadPaymentsList() {
    fetch('/tenant/accounting/receivables/debug-api/payments', {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.payments && data.payments.length > 0) {
            let html = '';
            data.payments.forEach(payment => {
                html += `
                    <div class="bg-white border border-gray-300 rounded-lg p-3">
                        <div class="flex justify-between items-center">
                            <div>
                                <strong>سند رقم: ${payment.receipt_number}</strong>
                                <div class="text-sm text-gray-600">المبلغ: ${payment.amount} د.ع | الفاتورة: ${payment.invoice_number}</div>
                            </div>
                            <div class="space-x-2">
                                <a href="/tenant/receipts/payment/${payment.id}" target="_blank" 
                                   class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm">
                                    PDF
                                </a>
                                <a href="/tenant/receipts/payment/${payment.id}/web" target="_blank" 
                                   class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm">
                                    ويب
                                </a>
                            </div>
                        </div>
                    </div>
                `;
            });
            document.getElementById('payments-list').innerHTML = html;
        } else {
            document.getElementById('payments-list').innerHTML = `
                <div class="text-center text-gray-500 py-8">
                    <div class="text-4xl mb-2">📄</div>
                    <div>لا توجد سندات استلام في النظام</div>
                    <div class="text-sm mt-2">انقر على "إنشاء بيانات اختبار" لإنشاء سندات للاختبار</div>
                </div>
            `;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('payments-list').innerHTML = '<div class="text-red-600">خطأ في تحميل البيانات</div>';
    });
}

function createTestData() {
    if (!confirm('هل تريد إنشاء بيانات اختبار؟ سيتم إنشاء فاتورة ودفعة للاختبار.')) {
        return;
    }
    
    fetch('/tenant/accounting/receivables/debug-api/create-test', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({})
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(`تم إنشاء البيانات بنجاح!\nرقم الدفعة: ${data.payment_id}\nرقم السند: ${data.receipt_number}`);
            loadSystemStats();
            loadPaymentsList();
        } else {
            alert('فشل في إنشاء البيانات: ' + (data.message || 'خطأ غير معروف'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('خطأ في إنشاء البيانات');
    });
}
</script>
@endpush
