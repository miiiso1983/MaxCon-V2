@php
    $headers = ['الموظف', 'التاريخ', 'الساعات', 'معدل الاضافي', 'المبلغ', 'الحالة'];
@endphp
<table>
    <thead>
        <tr>
            @foreach($headers as $h)
            <th>{{ $h }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach($overtimes as $ot)
        <tr>
            <td>{{ $ot->employee->full_name ?? '-' }}</td>
            <td>{{ optional($ot->date)->format('Y-m-d') }}</td>
            <td>{{ $ot->hours_approved ?? $ot->hours_requested }}</td>
            <td>{{ $ot->overtime_rate }}</td>
            <td>{{ $ot->total_amount }}</td>
            <td>{{ $ot->status_label }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

