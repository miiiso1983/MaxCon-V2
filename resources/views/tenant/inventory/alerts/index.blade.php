@extends('layouts.modern')

@section('page-title', 'تنبيهات المخزون')
@section('page-description', 'إدارة تنبيهات وإشعارات المخزون')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/inventory-alerts.css') }}">
@endpush

@section('content')
<!-- Page Header -->
<div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 20px; padding: 30px; margin-bottom: 30px; color: white; position: relative; overflow: hidden;">
    <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
    <div style="position: absolute; bottom: -30px; left: -30px; width: 150px; height: 150px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>
    
    <div style="position: relative; z-index: 2;">
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    <div style="background: rgba(255,255,255,0.2); border-radius: 15px; padding: 15px; margin-left: 20px;">
                        <i class="fas fa-exclamation-triangle" style="font-size: 32px;"></i>
                    </div>
                    <div>
                        <h1 style="font-size: 32px; font-weight: 800; margin: 0; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                            تنبيهات المخزون 🚨
                        </h1>
                        <p style="font-size: 18px; margin: 5px 0 0 0; opacity: 0.9;">
                            إدارة تنبيهات وإشعارات المخزون
                        </p>
                    </div>
                </div>
                
                <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-bell" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px; font-weight: 600;">{{ $stats['total_alerts'] }} تنبيه</span>
                    </div>
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-exclamation-circle" style="margin-left: 8px; color: #f87171;"></i>
                        <span style="font-size: 14px;">{{ $stats['active_alerts'] }} نشط</span>
                    </div>
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-times-circle" style="margin-left: 8px; color: #ef4444;"></i>
                        <span style="font-size: 14px;">{{ $stats['critical_alerts'] }} حرج</span>
                    </div>
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-check-circle" style="margin-left: 8px; color: #34d399;"></i>
                        <span style="font-size: 14px;">{{ $stats['resolved_today'] }} محلول اليوم</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="content-card" style="margin-bottom: 25px;">
    <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
        <i class="fas fa-filter" style="color: #667eea; margin-left: 10px;"></i>
        فلترة وبحث
    </h3>
    
    <form method="GET" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; align-items: end;">
        <div>
            <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #4a5568;">المستودع</label>
            <select name="warehouse_id" data-custom-select data-placeholder="اختر المستودع..." data-searchable="true" style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 8px;">
                <option value="">جميع المستودعات</option>
                @foreach($warehouses as $warehouse)
                    <option value="{{ $warehouse->id }}" {{ request('warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                        {{ $warehouse->name }}
                    </option>
                @endforeach
            </select>
        </div>
        
        <div>
            <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #4a5568;">نوع التنبيه</label>
            <select name="alert_type" style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 8px;">
                <option value="">جميع الأنواع</option>
                <option value="low_stock" {{ request('alert_type') === 'low_stock' ? 'selected' : '' }}>مخزون منخفض</option>
                <option value="out_of_stock" {{ request('alert_type') === 'out_of_stock' ? 'selected' : '' }}>نفاد المخزون</option>
                <option value="expiring_soon" {{ request('alert_type') === 'expiring_soon' ? 'selected' : '' }}>انتهاء صلاحية قريب</option>
                <option value="expired" {{ request('alert_type') === 'expired' ? 'selected' : '' }}>منتهي الصلاحية</option>
                <option value="damaged" {{ request('alert_type') === 'damaged' ? 'selected' : '' }}>تلف</option>
                <option value="overstock" {{ request('alert_type') === 'overstock' ? 'selected' : '' }}>مخزون زائد</option>
            </select>
        </div>
        
        <div>
            <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #4a5568;">الأولوية</label>
            <select name="priority" style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 8px;">
                <option value="">جميع الأولويات</option>
                <option value="low" {{ request('priority') === 'low' ? 'selected' : '' }}>منخفض</option>
                <option value="medium" {{ request('priority') === 'medium' ? 'selected' : '' }}>متوسط</option>
                <option value="high" {{ request('priority') === 'high' ? 'selected' : '' }}>عالي</option>
                <option value="critical" {{ request('priority') === 'critical' ? 'selected' : '' }}>حرج</option>
            </select>
        </div>
        
        <div>
            <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #4a5568;">الحالة</label>
            <select name="status" style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 8px;">
                <option value="">جميع الحالات</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>نشط</option>
                <option value="acknowledged" {{ request('status') === 'acknowledged' ? 'selected' : '' }}>مؤكد</option>
                <option value="resolved" {{ request('status') === 'resolved' ? 'selected' : '' }}>محلول</option>
                <option value="dismissed" {{ request('status') === 'dismissed' ? 'selected' : '' }}>مرفوض</option>
            </select>
        </div>
        
        <div style="display: flex; gap: 10px;">
            <button type="submit" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 10px 20px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-search"></i>
                بحث
            </button>
            <a href="{{ route('tenant.inventory.alerts.index') }}" style="background: #6b7280; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-times"></i>
                إلغاء
            </a>
        </div>
    </form>
</div>

<!-- Alerts List -->
<div class="content-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin: 0; display: flex; align-items: center;">
            <i class="fas fa-list" style="color: #667eea; margin-left: 10px;"></i>
            قائمة التنبيهات ({{ $alerts->total() }})
        </h3>
    </div>
    
    @if($alerts->count() > 0)
        <div style="display: grid; grid-template-columns: 1fr; gap: 15px;">
            @foreach($alerts as $alert)
                @php
                    $priorityColor = $alert->getPriorityColor();
                    $statusColor = $alert->getStatusColor();
                    $borderColor = $priorityColor === 'danger' ? '#ef4444' : ($priorityColor === 'warning' ? '#f59e0b' : '#3b82f6');
                    $iconColor = $priorityColor === 'danger' ? '#ef4444' : ($priorityColor === 'warning' ? '#f59e0b' : '#3b82f6');
                    $badgeBackground = $priorityColor === 'danger' ? '#fee2e2' : ($priorityColor === 'warning' ? '#fef3c7' : '#dbeafe');
                    $badgeColor = $priorityColor === 'danger' ? '#991b1b' : ($priorityColor === 'warning' ? '#92400e' : '#1e40af');
                    $statusBackground = $statusColor === 'success' ? '#d1fae5' : ($statusColor === 'warning' ? '#fef3c7' : ($statusColor === 'danger' ? '#fee2e2' : '#f1f5f9'));
                    $statusTextColor = $statusColor === 'success' ? '#065f46' : ($statusColor === 'warning' ? '#92400e' : ($statusColor === 'danger' ? '#991b1b' : '#374151'));
                @endphp
                <div class="alert-card priority-{{ $priorityColor }}"
                    
                    <div class="alert-header">
                        <div class="alert-content">
                            <div class="alert-title-row">
                                <i class="{{ $alert->getAlertIcon() }} alert-icon {{ $priorityColor }}"></i>
                                <h4 class="alert-title">{{ $alert->title }}</h4>
                            </div>
                            
                            <p class="alert-message">{{ $alert->message }}</p>

                            <div class="alert-details">
                                @if($alert->warehouse)
                                    <div style="display: flex; align-items: center; gap: 5px;">
                                        <i class="fas fa-warehouse"></i>
                                        <span>{{ $alert->warehouse->name }}</span>
                                    </div>
                                @endif
                                
                                @if($alert->product)
                                    <div style="display: flex; align-items: center; gap: 5px;">
                                        <i class="fas fa-box"></i>
                                        <span>{{ $alert->product->name }}</span>
                                    </div>
                                @endif
                                
                                <div style="display: flex; align-items: center; gap: 5px;">
                                    <i class="fas fa-clock"></i>
                                    <span>{{ $alert->getTimeSinceTriggered() }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="alert-badges">
                            <!-- Priority Badge -->
                            <span class="badge priority-{{ $priorityColor }}">
                                {{ $alert->getPriorityLabel() }}
                            </span>

                            <!-- Status Badge -->
                            <span class="badge status-{{ $statusColor }}">
                                {{ $alert->getStatusLabel() }}
                            </span>
                            
                            <!-- Type Badge -->
                            <span class="badge" style="background: #f3e8ff; color: #7c3aed;">
                                {{ $alert->getAlertTypeLabel() }}
                            </span>
                        </div>
                    </div>
                    
                    <!-- Actions -->
                    @if($alert->status === 'active')
                        <div class="alert-actions">
                            <a href="{{ route('tenant.inventory.alerts.show', $alert) }}" class="btn btn-primary">
                                <i class="fas fa-eye"></i> عرض
                            </a>

                            <form method="POST" action="{{ route('tenant.inventory.alerts.acknowledge', $alert) }}" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-warning">
                                    <i class="fas fa-check"></i> تأكيد
                                </button>
                            </form>

                            <button onclick="showResolveModal('{{ $alert->id }}')" class="btn btn-success">
                                <i class="fas fa-check-circle"></i> حل
                            </button>

                            <form method="POST" action="{{ route('tenant.inventory.alerts.dismiss', $alert) }}" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-secondary" onclick="return confirm('هل أنت متأكد من تجاهل هذا التنبيه؟')">
                                    <i class="fas fa-times"></i> تجاهل
                                </button>
                            </form>
                        </div>
                    @elseif($alert->status === 'acknowledged')
                        <div class="alert-actions">
                            <a href="{{ route('tenant.inventory.alerts.show', $alert) }}" class="btn btn-primary">
                                <i class="fas fa-eye"></i> عرض
                            </a>

                            <button onclick="showResolveModal('{{ $alert->id }}')" class="btn btn-success">
                                <i class="fas fa-check-circle"></i> حل
                            </button>
                        </div>
                    @else
                        <div class="alert-actions">
                            <a href="{{ route('tenant.inventory.alerts.show', $alert) }}" class="btn btn-primary">
                                <i class="fas fa-eye"></i> عرض
                            </a>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
        
        <!-- Pagination -->
        <div style="margin-top: 30px;">
            {{ $alerts->links() }}
        </div>
    @else
        <div style="text-align: center; padding: 40px; color: #6b7280;">
            <i class="fas fa-bell-slash" style="font-size: 48px; margin-bottom: 15px; opacity: 0.5;"></i>
            <h3 style="margin: 0 0 10px 0;">لا توجد تنبيهات</h3>
            <p style="margin: 0;">لم يتم العثور على أي تنبيهات تطابق معايير البحث</p>
        </div>
    @endif
</div>

<!-- Resolve Modal -->
<div id="resolveModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">حل التنبيه</h3>
            <button type="button" class="close-btn" onclick="hideResolveModal()">×</button>
        </div>
        <form id="resolveForm" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">ملاحظات الحل</label>
                <textarea name="resolution_notes" class="form-input form-textarea" placeholder="اكتب ملاحظات حول كيفية حل هذا التنبيه..."></textarea>
            </div>
            <div class="modal-actions">
                <button type="button" onclick="hideResolveModal()" class="btn btn-secondary">
                    إلغاء
                </button>
                <button type="submit" class="btn btn-success">
                    حل التنبيه
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function showResolveModal(alertId) {
    const modal = document.getElementById('resolveModal');
    const form = document.getElementById('resolveForm');
    form.action = `/tenant/inventory/alerts/${alertId}/resolve`;
    modal.style.display = 'flex';
}

function hideResolveModal() {
    const modal = document.getElementById('resolveModal');
    modal.style.display = 'none';
}

// Close modal when clicking outside
document.getElementById('resolveModal').addEventListener('click', function(e) {
    if (e.target === this) {
        hideResolveModal();
    }
});
</script>
@endpush
@endsection
