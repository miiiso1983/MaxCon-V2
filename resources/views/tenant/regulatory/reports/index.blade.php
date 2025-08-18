@extends('layouts.modern')

@section('title', 'التقارير التنظيمية')

@section('content')
<div style="padding: 30px; background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); min-height: 100vh;">
    <!-- Header -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; margin-bottom: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <div style="display: flex; align-items: center; justify-content: between; margin-bottom: 20px;">
            <div style="display: flex; align-items: center;">
                <div style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); color: #2d3748; border-radius: 50%; width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; margin-left: 20px; font-size: 32px;">
                    <i class="fas fa-file-alt"></i>
                </div>
                <div>
                    <h1 style="color: #2d3748; margin: 0; font-size: 32px; font-weight: 700;">التقارير التنظيمية</h1>
                    <p style="color: #718096; margin: 5px 0 0 0; font-size: 16px;">إنشاء وإدارة التقارير التنظيمية المطلوبة</p>
                </div>
            </div>
            <div style="display: flex; gap: 15px;">
                <button onclick="showCreateReportModal()" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); color: #2d3748; padding: 15px 25px; border: none; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; cursor: pointer;">
                    <i class="fas fa-plus"></i>
                    إنشاء تقرير جديد
                </button>
                <a href="{{ route('tenant.inventory.regulatory.dashboard') }}" style="background: rgba(255,255,255,0.2); color: #2d3748; padding: 15px 25px; border: 2px solid #2d3748; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; text-decoration: none;">
                    <i class="fas fa-arrow-right"></i>
                    العودة للوحة الرئيسية
                </a>
            </div>
        </div>
    </div>
        @if(session('success'))
            <div style="background:#ecfdf5; color:#065f46; border:1px solid #10b981; padding:12px 16px; border-radius:10px; margin: 0 0 20px 0;">
                {{ session('success') }}
            </div>
        @endif
        @if(session('warning'))
            <div style="background:#fffbeb; color:#92400e; border:1px solid #f59e0b; padding:12px 16px; border-radius:10px; margin: 0 0 20px 0;">
                {{ session('warning') }}
            </div>
        @endif
        @if(session('error'))
            <div style="background:#fef2f2; color:#991b1b; border:1px solid #ef4444; padding:12px 16px; border-radius:10px; margin: 0 0 20px 0;">
                {{ session('error') }}
            </div>
        @endif
        @if ($errors->any())
            <div style="background:#fff7ed; color:#9a3412; border:1px solid #fb923c; padding:12px 16px; border-radius:10px; margin: 0 0 20px 0;">
                <ul style="margin:0; padding-left:20px;">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

    @if(($reports ?? null) && count($reports))
    <div class="mx-auto" style="background:#fff; border:1px solid #e5e7eb; border-radius:14px; padding:18px; box-shadow: 0 8px 20px rgba(0,0,0,0.06); margin-bottom:20px;">
        <div style="display:flex; align-items:center; justify-content:space-between; gap:12px; margin-bottom:12px;">
            <h2 style="margin:0; font-size:20px; color:#111827;">سجل التقارير</h2>
            <div style="display:flex; gap:8px;">
                <a href="{{ route('tenant.inventory.regulatory.reports.create') }}" class="btn" style="background:#2563eb; color:#fff; padding:8px 12px; border-radius:10px; text-decoration:none;">إنشاء تقرير</a>
                <a href="{{ route('tenant.inventory.regulatory.reports.templates') }}" class="btn" style="background:#f3f4f6; color:#111827; padding:8px 12px; border-radius:10px; text-decoration:none;">قوالب</a>
                <a href="{{ route('tenant.inventory.regulatory.reports.export') }}" class="btn" style="background:#10b981; color:#fff; padding:8px 12px; border-radius:10px; text-decoration:none;">تصدير CSV</a>
            </div>
        </div>
        <form method="GET" action="{{ route('tenant.inventory.regulatory.reports.index') }}" style="display:flex; gap:8px; flex-wrap:wrap; margin-bottom:12px;">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="بحث بالعنوان/الرقم" style="padding:8px 10px; border:1px solid #e5e7eb; border-radius:10px; min-width:220px;">
            <select name="type" style="padding:8px 10px; border:1px solid #e5e7eb; border-radius:10px;">
                <option value="">النوع</option>
                @foreach(\App\Models\Tenant\Regulatory\RegulatoryReport::REPORT_TYPES as $key => $label)
                    <option value="{{ $key }}" {{ request('type')===$key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            <select name="status" style="padding:8px 10px; border:1px solid #e5e7eb; border-radius:10px;">
                <option value="">الحالة</option>
                @foreach(\App\Models\Tenant\Regulatory\RegulatoryReport::STATUS_TYPES as $key => $label)
                    <option value="{{ $key }}" {{ request('status')===$key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            <select name="priority" style="padding:8px 10px; border:1px solid #e5e7eb; border-radius:10px;">
                <option value="">الأولوية</option>
                @foreach(\App\Models\Tenant\Regulatory\RegulatoryReport::PRIORITY_LEVELS as $key => $label)
                    <option value="{{ $key }}" {{ request('priority')===$key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            <input type="text" name="authority" value="{{ request('authority') }}" placeholder="الجهة" style="padding:8px 10px; border:1px solid #e5e7eb; border-radius:10px; min-width:160px;">
            <input type="date" name="date_from" value="{{ request('date_from') }}" style="padding:8px 10px; border:1px solid #e5e7eb; border-radius:10px;">
            <input type="date" name="date_to" value="{{ request('date_to') }}" style="padding:8px 10px; border:1px solid #e5e7eb; border-radius:10px;">
            <button type="submit" style="background:#111827; color:#fff; padding:8px 12px; border:none; border-radius:10px;">تصفية</button>
            <a href="{{ route('tenant.inventory.regulatory.reports.index') }}" style="background:#f3f4f6; color:#111827; padding:8px 12px; border-radius:10px; text-decoration:none;">تفريغ</a>
        </form>
        <div style="overflow:auto; border:1px solid #e5e7eb; border-radius:12px;">
            <table style="width:100%; border-collapse:separate; border-spacing:0;">
                <thead>
                    <tr style="background:#f9fafb; color:#374151;">
                        <th style="padding:10px 12px; text-align:right;">#</th>
                        <th style="padding:10px 12px; text-align:right;">رقم التقرير</th>
                        <th style="padding:10px 12px; text-align:right;">العنوان</th>
                        <th style="padding:10px 12px; text-align:right;">النوع</th>
                        <th style="padding:10px 12px; text-align:right;">الحالة</th>
                        <th style="padding:10px 12px; text-align:right;">الأولوية</th>
                        <th style="padding:10px 12px; text-align:right;">الجهة</th>
                        <th style="padding:10px 12px; text-align:right;">الاستحقاق</th>
                        <th style="padding:10px 12px; text-align:right;">أنشئ</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reports as $i => $r)
                        <tr style="border-top:1px solid #e5e7eb;">
                            <td style="padding:10px 12px; color:#6b7280;">{{ $loop->iteration }}</td>
                            <td style="padding:10px 12px; font-weight:600; color:#111827;">{{ $r->report_number ?? '-' }}</td>
                            <td style="padding:10px 12px;">{{ $r->report_title ?? $r->title ?? '-' }}</td>
                            <td style="padding:10px 12px;">{{ $r->report_type_name ?? '-' }}</td>
                            <td style="padding:10px 12px;">{{ $r->status_name ?? '-' }}</td>
                            <td style="padding:10px 12px;">{{ $r->priority_name ?? '-' }}</td>
                            <td style="padding:10px 12px;">{{ $r->regulatory_authority ?? '-' }}</td>
                            <td style="padding:10px 12px;">{{ $r->due_date ? $r->due_date->format('Y-m-d') : '-' }}</td>
                            <td style="padding:10px 12px;">{{ $r->created_at ? $r->created_at->format('Y-m-d') : '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="margin-top:10px;">{{ method_exists($reports, 'links') ? $reports->links() : '' }}</div>
    </div>
    @endif
    @if(false)

    <!-- Stats Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px;">
        <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 25px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
            <div style="display: flex; align-items: center; justify-content: between;">
                <div>
                    <h3 style="margin: 0 0 10px 0; font-size: 18px; color: #718096;">إجمالي التقارير</h3>
                    <p style="margin: 0; font-size: 32px; font-weight: 700; color: #2d3748;">{{ $counts['total'] ?? 0 }}</p>
                </div>
                <div style="font-size: 40px; color: #fa709a; opacity: 0.3;">
                    <i class="fas fa-file-alt"></i>
                </div>
            </div>
        </div>

        <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 25px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
            <div style="display: flex; align-items: center; justify-content: between;">
                <div>
                    <h3 style="margin: 0 0 10px 0; font-size: 18px; color: #718096;">قيد المراجعة</h3>
                    <p style="margin: 0; font-size: 32px; font-weight: 700; color: #ed8936;">{{ $counts['pending'] ?? 0 }}</p>
                </div>
                <div style="font-size: 40px; color: #ed8936; opacity: 0.3;">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
        </div>

        <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 25px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
            <div style="display: flex; align-items: center; justify-content: between;">
                <div>
                    <h3 style="margin: 0 0 10px 0; font-size: 18px; color: #718096;">معتمدة</h3>
                    <p style="margin: 0; font-size: 32px; font-weight: 700; color: #48bb78;">{{ $counts['approved'] ?? 0 }}</p>
                </div>
                <div style="font-size: 40px; color: #48bb78; opacity: 0.3;">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>

        <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 25px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
            <div style="display: flex; align-items: center; justify-content: between;">
                <div>
                    <h3 style="margin: 0 0 10px 0; font-size: 18px; color: #718096;">متأخرة</h3>
                    <p style="margin: 0; font-size: 32px; font-weight: 700; color: #f56565;">{{ $counts['overdue'] ?? 0 }}</p>
                </div>
                <div style="font-size: 40px; color: #f56565; opacity: 0.3;">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
            </div>
        </div>
    </div>

            <form method="GET" action="{{ route('tenant.inventory.regulatory.reports.index') }}" style="display:flex; gap:10px; flex-wrap:wrap; margin-top:10px;">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="بحث بالعنوان/الرقم" style="padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px; min-width:220px;">
                <select name="type" style="padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                    <option value="">كل الأنواع</option>
                    @foreach(\App\Models\Tenant\Regulatory\RegulatoryReport::REPORT_TYPES as $key => $label)
                        <option value="{{ $key }}" {{ request('type')===$key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                <select name="status" style="padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                    <option value="">كل الحالات</option>
                    @foreach(\App\Models\Tenant\Regulatory\RegulatoryReport::STATUS_TYPES as $key => $label)
                        <option value="{{ $key }}" {{ request('status')===$key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                <select name="priority" style="padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                    <option value="">كل الأولويات</option>
                    @foreach(\App\Models\Tenant\Regulatory\RegulatoryReport::PRIORITY_LEVELS as $key => $label)
                        <option value="{{ $key }}" {{ request('priority')===$key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                <input type="text" name="authority" value="{{ request('authority') }}" placeholder="الجهة" style="padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px; min-width:160px;">
                <input type="date" name="date_from" value="{{ request('date_from') }}" style="padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                <input type="date" name="date_to" value="{{ request('date_to') }}" style="padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                <button type="submit" style="background:#2563eb; color:#fff; padding:10px 16px; border:none; border-radius:10px;">بحث</button>
                <a href="{{ route('tenant.inventory.regulatory.reports.index') }}" style="background:#e5e7eb; color:#111827; padding:10px 16px; border:none; border-radius:10px; text-decoration:none;">تفريغ</a>
                <a href="{{ route('tenant.inventory.regulatory.reports.export') }}" style="background:#10b981; color:#fff; padding:10px 16px; border:none; border-radius:10px; text-decoration:none;">تصدير CSV</a>
            </form>
    <!-- Main Content -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <div style="text-align: center; padding: 60px 20px;">
            <div style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); color: #2d3748; border-radius: 50%; width: 120px; height: 120px; display: flex; align-items: center; justify-content: center; margin: 0 auto 30px; font-size: 48px;">
                <i class="fas fa-file-alt"></i>
            </div>
            <h2 style="color: #2d3748; margin: 0 0 15px 0; font-size: 28px; font-weight: 700;">مرحباً بك في وحدة التقارير التنظيمية</h2>
            <p style="color: #718096; margin: 0 0 30px 0; font-size: 18px; line-height: 1.6; max-width: 600px; margin-left: auto; margin-right: auto;">
                هذه الوحدة تتيح لك إنشاء وإدارة التقارير التنظيمية المطلوبة.
                يمكنك إعداد تقارير التفتيش، التقارير المخبرية، تقارير الأحداث الضارة، والتقارير الدورية.
            </p>

            <div style="display: flex; gap: 20px; justify-content: center; flex-wrap: wrap;">
                <button onclick="showCreateReportModal()" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); color: #2d3748; padding: 15px 30px; border: none; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; cursor: pointer; font-size: 16px;">
                    <i class="fas fa-plus"></i>
                    إنشاء تقرير جديد
                </button>
                <button onclick="showTemplatesModal()" style="background: rgba(250, 112, 154, 0.1); color: #2d3748; padding: 15px 30px; border: 2px solid #fa709a; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; cursor: pointer; font-size: 16px;">
                    <i class="fas fa-file-alt"></i>
                    قوالب التقارير
                </button>
                <button onclick="exportReportsToExcel()" style="background: rgba(250, 112, 154, 0.1); color: #2d3748; padding: 15px 30px; border: 2px solid #fa709a; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; cursor: pointer; font-size: 16px;">
                    <i class="fas fa-download"></i>
                    تصدير التقارير
                </button>
            </div>
        </div>

        <!-- Report Types Grid -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 25px; margin-top: 40px;">
            <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 15px; padding: 25px;">
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    <i class="fas fa-search" style="font-size: 24px; margin-left: 15px;"></i>
                    <h3 style="margin: 0; font-size: 20px; font-weight: 700;">تقرير تفتيش</h3>
                </div>
                <p style="margin: 0; opacity: 0.9; line-height: 1.6;">
                    تقارير شاملة لنتائج التفتيش والملاحظات والإجراءات التصحيحية
                </p>
            </div>

            <div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; border-radius: 15px; padding: 25px;">
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    <i class="fas fa-flask" style="font-size: 24px; margin-left: 15px;"></i>
                    <h3 style="margin: 0; font-size: 20px; font-weight: 700;">تقرير مخبري</h3>
                </div>
            @if(($reports ?? null) && count($reports))
                <div style="margin-top:30px;">
                    <h3 style="color:#2d3748; margin-bottom:12px;">التقارير المحفوظة</h3>
                    <div style="overflow-x:auto;">
                        <table style="width:100%; border-collapse:collapse; background:white; border-radius:12px; overflow:hidden;">
                            <thead>
                                <tr style="background:#f7fafc; color:#4a5568;">
                                    <th style="text-align:right; padding:12px;">#</th>
                                    <th style="text-align:right; padding:12px;">الرقم</th>
                                    <th style="text-align:right; padding:12px;">العنوان</th>
                                    <th style="text-align:right; padding:12px;">النوع</th>
                                    <th style="text-align:right; padding:12px;">الحالة</th>
                                    <th style="text-align:right; padding:12px;">الجهة</th>
                                    <th style="text-align:right; padding:12px;">الاستحقاق</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($reports as $i => $r)
                                    <tr style="border-top:1px solid #e2e8f0;">
                                        <td style="padding:10px; color:#4a5568;">{{ $i + 1 }}</td>
                                        <td style="padding:10px; color:#1f2937; font-weight:600;">{{ $r->report_number ?? '-' }}</td>
                                        <td style="padding:10px;">{{ $r->report_title ?? $r->title ?? '-' }}</td>
                                        <td style="padding:10px;">
                                            <span style="padding:4px 8px; border-radius:9999px; background:#eef2ff; color:#3730a3; font-size:12px;">
                                                {{ $r->report_type ?? '-' }}
                                            </span>
                                        </td>
                                        <td style="padding:10px;">
                                            <span style="padding:4px 8px; border-radius:9999px; background:#ecfeff; color:#155e75; font-size:12px;">
                                                {{ $r->status ?? '-' }}
                                            </span>
                                        </td>
                                        <td style="padding:10px;">{{ $r->regulatory_authority ?? '-' }}</td>
                                        <td style="padding:10px;">{{ $r->due_date ? $r->due_date->format('Y-m-d') : '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div style="margin-top:12px;">
                        {{ method_exists($reports, 'links') ? $reports->links() : '' }}
                    </div>
                </div>
            @endif
                <p style="margin: 0; opacity: 0.9; line-height: 1.6;">
                    تقارير نتائج الفحوصات المخبرية والتحاليل والمطابقة للمواصفات
                </p>
            </div>

            <div style="background: linear-gradient(135deg, #4ecdc4 0%, #44a08d 100%); color: white; border-radius: 15px; padding: 25px;">
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    <i class="fas fa-exclamation-triangle" style="font-size: 24px; margin-left: 15px;"></i>
                    <h3 style="margin: 0; font-size: 20px; font-weight: 700;">تقرير حدث ضار</h3>
                </div>
                <p style="margin: 0; opacity: 0.9; line-height: 1.6;">
                    تقارير الأحداث الضارة والآثار الجانبية للمنتجات الدوائية
                </p>
            </div>

            <div style="background: linear-gradient(135deg, #42a5f5 0%, #2196f3 100%); color: white; border-radius: 15px; padding: 25px;">
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    <i class="fas fa-calendar-alt" style="font-size: 24px; margin-left: 15px;"></i>
                    <h3 style="margin: 0; font-size: 20px; font-weight: 700;">تقارير دورية</h3>
                </div>
                <p style="margin: 0; opacity: 0.9; line-height: 1.6;">
                    التقارير الشهرية والربع سنوية والسنوية المطلوبة تنظيمياً
                </p>
            </div>
        </div>
    </div>
</div>

@endif

<script>
function showCreateReportModal() {
    window.location.href = '{{ route("tenant.inventory.regulatory.reports.create") }}';
}

function showImportModal() {
    window.location.href = '{{ route("tenant.inventory.regulatory.reports.import.form") }}';
}

function showTemplatesModal() {
    window.location.href = '{{ route("tenant.inventory.regulatory.reports.templates") }}';
}

function exportReportsToExcel() {
    window.location.href = '{{ route("tenant.inventory.regulatory.reports.export") }}';
}
</script>

@endsection
