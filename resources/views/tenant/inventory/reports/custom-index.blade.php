@extends('layouts.modern')

@section('page-title', 'التقارير المخصصة - المخزون')
@section('page-description', 'إنشاء تقارير مخصصة للمخزون حسب احتياجاتك')

@section('content')
<div class="content-card">
    <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display:flex; align-items:center; gap:10px;">
        <i class="fas fa-cogs" style="color:#8b5cf6;"></i>
        منشئ التقارير المخصصة للمخزون
    </h3>

    <form method="POST" action="{{ route('tenant.inventory.reports.custom.run') }}" style="display:grid; gap:16px;">
        @csrf
        <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:16px;">
            <div>
                <label style="display:block; font-weight:600; color:#4a5568; margin-bottom:6px;">نوع التقرير</label>
                <select name="type" style="width:100%; padding:10px; border:2px solid #e2e8f0; border-radius:8px;">
                    <option value="inventory_summary">ملخص المخزون</option>
                    <option value="movement_analysis">تحليل الحركات</option>
                    <option value="cost_analysis">تحليل التكلفة</option>
                    <option value="expiry_tracking">تتبع الانتهاء</option>
                </select>
            </div>
            <div>
                <label style="display:block; font-weight:600; color:#4a5568; margin-bottom:6px;">المستودع</label>
                <select name="warehouse_id" style="width:100%; padding:10px; border:2px solid #e2e8f0; border-radius:8px;">
                    <option value="">جميع المستودعات</option>
                    @foreach($warehouses as $w)
                        <option value="{{ $w->id }}">{{ $w->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label style="display:block; font-weight:600; color:#4a5568; margin-bottom:6px;">المنتج</label>
                <select name="product_id" style="width:100%; padding:10px; border:2px solid #e2e8f0; border-radius:8px;">
                    <option value="">كل المنتجات</option>
                    @foreach($products as $p)
                        <option value="{{ $p->id }}">{{ $p->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label style="display:block; font-weight:600; color:#4a5568; margin-bottom:6px;">من تاريخ</label>
                <input type="date" name="date_from" style="width:100%; padding:10px; border:2px solid #e2e8f0; border-radius:8px;">
            </div>
            <div>
                <label style="display:block; font-weight:600; color:#4a5568; margin-bottom:6px;">إلى تاريخ</label>
                <input type="date" name="date_to" style="width:100%; padding:10px; border:2px solid #e2e8f0; border-radius:8px;">
            </div>
        </div>

        <div style="display:flex; gap:10px;">
            <button type="submit" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color:#fff; padding:12px 20px; border:none; border-radius:10px; font-weight:700; cursor:pointer;">
                <i class="fas fa-play"></i> تشغيل التقرير
            </button>
            <a href="{{ route('tenant.inventory.reports.index') }}" style="background:#6b7280; color:#fff; padding:12px 20px; border-radius:10px; text-decoration:none; font-weight:700;">
                رجوع
            </a>
        </div>
    </form>
</div>
@endsection

