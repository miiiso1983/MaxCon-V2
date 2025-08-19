@extends('layouts.modern')

@section('page-title', 'التقارير المخصصة')
@section('page-description', 'إنشاء تقارير مخصصة حسب مراكز التكلفة والفروع والفترات الزمنية')

@section('content')
<div class="content-card" style="margin-bottom: 20px;">
  <form method="POST" action="{{ route('tenant.inventory.accounting.reports.custom.generate') }}">
    @csrf
    <div style="display:grid; grid-template-columns: repeat(auto-fit,minmax(220px,1fr)); gap:16px;">
      <div>
        <label style="display:block; margin-bottom:6px; font-weight:600;">من تاريخ</label>
        <input type="date" name="date_from" value="{{ old('date_from', $dateFrom) }}" style="width:100%; padding:10px; border:2px solid #e2e8f0; border-radius:8px;">
      </div>
      <div>
        <label style="display:block; margin-bottom:6px; font-weight:600;">إلى تاريخ</label>
        <input type="date" name="date_to" value="{{ old('date_to', $dateTo) }}" style="width:100%; padding:10px; border:2px solid #e2e8f0; border-radius:8px;">
      </div>
      <div>
        <label style="display:block; margin-bottom:6px; font-weight:600;">مركز التكلفة</label>
        <select name="cost_center_id" style="width:100%; padding:10px; border:2px solid #e2e8f0; border-radius:8px;">
          <option value="">جميع المراكز</option>
          @foreach($costCenters as $cc)
            <option value="{{ $cc->id }}" {{ (optional($filters)['costCenterId'] ?? null) == $cc->id ? 'selected' : '' }}>{{ $cc->code }} - {{ $cc->name }}</option>
          @endforeach
        </select>
      </div>
      <div>
        <label style="display:block; margin-bottom:6px; font-weight:600;">الفرع (المستودع)</label>
        <select name="warehouse_id" style="width:100%; padding:10px; border:2px solid #e2e8f0; border-radius:8px;">
          <option value="">جميع الفروع</option>
          @foreach($warehouses as $wh)
            <option value="{{ $wh->id }}" {{ (optional($filters)['warehouseId'] ?? null) == $wh->id ? 'selected' : '' }}>{{ $wh->code }} - {{ $wh->name }}</option>
          @endforeach
        </select>
      </div>
      <div>
        <label style="display:block; margin-bottom:6px; font-weight:600;">نوع التقرير</label>
        <select name="report_type" style="width:100%; padding:10px; border:2px solid #e2e8f0; border-radius:8px;">
          @foreach($reportTypes as $key=>$label)
            <option value="{{ $key }}" {{ (isset($type) && $type===$key) ? 'selected' : '' }}>{{ $label }}</option>
          @endforeach
        </select>
      </div>
    </div>
    <div style="display:flex; gap:10px; margin-top:16px;">
      <button type="submit" style="background:#0ea5e9; color:#fff; padding:10px 16px; border:none; border-radius:8px; font-weight:700;">عرض</button>
      <button type="submit" name="export" value="excel" style="background:#22c55e; color:#fff; padding:10px 16px; border:none; border-radius:8px; font-weight:700;">تصدير Excel</button>
    </div>
  </form>
</div>

@if(isset($result))
  <div class="content-card">
    @if($type==='revenue_by_cc_branch' || $type==='expense_by_cc_branch')
      <h3 style="margin-bottom:12px;">نتائج {{ $reportTypes[$type] ?? '' }}</h3>
      <table style="width:100%; border-collapse:collapse;">
        <thead>
          <tr style="background:#f8fafc;">
            <th style="padding:10px; border:1px solid #e2e8f0;">مركز التكلفة</th>
            <th style="padding:10px; border:1px solid #e2e8f0;">الفرع</th>
            <th style="padding:10px; border:1px solid #e2e8f0;">المبلغ</th>
          </tr>
        </thead>
        <tbody>
          @php $total=0; @endphp
          @foreach(($result['rows'] ?? []) as $row)
            @php $total += (float)($row->amount ?? 0); @endphp
            <tr>
              <td style="padding:10px; border:1px solid #e2e8f0;">{{ optional($costCenters->firstWhere('id',$row->cost_center_id))->name ?? '-' }}</td>
              <td style="padding:10px; border:1px solid #e2e8f0;">{{ optional($warehouses->firstWhere('id',$row->warehouse_id))->name ?? '-' }}</td>
              <td style="padding:10px; border:1px solid #e2e8f0; text-align:left;">{{ number_format($row->amount ?? 0, 2) }}</td>
            </tr>
          @endforeach
        </tbody>
        <tfoot>
          <tr style="background:#eef2ff;">
            <td colspan="2" style="padding:10px; border:1px solid #e2e8f0; font-weight:800;">الإجمالي</td>
            <td style="padding:10px; border:1px solid #e2e8f0; text-align:left; font-weight:800;">{{ number_format($total, 2) }}</td>
          </tr>
        </tfoot>
      </table>
    @elseif($type==='profitability')
      <h3 style="margin-bottom:12px;">الربحية (إيراد - مصروف)</h3>
      @php $profit = ($result['profit'] ?? 0); @endphp
      <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:12px;">
        <div style="background:#dcfce7; padding:16px; border-radius:8px;">
          <div style="color:#166534; font-weight:700;">الإيرادات</div>
          <div style="color:#166534; font-size:20px; font-weight:900;">{{ number_format($result['revenue'] ?? 0, 2) }}</div>
        </div>
        <div style="background:#fee2e2; padding:16px; border-radius:8px;">
          <div style="color:#991b1b; font-weight:700;">المصروفات</div>
          <div style="color:#991b1b; font-size:20px; font-weight:900;">{{ number_format($result['expense'] ?? 0, 2) }}</div>
        </div>
        <div style="background: {{ $profit>=0 ? '#f0f9ff' : '#fef2f2' }}; padding:16px; border-radius:8px;">
          <div style="color: {{ $profit>=0 ? '#1e40af' : '#991b1b' }}; font-weight:700;">النتيجة</div>
          <div style="color: {{ $profit>=0 ? '#1e40af' : '#991b1b' }}; font-size:20px; font-weight:900;">{{ number_format($profit, 2) }}</div>
        </div>
      </div>
    @elseif($type==='monthly_series')
      <h3 style="margin-bottom:12px;">سلسلة شهرية للإيرادات والمصروفات</h3>
      <table style="width:100%; border-collapse:collapse;">
        <thead>
          <tr style="background:#f8fafc;">
            <th style="padding:10px; border:1px solid #e2e8f0;">الشهر</th>
            <th style="padding:10px; border:1px solid #e2e8f0;">الإيرادات</th>
            <th style="padding:10px; border:1px solid #e2e8f0;">المصروفات</th>
            <th style="padding:10px; border:1px solid #e2e8f0;">النتيجة</th>
          </tr>
        </thead>
        <tbody>
          @foreach(($result['series'] ?? []) as $row)
            @php $profit = (float)($row->revenue ?? 0) - (float)($row->expense ?? 0); @endphp
            <tr>
              <td style="padding:10px; border:1px solid #e2e8f0;">{{ $row->ym }}</td>
              <td style="padding:10px; border:1px solid #e2e8f0; text-align:left;">{{ number_format($row->revenue ?? 0, 2) }}</td>
              <td style="padding:10px; border:1px solid #e2e8f0; text-align:left;">{{ number_format($row->expense ?? 0, 2) }}</td>
              <td style="padding:10px; border:1px solid #e2e8f0; text-align:left; font-weight:700;">{{ number_format($profit, 2) }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    @endif
  </div>
@endif
@endsection

