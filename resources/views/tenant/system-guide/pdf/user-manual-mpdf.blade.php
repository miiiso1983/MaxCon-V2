<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>دليل المستخدم - MaxCon ERP</title>
    <style>
        @page {
            margin: 15mm;
            margin-header: 9mm;
            margin-footer: 9mm;
            odd-header-name: html_myHeader1;
            even-header-name: html_myHeader2;
            odd-footer-name: html_myFooter1;
            even-footer-name: html_myFooter2;
        }

        body {
            font-family: 'amiri', 'noto', 'dejavusans', sans-serif;
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            height: 250mm;
            display: table-cell;
            vertical-align: middle;
        }

        .cover-title {
            font-size: 36pt;
            font-weight: bold;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .cover-subtitle {
            font-size: 24pt;
            margin-bottom: 30px;
            opacity: 0.9;
        }

        .cover-version {
            font-size: 14pt;
            margin-top: 40px;
            opacity: 0.8;
        }

        .chapter {
            page-break-before: always;
            margin-bottom: 30px;
        }

        .chapter-title {
            font-size: 20pt;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 3px solid #667eea;
        }

        .section-title {
            font-size: 16pt;
            font-weight: bold;
            color: #2d3748;
            margin: 20px 0 10px 0;
        }

        .subsection-title {
            font-size: 14pt;
            font-weight: bold;
            color: #4a5568;
            margin: 15px 0 8px 0;
        }

        .content-text {
            font-size: 12pt;
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
            font-size: 11pt;
            line-height: 1.6;
        }

        .warning-box {
            background-color: #fef5e7;
            border: 2px solid #f6ad55;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
        }

        .warning-title {
            font-weight: bold;
            color: #c05621;
            margin-bottom: 8px;
        }

        .info-box {
            background-color: #ebf8ff;
            border: 2px solid #63b3ed;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
        }

        .info-title {
            font-weight: bold;
            color: #2b6cb0;
            margin-bottom: 8px;
        }

        .toc {
            page-break-after: always;
        }

        .toc-title {
            font-size: 18pt;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 20px;
            text-align: center;
        }

        .toc-item {
            margin-bottom: 8px;
            font-size: 12pt;
        }

        .toc-chapter {
            font-weight: bold;
            margin-top: 15px;
        }

        .toc-section {
            margin-right: 20px;
        }

        .user-type-card {
            background-color: #f7fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 15px;
            margin: 15px 0;
        }

        .user-type-title {
            font-size: 14pt;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .faq-item {
            margin-bottom: 20px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 8px;
        }

        .faq-question {
            font-weight: bold;
            color: #2d3748;
            margin-bottom: 8px;
        }

        .faq-answer {
            color: #4a5568;
            line-height: 1.6;
        }

        .page-number {
            text-align: center;
            font-size: 10pt;
            color: #718096;
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
    <!-- Headers and Footers -->
    <htmlpageheader name="myHeader1" style="display:none">
        <div class="header-content">
            <table width="100%">
                <tr>
                    <td width="50%" style="text-align: right;">دليل المستخدم - MaxCon ERP</td>
                    <td width="50%" style="text-align: left;">{{ date('Y/m/d') }}</td>
                </tr>
            </table>
        </div>
    </htmlpageheader>

    <htmlpageheader name="myHeader2" style="display:none">
        <div class="header-content">
            <table width="100%">
                <tr>
                    <td width="50%" style="text-align: right;">{{ date('Y/m/d') }}</td>
                    <td width="50%" style="text-align: left;">دليل المستخدم - MaxCon ERP</td>
                </tr>
            </table>
        </div>
    </htmlpageheader>

    <htmlpagefooter name="myFooter1" style="display:none">
        <div class="footer-content">
            <table width="100%">
                <tr>
                    <td width="33%" style="text-align: right;">MaxCon ERP System</td>
                    <td width="33%" style="text-align: center;">الصفحة {PAGENO} من {nbpg}</td>
                    <td width="33%" style="text-align: left;">www.maxcon.app</td>
                </tr>
            </table>
        </div>
    </htmlpagefooter>

    <htmlpagefooter name="myFooter2" style="display:none">
        <div class="footer-content">
            <table width="100%">
                <tr>
                    <td width="33%" style="text-align: right;">www.maxcon.app</td>
                    <td width="33%" style="text-align: center;">الصفحة {PAGENO} من {nbpg}</td>
                    <td width="33%" style="text-align: left;">MaxCon ERP System</td>
                </tr>
            </table>
        </div>
    </htmlpagefooter>

    <!-- Cover Page -->
    <div class="cover-page">
        <div class="cover-title">دليل المستخدم</div>
        <div class="cover-subtitle">نظام MaxCon ERP</div>
        <div style="font-size: 16pt; margin: 20px 0;">
            نظام إدارة موارد المؤسسات الشامل
        </div>
        <div style="font-size: 14pt; margin: 20px 0;">
            الحل المتكامل لإدارة الأعمال الصيدلانية
        </div>
        <div class="cover-version">
            الإصدار 2.0 - {{ date('Y/m/d') }}
        </div>
        <div style="font-size: 12pt; margin-top: 40px; opacity: 0.8;">
            © {{ date('Y') }} MaxCon ERP - جميع الحقوق محفوظة
        </div>
    </div>

    <!-- Table of Contents -->
    <div class="toc">
        <div class="toc-title">فهرس المحتويات</div>
        
        <div class="toc-item toc-chapter">الفصل الأول: مقدمة عن النظام</div>
        <div class="toc-item toc-section">ما هو نظام MaxCon ERP؟</div>
        <div class="toc-item toc-section">مميزات النظام الرئيسية</div>
        
        <div class="toc-item toc-chapter">الفصل الثاني: أنواع المستخدمين</div>
        @foreach($userTypes as $userType)
        <div class="toc-item toc-section">{{ $userType['title'] }}</div>
        @endforeach
        
        <div class="toc-item toc-chapter">الفصل الثالث: وحدات النظام</div>
        @foreach($modules as $slug => $module)
        <div class="toc-item toc-section">{{ $module['name'] }}</div>
        @endforeach
        
        <div class="toc-item toc-chapter">الفصل الرابع: الأسئلة الشائعة</div>
        <div class="toc-item toc-section">أسئلة عامة حول النظام</div>
        <div class="toc-item toc-section">أسئلة تقنية</div>
    </div>

    <!-- Chapter 1: Introduction -->
    <div class="chapter">
        <div class="chapter-title">الفصل الأول: مقدمة عن النظام</div>
        
        <div class="section-title">ما هو نظام MaxCon ERP؟</div>
        <div class="content-text">
            MaxCon ERP هو نظام إدارة موارد المؤسسات (Enterprise Resource Planning) متكامل وشامل، 
            مصمم خصيصاً للشركات الصيدلانية والطبية في العراق والمنطقة العربية. يهدف النظام إلى توحيد 
            وأتمتة جميع العمليات التجارية في مؤسستك من خلال منصة واحدة موحدة تدعم اللغة العربية بالكامل.
        </div>
        
        <div class="content-text">
            يغطي النظام جميع جوانب إدارة الأعمال بدءاً من إدارة المبيعات والمخزون، مروراً بالمحاسبة والموارد البشرية، 
            وصولاً إلى التحليلات المتقدمة والذكاء الاصطناعي. كما يتضمن وحدات متخصصة للشؤون التنظيمية 
            والامتثال للمعايير الصيدلانية المحلية والدولية.
        </div>

        <div class="info-box">
            <div class="info-title">لماذا MaxCon ERP؟</div>
            <div>
                تم تطوير النظام بناءً على خبرة عملية في السوق العراقي والعربي، مع مراعاة المتطلبات المحلية 
                والتحديات الخاصة بالقطاع الصيدلاني. النظام يدعم العملة العراقية والضرائب المحلية ويتكامل 
                مع الأنظمة الحكومية المطلوبة.
            </div>
        </div>
        
        <div class="section-title">المميزات الرئيسية للنظام</div>
        @if(isset($systemFeatures))
        <ul class="feature-list">
            @foreach($systemFeatures as $feature)
            <li>{{ $feature }}</li>
            @endforeach
        </ul>
        @endif
    </div>

    <!-- Chapter 2: User Types -->
    <div class="chapter">
        <div class="chapter-title">الفصل الثاني: أنواع المستخدمين</div>

        <div class="content-text">
            يدعم نظام MaxCon ERP أنواعاً مختلفة من المستخدمين، كل نوع له صلاحيات ومسؤوليات محددة
            تتناسب مع دوره في المؤسسة. هذا التصميم يضمن الأمان والكفاءة في استخدام النظام.
        </div>

        @foreach($userTypes as $userType)
        <div class="user-type-card">
            <div class="user-type-title" style="color: {{ $userType['color'] }};">{{ $userType['title'] }}</div>
            <div class="content-text">{{ $userType['description'] }}</div>

            <div class="subsection-title">الصلاحيات الرئيسية:</div>
            <ul class="feature-list">
                @foreach($userType['permissions'] as $permission)
                <li>{{ $permission }}</li>
                @endforeach
            </ul>
        </div>
        @endforeach
    </div>

    <!-- Chapter 3: System Modules -->
    <div class="chapter">
        <div class="chapter-title">الفصل الثالث: وحدات النظام</div>

        <div class="content-text">
            يتكون نظام MaxCon ERP من وحدات متكاملة تغطي جميع جوانب إدارة الأعمال. كل وحدة مصممة
            لتعمل بشكل مستقل أو متكامل مع الوحدات الأخرى حسب احتياجات مؤسستك.
        </div>

        @foreach($modules as $slug => $module)
        <div class="section-title">{{ $module['name'] }}</div>
        <div class="content-text">{{ $module['description'] }}</div>

        <div class="subsection-title">المميزات الرئيسية:</div>
        <ul class="feature-list">
            @foreach($module['features'] as $feature)
            <li>{{ $feature }}</li>
            @endforeach
        </ul>

        <div class="info-box">
            <div class="info-title">معلومات الوحدة:</div>
            <div>
                <strong>مستوى الصعوبة:</strong> {{ $module['difficulty'] }}<br>
                <strong>مدة الفيديو التعليمي:</strong> {{ $module['video_duration'] }}<br>
                <strong>عدد الميزات:</strong> {{ count($module['features']) }} ميزة
            </div>
        </div>
        @endforeach
    </div>

    <!-- Chapter 4: FAQ -->
    <div class="chapter">
        <div class="chapter-title">الفصل الرابع: الأسئلة الشائعة</div>

        <div class="content-text">
            فيما يلي مجموعة من الأسئلة الأكثر شيوعاً حول استخدام نظام MaxCon ERP مع إجاباتها التفصيلية.
        </div>

        @foreach($faqs as $category => $categoryFaqs)
        <div class="section-title">{{ $category }}</div>

        @foreach($categoryFaqs as $faq)
        <div class="faq-item">
            <div class="faq-question">س: {{ $faq['question'] }}</div>
            <div class="faq-answer">ج: {{ $faq['answer'] }}</div>
        </div>
        @endforeach
        @endforeach
    </div>

    <!-- Chapter 5: Contact and Support -->
    <div class="chapter">
        <div class="chapter-title">الفصل الخامس: الدعم والتواصل</div>

        <div class="section-title">كيفية الحصول على الدعم</div>
        <div class="content-text">
            فريق دعم MaxCon ERP متاح لمساعدتك في جميع الأوقات. يمكنك التواصل معنا من خلال القنوات التالية:
        </div>

        <ul class="feature-list">
            <li><strong>الموقع الإلكتروني:</strong> www.maxcon.app</li>
            <li><strong>البريد الإلكتروني:</strong> support@maxcon.app</li>
            <li><strong>الهاتف:</strong> +964 XXX XXX XXXX</li>
            <li><strong>الدعم المباشر:</strong> متاح من خلال النظام 24/7</li>
        </ul>

        <div class="section-title">التدريب والتأهيل</div>
        <div class="content-text">
            نوفر برامج تدريبية شاملة لضمان الاستفادة القصوى من النظام:
        </div>

        <ul class="feature-list">
            <li>جلسات تدريبية مباشرة عبر الإنترنت</li>
            <li>فيديوهات تعليمية تفاعلية</li>
            <li>دليل المستخدم التفصيلي</li>
            <li>دعم فني مخصص لكل عميل</li>
        </ul>

        <div class="warning-box">
            <div class="warning-title">ملاحظة مهمة:</div>
            <div>
                هذا الدليل يتم تحديثه بانتظام مع كل إصدار جديد من النظام. تأكد من تحميل أحدث نسخة
                من الموقع الرسمي للحصول على أحدث المعلومات والميزات.
            </div>
        </div>
    </div>

    <!-- Final Page -->
    <div class="chapter">
        <div style="text-align: center; padding: 50px 20px;">
            <div style="font-size: 24pt; font-weight: bold; color: #667eea; margin-bottom: 20px;">
                شكراً لاختياركم MaxCon ERP
            </div>
            <div style="font-size: 14pt; line-height: 1.8; color: #4a5568;">
                نحن فخورون بثقتكم في حلولنا التقنية<br>
                ونتطلع لمساعدتكم في تحقيق أهدافكم التجارية<br><br>

                <strong>فريق MaxCon ERP</strong><br>
                {{ date('Y') }}
            </div>
        </div>
    </div>
</body>
</html>
