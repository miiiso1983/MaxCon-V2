@extends('layouts.modern')

@section('title', 'سحب المنتجات')

@section('content')
<div style="padding: 30px; background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%); min-height: 100vh;">
    <!-- Header -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; margin-bottom: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <div style="display: flex; align-items: center; justify-content: between; margin-bottom: 20px;">
            <div style="display: flex; align-items: center;">
                <div style="background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%); color: white; border-radius: 50%; width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; margin-left: 20px; font-size: 32px;">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div>
                    <h1 style="color: #2d3748; margin: 0; font-size: 32px; font-weight: 700;">سحب المنتجات</h1>
                    <p style="color: #718096; margin: 5px 0 0 0; font-size: 16px;">إدارة عمليات سحب المنتجات من السوق</p>
                </div>
            </div>
            <div style="display: flex; gap: 15px;">
                <a href="{{ route('tenant.inventory.regulatory.product-recalls.create') }}" style="background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%); color: white; padding: 15px 25px; border: none; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; text-decoration: none;">
                    <i class="fas fa-plus"></i>
                    بدء سحب جديد
                </a>
            </div>
        </div>
    </div>
    {{-- Flash Messages --}}
    @if(session('success'))
        <div style="background: rgba(72, 187, 120, 0.1); border: 2px solid #48bb78; border-radius: 15px; padding: 14px 18px; margin: 15px 0; color: #2d3748;">
            <i class="fas fa-check-circle" style="color:#48bb78; margin-left:8px;"></i>
            {{ session('success') }}
        </div>
    @endif
    @if(session('warning'))
        <div style="background: rgba(237, 137, 54, 0.1); border: 2px solid #ed8936; border-radius: 15px; padding: 14px 18px; margin: 15px 0; color: #2d3748;">
            <i class="fas fa-exclamation-triangle" style="color:#ed8936; margin-left:8px;"></i>
            {{ session('warning') }}
        </div>
    @endif
    @if(session('error'))
        <div style="background: rgba(245, 101, 101, 0.1); border: 2px solid #f56565; border-radius: 15px; padding: 14px 18px; margin: 15px 0; color: #2d3748;">
            <i class="fas fa-times-circle" style="color:#f56565; margin-left:8px;"></i>
            {{ session('error') }}
        </div>
    @endif
    @if($errors->any())
        <div style="background: rgba(245, 101, 101, 0.1); border: 2px solid #f56565; border-radius: 15px; padding: 14px 18px; margin: 15px 0; color: #2d3748;">
            <strong>يرجى تصحيح الأخطاء التالية:</strong>
            <ul style="margin:8px 0 0 0; padding-right:20px;">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Real Recalls Table -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); margin-bottom: 30px;">
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:12px;">
            <h3 style="margin:0; color:#2d3748; font-size:20px; font-weight:700;">
                <i class="fas fa-table" style="margin-left:8px; color:#ff9a9e;"></i>
                السحوبات المحفوظة
            </h3>
            <a href="{{ route('tenant.inventory.regulatory.product-recalls.create') }}" style="background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%); color: white; padding: 10px 16px; border-radius: 10px; font-weight: 600; text-decoration:none;">
                <i class="fas fa-plus" style="margin-left:6px;"></i> سحب جديد
            </a>
        </div>
        @if(isset($recalls) && method_exists($recalls, 'count') && $recalls->count())
            <div style="overflow-x:auto;">
                <table style="width:100%; border-collapse:collapse;">
                    <thead>
                        <tr style="background:#f7fafc; color:#2d3748; text-align:right;">
                            <th style="padding:10px; border-bottom:1px solid #e2e8f0;">العنوان</th>
                            <th style="padding:10px; border-bottom:1px solid #e2e8f0;">المنتج</th>
                            <th style="padding:10px; border-bottom:1px solid #e2e8f0;">نوع السحب</th>
                            <th style="padding:10px; border-bottom:1px solid #e2e8f0;">مستوى/فئة</th>
                            <th style="padding:10px; border-bottom:1px solid #e2e8f0;">الحالة</th>
                            <th style="padding:10px; border-bottom:1px solid #e2e8f0;">تاريخ البدء</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recalls as $r)
                            <tr>
                                <td style="padding:10px; border-bottom:1px solid #edf2f7;">{{ $r->recall_title ?? '' }}</td>
                                <td style="padding:10px; border-bottom:1px solid #edf2f7;">{{ $r->product_name ?? '' }}</td>
                                <td style="padding:10px; border-bottom:1px solid #edf2f7;">{{ $r->recall_type ?? '' }}</td>
                                <td style="padding:10px; border-bottom:1px solid #edf2f7;">{{ $r->risk_level ?? $r->recall_class ?? '' }}</td>
                                <td style="padding:10px; border-bottom:1px solid #edf2f7;">{{ $r->recall_status ?? $r->status ?? '' }}</td>
                                <td style="padding:10px; border-bottom:1px solid #edf2f7;">
                                    {{ ($r->initiated_date ?? $r->recall_initiation_date ?? $r->initiation_date ?? null) ? \Carbon\Carbon::parse($r->initiated_date ?? $r->recall_initiation_date ?? $r->initiation_date)->format('Y-m-d') : '' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div style="margin-top:12px;">
                {{ $recalls->links() }}
            </div>
        @else
            <div style="padding:12px; color:#718096;">لا توجد سجلات سحب محفوظة بعد.</div>
        @endif
    </div>


    <!-- Quick Actions -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px;">

        <!-- Add New Recall -->
        <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 25px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px); text-align: center; cursor: pointer; transition: transform 0.3s;"
             onclick="showCreateRecallModal()"
             onmouseover="this.style.transform='translateY(-5px)'"
             onmouseout="this.style.transform='translateY(0)'">
            <div style="background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%); color: white; border-radius: 50%; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; font-size: 24px;">
                <i class="fas fa-plus"></i>
            </div>
            <h3 style="color: #2d3748; margin: 0 0 10px 0; font-size: 18px; font-weight: 700;">بدء سحب جديد</h3>
            <p style="color: #718096; margin: 0; font-size: 14px;">إنشاء عملية سحب منتج جديدة</p>
        </div>

        <!-- Import Recalls -->
        <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 25px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px); text-align: center; cursor: pointer; transition: transform 0.3s;"
             onclick="showImportModal()"
             onmouseover="this.style.transform='translateY(-5px)'"
             onmouseout="this.style.transform='translateY(0)'">
            <div style="background: linear-gradient(135deg, #4ecdc4 0%, #44a08d 100%); color: white; border-radius: 50%; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; font-size: 24px;">
                <i class="fas fa-upload"></i>
            </div>
            <h3 style="color: #2d3748; margin: 0 0 10px 0; font-size: 18px; font-weight: 700;">استيراد من Excel</h3>
            <p style="color: #718096; margin: 0; font-size: 14px;">رفع ملف Excel لاستيراد السحوبات</p>
        </div>

        <!-- Export Recalls -->
        <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 25px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px); text-align: center; cursor: pointer; transition: transform 0.3s;"
             onclick="exportRecallsToExcel()"
             onmouseover="this.style.transform='translateY(-5px)'"
             onmouseout="this.style.transform='translateY(0)'">
            <div style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); color: white; border-radius: 50%; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; font-size: 24px;">
                <i class="fas fa-download"></i>
            </div>
            <h3 style="color: #2d3748; margin: 0 0 10px 0; font-size: 18px; font-weight: 700;">تصدير إلى Excel</h3>
            <p style="color: #718096; margin: 0; font-size: 14px;">تحميل بيانات السحوبات</p>
        </div>

        <!-- Recall Statistics -->
        <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 25px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px); text-align: center; cursor: pointer; transition: transform 0.3s;"
             onclick="showRecallStatistics()"
             onmouseover="this.style.transform='translateY(-5px)'"
             onmouseout="this.style.transform='translateY(0)'">
            <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 50%; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; font-size: 24px;">
                <i class="fas fa-chart-bar"></i>
            </div>
            <h3 style="color: #2d3748; margin: 0 0 10px 0; font-size: 18px; font-weight: 700;">إحصائيات السحب</h3>
            <p style="color: #718096; margin: 0; font-size: 14px;">عرض تقارير وإحصائيات</p>
        </div>
    </div>

    <!-- Recalls List -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <h3 style="color: #2d3748; margin: 0 0 25px 0; font-size: 24px; font-weight: 700; text-align: center;">
            <i class="fas fa-list" style="margin-left: 10px; color: #ff9a9e;"></i>
            قائمة عمليات السحب
        </h3>

        <!-- Sample Data Message -->
        <div style="background: rgba(255, 154, 158, 0.1); border: 2px solid #ff9a9e; border-radius: 15px; padding: 20px; margin-bottom: 25px; text-align: center;">
            <div style="display: flex; align-items: center; justify-content: center; gap: 10px; margin-bottom: 10px;">
                <i class="fas fa-info-circle" style="color: #ff9a9e; font-size: 20px;"></i>
                <strong style="color: #2d3748;">بيانات تجريبية</strong>
            </div>
            <p style="color: #4a5568; margin: 0;">البيانات المعروضة أدناه هي بيانات تجريبية لأغراض العرض. يمكنك إضافة عمليات سحب حقيقية باستخدام الأزرار أعلاه.</p>
        </div>

        <!-- Sample Recalls Table -->
        <div style="overflow-x: auto; border-radius: 15px; border: 1px solid #e2e8f0;">
            <table style="width: 100%; border-collapse: collapse; background: white;">
                <thead>
                    <tr style="background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%); color: white;">
                        <th style="padding: 15px; text-align: right; font-weight: 700;">عنوان السحب</th>
                        <th style="padding: 15px; text-align: right; font-weight: 700;">المنتج</th>
                        <th style="padding: 15px; text-align: right; font-weight: 700;">نوع السحب</th>
                        <th style="padding: 15px; text-align: right; font-weight: 700;">مستوى المخاطر</th>
                        <th style="padding: 15px; text-align: right; font-weight: 700;">الحالة</th>
                        <th style="padding: 15px; text-align: right; font-weight: 700;">تاريخ البدء</th>
                        <th style="padding: 15px; text-align: center; font-weight: 700;">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Sample Recall 1 -->
                    <tr style="border-bottom: 1px solid #e2e8f0; transition: background-color 0.3s;"
                        onmouseover="this.style.backgroundColor='#f7fafc'"
                        onmouseout="this.style.backgroundColor='white'">

                        <td style="padding: 15px; color: #2d3748; font-weight: 600;">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div style="background: #f56565; color: white; border-radius: 50%; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; font-size: 14px;">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                                <div>
                                    <div>سحب دواء باراسيتامول 500 مجم</div>
                                    <div style="font-size: 12px; color: #718096;">RECALL-2024-001</div>
                                </div>
                            </div>
                        </td>

                        <td style="padding: 15px; color: #4a5568;">
                            <div>باراسيتامول 500 مجم</div>
                            <div style="font-size: 12px; color: #718096;">دفعات: BATCH001, BATCH002</div>
                        </td>

                        <td style="padding: 15px;">
                            <span style="background: #ed8936; color: white; padding: 4px 8px; border-radius: 6px; font-size: 12px; font-weight: 600;">
                                طوعي
                            </span>
                        </td>

                        <td style="padding: 15px;">
                            <span style="background: #f56565; color: white; padding: 6px 12px; border-radius: 8px; font-size: 12px; font-weight: 600;">
                                الفئة الأولى
                            </span>
                        </td>

                        <td style="padding: 15px;">
                            <span style="background: #4299e1; color: white; padding: 6px 12px; border-radius: 8px; font-size: 12px; font-weight: 600;">
                                قيد التنفيذ
                            </span>
                        </td>

                        <td style="padding: 15px; color: #4a5568;">
                            {{ now()->subDays(5)->format('Y-m-d') }}
                        </td>

                        <td style="padding: 15px; text-align: center;">
                            <div style="display: flex; gap: 8px; justify-content: center;">
                                <button onclick="viewRecall('sample-1')"
                                        style="background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%); color: white; padding: 8px 12px; border: none; border-radius: 8px; font-size: 12px; font-weight: 600; cursor: pointer;">
                                    <i class="fas fa-eye" style="margin-left: 4px;"></i>
                                    عرض
                                </button>

                                <button onclick="editRecall('sample-1')"
                                        style="background: rgba(255, 154, 158, 0.1); color: #ff9a9e; padding: 8px 12px; border: 1px solid #ff9a9e; border-radius: 8px; font-size: 12px; font-weight: 600; cursor: pointer;">
                                    <i class="fas fa-edit" style="margin-left: 4px;"></i>
                                    تعديل
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Sample Recall 2 -->
                    <tr style="border-bottom: 1px solid #e2e8f0; transition: background-color 0.3s;"
                        onmouseover="this.style.backgroundColor='#f7fafc'"
                        onmouseout="this.style.backgroundColor='white'">

                        <td style="padding: 15px; color: #2d3748; font-weight: 600;">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div style="background: #ed8936; color: white; border-radius: 50%; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; font-size: 14px;">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                                <div>
                                    <div>سحب مضاد حيوي أموكسيسيلين</div>
                                    <div style="font-size: 12px; color: #718096;">RECALL-2024-002</div>
                                </div>
                            </div>
                        </td>

                        <td style="padding: 15px; color: #4a5568;">
                            <div>أموكسيسيلين 250 مجم</div>
                            <div style="font-size: 12px; color: #718096;">دفعات: BATCH003, BATCH004</div>
                        </td>

                        <td style="padding: 15px;">
                            <span style="background: #f56565; color: white; padding: 4px 8px; border-radius: 6px; font-size: 12px; font-weight: 600;">
                                إجباري
                            </span>
                        </td>

                        <td style="padding: 15px;">
                            <span style="background: #ed8936; color: white; padding: 6px 12px; border-radius: 8px; font-size: 12px; font-weight: 600;">
                                الفئة الثانية
                            </span>
                        </td>

                        <td style="padding: 15px;">
                            <span style="background: #48bb78; color: white; padding: 6px 12px; border-radius: 8px; font-size: 12px; font-weight: 600;">
                                مكتمل
                            </span>
                        </td>

                        <td style="padding: 15px; color: #4a5568;">
                            {{ now()->subDays(15)->format('Y-m-d') }}
                        </td>

                        <td style="padding: 15px; text-align: center;">
                            <div style="display: flex; gap: 8px; justify-content: center;">
                                <button onclick="viewRecall('sample-2')"
                                        style="background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%); color: white; padding: 8px 12px; border: none; border-radius: 8px; font-size: 12px; font-weight: 600; cursor: pointer;">
                                    <i class="fas fa-eye" style="margin-left: 4px;"></i>
                                    عرض
                                </button>

                                <button onclick="generateReport('sample-2')"
                                        style="background: rgba(72, 187, 120, 0.1); color: #48bb78; padding: 8px 12px; border: 1px solid #48bb78; border-radius: 8px; font-size: 12px; font-weight: 600; cursor: pointer;">
                                    <i class="fas fa-file-alt" style="margin-left: 4px;"></i>
                                    تقرير
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Sample Recall 3 -->
                    <tr style="border-bottom: 1px solid #e2e8f0; transition: background-color 0.3s;"
                        onmouseover="this.style.backgroundColor='#f7fafc'"
                        onmouseout="this.style.backgroundColor='white'">

                        <td style="padding: 15px; color: #2d3748; font-weight: 600;">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div style="background: #ecc94b; color: white; border-radius: 50%; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; font-size: 14px;">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                                <div>
                                    <div>سحب فيتامين د من السوق</div>
                                    <div style="font-size: 12px; color: #718096;">RECALL-2024-003</div>
                                </div>
                            </div>
                        </td>

                        <td style="padding: 15px; color: #4a5568;">
                            <div>فيتامين د 1000 وحدة</div>
                            <div style="font-size: 12px; color: #718096;">دفعات: BATCH005</div>
                        </td>

                        <td style="padding: 15px;">
                            <span style="background: #4299e1; color: white; padding: 4px 8px; border-radius: 6px; font-size: 12px; font-weight: 600;">
                                سحب من السوق
                            </span>
                        </td>

                        <td style="padding: 15px;">
                            <span style="background: #ecc94b; color: white; padding: 6px 12px; border-radius: 8px; font-size: 12px; font-weight: 600;">
                                الفئة الثالثة
                            </span>
                        </td>

                        <td style="padding: 15px;">
                            <span style="background: #ed8936; color: white; padding: 6px 12px; border-radius: 8px; font-size: 12px; font-weight: 600;">
                                مجدول
                            </span>
                        </td>

                        <td style="padding: 15px; color: #4a5568;">
                            {{ now()->addDays(3)->format('Y-m-d') }}
                        </td>

                        <td style="padding: 15px; text-align: center;">
                            <div style="display: flex; gap: 8px; justify-content: center;">
                                <button onclick="viewRecall('sample-3')"
                                        style="background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%); color: white; padding: 8px 12px; border: none; border-radius: 8px; font-size: 12px; font-weight: 600; cursor: pointer;">
                                    <i class="fas fa-eye" style="margin-left: 4px;"></i>
                                    عرض
                                </button>

                                <button onclick="startRecall('sample-3')"
                                        style="background: rgba(237, 137, 54, 0.1); color: #ed8936; padding: 8px 12px; border: 1px solid #ed8936; border-radius: 8px; font-size: 12px; font-weight: 600; cursor: pointer;">
                                    <i class="fas fa-play" style="margin-left: 4px;"></i>
                                    بدء
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function showCreateRecallModal() {
    window.location.href = '{{ route("tenant.inventory.regulatory.product-recalls.create") }}';
}

function showImportModal() {
    window.location.href = '{{ route("tenant.inventory.regulatory.product-recalls.import.form") }}';
}

function exportRecallsToExcel() {
    window.location.href = '{{ route("tenant.inventory.regulatory.product-recalls.export") }}';
}

function showRecallStatistics() {
    window.location.href = '{{ route("tenant.inventory.regulatory.product-recalls.statistics") }}';
}

function viewRecall(recallId) {
    alert('عرض تفاصيل السحب: ' + recallId + '\nسيتم فتح صفحة التفاصيل قريباً');
}

function editRecall(recallId) {
    alert('تعديل السحب: ' + recallId + '\nسيتم فتح صفحة التعديل قريباً');
}

function generateReport(recallId) {
    alert('إنشاء تقرير للسحب: ' + recallId + '\nسيتم إنشاء التقرير قريباً');
}

function startRecall(recallId) {
    if (confirm('هل أنت متأكد من بدء عملية السحب؟')) {
        alert('تم بدء عملية السحب: ' + recallId);
    }
}
</script>

@endsection
