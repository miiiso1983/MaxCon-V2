<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>اختبار إنشاء شركة</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, select, textarea { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; }
        button { background: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; }
        button:disabled { background: #ccc; }
        .error { color: red; margin-top: 10px; }
        .success { color: green; margin-top: 10px; }
    </style>
</head>
<body>
    <h1>اختبار إنشاء شركة جديدة</h1>
    
    <form id="testForm">
        <div class="form-group">
            <label>اسم الشركة *</label>
            <input type="text" name="company_name" required value="شركة اختبار جديدة">
        </div>
        
        <div class="form-group">
            <label>رقم التسجيل *</label>
            <input type="text" name="registration_number" required value="REG-TEST-003">
        </div>
        
        <div class="form-group">
            <label>رقم الترخيص *</label>
            <input type="text" name="license_number" required value="LIC-TEST-003">
        </div>
        
        <div class="form-group">
            <label>نوع الترخيص *</label>
            <select name="license_type" required>
                <option value="manufacturing">تصنيع</option>
                <option value="import">استيراد</option>
                <option value="export">تصدير</option>
                <option value="distribution">توزيع</option>
            </select>
        </div>
        
        <div class="form-group">
            <label>الجهة التنظيمية *</label>
            <input type="text" name="regulatory_authority" required value="وزارة الصحة العراقية">
        </div>
        
        <div class="form-group">
            <label>تاريخ التسجيل *</label>
            <input type="date" name="registration_date" required value="2024-01-01">
        </div>
        
        <div class="form-group">
            <label>تاريخ إصدار الترخيص *</label>
            <input type="date" name="license_issue_date" required value="2024-01-01">
        </div>
        
        <div class="form-group">
            <label>تاريخ انتهاء الترخيص *</label>
            <input type="date" name="license_expiry_date" required value="2025-12-31">
        </div>
        
        <div class="form-group">
            <label>عنوان الشركة *</label>
            <textarea name="company_address" required>بغداد - العراق</textarea>
        </div>
        
        <div class="form-group">
            <label>الشخص المسؤول *</label>
            <input type="text" name="contact_person" required value="أحمد محمد">
        </div>
        
        <div class="form-group">
            <label>البريد الإلكتروني *</label>
            <input type="email" name="contact_email" required value="test@company.com">
        </div>
        
        <div class="form-group">
            <label>رقم الهاتف *</label>
            <input type="text" name="contact_phone" required value="123456789">
        </div>
        
        <div class="form-group">
            <label>حالة الامتثال *</label>
            <select name="compliance_status" required>
                <option value="compliant">ملتزم</option>
                <option value="non_compliant">غير ملتزم</option>
                <option value="under_investigation">قيد التحقيق</option>
                <option value="corrective_action">إجراء تصحيحي</option>
            </select>
        </div>
        
        <button type="submit" id="submitBtn">إنشاء الشركة (Ultra Simple)</button>
        <button type="button" id="testSimpleBtn" style="background: #28a745; margin-left: 10px;">اختبار Simple Route</button>
    </form>

    <div id="result"></div>
    
    <script>
        document.getElementById('testForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitBtn = document.getElementById('submitBtn');
            const result = document.getElementById('result');
            
            submitBtn.disabled = true;
            submitBtn.textContent = 'جاري الإنشاء...';
            result.innerHTML = '';
            
            // Test with ultra simple route (direct DB insert)
            fetch('{{ route("test.company.store.ultra.simple") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                console.log('Response status:', response.status);
                console.log('Response headers:', response.headers);
                
                if (response.ok) {
                    return response.json();
                } else {
                    return response.text().then(text => {
                        throw new Error(`HTTP ${response.status}: ${text}`);
                    });
                }
            })
            .then(data => {
                console.log('Success data:', data);
                result.innerHTML = '<div class="success">✅ تم إنشاء الشركة بنجاح!</div>';
                result.innerHTML += '<pre>' + JSON.stringify(data, null, 2) + '</pre>';
            })
            .catch(error => {
                console.error('Error:', error);
                result.innerHTML = '<div class="error">❌ خطأ: ' + error.message + '</div>';
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.textContent = 'إنشاء الشركة';
            });
        });

        // Test simple route button
        document.getElementById('testSimpleBtn').addEventListener('click', function() {
            const form = document.getElementById('testForm');
            const formData = new FormData(form);
            const result = document.getElementById('result');
            const btn = this;

            btn.disabled = true;
            btn.textContent = 'جاري الاختبار...';
            result.innerHTML = '';

            fetch('{{ route("test.company.store.simple") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                console.log('Simple route response status:', response.status);

                if (response.ok) {
                    return response.json();
                } else {
                    return response.text().then(text => {
                        throw new Error(`HTTP ${response.status}: ${text}`);
                    });
                }
            })
            .then(data => {
                console.log('Simple route success data:', data);
                result.innerHTML = '<div class="success">✅ Simple Route: تم إنشاء الشركة بنجاح!</div>';
                result.innerHTML += '<pre>' + JSON.stringify(data, null, 2) + '</pre>';
            })
            .catch(error => {
                console.error('Simple route error:', error);
                result.innerHTML = '<div class="error">❌ Simple Route خطأ: ' + error.message + '</div>';
            })
            .finally(() => {
                btn.disabled = false;
                btn.textContent = 'اختبار Simple Route';
            });
        });
    </script>
</body>
</html>
