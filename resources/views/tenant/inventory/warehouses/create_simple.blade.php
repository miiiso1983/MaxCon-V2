@extends('layouts.tenant')

@section('title', 'إنشاء مستودع جديد')

@push('styles')
<style>
    .text-purple {
        color: #8b5cf6 !important;
    }

    .card {
        border: none;
        border-radius: 12px;
    }

    .card-header {
        border-radius: 12px 12px 0 0 !important;
        border-bottom: 1px solid #e9ecef;
    }

    .card-footer {
        border-radius: 0 0 12px 12px !important;
        border-top: 1px solid #e9ecef;
    }

    .form-control {
        border-radius: 8px;
        border: 2px solid #e9ecef;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }

    .btn {
        border-radius: 8px;
        font-weight: 600;
        padding: 10px 20px;
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #5a67d8 0%, #6b46c1 100%);
        transform: translateY(-1px);
    }

    .btn-outline-secondary:hover {
        transform: translateY(-1px);
    }

    @media (max-width: 576px) {
        .container-fluid {
            padding-left: 15px;
            padding-right: 15px;
        }

        .card-body {
            padding: 1rem;
        }

        .btn {
            width: 100%;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-3 px-md-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                <div class="mb-3 mb-md-0">
                    <h1 class="h3 fw-bold text-dark mb-1">
                        <i class="fas fa-warehouse text-primary me-2"></i>
                        إنشاء مستودع جديد
                    </h1>
                    <p class="text-muted mb-0">أضف مستودع جديد لإدارة المخزون</p>
                </div>
                <a href="{{ route('tenant.inventory.warehouses.index') }}"
                   class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-right me-1"></i>
                    العودة للقائمة
                </a>
            </div>
        </div>
    </div>

    <!-- Display Errors -->
    @if ($errors->any())
        <div class="row mb-4">
            <div class="col-12">
                <div class="alert alert-danger">
                    <h6 class="fw-bold mb-2">يرجى تصحيح الأخطاء التالية:</h6>
                    <ul class="mb-0 pe-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="row mb-4">
            <div class="col-12">
                <div class="alert alert-danger">
                    <strong>خطأ:</strong> {{ session('error') }}
                </div>
            </div>
        </div>
    @endif

    <!-- Simple Form -->
    <div class="row">
        <div class="col-12 col-lg-8 col-xl-6 mx-auto">
            <form method="POST" action="{{ route('tenant.inventory.warehouses.store') }}" id="warehouseForm">
                @csrf

                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-info-circle text-primary me-2"></i>
                            معلومات المستودع
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <!-- Name -->
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-warehouse text-success me-1"></i>
                                    اسم المستودع *
                                </label>
                                <input type="text" name="name" value="{{ old('name') }}" required
                                       class="form-control @error('name') is-invalid @enderror"
                                       placeholder="أدخل اسم المستودع">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Phone -->
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-phone text-purple me-1"></i>
                                    رقم الهاتف
                                </label>
                                <input type="text" name="phone" value="{{ old('phone') }}"
                                       class="form-control @error('phone') is-invalid @enderror"
                                       placeholder="رقم الهاتف (اختياري)">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div class="col-12">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-align-left text-warning me-1"></i>
                                    الوصف
                                </label>
                                <textarea name="description" rows="3"
                                          class="form-control @error('description') is-invalid @enderror"
                                          placeholder="وصف المستودع (اختياري)">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="card-footer bg-light">
                    <div class="d-flex flex-column flex-sm-row gap-2 justify-content-end">
                        <a href="{{ route('tenant.inventory.warehouses.index') }}"
                           class="btn btn-outline-secondary order-2 order-sm-1">
                            <i class="fas fa-times me-1"></i>
                            إلغاء
                        </a>
                        <button type="submit" class="btn btn-primary order-1 order-sm-2">
                            <i class="fas fa-save me-1"></i>
                            إنشاء المستودع
                        </button>
                    </div>
                </div>
            </form>
        </div>
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
