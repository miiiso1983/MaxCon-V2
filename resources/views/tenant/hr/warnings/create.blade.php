@extends('tenant.layouts.app')
@section('content')
<div class="content-card">
  <h3><i class="fas fa-plus-circle" style="color:#f59e0b;"></i> تسجيل إنذار</h3>
  <form method="POST" action="{{ route('tenant.hr.warnings.store') }}" style="max-width:640px;">
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
        <option value="behavior">سلوك</option>
        <option value="performance">أداء</option>
      </select>
    </div>
    <div class="mb-3">
      <label>الدرجة</label>
      <select name="severity" class="form-control" required>
        <option value="low">منخفض</option>
        <option value="medium">متوسط</option>
        <option value="high">عالٍ</option>
        <option value="critical">حرج</option>
      </select>
    </div>
    <div class="mb-3">
      <label>التاريخ</label>
      <input type="date" name="date" class="form-control" required />
    </div>
    <div class="mb-3">
      <label>السبب</label>
      <textarea name="reason" class="form-control" rows="3" required></textarea>
    </div>
    <button class="btn btn-primary">حفظ</button>
    <a href="{{ route('tenant.hr.warnings.index') }}" class="btn btn-secondary">رجوع</a>
  </form>
</div>
@endsection

