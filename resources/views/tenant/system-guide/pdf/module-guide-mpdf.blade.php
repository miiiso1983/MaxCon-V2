<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>دليل وحدة {{ $module['name'] }} - MaxCon ERP</title>
    <style>
        :root {
            --module-color: {{ $module['color'] ?? '#667eea' }};
        }

        @page {
            margin: 15mm;
            margin-header: 9mm;
            margin-footer: 9mm;
            odd-header-name: html_myHeader1;
            odd-footer-name: html_myFooter1;
        }

        body {
            font-family: 'dejavusans', sans-serif;
            font-size: 12pt;
            line-height: 1.6;
            color: #333;
            direction: rtl;
            text-align: right;
        }

        .cover-page {
            text-align: center;
            padding: 50px 20px;
            page-break-after: always;
            background: linear-gradient(135deg, var(--module-color) 0%, #764ba2 100%);
            color: white;
            height: 250mm;
            display: table-cell;
            vertical-align: middle;
        }

        .cover-title {
            font-size: 32pt;
            font-weight: bold;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .cover-subtitle {
            font-size: 20pt;
            margin-bottom: 30px;
            opacity: 0.9;
        }

        .module-icon {
            font-size: 48pt;
            margin-bottom: 20px;
        }

        .chapter {
            page-break-before: always;
            margin-bottom: 30px;
        }

        .chapter-title {
            font-size: 18pt;
            font-weight: bold;
            color: var(--module-color);
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 3px solid var(--module-color);
        }

        .section-title {
            font-size: 14pt;
            font-weight: bold;
            color: #2d3748;
            margin: 20px 0 10px 0;
        }

        .subsection-title {
            font-size: 12pt;
            font-weight: bold;
            color: #4a5568;
            margin: 15px 0 8px 0;
        }

        .content-text {
            font-size: 11pt;
            line-height: 1.8;
            margin-bottom: 15px;
            text-align: justify;
        }

        .feature-list {
            margin: 10px 0 20px 20px;
            padding: 0;
        }

        .feature-list li {
            margin-bottom: 8px;
            font-size: 10pt;
            line-height: 1.6;
        }

        .step-box {
            background-color: #f7fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 15px;
            margin: 15px 0;
        }

        .step-number {
            background-color: var(--module-color);
            color: white;
            border-radius: 50%;
            width: 25px;
            height: 25px;
            display: inline-block;
            text-align: center;
            line-height: 25px;
            font-weight: bold;
            margin-left: 10px;
        }

        .warning-box {
            background-color: #fef5e7;
            border: 2px solid #f6ad55;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
        }

        .info-box {
            background-color: #ebf8ff;
            border: 2px solid #63b3ed;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
        }

        .tip-box {
            background-color: #f0fff4;
            border: 2px solid #68d391;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
        }

        .difficulty-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 10pt;
            font-weight: bold;
            margin: 10px 0;
        }

        .difficulty-easy {
            background-color: #c6f6d5;
            color: #22543d;
        }

        .difficulty-medium {
            background-color: #fef5e7;
            color: #c05621;
        }

        .difficulty-hard {
            background-color: #fed7d7;
            color: #742a2a;
        }

        .header-content {
            font-size: 10pt;
            color: #718096;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 5px;
        }

        .footer-content {
            font-size: 10pt;
            color: #718096;
            border-top: 1px solid #e2e8f0;
            padding-top: 5px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <htmlpageheader name="myHeader1" style="display:none">
        <div class="header-content">
            <table width="100%">
                <tr>
                    <td width="50%" style="text-align: right;">دليل وحدة {{ $module['name'] }}</td>
                    <td width="50%" style="text-align: left;">MaxCon ERP</td>
                </tr>
            </table>
        </div>
    </htmlpageheader>

    <!-- Footer -->
    <htmlpagefooter name="myFooter1" style="display:none">
        <div class="footer-content">
            <table width="100%">
                <tr>
                    <td width="33%" style="text-align: right;">{{ $module['name'] }}</td>
                    <td width="33%" style="text-align: center;">الصفحة {PAGENO} من {nbpg}</td>
                    <td width="33%" style="text-align: left;">www.maxcon.app</td>
                </tr>
            </table>
        </div>
    </htmlpagefooter>

    <!-- Cover Page -->
    <div class="cover-page">
        <div class="module-icon">{{ $module['icon'] ?? '📊' }}</div>
        <div class="cover-title">دليل وحدة {{ $module['name'] }}</div>
        <div class="cover-subtitle">MaxCon ERP System</div>
        
        <div style="font-size: 14pt; margin: 20px 0;">
            {{ $module['description'] }}
        </div>
        
        <div class="difficulty-badge difficulty-{{ strtolower($module['difficulty'] ?? 'medium') }}">
            مستوى الصعوبة: {{ $module['difficulty'] ?? 'متوسط' }}
        </div>
        
        <div style="font-size: 12pt; margin-top: 40px; opacity: 0.8;">
            مدة الفيديو التعليمي: {{ $module['video_duration'] ?? '5-7 دقائق' }}
        </div>
        
        <div style="font-size: 12pt; margin-top: 20px; opacity: 0.8;">
            تاريخ الإصدار: {{ date('Y/m/d') }}
        </div>
    </div>

    <!-- Chapter 1: Overview -->
    <div class="chapter">
        <div class="chapter-title">نظرة عامة على الوحدة</div>
        
        <div class="section-title">ما هي وحدة {{ $module['name'] }}؟</div>
        <div class="content-text">
            {{ $module['description'] }}
        </div>
        
        <div class="info-box">
            <strong>معلومات الوحدة:</strong><br>
            <strong>الاسم:</strong> {{ $module['name'] }}<br>
            <strong>النوع:</strong> {{ $module['type'] ?? 'وحدة أساسية' }}<br>
            <strong>مستوى الصعوبة:</strong> {{ $module['difficulty'] ?? 'متوسط' }}<br>
            <strong>عدد الميزات:</strong> {{ count($module['features']) }} ميزة
        </div>
        
        <div class="section-title">المميزات الرئيسية</div>
        <ul class="feature-list">
            @foreach($module['features'] as $feature)
            <li>{{ $feature }}</li>
            @endforeach
        </ul>
    </div>

    <!-- Chapter 2: Getting Started -->
    <div class="chapter">
        <div class="chapter-title">البدء مع الوحدة</div>
        
        <div class="section-title">الوصول للوحدة</div>
        <div class="step-box">
            <span class="step-number">1</span>
            <strong>تسجيل الدخول:</strong> قم بتسجيل الدخول إلى نظام MaxCon ERP
        </div>
        
        <div class="step-box">
            <span class="step-number">2</span>
            <strong>التنقل:</strong> من القائمة الجانبية، اختر "{{ $module['name'] }}"
        </div>
        
        <div class="step-box">
            <span class="step-number">3</span>
            <strong>البدء:</strong> ستظهر لك الصفحة الرئيسية للوحدة مع جميع الخيارات المتاحة
        </div>
        
        <div class="tip-box">
            <strong>نصيحة:</strong> إذا كانت هذه المرة الأولى التي تستخدم فيها الوحدة، 
            ننصح بمشاهدة الفيديو التعليمي أولاً للحصول على فهم شامل.
        </div>
    </div>

    <!-- Chapter 3: Main Features -->
    <div class="chapter">
        <div class="chapter-title">الميزات التفصيلية</div>
        
        @foreach($module['features'] as $index => $feature)
        <div class="section-title">{{ $index + 1 }}. {{ $feature }}</div>
        <div class="content-text">
            هذه الميزة تتيح لك {{ $feature }}. يمكنك الوصول إليها من خلال الواجهة الرئيسية للوحدة.
        </div>
        
        <div class="subsection-title">كيفية الاستخدام:</div>
        <div class="step-box">
            <span class="step-number">1</span>
            انتقل إلى قسم {{ $feature }} في الوحدة
        </div>
        <div class="step-box">
            <span class="step-number">2</span>
            اتبع التعليمات المعروضة على الشاشة
        </div>
        <div class="step-box">
            <span class="step-number">3</span>
            احفظ التغييرات عند الانتهاء
        </div>
        @endforeach
    </div>

    <!-- Chapter 4: Tips and Best Practices -->
    <div class="chapter">
        <div class="chapter-title">نصائح وأفضل الممارسات</div>
        
        <div class="section-title">نصائح للاستخدام الأمثل</div>
        <ul class="feature-list">
            <li>تأكد من حفظ البيانات بانتظام</li>
            <li>استخدم خاصية البحث للعثور على البيانات بسرعة</li>
            <li>راجع التقارير بشكل دوري لمتابعة الأداء</li>
            <li>استفد من خاصية التصدير لحفظ نسخ احتياطية</li>
        </ul>
        
        <div class="warning-box">
            <strong>تحذير مهم:</strong><br>
            تأكد من وجود صلاحيات مناسبة قبل إجراء تعديلات على البيانات الحساسة.
            راجع مدير النظام إذا كنت بحاجة لصلاحيات إضافية.
        </div>
        
        <div class="section-title">الأخطاء الشائعة وحلولها</div>
        <div class="content-text">
            فيما يلي بعض الأخطاء الشائعة التي قد تواجهها وطرق حلها:
        </div>
        
        <div class="step-box">
            <strong>مشكلة:</strong> لا يمكن حفظ البيانات<br>
            <strong>الحل:</strong> تحقق من اتصال الإنترنت وصلاحيات المستخدم
        </div>
        
        <div class="step-box">
            <strong>مشكلة:</strong> البيانات لا تظهر<br>
            <strong>الحل:</strong> تأكد من تحديد الفترة الزمنية الصحيحة في الفلاتر
        </div>
    </div>

    <!-- Final Page -->
    <div class="chapter">
        <div style="text-align: center; padding: 50px 20px;">
            <div style="font-size: 20pt; font-weight: bold; color: var(--module-color); margin-bottom: 20px;">
                انتهى دليل وحدة {{ $module['name'] }}
            </div>
            <div style="font-size: 12pt; line-height: 1.8; color: #4a5568;">
                نأمل أن يكون هذا الدليل مفيداً في استخدام الوحدة<br>
                للمزيد من المساعدة، تواصل مع فريق الدعم<br><br>
                
                <strong>MaxCon ERP Support Team</strong><br>
                support@maxcon.app<br>
                www.maxcon.app
            </div>
        </div>
    </div>
</body>
</html>
