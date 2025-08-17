@extends('layouts.modern')

@section('title', 'تسجيل المنتجات الدوائية')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 mb-0 text-gray-800">
                    <i class="fas fa-pills text-primary me-2"></i>
                    تسجيل المنتجات الدوائية
                </h1>
                <p class="text-muted mb-0">إدارة تسجيلات المنتجات الدوائية ومتابعة الموافقات والانتهاء</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('tenant.inventory.regulatory.product-registrations.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i> إضافة منتج
                </a>
                <a href="{{ route('tenant.inventory.regulatory.product-registrations.import.form') }}" class="btn btn-outline-primary">
                    <i class="fas fa-file-import me-1"></i> استيراد
                </a>
                <a href="{{ route('tenant.inventory.regulatory.product-registrations.download-template') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-download me-1"></i> قالب CSV
                </a>
                <a href="{{ route('tenant.inventory.regulatory.product-registrations.export') }}" class="btn btn-success">
                    <i class="fas fa-file-export me-1"></i> تصدير
                </a>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            @if($registrations->count() === 0)
                <div class="text-center py-5">
                    <div class="mb-3"><i class="fas fa-inbox fa-3x text-muted"></i></div>
                    <h5 class="text-muted">لا توجد تسجيلات منتجات حتى الآن</h5>
                    <p class="text-muted">ابدأ بإضافة منتج جديد أو استيراد من CSV</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>المنتج</th>
                                <th>رقم التسجيل</th>
                                <th>النوع</th>
                                <th>الحالة</th>
                                <th>الشركة</th>
                                <th>تاريخ الانتهاء</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($registrations as $r)
                            <tr>
                                <td>{{ $r->product_name }}</td>
                                <td><span class="badge bg-secondary">{{ $r->registration_number }}</span></td>
                                <td>{{ $r->product_type_name }}</td>
                                <td><span class="badge bg-info">{{ $r->status_name }}</span></td>
                                <td>{{ optional($r->company)->company_name }}</td>
                                <td>{{ optional($r->expiry_date)->format('Y-m-d') }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">{{ $registrations->links() }}</div>
            @endif
        </div>
    </div>
</div>
@endsection

