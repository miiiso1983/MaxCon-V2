@extends('layouts.modern')

@section('page-title', 'إنشاء طلب إرجاع جديد')
@section('page-description', 'إنشاء طلب إرجاع أو استبدال للمنتجات')

@section('content')
<!-- Page Header -->
<div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border-radius: 20px; padding: 30px; margin-bottom: 30px; color: white; position: relative; overflow: hidden;">
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
                            طلب إرجاع جديد 🔄
                        </h1>
                        <p style="font-size: 18px; margin: 5px 0 0 0; opacity: 0.9;">
                            إنشاء طلب إرجاع أو استبدال للمنتجات مع تحديث المخزون
                        </p>
                    </div>
                </div>
            </div>
            
            <div style="display: flex; gap: 15px;">
                <a href="{{ route('tenant.sales.returns.index') }}" style="background: rgba(255,255,255,0.2); color: white; padding: 15px 25px; border-radius: 15px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3);">
                    <i class="fas fa-arrow-right"></i>
                    العودة للقائمة
                </a>
            </div>
        </div>
    </div>
</div>

<form method="POST" action="{{ route('tenant.sales.returns.store') }}" id="returnForm">
    @csrf
    
    <!-- Invoice Selection -->
    <div class="content-card" style="margin-bottom: 25px;">
        <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-file-invoice" style="color: #f093fb; margin-left: 10px;"></i>
            اختيار الفاتورة
        </h3>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">رقم الفاتورة *</label>
                <input type="text" id="invoice_search" placeholder="ابحث برقم الفاتورة..." style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;" value="{{ $invoice ? $invoice->invoice_number : '' }}">
                <input type="hidden" name="invoice_id" id="invoice_id" value="{{ $invoice ? $invoice->id : '' }}">
                <div id="invoice_results" style="position: relative; z-index: 1000;"></div>
                @error('invoice_id')
                    <div style="color: #f56565; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">العميل</label>
                <input type="text" id="customer_name" readonly style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; background: #f8fafc;" value="{{ $invoice ? $invoice->customer->name : '' }}">
                <input type="hidden" name="customer_id" id="customer_id" value="{{ $invoice ? $invoice->customer_id : '' }}">
            </div>
        </div>
        
        @if($invoice)
            <div style="margin-top: 20px; padding: 15px; background: #f0fff4; border: 1px solid #c6f6d5; border-radius: 8px;">
                <h4 style="margin: 0 0 10px 0; color: #065f46;">تفاصيل الفاتورة المحددة:</h4>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 10px; font-size: 14px;">
                    <div><strong>رقم الفاتورة:</strong> {{ $invoice->invoice_number }}</div>
                    <div><strong>التاريخ:</strong> {{ $invoice->invoice_date->format('Y/m/d') }}</div>
                    <div><strong>المبلغ:</strong> {{ number_format($invoice->total_amount, 0) }} د.ع</div>
                    <div><strong>عدد الأصناف:</strong> {{ $invoice->items->count() }}</div>
                </div>
            </div>
        @endif
    </div>

    <!-- Return Details -->
    <div class="content-card" style="margin-bottom: 25px;">
        <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-info-circle" style="color: #f093fb; margin-left: 10px;"></i>
            تفاصيل الإرجاع
        </h3>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px;">
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">تاريخ الإرجاع *</label>
                <input type="date" name="return_date" value="{{ old('return_date', date('Y-m-d')) }}" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;" required>
                @error('return_date')
                    <div style="color: #f56565; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">نوع العملية *</label>
                <select name="type" id="return_type" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;" required>
                    <option value="return" {{ old('type') === 'return' ? 'selected' : '' }}>إرجاع (استرداد نقدي)</option>
                    <option value="exchange" {{ old('type') === 'exchange' ? 'selected' : '' }}>استبدال (تبديل منتج)</option>
                </select>
                @error('type')
                    <div style="color: #f56565; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
            
            <div id="refund_method_div">
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">طريقة الاسترداد</label>
                <select name="refund_method" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;">
                    <option value="">اختر طريقة الاسترداد</option>
                    <option value="cash" {{ old('refund_method') === 'cash' ? 'selected' : '' }}>نقداً</option>
                    <option value="credit" {{ old('refund_method') === 'credit' ? 'selected' : '' }}>رصيد للعميل</option>
                    <option value="bank_transfer" {{ old('refund_method') === 'bank_transfer' ? 'selected' : '' }}>تحويل بنكي</option>
                </select>
                @error('refund_method')
                    <div style="color: #f56565; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
        </div>
        
        <div style="margin-top: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">سبب الإرجاع *</label>
            <textarea name="reason" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; height: 80px;" placeholder="اذكر سبب الإرجاع بالتفصيل..." required>{{ old('reason') }}</textarea>
            @error('reason')
                <div style="color: #f56565; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
            @enderror
        </div>
        
        <div style="margin-top: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">ملاحظات إضافية</label>
            <textarea name="notes" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; height: 60px;" placeholder="أي ملاحظات إضافية...">{{ old('notes') }}</textarea>
            @error('notes')
                <div style="color: #f56565; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <!-- Invoice Items -->
    @if($invoice)
    <div class="content-card" style="margin-bottom: 25px;">
        <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-list" style="color: #f093fb; margin-left: 10px;"></i>
            أصناف الفاتورة المراد إرجاعها
        </h3>
        
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8fafc;">
                        <th style="padding: 12px; text-align: right; border-bottom: 2px solid #e2e8f0; font-weight: 700; color: #2d3748;">اختيار</th>
                        <th style="padding: 12px; text-align: right; border-bottom: 2px solid #e2e8f0; font-weight: 700; color: #2d3748;">المنتج</th>
                        <th style="padding: 12px; text-align: right; border-bottom: 2px solid #e2e8f0; font-weight: 700; color: #2d3748;">الكمية الأصلية</th>
                        <th style="padding: 12px; text-align: right; border-bottom: 2px solid #e2e8f0; font-weight: 700; color: #2d3748;">الكمية المرتجعة</th>
                        <th style="padding: 12px; text-align: right; border-bottom: 2px solid #e2e8f0; font-weight: 700; color: #2d3748;">حالة المنتج</th>
                        <th style="padding: 12px; text-align: right; border-bottom: 2px solid #e2e8f0; font-weight: 700; color: #2d3748;">سبب الإرجاع</th>
                        <th style="padding: 12px; text-align: right; border-bottom: 2px solid #e2e8f0; font-weight: 700; color: #2d3748;" id="exchange_header" style="display: none;">منتج الاستبدال</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoice->items as $index => $item)
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">
                                <input type="checkbox" name="items[{{ $index }}][selected]" value="1" class="item-checkbox" style="width: 18px; height: 18px;">
                                <input type="hidden" name="items[{{ $index }}][invoice_item_id]" value="{{ $item->id }}">
                            </td>
                            <td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">
                                <div style="font-weight: 600; color: #2d3748;">{{ $item->product_name }}</div>
                                <div style="font-size: 12px; color: #6b7280;">{{ $item->product_code }}</div>
                                <div style="font-size: 12px; color: #6b7280;">سعر الوحدة: {{ number_format($item->unit_price, 0) }} د.ع</div>
                            </td>
                            <td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">
                                <span style="font-weight: 600; color: #059669;">{{ $item->quantity }}</span>
                            </td>
                            <td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">
                                <input type="number" name="items[{{ $index }}][quantity_returned]" min="1" max="{{ $item->quantity }}" style="width: 80px; padding: 8px; border: 2px solid #e2e8f0; border-radius: 6px;" disabled>
                            </td>
                            <td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">
                                <select name="items[{{ $index }}][condition]" style="width: 120px; padding: 8px; border: 2px solid #e2e8f0; border-radius: 6px;" disabled>
                                    <option value="good">جيد</option>
                                    <option value="damaged">تالف</option>
                                    <option value="expired">منتهي الصلاحية</option>
                                </select>
                            </td>
                            <td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">
                                <input type="text" name="items[{{ $index }}][reason]" placeholder="سبب إرجاع هذا الصنف..." style="width: 150px; padding: 8px; border: 2px solid #e2e8f0; border-radius: 6px;" disabled>
                            </td>
                            <td style="padding: 12px; border-bottom: 1px solid #e2e8f0; display: none;" class="exchange-column">
                                <select name="items[{{ $index }}][exchange_product_id]" style="width: 150px; padding: 8px; border: 2px solid #e2e8f0; border-radius: 6px;" disabled>
                                    <option value="">اختر منتج الاستبدال</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}" data-price="{{ $product->selling_price }}">{{ $product->name }}</option>
                                    @endforeach
                                </select>
                                <input type="number" name="items[{{ $index }}][exchange_quantity]" min="1" placeholder="الكمية" style="width: 80px; padding: 8px; border: 2px solid #e2e8f0; border-radius: 6px; margin-top: 5px;" disabled>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- Form Actions -->
    <div style="display: flex; gap: 15px; justify-content: flex-end;">
        <a href="{{ route('tenant.sales.returns.index') }}" style="padding: 12px 24px; border: 2px solid #e2e8f0; border-radius: 8px; background: white; color: #4a5568; font-weight: 600; text-decoration: none; display: flex; align-items: center; gap: 8px;">
            <i class="fas fa-times"></i>
            إلغاء
        </a>
        <button type="submit" style="padding: 12px 24px; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px;">
            <i class="fas fa-save"></i>
            إنشاء طلب الإرجاع
        </button>
    </div>
</form>

@push('scripts')
<script>
// Handle return type change
document.getElementById('return_type').addEventListener('change', function() {
    const isExchange = this.value === 'exchange';
    const refundMethodDiv = document.getElementById('refund_method_div');
    const exchangeHeader = document.getElementById('exchange_header');
    const exchangeColumns = document.querySelectorAll('.exchange-column');
    
    if (isExchange) {
        refundMethodDiv.style.display = 'none';
        exchangeHeader.style.display = 'table-cell';
        exchangeColumns.forEach(col => col.style.display = 'table-cell');
    } else {
        refundMethodDiv.style.display = 'block';
        exchangeHeader.style.display = 'none';
        exchangeColumns.forEach(col => col.style.display = 'none');
    }
});

// Handle item selection
document.querySelectorAll('.item-checkbox').forEach(checkbox => {
    checkbox.addEventListener('change', function() {
        const row = this.closest('tr');
        const inputs = row.querySelectorAll('input:not([type="checkbox"]):not([type="hidden"]), select');
        
        inputs.forEach(input => {
            input.disabled = !this.checked;
            if (!this.checked) {
                input.value = '';
            }
        });
    });
});

// Invoice search functionality
let searchTimeout;
document.getElementById('invoice_search').addEventListener('input', function() {
    clearTimeout(searchTimeout);
    const query = this.value.trim();
    
    if (query.length < 2) {
        document.getElementById('invoice_results').innerHTML = '';
        return;
    }
    
    searchTimeout = setTimeout(() => {
        // Here you would implement AJAX search for invoices
        // For now, we'll just show a placeholder
        console.log('Searching for invoice:', query);
    }, 300);
});

// Form validation
document.getElementById('returnForm').addEventListener('submit', function(e) {
    const selectedItems = document.querySelectorAll('.item-checkbox:checked');
    
    if (selectedItems.length === 0) {
        e.preventDefault();
        alert('يرجى اختيار صنف واحد على الأقل للإرجاع');
        return false;
    }
    
    // Validate quantities
    let hasValidQuantity = false;
    selectedItems.forEach(checkbox => {
        const row = checkbox.closest('tr');
        const quantityInput = row.querySelector('input[name*="[quantity_returned]"]');
        if (quantityInput && quantityInput.value > 0) {
            hasValidQuantity = true;
        }
    });
    
    if (!hasValidQuantity) {
        e.preventDefault();
        alert('يرجى إدخال كمية صحيحة للأصناف المحددة');
        return false;
    }
});

// Initialize form state
document.addEventListener('DOMContentLoaded', function() {
    // Trigger return type change to set initial state
    document.getElementById('return_type').dispatchEvent(new Event('change'));
});
</script>
@endpush
@endsection
