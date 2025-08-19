<table>
    <thead>
        <tr>
            <th colspan="3" style="font-weight:bold; text-align:center; font-size:16px;">قائمة التدفقات النقدية</th>
        </tr>
        <tr>
            <th colspan="3" style="text-align:center;">من {{ \Carbon\Carbon::parse($dateFrom)->format('Y/m/d') }} إلى {{ \Carbon\Carbon::parse($dateTo)->format('Y/m/d') }}</th>
        </tr>
        <tr>
            <th colspan="3" style="text-align:center;">{{ $method == 'direct' ? 'الطريقة المباشرة' : 'الطريقة غير المباشرة' }}</th>
        </tr>
    </thead>
    <tbody>
        <tr><th colspan="3">الأنشطة التشغيلية</th></tr>
        <tr>
            <td>المقبوضات من العملاء</td>
            <td></td>
            <td>{{ number_format($operatingCashFlows['customer_receipts'] ?? 0, 2) }}</td>
        </tr>
        <tr>
            <td>المدفوعات للموردين</td>
            <td></td>
            <td>({{ number_format($operatingCashFlows['supplier_payments'] ?? 0, 2) }})</td>
        </tr>
        <tr>
            <td>المدفوعات للموظفين</td>
            <td></td>
            <td>({{ number_format($operatingCashFlows['employee_payments'] ?? 0, 2) }})</td>
        </tr>
        <tr>
            <td>مدفوعات تشغيلية أخرى</td>
            <td></td>
            <td>({{ number_format($operatingCashFlows['other_operating_payments'] ?? 0, 2) }})</td>
        </tr>
        <tr>
            <td colspan="2" style="font-weight:bold;">صافي التدفق النقدي من الأنشطة التشغيلية</td>
            <td>{{ number_format($netOperatingCashFlow, 2) }}</td>
        </tr>

        <tr><th colspan="3">الأنشطة الاستثمارية</th></tr>
        <tr>
            <td>شراء الأصول الثابتة</td>
            <td></td>
            <td>({{ number_format($investingCashFlows['asset_purchases'] ?? 0, 2) }})</td>
        </tr>
        <tr>
            <td>بيع الأصول الثابتة</td>
            <td></td>
            <td>{{ number_format($investingCashFlows['asset_sales'] ?? 0, 2) }}</td>
        </tr>
        <tr>
            <td>الاستثمارات</td>
            <td></td>
            <td>({{ number_format($investingCashFlows['investments'] ?? 0, 2) }})</td>
        </tr>
        <tr>
            <td colspan="2" style="font-weight:bold;">صافي التدفق النقدي من الأنشطة الاستثمارية</td>
            <td>{{ $netInvestingCashFlow >= 0 ? '' : '(' }}{{ number_format(abs($netInvestingCashFlow), 2) }}{{ $netInvestingCashFlow >= 0 ? '' : ')' }}</td>
        </tr>

        <tr><th colspan="3">الأنشطة التمويلية</th></tr>
        <tr>
            <td>القروض المحصلة</td>
            <td></td>
            <td>{{ number_format($financingCashFlows['loans_received'] ?? 0, 2) }}</td>
        </tr>
        <tr>
            <td>سداد القروض</td>
            <td></td>
            <td>({{ number_format($financingCashFlows['loan_payments'] ?? 0, 2) }})</td>
        </tr>
        <tr>
            <td>رأس المال المدفوع</td>
            <td></td>
            <td>{{ number_format($financingCashFlows['capital_contributions'] ?? 0, 2) }}</td>
        </tr>
        <tr>
            <td>توزيعات الأرباح المدفوعة</td>
            <td></td>
            <td>({{ number_format($financingCashFlows['dividends_paid'] ?? 0, 2) }})</td>
        </tr>
        <tr>
            <td colspan="2" style="font-weight:bold;">صافي التدفق النقدي من الأنشطة التمويلية</td>
            <td>{{ $netFinancingCashFlow >= 0 ? '' : '(' }}{{ number_format(abs($netFinancingCashFlow), 2) }}{{ $netFinancingCashFlow >= 0 ? '' : ')' }}</td>
        </tr>

        <tr><th colspan="3">ملخص النقدية</th></tr>
        <tr>
            <td>النقدية أول الفترة</td>
            <td></td>
            <td>{{ number_format($beginningCash, 2) }}</td>
        </tr>
        <tr>
            <td>صافي التغير في النقدية</td>
            <td></td>
            <td>{{ $netChangeInCash >= 0 ? '' : '(' }}{{ number_format(abs($netChangeInCash ?? ($netOperatingCashFlow + $netInvestingCashFlow + $netFinancingCashFlow)), 2) }}{{ $netChangeInCash >= 0 ? '' : ')' }}</td>
        </tr>
        <tr>
            <td>النقدية آخر الفترة</td>
            <td></td>
            <td>{{ number_format($endingCash, 2) }}</td>
        </tr>
    </tbody>
</table>

