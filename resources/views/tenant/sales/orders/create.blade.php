@extends('layouts.modern')

@section('page-title', 'إنشاء طلب مبيعات جديد')
@section('page-description', 'إنشاء طلب مبيعات جديد مع تفاصيل المنتجات')

@section('content')
<!-- Page Header -->
<div style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); border-radius: 20px; padding: 30px; margin-bottom: 30px; color: white; position: relative; overflow: hidden;">
    <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
    <div style="position: absolute; bottom: -30px; left: -30px; width: 150px; height: 150px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>
    
    <div style="position: relative; z-index: 2;">
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    <div style="background: rgba(255,255,255,0.2); border-radius: 15px; padding: 15px; margin-left: 20px;">
                        <i class="fas fa-plus-circle" style="font-size: 32px;"></i>
                    </div>
                    <div>
                        <h1 style="font-size: 32px; font-weight: 800; margin: 0; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                            إنشاء طلب مبيعات جديد ✨
                        </h1>
                        <p style="font-size: 18px; margin: 5px 0 0 0; opacity: 0.9;">
                            إنشاء طلب مبيعات جديد مع تفاصيل المنتجات
                        </p>
                    </div>
                </div>
            </div>
            
            <div>
                <a href="{{ route('tenant.sales.orders.index') }}" style="background: rgba(255,255,255,0.2); color: white; padding: 15px 25px; border-radius: 15px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3); transition: all 0.3s ease;"
                   onmouseover="this.style.background='rgba(255,255,255,0.3)'; this.style.transform='translateY(-2px)';"
                   onmouseout="this.style.background='rgba(255,255,255,0.2)'; this.style.transform='translateY(0)';">
                    <i class="fas fa-arrow-right"></i>
                    العودة للقائمة
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Order Form -->
<form id="orderForm" method="POST" action="{{ route('tenant.sales.orders.store') }}">
    @csrf
    
    <!-- Order Information -->
    <div class="content-card" style="margin-bottom: 25px;">
        <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-info-circle" style="color: #4299e1; margin-left: 10px;"></i>
            معلومات الطلب
        </h3>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
            <!-- Customer -->
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">العميل *</label>
                <select name="customer_id" required style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;">
                    <option value="">اختر العميل</option>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                            {{ $customer->name }} ({{ $customer->customer_code }})
                        </option>
                    @endforeach
                </select>
                @error('customer_id')
                    <div style="color: #f56565; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
            
            <!-- Order Date -->
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">تاريخ الطلب *</label>
                <input type="date" name="order_date" value="{{ old('order_date', now()->format('Y-m-d')) }}" required 
                       style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;">
                @error('order_date')
                    <div style="color: #f56565; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
            
            <!-- Required Date -->
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">التاريخ المطلوب</label>
                <input type="date" name="required_date" value="{{ old('required_date') }}" 
                       style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;">
                @error('required_date')
                    <div style="color: #f56565; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
            
            <!-- Priority -->
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">الأولوية *</label>
                <select name="priority" required style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;">
                    <option value="normal" {{ old('priority') === 'normal' ? 'selected' : '' }}>عادية</option>
                    <option value="low" {{ old('priority') === 'low' ? 'selected' : '' }}>منخفضة</option>
                    <option value="high" {{ old('priority') === 'high' ? 'selected' : '' }}>عالية</option>
                    <option value="urgent" {{ old('priority') === 'urgent' ? 'selected' : '' }}>عاجلة</option>
                </select>
                @error('priority')
                    <div style="color: #f56565; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
        </div>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-top: 20px;">
            <!-- Shipping Method -->
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">طريقة الشحن</label>
                <input type="text" name="shipping_method" value="{{ old('shipping_method') }}" 
                       style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;" 
                       placeholder="مثال: شحن سريع، شحن عادي...">
            </div>
            
            <!-- Payment Method -->
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">طريقة الدفع</label>
                <input type="text" name="payment_method" value="{{ old('payment_method') }}" 
                       style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;" 
                       placeholder="مثال: نقداً، آجل، بطاقة ائتمان...">
            </div>
        </div>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 20px;">
            <!-- Shipping Address -->
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">عنوان الشحن</label>
                <textarea name="shipping_address" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; height: 80px;" placeholder="عنوان الشحن الكامل...">{{ old('shipping_address') }}</textarea>
            </div>
            
            <!-- Billing Address -->
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">عنوان الفوترة</label>
                <textarea name="billing_address" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; height: 80px;" placeholder="عنوان الفوترة الكامل...">{{ old('billing_address') }}</textarea>
            </div>
        </div>
        
        <!-- Notes -->
        <div style="margin-top: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">ملاحظات</label>
            <textarea name="notes" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; height: 80px;" placeholder="ملاحظات إضافية...">{{ old('notes') }}</textarea>
        </div>
    </div>

    <!-- Order Items -->
    <div class="content-card" style="margin-bottom: 25px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin: 0; display: flex; align-items: center;">
                <i class="fas fa-list" style="color: #48bb78; margin-left: 10px;"></i>
                عناصر الطلب
            </h3>
            <button type="button" onclick="addOrderItem()" style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); color: white; padding: 10px 20px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-plus"></i>
                إضافة منتج
            </button>
        </div>
        
        <div id="orderItems">
            <!-- Order items will be added here dynamically -->
        </div>
        
        @error('items')
            <div style="color: #f56565; font-size: 14px; margin-top: 10px;">{{ $message }}</div>
        @enderror
    </div>

    <!-- Order Summary -->
    <div class="content-card" style="margin-bottom: 25px;">
        <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-calculator" style="color: #9f7aea; margin-left: 10px;"></i>
            ملخص الطلب
        </h3>
        
        <div style="background: #f7fafc; border-radius: 12px; padding: 20px;">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
                <div style="text-align: center;">
                    <div style="font-size: 24px; font-weight: 700; color: #2d3748;" id="subtotalDisplay">0.00</div>
                    <div style="font-size: 14px; color: #718096;">المجموع الفرعي</div>
                </div>
                <div style="text-align: center;">
                    <div style="font-size: 24px; font-weight: 700; color: #2d3748;" id="taxDisplay">0.00</div>
                    <div style="font-size: 14px; color: #718096;">الضريبة</div>
                </div>
                <div style="text-align: center;">
                    <div style="font-size: 28px; font-weight: 800; color: #48bb78;" id="totalDisplay">0.00</div>
                    <div style="font-size: 14px; color: #718096;">المجموع الإجمالي</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Actions -->
    <div style="display: flex; gap: 15px; justify-content: flex-end;">
        <a href="{{ route('tenant.sales.orders.index') }}" style="padding: 12px 24px; border: 2px solid #e2e8f0; border-radius: 8px; background: white; color: #4a5568; font-weight: 600; text-decoration: none; display: flex; align-items: center; gap: 8px;">
            <i class="fas fa-times"></i>
            إلغاء
        </a>
        <button type="submit" class="btn-green" style="padding: 12px 24px;">
            <i class="fas fa-save"></i>
            حفظ الطلب
        </button>
    </div>
</form>

@push('scripts')
<script>
let itemCounter = 0;
const products = @json($products);

function addOrderItem() {
    itemCounter++;
    const itemHtml = `
        <div class="order-item" id="item-${itemCounter}" style="border: 2px solid #e2e8f0; border-radius: 12px; padding: 20px; margin-bottom: 15px; position: relative;">
            <button type="button" onclick="removeOrderItem(${itemCounter})" style="position: absolute; top: 10px; left: 10px; background: #f56565; color: white; border: none; border-radius: 50%; width: 30px; height: 30px; cursor: pointer; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-times"></i>
            </button>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">المنتج *</label>
                    <select name="items[${itemCounter}][product_id]" required data-custom-select data-placeholder="اختر المنتج..." data-searchable="true" onchange="updateProductInfo(${itemCounter})" style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 8px;">
                        <option value="">اختر المنتج</option>
                        ${products.map(product => `<option value="${product.id}" data-price="${product.selling_price}" data-tax="${product.tax_rate}">${product.name} (${product.product_code})</option>`).join('')}
                    </select>
                </div>
                
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">الكمية *</label>
                    <input type="number" name="items[${itemCounter}][quantity]" min="1" value="1" required onchange="calculateItemTotal(${itemCounter})" style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 8px;">
                </div>
                
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">سعر الوحدة *</label>
                    <input type="number" name="items[${itemCounter}][unit_price]" step="0.01" min="0" required onchange="calculateItemTotal(${itemCounter})" style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 8px;">
                </div>
                
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">خصم (%)</label>
                    <input type="number" name="items[${itemCounter}][discount_percentage]" step="0.01" min="0" max="100" value="0" onchange="calculateItemTotal(${itemCounter})" style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 8px;">
                </div>
                
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">معدل الضريبة (%)</label>
                    <input type="number" name="items[${itemCounter}][tax_rate]" step="0.01" min="0" max="100" value="15" onchange="calculateItemTotal(${itemCounter})" style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 8px;">
                </div>
                
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">المجموع</label>
                    <div id="itemTotal-${itemCounter}" style="padding: 10px; background: #f7fafc; border-radius: 8px; font-weight: 600; color: #2d3748;">0.00 ريال</div>
                </div>
            </div>
            
            <div style="margin-top: 15px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">ملاحظات</label>
                <input type="text" name="items[${itemCounter}][notes]" style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 8px;" placeholder="ملاحظات إضافية...">
            </div>
        </div>
    `;
    
    document.getElementById('orderItems').insertAdjacentHTML('beforeend', itemHtml);
}

function removeOrderItem(itemId) {
    document.getElementById(`item-${itemId}`).remove();
    calculateOrderTotal();
}

function updateProductInfo(itemId) {
    const select = document.querySelector(`select[name="items[${itemId}][product_id]"]`);
    const selectedOption = select.options[select.selectedIndex];
    
    if (selectedOption.value) {
        const price = selectedOption.dataset.price;
        const taxRate = selectedOption.dataset.tax;
        
        document.querySelector(`input[name="items[${itemId}][unit_price]"]`).value = price;
        document.querySelector(`input[name="items[${itemId}][tax_rate]"]`).value = taxRate;
        
        calculateItemTotal(itemId);
    }
}

function calculateItemTotal(itemId) {
    const quantity = parseFloat(document.querySelector(`input[name="items[${itemId}][quantity]"]`).value) || 0;
    const unitPrice = parseFloat(document.querySelector(`input[name="items[${itemId}][unit_price]"]`).value) || 0;
    const discountPercentage = parseFloat(document.querySelector(`input[name="items[${itemId}][discount_percentage]"]`).value) || 0;
    const taxRate = parseFloat(document.querySelector(`input[name="items[${itemId}][tax_rate]"]`).value) || 0;
    
    const subtotal = quantity * unitPrice;
    const discountAmount = (subtotal * discountPercentage) / 100;
    const afterDiscount = subtotal - discountAmount;
    const taxAmount = (afterDiscount * taxRate) / 100;
    const total = afterDiscount + taxAmount;
    
    document.getElementById(`itemTotal-${itemId}`).textContent = total.toFixed(2) + ' ريال';
    
    calculateOrderTotal();
}

function calculateOrderTotal() {
    let subtotal = 0;
    let totalTax = 0;
    
    document.querySelectorAll('.order-item').forEach(item => {
        const itemId = item.id.split('-')[1];
        const quantity = parseFloat(document.querySelector(`input[name="items[${itemId}][quantity]"]`).value) || 0;
        const unitPrice = parseFloat(document.querySelector(`input[name="items[${itemId}][unit_price]"]`).value) || 0;
        const discountPercentage = parseFloat(document.querySelector(`input[name="items[${itemId}][discount_percentage]"]`).value) || 0;
        const taxRate = parseFloat(document.querySelector(`input[name="items[${itemId}][tax_rate]"]`).value) || 0;
        
        const itemSubtotal = quantity * unitPrice;
        const discountAmount = (itemSubtotal * discountPercentage) / 100;
        const afterDiscount = itemSubtotal - discountAmount;
        const taxAmount = (afterDiscount * taxRate) / 100;
        
        subtotal += afterDiscount;
        totalTax += taxAmount;
    });
    
    const total = subtotal + totalTax;
    
    document.getElementById('subtotalDisplay').textContent = subtotal.toFixed(2);
    document.getElementById('taxDisplay').textContent = totalTax.toFixed(2);
    document.getElementById('totalDisplay').textContent = total.toFixed(2);
}

// Add first item on page load
document.addEventListener('DOMContentLoaded', function() {
    addOrderItem();
});
</script>
@endpush
@endsection
