@extends('layouts.modern')
@section('content')
<div class="content-card">
  <h3 style="display:flex;align-items:center;gap:10px;">
    <i class="fas fa-gift" style="color:#16a34a;"></i> إدارة الحوافز والمكافآت
  </h3>
  <form method="GET" class="mb-4" style="display:flex;gap:10px;flex-wrap:wrap;">
    <select name="employee_id" class="form-control" style="min-width:220px;">
      <option value="">-- الموظف --</option>
      @foreach($employees as $emp)
        <option value="{{ $emp->id }}" @selected(request('employee_id')==$emp->id)>{{ $emp->first_name }} {{ $emp->last_name }}</option>
      @endforeach
    </select>
    <select name="type" class="form-control" style="min-width:200px;">
      <option value="">-- النوع --</option>
      <option value="performance" @selected(request('type')=='performance')>أداء</option>
      <option value="sales" @selected(request('type')=='sales')>مبيعات</option>
      <option value="attendance" @selected(request('type')=='attendance')>حضور</option>
      <option value="special" @selected(request('type')=='special')>مكافآت خاصة</option>
    </select>
    <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-control"/>
    <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-control"/>
    <button class="btn btn-primary">فلترة</button>
    <a href="{{ route('tenant.hr.incentives.create') }}" class="btn btn-success">إضافة حافز/مكافأة</a>
    <a href="{{ route('tenant.hr.incentives.export', request()->query()) }}" class="btn btn-outline-secondary">تصدير Excel</a>
  </form>
  <div class="table-responsive">
    <table class="table table-striped">
      <thead>
        <tr>
          <th>التاريخ</th>
          <th>الموظف</th>
          <th>النوع</th>
          <th>المبلغ</th>
          <th>السبب</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @forelse($incentives as $row)
          <tr>
            <td>{{ $row->date->format('Y-m-d') }}</td>
            <td>{{ $row->employee->first_name }} {{ $row->employee->last_name }}</td>
            <td>{{ $row->type }}</td>
            <td>{{ number_format($row->amount,2) }}</td>
            <td>{{ $row->reason }}</td>
            <td>
              <a href="{{ route('tenant.hr.incentives.edit',$row->id) }}" class="btn btn-sm btn-primary">تعديل</a>
              <form action="{{ route('tenant.hr.incentives.destroy',$row->id) }}" method="POST" style="display:inline-block" onsubmit="return confirm('حذف السجل؟');">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-danger">حذف</button>
              </form>
            </td>
          </tr>
        @empty
          <tr><td colspan="6" class="text-center">لا توجد بيانات</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  {{ $incentives->links() }}
</div>
@endsection

