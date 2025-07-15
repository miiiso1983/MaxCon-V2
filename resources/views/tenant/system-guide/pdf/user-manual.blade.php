<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>دليل المستخدم - MaxCon ERP</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&display=swap');
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Amiri', serif;
            line-height: 1.6;
            color: #333;
            direction: rtl;
            text-align: right;
        }

        .cover-page {
            text-align: center;
            padding: 100px 50px;
            page-break-after: always;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .cover-title {
            font-size: 48px;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .cover-subtitle {
            font-size: 24px;
            margin-bottom: 40px;
            opacity: 0.9;
        }

        .cover-version {
            font-size: 16px;
            opacity: 0.8;
            margin-top: 40px;
        }

        .toc {
            page-break-after: always;
            padding: 50px;
        }

        .toc-title {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 30px;
            color: #2c5aa0;
            border-bottom: 3px solid #2c5aa0;
            padding-bottom: 10px;
        }

        .toc-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px dotted #ccc;
        }

        .chapter {
            page-break-before: always;
            padding: 50px;
        }

        .chapter-title {
            font-size: 28px;
            font-weight: 700;
            color: #2c5aa0;
            margin-bottom: 20px;
            border-bottom: 2px solid #2c5aa0;
            padding-bottom: 10px;
        }

        .section-title {
            font-size: 22px;
            font-weight: 700;
            color: #1e293b;
            margin: 30px 0 15px 0;
        }

        .subsection-title {
            font-size: 18px;
            font-weight: 600;
            color: #374151;
            margin: 20px 0 10px 0;
        }

        .content-text {
            font-size: 14px;
            line-height: 1.8;
            margin-bottom: 15px;
            text-align: justify;
        }

        .feature-list {
            list-style: none;
            padding: 0;
            margin: 15px 0;
        }

        .feature-list li {
            padding: 8px 0;
            padding-right: 20px;
            position: relative;
        }

        .feature-list li::before {
            content: '✓';
            position: absolute;
            right: 0;
            color: #10b981;
            font-weight: bold;
        }

        .step-list {
            counter-reset: step-counter;
            list-style: none;
            padding: 0;
            margin: 15px 0;
        }

        .step-list li {
            counter-increment: step-counter;
            padding: 10px 0;
            padding-right: 30px;
            position: relative;
            border-bottom: 1px solid #f1f5f9;
        }

        .step-list li::before {
            content: counter(step-counter);
            position: absolute;
            right: 0;
            background: #3b82f6;
            color: white;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: bold;
        }

        .module-box {
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
            background: #f8fafc;
        }

        .module-title {
            font-size: 18px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 10px;
        }

        .warning-box {
            background: #fef3c7;
            border: 2px solid #f59e0b;
            border-radius: 8px;
            padding: 15px;
            margin: 15px 0;
        }

        .warning-title {
            font-weight: 700;
            color: #92400e;
            margin-bottom: 5px;
        }

        .info-box {
            background: #e0f2fe;
            border: 2px solid #0284c7;
            border-radius: 8px;
            padding: 15px;
            margin: 15px 0;
        }

        .info-title {
            font-weight: 700;
            color: #0c4a6e;
            margin-bottom: 5px;
        }

        .faq-item {
            margin: 20px 0;
            padding: 15px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
        }

        .faq-question {
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 8px;
        }

        .faq-answer {
            color: #475569;
            line-height: 1.6;
        }

        .footer {
            position: fixed;
            bottom: 30px;
            left: 50px;
            right: 50px;
            text-align: center;
            font-size: 12px;
            color: #64748b;
            border-top: 1px solid #e2e8f0;
            padding-top: 10px;
        }

        @page {
            margin: 0;
        }
    </style>
</head>
<body>
    <!-- Cover Page -->
    <div class="cover-page">
        <div class="cover-title">دليل المستخدم</div>
        <div class="cover-subtitle">نظام MaxCon ERP</div>
        <div style="font-size: 18px; margin: 20px 0;">
            نظام إدارة موارد المؤسسات الشامل
        </div>
        <div class="cover-version">
            الإصدار 2.0 - {{ date('Y/m/d') }}
        </div>
    </div>

    <!-- Table of Contents -->
    <div class="toc">
        <div class="toc-title">فهرس المحتويات</div>
        
        <div class="toc-item">
            <span>الفصل الأول: مقدمة عن النظام</span>
            <span>3</span>
        </div>
        <div class="toc-item">
            <span>الفصل الثاني: المميزات الرئيسية</span>
            <span>5</span>
        </div>
        <div class="toc-item">
            <span>الفصل الثالث: أنواع المستخدمين</span>
            <span>7</span>
        </div>
        <div class="toc-item">
            <span>الفصل الرابع: وحدات النظام</span>
            <span>9</span>
        </div>
        <div class="toc-item">
            <span>الفصل الخامس: الأسئلة الشائعة</span>
            <span>25</span>
        </div>
        <div class="toc-item">
            <span>الفصل السادس: الدعم الفني</span>
            <span>35</span>
        </div>
    </div>

    <!-- Chapter 1: Introduction -->
    <div class="chapter">
        <div class="chapter-title">الفصل الأول: مقدمة عن النظام</div>
        
        <div class="section-title">ما هو نظام MaxCon ERP؟</div>
        <div class="content-text">
            MaxCon ERP هو نظام إدارة موارد المؤسسات (Enterprise Resource Planning) متكامل وشامل، 
            يهدف إلى توحيد وأتمتة جميع العمليات التجارية في مؤسستك من خلال منصة واحدة موحدة.
        </div>
        
        <div class="content-text">
            يغطي النظام جميع جوانب إدارة الأعمال بدءاً من إدارة المبيعات والمخزون، مروراً بالمحاسبة والموارد البشرية، 
            وصولاً إلى التحليلات المتقدمة والذكاء الاصطناعي.
        </div>

        <div class="info-box">
            <div class="info-title">لماذا MaxCon ERP؟</div>
            <div>
                مصمم خصيصاً للسوق العراقي مع دعم كامل للغة العربية، الدينار العراقي، 
                والقوانين المحلية، مما يجعله الخيار الأمثل للشركات العراقية.
            </div>
        </div>

        <div class="section-title">أهداف النظام</div>
        <ul class="feature-list">
            <li>توحيد جميع العمليات التجارية في منصة واحدة</li>
            <li>تحسين الكفاءة وتقليل الأخطاء البشرية</li>
            <li>توفير تقارير دقيقة وتحليلات متقدمة</li>
            <li>تسهيل اتخاذ القرارات الإدارية</li>
            <li>ضمان الامتثال للقوانين المحلية</li>
        </ul>
    </div>

    <!-- Chapter 2: System Features -->
    <div class="chapter">
        <div class="chapter-title">الفصل الثاني: المميزات الرئيسية</div>
        
        @foreach($systemFeatures as $feature)
        <div class="module-box">
            <div class="module-title">{{ $feature['title'] }}</div>
            <div class="content-text">{{ $feature['description'] }}</div>
        </div>
        @endforeach
    </div>

    <!-- Chapter 3: User Types -->
    <div class="chapter">
        <div class="chapter-title">الفصل الثالث: أنواع المستخدمين</div>
        
        @foreach($userTypes as $userType)
        <div class="section-title">{{ $userType['title'] }}</div>
        <div class="content-text">{{ $userType['description'] }}</div>
        
        <div class="subsection-title">الصلاحيات الرئيسية:</div>
        <ul class="feature-list">
            @foreach($userType['permissions'] as $permission)
            <li>{{ $permission }}</li>
            @endforeach
        </ul>
        @endforeach
    </div>

    <!-- Chapter 4: System Modules -->
    <div class="chapter">
        <div class="chapter-title">الفصل الرابع: وحدات النظام</div>
        
        @foreach($modules as $slug => $module)
        <div class="section-title">{{ $module['name'] }}</div>
        <div class="content-text">{{ $module['description'] }}</div>
        
        <div class="subsection-title">المميزات الرئيسية:</div>
        <ul class="feature-list">
            @foreach($module['features'] as $feature)
            <li>{{ $feature }}</li>
            @endforeach
        </ul>

        <div class="warning-box">
            <div class="warning-title">ملاحظة مهمة:</div>
            <div>مستوى الصعوبة: {{ $module['difficulty'] }} | مدة الفيديو التعليمي: {{ $module['video_duration'] }}</div>
        </div>
        @endforeach
    </div>

    <!-- Chapter 5: FAQ -->
    <div class="chapter">
        <div class="chapter-title">الفصل الخامس: الأسئلة الشائعة</div>
        
        @foreach($faqs as $faq)
        <div class="faq-item">
            <div class="faq-question">{{ $faq['question'] }}</div>
            <div class="faq-answer">{{ $faq['answer'] }}</div>
        </div>
        @endforeach
    </div>

    <!-- Chapter 6: Support -->
    <div class="chapter">
        <div class="chapter-title">الفصل السادس: الدعم الفني</div>
        
        <div class="section-title">كيفية الحصول على المساعدة</div>
        <div class="content-text">
            فريق الدعم الفني في MaxCon متاح لمساعدتك في أي وقت. يمكنك التواصل معنا من خلال:
        </div>

        <div class="subsection-title">قنوات التواصل:</div>
        <ol class="step-list">
            <li><strong>البريد الإلكتروني:</strong> support@maxcon.app</li>
            <li><strong>الهاتف:</strong> +964-XXX-XXXX</li>
            <li><strong>الدردشة المباشرة:</strong> متاحة داخل النظام</li>
            <li><strong>نظام التذاكر:</strong> لمتابعة المشاكل التقنية</li>
        </ol>

        <div class="info-box">
            <div class="info-title">نصائح للحصول على دعم أفضل:</div>
            <ul class="feature-list">
                <li>وصف المشكلة بوضوح وتفصيل</li>
                <li>إرفاق لقطات شاشة إن أمكن</li>
                <li>ذكر الخطوات التي أدت للمشكلة</li>
                <li>تحديد الوحدة أو الصفحة المتأثرة</li>
            </ul>
        </div>

        <div class="section-title">ساعات العمل</div>
        <div class="content-text">
            فريق الدعم الفني متاح من الأحد إلى الخميس من الساعة 8:00 صباحاً حتى 6:00 مساءً (بتوقيت بغداد).
            للحالات الطارئة، يتوفر دعم على مدار 24 ساعة للعملاء المميزين.
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <div>دليل المستخدم - نظام MaxCon ERP | الإصدار 2.0 | {{ date('Y') }} © جميع الحقوق محفوظة</div>
    </div>
</body>
</html>
