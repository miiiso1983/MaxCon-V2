@extends('layouts.modern')

@section('page-title', 'إنشاء حركة مخزون جديدة')
@section('page-description', 'إضافة حركة مخزون جديدة للنظام')

@section('content')
<!-- رسائل التنبيه -->
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); border: none; border-radius: 12px; color: white; margin-bottom: 20px;">
        <div style="display: flex; align-items: flex-start; gap: 12px;">
            <i class="fas fa-check-circle" style="font-size: 20px; margin-top: 2px;"></i>
            <div style="flex: 1;">
                <strong>نجح!</strong>
                <div style="white-space: pre-line; margin-top: 8px; line-height: 1.6;">{{ session('success') }}</div>
            </div>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); border: none; border-radius: 12px; color: white; margin-bottom: 20px;">
        <div style="display: flex; align-items: flex-start; gap: 12px;">
            <i class="fas fa-exclamation-circle" style="font-size: 20px; margin-top: 2px;"></i>
            <div style="flex: 1;">
                <strong>خطأ!</strong>
                <div style="white-space: pre-line; margin-top: 8px; line-height: 1.6;">{{ session('error') }}</div>
            </div>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
@endif
@if(session('warning'))
    <div class="alert alert-warning alert-dismissible fade show" role="alert" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); border: none; border-radius: 12px; color: white; margin-bottom: 20px;">
        <div style="display: flex; align-items: flex-start; gap: 12px;">
            <i class="fas fa-exclamation-triangle" style="font-size: 20px; margin-top: 2px;"></i>
            <div style="flex: 1;">
                <strong>تحذير!</strong>
                <div style="white-space: pre-line; margin-top: 8px; line-height: 1.6;">{{ session('warning') }}</div>
            </div>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
@endif

<!-- Tabs -->
<div class="tab-container" style="background: white; border-radius: 15px; overflow: hidden; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); margin-bottom: 20px;">
    <div class="tab-header" style="display: flex; background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
        <button type="button" id="tab-manual" class="tab-button" onclick="showTab('manual')" style="flex:1; padding: 14px 20px; background: none; border: none; font-size: 16px; font-weight: 700; cursor: pointer;">إدخال يدوي</button>
        <button type="button" id="tab-excel" class="tab-button" onclick="showTab('excel')" style="flex:1; padding: 14px 20px; background: none; border: none; font-size: 16px; font-weight: 700; cursor: pointer;">رفع ملف Excel</button>
    </div>
</div>

<!-- قسم رفع ملف Excel -->
<div id="excel-upload-section" style="display:none; margin-bottom: 25px;">
    <div class="content-card" style="padding: 20px; background: #fff; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); border: 1px solid #e5e7eb;">
        <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 15px; display: flex; align-items: center;">
            <i class="fas fa-file-excel" style="color: #10b981; margin-left: 10px;"></i>
            رفع ملف Excel لحركات المخزون
        </h3>
        <form method="POST" action="{{ route('tenant.inventory.movements.import-excel') }}" enctype="multipart/form-data">
            @csrf
            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 15px; align-items: end;">
                <div>
                    <label style="display:block; margin-bottom:8px; font-weight:600; color:#4a5568;">اختر الملف (CSV/XLSX)</label>
                    <input type="file" name="excel_file" accept=".csv,.xlsx" required style="width:100%; padding:12px; border:2px solid #e2e8f0; border-radius:8px;">
                </div>
                <div style="display:flex; gap:10px;">
                    <a href="{{ route('tenant.inventory.movements.download-template') }}" class="btn btn-secondary" style="padding: 12px 16px; background:#3b82f6; color:white; border:none; border-radius:8px; text-decoration:none; display:flex; align-items:center; gap:8px;">
                        <i class="fas fa-download"></i>
                        تحميل القالب
                    </a>
                    <button type="submit" class="btn btn-primary" style="padding: 12px 16px; background:linear-gradient(135deg,#10b981 0%,#059669 100%); color:white; border:none; border-radius:8px; display:flex; align-items:center; gap:8px;">
                        <i class="fas fa-upload"></i>
                        رفع ومعالجة الملف
                    </button>
                    <button type="button" id="btnDryRun" style="padding: 12px 16px; background:#6b7280; color:white; border:none; border-radius:8px; display:flex; align-items:center; gap:8px;">
                        <i class="fas fa-vials"></i>
                        استيراد تجريبي
                    </button>
                </div>
            </div>
            <div style="margin-top: 12px; font-size: 13px; color:#6b7280;">
                الحقول المطلوبة في الملف: كود المنتج، اسم المنتج، كود المستودع، نوع الحركة (in/out)، الكمية، السبب، التاريخ (YYYY-MM-DD)، ملاحظات.
            </div>
        </form>
        <div style="margin-top:10px;">
            <a href="{{ route('tenant.inventory.movements.logs') }}" style="font-size: 13px;">عرض سجلات التشخيص</a>
        </div>
    </div>
</div>

<!-- Modal معاينة الاستيراد التجريبي -->
<div id="dryRunModal" style="display:none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 2000; align-items: center; justify-content: center;">
  <div style="background: #fff; width: min(900px, 95%); max-height: 80vh; overflow: auto; border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,0.2);">
    <div style="display:flex; align-items:center; justify-content: space-between; padding:16px 20px; border-bottom:1px solid #e5e7eb;">
      <div style="display:flex; align-items:center; gap:10px;">
        <i class="fas fa-vials" style="color:#10b981"></i>
        <h3 style="margin:0; font-weight:800; font-size:18px; color:#111827;">معاينة الاستيراد التجريبي</h3>
      </div>
      <button type="button" onclick="closeDryRunModal()" style="background:#ef4444; color:#fff; border:none; border-radius:8px; padding:8px 12px; cursor:pointer;"><i class="fas fa-times"></i> إغلاق</button>
    </div>
    <div style="padding: 16px 20px;">
      <div id="dry-run-meta" style="font-size:13px; color:#6b7280; margin-bottom:10px;"></div>
      <div id="dry-run-table-container" style="border:1px solid #e5e7eb; border-radius:12px; overflow:auto;"></div>
    </div>
  </div>
</div>


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
                            حركة مخزون جديدة ➕
                        </h1>
                        <p style="font-size: 18px; margin: 5px 0 0 0; opacity: 0.9;">
                            إضافة حركة مخزون جديدة للنظام
                        </p>
                    </div>
                </div>
            </div>

            <div style="display: flex; gap: 15px;">
                <a href="{{ route('tenant.inventory.movements.index') }}" style="background: rgba(255,255,255,0.2); color: white; padding: 15px 25px; border-radius: 15px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3);">
                    <i class="fas fa-arrow-right"></i>
                    العودة للقائمة
                </a>
            </div>
        </div>
    </div>
</div>

<form method="POST" action="{{ route('tenant.inventory.movements.store') }}" id="movementForm">
    @csrf

    <!-- Basic Information -->
    <div class="content-card" style="margin-bottom: 25px;">
        <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-info-circle" style="color: #667eea; margin-left: 10px;"></i>
            معلومات الحركة
        </h3>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">نوع الحركة *</label>
                <select name="movement_type" class="simple-select" required style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;" onchange="updateMovementOptions()">
                    <option value="">اختر نوع الحركة</option>
                    <option value="in">إدخال</option>
                    <option value="out">إخراج</option>
                    <option value="transfer_in">تحويل وارد</option>
                    <option value="transfer_out">تحويل صادر</option>
                    <option value="adjustment_in">تعديل زيادة</option>
                    <option value="adjustment_out">تعديل نقص</option>
                    <option value="return_in">إرجاع وارد</option>
                    <option value="return_out">إرجاع صادر</option>
                </select>
                @error('movement_type')
                    <div style="color: #f56565; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">سبب الحركة *</label>
                <select name="movement_reason" class="simple-select" required style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;">
                    <option value="">اختر سبب الحركة</option>
                    <option value="purchase">شراء</option>
                    <option value="sale">بيع</option>
                    <option value="transfer">تحويل</option>
                    <option value="adjustment">تعديل</option>
                    <option value="return">إرجاع</option>
                    <option value="damage">تلف</option>
                    <option value="expired">انتهاء صلاحية</option>
                    <option value="theft">سرقة</option>
                    <option value="audit">جرد</option>
                    <option value="initial">رصيد افتتاحي</option>
                </select>
                @error('movement_reason')
                    <div style="color: #f56565; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">المستودع *</label>
                <select name="warehouse_id" class="simple-select" required style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;">
                    <option value="">اختر المستودع</option>
                    @foreach($warehouses as $warehouse)
                        <option value="{{ $warehouse->id }}">{{ $warehouse->name }} ({{ $warehouse->code }})</option>
                    @endforeach
                </select>
                @error('warehouse_id')
                    <div style="color: #f56565; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>



            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">تاريخ الحركة *</label>
                <input type="datetime-local" name="movement_date" value="{{ old('movement_date', now()->format('Y-m-d\TH:i')) }}" required style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;">
                @error('movement_date')
                    <div style="color: #f56565; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">رقم المرجع</label>
                <input type="text" name="reference_number" value="{{ old('reference_number') }}" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;" placeholder="رقم الفاتورة أو المرجع">
                @error('reference_number')
                    <div style="color: #f56565; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div style="margin-top: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">ملاحظات</label>
            <textarea name="notes" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; height: 80px;" placeholder="ملاحظات إضافية حول الحركة...">{{ old('notes') }}</textarea>
            @error('notes')
                <div style="color: #f56565; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
            @enderror
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
                            <div class="product-selector" style="position: relative;">
                                <input type="hidden" name="products[INDEX][product_id]" class="product-id-input">
                                <div class="product-dropdown" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; background: white; cursor: pointer; display: flex; justify-content: space-between; align-items: center;" onclick="toggleProductDropdown(this)">
                                    <span class="selected-text" style="color: #9ca3af;">اختر المنتج</span>
                                    <i class="fas fa-chevron-down" style="color: #6b7280;"></i>
                                </div>
                                <div class="product-options" style="position: absolute; top: 100%; left: 0; right: 0; background: white; border: 2px solid #e2e8f0; border-top: none; border-radius: 0 0 8px 8px; max-height: 240px; overflow-y: auto; z-index: 1000; display: none;">
                                    <div style="padding:8px; border-bottom:1px solid #f3f4f6;">
                                        <input type="text" class="product-search" placeholder="ابحث عن المنتج..." style="width:100%; padding:8px; border:1px solid #e5e7eb; border-radius:6px;">
                                    </div>
                                    <div class="product-option" data-value="" style="padding: 10px; cursor: pointer; border-bottom: 1px solid #f3f4f6;" onclick="selectProduct(this)">
                                        اختر المنتج
                                    </div>
                                    @foreach($products as $product)
                                        <div class="product-option" data-value="{{ $product->id }}" data-name="{{ $product->name }}" data-code="{{ $product->code ?? $product->product_code }}" data-unit="{{ $product->unit ?? 'وحدة' }}" style="padding: 10px; cursor: pointer; border-bottom: 1px solid #f3f4f6;" onclick="selectProduct(this)">
                                            {{ $product->name }} ({{ $product->code ?? $product->product_code }})
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div>
                            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">الكمية *</label>
                            <input type="number" name="products[INDEX][quantity]" required min="0" step="0.001" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;" placeholder="0" onchange="calculateRowTotal(this)">
                        </div>

                        <div>
                            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">تكلفة الوحدة</label>
                            <input type="number" name="products[INDEX][unit_cost]" min="0" step="0.01" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;" placeholder="0.00" onchange="calculateRowTotal(this)">
                        </div>

                        <div>
                            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">الإجمالي</label>
                            <div class="row-total" style="padding: 12px; background: #f0f9ff; border: 2px solid #3b82f6; border-radius: 8px; font-weight: 600; color: #1e40af; text-align: center;">0.00 د.ع</div>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                        <div>
                            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">رقم الدفعة</label>
                            <input type="text" name="products[INDEX][batch_number]" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;" placeholder="رقم الدفعة">
                        </div>

                        <div>
                            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">ملاحظات المنتج</label>
                            <input type="text" name="products[INDEX][notes]" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;" placeholder="ملاحظات خاصة بهذا المنتج">
                        </div>
                    </div>

                    <div class="product-info" style="margin-top: 10px; padding: 10px; background: #f0fdf4; border-radius: 8px; display: none;">
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
            <p style="margin: 0 0 15px 0;">انقر على "إضافة منتج" لبدء إضافة المنتجات للحركة</p>
            <button type="button" onclick="addProductRow()" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 12px 24px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                <i class="fas fa-plus"></i> إضافة أول منتج
            </button>
        </div>

        <!-- Total Summary -->
        <div id="total-summary" style="display: none; margin-top: 20px; padding: 20px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 12px; color: white;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h4 style="margin: 0 0 5px 0; font-size: 18px;">إجمالي الحركة</h4>
                    <div style="font-size: 14px; opacity: 0.9;">عدد المنتجات: <span id="total-products">0</span></div>
                </div>
                <div style="text-align: left;">
                    <div style="font-size: 28px; font-weight: 700;" id="grand-total">0.00 د.ع</div>
                    <div style="font-size: 14px; opacity: 0.9;">الإجمالي الكلي</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Actions -->
    <div style="display: flex; gap: 15px; justify-content: flex-end;">
        <a href="{{ route('tenant.inventory.movements.index') }}" style="padding: 12px 24px; border: 2px solid #e2e8f0; border-radius: 8px; background: white; color: #4a5568; font-weight: 600; text-decoration: none; display: flex; align-items: center; gap: 8px;">
            <i class="fas fa-times"></i>
            إلغاء
        </a>
        <button type="submit" style="padding: 12px 24px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px;">
            <i class="fas fa-save"></i>
            إنشاء الحركة
        </button>
    </div>
</form>

@push('scripts')
<script>
let productRowIndex = 0;

function updateMovementOptions() {
    const movementType = document.querySelector('select[name="movement_type"]').value;
    const reasonSelect = document.querySelector('select[name="movement_reason"]');

    // Clear current options
    reasonSelect.innerHTML = '<option value="">اختر سبب الحركة</option>';

    let reasons = [];

    switch(movementType) {
        case 'in':
        case 'transfer_in':
        case 'return_in':
        case 'adjustment_in':
            reasons = [
                {value: 'purchase', text: 'شراء'},
                {value: 'transfer', text: 'تحويل'},
                {value: 'return', text: 'إرجاع'},
                {value: 'adjustment', text: 'تعديل'},
                {value: 'initial', text: 'رصيد افتتاحي'}
            ];
            break;
        case 'out':
        case 'transfer_out':
        case 'return_out':
        case 'adjustment_out':
            reasons = [
                {value: 'sale', text: 'بيع'},
                {value: 'transfer', text: 'تحويل'},
                {value: 'return', text: 'إرجاع'},
                {value: 'adjustment', text: 'تعديل'},
                {value: 'damage', text: 'تلف'},
                {value: 'expired', text: 'انتهاء صلاحية'},
                {value: 'theft', text: 'سرقة'}
            ];
            break;
    }

    reasons.forEach(reason => {
        const option = document.createElement('option');
        option.value = reason.value;
        option.textContent = reason.text;
        reasonSelect.appendChild(option);
    });
}

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

    // Add to container
    container.appendChild(newRow);

    // Hide empty state and show total summary
    emptyState.style.display = 'none';
    totalSummary.style.display = 'block';

    // Increment index
    productRowIndex++;

    // Update totals
    updateTotals();
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
    } else {
        productInfo.style.display = 'none';
    }
}

function calculateRowTotal(input) {
    const row = input.closest('.product-row');
    const quantityInput = row.querySelector('input[name*="[quantity]"]');
    const costInput = row.querySelector('input[name*="[unit_cost]"]');
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
        const costInput = row.querySelector('input[name*="[unit_cost]"]');

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

// Form validation (wrapped to ensure DOM is ready)
document.addEventListener('DOMContentLoaded', function(){
    const formEl = document.getElementById('movementForm');
    if (!formEl) return;
    // Populate reasons based on current movement type (if already chosen)
    try { updateMovementOptions(); } catch(_) {}
    formEl.addEventListener('submit', function(e) {
    const movementType = document.querySelector('select[name="movement_type"]').value;
    const warehouse = document.querySelector('select[name="warehouse_id"]').value;
    const movementReason = document.querySelector('select[name="movement_reason"]').value;

    if (!movementType || !warehouse || !movementReason) {
        e.preventDefault();
        alert('يرجى ملء جميع الحقول المطلوبة في معلومات الحركة');
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
        const productInput = row.querySelector('input[name*="[product_id]"]');
        const quantityInput = row.querySelector('input[name*="[quantity]"]');

        if (productInput && productInput.value && quantityInput && quantityInput.value) {
            const quantity = parseFloat(quantityInput.value);
            if (quantity > 0) {
                hasValidProduct = true;
            } else {
                e.preventDefault();
                alert('يرجى إدخال كمية صحيحة لجميع المنتجات');
                return false;
            }
        } else if ((productInput && productInput.value) || (quantityInput && quantityInput.value)) {
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
}); // end DOMContentLoaded wrapper for form validation


// دوال قائمة المنتجات المخصصة (مقتبسة من صفحة المخزون) — معرفة في النطاق العام
window.toggleProductDropdown = function(element) {
    const dropdown = element.nextElementSibling;
    const isVisible = dropdown.style.display === 'block';
    document.querySelectorAll('.product-options').forEach(opt => opt.style.display = 'none');
    dropdown.style.display = isVisible ? 'none' : 'block';
}

window.selectProduct = function(element) {
    const value = element.getAttribute('data-value');
    const name = element.getAttribute('data-name');
    const code = element.getAttribute('data-code');
    const unit = element.getAttribute('data-unit');
    const text = element.textContent.trim();

    const selector = element.closest('.product-selector');
    selector.querySelector('.product-id-input').value = value || '';
    selector.querySelector('.selected-text').textContent = text || 'اختر المنتج';
    selector.querySelector('.product-options').style.display = 'none';

    // تحديث معلومات المنتج في الصف
    const row = element.closest('.product-row');
    if (row) {
        const info = row.querySelector('.product-info');
        if (info) {
            row.querySelector('.product-name').textContent = name || '';
            row.querySelector('.product-code').textContent = code || '';
            row.querySelector('.product-unit').textContent = unit || '';
            info.style.display = value ? 'block' : 'none';
        }
    }
}

// بحث فوري داخل قائمة المنتجات
window.addEventListener('input', function(e){
    if (e.target && e.target.classList.contains('product-search')) {
        const q = e.target.value.toLowerCase();
        const list = e.target.closest('.product-options');
        list.querySelectorAll('.product-option').forEach(opt => {
            const text = opt.textContent.toLowerCase();
            opt.style.display = text.includes(q) ? 'block' : 'none';
        });
    }
});

// Modal helpers — معرفة في النطاق العام
window.openDryRunModal = function(data) {
    const modal = document.getElementById('dryRunModal');
    const meta = document.getElementById('dry-run-meta');
    const wrap = document.getElementById('dry-run-table-container');

    meta.textContent = `الملف: ${data.file?.name || ''} — نوع: ${data.file?.mime || ''} — صفوف معاينة: ${data.preview_rows?.length || 0}`;

    const rows = data.preview_rows || [];
    if (rows.length === 0) {
        wrap.innerHTML = '<div style="padding:16px; color:#6b7280;">لا توجد صفوف لعرضها</div>';
    } else {
        // جمع الأعمدة المتاحة من كل الصفوف
        const columnsSet = new Set();
        rows.forEach(r => Object.keys(r).forEach(k => columnsSet.add(k)));
        const columns = Array.from(columnsSet);

        let html = '<table style="width:100%; border-collapse: collapse;">';
        html += '<thead><tr>' + columns.map(c => `<th style="position:sticky; top:0; background:#f9fafb; text-align:right; padding:8px; border-bottom:1px solid #e5e7eb; font-weight:700;">${escapeHtml(c)}</th>`).join('') + '</tr></thead>';
        html += '<tbody>';
        rows.forEach(row => {
            html += '<tr>' + columns.map(c => `<td style="padding:8px; border-bottom:1px solid #f3f4f6; font-size:13px;">${escapeHtml(row[c] ?? '')}</td>`).join('') + '</tr>';
        });
        html += '</tbody></table>';
        wrap.innerHTML = html;
    }

    modal.style.display = 'flex';
}

window.closeDryRunModal = function() {
    document.getElementById('dryRunModal').style.display = 'none';
}

function escapeHtml(v){
    return String(v)
      .replace(/&/g,'&amp;')
      .replace(/</g,'&lt;')
      .replace(/>/g,'&gt;')
      .replace(/\"/g,'&quot;')
      .replace(/'/g,'&#039;');
}


// Auto-add first product row when page loads
document.addEventListener('DOMContentLoaded', function() {
    // تبويب افتراضي: الإدخال اليدوي
    showTab('manual');

    // استيراد تجريبي
    const dryBtn = document.getElementById('btnDryRun');
    if (dryBtn) {
        dryBtn.addEventListener('click', async function() {
            const fileInput = document.querySelector('#excel-upload-section input[type="file"][name="excel_file"]');
            if (!fileInput || !fileInput.files.length) {
                alert('يرجى اختيار ملف أولاً');
                return;
            }
            const formData = new FormData();
            formData.append('excel_file', fileInput.files[0]);
            try {
                const res = await fetch("{{ route('tenant.inventory.movements.import-dry-run') }}", {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: formData
                });
                const data = await res.json();
                if (data.status === 'ok') {
                    openDryRunModal(data);
                } else {
                    alert('فشل الاستيراد التجريبي: ' + (data.message || 'غير معروف'));

// [REMOVED - moved to global scope above]
/* window.toggleProductDropdown = function(element) { ... }
   window.selectProduct = function(element) { ... }
   function openDryRunModal(data) { ... }
   function closeDryRunModal() { ... }
   function escapeHtml(v) { ... }
*/

                }
            } catch (e) {
                alert('حدث خطأ أثناء الاستيراد التجريبي');
            }
        });
    }
});

function showTab(tab) {
    const excelSection = document.getElementById('excel-upload-section');
    const form = document.querySelector('form#movementForm');
    const tabManual = document.getElementById('tab-manual');
    const tabExcel = document.getElementById('tab-excel');

    if (tab === 'excel') {
        excelSection.style.display = 'block';
        form.style.display = 'none';
        tabExcel.style.borderBottom = '3px solid #10b981';
        tabManual.style.borderBottom = 'none';
    } else {
        excelSection.style.display = 'none';
        form.style.display = 'block';
        tabManual.style.borderBottom = '3px solid #667eea';
        tabExcel.style.borderBottom = 'none';
    }
}
</script>
@endpush
@endsection
