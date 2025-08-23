@extends('tenant.layouts.app')
@section('content')
<div class="content-card">
  <h3><i class="fas fa-edit" style="color:#2563eb;"></i> تعديل حافز/مكافأة</h3>
  <form method="POST" action="{{ route('tenant.hr.incentives.update',$incentive->id) }}" style="max-width:640px;">
    @csrf @method('PUT')
    <div class="mb-3">
      <label>الموظف</label>
      <input class="form-control" value="{{ $incentive->employee->first_name }} {{ $incentive->employee->last_name }}" disabled />
    </div>
    <div class="mb-3">
      <label>النوع</label>
      <select name="type" class="form-control" required>
        <option value="performance" @selected($incentive->type=='performance')>أداء</option>
        <option value="sales" @selected($incentive->type=='sales')>مبيعات</option>
        <option value="attendance" @selected($incentive->type=='attendance')>حضور</option>
        <option value="special" @selected($incentive->type=='special')>مكافآت خاصة</option>
      </select>
    </div>
    <div class="mb-3">
      <label>المبلغ</label>
      <input type="number" step="0.01" min="0" name="amount" class="form-control" required value="{{ $incentive->amount }}" />
    </div>
    <div class="mb-3">
      <label>التاريخ</label>
      <input type="date" name="date" class="form-control" required value="{{ $incentive->date->format('Y-m-d') }}" />
    </div>
    <div class="mb-3">
      <label>السبب</label>
      <textarea name="reason" class="form-control" rows="3">{{ $incentive->reason }}</textarea>
    </div>
    <button class="btn btn-primary">حفظ</button>
    <a href="{{ route('tenant.hr.incentives.index') }}" class="btn btn-secondary">رجوع</a>
  </form>
</div>
@endsection

