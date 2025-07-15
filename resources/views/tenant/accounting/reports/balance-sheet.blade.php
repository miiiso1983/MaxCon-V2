@extends('layouts.modern')

@section('page-title', 'الميزانية العمومية')
@section('page-description', 'الميزانية العمومية والمركز المالي')

@section('content')
<!-- Page Header -->
<div style="background: linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%); border-radius: 20px; padding: 30px; margin-bottom: 30px; color: white; position: relative; overflow: hidden;">
    <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
    <div style="position: absolute; bottom: -30px; left: -30px; width: 150px; height: 150px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>
    
    <div style="position: relative; z-index: 2;">
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    <div style="background: rgba(255,255,255,0.2); border-radius: 15px; padding: 15px; margin-left: 20px;">
                        <i class="fas fa-building" style="font-size: 32px;"></i>
                    </div>
                    <div>
                        <h1 style="font-size: 32px; font-weight: 800; margin: 0; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                            الميزانية العمومية 🏛️
                        </h1>
                        <p style="font-size: 18px; margin: 5px 0 0 0; opacity: 0.9;">
                            المركز المالي للشركة في تاريخ محدد
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
    <form method="GET" action="{{ route('tenant.inventory.accounting.reports.balance-sheet') }}">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 20px;">
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">تاريخ الميزانية:</label>
                <input type="date" name="as_of_date" value="{{ request('as_of_date', $asOfDate) }}" 
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
            <button type="submit" style="background: #7c3aed; color: white; padding: 10px 20px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                <i class="fas fa-search" style="margin-left: 8px;"></i>
                عرض التقرير
            </button>
            <a href="{{ route('tenant.inventory.accounting.reports.balance-sheet') }}" style="background: #6b7280; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: 600;">
                <i class="fas fa-refresh" style="margin-left: 8px;"></i>
                إعادة تعيين
            </a>
        </div>
    </form>
</div>

<!-- Balance Sheet -->
<div class="content-card" id="reportContent">
    <div style="text-align: center; margin-bottom: 30px; padding-bottom: 20px; border-bottom: 2px solid #e2e8f0;">
        <h2 style="color: #2d3748; margin: 0 0 10px 0; font-size: 24px; font-weight: 800;">الميزانية العمومية</h2>
        <p style="color: #6b7280; margin: 0; font-size: 16px;">
            كما في {{ \Carbon\Carbon::parse($asOfDate)->format('Y/m/d') }}
        </p>
        @if(request('cost_center_id'))
            <p style="color: #6b7280; margin: 5px 0 0 0; font-size: 14px;">
                مركز التكلفة: {{ $costCenters->find(request('cost_center_id'))->name }}
            </p>
        @endif
    </div>
    
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
        <!-- Assets Side -->
        <div>
            <h3 style="background: #dbeafe; color: #1e40af; padding: 15px; margin: 0 0 20px 0; font-weight: 700; border-radius: 8px; text-align: center;">
                <i class="fas fa-coins" style="margin-left: 8px;"></i>
                الأصول
            </h3>
            
            <!-- Current Assets -->
            <div style="margin-bottom: 25px;">
                <h4 style="color: #374151; font-weight: 600; margin-bottom: 15px; padding: 10px; background: #f8fafc; border-radius: 6px;">
                    الأصول المتداولة
                </h4>
                <table style="width: 100%; border-collapse: collapse;">
                    @php $totalCurrentAssets = 0; @endphp
                    @foreach($currentAssets as $asset)
                        @php $totalCurrentAssets += $asset->balance; @endphp
                        <tr style="border-bottom: 1px solid #e2e8f0;">
                            <td style="padding: 8px 12px; color: #2d3748;">{{ $asset->account_name }}</td>
                            <td style="padding: 8px 12px; text-align: left; font-weight: 600; color: #059669;">
                                {{ number_format($asset->balance, 2) }}
                            </td>
                        </tr>
                    @endforeach
                    <tr style="background: #f0fdf4; border-top: 2px solid #22c55e;">
                        <td style="padding: 12px; font-weight: 700; color: #166534;">إجمالي الأصول المتداولة</td>
                        <td style="padding: 12px; text-align: left; font-weight: 800; color: #166534;">
                            {{ number_format($totalCurrentAssets, 2) }}
                        </td>
                    </tr>
                </table>
            </div>
            
            <!-- Non-Current Assets -->
            <div style="margin-bottom: 25px;">
                <h4 style="color: #374151; font-weight: 600; margin-bottom: 15px; padding: 10px; background: #f8fafc; border-radius: 6px;">
                    الأصول غير المتداولة
                </h4>
                <table style="width: 100%; border-collapse: collapse;">
                    @php $totalNonCurrentAssets = 0; @endphp
                    @foreach($nonCurrentAssets as $asset)
                        @php $totalNonCurrentAssets += $asset->balance; @endphp
                        <tr style="border-bottom: 1px solid #e2e8f0;">
                            <td style="padding: 8px 12px; color: #2d3748;">{{ $asset->account_name }}</td>
                            <td style="padding: 8px 12px; text-align: left; font-weight: 600; color: #059669;">
                                {{ number_format($asset->balance, 2) }}
                            </td>
                        </tr>
                    @endforeach
                    <tr style="background: #f0fdf4; border-top: 2px solid #22c55e;">
                        <td style="padding: 12px; font-weight: 700; color: #166534;">إجمالي الأصول غير المتداولة</td>
                        <td style="padding: 12px; text-align: left; font-weight: 800; color: #166534;">
                            {{ number_format($totalNonCurrentAssets, 2) }}
                        </td>
                    </tr>
                </table>
            </div>
            
            <!-- Total Assets -->
            @php $totalAssets = $totalCurrentAssets + $totalNonCurrentAssets; @endphp
            <div style="background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%); border: 3px solid #3b82f6; border-radius: 12px; padding: 20px; text-align: center;">
                <h3 style="color: #1e40af; margin: 0 0 10px 0; font-size: 20px; font-weight: 800;">إجمالي الأصول</h3>
                <div style="color: #1e40af; font-size: 28px; font-weight: 900;">{{ number_format($totalAssets, 2) }} دينار</div>
            </div>
        </div>
        
        <!-- Liabilities & Equity Side -->
        <div>
            <h3 style="background: #fef3c7; color: #d97706; padding: 15px; margin: 0 0 20px 0; font-weight: 700; border-radius: 8px; text-align: center;">
                <i class="fas fa-file-invoice-dollar" style="margin-left: 8px;"></i>
                الخصوم وحقوق الملكية
            </h3>
            
            <!-- Current Liabilities -->
            <div style="margin-bottom: 25px;">
                <h4 style="color: #374151; font-weight: 600; margin-bottom: 15px; padding: 10px; background: #f8fafc; border-radius: 6px;">
                    الخصوم المتداولة
                </h4>
                <table style="width: 100%; border-collapse: collapse;">
                    @php $totalCurrentLiabilities = 0; @endphp
                    @foreach($currentLiabilities as $liability)
                        @php $totalCurrentLiabilities += $liability->balance; @endphp
                        <tr style="border-bottom: 1px solid #e2e8f0;">
                            <td style="padding: 8px 12px; color: #2d3748;">{{ $liability->account_name }}</td>
                            <td style="padding: 8px 12px; text-align: left; font-weight: 600; color: #dc2626;">
                                {{ number_format($liability->balance, 2) }}
                            </td>
                        </tr>
                    @endforeach
                    <tr style="background: #fef2f2; border-top: 2px solid #ef4444;">
                        <td style="padding: 12px; font-weight: 700; color: #991b1b;">إجمالي الخصوم المتداولة</td>
                        <td style="padding: 12px; text-align: left; font-weight: 800; color: #991b1b;">
                            {{ number_format($totalCurrentLiabilities, 2) }}
                        </td>
                    </tr>
                </table>
            </div>
            
            <!-- Non-Current Liabilities -->
            <div style="margin-bottom: 25px;">
                <h4 style="color: #374151; font-weight: 600; margin-bottom: 15px; padding: 10px; background: #f8fafc; border-radius: 6px;">
                    الخصوم غير المتداولة
                </h4>
                <table style="width: 100%; border-collapse: collapse;">
                    @php $totalNonCurrentLiabilities = 0; @endphp
                    @foreach($nonCurrentLiabilities as $liability)
                        @php $totalNonCurrentLiabilities += $liability->balance; @endphp
                        <tr style="border-bottom: 1px solid #e2e8f0;">
                            <td style="padding: 8px 12px; color: #2d3748;">{{ $liability->account_name }}</td>
                            <td style="padding: 8px 12px; text-align: left; font-weight: 600; color: #dc2626;">
                                {{ number_format($liability->balance, 2) }}
                            </td>
                        </tr>
                    @endforeach
                    <tr style="background: #fef2f2; border-top: 2px solid #ef4444;">
                        <td style="padding: 12px; font-weight: 700; color: #991b1b;">إجمالي الخصوم غير المتداولة</td>
                        <td style="padding: 12px; text-align: left; font-weight: 800; color: #991b1b;">
                            {{ number_format($totalNonCurrentLiabilities, 2) }}
                        </td>
                    </tr>
                </table>
            </div>
            
            <!-- Equity -->
            <div style="margin-bottom: 25px;">
                <h4 style="color: #374151; font-weight: 600; margin-bottom: 15px; padding: 10px; background: #f8fafc; border-radius: 6px;">
                    حقوق الملكية
                </h4>
                <table style="width: 100%; border-collapse: collapse;">
                    @php $totalEquity = 0; @endphp
                    @foreach($equityAccounts as $equity)
                        @php $totalEquity += $equity->balance; @endphp
                        <tr style="border-bottom: 1px solid #e2e8f0;">
                            <td style="padding: 8px 12px; color: #2d3748;">{{ $equity->account_name }}</td>
                            <td style="padding: 8px 12px; text-align: left; font-weight: 600; color: #7c3aed;">
                                {{ number_format($equity->balance, 2) }}
                            </td>
                        </tr>
                    @endforeach
                    <tr style="background: #faf5ff; border-top: 2px solid #8b5cf6;">
                        <td style="padding: 12px; font-weight: 700; color: #6b21a8;">إجمالي حقوق الملكية</td>
                        <td style="padding: 12px; text-align: left; font-weight: 800; color: #6b21a8;">
                            {{ number_format($totalEquity, 2) }}
                        </td>
                    </tr>
                </table>
            </div>
            
            <!-- Total Liabilities & Equity -->
            @php $totalLiabilitiesEquity = $totalCurrentLiabilities + $totalNonCurrentLiabilities + $totalEquity; @endphp
            <div style="background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); border: 3px solid #f59e0b; border-radius: 12px; padding: 20px; text-align: center;">
                <h3 style="color: #d97706; margin: 0 0 10px 0; font-size: 20px; font-weight: 800;">إجمالي الخصوم وحقوق الملكية</h3>
                <div style="color: #d97706; font-size: 28px; font-weight: 900;">{{ number_format($totalLiabilitiesEquity, 2) }} دينار</div>
            </div>
        </div>
    </div>
    
    <!-- Balance Check -->
    @php $isBalanced = abs($totalAssets - $totalLiabilitiesEquity) < 0.01; @endphp
    <div style="margin-top: 30px; padding: 20px; background: {{ $isBalanced ? '#dcfce7' : '#fee2e2' }}; border-radius: 8px; text-align: center;">
        @if($isBalanced)
            <div style="color: #166534; font-size: 18px; font-weight: 700; margin-bottom: 8px;">
                <i class="fas fa-check-circle" style="margin-left: 8px;"></i>
                الميزانية متوازنة ✅
            </div>
            <p style="color: #14532d; margin: 0;">الأصول = الخصوم + حقوق الملكية = {{ number_format($totalAssets, 2) }} دينار</p>
        @else
            <div style="color: #991b1b; font-size: 18px; font-weight: 700; margin-bottom: 8px;">
                <i class="fas fa-exclamation-triangle" style="margin-left: 8px;"></i>
                الميزانية غير متوازنة ⚠️
            </div>
            <p style="color: #7f1d1d; margin: 0;">
                الفرق: {{ number_format(abs($totalAssets - $totalLiabilitiesEquity), 2) }} دينار
            </p>
        @endif
    </div>
    
    <!-- Financial Ratios -->
    <div style="margin-top: 30px; display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
        <div style="background: #f8fafc; border: 2px solid #e2e8f0; border-radius: 12px; padding: 20px; text-align: center;">
            <div style="color: #6366f1; font-size: 14px; font-weight: 600; margin-bottom: 8px;">نسبة السيولة</div>
            <div style="color: #4f46e5; font-size: 24px; font-weight: 800;">
                {{ $totalCurrentLiabilities > 0 ? number_format($totalCurrentAssets / $totalCurrentLiabilities, 2) : '∞' }}
            </div>
        </div>
        
        <div style="background: #f8fafc; border: 2px solid #e2e8f0; border-radius: 12px; padding: 20px; text-align: center;">
            <div style="color: #8b5cf6; font-size: 14px; font-weight: 600; margin-bottom: 8px;">نسبة المديونية</div>
            <div style="color: #7c3aed; font-size: 24px; font-weight: 800;">
                {{ $totalAssets > 0 ? number_format((($totalCurrentLiabilities + $totalNonCurrentLiabilities) / $totalAssets) * 100, 1) : 0 }}%
            </div>
        </div>
        
        <div style="background: #f8fafc; border: 2px solid #e2e8f0; border-radius: 12px; padding: 20px; text-align: center;">
            <div style="color: #ec4899; font-size: 14px; font-weight: 600; margin-bottom: 8px;">نسبة حقوق الملكية</div>
            <div style="color: #db2777; font-size: 24px; font-weight: 800;">
                {{ $totalAssets > 0 ? number_format(($totalEquity / $totalAssets) * 100, 1) : 0 }}%
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
    alert('سيتم تصدير التقرير إلى Excel قريباً...');
}
</script>
@endsection
