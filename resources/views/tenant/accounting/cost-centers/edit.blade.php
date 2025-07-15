@extends('layouts.modern')

@section('page-title', 'تعديل مركز التكلفة: ' . $costCenter->name)
@section('page-description', 'تعديل بيانات مركز التكلفة والميزانية')

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
                            تعديل مركز التكلفة ✏️
                        </h1>
                        <p style="font-size: 18px; margin: 5px 0 0 0; opacity: 0.9;">
                            {{ $costCenter->code }} - {{ $costCenter->name }}
                        </p>
                    </div>
                </div>
            </div>
            
            <div style="display: flex; gap: 15px;">
                <a href="{{ route('tenant.inventory.accounting.cost-centers.show', $costCenter) }}" style="background: rgba(59, 130, 246, 0.2); color: white; padding: 15px 25px; border-radius: 15px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3);">
                    <i class="fas fa-eye"></i>
                    عرض المركز
                </a>
                <a href="{{ route('tenant.inventory.accounting.cost-centers.index') }}" style="background: rgba(255,255,255,0.2); color: white; padding: 15px 25px; border-radius: 15px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3);">
                    <i class="fas fa-arrow-right"></i>
                    العودة للقائمة
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Edit Form -->
<div class="content-card">
    <form method="POST" action="{{ route('tenant.inventory.accounting.cost-centers.update', $costCenter) }}">
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
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">اسم مركز التكلفة (عربي) *</label>
                    <input type="text" name="name" value="{{ old('name', $costCenter->name) }}" required
                           style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 16px; transition: border-color 0.2s;"
                           placeholder="مثال: قسم المبيعات"
                           onfocus="this.style.borderColor='#f59e0b'" onblur="this.style.borderColor='#e2e8f0'">
                    @error('name')
                        <span style="color: #ef4444; font-size: 14px; margin-top: 5px; display: block;">{{ $message }}</span>
                    @enderror
                </div>
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">اسم مركز التكلفة (إنجليزي)</label>
                    <input type="text" name="name_en" value="{{ old('name_en', $costCenter->name_en) }}"
                           style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 16px; transition: border-color 0.2s;"
                           placeholder="Example: Sales Department"
                           onfocus="this.style.borderColor='#f59e0b'" onblur="this.style.borderColor='#e2e8f0'">
                    @error('name_en')
                        <span style="color: #ef4444; font-size: 14px; margin-top: 5px; display: block;">{{ $message }}</span>
                    @enderror
                </div>
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">مركز التكلفة الأب</label>
                    <select name="parent_cost_center_id"
                            style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 16px; transition: border-color 0.2s;"
                            onfocus="this.style.borderColor='#f59e0b'" onblur="this.style.borderColor='#e2e8f0'">
                        <option value="">لا يوجد (مركز رئيسي)</option>
                        @foreach($parentCostCenters as $parent)
                            <option value="{{ $parent->id }}" {{ old('parent_cost_center_id', $costCenter->parent_cost_center_id) == $parent->id ? 'selected' : '' }}>
                                {{ $parent->code }} - {{ $parent->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('parent_cost_center_id')
                        <span style="color: #ef4444; font-size: 14px; margin-top: 5px; display: block;">{{ $message }}</span>
                    @enderror
                </div>
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">الوصف</label>
                    <textarea name="description" rows="3"
                              style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 16px; transition: border-color 0.2s; resize: vertical;"
                              placeholder="وصف مختصر لمركز التكلفة..."
                              onfocus="this.style.borderColor='#f59e0b'" onblur="this.style.borderColor='#e2e8f0'">{{ old('description', $costCenter->description) }}</textarea>
                    @error('description')
                        <span style="color: #ef4444; font-size: 14px; margin-top: 5px; display: block;">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            
            <!-- Management & Budget -->
            <div>
                <h3 style="color: #2d3748; margin-bottom: 20px; font-size: 20px; font-weight: 700; display: flex; align-items: center; gap: 10px; border-bottom: 2px solid #e2e8f0; padding-bottom: 10px;">
                    <i class="fas fa-user-tie" style="color: #6366f1;"></i>
                    الإدارة والميزانية
                </h3>
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">اسم المدير المسؤول</label>
                    <input type="text" name="manager_name" value="{{ old('manager_name', $costCenter->manager_name) }}"
                           style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 16px; transition: border-color 0.2s;"
                           placeholder="اسم المدير المسؤول عن المركز"
                           onfocus="this.style.borderColor='#f59e0b'" onblur="this.style.borderColor='#e2e8f0'">
                    @error('manager_name')
                        <span style="color: #ef4444; font-size: 14px; margin-top: 5px; display: block;">{{ $message }}</span>
                    @enderror
                </div>
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">بريد المدير الإلكتروني</label>
                    <input type="email" name="manager_email" value="{{ old('manager_email', $costCenter->manager_email) }}"
                           style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 16px; transition: border-color 0.2s;"
                           placeholder="manager@example.com"
                           onfocus="this.style.borderColor='#f59e0b'" onblur="this.style.borderColor='#e2e8f0'">
                    @error('manager_email')
                        <span style="color: #ef4444; font-size: 14px; margin-top: 5px; display: block;">{{ $message }}</span>
                    @enderror
                </div>
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">مبلغ الميزانية</label>
                    <input type="number" name="budget_amount" value="{{ old('budget_amount', $costCenter->budget_amount) }}" step="0.01" min="0"
                           style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 16px; transition: border-color 0.2s;"
                           placeholder="0.00"
                           onfocus="this.style.borderColor='#f59e0b'" onblur="this.style.borderColor='#e2e8f0'">
                    @error('budget_amount')
                        <span style="color: #ef4444; font-size: 14px; margin-top: 5px; display: block;">{{ $message }}</span>
                    @enderror
                </div>
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">العملة *</label>
                    <select name="currency_code" required
                            style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 16px; transition: border-color 0.2s;"
                            onfocus="this.style.borderColor='#f59e0b'" onblur="this.style.borderColor='#e2e8f0'">
                        <option value="IQD" {{ old('currency_code', $costCenter->currency_code) == 'IQD' ? 'selected' : '' }}>دينار عراقي (IQD)</option>
                        <option value="USD" {{ old('currency_code', $costCenter->currency_code) == 'USD' ? 'selected' : '' }}>دولار أمريكي (USD)</option>
                        <option value="EUR" {{ old('currency_code', $costCenter->currency_code) == 'EUR' ? 'selected' : '' }}>يورو (EUR)</option>
                    </select>
                    @error('currency_code')
                        <span style="color: #ef4444; font-size: 14px; margin-top: 5px; display: block;">{{ $message }}</span>
                    @enderror
                </div>
                
                <div style="margin-bottom: 20px;">
                    <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $costCenter->is_active) ? 'checked' : '' }}
                               style="width: 18px; height: 18px; accent-color: #f59e0b;">
                        <span style="font-weight: 600; color: #4a5568;">مركز التكلفة نشط</span>
                    </label>
                    @error('is_active')
                        <span style="color: #ef4444; font-size: 14px; margin-top: 5px; display: block;">{{ $message }}</span>
                    @enderror
                </div>
                
                <!-- Current Budget Status -->
                <div style="background: #f8fafc; border: 2px solid #e2e8f0; border-radius: 8px; padding: 15px; margin-bottom: 20px;">
                    <h4 style="color: #2d3748; margin: 0 0 10px 0; font-size: 16px; font-weight: 600;">الوضع الحالي للميزانية</h4>
                    <div style="display: grid; gap: 10px;">
                        <div style="display: flex; justify-content: space-between;">
                            <span style="color: #6b7280;">الميزانية المخصصة:</span>
                            <span style="font-weight: 600; color: #2d3748;">{{ number_format($costCenter->budget_amount, 2) }} {{ $costCenter->currency_code }}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                            <span style="color: #6b7280;">المبلغ المصروف:</span>
                            <span style="font-weight: 600; color: #f59e0b;">{{ number_format($costCenter->actual_amount, 2) }} {{ $costCenter->currency_code }}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                            <span style="color: #6b7280;">المتبقي:</span>
                            <span style="font-weight: 600; color: {{ ($costCenter->budget_amount - $costCenter->actual_amount) >= 0 ? '#059669' : '#dc2626' }};">
                                {{ number_format($costCenter->budget_amount - $costCenter->actual_amount, 2) }} {{ $costCenter->currency_code }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Form Actions -->
        <div style="margin-top: 30px; padding-top: 20px; border-top: 2px solid #e2e8f0; display: flex; gap: 15px; justify-content: flex-end;">
            <a href="{{ route('tenant.inventory.accounting.cost-centers.show', $costCenter) }}" 
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
@endsection
