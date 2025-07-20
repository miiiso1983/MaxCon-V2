@extends('layouts.modern')

@section('page-title', 'تعديل المستأجر')
@section('page-description', 'تعديل بيانات المستأجر: ' . ($tenant->name ?? 'غير محدد'))

@section('content')
<!-- Page Header -->
<div style="background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%); border-radius: 20px; padding: 30px; margin-bottom: 30px; color: white; position: relative; overflow: hidden;">
    <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
    <div style="position: absolute; bottom: -30px; left: -30px; width: 150px; height: 150px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>
    
    <div style="position: relative; z-index: 2;">
        <div style="display: flex; align-items: center; gap: 25px;">
            <div style="width: 100px; height: 100px; border-radius: 50%; background: rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center; color: white; font-size: 40px; font-weight: 800; backdrop-filter: blur(10px);">
                {{ substr($tenant->name ?? 'T', 0, 1) }}
            </div>
            <div>
                <h1 style="font-size: 32px; font-weight: 800; margin: 0; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                    تعديل {{ $tenant->name ?? 'المستأجر' }} ✏️
                </h1>
                <p style="font-size: 18px; margin: 5px 0 15px 0; opacity: 0.9;">
                    تحديث بيانات المؤسسة في النظام
                </p>
                
                <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-edit" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px; font-weight: 600;">تعديل البيانات</span>
                    </div>
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-cog" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px;">إعداد النظام</span>
                    </div>
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-save" style="margin-left: 8px; color: #4ade80;"></i>
                        <span style="font-size: 14px;">حفظ آمن</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="content-card">
    <form method="POST" action="{{ route('admin.tenants.update', $tenant->id ?? 1) }}">
        @csrf
        @method('PUT')
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 25px;">
            <!-- اسم المؤسسة -->
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #2d3748;">اسم المؤسسة *</label>
                <input type="text" name="name" value="{{ old('name', $tenant->name ?? '') }}" 
                       style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px;"
                       placeholder="اسم المؤسسة" required>
                @error('name')
                    <div style="color: #e53e3e; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                @enderror
            </div>

            <!-- الرمز المختصر -->
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #2d3748;">الرمز المختصر</label>
                <input type="text" name="slug" value="{{ old('slug', $tenant->slug ?? '') }}" 
                       style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px;"
                       placeholder="company-slug">
                @error('slug')
                    <div style="color: #e53e3e; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                @enderror
            </div>

            <!-- النطاق -->
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #2d3748;">النطاق</label>
                <input type="text" name="domain" value="{{ old('domain', $tenant->domain ?? '') }}" 
                       style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px;"
                       placeholder="company.com">
                @error('domain')
                    <div style="color: #e53e3e; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                @enderror
            </div>

            <!-- النطاق الفرعي -->
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #2d3748;">النطاق الفرعي</label>
                <input type="text" name="subdomain" value="{{ old('subdomain', $tenant->subdomain ?? '') }}" 
                       style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px;"
                       placeholder="company">
                @error('subdomain')
                    <div style="color: #e53e3e; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                @enderror
            </div>

            <!-- الخطة -->
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #2d3748;">الخطة *</label>
                <select name="plan" 
                        style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px;" required>
                    <option value="">اختر الخطة</option>
                    <option value="basic" {{ old('plan', $tenant->plan ?? '') == 'basic' ? 'selected' : '' }}>أساسية</option>
                    <option value="premium" {{ old('plan', $tenant->plan ?? '') == 'premium' ? 'selected' : '' }}>متقدمة</option>
                    <option value="enterprise" {{ old('plan', $tenant->plan ?? '') == 'enterprise' ? 'selected' : '' }}>مؤسسية</option>
                </select>
                @error('plan')
                    <div style="color: #e53e3e; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                @enderror
            </div>

            <!-- الحد الأقصى للمستخدمين -->
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #2d3748;">الحد الأقصى للمستخدمين *</label>
                <input type="number" name="max_users" value="{{ old('max_users', $tenant->max_users ?? 10) }}"
                       style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px;"
                       min="1" required>
                @error('max_users')
                    <div style="color: #e53e3e; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                @enderror
            </div>

            <!-- الحد الأقصى للعملاء -->
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #2d3748;">الحد الأقصى للعملاء *</label>
                <input type="number" name="max_customers" value="{{ old('max_customers', $tenant->max_customers ?? 100) }}"
                       style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px;"
                       min="1" required>
                <div style="color: #718096; font-size: 12px; margin-top: 4px;">
                    <i class="fas fa-info-circle" style="margin-left: 4px;"></i>
                    عدد العملاء الذين يمكن للمستأجر إضافتهم للنظام
                </div>
                @error('max_customers')
                    <div style="color: #e53e3e; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                @enderror
            </div>

            <!-- حد التخزين (GB) -->
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #2d3748;">حد التخزين (GB) *</label>
                <input type="number" name="storage_limit" value="{{ old('storage_limit', $tenant->storage_limit ?? 5) }}" 
                       style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px;"
                       min="1" required>
                @error('storage_limit')
                    <div style="color: #e53e3e; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                @enderror
            </div>

            <!-- الحالة -->
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #2d3748;">الحالة</label>
                <select name="is_active" 
                        style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px;">
                    <option value="1" {{ old('is_active', $tenant->is_active ?? 1) == '1' ? 'selected' : '' }}>نشط</option>
                    <option value="0" {{ old('is_active', $tenant->is_active ?? 1) == '0' ? 'selected' : '' }}>معطل</option>
                </select>
                @error('is_active')
                    <div style="color: #e53e3e; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- الوصف -->
        <div style="margin-bottom: 25px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #2d3748;">الوصف</label>
            <textarea name="description" rows="4" 
                      style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px; resize: vertical;"
                      placeholder="وصف المؤسسة...">{{ old('description', $tenant->description ?? '') }}</textarea>
            @error('description')
                <div style="color: #e53e3e; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
            @enderror
        </div>

        <!-- أزرار الحفظ -->
        <div style="display: flex; gap: 15px; justify-content: flex-end;">
            <a href="{{ route('admin.tenants.show', $tenant->id ?? 1) }}" 
               style="padding: 12px 24px; border: 1px solid #e2e8f0; border-radius: 8px; color: #4a5568; text-decoration: none; font-weight: 600;">
                إلغاء
            </a>
            <button type="submit" style="background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%); color: white; padding: 12px 24px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-save"></i>
                حفظ التغييرات
            </button>
        </div>
    </form>
</div>
@endsection
