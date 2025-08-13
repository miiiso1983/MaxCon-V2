@extends('layouts.tenant')

@section('title', 'إنشاء مستودع جديد')

@push('styles')
<style>
    .warehouse-form {
        max-width: 800px;
        margin: 0 auto;
    }

    .form-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        border: none;
        overflow: hidden;
    }

    .form-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 20px 25px;
        border-bottom: none;
    }

    .form-body {
        padding: 30px 25px;
    }

    .form-footer {
        background: #f8f9fa;
        padding: 20px 25px;
        border-top: 1px solid #e9ecef;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        font-weight: 600;
        color: #374151;
        margin-bottom: 8px;
        display: block;
    }

    .form-control {
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        padding: 12px 15px;
        font-size: 14px;
        transition: all 0.3s ease;
        width: 100%;
    }

    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        outline: none;
    }

    .btn-custom {
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }

    .btn-primary-custom {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .btn-primary-custom:hover {
        background: linear-gradient(135deg, #5a67d8 0%, #6b46c1 100%);
        transform: translateY(-1px);
        color: white;
    }

    .btn-secondary-custom {
        background: white;
        color: #6b7280;
        border: 2px solid #e5e7eb;
    }

    .btn-secondary-custom:hover {
        background: #f9fafb;
        border-color: #d1d5db;
        transform: translateY(-1px);
        color: #6b7280;
        text-decoration: none;
    }

    .icon {
        margin-left: 5px;
    }

    @media (max-width: 768px) {
        .warehouse-form {
            margin: 0 15px;
        }

        .form-body {
            padding: 20px;
        }

        .form-header {
            padding: 15px 20px;
        }

        .form-footer {
            padding: 15px 20px;
        }

        .btn-custom {
            width: 100%;
            justify-content: center;
            margin-bottom: 10px;
        }

        .grid-responsive {
            grid-template-columns: 1fr !important;
        }
    }
</style>
@endpush

@section('content')
<div style="padding: 20px;">
    <!-- Header -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; flex-wrap: wrap; gap: 15px;">
        <div>
            <h1 style="font-size: 28px; font-weight: 700; color: #1a202c; margin: 0;">
                <i class="fas fa-warehouse" style="color: #667eea; margin-left: 10px;"></i>
                إنشاء مستودع جديد
            </h1>
            <p style="color: #718096; margin: 5px 0 0 0;">أضف مستودع جديد لإدارة المخزون</p>
        </div>
        <a href="{{ route('tenant.inventory.warehouses.index') }}"
           class="btn-custom btn-secondary-custom">
            <i class="fas fa-arrow-right icon"></i>
            العودة للقائمة
        </a>
    </div>

    <!-- Display Errors -->
    @if ($errors->any())
        <div class="warehouse-form" style="margin-bottom: 20px;">
            <div style="background: #fee2e2; border: 1px solid #fecaca; color: #dc2626; padding: 15px; border-radius: 8px;">
                <h6 style="margin: 0 0 10px 0; font-weight: 600;">يرجى تصحيح الأخطاء التالية:</h6>
                <ul style="margin: 0; padding-right: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="warehouse-form" style="margin-bottom: 20px;">
            <div style="background: #fee2e2; border: 1px solid #fecaca; color: #dc2626; padding: 15px; border-radius: 8px;">
                <strong>خطأ:</strong> {{ session('error') }}
            </div>
        </div>
    @endif

    <!-- Simple Form -->
    <div class="warehouse-form">
        <form method="POST" action="{{ route('tenant.inventory.warehouses.store') }}" id="warehouseForm">
            @csrf

            <div class="form-card">
                <div class="form-header">
                    <h3 style="margin: 0; font-size: 18px; font-weight: 600;">
                        <i class="fas fa-info-circle icon"></i>
                        معلومات المستودع
                    </h3>
                </div>

                <div class="form-body">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;" class="grid-responsive">
                        <!-- Name -->
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-warehouse" style="color: #10b981; margin-left: 5px;"></i>
                                اسم المستودع *
                            </label>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                   class="form-control"
                                   placeholder="أدخل اسم المستودع">
                            @error('name')
                                <div style="color: #dc2626; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Phone -->
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-phone" style="color: #8b5cf6; margin-left: 5px;"></i>
                                رقم الهاتف
                            </label>
                            <input type="text" name="phone" value="{{ old('phone') }}"
                                   class="form-control"
                                   placeholder="رقم الهاتف (اختياري)">
                            @error('phone')
                                <div style="color: #dc2626; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-align-left" style="color: #f59e0b; margin-left: 5px;"></i>
                            الوصف
                        </label>
                        <textarea name="description" rows="3"
                                  class="form-control"
                                  placeholder="وصف المستودع (اختياري)">{{ old('description') }}</textarea>
                        @error('description')
                            <div style="color: #dc2626; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-footer">
                    <div style="display: flex; gap: 15px; justify-content: flex-end; flex-wrap: wrap;">
                        <a href="{{ route('tenant.inventory.warehouses.index') }}"
                           class="btn-custom btn-secondary-custom">
                            <i class="fas fa-times icon"></i>
                            إلغاء
                        </a>
                        <button type="submit" class="btn-custom btn-primary-custom">
                            <i class="fas fa-save icon"></i>
                            إنشاء المستودع
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
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
