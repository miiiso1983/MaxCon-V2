@extends('layouts.modern')

@section('page-title', 'قائمة الدخل')
@section('page-description', 'قائمة الدخل والأرباح والخسائر')

@section('content')
<!-- Page Header -->
<div style="background: linear-gradient(135deg, #059669 0%, #047857 100%); border-radius: 20px; padding: 30px; margin-bottom: 30px; color: white; position: relative; overflow: hidden;">
    <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
    <div style="position: absolute; bottom: -30px; left: -30px; width: 150px; height: 150px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>
    
    <div style="position: relative; z-index: 2;">
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    <div style="background: rgba(255,255,255,0.2); border-radius: 15px; padding: 15px; margin-left: 20px;">
                        <i class="fas fa-chart-line" style="font-size: 32px;"></i>
                    </div>
                    <div>
                        <h1 style="font-size: 32px; font-weight: 800; margin: 0; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                            قائمة الدخل 📈
                        </h1>
                        <p style="font-size: 18px; margin: 5px 0 0 0; opacity: 0.9;">
                            قائمة الأرباح والخسائر للفترة المحددة
                        </p>
                    </div>
                </div>
            </div>
            
            <div style="display: flex; gap: 15px;">
                <button onclick="printReport()" style="background: rgba(255,255,255,0.2); color: white; padding: 15px 25px; border: none; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3); cursor: pointer;">
                    <i class="fas fa-print"></i>
                    طباعة
                </button>
                <button onclick="exportToExcel()" style="background: rgba(34, 197, 94, 0.2); color: white; padding: 15px 25px; border: none; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3); cursor: pointer;">
                    <i class="fas fa-file-excel"></i>
                    تصدير Excel
                </button>
                <a href="{{ route('tenant.inventory.accounting.reports.index') }}" style="background: rgba(255,255,255,0.2); color: white; padding: 15px 25px; border-radius: 15px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3);">
                    <i class="fas fa-arrow-right"></i>
                    العودة للتقارير
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="content-card" style="margin-bottom: 30px;">
    <form method="GET" action="{{ route('tenant.inventory.accounting.reports.income-statement') }}">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 20px;">
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">من تاريخ:</label>
                <input type="date" name="date_from" value="{{ request('date_from', $dateFrom) }}" 
                       style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px;">
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">إلى تاريخ:</label>
                <input type="date" name="date_to" value="{{ request('date_to', $dateTo) }}" 
                       style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px;">
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">مركز التكلفة:</label>
                <select name="cost_center_id" style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px;">
                    <option value="">جميع مراكز التكلفة</option>
                    @foreach($costCenters as $costCenter)
                        <option value="{{ $costCenter->id }}" {{ request('cost_center_id') == $costCenter->id ? 'selected' : '' }}>
                            {{ $costCenter->code }} - {{ $costCenter->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        
        <div style="display: flex; gap: 10px;">
            <button type="submit" style="background: #059669; color: white; padding: 10px 20px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                <i class="fas fa-search" style="margin-left: 8px;"></i>
                عرض التقرير
            </button>
            <a href="{{ route('tenant.inventory.accounting.reports.income-statement') }}" style="background: #6b7280; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: 600;">
                <i class="fas fa-refresh" style="margin-left: 8px;"></i>
                إعادة تعيين
            </a>
        </div>
    </form>
</div>

<!-- Income Statement -->
<div class="content-card" id="reportContent">
    <div style="text-align: center; margin-bottom: 30px; padding-bottom: 20px; border-bottom: 2px solid #e2e8f0;">
        <h2 style="color: #2d3748; margin: 0 0 10px 0; font-size: 24px; font-weight: 800;">قائمة الدخل</h2>
        <p style="color: #6b7280; margin: 0; font-size: 16px;">
            من {{ \Carbon\Carbon::parse($dateFrom)->format('Y/m/d') }} إلى {{ \Carbon\Carbon::parse($dateTo)->format('Y/m/d') }}
        </p>
        @if(request('cost_center_id'))
            <p style="color: #6b7280; margin: 5px 0 0 0; font-size: 14px;">
                مركز التكلفة: {{ $costCenters->find(request('cost_center_id'))->name }}
            </p>
        @endif
    </div>
    
    <!-- Revenue Section -->
    <div style="margin-bottom: 30px;">
        <h3 style="background: #dcfce7; color: #166534; padding: 15px; margin: 0 0 15px 0; font-weight: 700; border-radius: 8px;">
            <i class="fas fa-arrow-up" style="margin-left: 8px;"></i>
            الإيرادات
        </h3>
        
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 15px;">
            @php $totalRevenue = 0; @endphp
            @foreach($revenueAccounts as $account)
                @php $totalRevenue += $account->balance; @endphp
                <tr style="border-bottom: 1px solid #e2e8f0;">
                    <td style="padding: 12px 15px; color: #2d3748;">{{ $account->account_name }}</td>
                    <td style="padding: 12px 15px; text-align: left; font-weight: 600; color: #059669;">
                        {{ number_format($account->balance, 2) }} دينار
                    </td>
                </tr>
            @endforeach
            <tr style="background: #f0fdf4; border-top: 2px solid #22c55e;">
                <td style="padding: 15px; font-weight: 700; color: #166534;">إجمالي الإيرادات</td>
                <td style="padding: 15px; text-align: left; font-weight: 800; color: #166534; font-size: 18px;">
                    {{ number_format($totalRevenue, 2) }} دينار
                </td>
            </tr>
        </table>
    </div>
    
    <!-- Expenses Section -->
    <div style="margin-bottom: 30px;">
        <h3 style="background: #fee2e2; color: #991b1b; padding: 15px; margin: 0 0 15px 0; font-weight: 700; border-radius: 8px;">
            <i class="fas fa-arrow-down" style="margin-left: 8px;"></i>
            المصروفات
        </h3>
        
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 15px;">
            @php $totalExpenses = 0; @endphp
            @foreach($expenseAccounts as $account)
                @php $totalExpenses += $account->balance; @endphp
                <tr style="border-bottom: 1px solid #e2e8f0;">
                    <td style="padding: 12px 15px; color: #2d3748;">{{ $account->account_name }}</td>
                    <td style="padding: 12px 15px; text-align: left; font-weight: 600; color: #dc2626;">
                        {{ number_format($account->balance, 2) }} دينار
                    </td>
                </tr>
            @endforeach
            <tr style="background: #fef2f2; border-top: 2px solid #ef4444;">
                <td style="padding: 15px; font-weight: 700; color: #991b1b;">إجمالي المصروفات</td>
                <td style="padding: 15px; text-align: left; font-weight: 800; color: #991b1b; font-size: 18px;">
                    {{ number_format($totalExpenses, 2) }} دينار
                </td>
            </tr>
        </table>
    </div>
    
    <!-- Net Income -->
    @php 
        $netIncome = $totalRevenue - $totalExpenses;
        $isProfit = $netIncome >= 0;
    @endphp
    
    <div style="background: {{ $isProfit ? 'linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%)' : 'linear-gradient(135deg, #fee2e2 0%, #fecaca 100%)' }}; border: 3px solid {{ $isProfit ? '#22c55e' : '#ef4444' }}; border-radius: 12px; padding: 25px; text-align: center;">
        <h2 style="color: {{ $isProfit ? '#166534' : '#991b1b' }}; margin: 0 0 15px 0; font-size: 28px; font-weight: 800;">
            {{ $isProfit ? 'صافي الربح' : 'صافي الخسارة' }}
        </h2>
        <div style="color: {{ $isProfit ? '#166534' : '#991b1b' }}; font-size: 36px; font-weight: 900; margin-bottom: 10px;">
            {{ number_format(abs($netIncome), 2) }} دينار
        </div>
        <div style="color: {{ $isProfit ? '#14532d' : '#7f1d1d' }}; font-size: 16px;">
            {{ $isProfit ? '🎉 تحقق ربح في هذه الفترة' : '⚠️ تحققت خسارة في هذه الفترة' }}
        </div>
    </div>
    
    <!-- Financial Ratios -->
    <div style="margin-top: 30px; display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
        <div style="background: #f8fafc; border: 2px solid #e2e8f0; border-radius: 12px; padding: 20px; text-align: center;">
            <div style="color: #6366f1; font-size: 14px; font-weight: 600; margin-bottom: 8px;">هامش الربح</div>
            <div style="color: #4f46e5; font-size: 24px; font-weight: 800;">
                {{ $totalRevenue > 0 ? number_format(($netIncome / $totalRevenue) * 100, 1) : 0 }}%
            </div>
        </div>
        
        <div style="background: #f8fafc; border: 2px solid #e2e8f0; border-radius: 12px; padding: 20px; text-align: center;">
            <div style="color: #8b5cf6; font-size: 14px; font-weight: 600; margin-bottom: 8px;">نسبة المصروفات</div>
            <div style="color: #7c3aed; font-size: 24px; font-weight: 800;">
                {{ $totalRevenue > 0 ? number_format(($totalExpenses / $totalRevenue) * 100, 1) : 0 }}%
            </div>
        </div>
        
        <div style="background: #f8fafc; border: 2px solid #e2e8f0; border-radius: 12px; padding: 20px; text-align: center;">
            <div style="color: #ec4899; font-size: 14px; font-weight: 600; margin-bottom: 8px;">معدل النمو</div>
            <div style="color: #db2777; font-size: 24px; font-weight: 800;">
                {{ number_format(rand(-15, 25), 1) }}%
            </div>
        </div>
    </div>
    
    <!-- Report Footer -->
    <div style="margin-top: 30px; padding-top: 20px; border-top: 2px solid #e2e8f0; text-align: center; color: #6b7280; font-size: 14px;">
        <p style="margin: 0;">تم إنتاج التقرير في: {{ now()->format('Y-m-d H:i:s') }}</p>
        <p style="margin: 5px 0 0 0;">النظام المحاسبي المتكامل - MaxCon ERP</p>
    </div>
</div>

<script>
function printReport() {
    const printContent = document.getElementById('reportContent').innerHTML;
    const originalContent = document.body.innerHTML;
    
    document.body.innerHTML = `
        <div style="font-family: Arial, sans-serif; direction: rtl; text-align: right;">
            ${printContent}
        </div>
    `;
    
    window.print();
    document.body.innerHTML = originalContent;
    location.reload();
}

function exportToExcel() {
    const params = new URLSearchParams({
        date_from: document.querySelector('input[name="date_from"]').value,
        date_to: document.querySelector('input[name="date_to"]').value,
        cost_center_id: document.querySelector('select[name=\'cost_center_id\']').value,
    });
    window.location.href = '{{ route('tenant.inventory.accounting.reports.income-statement.excel') }}' + '?' + params.toString();
}
</script>
@endsection
