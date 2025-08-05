@extends('layouts.tenant')

@section('title', 'تعديل المورد')

@section('content')
<div class="main-content">
    <!-- Breadcrumb -->
    <div class="breadcrumb">
        <a href="{{ route('tenant.dashboard') }}">الرئيسية</a>
        <span class="mx-2">/</span>
        <a href="{{ route('tenant.purchasing.suppliers.index') }}">الموردين</a>
        <span class="mx-2">/</span>
        <span class="current">تعديل المورد</span>
    </div>

    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-edit text-primary me-2"></i>
                تعديل المورد
            </h1>
            <p class="text-muted mb-0">قم بتحديث معلومات المورد</p>
        </div>
        <div>
            <a href="{{ route('tenant.purchasing.suppliers.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-right me-2"></i>العودة للقائمة
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Main Form -->
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-user-edit me-2"></i>
                        تعديل بيانات المورد
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('tenant.purchasing.suppliers.update', $supplier) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Basic Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-user me-2"></i>المعلومات الأساسية
                                </h5>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">اسم المورد <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                           id="name" name="name" value="{{ old('name', $supplier->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="company_name" class="form-label">اسم الشركة</label>
                                    <input type="text" class="form-control @error('company_name') is-invalid @enderror"
                                           id="company_name" name="company_name" value="{{ old('company_name', $supplier->company_name) }}">
                                    @error('company_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-info mb-3">
                                    <i class="fas fa-phone me-2"></i>معلومات الاتصال
                                </h5>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="email" class="form-label">البريد الإلكتروني</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                           id="email" name="email" value="{{ old('email', $supplier->email) }}">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="phone" class="form-label">رقم الهاتف</label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                           id="phone" name="phone" value="{{ old('phone', $supplier->phone) }}">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="mobile" class="form-label">رقم الجوال</label>
                                    <input type="text" class="form-control @error('mobile') is-invalid @enderror"
                                           id="mobile" name="mobile" value="{{ old('mobile', $supplier->mobile) }}">
                                    @error('mobile')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Address Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-warning mb-3">
                                    <i class="fas fa-map-marker-alt me-2"></i>معلومات العنوان
                                </h5>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="city" class="form-label">المدينة</label>
                                    <input type="text" class="form-control @error('city') is-invalid @enderror"
                                           id="city" name="city" value="{{ old('city', $supplier->city) }}">
                                    @error('city')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="address" class="form-label">العنوان الكامل</label>
                                    <textarea class="form-control @error('address') is-invalid @enderror"
                                              id="address" name="address" rows="3">{{ old('address', $supplier->address) }}</textarea>
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Business Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-success mb-3">
                                    <i class="fas fa-building me-2"></i>المعلومات التجارية والمالية
                                </h5>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="tax_number" class="form-label">الرقم الضريبي</label>
                                    <input type="text" class="form-control @error('tax_number') is-invalid @enderror"
                                           id="tax_number" name="tax_number" value="{{ old('tax_number', $supplier->tax_number) }}">
                                    @error('tax_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="commercial_register" class="form-label">السجل التجاري</label>
                                    <input type="text" class="form-control @error('commercial_register') is-invalid @enderror"
                                           id="commercial_register" name="commercial_register" value="{{ old('commercial_register', $supplier->commercial_register) }}">
                                    @error('commercial_register')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="credit_limit" class="form-label">حد الائتمان (دينار عراقي)</label>
                                    <input type="number" step="0.01" class="form-control @error('credit_limit') is-invalid @enderror"
                                           id="credit_limit" name="credit_limit" value="{{ old('credit_limit', $supplier->credit_limit) }}">
                                    @error('credit_limit')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="payment_terms" class="form-label">شروط الدفع</label>
                                    <select class="form-control @error('payment_terms') is-invalid @enderror"
                                            id="payment_terms" name="payment_terms">
                                        <option value="">اختر شروط الدفع</option>
                                        <option value="cash" {{ old('payment_terms', $supplier->payment_terms) == 'cash' ? 'selected' : '' }}>نقداً</option>
                                        <option value="30_days" {{ old('payment_terms', $supplier->payment_terms) == '30_days' ? 'selected' : '' }}>30 يوم</option>
                                        <option value="60_days" {{ old('payment_terms', $supplier->payment_terms) == '60_days' ? 'selected' : '' }}>60 يوم</option>
                                        <option value="90_days" {{ old('payment_terms', $supplier->payment_terms) == '90_days' ? 'selected' : '' }}>90 يوم</option>
                                    </select>
                                    @error('payment_terms')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Status and Notes -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-danger mb-3">
                                    <i class="fas fa-cog me-2"></i>الحالة والملاحظات
                                </h5>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">الحالة</label>
                                    <select class="form-control @error('status') is-invalid @enderror"
                                            id="status" name="status">
                                        <option value="active" {{ old('status', $supplier->status) == 'active' ? 'selected' : '' }}>نشط</option>
                                        <option value="inactive" {{ old('status', $supplier->status) == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="notes" class="form-label">ملاحظات</label>
                                    <textarea class="form-control @error('notes') is-invalid @enderror"
                                              id="notes" name="notes" rows="4">{{ old('notes', $supplier->notes) }}</textarea>
                                    @error('notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('tenant.purchasing.suppliers.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>إلغاء
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>حفظ التغييرات
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Supplier Info Card -->
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-info">
                        <i class="fas fa-info-circle me-2"></i>معلومات المورد
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted">تاريخ الإنشاء</small>
                        <div class="fw-bold">{{ $supplier->created_at->format('Y-m-d H:i') }}</div>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">آخر تحديث</small>
                        <div class="fw-bold">{{ $supplier->updated_at->format('Y-m-d H:i') }}</div>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">الحالة الحالية</small>
                        <div>
                            @if($supplier->status == 'active')
                                <span class="badge bg-success">نشط</span>
                            @else
                                <span class="badge bg-danger">غير نشط</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-warning">
                        <i class="fas fa-bolt me-2"></i>إجراءات سريعة
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('tenant.purchasing.suppliers.show', $supplier) }}" class="btn btn-outline-info btn-sm">
                            <i class="fas fa-eye me-2"></i>عرض التفاصيل
                        </a>
                        <a href="#" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-file-contract me-2"></i>العقود
                        </a>
                        <a href="#" class="btn btn-outline-success btn-sm">
                            <i class="fas fa-shopping-cart me-2"></i>أوامر الشراء
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Form validation
    $('form').on('submit', function(e) {
        let isValid = true;

        // Check required fields
        $('input[required]').each(function() {
            if ($(this).val().trim() === '') {
                isValid = false;
                $(this).addClass('is-invalid');
            } else {
                $(this).removeClass('is-invalid');
            }
        });

        if (!isValid) {
            e.preventDefault();
            alert('يرجى ملء جميع الحقول المطلوبة');
        }
    });

    // Remove validation errors on input
    $('input, select, textarea').on('input change', function() {
        $(this).removeClass('is-invalid');
    });
});
</script>
@endpush
