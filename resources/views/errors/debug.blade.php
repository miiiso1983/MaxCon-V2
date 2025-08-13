<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تشخيص الخطأ - MaxCon ERP</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 20px;
            background: #f8f9fa;
            direction: rtl;
            line-height: 1.6;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            color: white;
            padding: 20px;
            text-align: center;
        }
        .content {
            padding: 30px;
        }
        .section {
            margin-bottom: 30px;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            overflow: hidden;
        }
        .section-header {
            background: #f8f9fa;
            padding: 15px 20px;
            border-bottom: 1px solid #e9ecef;
            font-weight: bold;
            color: #495057;
        }
        .section-content {
            padding: 20px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 200px 1fr;
            gap: 10px;
            margin-bottom: 15px;
        }
        .info-label {
            font-weight: bold;
            color: #6c757d;
        }
        .info-value {
            color: #495057;
            word-break: break-all;
        }
        .code-block {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 4px;
            padding: 15px;
            font-family: 'Courier New', monospace;
            font-size: 14px;
            overflow-x: auto;
            white-space: pre-wrap;
            margin: 10px 0;
        }
        .error-highlight {
            background: #fff5f5;
            border-left: 4px solid #e74c3c;
            padding: 15px;
            margin: 10px 0;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 5px;
            border: none;
            cursor: pointer;
        }
        .btn:hover {
            background: #0056b3;
        }
        .btn-secondary {
            background: #6c757d;
        }
        .btn-secondary:hover {
            background: #545b62;
        }
        .actions {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
        }
        .stack-trace {
            max-height: 400px;
            overflow-y: auto;
            background: #f1f3f4;
            border: 1px solid #dadce0;
            border-radius: 4px;
            padding: 15px;
            font-family: 'Courier New', monospace;
            font-size: 12px;
            line-height: 1.4;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔍 تشخيص تفصيلي للخطأ</h1>
            <p>معلومات تقنية مفصلة لحل المشكلة</p>
        </div>
        
        <div class="content">
            @if(isset($exception))
            <!-- معلومات الخطأ الأساسية -->
            <div class="section">
                <div class="section-header">📋 معلومات الخطأ الأساسية</div>
                <div class="section-content">
                    <div class="error-highlight">
                        <strong>{{ get_class($exception) }}</strong><br>
                        {{ $exception->getMessage() }}
                    </div>
                    
                    <div class="info-grid">
                        <div class="info-label">نوع الخطأ:</div>
                        <div class="info-value">{{ get_class($exception) }}</div>
                        
                        <div class="info-label">رسالة الخطأ:</div>
                        <div class="info-value">{{ $exception->getMessage() }}</div>
                        
                        <div class="info-label">الملف:</div>
                        <div class="info-value">{{ $exception->getFile() }}</div>
                        
                        <div class="info-label">السطر:</div>
                        <div class="info-value">{{ $exception->getLine() }}</div>
                        
                        <div class="info-label">كود الخطأ:</div>
                        <div class="info-value">{{ $exception->getCode() }}</div>
                    </div>
                </div>
            </div>
            @endif

            <!-- معلومات الطلب -->
            <div class="section">
                <div class="section-header">🌐 معلومات الطلب</div>
                <div class="section-content">
                    <div class="info-grid">
                        <div class="info-label">الرابط:</div>
                        <div class="info-value">{{ request()->fullUrl() }}</div>
                        
                        <div class="info-label">طريقة الطلب:</div>
                        <div class="info-value">{{ request()->method() }}</div>
                        
                        <div class="info-label">عنوان IP:</div>
                        <div class="info-value">{{ request()->ip() }}</div>
                        
                        <div class="info-label">User Agent:</div>
                        <div class="info-value">{{ request()->userAgent() }}</div>
                        
                        <div class="info-label">الوقت:</div>
                        <div class="info-value">{{ now()->format('Y-m-d H:i:s T') }}</div>
                    </div>
                </div>
            </div>

            <!-- معلومات المستخدم -->
            <div class="section">
                <div class="section-header">👤 معلومات المستخدم</div>
                <div class="section-content">
                    @if(auth()->check())
                        <div class="info-grid">
                            <div class="info-label">اسم المستخدم:</div>
                            <div class="info-value">{{ auth()->user()->name }}</div>
                            
                            <div class="info-label">ID المستخدم:</div>
                            <div class="info-value">{{ auth()->id() }}</div>
                            
                            <div class="info-label">البريد الإلكتروني:</div>
                            <div class="info-value">{{ auth()->user()->email }}</div>
                            
                            <div class="info-label">ID المستأجر:</div>
                            <div class="info-value">{{ auth()->user()->tenant_id ?? 'غير محدد' }}</div>
                            
                            <div class="info-label">الدور:</div>
                            <div class="info-value">{{ auth()->user()->role ?? 'غير محدد' }}</div>
                        </div>
                    @else
                        <p>المستخدم غير مسجل دخول</p>
                    @endif
                </div>
            </div>

            <!-- معلومات النظام -->
            <div class="section">
                <div class="section-header">⚙️ معلومات النظام</div>
                <div class="section-content">
                    <div class="info-grid">
                        <div class="info-label">إصدار PHP:</div>
                        <div class="info-value">{{ PHP_VERSION }}</div>
                        
                        <div class="info-label">إصدار Laravel:</div>
                        <div class="info-value">{{ app()->version() }}</div>
                        
                        <div class="info-label">البيئة:</div>
                        <div class="info-value">{{ app()->environment() }}</div>
                        
                        <div class="info-label">وضع التشخيص:</div>
                        <div class="info-value">{{ config('app.debug') ? 'مفعل' : 'معطل' }}</div>
                        
                        <div class="info-label">المنطقة الزمنية:</div>
                        <div class="info-value">{{ config('app.timezone') }}</div>
                        
                        <div class="info-label">الذاكرة المستخدمة:</div>
                        <div class="info-value">{{ round(memory_get_usage(true) / 1024 / 1024, 2) }} MB</div>
                    </div>
                </div>
            </div>

            @if(isset($exception))
            <!-- تتبع المكدس -->
            <div class="section">
                <div class="section-header">📚 تتبع المكدس (Stack Trace)</div>
                <div class="section-content">
                    <div class="stack-trace">{{ $exception->getTraceAsString() }}</div>
                </div>
            </div>
            @endif

            <!-- الإجراءات -->
            <div class="actions">
                <a href="{{ url()->previous() }}" class="btn btn-secondary">العودة للصفحة السابقة</a>
                <a href="{{ route('tenant.dashboard') }}" class="btn">لوحة التحكم</a>
                <button onclick="location.reload()" class="btn btn-secondary">إعادة المحاولة</button>
                <button onclick="copyErrorInfo()" class="btn">نسخ معلومات الخطأ</button>
            </div>
        </div>
    </div>

    <script>
        function copyErrorInfo() {
            const errorInfo = `
خطأ في النظام - MaxCon ERP
================================
@if(isset($exception))
نوع الخطأ: {{ get_class($exception) }}
رسالة الخطأ: {{ $exception->getMessage() }}
الملف: {{ $exception->getFile() }}
السطر: {{ $exception->getLine() }}
@endif
الرابط: {{ request()->fullUrl() }}
طريقة الطلب: {{ request()->method() }}
الوقت: {{ now()->format('Y-m-d H:i:s T') }}
@if(auth()->check())
المستخدم: {{ auth()->user()->name }} (ID: {{ auth()->id() }})
المستأجر: {{ auth()->user()->tenant_id ?? 'غير محدد' }}
@endif
إصدار PHP: {{ PHP_VERSION }}
إصدار Laravel: {{ app()->version() }}
البيئة: {{ app()->environment() }}
            `.trim();

            navigator.clipboard.writeText(errorInfo).then(function() {
                alert('تم نسخ معلومات الخطأ إلى الحافظة');
            }, function() {
                alert('فشل في نسخ المعلومات');
            });
        }
    </script>
</body>
</html>
