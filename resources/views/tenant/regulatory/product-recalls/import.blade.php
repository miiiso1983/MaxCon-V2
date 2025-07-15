@extends('layouts.modern')

@section('title', 'استيراد سحب المنتجات من Excel')

@section('content')
<div style="padding: 30px; background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%); min-height: 100vh;">
    <!-- Header -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; margin-bottom: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <div style="display: flex; align-items: center; justify-content: between; margin-bottom: 20px;">
            <div style="display: flex; align-items: center;">
                <div style="background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%); color: white; border-radius: 50%; width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; margin-left: 20px; font-size: 32px;">
                    <i class="fas fa-upload"></i>
                </div>
                <div>
                    <h1 style="color: #2d3748; margin: 0; font-size: 32px; font-weight: 700;">استيراد سحب المنتجات من Excel</h1>
                    <p style="color: #718096; margin: 5px 0 0 0; font-size: 16px;">رفع ملف Excel أو CSV لاستيراد بيانات سحب المنتجات</p>
                </div>
            </div>
            <div style="display: flex; gap: 15px;">
                <a href="{{ route('tenant.inventory.regulatory.product-recalls.download-template') }}" style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); color: white; padding: 15px 25px; border: none; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; text-decoration: none;">
                    <i class="fas fa-download"></i>
                    تحميل النموذج
                </a>
                <a href="{{ route('tenant.inventory.regulatory.product-recalls.index') }}" style="background: rgba(255,255,255,0.2); color: #ff9a9e; padding: 15px 25px; border: 2px solid #ff9a9e; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; text-decoration: none;">
                    <i class="fas fa-arrow-right"></i>
                    العودة للقائمة
                </a>
            </div>
        </div>
    </div>

    <!-- Messages -->
    @if(session('success'))
        <div style="background: rgba(72, 187, 120, 0.1); border: 2px solid #48bb78; border-radius: 15px; padding: 20px; margin-bottom: 20px; color: #2d3748;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-check-circle" style="color: #48bb78; font-size: 20px;"></i>
                <strong>{{ session('success') }}</strong>
            </div>
        </div>
    @endif

    @if(session('warning'))
        <div style="background: rgba(237, 137, 54, 0.1); border: 2px solid #ed8936; border-radius: 15px; padding: 20px; margin-bottom: 20px; color: #2d3748;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-exclamation-triangle" style="color: #ed8936; font-size: 20px;"></i>
                <strong>{{ session('warning') }}</strong>
            </div>
            @if(session('import_errors'))
                <div style="margin-top: 15px; padding: 15px; background: rgba(255,255,255,0.5); border-radius: 10px;">
                    <strong>الأخطاء:</strong>
                    <ul style="margin: 10px 0 0 20px;">
                        @foreach(session('import_errors') as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    @endif

    @if(session('error'))
        <div style="background: rgba(245, 101, 101, 0.1); border: 2px solid #f56565; border-radius: 15px; padding: 20px; margin-bottom: 20px; color: #2d3748;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-times-circle" style="color: #f56565; font-size: 20px;"></i>
                <strong>{{ session('error') }}</strong>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div style="background: rgba(245, 101, 101, 0.1); border: 2px solid #f56565; border-radius: 15px; padding: 20px; margin-bottom: 20px; color: #2d3748;">
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                <i class="fas fa-times-circle" style="color: #f56565; font-size: 20px;"></i>
                <strong>يرجى تصحيح الأخطاء التالية:</strong>
            </div>
            <ul style="margin: 0 0 0 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Import Form -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        
        <!-- Instructions -->
        <div style="background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%); color: white; border-radius: 15px; padding: 25px; margin-bottom: 30px;">
            <h3 style="margin: 0 0 15px 0; font-size: 20px; font-weight: 700;">
                <i class="fas fa-info-circle" style="margin-left: 10px;"></i>
                تعليمات الاستيراد
            </h3>
            <ul style="margin: 0; padding-right: 20px; line-height: 1.8;">
                <li>قم بتحميل النموذج أولاً لمعرفة تنسيق البيانات المطلوب</li>
                <li>املأ البيانات في النموذج واحفظه بصيغة CSV أو Excel</li>
                <li>تأكد من أن الحقول المطلوبة (عنوان السحب، المنتج، نوع السحب) مملوءة</li>
                <li>استخدم التواريخ بصيغة YYYY-MM-DD (مثل: 2024-01-15)</li>
                <li>استخدم القيم المحددة لنوع السحب ومستوى المخاطر كما في النموذج</li>
                <li>حجم الملف يجب أن يكون أقل من 2 ميجابايت</li>
            </ul>
        </div>

        <!-- Upload Form -->
        <form action="{{ route('tenant.inventory.regulatory.product-recalls.import') }}" method="POST" enctype="multipart/form-data" id="importForm">
            @csrf
            
            <div style="text-align: center; padding: 40px 20px;">
                <div id="dropZone" style="border: 3px dashed #ff9a9e; border-radius: 15px; padding: 60px 20px; background: rgba(255, 154, 158, 0.05); transition: all 0.3s; cursor: pointer;"
                     ondrop="dropHandler(event);" ondragover="dragOverHandler(event);" ondragleave="dragLeaveHandler(event);" onclick="document.getElementById('fileInput').click();">
                    
                    <div style="font-size: 64px; color: #ff9a9e; margin-bottom: 20px;">
                        <i class="fas fa-cloud-upload-alt"></i>
                    </div>
                    
                    <h3 style="color: #2d3748; margin: 0 0 10px 0; font-size: 24px; font-weight: 700;">
                        اسحب الملف هنا أو انقر للاختيار
                    </h3>
                    
                    <p style="color: #718096; margin: 0 0 20px 0; font-size: 16px;">
                        الملفات المدعومة: CSV, Excel (.xlsx)
                    </p>
                    
                    <input type="file" id="fileInput" name="import_file" accept=".csv,.xlsx,.xls" style="display: none;" onchange="fileSelected(this)">
                    
                    <div id="fileInfo" style="display: none; background: rgba(72, 187, 120, 0.1); border: 2px solid #48bb78; border-radius: 10px; padding: 15px; margin-top: 20px;">
                        <i class="fas fa-file-excel" style="color: #48bb78; margin-left: 10px;"></i>
                        <span id="fileName" style="color: #2d3748; font-weight: 600;"></span>
                        <button type="button" onclick="removeFile()" style="background: none; border: none; color: #f56565; margin-right: 10px; cursor: pointer;">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div style="text-align: center; margin-top: 30px;">
                <button type="submit" id="submitBtn" disabled style="background: #cbd5e0; color: #a0aec0; padding: 15px 40px; border: none; border-radius: 15px; font-weight: 600; font-size: 16px; cursor: not-allowed; display: inline-flex; align-items: center; gap: 10px;">
                    <i class="fas fa-upload"></i>
                    بدء الاستيراد
                </button>
            </div>
        </form>

        <!-- Sample Data Preview -->
        <div style="margin-top: 40px; padding-top: 30px; border-top: 2px solid #e2e8f0;">
            <h3 style="color: #2d3748; margin: 0 0 20px 0; font-size: 20px; font-weight: 700;">
                <i class="fas fa-table" style="margin-left: 10px; color: #ff9a9e;"></i>
                مثال على البيانات المطلوبة
            </h3>
            
            <div style="overflow-x: auto; border-radius: 10px; border: 1px solid #e2e8f0;">
                <table style="width: 100%; border-collapse: collapse; background: white;">
                    <thead>
                        <tr style="background: #f7fafc;">
                            <th style="padding: 12px; border-bottom: 1px solid #e2e8f0; text-align: right; font-weight: 600; color: #2d3748;">عنوان السحب</th>
                            <th style="padding: 12px; border-bottom: 1px solid #e2e8f0; text-align: right; font-weight: 600; color: #2d3748;">نوع السحب</th>
                            <th style="padding: 12px; border-bottom: 1px solid #e2e8f0; text-align: right; font-weight: 600; color: #2d3748;">اسم المنتج</th>
                            <th style="padding: 12px; border-bottom: 1px solid #e2e8f0; text-align: right; font-weight: 600; color: #2d3748;">أرقام الدفعات</th>
                            <th style="padding: 12px; border-bottom: 1px solid #e2e8f0; text-align: right; font-weight: 600; color: #2d3748;">مستوى المخاطر</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="padding: 12px; border-bottom: 1px solid #e2e8f0; color: #4a5568;">سحب دواء باراسيتامول</td>
                            <td style="padding: 12px; border-bottom: 1px solid #e2e8f0; color: #4a5568;">طوعي</td>
                            <td style="padding: 12px; border-bottom: 1px solid #e2e8f0; color: #4a5568;">باراسيتامول 500 مجم</td>
                            <td style="padding: 12px; border-bottom: 1px solid #e2e8f0; color: #4a5568;">BATCH001, BATCH002</td>
                            <td style="padding: 12px; border-bottom: 1px solid #e2e8f0; color: #4a5568;">الفئة الأولى</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <!-- Valid Values -->
            <div style="margin-top: 20px; padding: 20px; background: rgba(255, 154, 158, 0.05); border-radius: 10px;">
                <h4 style="color: #2d3748; margin: 0 0 15px 0; font-weight: 700;">القيم المقبولة:</h4>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 15px;">
                    <div>
                        <strong style="color: #ff9a9e;">نوع السحب:</strong>
                        <ul style="margin: 5px 0 0 20px; color: #4a5568; font-size: 14px;">
                            <li>طوعي</li>
                            <li>إجباري</li>
                            <li>سحب من السوق</li>
                        </ul>
                    </div>
                    <div>
                        <strong style="color: #ff9a9e;">مستوى المخاطر:</strong>
                        <ul style="margin: 5px 0 0 20px; color: #4a5568; font-size: 14px;">
                            <li>الفئة الأولى</li>
                            <li>الفئة الثانية</li>
                            <li>الفئة الثالثة</li>
                        </ul>
                    </div>
                    <div>
                        <strong style="color: #ff9a9e;">حالة السحب:</strong>
                        <ul style="margin: 5px 0 0 20px; color: #4a5568; font-size: 14px;">
                            <li>بدأ</li>
                            <li>قيد التنفيذ</li>
                            <li>مكتمل</li>
                            <li>منتهي</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function dragOverHandler(ev) {
    ev.preventDefault();
    document.getElementById('dropZone').style.borderColor = '#48bb78';
    document.getElementById('dropZone').style.background = 'rgba(72, 187, 120, 0.1)';
}

function dragLeaveHandler(ev) {
    document.getElementById('dropZone').style.borderColor = '#ff9a9e';
    document.getElementById('dropZone').style.background = 'rgba(255, 154, 158, 0.05)';
}

function dropHandler(ev) {
    ev.preventDefault();
    
    const files = ev.dataTransfer.files;
    if (files.length > 0) {
        document.getElementById('fileInput').files = files;
        fileSelected(document.getElementById('fileInput'));
    }
    
    dragLeaveHandler(ev);
}

function fileSelected(input) {
    const file = input.files[0];
    if (file) {
        // Check file type
        const allowedTypes = ['text/csv', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
        if (!allowedTypes.includes(file.type) && !file.name.toLowerCase().endsWith('.csv')) {
            alert('نوع الملف غير مدعوم. يرجى اختيار ملف CSV أو Excel.');
            removeFile();
            return;
        }
        
        // Check file size (2MB)
        if (file.size > 2 * 1024 * 1024) {
            alert('حجم الملف كبير جداً. يجب أن يكون أقل من 2 ميجابايت.');
            removeFile();
            return;
        }
        
        document.getElementById('fileName').textContent = file.name;
        document.getElementById('fileInfo').style.display = 'block';
        document.getElementById('submitBtn').disabled = false;
        document.getElementById('submitBtn').style.background = 'linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%)';
        document.getElementById('submitBtn').style.color = 'white';
        document.getElementById('submitBtn').style.cursor = 'pointer';
    }
}

function removeFile() {
    document.getElementById('fileInput').value = '';
    document.getElementById('fileInfo').style.display = 'none';
    document.getElementById('submitBtn').disabled = true;
    document.getElementById('submitBtn').style.background = '#cbd5e0';
    document.getElementById('submitBtn').style.color = '#a0aec0';
    document.getElementById('submitBtn').style.cursor = 'not-allowed';
}

// Form submission
document.getElementById('importForm').addEventListener('submit', function(e) {
    const submitBtn = document.getElementById('submitBtn');
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الاستيراد...';
    submitBtn.disabled = true;
});
</script>

@endsection
