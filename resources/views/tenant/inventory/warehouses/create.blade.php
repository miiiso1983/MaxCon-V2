@extends('layouts.modern')

@section('page-title', 'إنشاء مستودع جديد')
@section('page-description', 'إضافة مستودع جديد للنظام')

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
                            مستودع جديد 🏭
                        </h1>
                        <p style="font-size: 18px; margin: 5px 0 0 0; opacity: 0.9;">
                            إضافة مستودع جديد مع تحديد المواقع والإعدادات
                        </p>
                    </div>
                </div>
            </div>
            
            <div style="display: flex; gap: 15px;">
                <a href="{{ route('tenant.inventory.warehouses.index') }}" style="background: rgba(255,255,255,0.2); color: white; padding: 15px 25px; border-radius: 15px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3);">
                    <i class="fas fa-arrow-right"></i>
                    العودة للقائمة
                </a>
            </div>
        </div>
    </div>
</div>

<form method="POST" action="{{ route('tenant.inventory.warehouses.store') }}" id="warehouseForm">
    @csrf
    
    <!-- Basic Information -->
    <div class="content-card" style="margin-bottom: 25px;">
        <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-info-circle" style="color: #667eea; margin-left: 10px;"></i>
            المعلومات الأساسية
        </h3>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">اسم المستودع *</label>
                <input type="text" name="name" value="{{ old('name') }}" required style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;" placeholder="مثال: المستودع الرئيسي">
                @error('name')
                    <div style="color: #f56565; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">نوع المستودع *</label>
                <select name="type" required style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;">
                    <option value="">اختر نوع المستودع</option>
                    <option value="main" {{ old('type') === 'main' ? 'selected' : '' }}>مستودع رئيسي</option>
                    <option value="branch" {{ old('type') === 'branch' ? 'selected' : '' }}>مستودع فرع</option>
                    <option value="storage" {{ old('type') === 'storage' ? 'selected' : '' }}>مخزن</option>
                    <option value="pharmacy" {{ old('type') === 'pharmacy' ? 'selected' : '' }}>صيدلية</option>
                </select>
                @error('type')
                    <div style="color: #f56565; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">الموقع</label>
                <input type="text" name="location" value="{{ old('location') }}" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;" placeholder="مثال: بغداد - المنطقة الصناعية">
                @error('location')
                    <div style="color: #f56565; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">مدير المستودع</label>
                <select name="manager_id" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;">
                    <option value="">اختر مدير المستودع</option>
                    @foreach($managers as $manager)
                        <option value="{{ $manager->id }}" {{ old('manager_id') == $manager->id ? 'selected' : '' }}>
                            {{ $manager->name }}
                        </option>
                    @endforeach
                </select>
                @error('manager_id')
                    <div style="color: #f56565; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
        </div>
        
        <div style="margin-top: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">وصف المستودع</label>
            <textarea name="description" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; height: 80px;" placeholder="وصف مختصر عن المستودع ووظيفته...">{{ old('description') }}</textarea>
            @error('description')
                <div style="color: #f56565; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <!-- Contact Information -->
    <div class="content-card" style="margin-bottom: 25px;">
        <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-address-book" style="color: #667eea; margin-left: 10px;"></i>
            معلومات الاتصال
        </h3>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">رقم الهاتف</label>
                <input type="tel" name="phone" value="{{ old('phone') }}" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;" placeholder="07901234567">
                @error('phone')
                    <div style="color: #f56565; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">البريد الإلكتروني</label>
                <input type="email" name="email" value="{{ old('email') }}" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;" placeholder="warehouse@company.com">
                @error('email')
                    <div style="color: #f56565; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
        </div>
        
        <div style="margin-top: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">العنوان الكامل</label>
            <textarea name="address" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; height: 80px;" placeholder="العنوان التفصيلي للمستودع...">{{ old('address') }}</textarea>
            @error('address')
                <div style="color: #f56565; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <!-- Capacity Settings -->
    <div class="content-card" style="margin-bottom: 25px;">
        <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-cube" style="color: #667eea; margin-left: 10px;"></i>
            إعدادات السعة
        </h3>
        
        <div style="display: grid; grid-template-columns: 1fr; gap: 20px;">
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">السعة الإجمالية (متر مكعب)</label>
                <input type="number" name="total_capacity" value="{{ old('total_capacity') }}" min="0" step="0.01" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;" placeholder="1000">
                <div style="font-size: 12px; color: #6b7280; margin-top: 5px;">اتركه فارغاً إذا لم تكن تريد تتبع السعة</div>
                @error('total_capacity')
                    <div style="color: #f56565; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>

    <!-- Advanced Settings -->
    <div class="content-card" style="margin-bottom: 25px;">
        <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-cogs" style="color: #667eea; margin-left: 10px;"></i>
            الإعدادات المتقدمة
        </h3>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <!-- Temperature Control -->
            <div style="padding: 15px; background: #f8fafc; border-radius: 8px; border: 1px solid #e2e8f0;">
                <div style="display: flex; align-items: center; margin-bottom: 10px;">
                    <input type="checkbox" id="temperature_controlled" name="settings[temperature_controlled]" value="1" {{ old('settings.temperature_controlled') ? 'checked' : '' }} style="width: 18px; height: 18px; margin-left: 8px;">
                    <label for="temperature_controlled" style="font-weight: 600; color: #4a5568; cursor: pointer;">
                        <i class="fas fa-thermometer-half" style="color: #ef4444; margin-left: 5px;"></i>
                        التحكم في درجة الحرارة
                    </label>
                </div>
                
                <div id="temperature_settings" style="display: none; margin-top: 10px;">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                        <div>
                            <label style="display: block; margin-bottom: 5px; font-size: 12px; color: #6b7280;">الحد الأدنى (°C)</label>
                            <input type="number" name="settings[min_temperature]" value="{{ old('settings.min_temperature', 15) }}" style="width: 100%; padding: 8px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px;">
                        </div>
                        <div>
                            <label style="display: block; margin-bottom: 5px; font-size: 12px; color: #6b7280;">الحد الأقصى (°C)</label>
                            <input type="number" name="settings[max_temperature]" value="{{ old('settings.max_temperature', 25) }}" style="width: 100%; padding: 8px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px;">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Humidity Control -->
            <div style="padding: 15px; background: #f8fafc; border-radius: 8px; border: 1px solid #e2e8f0;">
                <div style="display: flex; align-items: center; margin-bottom: 10px;">
                    <input type="checkbox" id="humidity_controlled" name="settings[humidity_controlled]" value="1" {{ old('settings.humidity_controlled') ? 'checked' : '' }} style="width: 18px; height: 18px; margin-left: 8px;">
                    <label for="humidity_controlled" style="font-weight: 600; color: #4a5568; cursor: pointer;">
                        <i class="fas fa-tint" style="color: #3b82f6; margin-left: 5px;"></i>
                        التحكم في الرطوبة
                    </label>
                </div>
                
                <div id="humidity_settings" style="display: none; margin-top: 10px;">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                        <div>
                            <label style="display: block; margin-bottom: 5px; font-size: 12px; color: #6b7280;">الحد الأدنى (%)</label>
                            <input type="number" name="settings[min_humidity]" value="{{ old('settings.min_humidity', 40) }}" style="width: 100%; padding: 8px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px;">
                        </div>
                        <div>
                            <label style="display: block; margin-bottom: 5px; font-size: 12px; color: #6b7280;">الحد الأقصى (%)</label>
                            <input type="number" name="settings[max_humidity]" value="{{ old('settings.max_humidity', 60) }}" style="width: 100%; padding: 8px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px;">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Security Level -->
            <div style="padding: 15px; background: #f8fafc; border-radius: 8px; border: 1px solid #e2e8f0;">
                <label style="display: block; margin-bottom: 10px; font-weight: 600; color: #4a5568;">
                    <i class="fas fa-shield-alt" style="color: #10b981; margin-left: 5px;"></i>
                    مستوى الأمان
                </label>
                <select name="settings[security_level]" style="width: 100%; padding: 8px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px;">
                    <option value="low" {{ old('settings.security_level') === 'low' ? 'selected' : '' }}>منخفض</option>
                    <option value="medium" {{ old('settings.security_level', 'medium') === 'medium' ? 'selected' : '' }}>متوسط</option>
                    <option value="high" {{ old('settings.security_level') === 'high' ? 'selected' : '' }}>عالي</option>
                </select>
            </div>

            <!-- Pharmaceutical Grade -->
            <div style="padding: 15px; background: #f8fafc; border-radius: 8px; border: 1px solid #e2e8f0;">
                <div style="display: flex; align-items: center;">
                    <input type="checkbox" id="pharmaceutical_grade" name="settings[pharmaceutical_grade]" value="1" {{ old('settings.pharmaceutical_grade') ? 'checked' : '' }} style="width: 18px; height: 18px; margin-left: 8px;">
                    <label for="pharmaceutical_grade" style="font-weight: 600; color: #4a5568; cursor: pointer;">
                        <i class="fas fa-pills" style="color: #8b5cf6; margin-left: 5px;"></i>
                        درجة صيدلانية
                    </label>
                </div>
                <div style="font-size: 12px; color: #6b7280; margin-top: 5px;">للمنتجات الطبية والصيدلانية</div>
            </div>
        </div>
    </div>

    <!-- Form Actions -->
    <div style="display: flex; gap: 15px; justify-content: flex-end;">
        <a href="{{ route('tenant.inventory.warehouses.index') }}" style="padding: 12px 24px; border: 2px solid #e2e8f0; border-radius: 8px; background: white; color: #4a5568; font-weight: 600; text-decoration: none; display: flex; align-items: center; gap: 8px;">
            <i class="fas fa-times"></i>
            إلغاء
        </a>
        <button type="submit" style="padding: 12px 24px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px;">
            <i class="fas fa-save"></i>
            إنشاء المستودع
        </button>
    </div>
</form>

@push('scripts')
<script>
// Handle temperature control toggle
document.getElementById('temperature_controlled').addEventListener('change', function() {
    const settings = document.getElementById('temperature_settings');
    settings.style.display = this.checked ? 'block' : 'none';
});

// Handle humidity control toggle
document.getElementById('humidity_controlled').addEventListener('change', function() {
    const settings = document.getElementById('humidity_settings');
    settings.style.display = this.checked ? 'block' : 'none';
});

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    // Show temperature settings if checked
    if (document.getElementById('temperature_controlled').checked) {
        document.getElementById('temperature_settings').style.display = 'block';
    }
    
    // Show humidity settings if checked
    if (document.getElementById('humidity_controlled').checked) {
        document.getElementById('humidity_settings').style.display = 'block';
    }
});

// Form validation
document.getElementById('warehouseForm').addEventListener('submit', function(e) {
    const name = document.querySelector('input[name="name"]').value.trim();
    const type = document.querySelector('select[name="type"]').value;
    
    if (!name) {
        e.preventDefault();
        alert('يرجى إدخال اسم المستودع');
        return false;
    }
    
    if (!type) {
        e.preventDefault();
        alert('يرجى اختيار نوع المستودع');
        return false;
    }
});
</script>
@endpush
@endsection
