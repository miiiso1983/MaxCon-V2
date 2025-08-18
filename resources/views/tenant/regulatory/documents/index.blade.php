@extends('layouts.modern')

@section('title', 'الوثائق التنظيمية')

@section('content')
<div style="padding: 30px; background: linear-gradient(135deg, #4ecdc4 0%, #44a08d 100%); min-height: 100vh;">
    <!-- Header -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; margin-bottom: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <div style="display: flex; align-items: center; justify-content: between; margin-bottom: 20px;">
            <div style="display: flex; align-items: center;">
                <div style="background: linear-gradient(135deg, #4ecdc4 0%, #44a08d 100%); color: white; border-radius: 50%; width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; margin-left: 20px; font-size: 32px;">
                    <i class="fas fa-folder-open"></i>
                </div>
                <div>
                    <h1 style="color: #2d3748; margin: 0; font-size: 32px; font-weight: 700;">الوثائق التنظيمية</h1>
                    <p style="color: #718096; margin: 5px 0 0 0; font-size: 16px;">حفظ الوثائق القانونية والتنظيمية</p>
                </div>
            </div>
            <div style="display: flex; gap: 15px;">
                <button onclick="showAddDocumentModal()" style="background: linear-gradient(135deg, #4ecdc4 0%, #44a08d 100%); color: white; padding: 15px 25px; border: none; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; cursor: pointer;">
                    <i class="fas fa-plus"></i>
                    إضافة وثيقة جديدة
                </button>
                <a href="{{ route('tenant.inventory.regulatory.dashboard') }}" style="background: rgba(255,255,255,0.2); color: #4ecdc4; padding: 15px 25px; border: 2px solid #4ecdc4; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; text-decoration: none;">
                    <i class="fas fa-arrow-right"></i>
                    العودة للوحة الرئيسية
                </a>
            </div>
        </div>
    </div>
    @if(($documents ?? null) && count($documents))
    <div class="mx-auto" style="background:#fff; border:1px solid #e5e7eb; border-radius:14px; padding:18px; box-shadow: 0 8px 20px rgba(0,0,0,0.06); margin-bottom:20px;">
        <div style="display:flex; align-items:center; justify-content:space-between; gap:12px; margin-bottom:12px;">
            <h2 style="margin:0; font-size:20px; color:#111827;">سجل الوثائق</h2>
            <div style="display:flex; gap:8px;">
                <a href="{{ route('tenant.inventory.regulatory.documents.create') }}" style="background:#10b981; color:#fff; padding:8px 12px; border-radius:10px; text-decoration:none;">إضافة وثيقة</a>
                <a href="{{ route('tenant.inventory.regulatory.documents.bulk-upload') }}" style="background:#f3f4f6; color:#111827; padding:8px 12px; border-radius:10px; text-decoration:none;">رفع متعدد</a>
                <a href="{{ route('tenant.inventory.regulatory.documents.export') }}" style="background:#2563eb; color:#fff; padding:8px 12px; border-radius:10px; text-decoration:none;">تصدير CSV</a>
            </div>
        </div>
        <form method="GET" action="{{ route('tenant.inventory.regulatory.documents.index') }}" style="display:flex; gap:8px; flex-wrap:wrap; margin-bottom:12px;">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="بحث بالعنوان/الرقم/الجهة" style="padding:8px 10px; border:1px solid #e5e7eb; border-radius:10px; min-width:240px;">
            <select name="type" style="padding:8px 10px; border:1px solid #e5e7eb; border-radius:10px;">
                <option value="">النوع</option>
                @foreach(\App\Models\Tenant\Regulatory\RegulatoryDocument::DOCUMENT_TYPES as $key => $label)
                    <option value="{{ $key }}" {{ request('type')===$key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            <select name="status" style="padding:8px 10px; border:1px solid #e5e7eb; border-radius:10px;">
                <option value="">الحالة</option>
                @foreach(\App\Models\Tenant\Regulatory\RegulatoryDocument::STATUS_TYPES as $key => $label)
                    <option value="{{ $key }}" {{ request('status')===$key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            <input type="date" name="issue_from" value="{{ request('issue_from') }}" style="padding:8px 10px; border:1px solid #e5e7eb; border-radius:10px;">
            <input type="date" name="issue_to" value="{{ request('issue_to') }}" style="padding:8px 10px; border:1px solid #e5e7eb; border-radius:10px;">
            <button type="submit" style="background:#111827; color:#fff; padding:8px 12px; border:none; border-radius:10px;">تصفية</button>
            <a href="{{ route('tenant.inventory.regulatory.documents.index') }}" style="background:#f3f4f6; color:#111827; padding:8px 12px; border-radius:10px; text-decoration:none;">تفريغ</a>
        </form>
        <div style="overflow:auto; border:1px solid #e5e7eb; border-radius:12px;">
            <table style="width:100%; border-collapse:separate; border-spacing:0;">
                <thead>
                    <tr style="background:#f9fafb; color:#374151;">
                        <th style="padding:10px 12px; text-align:right;">#</th>
                        <th style="padding:10px 12px; text-align:right;">العنوان</th>
                        <th style="padding:10px 12px; text-align:right;">نوع الوثيقة</th>
                        <th style="padding:10px 12px; text-align:right;">رقم الوثيقة</th>
                        <th style="padding:10px 12px; text-align:right;">الجهة</th>
                        <th style="padding:10px 12px; text-align:right;">تاريخ الإصدار</th>
                        <th style="padding:10px 12px; text-align:right;">الانتهاء</th>
                        <th style="padding:10px 12px; text-align:right;">الملف</th>
                        <th style="padding:10px 12px; text-align:right;">الحالة</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($documents as $d)
                        <tr style="border-top:1px solid #e5e7eb;">
                            <td style="padding:10px 12px; color:#6b7280;">{{ $loop->iteration }}</td>
                            <td style="padding:10px 12px; font-weight:600; color:#111827;">{{ $d->document_title ?? $d->title ?? '-' }}</td>
                            <td style="padding:10px 12px;">{{ $d->document_type_name ?? $d->document_type }}</td>
                            <td style="padding:10px 12px;">{{ $d->document_number ?? '-' }}</td>
                            <td style="padding:10px 12px;">{{ $d->issuing_authority ?? $d->regulatory_authority ?? '-' }}</td>
                            <td style="padding:10px 12px;">{{ $d->issue_date ? $d->issue_date->format('Y-m-d') : ($d->submission_date ? $d->submission_date->format('Y-m-d') : '-') }}</td>
                            <td style="padding:10px 12px;">{{ $d->expiry_date ? $d->expiry_date->format('Y-m-d') : '-' }}</td>
                            <td style="padding:10px 12px;">
                                @if(!empty($d->file_path))
                                    <a href="{{ route('tenant.inventory.regulatory.documents.download', $d->id) }}" style="color:#2563eb; text-decoration:none;">تحميل</a>
                                @else
                                    -
                                @endif
                            </td>
                            <td style="padding:10px 12px;">{{ $d->status ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="margin-top:10px;">{{ method_exists($documents, 'links') ? $documents->links() : '' }}</div>
    </div>
    @endif

    <!-- Stats Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px;">
        <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 25px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
            <div style="display: flex; align-items: center; justify-content: between;">
                <div>
                    <h3 style="margin: 0 0 10px 0; font-size: 18px; color: #718096;">إجمالي الوثائق</h3>
                    <p style="margin: 0; font-size: 32px; font-weight: 700; color: #2d3748;">0</p>
                </div>
                <div style="font-size: 40px; color: #4ecdc4; opacity: 0.3;">
                    <i class="fas fa-folder-open"></i>
                </div>
            </div>
        </div>

        <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 25px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
            <div style="display: flex; align-items: center; justify-content: between;">
                <div>
                    <h3 style="margin: 0 0 10px 0; font-size: 18px; color: #718096;">نافذة</h3>
                    <p style="margin: 0; font-size: 32px; font-weight: 700; color: #48bb78;">0</p>
                </div>
                <div style="font-size: 40px; color: #48bb78; opacity: 0.3;">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>

        <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 25px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
            <div style="display: flex; align-items: center; justify-content: between;">
                <div>
                    <h3 style="margin: 0 0 10px 0; font-size: 18px; color: #718096;">تحتاج مراجعة</h3>
                    <p style="margin: 0; font-size: 32px; font-weight: 700; color: #ed8936;">0</p>
                </div>
                <div style="font-size: 40px; color: #ed8936; opacity: 0.3;">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
        </div>

        <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 25px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
            <div style="display: flex; align-items: center; justify-content: between;">
                <div>
                    <h3 style="margin: 0 0 10px 0; font-size: 18px; color: #718096;">منتهية الصلاحية</h3>
                    <p style="margin: 0; font-size: 32px; font-weight: 700; color: #f56565;">0</p>
                </div>
                <div style="font-size: 40px; color: #f56565; opacity: 0.3;">
                    <i class="fas fa-times-circle"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <div style="text-align: center; padding: 60px 20px;">
            <div style="background: linear-gradient(135deg, #4ecdc4 0%, #44a08d 100%); color: white; border-radius: 50%; width: 120px; height: 120px; display: flex; align-items: center; justify-content: center; margin: 0 auto 30px; font-size: 48px;">
                <i class="fas fa-folder-open"></i>
            </div>
            <h2 style="color: #2d3748; margin: 0 0 15px 0; font-size: 28px; font-weight: 700;">مرحباً بك في وحدة الوثائق التنظيمية</h2>
            <p style="color: #718096; margin: 0 0 30px 0; font-size: 18px; line-height: 1.6; max-width: 600px; margin-left: auto; margin-right: auto;">
                هذه الوحدة تتيح لك حفظ الوثائق القانونية والتنظيمية بطريقة آمنة ومنظمة.
                يمكنك إدارة التراخيص، الشهادات، السياسات، والإجراءات مع تتبع تواريخ المراجعة والانتهاء.
            </p>

            <div style="display: flex; gap: 20px; justify-content: center; flex-wrap: wrap;">
                <button onclick="showAddDocumentModal()" style="background: linear-gradient(135deg, #4ecdc4 0%, #44a08d 100%); color: white; padding: 15px 30px; border: none; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; cursor: pointer; font-size: 16px;">
                    <i class="fas fa-plus"></i>
                    إضافة وثيقة جديدة
                </button>
                <button onclick="showBulkUploadModal()" style="background: rgba(78, 205, 196, 0.1); color: #4ecdc4; padding: 15px 30px; border: 2px solid #4ecdc4; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; cursor: pointer; font-size: 16px;">
                    <i class="fas fa-upload"></i>
                    رفع ملفات متعددة
                </button>
                <button onclick="showArchiveModal()" style="background: rgba(78, 205, 196, 0.1); color: #4ecdc4; padding: 15px 30px; border: 2px solid #4ecdc4; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; cursor: pointer; font-size: 16px;">
                    <i class="fas fa-archive"></i>
                    إدارة الأرشيف
                </button>
                <button onclick="exportDocumentsToExcel()" style="background: rgba(78, 205, 196, 0.1); color: #4ecdc4; padding: 15px 30px; border: 2px solid #4ecdc4; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; cursor: pointer; font-size: 16px;">
                    <i class="fas fa-download"></i>
                    تصدير التقرير
                </button>
            </div>
        </div>

        <!-- Document Types Grid -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 25px; margin-top: 40px;">
            <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 15px; padding: 25px;">
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    <i class="fas fa-certificate" style="font-size: 24px; margin-left: 15px;"></i>
                    <h3 style="margin: 0; font-size: 20px; font-weight: 700;">التراخيص والشهادات</h3>
                </div>
                <p style="margin: 0; opacity: 0.9; line-height: 1.6;">
                    تراخيص التشغيل، شهادات الجودة، والموافقات الرسمية
                </p>
            </div>

            <div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; border-radius: 15px; padding: 25px;">
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    <i class="fas fa-file-contract" style="font-size: 24px; margin-left: 15px;"></i>
                    <h3 style="margin: 0; font-size: 20px; font-weight: 700;">السياسات والإجراءات</h3>
                </div>
                <p style="margin: 0; opacity: 0.9; line-height: 1.6;">
                    إجراءات التشغيل المعيارية والسياسات الداخلية
                </p>
            </div>

            <div style="background: linear-gradient(135deg, #42a5f5 0%, #2196f3 100%); color: white; border-radius: 15px; padding: 25px;">
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    <i class="fas fa-balance-scale" style="font-size: 24px; margin-left: 15px;"></i>
                    <h3 style="margin: 0; font-size: 20px; font-weight: 700;">الوثائق القانونية</h3>
                </div>
                <p style="margin: 0; opacity: 0.9; line-height: 1.6;">
                    العقود، الاتفاقيات، والمراسلات الرسمية
                </p>
            </div>

            <div style="background: linear-gradient(135deg, #66bb6a 0%, #4caf50 100%); color: white; border-radius: 15px; padding: 25px;">
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    <i class="fas fa-clipboard-list" style="font-size: 24px; margin-left: 15px;"></i>
                    <h3 style="margin: 0; font-size: 20px; font-weight: 700;">وثائق الامتثال</h3>
                </div>
                <p style="margin: 0; opacity: 0.9; line-height: 1.6;">
                    تقارير التفتيش، خطط الامتثال، والإجراءات التصحيحية
                </p>
            </div>
        </div>
    </div>
</div>

<script>
function showAddDocumentModal() {
    window.location.href = '{{ route("tenant.inventory.regulatory.documents.create") }}';
}

function showBulkUploadModal() {
    window.location.href = '{{ route("tenant.inventory.regulatory.documents.bulk-upload") }}';
}

function showArchiveModal() {
    window.location.href = '{{ route("tenant.inventory.regulatory.documents.archive") }}';
}

function exportDocumentsToExcel() {
    window.location.href = '{{ route("tenant.inventory.regulatory.documents.export") }}';
}
</script>

@endsection
