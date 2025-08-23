@extends('tenant.layouts.app')
@section('content')
<div class="content-card">
  <h3><i class="fas fa-plus-circle" style="color:#16a34a;"></i> إضافة خصم</h3>
  <form method="POST" action="{{ route('tenant.hr.deductions.store') }}" style="max-width:640px;">
    @csrf
    <div class="mb-3">
      <label>الموظف</label>
      <select name="employee_id" class="form-control" required>
        @foreach($employees as $emp)
          <option value="{{ $emp->id }}">{{ $emp->first_name }} {{ $emp->last_name }}</option>
        @endforeach
      </select>
    </div>
    <div class="mb-3">
      <label>النوع</label>
      <select name="type" class="form-control" required>
        <option value="delay">تأخير</option>
        <option value="absence">غياب</option>
        <option value="violation">مخالفة</option>
        <option value="admin">خصومات إدارية</option>
      </select>
    </div>
    <div class="mb-3">
      <label>المبلغ</label>
      <input type="number" step="0.01" min="0" name="amount" class="form-control" required />
    </div>
    <div class="mb-3">
      <label>التاريخ</label>
      <input type="date" name="date" class="form-control" required />
    </div>
    <div class="mb-3">
      <label>السبب</label>
      <textarea name="reason" class="form-control" rows="3"></textarea>
    </div>
    <button class="btn btn-primary">حفظ</button>
    <a href="{{ route('tenant.hr.deductions.index') }}" class="btn btn-secondary">رجوع</a>
  </form>
</div>
@endsection

