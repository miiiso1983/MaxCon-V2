@extends('layouts.modern')

@section('page-title', 'إنشاء مستودع جديد')
@section('page-description', 'أضف مستودع جديد لإدارة المخزون')

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
                        <i class="fas fa-plus" style="font-size: 32px;"></i>
                    </div>
                    <div>
                        <h1 style="font-size: 32px; font-weight: 800; margin: 0; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                            إنشاء مستودع جديد ✨
                        </h1>
                        <p style="font-size: 18px; margin: 5px 0 0 0; opacity: 0.9;">
                            أضف مستودع جديد لإدارة المخزون بكفاءة
                        </p>
                    </div>
                </div>
            </div>

            <div style="display: flex; gap: 15px;">
                <a href="{{ route('tenant.inventory.warehouses.index') }}" style="background: rgba(255,255,255,0.2); color: white; padding: 15px 25px; border-radius: 15px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3); transition: all 0.3s ease;"
                   onmouseover="this.style.background='rgba(255,255,255,0.3)'"
                   onmouseout="this.style.background='rgba(255,255,255,0.2)'">
                    <i class="fas fa-arrow-right"></i>
                    العودة للقائمة
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Display Errors -->
@if ($errors->any())
    <div class="content-card" style="margin-bottom: 25px; background: #fee2e2; border: 1px solid #fecaca; color: #dc2626;">
        <h6 style="margin: 0 0 15px 0; font-weight: 600; font-size: 16px;">
            <i class="fas fa-exclamation-triangle" style="margin-left: 8px;"></i>
            يرجى تصحيح الأخطاء التالية:
        </h6>
        <ul style="margin: 0; padding-right: 25px;">
            @foreach ($errors->all() as $error)
                <li style="margin-bottom: 5px;">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if (session('error'))
    <div class="content-card" style="margin-bottom: 25px; background: #fee2e2; border: 1px solid #fecaca; color: #dc2626;">
        <strong>
            <i class="fas fa-times-circle" style="margin-left: 8px;"></i>
            خطأ:
        </strong> {{ session('error') }}
    </div>
@endif

<!-- Form -->
<div class="content-card">
    <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
        <i class="fas fa-info-circle" style="color: #667eea; margin-left: 10px;"></i>
        معلومات المستودع
    </h3>

    <form method="POST" action="{{ route('tenant.inventory.warehouses.store') }}" id="warehouseForm">
        @csrf

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-bottom: 25px;">
            <!-- Name -->
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">
                    <i class="fas fa-warehouse" style="color: #10b981; margin-left: 5px;"></i>
                    اسم المستودع *
                </label>
                <input type="text" name="name" value="{{ old('name') }}" required
                       style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; transition: border-color 0.3s ease;"
                       placeholder="أدخل اسم المستودع">
                @error('name')
                    <div style="color: #f56565; font-size: 14px; margin-top: 5px;">
                        <i class="fas fa-exclamation-circle" style="margin-left: 5px;"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Phone -->
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">
                    <i class="fas fa-phone" style="color: #8b5cf6; margin-left: 5px;"></i>
                    رقم الهاتف
                </label>
                <input type="text" name="phone" value="{{ old('phone') }}"
                       style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; transition: border-color 0.3s ease;"
                       placeholder="رقم الهاتف (اختياري)">
                @error('phone')
                    <div style="color: #f56565; font-size: 14px; margin-top: 5px;">
                        <i class="fas fa-exclamation-circle" style="margin-left: 5px;"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>

        <!-- Description -->
        <div style="margin-bottom: 25px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">
                <i class="fas fa-align-left" style="color: #f59e0b; margin-left: 5px;"></i>
                الوصف
            </label>
            <textarea name="description" rows="4"
                      style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; resize: vertical; transition: border-color 0.3s ease;"
                      placeholder="وصف المستودع (اختياري)">{{ old('description') }}</textarea>
            @error('description')
                <div style="color: #f56565; font-size: 14px; margin-top: 5px;">
                    <i class="fas fa-exclamation-circle" style="margin-left: 5px;"></i>
                    {{ $message }}
                </div>
            @enderror
        </div>

        <!-- Form Actions -->
        <div style="display: flex; gap: 15px; justify-content: flex-end; align-items: center; padding-top: 20px; border-top: 1px solid #e2e8f0;">
            <div style="color: #6b7280; font-size: 14px; margin-left: auto;">
                <i class="fas fa-info-circle" style="margin-left: 5px;"></i>
                الحقول المميزة بـ * مطلوبة
            </div>

            <a href="{{ route('tenant.inventory.warehouses.index') }}"
               style="background: #6b7280; color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 8px; transition: all 0.3s ease;"
               onmouseover="this.style.background='#4b5563'"
               onmouseout="this.style.background='#6b7280'">
                <i class="fas fa-times"></i>
                إلغاء
            </a>

            <button type="submit"
                    style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 12px 24px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px; transition: all 0.3s ease;"
                    onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 12px rgba(102, 126, 234, 0.3)'"
                    onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                <i class="fas fa-save"></i>
                إنشاء المستودع
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
// Wait for DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('Simple warehouse form script loaded');
    
    // Form validation and submission
    const warehouseForm = document.getElementById('warehouseForm');
    if (warehouseForm) {
        warehouseForm.addEventListener('submit', function(e) {
            console.log('Form submission attempted');
            
            const nameInput = document.querySelector('input[name="name"]');
            
            if (!nameInput) {
                console.error('Name input not found');
                e.preventDefault();
                alert('خطأ في النموذج. يرجى إعادة تحميل الصفحة.');
                return false;
            }
            
            const name = nameInput.value.trim();
            
            console.log('Form data:', { name });
            
            // Check CSRF token
            const csrfToken = document.querySelector('input[name="_token"]');
            if (!csrfToken || !csrfToken.value) {
                e.preventDefault();
                alert('خطأ في الأمان. يرجى إعادة تحميل الصفحة.');
                console.error('CSRF token missing');
                return false;
            }
            
            if (!name) {
                e.preventDefault();
                alert('يرجى إدخال اسم المستودع');
                nameInput.focus();
                return false;
            }
            
            // Show loading state
            const submitBtn = document.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الحفظ...';
            }
            
            // Log form action for debugging
            console.log('Form action:', warehouseForm.action);
            console.log('Form method:', warehouseForm.method);
            console.log('CSRF token:', csrfToken.value);
            
            console.log('Form validation passed, submitting...');
            return true;
        });
    } else {
        console.error('Warehouse form not found');
    }
});
</script>
@endpush
@endsection
