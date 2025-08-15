@extends('layouts.modern')

@section('page-title', 'التحصيل - الذمم المدينة')
@section('page-description', 'إدارة تحصيل فواتير العملاء')

@section('content')
<div class="content-card">
  <form method="GET" style="display:grid; gap:10px; margin-bottom:15px;">
    <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(200px,1fr)); gap:10px;">
      <select name="payment_status" style="padding:10px; border:1px solid #e5e7eb; border-radius:8px;">
        <option value="">كل الحالات</option>
        <option value="paid" @selected(request('payment_status')=='paid')>مدفوعة</option>
        <option value="partial" @selected(request('payment_status')=='partial')>مدفوعة جزئياً</option>
        <option value="unpaid" @selected(request('payment_status')=='unpaid')>غير مدفوعة</option>
      </select>
      <select name="customer_id" style="padding:10px; border:1px solid #e5e7eb; border-radius:8px;">
        <option value="">كل العملاء</option>
        @foreach($customers as $c)
          <option value="{{ $c->id }}" @selected(request('customer_id')==$c->id)>{{ $c->name }}</option>
        @endforeach
      </select>
      <input type="date" name="date_from" value="{{ request('date_from') }}" style="padding:10px; border:1px solid #e5e7eb; border-radius:8px;"/>
      <input type="date" name="date_to" value="{{ request('date_to') }}" style="padding:10px; border:1px solid #e5e7eb; border-radius:8px;"/>
    </div>
    <div>
      <button class="btn btn-primary" style="background:#3b82f6; color:#fff; padding:10px 16px; border:none; border-radius:8px;">تصفية</button>
    </div>
  </form>

  <div style="overflow-x:auto;">
    <table style="width:100%; border-collapse:collapse;">
      <thead>
        <tr style="background:#f8fafc;">
          <th style="padding:12px; text-align:right;">رقم الفاتورة</th>
          <th style="padding:12px; text-align:center;">العميل</th>
          <th style="padding:12px; text-align:center;">تاريخ الإصدار</th>
          <th style="padding:12px; text-align:center;">تاريخ الاستحقاق</th>
          <th style="padding:12px; text-align:center;">الإجمالي</th>
          <th style="padding:12px; text-align:center;">المدفوع</th>
          <th style="padding:12px; text-align:center;">المتبقي</th>
          <th style="padding:12px; text-align:center;">حالة الدفع</th>
          <th style="padding:12px; text-align:center;">إجراء</th>
        </tr>
      </thead>
      <tbody>
        @foreach($invoices as $inv)
        <tr style="border-bottom:1px solid #e5e7eb;">
          <td style="padding:10px;">{{ $inv->invoice_number }}</td>
          <td style="padding:10px; text-align:center;">{{ $inv->customer->name ?? '-' }}</td>
          <td style="padding:10px; text-align:center;">{{ optional($inv->invoice_date)->format('Y-m-d') }}</td>
          <td style="padding:10px; text-align:center; color: {{ $inv->isOverdue() ? '#ef4444' : '#111827' }}">{{ optional($inv->due_date)->format('Y-m-d') }}</td>
          <td style="padding:10px; text-align:center;">{{ number_format($inv->total_amount, 2) }} د.ع</td>
          <td style="padding:10px; text-align:center;">{{ number_format($inv->paid_amount, 2) }} د.ع</td>
          <td style="padding:10px; text-align:center; font-weight:700;">{{ number_format($inv->remaining_amount, 2) }} د.ع</td>
          <td style="padding:10px; text-align:center;">{{ $inv->payment_status }}</td>
          <td style="padding:10px; text-align:center;">
            <a href="{{ route('tenant.inventory.accounting.receivables.invoice', $inv) }}" class="btn" style="background:#10b981; color:#fff; padding:8px 12px; border-radius:8px; text-decoration:none;">تحصيل</a>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  <div style="margin-top:10px;">
    {{ $invoices->links() }}
  </div>
</div>
@endsection

