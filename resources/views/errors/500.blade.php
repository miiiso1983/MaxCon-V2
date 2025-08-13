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
            border-left: 4px solid #e74c3c;
            max-height: 300px;
            overflow-y: auto;
        }
        .error-section {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 8px;
            padding: 15px;
            margin: 15px 0;
            text-align: right;
        }
        .error-section h4 {
            color: #856404;
            margin: 0 0 10px 0;
            font-size: 16px;
        }
        .error-section p {
            color: #856404;
            margin: 5px 0;
            font-size: 14px;
        }
        .stack-trace {
            background: #f1f2f6;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            margin: 15px 0;
            font-family: 'Courier New', monospace;
            font-size: 12px;
            color: #2c3e50;
            text-align: left;
            max-height: 200px;
            overflow-y: auto;
            white-space: pre-wrap;
        }
        .toggle-btn {
            background: #f39c12;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 12px;
            margin: 5px 0;
        }
        .toggle-btn:hover {
            background: #e67e22;
        }
        .hidden {
            display: none;
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

        @if(config('app.debug') || app()->environment('local'))
            <!-- معلومات تفصيلية للمطورين -->
            @if(isset($exception))
                <div class="error-section">
                    <h4>🔍 تفاصيل الخطأ للمطورين</h4>
                    <p><strong>نوع الخطأ:</strong> {{ get_class($exception) }}</p>
                    <p><strong>رسالة الخطأ:</strong> {{ $exception->getMessage() }}</p>
                    <p><strong>الملف:</strong> {{ $exception->getFile() }}</p>
                    <p><strong>السطر:</strong> {{ $exception->getLine() }}</p>
                    <p><strong>الوقت:</strong> {{ now()->format('Y-m-d H:i:s') }}</p>
                    @php
                        try {
                            if(auth()->check()) {
                                echo '<p><strong>المستخدم:</strong> ' . auth()->user()->name . ' (ID: ' . auth()->id() . ')</p>';
                                echo '<p><strong>المستأجر:</strong> ' . (auth()->user()->tenant_id ?? 'غير محدد') . '</p>';
                            } else {
                                echo '<p><strong>المستخدم:</strong> غير مسجل دخول</p>';
                            }
                        } catch(\Exception $e) {
                            echo '<p><strong>المستخدم:</strong> خطأ في جلب معلومات المستخدم</p>';
                        }
                    @endphp
                    <p><strong>الرابط:</strong> {{ request()->fullUrl() }}</p>
                    <p><strong>طريقة الطلب:</strong> {{ request()->method() }}</p>
                    <p><strong>عنوان IP:</strong> {{ request()->ip() }}</p>
                </div>

                <button class="toggle-btn" onclick="toggleStackTrace()">عرض/إخفاء تتبع المكدس</button>
                <div id="stackTrace" class="stack-trace hidden">{{ $exception->getTraceAsString() }}</div>
            @elseif(isset($message))
                <div class="error-details">
                    <strong>تفاصيل الخطأ:</strong><br>
                    {{ $message }}
                </div>
            @else
                <div class="error-section">
                    <h4>⚠️ معلومات الخطأ</h4>
                    <p><strong>الوقت:</strong> {{ now()->format('Y-m-d H:i:s') }}</p>
                    <p><strong>الرابط:</strong> {{ request()->fullUrl() }}</p>
                    <p><strong>طريقة الطلب:</strong> {{ request()->method() }}</p>
                    <p><strong>عنوان IP:</strong> {{ request()->ip() }}</p>
                    <p>لم يتم العثور على تفاصيل إضافية للخطأ.</p>
                </div>
            @endif
        @else
            <!-- معلومات مبسطة للمستخدمين -->
            <div class="error-section">
                <h4>ℹ️ معلومات إضافية</h4>
                <p><strong>الوقت:</strong> {{ now()->format('Y-m-d H:i:s') }}</p>
                <p><strong>كود المرجع:</strong> {{ Str::random(8) }}</p>
                @php
                    try {
                        if(auth()->check()) {
                            echo '<p><strong>المستخدم:</strong> ' . auth()->user()->name . '</p>';
                            if(auth()->user()->tenant_id) {
                                echo '<p><strong>رقم المستأجر:</strong> ' . auth()->user()->tenant_id . '</p>';
                            }
                            echo '<p><strong>البريد الإلكتروني:</strong> ' . auth()->user()->email . '</p>';
                        } else {
                            echo '<p><strong>المستخدم:</strong> غير مسجل دخول</p>';
                        }
                    } catch(\Exception $e) {
                        echo '<p><strong>المستخدم:</strong> خطأ في جلب معلومات المستخدم</p>';
                    }
                @endphp
                <p><strong>الصفحة المطلوبة:</strong> {{ request()->path() }}</p>
                <p><strong>المتصفح:</strong> {{ request()->header('User-Agent') ? Str::limit(request()->header('User-Agent'), 50) : 'غير محدد' }}</p>
            </div>
        @endif
        
        <div class="links">
            @if(auth()->check())
                <a href="{{ route('tenant.dashboard') }}" class="btn">لوحة التحكم</a>
                <a href="{{ url()->previous() }}" class="btn btn-secondary">الصفحة السابقة</a>
            @else
                <a href="{{ route('login') }}" class="btn">تسجيل الدخول</a>
            @endif
            <a href="/" class="btn btn-secondary">الصفحة الرئيسية</a>
            <button class="btn btn-secondary" onclick="location.reload()">إعادة المحاولة</button>
            @if(config('app.debug'))
                <a href="/debug-error?url={{ urlencode(request()->fullUrl()) }}" class="btn" style="background: #f39c12;">تشخيص مفصل</a>
                <button onclick="downloadErrorReport()" class="btn" style="background: #28a745;">تحميل تقرير الخطأ</button>
            @endif
            <button onclick="copyErrorInfo()" class="btn" style="background: #6c757d;">نسخ معلومات الخطأ</button>
        </div>

        @if(config('app.debug'))
        <div style="margin-top: 30px; font-size: 14px; color: #7f8c8d;">
            <p><strong>نصائح للمطورين:</strong></p>
            <ul style="list-style: none; padding: 0; text-align: right;">
                <li>• تحقق من ملف اللوج: <code>storage/logs/laravel.log</code></li>
                <li>• تأكد من إعدادات قاعدة البيانات في <code>.env</code></li>
                <li>• تحقق من صلاحيات الملفات والمجلدات</li>
                <li>• راجع متغيرات البيئة والتكوين</li>
                <li>• تأكد من تشغيل <code>php artisan config:clear</code></li>
                <li>• تحقق من <code>composer install</code> و <code>npm install</code></li>
            </ul>

            <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin-top: 15px; border-left: 4px solid #007bff;">
                <p><strong>🔧 معلومات تقنية سريعة:</strong></p>
                <p>• <strong>إصدار PHP:</strong> {{ PHP_VERSION }}</p>
                <p>• <strong>إصدار Laravel:</strong> {{ app()->version() }}</p>
                <p>• <strong>البيئة:</strong> {{ app()->environment() }}</p>
                <p>• <strong>الذاكرة المستخدمة:</strong> {{ round(memory_get_usage(true) / 1024 / 1024, 2) }} MB</p>
                <p>• <strong>وقت التشغيل:</strong> {{ round((microtime(true) - LARAVEL_START) * 1000, 2) }} ms</p>
            </div>
        </div>
        @else
        <div style="margin-top: 30px; font-size: 14px; color: #7f8c8d;">
            <p>إذا استمرت المشكلة، يرجى المحاولة مرة أخرى أو الاتصال بالدعم الفني.</p>
        </div>
        @endif
    </div>

    <script>
        function toggleStackTrace() {
            const stackTrace = document.getElementById('stackTrace');
            if (stackTrace) {
                stackTrace.classList.toggle('hidden');
            }
        }

        // تسجيل معلومات الخطأ في وحدة التحكم
        console.group('🐛 معلومات الخطأ 500');
        console.log('الوقت:', new Date().toISOString());
        console.log('الرابط:', window.location.href);
        console.log('User Agent:', navigator.userAgent);
        console.groupEnd();

        // نسخ معلومات الخطأ
        function copyErrorInfo() {
            const errorInfo = `
خطأ في الخادم - MaxCon ERP
============================
الوقت: {{ now()->format('Y-m-d H:i:s') }}
كود المرجع: {{ Str::random(8) }}
@php
try {
    if(auth()->check()) {
        echo "المستخدم: " . auth()->user()->name . "\n";
        echo "البريد الإلكتروني: " . auth()->user()->email . "\n";
        if(auth()->user()->tenant_id) {
            echo "رقم المستأجر: " . auth()->user()->tenant_id . "\n";
        }
    } else {
        echo "المستخدم: غير مسجل دخول\n";
    }
} catch(\Exception $e) {
    echo "المستخدم: خطأ في جلب المعلومات\n";
}
@endphp
الرابط: {{ request()->fullUrl() }}
طريقة الطلب: {{ request()->method() }}
الصفحة: {{ request()->path() }}
عنوان IP: {{ request()->ip() }}
@if(isset($exception))
نوع الخطأ: {{ get_class($exception) }}
رسالة الخطأ: {{ $exception->getMessage() }}
الملف: {{ $exception->getFile() }}
السطر: {{ $exception->getLine() }}
@endif
إصدار PHP: {{ PHP_VERSION }}
إصدار Laravel: {{ app()->version() }}
البيئة: {{ app()->environment() }}
            `.trim();

            if (navigator.clipboard) {
                navigator.clipboard.writeText(errorInfo).then(function() {
                    alert('✅ تم نسخ معلومات الخطأ إلى الحافظة');
                }, function() {
                    alert('❌ فشل في نسخ المعلومات');
                });
            } else {
                // Fallback for older browsers
                const textArea = document.createElement('textarea');
                textArea.value = errorInfo;
                document.body.appendChild(textArea);
                textArea.select();
                try {
                    document.execCommand('copy');
                    alert('✅ تم نسخ معلومات الخطأ');
                } catch (err) {
                    alert('❌ فشل في نسخ المعلومات');
                }
                document.body.removeChild(textArea);
            }
        }

        // تحميل تقرير الخطأ
        function downloadErrorReport() {
            const errorReport = `
تقرير خطأ الخادم - MaxCon ERP
===============================

معلومات الخطأ:
--------------
الوقت: {{ now()->format('Y-m-d H:i:s T') }}
كود المرجع: {{ Str::random(8) }}
@php
try {
    if(auth()->check()) {
        echo "المستخدم: " . auth()->user()->name . "\n";
        echo "البريد الإلكتروني: " . auth()->user()->email . "\n";
        echo "ID المستخدم: " . auth()->id() . "\n";
        if(auth()->user()->tenant_id) {
            echo "رقم المستأجر: " . auth()->user()->tenant_id . "\n";
        }
    } else {
        echo "المستخدم: غير مسجل دخول\n";
    }
} catch(\Exception $e) {
    echo "المستخدم: خطأ في جلب المعلومات\n";
}
@endphp

معلومات الطلب:
--------------
الرابط الكامل: {{ request()->fullUrl() }}
طريقة الطلب: {{ request()->method() }}
المسار: {{ request()->path() }}
عنوان IP: {{ request()->ip() }}
User Agent: {{ request()->userAgent() }}

@if(isset($exception))
تفاصيل الخطأ التقنية:
--------------------
نوع الخطأ: {{ get_class($exception) }}
رسالة الخطأ: {{ $exception->getMessage() }}
الملف: {{ $exception->getFile() }}
السطر: {{ $exception->getLine() }}
كود الخطأ: {{ $exception->getCode() }}

Stack Trace:
-----------
{{ $exception->getTraceAsString() }}
@endif

معلومات النظام:
---------------
إصدار PHP: {{ PHP_VERSION }}
إصدار Laravel: {{ app()->version() }}
البيئة: {{ app()->environment() }}
المنطقة الزمنية: {{ config('app.timezone') }}
الذاكرة المستخدمة: {{ round(memory_get_usage(true) / 1024 / 1024, 2) }} MB
وقت التشغيل: {{ round((microtime(true) - LARAVEL_START) * 1000, 2) }} ms

تم إنشاء هذا التقرير تلقائياً بواسطة نظام MaxCon ERP
            `.trim();

            const blob = new Blob([errorReport], { type: 'text/plain;charset=utf-8' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'error-report-{{ now()->format("Y-m-d-H-i-s") }}.txt';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            window.URL.revokeObjectURL(url);
        }
    </script>
</body>
</html>
