@extends('layouts.tenant')

@section('title', 'تفاصيل التفتيش')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 text-gray-800">
                        <i class="fas fa-search text-primary me-2"></i>
                        تفاصيل التفتيش
                    </h1>
                    <p class="text-muted mb-0">{{ $inspection->inspection_title }}</p>
                </div>
                <div>
                    <a href="{{ route('tenant.inventory.regulatory.inspections.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i>
                        العودة للقائمة
                    </a>
                    <a href="{{ route('tenant.inventory.regulatory.inspections.edit', $inspection->id) }}" class="btn btn-primary">
                        <i class="fas fa-edit me-1"></i>
                        تعديل
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Inspection Status Alert -->
    <div class="row mb-4">
        <div class="col-12">
            @php
                $statusClass = match($inspection->inspection_status) {
                    'completed' => 'success',
                    'in_progress' => 'info',
                    'scheduled' => 'warning',
                    'cancelled' => 'secondary',
                    'postponed' => 'danger',
                    default => 'light'
                };
                $statusIcon = match($inspection->inspection_status) {
                    'completed' => 'check-circle',
                    'in_progress' => 'clock',
                    'scheduled' => 'calendar-alt',
                    'cancelled' => 'times-circle',
                    'postponed' => 'pause-circle',
                    default => 'question-circle'
                };
            @endphp
            <div class="alert alert-{{ $statusClass }}" role="alert">
                <div class="d-flex align-items-center">
                    <i class="fas fa-{{ $statusIcon }} fa-2x me-3"></i>
                    <div>
                        <h4 class="alert-heading mb-1">حالة التفتيش: {{ $inspection->getInspectionStatusLabel() }}</h4>
                        <p class="mb-0">
                            @if($inspection->inspection_status === 'completed')
                                تم إنجاز التفتيش بنجاح في {{ $inspection->completion_date?->format('Y-m-d') }}
                            @elseif($inspection->inspection_status === 'in_progress')
                                التفتيش قيد التنفيذ حالياً
                            @elseif($inspection->inspection_status === 'scheduled')
                                التفتيش مجدول في {{ $inspection->scheduled_date->format('Y-m-d') }}
                            @elseif($inspection->inspection_status === 'cancelled')
                                تم إلغاء التفتيش
                            @elseif($inspection->inspection_status === 'postponed')
                                تم تأجيل التفتيش
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="row">
        <!-- Basic Information -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle me-2"></i>
                        المعلومات الأساسية
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="font-weight-bold text-gray-700">عنوان التفتيش:</label>
                                <p class="text-gray-900">{{ $inspection->inspection_title }}</p>
                            </div>
                            <div class="form-group mb-3">
                                <label class="font-weight-bold text-gray-700">نوع التفتيش:</label>
                                <p class="text-gray-900">
                                    <span class="badge badge-info">{{ $inspection->getInspectionTypeLabel() }}</span>
                                </p>
                            </div>
                            <div class="form-group mb-3">
                                <label class="font-weight-bold text-gray-700">اسم المفتش:</label>
                                <p class="text-gray-900">{{ $inspection->inspector_name }}</p>
                            </div>
                            <div class="form-group mb-3">
                                <label class="font-weight-bold text-gray-700">الجهة المفتشة:</label>
                                <p class="text-gray-900">{{ $inspection->inspection_authority }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="font-weight-bold text-gray-700">التاريخ المجدول:</label>
                                <p class="text-gray-900">
                                    <i class="fas fa-calendar-alt text-primary me-1"></i>
                                    {{ $inspection->scheduled_date->format('Y-m-d') }}
                                </p>
                            </div>
                            @if($inspection->completion_date)
                            <div class="form-group mb-3">
                                <label class="font-weight-bold text-gray-700">تاريخ الإنجاز:</label>
                                <p class="text-gray-900">
                                    <i class="fas fa-check-circle text-success me-1"></i>
                                    {{ $inspection->completion_date->format('Y-m-d') }}
                                </p>
                            </div>
                            @endif
                            <div class="form-group mb-3">
                                <label class="font-weight-bold text-gray-700">اسم المنشأة:</label>
                                <p class="text-gray-900">{{ $inspection->facility_name }}</p>
                            </div>
                            <div class="form-group mb-3">
                                <label class="font-weight-bold text-gray-700">عنوان المنشأة:</label>
                                <p class="text-gray-900">{{ $inspection->facility_address }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Inspection Details -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-clipboard-list me-2"></i>
                        تفاصيل التفتيش
                    </h6>
                </div>
                <div class="card-body">
                    @if($inspection->scope_of_inspection)
                    <div class="form-group mb-4">
                        <label class="font-weight-bold text-gray-700">نطاق التفتيش:</label>
                        <div class="bg-light p-3 rounded">
                            <p class="mb-0">{{ $inspection->scope_of_inspection }}</p>
                        </div>
                    </div>
                    @endif

                    @if($inspection->findings)
                    <div class="form-group mb-4">
                        <label class="font-weight-bold text-gray-700">النتائج والملاحظات:</label>
                        <div class="bg-light p-3 rounded">
                            <p class="mb-0">{{ $inspection->findings }}</p>
                        </div>
                    </div>
                    @endif

                    @if($inspection->recommendations)
                    <div class="form-group mb-4">
                        <label class="font-weight-bold text-gray-700">التوصيات:</label>
                        <div class="bg-warning-light p-3 rounded border-left-warning">
                            <p class="mb-0">{{ $inspection->recommendations }}</p>
                        </div>
                    </div>
                    @endif

                    @if($inspection->notes)
                    <div class="form-group mb-0">
                        <label class="font-weight-bold text-gray-700">ملاحظات إضافية:</label>
                        <div class="bg-light p-3 rounded">
                            <p class="mb-0">{{ $inspection->notes }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar Information -->
        <div class="col-lg-4">
            <!-- Status Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-bar me-2"></i>
                        حالة التفتيش
                    </h6>
                </div>
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-{{ $statusIcon }} fa-3x text-{{ $statusClass }}"></i>
                    </div>
                    <h5 class="text-{{ $statusClass }}">{{ $inspection->getInspectionStatusLabel() }}</h5>
                    
                    @if($inspection->compliance_rating)
                    <div class="mt-4">
                        <label class="font-weight-bold text-gray-700">تقييم الامتثال:</label>
                        @php
                            $ratingClass = match($inspection->compliance_rating) {
                                'excellent' => 'success',
                                'good' => 'info',
                                'satisfactory' => 'warning',
                                'needs_improvement' => 'warning',
                                'non_compliant' => 'danger',
                                default => 'secondary'
                            };
                        @endphp
                        <div class="mt-2">
                            <span class="badge badge-{{ $ratingClass }} badge-lg">
                                {{ $inspection->getComplianceRatingLabel() }}
                            </span>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Follow-up Information -->
            @if($inspection->follow_up_required)
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        متابعة مطلوبة
                    </h6>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <i class="fas fa-calendar-check fa-2x text-warning mb-3"></i>
                        <p class="text-gray-700">هذا التفتيش يتطلب متابعة</p>
                        @if($inspection->follow_up_date)
                        <p class="font-weight-bold text-warning">
                            تاريخ المتابعة: {{ $inspection->follow_up_date->format('Y-m-d') }}
                        </p>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <!-- Timeline -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-history me-2"></i>
                        الجدول الزمني
                    </h6>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-primary"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">تم إنشاء التفتيش</h6>
                                <p class="timeline-text">{{ $inspection->created_at->format('Y-m-d H:i') }}</p>
                            </div>
                        </div>
                        
                        <div class="timeline-item">
                            <div class="timeline-marker bg-warning"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">التاريخ المجدول</h6>
                                <p class="timeline-text">{{ $inspection->scheduled_date->format('Y-m-d') }}</p>
                            </div>
                        </div>
                        
                        @if($inspection->completion_date)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">تم الإنجاز</h6>
                                <p class="timeline-text">{{ $inspection->completion_date->format('Y-m-d') }}</p>
                            </div>
                        </div>
                        @endif
                        
                        @if($inspection->follow_up_required && $inspection->follow_up_date)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-info"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">متابعة مطلوبة</h6>
                                <p class="timeline-text">{{ $inspection->follow_up_date->format('Y-m-d') }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-cogs me-2"></i>
                        الإجراءات
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('tenant.inventory.regulatory.inspections.edit', $inspection->id) }}" 
                           class="btn btn-primary btn-sm">
                            <i class="fas fa-edit me-1"></i>
                            تعديل التفتيش
                        </a>
                        
                        @if($inspection->inspection_status !== 'completed')
                        <button type="button" class="btn btn-success btn-sm" 
                                data-toggle="modal" data-target="#completeModal">
                            <i class="fas fa-check me-1"></i>
                            إنهاء التفتيش
                        </button>
                        @endif
                        
                        <button type="button" class="btn btn-info btn-sm" onclick="window.print()">
                            <i class="fas fa-print me-1"></i>
                            طباعة التقرير
                        </button>
                        
                        <button type="button" class="btn btn-danger btn-sm" 
                                data-toggle="modal" data-target="#deleteModal">
                            <i class="fas fa-trash me-1"></i>
                            حذف التفتيش
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Complete Inspection Modal -->
<div class="modal fade" id="completeModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">إنهاء التفتيش</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="{{ route('tenant.inventory.regulatory.inspections.update', $inspection->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <input type="hidden" name="inspection_status" value="completed">
                    <input type="hidden" name="completion_date" value="{{ now()->format('Y-m-d') }}">
                    
                    <div class="form-group">
                        <label for="compliance_rating">تقييم الامتثال</label>
                        <select class="form-control" name="compliance_rating" required>
                            <option value="">اختر التقييم</option>
                            <option value="excellent">ممتاز</option>
                            <option value="good">جيد</option>
                            <option value="satisfactory">مرضي</option>
                            <option value="needs_improvement">يحتاج تحسين</option>
                            <option value="non_compliant">غير ملتزم</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="findings">النتائج النهائية</label>
                        <textarea class="form-control" name="findings" rows="3" 
                                  placeholder="ملخص نتائج التفتيش...">{{ $inspection->findings }}</textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="recommendations">التوصيات</label>
                        <textarea class="form-control" name="recommendations" rows="3" 
                                  placeholder="التوصيات والإجراءات المطلوبة...">{{ $inspection->recommendations }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check me-1"></i>
                        إنهاء التفتيش
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">تأكيد الحذف</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>هل أنت متأكد من حذف هذا التفتيش؟</p>
                <p class="text-danger"><strong>تحذير:</strong> هذا الإجراء لا يمكن التراجع عنه.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                <form action="{{ route('tenant.inventory.regulatory.inspections.destroy', $inspection->id) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-1"></i>
                        حذف
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e3e6f0;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -22px;
    top: 0;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 2px solid #fff;
    box-shadow: 0 0 0 2px #e3e6f0;
}

.timeline-content {
    padding-left: 15px;
}

.timeline-title {
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 5px;
}

.timeline-text {
    font-size: 12px;
    color: #6c757d;
    margin-bottom: 0;
}

.bg-warning-light {
    background-color: #fff3cd;
}

.border-left-warning {
    border-left: 4px solid #ffc107;
}

.badge-lg {
    font-size: 14px;
    padding: 8px 12px;
}
</style>
@endpush
@endsection
