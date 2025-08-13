@extends('layouts.tenant')

@section('title', 'إنشاء مستودع جديد')

@section('content')
<div style="padding: 20px;">
    <!-- Header -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <div>
            <h1 style="font-size: 28px; font-weight: 700; color: #1a202c; margin: 0;">
                <i class="fas fa-warehouse" style="color: #667eea; margin-left: 10px;"></i>
                إنشاء مستودع جديد
            </h1>
            <p style="color: #718096; margin: 5px 0 0 0;">أضف مستودع جديد لإدارة المخزون</p>
        </div>
        <a href="{{ route('tenant.inventory.warehouses.index') }}" 
           style="padding: 10px 20px; background: #e2e8f0; color: #4a5568; border-radius: 8px; text-decoration: none; font-weight: 600;">
            <i class="fas fa-arrow-right" style="margin-left: 5px;"></i>
            العودة للقائمة
        </a>
    </div>

    <!-- Display Errors -->
    @if ($errors->any())
        <div style="background: #fee2e2; border: 1px solid #fecaca; color: #dc2626; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            <h4 style="margin: 0 0 10px 0; font-weight: 600;">يرجى تصحيح الأخطاء التالية:</h4>
            <ul style="margin: 0; padding-right: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('error'))
        <div style="background: #fee2e2; border: 1px solid #fecaca; color: #dc2626; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            <strong>خطأ:</strong> {{ session('error') }}
        </div>
    @endif

    <!-- Simple Form -->
    <form method="POST" action="{{ route('tenant.inventory.warehouses.store') }}" id="warehouseForm">
        @csrf
        
        <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 25px;">
            <h3 style="color: #2d3748; margin-bottom: 20px; font-size: 18px; font-weight: 700; border-bottom: 2px solid #e2e8f0; padding-bottom: 10px;">
                <i class="fas fa-info-circle" style="color: #3b82f6; margin-left: 8px;"></i>
                معلومات المستودع
            </h3>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <!-- Name -->
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151;">
                        <i class="fas fa-warehouse" style="margin-left: 5px; color: #10b981;"></i>
                        اسم المستودع *
                    </label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           style="width: 100%; padding: 12px; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 14px; transition: border-color 0.3s ease;"
                           placeholder="أدخل اسم المستودع">
                    @error('name')
                        <div style="color: #f56565; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Phone -->
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151;">
                        <i class="fas fa-phone" style="margin-left: 5px; color: #8b5cf6;"></i>
                        رقم الهاتف
                    </label>
                    <input type="text" name="phone" value="{{ old('phone') }}"
                           style="width: 100%; padding: 12px; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 14px; transition: border-color 0.3s ease;"
                           placeholder="رقم الهاتف (اختياري)">
                    @error('phone')
                        <div style="color: #f56565; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div style="margin-top: 20px;">
                <!-- Description -->
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151;">
                        <i class="fas fa-align-left" style="margin-left: 5px; color: #f59e0b;"></i>
                        الوصف
                    </label>
                    <textarea name="description" rows="3"
                              style="width: 100%; padding: 12px; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 14px; resize: vertical; transition: border-color 0.3s ease;"
                              placeholder="وصف المستودع (اختياري)">{{ old('description') }}</textarea>
                    @error('description')
                        <div style="color: #f56565; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div style="display: flex; gap: 15px; justify-content: flex-end;">
            <a href="{{ route('tenant.inventory.warehouses.index') }}" 
               style="padding: 12px 24px; border: 2px solid #e2e8f0; border-radius: 8px; background: white; color: #4a5568; font-weight: 600; text-decoration: none; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-times"></i>
                إلغاء
            </a>
            <button type="submit" 
                    style="padding: 12px 24px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px;">
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
