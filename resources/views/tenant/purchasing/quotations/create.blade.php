@extends('layouts.modern')

@section('title', 'إنشاء طلب عرض سعر جديد')

@section('content')
<div class="page-header">
    <div class="page-title">
        <h1><i class="fas fa-quote-right"></i> إنشاء طلب عرض سعر جديد</h1>
        <p>إنشاء طلب عرض سعر للموردين</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('tenant.purchasing.quotations.index') }}" class="btn btn-secondary">
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

<form method="POST" action="{{ route('tenant.purchasing.quotations.store') }}" id="quotationForm">
    @csrf

    <!-- Basic Information -->
    <div class="content-card" style="margin-bottom: 25px;">
        <h3 style="margin-bottom: 20px; color: #2d3748; font-weight: 700;">
            <i class="fas fa-info-circle" style="color: #f59e0b; margin-left: 8px;"></i>
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
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">عنوان الطلب *</label>
                <input type="text" name="title" value="{{ old('title') }}" required style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;" placeholder="عنوان طلب عرض السعر">
                @error('title')
                    <div style="color: #dc2626; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">تاريخ الطلب *</label>
                <input type="date" name="quotation_date" value="{{ old('quotation_date', date('Y-m-d')) }}" required style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;">
                @error('quotation_date')
                    <div style="color: #dc2626; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">صالح حتى *</label>
                <input type="date" name="valid_until" value="{{ old('valid_until') }}" required style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;">
                @error('valid_until')
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
            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">وصف الطلب</label>
            <textarea name="description" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; min-height: 80px;" placeholder="وصف تفصيلي لطلب عرض السعر">{{ old('description') }}</textarea>
            @error('description')
                <div style="color: #dc2626; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <!-- Terms and Conditions -->
    <div class="content-card" style="margin-bottom: 25px;">
        <h3 style="margin-bottom: 20px; color: #2d3748; font-weight: 700;">
            <i class="fas fa-handshake" style="color: #3b82f6; margin-left: 8px;"></i>
            الشروط والأحكام
        </h3>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
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
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">مدة التسليم (بالأيام)</label>
                <input type="number" name="delivery_days" value="{{ old('delivery_days') }}" min="1" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;" placeholder="عدد أيام التسليم">
                @error('delivery_days')
                    <div style="color: #dc2626; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">شروط التسليم</label>
                <textarea name="delivery_terms" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; min-height: 60px;" placeholder="شروط وتفاصيل التسليم">{{ old('delivery_terms') }}</textarea>
                @error('delivery_terms')
                    <div style="color: #dc2626; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">شروط الضمان</label>
                <textarea name="warranty_terms" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; min-height: 60px;" placeholder="شروط وتفاصيل الضمان">{{ old('warranty_terms') }}</textarea>
                @error('warranty_terms')
                    <div style="color: #dc2626; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>

    <!-- Items Section -->
    <div class="content-card" style="margin-bottom: 25px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3 style="margin: 0; color: #2d3748; font-weight: 700;">
                <i class="fas fa-boxes" style="color: #10b981; margin-left: 8px;"></i>
                المنتجات المطلوبة
            </h3>
            <button type="button" onclick="addItemRow()" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 10px 16px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                <i class="fas fa-plus" style="margin-left: 8px;"></i>
                إضافة منتج
            </button>
        </div>

        <div id="items-container">
            <!-- First item row -->
            <div class="item-row" style="background: #f8fafc; padding: 20px; border-radius: 8px; margin-bottom: 15px; border: 2px solid #e2e8f0;">
                <div style="display: grid; grid-template-columns: 2fr 1fr 1fr 1fr auto; gap: 15px; align-items: end;">
                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">اسم المنتج *</label>
                        <input type="text" name="items[0][item_name]" required style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;" placeholder="اسم المنتج المطلوب">
                    </div>

                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">الكمية *</label>
                        <input type="number" name="items[0][quantity]" step="0.01" min="0.01" required style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;" onchange="calculateRowTotal(0)">
                    </div>

                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">الوحدة</label>
                        <input type="text" name="items[0][unit]" value="قطعة" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;" placeholder="الوحدة">
                    </div>

                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">السعر المتوقع</label>
                        <input type="number" name="items[0][unit_price]" step="0.01" min="0" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;" onchange="calculateRowTotal(0)" placeholder="السعر المتوقع">
                    </div>

                    <div>
                        <button type="button" onclick="removeItemRow(this)" style="background: #ef4444; color: white; padding: 12px; border: none; border-radius: 8px; cursor: pointer;">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>

                <div style="margin-top: 15px; display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 15px;">
                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">المواصفات</label>
                        <textarea name="items[0][specifications]" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; min-height: 60px;" placeholder="المواصفات المطلوبة"></textarea>
                    </div>

                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">العلامة التجارية المفضلة</label>
                        <input type="text" name="items[0][brand]" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;" placeholder="العلامة التجارية">
                    </div>

                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">الموديل المفضل</label>
                        <input type="text" name="items[0][model]" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;" placeholder="الموديل">
                    </div>
                </div>

                <div style="margin-top: 15px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">مدة الضمان (بالأشهر)</label>
                    <input type="number" name="items[0][warranty_months]" min="0" style="width: 200px; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;" placeholder="مدة الضمان">
                </div>

                <div style="margin-top: 10px; text-align: left;">
                    <span style="font-weight: 600; color: #1e40af;">الإجمالي المتوقع: <span class="row-total">0.00</span></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Information -->
    <div class="content-card" style="margin-bottom: 25px;">
        <h3 style="margin-bottom: 20px; color: #2d3748; font-weight: 700;">
            <i class="fas fa-clipboard-list" style="color: #8b5cf6; margin-left: 8px;"></i>
            معلومات إضافية
        </h3>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">شروط خاصة</label>
                <textarea name="special_conditions" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; min-height: 100px;" placeholder="أي شروط خاصة أو متطلبات إضافية">{{ old('special_conditions') }}</textarea>
                @error('special_conditions')
                    <div style="color: #dc2626; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">ملاحظات</label>
                <textarea name="notes" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; min-height: 100px;" placeholder="ملاحظات إضافية">{{ old('notes') }}</textarea>
                @error('notes')
                    <div style="color: #dc2626; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>

    <!-- Submit Buttons -->
    <div class="content-card">
        <div style="display: flex; gap: 15px; justify-content: flex-end;">
            <a href="{{ route('tenant.purchasing.quotations.index') }}" style="background: #6b7280; color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600;">
                إلغاء
            </a>
            <button type="submit" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; padding: 12px 24px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                <i class="fas fa-save" style="margin-left: 8px;"></i>
                حفظ طلب عرض السعر
            </button>
        </div>
    </div>
</form>

@push('scripts')
<script>
let itemIndex = 1;

function addItemRow() {
    const container = document.getElementById('items-container');
    const newRow = document.querySelector('.item-row').cloneNode(true);

    // Update indices in the new row
    newRow.innerHTML = newRow.innerHTML.replace(/\[0\]/g, `[${itemIndex}]`);
    newRow.innerHTML = newRow.innerHTML.replace(/\(0\)/g, `(${itemIndex})`);

    // Clear values
    newRow.querySelectorAll('input, textarea').forEach(input => {
        input.value = '';
    });

    // Reset total
    newRow.querySelector('.row-total').textContent = '0.00';

    container.appendChild(newRow);
    itemIndex++;
}

function removeItemRow(button) {
    const rows = document.querySelectorAll('.item-row');
    if (rows.length > 1) {
        button.closest('.item-row').remove();
    }
}

function calculateRowTotal(index) {
    const row = document.querySelector(`input[name="items[${index}][item_name]"]`).closest('.item-row');
    const quantity = parseFloat(row.querySelector(`input[name="items[${index}][quantity]"]`).value) || 0;
    const unitPrice = parseFloat(row.querySelector(`input[name="items[${index}][unit_price]"]`).value) || 0;

    const total = quantity * unitPrice;
    row.querySelector('.row-total').textContent = total.toFixed(2);
}

// Initialize calculations
document.addEventListener('DOMContentLoaded', function() {
    // Set default unit value
    document.querySelectorAll('input[name*="[unit]"]').forEach(input => {
        if (!input.value) {
            input.value = 'قطعة';
        }
    });
});
</script>
@endpush
@endsection
