<table>
    <thead>
        <tr>
            <th colspan="6" style="font-weight:bold; text-align:center; font-size:16px;">دفتر حساب</th>
        </tr>
        <tr>
            <th colspan="6" style="text-align:center;">من {{ \Carbon\Carbon::parse($dateFrom)->format('Y/m/d') }} إلى {{ \Carbon\Carbon::parse($dateTo)->format('Y/m/d') }}</th>
        </tr>
        <tr>
            <th>التاريخ</th>
            <th>رقم القيد</th>
            <th>الوصف</th>
            <th>مدين</th>
            <th>دائن</th>
            <th>الرصيد</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td colspan="5" style="font-weight:bold;">رصيد افتتاحي</td>
            <td>{{ number_format($openingBalance, 2) }}</td>
        </tr>
        @php $running = $openingBalance; @endphp
        @foreach($ledgerEntries as $row)
            @php $running += ($row['debit_amount'] - $row['credit_amount']); @endphp
            <tr>
                <td>{{ $row['date'] }}</td>
                <td>{{ $row['journal_number'] }}</td>
                <td>{{ $row['description'] }}</td>
                <td>{{ number_format($row['debit_amount'], 2) }}</td>
                <td>{{ number_format($row['credit_amount'], 2) }}</td>
                <td>{{ number_format($running, 2) }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="3" style="font-weight:bold;">الإجماليات</td>
            <td>{{ number_format($totalDebits, 2) }}</td>
            <td>{{ number_format($totalCredits, 2) }}</td>
            <td>{{ number_format($closingBalance, 2) }}</td>
        </tr>
    </tfoot>
</table>

