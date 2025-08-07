@extends('layouts.modern')

@section('page-title', 'إنشاء فاتورة جديدة')
@section('page-description', 'إنشاء فاتورة مبيعات احترافية مع QR Code')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
<style>
    body, .content-card, .form-container {
        font-family: 'Cairo', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif !important;
    }

    /* Custom Searchable Dropdown Styles */
    .custom-dropdown {
        position: relative;
        width: 100%;
    }

    .dropdown-header {
        width: 100%;
        padding: 12px 40px 12px 12px;
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        background: white;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 14px;
        color: #4a5568;
        transition: all 0.3s ease;
    }

    .dropdown-header:hover {
        border-color: #cbd5e0;
    }

    .dropdown-header.active {
        border-color: #4299e1;
        box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.1);
    }

    .dropdown-arrow {
        transition: transform 0.3s ease;
        color: #a0aec0;
    }

    .dropdown-header.active .dropdown-arrow {
        transform: rotate(180deg);
    }

    .dropdown-placeholder {
        color: #a0aec0;
    }

    .dropdown-content {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: white;
        border: 2px solid #e2e8f0;
        border-top: none;
        border-radius: 0 0 8px 8px;
        max-height: 300px;
        overflow-y: auto;
        z-index: 1000;
        display: none;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .dropdown-content.show {
        display: block;
    }

    .dropdown-search {
        width: 100%;
        padding: 8px 12px;
        border: none;
        border-bottom: 1px solid #e2e8f0;
        outline: none;
        font-size: 14px;
        background: #f7fafc;
    }

    .dropdown-search:focus {
        background: white;
        border-bottom-color: #4299e1;
    }

    .dropdown-options {
        max-height: 250px;
        overflow-y: auto;
    }

    .dropdown-option {
        padding: 10px 12px;
        cursor: pointer;
        font-size: 14px;
        color: #4a5568;
        border-bottom: 1px solid #f1f5f9;
        transition: all 0.2s ease;
    }

    .dropdown-option:hover {
        background: #edf2f7;
        color: #2d3748;
    }

    .dropdown-option.selected {
        background: #4299e1;
        color: white;
    }

    .dropdown-option.highlighted {
        background: #bee3f8;
        color: #2b6cb0;
    }

    /* Ensure dropdown header remains clickable */
    .dropdown-header {
        cursor: pointer !important;
        pointer-events: auto !important;
    }

    .dropdown-header.disabled {
        opacity: 0.6;
        cursor: not-allowed !important;
    }

    /* Improve dropdown responsiveness */
    .custom-dropdown {
        position: relative;
        z-index: 10;
    }

    .custom-dropdown.active {
        z-index: 1000;
    }

    .dropdown-option:last-child {
        border-bottom: none;
    }

    /* Loading state */
    .dropdown-loading {
        padding: 20px;
        text-align: center;
        color: #a0aec0;
        font-style: italic;
    }

    /* No results state */
    .dropdown-no-results {
        padding: 20px;
        text-align: center;
        color: #a0aec0;
        font-style: italic;
    }

    .dropdown-content {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: white;
        border: 2px solid #e2e8f0;
        border-top: none;
        border-radius: 0 0 8px 8px;
        max-height: 300px;
        overflow: hidden;
        z-index: 1000;
        display: none;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .dropdown-content.show {
        display: block;
    }

    .dropdown-search {
        width: 100%;
        padding: 12px;
        border: none;
        border-bottom: 1px solid #e2e8f0;
        outline: none;
        font-size: 14px;
        background: #f7fafc;
    }

    .dropdown-search:focus {
        background: white;
        border-bottom-color: #4299e1;
    }

    .dropdown-options {
        max-height: 200px;
        overflow-y: auto;
    }

    .dropdown-option {
        padding: 12px;
        cursor: pointer;
        border-bottom: 1px solid #f7fafc;
        transition: background-color 0.2s ease;
        font-size: 14px;
        color: #2d3748;
    }

    .dropdown-option:hover {
        background-color: #edf2f7;
    }

    .dropdown-option.selected {
        background-color: #4299e1;
        color: white;
    }

    .dropdown-option:last-child {
        border-bottom: none;
    }

    .dropdown-placeholder {
        color: #a0aec0;
    }

    /* Hide original select elements */
    .custom-dropdown select {
        display: none;
    }
</style>
@endpush

@section('content')
<!-- Page Header -->
<div style="background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%); border-radius: 20px; padding: 30px; margin-bottom: 30px; color: white; position: relative; overflow: hidden;">
    <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
    <div style="position: absolute; bottom: -30px; left: -30px; width: 150px; height: 150px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>

    <div style="position: relative; z-index: 2;">
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    <div style="background: rgba(255,255,255,0.2); border-radius: 15px; padding: 15px; margin-left: 20px;">
                        <i class="fas fa-file-invoice-dollar" style="font-size: 32px;"></i>
                    </div>
                    <div>
                        <h1 style="font-size: 32px; font-weight: 800; margin: 0; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                            إنشاء فاتورة جديدة 📄
                        </h1>
                        <p style="font-size: 18px; margin: 5px 0 0 0; opacity: 0.9;">
                            فاتورة مبيعات احترافية مع QR Code ودعم العملات المتعددة
                        </p>
                    </div>
                </div>
            </div>

            <div>
                <a href="{{ route('tenant.sales.invoices.index') }}" style="background: rgba(255,255,255,0.2); color: white; padding: 15px 25px; border-radius: 15px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3); transition: all 0.3s ease;"
                   onmouseover="this.style.background='rgba(255,255,255,0.3)'; this.style.transform='translateY(-2px)';"
                   onmouseout="this.style.background='rgba(255,255,255,0.2)'; this.style.transform='translateY(0)';">
                    <i class="fas fa-arrow-right"></i>
                    العودة للقائمة
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Invoice Form -->
<form method="POST" action="{{ route('tenant.sales.invoices.store') }}" id="invoiceForm">
    @csrf

    <!-- Invoice Header Information -->
    <div class="content-card" style="margin-bottom: 25px;">
        <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-info-circle" style="color: #ed8936; margin-left: 10px;"></i>
            معلومات الفاتورة
        </h3>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
            <!-- Customer -->
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">العميل *</label>
                <div class="custom-dropdown" data-name="customer_id" data-required="true" data-onchange="updateCustomerInfo">
                    <div class="dropdown-header">
                        <span class="dropdown-placeholder">اختر العميل</span>
                        <i class="fas fa-chevron-down dropdown-arrow"></i>
                    </div>
                    <div class="dropdown-content">
                        <input type="text" class="dropdown-search" placeholder="البحث عن عميل...">
                        <div class="dropdown-options">
                            <div class="dropdown-option" data-value="" data-payment-terms="" data-currency="" data-credit-limit="0" data-current-balance="0">
                                اختر العميل
                            </div>
                            @foreach($customers as $customer)
                                <div class="dropdown-option"
                                     data-value="{{ $customer->id }}"
                                     data-payment-terms="{{ $customer->payment_terms }}"
                                     data-currency="{{ $customer->currency }}"
                                     data-credit-limit="{{ $customer->credit_limit ?? 0 }}"
                                     data-current-balance="{{ $customer->current_balance ?? 0 }}"
                                     {{ old('customer_id') == $customer->id ? 'data-selected="true"' : '' }}>
                                    {{ $customer->name }} ({{ $customer->customer_code }})
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <select name="customer_id" required style="display: none;">
                        <option value="">اختر العميل</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}"
                                    data-payment-terms="{{ $customer->payment_terms }}"
                                    data-currency="{{ $customer->currency }}"
                                    data-credit-limit="{{ $customer->credit_limit ?? 0 }}"
                                    data-current-balance="{{ $customer->current_balance ?? 0 }}"
                                    {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                {{ $customer->name }} ({{ $customer->customer_code }})
                            </option>
                        @endforeach
                    </select>
                </div>
                @error('customer_id')
                    <div style="color: #f56565; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>

            <!-- Invoice Type -->
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">نوع الفاتورة *</label>
                <div class="custom-dropdown" data-name="type" data-required="true">
                    <div class="dropdown-header">
                        <span class="dropdown-placeholder">اختر نوع الفاتورة</span>
                        <i class="fas fa-chevron-down dropdown-arrow"></i>
                    </div>
                    <div class="dropdown-content">
                        <input type="text" class="dropdown-search" placeholder="البحث عن نوع الفاتورة...">
                        <div class="dropdown-options">
                            <div class="dropdown-option" data-value="" >
                                اختر نوع الفاتورة
                            </div>
                            <div class="dropdown-option" data-value="sales" {{ old('type', 'sales') === 'sales' ? 'data-selected="true"' : '' }}>
                                فاتورة مبيعات
                            </div>
                            <div class="dropdown-option" data-value="proforma" {{ old('type') === 'proforma' ? 'data-selected="true"' : '' }}>
                                فاتورة أولية
                            </div>
                        </div>
                    </div>
                    <select name="type" required style="display: none;">
                        <option value="sales" {{ old('type', 'sales') === 'sales' ? 'selected' : '' }}>فاتورة مبيعات</option>
                        <option value="proforma" {{ old('type') === 'proforma' ? 'selected' : '' }}>فاتورة أولية</option>
                    </select>
                </div>
                @error('type')
                    <div style="color: #f56565; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>

            <!-- Invoice Date -->
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">تاريخ الفاتورة *</label>
                <input type="date" name="invoice_date" value="{{ old('invoice_date', date('Y-m-d')) }}" required
                       style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;">
                @error('invoice_date')
                    <div style="color: #f56565; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>

            <!-- Due Date -->
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">تاريخ الاستحقاق *</label>
                <input type="date" name="due_date" value="{{ old('due_date') }}" required
                       style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;" id="dueDateField">
                @error('due_date')
                    <div style="color: #f56565; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>

            <!-- Currency -->
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">العملة *</label>
                <div class="custom-dropdown" data-name="currency" data-required="true" data-id="currencyField">
                    <div class="dropdown-header">
                        <span class="dropdown-placeholder">اختر العملة</span>
                        <i class="fas fa-chevron-down dropdown-arrow"></i>
                    </div>
                    <div class="dropdown-content">
                        <input type="text" class="dropdown-search" placeholder="البحث عن عملة...">
                        <div class="dropdown-options">
                            <div class="dropdown-option" data-value="">
                                اختر العملة
                            </div>
                            <div class="dropdown-option" data-value="IQD" {{ old('currency', 'IQD') === 'IQD' ? 'data-selected="true"' : '' }}>
                                دينار عراقي (IQD)
                            </div>
                            <div class="dropdown-option" data-value="USD" {{ old('currency') === 'USD' ? 'data-selected="true"' : '' }}>
                                دولار أمريكي (USD)
                            </div>
                            <div class="dropdown-option" data-value="SAR" {{ old('currency') === 'SAR' ? 'data-selected="true"' : '' }}>
                                ريال سعودي (SAR)
                            </div>
                            <div class="dropdown-option" data-value="AED" {{ old('currency') === 'AED' ? 'data-selected="true"' : '' }}>
                                درهم إماراتي (AED)
                            </div>
                            <div class="dropdown-option" data-value="EUR" {{ old('currency') === 'EUR' ? 'data-selected="true"' : '' }}>
                                يورو (EUR)
                            </div>
                        </div>
                    </div>
                    <select name="currency" required style="display: none;" id="currencyField">
                        <option value="IQD" {{ old('currency', 'IQD') === 'IQD' ? 'selected' : '' }}>دينار عراقي (IQD)</option>
                        <option value="USD" {{ old('currency') === 'USD' ? 'selected' : '' }}>دولار أمريكي (USD)</option>
                        <option value="SAR" {{ old('currency') === 'SAR' ? 'selected' : '' }}>ريال سعودي (SAR)</option>
                        <option value="AED" {{ old('currency') === 'AED' ? 'selected' : '' }}>درهم إماراتي (AED)</option>
                        <option value="EUR" {{ old('currency') === 'EUR' ? 'selected' : '' }}>يورو (EUR)</option>
                    </select>
                </div>
                @error('currency')
                    <div style="color: #f56565; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>

            <!-- Sales Representative -->
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">المندوب *</label>
                <input type="text" name="sales_representative" value="{{ old('sales_representative', auth()->user()->name) }}" required
                       style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;"
                       placeholder="اسم المندوب">
                @error('sales_representative')
                    <div style="color: #f56565; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>

            <!-- Sales Order (Optional) -->
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">طلب المبيعات (اختياري)</label>
                <div class="custom-dropdown" data-name="sales_order_id" data-onchange="loadOrderItems">
                    <div class="dropdown-header">
                        <span class="dropdown-placeholder">اختر طلب مبيعات</span>
                        <i class="fas fa-chevron-down dropdown-arrow"></i>
                    </div>
                    <div class="dropdown-content">
                        <input type="text" class="dropdown-search" placeholder="البحث عن طلب مبيعات...">
                        <div class="dropdown-options">
                            <div class="dropdown-option" data-value="">
                                اختر طلب مبيعات
                            </div>
                            @foreach($salesOrders as $order)
                                <div class="dropdown-option"
                                     data-value="{{ $order->id }}"
                                     {{ old('sales_order_id') == $order->id ? 'data-selected="true"' : '' }}>
                                    {{ $order->order_number }} - {{ $order->customer->name }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <select name="sales_order_id" style="display: none;">
                        <option value="">اختر طلب مبيعات</option>
                        @foreach($salesOrders as $order)
                            <option value="{{ $order->id }}" {{ old('sales_order_id') == $order->id ? 'selected' : '' }}>
                                {{ $order->order_number }} - {{ $order->customer->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @error('sales_order_id')
                    <div style="color: #f56565; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>

    <!-- Customer Credit Information -->
    <div class="content-card" id="customerCreditInfo" style="margin-bottom: 25px; display: none;">
        <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-credit-card" style="color: #dc2626; margin-left: 10px;"></i>
            معلومات المديونية
        </h3>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
            <!-- Current Balance -->
            <div style="background: #fef2f2; border: 2px solid #fecaca; border-radius: 12px; padding: 20px;">
                <div style="display: flex; align-items: center; margin-bottom: 10px;">
                    <i class="fas fa-balance-scale" style="color: #dc2626; margin-left: 10px; font-size: 20px;"></i>
                    <h4 style="margin: 0; color: #dc2626; font-weight: 600;">المديونية الحالية</h4>
                </div>
                <div style="font-size: 24px; font-weight: 800; color: #dc2626;" id="currentBalanceDisplay">0.00 ر.س</div>
                <div style="font-size: 14px; color: #7f1d1d; margin-top: 5px;">إجمالي المبلغ المستحق</div>
            </div>

            <!-- Credit Limit -->
            <div style="background: #f0f9ff; border: 2px solid #bae6fd; border-radius: 12px; padding: 20px;">
                <div style="display: flex; align-items: center; margin-bottom: 10px;">
                    <i class="fas fa-chart-line" style="color: #0284c7; margin-left: 10px; font-size: 20px;"></i>
                    <h4 style="margin: 0; color: #0284c7; font-weight: 600;">سقف المديونية</h4>
                </div>
                <div style="font-size: 24px; font-weight: 800; color: #0284c7;" id="creditLimitDisplay">0.00 ر.س</div>
                <div style="font-size: 14px; color: #075985; margin-top: 5px;">الحد الأقصى المسموح</div>
            </div>

            <!-- Available Credit -->
            <div style="background: #f0fdf4; border: 2px solid #bbf7d0; border-radius: 12px; padding: 20px;">
                <div style="display: flex; align-items: center; margin-bottom: 10px;">
                    <i class="fas fa-check-circle" style="color: #059669; margin-left: 10px; font-size: 20px;"></i>
                    <h4 style="margin: 0; color: #059669; font-weight: 600;">الائتمان المتاح</h4>
                </div>
                <div style="font-size: 24px; font-weight: 800; color: #059669;" id="availableCreditDisplay">0.00 ر.س</div>
                <div style="font-size: 14px; color: #065f46; margin-top: 5px;">المبلغ المتاح للشراء</div>
            </div>

            <!-- Credit Status After Invoice -->
            <div style="background: #fffbeb; border: 2px solid #fed7aa; border-radius: 12px; padding: 20px;">
                <div style="display: flex; align-items: center; margin-bottom: 10px;">
                    <i class="fas fa-exclamation-triangle" style="color: #d97706; margin-left: 10px; font-size: 20px;"></i>
                    <h4 style="margin: 0; color: #d97706; font-weight: 600;">الرصيد بعد الفاتورة</h4>
                </div>
                <div style="font-size: 24px; font-weight: 800; color: #d97706;" id="balanceAfterInvoiceDisplay">0.00 ر.س</div>
                <div style="font-size: 14px; color: #92400e; margin-top: 5px;" id="creditStatusMessage">حالة الائتمان</div>
            </div>
        </div>

        <!-- Credit Warning -->
        <div id="creditWarning" style="display: none; background: #fef2f2; border: 2px solid #fca5a5; border-radius: 12px; padding: 20px; margin-top: 20px;">
            <div style="display: flex; align-items: center; margin-bottom: 10px;">
                <i class="fas fa-exclamation-circle" style="color: #dc2626; margin-left: 10px; font-size: 20px;"></i>
                <h4 style="margin: 0; color: #dc2626; font-weight: 600;">تحذير ائتماني</h4>
            </div>
            <p style="color: #7f1d1d; margin: 0;" id="creditWarningMessage">هذه الفاتورة ستتجاوز سقف المديونية المسموح للعميل.</p>
        </div>
    </div>

    <!-- Invoice Items -->
    <div class="content-card" style="margin-bottom: 25px;">
        <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-list" style="color: #9f7aea; margin-left: 10px;"></i>
            عناصر الفاتورة
        </h3>

        <div id="invoiceItems">
            <!-- Item Template (Hidden) -->
            <template id="itemTemplate">
                <div class="invoice-item" style="border: 2px solid #e2e8f0; border-radius: 12px; padding: 20px; margin-bottom: 15px; position: relative;">
                    <button type="button" onclick="removeItem(this)" style="position: absolute; top: 10px; left: 10px; background: #f56565; color: white; border: none; border-radius: 50%; width: 30px; height: 30px; cursor: pointer; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-times"></i>
                    </button>

                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
                        <!-- Product -->
                        <div>
                            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">المنتج *</label>
                            <div class="custom-dropdown" data-name="items[INDEX][product_id]" data-required="true" data-onchange="updateProductInfo">
                                <div class="dropdown-header">
                                    <span class="dropdown-placeholder">اختر المنتج</span>
                                    <i class="fas fa-chevron-down dropdown-arrow"></i>
                                </div>
                                <div class="dropdown-content">
                                    <input type="text" class="dropdown-search" placeholder="البحث عن منتج...">
                                    <div class="dropdown-options">
                                        <div class="dropdown-option" data-value="" data-price="" data-stock="" data-unit="">
                                            اختر المنتج
                                        </div>
                                        @foreach($products as $product)
                                            <div class="dropdown-option"
                                                 data-value="{{ $product->id }}"
                                                 data-price="{{ $product->selling_price }}"
                                                 data-stock="{{ $product->current_stock }}"
                                                 data-unit="{{ $product->unit }}">
                                                {{ $product->name }} ({{ $product->current_stock }} {{ $product->unit }})
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <select name="items[INDEX][product_id]" required style="display: none;">
                                    <option value="">اختر المنتج</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}"
                                                data-price="{{ $product->selling_price }}"
                                                data-stock="{{ $product->current_stock }}"
                                                data-unit="{{ $product->unit }}">
                                            {{ $product->name }} ({{ $product->current_stock }} {{ $product->unit }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Quantity -->
                        <div>
                            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">الكمية *</label>
                            <input type="number" name="items[INDEX][quantity]" min="1" step="1" required
                                   style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 8px;"
                                   placeholder="1" onchange="calculateItemTotal(this)">
                        </div>

                        <!-- Unit Price -->
                        <div>
                            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">سعر الوحدة *</label>
                            <input type="number" name="items[INDEX][unit_price]" min="0" step="0.01" required
                                   style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 8px;"
                                   placeholder="0.00" onchange="calculateItemTotal(this)">
                        </div>

                        <!-- Discount -->
                        <div>
                            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">الخصم</label>
                            <div style="display: flex; gap: 5px;">
                                <input type="number" name="items[INDEX][discount_amount]" min="0" step="0.01"
                                       style="flex: 1; padding: 10px; border: 2px solid #e2e8f0; border-radius: 8px;"
                                       placeholder="0.00" onchange="calculateItemTotal(this)">
                                <div class="custom-dropdown" data-name="items[INDEX][discount_type]" style="width: 80px;">
                                    <div class="dropdown-header" style="padding: 10px 8px;">
                                        <span class="dropdown-placeholder">اختر نوع الخصم</span>
                                        <i class="fas fa-chevron-down dropdown-arrow" style="font-size: 10px;"></i>
                                    </div>
                                    <div class="dropdown-content">
                                        <input type="text" class="dropdown-search" placeholder="البحث عن نوع الخصم...">
                                        <div class="dropdown-options">
                                            <div class="dropdown-option" data-value="">
                                                اختر نوع الخصم
                                            </div>
                                            <div class="dropdown-option" data-value="fixed" data-selected="true">
                                                ثابت
                                            </div>
                                            <div class="dropdown-option" data-value="percentage">
                                                %
                                            </div>
                                        </div>
                                    </div>
                                    <select name="items[INDEX][discount_type]" style="display: none;">
                                        <option value="fixed">ثابت</option>
                                        <option value="percentage">%</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Total -->
                        <div>
                            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">الإجمالي</label>
                            <input type="number" name="items[INDEX][total_amount]" readonly
                                   style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 8px; background: #f7fafc;"
                                   placeholder="0.00">
                        </div>

                        <!-- Notes -->
                        <div style="grid-column: 1 / -1;">
                            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">ملاحظات</label>
                            <input type="text" name="items[INDEX][notes]"
                                   style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 8px;"
                                   placeholder="ملاحظات إضافية للعنصر...">
                        </div>
                    </div>
                </div>
            </template>

            <!-- Initial Item -->
            <div class="invoice-item" style="border: 2px solid #e2e8f0; border-radius: 12px; padding: 20px; margin-bottom: 15px; position: relative;">
                <button type="button" onclick="removeItem(this)" style="position: absolute; top: 10px; left: 10px; background: #f56565; color: white; border: none; border-radius: 50%; width: 30px; height: 30px; cursor: pointer; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-times"></i>
                </button>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
                    <!-- Product -->
                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">المنتج *</label>
                        <div class="custom-dropdown" data-name="items[0][product_id]" data-required="true" data-onchange="updateProductInfo">
                            <div class="dropdown-header">
                                <span class="dropdown-placeholder">اختر المنتج</span>
                                <i class="fas fa-chevron-down dropdown-arrow"></i>
                            </div>
                            <div class="dropdown-content">
                                <input type="text" class="dropdown-search" placeholder="البحث عن منتج...">
                                <div class="dropdown-options">
                                    <div class="dropdown-option" data-value="" data-price="" data-stock="" data-unit="">
                                        اختر المنتج
                                    </div>
                                    @foreach($products as $product)
                                        <div class="dropdown-option"
                                             data-value="{{ $product->id }}"
                                             data-price="{{ $product->selling_price }}"
                                             data-stock="{{ $product->current_stock }}"
                                             data-unit="{{ $product->unit }}">
                                            {{ $product->name }} ({{ $product->current_stock }} {{ $product->unit }})
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <select name="items[0][product_id]" required style="display: none;">
                                <option value="">اختر المنتج</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}"
                                            data-price="{{ $product->selling_price }}"
                                            data-stock="{{ $product->current_stock }}"
                                            data-unit="{{ $product->unit }}">
                                        {{ $product->name }} ({{ $product->current_stock }} {{ $product->unit }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Quantity -->
                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">الكمية *</label>
                        <input type="number" name="items[0][quantity]" min="1" step="1" required
                               style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 8px;"
                               placeholder="1" onchange="calculateItemTotal(this)">
                    </div>

                    <!-- Unit Price -->
                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">سعر الوحدة *</label>
                        <input type="number" name="items[0][unit_price]" min="0" step="0.01" required
                               style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 8px;"
                               placeholder="0.00" onchange="calculateItemTotal(this)">
                    </div>

                    <!-- Discount -->
                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">الخصم</label>
                        <div style="display: flex; gap: 5px;">
                            <input type="number" name="items[0][discount_amount]" min="0" step="0.01"
                                   style="flex: 1; padding: 10px; border: 2px solid #e2e8f0; border-radius: 8px;"
                                   placeholder="0.00" onchange="calculateItemTotal(this)">
                            <div class="custom-dropdown" data-name="items[0][discount_type]" style="width: 80px;">
                                <div class="dropdown-header" style="padding: 10px 8px;">
                                    <span class="dropdown-placeholder">اختر نوع الخصم</span>
                                    <i class="fas fa-chevron-down dropdown-arrow" style="font-size: 10px;"></i>
                                </div>
                                <div class="dropdown-content">
                                    <input type="text" class="dropdown-search" placeholder="البحث عن نوع الخصم...">
                                    <div class="dropdown-options">
                                        <div class="dropdown-option" data-value="">
                                            اختر نوع الخصم
                                        </div>
                                        <div class="dropdown-option" data-value="fixed" data-selected="true">
                                            ثابت
                                        </div>
                                        <div class="dropdown-option" data-value="percentage">
                                            %
                                        </div>
                                    </div>
                                </div>
                                <select name="items[0][discount_type]" style="display: none;">
                                    <option value="fixed">ثابت</option>
                                    <option value="percentage">%</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Total -->
                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">الإجمالي</label>
                        <input type="number" name="items[0][total_amount]" readonly
                               style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 8px; background: #f7fafc;"
                               placeholder="0.00">
                    </div>

                    <!-- Notes -->
                    <div style="grid-column: 1 / -1;">
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">ملاحظات</label>
                        <input type="text" name="items[0][notes]"
                               style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 8px;"
                               placeholder="ملاحظات إضافية للعنصر...">
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Item Button -->
        <button type="button" onclick="addItem()" style="background: linear-gradient(135deg, #9f7aea 0%, #805ad5 100%); color: white; padding: 12px 24px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px;">
            <i class="fas fa-plus"></i>
            إضافة عنصر جديد
        </button>
    </div>

    <!-- Invoice Totals -->
    <div class="content-card" style="margin-bottom: 25px;">
        <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-calculator" style="color: #059669; margin-left: 10px;"></i>
            إجماليات الفاتورة
        </h3>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
            <!-- Left Column - Additional Charges -->
            <div>
                <!-- Shipping Cost -->
                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">تكلفة الشحن</label>
                    <input type="number" name="shipping_cost" value="{{ old('shipping_cost', '0') }}" min="0" step="0.01"
                           style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;"
                           placeholder="0.00" onchange="calculateTotals()">
                </div>

                <!-- Additional Charges -->
                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">رسوم إضافية</label>
                    <input type="number" name="additional_charges" value="{{ old('additional_charges', '0') }}" min="0" step="0.01"
                           style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;"
                           placeholder="0.00" onchange="calculateTotals()">
                </div>

                <!-- Global Discount -->
                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">خصم إجمالي</label>
                    <div style="display: flex; gap: 5px;">
                        <input type="number" name="discount_amount" value="{{ old('discount_amount', '0') }}" min="0" step="0.01"
                               style="flex: 1; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;"
                               placeholder="0.00" onchange="calculateTotals()">
                        <div class="custom-dropdown" data-name="discount_type" style="width: 100px;">
                            <div class="dropdown-header" style="padding: 12px 8px;">
                                <span class="dropdown-placeholder">اختر نوع الخصم</span>
                                <i class="fas fa-chevron-down dropdown-arrow" style="font-size: 10px;"></i>
                            </div>
                            <div class="dropdown-content">
                                <input type="text" class="dropdown-search" placeholder="البحث عن نوع الخصم...">
                                <div class="dropdown-options">
                                    <div class="dropdown-option" data-value="">
                                        اختر نوع الخصم
                                    </div>
                                    <div class="dropdown-option" data-value="fixed" {{ old('discount_type', 'fixed') === 'fixed' ? 'data-selected="true"' : '' }}>
                                        ثابت
                                    </div>
                                    <div class="dropdown-option" data-value="percentage" {{ old('discount_type') === 'percentage' ? 'data-selected="true"' : '' }}>
                                        %
                                    </div>
                                </div>
                            </div>
                            <select name="discount_type" style="display: none;">
                                <option value="fixed" {{ old('discount_type', 'fixed') === 'fixed' ? 'selected' : '' }}>ثابت</option>
                                <option value="percentage" {{ old('discount_type') === 'percentage' ? 'selected' : '' }}>%</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Totals Display -->
            <div style="background: #f8fafc; border-radius: 12px; padding: 20px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; padding-bottom: 10px; border-bottom: 1px solid #e2e8f0;">
                    <span style="font-weight: 600; color: #4a5568;">المجموع الفرعي:</span>
                    <span id="subtotalDisplay" style="font-weight: 700; color: #2d3748;">0.00</span>
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; padding-bottom: 10px; border-bottom: 1px solid #e2e8f0;">
                    <span style="font-weight: 600; color: #4a5568;">الخصم:</span>
                    <span id="discountDisplay" style="font-weight: 700; color: #f56565;">0.00</span>
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; padding-bottom: 10px; border-bottom: 1px solid #e2e8f0;">
                    <span style="font-weight: 600; color: #4a5568;">الشحن والرسوم:</span>
                    <span id="chargesDisplay" style="font-weight: 700; color: #2d3748;">0.00</span>
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; padding-bottom: 10px; border-bottom: 1px solid #e2e8f0;">
                    <span style="font-weight: 600; color: #4a5568;">ضريبة القيمة المضافة (15%):</span>
                    <span id="taxDisplay" style="font-weight: 700; color: #2d3748;">0.00</span>
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; padding-bottom: 10px; border-bottom: 1px solid #e2e8f0;">
                    <span style="font-weight: 600; color: #4a5568;">المديونية السابقة:</span>
                    <span id="previousBalanceDisplay" style="font-weight: 700; color: #dc2626;">0.00</span>
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; padding-bottom: 10px; border-bottom: 2px solid #ed8936;">
                    <span style="font-weight: 600; color: #4a5568;">إجمالي الفاتورة:</span>
                    <span id="invoiceTotalDisplay" style="font-weight: 700; color: #2d3748;">0.00</span>
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center; padding: 15px; background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%); border-radius: 8px; color: white;">
                    <span style="font-weight: 700; font-size: 18px;">إجمالي المديونية:</span>
                    <span id="totalDebtDisplay" style="font-weight: 800; font-size: 20px;">0.00</span>
                </div>
            </div>
        </div>

        <!-- Hidden fields for totals -->
        <input type="hidden" name="subtotal_amount" id="subtotalAmount">
        <input type="hidden" name="tax_amount" id="taxAmount">
        <input type="hidden" name="total_amount" id="totalAmount">

        <!-- Hidden fields for customer credit info -->
        <input type="hidden" name="previous_balance" id="previousBalance" value="0">
        <input type="hidden" name="credit_limit" id="creditLimitField" value="0">
    </div>

    <!-- Notes and Free Samples -->
    <div class="content-card" style="margin-bottom: 25px; background: white; padding: 25px; border-radius: 15px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-sticky-note" style="color: #6366f1; margin-left: 10px;"></i>
            ملاحظات ومعلومات إضافية
        </h3>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <!-- Notes -->
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">ملاحظات الفاتورة</label>
                <textarea name="notes" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; height: 100px;" placeholder="ملاحظات إضافية للفاتورة...">{{ old('notes') }}</textarea>
                @error('notes')
                    <div style="color: #f56565; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>

            <!-- Free Samples -->
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">
                    <i class="fas fa-gift" style="color: #10b981; margin-left: 5px;"></i>
                    العينات المجانية
                </label>
                <textarea name="free_samples" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; height: 100px;" placeholder="قائمة العينات المجانية المرفقة مع الفاتورة...">{{ old('free_samples') }}</textarea>
                @error('free_samples')
                    <div style="color: #f56565; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
                <div style="font-size: 12px; color: #718096; margin-top: 5px;">
                    مثال: عينة دواء A - 5 حبات، عينة كريم B - أنبوب واحد
                </div>
            </div>
        </div>
    </div>

    <!-- Stock Check Options -->
    <div class="content-card" style="margin-bottom: 25px; background: #fff8e1; border-left: 4px solid #ff9800;">
        <h3 style="font-size: 18px; font-weight: 700; color: #e65100; margin-bottom: 15px; display: flex; align-items: center;">
            <i class="fas fa-warehouse" style="color: #ff9800; margin-left: 10px;"></i>
            إعدادات المخزون
        </h3>

        <div style="display: flex; align-items: center; gap: 10px;">
            <input type="checkbox" id="ignore_stock_check" name="ignore_stock_check" value="1" style="width: 18px; height: 18px;">
            <label for="ignore_stock_check" style="font-weight: 600; color: #e65100; cursor: pointer;">
                تجاهل التحقق من المخزون (السماح بالبيع حتى لو كان المخزون غير كافي)
            </label>
        </div>

        <div style="font-size: 12px; color: #bf360c; margin-top: 8px; display: flex; align-items: center; gap: 5px;">
            <i class="fas fa-exclamation-triangle"></i>
            <span>تحذير: تفعيل هذا الخيار سيسمح بإنشاء فواتير حتى لو كان المخزون غير كافي</span>
        </div>
    </div>

    <!-- Form Actions -->
    <div style="display: flex; gap: 15px; justify-content: flex-end;">
        <a href="{{ route('tenant.sales.invoices.index') }}" style="padding: 12px 24px; border: 2px solid #e2e8f0; border-radius: 8px; background: white; color: #4a5568; font-weight: 600; text-decoration: none; display: flex; align-items: center; gap: 8px;">
            <i class="fas fa-times"></i>
            إلغاء
        </a>
        <button type="submit" name="action" value="draft" style="padding: 12px 24px; border: 2px solid #6b7280; border-radius: 8px; background: white; color: #6b7280; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px;">
            <i class="fas fa-save"></i>
            حفظ كمسودة
        </button>
        <button type="submit" name="action" value="finalize" class="btn-orange" style="padding: 12px 24px;">
            <i class="fas fa-check"></i>
            إنهاء الفاتورة
        </button>
    </div>
</form>

@push('scripts')
<script>
let itemIndex = 1;

// Main DOMContentLoaded event handler
document.addEventListener('DOMContentLoaded', function() {
    // Handle ignore stock check warning
    const ignoreStockCheckbox = document.getElementById('ignore_stock_check');
    if (ignoreStockCheckbox) {
        ignoreStockCheckbox.addEventListener('change', function() {
            if (this.checked) {
                if (!confirm('تحذير: هل أنت متأكد من تجاهل التحقق من المخزون؟ هذا سيسمح بإنشاء فواتير حتى لو كان المخزون غير كافي.')) {
                    this.checked = false;
                }
            }
        });
    }

    // Initialize custom dropdowns
    console.log('Initializing custom dropdowns...');
    initializeCustomDropdowns();

    // Initialize calculations
    calculateTotals();

    // Add event listeners for currency changes
    const currencyField = document.getElementById('currencyField');
    if (currencyField) {
        currencyField.addEventListener('change', calculateTotals);
    }

    console.log('Page initialization complete');
});

// Update customer information when customer is selected
function updateCustomerInfo() {
    const customerSelect = document.querySelector('select[name="customer_id"]');
    const selectedOption = customerSelect.options[customerSelect.selectedIndex];
    const creditInfoDiv = document.getElementById('customerCreditInfo');

    if (selectedOption.value) {
        const paymentTerms = selectedOption.dataset.paymentTerms;
        const currency = selectedOption.dataset.currency;
        const creditLimit = parseFloat(selectedOption.dataset.creditLimit) || 0;
        const currentBalance = parseFloat(selectedOption.dataset.currentBalance) || 0;

        // Update currency
        document.getElementById('currencyField').value = currency;

        // Update due date based on payment terms
        const invoiceDate = new Date(document.querySelector('input[name="invoice_date"]').value);
        let daysToAdd = 0;

        switch(paymentTerms) {
            case 'credit_7': daysToAdd = 7; break;
            case 'credit_15': daysToAdd = 15; break;
            case 'credit_30': daysToAdd = 30; break;
            case 'credit_60': daysToAdd = 60; break;
            case 'credit_90': daysToAdd = 90; break;
            default: daysToAdd = 0; break;
        }

        const dueDate = new Date(invoiceDate);
        dueDate.setDate(dueDate.getDate() + daysToAdd);
        document.getElementById('dueDateField').value = dueDate.toISOString().split('T')[0];

        // Show and update credit information
        creditInfoDiv.style.display = 'block';
        updateCreditInfo(creditLimit, currentBalance, currency);

        // Update hidden fields for form submission
        document.getElementById('previousBalance').value = currentBalance;
        document.getElementById('creditLimitField').value = creditLimit;
    } else {
        // Hide credit information if no customer selected
        creditInfoDiv.style.display = 'none';
        document.getElementById('previousBalance').value = 0;
        document.getElementById('creditLimitField').value = 0;
    }
}

// Update credit information display
function updateCreditInfo(creditLimit, currentBalance, currency) {
    const currencySymbol = getCurrencySymbol(currency);
    const availableCredit = Math.max(0, creditLimit - currentBalance);

    // Get current invoice total
    const invoiceTotal = parseFloat(document.getElementById('totalAmount').value) || 0;
    const balanceAfterInvoice = currentBalance + invoiceTotal;
    const remainingCredit = creditLimit - balanceAfterInvoice;

    // Update displays
    document.getElementById('currentBalanceDisplay').textContent = currentBalance.toFixed(2) + ' ' + currencySymbol;
    document.getElementById('creditLimitDisplay').textContent = creditLimit.toFixed(2) + ' ' + currencySymbol;
    document.getElementById('availableCreditDisplay').textContent = availableCredit.toFixed(2) + ' ' + currencySymbol;
    document.getElementById('balanceAfterInvoiceDisplay').textContent = balanceAfterInvoice.toFixed(2) + ' ' + currencySymbol;

    // Update credit status message and warning
    const creditWarning = document.getElementById('creditWarning');
    const creditStatusMessage = document.getElementById('creditStatusMessage');
    const creditWarningMessage = document.getElementById('creditWarningMessage');

    if (balanceAfterInvoice > creditLimit) {
        // Exceeds credit limit
        creditWarning.style.display = 'block';
        creditStatusMessage.textContent = 'تجاوز سقف المديونية';
        creditStatusMessage.style.color = '#dc2626';
        creditWarningMessage.textContent = `هذه الفاتورة ستتجاوز سقف المديونية بمبلغ ${(balanceAfterInvoice - creditLimit).toFixed(2)} ${currencySymbol}`;

        // Change balance after invoice color to red
        document.getElementById('balanceAfterInvoiceDisplay').style.color = '#dc2626';
    } else if (remainingCredit < (creditLimit * 0.1)) {
        // Close to credit limit (within 10%)
        creditWarning.style.display = 'block';
        creditStatusMessage.textContent = 'قريب من سقف المديونية';
        creditStatusMessage.style.color = '#d97706';
        creditWarningMessage.textContent = `المتبقي من سقف المديونية: ${remainingCredit.toFixed(2)} ${currencySymbol}`;

        // Change balance after invoice color to orange
        document.getElementById('balanceAfterInvoiceDisplay').style.color = '#d97706';
    } else {
        // Within safe limits
        creditWarning.style.display = 'none';
        creditStatusMessage.textContent = 'ضمن الحدود المسموحة';
        creditStatusMessage.style.color = '#059669';

        // Change balance after invoice color to green
        document.getElementById('balanceAfterInvoiceDisplay').style.color = '#059669';
    }
}

// Update product information when product is selected
function updateProductInfo(selectElement) {
    console.log('updateProductInfo called with:', selectElement);

    let selectedOption = null;
    let itemDiv = null;

    // Handle both custom dropdown and regular select
    if (selectElement.tagName === 'SELECT') {
        // Regular select element
        selectedOption = selectElement.options[selectElement.selectedIndex];
        itemDiv = selectElement.closest('.invoice-item');
    } else {
        // Custom dropdown - find the hidden select
        const dropdown = selectElement.closest('.custom-dropdown');
        if (dropdown) {
            const hiddenSelect = dropdown.querySelector('select[name*="[product_id]"]');
            if (hiddenSelect) {
                selectedOption = hiddenSelect.options[hiddenSelect.selectedIndex];
                itemDiv = dropdown.closest('.invoice-item');
            }
        }
    }

    if (!selectedOption || !itemDiv) {
        console.log('Could not find selected option or item div');
        return;
    }

    console.log('Selected option:', selectedOption);
    console.log('Selected value:', selectedOption.value);

    if (selectedOption.value) {
        const price = selectedOption.dataset.price || selectedOption.getAttribute('data-price');
        const stock = selectedOption.dataset.stock || selectedOption.getAttribute('data-stock');
        const unit = selectedOption.dataset.unit || selectedOption.getAttribute('data-unit');

        console.log('Product data:', { price, stock, unit });

        // Update unit price
        const unitPriceInput = itemDiv.querySelector('input[name*="[unit_price]"]');
        if (unitPriceInput && price) {
            unitPriceInput.value = price;
            console.log('Updated unit price to:', price);
        }

        // Update quantity max
        const quantityInput = itemDiv.querySelector('input[name*="[quantity]"]');
        if (quantityInput && stock) {
            quantityInput.max = stock;
            console.log('Updated max quantity to:', stock);
        }

        // Update unit display if exists
        const unitDisplay = itemDiv.querySelector('.unit-display');
        if (unitDisplay && unit) {
            unitDisplay.textContent = unit;
        }

        // Calculate total
        if (unitPriceInput) {
            calculateItemTotal(unitPriceInput);
        }
    } else {
        // Clear fields when no product selected
        const unitPriceInput = itemDiv.querySelector('input[name*="[unit_price]"]');
        const quantityInput = itemDiv.querySelector('input[name*="[quantity]"]');

        if (unitPriceInput) unitPriceInput.value = '';
        if (quantityInput) {
            quantityInput.value = '';
            quantityInput.removeAttribute('max');
        }

        calculateTotals();
    }
}

// Calculate item total
function calculateItemTotal(element) {
    const itemDiv = element.closest('.invoice-item');
    const quantity = parseFloat(itemDiv.querySelector('input[name*="[quantity]"]').value) || 0;
    const unitPrice = parseFloat(itemDiv.querySelector('input[name*="[unit_price]"]').value) || 0;
    const discountAmount = parseFloat(itemDiv.querySelector('input[name*="[discount_amount]"]').value) || 0;
    const discountType = itemDiv.querySelector('select[name*="[discount_type]"]').value;

    // Check stock availability
    const productSelect = itemDiv.querySelector('select[name*="[product_id]"]');
    const ignoreStockCheck = document.getElementById('ignore_stock_check').checked;

    let selectedOption = null;
    let stock = 0;

    if (productSelect) {
        selectedOption = productSelect.options[productSelect.selectedIndex];
        if (selectedOption && selectedOption.value) {
            stock = parseFloat(selectedOption.dataset.stock || selectedOption.getAttribute('data-stock')) || 0;
        }
    }

    if (selectedOption && selectedOption.value && !ignoreStockCheck) {
        const quantityInput = itemDiv.querySelector('input[name*="[quantity]"]');

        if (quantity > stock) {
            // Show warning
            let warningDiv = itemDiv.querySelector('.stock-warning');
            if (!warningDiv) {
                warningDiv = document.createElement('div');
                warningDiv.className = 'stock-warning';
                warningDiv.style.cssText = 'color: #f56565; font-size: 12px; margin-top: 5px; display: flex; align-items: center; gap: 5px;';
                quantityInput.parentNode.appendChild(warningDiv);
            }
            warningDiv.innerHTML = '<i class="fas fa-exclamation-triangle"></i> تحذير: الكمية المطلوبة (' + quantity + ') أكبر من المخزون المتاح (' + stock + ')';
            quantityInput.style.borderColor = '#f56565';
        } else {
            // Remove warning
            const warningDiv = itemDiv.querySelector('.stock-warning');
            if (warningDiv) {
                warningDiv.remove();
            }
            quantityInput.style.borderColor = '#e2e8f0';
        }
    }

    let subtotal = quantity * unitPrice;
    let discount = 0;

    if (discountType === 'percentage') {
        discount = subtotal * (discountAmount / 100);
    } else {
        discount = discountAmount;
    }

    const total = subtotal - discount;
    itemDiv.querySelector('input[name*="[total_amount]"]').value = total.toFixed(2);

    calculateTotals();
}

// Add new item
function addItem() {
    const template = document.getElementById('itemTemplate');
    const newItem = template.content.cloneNode(true);

    // Update all name attributes with new index
    const inputs = newItem.querySelectorAll('input, select');
    inputs.forEach(input => {
        if (input.name) {
            input.name = input.name.replace('INDEX', itemIndex);
        }
    });

    // Update custom dropdown data-name attributes
    const customDropdowns = newItem.querySelectorAll('.custom-dropdown');
    customDropdowns.forEach(dropdown => {
        if (dropdown.dataset.name) {
            dropdown.dataset.name = dropdown.dataset.name.replace('INDEX', itemIndex);
        }
    });

    // Append to container
    const container = document.getElementById('invoiceItems');
    container.appendChild(newItem);

    // Initialize custom dropdowns for the new item
    const newDropdowns = container.querySelectorAll('.invoice-item:last-child .custom-dropdown');
    newDropdowns.forEach(dropdown => {
        initializeCustomDropdown(dropdown);
    });

    itemIndex++;

    console.log('Added new item with index:', itemIndex - 1);
}

// Initialize a single custom dropdown
function initializeCustomDropdown(dropdown) {
    console.log('Initializing dropdown:', dropdown);

    // Create hidden select if it doesn't exist
    let hiddenSelect = dropdown.querySelector('select');
    if (!hiddenSelect) {
        hiddenSelect = document.createElement('select');
        hiddenSelect.name = dropdown.dataset.name;
        hiddenSelect.style.display = 'none';
        if (dropdown.dataset.required === 'true') {
            hiddenSelect.required = true;
        }

        // Add options from dropdown-options
        const options = dropdown.querySelectorAll('.dropdown-option');
        options.forEach(option => {
            const selectOption = document.createElement('option');
            selectOption.value = option.dataset.value || '';
            selectOption.textContent = option.textContent.trim();

            // Copy all data attributes
            Object.keys(option.dataset).forEach(key => {
                selectOption.setAttribute('data-' + key, option.dataset[key]);
            });

            hiddenSelect.appendChild(selectOption);
        });

        dropdown.appendChild(hiddenSelect);
        console.log('Created hidden select with', hiddenSelect.options.length, 'options');
    }

    // Mark header as initialized (event delegation will handle clicks)
    const header = dropdown.querySelector('.dropdown-header');
    if (header) {
        header.setAttribute('data-initialized', 'true');
    }

    // Mark options as initialized (event delegation will handle clicks)
    const options = dropdown.querySelectorAll('.dropdown-option');
    options.forEach(option => {
        option.setAttribute('data-initialized', 'true');
    });

    // Add search functionality
    const searchInput = dropdown.querySelector('.dropdown-search');
    if (searchInput && !searchInput.hasAttribute('data-initialized')) {
        searchInput.addEventListener('input', function() {
            filterDropdownOptions(this);
        });

        // Handle keyboard navigation
        searchInput.addEventListener('keydown', function(e) {
            const options = dropdown.querySelectorAll('.dropdown-option:not([style*="display: none"])');
            let currentIndex = -1;

            // Find currently highlighted option
            options.forEach((option, index) => {
                if (option.classList.contains('highlighted')) {
                    currentIndex = index;
                }
            });

            switch(e.key) {
                case 'ArrowDown':
                    e.preventDefault();
                    if (currentIndex < options.length - 1) {
                        if (currentIndex >= 0) options[currentIndex].classList.remove('highlighted');
                        options[currentIndex + 1].classList.add('highlighted');
                    }
                    break;
                case 'ArrowUp':
                    e.preventDefault();
                    if (currentIndex > 0) {
                        options[currentIndex].classList.remove('highlighted');
                        options[currentIndex - 1].classList.add('highlighted');
                    }
                    break;
                case 'Enter':
                    e.preventDefault();
                    if (currentIndex >= 0) {
                        selectOption(options[currentIndex]);
                    }
                    break;
                case 'Escape':
                    e.preventDefault();
                    dropdown.querySelector('.dropdown-content').classList.remove('show');
                    dropdown.querySelector('.dropdown-header').classList.remove('active');
                    break;
            }
        });

        searchInput.setAttribute('data-initialized', 'true');
    }
}

// Filter dropdown options based on search
function filterDropdownOptions(searchInput) {
    const dropdown = searchInput.closest('.custom-dropdown');
    const options = dropdown.querySelectorAll('.dropdown-option');
    const searchTerm = searchInput.value.toLowerCase().trim();
    let visibleCount = 0;

    options.forEach(option => {
        const text = option.textContent.toLowerCase();
        const isEmptyOption = option.dataset.value === '';
        const shouldShow = isEmptyOption || text.includes(searchTerm);

        option.style.display = shouldShow ? 'block' : 'none';
        if (shouldShow && !isEmptyOption) {
            visibleCount++;
        }
    });

    // Show/hide no results message
    let noResultsDiv = dropdown.querySelector('.dropdown-no-results');
    if (visibleCount === 0 && searchTerm.length > 0) {
        if (!noResultsDiv) {
            noResultsDiv = document.createElement('div');
            noResultsDiv.className = 'dropdown-no-results';
            noResultsDiv.textContent = 'لا توجد نتائج مطابقة';
            dropdown.querySelector('.dropdown-options').appendChild(noResultsDiv);
        }
        noResultsDiv.style.display = 'block';
    } else if (noResultsDiv) {
        noResultsDiv.style.display = 'none';
    }
}

// Remove item
function removeItem(button) {
    const items = document.querySelectorAll('.invoice-item');
    if (items.length > 1) {
        button.closest('.invoice-item').remove();
        calculateTotals();
    } else {
        alert('يجب أن تحتوي الفاتورة على عنصر واحد على الأقل');
    }
}

// Calculate totals
function calculateTotals() {
    const items = document.querySelectorAll('.invoice-item:not(template .invoice-item)');
    let subtotal = 0;

    items.forEach(item => {
        // Skip if this is inside a template
        if (item.closest('template')) return;

        const totalInput = item.querySelector('input[name*="[total_amount]"]');
        if (totalInput) {
            const total = parseFloat(totalInput.value) || 0;
            subtotal += total;
        }
    });

    const shippingCost = parseFloat(document.querySelector('input[name="shipping_cost"]').value) || 0;
    const additionalCharges = parseFloat(document.querySelector('input[name="additional_charges"]').value) || 0;
    const discountAmount = parseFloat(document.querySelector('input[name="discount_amount"]').value) || 0;
    const discountType = document.querySelector('select[name="discount_type"]').value;

    let globalDiscount = 0;
    if (discountType === 'percentage') {
        globalDiscount = subtotal * (discountAmount / 100);
    } else {
        globalDiscount = discountAmount;
    }

    const afterDiscount = subtotal - globalDiscount;
    const charges = shippingCost + additionalCharges;
    const taxableAmount = afterDiscount + charges;
    const taxAmount = taxableAmount * 0.15; // 15% VAT
    const total = taxableAmount + taxAmount;

    // Get previous balance
    const previousBalance = parseFloat(document.getElementById('previousBalance').value) || 0;
    const totalDebt = previousBalance + total;

    // Update displays
    const currency = document.getElementById('currencyField').value;
    const currencySymbol = getCurrencySymbol(currency);

    document.getElementById('subtotalDisplay').textContent = subtotal.toFixed(2) + ' ' + currencySymbol;
    document.getElementById('discountDisplay').textContent = globalDiscount.toFixed(2) + ' ' + currencySymbol;
    document.getElementById('chargesDisplay').textContent = charges.toFixed(2) + ' ' + currencySymbol;
    document.getElementById('taxDisplay').textContent = taxAmount.toFixed(2) + ' ' + currencySymbol;
    document.getElementById('previousBalanceDisplay').textContent = previousBalance.toFixed(2) + ' ' + currencySymbol;
    document.getElementById('invoiceTotalDisplay').textContent = total.toFixed(2) + ' ' + currencySymbol;
    document.getElementById('totalDebtDisplay').textContent = totalDebt.toFixed(2) + ' ' + currencySymbol;

    // Update hidden fields
    document.getElementById('subtotalAmount').value = subtotal.toFixed(2);
    document.getElementById('taxAmount').value = taxAmount.toFixed(2);
    document.getElementById('totalAmount').value = total.toFixed(2);

    // Update credit information if customer is selected
    const customerSelect = document.querySelector('select[name="customer_id"]');
    if (customerSelect.value) {
        const selectedOption = customerSelect.options[customerSelect.selectedIndex];
        const creditLimit = parseFloat(selectedOption.dataset.creditLimit) || 0;
        const currentBalance = parseFloat(selectedOption.dataset.currentBalance) || 0;
        updateCreditInfo(creditLimit, currentBalance, currency);
    }
}

// Get currency symbol
function getCurrencySymbol(currency) {
    const symbols = {
        'IQD': 'د.ع',
        'USD': '$',
        'SAR': 'ر.س',
        'AED': 'د.إ',
        'EUR': '€'
    };
    return symbols[currency] || currency;
}

// Load order items when sales order is selected
function loadOrderItems() {
    const orderSelect = document.querySelector('select[name="sales_order_id"]');
    if (orderSelect.value) {
        // This would typically make an AJAX call to load order items
        // For now, we'll just show a message
        console.log('Loading order items for order ID:', orderSelect.value);
    }
}

    // Add form validation before submit
    document.getElementById('invoiceForm').addEventListener('submit', function(e) {
        console.log('Form submission started');

        // Check if at least one item exists (excluding template)
        const items = document.querySelectorAll('.invoice-item');
        const validItems = Array.from(items).filter(item => !item.closest('template'));

        console.log('Found items:', validItems.length);

        if (validItems.length === 0) {
            e.preventDefault();
            alert('يجب إضافة عنصر واحد على الأقل للفاتورة');
            return false;
        }

        // Check if all required fields are filled (excluding template)
        let hasErrors = false;
        let errorMessages = [];

        validItems.forEach((item, index) => {
            const productSelect = item.querySelector('select[name*="[product_id]"]');
            const quantityInput = item.querySelector('input[name*="[quantity]"]');
            const unitPriceInput = item.querySelector('input[name*="[unit_price]"]');

            console.log(`Item ${index + 1}:`, {
                product: productSelect ? productSelect.value : 'not found',
                quantity: quantityInput ? quantityInput.value : 'not found',
                unitPrice: unitPriceInput ? unitPriceInput.value : 'not found'
            });

            if (!productSelect || !productSelect.value) {
                hasErrors = true;
                errorMessages.push(`العنصر ${index + 1}: يجب اختيار منتج`);
            }
            if (!quantityInput || !quantityInput.value) {
                hasErrors = true;
                errorMessages.push(`العنصر ${index + 1}: يجب إدخال الكمية`);
            }
            if (!unitPriceInput || !unitPriceInput.value) {
                hasErrors = true;
                errorMessages.push(`العنصر ${index + 1}: يجب إدخال سعر الوحدة`);
            }
        });

        if (hasErrors) {
            e.preventDefault();
            alert('يرجى ملء جميع الحقول المطلوبة:\n' + errorMessages.join('\n'));
            return false;
        }

        // Show loading state
        const submitButton = e.submitter;
        if (submitButton) {
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري المعالجة...';
        }

        console.log('Form validation passed, submitting...');
        return true;
    });

// Custom Dropdown Functions
function toggleDropdown(header) {
    console.log('toggleDropdown called');
    const dropdown = header.closest('.custom-dropdown');
    const content = dropdown.querySelector('.dropdown-content');
    const arrow = header.querySelector('.dropdown-arrow');

    // Check if header is disabled
    if (header.classList.contains('disabled') || header.style.pointerEvents === 'none') {
        console.log('Dropdown is disabled, ignoring click');
        return;
    }

    // Close all other dropdowns
    document.querySelectorAll('.custom-dropdown .dropdown-content.show').forEach(otherContent => {
        if (otherContent !== content) {
            otherContent.classList.remove('show');
            otherContent.closest('.custom-dropdown').querySelector('.dropdown-header').classList.remove('active');
        }
    });

    // Toggle current dropdown
    const isCurrentlyOpen = content.classList.contains('show');

    if (isCurrentlyOpen) {
        content.classList.remove('show');
        header.classList.remove('active');
    } else {
        content.classList.add('show');
        header.classList.add('active');

        // Focus search input if dropdown is opened
        const searchInput = content.querySelector('.dropdown-search');
        if (searchInput) {
            setTimeout(() => searchInput.focus(), 100);
        }
    }

    console.log('Dropdown toggled, now open:', !isCurrentlyOpen);
}

function selectOption(option) {
    const dropdown = option.closest('.custom-dropdown');
    const header = dropdown.querySelector('.dropdown-header');
    const placeholder = header.querySelector('.dropdown-placeholder');
    const content = dropdown.querySelector('.dropdown-content');
    const hiddenSelect = dropdown.querySelector('select');

    console.log('selectOption called for:', option.textContent.trim());

    // Update visual display
    placeholder.textContent = option.textContent.trim();
    placeholder.classList.remove('dropdown-placeholder');

    // Update hidden select
    hiddenSelect.value = option.dataset.value;

    // Copy all data attributes to the select option
    const selectOption = hiddenSelect.querySelector(`option[value="${option.dataset.value}"]`);
    if (selectOption) {
        Object.keys(option.dataset).forEach(key => {
            if (key !== 'value') {
                selectOption.setAttribute('data-' + key, option.dataset[key]);
            }
        });
        console.log('Updated select option with data:', selectOption.dataset);
    }

    // Mark as selected
    dropdown.querySelectorAll('.dropdown-option').forEach(opt => {
        opt.classList.remove('selected');
        opt.removeAttribute('data-selected');
    });
    option.classList.add('selected');
    option.setAttribute('data-selected', 'true');

    // Close dropdown
    content.classList.remove('show');
    header.classList.remove('active');

    // Trigger change event
    const changeEvent = new Event('change', { bubbles: true });
    hiddenSelect.dispatchEvent(changeEvent);

    // Re-enable dropdown functionality
    setTimeout(() => {
        header.style.pointerEvents = 'auto';
        header.classList.remove('disabled');
    }, 100);

    // Handle custom onchange functions
    const onchangeFunction = dropdown.dataset.onchange;
    if (onchangeFunction) {
        if (onchangeFunction === 'updateCustomerInfo') {
            updateCustomerInfo();
        } else if (onchangeFunction === 'updateProductInfo') {
            updateProductInfo(hiddenSelect);
        } else if (onchangeFunction === 'calculateItemTotal') {
            calculateItemTotal(hiddenSelect);
        } else if (onchangeFunction === 'calculateTotals') {
            calculateTotals();
        }
    }
}

function filterOptions(searchInput) {
    const searchTerm = searchInput.value.toLowerCase();
    const options = searchInput.closest('.dropdown-content').querySelectorAll('.dropdown-option');

    options.forEach(option => {
        const text = option.textContent.toLowerCase();
        if (text.includes(searchTerm)) {
            option.style.display = 'block';
        } else {
            option.style.display = 'none';
        }
    });
}

function initializeCustomDropdowns() {
    console.log('initializeCustomDropdowns called');
    const dropdowns = document.querySelectorAll('.custom-dropdown:not([data-initialized])');
    console.log('Found', dropdowns.length, 'uninitialized custom dropdowns');

    // Initialize each dropdown
    dropdowns.forEach(dropdown => {
        initializeCustomDropdown(dropdown);
        dropdown.setAttribute('data-initialized', 'true');
    });

    // Close dropdowns when clicking outside (only add once)
    if (!document.body.hasAttribute('data-dropdown-listener-added')) {
        document.body.setAttribute('data-dropdown-listener-added', 'true');
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.custom-dropdown')) {
                document.querySelectorAll('.dropdown-content.show').forEach(content => {
                    content.classList.remove('show');
                    content.closest('.custom-dropdown').querySelector('.dropdown-header').classList.remove('active');
                });
            }
        });
    }

    console.log('Custom dropdowns initialization complete');
}

// Update addItem function to initialize dropdowns for new items
function addItem() {
    const template = document.getElementById('itemTemplate');
    const clone = template.content.cloneNode(true);
    const itemsContainer = document.getElementById('invoiceItems');
    const itemCount = itemsContainer.querySelectorAll('.invoice-item').length;

    // Update name attributes
    clone.querySelectorAll('[name*="INDEX"]').forEach(element => {
        element.name = element.name.replace('INDEX', itemCount);
    });

    // Update custom dropdown data-name attributes
    clone.querySelectorAll('.custom-dropdown[data-name*="INDEX"]').forEach(dropdown => {
        dropdown.dataset.name = dropdown.dataset.name.replace('INDEX', itemCount);
    });

    itemsContainer.appendChild(clone);

    // Initialize dropdowns for the new item
    initializeCustomDropdowns();
}

// Setup event delegation for better dropdown functionality
document.addEventListener('DOMContentLoaded', function() {
    // Event delegation for dropdown headers - this ensures clicks work even after DOM changes
    document.addEventListener('click', function(e) {
        const header = e.target.closest('.dropdown-header');
        if (header) {
            e.preventDefault();
            e.stopPropagation();
            console.log('Header clicked via delegation');
            toggleDropdown(header);
        }
    });

    // Event delegation for dropdown options
    document.addEventListener('click', function(e) {
        const option = e.target.closest('.dropdown-option');
        if (option) {
            e.preventDefault();
            e.stopPropagation();
            console.log('Option clicked via delegation:', option.textContent.trim());
            selectOption(option);
        }
    });

    // Close dropdowns when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.custom-dropdown')) {
            document.querySelectorAll('.dropdown-content.show').forEach(content => {
                content.classList.remove('show');
                content.closest('.custom-dropdown').querySelector('.dropdown-header').classList.remove('active');
            });
        }
    });

    console.log('Event delegation setup complete');
});
</script>
@endpush
@endsection