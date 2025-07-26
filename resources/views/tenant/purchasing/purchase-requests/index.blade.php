@extends('layouts.modern')

@section('page-title', 'طلبات الشراء')
@section('page-description', 'إدارة طلبات الشراء الداخلية والموافقات')

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
                            طلبات الشراء 📋
                        </h1>
                        <p style="font-size: 18px; margin: 5px 0 0 0; opacity: 0.9;">
                            إدارة طلبات الشراء الداخلية والموافقات
                        </p>
                    </div>
                </div>
                
                <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-check-circle" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px; font-weight: 600;">نظام الموافقات</span>
                    </div>
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-clock" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px;">تتبع المواعيد</span>
                    </div>
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-exclamation-triangle" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px;">الطلبات العاجلة</span>
                    </div>
                </div>
            </div>
            
            <div style="display: flex; gap: 15px;">
                <a href="{{ route('tenant.purchasing.purchase-requests.create') }}" style="background: rgba(255,255,255,0.2); color: white; padding: 15px 25px; border-radius: 15px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3);">
                    <i class="fas fa-plus"></i>
                    طلب شراء جديد
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
                <span style="font-size: 12px; opacity: 0.8;">إجمالي الطلبات</span>
            </div>
            <div style="font-size: 28px; font-weight: 700;">{{ number_format($stats['total']) }}</div>
            <div style="font-size: 12px; opacity: 0.8;">طلب شراء</div>
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
            <div style="font-size: 12px; opacity: 0.8;">طلب معلق</div>
        </div>
    </div>
    
    <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius: 15px; padding: 20px; color: white; position: relative; overflow: hidden;">
        <div style="position: absolute; top: -10px; right: -10px; width: 60px; height: 60px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
        <div style="position: relative; z-index: 2;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                <i class="fas fa-check-circle" style="font-size: 24px; opacity: 0.8;"></i>
                <span style="font-size: 12px; opacity: 0.8;">معتمد</span>
            </div>
            <div style="font-size: 28px; font-weight: 700;">{{ number_format($stats['approved']) }}</div>
            <div style="font-size: 12px; opacity: 0.8;">طلب معتمد</div>
        </div>
    </div>
    
    <div style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); border-radius: 15px; padding: 20px; color: white; position: relative; overflow: hidden;">
        <div style="position: absolute; top: -10px; right: -10px; width: 60px; height: 60px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
        <div style="position: relative; z-index: 2;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                <i class="fas fa-exclamation-triangle" style="font-size: 24px; opacity: 0.8;"></i>
                <span style="font-size: 12px; opacity: 0.8;">عاجل</span>
            </div>
            <div style="font-size: 28px; font-weight: 700;">{{ number_format($stats['urgent']) }}</div>
            <div style="font-size: 12px; opacity: 0.8;">طلب عاجل</div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="content-card" style="margin-bottom: 25px;">
    <form method="GET" action="{{ route('tenant.purchasing.purchase-requests.index') }}" style="display: grid; grid-template-columns: 2fr 1fr 1fr 1fr auto; gap: 15px; align-items: end;">
        <div>
            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">البحث</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="البحث برقم الطلب، العنوان، الوصف..." style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;">
        </div>
        
        <div>
            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">الحالة</label>
            <select name="status" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;">
                <option value="">جميع الحالات</option>
                <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>مسودة</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>في الانتظار</option>
                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>معتمد</option>
                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>مرفوض</option>
                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>ملغي</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>مكتمل</option>
            </select>
        </div>
        
        <div>
            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">الأولوية</label>
            <select name="priority" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;">
                <option value="">جميع الأولويات</option>
                <option value="low" {{ request('priority') === 'low' ? 'selected' : '' }}>منخفضة</option>
                <option value="medium" {{ request('priority') === 'medium' ? 'selected' : '' }}>متوسطة</option>
                <option value="high" {{ request('priority') === 'high' ? 'selected' : '' }}>عالية</option>
                <option value="urgent" {{ request('priority') === 'urgent' ? 'selected' : '' }}>عاجل</option>
            </select>
        </div>
        
        <div>
            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">مقدم الطلب</label>
            <select name="requested_by" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;">
                <option value="">جميع المستخدمين</option>
                <!-- Add users options here -->
            </select>
        </div>
        
        <div style="display: flex; gap: 10px;">
            <button type="submit" style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); color: white; padding: 12px 20px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px; transition: all 0.3s ease; box-shadow: 0 2px 4px rgba(59, 130, 246, 0.3);" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 8px rgba(59, 130, 246, 0.4)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(59, 130, 246, 0.3)'">
                <i class="fas fa-search"></i>
                <span>بحث</span>
            </button>
            <a href="{{ route('tenant.purchasing.purchase-requests.index') }}" style="background: #6b7280; color: white; padding: 12px 20px; border-radius: 8px; text-decoration: none; display: flex; align-items: center; gap: 8px; font-weight: 600; transition: all 0.3s ease; box-shadow: 0 2px 4px rgba(107, 114, 128, 0.3);" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 8px rgba(107, 114, 128, 0.4)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(107, 114, 128, 0.3)'">
                <i class="fas fa-times"></i>
                <span>إلغاء</span>
            </a>
        </div>
    </form>
</div>

<!-- Purchase Requests Table -->
<div class="content-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin: 0;">قائمة طلبات الشراء</h3>
        <div style="display: flex; gap: 10px;">
            <button onclick="exportRequests()" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 12px 20px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px; transition: all 0.3s ease; box-shadow: 0 2px 4px rgba(16, 185, 129, 0.3);" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 8px rgba(16, 185, 129, 0.4)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(16, 185, 129, 0.3)'">
                <i class="fas fa-file-excel"></i>
                <span>تصدير Excel</span>
            </button>
        </div>
    </div>
    
    @if($purchaseRequests->count() > 0)
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                        <th style="padding: 15px; text-align: right; font-weight: 600; color: #4a5568;">رقم الطلب</th>
                        <th style="padding: 15px; text-align: right; font-weight: 600; color: #4a5568;">العنوان</th>
                        <th style="padding: 15px; text-align: center; font-weight: 600; color: #4a5568;">مقدم الطلب</th>
                        <th style="padding: 15px; text-align: center; font-weight: 600; color: #4a5568;">الأولوية</th>
                        <th style="padding: 15px; text-align: center; font-weight: 600; color: #4a5568;">الحالة</th>
                        <th style="padding: 15px; text-align: center; font-weight: 600; color: #4a5568;">التاريخ المطلوب</th>
                        <th style="padding: 15px; text-align: center; font-weight: 600; color: #4a5568;">القيمة المقدرة</th>
                        <th style="padding: 15px; text-align: center; font-weight: 600; color: #4a5568;">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($purchaseRequests as $request)
                        <tr style="border-bottom: 1px solid #e2e8f0; transition: background-color 0.3s ease;" 
                            onmouseover="this.style.backgroundColor='#f8fafc'" 
                            onmouseout="this.style.backgroundColor='transparent'">
                            <td style="padding: 15px;">
                                <div style="font-weight: 600; color: #2d3748;">{{ $request->request_number }}</div>
                                <div style="font-size: 12px; color: #6b7280;">{{ $request->created_at->format('Y-m-d') }}</div>
                            </td>
                            <td style="padding: 15px;">
                                <div style="font-weight: 600; color: #2d3748;">{{ $request->title }}</div>
                                @if($request->description)
                                    <div style="font-size: 12px; color: #6b7280;">{{ Str::limit($request->description, 50) }}</div>
                                @endif
                                @if($request->is_urgent)
                                    <span style="background: #fee2e2; color: #991b1b; padding: 2px 6px; border-radius: 8px; font-size: 10px; font-weight: 600;">
                                        عاجل
                                    </span>
                                @endif
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <div style="font-weight: 600; color: #2d3748;">{{ $request->requestedBy->name }}</div>
                                <div style="font-size: 12px; color: #6b7280;">{{ $request->requestedBy->email }}</div>
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                @php
                                    $priorityColors = [
                                        'low' => ['bg' => '#f0f9ff', 'text' => '#0369a1'],
                                        'medium' => ['bg' => '#fef3c7', 'text' => '#92400e'],
                                        'high' => ['bg' => '#fef2f2', 'text' => '#991b1b'],
                                        'urgent' => ['bg' => '#fee2e2', 'text' => '#991b1b'],
                                    ];
                                    $priority = $priorityColors[$request->priority] ?? ['bg' => '#f3f4f6', 'text' => '#374151'];
                                    $priorityLabels = [
                                        'low' => 'منخفضة',
                                        'medium' => 'متوسطة',
                                        'high' => 'عالية',
                                        'urgent' => 'عاجل'
                                    ];
                                @endphp
                                <span class="priority-badge priority-{{ $request->priority }}" style="padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                                    {{ $priorityLabels[$request->priority] ?? $request->priority }}
                                </span>
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                @php
                                    $statusColors = [
                                        'draft' => ['bg' => '#f3f4f6', 'text' => '#374151'],
                                        'pending' => ['bg' => '#fef3c7', 'text' => '#92400e'],
                                        'approved' => ['bg' => '#d1fae5', 'text' => '#065f46'],
                                        'rejected' => ['bg' => '#fee2e2', 'text' => '#991b1b'],
                                        'cancelled' => ['bg' => '#f3f4f6', 'text' => '#6b7280'],
                                        'completed' => ['bg' => '#dbeafe', 'text' => '#1e40af'],
                                    ];
                                    $status = $statusColors[$request->status] ?? ['bg' => '#f3f4f6', 'text' => '#374151'];
                                @endphp
                                @php
                                    $statusLabels = [
                                        'draft' => 'مسودة',
                                        'pending' => 'في الانتظار',
                                        'approved' => 'معتمد',
                                        'rejected' => 'مرفوض',
                                        'cancelled' => 'ملغي',
                                        'completed' => 'مكتمل'
                                    ];
                                @endphp
                                <span class="status-badge status-{{ $request->status }}" style="padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                                    {{ $statusLabels[$request->status] ?? $request->status }}
                                </span>
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <div style="font-weight: 600; color: #2d3748;">{{ $request->required_date->format('Y-m-d') }}</div>
                                @php
                                    $daysLeft = now()->diffInDays($request->required_date, false);
                                @endphp
                                @if($daysLeft < 0)
                                    <div style="font-size: 12px; color: #ef4444;">متأخر {{ abs($daysLeft) }} يوم</div>
                                @elseif($daysLeft <= 7)
                                    <div style="font-size: 12px; color: #f59e0b;">{{ $daysLeft }} يوم متبقي</div>
                                @else
                                    <div style="font-size: 12px; color: #6b7280;">{{ $daysLeft }} يوم متبقي</div>
                                @endif
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <div style="font-weight: 600; color: #1e40af;">{{ number_format($request->estimated_total, 0) }}</div>
                                <div style="font-size: 12px; color: #6b7280;">د.ع</div>
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <div style="display: flex; justify-content: center; gap: 8px;">
                                    <a href="{{ route('tenant.purchasing.purchase-requests.show', $request) }}"
                                       style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); color: white; padding: 10px 14px; border-radius: 8px; text-decoration: none; font-size: 14px; font-weight: 600; display: flex; align-items: center; gap: 6px; transition: all 0.3s ease; box-shadow: 0 2px 4px rgba(59, 130, 246, 0.3);"
                                       onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 8px rgba(59, 130, 246, 0.4)'"
                                       onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(59, 130, 246, 0.3)'"
                                       title="عرض التفاصيل">
                                        <i class="fas fa-eye"></i>
                                        <span>عرض</span>
                                    </a>
                                    @if(in_array($request->status, ['draft', 'pending']))
                                        <a href="{{ route('tenant.purchasing.purchase-requests.edit', $request) }}"
                                           style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; padding: 10px 14px; border-radius: 8px; text-decoration: none; font-size: 14px; font-weight: 600; display: flex; align-items: center; gap: 6px; transition: all 0.3s ease; box-shadow: 0 2px 4px rgba(245, 158, 11, 0.3);"
                                           onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 8px rgba(245, 158, 11, 0.4)'"
                                           onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(245, 158, 11, 0.3)'"
                                           title="تعديل الطلب">
                                            <i class="fas fa-edit"></i>
                                            <span>تعديل</span>
                                        </a>
                                    @endif
                                    @if(in_array($request->status, ['draft', 'cancelled']))
                                        <form method="POST" action="{{ route('tenant.purchasing.purchase-requests.destroy', $request) }}" style="display: inline;" onsubmit="return confirm('هل أنت متأكد من حذف هذا الطلب؟')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; padding: 10px 14px; border: none; border-radius: 8px; cursor: pointer; font-size: 14px; font-weight: 600; display: flex; align-items: center; gap: 6px; transition: all 0.3s ease; box-shadow: 0 2px 4px rgba(239, 68, 68, 0.3);"
                                                    onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 8px rgba(239, 68, 68, 0.4)'"
                                                    onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(239, 68, 68, 0.3)'"
                                                    title="حذف الطلب">
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
            {{ $purchaseRequests->links() }}
        </div>
    @else
        <div style="text-align: center; padding: 40px; color: #6b7280;">
            <i class="fas fa-file-alt" style="font-size: 48px; margin-bottom: 15px; opacity: 0.5;"></i>
            <h3 style="margin: 0 0 10px 0;">لا توجد طلبات شراء</h3>
            <p style="margin: 0 0 20px 0;">ابدأ بإنشاء أول طلب شراء</p>
            <a href="{{ route('tenant.purchasing.purchase-requests.create') }}" style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600;">
                <i class="fas fa-plus" style="margin-left: 8px;"></i>
                طلب شراء جديد
            </a>
        </div>
    @endif
</div>

@push('scripts')
<script>
function exportRequests() {
    // Create CSV content
    let csv = 'رقم الطلب,العنوان,مقدم الطلب,الأولوية,الحالة,التاريخ المطلوب,القيمة المقدرة\n';

    // Get data from table rows
    const rows = document.querySelectorAll('tbody tr');
    rows.forEach(row => {
        if (row.style.display !== 'none') {
            const cells = row.querySelectorAll('td');
            if (cells.length >= 7) {
                const requestNumber = cells[0].querySelector('div').textContent.trim();
                const title = cells[1].querySelector('div').textContent.trim();
                const requester = cells[2].querySelector('div').textContent.trim();
                const priority = cells[3].querySelector('span').textContent.trim();
                const status = cells[4].querySelector('span').textContent.trim();
                const date = cells[5].querySelector('div').textContent.trim();
                const amount = cells[6].querySelector('div').textContent.trim();

                csv += `"${requestNumber}","${title}","${requester}","${priority}","${status}","${date}","${amount}"\n`;
            }
        }
    });

    // Download CSV
    const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    const url = URL.createObjectURL(blob);
    link.setAttribute('href', url);
    link.setAttribute('download', 'purchase_requests_' + new Date().toISOString().split('T')[0] + '.csv');
    link.style.visibility = 'hidden';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}
</script>

<style>
/* Priority badges */
.priority-badge.priority-low {
    background: #f0f9ff;
    color: #0369a1;
}
.priority-badge.priority-medium {
    background: #fef3c7;
    color: #92400e;
}
.priority-badge.priority-high {
    background: #fef2f2;
    color: #991b1b;
}
.priority-badge.priority-urgent {
    background: #fee2e2;
    color: #991b1b;
}

/* Status badges */
.status-badge.status-draft {
    background: #f3f4f6;
    color: #374151;
}
.status-badge.status-pending {
    background: #fef3c7;
    color: #92400e;
}
.status-badge.status-approved {
    background: #d1fae5;
    color: #065f46;
}
.status-badge.status-rejected {
    background: #fee2e2;
    color: #991b1b;
}
.status-badge.status-cancelled {
    background: #f3f4f6;
    color: #6b7280;
}
.status-badge.status-completed {
    background: #dbeafe;
    color: #1e40af;
}
</style>
@endpush
@endsection
