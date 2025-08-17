@extends('layouts.modern')

@section('title', 'شهادات الجودة')

@section('content')
<div style="padding: 30px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh;">
    <!-- Header -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; margin-bottom: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <div style="display: flex; align-items: center; justify-content: between; margin-bottom: 20px;">
            <div style="display: flex; align-items: center;">
                <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 50%; width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; margin-left: 20px; font-size: 32px;">
                    <i class="fas fa-certificate"></i>
                </div>
                <div>
                    <h1 style="color: #2d3748; margin: 0; font-size: 32px; font-weight: 700;">شهادات الجودة</h1>
                    <p style="color: #718096; margin: 5px 0 0 0; font-size: 16px;">دعم شهادات الجودة وتتبع الصلاحية</p>
                </div>
            </div>
            <div style="display: flex; gap: 15px;">
                <button onclick="showAddCertificateModal()" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 15px 25px; border: none; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; cursor: pointer;">
                    <i class="fas fa-plus"></i>
                    إضافة شهادة جديدة
                </button>
                <a href="{{ route('tenant.inventory.regulatory.dashboard') }}" style="background: rgba(255,255,255,0.2); color: #667eea; padding: 15px 25px; border: 2px solid #667eea; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; text-decoration: none;">
                    <i class="fas fa-arrow-right"></i>
                    العودة للوحة الرئيسية
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


    <!-- Stats Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px;">
        <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 25px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
            <div style="display: flex; align-items: center; justify-content: between;">
                <div>
                    <h3 style="margin: 0 0 10px 0; font-size: 18px; color: #718096;">إجمالي الشهادات</h3>
                    <p style="margin: 0; font-size: 32px; font-weight: 700; color: #2d3748;">{{ $counts['total'] ?? 0 }}</p>
                </div>
                <div style="font-size: 40px; color: #667eea; opacity: 0.3;">
                    <i class="fas fa-certificate"></i>
                </div>
            </div>
        </div>

        <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 25px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
            <div style="display: flex; align-items: center; justify-content: between;">
                <div>
                    <h3 style="margin: 0 0 10px 0; font-size: 18px; color: #718096;">صالحة</h3>
                    <p style="margin: 0; font-size: 32px; font-weight: 700; color: #48bb78;">{{ $counts['active'] ?? 0 }}</p>
                </div>
                <div style="font-size: 40px; color: #48bb78; opacity: 0.3;">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>

        <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 25px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
            <div style="display: flex; align-items: center; justify-content: between;">
                <div>
                    <h3 style="margin: 0 0 10px 0; font-size: 18px; color: #718096;">تنتهي قريباً</h3>
                    <p style="margin: 0; font-size: 32px; font-weight: 700; color: #ed8936;">{{ $counts['expiring_soon'] ?? 0 }}</p>
                </div>
                <div style="font-size: 40px; color: #ed8936; opacity: 0.3;">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
            </div>
        </div>

        <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 25px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
            <div style="display: flex; align-items: center; justify-content: between;">
                <div>
                    <h3 style="margin: 0 0 10px 0; font-size: 18px; color: #718096;">منتهية الصلاحية</h3>
                    <p style="margin: 0; font-size: 32px; font-weight: 700; color: #f56565;">{{ $counts['expired'] ?? 0 }}</p>
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
            <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 50%; width: 120px; height: 120px; display: flex; align-items: center; justify-content: center; margin: 0 auto 30px; font-size: 48px;">
                <i class="fas fa-certificate"></i>
            </div>
            <h2 style="color: #2d3748; margin: 0 0 15px 0; font-size: 28px; font-weight: 700;">مرحباً بك في وحدة شهادات الجودة</h2>
            <p style="color: #718096; margin: 0 0 30px 0; font-size: 18px; line-height: 1.6; max-width: 600px; margin-left: auto; margin-right: auto;">
                هذه الوحدة تتيح لك دعم شهادات الجودة وتتبع الصلاحية.
                يمكنك إدارة شهادات التحليل، شهادات GMP، ISO، الحلال، والشهادات العضوية.
            </p>

            <div style="display: flex; gap: 20px; justify-content: center; flex-wrap: wrap;">
                <button onclick="showAddCertificateModal()" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 15px 30px; border: none; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; cursor: pointer; font-size: 16px;">
                    <i class="fas fa-plus"></i>
                    إضافة شهادة جديدة
                </button>
                <button onclick="showRenewalModal()" style="background: rgba(102, 126, 234, 0.1); color: #667eea; padding: 15px 30px; border: 2px solid #667eea; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; cursor: pointer; font-size: 16px;">
                    <i class="fas fa-sync"></i>
                    تجديد الشهادات
                </button>
                <button onclick="exportCertificatesToExcel()" style="background: rgba(102, 126, 234, 0.1); color: #667eea; padding: 15px 30px; border: 2px solid #667eea; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; cursor: pointer; font-size: 16px;">
                    <i class="fas fa-download"></i>
                    تصدير الشهادات
                </button>
            </div>
        </div>

        <!-- Certificate Types Grid -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 25px; margin-top: 40px;">
            <div style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; border-radius: 15px; padding: 25px;">
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    <i class="fas fa-file-alt" style="font-size: 24px; margin-left: 15px;"></i>
                    <h3 style="margin: 0; font-size: 20px; font-weight: 700;">شهادة تحليل (COA)</h3>
                </div>
                <p style="margin: 0; opacity: 0.9; line-height: 1.6;">
                    شهادات تحليل المنتجات مع نتائج الفحوصات والمطابقة للمواصفات
                </p>
            </div>

            <div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; border-radius: 15px; padding: 25px;">
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    <i class="fas fa-industry" style="font-size: 24px; margin-left: 15px;"></i>
                    <h3 style="margin: 0; font-size: 20px; font-weight: 700;">شهادة GMP</h3>
                </div>
                <p style="margin: 0; opacity: 0.9; line-height: 1.6;">
                    شهادات ممارسات التصنيع الجيدة للمرافق والعمليات
                </p>
            </div>

            <div style="background: linear-gradient(135deg, #4ecdc4 0%, #44a08d 100%); color: white; border-radius: 15px; padding: 25px;">
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    <i class="fas fa-award" style="font-size: 24px; margin-left: 15px;"></i>
                    <h3 style="margin: 0; font-size: 20px; font-weight: 700;">شهادة ISO</h3>
                </div>
                <p style="margin: 0; opacity: 0.9; line-height: 1.6;">
                    شهادات المعايير الدولية لأنظمة إدارة الجودة
                </p>
            </div>

            <div style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); color: #2d3748; border-radius: 15px; padding: 25px;">
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    <i class="fas fa-leaf" style="font-size: 24px; margin-left: 15px;"></i>
                    <h3 style="margin: 0; font-size: 20px; font-weight: 700;">شهادات خاصة</h3>
                </div>
                <p style="margin: 0; opacity: 0.8; line-height: 1.6;">
                    شهادات الحلال، العضوي، والشهادات المتخصصة الأخرى
                </p>
            </div>
        </div>
        <!-- Certificates Table -->
        <div style="margin-top: 30px; background: rgba(255,255,255,0.95); border-radius: 20px; padding: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.08);">
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:12px;">
                <h3 style="margin:0; color:#2d3748; font-size:20px; font-weight:700;">
                    <i class="fas fa-table" style="margin-left:8px; color:#667eea;"></i>
                    قائمة الشهادات
                </h3>
            </div>
            @if(isset($certificates) && method_exists($certificates, 'count') && $certificates->count())
                <div style="overflow-x:auto;">
                    <table style="width:100%; border-collapse:collapse;">
                        <thead>
                            <tr style="background:#f7fafc; color:#2d3748; text-align:right;">
                                <th style="padding:10px; border-bottom:1px solid #e2e8f0;">الاسم</th>
                                <th style="padding:10px; border-bottom:1px solid #e2e8f0;">النوع</th>
                                <th style="padding:10px; border-bottom:1px solid #e2e8f0;">الرقم</th>
                                <th style="padding:10px; border-bottom:1px solid #e2e8f0;">الحالة</th>
                                <th style="padding:10px; border-bottom:1px solid #e2e8f0;">تاريخ الإصدار</th>
                                <th style="padding:10px; border-bottom:1px solid #e2e8f0;">تاريخ الانتهاء</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($certificates as $c)
                                <tr>
                                    <td style="padding:10px; border-bottom:1px solid #edf2f7;">{{ $c->certificate_name }}</td>
                                    <td style="padding:10px; border-bottom:1px solid #edf2f7;">{{ $c->getCertificateTypeLabel() }}</td>
                                    <td style="padding:10px; border-bottom:1px solid #edf2f7;">{{ $c->certificate_number }}</td>
                                    <td style="padding:10px; border-bottom:1px solid #edf2f7;">
                                        <span style="display:inline-block; padding:4px 10px; border-radius:999px; color:#fff; background: {{ $c->getCertificateStatusColor() }};">
                                            {{ $c->getCertificateStatusLabel() }}
                                        </span>
                                    </td>
                                    <td style="padding:10px; border-bottom:1px solid #edf2f7;">{{ $c->issue_date ? \Carbon\Carbon::parse($c->issue_date)->format('Y-m-d') : '' }}</td>
                                    <td style="padding:10px; border-bottom:1px solid #edf2f7;">{{ $c->expiry_date ? \Carbon\Carbon::parse($c->expiry_date)->format('Y-m-d') : '' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div style="margin-top:12px;">
                    {{ $certificates->links() }}
                </div>
            @else
                <div style="padding:12px; color:#718096;">لا توجد شهادات حتى الآن.</div>
            @endif
        </div>

    </div>
</div>

<script>
function showAddCertificateModal() {
    window.location.href = '{{ route("tenant.inventory.regulatory.certificates.create") }}';
}

function showImportModal() {
    window.location.href = '{{ route("tenant.inventory.regulatory.certificates.import.form") }}';
}

function showRenewalModal() {
    window.location.href = '{{ route("tenant.inventory.regulatory.certificates.renewal") }}';
}

function exportCertificatesToExcel() {
    window.location.href = '{{ route("tenant.inventory.regulatory.certificates.export") }}';
}
</script>

@endsection
