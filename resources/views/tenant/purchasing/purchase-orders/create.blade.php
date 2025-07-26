@extends('layouts.tenant')

@section('title', 'إنشاء أمر شراء جديد')

@section('content')
<div class="page-header">
    <div class="page-title">
        <h1><i class="fas fa-clipboard-list"></i> إنشاء أمر شراء جديد</h1>
        <p>إنشاء أمر شراء رسمي للمورد</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('tenant.purchasing.purchase-orders.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-right"></i>
            العودة للقائمة
        </a>
    </div>
</div>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('tenant.purchasing.purchase-orders.store') }}" id="purchaseOrderForm">
    @csrf
    
    <!-- Basic Information -->
    <div class="content-card" style="margin-bottom: 25px;">
        <h3 style="margin-bottom: 20px; color: #2d3748; font-weight: 700;">
            <i class="fas fa-info-circle" style="color: #3b82f6; margin-left: 8px;"></i>
            المعلومات الأساسية
        </h3>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">المورد *</label>
                <select name="supplier_id" required style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;">
                    <option value="">اختر المورد</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                            {{ $supplier->name }} ({{ $supplier->supplier_code }})
                        </option>
                    @endforeach
                </select>
                @error('supplier_id')
                    <div style="color: #dc2626; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">طلب الشراء (اختياري)</label>
                <select name="purchase_request_id" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;">
                    <option value="">اختر طلب الشراء</option>
                    @foreach($purchaseRequests as $request)
                        <option value="{{ $request->id }}" {{ old('purchase_request_id') == $request->id ? 'selected' : '' }}>
                            {{ $request->request_number }} - {{ $request->description }}
                        </option>
                    @endforeach
                </select>
                @error('purchase_request_id')
                    <div style="color: #dc2626; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">تاريخ الأمر *</label>
                <input type="date" name="order_date" value="{{ old('order_date', date('Y-m-d')) }}" required style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;">
                @error('order_date')
                    <div style="color: #dc2626; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">تاريخ التسليم المتوقع *</label>
                <input type="date" name="expected_delivery_date" value="{{ old('expected_delivery_date') }}" required style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;">
                @error('expected_delivery_date')
                    <div style="color: #dc2626; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">شروط الدفع *</label>
                <select name="payment_terms" required style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;">
                    <option value="">اختر شروط الدفع</option>
                    <option value="cash" {{ old('payment_terms') === 'cash' ? 'selected' : '' }}>نقداً</option>
                    <option value="credit_7" {{ old('payment_terms') === 'credit_7' ? 'selected' : '' }}>آجل 7 أيام</option>
                    <option value="credit_15" {{ old('payment_terms') === 'credit_15' ? 'selected' : '' }}>آجل 15 يوم</option>
                    <option value="credit_30" {{ old('payment_terms') === 'credit_30' ? 'selected' : '' }}>آجل 30 يوم</option>
                    <option value="credit_45" {{ old('payment_terms') === 'credit_45' ? 'selected' : '' }}>آجل 45 يوم</option>
                    <option value="credit_60" {{ old('payment_terms') === 'credit_60' ? 'selected' : '' }}>آجل 60 يوم</option>
                    <option value="credit_90" {{ old('payment_terms') === 'credit_90' ? 'selected' : '' }}>آجل 90 يوم</option>
                    <option value="custom" {{ old('payment_terms') === 'custom' ? 'selected' : '' }}>مخصص</option>
                </select>
                @error('payment_terms')
                    <div style="color: #dc2626; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">العملة *</label>
                <select name="currency" required style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;">
                    <option value="">اختر العملة</option>
                    <option value="IQD" {{ old('currency', 'IQD') === 'IQD' ? 'selected' : '' }}>دينار عراقي (IQD)</option>
                    <option value="USD" {{ old('currency') === 'USD' ? 'selected' : '' }}>دولار أمريكي (USD)</option>
                    <option value="EUR" {{ old('currency') === 'EUR' ? 'selected' : '' }}>يورو (EUR)</option>
                    <option value="SAR" {{ old('currency') === 'SAR' ? 'selected' : '' }}>ريال سعودي (SAR)</option>
                    <option value="AED" {{ old('currency') === 'AED' ? 'selected' : '' }}>درهم إماراتي (AED)</option>
                </select>
                @error('currency')
                    <div style="color: #dc2626; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
        </div>
        
        <div style="margin-top: 20px;">
            <label style="display: flex; align-items: center; gap: 8px; font-weight: 600; color: #4a5568;">
                <input type="checkbox" name="is_urgent" value="1" {{ old('is_urgent') ? 'checked' : '' }} style="width: 18px; height: 18px;">
                أمر عاجل
            </label>
        </div>
    </div>
    
    <!-- Delivery Information -->
    <div class="content-card" style="margin-bottom: 25px;">
        <h3 style="margin-bottom: 20px; color: #2d3748; font-weight: 700;">
            <i class="fas fa-truck" style="color: #10b981; margin-left: 8px;"></i>
            معلومات التسليم
        </h3>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">عنوان التسليم *</label>
                <textarea name="delivery_address" required style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; min-height: 80px;" placeholder="أدخل عنوان التسليم الكامل">{{ old('delivery_address') }}</textarea>
                @error('delivery_address')
                    <div style="color: #dc2626; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">جهة الاتصال *</label>
                <input type="text" name="delivery_contact" value="{{ old('delivery_contact') }}" required style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;" placeholder="اسم جهة الاتصال">
                @error('delivery_contact')
                    <div style="color: #dc2626; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">رقم الهاتف *</label>
                <input type="tel" name="delivery_phone" value="{{ old('delivery_phone') }}" required style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;" placeholder="رقم هاتف جهة الاتصال">
                @error('delivery_phone')
                    <div style="color: #dc2626; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">تعليمات التسليم</label>
                <textarea name="delivery_instructions" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; min-height: 80px;" placeholder="تعليمات خاصة للتسليم">{{ old('delivery_instructions') }}</textarea>
                @error('delivery_instructions')
                    <div style="color: #dc2626; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>
    
    <!-- Products Section -->
    <div class="content-card" style="margin-bottom: 25px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3 style="margin: 0; color: #2d3748; font-weight: 700;">
                <i class="fas fa-boxes" style="color: #f59e0b; margin-left: 8px;"></i>
                المنتجات
            </h3>
            <button type="button" onclick="addProductRow()" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 10px 16px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                <i class="fas fa-plus" style="margin-left: 8px;"></i>
                إضافة منتج
            </button>
        </div>
        
        <div id="products-container">
            <!-- First product row -->
            <div class="product-row" style="background: #f8fafc; padding: 20px; border-radius: 8px; margin-bottom: 15px; border: 2px solid #e2e8f0;">
                <div style="display: grid; grid-template-columns: 2fr 1fr 1fr 1fr 1fr auto; gap: 15px; align-items: end;">
                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">المنتج *</label>
                        <select name="items[0][product_id]" required style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;" onchange="updateProductInfo(this, 0)">
                            <option value="">اختر المنتج</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" data-price="{{ $product->purchase_price ?? $product->selling_price }}" data-unit="{{ $product->unit }}">
                                    {{ $product->name }} ({{ $product->current_stock }} {{ $product->unit }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">الكمية *</label>
                        <input type="number" name="items[0][quantity]" step="0.01" min="0.01" required style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;" onchange="calculateRowTotal(0)">
                    </div>
                    
                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">سعر الوحدة *</label>
                        <input type="number" name="items[0][unit_price]" step="0.01" min="0" required style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;" onchange="calculateRowTotal(0)">
                    </div>
                    
                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">الخصم</label>
                        <input type="number" name="items[0][discount_amount]" step="0.01" min="0" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;" onchange="calculateRowTotal(0)">
                    </div>
                    
                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">الضريبة %</label>
                        <input type="number" name="items[0][tax_percentage]" step="0.01" min="0" max="100" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;" onchange="calculateRowTotal(0)">
                    </div>
                    
                    <div>
                        <button type="button" onclick="removeProductRow(this)" style="background: #ef4444; color: white; padding: 12px; border: none; border-radius: 8px; cursor: pointer;">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
                
                <div style="margin-top: 15px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">وصف إضافي</label>
                    <textarea name="items[0][description]" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; min-height: 60px;" placeholder="وصف أو مواصفات إضافية للمنتج"></textarea>
                </div>
                
                <div style="margin-top: 10px; text-align: left;">
                    <span style="font-weight: 600; color: #1e40af;">الإجمالي: <span class="row-total">0.00</span></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Totals Section -->
    <div class="content-card" style="margin-bottom: 25px;">
        <h3 style="margin-bottom: 20px; color: #2d3748; font-weight: 700;">
            <i class="fas fa-calculator" style="color: #8b5cf6; margin-left: 8px;"></i>
            الإجماليات
        </h3>

        <div style="display: grid; grid-template-columns: 1fr 300px; gap: 30px;">
            <div>
                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">ملاحظات</label>
                    <textarea name="notes" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; min-height: 100px;" placeholder="ملاحظات إضافية على الأمر">{{ old('notes') }}</textarea>
                </div>

                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">الشروط والأحكام</label>
                    <textarea name="terms_conditions" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; min-height: 100px;" placeholder="الشروط والأحكام الخاصة بالأمر">{{ old('terms_conditions') }}</textarea>
                </div>
            </div>

            <div style="background: #f8fafc; padding: 20px; border-radius: 8px; border: 2px solid #e2e8f0;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                    <span style="font-weight: 600; color: #4a5568;">المجموع الفرعي:</span>
                    <span id="subtotal" style="font-weight: 600; color: #2d3748;">0.00</span>
                </div>

                <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                    <span style="font-weight: 600; color: #4a5568;">إجمالي الخصم:</span>
                    <span id="total-discount" style="font-weight: 600; color: #dc2626;">0.00</span>
                </div>

                <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                    <span style="font-weight: 600; color: #4a5568;">إجمالي الضريبة:</span>
                    <span id="total-tax" style="font-weight: 600; color: #059669;">0.00</span>
                </div>

                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">تكلفة الشحن:</label>
                    <input type="number" name="shipping_cost" step="0.01" min="0" value="{{ old('shipping_cost', 0) }}" style="width: 100%; padding: 8px; border: 2px solid #e2e8f0; border-radius: 6px;" onchange="calculateTotals()">
                </div>

                <hr style="border: none; border-top: 2px solid #e2e8f0; margin: 15px 0;">

                <div style="display: flex; justify-content: space-between; font-size: 18px; font-weight: 700;">
                    <span style="color: #2d3748;">الإجمالي النهائي:</span>
                    <span id="grand-total" style="color: #1e40af;">0.00</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Submit Buttons -->
    <div class="content-card">
        <div style="display: flex; gap: 15px; justify-content: flex-end;">
            <a href="{{ route('tenant.purchasing.purchase-orders.index') }}" style="background: #6b7280; color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600;">
                إلغاء
            </a>
            <button type="submit" style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); color: white; padding: 12px 24px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                <i class="fas fa-save" style="margin-left: 8px;"></i>
                حفظ أمر الشراء
            </button>
        </div>
    </div>
</form>

@push('scripts')
<script>
let productIndex = 1;

function addProductRow() {
    const container = document.getElementById('products-container');
    const newRow = document.querySelector('.product-row').cloneNode(true);

    // Update indices in the new row
    newRow.innerHTML = newRow.innerHTML.replace(/\[0\]/g, `[${productIndex}]`);
    newRow.innerHTML = newRow.innerHTML.replace(/\(0\)/g, `(${productIndex})`);

    // Clear values
    newRow.querySelectorAll('input, select, textarea').forEach(input => {
        if (input.type === 'checkbox') {
            input.checked = false;
        } else {
            input.value = '';
        }
    });

    // Reset total
    newRow.querySelector('.row-total').textContent = '0.00';

    container.appendChild(newRow);
    productIndex++;
}

function removeProductRow(button) {
    const rows = document.querySelectorAll('.product-row');
    if (rows.length > 1) {
        button.closest('.product-row').remove();
        calculateTotals();
    }
}

function updateProductInfo(select, index) {
    const option = select.options[select.selectedIndex];
    if (option.value) {
        const price = option.getAttribute('data-price');
        const row = select.closest('.product-row');
        const priceInput = row.querySelector(`input[name="items[${index}][unit_price]"]`);
        if (priceInput && price) {
            priceInput.value = price;
            calculateRowTotal(index);
        }
    }
}

function calculateRowTotal(index) {
    const row = document.querySelector(`select[name="items[${index}][product_id]"]`).closest('.product-row');
    const quantity = parseFloat(row.querySelector(`input[name="items[${index}][quantity]"]`).value) || 0;
    const unitPrice = parseFloat(row.querySelector(`input[name="items[${index}][unit_price]"]`).value) || 0;
    const discount = parseFloat(row.querySelector(`input[name="items[${index}][discount_amount]"]`).value) || 0;
    const taxPercentage = parseFloat(row.querySelector(`input[name="items[${index}][tax_percentage]"]`).value) || 0;

    const subtotal = quantity * unitPrice;
    const afterDiscount = subtotal - discount;
    const tax = afterDiscount * (taxPercentage / 100);
    const total = afterDiscount + tax;

    row.querySelector('.row-total').textContent = total.toFixed(2);

    calculateTotals();
}

function calculateTotals() {
    let subtotal = 0;
    let totalDiscount = 0;
    let totalTax = 0;

    document.querySelectorAll('.product-row').forEach((row, index) => {
        const quantity = parseFloat(row.querySelector(`input[name*="[quantity]"]`).value) || 0;
        const unitPrice = parseFloat(row.querySelector(`input[name*="[unit_price]"]`).value) || 0;
        const discount = parseFloat(row.querySelector(`input[name*="[discount_amount]"]`).value) || 0;
        const taxPercentage = parseFloat(row.querySelector(`input[name*="[tax_percentage]"]`).value) || 0;

        const itemSubtotal = quantity * unitPrice;
        const afterDiscount = itemSubtotal - discount;
        const tax = afterDiscount * (taxPercentage / 100);

        subtotal += itemSubtotal;
        totalDiscount += discount;
        totalTax += tax;
    });

    const shippingCost = parseFloat(document.querySelector('input[name="shipping_cost"]').value) || 0;
    const grandTotal = subtotal - totalDiscount + totalTax + shippingCost;

    document.getElementById('subtotal').textContent = subtotal.toFixed(2);
    document.getElementById('total-discount').textContent = totalDiscount.toFixed(2);
    document.getElementById('total-tax').textContent = totalTax.toFixed(2);
    document.getElementById('grand-total').textContent = grandTotal.toFixed(2);
}

// Initialize calculations
document.addEventListener('DOMContentLoaded', function() {
    calculateTotals();
});
</script>
@endpush
@endsection
