@extends('layouts.modern')

@section('page-title', 'استيراد العملاء من Excel')
@section('page-description', 'استيراد قائمة العملاء من ملف Excel')

@section('content')
<!-- Page Header -->
<div style="background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%); border-radius: 20px; padding: 30px; margin-bottom: 30px; color: white; position: relative; overflow: hidden;">
    <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
    <div style="position: absolute; bottom: -30px; left: -30px; width: 150px; height: 150px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>
    
    <div style="position: relative; z-index: 2;">
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    <div style="background: rgba(255,255,255,0.2); border-radius: 15px; padding: 15px; margin-left: 20px;">
                        <i class="fas fa-file-excel" style="font-size: 32px;"></i>
                    </div>
                    <div>
                        <h1 style="font-size: 32px; font-weight: 800; margin: 0; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                            استيراد العملاء من Excel 📊
                        </h1>
                        <p style="font-size: 18px; margin: 5px 0 0 0; opacity: 0.9;">
                            استيراد قائمة العملاء بشكل مجمع من ملف Excel
                        </p>
                    </div>
                </div>
            </div>
            
            <div style="display: flex; gap: 15px;">
                <a href="{{ route('tenant.sales.customers.template') }}" style="background: rgba(255,255,255,0.2); color: white; padding: 15px 25px; border-radius: 15px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3); transition: all 0.3s ease;"
                   onmouseover="this.style.background='rgba(255,255,255,0.3)'; this.style.transform='translateY(-2px)';"
                   onmouseout="this.style.background='rgba(255,255,255,0.2)'; this.style.transform='translateY(0)';">
                    <i class="fas fa-download"></i>
                    تحميل النموذج
                </a>
                <a href="{{ route('tenant.sales.customers.index') }}" style="background: rgba(255,255,255,0.2); color: white; padding: 15px 25px; border-radius: 15px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3); transition: all 0.3s ease;"
                   onmouseover="this.style.background='rgba(255,255,255,0.3)'; this.style.transform='translateY(-2px)';"
                   onmouseout="this.style.background='rgba(255,255,255,0.2)'; this.style.transform='translateY(0)';">
                    <i class="fas fa-arrow-right"></i>
                    العودة للقائمة
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Instructions -->
<div class="content-card" style="margin-bottom: 25px;">
    <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
        <i class="fas fa-info-circle" style="color: #4299e1; margin-left: 10px;"></i>
        تعليمات الاستيراد
    </h3>
    
    <div style="background: #f0f9ff; border: 1px solid #bae6fd; border-radius: 12px; padding: 20px; margin-bottom: 20px;">
        <h4 style="color: #0369a1; margin: 0 0 15px 0; font-weight: 600;">خطوات الاستيراد:</h4>
        <ol style="color: #0c4a6e; margin: 0; padding-right: 20px;">
            <li style="margin-bottom: 8px;">قم بتحميل النموذج المجاني من الزر أعلاه</li>
            <li style="margin-bottom: 8px;">املأ البيانات في النموذج حسب التعليمات</li>
            <li style="margin-bottom: 8px;">احفظ الملف بصيغة Excel (.xlsx أو .xls) أو CSV</li>
            <li style="margin-bottom: 8px;">ارفع الملف باستخدام النموذج أدناه</li>
            <li>انقر على "استيراد العملاء" لبدء عملية الاستيراد</li>
        </ol>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
        <!-- Required Fields -->
        <div style="background: #fef2f2; border: 1px solid #fecaca; border-radius: 12px; padding: 20px;">
            <h4 style="color: #dc2626; margin: 0 0 15px 0; font-weight: 600; display: flex; align-items: center;">
                <i class="fas fa-exclamation-triangle" style="margin-left: 8px;"></i>
                الحقول المطلوبة
            </h4>
            <ul style="color: #7f1d1d; margin: 0; padding-right: 20px;">
                <li>name - اسم العميل</li>
                <li>customer_type - نوع العميل (individual أو company)</li>
                <li>payment_terms - شروط الدفع</li>
                <li>credit_limit - الحد الائتماني</li>
                <li>currency - العملة</li>
                <li>country - الدولة</li>
            </ul>
        </div>

        <!-- Optional Fields -->
        <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 12px; padding: 20px;">
            <h4 style="color: #16a34a; margin: 0 0 15px 0; font-weight: 600; display: flex; align-items: center;">
                <i class="fas fa-check-circle" style="margin-left: 8px;"></i>
                الحقول الاختيارية
            </h4>
            <ul style="color: #14532d; margin: 0; padding-right: 20px;">
                <li>email - البريد الإلكتروني</li>
                <li>phone - الهاتف</li>
                <li>mobile - الجوال</li>
                <li>address - العنوان</li>
                <li>city - المدينة</li>
                <li>state - المنطقة</li>
                <li>postal_code - الرمز البريدي</li>
                <li>tax_number - الرقم الضريبي</li>
                <li>commercial_register - السجل التجاري</li>
                <li>notes - ملاحظات</li>
            </ul>
        </div>
    </div>

    <div style="background: #fffbeb; border: 1px solid #fed7aa; border-radius: 12px; padding: 20px; margin-top: 20px;">
        <h4 style="color: #d97706; margin: 0 0 10px 0; font-weight: 600; display: flex; align-items: center;">
            <i class="fas fa-lightbulb" style="margin-left: 8px;"></i>
            نصائح مهمة
        </h4>
        <ul style="color: #92400e; margin: 0; padding-right: 20px;">
            <li>تأكد من أن أسماء الأعمدة تطابق النموذج تماماً</li>
            <li>لا تترك الحقول المطلوبة فارغة</li>
            <li>استخدم القيم المحددة للحقول مثل customer_type و payment_terms</li>
            <li>سيتم تخطي العملاء المكررين تلقائياً</li>
            <li>الحد الأقصى لحجم الملف هو 10 ميجابايت</li>
        </ul>
    </div>
</div>

<!-- Upload Form -->
<div class="content-card">
    <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
        <i class="fas fa-upload" style="color: #48bb78; margin-left: 10px;"></i>
        رفع ملف Excel
    </h3>

    <form method="POST" action="{{ route('tenant.sales.customers.process-import') }}" enctype="multipart/form-data">
        @csrf
        
        <div style="border: 2px dashed #d1d5db; border-radius: 12px; padding: 40px; text-align: center; margin-bottom: 20px; transition: all 0.3s ease;" 
             id="dropZone"
             ondragover="event.preventDefault(); this.style.borderColor='#4299e1'; this.style.background='#f0f9ff';"
             ondragleave="this.style.borderColor='#d1d5db'; this.style.background='white';"
             ondrop="handleDrop(event);">
            
            <div style="margin-bottom: 20px;">
                <i class="fas fa-cloud-upload-alt" style="font-size: 48px; color: #9ca3af; margin-bottom: 15px;"></i>
                <h4 style="color: #374151; margin: 0 0 10px 0;">اسحب وأفلت ملف Excel هنا</h4>
                <p style="color: #6b7280; margin: 0;">أو انقر لاختيار الملف</p>
            </div>
            
            <input type="file" name="excel_file" id="excelFile" accept=".xlsx,.xls,.csv" required 
                   style="display: none;" onchange="displayFileName(this)">
            
            <button type="button" onclick="document.getElementById('excelFile').click()" 
                    style="background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%); color: white; padding: 12px 24px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                <i class="fas fa-folder-open" style="margin-left: 8px;"></i>
                اختيار ملف
            </button>
            
            <div id="fileName" style="margin-top: 15px; color: #059669; font-weight: 600; display: none;">
                <i class="fas fa-file-excel" style="margin-left: 8px;"></i>
                <span></span>
            </div>
        </div>

        @error('excel_file')
            <div style="color: #f56565; font-size: 14px; margin-bottom: 15px; text-align: center;">{{ $message }}</div>
        @enderror

        <div style="text-align: center;">
            <button type="submit" class="btn-blue" style="padding: 15px 30px; font-size: 16px;" id="submitBtn" disabled>
                <i class="fas fa-upload" style="margin-left: 8px;"></i>
                استيراد العملاء
            </button>
        </div>
    </form>
</div>

<!-- Import Failures Display -->
@if(session('import_failures'))
<div class="content-card" style="margin-top: 25px;">
    <h3 style="font-size: 20px; font-weight: 700; color: #dc2626; margin-bottom: 20px; display: flex; align-items: center;">
        <i class="fas fa-exclamation-triangle" style="color: #dc2626; margin-left: 10px;"></i>
        أخطاء في البيانات
    </h3>
    
    <div style="background: #fef2f2; border: 1px solid #fecaca; border-radius: 12px; padding: 20px; max-height: 400px; overflow-y: auto;">
        @foreach(session('import_failures') as $failure)
            <div style="margin-bottom: 15px; padding: 10px; background: white; border-radius: 8px; border-right: 4px solid #dc2626;">
                <div style="font-weight: 600; color: #dc2626; margin-bottom: 5px;">
                    الصف {{ $failure->row() }}:
                </div>
                <ul style="margin: 0; padding-right: 20px; color: #7f1d1d;">
                    @foreach($failure->errors() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endforeach
    </div>
</div>
@endif

@push('scripts')
<script>
function handleDrop(event) {
    event.preventDefault();
    const dropZone = event.target;
    dropZone.style.borderColor = '#d1d5db';
    dropZone.style.background = 'white';
    
    const files = event.dataTransfer.files;
    if (files.length > 0) {
        document.getElementById('excelFile').files = files;
        displayFileName(document.getElementById('excelFile'));
    }
}

function displayFileName(input) {
    const fileName = document.getElementById('fileName');
    const submitBtn = document.getElementById('submitBtn');
    
    if (input.files && input.files[0]) {
        fileName.style.display = 'block';
        fileName.querySelector('span').textContent = input.files[0].name;
        submitBtn.disabled = false;
        submitBtn.style.opacity = '1';
    } else {
        fileName.style.display = 'none';
        submitBtn.disabled = true;
        submitBtn.style.opacity = '0.5';
    }
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    const submitBtn = document.getElementById('submitBtn');
    submitBtn.style.opacity = '0.5';
});
</script>
@endpush
@endsection
