<table>
    <thead>
        <tr>
            <th colspan="3" style="font-weight:bold; text-align:center; font-size:16px;">الميزانية العمومية</th>
        </tr>
        <tr>
            <th colspan="3" style="text-align:center;">كما في {{ \Carbon\Carbon::parse($asOfDate)->format('Y/m/d') }}</th>
        </tr>
    </thead>
    <tbody>
        <tr><th colspan="3">الأصول</th></tr>
        <tr><th colspan="3">الأصول المتداولة</th></tr>
        @php $totalCurrentAssets = 0; @endphp
        @foreach($currentAssets as $acc)
        @php $totalCurrentAssets += $acc->balance; @endphp
        <tr>
            <td>أصل متداول</td>
            <td>{{ $acc->account_code }} - {{ $acc->account_name }}</td>
            <td>{{ number_format($acc->balance, 2) }}</td>
        </tr>
        @endforeach
        <tr><th colspan="3">الأصول غير المتداولة</th></tr>
        @php $totalNonCurrentAssets = 0; @endphp
        @foreach($nonCurrentAssets as $acc)
        @php $totalNonCurrentAssets += $acc->balance; @endphp
        <tr>
            <td>أصل غير متداول</td>
            <td>{{ $acc->account_code }} - {{ $acc->account_name }}</td>
            <td>{{ number_format($acc->balance, 2) }}</td>
        </tr>
        @endforeach
        <tr>
            <td colspan="2" style="font-weight:bold;">إجمالي الأصول</td>
            <td>{{ number_format($totalCurrentAssets + $totalNonCurrentAssets, 2) }}</td>
        </tr>

        <tr><th colspan="3">الخصوم وحقوق الملكية</th></tr>
        <tr><th colspan="3">الخصوم المتداولة</th></tr>
        @php $totalCurrentLiabilities = 0; @endphp
        @foreach($currentLiabilities as $acc)
        @php $totalCurrentLiabilities += $acc->balance; @endphp
        <tr>
            <td>خصم متداول</td>
            <td>{{ $acc->account_code }} - {{ $acc->account_name }}</td>
            <td>{{ number_format($acc->balance, 2) }}</td>
        </tr>
        @endforeach
        <tr><th colspan="3">الخصوم غير المتداولة</th></tr>
        @php $totalNonCurrentLiabilities = 0; @endphp
        @foreach($nonCurrentLiabilities as $acc)
        @php $totalNonCurrentLiabilities += $acc->balance; @endphp
        <tr>
            <td>خصم غير متداول</td>
            <td>{{ $acc->account_code }} - {{ $acc->account_name }}</td>
            <td>{{ number_format($acc->balance, 2) }}</td>
        </tr>
        @endforeach
        <tr><th colspan="3">حقوق الملكية</th></tr>
        @php $totalEquity = 0; @endphp
        @foreach($equityAccounts as $acc)
        @php $totalEquity += $acc->balance; @endphp
        <tr>
            <td>حقوق ملكية</td>
            <td>{{ $acc->account_code }} - {{ $acc->account_name }}</td>
            <td>{{ number_format($acc->balance, 2) }}</td>
        </tr>
        @endforeach
        <tr>
            <td colspan="2" style="font-weight:bold;">إجمالي الخصوم وحقوق الملكية</td>
            <td>{{ number_format($totalCurrentLiabilities + $totalNonCurrentLiabilities + $totalEquity, 2) }}</td>
        </tr>
    </tbody>
</table>

