@extends('layouts.modern')

@section('page-title', 'لوحة تحليلات المخزون')
@section('page-description', 'مؤشرات أداء المخزون والحركات')

@section('content')
<div style="display:grid; gap:20px;">
    <!-- KPI Cards -->
    <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:20px;">
        <div class="content-card" style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); color:#fff;">
            <div style="font-size:12px; opacity:.9;">عدد المنتجات</div>
            <div style="font-size:28px; font-weight:800;">{{ number_format($metrics['products']) }}</div>
        </div>
        <div class="content-card" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color:#fff;">
            <div style="font-size:12px; opacity:.9;">عناصر المخزون</div>
            <div style="font-size:28px; font-weight:800;">{{ number_format($metrics['items']) }}</div>
        </div>
        <div class="content-card" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color:#fff;">
            <div style="font-size:12px; opacity:.9;">مخزون منخفض</div>
            <div style="font-size:28px; font-weight:800;">{{ number_format($metrics['low_stock']) }}</div>
        </div>
        <div class="content-card" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color:#fff;">
            <div style="font-size:12px; opacity:.9;">منتهي الصلاحية</div>
            <div style="font-size:28px; font-weight:800;">{{ number_format($metrics['expired']) }}</div>
        </div>
    </div>

    <!-- Recent Movements Chart -->
    <div class="content-card">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px;">
            <h3 style="margin:0; font-size:18px;">حركات آخر 7 أيام</h3>
            <a href="{{ route('tenant.inventory.reports.movement-history') }}" style="text-decoration:none; color:#3b82f6; font-weight:700;">تاريخ الحركات</a>
        </div>
        <div style="height: 300px;">
            <canvas id="recentMovements"></canvas>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
(function(){
  var data = @json($recent);
  var labels = Object.keys(data);
  var values = Object.values(data);
  var ctx = document.getElementById('recentMovements').getContext('2d');
  if (typeof Chart !== 'undefined') {
    // Set some nicer defaults for Arabic UI
    Chart.defaults.font.family = 'Cairo, sans-serif';
    Chart.defaults.font.size = 12;
    Chart.defaults.color = '#334155';
    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: labels,
        datasets: [{
          label: 'عدد الحركات',
          data: values,
          backgroundColor: 'rgba(139, 92, 246, 0.8)',
          borderColor: '#7c3aed',
          borderWidth: 1,
          borderRadius: 6,
          maxBarThickness: 28
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { display: true, labels: { usePointStyle: true } },
          tooltip: { enabled: true }
        },
        scales: {
          x: { grid: { display: false } },
          y: { beginAtZero: true, grid: { color: 'rgba(226,232,240,0.6)' } }
        },
        layout: { padding: 10 }
      }
    });
  }
})();
</script>
@endpush
@endsection

