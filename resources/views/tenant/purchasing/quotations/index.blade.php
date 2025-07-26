@extends('layouts.modern')

@section('page-title', 'عروض الأسعار')
@section('page-description', 'مقارنة عروض الأسعار واختيار الأفضل')

@section('content')
<!-- Page Header -->
<div style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); border-radius: 20px; padding: 30px; margin-bottom: 30px; color: white; position: relative; overflow: hidden;">
    <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
    <div style="position: absolute; bottom: -30px; left: -30px; width: 150px; height: 150px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>
    
    <div style="position: relative; z-index: 2;">
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    <div style="background: rgba(255,255,255,0.2); border-radius: 15px; padding: 15px; margin-left: 20px;">
                        <i class="fas fa-quote-right" style="font-size: 32px;"></i>
                    </div>
                    <div>
                        <h1 style="font-size: 32px; font-weight: 800; margin: 0; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                            عروض الأسعار 💰
                        </h1>
                        <p style="font-size: 18px; margin: 5px 0 0 0; opacity: 0.9;">
                            مقارنة عروض الأسعار واختيار الأفضل
                        </p>
                    </div>
                </div>
                
                <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-balance-scale" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px; font-weight: 600;">مقارنة العروض</span>
                    </div>
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-star" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px;">تقييم العروض</span>
                    </div>
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-chart-line" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px;">تحليل الأسعار</span>
                    </div>
                </div>
            </div>
            
            <div style="display: flex; gap: 15px;">
                <a href="{{ route('tenant.purchasing.quotations.create') }}" style="background: rgba(255,255,255,0.2); color: white; padding: 15px 25px; border-radius: 15px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3);">
                    <i class="fas fa-plus"></i>
                    طلب عرض سعر
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px;">
    <div style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); border-radius: 15px; padding: 20px; color: white; position: relative; overflow: hidden;">
        <div style="position: absolute; top: -10px; right: -10px; width: 60px; height: 60px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
        <div style="position: relative; z-index: 2;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                <i class="fas fa-list" style="font-size: 24px; opacity: 0.8;"></i>
                <span style="font-size: 12px; opacity: 0.8;">إجمالي العروض</span>
            </div>
            <div style="font-size: 28px; font-weight: 700;">{{ number_format($stats['total']) }}</div>
            <div style="font-size: 12px; opacity: 0.8;">عرض سعر</div>
        </div>
    </div>
    
    <div style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); border-radius: 15px; padding: 20px; color: white; position: relative; overflow: hidden;">
        <div style="position: absolute; top: -10px; right: -10px; width: 60px; height: 60px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
        <div style="position: relative; z-index: 2;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                <i class="fas fa-clock" style="font-size: 24px; opacity: 0.8;"></i>
                <span style="font-size: 12px; opacity: 0.8;">في الانتظار</span>
            </div>
            <div style="font-size: 28px; font-weight: 700;">{{ number_format($stats['pending']) }}</div>
            <div style="font-size: 12px; opacity: 0.8;">عرض معلق</div>
        </div>
    </div>
    
    <div style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); border-radius: 15px; padding: 20px; color: white; position: relative; overflow: hidden;">
        <div style="position: absolute; top: -10px; right: -10px; width: 60px; height: 60px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
        <div style="position: relative; z-index: 2;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                <i class="fas fa-inbox" style="font-size: 24px; opacity: 0.8;"></i>
                <span style="font-size: 12px; opacity: 0.8;">مستلم</span>
            </div>
            <div style="font-size: 28px; font-weight: 700;">{{ number_format($stats['received']) }}</div>
            <div style="font-size: 12px; opacity: 0.8;">عرض مستلم</div>
        </div>
    </div>
    
    <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius: 15px; padding: 20px; color: white; position: relative; overflow: hidden;">
        <div style="position: absolute; top: -10px; right: -10px; width: 60px; height: 60px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
        <div style="position: relative; z-index: 2;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                <i class="fas fa-check-circle" style="font-size: 24px; opacity: 0.8;"></i>
                <span style="font-size: 12px; opacity: 0.8;">مقبول</span>
            </div>
            <div style="font-size: 28px; font-weight: 700;">{{ number_format($stats['accepted']) }}</div>
            <div style="font-size: 12px; opacity: 0.8;">عرض مقبول</div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="content-card" style="margin-bottom: 25px;">
    <form method="GET" action="{{ route('tenant.purchasing.quotations.index') }}" style="display: grid; grid-template-columns: 2fr 1fr 1fr auto; gap: 15px; align-items: end;">
        <div>
            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">البحث</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="البحث برقم العرض، العنوان، اسم المورد..." style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;">
        </div>

        <div>
            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">الحالة</label>
            <select name="status" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;">
                <option value="">جميع الحالات</option>
                <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>مسودة</option>
                <option value="sent" {{ request('status') === 'sent' ? 'selected' : '' }}>مرسل</option>
                <option value="received" {{ request('status') === 'received' ? 'selected' : '' }}>مستلم</option>
                <option value="under_review" {{ request('status') === 'under_review' ? 'selected' : '' }}>قيد المراجعة</option>
                <option value="accepted" {{ request('status') === 'accepted' ? 'selected' : '' }}>مقبول</option>
                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>مرفوض</option>
                <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>منتهي الصلاحية</option>
            </select>
        </div>

        <div>
            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">المورد</label>
            <select name="supplier_id" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;">
                <option value="">جميع الموردين</option>
                @foreach($suppliers as $supplier)
                    <option value="{{ $supplier->id }}" {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>
                        {{ $supplier->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div style="display: flex; gap: 10px;">
            <button type="submit" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; padding: 12px 20px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px; transition: all 0.3s ease; box-shadow: 0 2px 4px rgba(245, 158, 11, 0.3);" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 8px rgba(245, 158, 11, 0.4)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(245, 158, 11, 0.3)'">
                <i class="fas fa-search"></i>
                <span>بحث</span>
            </button>
            <a href="{{ route('tenant.purchasing.quotations.index') }}" style="background: #6b7280; color: white; padding: 12px 20px; border-radius: 8px; text-decoration: none; display: flex; align-items: center; gap: 8px; font-weight: 600; transition: all 0.3s ease; box-shadow: 0 2px 4px rgba(107, 114, 128, 0.3);" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 8px rgba(107, 114, 128, 0.4)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(107, 114, 128, 0.3)'">
                <i class="fas fa-times"></i>
                <span>إلغاء</span>
            </a>
        </div>
    </form>
</div>

<!-- Quotations Table -->
<div class="content-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin: 0;">قائمة عروض الأسعار</h3>
        <div style="display: flex; gap: 10px;">
            <button onclick="exportQuotations()" style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); color: white; padding: 12px 20px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px; transition: all 0.3s ease; box-shadow: 0 2px 4px rgba(59, 130, 246, 0.3);" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 8px rgba(59, 130, 246, 0.4)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(59, 130, 246, 0.3)'">
                <i class="fas fa-file-excel"></i>
                <span>تصدير Excel</span>
            </button>
        </div>
    </div>

    @if($quotations->count() > 0)
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                        <th style="padding: 15px; text-align: right; font-weight: 600; color: #4a5568;">رقم العرض</th>
                        <th style="padding: 15px; text-align: right; font-weight: 600; color: #4a5568;">العنوان</th>
                        <th style="padding: 15px; text-align: right; font-weight: 600; color: #4a5568;">المورد</th>
                        <th style="padding: 15px; text-align: center; font-weight: 600; color: #4a5568;">الحالة</th>
                        <th style="padding: 15px; text-align: center; font-weight: 600; color: #4a5568;">تاريخ الطلب</th>
                        <th style="padding: 15px; text-align: center; font-weight: 600; color: #4a5568;">صالح حتى</th>
                        <th style="padding: 15px; text-align: center; font-weight: 600; color: #4a5568;">القيمة الإجمالية</th>
                        <th style="padding: 15px; text-align: center; font-weight: 600; color: #4a5568;">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($quotations as $quotation)
                        <tr style="border-bottom: 1px solid #e2e8f0; transition: background-color 0.3s ease;"
                            onmouseover="this.style.backgroundColor='#f8fafc'"
                            onmouseout="this.style.backgroundColor='transparent'">
                            <td style="padding: 15px;">
                                <div style="font-weight: 600; color: #2d3748;">{{ $quotation->quotation_number }}</div>
                                <div style="font-size: 12px; color: #6b7280;">{{ $quotation->created_at->format('Y-m-d') }}</div>
                            </td>
                            <td style="padding: 15px;">
                                <div style="font-weight: 600; color: #2d3748;">{{ $quotation->title }}</div>
                                @if($quotation->description)
                                    <div style="font-size: 12px; color: #6b7280;">{{ Str::limit($quotation->description, 50) }}</div>
                                @endif
                            </td>
                            <td style="padding: 15px;">
                                <div style="font-weight: 600; color: #2d3748;">{{ $quotation->supplier->name }}</div>
                                <div style="font-size: 12px; color: #6b7280;">{{ $quotation->supplier->supplier_code }}</div>
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                @php
                                    $statusColors = [
                                        'draft' => ['bg' => '#f3f4f6', 'text' => '#374151'],
                                        'sent' => ['bg' => '#dbeafe', 'text' => '#1e40af'],
                                        'received' => ['bg' => '#d1fae5', 'text' => '#065f46'],
                                        'under_review' => ['bg' => '#fef3c7', 'text' => '#92400e'],
                                        'accepted' => ['bg' => '#dcfce7', 'text' => '#166534'],
                                        'rejected' => ['bg' => '#fee2e2', 'text' => '#991b1b'],
                                        'expired' => ['bg' => '#f3f4f6', 'text' => '#6b7280'],
                                    ];
                                    $status = $statusColors[$quotation->status] ?? ['bg' => '#f3f4f6', 'text' => '#374151'];
                                @endphp
                                <span style="background: {{ $status['bg'] }}; color: {{ $status['text'] }}; padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                                    {{ $quotation->status_label }}
                                </span>
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <div style="font-weight: 600; color: #2d3748;">{{ $quotation->quotation_date->format('Y-m-d') }}</div>
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <div style="font-weight: 600; color: #2d3748;">{{ $quotation->valid_until->format('Y-m-d') }}</div>
                                @if($quotation->is_expired)
                                    <div style="font-size: 10px; color: #dc2626; font-weight: 600;">منتهي الصلاحية</div>
                                @endif
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <div style="font-weight: 600; color: #1e40af;">{{ number_format($quotation->total_amount, 0) }}</div>
                                <div style="font-size: 12px; color: #6b7280;">{{ $quotation->currency }}</div>
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <div style="display: flex; justify-content: center; gap: 8px;">
                                    <a href="{{ route('tenant.purchasing.quotations.show', $quotation) }}"
                                       style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); color: white; padding: 10px 14px; border-radius: 8px; text-decoration: none; font-size: 14px; font-weight: 600; display: flex; align-items: center; gap: 6px; transition: all 0.3s ease; box-shadow: 0 2px 4px rgba(59, 130, 246, 0.3);"
                                       onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 8px rgba(59, 130, 246, 0.4)'"
                                       onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(59, 130, 246, 0.3)'"
                                       title="عرض التفاصيل">
                                        <i class="fas fa-eye"></i>
                                        <span>عرض</span>
                                    </a>
                                    @if(in_array($quotation->status, ['draft']))
                                        <a href="{{ route('tenant.purchasing.quotations.edit', $quotation) }}"
                                           style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; padding: 10px 14px; border-radius: 8px; text-decoration: none; font-size: 14px; font-weight: 600; display: flex; align-items: center; gap: 6px; transition: all 0.3s ease; box-shadow: 0 2px 4px rgba(245, 158, 11, 0.3);"
                                           onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 8px rgba(245, 158, 11, 0.4)'"
                                           onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(245, 158, 11, 0.3)'"
                                           title="تعديل العرض">
                                            <i class="fas fa-edit"></i>
                                            <span>تعديل</span>
                                        </a>
                                    @endif
                                    @if(in_array($quotation->status, ['draft', 'rejected', 'expired']))
                                        <form method="POST" action="{{ route('tenant.purchasing.quotations.destroy', $quotation) }}" style="display: inline;" onsubmit="return confirm('هل أنت متأكد من حذف هذا العرض؟')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; padding: 10px 14px; border: none; border-radius: 8px; cursor: pointer; font-size: 14px; font-weight: 600; display: flex; align-items: center; gap: 6px; transition: all 0.3s ease; box-shadow: 0 2px 4px rgba(239, 68, 68, 0.3);"
                                                    onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 8px rgba(239, 68, 68, 0.4)'"
                                                    onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(239, 68, 68, 0.3)'"
                                                    title="حذف العرض">
                                                <i class="fas fa-trash"></i>
                                                <span>حذف</span>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div style="margin-top: 20px;">
            {{ $quotations->links() }}
        </div>
    @else
        <div style="text-align: center; padding: 40px; color: #6b7280;">
            <i class="fas fa-quote-right" style="font-size: 48px; margin-bottom: 15px; opacity: 0.5;"></i>
            <h3 style="margin: 0 0 10px 0;">لا توجد عروض أسعار</h3>
            <p style="margin: 0 0 20px 0;">ابدأ بإنشاء أول طلب عرض سعر</p>
            <a href="{{ route('tenant.purchasing.quotations.create') }}" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600;">
                <i class="fas fa-plus" style="margin-left: 8px;"></i>
                طلب عرض سعر جديد
            </a>
        </div>
    @endif
</div>

@push('scripts')
<script>
function exportQuotations() {
    // Create CSV content
    let csv = 'رقم العرض,العنوان,المورد,الحالة,تاريخ الطلب,صالح حتى,القيمة الإجمالية,العملة\n';

    @foreach($quotations as $quotation)
        csv += '"{{ $quotation->quotation_number }}","{{ $quotation->title }}","{{ $quotation->supplier->name }}","{{ $quotation->status_label }}","{{ $quotation->quotation_date->format('Y-m-d') }}","{{ $quotation->valid_until->format('Y-m-d') }}",{{ $quotation->total_amount }},"{{ $quotation->currency }}"\n';
    @endforeach

    // Download CSV
    const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    const url = URL.createObjectURL(blob);
    link.setAttribute('href', url);
    link.setAttribute('download', 'quotations_' + new Date().toISOString().split('T')[0] + '.csv');
    link.style.visibility = 'hidden';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}
</script>
@endpush
@endsection
