@extends('layouts.modern')

@section('page-title', 'إنشاء فاتورة جديدة')
@section('page-description', 'إنشاء فاتورة مبيعات احترافية')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    /* Select2 Styling to match reference image */
    .select2-container { width: 100% !important; }

    .select2-container--default .select2-selection--single {
        height: 42px !important;
        border: 1px solid #cbd5e1 !important; /* slate-300 */
        border-radius: 6px !important;
        background: #fff !important;
        transition: border-color .2s ease, box-shadow .2s ease !important;
        padding: 0 12px !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #111827 !important; /* gray-900 */
        line-height: 40px !important;
        padding: 0 !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__placeholder {
        color: #9ca3af !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 40px !important;
        right: 10px !important;
    }

    .select2-container--default .select2-selection--single:focus,
    .select2-container--default.select2-container--focus .select2-selection--single {
        border-color: #2563eb !important; /* blue-600 */
        box-shadow: 0 0 0 2px rgba(37,99,235,0.2) !important;
        outline: none !important;
    }

    .select2-dropdown {
        border: 1px solid #93c5fd !important; /* blue-300 */
        border-radius: 6px !important;
        box-shadow: 0 8px 20px rgba(0,0,0,0.08) !important;
        background: #fff !important;
        overflow: hidden !important;
    }

    .select2-search--dropdown .select2-search__field {
        border: 1px solid #cbd5e1 !important;
        border-radius: 4px !important;
        padding: 8px 10px !important;
        margin: 8px !important;
        width: calc(100% - 16px) !important;
        outline: none !important;
    }

    .select2-search--dropdown .select2-search__field:focus {
        border-color: #2563eb !important;
        box-shadow: 0 0 0 2px rgba(37,99,235,0.15) !important;
    }

    .select2-results__options { max-height: 260px !important; }

    .select2-results__option {
        padding: 10px 12px !important;
        border-bottom: 1px solid #f1f5f9 !important; /* slate-100 */
        color: #111827 !important;
    }

    .select2-results__option--highlighted {
        background: #e5efff !important; /* light blue highlight */
        color: #111827 !important;
    }

    .select2-results__option[aria-selected="true"] {
        background: #eef2ff !important; /* indigo-50 */
        color: #1f2937 !important;
        font-weight: 600 !important;
    }


    * {
        font-family: 'Cairo', sans-serif !important;
    }

    body {
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        min-height: 100vh;
    }

    .invoice-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem;
    }

    .professional-header {
        background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        color: white;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
    }

    .header-content {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .header-title {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .header-icon {
        background: rgba(255, 255, 255, 0.2);
        padding: 1rem;
        border-radius: 15px;
        font-size: 2rem;
    }

    .header-text h1 {
        font-size: 2.5rem;
        font-weight: 700;
        margin: 0;
    }

    .header-text p {
        font-size: 1.1rem;
        opacity: 0.9;
        margin: 0.5rem 0 0 0;
    }

    .btn {
        padding: 1rem 1.5rem;
        border-radius: 15px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        font-size: 1rem;
    }

    .btn-back {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .btn-back:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: translateY(-2px);
    }

    .form-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        margin-bottom: 2rem;
        overflow: hidden;
        border: 1px solid #e2e8f0;
    }

    .card-header {
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        padding: 1.5rem 2rem;
        border-bottom: 2px solid #e2e8f0;
    }

    .card-title {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin: 0;
        font-size: 1.5rem;
        font-weight: 700;
        color: #1f2937;
    }

    .card-icon {
        background: #4f46e5;
        color: white;
        padding: 0.75rem;
        border-radius: 12px;
        font-size: 1.2rem;
    }

    .card-body {
        padding: 2rem;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        display: block;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.5rem;
        font-size: 0.95rem;
    }

    .form-label i {
        margin-left: 0.5rem;
    }

    .required::after {
        content: ' *';
        color: #ef4444;
        font-weight: bold;
    }

    .form-control {
        width: 100%;
        padding: 1rem;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: white;
    }

    .form-control:focus {
        outline: none;
        border-color: #4f46e5;
        box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
    }

    .form-control:hover {
        border-color: #d1d5db;
    }

    .table-container {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .invoice-table {
        width: 100%;
        border-collapse: collapse;
    }

    .invoice-table th {
        background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
        color: white;
        padding: 1.25rem 1rem;
        text-align: center;
        font-weight: 600;
        font-size: 1rem;
    }

    .invoice-table td {
        padding: 1rem;
        text-align: center;
        border-bottom: 1px solid #f3f4f6;
        vertical-align: middle;
    }

    .invoice-table tr:hover {
        background: #f8fafc;
    }

    .btn-primary {
        background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
        color: white;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #3730a3 0%, #4f46e5 100%);
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.3);
    }

    .btn-success {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
    }

    .btn-success:hover {
        background: linear-gradient(135deg, #047857 0%, #10b981 100%);
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(16, 185, 129, 0.3);
    }

    .btn-secondary {
        background: #6b7280;
        color: white;
    }

    .btn-secondary:hover {
        background: #4b5563;
        transform: translateY(-2px);
    }

    .btn-danger {
        background: #ef4444;
        color: white;
        padding: 0.75rem;
        border-radius: 10px;
    }

    .btn-danger:hover {
        background: #dc2626;
        transform: scale(1.05);
    }

    .error-message {
        color: #ef4444;
        font-size: 0.875rem;
        margin-top: 0.5rem;
    }

    .add-item-section {
        text-align: center;
        padding: 1.5rem;
        background: #f8fafc;
        border-top: 2px solid #e5e7eb;
    }

    .actions-card {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        border: 1px solid #e2e8f0;
    }

    .actions-buttons {
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
        flex-wrap: wrap;
    }

    @media (max-width: 768px) {
        .invoice-container {
            padding: 1rem;
        }

        .header-content {
            flex-direction: column;
            text-align: center;
        }

        .form-grid {
            grid-template-columns: 1fr;
        }

        .actions-buttons {
            justify-content: center;
        }

        .invoice-table {
            font-size: 0.875rem;
        }

        .invoice-table th,
        .invoice-table td {
            padding: 0.75rem 0.5rem;
        }
    }
</style>
@endpush

@section('content')
<div class="invoice-container">
    <!-- Professional Header -->
    <div class="professional-header">
        <div class="header-content">
            <div class="header-title">
                <div class="header-icon">
                    <i class="fas fa-file-invoice-dollar"></i>
                </div>
                <div class="header-text">
                    <h1>إنشاء فاتورة جديدة</h1>
                    <p>فاتورة مبيعات احترافية مع QR Code ودعم العملات المتعددة</p>
                </div>
            </div>
            <a href="{{ route('tenant.sales.invoices.index') }}" class="btn btn-back">
                <i class="fas fa-arrow-right"></i>
                العودة للقائمة
            </a>
        </div>
    </div>

    <!-- Professional Invoice Form -->
    <form method="POST" action="{{ route('tenant.sales.invoices.store') }}" id="invoiceForm">
        @csrf

        <!-- Basic Information -->
        <div class="form-card">
            <div class="card-header">
                <h3 class="card-title">
                    <div class="card-icon">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    معلومات الفاتورة الأساسية
                </h3>
            </div>
            <div class="card-body">
                <div class="form-grid">
                    <!-- Customer -->
                    <div class="form-group">
                        <label class="form-label required">
                            <i class="fas fa-user-tie" style="color: #4f46e5;"></i>
                            العميل
                        </label>
                        <select name="customer_id" required class="form-control" data-custom-select data-placeholder="اختر العميل" data-searchable="true">
                            <option value="">اختر العميل</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}"
                                        data-credit-limit="{{ $customer->credit_limit ?? 0 }}"
                                        data-previous-balance="{{ $customer->current_balance ?? ($customer->previous_balance ?? 0) }}"
                                        {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                    {{ $customer->name }} {{ $customer->customer_code ? '(' . $customer->customer_code . ')' : '' }}
                                </option>
                            @endforeach
                        </select>
                        @error('customer_id')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Invoice Type -->
                    <div class="form-group">
                        <label class="form-label required">
                            <i class="fas fa-file-alt" style="color: #10b981;"></i>
                            نوع الفاتورة
                        </label>
                        <select name="type" required class="form-control">
                            <option value="sales" {{ old('type', 'sales') == 'sales' ? 'selected' : '' }}>فاتورة مبيعات</option>
                            <option value="proforma" {{ old('type') == 'proforma' ? 'selected' : '' }}>فاتورة أولية</option>
                        </select>
                        @error('type')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Invoice Date -->
                    <div class="form-group">
                        <label class="form-label required">
                            <i class="fas fa-calendar" style="color: #8b5cf6;"></i>
                            تاريخ الفاتورة
                        </label>
                        <input type="date" name="invoice_date" required
                               value="{{ old('invoice_date', date('Y-m-d')) }}"
                               class="form-control">
                        @error('invoice_date')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Due Date -->
                    <div class="form-group">
                        <label class="form-label required">
                            <i class="fas fa-clock" style="color: #f59e0b;"></i>
                            تاريخ الاستحقاق
                        </label>
                        <input type="date" name="due_date" required
                               value="{{ old('due_date', date('Y-m-d', strtotime('+30 days'))) }}"
                               class="form-control">
                        @error('due_date')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Currency -->
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-coins" style="color: #eab308;"></i>
                            العملة
                        </label>
                        <select name="currency" class="form-control">
                            <option value="IQD" {{ old('currency', 'IQD') == 'IQD' ? 'selected' : '' }}>دينار عراقي (IQD)</option>
                            <option value="USD" {{ old('currency') == 'USD' ? 'selected' : '' }}>دولار أمريكي (USD)</option>
                            <option value="EUR" {{ old('currency') == 'EUR' ? 'selected' : '' }}>يورو (EUR)</option>
                        </select>
                        @error('currency')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Sales Representative -->
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-user-tie" style="color: #6366f1;"></i>
                            مندوب المبيعات
                        </label>
                        <input type="text" name="sales_representative"
                               value="{{ old('sales_representative', auth()->user()->name) }}"
                               class="form-control" placeholder="اسم مندوب المبيعات">
                        @error('sales_representative')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
        <!-- Financials & Discounts -->
        <div class="form-card">
            <div class="card-header">
                <h3 class="card-title">
                    <div class="card-icon">
                        <i class="fas fa-calculator"></i>
                    </div>
                    المعلومات المالية والخصومات
                </h3>
            </div>
            <div class="card-body">
                <div class="form-grid">
                    <!-- Discount Type -->
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-percentage" style="color:#10b981"></i>
                            نوع الخصم
                        </label>
                        <select name="discount_type" id="discountType" class="form-control">
                            <option value="fixed" {{ old('discount_type','fixed')=='fixed'?'selected':'' }}>مبلغ ثابت</option>
                            <option value="percentage" {{ old('discount_type')=='percentage'?'selected':'' }}>نسبة مئوية</option>
                        </select>
                    </div>

                    <!-- Discount Amount -->
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-tags" style="color:#ef4444"></i>
                            قيمة الخصم
                        </label>
                        <input type="number" name="discount_amount" id="discountAmount" min="0" step="0.01"
                               value="{{ old('discount_amount',0) }}" class="form-control" placeholder="0.00">
                    </div>

                    <!-- Credit Limit -->
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-credit-card" style="color:#0ea5e9"></i>
                            سقف المديونية
                        </label>
                        <input type="number" name="credit_limit" id="creditLimit" min="0" step="0.01"
                               value="{{ old('credit_limit',0) }}" class="form-control" placeholder="0.00" readonly>
                    </div>

                    <!-- Previous Balance -->
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-money-bill-wave" style="color:#f59e0b"></i>
                            المديونية السابقة
                        </label>
                        <input type="number" name="previous_balance" id="previousBalance" min="0" step="0.01"
                               value="{{ old('previous_balance',0) }}" class="form-control" placeholder="0.00" readonly>
                    </div>

                    <!-- Warehouse Name -->
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-warehouse" style="color:#6366f1"></i>
                            اسم المخزن
                        </label>
                        <input type="text" name="warehouse_name" id="warehouseName"
                               value="{{ old('warehouse_name') }}" class="form-control" placeholder="مثال: مخزن الرئيسي">
                    </div>
                </div>

                <div class="mt-4" style="display:flex; gap:1rem; flex-wrap: wrap;">
                    <div>
                        <span style="font-weight:700; color:#374151">المجموع الفرعي:</span>
                        <span id="uiSubtotal" style="margin-inline-start:.5rem">0.00</span>
                    </div>
                    <div>
                        <span style="font-weight:700; color:#374151">الخصم:</span>
                        <span id="uiDiscount" style="margin-inline-start:.5rem">0.00</span>
                    </div>
                    <div>
                        <span style="font-weight:700; color:#374151">الضريبة:</span>
                        <span id="uiTax" style="margin-inline-start:.5rem">0.00</span>
                    </div>
                    <div>
                        <span style="font-weight:700; color:#374151">الإجمالي:</span>
                        <span id="uiTotal" style="margin-inline-start:.5rem">0.00</span>
                    </div>
                </div>
            </div>
        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Invoice Items -->
        <div class="form-card">
            <div class="card-header">
                <h3 class="card-title">
                    <div class="card-icon">
                        <i class="fas fa-list"></i>
                    </div>
                    عناصر الفاتورة
                </h3>
            </div>
            <div class="card-body">
                <div class="table-container">
                    <table class="invoice-table">
                        <thead>
                            <tr>
                                <th style="width: 35%;">المنتج</th>
                                <th style="width: 15%;">الكمية</th>
                                <th style="width: 20%;">سعر الوحدة</th>
                                <th style="width: 20%;">الإجمالي</th>
                                <th style="width: 10%;">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody id="invoiceItems">
                            <tr>
                                <td>
                                    <select name="items[0][product_id]" required class="form-control" data-custom-select
                                            data-placeholder="اختر المنتج" data-searchable="true" onchange="updatePrice(this)">
                                        <option value="">اختر المنتج</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}"
                                                    data-price="{{ $product->selling_price ?? $product->unit_price ?? 0 }}"
                                                    {{ old('items.0.product_id') == $product->id ? 'selected' : '' }}>
                                                {{ $product->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="number" name="items[0][quantity]" min="1" step="1" required
                                           class="form-control" placeholder="1"
                                           value="{{ old('items.0.quantity', 1) }}"
                                           onchange="calculateItemTotal(this)">
                                </td>
                                <td>
                                    <input type="number" name="items[0][unit_price]" min="0" step="0.01" required
                                           class="form-control" placeholder="0.00"
                                           value="{{ old('items.0.unit_price', 0) }}"
                                           onchange="calculateItemTotal(this)">
                                </td>
                                <td>
                                    <input type="number" name="items[0][total_amount]" readonly
                                           class="form-control" placeholder="0.00"
                                           value="{{ old('items.0.total_amount', 0) }}"
                                           style="background: #f9fafb;">
                                </td>
                                <td>
                                    <button type="button" onclick="removeItem(this)" class="btn btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="add-item-section">
                    <button type="button" onclick="addItem()" class="btn btn-primary">
                        <i class="fas fa-plus"></i>
                        إضافة عنصر جديد
                    </button>
                </div>
            </div>
        </div>

        <!-- Hidden Fields -->
        <input type="hidden" name="subtotal_amount" id="subtotalAmount" value="{{ old('subtotal_amount', 0) }}">
        <input type="hidden" name="tax_amount" id="taxAmount" value="{{ old('tax_amount', 0) }}">
        <input type="hidden" name="total_amount" id="totalAmount" value="{{ old('total_amount', 0) }}">
        <input type="hidden" name="shipping_cost" value="{{ old('shipping_cost', 0) }}">
        <input type="hidden" name="additional_charges" value="{{ old('additional_charges', 0) }}">

        <!-- Submit Buttons -->
        <div class="actions-card">
            <div class="actions-buttons">
                <a href="{{ route('tenant.sales.invoices.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i>
                    إلغاء
                </a>
                <button type="submit" name="action" value="draft" class="btn btn-secondary">
                    <i class="fas fa-save"></i>
                    حفظ كمسودة
                </button>
                <button type="submit" name="action" value="finalize" class="btn btn-success">
                    <i class="fas fa-check"></i>
                    إنهاء الفاتورة
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI/t0Oyy1lG3Gk6fYPMJ0H+RmE5ZkT2Qq8H0U5wE=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
let itemIndex = 1;

// Initialize custom selects for existing elements
function initializeCustomSelects() {
    // Initialize all custom selects
    if (window.initCustomSelects) {
        window.initCustomSelects();
    } else if (window.UniversalDropdowns) {
        window.UniversalDropdowns.initializeAllSelects();
    }
}

function addItem() {
    const tbody = document.getElementById('invoiceItems');
    const newRow = document.createElement('tr');

    newRow.innerHTML = `
        <td>
            <select name="items[${itemIndex}][product_id]" required class="form-control" data-custom-select
                    data-placeholder="اختر المنتج" data-searchable="true" onchange="updatePrice(this)">
                <option value="">اختر المنتج</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}" data-price="{{ $product->selling_price ?? $product->unit_price ?? 0 }}">
                        {{ $product->name }}
                    </option>
                @endforeach
            </select>
        </td>
        <td>
            <input type="number" name="items[${itemIndex}][quantity]" min="1" step="1" required
                   class="form-control" placeholder="1" value="1" onchange="calculateItemTotal(this)">
        </td>
        <td>
            <input type="number" name="items[${itemIndex}][unit_price]" min="0" step="0.01" required
                   class="form-control" placeholder="0.00" value="0" onchange="calculateItemTotal(this)">
        </td>
        <td>
            <input type="number" name="items[${itemIndex}][total_amount]" readonly
                   class="form-control" placeholder="0.00" value="0" style="background: #f9fafb;">
        </td>
        <td>
            <button type="button" onclick="removeItem(this)" class="btn btn-danger">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    `;

    tbody.appendChild(newRow);

    // Initialize custom select for the new dropdown
    if (window.initCustomSelects) {
        window.initCustomSelects(newRow);
    } else if (window.UniversalDropdowns) {
        window.UniversalDropdowns.initializeAllSelects(newRow);
    }

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

function updatePrice(selectElement) {
    const selectedOption = selectElement.options[selectElement.selectedIndex];
    const price = selectedOption.getAttribute('data-price') || 0;
    const row = selectElement.closest('tr');
    const priceInput = row.querySelector('input[name*="[unit_price]"]');

    if (priceInput && price > 0) {
        priceInput.value = parseFloat(price).toFixed(2);
        calculateItemTotal(priceInput);
    }
}

function calculateItemTotal(input) {
    const row = input.closest('tr');
    const quantity = parseFloat(row.querySelector('input[name*="[quantity]"]').value) || 0;
    const unitPrice = parseFloat(row.querySelector('input[name*="[unit_price]"]').value) || 0;
    const total = quantity * unitPrice;

    row.querySelector('input[name*="[total_amount]"]').value = total.toFixed(2);
    calculateGrandTotal();
}

function calculateGrandTotal() {
    const totalInputs = document.querySelectorAll('input[name*="[total_amount]"]');
    let subtotal = 0;

    totalInputs.forEach(input => {
        subtotal += parseFloat(input.value) || 0;
    });

    // Discount calculation
    const discountType = (document.getElementById('discountType')?.value || 'fixed');
    const discountValue = parseFloat(document.getElementById('discountAmount')?.value || 0);

    let discountAmount = 0;
    if (discountType === 'percentage') {
        discountAmount = Math.min(subtotal, subtotal * (discountValue / 100));
    } else {
        discountAmount = Math.min(subtotal, discountValue);
    }

    // Tax calculation (10%)
    const taxRate = 0.1;
    const taxableBase = Math.max(0, subtotal - discountAmount);
    const taxAmount = taxableBase * taxRate;

    const grandTotal = taxableBase + taxAmount;

    // Update hidden fields
    document.getElementById('subtotalAmount').value = subtotal.toFixed(2);
    document.getElementById('taxAmount').value = taxAmount.toFixed(2);
    document.getElementById('totalAmount').value = grandTotal.toFixed(2);

    // Update UI labels
    const fmt = (n) => (parseFloat(n)||0).toFixed(2);
    document.getElementById('uiSubtotal').innerText = fmt(subtotal);
    document.getElementById('uiDiscount').innerText = fmt(discountAmount);
    document.getElementById('uiTax').innerText = fmt(taxAmount);
    document.getElementById('uiTotal').innerText = fmt(grandTotal);
}

// React to discount inputs
$(document).on('input change', '#discountAmount, #discountType', function() {
    calculateGrandTotal();
});

// Update credit limit and previous balance when customer changes
$(document).on('change', 'select[name="customer_id"]', function() {
    const selected = $(this).find('option:selected');
    const creditLimit = parseFloat(selected.data('credit-limit') || 0);
    const previousBalance = parseFloat(selected.data('previous-balance') || 0);

    $('#creditLimit').val(creditLimit.toFixed(2));
    $('#previousBalance').val(previousBalance.toFixed(2));

    // Re-validate credit limit after selection
    calculateGrandTotal();
});

// Validate credit limit on submit
$('#invoiceForm').on('submit', function(e) {
    const action = $('button[type="submit"][clicked=true]').val() || 'draft';
    const creditLimit = parseFloat($('#creditLimit').val() || 0);
    const previousBalance = parseFloat($('#previousBalance').val() || 0);
    const totalAmount = parseFloat($('#totalAmount').val() || 0);

    // Only enforce on finalize
    if (action === 'finalize') {
        const totalDebt = previousBalance + totalAmount;
        if (creditLimit > 0 && totalDebt > creditLimit) {
            e.preventDefault();
            alert('لا يمكن إنهاء الفاتورة: إجمالي المديونية يتجاوز سقف المديونية المحدد للعميل.');
            return false;
        }
    }
});

// Track which submit button was clicked
$('button[type="submit"]').on('click', function() {
    $('button[type="submit"]').removeAttr('clicked');
    $(this).attr('clicked', 'true');
});

// Initialize calculations on page load
document.addEventListener('DOMContentLoaded', function() {
    // Initialize custom selects
    initializeCustomSelects();

    calculateGrandTotal();

    // Add event listeners to existing inputs
    document.querySelectorAll('input[name*="[quantity]"], input[name*="[unit_price]"]').forEach(input => {
        input.addEventListener('input', function() {
            calculateItemTotal(this);
        });
    });

    // Handle product select change events
    $(document).on('change', 'select[name*="[product_id]"]', function() {
        updatePrice(this);
    });
});

// Form validation before submit
document.getElementById('invoiceForm').addEventListener('submit', function(e) {
    const customerSelect = document.querySelector('select[name="customer_id"]');
    const productSelects = document.querySelectorAll('select[name*="[product_id]"]');

    if (!customerSelect.value) {
        e.preventDefault();
        alert('يرجى اختيار العميل');
        customerSelect.focus();
        return false;
    }

    let hasValidItems = false;
    productSelects.forEach(select => {
        if (select.value) {
            hasValidItems = true;
        }
    });

    if (!hasValidItems) {
        e.preventDefault();
        alert('يرجى إضافة منتج واحد على الأقل');
        return false;
    }

    // Show loading state
    const submitButtons = document.querySelectorAll('button[type="submit"]');
    submitButtons.forEach(btn => {
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الحفظ...';
    });
});
</script>
@endpush
