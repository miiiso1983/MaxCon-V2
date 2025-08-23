@extends('tenant.layouts.app')
@section('content')
<div class="content-card">
  <h3><i class="fas fa-edit" style="color:#2563eb;"></i> تعديل إنذار</h3>
  <form method="POST" action="{{ route('tenant.hr.warnings.update',$warning->id) }}" style="max-width:640px;">
    @csrf @method('PUT')
    <div class="mb-3">
      <label>الموظف</label>
      <input class="form-control" value="{{ $warning->employee->first_name }} {{ $warning->employee->last_name }}" disabled />
    </div>
    <div class="mb-3">
      <label>النوع</label>
      <select name="type" class="form-control" required>
        <option value="delay" @selected($warning->type=='delay')>تأخير</option>
        <option value="absence" @selected($warning->type=='absence')>غياب</option>
        <option value="behavior" @selected($warning->type=='behavior')>سلوك</option>
        <option value="performance" @selected($warning->type=='performance')>أداء</option>
      </select>
    </div>
    <div class="mb-3">
      <label>الدرجة</label>
      <select name="severity" class="form-control" required>
        <option value="low" @selected($warning->severity=='low')>منخفض</option>
        <option value="medium" @selected($warning->severity=='medium')>متوسط</option>
        <option value="high" @selected($warning->severity=='high')>عالٍ</option>
        <option value="critical" @selected($warning->severity=='critical')>حرج</option>
      </select>
    </div>
    <div class="mb-3">
      <label>التاريخ</label>
      <input type="date" name="date" class="form-control" required value="{{ $warning->date->format('Y-m-d') }}" />
    </div>
    <div class="mb-3">
      <label>السبب</label>
      <textarea name="reason" class="form-control" rows="3" required>{{ $warning->reason }}</textarea>
    </div>
    <div class="mb-3">
      <label>تصعيد</label>
      <input type="checkbox" name="escalated" value="1" @checked($warning->escalated) />
    </div>
    <button class="btn btn-primary">حفظ</button>
    <a href="{{ route('tenant.hr.warnings.index') }}" class="btn btn-secondary">رجوع</a>
  </form>
</div>
@endsection

