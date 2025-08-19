<table>
    <thead>
        <tr>
            <th colspan="3" style="font-weight:bold; text-align:center; font-size:16px;">قائمة الدخل</th>
        </tr>
        <tr>
            <th colspan="3" style="text-align:center;">من {{ \Carbon\Carbon::parse($dateFrom)->format('Y/m/d') }} إلى {{ \Carbon\Carbon::parse($dateTo)->format('Y/m/d') }}</th>
        </tr>
        <tr>
            <th>النوع</th>
            <th>الحساب</th>
            <th>المبلغ</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td colspan="3" style="font-weight:bold;">الإيرادات</td>
        </tr>
        @php $totalRevenue = 0; @endphp
        @foreach($revenueAccounts as $acc)
        @php $totalRevenue += $acc->balance; @endphp
        <tr>
            <td>إيراد</td>
            <td>{{ $acc->account_code }} - {{ $acc->account_name }}</td>
            <td>{{ number_format($acc->balance, 2) }}</td>
        </tr>
        @endforeach
        <tr>
            <td colspan="3" style="font-weight:bold;">المصروفات</td>
        </tr>
        @php $totalExpense = 0; @endphp
        @foreach($expenseAccounts as $acc)
        @php $totalExpense += $acc->balance; @endphp
        <tr>
            <td>مصروف</td>
            <td>{{ $acc->account_code }} - {{ $acc->account_name }}</td>
            <td>{{ number_format($acc->balance, 2) }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="2" style="font-weight:bold;">إجمالي الإيرادات</td>
            <td>{{ number_format($totalRevenue, 2) }}</td>
        </tr>
        <tr>
            <td colspan="2" style="font-weight:bold;">إجمالي المصروفات</td>
            <td>{{ number_format($totalExpense, 2) }}</td>
        </tr>
        <tr>
            <td colspan="2" style="font-weight:bold;">صافي الربح (الخسارة)</td>
            <td>{{ number_format($totalRevenue - $totalExpense, 2) }}</td>
        </tr>
    </tfoot>
</table>

