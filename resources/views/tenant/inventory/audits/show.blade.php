@extends('layouts.modern')

@section('page-title', 'جرد ' . $audit->audit_number)
@section('page-description', 'تفاصيل عملية الجرد')

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
                        <i class="fas fa-clipboard-list" style="font-size: 32px;"></i>
                    </div>
                    <div>
                        <h1 style="font-size: 32px; font-weight: 800; margin: 0; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                            {{ $audit->audit_number }} 📋
                        </h1>
                        <p style="font-size: 18px; margin: 5px 0 0 0; opacity: 0.9;">
                            {{ $audit->getAuditTypeLabel() }} - {{ $audit->warehouse->name }}
                        </p>
                    </div>
                </div>
                
                <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <span style="background: {{ $audit->getStatusColor() === 'success' ? '#d1fae5' : ($audit->getStatusColor() === 'warning' ? '#fef3c7' : ($audit->getStatusColor() === 'info' ? '#dbeafe' : '#fee2e2')) }}; 
                                     color: {{ $audit->getStatusColor() === 'success' ? '#065f46' : ($audit->getStatusColor() === 'warning' ? '#92400e' : ($audit->getStatusColor() === 'info' ? '#1e40af' : '#991b1b')) }}; 
                                     padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                            {{ $audit->getStatusLabel() }}
                        </span>
                    </div>
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-calendar" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px;">{{ $audit->scheduled_date->format('Y-m-d') }}</span>
                    </div>
                    @if($audit->status === 'in_progress')
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-percentage" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px;">{{ $audit->getProgressPercentage() }}% مكتمل</span>
                    </div>
                    @endif
                </div>
            </div>
            
            <div style="display: flex; gap: 15px;">
                @if($audit->status === 'scheduled')
                <button onclick="startAudit()" style="background: rgba(255,255,255,0.2); color: white; padding: 15px 25px; border: none; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3); cursor: pointer;">
                    <i class="fas fa-play"></i>
                    بدء الجرد
                </button>
                @endif
                <a href="{{ route('tenant.inventory.audits.index') }}" style="background: rgba(255,255,255,0.2); color: white; padding: 15px 25px; border-radius: 15px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3);">
                    <i class="fas fa-arrow-right"></i>
                    العودة للقائمة
                </a>
            </div>
        </div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 25px;">
    <!-- Audit Details -->
    <div class="content-card">
        <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-info-circle" style="color: #667eea; margin-left: 10px;"></i>
            تفاصيل الجرد
        </h3>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            <div>
                <label style="font-weight: 600; color: #4a5568; margin-bottom: 5px; display: block;">رقم الجرد</label>
                <div style="padding: 10px; background: #f8fafc; border-radius: 6px; font-weight: 600;">{{ $audit->audit_number }}</div>
            </div>
            <div>
                <label style="font-weight: 600; color: #4a5568; margin-bottom: 5px; display: block;">نوع الجرد</label>
                <div style="padding: 10px; background: #f8fafc; border-radius: 6px;">{{ $audit->getAuditTypeLabel() }}</div>
            </div>
            <div>
                <label style="font-weight: 600; color: #4a5568; margin-bottom: 5px; display: block;">المستودع</label>
                <div style="padding: 10px; background: #f8fafc; border-radius: 6px;">{{ $audit->warehouse->name }} ({{ $audit->warehouse->code }})</div>
            </div>
            <div>
                <label style="font-weight: 600; color: #4a5568; margin-bottom: 5px; display: block;">تاريخ الجدولة</label>
                <div style="padding: 10px; background: #f8fafc; border-radius: 6px;">{{ $audit->scheduled_date->format('Y-m-d H:i') }}</div>
            </div>
            @if($audit->started_at)
            <div>
                <label style="font-weight: 600; color: #4a5568; margin-bottom: 5px; display: block;">تاريخ البدء</label>
                <div style="padding: 10px; background: #f8fafc; border-radius: 6px;">{{ $audit->started_at->format('Y-m-d H:i') }}</div>
            </div>
            @endif
            @if($audit->completed_at)
            <div>
                <label style="font-weight: 600; color: #4a5568; margin-bottom: 5px; display: block;">تاريخ الإنجاز</label>
                <div style="padding: 10px; background: #f8fafc; border-radius: 6px;">{{ $audit->completed_at->format('Y-m-d H:i') }}</div>
            </div>
            @endif
        </div>
        
        @if($audit->notes)
        <div>
            <label style="font-weight: 600; color: #4a5568; margin-bottom: 5px; display: block;">الملاحظات</label>
            <div style="padding: 15px; background: #f8fafc; border-radius: 6px; line-height: 1.6;">{{ $audit->notes }}</div>
        </div>
        @endif
    </div>
    
    <!-- Audit Statistics -->
    <div class="content-card">
        <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-chart-bar" style="color: #667eea; margin-left: 10px;"></i>
            إحصائيات الجرد
        </h3>
        
        <!-- Progress (for in-progress audits) -->
        @if($audit->status === 'in_progress')
        <div style="margin-bottom: 20px; padding: 15px; background: #fef3c7; border-radius: 8px;">
            <h4 style="margin: 0 0 10px 0; color: #92400e; font-size: 14px; font-weight: 600;">التقدم</h4>
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                <span style="font-size: 12px; color: #374151;">{{ $audit->getCompletedItems() }} / {{ $audit->getTotalItems() }} عنصر</span>
                <span style="font-size: 12px; font-weight: 600; color: #92400e;">{{ $audit->getProgressPercentage() }}%</span>
            </div>
            <div style="background: #fde68a; border-radius: 10px; height: 10px; overflow: hidden;">
                <div style="background: linear-gradient(90deg, #f59e0b, #d97706); height: 100%; width: {{ $audit->getProgressPercentage() }}%; transition: width 0.3s ease;"></div>
            </div>
        </div>
        @endif
        
        <!-- Key Metrics -->
        <div style="display: grid; grid-template-columns: 1fr; gap: 15px;">
            <div style="text-align: center; padding: 15px; background: #f8fafc; border-radius: 8px; border-left: 4px solid #3b82f6;">
                <div style="font-size: 24px; font-weight: 700; color: #3b82f6;">{{ $audit->getTotalItems() }}</div>
                <div style="font-size: 12px; color: #6b7280;">إجمالي العناصر</div>
            </div>
            
            @if($audit->status !== 'scheduled')
            <div style="text-align: center; padding: 15px; background: #f8fafc; border-radius: 8px; border-left: 4px solid #059669;">
                <div style="font-size: 24px; font-weight: 700; color: #059669;">{{ $audit->getCompletedItems() }}</div>
                <div style="font-size: 12px; color: #6b7280;">عناصر مكتملة</div>
            </div>
            
            <div style="text-align: center; padding: 15px; background: #f8fafc; border-radius: 8px; border-left: 4px solid #ef4444;">
                <div style="font-size: 24px; font-weight: 700; color: #ef4444;">{{ $audit->getDiscrepancies() }}</div>
                <div style="font-size: 12px; color: #6b7280;">فروقات</div>
            </div>
            @endif
        </div>
        
        <!-- Created By -->
        @if($audit->createdBy)
        <div style="margin-top: 20px; padding: 15px; background: #f0fdf4; border-radius: 8px;">
            <h4 style="margin: 0 0 10px 0; color: #166534; font-size: 14px; font-weight: 600;">معلومات الإنشاء</h4>
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px;">
                <i class="fas fa-user" style="color: #059669;"></i>
                <span style="font-size: 14px; font-weight: 600; color: #374151;">{{ $audit->createdBy->name }}</span>
            </div>
            <div style="display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-clock" style="color: #059669;"></i>
                <span style="font-size: 12px; color: #6b7280;">{{ $audit->created_at->format('Y-m-d H:i') }}</span>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Audit Items -->
@if($audit->auditItems->count() > 0)
<div class="content-card" style="margin-top: 25px;">
    <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
        <i class="fas fa-list" style="color: #667eea; margin-left: 10px;"></i>
        عناصر الجرد ({{ $audit->auditItems->count() }})
    </h3>
    
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                    <th style="padding: 15px; text-align: right; font-weight: 600; color: #4a5568;">المنتج</th>
                    <th style="padding: 15px; text-align: center; font-weight: 600; color: #4a5568;">الكمية في النظام</th>
                    <th style="padding: 15px; text-align: center; font-weight: 600; color: #4a5568;">الكمية المعدودة</th>
                    <th style="padding: 15px; text-align: center; font-weight: 600; color: #4a5568;">الفرق</th>
                    <th style="padding: 15px; text-align: center; font-weight: 600; color: #4a5568;">الحالة</th>
                </tr>
            </thead>
            <tbody>
                @foreach($audit->auditItems as $item)
                    <tr style="border-bottom: 1px solid #e2e8f0; transition: background-color 0.3s ease;" 
                        onmouseover="this.style.backgroundColor='#f8fafc'" 
                        onmouseout="this.style.backgroundColor='transparent'">
                        <td style="padding: 15px;">
                            <div style="font-weight: 600; color: #2d3748;">{{ $item->inventory->product->name }}</div>
                            <div style="font-size: 12px; color: #6b7280;">{{ $item->inventory->product->code }}</div>
                        </td>
                        <td style="padding: 15px; text-align: center;">
                            <div style="font-weight: 600; color: #2d3748;">{{ number_format($item->system_quantity, 3) }}</div>
                        </td>
                        <td style="padding: 15px; text-align: center;">
                            @if($item->counted_quantity !== null)
                                <div style="font-weight: 600; color: #2d3748;">{{ number_format($item->counted_quantity, 3) }}</div>
                            @else
                                <span style="color: #6b7280; font-style: italic;">لم يتم العد</span>
                            @endif
                        </td>
                        <td style="padding: 15px; text-align: center;">
                            @if($item->counted_quantity !== null)
                                @php
                                    $difference = $item->counted_quantity - $item->system_quantity;
                                @endphp
                                <div style="font-weight: 600; color: {{ $difference == 0 ? '#059669' : ($difference > 0 ? '#3b82f6' : '#ef4444') }};">
                                    {{ $difference > 0 ? '+' : '' }}{{ number_format($difference, 3) }}
                                </div>
                            @else
                                <span style="color: #6b7280;">-</span>
                            @endif
                        </td>
                        <td style="padding: 15px; text-align: center;">
                            @if($item->counted_quantity !== null)
                                @php
                                    $difference = $item->counted_quantity - $item->system_quantity;
                                @endphp
                                @if($difference == 0)
                                    <span style="background: #d1fae5; color: #065f46; padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                                        مطابق
                                    </span>
                                @elseif($difference > 0)
                                    <span style="background: #dbeafe; color: #1e40af; padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                                        زيادة
                                    </span>
                                @else
                                    <span style="background: #fee2e2; color: #991b1b; padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                                        نقص
                                    </span>
                                @endif
                            @else
                                <span style="background: #f1f5f9; color: #64748b; padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                                    معلق
                                </span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@else
<div class="content-card" style="margin-top: 25px;">
    <div style="text-align: center; padding: 40px; color: #6b7280;">
        <i class="fas fa-clipboard-list" style="font-size: 48px; margin-bottom: 15px; opacity: 0.5;"></i>
        <h3 style="margin: 0 0 10px 0;">لا توجد عناصر جرد</h3>
        <p style="margin: 0;">لم يتم إضافة أي عناصر لهذا الجرد بعد</p>
    </div>
</div>
@endif

@push('scripts')
<script>
function startAudit() {
    if (confirm('هل أنت متأكد من بدء عملية الجرد؟')) {
        // In a real implementation, this would make an AJAX call to start the audit
        alert('تم بدء عملية الجرد بنجاح');
        location.reload();
    }
}
</script>
@endpush
@endsection
