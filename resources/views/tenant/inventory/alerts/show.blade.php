@extends('layouts.modern')

@section('page-title', 'تنبيه المخزون')
@section('page-description', 'تفاصيل التنبيه')

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
                        <i class="{{ $alert->getAlertIcon() }}" style="font-size: 32px;"></i>
                    </div>
                    <div>
                        <h1 style="font-size: 32px; font-weight: 800; margin: 0; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                            {{ $alert->title }} 🚨
                        </h1>
                        <p style="font-size: 18px; margin: 5px 0 0 0; opacity: 0.9;">
                            {{ $alert->getAlertTypeLabel() }} - {{ $alert->getPriorityLabel() }}
                        </p>
                    </div>
                </div>
                
                <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <span style="background: {{ $alert->getPriorityColor() === 'danger' ? '#fee2e2' : ($alert->getPriorityColor() === 'warning' ? '#fef3c7' : '#dbeafe') }}; 
                                     color: {{ $alert->getPriorityColor() === 'danger' ? '#991b1b' : ($alert->getPriorityColor() === 'warning' ? '#92400e' : '#1e40af') }}; 
                                     padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                            {{ $alert->getPriorityLabel() }}
                        </span>
                    </div>
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <span style="background: {{ $alert->getStatusColor() === 'success' ? '#d1fae5' : ($alert->getStatusColor() === 'warning' ? '#fef3c7' : ($alert->getStatusColor() === 'danger' ? '#fee2e2' : '#f1f5f9')) }}; 
                                     color: {{ $alert->getStatusColor() === 'success' ? '#065f46' : ($alert->getStatusColor() === 'warning' ? '#92400e' : ($alert->getStatusColor() === 'danger' ? '#991b1b' : '#374151')) }}; 
                                     padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                            {{ $alert->getStatusLabel() }}
                        </span>
                    </div>
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-clock" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px;">{{ $alert->getTimeSinceTriggered() }}</span>
                    </div>
                </div>
            </div>
            
            <div style="display: flex; gap: 15px;">
                <a href="{{ route('tenant.inventory.alerts.index') }}" style="background: rgba(255,255,255,0.2); color: white; padding: 15px 25px; border-radius: 15px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3);">
                    <i class="fas fa-arrow-right"></i>
                    العودة للقائمة
                </a>
            </div>
        </div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 25px;">
    <!-- Alert Details -->
    <div class="content-card">
        <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-info-circle" style="color: #667eea; margin-left: 10px;"></i>
            تفاصيل التنبيه
        </h3>
        
        <!-- Alert Message -->
        <div style="margin-bottom: 25px; padding: 20px; background: #f8fafc; border-radius: 12px; border-right: 4px solid {{ $alert->getPriorityColor() === 'danger' ? '#ef4444' : ($alert->getPriorityColor() === 'warning' ? '#f59e0b' : '#3b82f6') }};">
            <h4 style="margin: 0 0 10px 0; color: #2d3748; font-size: 18px;">{{ $alert->title }}</h4>
            <p style="margin: 0; color: #4a5568; line-height: 1.6; font-size: 16px;">{{ $alert->message }}</p>
        </div>
        
        <!-- Basic Information -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            <div>
                <label style="font-weight: 600; color: #4a5568; margin-bottom: 5px; display: block;">نوع التنبيه</label>
                <div style="padding: 10px; background: #f8fafc; border-radius: 6px;">{{ $alert->getAlertTypeLabel() }}</div>
            </div>
            <div>
                <label style="font-weight: 600; color: #4a5568; margin-bottom: 5px; display: block;">مستوى الأولوية</label>
                <div style="padding: 10px; background: #f8fafc; border-radius: 6px;">
                    <span style="background: {{ $alert->getPriorityColor() === 'danger' ? '#fee2e2' : ($alert->getPriorityColor() === 'warning' ? '#fef3c7' : '#dbeafe') }}; 
                                 color: {{ $alert->getPriorityColor() === 'danger' ? '#991b1b' : ($alert->getPriorityColor() === 'warning' ? '#92400e' : '#1e40af') }}; 
                                 padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                        {{ $alert->getPriorityLabel() }}
                    </span>
                </div>
            </div>
            <div>
                <label style="font-weight: 600; color: #4a5568; margin-bottom: 5px; display: block;">تاريخ التنبيه</label>
                <div style="padding: 10px; background: #f8fafc; border-radius: 6px;">{{ $alert->triggered_at->format('Y-m-d H:i') }}</div>
            </div>
            <div>
                <label style="font-weight: 600; color: #4a5568; margin-bottom: 5px; display: block;">الحالة</label>
                <div style="padding: 10px; background: #f8fafc; border-radius: 6px;">
                    <span style="background: {{ $alert->getStatusColor() === 'success' ? '#d1fae5' : ($alert->getStatusColor() === 'warning' ? '#fef3c7' : ($alert->getStatusColor() === 'danger' ? '#fee2e2' : '#f1f5f9')) }}; 
                                 color: {{ $alert->getStatusColor() === 'success' ? '#065f46' : ($alert->getStatusColor() === 'warning' ? '#92400e' : ($alert->getStatusColor() === 'danger' ? '#991b1b' : '#374151')) }}; 
                                 padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                        {{ $alert->getStatusLabel() }}
                    </span>
                </div>
            </div>
        </div>
        
        <!-- Related Information -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            @if($alert->warehouse)
            <div>
                <label style="font-weight: 600; color: #4a5568; margin-bottom: 5px; display: block;">المستودع</label>
                <div style="padding: 10px; background: #f8fafc; border-radius: 6px;">{{ $alert->warehouse->name }} ({{ $alert->warehouse->code }})</div>
            </div>
            @endif
            
            @if($alert->product)
            <div>
                <label style="font-weight: 600; color: #4a5568; margin-bottom: 5px; display: block;">المنتج</label>
                <div style="padding: 10px; background: #f8fafc; border-radius: 6px;">{{ $alert->product->name }} ({{ $alert->product->code }})</div>
            </div>
            @endif
        </div>
        
        <!-- Additional Data -->
        @if($alert->alert_data && count($alert->alert_data) > 0)
        <div style="margin-bottom: 20px;">
            <label style="font-weight: 600; color: #4a5568; margin-bottom: 10px; display: block;">بيانات إضافية</label>
            <div style="padding: 15px; background: #f8fafc; border-radius: 6px;">
                @foreach($alert->alert_data as $key => $value)
                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px; padding-bottom: 8px; border-bottom: 1px solid #e2e8f0;">
                        <span style="font-weight: 600; color: #4a5568;">{{ ucfirst(str_replace('_', ' ', $key)) }}:</span>
                        <span style="color: #2d3748;">{{ is_array($value) ? json_encode($value) : $value }}</span>
                    </div>
                @endforeach
            </div>
        </div>
        @endif
        
        <!-- Resolution Notes -->
        @if($alert->resolution_notes)
        <div>
            <label style="font-weight: 600; color: #4a5568; margin-bottom: 5px; display: block;">ملاحظات الحل</label>
            <div style="padding: 15px; background: #f0fdf4; border-radius: 6px; line-height: 1.6; border-right: 4px solid #10b981;">{{ $alert->resolution_notes }}</div>
        </div>
        @endif
    </div>
    
    <!-- Alert Actions & Timeline -->
    <div class="content-card">
        <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-cogs" style="color: #667eea; margin-left: 10px;"></i>
            الإجراءات
        </h3>
        
        <!-- Actions -->
        @if($alert->status === 'active')
        <div style="margin-bottom: 25px;">
            <div style="display: flex; flex-direction: column; gap: 10px;">
                <form method="POST" action="{{ route('tenant.inventory.alerts.acknowledge', $alert) }}">
                    @csrf
                    <button type="submit" style="width: 100%; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; padding: 12px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px;">
                        <i class="fas fa-check"></i>
                        تأكيد التنبيه
                    </button>
                </form>
                
                <button onclick="showResolveModal()" style="width: 100%; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 12px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px;">
                    <i class="fas fa-check-circle"></i>
                    حل التنبيه
                </button>
                
                <form method="POST" action="{{ route('tenant.inventory.alerts.dismiss', $alert) }}">
                    @csrf
                    <button type="submit" style="width: 100%; background: #6b7280; color: white; padding: 12px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px;" onclick="return confirm('هل أنت متأكد من تجاهل هذا التنبيه؟')">
                        <i class="fas fa-times"></i>
                        تجاهل التنبيه
                    </button>
                </form>
            </div>
        </div>
        @elseif($alert->status === 'acknowledged')
        <div style="margin-bottom: 25px;">
            <button onclick="showResolveModal()" style="width: 100%; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 12px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px;">
                <i class="fas fa-check-circle"></i>
                حل التنبيه
            </button>
        </div>
        @endif
        
        <!-- Timeline -->
        <div>
            <h4 style="font-size: 16px; font-weight: 600; color: #2d3748; margin-bottom: 15px;">الجدول الزمني</h4>
            
            <div style="position: relative;">
                <!-- Timeline line -->
                <div style="position: absolute; right: 20px; top: 0; bottom: 0; width: 2px; background: #e2e8f0;"></div>
                
                <!-- Timeline items -->
                <div style="position: relative; padding-right: 50px; margin-bottom: 20px;">
                    <div style="position: absolute; right: 12px; top: 5px; width: 16px; height: 16px; background: #ef4444; border-radius: 50%; border: 3px solid white; box-shadow: 0 0 0 2px #ef4444;"></div>
                    <div style="background: #fee2e2; padding: 12px; border-radius: 8px;">
                        <div style="font-weight: 600; color: #991b1b; font-size: 14px;">تم إنشاء التنبيه</div>
                        <div style="font-size: 12px; color: #6b7280;">{{ $alert->triggered_at->format('Y-m-d H:i') }}</div>
                    </div>
                </div>
                
                @if($alert->acknowledged_at)
                <div style="position: relative; padding-right: 50px; margin-bottom: 20px;">
                    <div style="position: absolute; right: 12px; top: 5px; width: 16px; height: 16px; background: #f59e0b; border-radius: 50%; border: 3px solid white; box-shadow: 0 0 0 2px #f59e0b;"></div>
                    <div style="background: #fef3c7; padding: 12px; border-radius: 8px;">
                        <div style="font-weight: 600; color: #92400e; font-size: 14px;">تم تأكيد التنبيه</div>
                        <div style="font-size: 12px; color: #6b7280;">{{ $alert->acknowledged_at->format('Y-m-d H:i') }}</div>
                        @if($alert->acknowledgedBy)
                            <div style="font-size: 12px; color: #6b7280;">بواسطة: {{ $alert->acknowledgedBy->name }}</div>
                        @endif
                    </div>
                </div>
                @endif
                
                @if($alert->resolved_at)
                <div style="position: relative; padding-right: 50px;">
                    <div style="position: absolute; right: 12px; top: 5px; width: 16px; height: 16px; background: #10b981; border-radius: 50%; border: 3px solid white; box-shadow: 0 0 0 2px #10b981;"></div>
                    <div style="background: #d1fae5; padding: 12px; border-radius: 8px;">
                        <div style="font-weight: 600; color: #065f46; font-size: 14px;">تم حل التنبيه</div>
                        <div style="font-size: 12px; color: #6b7280;">{{ $alert->resolved_at->format('Y-m-d H:i') }}</div>
                        @if($alert->resolvedBy)
                            <div style="font-size: 12px; color: #6b7280;">بواسطة: {{ $alert->resolvedBy->name }}</div>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Resolve Modal -->
<div id="resolveModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 12px; padding: 30px; max-width: 500px; width: 90%;">
        <h3 style="margin: 0 0 20px 0; color: #2d3748;">حل التنبيه</h3>
        <form method="POST" action="{{ route('tenant.inventory.alerts.resolve', $alert) }}">
            @csrf
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">ملاحظات الحل</label>
                <textarea name="resolution_notes" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; height: 100px;" placeholder="اكتب ملاحظات حول كيفية حل هذا التنبيه..."></textarea>
            </div>
            <div style="display: flex; gap: 10px; justify-content: flex-end;">
                <button type="button" onclick="hideResolveModal()" style="background: #6b7280; color: white; padding: 10px 20px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                    إلغاء
                </button>
                <button type="submit" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 10px 20px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                    حل التنبيه
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function showResolveModal() {
    document.getElementById('resolveModal').style.display = 'flex';
}

function hideResolveModal() {
    document.getElementById('resolveModal').style.display = 'none';
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
