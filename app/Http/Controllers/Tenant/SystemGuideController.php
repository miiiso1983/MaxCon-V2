<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Mpdf\Mpdf;
use TCPDF;

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
     * Display New Tenant Guide
     */
    public function newTenantGuide()
    {
        $setupSteps = $this->getSetupSteps();
        $modules = $this->getSystemModules();
        $checklist = $this->getNewTenantChecklist();
        $timeline = $this->getImplementationTimeline();

        return view('tenant.system-guide.new-tenant-guide', compact('setupSteps', 'modules', 'checklist', 'timeline'));
    }

    /**
     * Display video tutorials
     */
    public function videos($moduleSlug = null)
    {
        $videos = $this->getVideos($moduleSlug);
        $modules = $this->getSystemModules();
        $categories = $this->getVideoCategories();
        $featuredVideo = $this->getFeaturedVideo();
        $videoStats = $this->getVideoStats();

        return view('tenant.system-guide.videos-clean', compact('videos', 'modules', 'categories', 'featuredVideo', 'videoStats', 'moduleSlug'));
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
     * Generate user manual PDF using TCPDF (best Arabic support)
     */
    private function generateUserManualPDF()
    {
        $modules = $this->getSystemModules();
        $systemFeatures = $this->getSystemFeatures();
        $userTypes = $this->getUserTypes();
        $faqs = $this->getFAQs();

        try {
            // Try TCPDF first (best Arabic support)
            return $this->generateUserManualPDFWithTCPDF($modules, $systemFeatures, $userTypes, $faqs);
        } catch (\Exception $e) {
            try {
                // Fallback to mPDF
                return $this->generateUserManualPDFWithMPDF($modules, $systemFeatures, $userTypes, $faqs);
            } catch (\Exception $e2) {
                // Final fallback to DomPDF
                return $this->generateUserManualPDFWithDomPDF($modules, $systemFeatures, $userTypes, $faqs);
            }
        }
    }

    /**
     * Generate PDF using TCPDF (best Arabic support)
     */
    private function generateUserManualPDFWithTCPDF($modules, $systemFeatures, $userTypes, $faqs)
    {
        // Create new TCPDF document
        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

        // Set document information
        $pdf->SetCreator('MaxCon ERP');
        $pdf->SetAuthor('MaxCon ERP System');
        $pdf->SetTitle('دليل المستخدم - MaxCon ERP');
        $pdf->SetSubject('دليل استخدام نظام MaxCon ERP');
        $pdf->SetKeywords('MaxCon, ERP, دليل المستخدم');

        // Set default header data
        $pdf->SetHeaderData('', 0, 'دليل المستخدم - MaxCon ERP', 'نظام إدارة موارد المؤسسات');

        // Set header and footer fonts
        $pdf->setHeaderFont(['dejavusans', '', 10]);
        $pdf->setFooterFont(['dejavusans', '', 8]);

        // Set margins
        $pdf->SetMargins(15, 27, 15);
        $pdf->SetHeaderMargin(5);
        $pdf->SetFooterMargin(10);

        // Set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, 25);

        // Set font
        $pdf->SetFont('dejavusans', '', 12);

        // Set language direction
        $pdf->setRTL(true);

        // Generate content
        $this->addCoverPageTCPDF($pdf);
        $this->addSystemFeaturesPageTCPDF($pdf, $systemFeatures);
        $this->addUserTypesPageTCPDF($pdf, $userTypes);
        $this->addModulesPageTCPDF($pdf, $modules);
        $this->addFAQPageTCPDF($pdf, $faqs);

        $filename = 'دليل_المستخدم_MaxCon_' . date('Y-m-d') . '.pdf';
        $path = storage_path("app/public/manuals/{$filename}");

        // Ensure directory exists
        if (!file_exists(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }

        // Save PDF
        $pdf->Output($path, 'F');

        return $path;
    }

    /**
     * Generate PDF using mPDF (fallback)
     */
    private function generateUserManualPDFWithMPDF($modules, $systemFeatures, $userTypes, $faqs)
    {
        // Create PDF content
        $html = view('tenant.system-guide.pdf.user-manual-mpdf', compact(
            'modules', 'systemFeatures', 'userTypes', 'faqs'
        ))->render();

        // Initialize mPDF with enhanced Arabic support
        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'orientation' => 'P',
            'margin_left' => 15,
            'margin_right' => 15,
            'margin_top' => 16,
            'margin_bottom' => 16,
            'margin_header' => 9,
            'margin_footer' => 9,
            'default_font_size' => 12,
            'default_font' => 'dejavusans',
            'dir' => 'rtl',
            'autoScriptToLang' => true,
            'autoLangToFont' => true,
            'useSubstitutions' => true,
            'debug' => false,
            'fontDir' => [
                storage_path('fonts/'),
                public_path('fonts/'),
            ],
            'tempDir' => storage_path('app/temp/'),
        ]);

        // Set document properties
        $mpdf->SetTitle('دليل المستخدم - MaxCon ERP');
        $mpdf->SetAuthor('MaxCon ERP System');
        $mpdf->SetSubject('دليل استخدام نظام MaxCon ERP');
        $mpdf->SetKeywords('MaxCon, ERP, دليل المستخدم, نظام إدارة');

        // Write HTML content
        $mpdf->WriteHTML($html);

        $filename = 'دليل_المستخدم_MaxCon_' . date('Y-m-d') . '.pdf';
        $path = storage_path("app/public/manuals/{$filename}");

        // Ensure directory exists
        if (!file_exists(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }

        // Save PDF
        $mpdf->Output($path, 'F');

        return $path;
    }

    /**
     * Fallback PDF generation using DomPDF
     */
    private function generateUserManualPDFWithDomPDF($modules, $systemFeatures, $userTypes, $faqs)
    {
        $html = view('tenant.system-guide.pdf.user-manual', compact(
            'modules', 'systemFeatures', 'userTypes', 'faqs'
        ))->render();

        $pdf = app('dompdf.wrapper');
        $pdf->loadHTML($html);
        $pdf->setPaper('A4', 'portrait');

        $filename = 'دليل_المستخدم_MaxCon_' . date('Y-m-d') . '.pdf';
        $path = storage_path("app/public/manuals/{$filename}");

        if (!file_exists(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }

        $pdf->save($path);
        return $path;
    }

    /**
     * Generate module guide PDF using mPDF (better Arabic support)
     */
    private function generateModuleGuidePDF($moduleSlug)
    {
        $modules = $this->getSystemModules();
        $module = $modules[$moduleSlug] ?? null;

        if (!$module) {
            throw new \Exception('Module not found');
        }

        // Create PDF content
        $html = view('tenant.system-guide.pdf.module-guide-mpdf', compact('module', 'moduleSlug'))->render();

        try {
            // Initialize mPDF with Arabic support
            $mpdf = new Mpdf([
                'mode' => 'utf-8',
                'format' => 'A4',
                'orientation' => 'P',
                'margin_left' => 15,
                'margin_right' => 15,
                'margin_top' => 16,
                'margin_bottom' => 16,
                'default_font_size' => 12,
                'default_font' => 'dejavusans',
                'dir' => 'rtl',
                'autoScriptToLang' => true,
                'autoLangToFont' => true,
            ]);

            // Set document properties
            $mpdf->SetTitle('دليل وحدة ' . $module['name'] . ' - MaxCon ERP');
            $mpdf->SetAuthor('MaxCon ERP System');
            $mpdf->SetSubject('دليل استخدام وحدة ' . $module['name']);

            // Write HTML content
            $mpdf->WriteHTML($html);

            $filename = 'دليل_وحدة_' . $module['name'] . '_' . date('Y-m-d') . '.pdf';
            $path = storage_path("app/public/manuals/{$filename}");

            // Ensure directory exists
            if (!file_exists(dirname($path))) {
                mkdir(dirname($path), 0755, true);
            }

            // Save PDF
            $mpdf->Output($path, 'F');

            return $path;

        } catch (\Exception $e) {
            // Fallback to DomPDF if mPDF fails
            return $this->generateModuleGuidePDFWithDomPDF($module, $moduleSlug);
        }
    }

    /**
     * Fallback module PDF generation using DomPDF
     */
    private function generateModuleGuidePDFWithDomPDF($module, $moduleSlug)
    {
        $html = view('tenant.system-guide.pdf.module-guide', compact('module', 'moduleSlug'))->render();

        $pdf = app('dompdf.wrapper');
        $pdf->loadHTML($html);
        $pdf->setPaper('A4', 'portrait');

        $filename = 'دليل_وحدة_' . $module['name'] . '_' . date('Y-m-d') . '.pdf';
        $path = storage_path("app/public/manuals/{$filename}");

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
     * Add cover page using TCPDF
     */
    private function addCoverPageTCPDF($pdf)
    {
        $pdf->AddPage();

        $html = '
        <style>
            .cover { text-align: center; padding: 50px 20px; }
            .title { font-size: 28pt; font-weight: bold; color: #667eea; margin-bottom: 20px; }
            .subtitle { font-size: 18pt; margin-bottom: 30px; }
            .version { font-size: 12pt; margin-top: 40px; }
        </style>
        <div class="cover">
            <div class="title">دليل المستخدم</div>
            <div class="subtitle">نظام MaxCon ERP</div>
            <div>نظام إدارة موارد المؤسسات الشامل</div>
            <div class="version">الإصدار 2.0 - ' . date('Y/m/d') . '</div>
        </div>';

        $pdf->writeHTML($html, true, false, true, false, '');
    }

    /**
     * Add system features page using TCPDF
     */
    private function addSystemFeaturesPageTCPDF($pdf, $systemFeatures)
    {
        $pdf->AddPage();

        $html = '<div style="font-size: 20pt; font-weight: bold; color: #667eea; margin-bottom: 20px; text-align: center;">مميزات النظام</div>';

        if (is_array($systemFeatures)) {
            foreach ($systemFeatures as $feature) {
                if (is_array($feature) && isset($feature['title'])) {
                    $html .= '
                    <div style="margin-bottom: 20px; padding: 15px; border: 1px solid #e2e8f0; border-radius: 8px;">
                        <div style="font-size: 16pt; font-weight: bold; color: #2d3748; margin-bottom: 10px;">' . $feature['title'] . '</div>
                        <div style="font-size: 12pt; line-height: 1.6;">' . ($feature['description'] ?? '') . '</div>
                    </div>';
                }
            }
        }

        $pdf->writeHTML($html, true, false, true, false, '');
    }

    /**
     * Add user types page using TCPDF
     */
    private function addUserTypesPageTCPDF($pdf, $userTypes)
    {
        $pdf->AddPage();

        $html = '<div style="font-size: 20pt; font-weight: bold; color: #667eea; margin-bottom: 20px; text-align: center;">أنواع المستخدمين</div>';

        if (is_array($userTypes)) {
            foreach ($userTypes as $userType) {
                if (is_array($userType) && isset($userType['title'])) {
                    $html .= '
                    <div style="margin-bottom: 25px; padding: 15px; border: 1px solid #e2e8f0; border-radius: 8px;">
                        <div style="font-size: 16pt; font-weight: bold; color: ' . ($userType['color'] ?? '#667eea') . '; margin-bottom: 10px;">' . $userType['title'] . '</div>
                        <div style="font-size: 12pt; line-height: 1.6; margin-bottom: 10px;">' . ($userType['description'] ?? '') . '</div>';

                    if (isset($userType['permissions']) && is_array($userType['permissions'])) {
                        $html .= '<div style="font-size: 14pt; font-weight: bold; margin-bottom: 8px;">الصلاحيات:</div><ul>';
                        foreach ($userType['permissions'] as $permission) {
                            $html .= '<li style="margin-bottom: 5px;">' . $permission . '</li>';
                        }
                        $html .= '</ul>';
                    }

                    $html .= '</div>';
                }
            }
        }

        $pdf->writeHTML($html, true, false, true, false, '');
    }

    /**
     * Add modules page using TCPDF
     */
    private function addModulesPageTCPDF($pdf, $modules)
    {
        $pdf->AddPage();

        $html = '<div style="font-size: 20pt; font-weight: bold; color: #667eea; margin-bottom: 20px; text-align: center;">وحدات النظام</div>';

        if (is_array($modules)) {
            foreach ($modules as $module) {
                if (is_array($module) && isset($module['name'])) {
                    $html .= '
                    <div style="margin-bottom: 25px; padding: 15px; border: 1px solid #e2e8f0; border-radius: 8px;">
                        <div style="font-size: 16pt; font-weight: bold; color: #2d3748; margin-bottom: 10px;">' . $module['name'] . '</div>
                        <div style="font-size: 12pt; line-height: 1.6; margin-bottom: 10px;">' . ($module['description'] ?? '') . '</div>';

                    if (isset($module['features']) && is_array($module['features'])) {
                        $html .= '<div style="font-size: 14pt; font-weight: bold; margin-bottom: 8px;">المميزات:</div><ul>';
                        foreach ($module['features'] as $feature) {
                            $html .= '<li style="margin-bottom: 5px;">' . $feature . '</li>';
                        }
                        $html .= '</ul>';
                    }

                    $html .= '</div>';
                }
            }
        }

        $pdf->writeHTML($html, true, false, true, false, '');
    }

    /**
     * Add FAQ page using TCPDF
     */
    private function addFAQPageTCPDF($pdf, $faqs)
    {
        $pdf->AddPage();

        $html = '<div style="font-size: 20pt; font-weight: bold; color: #667eea; margin-bottom: 20px; text-align: center;">الأسئلة الشائعة</div>';

        if (is_array($faqs)) {
            foreach ($faqs as $category => $categoryFaqs) {
                $html .= '<div style="font-size: 16pt; font-weight: bold; color: #2d3748; margin: 20px 0 10px 0;">' . $category . '</div>';

                if (is_array($categoryFaqs)) {
                    foreach ($categoryFaqs as $faq) {
                        if (is_array($faq)) {
                            $html .= '
                            <div style="margin-bottom: 15px; padding: 10px; background-color: #f8f9fa; border-radius: 8px;">
                                <div style="font-weight: bold; margin-bottom: 5px;">س: ' . ($faq['question'] ?? '') . '</div>
                                <div>ج: ' . ($faq['answer'] ?? '') . '</div>
                            </div>';
                        }
                    }
                }
            }
        }

        $pdf->writeHTML($html, true, false, true, false, '');
    }

    /**
     * Get setup steps for new tenants
     */
    private function getSetupSteps()
    {
        return [
            [
                'id' => 1,
                'title' => 'إعداد معلومات الشركة',
                'description' => 'إدخال البيانات الأساسية للشركة والإعدادات الأولية',
                'icon' => 'fas fa-building',
                'color' => '#667eea',
                'estimated_time' => '30 دقيقة',
                'tasks' => [
                    'إدخال اسم الشركة والعنوان',
                    'رفع شعار الشركة',
                    'تحديد العملة والمنطقة الزمنية',
                    'إعداد إعدادات الأمان'
                ]
            ],
            [
                'id' => 2,
                'title' => 'إدارة المستخدمين',
                'description' => 'إضافة المستخدمين وتعيين الأدوار والصلاحيات',
                'icon' => 'fas fa-users',
                'color' => '#10b981',
                'estimated_time' => '45 دقيقة',
                'tasks' => [
                    'إضافة المستخدمين الأساسيين',
                    'تعيين الأدوار والصلاحيات',
                    'إعداد كلمات مرور قوية',
                    'اختبار تسجيل الدخول'
                ]
            ],
            [
                'id' => 3,
                'title' => 'إعداد البيانات الأساسية',
                'description' => 'إدخال بيانات العملاء والمنتجات الأساسية',
                'icon' => 'fas fa-database',
                'color' => '#f59e0b',
                'estimated_time' => '2 ساعة',
                'tasks' => [
                    'إدخال بيانات العملاء الرئيسيين',
                    'إعداد كتالوج المنتجات',
                    'تحديد أسعار البيع والشراء',
                    'إعداد فئات المنتجات'
                ]
            ],
            [
                'id' => 4,
                'title' => 'تفعيل الوحدات',
                'description' => 'تفعيل واختبار جميع وحدات النظام',
                'icon' => 'fas fa-cogs',
                'color' => '#8b5cf6',
                'estimated_time' => '3 ساعات',
                'tasks' => [
                    'تفعيل وحدة المبيعات',
                    'تفعيل وحدة المخزون',
                    'تفعيل وحدة المحاسبة',
                    'تفعيل وحدة الموارد البشرية'
                ]
            ]
        ];
    }

    /**
     * Get new tenant checklist
     */
    private function getNewTenantChecklist()
    {
        return [
            'basic_setup' => [
                'title' => 'الإعداد الأساسي',
                'items' => [
                    ['id' => 'company_info', 'text' => 'إعداد معلومات الشركة', 'completed' => false],
                    ['id' => 'logo_upload', 'text' => 'رفع شعار الشركة', 'completed' => false],
                    ['id' => 'currency_timezone', 'text' => 'تحديد العملة والمنطقة الزمنية', 'completed' => false],
                    ['id' => 'security_settings', 'text' => 'إعداد إعدادات الأمان', 'completed' => false]
                ]
            ],
            'users_management' => [
                'title' => 'إدارة المستخدمين',
                'items' => [
                    ['id' => 'add_users', 'text' => 'إضافة المستخدمين الأساسيين', 'completed' => false],
                    ['id' => 'assign_roles', 'text' => 'تعيين الأدوار والصلاحيات', 'completed' => false],
                    ['id' => 'strong_passwords', 'text' => 'إعداد كلمات مرور قوية', 'completed' => false],
                    ['id' => 'test_login', 'text' => 'اختبار تسجيل الدخول', 'completed' => false]
                ]
            ],
            'data_entry' => [
                'title' => 'إدخال البيانات',
                'items' => [
                    ['id' => 'customers_data', 'text' => 'إدخال بيانات العملاء', 'completed' => false],
                    ['id' => 'products_catalog', 'text' => 'إعداد كتالوج المنتجات', 'completed' => false],
                    ['id' => 'pricing', 'text' => 'تحديد أسعار البيع والشراء', 'completed' => false],
                    ['id' => 'categories', 'text' => 'إعداد فئات المنتجات', 'completed' => false]
                ]
            ],
            'modules_activation' => [
                'title' => 'تفعيل الوحدات',
                'items' => [
                    ['id' => 'sales_module', 'text' => 'تفعيل وحدة المبيعات', 'completed' => false],
                    ['id' => 'inventory_module', 'text' => 'تفعيل وحدة المخزون', 'completed' => false],
                    ['id' => 'accounting_module', 'text' => 'تفعيل وحدة المحاسبة', 'completed' => false],
                    ['id' => 'hr_module', 'text' => 'تفعيل وحدة الموارد البشرية', 'completed' => false]
                ]
            ]
        ];
    }

    /**
     * Get implementation timeline
     */
    private function getImplementationTimeline()
    {
        return [
            [
                'week' => 1,
                'title' => 'الأسبوع الأول: الإعداد الأساسي',
                'color' => '#667eea',
                'days' => [
                    [
                        'day' => '1-2',
                        'title' => 'إعداد النظام',
                        'tasks' => [
                            'تسجيل الدخول الأول وتغيير كلمة المرور',
                            'إعداد معلومات الشركة الأساسية',
                            'تحديد العملة والمنطقة الزمنية',
                            'إعداد إعدادات الأمان'
                        ]
                    ],
                    [
                        'day' => '3-4',
                        'title' => 'إدارة المستخدمين',
                        'tasks' => [
                            'إضافة المستخدمين الأساسيين (5-10)',
                            'تعيين الأدوار والصلاحيات',
                            'إعداد كلمات مرور قوية',
                            'اختبار تسجيل الدخول'
                        ]
                    ],
                    [
                        'day' => '5-7',
                        'title' => 'البيانات الأساسية',
                        'tasks' => [
                            'إدخال بيانات العملاء (20-50 عميل)',
                            'إعداد كتالوج المنتجات (50-100 منتج)',
                            'تحديد أسعار البيع والشراء',
                            'إعداد فئات المنتجات'
                        ]
                    ]
                ]
            ],
            [
                'week' => 2,
                'title' => 'الأسبوع الثاني: تفعيل الوحدات الأساسية',
                'color' => '#10b981',
                'days' => [
                    [
                        'day' => '8-10',
                        'title' => 'وحدة المبيعات',
                        'tasks' => [
                            'إنشاء أول فاتورة مبيعات',
                            'اختبار طباعة الفاتورة مع QR Code',
                            'تسجيل عملية دفع',
                            'إنشاء فاتورة مرتجعات',
                            'تدريب فريق المبيعات'
                        ]
                    ],
                    [
                        'day' => '11-12',
                        'title' => 'وحدة المخزون',
                        'tasks' => [
                            'إعداد المستودعات الأساسية',
                            'إدخال الكميات الحالية',
                            'تسجيل حركات استلام وصرف',
                            'إجراء جرد تجريبي',
                            'إعداد تنبيهات المخزون'
                        ]
                    ],
                    [
                        'day' => '13-14',
                        'title' => 'وحدة المحاسبة',
                        'tasks' => [
                            'إعداد دليل الحسابات',
                            'إدخال الأرصدة الافتتاحية',
                            'تسجيل قيود تجريبية',
                            'إنشاء أول تقرير مالي',
                            'ربط المبيعات بالحسابات'
                        ]
                    ]
                ]
            ]
        ];
    }

    /**
     * Get video tutorials
     */
    private function getVideos($moduleSlug = null)
    {
        $allVideos = [
            'general' => [
                [
                    'id' => 'system-overview',
                    'title' => 'نظرة عامة على النظام',
                    'description' => 'مقدمة شاملة عن نظام MaxCon ERP وجميع وحداته',
                    'duration' => '8:30',
                    'difficulty' => 'مبتدئ',
                    'thumbnail' => '/images/videos/system-overview.jpg',
                    'video_url' => 'https://example.com/videos/system-overview.mp4',
                    'embed_code' => null,
                    'category' => 'عام',
                    'tags' => ['مقدمة', 'نظرة عامة', 'ERP'],
                    'views' => 1250,
                    'rating' => 4.8,
                    'created_at' => '2024-01-15',
                    'updated_at' => '2024-01-15'
                ],
                [
                    'id' => 'first-login',
                    'title' => 'تسجيل الدخول الأول وإعداد الحساب',
                    'description' => 'كيفية تسجيل الدخول لأول مرة وإعداد معلومات الحساب الأساسية',
                    'duration' => '5:45',
                    'difficulty' => 'مبتدئ',
                    'thumbnail' => '/images/videos/first-login.jpg',
                    'video_url' => 'https://example.com/videos/first-login.mp4',
                    'embed_code' => null,
                    'category' => 'البداية',
                    'tags' => ['تسجيل دخول', 'إعداد', 'حساب'],
                    'views' => 980,
                    'rating' => 4.9,
                    'created_at' => '2024-01-16',
                    'updated_at' => '2024-01-16'
                ],
                [
                    'id' => 'navigation-basics',
                    'title' => 'أساسيات التنقل في النظام',
                    'description' => 'تعلم كيفية التنقل بين الوحدات والقوائم المختلفة',
                    'duration' => '6:20',
                    'difficulty' => 'مبتدئ',
                    'thumbnail' => '/images/videos/navigation.jpg',
                    'video_url' => 'https://example.com/videos/navigation.mp4',
                    'embed_code' => null,
                    'category' => 'أساسيات',
                    'tags' => ['تنقل', 'قوائم', 'واجهة'],
                    'views' => 756,
                    'rating' => 4.7,
                    'created_at' => '2024-01-17',
                    'updated_at' => '2024-01-17'
                ]
            ],
            'sales' => [
                [
                    'id' => 'sales-overview',
                    'title' => 'مقدمة في إدارة المبيعات',
                    'description' => 'نظرة شاملة على وحدة إدارة المبيعات وإمكانياتها',
                    'duration' => '10:15',
                    'difficulty' => 'مبتدئ',
                    'thumbnail' => '/images/videos/sales-overview.jpg',
                    'video_url' => 'https://example.com/videos/sales-overview.mp4',
                    'embed_code' => null,
                    'category' => 'المبيعات',
                    'tags' => ['مبيعات', 'عملاء', 'طلبات'],
                    'views' => 1100,
                    'rating' => 4.8,
                    'created_at' => '2024-01-18',
                    'updated_at' => '2024-01-18'
                ],
                [
                    'id' => 'customer-management',
                    'title' => 'إدارة العملاء',
                    'description' => 'كيفية إضافة وإدارة بيانات العملاء بشكل فعال',
                    'duration' => '12:30',
                    'difficulty' => 'مبتدئ',
                    'thumbnail' => '/images/videos/customers.jpg',
                    'video_url' => 'https://example.com/videos/customers.mp4',
                    'embed_code' => null,
                    'category' => 'المبيعات',
                    'tags' => ['عملاء', 'إدارة', 'بيانات'],
                    'views' => 890,
                    'rating' => 4.9,
                    'created_at' => '2024-01-19',
                    'updated_at' => '2024-01-19'
                ],
                [
                    'id' => 'invoice-creation',
                    'title' => 'إنشاء الفواتير الإلكترونية',
                    'description' => 'دليل شامل لإنشاء وإدارة الفواتير الإلكترونية',
                    'duration' => '15:45',
                    'difficulty' => 'متوسط',
                    'thumbnail' => '/images/videos/invoices.jpg',
                    'video_url' => 'https://example.com/videos/invoices.mp4',
                    'embed_code' => null,
                    'category' => 'المبيعات',
                    'tags' => ['فواتير', 'إلكترونية', 'QR'],
                    'views' => 1350,
                    'rating' => 4.8,
                    'created_at' => '2024-01-20',
                    'updated_at' => '2024-01-20'
                ],
                [
                    'id' => 'payment-tracking',
                    'title' => 'تتبع المدفوعات والمستحقات',
                    'description' => 'كيفية تتبع المدفوعات وإدارة المستحقات المالية',
                    'duration' => '11:20',
                    'difficulty' => 'متوسط',
                    'thumbnail' => '/images/videos/payments.jpg',
                    'video_url' => 'https://example.com/videos/payments.mp4',
                    'embed_code' => null,
                    'category' => 'المبيعات',
                    'tags' => ['مدفوعات', 'مستحقات', 'مالية'],
                    'views' => 720,
                    'rating' => 4.7,
                    'created_at' => '2024-01-21',
                    'updated_at' => '2024-01-21'
                ]
            ]
        ];

        // Add more modules...
        $allVideos = array_merge($allVideos, $this->getInventoryVideos());
        $allVideos = array_merge($allVideos, $this->getAccountingVideos());
        $allVideos = array_merge($allVideos, $this->getHRVideos());
        $allVideos = array_merge($allVideos, $this->getRegulatoryVideos());
        $allVideos = array_merge($allVideos, $this->getAnalyticsVideos());

        if ($moduleSlug && isset($allVideos[$moduleSlug])) {
            return $allVideos[$moduleSlug];
        }

        return $allVideos;
    }

    /**
     * Get inventory videos
     */
    private function getInventoryVideos()
    {
        return [
            'inventory' => [
                [
                    'id' => 'inventory-overview',
                    'title' => 'مقدمة في إدارة المخزون',
                    'description' => 'نظرة شاملة على وحدة إدارة المخزون وإمكانياتها',
                    'duration' => '12:15',
                    'difficulty' => 'مبتدئ',
                    'thumbnail' => '/images/videos/inventory-overview.jpg',
                    'video_url' => 'https://example.com/videos/inventory-overview.mp4',
                    'embed_code' => null,
                    'category' => 'المخزون',
                    'tags' => ['مخزون', 'منتجات', 'مستودعات'],
                    'views' => 850,
                    'rating' => 4.7,
                    'created_at' => '2024-01-22',
                    'updated_at' => '2024-01-22'
                ],
                [
                    'id' => 'product-catalog',
                    'title' => 'إدارة كتالوج المنتجات',
                    'description' => 'كيفية إضافة وإدارة المنتجات والفئات',
                    'duration' => '14:30',
                    'difficulty' => 'متوسط',
                    'thumbnail' => '/images/videos/products.jpg',
                    'video_url' => 'https://example.com/videos/products.mp4',
                    'embed_code' => null,
                    'category' => 'المخزون',
                    'tags' => ['منتجات', 'كتالوج', 'فئات'],
                    'views' => 920,
                    'rating' => 4.8,
                    'created_at' => '2024-01-23',
                    'updated_at' => '2024-01-23'
                ],
                [
                    'id' => 'stock-movements',
                    'title' => 'حركات المخزون والجرد',
                    'description' => 'تتبع حركات المخزون وإجراء الجرد الدوري',
                    'duration' => '16:45',
                    'difficulty' => 'متوسط',
                    'thumbnail' => '/images/videos/stock-movements.jpg',
                    'video_url' => 'https://example.com/videos/stock-movements.mp4',
                    'embed_code' => null,
                    'category' => 'المخزون',
                    'tags' => ['حركات', 'جرد', 'تتبع'],
                    'views' => 680,
                    'rating' => 4.6,
                    'created_at' => '2024-01-24',
                    'updated_at' => '2024-01-24'
                ]
            ]
        ];
    }

    /**
     * Get accounting videos
     */
    private function getAccountingVideos()
    {
        return [
            'accounting' => [
                [
                    'id' => 'accounting-overview',
                    'title' => 'مقدمة في النظام المحاسبي',
                    'description' => 'أساسيات النظام المحاسبي ودليل الحسابات',
                    'duration' => '18:20',
                    'difficulty' => 'متوسط',
                    'thumbnail' => '/images/videos/accounting-overview.jpg',
                    'video_url' => 'https://example.com/videos/accounting-overview.mp4',
                    'embed_code' => null,
                    'category' => 'المحاسبة',
                    'tags' => ['محاسبة', 'قيود', 'حسابات'],
                    'views' => 1200,
                    'rating' => 4.9,
                    'created_at' => '2024-01-25',
                    'updated_at' => '2024-01-25'
                ],
                [
                    'id' => 'journal-entries',
                    'title' => 'إنشاء القيود المحاسبية',
                    'description' => 'كيفية إنشاء وإدارة القيود المحاسبية',
                    'duration' => '20:15',
                    'difficulty' => 'متقدم',
                    'thumbnail' => '/images/videos/journal-entries.jpg',
                    'video_url' => 'https://example.com/videos/journal-entries.mp4',
                    'embed_code' => null,
                    'category' => 'المحاسبة',
                    'tags' => ['قيود', 'محاسبية', 'دفتر'],
                    'views' => 950,
                    'rating' => 4.8,
                    'created_at' => '2024-01-26',
                    'updated_at' => '2024-01-26'
                ],
                [
                    'id' => 'financial-reports',
                    'title' => 'التقارير المالية',
                    'description' => 'إنشاء وتخصيص التقارير المالية المختلفة',
                    'duration' => '22:30',
                    'difficulty' => 'متقدم',
                    'thumbnail' => '/images/videos/financial-reports.jpg',
                    'video_url' => 'https://example.com/videos/financial-reports.mp4',
                    'embed_code' => null,
                    'category' => 'المحاسبة',
                    'tags' => ['تقارير', 'مالية', 'ميزانية'],
                    'views' => 780,
                    'rating' => 4.7,
                    'created_at' => '2024-01-27',
                    'updated_at' => '2024-01-27'
                ]
            ]
        ];
    }

    /**
     * Get HR videos
     */
    private function getHRVideos()
    {
        return [
            'hr' => [
                [
                    'id' => 'hr-overview',
                    'title' => 'مقدمة في إدارة الموارد البشرية',
                    'description' => 'نظرة شاملة على وحدة الموارد البشرية',
                    'duration' => '13:45',
                    'difficulty' => 'مبتدئ',
                    'thumbnail' => '/images/videos/hr-overview.jpg',
                    'video_url' => 'https://example.com/videos/hr-overview.mp4',
                    'embed_code' => null,
                    'category' => 'الموارد البشرية',
                    'tags' => ['موارد بشرية', 'موظفين', 'رواتب'],
                    'views' => 650,
                    'rating' => 4.6,
                    'created_at' => '2024-01-28',
                    'updated_at' => '2024-01-28'
                ],
                [
                    'id' => 'employee-management',
                    'title' => 'إدارة ملفات الموظفين',
                    'description' => 'كيفية إضافة وإدارة بيانات الموظفين',
                    'duration' => '15:20',
                    'difficulty' => 'مبتدئ',
                    'thumbnail' => '/images/videos/employees.jpg',
                    'video_url' => 'https://example.com/videos/employees.mp4',
                    'embed_code' => null,
                    'category' => 'الموارد البشرية',
                    'tags' => ['موظفين', 'ملفات', 'بيانات'],
                    'views' => 580,
                    'rating' => 4.7,
                    'created_at' => '2024-01-29',
                    'updated_at' => '2024-01-29'
                ]
            ]
        ];
    }

    /**
     * Get regulatory videos
     */
    private function getRegulatoryVideos()
    {
        return [
            'regulatory' => [
                [
                    'id' => 'regulatory-overview',
                    'title' => 'مقدمة في الشؤون التنظيمية',
                    'description' => 'نظرة شاملة على وحدة الشؤون التنظيمية والامتثال',
                    'duration' => '14:25',
                    'difficulty' => 'متقدم',
                    'thumbnail' => '/images/videos/regulatory-overview.jpg',
                    'video_url' => 'https://example.com/videos/regulatory-overview.mp4',
                    'embed_code' => null,
                    'category' => 'الشؤون التنظيمية',
                    'tags' => ['تنظيمية', 'امتثال', 'تراخيص'],
                    'views' => 420,
                    'rating' => 4.8,
                    'created_at' => '2024-01-30',
                    'updated_at' => '2024-01-30'
                ],
                [
                    'id' => 'company-registration',
                    'title' => 'تسجيل الشركات والتراخيص',
                    'description' => 'كيفية تسجيل الشركات وإدارة التراخيص',
                    'duration' => '16:30',
                    'difficulty' => 'متقدم',
                    'thumbnail' => '/images/videos/company-registration.jpg',
                    'video_url' => 'https://example.com/videos/company-registration.mp4',
                    'embed_code' => null,
                    'category' => 'الشؤون التنظيمية',
                    'tags' => ['تسجيل', 'شركات', 'تراخيص'],
                    'views' => 380,
                    'rating' => 4.7,
                    'created_at' => '2024-01-31',
                    'updated_at' => '2024-01-31'
                ]
            ]
        ];
    }

    /**
     * Get analytics videos
     */
    private function getAnalyticsVideos()
    {
        return [
            'analytics' => [
                [
                    'id' => 'analytics-overview',
                    'title' => 'مقدمة في التحليلات والذكاء الاصطناعي',
                    'description' => 'استخدام أدوات التحليل والذكاء الاصطناعي',
                    'duration' => '9:45',
                    'difficulty' => 'متقدم',
                    'thumbnail' => '/images/videos/analytics-overview.jpg',
                    'video_url' => 'https://example.com/videos/analytics-overview.mp4',
                    'embed_code' => null,
                    'category' => 'التحليلات',
                    'tags' => ['تحليلات', 'ذكاء اصطناعي', 'تقارير'],
                    'views' => 890,
                    'rating' => 4.9,
                    'created_at' => '2024-02-01',
                    'updated_at' => '2024-02-01'
                ],
                [
                    'id' => 'dashboard-creation',
                    'title' => 'إنشاء لوحات التحكم التفاعلية',
                    'description' => 'كيفية إنشاء وتخصيص لوحات التحكم',
                    'duration' => '18:15',
                    'difficulty' => 'متقدم',
                    'thumbnail' => '/images/videos/dashboards.jpg',
                    'video_url' => 'https://example.com/videos/dashboards.mp4',
                    'embed_code' => null,
                    'category' => 'التحليلات',
                    'tags' => ['لوحات تحكم', 'تفاعلية', 'مؤشرات'],
                    'views' => 720,
                    'rating' => 4.8,
                    'created_at' => '2024-02-02',
                    'updated_at' => '2024-02-02'
                ]
            ]
        ];
    }

    /**
     * Get video categories for filtering
     */
    private function getVideoCategories()
    {
        return [
            'general' => [
                'name' => 'عام',
                'icon' => 'fas fa-home',
                'color' => '#667eea',
                'description' => 'مقدمات عامة وأساسيات النظام'
            ],
            'sales' => [
                'name' => 'المبيعات',
                'icon' => 'fas fa-shopping-bag',
                'color' => '#10b981',
                'description' => 'إدارة العملاء والطلبات والفواتير'
            ],
            'inventory' => [
                'name' => 'المخزون',
                'icon' => 'fas fa-warehouse',
                'color' => '#3b82f6',
                'description' => 'إدارة المنتجات والمستودعات'
            ],
            'accounting' => [
                'name' => 'المحاسبة',
                'icon' => 'fas fa-calculator',
                'color' => '#ef4444',
                'description' => 'النظام المحاسبي والتقارير المالية'
            ],
            'hr' => [
                'name' => 'الموارد البشرية',
                'icon' => 'fas fa-users',
                'color' => '#f59e0b',
                'description' => 'إدارة الموظفين والرواتب'
            ],
            'regulatory' => [
                'name' => 'الشؤون التنظيمية',
                'icon' => 'fas fa-shield-alt',
                'color' => '#dc2626',
                'description' => 'التراخيص والامتثال التنظيمي'
            ],
            'analytics' => [
                'name' => 'التحليلات',
                'icon' => 'fas fa-brain',
                'color' => '#8b5cf6',
                'description' => 'التحليلات والذكاء الاصطناعي'
            ]
        ];
    }

    /**
     * Get featured video
     */
    private function getFeaturedVideo()
    {
        return [
            'id' => 'system-overview',
            'title' => 'نظرة عامة شاملة على نظام MaxCon ERP',
            'description' => 'تعرف على جميع وحدات النظام وكيفية استخدامها بفعالية لإدارة أعمالك الصيدلانية',
            'duration' => '12:30',
            'difficulty' => 'مبتدئ',
            'thumbnail' => '/images/videos/featured-overview.jpg',
            'video_url' => 'https://example.com/videos/featured-overview.mp4',
            'embed_code' => null,
            'category' => 'عام',
            'tags' => ['مقدمة', 'نظرة عامة', 'ERP', 'صيدلانية'],
            'views' => 2150,
            'rating' => 4.9,
            'instructor' => 'فريق MaxCon التقني',
            'created_at' => '2024-01-15',
            'updated_at' => '2024-01-15'
        ];
    }

    /**
     * Get video statistics
     */
    private function getVideoStats()
    {
        return [
            'total_videos' => 25,
            'total_duration' => '6:45:30', // 6 hours 45 minutes 30 seconds
            'total_views' => 18750,
            'average_rating' => 4.7,
            'completion_rate' => 78,
            'most_popular_category' => 'المبيعات',
            'newest_videos' => 5,
            'updated_this_week' => 3
        ];
    }

    /**
     * Get video playlists
     */
    private function getVideoPlaylists()
    {
        return [
            'beginner' => [
                'name' => 'دليل المبتدئين',
                'description' => 'ابدأ رحلتك مع MaxCon ERP',
                'videos' => ['system-overview', 'first-login', 'navigation-basics'],
                'duration' => '20:35',
                'difficulty' => 'مبتدئ',
                'color' => '#10b981'
            ],
            'sales-complete' => [
                'name' => 'إدارة المبيعات الشاملة',
                'description' => 'تعلم كل شيء عن إدارة المبيعات',
                'videos' => ['sales-overview', 'customer-management', 'invoice-creation', 'payment-tracking'],
                'duration' => '49:50',
                'difficulty' => 'متوسط',
                'color' => '#3b82f6'
            ],
            'advanced-features' => [
                'name' => 'الميزات المتقدمة',
                'description' => 'استخدم الميزات المتقدمة في النظام',
                'videos' => ['financial-reports', 'analytics-overview', 'dashboard-creation'],
                'duration' => '50:30',
                'difficulty' => 'متقدم',
                'color' => '#8b5cf6'
            ]
        ];
    }

    /**
     * Additional methods to be implemented
     */
    private function getRelatedModules($moduleSlug) { return []; }
}
