@extends('tenant.layouts.app')
@section('content')
<div class="content-card">
  <h3><i class="fas fa-edit" style="color:#2563eb;"></i> تعديل خصم</h3>
  <form method="POST" action="{{ route('tenant.hr.deductions.update',$deduction->id) }}" style="max-width:640px;">
    @csrf @method('PUT')
    <div class="mb-3">
      <label>الموظف</label>
      <input class="form-control" value="{{ $deduction->employee->first_name }} {{ $deduction->employee->last_name }}" disabled />
    </div>
    <div class="mb-3">
      <label>النوع</label>
      <select name="type" class="form-control" required>
        <option value="delay" @selected($deduction->type=='delay')>تأخير</option>
        <option value="absence" @selected($deduction->type=='absence')>غياب</option>
        <option value="violation" @selected($deduction->type=='violation')>مخالفة</option>
        <option value="admin" @selected($deduction->type=='admin')>خصومات إدارية</option>
      </select>
    </div>
    <div class="mb-3">
      <label>المبلغ</label>
      <input type="number" step="0.01" min="0" name="amount" class="form-control" required value="{{ $deduction->amount }}" />
    </div>
    <div class="mb-3">
      <label>التاريخ</label>
      <input type="date" name="date" class="form-control" required value="{{ $deduction->date->format('Y-m-d') }}" />
    </div>
    <div class="mb-3">
      <label>السبب</label>
      <textarea name="reason" class="form-control" rows="3">{{ $deduction->reason }}</textarea>
    </div>
    <button class="btn btn-primary">حفظ</button>
    <a href="{{ route('tenant.hr.deductions.index') }}" class="btn btn-secondary">رجوع</a>
  </form>
</div>
@endsection

