<table>
    <thead>
        <tr>
            <th colspan="4" style="font-weight: bold; font-size: 16px; text-align: center;">ميزان المراجعة</th>
        </tr>
        <tr>
            <th colspan="4" style="text-align: center;">من {{ \Carbon\Carbon::parse($dateFrom)->format('Y/m/d') }} إلى {{ \Carbon\Carbon::parse($dateTo)->format('Y/m/d') }}</th>
        </tr>
        @if($costCenterName)
        <tr>
            <th colspan="4" style="text-align: center;">مركز التكلفة: {{ $costCenterName }}</th>
        </tr>
        @endif
        <tr>
            <th>رمز الحساب</th>
            <th>اسم الحساب</th>
            <th>مدين</th>
            <th>دائن</th>
        </tr>
    </thead>
    <tbody>
        @foreach($trialBalanceData as $item)
        <tr>
            <td>{{ $item['account']->account_code }}</td>
            <td>{{ $item['account']->account_name }}</td>
            <td>{{ $item['debit_balance'] > 0 ? number_format($item['debit_balance'], 2) : '-' }}</td>
            <td>{{ $item['credit_balance'] > 0 ? number_format($item['credit_balance'], 2) : '-' }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="2" style="font-weight: bold;">الإجمالي</td>
            <td>{{ number_format($totalDebits, 2) }}</td>
            <td>{{ number_format($totalCredits, 2) }}</td>
        </tr>
    </tfoot>
</table>

