@extends('layouts.modern')

@section('title', 'الشركات المنتهية الصلاحية قريباً')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 text-gray-800">
                        <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                        الشركات المنتهية الصلاحية قريباً
                    </h1>
                    <p class="text-muted mb-0">الشركات التي تنتهي صلاحية تراخيصها خلال الـ 90 يوم القادمة</p>
                </div>
                <div>
                    <a href="{{ route('tenant.inventory.regulatory.companies.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i>
                        العودة للقائمة
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert if no companies -->
    @if($companies->isEmpty())
    <div class="row">
        <div class="col-12">
            <div class="alert alert-success" role="alert">
                <div class="d-flex align-items-center">
                    <i class="fas fa-check-circle fa-2x text-success me-3"></i>
                    <div>
                        <h4 class="alert-heading mb-1">ممتاز! لا توجد شركات منتهية الصلاحية</h4>
                        <p class="mb-0">جميع تراخيص الشركات المسجلة صالحة لأكثر من 90 يوماً.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                منتهية الصلاحية
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $companies->where('license_expiry_date', '<', now())->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-times-circle fa-2x text-danger"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                تنتهي خلال 30 يوم
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $companies->where('license_expiry_date', '>=', now())->where('license_expiry_date', '<=', now()->addDays(30))->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                تنتهي خلال 60 يوم
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $companies->where('license_expiry_date', '>', now()->addDays(30))->where('license_expiry_date', '<=', now()->addDays(60))->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                تنتهي خلال 90 يوم
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $companies->where('license_expiry_date', '>', now()->addDays(60))->where('license_expiry_date', '<=', now()->addDays(90))->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-alt fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Companies Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-building me-2"></i>
                قائمة الشركات المنتهية الصلاحية قريباً ({{ $companies->count() }} شركة)
            </h6>
            <div class="dropdown no-arrow">
                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                    <div class="dropdown-header">إجراءات:</div>
                    <a class="dropdown-item" href="{{ route('tenant.inventory.regulatory.companies.export') }}">
                        <i class="fas fa-download fa-sm fa-fw mr-2 text-gray-400"></i>
                        تصدير إلى Excel
                    </a>
                    <a class="dropdown-item" href="#" onclick="window.print()">
                        <i class="fas fa-print fa-sm fa-fw mr-2 text-gray-400"></i>
                        طباعة
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>اسم الشركة</th>
                            <th>رقم الترخيص</th>
                            <th>نوع الشركة</th>
                            <th>تاريخ انتهاء الترخيص</th>
                            <th>الأيام المتبقية</th>
                            <th>الحالة</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($companies as $company)
                        @php
                            $daysRemaining = now()->diffInDays($company->license_expiry_date, false);
                            $isExpired = $daysRemaining < 0;
                            $urgencyClass = $isExpired ? 'danger' : ($daysRemaining <= 30 ? 'warning' : ($daysRemaining <= 60 ? 'info' : 'primary'));
                            $urgencyIcon = $isExpired ? 'times-circle' : ($daysRemaining <= 30 ? 'exclamation-triangle' : ($daysRemaining <= 60 ? 'clock' : 'calendar-alt'));
                        @endphp
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="mr-3">
                                        <div class="icon-circle bg-{{ $urgencyClass }}">
                                            <i class="fas fa-{{ $urgencyIcon }} text-white"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="font-weight-bold">{{ $company->company_name }}</div>
                                        <div class="text-muted small">{{ $company->company_name_english }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-secondary">{{ $company->license_number }}</span>
                            </td>
                            <td>
                                @switch($company->company_type)
                                    @case('manufacturer')
                                        <span class="badge badge-primary">مصنع</span>
                                        @break
                                    @case('distributor')
                                        <span class="badge badge-info">موزع</span>
                                        @break
                                    @case('importer')
                                        <span class="badge badge-success">مستورد</span>
                                        @break
                                    @case('exporter')
                                        <span class="badge badge-warning">مصدر</span>
                                        @break
                                    @case('retailer')
                                        <span class="badge badge-secondary">تجزئة</span>
                                        @break
                                    @default
                                        <span class="badge badge-light">{{ $company->company_type }}</span>
                                @endswitch
                            </td>
                            <td>
                                <span class="text-{{ $urgencyClass }}">
                                    {{ $company->license_expiry_date->format('Y-m-d') }}
                                </span>
                            </td>
                            <td>
                                @if($isExpired)
                                    <span class="badge badge-danger">
                                        <i class="fas fa-times-circle me-1"></i>
                                        منتهية منذ {{ abs($daysRemaining) }} يوم
                                    </span>
                                @else
                                    <span class="badge badge-{{ $urgencyClass }}">
                                        <i class="fas fa-{{ $urgencyIcon }} me-1"></i>
                                        {{ $daysRemaining }} يوم
                                    </span>
                                @endif
                            </td>
                            <td>
                                @switch($company->status)
                                    @case('active')
                                        <span class="badge badge-success">نشط</span>
                                        @break
                                    @case('inactive')
                                        <span class="badge badge-secondary">غير نشط</span>
                                        @break
                                    @case('suspended')
                                        <span class="badge badge-warning">معلق</span>
                                        @break
                                    @case('expired')
                                        <span class="badge badge-danger">منتهي</span>
                                        @break
                                    @default
                                        <span class="badge badge-light">{{ $company->status }}</span>
                                @endswitch
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('tenant.inventory.regulatory.companies.show', $company) }}" 
                                       class="btn btn-sm btn-outline-primary" title="عرض التفاصيل">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('tenant.inventory.regulatory.companies.edit', $company) }}" 
                                       class="btn btn-sm btn-outline-warning" title="تعديل">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-success" 
                                            data-toggle="modal" data-target="#renewModal{{ $company->id }}" title="تجديد الترخيص">
                                        <i class="fas fa-sync-alt"></i>
                                    </button>
                                </div>

                                <!-- Renewal Modal -->
                                <div class="modal fade" id="renewModal{{ $company->id }}" tabindex="-1" role="dialog">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">تجديد ترخيص {{ $company->company_name }}</h5>
                                                <button type="button" class="close" data-dismiss="modal">
                                                    <span>&times;</span>
                                                </button>
                                            </div>
                                            <form action="{{ route('tenant.inventory.regulatory.companies.renew', $company) }}" method="POST">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label for="new_expiry_date">تاريخ انتهاء الترخيص الجديد</label>
                                                        <input type="date" class="form-control" name="new_expiry_date" 
                                                               min="{{ now()->addDay()->format('Y-m-d') }}" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="new_license_number">رقم الترخيص الجديد (اختياري)</label>
                                                        <input type="text" class="form-control" name="new_license_number" 
                                                               placeholder="اتركه فارغاً للاحتفاظ بالرقم الحالي">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="notes">ملاحظات</label>
                                                        <textarea class="form-control" name="notes" rows="3" 
                                                                  placeholder="ملاحظات حول التجديد..."></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                                                    <button type="submit" class="btn btn-success">
                                                        <i class="fas fa-sync-alt me-1"></i>
                                                        تجديد الترخيص
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
</div>

@push('styles')
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap4.min.css">
<!-- Custom CSS -->
<link rel="stylesheet" href="{{ asset('css/regulatory-companies.css') }}">
<style>
.icon-circle {
    height: 2rem;
    width: 2rem;
    border-radius: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}
.card {
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
    border: 1px solid #e3e6f0;
}
.border-left-danger {
    border-left: 0.25rem solid #e74a3b !important;
}
.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}
.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}
.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}
</style>
@endpush

@push('scripts')
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap4.min.js"></script>
<script>
$(document).ready(function() {
    $('#dataTable').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Arabic.json"
        },
        "order": [[ 4, "asc" ]], // Sort by days remaining
        "pageLength": 25,
        "responsive": true,
        "columnDefs": [
            { "responsivePriority": 1, "targets": 0 },
            { "responsivePriority": 2, "targets": 4 },
            { "responsivePriority": 3, "targets": -1 }
        ]
    });
});
</script>
@endpush
@endsection
