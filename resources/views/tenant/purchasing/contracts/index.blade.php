@extends('layouts.modern')

@section('page-title', 'العقود والاتفاقيات')
@section('page-description', 'إدارة عقود الموردين والاتفاقيات التجارية')

@section('content')
<!-- Page Header -->
<div style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); border-radius: 20px; padding: 30px; margin-bottom: 30px; color: white; position: relative; overflow: hidden;">
    <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
    <div style="position: absolute; bottom: -30px; left: -30px; width: 150px; height: 150px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>
    
    <div style="position: relative; z-index: 2;">
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    <div style="background: rgba(255,255,255,0.2); border-radius: 15px; padding: 15px; margin-left: 20px;">
                        <i class="fas fa-file-contract" style="font-size: 32px;"></i>
                    </div>
                    <div>
                        <h1 style="font-size: 32px; font-weight: 800; margin: 0; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                            العقود والاتفاقيات 📄
                        </h1>
                        <p style="font-size: 18px; margin: 5px 0 0 0; opacity: 0.9;">
                            إدارة عقود الموردين والاتفاقيات التجارية
                        </p>
                    </div>
                </div>
                
                <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-calendar-alt" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px; font-weight: 600;">تتبع المواعيد</span>
                    </div>
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-bell" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px;">تنبيهات التجديد</span>
                    </div>
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-chart-bar" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px;">متابعة الأداء</span>
                    </div>
                </div>
            </div>
            
            <div style="display: flex; gap: 15px;">
                <a href="{{ route('tenant.purchasing.contracts.create') }}" style="background: rgba(255,255,255,0.2); color: white; padding: 15px 25px; border-radius: 15px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3);">
                    <i class="fas fa-plus"></i>
                    عقد جديد
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px;">
    <div style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); border-radius: 15px; padding: 20px; color: white; position: relative; overflow: hidden;">
        <div style="position: absolute; top: -10px; right: -10px; width: 60px; height: 60px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
        <div style="position: relative; z-index: 2;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                <i class="fas fa-list" style="font-size: 24px; opacity: 0.8;"></i>
                <span style="font-size: 12px; opacity: 0.8;">إجمالي العقود</span>
            </div>
            <div style="font-size: 28px; font-weight: 700;">{{ number_format($stats['total']) }}</div>
            <div style="font-size: 12px; opacity: 0.8;">عقد</div>
        </div>
    </div>
    
    <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius: 15px; padding: 20px; color: white; position: relative; overflow: hidden;">
        <div style="position: absolute; top: -10px; right: -10px; width: 60px; height: 60px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
        <div style="position: relative; z-index: 2;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                <i class="fas fa-check-circle" style="font-size: 24px; opacity: 0.8;"></i>
                <span style="font-size: 12px; opacity: 0.8;">نشط</span>
            </div>
            <div style="font-size: 28px; font-weight: 700;">{{ number_format($stats['active']) }}</div>
            <div style="font-size: 12px; opacity: 0.8;">عقد نشط</div>
        </div>
    </div>
    
    <div style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); border-radius: 15px; padding: 20px; color: white; position: relative; overflow: hidden;">
        <div style="position: absolute; top: -10px; right: -10px; width: 60px; height: 60px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
        <div style="position: relative; z-index: 2;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                <i class="fas fa-times-circle" style="font-size: 24px; opacity: 0.8;"></i>
                <span style="font-size: 12px; opacity: 0.8;">منتهي الصلاحية</span>
            </div>
            <div style="font-size: 28px; font-weight: 700;">{{ number_format($stats['expired']) }}</div>
            <div style="font-size: 12px; opacity: 0.8;">عقد منتهي</div>
        </div>
    </div>
    
    <div style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); border-radius: 15px; padding: 20px; color: white; position: relative; overflow: hidden;">
        <div style="position: absolute; top: -10px; right: -10px; width: 60px; height: 60px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
        <div style="position: relative; z-index: 2;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                <i class="fas fa-exclamation-triangle" style="font-size: 24px; opacity: 0.8;"></i>
                <span style="font-size: 12px; opacity: 0.8;">ينتهي قريباً</span>
            </div>
            <div style="font-size: 28px; font-weight: 700;">{{ number_format($stats['expiring_soon']) }}</div>
            <div style="font-size: 12px; opacity: 0.8;">عقد</div>
        </div>
    </div>
</div>

<!-- Contracts Table -->
<div class="content-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
        <h3 style="margin: 0; color: #2d3748; font-size: 20px; font-weight: 700;">
            <i class="fas fa-list" style="margin-left: 10px; color: #8b5cf6;"></i>
            قائمة العقود
        </h3>

        <div style="display: flex; gap: 10px;">
            <input type="text" id="searchInput" placeholder="البحث في العقود..."
                   style="padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 8px; width: 250px;">

            <select id="statusFilter" style="padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 8px;">
                <option value="">جميع الحالات</option>
                <option value="draft">مسودة</option>
                <option value="pending">في الانتظار</option>
                <option value="active">نشط</option>
                <option value="expired">منتهي</option>
                <option value="terminated">مُنهى</option>
                <option value="cancelled">ملغي</option>
            </select>
        </div>
    </div>

    @if($contracts->count() > 0)
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                <thead style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); color: white;">
                    <tr>
                        <th style="padding: 15px; text-align: right; font-weight: 600;">رقم العقد</th>
                        <th style="padding: 15px; text-align: right; font-weight: 600;">العنوان</th>
                        <th style="padding: 15px; text-align: right; font-weight: 600;">المورد</th>
                        <th style="padding: 15px; text-align: right; font-weight: 600;">النوع</th>
                        <th style="padding: 15px; text-align: center; font-weight: 600;">الحالة</th>
                        <th style="padding: 15px; text-align: center; font-weight: 600;">تاريخ البداية</th>
                        <th style="padding: 15px; text-align: center; font-weight: 600;">تاريخ الانتهاء</th>
                        <th style="padding: 15px; text-align: center; font-weight: 600;">القيمة</th>
                        <th style="padding: 15px; text-align: center; font-weight: 600;">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($contracts as $contract)
                    <tr style="border-bottom: 1px solid #f3f4f6; transition: background-color 0.2s;"
                        onmouseover="this.style.backgroundColor='#f9fafb'"
                        onmouseout="this.style.backgroundColor='white'">

                        <td style="padding: 12px 15px;">
                            <span style="font-weight: 600; color: #374151;">{{ $contract->contract_number }}</span>
                        </td>

                        <td style="padding: 12px 15px;">
                            <div>
                                <div style="font-weight: 600; color: #111827;">{{ $contract->title }}</div>
                                @if($contract->description)
                                    <div style="font-size: 12px; color: #6b7280; margin-top: 2px;">
                                        {{ Str::limit($contract->description, 50) }}
                                    </div>
                                @endif
                            </div>
                        </td>

                        <td style="padding: 12px 15px;">
                            <span style="color: #374151;">{{ $contract->supplier->name ?? 'غير محدد' }}</span>
                        </td>

                        <td style="padding: 12px 15px;">
                            <span style="color: #6b7280;">{{ $contract->type_text }}</span>
                        </td>

                        <td style="padding: 12px 15px; text-align: center;">
                            <span style="background: {{ $contract->status === 'active' ? '#dcfce7' : ($contract->status === 'expired' ? '#fecaca' : ($contract->status === 'draft' ? '#fef3c7' : '#f3f4f6')) }};
                                         color: {{ $contract->status === 'active' ? '#166534' : ($contract->status === 'expired' ? '#dc2626' : ($contract->status === 'draft' ? '#d97706' : '#6b7280')) }};
                                         padding: 4px 8px; border-radius: 6px; font-size: 12px; font-weight: 600;">
                                {{ $contract->status_text }}
                            </span>
                        </td>

                        <td style="padding: 12px 15px; text-align: center;">
                            <span style="color: #6b7280; font-size: 14px;">
                                {{ $contract->start_date->format('Y/m/d') }}
                            </span>
                        </td>

                        <td style="padding: 12px 15px; text-align: center;">
                            <span style="color: {{ $contract->is_expired ? '#dc2626' : ($contract->is_expiring_soon ? '#d97706' : '#6b7280') }}; font-size: 14px; font-weight: {{ $contract->is_expired || $contract->is_expiring_soon ? '600' : '400' }};">
                                {{ $contract->end_date->format('Y/m/d') }}
                                @if($contract->is_expiring_soon)
                                    <i class="fas fa-exclamation-triangle" style="margin-right: 5px; color: #f59e0b;"></i>
                                @elseif($contract->is_expired)
                                    <i class="fas fa-times-circle" style="margin-right: 5px; color: #ef4444;"></i>
                                @endif
                            </span>
                        </td>

                        <td style="padding: 12px 15px; text-align: center;">
                            <span style="font-weight: 600; color: #059669;">
                                {{ number_format($contract->contract_value, 0) }} {{ $contract->currency }}
                            </span>
                        </td>

                        <td style="padding: 12px 15px; text-align: center;">
                            <div style="display: flex; gap: 5px; justify-content: center;">
                                <a href="{{ route('tenant.purchasing.contracts.show', $contract) }}"
                                   style="background: #3b82f6; color: white; padding: 6px 10px; border-radius: 6px; text-decoration: none; font-size: 12px;"
                                   title="عرض">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('tenant.purchasing.contracts.edit', $contract) }}"
                                   style="background: #f59e0b; color: white; padding: 6px 10px; border-radius: 6px; text-decoration: none; font-size: 12px;"
                                   title="تعديل">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('tenant.purchasing.contracts.destroy', $contract) }}"
                                      style="display: inline;"
                                      onsubmit="return confirm('هل أنت متأكد من حذف هذا العقد؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            style="background: #ef4444; color: white; padding: 6px 10px; border-radius: 6px; border: none; cursor: pointer; font-size: 12px;"
                                            title="حذف">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div style="margin-top: 20px; display: flex; justify-content: center;">
            {{ $contracts->links() }}
        </div>
    @else
        <div style="text-align: center; padding: 60px 40px; color: #6b7280;">
            <div style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); color: white; border-radius: 50%; width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; font-size: 32px;">
                <i class="fas fa-file-contract"></i>
            </div>
            <h3 style="margin: 0 0 10px 0; color: #2d3748; font-size: 20px; font-weight: 700;">لا توجد عقود بعد</h3>
            <p style="margin: 0 0 20px 0; color: #6b7280;">ابدأ بإنشاء أول عقد مع الموردين</p>
            <a href="{{ route('tenant.purchasing.contracts.create') }}"
               style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 8px;">
                <i class="fas fa-plus"></i>
                إنشاء عقد جديد
            </a>
        </div>
    @endif
</div>

<script>
// Search functionality
document.getElementById('searchInput').addEventListener('keyup', function() {
    const searchTerm = this.value.toLowerCase();
    const rows = document.querySelectorAll('tbody tr');

    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchTerm) ? '' : 'none';
    });
});

// Status filter
document.getElementById('statusFilter').addEventListener('change', function() {
    const selectedStatus = this.value;
    const rows = document.querySelectorAll('tbody tr');

    rows.forEach(row => {
        if (!selectedStatus) {
            row.style.display = '';
        } else {
            const statusCell = row.querySelector('td:nth-child(5) span');
            const statusText = statusCell ? statusCell.textContent.trim() : '';

            // Map Arabic status to English
            const statusMap = {
                'مسودة': 'draft',
                'في الانتظار': 'pending',
                'نشط': 'active',
                'منتهي': 'expired',
                'مُنهى': 'terminated',
                'ملغي': 'cancelled'
            };

            const englishStatus = Object.keys(statusMap).find(key => statusText.includes(key));
            const mappedStatus = englishStatus ? statusMap[englishStatus] : '';

            row.style.display = mappedStatus === selectedStatus ? '' : 'none';
        }
    });
});
</script>
@endsection
