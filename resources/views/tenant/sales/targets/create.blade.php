@extends('layouts.tenant')

@section('title', 'إنشاء هدف بيع جديد')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); border-radius: 20px; padding: 30px; margin-bottom: 30px; color: white;">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px;">
            <div>
                <h1 style="margin: 0; font-size: 28px; font-weight: 700;">
                    <i class="fas fa-plus-circle" style="margin-left: 10px;"></i>
                    إنشاء هدف بيع جديد
                </h1>
                <p style="margin: 5px 0 0 0; opacity: 0.9; font-size: 16px;">
                    تحديد هدف جديد للمبيعات مع إعدادات التتبع والإشعارات
                </p>
            </div>
            <div>
                <a href="{{ route('tenant.sales.targets.index') }}" 
                   style="background: rgba(255,255,255,0.2); color: white; padding: 12px 20px; border-radius: 10px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 8px; backdrop-filter: blur(10px);">
                    <i class="fas fa-arrow-right"></i>
                    العودة للقائمة
                </a>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div style="background: white; border-radius: 15px; padding: 30px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <form method="POST" action="{{ route('tenant.sales.targets.store') }}" id="targetForm">
            @csrf
            
            <!-- Basic Information -->
            <div style="margin-bottom: 30px;">
                <h3 style="color: #1f2937; margin: 0 0 20px 0; font-size: 20px; font-weight: 600; border-bottom: 2px solid #e5e7eb; padding-bottom: 10px;">
                    <i class="fas fa-info-circle" style="margin-left: 8px; color: #4299e1;"></i>
                    المعلومات الأساسية
                </h3>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
                    <div>
                        <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #374151;">عنوان الهدف *</label>
                        <input type="text" name="title" value="{{ old('title') }}" required
                               style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px;"
                               placeholder="مثال: هدف مبيعات أدوية القلب - يناير 2024">
                        @error('title')
                            <div style="color: #ef4444; font-size: 12px; margin-top: 5px;">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div>
                        <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #374151;">نوع الهدف *</label>
                        <select name="target_type" id="targetType" required onchange="updateEntityOptions()"
                                style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px;">
                            <option value="">اختر نوع الهدف</option>
                            <option value="product" {{ old('target_type') == 'product' ? 'selected' : '' }}>منتج</option>
                            <option value="vendor" {{ old('target_type') == 'vendor' ? 'selected' : '' }}>شركة</option>
                            <option value="sales_team" {{ old('target_type') == 'sales_team' ? 'selected' : '' }}>فريق مبيعات</option>
                            <option value="department" {{ old('target_type') == 'department' ? 'selected' : '' }}>قسم</option>
                            <option value="sales_rep" {{ old('target_type') == 'sales_rep' ? 'selected' : '' }}>مندوب مبيعات</option>
                        </select>
                        @error('target_type')
                            <div style="color: #ef4444; font-size: 12px; margin-top: 5px;">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div style="margin-top: 20px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #374151;">الكيان المستهدف *</label>
                    <select name="target_entity_id" id="targetEntity" required
                            style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px;">
                        <option value="">اختر الكيان المستهدف</option>
                    </select>
                    <input type="hidden" name="target_entity_name" id="targetEntityName" value="{{ old('target_entity_name') }}">
                    @error('target_entity_id')
                        <div style="color: #ef4444; font-size: 12px; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>
                
                <div style="margin-top: 20px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #374151;">وصف الهدف</label>
                    <textarea name="description" rows="3"
                              style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; resize: vertical;"
                              placeholder="وصف تفصيلي للهدف وأهدافه...">{{ old('description') }}</textarea>
                    @error('description')
                        <div style="color: #ef4444; font-size: 12px; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Time Period -->
            <div style="margin-bottom: 30px;">
                <h3 style="color: #1f2937; margin: 0 0 20px 0; font-size: 20px; font-weight: 600; border-bottom: 2px solid #e5e7eb; padding-bottom: 10px;">
                    <i class="fas fa-calendar-alt" style="margin-left: 8px; color: #f59e0b;"></i>
                    الفترة الزمنية
                </h3>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
                    <div>
                        <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #374151;">نوع الفترة *</label>
                        <select name="period_type" id="periodType" required onchange="updateDateFields()"
                                style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px;">
                            <option value="">اختر نوع الفترة</option>
                            <option value="monthly" {{ old('period_type') == 'monthly' ? 'selected' : '' }}>شهرية</option>
                            <option value="quarterly" {{ old('period_type') == 'quarterly' ? 'selected' : '' }}>فصلية</option>
                            <option value="yearly" {{ old('period_type') == 'yearly' ? 'selected' : '' }}>سنوية</option>
                        </select>
                        @error('period_type')
                            <div style="color: #ef4444; font-size: 12px; margin-top: 5px;">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div>
                        <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #374151;">تاريخ البداية *</label>
                        <input type="date" name="start_date" id="startDate" value="{{ old('start_date') }}" required
                               style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px;">
                        @error('start_date')
                            <div style="color: #ef4444; font-size: 12px; margin-top: 5px;">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div>
                        <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #374151;">تاريخ النهاية *</label>
                        <input type="date" name="end_date" id="endDate" value="{{ old('end_date') }}" required
                               style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px;">
                        @error('end_date')
                            <div style="color: #ef4444; font-size: 12px; margin-top: 5px;">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Target Values -->
            <div style="margin-bottom: 30px;">
                <h3 style="color: #1f2937; margin: 0 0 20px 0; font-size: 20px; font-weight: 600; border-bottom: 2px solid #e5e7eb; padding-bottom: 10px;">
                    <i class="fas fa-target" style="margin-left: 8px; color: #10b981;"></i>
                    قيم الهدف
                </h3>
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #374151;">نوع القياس *</label>
                    <select name="measurement_type" id="measurementType" required onchange="updateMeasurementFields()"
                            style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px;">
                        <option value="">اختر نوع القياس</option>
                        <option value="quantity" {{ old('measurement_type') == 'quantity' ? 'selected' : '' }}>الكمية فقط</option>
                        <option value="value" {{ old('measurement_type') == 'value' ? 'selected' : '' }}>القيمة فقط</option>
                        <option value="both" {{ old('measurement_type') == 'both' ? 'selected' : '' }}>الكمية والقيمة</option>
                    </select>
                    @error('measurement_type')
                        <div style="color: #ef4444; font-size: 12px; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
                    <div id="quantityField" style="display: none;">
                        <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #374151;">الكمية المستهدفة</label>
                        <div style="display: flex; gap: 10px;">
                            <input type="number" name="target_quantity" value="{{ old('target_quantity') }}" step="0.01" min="0"
                                   style="flex: 1; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px;"
                                   placeholder="0.00">
                            <input type="text" name="unit" value="{{ old('unit') }}" 
                                   style="width: 100px; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px;"
                                   placeholder="الوحدة">
                        </div>
                        @error('target_quantity')
                            <div style="color: #ef4444; font-size: 12px; margin-top: 5px;">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div id="valueField" style="display: none;">
                        <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #374151;">القيمة المستهدفة</label>
                        <div style="display: flex; gap: 10px;">
                            <input type="number" name="target_value" value="{{ old('target_value') }}" step="0.01" min="0"
                                   style="flex: 1; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px;"
                                   placeholder="0.00">
                            <select name="currency" style="width: 100px; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px;">
                                <option value="IQD" {{ old('currency') == 'IQD' ? 'selected' : '' }}>IQD</option>
                                <option value="USD" {{ old('currency') == 'USD' ? 'selected' : '' }}>USD</option>
                                <option value="EUR" {{ old('currency') == 'EUR' ? 'selected' : '' }}>EUR</option>
                            </select>
                        </div>
                        @error('target_value')
                            <div style="color: #ef4444; font-size: 12px; margin-top: 5px;">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Additional Settings -->
            <div style="margin-bottom: 30px;">
                <h3 style="color: #1f2937; margin: 0 0 20px 0; font-size: 20px; font-weight: 600; border-bottom: 2px solid #e5e7eb; padding-bottom: 10px;">
                    <i class="fas fa-cog" style="margin-left: 8px; color: #8b5cf6;"></i>
                    إعدادات إضافية
                </h3>
                
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #374151;">ملاحظات</label>
                    <textarea name="notes" rows="3"
                              style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; resize: vertical;"
                              placeholder="أي ملاحظات أو تعليمات خاصة بالهدف...">{{ old('notes') }}</textarea>
                    @error('notes')
                        <div style="color: #ef4444; font-size: 12px; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Submit Buttons -->
            <div style="display: flex; gap: 15px; justify-content: flex-end; padding-top: 20px; border-top: 1px solid #e5e7eb;">
                <a href="{{ route('tenant.sales.targets.index') }}" 
                   style="background: #6b7280; color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600;">
                    <i class="fas fa-times"></i> إلغاء
                </a>
                <button type="submit" 
                        style="background: #10b981; color: white; padding: 12px 24px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                    <i class="fas fa-save"></i> حفظ الهدف
                </button>
            </div>
        </form>
    </div>
</div>

<!-- JavaScript -->
<script>
// Data for entities
const entitiesData = {
    product: @json($products ?? []),
    vendor: @json($vendors ?? []),
    sales_rep: @json($salesReps ?? [])
};

// Update entity options based on target type
function updateEntityOptions() {
    const targetType = document.getElementById('targetType').value;
    const entitySelect = document.getElementById('targetEntity');
    const entityNameInput = document.getElementById('targetEntityName');
    
    // Clear existing options
    entitySelect.innerHTML = '<option value="">اختر الكيان المستهدف</option>';
    entityNameInput.value = '';
    
    if (targetType && entitiesData[targetType]) {
        entitiesData[targetType].forEach(entity => {
            const option = document.createElement('option');
            option.value = entity.id;
            option.textContent = entity.name || entity.title || entity.company_name;
            option.dataset.name = entity.name || entity.title || entity.company_name;
            entitySelect.appendChild(option);
        });
    } else if (targetType === 'sales_team') {
        // Add sample sales teams
        const teams = ['فريق المبيعات الأول', 'فريق المبيعات الثاني', 'فريق المبيعات الثالث'];
        teams.forEach((team, index) => {
            const option = document.createElement('option');
            option.value = index + 1;
            option.textContent = team;
            option.dataset.name = team;
            entitySelect.appendChild(option);
        });
    } else if (targetType === 'department') {
        // Add sample departments
        const departments = ['قسم المبيعات', 'قسم التسويق', 'قسم خدمة العملاء'];
        departments.forEach((dept, index) => {
            const option = document.createElement('option');
            option.value = index + 1;
            option.textContent = dept;
            option.dataset.name = dept;
            entitySelect.appendChild(option);
        });
    }
}

// Update entity name when selection changes
document.getElementById('targetEntity').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const entityNameInput = document.getElementById('targetEntityName');
    entityNameInput.value = selectedOption.dataset.name || selectedOption.textContent;
});

// Update measurement fields based on measurement type
function updateMeasurementFields() {
    const measurementType = document.getElementById('measurementType').value;
    const quantityField = document.getElementById('quantityField');
    const valueField = document.getElementById('valueField');
    
    quantityField.style.display = 'none';
    valueField.style.display = 'none';
    
    if (measurementType === 'quantity' || measurementType === 'both') {
        quantityField.style.display = 'block';
    }
    
    if (measurementType === 'value' || measurementType === 'both') {
        valueField.style.display = 'block';
    }
}

// Update date fields based on period type
function updateDateFields() {
    const periodType = document.getElementById('periodType').value;
    const startDate = document.getElementById('startDate');
    const endDate = document.getElementById('endDate');
    
    if (periodType && startDate.value) {
        const start = new Date(startDate.value);
        let end = new Date(start);
        
        switch (periodType) {
            case 'monthly':
                end.setMonth(end.getMonth() + 1);
                end.setDate(end.getDate() - 1);
                break;
            case 'quarterly':
                end.setMonth(end.getMonth() + 3);
                end.setDate(end.getDate() - 1);
                break;
            case 'yearly':
                end.setFullYear(end.getFullYear() + 1);
                end.setDate(end.getDate() - 1);
                break;
        }
        
        endDate.value = end.toISOString().split('T')[0];
    }
}

// Initialize form
document.addEventListener('DOMContentLoaded', function() {
    updateEntityOptions();
    updateMeasurementFields();
    
    // Set default start date to today
    const today = new Date().toISOString().split('T')[0];
    if (!document.getElementById('startDate').value) {
        document.getElementById('startDate').value = today;
    }
});

// Update end date when start date changes
document.getElementById('startDate').addEventListener('change', updateDateFields);
</script>
@endsection
