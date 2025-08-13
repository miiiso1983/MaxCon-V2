@extends('layouts.tenant')

@section('title', 'إنشاء مستودع جديد')

@push('styles')
<style>
    /* إعادة تعيين التخطيط الأساسي */
    .main-content {
        width: 100% !important;
        max-width: none !important;
        margin: 0 !important;
        padding: 20px !important;
    }

    /* تصميم الحاوية الرئيسية */
    .warehouse-container {
        width: 100%;
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }

    /* تصميم الهيدر */
    .page-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 30px;
        border-radius: 15px;
        margin-bottom: 30px;
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
    }

    .page-title {
        font-size: 32px;
        font-weight: 700;
        margin: 0 0 10px 0;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .page-subtitle {
        font-size: 16px;
        opacity: 0.9;
        margin: 0;
    }

    /* تصميم النموذج */
    .form-container {
        background: white;
        border-radius: 15px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        border: 1px solid #e5e7eb;
    }

    .form-header {
        background: #f8fafc;
        padding: 25px 30px;
        border-bottom: 2px solid #e5e7eb;
    }

    .form-header h3 {
        margin: 0;
        font-size: 20px;
        font-weight: 600;
        color: #1f2937;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .form-body {
        padding: 40px 30px;
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 30px;
        margin-bottom: 30px;
    }

    .form-group {
        margin-bottom: 0;
    }

    .form-label {
        font-weight: 600;
        color: #374151;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 15px;
    }

    .form-control {
        width: 100%;
        padding: 15px 18px;
        border: 2px solid #e5e7eb;
        border-radius: 10px;
        font-size: 15px;
        transition: all 0.3s ease;
        background: #fafbfc;
    }

    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        outline: none;
        background: white;
    }

    .form-control::placeholder {
        color: #9ca3af;
    }

    .form-footer {
        background: #f8fafc;
        padding: 25px 30px;
        border-top: 2px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
    }

    .btn-group {
        display: flex;
        gap: 15px;
    }

    .btn-custom {
        padding: 15px 30px;
        border-radius: 10px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        font-size: 15px;
        min-width: 140px;
        justify-content: center;
    }

    .btn-primary-custom {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }

    .btn-primary-custom:hover {
        background: linear-gradient(135deg, #5a67d8 0%, #6b46c1 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        color: white;
    }

    .btn-secondary-custom {
        background: white;
        color: #6b7280;
        border: 2px solid #e5e7eb;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .btn-secondary-custom:hover {
        background: #f9fafb;
        border-color: #d1d5db;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        color: #6b7280;
        text-decoration: none;
    }

    .error-message {
        color: #dc2626;
        font-size: 14px;
        margin-top: 8px;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    /* تصميم متجاوب */
    @media (max-width: 768px) {
        .warehouse-container {
            padding: 0 15px;
        }

        .page-header {
            padding: 20px;
            margin-bottom: 20px;
        }

        .page-title {
            font-size: 24px;
        }

        .form-grid {
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .form-body {
            padding: 25px 20px;
        }

        .form-header {
            padding: 20px;
        }

        .form-footer {
            padding: 20px;
            flex-direction: column;
            gap: 15px;
        }

        .btn-group {
            width: 100%;
            flex-direction: column;
        }

        .btn-custom {
            width: 100%;
        }
    }

    @media (max-width: 480px) {
        .warehouse-container {
            padding: 0 10px;
        }

        .page-header {
            padding: 15px;
        }

        .page-title {
            font-size: 20px;
            flex-direction: column;
            gap: 10px;
            text-align: center;
        }
    }
</style>
@endpush

@section('content')
<div class="main-content">
    <div class="warehouse-container">
        <!-- Header -->
        <div class="page-header">
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px;">
                <div>
                    <h1 class="page-title">
                        <i class="fas fa-warehouse"></i>
                        إنشاء مستودع جديد
                    </h1>
                    <p class="page-subtitle">أضف مستودع جديد لإدارة المخزون بكفاءة</p>
                </div>
                <a href="{{ route('tenant.inventory.warehouses.index') }}"
                   class="btn-custom btn-secondary-custom">
                    <i class="fas fa-arrow-right"></i>
                    العودة للقائمة
                </a>
            </div>
        </div>

        <!-- Display Errors -->
        @if ($errors->any())
            <div style="background: #fee2e2; border: 1px solid #fecaca; color: #dc2626; padding: 20px; border-radius: 12px; margin-bottom: 25px;">
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
            <div style="background: #fee2e2; border: 1px solid #fecaca; color: #dc2626; padding: 20px; border-radius: 12px; margin-bottom: 25px;">
                <strong>
                    <i class="fas fa-times-circle" style="margin-left: 8px;"></i>
                    خطأ:
                </strong> {{ session('error') }}
            </div>
        @endif

        <!-- Form -->
        <form method="POST" action="{{ route('tenant.inventory.warehouses.store') }}" id="warehouseForm">
            @csrf

            <div class="form-container">
                <div class="form-header">
                    <h3>
                        <i class="fas fa-info-circle" style="color: #667eea;"></i>
                        معلومات المستودع
                    </h3>
                </div>

                <div class="form-body">
                    <div class="form-grid">
                        <!-- Name -->
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-warehouse" style="color: #10b981;"></i>
                                اسم المستودع *
                            </label>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                   class="form-control"
                                   placeholder="أدخل اسم المستودع">
                            @error('name')
                                <div class="error-message">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Phone -->
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-phone" style="color: #8b5cf6;"></i>
                                رقم الهاتف
                            </label>
                            <input type="text" name="phone" value="{{ old('phone') }}"
                                   class="form-control"
                                   placeholder="رقم الهاتف (اختياري)">
                            @error('phone')
                                <div class="error-message">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-align-left" style="color: #f59e0b;"></i>
                            الوصف
                        </label>
                        <textarea name="description" rows="4"
                                  class="form-control"
                                  placeholder="وصف المستودع (اختياري)">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="form-footer">
                    <div style="color: #6b7280; font-size: 14px;">
                        <i class="fas fa-info-circle" style="margin-left: 5px;"></i>
                        الحقول المميزة بـ * مطلوبة
                    </div>
                    <div class="btn-group">
                        <a href="{{ route('tenant.inventory.warehouses.index') }}"
                           class="btn-custom btn-secondary-custom">
                            <i class="fas fa-times"></i>
                            إلغاء
                        </a>
                        <button type="submit" class="btn-custom btn-primary-custom">
                            <i class="fas fa-save"></i>
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
