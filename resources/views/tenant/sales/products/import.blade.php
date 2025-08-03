@extends('layouts.modern')

@section('page-title', 'استيراد المنتجات من Excel')
@section('page-description', 'استيراد قائمة المنتجات الدوائية من ملف Excel')

@push('styles')
<style>
@keyframes slideInDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
@endpush

@section('content')

@php
    // تسجيل للتحقق من وصول البيانات
    if (session('success')) {
        \Log::info('Import page loaded with success message', [
            'success' => session('success'),
            'import_stats' => session('import_stats'),
            'has_import_stats' => session()->has('import_stats')
        ]);
    }
@endphp

<!-- Success/Error Messages -->
@if(session('success'))
<div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 12px; padding: 20px; margin-bottom: 25px; animation: slideInDown 0.5s ease-out;">
    <div style="display: flex; align-items: center; gap: 15px;">
        <div style="background: #22c55e; border-radius: 50%; padding: 10px; flex-shrink: 0;">
            <i class="fas fa-check" style="color: white; font-size: 18px;"></i>
        </div>
        <div style="flex: 1;">
            <h4 style="color: #166534; margin: 0 0 8px 0; font-weight: 600; font-size: 18px;">
                تم الاستيراد بنجاح!
            </h4>
            <p style="color: #15803d; margin: 0; font-size: 16px; line-height: 1.5;">
                {{ session('success') }}
            </p>

            @if(session('import_stats'))
                <div style="margin-top: 15px; padding: 15px; background: white; border-radius: 8px; border: 1px solid #d1fae5;">
                    <h5 style="color: #166534; margin: 0 0 10px 0; font-size: 16px;">📊 إحصائيات الاستيراد:</h5>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 10px; font-size: 14px;">
                        <div style="text-align: center;">
                            <div style="color: #22c55e; font-weight: 600; font-size: 18px;">{{ session('import_stats')['imported'] }}</div>
                            <div style="color: #15803d;">منتج مستورد</div>
                        </div>
                        <div style="text-align: center;">
                            <div style="color: #f59e0b; font-weight: 600; font-size: 18px;">{{ session('import_stats')['skipped'] }}</div>
                            <div style="color: #92400e;">منتج متخطى</div>
                        </div>
                        <div style="text-align: center;">
                            <div style="color: #3b82f6; font-weight: 600; font-size: 18px;">{{ session('import_stats')['total_processed'] }}</div>
                            <div style="color: #1e40af;">إجمالي معالج</div>
                        </div>
                        <div style="text-align: center;">
                            <div style="color: #8b5cf6; font-weight: 600; font-size: 18px;">{{ session('import_stats')['execution_time'] }}</div>
                            <div style="color: #7c3aed;">وقت التنفيذ</div>
                        </div>
                    </div>
                </div>
            @endif

            <div style="margin-top: 15px; display: flex; gap: 10px; flex-wrap: wrap;">
                <a href="{{ route('tenant.sales.products.index') }}"
                   style="background: #22c55e; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 8px;">
                    <i class="fas fa-eye"></i>
                    عرض المنتجات المستوردة
                </a>
                <a href="{{ route('tenant.sales.products.import') }}"
                   style="background: #3b82f6; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 8px;">
                    <i class="fas fa-plus"></i>
                    استيراد ملف آخر
                </a>
            </div>
        </div>
        <button onclick="this.parentElement.parentElement.style.display='none'"
                style="background: none; border: none; color: #166534; font-size: 20px; cursor: pointer; padding: 5px;">
            <i class="fas fa-times"></i>
        </button>
    </div>
</div>
@endif

@if(session('error'))
<div style="background: #fef2f2; border: 1px solid #fecaca; border-radius: 12px; padding: 20px; margin-bottom: 25px; animation: slideInDown 0.5s ease-out;">
    <div style="display: flex; align-items: center; gap: 15px;">
        <div style="background: #ef4444; border-radius: 50%; padding: 10px; flex-shrink: 0;">
            <i class="fas fa-exclamation-triangle" style="color: white; font-size: 18px;"></i>
        </div>
        <div style="flex: 1;">
            <h4 style="color: #dc2626; margin: 0 0 8px 0; font-weight: 600; font-size: 18px;">
                فشل الاستيراد!
            </h4>
            <p style="color: #7f1d1d; margin: 0; font-size: 16px; line-height: 1.5;">
                {{ session('error') }}
            </p>
        </div>
        <button onclick="this.parentElement.parentElement.style.display='none'"
                style="background: none; border: none; color: #dc2626; font-size: 20px; cursor: pointer; padding: 5px;">
            <i class="fas fa-times"></i>
        </button>
    </div>
</div>
@endif

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

    <form method="POST" action="{{ route('tenant.sales.products.process-import') }}" enctype="multipart/form-data" id="importForm">
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

        <!-- تحذير للملفات الكبيرة -->
        <div id="largeFileWarning" style="display: none; background: #fef3c7; border: 1px solid #fbbf24; border-radius: 12px; padding: 20px; margin-bottom: 20px;">
            <h4 style="color: #92400e; margin: 0 0 15px 0; font-weight: 600; display: flex; align-items: center;">
                <i class="fas fa-exclamation-triangle" style="margin-left: 8px;"></i>
                تحذير: ملف كبير
            </h4>
            <div style="color: #92400e; line-height: 1.6;">
                <p style="margin: 0 0 10px 0;">الملف المختار كبير الحجم وقد يستغرق وقتاً طويلاً للمعالجة:</p>
                <ul style="margin: 0 0 15px 0; padding-right: 20px;">
                    <li>الوقت المتوقع: <span id="estimatedProcessingTime">--</span></li>
                    <li>يرجى عدم إغلاق الصفحة أو المتصفح أثناء المعالجة</li>
                    <li>تأكد من استقرار الاتصال بالإنترنت</li>
                    <li>يمكنك تقسيم الملف إلى ملفات أصغر لتسريع العملية</li>
                </ul>
                <div style="background: white; border-radius: 8px; padding: 10px; font-size: 14px;">
                    <strong>نصيحة:</strong> للملفات الكبيرة جداً (أكثر من 1000 منتج)، يُنصح بتقسيمها إلى ملفات أصغر (200-500 منتج لكل ملف).
                </div>
            </div>
        </div>

        <div style="text-align: center;">
            <button type="submit" class="btn-purple" style="padding: 15px 30px; font-size: 16px;" id="submitBtn" disabled onclick="handleSubmitClick(event)">
                <i class="fas fa-upload" style="margin-left: 8px;"></i>
                <span id="submitText">استيراد المنتجات</span>
            </button>

            <div id="uploadProgress" style="display: none; margin-top: 15px;">
                <div style="background: #f3f4f6; border-radius: 10px; padding: 20px;">
                    <div style="display: flex; align-items: center; gap: 10px; color: #6b7280; margin-bottom: 15px;">
                        <i class="fas fa-spinner fa-spin"></i>
                        <span id="progressText">جاري رفع الملف...</span>
                    </div>

                    <div style="background: #e5e7eb; border-radius: 5px; height: 10px; margin-bottom: 15px; overflow: hidden;">
                        <div id="progressBar" style="background: linear-gradient(90deg, #9f7aea, #805ad5); height: 100%; width: 0%; transition: width 0.5s ease;"></div>
                    </div>

                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 15px; font-size: 14px;">
                        <div style="text-align: center;">
                            <div style="color: #9f7aea; font-weight: 600;" id="timeElapsed">00:00</div>
                            <div style="color: #6b7280;">الوقت المنقضي</div>
                        </div>
                        <div style="text-align: center;">
                            <div style="color: #9f7aea; font-weight: 600;" id="estimatedTime">--:--</div>
                            <div style="color: #6b7280;">الوقت المتوقع</div>
                        </div>
                        <div style="text-align: center;">
                            <div style="color: #9f7aea; font-weight: 600;" id="fileSize">--</div>
                            <div style="color: #6b7280;">حجم الملف</div>
                        </div>
                    </div>

                    <div style="margin-top: 15px; padding: 10px; background: #fef3c7; border-radius: 8px; border: 1px solid #fbbf24;">
                        <div style="display: flex; align-items: center; gap: 8px; color: #92400e; font-size: 14px;">
                            <i class="fas fa-info-circle"></i>
                            <span>الملفات الكبيرة قد تستغرق عدة دقائق للمعالجة. يرجى عدم إغلاق الصفحة.</span>
                        </div>
                    </div>
                </div>
            </div>
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

    if (files.length === 0) {
        showFileError('لم يتم العثور على ملفات. تأكد من سحب ملف صحيح');
        return;
    }

    if (files.length > 1) {
        showFileError('يمكن رفع ملف واحد فقط في كل مرة. اختر ملف واحد');
        return;
    }

    const file = files[0];

    // التحقق الأولي من نوع الملف
    const fileName = file.name.toLowerCase();
    if (!fileName.endsWith('.xlsx') && !fileName.endsWith('.xls') && !fileName.endsWith('.csv')) {
        showFileError('نوع الملف غير مدعوم. اسحب ملف Excel (.xlsx, .xls) أو CSV (.csv) فقط');
        return;
    }

    // تعيين الملف وعرض التفاصيل
    try {
        const fileInput = document.getElementById('excelFile');
        fileInput.files = files;
        displayFileName(fileInput);
    } catch (error) {
        showFileError('حدث خطأ في معالجة الملف. جرب رفع الملف بالطريقة التقليدية');
    }
}

function displayFileName(input) {
    const fileName = document.getElementById('fileName');
    const submitBtn = document.getElementById('submitBtn');

    if (input.files && input.files[0]) {
        const file = input.files[0];

        // التحقق من نوع الملف
        const allowedTypes = ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                             'application/vnd.ms-excel',
                             'text/csv'];
        const allowedExtensions = ['.xlsx', '.xls', '.csv'];

        const fileExtension = file.name.toLowerCase().substring(file.name.lastIndexOf('.'));

        if (!allowedTypes.includes(file.type) && !allowedExtensions.includes(fileExtension)) {
            showFileError('نوع الملف غير مدعوم. يجب أن يكون الملف بصيغة Excel (.xlsx, .xls) أو CSV (.csv)');
            input.value = '';
            return;
        }

        // التحقق من حجم الملف (10MB = 10 * 1024 * 1024 bytes)
        const maxSize = 10 * 1024 * 1024;
        if (file.size > maxSize) {
            showFileError('حجم الملف كبير جداً. الحد الأقصى المسموح 10 ميجابايت. حجم الملف الحالي: ' + (file.size / 1024 / 1024).toFixed(2) + ' ميجابايت');
            input.value = '';
            return;
        }

        // التحقق من أن الملف ليس فارغ
        if (file.size === 0) {
            showFileError('الملف فارغ. تأكد من أن الملف يحتوي على بيانات');
            input.value = '';
            return;
        }

        // إخفاء رسائل الخطأ السابقة
        hideFileError();

        fileName.style.display = 'block';
        fileName.querySelector('span').textContent = file.name + ' (' + (file.size / 1024).toFixed(1) + ' كيلوبايت)';
        submitBtn.disabled = false;
        submitBtn.style.opacity = '1';

        // إظهار تحذير للملفات الكبيرة
        checkLargeFile(file);
    } else {
        fileName.style.display = 'none';
        submitBtn.disabled = true;
        submitBtn.style.opacity = '0.5';
    }
}

function showFileError(message) {
    let errorDiv = document.getElementById('fileError');
    if (!errorDiv) {
        errorDiv = document.createElement('div');
        errorDiv.id = 'fileError';
        errorDiv.style.cssText = `
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 8px;
            padding: 15px;
            margin-top: 15px;
            color: #dc2626;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        `;
        document.getElementById('dropZone').appendChild(errorDiv);
    }

    errorDiv.innerHTML = `
        <i class="fas fa-exclamation-triangle" style="color: #dc2626;"></i>
        <span>${message}</span>
    `;
    errorDiv.style.display = 'flex';

    const submitBtn = document.getElementById('submitBtn');
    submitBtn.disabled = true;
    submitBtn.style.opacity = '0.5';
}

function hideFileError() {
    const errorDiv = document.getElementById('fileError');
    if (errorDiv) {
        errorDiv.style.display = 'none';
    }
}

function checkLargeFile(file) {
    const largeFileWarning = document.getElementById('largeFileWarning');
    const estimatedProcessingTime = document.getElementById('estimatedProcessingTime');

    // اعتبار الملف كبير إذا كان أكبر من 1 ميجابايت
    const isLargeFile = file.size > 1024 * 1024;

    if (isLargeFile) {
        // تقدير الوقت بناءً على حجم الملف
        const fileSizeMB = file.size / (1024 * 1024);
        let estimatedMinutes;

        if (fileSizeMB < 2) {
            estimatedMinutes = "1-2 دقيقة";
        } else if (fileSizeMB < 5) {
            estimatedMinutes = "2-5 دقائق";
        } else if (fileSizeMB < 10) {
            estimatedMinutes = "5-10 دقائق";
        } else {
            estimatedMinutes = "أكثر من 10 دقائق";
        }

        estimatedProcessingTime.textContent = estimatedMinutes;
        largeFileWarning.style.display = 'block';

        // تمرير سلس للتحذير
        setTimeout(() => {
            largeFileWarning.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }, 100);
    } else {
        largeFileWarning.style.display = 'none';
    }
}

function handleSubmitClick(event) {
    // منع الإرسال الافتراضي
    event.preventDefault();

    // تشغيل التحقق
    if (validateBeforeSubmit()) {
        // تشغيل form submit event يدوياً
        const form = document.getElementById('importForm');
        const submitEvent = new Event('submit', { bubbles: true, cancelable: true });
        form.dispatchEvent(submitEvent);
    }
}

function validateBeforeSubmit() {
    const fileInput = document.getElementById('excelFile');
    const submitBtn = document.getElementById('submitBtn');
    const submitText = document.getElementById('submitText');
    const uploadProgress = document.getElementById('uploadProgress');

    if (!fileInput.files || fileInput.files.length === 0) {
        showFileError('يرجى اختيار ملف Excel للاستيراد');
        return false;
    }

    const file = fileInput.files[0];

    // التحقق النهائي من الملف
    if (file.size === 0) {
        showFileError('الملف فارغ. اختر ملف يحتوي على بيانات');
        return false;
    }

    if (file.size > 10 * 1024 * 1024) {
        showFileError('حجم الملف كبير جداً. الحد الأقصى 10 ميجابايت');
        return false;
    }

    // إظهار شريط التقدم المحسن
    submitBtn.disabled = true;
    submitText.innerHTML = '<i class="fas fa-spinner fa-spin" style="margin-left: 8px;"></i>جاري الرفع...';
    uploadProgress.style.display = 'block';

    // عرض حجم الملف
    const fileSizeElement = document.getElementById('fileSize');
    const fileSizeMB = (file.size / 1024 / 1024).toFixed(2);
    fileSizeElement.textContent = fileSizeMB + ' ميجابايت';

    // بدء عداد الوقت
    const startTime = Date.now();
    let progress = 0;
    const progressBar = document.getElementById('progressBar');
    const progressText = document.getElementById('progressText');
    const timeElapsed = document.getElementById('timeElapsed');
    const estimatedTime = document.getElementById('estimatedTime');

    // تقدير الوقت بناءً على حجم الملف
    const estimatedSeconds = Math.max(30, Math.min(300, file.size / (1024 * 1024) * 20)); // 20 ثانية لكل ميجابايت

    const progressStages = [
        { progress: 10, text: 'جاري رفع الملف...', duration: 2000 },
        { progress: 30, text: 'جاري قراءة البيانات...', duration: 3000 },
        { progress: 60, text: 'جاري معالجة المنتجات...', duration: estimatedSeconds * 600 },
        { progress: 85, text: 'جاري حفظ البيانات...', duration: 2000 },
        { progress: 95, text: 'جاري إنهاء العملية...', duration: 1000 }
    ];

    let currentStage = 0;
    let stageStartTime = Date.now();

    const updateProgress = () => {
        const elapsed = Date.now() - startTime;
        const elapsedSeconds = Math.floor(elapsed / 1000);
        const minutes = Math.floor(elapsedSeconds / 60);
        const seconds = elapsedSeconds % 60;
        timeElapsed.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;

        // تحديث التقدم بناءً على المرحلة الحالية
        if (currentStage < progressStages.length) {
            const stage = progressStages[currentStage];
            const stageElapsed = Date.now() - stageStartTime;
            const stageProgress = Math.min(1, stageElapsed / stage.duration);

            if (currentStage === 0) {
                progress = stage.progress * stageProgress;
            } else {
                const prevProgress = currentStage > 0 ? progressStages[currentStage - 1].progress : 0;
                progress = prevProgress + (stage.progress - prevProgress) * stageProgress;
            }

            progressBar.style.width = progress + '%';
            progressText.textContent = stage.text;

            // الانتقال للمرحلة التالية
            if (stageProgress >= 1 && currentStage < progressStages.length - 1) {
                currentStage++;
                stageStartTime = Date.now();
            }
        }

        // تحديث الوقت المتوقع
        if (progress > 5) {
            const estimatedTotal = (elapsed / progress) * 100;
            const remaining = Math.max(0, estimatedTotal - elapsed);
            const remainingSeconds = Math.floor(remaining / 1000);
            const estMinutes = Math.floor(remainingSeconds / 60);
            const estSeconds = remainingSeconds % 60;
            estimatedTime.textContent = `${estMinutes.toString().padStart(2, '0')}:${estSeconds.toString().padStart(2, '0')}`;
        }
    };

    const interval = setInterval(updateProgress, 500);

    // تنظيف عند اكتمال العملية
    setTimeout(() => {
        clearInterval(interval);
        progressBar.style.width = '100%';
        progressText.textContent = 'تم الانتهاء!';
    }, estimatedSeconds * 1000);

    return true;
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    const submitBtn = document.getElementById('submitBtn');
    submitBtn.style.opacity = '0.5';

    // إضافة معالج للنموذج لمنع الإرسال المتكرر
    const form = document.getElementById('importForm');
    let isSubmitting = false;

    form.addEventListener('submit', function(e) {
        const submitBtn = document.getElementById('submitBtn');

        // منع الإرسال المتكرر
        if (isSubmitting) {
            e.preventDefault();
            return false;
        }

        isSubmitting = true;

        // إضافة timeout للكشف عن المشاكل
        const timeoutId = setTimeout(function() {
            console.warn('Form submission taking too long...');

            // عرض رسالة للمستخدم
            const progressDiv = document.createElement('div');
            progressDiv.style.cssText = `
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                background: white;
                padding: 20px;
                border-radius: 10px;
                box-shadow: 0 4px 20px rgba(0,0,0,0.3);
                z-index: 9999;
                text-align: center;
                border: 2px solid #f59e0b;
            `;
            progressDiv.innerHTML = `
                <div style="color: #f59e0b; margin-bottom: 10px;">
                    <i class="fas fa-clock fa-2x"></i>
                </div>
                <h4 style="margin: 10px 0; color: #1f2937;">جاري المعالجة...</h4>
                <p style="margin: 5px 0; color: #6b7280;">قد تستغرق العملية عدة دقائق للملفات الكبيرة</p>
                <div style="margin-top: 15px;">
                    <button onclick="this.parentElement.parentElement.remove()"
                            style="background: #f59e0b; color: white; border: none; padding: 8px 16px; border-radius: 5px; cursor: pointer;">
                        إخفاء
                    </button>
                </div>
            `;
            document.body.appendChild(progressDiv);
        }, 30000); // 30 ثانية

        // إزالة timeout عند انتهاء العملية
        window.addEventListener('beforeunload', function() {
            clearTimeout(timeoutId);
        });
    });

    // إضافة timeout للتأكد من إعادة التوجيه
    form.addEventListener('submit', function() {
        // إذا لم يتم إعادة التوجيه خلال 15 دقيقة، أعد التوجيه يدوياً
        setTimeout(function() {
            if (document.getElementById('submitBtn').disabled) {
                console.log('Timeout reached, redirecting...');
                window.location.href = '{{ route("tenant.sales.products.import") }}';
            }
        }, 900000); // 15 دقيقة
    });
});
</script>
@endpush
@endsection
