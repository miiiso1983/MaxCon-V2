@extends('layouts.modern')

@section('page-title', 'ميزان المراجعة')
@section('page-description', 'ميزان المراجعة للتأكد من توازن الدفاتر المحاسبية')

@section('content')
<!-- Page Header -->
<div style="background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%); border-radius: 20px; padding: 30px; margin-bottom: 30px; color: white; position: relative; overflow: hidden;">
    <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
    <div style="position: absolute; bottom: -30px; left: -30px; width: 150px; height: 150px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>
    
    <div style="position: relative; z-index: 2;">
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    <div style="background: rgba(255,255,255,0.2); border-radius: 15px; padding: 15px; margin-left: 20px;">
                        <i class="fas fa-balance-scale" style="font-size: 32px;"></i>
                    </div>
                    <div>
                        <h1 style="font-size: 32px; font-weight: 800; margin: 0; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                            ميزان المراجعة ⚖️
                        </h1>
                        <p style="font-size: 18px; margin: 5px 0 0 0; opacity: 0.9;">
                            عرض أرصدة جميع الحسابات للتأكد من توازن الدفاتر
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
    <form method="GET" action="{{ route('tenant.inventory.accounting.reports.trial-balance') }}">
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
            
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">نوع الحساب:</label>
                <select name="account_type" style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px;">
                    <option value="">جميع الأنواع</option>
                    @foreach($accountTypes as $key => $value)
                        <option value="{{ $key }}" {{ request('account_type') == $key ? 'selected' : '' }}>{{ $value }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        
        <div style="display: flex; gap: 10px;">
            <button type="submit" style="background: #0ea5e9; color: white; padding: 10px 20px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                <i class="fas fa-search" style="margin-left: 8px;"></i>
                عرض التقرير
            </button>
            <a href="{{ route('tenant.inventory.accounting.reports.trial-balance') }}" style="background: #6b7280; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: 600;">
                <i class="fas fa-refresh" style="margin-left: 8px;"></i>
                إعادة تعيين
            </a>
        </div>
    </form>
</div>

<!-- Report Header -->
<div class="content-card" style="margin-bottom: 20px;" id="reportContent">
    <div style="text-align: center; margin-bottom: 30px; padding-bottom: 20px; border-bottom: 2px solid #e2e8f0;">
        <h2 style="color: #2d3748; margin: 0 0 10px 0; font-size: 24px; font-weight: 800;">ميزان المراجعة</h2>
        <p style="color: #6b7280; margin: 0; font-size: 16px;">
            من {{ \Carbon\Carbon::parse($dateFrom)->format('Y/m/d') }} إلى {{ \Carbon\Carbon::parse($dateTo)->format('Y/m/d') }}
        </p>
        @if(request('cost_center_id'))
            <p style="color: #6b7280; margin: 5px 0 0 0; font-size: 14px;">
                مركز التكلفة: {{ $costCenters->find(request('cost_center_id'))->name }}
            </p>
        @endif
    </div>
    
    <!-- Trial Balance Table -->
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                    <th style="padding: 15px; text-align: right; font-weight: 600; color: #2d3748; border: 1px solid #e2e8f0;">رمز الحساب</th>
                    <th style="padding: 15px; text-align: right; font-weight: 600; color: #2d3748; border: 1px solid #e2e8f0;">اسم الحساب</th>
                    <th style="padding: 15px; text-align: right; font-weight: 600; color: #2d3748; border: 1px solid #e2e8f0;">مدين</th>
                    <th style="padding: 15px; text-align: right; font-weight: 600; color: #2d3748; border: 1px solid #e2e8f0;">دائن</th>
                </tr>
            </thead>
            <tbody>
                @foreach($trialBalanceData as $item)
                    <tr style="border-bottom: 1px solid #e2e8f0;">
                        <td style="padding: 12px 15px; font-weight: 600; color: #4a5568; border: 1px solid #e2e8f0;">{{ $item['account']->account_code }}</td>
                        <td style="padding: 12px 15px; color: #2d3748; border: 1px solid #e2e8f0;">
                            {{ $item['account']->account_name }}
                        </td>
                        <td style="padding: 12px 15px; text-align: right; color: #059669; font-weight: 600; border: 1px solid #e2e8f0;">
                            {{ $item['debit_balance'] > 0 ? number_format($item['debit_balance'], 2) : '-' }}
                        </td>
                        <td style="padding: 12px 15px; text-align: right; color: #dc2626; font-weight: 600; border: 1px solid #e2e8f0;">
                            {{ $item['credit_balance'] > 0 ? number_format($item['credit_balance'], 2) : '-' }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr style="background: #1e40af; color: white; border-top: 3px solid #1e40af;">
                    <td colspan="2" style="padding: 15px; font-weight: 800; font-size: 16px; border: 1px solid #1e40af;">
                        الإجمالي العام
                    </td>
                    <td style="padding: 15px; text-align: right; font-weight: 800; font-size: 16px; border: 1px solid #1e40af;">
                        {{ number_format($totalDebits, 2) }}
                    </td>
                    <td style="padding: 15px; text-align: right; font-weight: 800; font-size: 16px; border: 1px solid #1e40af;">
                        {{ number_format($totalCredits, 2) }}
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
    
    <!-- Balance Check -->
    <div style="margin-top: 30px; padding: 20px; background: {{ abs($totalDebits - $totalCredits) < 0.01 ? '#dcfce7' : '#fee2e2' }}; border-radius: 8px; text-align: center;">
        @if(abs($totalDebits - $totalCredits) < 0.01)
            <div style="color: #166534; font-size: 18px; font-weight: 700; margin-bottom: 8px;">
                <i class="fas fa-check-circle" style="margin-left: 8px;"></i>
                الميزان متوازن ✅
            </div>
            <p style="color: #14532d; margin: 0;">إجمالي المدين = إجمالي الدائن = {{ number_format($totalDebits, 2) }} دينار عراقي</p>
        @else
            <div style="color: #991b1b; font-size: 18px; font-weight: 700; margin-bottom: 8px;">
                <i class="fas fa-exclamation-triangle" style="margin-left: 8px;"></i>
                الميزان غير متوازن ⚠️
            </div>
            <p style="color: #7f1d1d; margin: 0;">
                الفرق: {{ number_format(abs($totalDebits - $totalCredits), 2) }} دينار عراقي
                <br>
                المدين: {{ number_format($totalDebits, 2) }} | الدائن: {{ number_format($totalCredits, 2) }}
            </p>
        @endif
    </div>
    
    <!-- Report Footer -->
    <div style="margin-top: 30px; padding-top: 20px; border-top: 2px solid #e2e8f0; text-align: center; color: #6b7280; font-size: 14px;">
        <p style="margin: 0;">تم إنتاج التقرير في: {{ now()->format('Y-m-d H:i:s') }}</p>
        <p style="margin: 5px 0 0 0;">النظام المحاسبي المتكامل - MaxCon ERP</p>
    </div>
</div>

<script>
// Updated: {{ now()->timestamp }}
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
    // This would typically make an AJAX call to export the data
    alert('سيتم تصدير التقرير إلى Excel قريباً...');
}
</script>
@endsection
