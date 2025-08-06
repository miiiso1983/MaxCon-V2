<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>اختبار رفع ملف Excel</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            margin: 0;
            padding: 20px;
            min-height: 100vh;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        .title {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
            font-size: 28px;
        }
        .upload-form {
            border: 2px dashed #ddd;
            border-radius: 10px;
            padding: 30px;
            text-align: center;
            margin-bottom: 20px;
            background: #f9f9f9;
        }
        .file-input {
            margin: 20px 0;
        }
        .btn {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 25px;
            cursor: pointer;
            font-size: 16px;
            transition: all 0.3s;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        .result {
            margin-top: 20px;
            padding: 20px;
            border-radius: 10px;
            display: none;
        }
        .success {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }
        .error {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }
        .json-display {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 5px;
            padding: 15px;
            margin-top: 10px;
            font-family: 'Courier New', monospace;
            white-space: pre-wrap;
            max-height: 400px;
            overflow-y: auto;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="title">🔍 اختبار رفع ملف Excel للموردين</h1>
        
        <div class="upload-form">
            <h3>ارفع ملف Excel لفحص محتوياته</h3>
            <form id="uploadForm" enctype="multipart/form-data">
                <div class="file-input">
                    <input type="file" name="excel_file" id="excelFile" accept=".xlsx,.xls,.csv" required>
                </div>
                <button type="submit" class="btn">📊 فحص الملف</button>
            </form>
        </div>
        
        <div id="result" class="result">
            <div id="resultContent"></div>
        </div>
    </div>

    <script>
        document.getElementById('uploadForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData();
            const fileInput = document.getElementById('excelFile');
            const file = fileInput.files[0];
            
            if (!file) {
                alert('يرجى اختيار ملف');
                return;
            }
            
            formData.append('excel_file', file);
            
            const resultDiv = document.getElementById('result');
            const resultContent = document.getElementById('resultContent');
            
            try {
                resultContent.innerHTML = '<p>جاري فحص الملف...</p>';
                resultDiv.className = 'result';
                resultDiv.style.display = 'block';
                
                const response = await fetch('/tenant/purchasing/suppliers/debug-excel-upload', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                
                const data = await response.json();
                
                if (data.error) {
                    resultDiv.className = 'result error';
                    resultContent.innerHTML = `
                        <h4>❌ خطأ في قراءة الملف</h4>
                        <p><strong>الخطأ:</strong> ${data.error}</p>
                        <p><strong>الملف:</strong> ${data.file}</p>
                        <p><strong>السطر:</strong> ${data.line}</p>
                    `;
                } else {
                    resultDiv.className = 'result success';
                    resultContent.innerHTML = `
                        <h4>✅ تم قراءة الملف بنجاح</h4>
                        <p><strong>اسم الملف:</strong> ${data.file_info.name}</p>
                        <p><strong>حجم الملف:</strong> ${data.file_info.size} بايت</p>
                        <p><strong>نوع الملف:</strong> ${data.file_info.mime}</p>
                        <p><strong>عدد الأوراق:</strong> ${data.sheets_count}</p>
                        <p><strong>عدد الصفوف:</strong> ${data.first_sheet_rows}</p>
                        <p><strong>العناوين:</strong></p>
                        <div class="json-display">${JSON.stringify(data.headers, null, 2)}</div>
                        <p><strong>أول 3 صفوف:</strong></p>
                        <div class="json-display">${JSON.stringify(data.first_few_rows, null, 2)}</div>
                    `;
                }
            } catch (error) {
                resultDiv.className = 'result error';
                resultContent.innerHTML = `
                    <h4>❌ خطأ في الاتصال</h4>
                    <p>${error.message}</p>
                `;
            }
        });
    </script>
</body>
</html>
