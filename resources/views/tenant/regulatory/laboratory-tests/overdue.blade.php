@extends('layouts.tenant')

@section('title', 'الفحوصات المختبرية المتأخرة')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 text-gray-800">
                        <i class="fas fa-exclamation-triangle text-danger me-2"></i>
                        الفحوصات المختبرية المتأخرة
                    </h1>
                    <p class="text-muted mb-0">الفحوصات التي تجاوزت موعدها المحدد ولم تكتمل بعد</p>
                </div>
                <div>
                    <a href="{{ route('tenant.inventory.regulatory.laboratory-tests.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i>
                        العودة للقائمة
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert if no overdue tests -->
    @if($tests->isEmpty())
    <div class="row">
        <div class="col-12">
            <div class="alert alert-success" role="alert">
                <div class="d-flex align-items-center">
                    <i class="fas fa-check-circle fa-2x text-success me-3"></i>
                    <div>
                        <h4 class="alert-heading mb-1">ممتاز! لا توجد فحوصات متأخرة</h4>
                        <p class="mb-0">جميع الفحوصات المختبرية في الموعد المحدد أو مكتملة.</p>
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
                                إجمالي الفحوصات المتأخرة
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $tests->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-flask fa-2x text-danger"></i>
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
                                متأخرة أكثر من 7 أيام
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $tests->filter(function($test) { return $test->test_date->diffInDays(now()) > 7; })->count() }}
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
                                فحوصات الجودة المتأخرة
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $tests->where('test_type', 'quality_control')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-award fa-2x text-info"></i>
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
                                {{ round($tests->avg(function($test) { return $test->test_date->diffInDays(now()); })) }}
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

    <!-- Overdue Tests Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-flask me-2"></i>
                قائمة الفحوصات المختبرية المتأخرة ({{ $tests->count() }} فحص)
            </h6>
            <div class="dropdown no-arrow">
                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                    <div class="dropdown-header">إجراءات:</div>
                    <a class="dropdown-item" href="{{ route('tenant.inventory.regulatory.laboratory-tests.export') }}">
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
                            <th>اسم الفحص</th>
                            <th>نوع الفحص</th>
                            <th>اسم المنتج</th>
                            <th>رقم الدفعة</th>
                            <th>المختبر</th>
                            <th>تاريخ الفحص</th>
                            <th>أيام التأخير</th>
                            <th>الحالة</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tests as $test)
                        @php
                            $daysOverdue = $test->test_date->diffInDays(now());
                            $urgencyClass = $daysOverdue > 14 ? 'danger' : ($daysOverdue > 7 ? 'warning' : 'info');
                            $urgencyIcon = $daysOverdue > 14 ? 'exclamation-triangle' : ($daysOverdue > 7 ? 'clock' : 'calendar-alt');
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
                                        <div class="font-weight-bold">{{ $test->test_name }}</div>
                                        <div class="text-muted small">{{ $test->test_method ?? 'غير محدد' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @switch($test->test_type)
                                    @case('quality_control')
                                        <span class="badge badge-primary">مراقبة الجودة</span>
                                        @break
                                    @case('stability')
                                        <span class="badge badge-info">اختبار الثبات</span>
                                        @break
                                    @case('microbiological')
                                        <span class="badge badge-success">ميكروبيولوجي</span>
                                        @break
                                    @case('chemical')
                                        <span class="badge badge-warning">كيميائي</span>
                                        @break
                                    @case('physical')
                                        <span class="badge badge-secondary">فيزيائي</span>
                                        @break
                                    @case('bioequivalence')
                                        <span class="badge badge-dark">التكافؤ الحيوي</span>
                                        @break
                                    @default
                                        <span class="badge badge-light">{{ $test->test_type }}</span>
                                @endswitch
                            </td>
                            <td>
                                <div class="font-weight-bold">{{ $test->product_name }}</div>
                            </td>
                            <td>
                                <span class="badge badge-secondary">{{ $test->batch_number }}</span>
                            </td>
                            <td>
                                <div class="small">{{ $test->laboratory_name }}</div>
                            </td>
                            <td>
                                <span class="text-{{ $urgencyClass }}">
                                    {{ $test->test_date->format('Y-m-d') }}
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-{{ $urgencyClass }}">
                                    <i class="fas fa-{{ $urgencyIcon }} me-1"></i>
                                    {{ $daysOverdue }} يوم
                                </span>
                            </td>
                            <td>
                                @switch($test->status)
                                    @case('pending')
                                        <span class="badge badge-warning">معلق</span>
                                        @break
                                    @case('in_progress')
                                        <span class="badge badge-info">قيد التنفيذ</span>
                                        @break
                                    @case('completed')
                                        <span class="badge badge-success">مكتمل</span>
                                        @break
                                    @case('failed')
                                        <span class="badge badge-danger">فاشل</span>
                                        @break
                                    @case('cancelled')
                                        <span class="badge badge-secondary">ملغي</span>
                                        @break
                                    @default
                                        <span class="badge badge-light">{{ $test->status }}</span>
                                @endswitch
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-sm btn-outline-primary" 
                                            data-toggle="modal" data-target="#viewModal{{ $test->id }}" title="عرض التفاصيل">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-success" 
                                            data-toggle="modal" data-target="#updateModal{{ $test->id }}" title="تحديث الحالة">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-warning" 
                                            data-toggle="modal" data-target="#rescheduleModal{{ $test->id }}" title="إعادة جدولة">
                                        <i class="fas fa-calendar-alt"></i>
                                    </button>
                                </div>

                                <!-- View Modal -->
                                <div class="modal fade" id="viewModal{{ $test->id }}" tabindex="-1" role="dialog">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">تفاصيل الفحص: {{ $test->test_name }}</h5>
                                                <button type="button" class="close" data-dismiss="modal">
                                                    <span>&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <p><strong>اسم المنتج:</strong> {{ $test->product_name }}</p>
                                                        <p><strong>رقم الدفعة:</strong> {{ $test->batch_number }}</p>
                                                        <p><strong>المختبر:</strong> {{ $test->laboratory_name }}</p>
                                                        <p><strong>تاريخ الفحص:</strong> {{ $test->test_date->format('Y-m-d') }}</p>
                                                        <p><strong>أيام التأخير:</strong> <span class="text-{{ $urgencyClass }}">{{ $daysOverdue }} يوم</span></p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <p><strong>الفني المسؤول:</strong> {{ $test->technician_name ?? 'غير محدد' }}</p>
                                                        <p><strong>المشرف:</strong> {{ $test->supervisor_name ?? 'غير محدد' }}</p>
                                                        <p><strong>التكلفة:</strong> {{ $test->cost ? number_format($test->cost) . ' دينار' : 'غير محدد' }}</p>
                                                        <p><strong>المواصفات:</strong> {{ $test->specifications ?? 'غير محدد' }}</p>
                                                    </div>
                                                </div>
                                                @if($test->notes)
                                                <div class="row">
                                                    <div class="col-12">
                                                        <p><strong>ملاحظات:</strong></p>
                                                        <div class="alert alert-info">{{ $test->notes }}</div>
                                                    </div>
                                                </div>
                                                @endif
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Update Status Modal -->
                                <div class="modal fade" id="updateModal{{ $test->id }}" tabindex="-1" role="dialog">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">تحديث حالة الفحص</h5>
                                                <button type="button" class="close" data-dismiss="modal">
                                                    <span>&times;</span>
                                                </button>
                                            </div>
                                            <form action="#" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label for="status">الحالة الجديدة</label>
                                                        <select class="form-control" name="status" required>
                                                            <option value="in_progress" {{ $test->status == 'in_progress' ? 'selected' : '' }}>قيد التنفيذ</option>
                                                            <option value="completed" {{ $test->status == 'completed' ? 'selected' : '' }}>مكتمل</option>
                                                            <option value="failed" {{ $test->status == 'failed' ? 'selected' : '' }}>فاشل</option>
                                                            <option value="cancelled" {{ $test->status == 'cancelled' ? 'selected' : '' }}>ملغي</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="completion_date">تاريخ الإنجاز</label>
                                                        <input type="date" class="form-control" name="completion_date" 
                                                               value="{{ $test->completion_date ? $test->completion_date->format('Y-m-d') : '' }}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="results">النتائج</label>
                                                        <textarea class="form-control" name="results" rows="3" 
                                                                  placeholder="نتائج الفحص...">{{ $test->results }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                                                    <button type="submit" class="btn btn-success">
                                                        <i class="fas fa-save me-1"></i>
                                                        حفظ التحديث
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- Reschedule Modal -->
                                <div class="modal fade" id="rescheduleModal{{ $test->id }}" tabindex="-1" role="dialog">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">إعادة جدولة الفحص</h5>
                                                <button type="button" class="close" data-dismiss="modal">
                                                    <span>&times;</span>
                                                </button>
                                            </div>
                                            <form action="#" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label for="new_test_date">التاريخ الجديد للفحص</label>
                                                        <input type="date" class="form-control" name="new_test_date" 
                                                               min="{{ now()->format('Y-m-d') }}" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="reschedule_reason">سبب إعادة الجدولة</label>
                                                        <textarea class="form-control" name="reschedule_reason" rows="3" 
                                                                  placeholder="سبب إعادة جدولة الفحص..." required></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                                                    <button type="submit" class="btn btn-warning">
                                                        <i class="fas fa-calendar-alt me-1"></i>
                                                        إعادة جدولة
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
        "order": [[ 6, "desc" ]], // Sort by days overdue (descending)
        "pageLength": 25,
        "responsive": true,
        "columnDefs": [
            { "responsivePriority": 1, "targets": 0 },
            { "responsivePriority": 2, "targets": 6 },
            { "responsivePriority": 3, "targets": -1 }
        ]
    });
});
</script>
@endpush
@endsection
