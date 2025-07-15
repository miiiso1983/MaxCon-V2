@extends('layouts.modern')

@section('page-title', 'إضافة مركز تكلفة جديد')
@section('page-description', 'إضافة مركز تكلفة جديد لتتبع التكاليف والميزانيات')

@section('content')
<!-- Page Header -->
<div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius: 20px; padding: 30px; margin-bottom: 30px; color: white; position: relative; overflow: hidden;">
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
                            إضافة مركز تكلفة ➕
                        </h1>
                        <p style="font-size: 18px; margin: 5px 0 0 0; opacity: 0.9;">
                            إضافة مركز تكلفة جديد لتتبع التكاليف والميزانيات
                        </p>
                    </div>
                </div>
            </div>
            
            <div style="display: flex; gap: 15px;">
                <a href="{{ route('tenant.accounting.cost-centers.index') }}" style="background: rgba(255,255,255,0.2); color: white; padding: 15px 25px; border-radius: 15px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3);">
                    <i class="fas fa-arrow-right"></i>
                    العودة لمراكز التكلفة
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Create Form -->
<div class="content-card">
    <form method="POST" action="{{ route('tenant.accounting.cost-centers.store') }}">
        @csrf
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
            <!-- Basic Information -->
            <div>
                <h3 style="color: #2d3748; margin-bottom: 20px; font-size: 20px; font-weight: 700; display: flex; align-items: center; gap: 10px; border-bottom: 2px solid #e2e8f0; padding-bottom: 10px;">
                    <i class="fas fa-info-circle" style="color: #10b981;"></i>
                    المعلومات الأساسية
                </h3>
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">اسم مركز التكلفة (عربي) *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 16px; transition: border-color 0.2s;"
                           placeholder="مثال: قسم المبيعات"
                           onfocus="this.style.borderColor='#10b981'" onblur="this.style.borderColor='#e2e8f0'">
                    @error('name')
                        <span style="color: #ef4444; font-size: 14px; margin-top: 5px; display: block;">{{ $message }}</span>
                    @enderror
                </div>
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">اسم مركز التكلفة (إنجليزي)</label>
                    <input type="text" name="name_en" value="{{ old('name_en') }}"
                           style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 16px; transition: border-color 0.2s;"
                           placeholder="Example: Sales Department"
                           onfocus="this.style.borderColor='#10b981'" onblur="this.style.borderColor='#e2e8f0'">
                    @error('name_en')
                        <span style="color: #ef4444; font-size: 14px; margin-top: 5px; display: block;">{{ $message }}</span>
                    @enderror
                </div>
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">مركز التكلفة الأب</label>
                    <select name="parent_cost_center_id"
                            style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 16px; transition: border-color 0.2s;"
                            onfocus="this.style.borderColor='#10b981'" onblur="this.style.borderColor='#e2e8f0'">
                        <option value="">لا يوجد (مركز رئيسي)</option>
                        @foreach($parentCostCenters as $costCenter)
                            <option value="{{ $costCenter->id }}" {{ old('parent_cost_center_id') == $costCenter->id ? 'selected' : '' }}>
                                {{ $costCenter->code }} - {{ $costCenter->name }}
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
                              onfocus="this.style.borderColor='#10b981'" onblur="this.style.borderColor='#e2e8f0'">{{ old('description') }}</textarea>
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
                    <input type="text" name="manager_name" value="{{ old('manager_name') }}"
                           style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 16px; transition: border-color 0.2s;"
                           placeholder="اسم المدير المسؤول عن المركز"
                           onfocus="this.style.borderColor='#10b981'" onblur="this.style.borderColor='#e2e8f0'">
                    @error('manager_name')
                        <span style="color: #ef4444; font-size: 14px; margin-top: 5px; display: block;">{{ $message }}</span>
                    @enderror
                </div>
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">بريد المدير الإلكتروني</label>
                    <input type="email" name="manager_email" value="{{ old('manager_email') }}"
                           style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 16px; transition: border-color 0.2s;"
                           placeholder="manager@example.com"
                           onfocus="this.style.borderColor='#10b981'" onblur="this.style.borderColor='#e2e8f0'">
                    @error('manager_email')
                        <span style="color: #ef4444; font-size: 14px; margin-top: 5px; display: block;">{{ $message }}</span>
                    @enderror
                </div>
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">مبلغ الميزانية</label>
                    <input type="number" name="budget_amount" value="{{ old('budget_amount', 0) }}" step="0.01" min="0"
                           style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 16px; transition: border-color 0.2s;"
                           placeholder="0.00"
                           onfocus="this.style.borderColor='#10b981'" onblur="this.style.borderColor='#e2e8f0'">
                    @error('budget_amount')
                        <span style="color: #ef4444; font-size: 14px; margin-top: 5px; display: block;">{{ $message }}</span>
                    @enderror
                </div>
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">العملة *</label>
                    <select name="currency_code" required
                            style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 16px; transition: border-color 0.2s;"
                            onfocus="this.style.borderColor='#10b981'" onblur="this.style.borderColor='#e2e8f0'">
                        <option value="IQD" {{ old('currency_code', 'IQD') == 'IQD' ? 'selected' : '' }}>دينار عراقي (IQD)</option>
                        <option value="USD" {{ old('currency_code') == 'USD' ? 'selected' : '' }}>دولار أمريكي (USD)</option>
                        <option value="EUR" {{ old('currency_code') == 'EUR' ? 'selected' : '' }}>يورو (EUR)</option>
                    </select>
                    @error('currency_code')
                        <span style="color: #ef4444; font-size: 14px; margin-top: 5px; display: block;">{{ $message }}</span>
                    @enderror
                </div>
                
                <div style="margin-bottom: 20px;">
                    <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                               style="width: 18px; height: 18px; accent-color: #10b981;">
                        <span style="font-weight: 600; color: #4a5568;">مركز التكلفة نشط</span>
                    </label>
                    @error('is_active')
                        <span style="color: #ef4444; font-size: 14px; margin-top: 5px; display: block;">{{ $message }}</span>
                    @enderror
                </div>
                
                <!-- Budget Guidelines -->
                <div style="background: #f0f9ff; border: 2px solid #0ea5e9; border-radius: 8px; padding: 15px; margin-bottom: 20px;">
                    <h4 style="color: #0c4a6e; margin: 0 0 10px 0; font-size: 16px; font-weight: 600; display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-lightbulb"></i>
                        نصائح الميزانية
                    </h4>
                    <ul style="color: #075985; margin: 0; padding-right: 20px; font-size: 14px; line-height: 1.6;">
                        <li>حدد ميزانية واقعية بناءً على التوقعات</li>
                        <li>راجع الميزانية دورياً وقم بتحديثها</li>
                        <li>تابع الانحرافات بين المخطط والفعلي</li>
                        <li>استخدم التقارير لتحليل الأداء المالي</li>
                    </ul>
                </div>
            </div>
        </div>
        
        <!-- Form Actions -->
        <div style="margin-top: 30px; padding-top: 20px; border-top: 2px solid #e2e8f0; display: flex; gap: 15px; justify-content: flex-end;">
            <a href="{{ route('tenant.accounting.cost-centers.index') }}" 
               style="background: #6b7280; color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-times"></i>
                إلغاء
            </a>
            <button type="submit" 
                    style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 12px 24px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-save"></i>
                حفظ مركز التكلفة
            </button>
        </div>
    </form>
</div>

<!-- Cost Center Hierarchy Preview -->
@if($parentCostCenters->count() > 0)
<div class="content-card" style="margin-top: 30px;">
    <h3 style="color: #2d3748; margin-bottom: 20px; font-size: 20px; font-weight: 700; display: flex; align-items: center; gap: 10px; border-bottom: 2px solid #e2e8f0; padding-bottom: 10px;">
        <i class="fas fa-sitemap" style="color: #8b5cf6;"></i>
        الهيكل الحالي لمراكز التكلفة
    </h3>
    
    <div style="background: #f8fafc; border-radius: 8px; padding: 20px;">
        @foreach($parentCostCenters->where('parent_cost_center_id', null) as $rootCenter)
            <div style="margin-bottom: 15px;">
                <div style="display: flex; align-items: center; gap: 10px; padding: 10px; background: white; border-radius: 6px; border-right: 4px solid #8b5cf6;">
                    <i class="fas fa-building" style="color: #8b5cf6;"></i>
                    <span style="font-weight: 600; color: #2d3748;">{{ $rootCenter->code }} - {{ $rootCenter->name }}</span>
                </div>
                
                @foreach($parentCostCenters->where('parent_cost_center_id', $rootCenter->id) as $childCenter)
                    <div style="margin: 8px 0 8px 30px;">
                        <div style="display: flex; align-items: center; gap: 10px; padding: 8px; background: #f1f5f9; border-radius: 6px; border-right: 3px solid #64748b;">
                            <i class="fas fa-arrow-turn-down" style="color: #64748b; font-size: 12px;"></i>
                            <span style="font-weight: 500; color: #475569;">{{ $childCenter->code }} - {{ $childCenter->name }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>
</div>
@endif
@endsection
