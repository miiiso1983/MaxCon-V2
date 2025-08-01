# 🌐 تعليمات حل مشكلة دليل المستأجر الجديد - الخادم المباشر

## 📍 معلومات الخادم
- **الموقع**: https://www.maxcon.app
- **النوع**: خادم مباشر (Production)
- **المشكلة**: عدم ظهور رابط "دليل المستأجر الجديد" في القائمة الجانبية

## 🎯 الحلول المرتبة حسب الأولوية (للخادم المباشر)

### 1. مسح الكاش عبر cPanel أو SSH
```bash
# إذا كان لديك وصول SSH
cd /path/to/maxcon-app
php artisan route:clear
php artisan cache:clear
php artisan view:clear
php artisan config:clear
php artisan optimize:clear
```

### 2. مسح الكاش عبر cPanel
- اذهب إلى **cPanel** → **File Manager**
- احذف مجلد `bootstrap/cache/`
- احذف ملفات `storage/framework/cache/`
- احذف ملفات `storage/framework/views/`

### 3. إعادة تشغيل الخدمات
- في **cPanel** → **PHP Selector** → أعد تشغيل PHP
- أو اتصل بمزود الاستضافة لإعادة تشغيل الخادم

### 4. مسح كاش المتصفح
- **Windows**: `Ctrl + F5`
- **Mac**: `Cmd + Shift + R`
- **أو استخدم نافذة خاصة/متخفية**

## 🧪 اختبارات للخادم المباشر

### اختبار 1: الرابط المباشر
```
https://www.maxcon.app/tenant/system-guide/new-tenant-guide
```
**إذا عمل**: المشكلة في الكاش أو القائمة الجانبية
**إذا لم يعمل**: مشكلة في الراوت أو الكونترولر

### اختبار 2: الرابط التجريبي المؤقت
```
https://www.maxcon.app/test-new-tenant-guide-direct
```

### اختبار 3: صفحات التشخيص
```
https://www.maxcon.app/simple-test.html
https://www.maxcon.app/debug-routes.php
https://www.maxcon.app/LIVE-SERVER-INSTRUCTIONS.md
```

### اختبار 4: الأدلة الإضافية
```
https://www.maxcon.app/دليل_المستأجر_التفاعلي.html
https://www.maxcon.app/دليل_المستأجر_للطباعة.html
```

## 📁 التحقق من الملفات على الخادم المباشر

### ملفات يجب أن تكون موجودة:
- ✅ `routes/web.php`
- ✅ `routes/tenant/system-guide.php`
- ✅ `app/Http/Controllers/Tenant/SystemGuideController.php`
- ✅ `resources/views/tenant/system-guide/new-tenant-guide.blade.php`
- ✅ `resources/views/layouts/tenant.blade.php`

### فحص الملفات عبر cPanel:
1. **File Manager** → انتقل إلى مجلد الموقع
2. تحقق من وجود الملفات المذكورة أعلاه
3. تحقق من أن الملفات تحتوي على المحتوى الصحيح

## 🚨 حلول خاصة بالخادم المباشر

### إذا لم تعمل الحلول السابقة:

#### 1. رفع الملفات مرة أخرى
- حمّل آخر إصدار من GitHub
- ارفع الملفات المحدثة عبر FTP أو File Manager

#### 2. فحص صلاحيات الملفات
```bash
# الصلاحيات المطلوبة
chmod 644 routes/web.php
chmod 644 routes/tenant/system-guide.php
chmod 644 app/Http/Controllers/Tenant/SystemGuideController.php
chmod 644 resources/views/tenant/system-guide/new-tenant-guide.blade.php
chmod 644 resources/views/layouts/tenant.blade.php
```

#### 3. فحص سجل الأخطاء
- **cPanel** → **Error Logs**
- أو تحقق من `storage/logs/laravel.log`

#### 4. تحديث Composer (إذا أمكن)
```bash
composer dump-autoload
composer install --optimize-autoloader --no-dev
```

## 🔧 حل سريع للخادم المباشر

### إنشاء راوت مباشر في `routes/web.php`:
```php
// إضافة هذا الكود في نهاية ملف routes/web.php
Route::get('/tenant/system-guide/new-tenant-guide-fix', function () {
    return view('tenant.system-guide.new-tenant-guide', [
        'setupSteps' => [
            [
                'id' => 1,
                'title' => 'إعداد معلومات الشركة',
                'description' => 'إدخال البيانات الأساسية للشركة',
                'icon' => 'fas fa-building',
                'color' => '#667eea',
                'estimated_time' => '30 دقيقة',
                'tasks' => [
                    'إدخال اسم الشركة والعنوان',
                    'رفع شعار الشركة',
                    'تحديد العملة والمنطقة الزمنية'
                ]
            ]
        ],
        'modules' => [],
        'checklist' => [
            'basic_setup' => [
                'title' => 'الإعداد الأساسي',
                'items' => [
                    ['id' => 'company_info', 'text' => 'إعداد معلومات الشركة', 'completed' => false]
                ]
            ]
        ],
        'timeline' => [
            [
                'week' => 1,
                'title' => 'الأسبوع الأول',
                'color' => '#667eea',
                'days' => [
                    [
                        'day' => '1-2',
                        'title' => 'إعداد النظام',
                        'tasks' => ['تسجيل الدخول الأول']
                    ]
                ]
            ]
        ]
    ]);
})->middleware(['auth'])->name('tenant.system-guide.new-tenant-guide-fix');
```

### تحديث الرابط في القائمة الجانبية مؤقتاً:
```php
// في resources/views/layouts/tenant.blade.php
<a href="{{ route('tenant.system-guide.new-tenant-guide-fix') }}" class="submenu-item">
    <i class="fas fa-rocket"></i>
    دليل المستأجر الجديد
</a>
```

## 📞 للدعم الفني (الخادم المباشر)

### معلومات مطلوبة:
1. **لقطة شاشة** من القائمة الجانبية
2. **نتيجة اختبار الروابط** المباشرة
3. **سجل الأخطاء** من cPanel
4. **معلومات الاستضافة** (نوع الخادم، إصدار PHP)

### روابط الاختبار السريع:
```
https://www.maxcon.app/simple-test.html
https://www.maxcon.app/tenant/system-guide/new-tenant-guide
https://www.maxcon.app/test-new-tenant-guide-direct
```

## ✅ خطوات التحقق النهائية

### 1. تسجيل الدخول:
- اذهب إلى: https://www.maxcon.app
- سجل دخول كمستأجر (وليس كمدير عام)

### 2. فحص القائمة الجانبية:
- ابحث عن "كيفية استخدام النظام"
- اضغط لفتح القائمة الفرعية
- ابحث عن "دليل المستأجر الجديد" 🚀

### 3. إذا لم يظهر:
- جرب الرابط المباشر أولاً
- امسح كاش المتصفح
- جرب متصفح آخر أو نافذة خاصة

## 🎯 النتيجة المتوقعة
- ظهور رابط "دليل المستأجر الجديد" في القائمة الجانبية
- عمل الرابط عند النقر عليه
- ظهور الصفحة التفاعلية مع جميع المحتويات
- عمل تتبع التقدم وحفظ البيانات

---
**الموقع**: https://www.maxcon.app
**آخر تحديث**: ديسمبر 2024
**الإصدار**: MaxCon ERP v2.0
