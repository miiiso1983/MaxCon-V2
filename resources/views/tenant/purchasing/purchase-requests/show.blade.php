@extends('layouts.modern')

@section('page-title', 'طلب الشراء: ' . $purchaseRequest->request_number)
@section('page-description', 'تفاصيل طلب الشراء')

@section('content')
<!-- Page Header -->
<div style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); border-radius: 20px; padding: 30px; margin-bottom: 30px; color: white; position: relative; overflow: hidden;">
    <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
    <div style="position: absolute; bottom: -30px; left: -30px; width: 150px; height: 150px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>
    
    <div style="position: relative; z-index: 2;">
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    <div style="background: rgba(255,255,255,0.2); border-radius: 15px; padding: 15px; margin-left: 20px;">
                        <i class="fas fa-file-alt" style="font-size: 32px;"></i>
                    </div>
                    <div>
                        <h1 style="font-size: 32px; font-weight: 800; margin: 0; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                            {{ $purchaseRequest->request_number }} 📋
                        </h1>
                        <p style="font-size: 18px; margin: 5px 0 0 0; opacity: 0.9;">
                            {{ $purchaseRequest->title }}
                        </p>
                    </div>
                </div>
                
                <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                    @php
                        $statusColors = [
                            'draft' => ['bg' => 'rgba(107, 114, 128, 0.2)', 'text' => 'white'],
                            'pending' => ['bg' => 'rgba(245, 158, 11, 0.2)', 'text' => 'white'],
                            'approved' => ['bg' => 'rgba(16, 185, 129, 0.2)', 'text' => 'white'],
                            'rejected' => ['bg' => 'rgba(239, 68, 68, 0.2)', 'text' => 'white'],
                            'cancelled' => ['bg' => 'rgba(107, 114, 128, 0.2)', 'text' => 'white'],
                            'completed' => ['bg' => 'rgba(59, 130, 246, 0.2)', 'text' => 'white'],
                        ];
                        $status = $statusColors[$purchaseRequest->status] ?? ['bg' => 'rgba(107, 114, 128, 0.2)', 'text' => 'white'];
                    @endphp
                    <div style="background: {{ $status['bg'] }}; border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3);">
                        <span style="font-size: 14px; font-weight: 600; color: {{ $status['text'] }};">{{ $purchaseRequest->status_label }}</span>
                    </div>
                    
                    @if($purchaseRequest->is_urgent)
                        <div style="background: rgba(239, 68, 68, 0.2); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3);">
                            <i class="fas fa-exclamation-triangle" style="margin-left: 8px;"></i>
                            <span style="font-size: 14px; font-weight: 600;">عاجل</span>
                        </div>
                    @endif
                    
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-flag" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px;">{{ $purchaseRequest->priority_label }}</span>
                    </div>
                </div>
            </div>
            
            <div style="display: flex; gap: 15px;">
                @if($purchaseRequest->status === 'pending')
                    <button onclick="showApprovalModal()" style="background: rgba(16, 185, 129, 0.2); color: white; padding: 15px 25px; border: none; border-radius: 15px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3);">
                        <i class="fas fa-check"></i>
                        اعتماد
                    </button>
                    <button onclick="showRejectionModal()" style="background: rgba(239, 68, 68, 0.2); color: white; padding: 15px 25px; border: none; border-radius: 15px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3);">
                        <i class="fas fa-times"></i>
                        رفض
                    </button>
                @endif
                
                <a href="{{ route('tenant.purchasing.purchase-requests.index') }}" style="background: rgba(255,255,255,0.2); color: white; padding: 15px 25px; border-radius: 15px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3);">
                    <i class="fas fa-arrow-right"></i>
                    العودة للقائمة
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Request Details -->
<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 25px; margin-bottom: 30px;">
    <!-- Main Details -->
    <div class="content-card">
        <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-info-circle" style="color: #3b82f6; margin-left: 10px;"></i>
            تفاصيل الطلب
        </h3>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            <div>
                <label style="font-size: 14px; font-weight: 600; color: #6b7280; margin-bottom: 5px; display: block;">رقم الطلب</label>
                <div style="font-size: 16px; font-weight: 600; color: #2d3748;">{{ $purchaseRequest->request_number }}</div>
            </div>
            
            <div>
                <label style="font-size: 14px; font-weight: 600; color: #6b7280; margin-bottom: 5px; display: block;">تاريخ الإنشاء</label>
                <div style="font-size: 16px; color: #2d3748;">{{ $purchaseRequest->created_at->format('Y-m-d H:i') }}</div>
            </div>
        </div>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            <div>
                <label style="font-size: 14px; font-weight: 600; color: #6b7280; margin-bottom: 5px; display: block;">التاريخ المطلوب</label>
                <div style="font-size: 16px; color: #2d3748;">{{ $purchaseRequest->required_date->format('Y-m-d') }}</div>
                @php
                    $daysLeft = now()->diffInDays($purchaseRequest->required_date, false);
                @endphp
                @if($daysLeft < 0)
                    <div style="font-size: 12px; color: #ef4444;">متأخر {{ abs($daysLeft) }} يوم</div>
                @elseif($daysLeft <= 7)
                    <div style="font-size: 12px; color: #f59e0b;">{{ $daysLeft }} يوم متبقي</div>
                @else
                    <div style="font-size: 12px; color: #6b7280;">{{ $daysLeft }} يوم متبقي</div>
                @endif
            </div>
            
            <div>
                <label style="font-size: 14px; font-weight: 600; color: #6b7280; margin-bottom: 5px; display: block;">القيمة المقدرة</label>
                <div style="font-size: 16px; font-weight: 600; color: #1e40af;">{{ number_format($purchaseRequest->estimated_total, 2) }} د.ع</div>
            </div>
        </div>
        
        @if($purchaseRequest->description)
            <div style="margin-bottom: 20px;">
                <label style="font-size: 14px; font-weight: 600; color: #6b7280; margin-bottom: 5px; display: block;">الوصف</label>
                <div style="font-size: 16px; color: #2d3748; line-height: 1.6;">{{ $purchaseRequest->description }}</div>
            </div>
        @endif
        
        @if($purchaseRequest->justification)
            <div style="margin-bottom: 20px;">
                <label style="font-size: 14px; font-weight: 600; color: #6b7280; margin-bottom: 5px; display: block;">مبرر الطلب</label>
                <div style="font-size: 16px; color: #2d3748; line-height: 1.6;">{{ $purchaseRequest->justification }}</div>
            </div>
        @endif
        
        @if($purchaseRequest->special_instructions)
            <div>
                <label style="font-size: 14px; font-weight: 600; color: #6b7280; margin-bottom: 5px; display: block;">تعليمات خاصة</label>
                <div style="font-size: 16px; color: #2d3748; line-height: 1.6;">{{ $purchaseRequest->special_instructions }}</div>
            </div>
        @endif
    </div>
    
    <!-- Status & Actions -->
    <div class="content-card">
        <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-user" style="color: #3b82f6; margin-left: 10px;"></i>
            معلومات الطلب
        </h3>
        
        <div style="margin-bottom: 20px;">
            <label style="font-size: 14px; font-weight: 600; color: #6b7280; margin-bottom: 5px; display: block;">مقدم الطلب</label>
            <div style="display: flex; align-items: center; gap: 10px;">
                <div style="background: #3b82f6; color: white; border-radius: 50%; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 14px;">
                    {{ substr($purchaseRequest->requestedBy->name, 0, 1) }}
                </div>
                <div>
                    <div style="font-weight: 600; color: #2d3748;">{{ $purchaseRequest->requestedBy->name }}</div>
                    <div style="font-size: 12px; color: #6b7280;">{{ $purchaseRequest->requestedBy->email }}</div>
                </div>
            </div>
        </div>
        
        @if($purchaseRequest->approved_by)
            <div style="margin-bottom: 20px;">
                <label style="font-size: 14px; font-weight: 600; color: #6b7280; margin-bottom: 5px; display: block;">معتمد من</label>
                <div style="display: flex; align-items: center; gap: 10px;">
                    <div style="background: #10b981; color: white; border-radius: 50%; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 14px;">
                        {{ substr($purchaseRequest->approvedBy->name, 0, 1) }}
                    </div>
                    <div>
                        <div style="font-weight: 600; color: #2d3748;">{{ $purchaseRequest->approvedBy->name }}</div>
                        <div style="font-size: 12px; color: #6b7280;">{{ $purchaseRequest->approved_at->format('Y-m-d H:i') }}</div>
                    </div>
                </div>
                @if($purchaseRequest->approval_notes)
                    <div style="margin-top: 10px; padding: 10px; background: #d1fae5; border-radius: 8px; border-right: 4px solid #10b981;">
                        <div style="font-size: 14px; color: #065f46;">{{ $purchaseRequest->approval_notes }}</div>
                    </div>
                @endif
            </div>
        @endif
        
        @if($purchaseRequest->rejected_by)
            <div style="margin-bottom: 20px;">
                <label style="font-size: 14px; font-weight: 600; color: #6b7280; margin-bottom: 5px; display: block;">مرفوض من</label>
                <div style="display: flex; align-items: center; gap: 10px;">
                    <div style="background: #ef4444; color: white; border-radius: 50%; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 14px;">
                        {{ substr($purchaseRequest->rejectedBy->name, 0, 1) }}
                    </div>
                    <div>
                        <div style="font-weight: 600; color: #2d3748;">{{ $purchaseRequest->rejectedBy->name }}</div>
                        <div style="font-size: 12px; color: #6b7280;">{{ $purchaseRequest->rejected_at->format('Y-m-d H:i') }}</div>
                    </div>
                </div>
                @if($purchaseRequest->rejection_reason)
                    <div style="margin-top: 10px; padding: 10px; background: #fee2e2; border-radius: 8px; border-right: 4px solid #ef4444;">
                        <div style="font-size: 14px; color: #991b1b;">{{ $purchaseRequest->rejection_reason }}</div>
                    </div>
                @endif
            </div>
        @endif
        
        @if($purchaseRequest->budget_code || $purchaseRequest->cost_center)
            <div style="padding-top: 20px; border-top: 1px solid #e2e8f0;">
                @if($purchaseRequest->budget_code)
                    <div style="margin-bottom: 10px;">
                        <label style="font-size: 14px; font-weight: 600; color: #6b7280; margin-bottom: 5px; display: block;">رمز الميزانية</label>
                        <div style="font-size: 16px; color: #2d3748;">{{ $purchaseRequest->budget_code }}</div>
                    </div>
                @endif
                
                @if($purchaseRequest->cost_center)
                    <div>
                        <label style="font-size: 14px; font-weight: 600; color: #6b7280; margin-bottom: 5px; display: block;">مركز التكلفة</label>
                        <div style="font-size: 16px; color: #2d3748;">{{ $purchaseRequest->cost_center }}</div>
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>

<!-- Items -->
<div class="content-card">
    <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
        <i class="fas fa-list" style="color: #3b82f6; margin-left: 10px;"></i>
        عناصر الطلب ({{ $purchaseRequest->items->count() }})
    </h3>
    
    @if($purchaseRequest->items->count() > 0)
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                        <th style="padding: 15px; text-align: right; font-weight: 600; color: #4a5568;">#</th>
                        <th style="padding: 15px; text-align: right; font-weight: 600; color: #4a5568;">العنصر</th>
                        <th style="padding: 15px; text-align: center; font-weight: 600; color: #4a5568;">الكمية</th>
                        <th style="padding: 15px; text-align: center; font-weight: 600; color: #4a5568;">الوحدة</th>
                        <th style="padding: 15px; text-align: center; font-weight: 600; color: #4a5568;">السعر المقدر</th>
                        <th style="padding: 15px; text-align: center; font-weight: 600; color: #4a5568;">الإجمالي</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($purchaseRequest->items as $index => $item)
                        <tr style="border-bottom: 1px solid #e2e8f0;">
                            <td style="padding: 15px; font-weight: 600; color: #6b7280;">{{ $index + 1 }}</td>
                            <td style="padding: 15px;">
                                <div style="font-weight: 600; color: #2d3748;">{{ $item->item_name }}</div>
                                @if($item->description)
                                    <div style="font-size: 12px; color: #6b7280; margin-top: 2px;">{{ $item->description }}</div>
                                @endif
                                @if($item->specifications)
                                    <div style="font-size: 12px; color: #6b7280; margin-top: 2px; font-style: italic;">{{ $item->specifications }}</div>
                                @endif
                            </td>
                            <td style="padding: 15px; text-align: center; font-weight: 600; color: #2d3748;">{{ number_format($item->quantity, 2) }}</td>
                            <td style="padding: 15px; text-align: center; color: #6b7280;">{{ $item->unit }}</td>
                            <td style="padding: 15px; text-align: center; font-weight: 600; color: #1e40af;">{{ number_format($item->estimated_price, 2) }}</td>
                            <td style="padding: 15px; text-align: center; font-weight: 600; color: #1e40af;">{{ number_format($item->total_estimated, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr style="background: #f8fafc; border-top: 2px solid #e2e8f0;">
                        <td colspan="5" style="padding: 15px; text-align: left; font-weight: 700; color: #2d3748;">الإجمالي:</td>
                        <td style="padding: 15px; text-align: center; font-weight: 700; color: #1e40af; font-size: 18px;">{{ number_format($purchaseRequest->items->sum('total_estimated'), 2) }} د.ع</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    @else
        <div style="text-align: center; padding: 40px; color: #6b7280;">
            <i class="fas fa-list" style="font-size: 48px; margin-bottom: 15px; opacity: 0.5;"></i>
            <h3 style="margin: 0 0 10px 0;">لا توجد عناصر</h3>
            <p style="margin: 0;">لم يتم إضافة أي عناصر لهذا الطلب</p>
        </div>
    @endif
</div>

<!-- Approval Modal -->
@if($purchaseRequest->status === 'pending')
<div id="approvalModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 15px; padding: 30px; max-width: 500px; width: 90%;">
        <h3 style="margin: 0 0 20px 0; color: #2d3748; font-size: 20px; font-weight: 700;">اعتماد طلب الشراء</h3>
        
        <form method="POST" action="{{ route('tenant.purchasing.purchase-requests.approve', $purchaseRequest) }}">
            @csrf
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">الميزانية المعتمدة</label>
                <input type="number" name="approved_budget" step="0.01" min="0" value="{{ $purchaseRequest->estimated_total }}"
                       style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 16px;">
            </div>
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">ملاحظات الاعتماد</label>
                <textarea name="approval_notes" rows="3" 
                          style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 16px; resize: vertical;"
                          placeholder="أدخل أي ملاحظات على الاعتماد"></textarea>
            </div>
            
            <div style="display: flex; gap: 15px; justify-content: flex-end;">
                <button type="button" onclick="hideApprovalModal()" style="background: #6b7280; color: white; padding: 12px 24px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                    إلغاء
                </button>
                <button type="submit" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 12px 24px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                    اعتماد الطلب
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Rejection Modal -->
<div id="rejectionModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 15px; padding: 30px; max-width: 500px; width: 90%;">
        <h3 style="margin: 0 0 20px 0; color: #2d3748; font-size: 20px; font-weight: 700;">رفض طلب الشراء</h3>
        
        <form method="POST" action="{{ route('tenant.purchasing.purchase-requests.reject', $purchaseRequest) }}">
            @csrf
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">سبب الرفض *</label>
                <textarea name="rejection_reason" rows="4" required
                          style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 16px; resize: vertical;"
                          placeholder="أدخل سبب رفض الطلب"></textarea>
            </div>
            
            <div style="display: flex; gap: 15px; justify-content: flex-end;">
                <button type="button" onclick="hideRejectionModal()" style="background: #6b7280; color: white; padding: 12px 24px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                    إلغاء
                </button>
                <button type="submit" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; padding: 12px 24px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                    رفض الطلب
                </button>
            </div>
        </form>
    </div>
</div>
@endif

@push('scripts')
<script>
function showApprovalModal() {
    document.getElementById('approvalModal').style.display = 'flex';
}

function hideApprovalModal() {
    document.getElementById('approvalModal').style.display = 'none';
}

function showRejectionModal() {
    document.getElementById('rejectionModal').style.display = 'flex';
}

function hideRejectionModal() {
    document.getElementById('rejectionModal').style.display = 'none';
}

// Close modals when clicking outside
document.addEventListener('click', function(e) {
    if (e.target.id === 'approvalModal') {
        hideApprovalModal();
    }
    if (e.target.id === 'rejectionModal') {
        hideRejectionModal();
    }
});
</script>
@endpush
@endsection
