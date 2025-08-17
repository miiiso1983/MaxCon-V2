@extends('layouts.modern')

@section('title', 'استيراد تسجيلات المنتجات')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 mb-0 text-gray-800">
                    <i class="fas fa-file-import text-primary me-2"></i>
                    استيراد تسجيلات المنتجات
                </h1>
                <p class="text-muted mb-0">ارفع ملف CSV وفق القالب المعتمد</p>
            </div>
            <div>
                <a href="{{ route('tenant.inventory.regulatory.product-registrations.download-template') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-download me-1"></i> تحميل القالب
                </a>
                <a href="{{ route('tenant.inventory.regulatory.product-registrations.index') }}" class="btn btn-secondary">
                    العودة
                </a>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('tenant.inventory.regulatory.product-registrations.import') }}" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label class="form-label">ملف CSV *</label>
                    <input type="file" name="file" accept=".csv" class="form-control" required>
                    <small class="text-muted">استخدم القالب لضمان نجاح الاستيراد</small>
                </div>
                <div class="d-flex justify-content-end">
                    <button class="btn btn-primary" type="submit">
                        <i class="fas fa-upload me-1"></i> استيراد
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

