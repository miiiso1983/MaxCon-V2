<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>خطأ في الخادم - 500</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            direction: rtl;
        }
        .error-container {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            text-align: center;
            max-width: 600px;
            margin: 20px;
        }
        .error-code {
            font-size: 72px;
            font-weight: bold;
            color: #e74c3c;
            margin-bottom: 20px;
        }
        .error-title {
            font-size: 24px;
            color: #2c3e50;
            margin-bottom: 15px;
        }
        .error-message {
            font-size: 16px;
            color: #7f8c8d;
            margin-bottom: 30px;
            line-height: 1.6;
        }
        .error-details {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            text-align: right;
            font-family: monospace;
            font-size: 14px;
            color: #e74c3c;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            margin: 5px;
            transition: background 0.3s;
        }
        .btn:hover {
            background: #2980b9;
        }
        .btn-secondary {
            background: #95a5a6;
        }
        .btn-secondary:hover {
            background: #7f8c8d;
        }
        .links {
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-code">500</div>
        <div class="error-title">خطأ في الخادم</div>
        <div class="error-message">
            عذراً، حدث خطأ غير متوقع في الخادم. نحن نعمل على إصلاح هذه المشكلة.
        </div>
        
        @if(isset($message))
        <div class="error-details">
            <strong>تفاصيل الخطأ:</strong><br>
            {{ $message }}
        </div>
        @endif
        
        <div class="links">
            <a href="/direct-invoices" class="btn">الوصول المباشر للفواتير</a>
            <a href="/emergency-debug" class="btn btn-secondary">تشخيص النظام</a>
            <a href="/" class="btn btn-secondary">الصفحة الرئيسية</a>
        </div>
        
        <div style="margin-top: 30px; font-size: 14px; color: #7f8c8d;">
            <p>إذا استمرت المشكلة، يرجى المحاولة مرة أخرى أو الاتصال بالدعم الفني.</p>
            <p><strong>روابط مفيدة:</strong></p>
            <ul style="list-style: none; padding: 0;">
                <li><a href="/system-diagnosis">تشخيص قاعدة البيانات</a></li>
                <li><a href="/check-user-status">فحص حالة المستخدم</a></li>
                <li><a href="/permissions-help">مساعدة الصلاحيات</a></li>
            </ul>
        </div>
    </div>
</body>
</html>
