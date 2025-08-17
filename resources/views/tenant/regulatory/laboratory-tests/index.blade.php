@extends('layouts.modern')

@section('title', 'الفحوصات المخبرية')

@section('content')
<div style="padding: 30px; background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%); min-height: 100vh;">
    <!-- Header -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; margin-bottom: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <div style="display: flex; align-items: center; justify-content: between; margin-bottom: 20px;">
            <div style="display: flex; align-items: center;">
                <div style="background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%); color: #2d3748; border-radius: 50%; width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; margin-left: 20px; font-size: 32px;">
                    <i class="fas fa-flask"></i>
                </div>
                <div>
                    <h1 style="color: #2d3748; margin: 0; font-size: 32px; font-weight: 700;">الفحوصات المخبرية</h1>
                    <p style="color: #718096; margin: 5px 0 0 0; font-size: 16px;">تتبع الفحوصات المخبرية والتقارير الرقابية</p>
                </div>
            </div>
            <div style="display: flex; gap: 15px;">
                <button onclick="showAddTestModal()" style="background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%); color: #2d3748; padding: 15px 25px; border: none; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; cursor: pointer;">
                    <i class="fas fa-plus"></i>
                    إضافة فحص جديد
                </button>
                <a href="{{ route('tenant.inventory.regulatory.dashboard') }}" style="background: rgba(255,255,255,0.2); color: #2d3748; padding: 15px 25px; border: 2px solid #2d3748; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; text-decoration: none;">
                    <i class="fas fa-arrow-right"></i>
                    العودة للوحة الرئيسية
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px;">
        <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 25px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
            <div style="display: flex; align-items: center; justify-content: between;">
                <div>
                    <h3 style="margin: 0 0 10px 0; font-size: 18px; color: #718096;">إجمالي الفحوصات</h3>
                    <p style="margin: 0; font-size: 32px; font-weight: 700; color: #2d3748;">{{ $counts['total'] ?? 0 }}</p>
                </div>
                <div style="font-size: 40px; color: #ffecd2; opacity: 0.3;">
                    <i class="fas fa-flask"></i>
                </div>
            </div>
        </div>

        <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 25px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
            <div style="display: flex; align-items: center; justify-content: between;">
                <div>
                    <h3 style="margin: 0 0 10px 0; font-size: 18px; color: #718096;">قيد التنفيذ</h3>
                    <p style="margin: 0; font-size: 32px; font-weight: 700; color: #ed8936;">{{ $counts['in_progress'] ?? 0 }}</p>
                </div>
                <div style="font-size: 40px; color: #ed8936; opacity: 0.3;">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
        </div>

        <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 25px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
            <div style="display: flex; align-items: center; justify-content: between;">
                <div>
                    <h3 style="margin: 0 0 10px 0; font-size: 18px; color: #718096;">مكتملة</h3>
                    <p style="margin: 0; font-size: 32px; font-weight: 700; color: #48bb78;">{{ $counts['completed'] ?? 0 }}</p>
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

    <!-- Main Content -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <div style="text-align: center; padding: 60px 20px;">
            <div style="background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%); color: #2d3748; border-radius: 50%; width: 120px; height: 120px; display: flex; align-items: center; justify-content: center; margin: 0 auto 30px; font-size: 48px;">
                <i class="fas fa-flask"></i>
            </div>
            <h2 style="color: #2d3748; margin: 0 0 15px 0; font-size: 28px; font-weight: 700;">مرحباً بك في وحدة الفحوصات المخبرية</h2>
            <p style="color: #718096; margin: 0 0 30px 0; font-size: 18px; line-height: 1.6; max-width: 600px; margin-left: auto; margin-right: auto;">
                هذه الوحدة تتيح لك تتبع جميع الفحوصات المخبرية والتقارير الرقابية.
                يمكنك إدارة فحوصات الجودة، الثبات، التكافؤ الحيوي، والفحوصات الميكروبيولوجية.
            </p>

            <div style="display: flex; gap: 20px; justify-content: center; flex-wrap: wrap;">
                <button onclick="showAddTestModal()" style="background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%); color: #2d3748; padding: 15px 30px; border: none; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; cursor: pointer; font-size: 16px;">
                    <i class="fas fa-plus"></i>
                    إضافة فحص جديد
                </button>
                <button onclick="showImportModal()" style="background: rgba(255, 236, 210, 0.1); color: #2d3748; padding: 15px 30px; border: 2px solid #ffecd2; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; cursor: pointer; font-size: 16px;">
                    <i class="fas fa-upload"></i>
                    استيراد من Excel
                </button>
                <button onclick="showScheduleModal()" style="background: rgba(255, 236, 210, 0.1); color: #2d3748; padding: 15px 30px; border: 2px solid #ffecd2; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; cursor: pointer; font-size: 16px;">
                    <i class="fas fa-calendar"></i>
                    جدولة الفحوصات
                </button>
                <button onclick="exportTestsToExcel()" style="background: rgba(255, 236, 210, 0.1); color: #2d3748; padding: 15px 30px; border: 2px solid #ffecd2; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; cursor: pointer; font-size: 16px;">
                    <i class="fas fa-download"></i>
                    تصدير النتائج
                </button>
            </div>
        </div>

        <!-- Test Types Grid -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 25px; margin-top: 40px;">
            <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 15px; padding: 25px;">
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    <i class="fas fa-vial" style="font-size: 24px; margin-left: 15px;"></i>
                    <h3 style="margin: 0; font-size: 20px; font-weight: 700;">مراقبة الجودة</h3>
                </div>
                <p style="margin: 0; opacity: 0.9; line-height: 1.6;">
                    فحوصات شاملة لضمان جودة المنتجات وفقاً للمعايير الدولية
                </p>
            </div>

            <div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; border-radius: 15px; padding: 25px;">
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    <i class="fas fa-chart-line" style="font-size: 24px; margin-left: 15px;"></i>
                    <h3 style="margin: 0; font-size: 20px; font-weight: 700;">اختبار الثبات</h3>
                </div>
                <p style="margin: 0; opacity: 0.9; line-height: 1.6;">
                    دراسات الثبات لتحديد مدة الصلاحية وشروط التخزين المناسبة
                </p>
            </div>

            <div style="background: linear-gradient(135deg, #4ecdc4 0%, #44a08d 100%); color: white; border-radius: 15px; padding: 25px;">
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    <i class="fas fa-microscope" style="font-size: 24px; margin-left: 15px;"></i>
                    <h3 style="margin: 0; font-size: 20px; font-weight: 700;">الفحوصات الميكروبيولوجية</h3>
                </div>
                <p style="margin: 0; opacity: 0.9; line-height: 1.6;">
                    اختبارات العقامة والسموم الداخلية والحمولة الميكروبية
                </p>
            </div>

            <div style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); color: #2d3748; border-radius: 15px; padding: 25px;">
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    <i class="fas fa-balance-scale" style="font-size: 24px; margin-left: 15px;"></i>
                    <h3 style="margin: 0; font-size: 20px; font-weight: 700;">التكافؤ الحيوي</h3>
                </div>
                <p style="margin: 0; opacity: 0.8; line-height: 1.6;">
                    دراسات التكافؤ الحيوي والتوافر الحيوي للأدوية الجنيسة
                </p>
            </div>
        </div>
            <!-- Table of tests -->
            @if(isset($tests) && $tests instanceof Illuminate\Pagination\LengthAwarePaginator && $tests->total() > 0)
            <div class="table-responsive" style="margin-top: 20px;">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>اسم الفحص</th>
                            <th>نوع الفحص</th>
                            <th>المنتج</th>
                            <th>رقم الدفعة</th>
                            <th>المختبر</th>
                            <th>تاريخ الفحص</th>
                            <th>الحالة</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tests as $t)
                        <tr>
                            <td>{{ $t->test_name }}</td>
                            <td>{{ $t->test_type }}</td>
                            <td>{{ $t->product_name }}</td>
                            <td>{{ $t->batch_number }}</td>
                            <td>{{ $t->laboratory_name }}</td>
                            <td>{{ $t->test_date ? \Carbon\Carbon::parse($t->test_date)->format('Y-m-d') : '' }}</td>
                            <td>{{ $t->status }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div>
                    {{ $tests->links() }}
                </div>
            </div>
            @endif
    </div>
</div>

<script>
function showAddTestModal() {
    window.location.href = '{{ route("tenant.inventory.regulatory.laboratory-tests.create") }}';
}

function showImportModal() {
    window.location.href = '{{ route("tenant.inventory.regulatory.laboratory-tests.import.form") }}';
}

function showScheduleModal() {
    window.location.href = '{{ route("tenant.inventory.regulatory.laboratory-tests.schedule") }}';
}

function exportTestsToExcel() {
    window.location.href = '{{ route("tenant.inventory.regulatory.laboratory-tests.export") }}';
}
</script>

@endsection
