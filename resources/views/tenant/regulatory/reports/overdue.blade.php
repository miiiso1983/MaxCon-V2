@extends('layouts.tenant')

@section('title', 'التقارير التنظيمية المتأخرة')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 text-gray-800">
                        <i class="fas fa-exclamation-triangle text-danger me-2"></i>
                        التقارير التنظيمية المتأخرة
                    </h1>
                    <p class="text-muted mb-0">التقارير التي تجاوزت موعد الاستحقاق ولم يتم تقديمها بعد</p>
                </div>
                <div>
                    <a href="{{ route('tenant.inventory.regulatory.reports.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i>
                        العودة للقائمة
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert if no overdue reports -->
    @if($reports->isEmpty())
    <div class="row">
        <div class="col-12">
            <div class="alert alert-success" role="alert">
                <div class="d-flex align-items-center">
                    <i class="fas fa-check-circle fa-2x text-success me-3"></i>
                    <div>
                        <h4 class="alert-heading mb-1">ممتاز! لا توجد تقارير متأخرة</h4>
                        <p class="mb-0">جميع التقارير التنظيمية في الموعد المحدد أو مقدمة.</p>
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
                                إجمالي التقارير المتأخرة
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $reports->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-file-alt fa-2x text-danger"></i>
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
                                {{ $reports->filter(function($report) { return $report->due_date->diffInDays(now()) > 30; })->count() }}
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
                                تقارير عالية الأولوية
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $reports->whereIn('priority_level', ['high', 'urgent', 'critical'])->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation fa-2x text-info"></i>
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
                                {{ round($reports->avg(function($report) { return $report->due_date->diffInDays(now()); })) }}
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

    <!-- Overdue Reports Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-file-alt me-2"></i>
                قائمة التقارير التنظيمية المتأخرة ({{ $reports->count() }} تقرير)
            </h6>
            <div class="dropdown no-arrow">
                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                    <div class="dropdown-header">إجراءات:</div>
                    <a class="dropdown-item" href="{{ route('tenant.inventory.regulatory.reports.export') }}">
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
                            <th>عنوان التقرير</th>
                            <th>نوع التقرير</th>
                            <th>الجهة المقدمة</th>
                            <th>تاريخ الاستحقاق</th>
                            <th>أيام التأخير</th>
                            <th>الأولوية</th>
                            <th>الحالة</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reports as $report)
                        @php
                            $daysOverdue = $report->due_date->diffInDays(now());
                            $urgencyClass = $daysOverdue > 30 ? 'danger' : ($daysOverdue > 14 ? 'warning' : 'info');
                            $urgencyIcon = $daysOverdue > 30 ? 'exclamation-triangle' : ($daysOverdue > 14 ? 'clock' : 'calendar-alt');
                            $priorityClass = $report->priority_level == 'critical' ? 'danger' : ($report->priority_level == 'high' ? 'warning' : 'info');
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
                                        <div class="font-weight-bold">{{ $report->report_title }}</div>
                                        <div class="text-muted small">{{ $report->report_period ?? 'غير محدد' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @switch($report->report_type)
                                    @case('periodic')
                                        <span class="badge badge-primary">دوري</span>
                                        @break
                                    @case('incident')
                                        <span class="badge badge-danger">حادث</span>
                                        @break
                                    @case('compliance')
                                        <span class="badge badge-success">امتثال</span>
                                        @break
                                    @case('audit')
                                        <span class="badge badge-info">تدقيق</span>
                                        @break
                                    @case('inspection')
                                        <span class="badge badge-warning">تفتيش</span>
                                        @break
                                    @case('adverse_event')
                                        <span class="badge badge-dark">حدث سلبي</span>
                                        @break
                                    @default
                                        <span class="badge badge-light">{{ $report->report_type }}</span>
                                @endswitch
                            </td>
                            <td>
                                <div class="small">{{ $report->submission_authority }}</div>
                            </td>
                            <td>
                                <span class="text-{{ $urgencyClass }}">
                                    {{ $report->due_date->format('Y-m-d') }}
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-{{ $urgencyClass }}">
                                    <i class="fas fa-{{ $urgencyIcon }} me-1"></i>
                                    {{ $daysOverdue }} يوم
                                </span>
                            </td>
                            <td>
                                @switch($report->priority_level)
                                    @case('critical')
                                        <span class="badge badge-danger">حرجة</span>
                                        @break
                                    @case('high')
                                        <span class="badge badge-warning">عالية</span>
                                        @break
                                    @case('medium')
                                        <span class="badge badge-info">متوسطة</span>
                                        @break
                                    @case('low')
                                        <span class="badge badge-secondary">منخفضة</span>
                                        @break
                                    @default
                                        <span class="badge badge-light">{{ $report->priority_level ?? 'غير محدد' }}</span>
                                @endswitch
                            </td>
                            <td>
                                @switch($report->report_status)
                                    @case('draft')
                                        <span class="badge badge-secondary">مسودة</span>
                                        @break
                                    @case('pending_review')
                                        <span class="badge badge-warning">قيد المراجعة</span>
                                        @break
                                    @case('submitted')
                                        <span class="badge badge-success">مقدم</span>
                                        @break
                                    @case('approved')
                                        <span class="badge badge-success">معتمد</span>
                                        @break
                                    @case('rejected')
                                        <span class="badge badge-danger">مرفوض</span>
                                        @break
                                    @default
                                        <span class="badge badge-light">{{ $report->report_status }}</span>
                                @endswitch
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-sm btn-outline-primary" 
                                            data-toggle="modal" data-target="#viewModal{{ $report->id }}" title="عرض التفاصيل">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-success" 
                                            data-toggle="modal" data-target="#updateModal{{ $report->id }}" title="تحديث الحالة">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-warning" 
                                            data-toggle="modal" data-target="#extendModal{{ $report->id }}" title="تمديد الموعد">
                                        <i class="fas fa-calendar-plus"></i>
                                    </button>
                                </div>

                                <!-- View Modal -->
                                <div class="modal fade" id="viewModal{{ $report->id }}" tabindex="-1" role="dialog">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">تفاصيل التقرير: {{ $report->report_title }}</h5>
                                                <button type="button" class="close" data-dismiss="modal">
                                                    <span>&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <p><strong>نوع التقرير:</strong> {{ $report->report_type }}</p>
                                                        <p><strong>فترة التقرير:</strong> {{ $report->report_period ?? 'غير محدد' }}</p>
                                                        <p><strong>الجهة المقدمة:</strong> {{ $report->submission_authority }}</p>
                                                        <p><strong>تاريخ الاستحقاق:</strong> {{ $report->due_date->format('Y-m-d') }}</p>
                                                        <p><strong>أيام التأخير:</strong> <span class="text-{{ $urgencyClass }}">{{ $daysOverdue }} يوم</span></p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <p><strong>معد بواسطة:</strong> {{ $report->prepared_by ?? 'غير محدد' }}</p>
                                                        <p><strong>مراجع بواسطة:</strong> {{ $report->reviewed_by ?? 'غير محدد' }}</p>
                                                        <p><strong>معتمد بواسطة:</strong> {{ $report->approved_by ?? 'غير محدد' }}</p>
                                                        <p><strong>المرجع التنظيمي:</strong> {{ $report->regulatory_reference ?? 'غير محدد' }}</p>
                                                        <p><strong>مستوى الأولوية:</strong> 
                                                            <span class="badge badge-{{ $priorityClass }}">{{ $report->priority_level ?? 'غير محدد' }}</span>
                                                        </p>
                                                    </div>
                                                </div>
                                                @if($report->report_summary)
                                                <div class="row">
                                                    <div class="col-12">
                                                        <p><strong>ملخص التقرير:</strong></p>
                                                        <div class="alert alert-info">{{ $report->report_summary }}</div>
                                                    </div>
                                                </div>
                                                @endif
                                                @if($report->key_findings)
                                                <div class="row">
                                                    <div class="col-12">
                                                        <p><strong>النتائج الرئيسية:</strong></p>
                                                        <div class="alert alert-warning">{{ $report->key_findings }}</div>
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
                                <div class="modal fade" id="updateModal{{ $report->id }}" tabindex="-1" role="dialog">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">تحديث حالة التقرير</h5>
                                                <button type="button" class="close" data-dismiss="modal">
                                                    <span>&times;</span>
                                                </button>
                                            </div>
                                            <form action="#" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label for="report_status">الحالة الجديدة</label>
                                                        <select class="form-control" name="report_status" required>
                                                            <option value="pending_review" {{ $report->report_status == 'pending_review' ? 'selected' : '' }}>قيد المراجعة</option>
                                                            <option value="submitted" {{ $report->report_status == 'submitted' ? 'selected' : '' }}>مقدم</option>
                                                            <option value="approved" {{ $report->report_status == 'approved' ? 'selected' : '' }}>معتمد</option>
                                                            <option value="rejected" {{ $report->report_status == 'rejected' ? 'selected' : '' }}>مرفوض</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="submission_date">تاريخ التقديم</label>
                                                        <input type="date" class="form-control" name="submission_date" 
                                                               value="{{ $report->submission_date ? $report->submission_date->format('Y-m-d') : '' }}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="notes">ملاحظات</label>
                                                        <textarea class="form-control" name="notes" rows="3" 
                                                                  placeholder="ملاحظات حول التحديث...">{{ $report->notes }}</textarea>
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

                                <!-- Extend Deadline Modal -->
                                <div class="modal fade" id="extendModal{{ $report->id }}" tabindex="-1" role="dialog">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">تمديد موعد التقرير</h5>
                                                <button type="button" class="close" data-dismiss="modal">
                                                    <span>&times;</span>
                                                </button>
                                            </div>
                                            <form action="#" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label for="new_due_date">الموعد الجديد للاستحقاق</label>
                                                        <input type="date" class="form-control" name="new_due_date" 
                                                               min="{{ now()->format('Y-m-d') }}" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="extension_reason">سبب التمديد</label>
                                                        <textarea class="form-control" name="extension_reason" rows="3" 
                                                                  placeholder="سبب تمديد موعد التقرير..." required></textarea>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="approved_by">معتمد بواسطة</label>
                                                        <input type="text" class="form-control" name="approved_by" 
                                                               placeholder="اسم الشخص المعتمد للتمديد" required>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                                                    <button type="submit" class="btn btn-warning">
                                                        <i class="fas fa-calendar-plus me-1"></i>
                                                        تمديد الموعد
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
