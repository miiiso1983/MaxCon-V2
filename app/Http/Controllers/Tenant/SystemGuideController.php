<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * System Guide Controller
 * 
 * Handles the "How to Use the System" section
 */
class SystemGuideController extends Controller
{
    /**
     * Display the main system guide page
     */
    public function index()
    {
        $modules = $this->getSystemModules();
        $quickStats = $this->getQuickStats();
        
        return view('tenant.system-guide.index', compact('modules', 'quickStats'));
    }

    /**
     * Display introduction page
     */
    public function introduction()
    {
        $systemFeatures = $this->getSystemFeatures();
        $userTypes = $this->getUserTypes();
        
        return view('tenant.system-guide.introduction', compact('systemFeatures', 'userTypes'));
    }

    /**
     * Display module-specific guide
     */
    public function module($moduleSlug)
    {
        $module = $this->getModuleDetails($moduleSlug);
        
        if (!$module) {
            abort(404, 'الوحدة غير موجودة');
        }
        
        $relatedModules = $this->getRelatedModules($moduleSlug);
        
        return view('tenant.system-guide.module', compact('module', 'relatedModules'));
    }

    /**
     * Display FAQ page
     */
    public function faq()
    {
        $faqs = $this->getFAQs();
        $categories = $this->getFAQCategories();
        
        return view('tenant.system-guide.faq', compact('faqs', 'categories'));
    }

    /**
     * Display video tutorials
     */
    public function videos($moduleSlug = null)
    {
        $videos = $this->getVideos($moduleSlug);
        $modules = $this->getSystemModules();
        
        return view('tenant.system-guide.videos', compact('videos', 'modules', 'moduleSlug'));
    }

    /**
     * Download user manual PDF
     */
    public function downloadManual()
    {
        $pdfPath = $this->generateUserManualPDF();
        
        return response()->download($pdfPath, 'دليل_المستخدم_MaxCon.pdf');
    }

    /**
     * Start interactive tour
     */
    public function startTour(Request $request)
    {
        $tourType = $request->get('type', 'general');
        $tourSteps = $this->getTourSteps($tourType);
        
        return response()->json([
            'success' => true,
            'steps' => $tourSteps
        ]);
    }

    /**
     * Get system modules
     */
    private function getSystemModules()
    {
        return [
            'sales' => [
                'name' => 'إدارة المبيعات',
                'icon' => 'fas fa-shopping-bag',
                'description' => 'إدارة العملاء، الطلبات، الفواتير، والمرتجعات',
                'color' => '#10b981',
                'features' => [
                    'إدارة العملاء والموردين',
                    'إنشاء وإدارة الطلبات',
                    'إصدار الفواتير الإلكترونية',
                    'معالجة المرتجعات',
                    'تتبع المدفوعات'
                ],
                'video_duration' => '8:30',
                'difficulty' => 'مبتدئ'
            ],
            'inventory' => [
                'name' => 'إدارة المخزون',
                'icon' => 'fas fa-warehouse',
                'description' => 'تتبع المنتجات، المستودعات، وحركات المخزون',
                'color' => '#3b82f6',
                'features' => [
                    'كتالوج المنتجات',
                    'إدارة المستودعات',
                    'تتبع حركات المخزون',
                    'الجرد الدوري',
                    'تنبيهات المخزون'
                ],
                'video_duration' => '12:15',
                'difficulty' => 'متوسط'
            ],
            'purchasing' => [
                'name' => 'إدارة المشتريات',
                'icon' => 'fas fa-truck',
                'description' => 'إدارة الموردين، طلبات الشراء، والعقود',
                'color' => '#8b5cf6',
                'features' => [
                    'إدارة الموردين',
                    'طلبات الشراء',
                    'أوامر الشراء',
                    'عروض الأسعار',
                    'العقود والاتفاقيات'
                ],
                'video_duration' => '10:45',
                'difficulty' => 'متوسط'
            ],
            'targets' => [
                'name' => 'أهداف البيع',
                'icon' => 'fas fa-bullseye',
                'description' => 'تحديد ومتابعة أهداف المبيعات والأداء',
                'color' => '#f59e0b',
                'features' => [
                    'تحديد الأهداف',
                    'تتبع التقدم',
                    'تقارير الأداء',
                    'الإشعارات التلقائية',
                    'لوحة تحكم الأهداف'
                ],
                'video_duration' => '6:20',
                'difficulty' => 'مبتدئ'
            ],
            'accounting' => [
                'name' => 'النظام المحاسبي',
                'icon' => 'fas fa-calculator',
                'description' => 'إدارة الحسابات، القيود، والتقارير المالية',
                'color' => '#ef4444',
                'features' => [
                    'دليل الحسابات',
                    'القيود المحاسبية',
                    'التقارير المالية',
                    'مراكز التكلفة',
                    'الميزانيات'
                ],
                'video_duration' => '15:30',
                'difficulty' => 'متقدم'
            ],
            'hr' => [
                'name' => 'الموارد البشرية',
                'icon' => 'fas fa-users',
                'description' => 'إدارة الموظفين، الحضور، والرواتب',
                'color' => '#06b6d4',
                'features' => [
                    'ملفات الموظفين',
                    'الحضور والانصراف',
                    'إدارة الإجازات',
                    'كشف الرواتب',
                    'التقييمات'
                ],
                'video_duration' => '11:10',
                'difficulty' => 'متوسط'
            ],
            'regulatory' => [
                'name' => 'الشؤون التنظيمية',
                'icon' => 'fas fa-shield-alt',
                'description' => 'إدارة التراخيص، التفتيشات، والامتثال التنظيمي',
                'color' => '#dc2626',
                'features' => [
                    'تسجيل الشركات والتراخيص',
                    'إدارة التفتيشات التنظيمية',
                    'شهادات الجودة والامتثال',
                    'تتبع الاختبارات المعملية',
                    'إدارة سحب المنتجات',
                    'التقارير التنظيمية'
                ],
                'video_duration' => '14:25',
                'difficulty' => 'متقدم'
            ],
            'analytics' => [
                'name' => 'التحليلات والذكاء الاصطناعي',
                'icon' => 'fas fa-brain',
                'description' => 'تحليل البيانات والتنبؤات الذكية',
                'color' => '#8b5cf6',
                'features' => [
                    'لوحات التحكم التفاعلية',
                    'التنبؤات الذكية',
                    'تحليل الاتجاهات',
                    'تقارير تنفيذية',
                    'مؤشرات الأداء'
                ],
                'video_duration' => '9:45',
                'difficulty' => 'متقدم'
            ],
            'settings' => [
                'name' => 'إعدادات النظام',
                'icon' => 'fas fa-cogs',
                'description' => 'تكوين النظام وإدارة المستخدمين',
                'color' => '#6b7280',
                'features' => [
                    'إعدادات الشركة',
                    'إدارة المستخدمين',
                    'الأدوار والصلاحيات',
                    'إعدادات التنبيهات',
                    'النسخ الاحتياطية'
                ],
                'video_duration' => '7:25',
                'difficulty' => 'متوسط'
            ]
        ];
    }

    /**
     * Get system features
     */
    private function getSystemFeatures()
    {
        return [
            'comprehensive' => [
                'title' => 'نظام شامل ومتكامل',
                'description' => 'يغطي جميع احتياجات إدارة الأعمال من المبيعات إلى المحاسبة',
                'icon' => 'fas fa-puzzle-piece'
            ],
            'arabic' => [
                'title' => 'دعم كامل للغة العربية',
                'description' => 'واجهات عربية بالكامل مع دعم الكتابة من اليمين إلى اليسار',
                'icon' => 'fas fa-language'
            ],
            'cloud' => [
                'title' => 'نظام سحابي آمن',
                'description' => 'وصول من أي مكان مع أعلى معايير الأمان والحماية',
                'icon' => 'fas fa-cloud'
            ],
            'mobile' => [
                'title' => 'متوافق مع الأجهزة المحمولة',
                'description' => 'يعمل بسلاسة على الهواتف الذكية والأجهزة اللوحية',
                'icon' => 'fas fa-mobile-alt'
            ],
            'reports' => [
                'title' => 'تقارير ذكية ومتقدمة',
                'description' => 'تقارير تفاعلية مع إمكانيات تحليل متقدمة',
                'icon' => 'fas fa-chart-line'
            ],
            'ai' => [
                'title' => 'ذكاء اصطناعي مدمج',
                'description' => 'تنبؤات ذكية وتحليلات متقدمة لاتخاذ قرارات أفضل',
                'icon' => 'fas fa-brain'
            ]
        ];
    }

    /**
     * Get user types
     */
    private function getUserTypes()
    {
        return [
            'admin' => [
                'title' => 'مدير النظام',
                'description' => 'صلاحيات كاملة لإدارة النظام والمستخدمين',
                'permissions' => ['إدارة المستخدمين', 'إعدادات النظام', 'النسخ الاحتياطية', 'التقارير الشاملة'],
                'color' => '#ef4444'
            ],
            'manager' => [
                'title' => 'المدير التنفيذي',
                'description' => 'إدارة العمليات اليومية ومراجعة التقارير',
                'permissions' => ['إدارة المبيعات', 'إدارة المخزون', 'التقارير المالية', 'متابعة الأهداف'],
                'color' => '#3b82f6'
            ],
            'sales' => [
                'title' => 'موظف المبيعات',
                'description' => 'إدارة العملاء والطلبات والفواتير',
                'permissions' => ['إدارة العملاء', 'إنشاء الطلبات', 'إصدار الفواتير', 'متابعة المدفوعات'],
                'color' => '#10b981'
            ],
            'accountant' => [
                'title' => 'المحاسب',
                'description' => 'إدارة الحسابات والقيود المالية',
                'permissions' => ['القيود المحاسبية', 'التقارير المالية', 'إدارة الحسابات', 'الميزانيات'],
                'color' => '#f59e0b'
            ],
            'warehouse' => [
                'title' => 'أمين المخزن',
                'description' => 'إدارة المخزون وحركات البضائع',
                'permissions' => ['إدارة المخزون', 'حركات البضائع', 'الجرد', 'تقارير المخزون'],
                'color' => '#8b5cf6'
            ]
        ];
    }

    /**
     * Get quick stats for dashboard
     */
    private function getQuickStats()
    {
        return [
            'total_modules' => count($this->getSystemModules()),
            'video_tutorials' => 24,
            'faq_items' => 45,
            'user_manual_pages' => 120
        ];
    }

    /**
     * Get module details
     */
    private function getModuleDetails($moduleSlug)
    {
        $modules = $this->getSystemModules();

        if (!isset($modules[$moduleSlug])) {
            return null;
        }

        $module = $modules[$moduleSlug];
        $module['slug'] = $moduleSlug;

        // Add detailed steps and screenshots
        $module['steps'] = $this->getModuleSteps($moduleSlug);
        $module['screenshots'] = $this->getModuleScreenshots($moduleSlug);
        $module['tips'] = $this->getModuleTips($moduleSlug);
        $module['common_issues'] = $this->getModuleCommonIssues($moduleSlug);

        return $module;
    }

    /**
     * Get module steps
     */
    private function getModuleSteps($moduleSlug)
    {
        $steps = [
            'sales' => [
                [
                    'title' => 'إضافة عميل جديد',
                    'description' => 'ابدأ بإضافة معلومات العميل الأساسية',
                    'steps' => [
                        'انتقل إلى قسم "إدارة المبيعات" من القائمة الجانبية',
                        'اختر "إدارة العملاء" من القائمة الفرعية',
                        'انقر على زر "إضافة عميل جديد"',
                        'املأ المعلومات المطلوبة (الاسم، الهاتف، العنوان)',
                        'احفظ البيانات بالنقر على "حفظ"'
                    ],
                    'icon' => 'fas fa-user-plus',
                    'estimated_time' => '3 دقائق'
                ],
                [
                    'title' => 'إنشاء طلب بيع',
                    'description' => 'إنشاء طلب بيع جديد للعميل',
                    'steps' => [
                        'انتقل إلى "طلبات المبيعات"',
                        'انقر على "إنشاء طلب جديد"',
                        'اختر العميل من القائمة المنسدلة',
                        'أضف المنتجات والكميات المطلوبة',
                        'راجع التفاصيل واحفظ الطلب'
                    ],
                    'icon' => 'fas fa-shopping-cart',
                    'estimated_time' => '5 دقائق'
                ],
                [
                    'title' => 'إصدار فاتورة',
                    'description' => 'تحويل طلب البيع إلى فاتورة',
                    'steps' => [
                        'افتح طلب البيع المطلوب',
                        'انقر على "إنشاء فاتورة"',
                        'راجع تفاصيل الفاتورة',
                        'أضف أي خصومات أو ضرائب',
                        'احفظ وأرسل الفاتورة للعميل'
                    ],
                    'icon' => 'fas fa-file-invoice',
                    'estimated_time' => '4 دقائق'
                ]
            ],
            'inventory' => [
                [
                    'title' => 'إضافة منتج جديد',
                    'description' => 'إضافة منتج جديد إلى كتالوج المنتجات',
                    'steps' => [
                        'انتقل إلى "إدارة المخزون" > "كتالوج المنتجات"',
                        'انقر على "إضافة منتج جديد"',
                        'املأ معلومات المنتج (الاسم، الكود، الفئة)',
                        'حدد وحدة القياس والسعر',
                        'أضف صورة للمنتج إن أمكن',
                        'احفظ المنتج'
                    ],
                    'icon' => 'fas fa-plus-circle',
                    'estimated_time' => '4 دقائق'
                ],
                [
                    'title' => 'تسجيل حركة مخزون',
                    'description' => 'تسجيل دخول أو خروج بضائع',
                    'steps' => [
                        'انتقل إلى "حركات المخزون"',
                        'اختر نوع الحركة (دخول/خروج)',
                        'حدد المنتج والكمية',
                        'أضف ملاحظات إن لزم الأمر',
                        'احفظ الحركة'
                    ],
                    'icon' => 'fas fa-exchange-alt',
                    'estimated_time' => '3 دقائق'
                ]
            ],
            'targets' => [
                [
                    'title' => 'إنشاء هدف بيع جديد',
                    'description' => 'تحديد هدف مبيعات للفريق أو المندوب',
                    'steps' => [
                        'انتقل إلى "أهداف البيع"',
                        'انقر على "إنشاء هدف جديد"',
                        'حدد نوع الهدف (منتج، مندوب، فريق)',
                        'اختر الفترة الزمنية والمبلغ المستهدف',
                        'احفظ الهدف وابدأ المتابعة'
                    ],
                    'icon' => 'fas fa-bullseye',
                    'estimated_time' => '5 دقائق'
                ]
            ],
            'regulatory' => [
                [
                    'title' => 'تسجيل شركة جديدة',
                    'description' => 'إضافة شركة جديدة إلى النظام التنظيمي',
                    'steps' => [
                        'انتقل إلى "الشؤون التنظيمية" > "تسجيل الشركات"',
                        'انقر على "إضافة شركة جديدة"',
                        'املأ معلومات الشركة (الاسم، رقم التسجيل، النوع)',
                        'أضف معلومات الترخيص وتاريخ الانتهاء',
                        'حدد حالة الامتثال والملاحظات',
                        'احفظ بيانات الشركة'
                    ],
                    'icon' => 'fas fa-building',
                    'estimated_time' => '6 دقائق'
                ],
                [
                    'title' => 'جدولة تفتيش تنظيمي',
                    'description' => 'إنشاء وجدولة تفتيش تنظيمي جديد',
                    'steps' => [
                        'انتقل إلى "التفتيشات التنظيمية"',
                        'انقر على "جدولة تفتيش جديد"',
                        'اختر الشركة ونوع التفتيش',
                        'حدد المفتش والتاريخ المجدول',
                        'أضف نطاق التفتيش والملاحظات',
                        'احفظ وأرسل إشعار للمفتش'
                    ],
                    'icon' => 'fas fa-search',
                    'estimated_time' => '7 دقائق'
                ],
                [
                    'title' => 'إصدار شهادة جودة',
                    'description' => 'إنشاء وإصدار شهادة جودة للمنتج',
                    'steps' => [
                        'انتقل إلى "شهادات الجودة"',
                        'انقر على "إصدار شهادة جديدة"',
                        'اختر المنتج والشركة المصنعة',
                        'أضف نتائج الاختبارات والمعايير',
                        'حدد تاريخ الإصدار والانتهاء',
                        'احفظ وطباعة الشهادة'
                    ],
                    'icon' => 'fas fa-certificate',
                    'estimated_time' => '8 دقائق'
                ],
                [
                    'title' => 'تسجيل اختبار معملي',
                    'description' => 'إضافة نتائج اختبار معملي جديد',
                    'steps' => [
                        'انتقل إلى "الاختبارات المعملية"',
                        'انقر على "تسجيل اختبار جديد"',
                        'اختر المنتج ونوع الاختبار',
                        'أضف نتائج الاختبار والملاحظات',
                        'حدد حالة النتيجة (مطابق/غير مطابق)',
                        'احفظ النتائج وأرسل التقرير'
                    ],
                    'icon' => 'fas fa-flask',
                    'estimated_time' => '5 دقائق'
                ]
            ]
        ];

        return $steps[$moduleSlug] ?? [];
    }

    /**
     * Get module screenshots
     */
    private function getModuleScreenshots($moduleSlug)
    {
        // هذه ستكون مسارات الصور الفعلية
        return [
            'sales' => [
                '/images/screenshots/sales-dashboard.png',
                '/images/screenshots/customer-form.png',
                '/images/screenshots/invoice-creation.png'
            ],
            'inventory' => [
                '/images/screenshots/inventory-dashboard.png',
                '/images/screenshots/product-catalog.png',
                '/images/screenshots/stock-movements.png'
            ]
        ];
    }

    /**
     * Get module tips
     */
    private function getModuleTips($moduleSlug)
    {
        $tips = [
            'sales' => [
                'استخدم البحث السريع للعثور على العملاء بسرعة',
                'يمكنك إنشاء قوالب فواتير مخصصة لشركتك',
                'استفد من تقارير المبيعات لتحليل الأداء',
                'فعّل التنبيهات للمتابعة مع العملاء'
            ],
            'inventory' => [
                'استخدم الباركود لتسريع عمليات الجرد',
                'اضبط حدود التنبيه لتجنب نفاد المخزون',
                'راجع تقارير حركة المخزون بانتظام',
                'استخدم فئات المنتجات لتنظيم أفضل'
            ],
            'targets' => [
                'حدد أهدافاً واقعية وقابلة للتحقيق',
                'راجع التقدم بانتظام واضبط الخطط',
                'استخدم التقارير لتحليل الأداء',
                'شارك الأهداف مع الفريق للتحفيز'
            ],
            'regulatory' => [
                'احتفظ بنسخ احتياطية من جميع الوثائق التنظيمية',
                'راجع تواريخ انتهاء التراخيص والشهادات بانتظام',
                'تأكد من تحديث معلومات الاتصال للجهات التنظيمية',
                'استخدم التنبيهات التلقائية لمتابعة المواعيد المهمة',
                'احرص على توثيق جميع التفتيشات والنتائج',
                'تابع التحديثات في اللوائح والقوانين التنظيمية'
            ]
        ];

        return $tips[$moduleSlug] ?? [];
    }

    /**
     * Get module common issues
     */
    private function getModuleCommonIssues($moduleSlug)
    {
        $issues = [
            'sales' => [
                [
                    'issue' => 'لا يمكنني العثور على العميل في القائمة',
                    'solution' => 'تأكد من كتابة الاسم بشكل صحيح أو استخدم البحث بالهاتف أو البريد الإلكتروني'
                ],
                [
                    'issue' => 'الفاتورة لا تظهر الضريبة',
                    'solution' => 'تحقق من إعدادات الضريبة في إعدادات النظام وتأكد من تفعيلها'
                ]
            ],
            'inventory' => [
                [
                    'issue' => 'الكمية المعروضة غير صحيحة',
                    'solution' => 'راجع حركات المخزون الأخيرة وتأكد من تسجيل جميع العمليات'
                ],
                [
                    'issue' => 'لا يمكنني إضافة منتج جديد',
                    'solution' => 'تأكد من امتلاكك الصلاحيات المطلوبة وأن جميع الحقول الإجبارية مملوءة'
                ]
            ],
            'regulatory' => [
                [
                    'issue' => 'لا يمكنني العثور على الشركة في قائمة التفتيش',
                    'solution' => 'تأكد من أن الشركة مسجلة في النظام أولاً من خلال قسم "تسجيل الشركات"'
                ],
                [
                    'issue' => 'التفتيش لا يظهر في التقويم',
                    'solution' => 'تحقق من أن التاريخ المجدول صحيح وأن التفتيش في حالة "مجدول" وليس "مسودة"'
                ],
                [
                    'issue' => 'لا يمكنني إصدار شهادة جودة',
                    'solution' => 'تأكد من اكتمال جميع الاختبارات المطلوبة وأن نتائجها مطابقة للمعايير'
                ],
                [
                    'issue' => 'التنبيهات لا تصل في الوقت المحدد',
                    'solution' => 'راجع إعدادات التنبيهات في ملف التعريف وتأكد من صحة عنوان البريد الإلكتروني'
                ]
            ]
        ];

        return $issues[$moduleSlug] ?? [];
    }

    /**
     * Get FAQs
     */
    private function getFAQs()
    {
        return [
            [
                'id' => 1,
                'category' => 'عام',
                'question' => 'كيف يمكنني تسجيل الدخول إلى النظام؟',
                'answer' => 'يمكنك تسجيل الدخول باستخدام البريد الإلكتروني وكلمة المرور التي تم توفيرها لك من قبل مدير النظام. إذا نسيت كلمة المرور، يمكنك استخدام خيار "نسيت كلمة المرور" في صفحة تسجيل الدخول.',
                'helpful_count' => 45,
                'tags' => ['تسجيل الدخول', 'كلمة المرور']
            ],
            [
                'id' => 2,
                'category' => 'المبيعات',
                'question' => 'كيف يمكنني إنشاء فاتورة جديدة؟',
                'answer' => 'لإنشاء فاتورة جديدة: 1) انتقل إلى قسم "إدارة المبيعات" 2) اختر "الفواتير" 3) انقر على "إنشاء فاتورة جديدة" 4) اختر العميل 5) أضف المنتجات والكميات 6) احفظ الفاتورة.',
                'helpful_count' => 38,
                'tags' => ['فاتورة', 'مبيعات', 'عميل']
            ],
            [
                'id' => 3,
                'category' => 'المخزون',
                'question' => 'لماذا لا تظهر الكمية الصحيحة للمنتج؟',
                'answer' => 'قد يكون السبب: 1) عدم تسجيل حركة مخزون حديثة 2) وجود حركات معلقة لم يتم تأكيدها 3) خطأ في إدخال البيانات. تحقق من تقرير حركات المخزون للمنتج للتأكد من صحة البيانات.',
                'helpful_count' => 29,
                'tags' => ['مخزون', 'كمية', 'حركات']
            ],
            [
                'id' => 4,
                'category' => 'المحاسبة',
                'question' => 'كيف يمكنني إنشاء قيد محاسبي؟',
                'answer' => 'لإنشاء قيد محاسبي: 1) انتقل إلى "النظام المحاسبي" 2) اختر "القيود المحاسبية" 3) انقر على "قيد جديد" 4) أدخل تاريخ القيد والوصف 5) أضف الحسابات المدينة والدائنة 6) تأكد من توازن القيد 7) احفظ القيد.',
                'helpful_count' => 33,
                'tags' => ['قيد محاسبي', 'محاسبة', 'حسابات']
            ],
            [
                'id' => 5,
                'category' => 'الأهداف',
                'question' => 'كيف يمكنني تتبع تقدم الأهداف؟',
                'answer' => 'يمكنك تتبع تقدم الأهداف من خلال: 1) لوحة تحكم الأهداف التي تظهر النسب المئوية للإنجاز 2) تقارير الأهداف التفصيلية 3) التنبيهات التلقائية عند الوصول لمعالم مهمة 4) الرسوم البيانية التفاعلية.',
                'helpful_count' => 22,
                'tags' => ['أهداف', 'تتبع', 'تقارير']
            ],
            [
                'id' => 6,
                'category' => 'التقارير',
                'question' => 'كيف يمكنني تصدير التقارير؟',
                'answer' => 'يمكنك تصدير التقارير بعدة تنسيقات: 1) PDF للطباعة والأرشفة 2) Excel للتحليل والمعالجة 3) CSV لاستيراد البيانات في أنظمة أخرى. انقر على زر "تصدير" في أعلى التقرير واختر التنسيق المطلوب.',
                'helpful_count' => 41,
                'tags' => ['تقارير', 'تصدير', 'PDF', 'Excel']
            ],
            [
                'id' => 7,
                'category' => 'الإعدادات',
                'question' => 'كيف يمكنني تغيير معلومات الشركة؟',
                'answer' => 'لتغيير معلومات الشركة: 1) انتقل إلى "إعدادات النظام" 2) اختر "معلومات الشركة" 3) قم بتحديث البيانات المطلوبة (الاسم، العنوان، الهاتف، الشعار) 4) احفظ التغييرات. ملاحظة: قد تحتاج صلاحيات إدارية لهذا الإجراء.',
                'helpful_count' => 18,
                'tags' => ['إعدادات', 'شركة', 'معلومات']
            ],
            [
                'id' => 8,
                'category' => 'المستخدمين',
                'question' => 'كيف يمكنني إضافة مستخدم جديد؟',
                'answer' => 'لإضافة مستخدم جديد: 1) انتقل إلى "إدارة المستخدمين" 2) انقر على "إضافة مستخدم" 3) أدخل المعلومات الأساسية (الاسم، البريد الإلكتروني) 4) حدد الدور والصلاحيات 5) أرسل دعوة للمستخدم أو اضبط كلمة مرور مؤقتة.',
                'helpful_count' => 26,
                'tags' => ['مستخدمين', 'صلاحيات', 'دور']
            ]
        ];
    }

    /**
     * Get FAQ categories
     */
    private function getFAQCategories()
    {
        return [
            'عام' => [
                'name' => 'أسئلة عامة',
                'icon' => 'fas fa-question-circle',
                'color' => '#3b82f6',
                'count' => 8
            ],
            'المبيعات' => [
                'name' => 'إدارة المبيعات',
                'icon' => 'fas fa-shopping-bag',
                'color' => '#10b981',
                'count' => 12
            ],
            'المخزون' => [
                'name' => 'إدارة المخزون',
                'icon' => 'fas fa-warehouse',
                'color' => '#3b82f6',
                'count' => 9
            ],
            'المحاسبة' => [
                'name' => 'النظام المحاسبي',
                'icon' => 'fas fa-calculator',
                'color' => '#ef4444',
                'count' => 15
            ],
            'الأهداف' => [
                'name' => 'أهداف البيع',
                'icon' => 'fas fa-bullseye',
                'color' => '#f59e0b',
                'count' => 6
            ],
            'التقارير' => [
                'name' => 'التقارير والتحليلات',
                'icon' => 'fas fa-chart-line',
                'color' => '#8b5cf6',
                'count' => 7
            ],
            'الإعدادات' => [
                'name' => 'إعدادات النظام',
                'icon' => 'fas fa-cogs',
                'color' => '#6b7280',
                'count' => 5
            ],
            'المستخدمين' => [
                'name' => 'إدارة المستخدمين',
                'icon' => 'fas fa-users',
                'color' => '#06b6d4',
                'count' => 4
            ]
        ];
    }

    /**
     * Generate user manual PDF
     */
    private function generateUserManualPDF()
    {
        $modules = $this->getSystemModules();
        $systemFeatures = $this->getSystemFeatures();
        $userTypes = $this->getUserTypes();
        $faqs = $this->getFAQs();

        // Create PDF content
        $html = view('tenant.system-guide.pdf.user-manual', compact(
            'modules', 'systemFeatures', 'userTypes', 'faqs'
        ))->render();

        // Generate PDF using DomPDF or similar
        $pdf = app('dompdf.wrapper');
        $pdf->loadHTML($html);
        $pdf->setPaper('A4', 'portrait');

        $filename = 'دليل_المستخدم_MaxCon_' . date('Y-m-d') . '.pdf';
        $path = storage_path('app/public/manuals/' . $filename);

        // Ensure directory exists
        if (!file_exists(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }

        $pdf->save($path);

        return $path;
    }

    /**
     * Get tour steps
     */
    private function getTourSteps($tourType)
    {
        $tours = [
            'general' => [
                [
                    'element' => '.sidebar',
                    'intro' => 'هذه هي القائمة الرئيسية للتنقل بين وحدات النظام المختلفة.',
                    'position' => 'right'
                ],
                [
                    'element' => '.top-navbar',
                    'intro' => 'شريط التنقل العلوي يحتوي على الإشعارات والبحث السريع.',
                    'position' => 'bottom'
                ],
                [
                    'element' => '.main-content',
                    'intro' => 'هنا يتم عرض محتوى الصفحة الحالية والبيانات.',
                    'position' => 'top'
                ]
            ],
            'sales' => [
                [
                    'element' => '[href*="customers"]',
                    'intro' => 'من هنا يمكنك إدارة العملاء وإضافة عملاء جدد.',
                    'position' => 'right'
                ],
                [
                    'element' => '[href*="invoices"]',
                    'intro' => 'إصدار الفواتير الإلكترونية وطباعتها.',
                    'position' => 'right'
                ]
            ],
            'inventory' => [
                [
                    'element' => '[href*="products"]',
                    'intro' => 'إدارة جميع منتجاتك وتصنيفاتها.',
                    'position' => 'right'
                ],
                [
                    'element' => '[href*="movements"]',
                    'intro' => 'تسجيل حركات دخول وخروج البضائع.',
                    'position' => 'right'
                ]
            ]
        ];

        return $tours[$tourType] ?? [];
    }

    /**
     * Additional methods to be implemented
     */
    private function getRelatedModules($moduleSlug) { return []; }
    private function getVideos($moduleSlug) { return []; }
}
