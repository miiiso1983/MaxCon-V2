@extends('layouts.modern')

@section('page-title', 'إنشاء فاتورة جديدة')
@section('page-description', 'إنشاء فاتورة مبيعات احترافية')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<script src="https://cdn.tailwindcss.com"></script>
<style>
    body {
        font-family: 'Cairo', sans-serif !important;
    }
    
    .form-input {
        @apply w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-200 bg-white;
    }
    
    .form-label {
        @apply block text-sm font-semibold text-gray-700 mb-2;
    }
    
    .card {
        @apply bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden;
    }
    
    .card-header {
        @apply bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-200 px-8 py-6;
    }
    
    .card-body {
        @apply p-8;
    }
    
    .btn-primary {
        @apply bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-200 flex items-center space-x-2 rtl:space-x-reverse;
    }
    
    .btn-secondary {
        @apply bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-200 flex items-center space-x-2 rtl:space-x-reverse;
    }
    
    .btn-success {
        @apply bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-200 flex items-center space-x-2 rtl:space-x-reverse;
    }
    
    .btn-danger {
        @apply bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-lg font-semibold transition-all duration-200;
    }
    
    .table-header {
        @apply bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold text-center py-4;
    }
    
    .table-cell {
        @apply border-b border-gray-100 p-4 text-center;
    }
    
    .table-row:hover {
        @apply bg-blue-50;
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Professional Header -->
        <div class="card mb-8">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-8 py-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4 rtl:space-x-reverse">
                        <div class="bg-white/20 p-3 rounded-xl">
                            <i class="fas fa-file-invoice-dollar text-2xl text-white"></i>
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold text-white">إنشاء فاتورة جديدة</h1>
                            <p class="text-blue-100 mt-1">فاتورة مبيعات احترافية مع QR Code</p>
                        </div>
                    </div>
                    <a href="{{ route('tenant.sales.invoices.index') }}" class="btn-secondary">
                        <i class="fas fa-arrow-right"></i>
                        <span>العودة للقائمة</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Professional Invoice Form -->
        <form method="POST" action="{{ route('tenant.sales.invoices.store') }}" id="invoiceForm" class="space-y-8">
            @csrf

            <!-- Basic Information -->
            <div class="card">
                <div class="card-header">
                    <div class="flex items-center space-x-3 rtl:space-x-reverse">
                        <div class="bg-emerald-500 p-3 rounded-xl">
                            <i class="fas fa-info-circle text-white text-lg"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800">معلومات الفاتورة الأساسية</h3>
                    </div>
                </div>
                <div class="card-body">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        <!-- Customer -->
                        <div>
                            <label class="form-label">
                                <i class="fas fa-user-tie text-blue-500 mr-2"></i>
                                العميل <span class="text-red-500">*</span>
                            </label>
                            <select name="customer_id" required class="form-input">
                                <option value="">اختر العميل</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                @endforeach
                            </select>
                            @error('customer_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Invoice Type -->
                        <div>
                            <label class="form-label">
                                <i class="fas fa-file-alt text-green-500 mr-2"></i>
                                نوع الفاتورة <span class="text-red-500">*</span>
                            </label>
                            <select name="type" class="form-input">
                                <option value="sales">فاتورة مبيعات</option>
                                <option value="proforma">فاتورة أولية</option>
                            </select>
                        </div>

                        <!-- Invoice Date -->
                        <div>
                            <label class="form-label">
                                <i class="fas fa-calendar text-purple-500 mr-2"></i>
                                تاريخ الفاتورة <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="invoice_date" required value="{{ date('Y-m-d') }}" class="form-input">
                        </div>

                        <!-- Due Date -->
                        <div>
                            <label class="form-label">
                                <i class="fas fa-clock text-orange-500 mr-2"></i>
                                تاريخ الاستحقاق <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="due_date" required value="{{ date('Y-m-d', strtotime('+30 days')) }}" class="form-input">
                        </div>

                        <!-- Currency -->
                        <div>
                            <label class="form-label">
                                <i class="fas fa-coins text-yellow-500 mr-2"></i>
                                العملة
                            </label>
                            <select name="currency" class="form-input">
                                <option value="IQD">دينار عراقي (IQD)</option>
                                <option value="USD">دولار أمريكي (USD)</option>
                            </select>
                        </div>

                        <!-- Sales Representative -->
                        <div>
                            <label class="form-label">
                                <i class="fas fa-user-tie text-indigo-500 mr-2"></i>
                                مندوب المبيعات
                            </label>
                            <input type="text" name="sales_representative" value="{{ auth()->user()->name }}" class="form-input">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Invoice Items -->
            <div class="card">
                <div class="card-header">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3 rtl:space-x-reverse">
                            <div class="bg-purple-500 p-3 rounded-xl">
                                <i class="fas fa-list text-white text-lg"></i>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-800">عناصر الفاتورة</h3>
                        </div>
                        <button type="button" onclick="addItem()" class="btn-primary">
                            <i class="fas fa-plus"></i>
                            <span>إضافة عنصر</span>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr>
                                    <th class="table-header">المنتج</th>
                                    <th class="table-header">الكمية</th>
                                    <th class="table-header">سعر الوحدة</th>
                                    <th class="table-header">الإجمالي</th>
                                    <th class="table-header">الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody id="invoiceItems">
                                <tr class="table-row">
                                    <td class="table-cell">
                                        <select name="items[0][product_id]" required class="form-input">
                                            <option value="">اختر المنتج</option>
                                            @foreach($products as $product)
                                                <option value="{{ $product->id }}" data-price="{{ $product->selling_price ?? 0 }}">
                                                    {{ $product->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="table-cell">
                                        <input type="number" name="items[0][quantity]" min="1" required class="form-input" placeholder="1" onchange="calculateTotal(this)">
                                    </td>
                                    <td class="table-cell">
                                        <input type="number" name="items[0][unit_price]" min="0" step="0.01" required class="form-input" placeholder="0.00" onchange="calculateTotal(this)">
                                    </td>
                                    <td class="table-cell">
                                        <input type="number" name="items[0][total_amount]" readonly class="form-input bg-gray-50" placeholder="0.00">
                                    </td>
                                    <td class="table-cell">
                                        <button type="button" onclick="removeItem(this)" class="btn-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Hidden Fields -->
            <input type="hidden" name="subtotal_amount" id="subtotalAmount" value="0">
            <input type="hidden" name="tax_amount" id="taxAmount" value="0">
            <input type="hidden" name="total_amount" id="totalAmount" value="0">
            <input type="hidden" name="discount_amount" value="0">
            <input type="hidden" name="discount_type" value="fixed">

            <!-- Submit Buttons -->
            <div class="card">
                <div class="card-body">
                    <div class="flex justify-end space-x-4 rtl:space-x-reverse">
                        <a href="{{ route('tenant.sales.invoices.index') }}" class="btn-secondary">
                            <i class="fas fa-times"></i>
                            <span>إلغاء</span>
                        </a>
                        <button type="submit" name="action" value="draft" class="btn-secondary">
                            <i class="fas fa-save"></i>
                            <span>حفظ كمسودة</span>
                        </button>
                        <button type="submit" name="action" value="finalize" class="btn-success">
                            <i class="fas fa-check"></i>
                            <span>إنهاء الفاتورة</span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
let itemIndex = 1;

function addItem() {
    const tbody = document.getElementById('invoiceItems');
    const newRow = tbody.insertRow();
    newRow.className = 'table-row';
    
    newRow.innerHTML = `
        <td class="table-cell">
            <select name="items[${itemIndex}][product_id]" required class="form-input">
                <option value="">اختر المنتج</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}" data-price="{{ $product->selling_price ?? 0 }}">{{ $product->name }}</option>
                @endforeach
            </select>
        </td>
        <td class="table-cell">
            <input type="number" name="items[${itemIndex}][quantity]" min="1" required class="form-input" placeholder="1" onchange="calculateTotal(this)">
        </td>
        <td class="table-cell">
            <input type="number" name="items[${itemIndex}][unit_price]" min="0" step="0.01" required class="form-input" placeholder="0.00" onchange="calculateTotal(this)">
        </td>
        <td class="table-cell">
            <input type="number" name="items[${itemIndex}][total_amount]" readonly class="form-input bg-gray-50" placeholder="0.00">
        </td>
        <td class="table-cell">
            <button type="button" onclick="removeItem(this)" class="btn-danger">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    `;
    
    itemIndex++;
}

function removeItem(button) {
    const rows = document.querySelectorAll('#invoiceItems tr');
    if (rows.length > 1) {
        button.closest('tr').remove();
        calculateGrandTotal();
    } else {
        alert('يجب أن تحتوي الفاتورة على عنصر واحد على الأقل');
    }
}

function calculateTotal(input) {
    const row = input.closest('tr');
    const quantity = parseFloat(row.querySelector('input[name*="[quantity]"]').value) || 0;
    const unitPrice = parseFloat(row.querySelector('input[name*="[unit_price]"]').value) || 0;
    const total = quantity * unitPrice;
    
    row.querySelector('input[name*="[total_amount]"]').value = total.toFixed(2);
    calculateGrandTotal();
}

function calculateGrandTotal() {
    const totalInputs = document.querySelectorAll('input[name*="[total_amount]"]');
    let grandTotal = 0;
    
    totalInputs.forEach(input => {
        grandTotal += parseFloat(input.value) || 0;
    });
    
    document.getElementById('subtotalAmount').value = grandTotal.toFixed(2);
    document.getElementById('totalAmount').value = grandTotal.toFixed(2);
}

// Auto-fill price when product is selected
document.addEventListener('change', function(e) {
    if (e.target.name && e.target.name.includes('[product_id]')) {
        const selectedOption = e.target.options[e.target.selectedIndex];
        const price = selectedOption.getAttribute('data-price') || 0;
        const row = e.target.closest('tr');
        const priceInput = row.querySelector('input[name*="[unit_price]"]');
        if (priceInput) {
            priceInput.value = price;
            calculateTotal(priceInput);
        }
    }
});
</script>
@endpush
