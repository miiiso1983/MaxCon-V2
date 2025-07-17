@extends('layouts.modern')

@section('page-title', 'القيود المحاسبية')
@section('page-description', 'إدارة القيود المحاسبية ونظام الموافقات')

@section('content')
<!-- Page Header -->
<div style="background: linear-gradient(135deg, #ec4899 0%, #db2777 100%); border-radius: 20px; padding: 30px; margin-bottom: 30px; color: white; position: relative; overflow: hidden;">
    <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
    <div style="position: absolute; bottom: -30px; left: -30px; width: 150px; height: 150px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>
    
    <div style="position: relative; z-index: 2;">
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    <div style="background: rgba(255,255,255,0.2); border-radius: 15px; padding: 15px; margin-left: 20px;">
                        <i class="fas fa-file-invoice" style="font-size: 32px;"></i>
                    </div>
                    <div>
                        <h1 style="font-size: 32px; font-weight: 800; margin: 0; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                            القيود المحاسبية 📝
                        </h1>
                        <p style="font-size: 18px; margin: 5px 0 0 0; opacity: 0.9;">
                            إدارة القيود المحاسبية ونظام الموافقات والترحيل
                        </p>
                    </div>
                </div>
                
                <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-list" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px;">{{ $entries->total() }} قيد</span>
                    </div>
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-clock" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px;">{{ $entries->where('status', 'pending')->count() }} معلق</span>
                    </div>
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-check" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px;">{{ $entries->where('status', 'posted')->count() }} مرحل</span>
                    </div>
                </div>
            </div>
            
            <div style="display: flex; gap: 15px;">
                <a href="{{ route('tenant.inventory.accounting.journal-entries.create') }}" style="background: rgba(255,255,255,0.2); color: white; padding: 15px 25px; border-radius: 15px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3);">
                    <i class="fas fa-plus"></i>
                    إضافة قيد جديد
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="content-card" style="margin-bottom: 30px;">
    <form method="GET" action="{{ route('tenant.inventory.accounting.journal-entries.index') }}">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 20px;">
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">الحالة:</label>
                <select name="status" style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px;">
                    <option value="">جميع الحالات</option>
                    @foreach($statuses as $key => $value)
                        <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>{{ $value }}</option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">نوع القيد:</label>
                <select name="entry_type" style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px;">
                    <option value="">جميع الأنواع</option>
                    @foreach($types as $key => $value)
                        <option value="{{ $key }}" {{ request('entry_type') == $key ? 'selected' : '' }}>{{ $value }}</option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">من تاريخ:</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" 
                       style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px;">
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">إلى تاريخ:</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" 
                       style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px;">
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">البحث:</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="رقم القيد أو الوصف..." 
                       style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px;">
            </div>
        </div>
        
        <div style="display: flex; gap: 10px;">
            <button type="submit" style="background: #ec4899; color: white; padding: 10px 20px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                <i class="fas fa-search" style="margin-left: 8px;"></i>
                بحث
            </button>
            <a href="{{ route('tenant.inventory.accounting.journal-entries.index') }}" style="background: #6b7280; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: 600;">
                <i class="fas fa-times" style="margin-left: 8px;"></i>
                إلغاء
            </a>
        </div>
    </form>
</div>

<!-- Journal Entries Table -->
<div class="content-card">
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                    <th style="padding: 15px; text-align: right; font-weight: 600; color: #2d3748;">رقم القيد</th>
                    <th style="padding: 15px; text-align: right; font-weight: 600; color: #2d3748;">التاريخ</th>
                    <th style="padding: 15px; text-align: right; font-weight: 600; color: #2d3748;">الوصف</th>
                    <th style="padding: 15px; text-align: right; font-weight: 600; color: #2d3748;">النوع</th>
                    <th style="padding: 15px; text-align: right; font-weight: 600; color: #2d3748;">المدين</th>
                    <th style="padding: 15px; text-align: right; font-weight: 600; color: #2d3748;">الدائن</th>
                    <th style="padding: 15px; text-align: right; font-weight: 600; color: #2d3748;">الحالة</th>
                    <th style="padding: 15px; text-align: center; font-weight: 600; color: #2d3748;">الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($entries as $entry)
                    <tr style="border-bottom: 1px solid #e2e8f0; transition: background-color 0.2s;" 
                        onmouseover="this.style.backgroundColor='#f8fafc'" 
                        onmouseout="this.style.backgroundColor='transparent'">
                        <td style="padding: 15px; font-weight: 600; color: #4a5568;">
                            <a href="{{ route('tenant.inventory.accounting.journal-entries.show', $entry) }}" style="color: #ec4899; text-decoration: none;">
                                {{ $entry->journal_number }}
                            </a>
                        </td>
                        <td style="padding: 15px; color: #6b7280;">{{ $entry->entry_date->format('Y-m-d') }}</td>
                        <td style="padding: 15px; color: #2d3748;">
                            <div style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                {{ $entry->description }}
                            </div>
                        </td>
                        <td style="padding: 15px;">
                            <span style="background: #f3f4f6; color: #374151; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                                {{ $types[$entry->entry_type] }}
                            </span>
                        </td>
                        <td style="padding: 15px; font-weight: 600; color: #059669;">
                            {{ number_format($entry->total_debit, 2) }} {{ $entry->currency_code }}
                        </td>
                        <td style="padding: 15px; font-weight: 600; color: #dc2626;">
                            {{ number_format($entry->total_credit, 2) }} {{ $entry->currency_code }}
                        </td>
                        <td style="padding: 15px;">
                            @php
                                $statusColors = [
                                    'draft' => ['bg' => '#f3f4f6', 'text' => '#374151'],
                                    'pending' => ['bg' => '#fef3c7', 'text' => '#92400e'],
                                    'approved' => ['bg' => '#dbeafe', 'text' => '#1e40af'],
                                    'rejected' => ['bg' => '#fee2e2', 'text' => '#991b1b'],
                                    'posted' => ['bg' => '#dcfce7', 'text' => '#166534']
                                ];
                                $colors = $statusColors[$entry->status] ?? $statusColors['draft'];
                            @endphp
                            <span style="background: {{ $colors['bg'] }}; color: {{ $colors['text'] }}; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                                {{ $statuses[$entry->status] }}
                            </span>
                        </td>
                        <td style="padding: 15px; text-align: center;">
                            <div style="display: flex; gap: 8px; justify-content: center;">
                                <a href="{{ route('tenant.inventory.accounting.journal-entries.show', $entry) }}" 
                                   style="background: #3b82f6; color: white; padding: 8px 12px; border-radius: 6px; text-decoration: none; font-size: 12px;">
                                    <i class="fas fa-eye"></i>
                                </a>
                                
                                @if($entry->canBeEdited())
                                    <a href="{{ route('tenant.inventory.accounting.journal-entries.edit', $entry) }}" 
                                       style="background: #f59e0b; color: white; padding: 8px 12px; border-radius: 6px; text-decoration: none; font-size: 12px;">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                @endif
                                
                                @if($entry->status === 'draft')
                                    <form method="POST" action="{{ route('tenant.inventory.accounting.journal-entries.submit', $entry) }}" style="display: inline;">
                                        @csrf
                                        <button type="submit" style="background: #10b981; color: white; padding: 8px 12px; border: none; border-radius: 6px; cursor: pointer; font-size: 12px;" title="إرسال للاعتماد">
                                            <i class="fas fa-paper-plane"></i>
                                        </button>
                                    </form>
                                @endif
                                
                                @if($entry->canBeApproved())
                                    <form method="POST" action="{{ route('tenant.inventory.accounting.journal-entries.approve', $entry) }}" style="display: inline;">
                                        @csrf
                                        <button type="submit" style="background: #059669; color: white; padding: 8px 12px; border: none; border-radius: 6px; cursor: pointer; font-size: 12px;" title="اعتماد">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                @endif
                                
                                @if($entry->status === 'pending')
                                    <form method="POST" action="{{ route('tenant.inventory.accounting.journal-entries.reject', $entry) }}" style="display: inline;">
                                        @csrf
                                        <button type="submit" style="background: #dc2626; color: white; padding: 8px 12px; border: none; border-radius: 6px; cursor: pointer; font-size: 12px;" title="رفض">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                @endif
                                
                                @if($entry->canBePosted())
                                    <form method="POST" action="{{ route('tenant.inventory.accounting.journal-entries.post', $entry) }}" style="display: inline;">
                                        @csrf
                                        <button type="submit" style="background: #8b5cf6; color: white; padding: 8px 12px; border: none; border-radius: 6px; cursor: pointer; font-size: 12px;" title="ترحيل">
                                            <i class="fas fa-upload"></i>
                                        </button>
                                    </form>
                                @endif
                                
                                @if($entry->canBeEdited())
                                    <form method="POST" action="{{ route('tenant.inventory.accounting.journal-entries.destroy', $entry) }}" 
                                          style="display: inline;" onsubmit="return confirm('هل أنت متأكد من حذف هذا القيد؟')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" style="background: #ef4444; color: white; padding: 8px 12px; border: none; border-radius: 6px; cursor: pointer; font-size: 12px;">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="padding: 40px; text-align: center; color: #6b7280;">
                            <div style="display: flex; flex-direction: column; align-items: center; gap: 15px;">
                                <i class="fas fa-file-invoice" style="font-size: 48px; color: #d1d5db;"></i>
                                <div>
                                    <h3 style="margin: 0 0 8px 0; color: #374151;">لا توجد قيود محاسبية</h3>
                                    <p style="margin: 0; color: #6b7280;">ابدأ بإضافة قيود محاسبية جديدة</p>
                                </div>
                                <a href="{{ route('tenant.inventory.accounting.journal-entries.create') }}" 
                                   style="background: #ec4899; color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600;">
                                    <i class="fas fa-plus" style="margin-left: 8px;"></i>
                                    إضافة قيد جديد
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($entries->hasPages())
        <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #e2e8f0;">
            {{ $entries->links() }}
        </div>
    @endif
</div>

<!-- Status Summary -->
@if($entries->count() > 0)
<div class="content-card" style="margin-top: 30px;">
    <h3 style="color: #2d3748; margin-bottom: 20px; font-size: 20px; font-weight: 700; display: flex; align-items: center; gap: 10px; border-bottom: 2px solid #e2e8f0; padding-bottom: 10px;">
        <i class="fas fa-chart-pie" style="color: #ec4899;"></i>
        ملخص حالات القيود
    </h3>
    
    @php
        $statusCounts = [
            'draft' => $entries->where('status', 'draft')->count(),
            'pending' => $entries->where('status', 'pending')->count(),
            'approved' => $entries->where('status', 'approved')->count(),
            'rejected' => $entries->where('status', 'rejected')->count(),
            'posted' => $entries->where('status', 'posted')->count()
        ];
        $totalAmount = $entries->sum('total_debit');
    @endphp
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
        <div style="background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%); padding: 20px; border-radius: 12px; text-align: center;">
            <div style="color: #374151; font-size: 24px; font-weight: 800; margin-bottom: 8px;">{{ $statusCounts['draft'] }}</div>
            <div style="color: #4b5563; font-weight: 600;">مسودات</div>
        </div>
        
        <div style="background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); padding: 20px; border-radius: 12px; text-align: center;">
            <div style="color: #d97706; font-size: 24px; font-weight: 800; margin-bottom: 8px;">{{ $statusCounts['pending'] }}</div>
            <div style="color: #92400e; font-weight: 600;">في الانتظار</div>
        </div>
        
        <div style="background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%); padding: 20px; border-radius: 12px; text-align: center;">
            <div style="color: #1e40af; font-size: 24px; font-weight: 800; margin-bottom: 8px;">{{ $statusCounts['approved'] }}</div>
            <div style="color: #1e3a8a; font-weight: 600;">معتمدة</div>
        </div>
        
        <div style="background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%); padding: 20px; border-radius: 12px; text-align: center;">
            <div style="color: #166534; font-size: 24px; font-weight: 800; margin-bottom: 8px;">{{ $statusCounts['posted'] }}</div>
            <div style="color: #14532d; font-weight: 600;">مرحلة</div>
        </div>
        
        <div style="background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%); padding: 20px; border-radius: 12px; text-align: center;">
            <div style="color: #dc2626; font-size: 24px; font-weight: 800; margin-bottom: 8px;">{{ $statusCounts['rejected'] }}</div>
            <div style="color: #991b1b; font-weight: 600;">مرفوضة</div>
        </div>
    </div>
</div>
@endif
@endsection

<script>
// Auto-refresh status every 30 seconds for pending entries
setInterval(function() {
    if (document.querySelector('[style*="background: #fef3c7"]')) {
        location.reload();
    }
}, 30000);
</script>
