@extends('layouts.modern')

@section('page-title', 'استيراد المنتجات من Excel')
@section('page-description', 'استيراد قائمة المنتجات الدوائية من ملف Excel')

@section('content')
<!-- Page Header -->
<div style="background: linear-gradient(135deg, #9f7aea 0%, #805ad5 100%); border-radius: 20px; padding: 30px; margin-bottom: 30px; color: white; position: relative; overflow: hidden;">
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
                            استيراد المنتجات من Excel 💊
                        </h1>
                        <p style="font-size: 18px; margin: 5px 0 0 0; opacity: 0.9;">
                            استيراد قائمة المنتجات الدوائية بشكل مجمع من ملف Excel
                        </p>
                    </div>
                </div>
            </div>
            
            <div style="display: flex; gap: 15px;">
                <a href="{{ route('tenant.sales.products.template') }}" style="background: rgba(255,255,255,0.2); color: white; padding: 15px 25px; border-radius: 15px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3); transition: all 0.3s ease;"
                   onmouseover="this.style.background='rgba(255,255,255,0.3)'; this.style.transform='translateY(-2px)';"
                   onmouseout="this.style.background='rgba(255,255,255,0.2)'; this.style.transform='translateY(0)';">
                    <i class="fas fa-download"></i>
                    تحميل النموذج
                </a>
                <a href="{{ route('tenant.sales.products.index') }}" style="background: rgba(255,255,255,0.2); color: white; padding: 15px 25px; border-radius: 15px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3); transition: all 0.3s ease;"
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
        <i class="fas fa-info-circle" style="color: #9f7aea; margin-left: 10px;"></i>
        تعليمات الاستيراد
    </h3>
    
    <div style="background: #f3e8ff; border: 1px solid #c084fc; border-radius: 12px; padding: 20px; margin-bottom: 20px;">
        <h4 style="color: #7c3aed; margin: 0 0 15px 0; font-weight: 600;">خطوات الاستيراد:</h4>
        <ol style="color: #581c87; margin: 0; padding-right: 20px;">
            <li style="margin-bottom: 8px;">قم بتحميل النموذج المجاني من الزر أعلاه</li>
            <li style="margin-bottom: 8px;">املأ البيانات في النموذج حسب التعليمات</li>
            <li style="margin-bottom: 8px;">احفظ الملف بصيغة Excel (.xlsx أو .xls) أو CSV</li>
            <li style="margin-bottom: 8px;">ارفع الملف باستخدام النموذج أدناه</li>
            <li>انقر على "استيراد المنتجات" لبدء عملية الاستيراد</li>
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
                <li>name - اسم المنتج</li>
                <li>category - الفئة</li>
                <li>unit - الوحدة</li>
                <li>purchase_price - سعر الشراء</li>
                <li>selling_price - سعر البيع</li>
                <li>min_stock_level - الحد الأدنى للمخزون</li>
                <li>current_stock - المخزون الحالي</li>
            </ul>
        </div>

        <!-- Optional Fields -->
        <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 12px; padding: 20px;">
            <h4 style="color: #16a34a; margin: 0 0 15px 0; font-weight: 600; display: flex; align-items: center;">
                <i class="fas fa-check-circle" style="margin-left: 8px;"></i>
                الحقول الاختيارية
            </h4>
            <ul style="color: #14532d; margin: 0; padding-right: 20px;">
                <li>generic_name - الاسم العلمي</li>
                <li>manufacturer - الشركة المصنعة</li>
                <li>barcode - الباركود</li>
                <li>batch_number - رقم الدفعة</li>
                <li>expiry_date - تاريخ انتهاء الصلاحية</li>
                <li>manufacturing_date - تاريخ التصنيع</li>
                <li>storage_conditions - شروط التخزين</li>
                <li>description - الوصف</li>
                <li>notes - ملاحظات</li>
            </ul>
        </div>
    </div>

    <div style="background: #fffbeb; border: 1px solid #fed7aa; border-radius: 12px; padding: 20px; margin-top: 20px;">
        <h4 style="color: #d97706; margin: 0 0 10px 0; font-weight: 600; display: flex; align-items: center;">
            <i class="fas fa-lightbulb" style="margin-left: 8px;"></i>
            نصائح مهمة للمنتجات الدوائية
        </h4>
        <ul style="color: #92400e; margin: 0; padding-right: 20px;">
            <li>تأكد من أن أسماء الأعمدة تطابق النموذج تماماً</li>
            <li>استخدم الفئات المحددة مثل "المضادات الحيوية"، "مسكنات الألم"</li>
            <li>استخدم الوحدات المحددة مثل "قرص"، "كبسولة"، "شراب"</li>
            <li>تأكد من صحة تواريخ الصلاحية والتصنيع</li>
            <li>سيتم تخطي المنتجات المكررة تلقائياً</li>
            <li>الحد الأقصى لحجم الملف هو 10 ميجابايت</li>
            <li>تأكد من أن أسعار البيع أعلى من أسعار الشراء</li>
        </ul>
    </div>
</div>

<!-- Categories Reference -->
<div class="content-card" style="margin-bottom: 25px;">
    <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
        <i class="fas fa-tags" style="color: #8b5cf6; margin-left: 10px;"></i>
        الفئات المتاحة
    </h3>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 15px;">
        <div style="background: #f8fafc; border-radius: 8px; padding: 15px;">
            <h5 style="color: #374151; margin: 0 0 10px 0; font-weight: 600;">الفئات الرئيسية:</h5>
            <ul style="color: #6b7280; margin: 0; padding-right: 20px; font-size: 14px;">
                <li>أدوية القلب والأوعية الدموية</li>
                <li>المضادات الحيوية</li>
                <li>أدوية الجهاز التنفسي</li>
                <li>أدوية الجهاز الهضمي</li>
                <li>أدوية الجهاز العصبي</li>
            </ul>
        </div>
        
        <div style="background: #f8fafc; border-radius: 8px; padding: 15px;">
            <h5 style="color: #374151; margin: 0 0 10px 0; font-weight: 600;">فئات إضافية:</h5>
            <ul style="color: #6b7280; margin: 0; padding-right: 20px; font-size: 14px;">
                <li>أدوية السكري</li>
                <li>مسكنات الألم</li>
                <li>الفيتامينات والمكملات</li>
                <li>أدوية الأطفال</li>
                <li>أخرى</li>
            </ul>
        </div>
    </div>
</div>

<!-- Units Reference -->
<div class="content-card" style="margin-bottom: 25px;">
    <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
        <i class="fas fa-balance-scale" style="color: #059669; margin-left: 10px;"></i>
        الوحدات المتاحة
    </h3>
    
    <div style="display: flex; flex-wrap: wrap; gap: 10px;">
        <span style="background: #dcfce7; color: #166534; padding: 6px 12px; border-radius: 20px; font-size: 14px; font-weight: 600;">قرص</span>
        <span style="background: #dbeafe; color: #1e40af; padding: 6px 12px; border-radius: 20px; font-size: 14px; font-weight: 600;">كبسولة</span>
        <span style="background: #fef3c7; color: #d97706; padding: 6px 12px; border-radius: 20px; font-size: 14px; font-weight: 600;">شراب</span>
        <span style="background: #fce7f3; color: #be185d; padding: 6px 12px; border-radius: 20px; font-size: 14px; font-weight: 600;">حقنة</span>
        <span style="background: #f3e8ff; color: #7c3aed; padding: 6px 12px; border-radius: 20px; font-size: 14px; font-weight: 600;">مرهم</span>
        <span style="background: #ecfdf5; color: #059669; padding: 6px 12px; border-radius: 20px; font-size: 14px; font-weight: 600;">قطرة</span>
        <span style="background: #fef2f2; color: #dc2626; padding: 6px 12px; border-radius: 20px; font-size: 14px; font-weight: 600;">بخاخ</span>
        <span style="background: #f0f9ff; color: #0369a1; padding: 6px 12px; border-radius: 20px; font-size: 14px; font-weight: 600;">علبة</span>
        <span style="background: #fdf4ff; color: #a855f7; padding: 6px 12px; border-radius: 20px; font-size: 14px; font-weight: 600;">زجاجة</span>
    </div>
</div>

<!-- Quick Help -->
<div class="content-card" style="margin-bottom: 25px;">
    <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
        <i class="fas fa-question-circle" style="color: #3b82f6; margin-left: 10px;"></i>
        مساعدة سريعة - حل المشاكل الشائعة
    </h3>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
        <div style="background: #f0f9ff; border: 1px solid #bae6fd; border-radius: 12px; padding: 20px;">
            <h4 style="color: #0369a1; margin: 0 0 15px 0; font-weight: 600; display: flex; align-items: center;">
                <i class="fas fa-file-excel" style="margin-left: 8px;"></i>
                مشاكل الملف
            </h4>
            <ul style="color: #075985; margin: 0; padding-right: 20px; font-size: 14px; line-height: 1.6;">
                <li><strong>الملف لا يُرفع:</strong> تأكد من أن الملف أقل من 10 ميجابايت</li>
                <li><strong>خطأ في النوع:</strong> استخدم .xlsx أو .xls أو .csv فقط</li>
                <li><strong>الملف تالف:</strong> افتح الملف في Excel واحفظه مرة أخرى</li>
            </ul>
        </div>

        <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 12px; padding: 20px;">
            <h4 style="color: #166534; margin: 0 0 15px 0; font-weight: 600; display: flex; align-items: center;">
                <i class="fas fa-table" style="margin-left: 8px;"></i>
                مشاكل البيانات
            </h4>
            <ul style="color: #15803d; margin: 0; padding-right: 20px; font-size: 14px; line-height: 1.6;">
                <li><strong>أعمدة مفقودة:</strong> تأكد من وجود جميع الأعمدة المطلوبة</li>
                <li><strong>بيانات فارغة:</strong> املأ جميع الخلايا المطلوبة</li>
                <li><strong>أرقام خاطئة:</strong> تأكد من أن الأسعار والكميات أرقام</li>
            </ul>
        </div>

        <div style="background: #fefce8; border: 1px solid #fde047; border-radius: 12px; padding: 20px;">
            <h4 style="color: #a16207; margin: 0 0 15px 0; font-weight: 600; display: flex; align-items: center;">
                <i class="fas fa-tools" style="margin-left: 8px;"></i>
                حلول سريعة
            </h4>
            <ul style="color: #92400e; margin: 0; padding-right: 20px; font-size: 14px; line-height: 1.6;">
                <li><strong>حمل النموذج:</strong> استخدم النموذج المتوفر دائماً</li>
                <li><strong>ابدأ صغيراً:</strong> جرب 10-20 منتج أولاً</li>
                <li><strong>تحقق من التنسيق:</strong> تأكد من تطابق أسماء الأعمدة</li>
            </ul>
        </div>
    </div>
</div>

<!-- Upload Form -->
<div class="content-card">
    <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
        <i class="fas fa-upload" style="color: #9f7aea; margin-left: 10px;"></i>
        رفع ملف Excel
    </h3>

    <form method="POST" action="{{ route('tenant.sales.products.process-import') }}" enctype="multipart/form-data">
        @csrf
        
        <div style="border: 2px dashed #d1d5db; border-radius: 12px; padding: 40px; text-align: center; margin-bottom: 20px; transition: all 0.3s ease;" 
             id="dropZone"
             ondragover="event.preventDefault(); this.style.borderColor='#9f7aea'; this.style.background='#f3e8ff';"
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
                    style="background: linear-gradient(135deg, #9f7aea 0%, #805ad5 100%); color: white; padding: 12px 24px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                <i class="fas fa-folder-open" style="margin-left: 8px;"></i>
                اختيار ملف
            </button>
            
            <div id="fileName" style="margin-top: 15px; color: #059669; font-weight: 600; display: none;">
                <i class="fas fa-file-excel" style="margin-left: 8px;"></i>
                <span></span>
            </div>
        </div>

        @error('excel_file')
            <div style="background: #fef2f2; border: 1px solid #fecaca; border-radius: 12px; padding: 20px; margin-bottom: 20px;">
                <h4 style="color: #dc2626; margin: 0 0 15px 0; font-weight: 600; display: flex; align-items: center;">
                    <i class="fas fa-exclamation-triangle" style="margin-left: 8px;"></i>
                    خطأ في رفع الملف
                </h4>
                <p style="color: #7f1d1d; margin: 0 0 15px 0; font-weight: 600;">{{ $message }}</p>

                <div style="background: white; border-radius: 8px; padding: 15px;">
                    <h5 style="color: #dc2626; margin: 0 0 10px 0; font-size: 16px;">الأسباب المحتملة والحلول:</h5>
                    <ul style="color: #7f1d1d; margin: 0; padding-right: 20px; line-height: 1.6;">
                        <li><strong>نوع الملف غير مدعوم:</strong> تأكد من أن الملف بصيغة .xlsx أو .xls أو .csv</li>
                        <li><strong>حجم الملف كبير:</strong> الحد الأقصى 10 ميجابايت - قم بتقليل عدد الصفوف أو ضغط الملف</li>
                        <li><strong>الملف تالف:</strong> جرب فتح الملف في Excel والحفظ مرة أخرى</li>
                        <li><strong>مشكلة في الشبكة:</strong> تأكد من استقرار الاتصال وأعد المحاولة</li>
                        <li><strong>الملف فارغ:</strong> تأكد من وجود بيانات في الملف</li>
                    </ul>
                </div>
            </div>
        @enderror

        @if(session('error'))
            <div style="background: #fef2f2; border: 1px solid #fecaca; border-radius: 12px; padding: 20px; margin-bottom: 20px;">
                <h4 style="color: #dc2626; margin: 0 0 15px 0; font-weight: 600; display: flex; align-items: center;">
                    <i class="fas fa-times-circle" style="margin-left: 8px;"></i>
                    فشل في عملية الاستيراد
                </h4>
                <p style="color: #7f1d1d; margin: 0 0 15px 0; font-weight: 600;">{{ session('error') }}</p>

                <div style="background: white; border-radius: 8px; padding: 15px;">
                    <h5 style="color: #dc2626; margin: 0 0 10px 0; font-size: 16px;">خطوات حل المشكلة:</h5>
                    <ol style="color: #7f1d1d; margin: 0; padding-right: 20px; line-height: 1.6;">
                        <li><strong>تحقق من تنسيق الملف:</strong> تأكد من أن الصف الأول يحتوي على أسماء الأعمدة الصحيحة</li>
                        <li><strong>تحقق من البيانات:</strong> تأكد من عدم وجود خلايا فارغة في الأعمدة المطلوبة</li>
                        <li><strong>تحقق من الأرقام:</strong> تأكد من أن الأسعار والكميات أرقام صحيحة</li>
                        <li><strong>تحقق من التواريخ:</strong> استخدم تنسيق التاريخ YYYY-MM-DD</li>
                        <li><strong>حمل النموذج:</strong> استخدم النموذج المتوفر لضمان التنسيق الصحيح</li>
                        <li><strong>جرب ملف أصغر:</strong> ابدأ بعدد قليل من المنتجات للاختبار</li>
                    </ol>
                </div>

                <div style="background: #fffbeb; border: 1px solid #fed7aa; border-radius: 8px; padding: 15px; margin-top: 15px;">
                    <h6 style="color: #d97706; margin: 0 0 10px 0; font-size: 14px; font-weight: 600;">
                        <i class="fas fa-lightbulb" style="margin-left: 5px;"></i>
                        نصيحة سريعة:
                    </h6>
                    <p style="color: #92400e; margin: 0; font-size: 14px;">
                        حمل النموذج أولاً، املأه ببياناتك، ثم ارفعه. هذا يضمن التنسيق الصحيح ويقلل من الأخطاء.
                    </p>
                </div>
            </div>
        @endif

        <div style="text-align: center;">
            <button type="submit" class="btn-purple" style="padding: 15px 30px; font-size: 16px;" id="submitBtn" disabled>
                <i class="fas fa-upload" style="margin-left: 8px;"></i>
                استيراد المنتجات
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
