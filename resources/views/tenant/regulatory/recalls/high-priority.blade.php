@extends('layouts.modern')

@section('title', 'استدعاء المنتجات عالية الأولوية')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 text-gray-800">
                        <i class="fas fa-exclamation-triangle text-danger me-2"></i>
                        استدعاء المنتجات عالية الأولوية
                    </h1>
                    <p class="text-muted mb-0">استدعاءات الفئة الأولى والثانية التي تتطلب إجراءات عاجلة</p>
                </div>
                <div>
                    <a href="{{ route('tenant.inventory.regulatory.recalls.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i>
                        العودة للقائمة
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert if no high priority recalls -->
    @if($recalls->isEmpty())
    <div class="row">
        <div class="col-12">
            <div class="alert alert-success" role="alert">
                <div class="d-flex align-items-center">
                    <i class="fas fa-check-circle fa-2x text-success me-3"></i>
                    <div>
                        <h4 class="alert-heading mb-1">ممتاز! لا توجد استدعاءات عالية الأولوية</h4>
                        <p class="mb-0">جميع استدعاءات المنتجات من الفئة الثالثة أو مكتملة.</p>
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
                                الفئة الأولى (حرجة)
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $recalls->where('recall_class', 'class_i')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-skull-crossbones fa-2x text-danger"></i>
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
                                الفئة الثانية (خطيرة)
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $recalls->where('recall_class', 'class_ii')->count() }}
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
                                استدعاءات نشطة
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $recalls->whereIn('status', ['initiated', 'in_progress', 'ongoing'])->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-play-circle fa-2x text-info"></i>
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
                                متوسط نسبة الاسترداد
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ round($recalls->avg('recovery_percentage'), 1) }}%
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-percentage fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- High Priority Recalls Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-exclamation-triangle me-2"></i>
                استدعاءات المنتجات عالية الأولوية ({{ $recalls->count() }} استدعاء)
            </h6>
            <div class="dropdown no-arrow">
                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                    <div class="dropdown-header">إجراءات:</div>
                    <a class="dropdown-item" href="{{ route('tenant.inventory.regulatory.recalls.export') }}">
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
                            <th>رقم الاستدعاء</th>
                            <th>المنتج</th>
                            <th>الفئة</th>
                            <th>السبب</th>
                            <th>تاريخ البدء</th>
                            <th>الكمية المتأثرة</th>
                            <th>نسبة الاسترداد</th>
                            <th>الحالة</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recalls as $recall)
                        @php
                            $classColor = $recall->recall_class == 'class_i' ? 'danger' : 'warning';
                            $classIcon = $recall->recall_class == 'class_i' ? 'skull-crossbones' : 'exclamation-triangle';
                            $recoveryPercentage = $recall->quantity_affected > 0 ? round(($recall->quantity_recovered / $recall->quantity_affected) * 100, 1) : 0;
                        @endphp
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="mr-3">
                                        <div class="icon-circle bg-{{ $classColor }}">
                                            <i class="fas fa-{{ $classIcon }} text-white"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="font-weight-bold">{{ $recall->recall_number }}</div>
                                        <div class="text-muted small">{{ $recall->regulatory_authority ?? 'غير محدد' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="font-weight-bold">{{ $recall->product_name ?? 'غير محدد' }}</div>
                                <div class="text-muted small">
                                    @if(is_array($recall->affected_batches))
                                        الدفعات: {{ implode(', ', array_slice($recall->affected_batches, 0, 2)) }}
                                        @if(count($recall->affected_batches) > 2)
                                            <span class="text-info">+{{ count($recall->affected_batches) - 2 }} أخرى</span>
                                        @endif
                                    @else
                                        {{ $recall->affected_batches ?? 'غير محدد' }}
                                    @endif
                                </div>
                            </td>
                            <td>
                                @if($recall->recall_class == 'class_i')
                                    <span class="badge badge-danger">
                                        <i class="fas fa-skull-crossbones me-1"></i>
                                        الفئة الأولى
                                    </span>
                                @elseif($recall->recall_class == 'class_ii')
                                    <span class="badge badge-warning">
                                        <i class="fas fa-exclamation-triangle me-1"></i>
                                        الفئة الثانية
                                    </span>
                                @else
                                    <span class="badge badge-info">{{ $recall->recall_class }}</span>
                                @endif
                            </td>
                            <td>
                                <div class="text-wrap" style="max-width: 200px;">
                                    {{ Str::limit($recall->reason, 50) }}
                                </div>
                            </td>
                            <td>
                                <span class="text-{{ $classColor }}">
                                    {{ $recall->initiated_date ? $recall->initiated_date->format('Y-m-d') : 'غير محدد' }}
                                </span>
                            </td>
                            <td>
                                <div class="text-center">
                                    <div class="font-weight-bold">{{ number_format($recall->quantity_affected ?? 0) }}</div>
                                    <div class="text-muted small">وحدة</div>
                                </div>
                            </td>
                            <td>
                                <div class="text-center">
                                    @php
                                        $percentage = $recoveryPercentage;
                                        $progressColor = $percentage >= 80 ? 'success' : ($percentage >= 50 ? 'warning' : 'danger');
                                    @endphp
                                    <div class="progress mb-1" style="height: 20px;">
                                        <div class="progress-bar bg-{{ $progressColor }}" role="progressbar" 
                                             style="width: {{ $percentage }}%" 
                                             aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100">
                                            {{ $percentage }}%
                                        </div>
                                    </div>
                                    <small class="text-muted">{{ number_format($recall->quantity_recovered ?? 0) }} مسترد</small>
                                </div>
                            </td>
                            <td>
                                @switch($recall->status)
                                    @case('initiated')
                                        <span class="badge badge-info">مبدأ</span>
                                        @break
                                    @case('in_progress')
                                        <span class="badge badge-warning">قيد التنفيذ</span>
                                        @break
                                    @case('ongoing')
                                        <span class="badge badge-primary">مستمر</span>
                                        @break
                                    @case('completed')
                                        <span class="badge badge-success">مكتمل</span>
                                        @break
                                    @case('closed')
                                        <span class="badge badge-secondary">مغلق</span>
                                        @break
                                    @case('terminated')
                                        <span class="badge badge-dark">منتهي</span>
                                        @break
                                    @default
                                        <span class="badge badge-light">{{ $recall->status }}</span>
                                @endswitch
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-sm btn-outline-primary" 
                                            data-toggle="modal" data-target="#viewModal{{ $recall->id }}" title="عرض التفاصيل">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-success" 
                                            data-toggle="modal" data-target="#updateModal{{ $recall->id }}" title="تحديث الحالة">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-warning" 
                                            data-toggle="modal" data-target="#reportModal{{ $recall->id }}" title="تقرير الفعالية">
                                        <i class="fas fa-chart-line"></i>
                                    </button>
                                </div>

                                <!-- View Modal -->
                                <div class="modal fade" id="viewModal{{ $recall->id }}" tabindex="-1" role="dialog">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">تفاصيل الاستدعاء: {{ $recall->recall_number }}</h5>
                                                <button type="button" class="close" data-dismiss="modal">
                                                    <span>&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <p><strong>المنتج:</strong> {{ $recall->product_name ?? 'غير محدد' }}</p>
                                                        <p><strong>نوع الاستدعاء:</strong> {{ $recall->recall_type ?? 'غير محدد' }}</p>
                                                        <p><strong>الفئة:</strong> 
                                                            @if($recall->recall_class == 'class_i')
                                                                <span class="text-danger">الفئة الأولى (حرجة)</span>
                                                            @elseif($recall->recall_class == 'class_ii')
                                                                <span class="text-warning">الفئة الثانية (خطيرة)</span>
                                                            @else
                                                                {{ $recall->recall_class }}
                                                            @endif
                                                        </p>
                                                        <p><strong>تاريخ البدء:</strong> {{ $recall->initiated_date ? $recall->initiated_date->format('Y-m-d') : 'غير محدد' }}</p>
                                                        <p><strong>تاريخ الإشعار:</strong> {{ $recall->notification_date ? $recall->notification_date->format('Y-m-d') : 'غير محدد' }}</p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <p><strong>الكمية المتأثرة:</strong> {{ number_format($recall->quantity_affected ?? 0) }} وحدة</p>
                                                        <p><strong>الكمية المستردة:</strong> {{ number_format($recall->quantity_recovered ?? 0) }} وحدة</p>
                                                        <p><strong>نسبة الاسترداد:</strong> {{ $recoveryPercentage }}%</p>
                                                        <p><strong>مستوى التوزيع:</strong> {{ $recall->distribution_level ?? 'غير محدد' }}</p>
                                                        <p><strong>السلطة التنظيمية:</strong> {{ $recall->regulatory_authority ?? 'غير محدد' }}</p>
                                                    </div>
                                                </div>
                                                @if($recall->reason)
                                                <div class="row">
                                                    <div class="col-12">
                                                        <p><strong>سبب الاستدعاء:</strong></p>
                                                        <div class="alert alert-warning">{{ $recall->reason }}</div>
                                                    </div>
                                                </div>
                                                @endif
                                                @if($recall->risk_assessment)
                                                <div class="row">
                                                    <div class="col-12">
                                                        <p><strong>تقييم المخاطر:</strong></p>
                                                        <div class="alert alert-danger">{{ $recall->risk_assessment }}</div>
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
                                <div class="modal fade" id="updateModal{{ $recall->id }}" tabindex="-1" role="dialog">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">تحديث حالة الاستدعاء</h5>
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
                                                            <option value="in_progress" {{ $recall->status == 'in_progress' ? 'selected' : '' }}>قيد التنفيذ</option>
                                                            <option value="ongoing" {{ $recall->status == 'ongoing' ? 'selected' : '' }}>مستمر</option>
                                                            <option value="completed" {{ $recall->status == 'completed' ? 'selected' : '' }}>مكتمل</option>
                                                            <option value="closed" {{ $recall->status == 'closed' ? 'selected' : '' }}>مغلق</option>
                                                            <option value="terminated" {{ $recall->status == 'terminated' ? 'selected' : '' }}>منتهي</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="quantity_recovered">الكمية المستردة</label>
                                                        <input type="number" class="form-control" name="quantity_recovered" 
                                                               value="{{ $recall->quantity_recovered }}" min="0" max="{{ $recall->quantity_affected }}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="completion_date">تاريخ الإنجاز</label>
                                                        <input type="date" class="form-control" name="completion_date" 
                                                               value="{{ $recall->completion_date ? $recall->completion_date->format('Y-m-d') : '' }}">
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

                                <!-- Effectiveness Report Modal -->
                                <div class="modal fade" id="reportModal{{ $recall->id }}" tabindex="-1" role="dialog">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">تقرير فعالية الاستدعاء</h5>
                                                <button type="button" class="close" data-dismiss="modal">
                                                    <span>&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <canvas id="recallChart{{ $recall->id }}" width="300" height="200"></canvas>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <h6>إحصائيات الاستدعاء</h6>
                                                        <ul class="list-unstyled">
                                                            <li><strong>الكمية المتأثرة:</strong> {{ number_format($recall->quantity_affected ?? 0) }}</li>
                                                            <li><strong>الكمية المستردة:</strong> {{ number_format($recall->quantity_recovered ?? 0) }}</li>
                                                            <li><strong>نسبة الاسترداد:</strong> {{ $recoveryPercentage }}%</li>
                                                            <li><strong>المدة:</strong> {{ $recall->recall_duration ?? 'غير محدد' }} يوم</li>
                                                        </ul>
                                                        
                                                        @if($recoveryPercentage >= 80)
                                                            <div class="alert alert-success">
                                                                <i class="fas fa-check-circle"></i>
                                                                فعالية ممتازة في الاستدعاء
                                                            </div>
                                                        @elseif($recoveryPercentage >= 50)
                                                            <div class="alert alert-warning">
                                                                <i class="fas fa-exclamation-triangle"></i>
                                                                فعالية متوسطة - تحتاج تحسين
                                                            </div>
                                                        @else
                                                            <div class="alert alert-danger">
                                                                <i class="fas fa-times-circle"></i>
                                                                فعالية منخفضة - تحتاج إجراءات عاجلة
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                                                <button type="button" class="btn btn-primary">
                                                    <i class="fas fa-download me-1"></i>
                                                    تحميل التقرير
                                                </button>
                                            </div>
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
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(document).ready(function() {
    $('#dataTable').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Arabic.json"
        },
        "order": [[ 0, "desc" ]], // Sort by recall number (descending)
        "pageLength": 25,
        "responsive": true,
        "columnDefs": [
            { "responsivePriority": 1, "targets": 0 },
            { "responsivePriority": 2, "targets": 2 },
            { "responsivePriority": 3, "targets": -1 }
        ]
    });

    // Initialize charts for each recall
    @foreach($recalls as $recall)
    @php
        $recoveryPercentage = $recall->quantity_affected > 0 ? round(($recall->quantity_recovered / $recall->quantity_affected) * 100, 1) : 0;
        $remainingPercentage = 100 - $recoveryPercentage;
    @endphp
    
    const ctx{{ $recall->id }} = document.getElementById('recallChart{{ $recall->id }}');
    if (ctx{{ $recall->id }}) {
        new Chart(ctx{{ $recall->id }}, {
            type: 'doughnut',
            data: {
                labels: ['مسترد', 'متبقي'],
                datasets: [{
                    data: [{{ $recoveryPercentage }}, {{ $remainingPercentage }}],
                    backgroundColor: [
                        @if($recoveryPercentage >= 80)
                            '#28a745',
                        @elseif($recoveryPercentage >= 50)
                            '#ffc107',
                        @else
                            '#dc3545',
                        @endif
                        '#e9ecef'
                    ],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                    },
                    title: {
                        display: true,
                        text: 'نسبة الاسترداد'
                    }
                }
            }
        });
    }
    @endforeach
});
</script>
@endpush
@endsection
