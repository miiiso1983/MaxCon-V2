@extends('layouts.tenant')

@section('content')
<div style="direction: rtl; padding: 20px;">
    <div style="max-width: 980px; margin: 0 auto;">
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:16px;">
            <h1 style="font-size: 22px; font-weight: 800; color: #1a202c;">تعديل المنصب</h1>
            <a href="{{ route('tenant.hr.positions.index') }}" style="color:#3182ce; text-decoration:none;">رجوع إلى القائمة</a>
        </div>

        @if(session('error'))
            <div style="background:#fed7d7; color:#742a2a; padding:12px 14px; border-radius:10px; margin-bottom:12px;">{{ session('error') }}</div>
        @endif
        @if($errors->any())
            <div style="background:#fed7d7; color:#742a2a; padding:12px 14px; border-radius:10px; margin-bottom:12px;">
                <ul style="margin:0; padding-right:18px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('tenant.hr.positions.update', $position->id) }}">
            @csrf
            @method('PUT')

            <div style="background:#fff; border:1px solid #e2e8f0; border-radius:14px; padding:18px; box-shadow: 0 6px 16px rgba(0,0,0,0.06);">
                <div style="display:grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 16px;">
                    <div>
                        <label style="display:block; font-weight:600; color:#2d3748; margin-bottom:6px;">المسمى الوظيفي</label>
                        <input type="text" name="title" value="{{ old('title', $position->title) }}" required style="width:100%; padding:12px; border:1px solid #e2e8f0; border-radius:10px;">
                    </div>
                    <div>
                        <label style="display:block; font-weight:600; color:#2d3748; margin-bottom:6px;">الكود</label>
                        <input type="text" name="code" value="{{ old('code', $position->code) }}" placeholder="اتركه فارغاً للحفاظ على الكود" style="width:100%; padding:12px; border:1px solid #e2e8f0; border-radius:10px;">
                    </div>

                    <div>
                        <label style="display:block; font-weight:600; color:#2d3748; margin-bottom:6px;">القسم</label>
                        <select name="department_id" required style="width:100%; padding:12px; border:1px solid #e2e8f0; border-radius:10px;">
                            @foreach(($departments ?? []) as $dept)
                                <option value="{{ $dept->id }}" {{ old('department_id', $position->department_id)==$dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label style="display:block; font-weight:600; color:#2d3748; margin-bottom:6px;">المستوى</label>
                        <select name="level" required style="width:100%; padding:12px; border:1px solid #e2e8f0; border-radius:10px;">
                            @foreach(['entry','junior','mid','senior','lead','manager','director','executive'] as $lvl)
                                <option value="{{ $lvl }}" {{ old('level', $position->level)===$lvl ? 'selected' : '' }}>{{ $lvl }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label style="display:block; font-weight:600; color:#2d3748; margin-bottom:6px;">الراتب الأدنى</label>
                        <input type="number" step="0.01" name="min_salary" value="{{ old('min_salary', $position->min_salary) }}" style="width:100%; padding:12px; border:1px solid #e2e8f0; border-radius:10px;">
                    </div>
                    <div>
                        <label style="display:block; font-weight:600; color:#2d3748; margin-bottom:6px;">الراتب الأعلى</label>
                        <input type="number" step="0.01" name="max_salary" value="{{ old('max_salary', $position->max_salary) }}" style="width:100%; padding:12px; border:1px solid #e2e8f0; border-radius:10px;">
                    </div>

                    <div>
                        <label style="display:block; font-weight:600; color:#2d3748; margin-bottom:6px;">يرفع تقاريره إلى</label>
                        <select name="reports_to_position_id" style="width:100%; padding:12px; border:1px solid #e2e8f0; border-radius:10px;">
                            <option value="">لا يوجد</option>
                            @foreach(($positions ?? []) as $pos)
                                <option value="{{ $pos->id }}" {{ old('reports_to_position_id', $position->reports_to_position_id)==$pos->id ? 'selected' : '' }}>{{ $pos->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label style="display:block; font-weight:600; color:#2d3748; margin-bottom:6px;">الحالة</label>
                        <label style="display:flex; align-items:center; gap:8px;">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $position->is_active) ? 'checked' : '' }}>
                            <span>نشط</span>
                        </label>
                    </div>

                    <div style="grid-column: 1 / -1;">
                        <label style="display:block; font-weight:600; color:#2d3748; margin-bottom:6px;">الوصف الوظيفي</label>
                        <textarea name="description" rows="3" style="width:100%; padding:12px; border:1px solid #e2e8f0; border-radius:10px;">{{ old('description', $position->description) }}</textarea>
                    </div>

                    <div>
                        <label style="display:block; font-weight:600; color:#2d3748; margin-bottom:6px;">المتطلبات</label>
                        <textarea name="requirements_text" rows="3" style="width:100%; padding:12px; border:1px solid #e2e8f0; border-radius:10px;" placeholder="كل سطر عنصر">{{ old('requirements_text', is_array($position->requirements) ? implode("\n", $position->requirements) : '') }}</textarea>
                    </div>
                    <div>
                        <label style="display:block; font-weight:600; color:#2d3748; margin-bottom:6px;">المهام والمسؤوليات</label>
                        <textarea name="responsibilities_text" rows="3" style="width:100%; padding:12px; border:1px solid #e2e8f0; border-radius:10px;" placeholder="كل سطر عنصر">{{ old('responsibilities_text', is_array($position->responsibilities) ? implode("\n", $position->responsibilities) : '') }}</textarea>
                    </div>
                </div>

                <div style="display:flex; gap:10px; justify-content:flex-end; margin-top:16px;">
                    <button type="submit" style="background:#ed8936; color:#fff; padding:10px 14px; border:none; border-radius:10px;">حفظ التعديلات</button>
                    <a href="{{ route('tenant.hr.positions.index') }}" style="background:#e2e8f0; color:#2d3748; padding:10px 14px; border-radius:10px; text-decoration:none;">إلغاء</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

