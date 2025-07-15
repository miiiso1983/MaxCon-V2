@extends('layouts.modern')

@section('page-title', 'طلب شراء جديد')
@section('page-description', 'إنشاء طلب شراء داخلي جديد')

@section('content')
<!-- Page Header -->
<div style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); border-radius: 20px; padding: 30px; margin-bottom: 30px; color: white; position: relative; overflow: hidden;">
    <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
    <div style="position: absolute; bottom: -30px; left: -30px; width: 150px; height: 150px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>
    
    <div style="position: relative; z-index: 2;">
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    <div style="background: rgba(255,255,255,0.2); border-radius: 15px; padding: 15px; margin-left: 20px;">
                        <i class="fas fa-plus" style="font-size: 32px;"></i>
                    </div>
                    <div>
                        <h1 style="font-size: 32px; font-weight: 800; margin: 0; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                            طلب شراء جديد ✨
                        </h1>
                        <p style="font-size: 18px; margin: 5px 0 0 0; opacity: 0.9;">
                            إنشاء طلب شراء داخلي جديد
                        </p>
                    </div>
                </div>
            </div>
            
            <div style="display: flex; gap: 15px;">
                <a href="{{ route('tenant.purchasing.purchase-requests.index') }}" style="background: rgba(255,255,255,0.2); color: white; padding: 15px 25px; border-radius: 15px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3);">
                    <i class="fas fa-arrow-right"></i>
                    العودة للقائمة
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Form -->
<div class="content-card">
    <form method="POST" action="{{ route('tenant.purchasing.purchase-requests.store') }}" id="purchaseRequestForm">
        @csrf
        
        <!-- Basic Information -->
        <div style="margin-bottom: 30px;">
            <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
                <i class="fas fa-info-circle" style="color: #3b82f6; margin-left: 10px;"></i>
                المعلومات الأساسية
            </h3>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">عنوان الطلب *</label>
                    <input type="text" name="title" value="{{ old('title') }}" required 
                           style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 16px;"
                           placeholder="أدخل عنوان الطلب">
                    @error('title')
                        <div style="color: #ef4444; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>
                
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">الأولوية *</label>
                    <select name="priority" required style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 16px;">
                        <option value="">اختر الأولوية</option>
                        <option value="low" {{ old('priority') === 'low' ? 'selected' : '' }}>منخفضة</option>
                        <option value="medium" {{ old('priority') === 'medium' ? 'selected' : '' }}>متوسطة</option>
                        <option value="high" {{ old('priority') === 'high' ? 'selected' : '' }}>عالية</option>
                        <option value="urgent" {{ old('priority') === 'urgent' ? 'selected' : '' }}>عاجل</option>
                    </select>
                    @error('priority')
                        <div style="color: #ef4444; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">التاريخ المطلوب *</label>
                    <input type="date" name="required_date" value="{{ old('required_date') }}" required 
                           style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 16px;"
                           min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                    @error('required_date')
                        <div style="color: #ef4444; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>
                
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">القيمة المقدرة</label>
                    <input type="number" name="estimated_total" value="{{ old('estimated_total') }}" step="0.01" min="0"
                           style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 16px;"
                           placeholder="0.00">
                    @error('estimated_total')
                        <div style="color: #ef4444; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">وصف الطلب</label>
                <textarea name="description" rows="3" 
                          style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 16px; resize: vertical;"
                          placeholder="أدخل وصف تفصيلي للطلب">{{ old('description') }}</textarea>
                @error('description')
                    <div style="color: #ef4444; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">مبرر الطلب</label>
                <textarea name="justification" rows="2" 
                          style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 16px; resize: vertical;"
                          placeholder="أدخل مبرر الحاجة لهذا الطلب">{{ old('justification') }}</textarea>
                @error('justification')
                    <div style="color: #ef4444; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
            
            <div style="display: flex; align-items: center; gap: 10px;">
                <input type="checkbox" name="is_urgent" id="is_urgent" value="1" {{ old('is_urgent') ? 'checked' : '' }}
                       style="width: 18px; height: 18px;">
                <label for="is_urgent" style="font-weight: 600; color: #ef4444; cursor: pointer;">
                    <i class="fas fa-exclamation-triangle" style="margin-left: 5px;"></i>
                    طلب عاجل
                </label>
            </div>
        </div>
        
        <!-- Items Section -->
        <div style="margin-bottom: 30px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin: 0; display: flex; align-items: center;">
                    <i class="fas fa-list" style="color: #3b82f6; margin-left: 10px;"></i>
                    عناصر الطلب
                </h3>
                <button type="button" onclick="addItem()" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 10px 15px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-plus"></i>
                    إضافة عنصر
                </button>
            </div>
            
            <div id="itemsContainer">
                <!-- Items will be added here -->
            </div>
            
            @error('items')
                <div style="color: #ef4444; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
            @enderror
        </div>
        
        <!-- Additional Information -->
        <div style="margin-bottom: 30px;">
            <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
                <i class="fas fa-cog" style="color: #3b82f6; margin-left: 10px;"></i>
                معلومات إضافية
            </h3>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">رمز الميزانية</label>
                    <input type="text" name="budget_code" value="{{ old('budget_code') }}" 
                           style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 16px;"
                           placeholder="أدخل رمز الميزانية">
                    @error('budget_code')
                        <div style="color: #ef4444; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>
                
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">مركز التكلفة</label>
                    <input type="text" name="cost_center" value="{{ old('cost_center') }}" 
                           style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 16px;"
                           placeholder="أدخل مركز التكلفة">
                    @error('cost_center')
                        <div style="color: #ef4444; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">تعليمات خاصة</label>
                <textarea name="special_instructions" rows="3" 
                          style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 16px; resize: vertical;"
                          placeholder="أدخل أي تعليمات خاصة للطلب">{{ old('special_instructions') }}</textarea>
                @error('special_instructions')
                    <div style="color: #ef4444; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
        </div>
        
        <!-- Submit Buttons -->
        <div style="display: flex; gap: 15px; justify-content: flex-end; padding-top: 20px; border-top: 1px solid #e2e8f0;">
            <a href="{{ route('tenant.purchasing.purchase-requests.index') }}" style="background: #6b7280; color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-times"></i>
                إلغاء
            </a>
            <button type="submit" style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); color: white; padding: 12px 24px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-save"></i>
                حفظ الطلب
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
let itemIndex = 0;

function addItem() {
    const container = document.getElementById('itemsContainer');
    const itemHtml = `
        <div class="item-row" style="background: #f8fafc; padding: 20px; border-radius: 12px; margin-bottom: 15px; border: 1px solid #e2e8f0;">
            <div style="display: flex; justify-content: between; align-items: center; margin-bottom: 15px;">
                <h4 style="margin: 0; color: #2d3748; font-size: 16px; font-weight: 600;">عنصر ${itemIndex + 1}</h4>
                <button type="button" onclick="removeItem(this)" style="background: #ef4444; color: white; padding: 6px 10px; border: none; border-radius: 6px; cursor: pointer; font-size: 12px;">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
            
            <div style="display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #4a5568; font-size: 14px;">اسم العنصر *</label>
                    <input type="text" name="items[${itemIndex}][item_name]" required 
                           style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 6px; font-size: 14px;"
                           placeholder="أدخل اسم العنصر">
                </div>
                
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #4a5568; font-size: 14px;">الكمية *</label>
                    <input type="number" name="items[${itemIndex}][quantity]" required step="0.01" min="0.01"
                           style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 6px; font-size: 14px;"
                           placeholder="0">
                </div>
                
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #4a5568; font-size: 14px;">السعر المقدر</label>
                    <input type="number" name="items[${itemIndex}][estimated_price]" step="0.01" min="0"
                           style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 6px; font-size: 14px;"
                           placeholder="0.00">
                </div>
            </div>
            
            <div style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #4a5568; font-size: 14px;">الوحدة</label>
                <input type="text" name="items[${itemIndex}][unit]" value="قطعة"
                       style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 6px; font-size: 14px;"
                       placeholder="قطعة، كيلو، لتر...">
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #4a5568; font-size: 14px;">المواصفات</label>
                <textarea name="items[${itemIndex}][specifications]" rows="2"
                          style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 6px; font-size: 14px; resize: vertical;"
                          placeholder="أدخل المواصفات المطلوبة"></textarea>
            </div>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', itemHtml);
    itemIndex++;
}

function removeItem(button) {
    button.closest('.item-row').remove();
}

// Add first item on page load
document.addEventListener('DOMContentLoaded', function() {
    addItem();
});
</script>
@endpush
@endsection
