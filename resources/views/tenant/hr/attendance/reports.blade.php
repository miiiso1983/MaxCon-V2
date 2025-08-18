@extends('layouts.modern')

@section('title', 'تقارير الحضور')

@section('content')
<div style="padding: 30px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh;">
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 24px; margin-bottom: 20px; box-shadow: 0 12px 24px rgba(0,0,0,0.08);">
        <div style="display:flex; align-items:center; justify-content:space-between; gap: 12px; flex-wrap: wrap;">
            <div>
                <h1 style="margin:0; color:#1f2937; font-size:24px; font-weight:800;">تقارير الحضور</h1>
                <div style="color:#6b7280; font-size:14px;">الفترة: {{ $stats['from'] }} إلى {{ $stats['to'] }}</div>
            </div>
            <a href="{{ route('tenant.hr.attendance.index') }}" style="background:#e5e7eb; color:#111827; padding:10px 12px; border-radius:10px; text-decoration:none;">رجوع</a>
        </div>
        <form method="GET" style="margin-top: 12px; display:grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 10px; align-items:end;">
            <div>
                <label style="display:block; color:#374151; font-weight:600; margin-bottom:6px;">من تاريخ</label>
                <input type="date" name="from_date" value="{{ request('from_date', $stats['from']) }}" style="width:100%; padding:10px; border:1px solid #e5e7eb; border-radius:10px;">
            </div>
            <div>
                <label style="display:block; color:#374151; font-weight:600; margin-bottom:6px;">إلى تاريخ</label>
                <input type="date" name="to_date" value="{{ request('to_date', $stats['to']) }}" style="width:100%; padding:10px; border:1px solid #e5e7eb; border-radius:10px;">
            </div>
            <div>
                <label style="display:block; color:#374151; font-weight:600; margin-bottom:6px;">الحالة</label>
                <select name="status" style="width:100%; padding:10px; border:1px solid #e5e7eb; border-radius:10px;">
                    <option value="">الكل</option>
                    @foreach(\App\Models\Tenant\HR\Attendance::STATUS_OPTIONS as $k => $v)
                        <option value="{{ $k }}" {{ request('status')===$k ? 'selected' : '' }}>{{ $v }}</option>
                    @endforeach
                </select>
            </div>
            <div style="display:flex; gap:8px;">
                <button type="submit" style="background: #48bb78; color: white; padding: 10px 14px; border: none; border-radius: 10px; font-weight: 700;">تحديث</button>
                <a href="{{ route('tenant.hr.attendance.export', request()->query()) }}" style="background: #4299e1; color: white; padding: 10px 14px; border-radius: 10px; text-decoration:none;">تصدير Excel</a>
            </div>
        </form>
    </div>

    <!-- KPI Cards -->
    <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 14px; margin-bottom: 16px;">
        <div style="background:#fff; border:1px solid #e5e7eb; border-radius:14px; padding:16px; text-align:center;">
            <div style="color:#374151; font-weight:700;">عدد السجلات</div>
            <div style="font-size:24px; font-weight:800; color:#1f2937;">{{ array_sum($stats['status_counts']) }}</div>
        </div>
        <div style="background:#fff; border:1px solid #e5e7eb; border-radius:14px; padding:16px; text-align:center;">
            <div style="color:#374151; font-weight:700;">إجمالي الساعات</div>
            <div style="font-size:24px; font-weight:800; color:#1f2937;">{{ $stats['total_hours'] }}</div>
        </div>
        <div style="background:#fff; border:1px solid #e5e7eb; border-radius:14px; padding:16px; text-align:center;">
            <div style="color:#374151; font-weight:700;">متوسط الساعات</div>
            <div style="font-size:24px; font-weight:800; color:#1f2937;">{{ $stats['avg_hours'] }}</div>
        </div>
        <div style="background:#fff; border:1px solid #e5e7eb; border-radius:14px; padding:16px; text-align:center;">
            <div style="color:#374151; font-weight:700;">التأخير (دقائق)</div>
            <div style="font-size:24px; font-weight:800; color:#1f2937;">{{ $stats['late_minutes'] }}</div>
        </div>
        <div style="background:#fff; border:1px solid #e5e7eb; border-radius:14px; padding:16px; text-align:center;">
            <div style="color:#374151; font-weight:700;">انصراف مبكر (دقائق)</div>
            <div style="font-size:24px; font-weight:800; color:#1f2937;">{{ $stats['early_leave_minutes'] }}</div>
        </div>
    </div>

    <!-- Charts -->
    <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 16px;">
        <div style="background:#fff; border:1px solid #e5e7eb; border-radius:14px; padding:16px;">
            <div style="font-weight:700; color:#374151; margin-bottom:8px;">التوزيع حسب الحالة</div>
            <canvas id="statusChart" height="140"></canvas>
        </div>
        <div style="background:#fff; border:1px solid #e5e7eb; border-radius:14px; padding:16px;">
            <div style="font-weight:700; color:#374151; margin-bottom:8px;">اتجاه الساعات اليومية</div>
            <canvas id="dailyChart" height="140"></canvas>
        </div>
        <div style="background:#fff; border:1px solid #e5e7eb; border-radius:14px; padding:16px; grid-column: 1 / -1;">
            <div style="font-weight:700; color:#374151; margin-bottom:8px;">نمط الحضور حسب أيام الأسبوع</div>
            <canvas id="dowChart" height="120"></canvas>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const statusData = @json($stats['status_counts']);
    const dailyData = @json($stats['daily']);
    const dowData = @json($stats['dow']);

    // Status Pie
    new Chart(document.getElementById('statusChart'), {
        type: 'doughnut',
        data: {
            labels: Object.keys(statusData).map(k => ({present:'حاضر',absent:'غائب',late:'متأخر',early_leave:'انصراف مبكر',half_day:'نصف يوم',holiday:'عطلة',leave:'إجازة'}[k] || k)),
            datasets: [{
                data: Object.values(statusData),
                backgroundColor: ['#48bb78','#f56565','#ed8936','#4299e1','#9f7aea','#a0aec0','#f6ad55']
            }]
        },
        options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
    });

    // Daily Line
    const labels = Object.keys(dailyData);
    const hours = labels.map(k => dailyData[k].hours);
    new Chart(document.getElementById('dailyChart'), {
        type: 'line',
        data: {
            labels,
            datasets: [{ label: 'ساعات العمل', data: hours, fill: false, borderColor: '#4299e1', tension: 0.3 }]
        },
        options: { responsive: true, scales: { y: { beginAtZero: true } } }
    });

    // Day of Week Bar
    const dowLabels = ['الأحد','الاثنين','الثلاثاء','الأربعاء','الخميس','الجمعة','السبت'];
    new Chart(document.getElementById('dowChart'), {
        type: 'bar',
        data: {
            labels: dowLabels,
            datasets: [{ label: 'أيام الحضور', data: Object.values(dowData), backgroundColor: '#48bb78' }]
        },
        options: { responsive: true, scales: { y: { beginAtZero: true } } }
    });
</script>
@endpush
@endsection

