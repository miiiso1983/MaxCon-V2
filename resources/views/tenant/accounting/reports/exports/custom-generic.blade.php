<table>
    <thead>
        <tr>
            <th colspan="4" style="font-weight:bold; text-align:center; font-size:16px;">تقرير مخصص</th>
        </tr>
        <tr>
            <th colspan="4" style="text-align:center;">من {{ \Carbon\Carbon::parse($meta['dateFrom'])->format('Y/m/d') }} إلى {{ \Carbon\Carbon::parse($meta['dateTo'])->format('Y/m/d') }}</th>
        </tr>
    </thead>
    <tbody>
        @if($type==='revenue_by_cc_branch' || $type==='expense_by_cc_branch')
            <tr>
                <th>مركز التكلفة</th>
                <th>الفرع</th>
                <th>المبلغ</th>
            </tr>
            @php $total=0; @endphp
            @foreach(($data['rows'] ?? []) as $row)
                @php $total += (float)($row->amount ?? 0); @endphp
                <tr>
                    <td>{{ $row->cost_center_id }}</td>
                    <td>{{ $row->warehouse_id }}</td>
                    <td>{{ number_format($row->amount ?? 0, 2) }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="2" style="font-weight:bold;">الإجمالي</td>
                <td>{{ number_format($total, 2) }}</td>
            </tr>
        @elseif($type==='profitability')
            <tr><th>البند</th><th>القيمة</th></tr>
            <tr><td>الإيرادات</td><td>{{ number_format($data['revenue'] ?? 0, 2) }}</td></tr>
            <tr><td>المصروفات</td><td>{{ number_format($data['expense'] ?? 0, 2) }}</td></tr>
            <tr><td>الربح</td><td>{{ number_format(($data['profit'] ?? 0), 2) }}</td></tr>
        @elseif($type==='monthly_series')
            <tr>
                <th>الشهر</th>
                <th>الإيرادات</th>
                <th>المصروفات</th>
                <th>النتيجة</th>
            </tr>
            @foreach(($data['series'] ?? []) as $row)
                @php $profit = (float)($row->revenue ?? 0) - (float)($row->expense ?? 0); @endphp
                <tr>
                    <td>{{ $row->ym }}</td>
                    <td>{{ number_format($row->revenue ?? 0, 2) }}</td>
                    <td>{{ number_format($row->expense ?? 0, 2) }}</td>
                    <td>{{ number_format($profit, 2) }}</td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>

