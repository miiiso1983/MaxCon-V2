# طبقة التخصيص العراقية - Iraq Localization Layer

## نظرة عامة

طبقة تخصيص شاملة للسوق العراقي في نظام MaxCon ERP تتضمن:

- ✅ **دعم اللغة العربية الكامل** مع ملفات الترجمة والتنسيقات المناسبة
- ✅ **الدينار العراقي كعملة أساسية** مع التنسيقات والرموز المناسبة
- ✅ **النظام الضريبي العراقي** المتوافق مع القوانين المحلية
- ✅ **قوالب الفواتير الحكومية** المتوافقة مع المتطلبات الرسمية
- ✅ **تنسيقات الهاتف العراقية** مع دعم جميع الشركات المحلية
- ✅ **البنوك العراقية** وأنظمة الدفع المحلية
- ✅ **تقارير جاهزة للجهات الرسمية** العراقية

## الهيكل

```
app/Localization/Iraq/
├── IraqLocalizationProvider.php    # مقدم الخدمة الرئيسي
├── IraqCurrencyService.php         # خدمة العملة العراقية
├── IraqTaxService.php              # خدمة النظام الضريبي
├── IraqPhoneService.php            # خدمة أرقام الهواتف
├── IraqBankingService.php          # خدمة البنوك العراقية
├── IraqGovernmentReportsService.php # خدمة التقارير الحكومية
├── helpers.php                     # دوال مساعدة
├── config/
│   └── iraq.php                    # ملف التكوين الرئيسي
├── lang/
│   └── ar/
│       ├── common.php              # ترجمات عامة
│       └── financial.php           # ترجمات مالية
├── views/
│   └── invoices/
│       └── government-invoice.blade.php # قالب الفاتورة الحكومية
└── README.md                       # هذا الملف
```

## التثبيت والتفعيل

### 1. تفعيل مقدم الخدمة

تم تسجيل مقدم الخدمة تلقائياً في `bootstrap/providers.php`:

```php
App\Localization\Iraq\IraqLocalizationProvider::class,
```

### 2. نشر ملفات التكوين

```bash
php artisan vendor:publish --tag=iraq-config
```

### 3. نشر ملفات الترجمة

```bash
php artisan vendor:publish --tag=iraq-lang
```

### 4. نشر قوالب العرض

```bash
php artisan vendor:publish --tag=iraq-views
```

### 5. تحديث متغيرات البيئة

أضف إلى ملف `.env`:

```env
IRAQ_LOCALIZATION_ENABLED=true
APP_LOCALE=ar
APP_TIMEZONE=Asia/Baghdad
```

## الاستخدام

### خدمة العملة العراقية

```php
// تنسيق المبلغ
format_iraqi_currency(1500000); // "1,500,000 د.ع"

// تحويل إلى كلمات
iraqi_currency_to_words(1500000); // "مليون وخمسمائة ألف دينار عراقي"

// تحويل العملات
convert_to_iraqi_dinar(100, 'USD'); // 132000 (تقريباً)
```

### خدمة أرقام الهواتف

```php
// التحقق من صحة الرقم
validate_iraqi_phone('07801234567'); // true

// تنسيق الرقم
format_iraqi_phone('07801234567'); // "+964 780 123 4567"

// اكتشاف الشركة
detect_iraqi_operator('07801234567'); 
// ['name_ar' => 'زين العراق', 'code' => 'ZI']
```

### خدمة النظام الضريبي

```php
// حساب ضريبة الدخل
$tax = calculate_iraqi_income_tax(2000000);
// ['total_tax' => 75000, 'net_income' => 1925000, ...]

// حساب ضريبة الشركات
$corporateTax = calculate_iraqi_corporate_tax(5000000, ['expenses' => 1000000]);

// التحقق من الرقم الضريبي
validate_iraqi_tin('123456789'); // true
```

### خدمة البنوك العراقية

```php
// الحصول على جميع البنوك
$banks = get_iraqi_banks();

// البحث عن بنك بالرمز
$bank = get_iraqi_bank_by_code('RB'); // مصرف الرافدين

// التحقق من IBAN
validate_iraqi_iban('IQ98RAFI1234567890123456'); // true
```

### خدمة التقارير الحكومية

```php
// تقرير ضريبي شهري
$taxReport = generate_tax_report([
    'company' => $companyData,
    'financial' => $financialData,
    'tax' => $taxData
]);

// تقرير الأدوية لوزارة الصحة
$pharmReport = generate_pharmaceutical_report($pharmacyData);

// تقرير الموظفين لوزارة العمل
$employeeReport = generate_employee_report($employeeData);
```

## التكوين

### ملف التكوين الرئيسي (`config/iraq.php`)

```php
return [
    'enabled' => true,
    'currency' => [
        'code' => 'IQD',
        'symbol' => 'د.ع',
        'exchange_rates' => [
            'USD' => 1320,
            'EUR' => 1452,
            // ...
        ],
    ],
    'tax' => [
        'corporate_tax_rate' => 0.15,
        'income_tax_brackets' => [
            // شرائح ضريبة الدخل
        ],
    ],
    // المزيد من الإعدادات...
];
```

## الخدمات المتاحة

### 1. IraqCurrencyService

- تنسيق العملة العراقية
- تحويل المبالغ إلى كلمات عربية
- تحويل العملات
- معلومات العملة

### 2. IraqTaxService

- حساب ضريبة الدخل المتدرجة
- حساب ضريبة الشركات
- حساب ضريبة الاستقطاع
- التحقق من الأرقام الضريبية

### 3. IraqPhoneService

- التحقق من أرقام الهواتف العراقية
- تنسيق الأرقام
- اكتشاف شركات الاتصالات
- رموز المدن العراقية

### 4. IraqBankingService

- قاعدة بيانات البنوك العراقية
- التحقق من IBAN
- أنظمة الدفع المحلية
- رموز SWIFT

### 5. IraqGovernmentReportsService

- تقارير الهيئة العامة للضرائب
- تقارير وزارة الصحة
- تقارير وزارة العمل
- تقارير الجمارك

## الدوال المساعدة

جميع الخدمات متاحة عبر دوال مساعدة سهلة الاستخدام:

```php
// العملة
format_iraqi_currency($amount)
iraqi_currency_to_words($amount)

// الهواتف
validate_iraqi_phone($phone)
format_iraqi_phone($phone)

// الضرائب
calculate_iraqi_income_tax($income)
validate_iraqi_tin($tin)

// البنوك
get_iraqi_banks()
validate_iraqi_iban($iban)

// التقارير
generate_tax_report($data)
```

## قوالب الفواتير

### الفاتورة الحكومية

قالب فاتورة متوافق مع المتطلبات الحكومية العراقية:

- تصميم احترافي باللغة العربية
- معلومات ضريبية مطلوبة
- رمز QR للتحقق
- تواقيع وأختام
- تنسيق مناسب للطباعة

## الاختبار

زر صفحة الاختبار:

```
http://your-domain.com/test-iraq-localization.html
```

## الدعم والتطوير

هذه الطبقة قابلة للتوسع والتخصيص حسب احتياجاتك. يمكنك:

- إضافة بنوك جديدة
- تحديث أسعار الصرف
- إضافة تقارير جديدة
- تخصيص قوالب الفواتير

## الترخيص

هذه الطبقة جزء من نظام MaxCon ERP وتخضع لنفس شروط الترخيص.
