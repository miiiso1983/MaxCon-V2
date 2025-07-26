@extends('layouts.modern')

@section('page-title', 'إنشاء عقد جديد')
@section('page-description', 'إنشاء عقد جديد مع المورد')

@section('content')
<!-- Page Header -->
<div style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); border-radius: 20px; padding: 30px; margin-bottom: 30px; color: white; position: relative; overflow: hidden;">
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
                            إنشاء عقد جديد 📝
                        </h1>
                        <p style="font-size: 18px; margin: 5px 0 0 0; opacity: 0.9;">
                            إنشاء عقد جديد مع المورد
                        </p>
                    </div>
                </div>
            </div>
            
            <div>
                <a href="{{ route('tenant.purchasing.contracts.index') }}" style="background: rgba(255,255,255,0.2); color: white; padding: 15px 25px; border-radius: 15px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3);">
                    <i class="fas fa-arrow-right"></i>
                    العودة للقائمة
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Contract Form -->
<div class="content-card">
    <form method="POST" action="{{ route('tenant.purchasing.contracts.store') }}">
        @csrf
        
        <!-- Basic Information -->
        <div style="margin-bottom: 30px;">
            <h3 style="color: #2d3748; font-size: 20px; font-weight: 700; margin-bottom: 20px; display: flex; align-items: center;">
                <i class="fas fa-info-circle" style="color: #8b5cf6; margin-left: 10px;"></i>
                المعلومات الأساسية
            </h3>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151;">رقم العقد *</label>
                    <input type="text" name="contract_number" value="{{ old('contract_number') }}" required
                           style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px;"
                           placeholder="مثال: CON-2024-001">
                    @error('contract_number')
                        <div style="color: #ef4444; font-size: 12px; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>
                
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151;">المورد *</label>
                    <select name="supplier_id" required style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px;">
                        <option value="">اختر المورد</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                {{ $supplier->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('supplier_id')
                        <div style="color: #ef4444; font-size: 12px; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>
                
                <div style="grid-column: 1 / -1;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151;">عنوان العقد *</label>
                    <input type="text" name="title" value="{{ old('title') }}" required
                           style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px;"
                           placeholder="عنوان العقد">
                    @error('title')
                        <div style="color: #ef4444; font-size: 12px; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>
                
                <div style="grid-column: 1 / -1;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151;">وصف العقد</label>
                    <textarea name="description" rows="3" 
                              style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; resize: vertical;"
                              placeholder="وصف مختصر للعقد">{{ old('description') }}</textarea>
                    @error('description')
                        <div style="color: #ef4444; font-size: 12px; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Contract Details -->
        <div style="margin-bottom: 30px;">
            <h3 style="color: #2d3748; font-size: 20px; font-weight: 700; margin-bottom: 20px; display: flex; align-items: center;">
                <i class="fas fa-file-contract" style="color: #8b5cf6; margin-left: 10px;"></i>
                تفاصيل العقد
            </h3>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151;">نوع العقد *</label>
                    <select name="type" required style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px;">
                        <option value="">اختر نوع العقد</option>
                        <option value="supply" {{ old('type') == 'supply' ? 'selected' : '' }}>عقد توريد</option>
                        <option value="service" {{ old('type') == 'service' ? 'selected' : '' }}>عقد خدمة</option>
                        <option value="maintenance" {{ old('type') == 'maintenance' ? 'selected' : '' }}>عقد صيانة</option>
                        <option value="consulting" {{ old('type') == 'consulting' ? 'selected' : '' }}>عقد استشاري</option>
                        <option value="framework" {{ old('type') == 'framework' ? 'selected' : '' }}>عقد إطاري</option>
                    </select>
                    @error('type')
                        <div style="color: #ef4444; font-size: 12px; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>
                
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151;">العملة *</label>
                    <select name="currency" required style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px;">
                        <option value="IQD" {{ old('currency') == 'IQD' ? 'selected' : '' }}>دينار عراقي (IQD)</option>
                        <option value="USD" {{ old('currency') == 'USD' ? 'selected' : '' }}>دولار أمريكي (USD)</option>
                        <option value="EUR" {{ old('currency') == 'EUR' ? 'selected' : '' }}>يورو (EUR)</option>
                    </select>
                    @error('currency')
                        <div style="color: #ef4444; font-size: 12px; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>
                
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151;">تاريخ البداية *</label>
                    <input type="date" name="start_date" value="{{ old('start_date') }}" required
                           style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px;">
                    @error('start_date')
                        <div style="color: #ef4444; font-size: 12px; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>
                
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151;">تاريخ الانتهاء *</label>
                    <input type="date" name="end_date" value="{{ old('end_date') }}" required
                           style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px;">
                    @error('end_date')
                        <div style="color: #ef4444; font-size: 12px; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>
                
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151;">تاريخ التوقيع</label>
                    <input type="date" name="signed_date" value="{{ old('signed_date') }}"
                           style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px;">
                    @error('signed_date')
                        <div style="color: #ef4444; font-size: 12px; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Financial Terms -->
        <div style="margin-bottom: 30px;">
            <h3 style="color: #2d3748; font-size: 20px; font-weight: 700; margin-bottom: 20px; display: flex; align-items: center;">
                <i class="fas fa-dollar-sign" style="color: #8b5cf6; margin-left: 10px;"></i>
                الشروط المالية
            </h3>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151;">قيمة العقد *</label>
                    <input type="number" name="contract_value" value="{{ old('contract_value') }}" required step="0.01" min="0"
                           style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px;"
                           placeholder="0.00">
                    @error('contract_value')
                        <div style="color: #ef4444; font-size: 12px; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>
                
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151;">الحد الأدنى للطلب</label>
                    <input type="number" name="minimum_order_value" value="{{ old('minimum_order_value') }}" step="0.01" min="0"
                           style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px;"
                           placeholder="0.00">
                    @error('minimum_order_value')
                        <div style="color: #ef4444; font-size: 12px; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>
                
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151;">الحد الأقصى للطلب</label>
                    <input type="number" name="maximum_order_value" value="{{ old('maximum_order_value') }}" step="0.01" min="0"
                           style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px;"
                           placeholder="0.00">
                    @error('maximum_order_value')
                        <div style="color: #ef4444; font-size: 12px; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div style="display: flex; gap: 15px; justify-content: center; padding-top: 20px; border-top: 1px solid #e5e7eb;">
            <button type="submit" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); color: white; padding: 12px 30px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-save"></i>
                حفظ العقد
            </button>
            
            <a href="{{ route('tenant.purchasing.contracts.index') }}" style="background: #6b7280; color: white; padding: 12px 30px; border-radius: 8px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-times"></i>
                إلغاء
            </a>
        </div>
    </form>
</div>
@endsection
