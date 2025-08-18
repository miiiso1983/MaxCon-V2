@extends('layouts.modern')

@section('title', 'استيراد الموظفين من Excel')

@section('content')
<div style="padding: 30px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh;">
    <!-- Header -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; margin-bottom: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <div style="display: flex; align-items: center; justify-content: between; margin-bottom: 20px;">
            <div style="display: flex; align-items: center;">
                <div style="background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%); color: white; border-radius: 50%; width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; margin-left: 20px; font-size: 32px;">
                    <i class="fas fa-upload"></i>
                </div>
                <div>
                    <h1 style="color: #2d3748; margin: 0; font-size: 32px; font-weight: 700;">استيراد الموظفين من Excel</h1>
                    <p style="color: #718096; margin: 5px 0 0 0; font-size: 16px;">رفع ملف Excel لإضافة موظفين متعددين</p>
                </div>
            </div>
            <div style="display: flex; gap: 15px;">
                <a href="{{ route('tenant.hr.employees.index') }}" style="background: #e2e8f0; color: #4a5568; padding: 15px 25px; border: none; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; text-decoration: none;">
                    <i class="fas fa-arrow-right"></i>
                    العودة للقائمة
                </a>
            </div>
        </div>
    </div>

    <!-- Import Instructions -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; margin-bottom: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <h3 style="color: #2d3748; margin: 0 0 20px 0; font-size: 24px; font-weight: 700;">
            <i class="fas fa-info-circle" style="margin-left: 10px; color: #4299e1;"></i>
            تعليمات الاستيراد
        </h3>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 25px;">
            
            <!-- Step 1 -->
            <div style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); color: white; border-radius: 15px; padding: 25px; text-align: center;">
                <div style="background: rgba(255,255,255,0.2); border-radius: 50%; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; font-size: 24px; font-weight: 700;">
                    1
                </div>
                <h4 style="margin: 0 0 10px 0; font-size: 18px; font-weight: 700;">تحميل القالب</h4>
                <p style="margin: 0; opacity: 0.9; font-size: 14px;">قم بتحميل قالب Excel المعد مسبقاً</p>
            </div>

            <!-- Step 2 -->
            <div style="background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%); color: white; border-radius: 15px; padding: 25px; text-align: center;">
                <div style="background: rgba(255,255,255,0.2); border-radius: 50%; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; font-size: 24px; font-weight: 700;">
                    2
                </div>
                <h4 style="margin: 0 0 10px 0; font-size: 18px; font-weight: 700;">ملء البيانات</h4>
                <p style="margin: 0; opacity: 0.9; font-size: 14px;">أدخل بيانات الموظفين في القالب</p>
            </div>

            <!-- Step 3 -->
            <div style="background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%); color: white; border-radius: 15px; padding: 25px; text-align: center;">
                <div style="background: rgba(255,255,255,0.2); border-radius: 50%; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; font-size: 24px; font-weight: 700;">
                    3
                </div>
                <h4 style="margin: 0 0 10px 0; font-size: 18px; font-weight: 700;">رفع الملف</h4>
                <p style="margin: 0; opacity: 0.9; font-size: 14px;">ارفع الملف المكتمل للاستيراد</p>
            </div>
        </div>
    </div>

    <!-- Download Template -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; margin-bottom: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <h3 style="color: #2d3748; margin: 0 0 20px 0; font-size: 24px; font-weight: 700;">
            <i class="fas fa-download" style="margin-left: 10px; color: #48bb78;"></i>
            تحميل القالب
        </h3>
        
        <div style="text-align: center; padding: 40px;">
            <div style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); color: white; border-radius: 50%; width: 100px; height: 100px; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; font-size: 40px;">
                <i class="fas fa-file-excel"></i>
            </div>
            <h4 style="color: #2d3748; margin: 0 0 15px 0; font-size: 20px; font-weight: 700;">قالب استيراد الموظفين</h4>
            <p style="color: #718096; margin: 0 0 25px 0; font-size: 16px;">قم بتحميل القالب وملء البيانات المطلوبة</p>
            
            <button onclick="downloadTemplate()" style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); color: white; padding: 15px 30px; border: none; border-radius: 15px; font-size: 18px; font-weight: 700; cursor: pointer; display: inline-flex; align-items: center; gap: 10px; transition: transform 0.3s;"
                    onmouseover="this.style.transform='translateY(-2px)'" 
                    onmouseout="this.style.transform='translateY(0)'">
                <i class="fas fa-download"></i>
                تحميل قالب Excel
            </button>
        </div>

        <!-- Template Fields Info -->
        <div style="margin-top: 30px; padding-top: 25px; border-top: 1px solid #e2e8f0;">
            <h4 style="color: #2d3748; margin: 0 0 15px 0; font-size: 18px; font-weight: 700;">الحقول المطلوبة في القالب:</h4>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 15px;">
                <div style="background: #f7fafc; border-radius: 10px; padding: 15px;">
                    <h5 style="color: #2d3748; margin: 0 0 10px 0; font-weight: 700;">الحقول الإجبارية</h5>
                    <ul style="color: #4a5568; margin: 0; padding-right: 20px; font-size: 14px;">
                        <li>الاسم الأول</li>
                        <li>الاسم الأخير</li>
                        <li>رقم الهوية</li>
                        <li>البريد الإلكتروني</li>
                        <li>رقم الهاتف</li>
                        <li>تاريخ الميلاد</li>
                        <li>الجنس</li>
                        <li>القسم</li>
                        <li>المنصب</li>
                        <li>تاريخ التوظيف</li>
                        <li>الراتب الأساسي</li>
                        <li>نوع التوظيف</li>
                    </ul>
                </div>

                <div style="background: #f7fafc; border-radius: 10px; padding: 15px;">
                    <h5 style="color: #2d3748; margin: 0 0 10px 0; font-weight: 700;">الحقول الاختيارية</h5>
                    <ul style="color: #4a5568; margin: 0; padding-right: 20px; font-size: 14px;">
                        <li>كود الموظف</li>
                        <li>الحالة الاجتماعية</li>
                        <li>العنوان</li>
                        <li>المهارات</li>
                        <li>اللغات</li>
                        <li>الملاحظات</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Upload Form -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 40px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <h3 style="color: #2d3748; margin: 0 0 25px 0; font-size: 24px; font-weight: 700;">
            <i class="fas fa-upload" style="margin-left: 10px; color: #4299e1;"></i>
            رفع ملف الاستيراد
        </h3>
        
        <form action="{{ route('tenant.hr.employees.import') }}" method="POST" enctype="multipart/form-data" id="importForm">
            @csrf
            
            <!-- File Upload Area -->
            <div style="border: 3px dashed #e2e8f0; border-radius: 20px; padding: 60px 40px; text-align: center; transition: all 0.3s; cursor: pointer;" 
                 id="dropZone"
                 ondragover="handleDragOver(event)" 
                 ondrop="handleDrop(event)"
                 onclick="document.getElementById('fileInput').click()">
                
                <div style="color: #4299e1; font-size: 64px; margin-bottom: 20px;">
                    <i class="fas fa-cloud-upload-alt"></i>
                </div>
                
                <h4 style="color: #2d3748; margin: 0 0 10px 0; font-size: 20px; font-weight: 700;">اسحب الملف هنا أو انقر للاختيار</h4>
                <p style="color: #718096; margin: 0 0 20px 0; font-size: 16px;">يدعم ملفات Excel (.xlsx, .xls)</p>
                
                <input type="file" id="fileInput" name="file" accept=".xlsx,.xls" style="display: none;" onchange="handleFileSelect(event)">
                
                <div id="fileInfo" style="display: none; background: #f7fafc; border-radius: 10px; padding: 20px; margin-top: 20px;">
                    <div style="display: flex; align-items: center; justify-content: center; gap: 15px;">
                        <i class="fas fa-file-excel" style="color: #48bb78; font-size: 24px;"></i>
                        <div>
                            <div id="fileName" style="color: #2d3748; font-weight: 700; font-size: 16px;"></div>
                            <div id="fileSize" style="color: #718096; font-size: 14px;"></div>
                        </div>
                        <button type="button" onclick="clearFile()" style="background: #f56565; color: white; border: none; border-radius: 50%; width: 30px; height: 30px; cursor: pointer;">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Import Options -->
            <div style="margin-top: 30px; padding: 25px; background: #f7fafc; border-radius: 15px;">
                <h4 style="color: #2d3748; margin: 0 0 15px 0; font-size: 18px; font-weight: 700;">خيارات الاستيراد</h4>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
                    
                    <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                        <input type="checkbox" name="skip_duplicates" value="1" checked style="width: 18px; height: 18px;">
                        <span style="color: #2d3748; font-weight: 600;">تخطي البيانات المكررة</span>
                    </label>

                    <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                        <input type="checkbox" name="validate_emails" value="1" checked style="width: 18px; height: 18px;">
                        <span style="color: #2d3748; font-weight: 600;">التحقق من صحة البريد الإلكتروني</span>
                    </label>

                    <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                        <input type="checkbox" name="send_welcome_email" value="1" style="width: 18px; height: 18px;">
                        <span style="color: #2d3748; font-weight: 600;">إرسال بريد ترحيبي</span>
                    </label>
                </div>
            </div>

            <!-- Submit Button -->
            <div style="text-align: center; margin-top: 30px;">
                <button type="submit" id="submitBtn" disabled style="background: #e2e8f0; color: #a0aec0; padding: 15px 40px; border: none; border-radius: 15px; font-size: 18px; font-weight: 700; cursor: not-allowed; display: inline-flex; align-items: center; gap: 10px;">
                    <i class="fas fa-upload"></i>
                    بدء الاستيراد
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function downloadTemplate() {
    window.location.href = "{{ route('tenant.hr.employees.import.template') }}";
}

function handleDragOver(event) {
    event.preventDefault();
    event.currentTarget.style.borderColor = '#4299e1';
    event.currentTarget.style.backgroundColor = 'rgba(66, 153, 225, 0.05)';
}

function handleDrop(event) {
    event.preventDefault();
    const dropZone = event.currentTarget;
    dropZone.style.borderColor = '#e2e8f0';
    dropZone.style.backgroundColor = 'transparent';
    
    const files = event.dataTransfer.files;
    if (files.length > 0) {
        const fileInput = document.getElementById('fileInput');
        fileInput.files = files;
        handleFileSelect({ target: fileInput });
    }
}

function handleFileSelect(event) {
    const file = event.target.files[0];
    if (file) {
        const fileInfo = document.getElementById('fileInfo');
        const fileName = document.getElementById('fileName');
        const fileSize = document.getElementById('fileSize');
        const submitBtn = document.getElementById('submitBtn');
        
        fileName.textContent = file.name;
        fileSize.textContent = formatFileSize(file.size);
        fileInfo.style.display = 'block';
        
        // Enable submit button
        submitBtn.disabled = false;
        submitBtn.style.background = 'linear-gradient(135deg, #4299e1 0%, #3182ce 100%)';
        submitBtn.style.color = 'white';
        submitBtn.style.cursor = 'pointer';
    }
}

function clearFile() {
    const fileInput = document.getElementById('fileInput');
    const fileInfo = document.getElementById('fileInfo');
    const submitBtn = document.getElementById('submitBtn');
    
    fileInput.value = '';
    fileInfo.style.display = 'none';
    
    // Disable submit button
    submitBtn.disabled = true;
    submitBtn.style.background = '#e2e8f0';
    submitBtn.style.color = '#a0aec0';
    submitBtn.style.cursor = 'not-allowed';
}

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

// Form submission
document.getElementById('importForm').addEventListener('submit', function(e) {
    const fileInput = document.getElementById('fileInput');
    if (!fileInput.files[0]) {
        e.preventDefault();
        alert('يرجى اختيار ملف للاستيراد');
        return;
    }
    
    // Show loading state
    const submitBtn = document.getElementById('submitBtn');
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الاستيراد...';
    submitBtn.disabled = true;
});

// Reset drag zone styles when dragging leaves
document.getElementById('dropZone').addEventListener('dragleave', function(event) {
    event.currentTarget.style.borderColor = '#e2e8f0';
    event.currentTarget.style.backgroundColor = 'transparent';
});
</script>

@endsection
