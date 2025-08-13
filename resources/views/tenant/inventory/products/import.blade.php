@extends('layouts.modern')

@section('page-title', 'استيراد المنتجات من Excel')
@section('page-description', 'رفع ملف Excel لاستيراد المنتجات بشكل مجمع')

@section('content')
<!-- Page Header -->
<div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius: 20px; padding: 30px; margin-bottom: 30px; color: white; position: relative; overflow: hidden;">
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
                            استيراد المنتجات 📊
                        </h1>
                        <p style="font-size: 18px; margin: 5px 0 0 0; opacity: 0.9;">
                            رفع ملف Excel لاستيراد المنتجات بشكل مجمع وسريع
                        </p>
                    </div>
                </div>
            </div>
            
            <div style="display: flex; gap: 15px;">
                <a href="{{ route('tenant.inventory.inventory-products.template') }}"
                   style="background: rgba(255,255,255,0.2); color: white; padding: 15px 25px; border-radius: 15px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3);">
                    <i class="fas fa-download"></i>
                    تحميل النموذج
                </a>
                <a href="{{ route('tenant.inventory.inventory-products.export') }}"
                   style="background: rgba(245, 158, 11, 0.2); color: white; padding: 15px 25px; border-radius: 15px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3);">
                    <i class="fas fa-file-excel"></i>
                    تصدير الحالي
                </a>
                <a href="{{ route('tenant.inventory.inventory-products.create') }}"
                   style="background: rgba(59, 130, 246, 0.2); color: white; padding: 15px 25px; border-radius: 15px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3);">
                    <i class="fas fa-plus"></i>
                    إضافة يدوي
                </a>
                <a href="{{ route('tenant.inventory.inventory-products.index') }}"
                   style="background: rgba(255,255,255,0.2); color: white; padding: 15px 25px; border-radius: 15px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3);">
                    <i class="fas fa-arrow-right"></i>
                    العودة للقائمة
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Quick Stats -->
<div class="content-card" style="margin-bottom: 25px;">
    <h3 style="color: #2d3748; margin-bottom: 20px; font-size: 18px; font-weight: 700; display: flex; align-items: center; gap: 10px;">
        <i class="fas fa-chart-bar" style="color: #10b981;"></i>
        إحصائيات سريعة
    </h3>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
        @php
            $stats = [
                'total' => \App\Models\Product::where('tenant_id', auth()->user()->tenant_id)->count(),
                'categories' => \App\Models\ProductCategory::where('tenant_id', auth()->user()->tenant_id)->count(),
                'active' => \App\Models\Product::where('tenant_id', auth()->user()->tenant_id)->where('is_active', true)->count(),
                'low_stock' => \App\Models\Product::where('tenant_id', auth()->user()->tenant_id)->whereRaw('COALESCE(current_stock, stock_quantity, 0) <= COALESCE(minimum_stock, min_stock_level, 0)')->count(),
            ];
        @endphp

        <div style="background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%); color: white; padding: 20px; border-radius: 12px; text-align: center;">
            <div style="font-size: 32px; font-weight: 800; margin-bottom: 8px;">{{ number_format($stats['total']) }}</div>
            <div style="font-size: 14px; opacity: 0.9;">إجمالي المنتجات</div>
        </div>

        <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 20px; border-radius: 12px; text-align: center;">
            <div style="font-size: 32px; font-weight: 800; margin-bottom: 8px;">{{ number_format($stats['categories']) }}</div>
            <div style="font-size: 14px; opacity: 0.9;">الفئات المتوفرة</div>
        </div>

        <div style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); color: white; padding: 20px; border-radius: 12px; text-align: center;">
            <div style="font-size: 32px; font-weight: 800; margin-bottom: 8px;">{{ number_format($stats['active']) }}</div>
            <div style="font-size: 14px; opacity: 0.9;">المنتجات النشطة</div>
        </div>

        <div style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; padding: 20px; border-radius: 12px; text-align: center;">
            <div style="font-size: 32px; font-weight: 800; margin-bottom: 8px;">{{ number_format($stats['low_stock']) }}</div>
            <div style="font-size: 14px; opacity: 0.9;">مخزون منخفض</div>
        </div>
    </div>
</div>

<!-- Import Form -->
<div class="content-card">
    @if(session('success'))
        <div style="background: #d1fae5; border: 1px solid #a7f3d0; color: #065f46; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            <i class="fas fa-check-circle" style="margin-left: 8px;"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('warning'))
        <div style="background: #fef3c7; border: 1px solid #fde68a; color: #92400e; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            <i class="fas fa-exclamation-triangle" style="margin-left: 8px;"></i>
            {{ session('warning') }}
        </div>
    @endif

    @if(session('error'))
        <div style="background: #fee2e2; border: 1px solid #fecaca; color: #991b1b; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            <i class="fas fa-times-circle" style="margin-left: 8px;"></i>
            {{ session('error') }}
        </div>
    @endif

    @if(session('import_errors'))
        <div style="background: #fee2e2; border: 1px solid #fecaca; color: #991b1b; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            <h4 style="margin: 0 0 10px 0;">أخطاء الاستيراد:</h4>
            <ul style="margin: 0; padding-right: 20px;">
                @foreach(session('import_errors') as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Instructions -->
    <div style="background: #f0f9ff; border: 1px solid #bae6fd; border-radius: 12px; padding: 25px; margin-bottom: 30px;">
        <h3 style="color: #0c4a6e; margin: 0 0 15px 0; font-size: 18px; font-weight: 700; display: flex; align-items: center; gap: 10px;">
            <i class="fas fa-info-circle" style="color: #0284c7;"></i>
            تعليمات الاستيراد
        </h3>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            <div>
                <h4 style="color: #0c4a6e; margin: 0 0 10px 0; font-size: 16px;">📋 متطلبات الملف:</h4>
                <ul style="color: #0369a1; margin: 0; padding-right: 20px; line-height: 1.6;">
                    <li>ملف Excel (.xlsx, .xls) أو CSV</li>
                    <li>الحد الأقصى لحجم الملف: 10 ميجابايت</li>
                    <li>يجب أن يحتوي على عناوين الأعمدة في السطر الأول</li>
                    <li>استخدم النموذج المتوفر لضمان التوافق</li>
                </ul>
            </div>
            
            <div>
                <h4 style="color: #0c4a6e; margin: 0 0 10px 0; font-size: 16px;">✅ الحقول المطلوبة:</h4>
                <ul style="color: #0369a1; margin: 0; padding-right: 20px; line-height: 1.6;">
                    <li><strong>اسم المنتج:</strong> مطلوب</li>
                    <li><strong>سعر البيع:</strong> مطلوب ورقم</li>
                    <li><strong>كود المنتج:</strong> اختياري (سيتم إنشاؤه تلقائياً)</li>
                    <li><strong>الفئة:</strong> اختياري (سيتم إنشاؤها إذا لم تكن موجودة)</li>
                </ul>
            </div>
        </div>

        <div style="background: #e0f2fe; border-radius: 8px; padding: 15px;">
            <p style="margin: 0; color: #01579b; font-weight: 600;">
                <i class="fas fa-lightbulb" style="color: #ffa000; margin-left: 8px;"></i>
                نصيحة: حمل النموذج أولاً واملأه بالبيانات، ثم ارفعه هنا للاستيراد السريع.
            </p>
        </div>
    </div>

    <!-- Upload Form -->
    <form method="POST" action="{{ route('tenant.inventory.inventory-products.process-import') }}" enctype="multipart/form-data">
        @csrf
        
        <div style="border: 2px dashed #d1d5db; border-radius: 12px; padding: 40px; text-align: center; background: #f9fafb; margin-bottom: 30px;" id="dropZone">
            <div style="margin-bottom: 20px;">
                <i class="fas fa-cloud-upload-alt" style="font-size: 48px; color: #6b7280; margin-bottom: 15px;"></i>
                <h3 style="color: #374151; margin: 0 0 10px 0; font-size: 20px; font-weight: 600;">
                    اسحب وأفلت ملف Excel هنا
                </h3>
                <p style="color: #6b7280; margin: 0; font-size: 16px;">
                    أو انقر لاختيار الملف من جهازك
                </p>
            </div>
            
            <input type="file" 
                   name="excel_file" 
                   id="excel_file"
                   accept=".xlsx,.xls,.csv"
                   required
                   style="display: none;">
            
            <button type="button" 
                    onclick="document.getElementById('excel_file').click()"
                    style="background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%); color: white; padding: 12px 30px; border: none; border-radius: 8px; font-size: 16px; font-weight: 600; cursor: pointer;">
                <i class="fas fa-folder-open" style="margin-left: 8px;"></i>
                اختيار الملف
            </button>
            
            <div id="fileInfo" style="margin-top: 15px; display: none;">
                <p style="color: #059669; font-weight: 600; margin: 0;">
                    <i class="fas fa-file-excel" style="margin-left: 8px;"></i>
                    <span id="fileName"></span>
                </p>
            </div>
        </div>

        @error('excel_file')
            <div style="background: #fee2e2; border: 1px solid #fecaca; color: #991b1b; padding: 10px; border-radius: 6px; margin-bottom: 15px;">
                {{ $message }}
            </div>
        @enderror

        <!-- Submit Button -->
        <div style="text-align: center;">
            <button type="submit" 
                    id="submitBtn"
                    disabled
                    style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 15px 40px; border: none; border-radius: 12px; font-size: 18px; font-weight: 700; cursor: pointer; opacity: 0.5; transition: all 0.3s ease;">
                <i class="fas fa-upload" style="margin-left: 10px;"></i>
                بدء الاستيراد
            </button>
        </div>
    </form>

    <!-- Available Categories -->
    @if($categories->count() > 0)
    <div style="margin-top: 40px; padding-top: 30px; border-top: 1px solid #e5e7eb;">
        <h3 style="color: #374151; margin: 0 0 20px 0; font-size: 18px; font-weight: 700; display: flex; align-items: center; gap: 10px;">
            <i class="fas fa-tags" style="color: #8b5cf6;"></i>
            الفئات المتوفرة
        </h3>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 15px;">
            @foreach($categories as $category)
                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 15px; text-align: center;">
                    <i class="fas fa-tag" style="color: #8b5cf6; margin-bottom: 8px;"></i>
                    <p style="margin: 0; font-weight: 600; color: #374151;">{{ $category->name }}</p>
                </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('excel_file');
    const dropZone = document.getElementById('dropZone');
    const fileInfo = document.getElementById('fileInfo');
    const fileName = document.getElementById('fileName');
    const submitBtn = document.getElementById('submitBtn');

    // File input change event
    fileInput.addEventListener('change', function(e) {
        handleFileSelect(e.target.files[0]);
    });

    // Drag and drop events
    dropZone.addEventListener('dragover', function(e) {
        e.preventDefault();
        dropZone.style.borderColor = '#3b82f6';
        dropZone.style.backgroundColor = '#eff6ff';
    });

    dropZone.addEventListener('dragleave', function(e) {
        e.preventDefault();
        dropZone.style.borderColor = '#d1d5db';
        dropZone.style.backgroundColor = '#f9fafb';
    });

    dropZone.addEventListener('drop', function(e) {
        e.preventDefault();
        dropZone.style.borderColor = '#d1d5db';
        dropZone.style.backgroundColor = '#f9fafb';
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            fileInput.files = files;
            handleFileSelect(files[0]);
        }
    });

    function handleFileSelect(file) {
        if (file) {
            fileName.textContent = file.name;
            fileInfo.style.display = 'block';
            submitBtn.disabled = false;
            submitBtn.style.opacity = '1';
            submitBtn.style.cursor = 'pointer';
        }
    }
});
</script>
@endsection
