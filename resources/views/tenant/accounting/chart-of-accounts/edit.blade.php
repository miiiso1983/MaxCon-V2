@extends('layouts.modern')

@section('page-title', 'تعديل الحساب: ' . $chartOfAccount->account_name)
@section('page-description', 'تعديل بيانات الحساب المحاسبي')

@section('content')
<!-- Page Header -->
<div style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); border-radius: 20px; padding: 30px; margin-bottom: 30px; color: white; position: relative; overflow: hidden;">
    <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
    <div style="position: absolute; bottom: -30px; left: -30px; width: 150px; height: 150px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>
    
    <div style="position: relative; z-index: 2;">
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    <div style="background: rgba(255,255,255,0.2); border-radius: 15px; padding: 15px; margin-left: 20px;">
                        <i class="fas fa-edit" style="font-size: 32px;"></i>
                    </div>
                    <div>
                        <h1 style="font-size: 32px; font-weight: 800; margin: 0; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                            تعديل الحساب ✏️
                        </h1>
                        <p style="font-size: 18px; margin: 5px 0 0 0; opacity: 0.9;">
                            {{ $chartOfAccount->account_code }} - {{ $chartOfAccount->account_name }}
                        </p>
                    </div>
                </div>
            </div>
            
            <div style="display: flex; gap: 15px;">
                <a href="{{ route('tenant.accounting.chart-of-accounts.show', $chartOfAccount) }}" style="background: rgba(59, 130, 246, 0.2); color: white; padding: 15px 25px; border-radius: 15px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3);">
                    <i class="fas fa-eye"></i>
                    عرض الحساب
                </a>
                <a href="{{ route('tenant.accounting.chart-of-accounts.index') }}" style="background: rgba(255,255,255,0.2); color: white; padding: 15px 25px; border-radius: 15px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3);">
                    <i class="fas fa-arrow-right"></i>
                    العودة للقائمة
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Edit Form -->
<div class="content-card">
    <form method="POST" action="{{ route('tenant.accounting.chart-of-accounts.update', $chartOfAccount) }}">
        @csrf
        @method('PUT')
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
            <!-- Basic Information -->
            <div>
                <h3 style="color: #2d3748; margin-bottom: 20px; font-size: 20px; font-weight: 700; display: flex; align-items: center; gap: 10px; border-bottom: 2px solid #e2e8f0; padding-bottom: 10px;">
                    <i class="fas fa-info-circle" style="color: #f59e0b;"></i>
                    المعلومات الأساسية
                </h3>
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">اسم الحساب (عربي) *</label>
                    <input type="text" name="account_name" value="{{ old('account_name', $chartOfAccount->account_name) }}" required
                           style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 16px; transition: border-color 0.2s;"
                           placeholder="مثال: النقدية في الصندوق"
                           onfocus="this.style.borderColor='#f59e0b'" onblur="this.style.borderColor='#e2e8f0'">
                    @error('account_name')
                        <span style="color: #ef4444; font-size: 14px; margin-top: 5px; display: block;">{{ $message }}</span>
                    @enderror
                </div>
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">اسم الحساب (إنجليزي)</label>
                    <input type="text" name="account_name_en" value="{{ old('account_name_en', $chartOfAccount->account_name_en) }}"
                           style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 16px; transition: border-color 0.2s;"
                           placeholder="Example: Cash on Hand"
                           onfocus="this.style.borderColor='#f59e0b'" onblur="this.style.borderColor='#e2e8f0'">
                    @error('account_name_en')
                        <span style="color: #ef4444; font-size: 14px; margin-top: 5px; display: block;">{{ $message }}</span>
                    @enderror
                </div>
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">نوع الحساب *</label>
                    <select name="account_type" required id="accountType"
                            style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 16px; transition: border-color 0.2s;"
                            onfocus="this.style.borderColor='#f59e0b'" onblur="this.style.borderColor='#e2e8f0'"
                            onchange="updateCategories()">
                        <option value="">اختر نوع الحساب</option>
                        @foreach($accountTypes as $key => $value)
                            <option value="{{ $key }}" {{ old('account_type', $chartOfAccount->account_type) == $key ? 'selected' : '' }}>{{ $value }}</option>
                        @endforeach
                    </select>
                    @error('account_type')
                        <span style="color: #ef4444; font-size: 14px; margin-top: 5px; display: block;">{{ $message }}</span>
                    @enderror
                </div>
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">فئة الحساب *</label>
                    <select name="account_category" required id="accountCategory"
                            style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 16px; transition: border-color 0.2s;"
                            onfocus="this.style.borderColor='#f59e0b'" onblur="this.style.borderColor='#e2e8f0'">
                        <option value="">اختر فئة الحساب</option>
                        @foreach($accountCategories as $key => $value)
                            <option value="{{ $key }}" {{ old('account_category', $chartOfAccount->account_category) == $key ? 'selected' : '' }}>{{ $value }}</option>
                        @endforeach
                    </select>
                    @error('account_category')
                        <span style="color: #ef4444; font-size: 14px; margin-top: 5px; display: block;">{{ $message }}</span>
                    @enderror
                </div>
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">الوصف</label>
                    <textarea name="description" rows="3"
                              style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 16px; transition: border-color 0.2s; resize: vertical;"
                              placeholder="وصف مختصر للحساب..."
                              onfocus="this.style.borderColor='#f59e0b'" onblur="this.style.borderColor='#e2e8f0'">{{ old('description', $chartOfAccount->description) }}</textarea>
                    @error('description')
                        <span style="color: #ef4444; font-size: 14px; margin-top: 5px; display: block;">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            
            <!-- Additional Settings -->
            <div>
                <h3 style="color: #2d3748; margin-bottom: 20px; font-size: 20px; font-weight: 700; display: flex; align-items: center; gap: 10px; border-bottom: 2px solid #e2e8f0; padding-bottom: 10px;">
                    <i class="fas fa-cogs" style="color: #6366f1;"></i>
                    الإعدادات الإضافية
                </h3>
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">الحساب الأب</label>
                    <select name="parent_account_id"
                            style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 16px; transition: border-color 0.2s;"
                            onfocus="this.style.borderColor='#f59e0b'" onblur="this.style.borderColor='#e2e8f0'">
                        <option value="">لا يوجد (حساب رئيسي)</option>
                        @foreach($parentAccounts as $account)
                            <option value="{{ $account->id }}" {{ old('parent_account_id', $chartOfAccount->parent_account_id) == $account->id ? 'selected' : '' }}>
                                {{ $account->account_code }} - {{ $account->account_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('parent_account_id')
                        <span style="color: #ef4444; font-size: 14px; margin-top: 5px; display: block;">{{ $message }}</span>
                    @enderror
                </div>
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">مركز التكلفة</label>
                    <select name="cost_center_id"
                            style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 16px; transition: border-color 0.2s;"
                            onfocus="this.style.borderColor='#f59e0b'" onblur="this.style.borderColor='#e2e8f0'">
                        <option value="">لا يوجد</option>
                        @foreach($costCenters as $costCenter)
                            <option value="{{ $costCenter->id }}" {{ old('cost_center_id', $chartOfAccount->cost_center_id) == $costCenter->id ? 'selected' : '' }}>
                                {{ $costCenter->code }} - {{ $costCenter->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('cost_center_id')
                        <span style="color: #ef4444; font-size: 14px; margin-top: 5px; display: block;">{{ $message }}</span>
                    @enderror
                </div>
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">العملة *</label>
                    <select name="currency_code" required
                            style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 16px; transition: border-color 0.2s;"
                            onfocus="this.style.borderColor='#f59e0b'" onblur="this.style.borderColor='#e2e8f0'">
                        <option value="IQD" {{ old('currency_code', $chartOfAccount->currency_code) == 'IQD' ? 'selected' : '' }}>دينار عراقي (IQD)</option>
                        <option value="USD" {{ old('currency_code', $chartOfAccount->currency_code) == 'USD' ? 'selected' : '' }}>دولار أمريكي (USD)</option>
                        <option value="EUR" {{ old('currency_code', $chartOfAccount->currency_code) == 'EUR' ? 'selected' : '' }}>يورو (EUR)</option>
                    </select>
                    @error('currency_code')
                        <span style="color: #ef4444; font-size: 14px; margin-top: 5px; display: block;">{{ $message }}</span>
                    @enderror
                </div>
                
                <div style="margin-bottom: 20px;">
                    <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $chartOfAccount->is_active) ? 'checked' : '' }}
                               style="width: 18px; height: 18px; accent-color: #f59e0b;">
                        <span style="font-weight: 600; color: #4a5568;">الحساب نشط</span>
                    </label>
                    @error('is_active')
                        <span style="color: #ef4444; font-size: 14px; margin-top: 5px; display: block;">{{ $message }}</span>
                    @enderror
                </div>
                
                <!-- Current Balance Display -->
                <div style="background: #f8fafc; border: 2px solid #e2e8f0; border-radius: 8px; padding: 15px; margin-bottom: 20px;">
                    <h4 style="color: #2d3748; margin: 0 0 10px 0; font-size: 16px; font-weight: 600;">معلومات الرصيد</h4>
                    <div style="display: grid; gap: 10px;">
                        <div style="display: flex; justify-content: space-between;">
                            <span style="color: #6b7280;">الرصيد الافتتاحي:</span>
                            <span style="font-weight: 600; color: #2d3748;">{{ number_format($chartOfAccount->opening_balance, 2) }} {{ $chartOfAccount->currency_code }}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                            <span style="color: #6b7280;">الرصيد الحالي:</span>
                            <span style="font-weight: 600; color: {{ $chartOfAccount->current_balance >= 0 ? '#059669' : '#dc2626' }};">{{ number_format($chartOfAccount->current_balance, 2) }} {{ $chartOfAccount->currency_code }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Form Actions -->
        <div style="margin-top: 30px; padding-top: 20px; border-top: 2px solid #e2e8f0; display: flex; gap: 15px; justify-content: flex-end;">
            <a href="{{ route('tenant.accounting.chart-of-accounts.show', $chartOfAccount) }}" 
               style="background: #6b7280; color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-times"></i>
                إلغاء
            </a>
            <button type="submit" 
                    style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; padding: 12px 24px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-save"></i>
                حفظ التعديلات
            </button>
        </div>
    </form>
</div>

<script>
// Account categories mapping
const categoryMapping = {
    'asset': ['current_asset', 'non_current_asset'],
    'liability': ['current_liability', 'non_current_liability'],
    'equity': ['owners_equity'],
    'revenue': ['operating_revenue', 'non_operating_revenue'],
    'expense': ['operating_expense', 'non_operating_expense']
};

const categoryLabels = {
    'current_asset': 'الأصول المتداولة',
    'non_current_asset': 'الأصول غير المتداولة',
    'current_liability': 'الخصوم المتداولة',
    'non_current_liability': 'الخصوم غير المتداولة',
    'owners_equity': 'حقوق الملكية',
    'operating_revenue': 'الإيرادات التشغيلية',
    'non_operating_revenue': 'الإيرادات غير التشغيلية',
    'operating_expense': 'المصروفات التشغيلية',
    'non_operating_expense': 'المصروفات غير التشغيلية'
};

function updateCategories() {
    const accountType = document.getElementById('accountType').value;
    const categorySelect = document.getElementById('accountCategory');
    const currentCategory = '{{ old("account_category", $chartOfAccount->account_category) }}';
    
    // Clear existing options
    categorySelect.innerHTML = '<option value="">اختر فئة الحساب</option>';
    
    if (accountType && categoryMapping[accountType]) {
        categoryMapping[accountType].forEach(category => {
            const option = document.createElement('option');
            option.value = category;
            option.textContent = categoryLabels[category];
            if (category === currentCategory) {
                option.selected = true;
            }
            categorySelect.appendChild(option);
        });
    }
}

// Initialize categories on page load
document.addEventListener('DOMContentLoaded', function() {
    updateCategories();
});
</script>
@endsection
