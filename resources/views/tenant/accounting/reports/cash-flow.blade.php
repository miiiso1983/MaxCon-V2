@extends('layouts.modern')

@section('page-title', 'قائمة التدفقات النقدية')
@section('page-description', 'قائمة التدفقات النقدية الداخلة والخارجة')

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
                        <i class="fas fa-coins" style="font-size: 32px;"></i>
                    </div>
                    <div>
                        <h1 style="font-size: 32px; font-weight: 800; margin: 0; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                            التدفقات النقدية 💰
                        </h1>
                        <p style="font-size: 18px; margin: 5px 0 0 0; opacity: 0.9;">
                            قائمة التدفقات النقدية الداخلة والخارجة
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
    <form method="GET" action="{{ route('tenant.inventory.accounting.reports.cash-flow') }}">
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
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">طريقة العرض:</label>
                <select name="method" style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px;">
                    <option value="direct" {{ request('method', 'direct') == 'direct' ? 'selected' : '' }}>الطريقة المباشرة</option>
                    <option value="indirect" {{ request('method') == 'indirect' ? 'selected' : '' }}>الطريقة غير المباشرة</option>
                </select>
            </div>
        </div>
        
        <div style="display: flex; gap: 10px;">
            <button type="submit" style="background: #f59e0b; color: white; padding: 10px 20px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                <i class="fas fa-search" style="margin-left: 8px;"></i>
                عرض التقرير
            </button>
            <a href="{{ route('tenant.inventory.accounting.reports.cash-flow') }}" style="background: #6b7280; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: 600;">
                <i class="fas fa-refresh" style="margin-left: 8px;"></i>
                إعادة تعيين
            </a>
        </div>
    </form>
</div>

<!-- Cash Flow Statement -->
<div class="content-card" id="reportContent">
    <div style="text-align: center; margin-bottom: 30px; padding-bottom: 20px; border-bottom: 2px solid #e2e8f0;">
        <h2 style="color: #2d3748; margin: 0 0 10px 0; font-size: 24px; font-weight: 800;">قائمة التدفقات النقدية</h2>
        <p style="color: #6b7280; margin: 0; font-size: 16px;">
            من {{ \Carbon\Carbon::parse($dateFrom)->format('Y/m/d') }} إلى {{ \Carbon\Carbon::parse($dateTo)->format('Y/m/d') }}
        </p>
        <p style="color: #6b7280; margin: 5px 0 0 0; font-size: 14px;">
            {{ request('method', 'direct') == 'direct' ? 'الطريقة المباشرة' : 'الطريقة غير المباشرة' }}
        </p>
    </div>
    
    <!-- Operating Activities -->
    <div style="margin-bottom: 30px;">
        <h3 style="background: #dcfce7; color: #166534; padding: 15px; margin: 0 0 15px 0; font-weight: 700; border-radius: 8px;">
            <i class="fas fa-cogs" style="margin-left: 8px;"></i>
            التدفقات النقدية من الأنشطة التشغيلية
        </h3>
        
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 15px;">
            @if(request('method', 'direct') == 'direct')
                <!-- Direct Method -->
                <tr style="border-bottom: 1px solid #e2e8f0;">
                    <td style="padding: 12px 15px; color: #2d3748;">المقبوضات من العملاء</td>
                    <td style="padding: 12px 15px; text-align: left; font-weight: 600; color: #059669;">
                        {{ number_format($operatingCashFlows['customer_receipts'] ?? 0, 2) }} دينار
                    </td>
                </tr>
                <tr style="border-bottom: 1px solid #e2e8f0;">
                    <td style="padding: 12px 15px; color: #2d3748;">المدفوعات للموردين</td>
                    <td style="padding: 12px 15px; text-align: left; font-weight: 600; color: #dc2626;">
                        ({{ number_format($operatingCashFlows['supplier_payments'] ?? 0, 2) }}) دينار
                    </td>
                </tr>
                <tr style="border-bottom: 1px solid #e2e8f0;">
                    <td style="padding: 12px 15px; color: #2d3748;">المدفوعات للموظفين</td>
                    <td style="padding: 12px 15px; text-align: left; font-weight: 600; color: #dc2626;">
                        ({{ number_format($operatingCashFlows['employee_payments'] ?? 0, 2) }}) دينار
                    </td>
                </tr>
                <tr style="border-bottom: 1px solid #e2e8f0;">
                    <td style="padding: 12px 15px; color: #2d3748;">المدفوعات التشغيلية الأخرى</td>
                    <td style="padding: 12px 15px; text-align: left; font-weight: 600; color: #dc2626;">
                        ({{ number_format($operatingCashFlows['other_operating_payments'] ?? 0, 2) }}) دينار
                    </td>
                </tr>
            @else
                <!-- Indirect Method -->
                <tr style="border-bottom: 1px solid #e2e8f0;">
                    <td style="padding: 12px 15px; color: #2d3748;">صافي الدخل</td>
                    <td style="padding: 12px 15px; text-align: left; font-weight: 600; color: #059669;">
                        {{ number_format($netIncome ?? 0, 2) }} دينار
                    </td>
                </tr>
                <tr style="border-bottom: 1px solid #e2e8f0;">
                    <td style="padding: 12px 15px; color: #2d3748; padding-right: 30px;">الاستهلاك والإطفاء</td>
                    <td style="padding: 12px 15px; text-align: left; font-weight: 600; color: #059669;">
                        {{ number_format($depreciation ?? 0, 2) }} دينار
                    </td>
                </tr>
                <tr style="border-bottom: 1px solid #e2e8f0;">
                    <td style="padding: 12px 15px; color: #2d3748; padding-right: 30px;">التغير في المدينين</td>
                    <td style="padding: 12px 15px; text-align: left; font-weight: 600; color: {{ ($changeInReceivables ?? 0) >= 0 ? '#dc2626' : '#059669' }};">
                        {{ ($changeInReceivables ?? 0) >= 0 ? '(' : '' }}{{ number_format(abs($changeInReceivables ?? 0), 2) }}{{ ($changeInReceivables ?? 0) >= 0 ? ')' : '' }} دينار
                    </td>
                </tr>
                <tr style="border-bottom: 1px solid #e2e8f0;">
                    <td style="padding: 12px 15px; color: #2d3748; padding-right: 30px;">التغير في المخزون</td>
                    <td style="padding: 12px 15px; text-align: left; font-weight: 600; color: {{ ($changeInInventory ?? 0) >= 0 ? '#dc2626' : '#059669' }};">
                        {{ ($changeInInventory ?? 0) >= 0 ? '(' : '' }}{{ number_format(abs($changeInInventory ?? 0), 2) }}{{ ($changeInInventory ?? 0) >= 0 ? ')' : '' }} دينار
                    </td>
                </tr>
                <tr style="border-bottom: 1px solid #e2e8f0;">
                    <td style="padding: 12px 15px; color: #2d3748; padding-right: 30px;">التغير في الدائنين</td>
                    <td style="padding: 12px 15px; text-align: left; font-weight: 600; color: {{ ($changeInPayables ?? 0) >= 0 ? '#059669' : '#dc2626' }};">
                        {{ ($changeInPayables ?? 0) < 0 ? '(' : '' }}{{ number_format(abs($changeInPayables ?? 0), 2) }}{{ ($changeInPayables ?? 0) < 0 ? ')' : '' }} دينار
                    </td>
                </tr>
            @endif
            
            @php 
                $netOperatingCashFlow = ($operatingCashFlows['customer_receipts'] ?? 0) 
                                      - ($operatingCashFlows['supplier_payments'] ?? 0)
                                      - ($operatingCashFlows['employee_payments'] ?? 0)
                                      - ($operatingCashFlows['other_operating_payments'] ?? 0);
            @endphp
            
            <tr style="background: #f0fdf4; border-top: 2px solid #22c55e;">
                <td style="padding: 15px; font-weight: 700; color: #166534;">صافي التدفق النقدي من الأنشطة التشغيلية</td>
                <td style="padding: 15px; text-align: left; font-weight: 800; color: #166534; font-size: 18px;">
                    {{ number_format($netOperatingCashFlow, 2) }} دينار
                </td>
            </tr>
        </table>
    </div>
    
    <!-- Investing Activities -->
    <div style="margin-bottom: 30px;">
        <h3 style="background: #dbeafe; color: #1e40af; padding: 15px; margin: 0 0 15px 0; font-weight: 700; border-radius: 8px;">
            <i class="fas fa-chart-line" style="margin-left: 8px;"></i>
            التدفقات النقدية من الأنشطة الاستثمارية
        </h3>
        
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 15px;">
            <tr style="border-bottom: 1px solid #e2e8f0;">
                <td style="padding: 12px 15px; color: #2d3748;">شراء الأصول الثابتة</td>
                <td style="padding: 12px 15px; text-align: left; font-weight: 600; color: #dc2626;">
                    ({{ number_format($investingCashFlows['asset_purchases'] ?? 0, 2) }}) دينار
                </td>
            </tr>
            <tr style="border-bottom: 1px solid #e2e8f0;">
                <td style="padding: 12px 15px; color: #2d3748;">بيع الأصول الثابتة</td>
                <td style="padding: 12px 15px; text-align: left; font-weight: 600; color: #059669;">
                    {{ number_format($investingCashFlows['asset_sales'] ?? 0, 2) }} دينار
                </td>
            </tr>
            <tr style="border-bottom: 1px solid #e2e8f0;">
                <td style="padding: 12px 15px; color: #2d3748;">الاستثمارات في الأوراق المالية</td>
                <td style="padding: 12px 15px; text-align: left; font-weight: 600; color: #dc2626;">
                    ({{ number_format($investingCashFlows['investments'] ?? 0, 2) }}) دينار
                </td>
            </tr>
            
            @php 
                $netInvestingCashFlow = ($investingCashFlows['asset_sales'] ?? 0) 
                                      - ($investingCashFlows['asset_purchases'] ?? 0)
                                      - ($investingCashFlows['investments'] ?? 0);
            @endphp
            
            <tr style="background: #f0f9ff; border-top: 2px solid #3b82f6;">
                <td style="padding: 15px; font-weight: 700; color: #1e40af;">صافي التدفق النقدي من الأنشطة الاستثمارية</td>
                <td style="padding: 15px; text-align: left; font-weight: 800; color: #1e40af; font-size: 18px;">
                    {{ $netInvestingCashFlow >= 0 ? '' : '(' }}{{ number_format(abs($netInvestingCashFlow), 2) }}{{ $netInvestingCashFlow >= 0 ? '' : ')' }} دينار
                </td>
            </tr>
        </table>
    </div>
    
    <!-- Financing Activities -->
    <div style="margin-bottom: 30px;">
        <h3 style="background: #fef3c7; color: #d97706; padding: 15px; margin: 0 0 15px 0; font-weight: 700; border-radius: 8px;">
            <i class="fas fa-university" style="margin-left: 8px;"></i>
            التدفقات النقدية من الأنشطة التمويلية
        </h3>
        
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 15px;">
            <tr style="border-bottom: 1px solid #e2e8f0;">
                <td style="padding: 12px 15px; color: #2d3748;">القروض المحصلة</td>
                <td style="padding: 12px 15px; text-align: left; font-weight: 600; color: #059669;">
                    {{ number_format($financingCashFlows['loans_received'] ?? 0, 2) }} دينار
                </td>
            </tr>
            <tr style="border-bottom: 1px solid #e2e8f0;">
                <td style="padding: 12px 15px; color: #2d3748;">سداد القروض</td>
                <td style="padding: 12px 15px; text-align: left; font-weight: 600; color: #dc2626;">
                    ({{ number_format($financingCashFlows['loan_payments'] ?? 0, 2) }}) دينار
                </td>
            </tr>
            <tr style="border-bottom: 1px solid #e2e8f0;">
                <td style="padding: 12px 15px; color: #2d3748;">رأس المال المدفوع</td>
                <td style="padding: 12px 15px; text-align: left; font-weight: 600; color: #059669;">
                    {{ number_format($financingCashFlows['capital_contributions'] ?? 0, 2) }} دينار
                </td>
            </tr>
            <tr style="border-bottom: 1px solid #e2e8f0;">
                <td style="padding: 12px 15px; color: #2d3748;">توزيعات الأرباح المدفوعة</td>
                <td style="padding: 12px 15px; text-align: left; font-weight: 600; color: #dc2626;">
                    ({{ number_format($financingCashFlows['dividends_paid'] ?? 0, 2) }}) دينار
                </td>
            </tr>
            
            @php 
                $netFinancingCashFlow = ($financingCashFlows['loans_received'] ?? 0) 
                                      + ($financingCashFlows['capital_contributions'] ?? 0)
                                      - ($financingCashFlows['loan_payments'] ?? 0)
                                      - ($financingCashFlows['dividends_paid'] ?? 0);
            @endphp
            
            <tr style="background: #fefbf3; border-top: 2px solid #f59e0b;">
                <td style="padding: 15px; font-weight: 700; color: #d97706;">صافي التدفق النقدي من الأنشطة التمويلية</td>
                <td style="padding: 15px; text-align: left; font-weight: 800; color: #d97706; font-size: 18px;">
                    {{ $netFinancingCashFlow >= 0 ? '' : '(' }}{{ number_format(abs($netFinancingCashFlow), 2) }}{{ $netFinancingCashFlow >= 0 ? '' : ')' }} دينار
                </td>
            </tr>
        </table>
    </div>
    
    <!-- Net Change in Cash -->
    @php 
        $netChangeInCash = $netOperatingCashFlow + $netInvestingCashFlow + $netFinancingCashFlow;
        $beginningCash = $cashBalances['beginning'] ?? 0;
        $endingCash = $beginningCash + $netChangeInCash;
    @endphp
    
    <div style="margin-bottom: 30px;">
        <table style="width: 100%; border-collapse: collapse;">
            <tr style="border-bottom: 1px solid #e2e8f0;">
                <td style="padding: 15px; font-weight: 600; color: #2d3748;">صافي التغير في النقدية وما في حكمها</td>
                <td style="padding: 15px; text-align: left; font-weight: 700; color: {{ $netChangeInCash >= 0 ? '#059669' : '#dc2626' }}; font-size: 18px;">
                    {{ $netChangeInCash >= 0 ? '' : '(' }}{{ number_format(abs($netChangeInCash), 2) }}{{ $netChangeInCash >= 0 ? '' : ')' }} دينار
                </td>
            </tr>
            <tr style="border-bottom: 1px solid #e2e8f0;">
                <td style="padding: 15px; font-weight: 600; color: #2d3748;">النقدية في بداية الفترة</td>
                <td style="padding: 15px; text-align: left; font-weight: 700; color: #6b7280; font-size: 18px;">
                    {{ number_format($beginningCash, 2) }} دينار
                </td>
            </tr>
            <tr style="background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%); border-top: 3px solid #374151;">
                <td style="padding: 20px; font-weight: 800; color: #1f2937; font-size: 20px;">النقدية في نهاية الفترة</td>
                <td style="padding: 20px; text-align: left; font-weight: 900; color: #1f2937; font-size: 24px;">
                    {{ number_format($endingCash, 2) }} دينار
                </td>
            </tr>
        </table>
    </div>
    
    <!-- Cash Flow Analysis -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-top: 30px;">
        <div style="background: #f8fafc; border: 2px solid #e2e8f0; border-radius: 12px; padding: 20px; text-align: center;">
            <div style="color: #059669; font-size: 14px; font-weight: 600; margin-bottom: 8px;">كفاءة التشغيل</div>
            <div style="color: #047857; font-size: 24px; font-weight: 800;">
                {{ $netOperatingCashFlow >= 0 ? 'ممتاز' : 'يحتاج تحسين' }}
            </div>
        </div>
        
        <div style="background: #f8fafc; border: 2px solid #e2e8f0; border-radius: 12px; padding: 20px; text-align: center;">
            <div style="color: #1e40af; font-size: 14px; font-weight: 600; margin-bottom: 8px;">نشاط الاستثمار</div>
            <div style="color: #1e3a8a; font-size: 24px; font-weight: 800;">
                {{ $netInvestingCashFlow < 0 ? 'نشط' : 'محدود' }}
            </div>
        </div>
        
        <div style="background: #f8fafc; border: 2px solid #e2e8f0; border-radius: 12px; padding: 20px; text-align: center;">
            <div style="color: #d97706; font-size: 14px; font-weight: 600; margin-bottom: 8px;">الوضع التمويلي</div>
            <div style="color: #92400e; font-size: 24px; font-weight: 800;">
                {{ $netFinancingCashFlow >= 0 ? 'قوي' : 'متحفظ' }}
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
