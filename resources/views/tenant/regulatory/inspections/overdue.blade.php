@extends('layouts.modern')

@section('title', 'التفتيشات المتأخرة')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 text-gray-800">
                        <i class="fas fa-exclamation-triangle text-danger me-2"></i>
                        التفتيشات المتأخرة
                    </h1>
                    <p class="text-muted mb-0">التفتيشات التي تجاوزت موعدها المحدد ولم يتم إنجازها بعد</p>
                </div>
                <div>
                    <a href="{{ route('tenant.inventory.regulatory.inspections.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i>
                        العودة للقائمة
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert if no overdue inspections -->
    @if($inspections->isEmpty())
    <div class="row">
        <div class="col-12">
            <div class="alert alert-success" role="alert">
                <div class="d-flex align-items-center">
                    <i class="fas fa-check-circle fa-2x text-success me-3"></i>
                    <div>
                        <h4 class="alert-heading mb-1">ممتاز! لا توجد تفتيشات متأخرة</h4>
                        <p class="mb-0">جميع التفتيشات في الموعد المحدد أو مكتملة.</p>
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
                                إجمالي التفتيشات المتأخرة
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $inspections->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-search fa-2x text-danger"></i>
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
                                متأخرة أكثر من 30 يوم
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $inspections->filter(function($inspection) { return $inspection->scheduled_date->diffInDays(now()) > 30; })->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-times fa-2x text-warning"></i>
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
                                تفتيشات روتينية
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $inspections->where('inspection_type', 'routine')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-check fa-2x text-info"></i>
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
                                متوسط التأخير (أيام)
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ round($inspections->avg(function($inspection) { return $inspection->scheduled_date->diffInDays(now()); })) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Overdue Inspections Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-search me-2"></i>
                قائمة التفتيشات المتأخرة ({{ $inspections->count() }} تفتيش)
            </h6>
            <div class="dropdown no-arrow">
                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                    <div class="dropdown-header">إجراءات:</div>
                    <a class="dropdown-item" href="{{ route('tenant.inventory.regulatory.inspections.export') }}">
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
                            <th>عنوان التفتيش</th>
                            <th>نوع التفتيش</th>
                            <th>المفتش</th>
                            <th>التاريخ المجدول</th>
                            <th>أيام التأخير</th>
                            <th>المنشأة</th>
                            <th>الحالة</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($inspections as $inspection)
                        @php
                            $daysOverdue = $inspection->scheduled_date->diffInDays(now());
                            $urgencyClass = $daysOverdue > 30 ? 'danger' : ($daysOverdue > 14 ? 'warning' : 'info');
                            $urgencyIcon = $daysOverdue > 30 ? 'exclamation-triangle' : ($daysOverdue > 14 ? 'clock' : 'calendar-alt');
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
                                        <div class="font-weight-bold">{{ $inspection->inspection_title }}</div>
                                        <div class="text-muted small">{{ $inspection->inspection_authority }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-secondary">{{ $inspection->getInspectionTypeLabel() }}</span>
                            </td>
                            <td>
                                <div class="small">{{ $inspection->inspector_name }}</div>
                            </td>
                            <td>
                                <span class="text-{{ $urgencyClass }}">
                                    {{ $inspection->scheduled_date->format('Y-m-d') }}
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-{{ $urgencyClass }}">
                                    <i class="fas fa-{{ $urgencyIcon }} me-1"></i>
                                    {{ $daysOverdue }} يوم
                                </span>
                            </td>
                            <td>
                                <div class="small">
                                    <strong>{{ $inspection->facility_name }}</strong><br>
                                    <span class="text-muted">{{ Str::limit($inspection->facility_address, 30) }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-warning">{{ $inspection->getInspectionStatusLabel() }}</span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('tenant.inventory.regulatory.inspections.show', $inspection->id) }}" 
                                       class="btn btn-sm btn-outline-primary" title="عرض التفاصيل">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-success" 
                                            data-toggle="modal" data-target="#rescheduleModal{{ $inspection->id }}" title="إعادة جدولة">
                                        <i class="fas fa-calendar-plus"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-warning" 
                                            data-toggle="modal" data-target="#updateModal{{ $inspection->id }}" title="تحديث الحالة">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </div>

                                <!-- Reschedule Modal -->
                                <div class="modal fade" id="rescheduleModal{{ $inspection->id }}" tabindex="-1" role="dialog">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">إعادة جدولة التفتيش</h5>
                                                <button type="button" class="close" data-dismiss="modal">
                                                    <span>&times;</span>
                                                </button>
                                            </div>
                                            <form action="{{ route('tenant.inventory.regulatory.inspections.update', $inspection->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label for="scheduled_date">التاريخ الجديد</label>
                                                        <input type="date" class="form-control" name="scheduled_date" 
                                                               min="{{ now()->format('Y-m-d') }}" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="reschedule_reason">سبب إعادة الجدولة</label>
                                                        <textarea class="form-control" name="notes" rows="3" 
                                                                  placeholder="سبب تأجيل التفتيش..." required></textarea>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="inspector_name">المفتش المكلف</label>
                                                        <input type="text" class="form-control" name="inspector_name" 
                                                               value="{{ $inspection->inspector_name }}" required>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                                                    <button type="submit" class="btn btn-success">
                                                        <i class="fas fa-calendar-plus me-1"></i>
                                                        إعادة جدولة
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- Update Status Modal -->
                                <div class="modal fade" id="updateModal{{ $inspection->id }}" tabindex="-1" role="dialog">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">تحديث حالة التفتيش</h5>
                                                <button type="button" class="close" data-dismiss="modal">
                                                    <span>&times;</span>
                                                </button>
                                            </div>
                                            <form action="{{ route('tenant.inventory.regulatory.inspections.update', $inspection->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label for="inspection_status">الحالة الجديدة</label>
                                                        <select class="form-control" name="inspection_status" required>
                                                            <option value="in_progress" {{ $inspection->inspection_status == 'in_progress' ? 'selected' : '' }}>قيد التنفيذ</option>
                                                            <option value="completed" {{ $inspection->inspection_status == 'completed' ? 'selected' : '' }}>مكتمل</option>
                                                            <option value="postponed" {{ $inspection->inspection_status == 'postponed' ? 'selected' : '' }}>مؤجل</option>
                                                            <option value="cancelled" {{ $inspection->inspection_status == 'cancelled' ? 'selected' : '' }}>ملغي</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="completion_date">تاريخ الإنجاز (إذا كان مكتمل)</label>
                                                        <input type="date" class="form-control" name="completion_date" 
                                                               value="{{ $inspection->completion_date ? $inspection->completion_date->format('Y-m-d') : '' }}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="findings">النتائج</label>
                                                        <textarea class="form-control" name="findings" rows="3" 
                                                                  placeholder="نتائج التفتيش...">{{ $inspection->findings }}</textarea>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="compliance_rating">تقييم الامتثال</label>
                                                        <select class="form-control" name="compliance_rating">
                                                            <option value="">اختر التقييم</option>
                                                            <option value="excellent" {{ $inspection->compliance_rating == 'excellent' ? 'selected' : '' }}>ممتاز</option>
                                                            <option value="good" {{ $inspection->compliance_rating == 'good' ? 'selected' : '' }}>جيد</option>
                                                            <option value="satisfactory" {{ $inspection->compliance_rating == 'satisfactory' ? 'selected' : '' }}>مرضي</option>
                                                            <option value="needs_improvement" {{ $inspection->compliance_rating == 'needs_improvement' ? 'selected' : '' }}>يحتاج تحسين</option>
                                                            <option value="non_compliant" {{ $inspection->compliance_rating == 'non_compliant' ? 'selected' : '' }}>غير ملتزم</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                                                    <button type="submit" class="btn btn-warning">
                                                        <i class="fas fa-save me-1"></i>
                                                        حفظ التحديث
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
<style>
.icon-circle {
    height: 2rem;
    width: 2rem;
    border-radius: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
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
        "order": [[ 4, "desc" ]], // Sort by days overdue (descending)
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
