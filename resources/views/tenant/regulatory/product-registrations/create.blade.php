@extends('layouts.modern')

@section('title', 'إضافة منتج تنظيمي')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 mb-0 text-gray-800">
                    <i class="fas fa-plus text-primary me-2"></i>
                    إضافة منتج تنظيمي
                </h1>
                <p class="text-muted mb-0">أدخل بيانات المنتج وربطها بالشركة والجهة الرقابية</p>
            </div>
            <div>
                <a href="{{ route('tenant.inventory.regulatory.product-registrations.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-right me-1"></i> العودة للقائمة
                </a>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('tenant.inventory.regulatory.product-registrations.store') }}">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">الشركة *</label>
                        <select name="company_id" class="form-select" required>
                            <option value="">اختر الشركة</option>
                            @foreach($companies as $c)
                                <option value="{{ $c->id }}">{{ $c->company_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">اسم المنتج *</label>
                        <input type="text" name="product_name" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">رقم التسجيل *</label>
                        <input type="text" name="registration_number" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">النوع *</label>
                        <select name="product_type" class="form-select" required>
                            @foreach($productTypes as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">الشكل الدوائي</label>
                        <select name="dosage_form" class="form-select">
                            <option value="">—</option>
                            @foreach($dosageForms as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">الشركة المصنعة *</label>
                        <input type="text" name="manufacturer" class="form-control" value="{{ old('manufacturer') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">بلد المنشأ *</label>
                        <input type="text" name="country_of_origin" class="form-control" value="{{ old('country_of_origin') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">الجهة الرقابية *</label>
                        <input type="text" name="regulatory_authority" class="form-control" value="{{ old('regulatory_authority') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">تاريخ التسجيل *</label>
                        <input type="date" name="registration_date" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">تاريخ الانتهاء *</label>
                        <input type="date" name="expiry_date" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">الحالة *</label>
                        <select name="status" class="form-select" required>
                            @foreach($statusTypes as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">ملاحظات</label>
                        <textarea name="notes" class="form-control" rows="3"></textarea>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-4 gap-2">
                    <a href="{{ route('tenant.inventory.regulatory.product-registrations.index') }}" class="btn btn-outline-secondary">إلغاء</a>
                    <button type="submit" class="btn btn-primary">حفظ</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

