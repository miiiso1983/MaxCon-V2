@extends('layouts.modern')

@section('page-title', 'إضافة مخزون جديد')
@section('page-description', 'إضافة عنصر مخزون جديد للنظام')

@section('content')
<!-- Page Header -->
<div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 20px; padding: 30px; margin-bottom: 30px; color: white; position: relative; overflow: hidden;">
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
                            إضافة مخزون جديد ➕
                        </h1>
                        <p style="font-size: 18px; margin: 5px 0 0 0; opacity: 0.9;">
                            إضافة عنصر مخزون جديد للنظام
                        </p>
                    </div>
                </div>
            </div>
            
            <div style="display: flex; gap: 15px;">
                <a href="{{ route('tenant.inventory.index') }}" style="background: rgba(255,255,255,0.2); color: white; padding: 15px 25px; border-radius: 15px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3);">
                    <i class="fas fa-arrow-right"></i>
                    العودة للقائمة
                </a>
            </div>
        </div>
    </div>
</div>

<form method="POST" action="{{ route('tenant.inventory.store') }}" id="inventoryForm">
    @csrf
    
    <!-- Basic Information -->
    <div class="content-card" style="margin-bottom: 25px;">
        <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-info-circle" style="color: #667eea; margin-left: 10px;"></i>
            معلومات عامة
        </h3>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">المستودع *</label>
                <select name="warehouse_id" required style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;">
                    <option value="">اختر المستودع</option>
                    @foreach($warehouses as $warehouse)
                        <option value="{{ $warehouse->id }}" {{ old('warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                            {{ $warehouse->name }} ({{ $warehouse->code }})
                        </option>
                    @endforeach
                </select>
                @error('warehouse_id')
                    <div style="color: #f56565; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">الحالة الافتراضية *</label>
                <select name="default_status" required style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;">
                    <option value="active" {{ old('default_status', 'active') === 'active' ? 'selected' : '' }}>نشط</option>
                    <option value="quarantine" {{ old('default_status') === 'quarantine' ? 'selected' : '' }}>حجر صحي</option>
                    <option value="damaged" {{ old('default_status') === 'damaged' ? 'selected' : '' }}>تالف</option>
                    <option value="expired" {{ old('default_status') === 'expired' ? 'selected' : '' }}>منتهي الصلاحية</option>
                </select>
                @error('default_status')
                    <div style="color: #f56565; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>

    <!-- Products Section -->
    <div class="content-card" style="margin-bottom: 25px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin: 0; display: flex; align-items: center;">
                <i class="fas fa-boxes" style="color: #667eea; margin-left: 10px;"></i>
                المنتجات
            </h3>
            <button type="button" onclick="addProductRow()" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 10px 15px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-plus"></i>
                إضافة منتج
            </button>
        </div>

        <div id="products-container">
            <!-- Product Row Template (will be cloned) -->
            <div class="product-row" style="display: none;" id="product-row-template">
                <div style="background: #f8fafc; border: 2px solid #e2e8f0; border-radius: 12px; padding: 20px; margin-bottom: 15px; position: relative;">
                    <button type="button" onclick="removeProductRow(this)" style="position: absolute; top: 10px; left: 10px; background: #ef4444; color: white; border: none; border-radius: 50%; width: 30px; height: 30px; cursor: pointer; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-times"></i>
                    </button>

                    <div style="display: grid; grid-template-columns: 2fr 1fr 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                        <div>
                            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">المنتج *</label>
                            <select name="products[INDEX][product_id]" required style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;" onchange="updateProductInfo(this)">
                                <option value="">اختر المنتج</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" data-name="{{ $product->name }}" data-code="{{ $product->code }}" data-unit="{{ $product->unit ?? 'وحدة' }}">
                                        {{ $product->name }} ({{ $product->code }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">الكمية *</label>
                            <input type="number" name="products[INDEX][quantity]" required min="0" step="0.001" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;" placeholder="0" onchange="calculateRowTotal(this)">
                        </div>

                        <div>
                            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">سعر التكلفة</label>
                            <input type="number" name="products[INDEX][cost_price]" min="0" step="0.01" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;" placeholder="0.00" onchange="calculateRowTotal(this)">
                        </div>

                        <div>
                            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">القيمة الإجمالية</label>
                            <div class="row-total" style="padding: 12px; background: #f0f9ff; border: 2px solid #3b82f6; border-radius: 8px; font-weight: 600; color: #1e40af; text-align: center;">0.00 د.ع</div>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr 1fr; gap: 15px;">
                        <div>
                            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">كود الموقع</label>
                            <input type="text" name="products[INDEX][location_code]" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;" placeholder="مثال: A-01-01">
                        </div>

                        <div>
                            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">رقم الدفعة</label>
                            <input type="text" name="products[INDEX][batch_number]" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;" placeholder="رقم الدفعة">
                        </div>

                        <div>
                            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">تاريخ انتهاء الصلاحية</label>
                            <input type="date" name="products[INDEX][expiry_date]" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;">
                        </div>

                        <div>
                            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">الحالة</label>
                            <select name="products[INDEX][status]" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;" class="status-select">
                                <option value="active">نشط</option>
                                <option value="quarantine">حجر صحي</option>
                                <option value="damaged">تالف</option>
                                <option value="expired">منتهي الصلاحية</option>
                            </select>
                        </div>
                    </div>

                    <div class="product-info" style="margin-top: 15px; padding: 10px; background: #f0fdf4; border-radius: 8px; display: none;">
                        <div style="font-size: 12px; color: #065f46;">
                            <span class="product-name"></span> | <span class="product-code"></span> | <span class="product-unit"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add first product row button -->
        <div id="empty-state" style="text-align: center; padding: 40px; border: 2px dashed #e2e8f0; border-radius: 12px; color: #6b7280;">
            <i class="fas fa-boxes" style="font-size: 48px; margin-bottom: 15px; opacity: 0.5;"></i>
            <h3 style="margin: 0 0 10px 0;">لم يتم إضافة أي منتجات بعد</h3>
            <p style="margin: 0 0 15px 0;">انقر على "إضافة منتج" لبدء إضافة المنتجات للمخزون</p>
            <button type="button" onclick="addProductRow()" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 12px 24px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                <i class="fas fa-plus"></i> إضافة أول منتج
            </button>
        </div>

        <!-- Total Summary -->
        <div id="total-summary" style="display: none; margin-top: 20px; padding: 20px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 12px; color: white;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h4 style="margin: 0 0 5px 0; font-size: 18px;">إجمالي المخزون</h4>
                    <div style="font-size: 14px; opacity: 0.9;">عدد المنتجات: <span id="total-products">0</span></div>
                </div>
                <div style="text-align: left;">
                    <div style="font-size: 28px; font-weight: 700;" id="grand-total">0.00 د.ع</div>
                    <div style="font-size: 14px; opacity: 0.9;">القيمة الإجمالية</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Information -->
    <div class="content-card" style="margin-bottom: 25px;">
        <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-clipboard-list" style="color: #667eea; margin-left: 10px;"></i>
            معلومات إضافية
        </h3>
        
        <div style="background: #f8fafc; border-radius: 12px; padding: 20px; border-right: 4px solid #667eea;">
            <h4 style="margin: 0 0 15px 0; color: #2d3748; font-size: 16px;">ملاحظات مهمة:</h4>
            <ul style="margin: 0; padding-right: 20px; color: #4a5568; line-height: 1.6;">
                <li>إذا كان المنتج موجود بالفعل في نفس المستودع ونفس رقم الدفعة، سيتم إضافة الكمية للمخزون الموجود</li>
                <li>تأكد من صحة تاريخ انتهاء الصلاحية للمنتجات الطبية</li>
                <li>استخدم كود الموقع لتسهيل عملية العثور على المنتج في المستودع</li>
                <li>رقم الدفعة مهم لتتبع المنتجات وإدارة انتهاء الصلاحية</li>
            </ul>
        </div>
    </div>

    <!-- Form Actions -->
    <div style="display: flex; gap: 15px; justify-content: flex-end;">
        <a href="{{ route('tenant.inventory.index') }}" style="padding: 12px 24px; border: 2px solid #e2e8f0; border-radius: 8px; background: white; color: #4a5568; font-weight: 600; text-decoration: none; display: flex; align-items: center; gap: 8px;">
            <i class="fas fa-times"></i>
            إلغاء
        </a>
        <button type="submit" style="padding: 12px 24px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px;">
            <i class="fas fa-save"></i>
            إضافة المخزون
        </button>
    </div>
</form>

@push('scripts')
<script>
let productRowIndex = 0;

function addProductRow() {
    const template = document.getElementById('product-row-template');
    const container = document.getElementById('products-container');
    const emptyState = document.getElementById('empty-state');
    const totalSummary = document.getElementById('total-summary');

    // Clone template
    const newRow = template.cloneNode(true);
    newRow.style.display = 'block';
    newRow.id = 'product-row-' + productRowIndex;

    // Update input names with current index
    const inputs = newRow.querySelectorAll('input, select');
    inputs.forEach(input => {
        if (input.name) {
            input.name = input.name.replace('INDEX', productRowIndex);
        }
    });

    // Set default status from the main form
    const defaultStatus = document.querySelector('select[name="default_status"]').value;
    const statusSelect = newRow.querySelector('.status-select');
    statusSelect.value = defaultStatus;

    // Add to container
    container.appendChild(newRow);

    // Hide empty state and show total summary
    emptyState.style.display = 'none';
    totalSummary.style.display = 'block';

    // Increment index
    productRowIndex++;

    // Update totals
    updateTotals();

    // Auto-suggest location code based on warehouse
    updateLocationSuggestions(newRow);
}

function removeProductRow(button) {
    const row = button.closest('.product-row');
    const container = document.getElementById('products-container');
    const emptyState = document.getElementById('empty-state');
    const totalSummary = document.getElementById('total-summary');

    // Remove row
    row.remove();

    // Check if no rows left
    const remainingRows = container.querySelectorAll('.product-row[id!="product-row-template"]');
    if (remainingRows.length === 0) {
        emptyState.style.display = 'block';
        totalSummary.style.display = 'none';
    }

    // Update totals
    updateTotals();
}

function updateProductInfo(select) {
    const row = select.closest('.product-row');
    const productInfo = row.querySelector('.product-info');
    const selectedOption = select.options[select.selectedIndex];

    if (selectedOption.value) {
        const name = selectedOption.dataset.name;
        const code = selectedOption.dataset.code;
        const unit = selectedOption.dataset.unit;

        row.querySelector('.product-name').textContent = name;
        row.querySelector('.product-code').textContent = code;
        row.querySelector('.product-unit').textContent = unit;

        productInfo.style.display = 'block';

        // Auto-generate batch number suggestion
        generateBatchNumberSuggestion(row);
    } else {
        productInfo.style.display = 'none';
    }
}

function calculateRowTotal(input) {
    const row = input.closest('.product-row');
    const quantityInput = row.querySelector('input[name*="[quantity]"]');
    const costInput = row.querySelector('input[name*="[cost_price]"]');
    const totalDiv = row.querySelector('.row-total');

    const quantity = parseFloat(quantityInput.value) || 0;
    const cost = parseFloat(costInput.value) || 0;
    const total = quantity * cost;

    totalDiv.textContent = total.toFixed(2) + ' د.ع';

    // Update grand total
    updateTotals();
}

function updateTotals() {
    const container = document.getElementById('products-container');
    const rows = container.querySelectorAll('.product-row[id!="product-row-template"]');

    let grandTotal = 0;
    let productCount = 0;

    rows.forEach(row => {
        const quantityInput = row.querySelector('input[name*="[quantity]"]');
        const costInput = row.querySelector('input[name*="[cost_price]"]');

        if (quantityInput && costInput) {
            const quantity = parseFloat(quantityInput.value) || 0;
            const cost = parseFloat(costInput.value) || 0;
            grandTotal += quantity * cost;

            if (quantity > 0) {
                productCount++;
            }
        }
    });

    document.getElementById('grand-total').textContent = grandTotal.toFixed(2) + ' د.ع';
    document.getElementById('total-products').textContent = productCount;
}

function updateLocationSuggestions(row) {
    const warehouseSelect = document.querySelector('select[name="warehouse_id"]');
    const locationInput = row.querySelector('input[name*="[location_code]"]');

    if (warehouseSelect.value) {
        const warehouseName = warehouseSelect.options[warehouseSelect.selectedIndex].text.split(' ')[0];
        locationInput.placeholder = `مثال: ${warehouseName}-A-01-01`;
    } else {
        locationInput.placeholder = 'مثال: A-01-01';
    }
}

function generateBatchNumberSuggestion(row) {
    const batchInput = row.querySelector('input[name*="[batch_number]"]');

    if (!batchInput.value) {
        const today = new Date();
        const dateStr = today.getFullYear().toString().substr(-2) +
                       String(today.getMonth() + 1).padStart(2, '0') +
                       String(today.getDate()).padStart(2, '0');
        batchInput.placeholder = `مثال: BATCH-${dateStr}-001`;
    }
}

// Update all location suggestions when warehouse changes
document.querySelector('select[name="warehouse_id"]').addEventListener('change', function() {
    const container = document.getElementById('products-container');
    const rows = container.querySelectorAll('.product-row[id!="product-row-template"]');

    rows.forEach(row => {
        updateLocationSuggestions(row);
    });
});

// Update status for all products when default status changes
document.querySelector('select[name="default_status"]').addEventListener('change', function() {
    const newStatus = this.value;
    const container = document.getElementById('products-container');
    const statusSelects = container.querySelectorAll('.status-select');

    statusSelects.forEach(select => {
        select.value = newStatus;
    });
});

// Form validation
document.getElementById('inventoryForm').addEventListener('submit', function(e) {
    const warehouseId = document.querySelector('select[name="warehouse_id"]').value;
    const defaultStatus = document.querySelector('select[name="default_status"]').value;

    if (!warehouseId || !defaultStatus) {
        e.preventDefault();
        alert('يرجى ملء جميع الحقول المطلوبة في المعلومات العامة');
        return false;
    }

    // Check if at least one product is added
    const container = document.getElementById('products-container');
    const rows = container.querySelectorAll('.product-row[id!="product-row-template"]');

    if (rows.length === 0) {
        e.preventDefault();
        alert('يرجى إضافة منتج واحد على الأقل');
        return false;
    }

    // Validate each product row
    let hasValidProduct = false;
    for (let row of rows) {
        const productSelect = row.querySelector('select[name*="[product_id]"]');
        const quantityInput = row.querySelector('input[name*="[quantity]"]');
        const expiryInput = row.querySelector('input[name*="[expiry_date]"]');
        const statusSelect = row.querySelector('select[name*="[status]"]');

        if (productSelect.value && quantityInput.value) {
            const quantity = parseFloat(quantityInput.value);
            if (quantity > 0) {
                hasValidProduct = true;

                // Check expiry date if provided
                if (expiryInput.value) {
                    const today = new Date();
                    const expiry = new Date(expiryInput.value);

                    if (expiry <= today && statusSelect.value === 'active') {
                        if (!confirm('أحد المنتجات له تاريخ انتهاء صلاحية في الماضي أو اليوم والحالة نشطة. هل تريد المتابعة؟')) {
                            e.preventDefault();
                            return false;
                        }
                    }
                }
            } else {
                e.preventDefault();
                alert('يرجى إدخال كمية صحيحة لجميع المنتجات');
                return false;
            }
        } else if (productSelect.value || quantityInput.value) {
            e.preventDefault();
            alert('يرجى ملء جميع الحقول المطلوبة لكل منتج (المنتج والكمية)');
            return false;
        }
    }

    if (!hasValidProduct) {
        e.preventDefault();
        alert('يرجى إضافة منتج واحد صحيح على الأقل مع كمية أكبر من صفر');
        return false;
    }
});
</script>
@endpush
@endsection
