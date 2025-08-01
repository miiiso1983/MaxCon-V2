# 🔧 تعليمات حل مشكلة عدم ظهور دليل المستأجر الجديد

## 📋 المشكلة
لا يظهر رابط "دليل المستأجر الجديد" في القائمة الجانبية تحت وحدة "كيفية استخدام النظام"

## 🎯 الحلول المرتبة حسب الأولوية

### 1. مسح الكاش (الأهم!)
```bash
php artisan route:clear
php artisan cache:clear
php artisan view:clear
php artisan config:clear
php artisan optimize:clear
```

### 2. إعادة تشغيل الخادم
```bash
# أوقف الخادم (Ctrl+C)
php artisan serve
```

### 3. مسح كاش المتصفح
- **Windows**: `Ctrl + F5`
- **Mac**: `Cmd + Shift + R`
- **أو افتح نافذة خاصة/متخفية**

### 4. التأكد من تسجيل الدخول الصحيح
- تأكد من تسجيل الدخول كـ **مستأجر** وليس كمدير عام
- الرابط يظهر فقط في لوحة تحكم المستأجر

## 🧪 اختبارات للتحقق

### اختبار 1: الرابط المباشر
```
http://localhost:8000/tenant/system-guide/new-tenant-guide
```
**إذا عمل**: المشكلة في الكاش أو القائمة الجانبية
**إذا لم يعمل**: مشكلة في الراوت أو الكونترولر

### اختبار 2: الرابط التجريبي المؤقت
```
http://localhost:8000/test-new-tenant-guide-direct
```
**إذا عمل**: المشكلة في الراوت الأصلي
**إذا لم يعمل**: مشكلة في ملف العرض

### اختبار 3: صفحة التشخيص
```
http://localhost:8000/debug-routes.php
http://localhost:8000/simple-test.html
```

## 📁 التحقق من الملفات

### ملفات يجب أن تكون موجودة:
- ✅ `routes/web.php` (يحتوي على مجموعة tenant)
- ✅ `routes/tenant/system-guide.php` (يحتوي على الراوت)
- ✅ `app/Http/Controllers/Tenant/SystemGuideController.php` (يحتوي على دالة newTenantGuide)
- ✅ `resources/views/tenant/system-guide/new-tenant-guide.blade.php`
- ✅ `resources/views/layouts/tenant.blade.php` (يحتوي على الرابط)

### فحص محتوى الملفات:

#### في `routes/web.php` (حوالي السطر 500):
```php
// System Guide Module
Route::prefix('system-guide')->name('system-guide.')->group(function () {
    require __DIR__ . '/tenant/system-guide.php';
});
```

#### في `routes/tenant/system-guide.php`:
```php
// New Tenant Guide
Route::get('/new-tenant-guide', [SystemGuideController::class, 'newTenantGuide'])->name('new-tenant-guide');
```

#### في `resources/views/layouts/tenant.blade.php` (حوالي السطر 654):
```php
<a href="{{ route('tenant.system-guide.new-tenant-guide') }}" class="submenu-item">
    <i class="fas fa-rocket"></i>
    دليل المستأجر الجديد
</a>
```

## 🚨 إذا لم تعمل الحلول السابقة

### فحص سجل الأخطاء:
```bash
tail -f storage/logs/laravel.log
```

### فحص الراوتات المسجلة:
```bash
php artisan route:list | grep system-guide
```

### إعادة تثبيت الاعتماديات:
```bash
composer dump-autoload
```

## 🎯 الحل النهائي (إذا فشل كل شيء)

### إنشاء راوت مباشر مؤقت في `routes/web.php`:
```php
Route::get('/tenant/system-guide/new-tenant-guide-temp', function () {
    return view('tenant.system-guide.new-tenant-guide', [
        'setupSteps' => [],
        'modules' => [],
        'checklist' => [],
        'timeline' => []
    ]);
})->name('tenant.system-guide.new-tenant-guide-temp');
```

### تحديث الرابط في القائمة الجانبية مؤقتاً:
```php
<a href="{{ route('tenant.system-guide.new-tenant-guide-temp') }}" class="submenu-item">
```

## 📞 للدعم الفني
إذا لم تعمل أي من الحلول السابقة، أرسل:
1. لقطة شاشة من القائمة الجانبية
2. نتيجة `php artisan route:list | grep system-guide`
3. محتوى ملف `storage/logs/laravel.log` (آخر 50 سطر)
4. نتيجة اختبار الروابط المباشرة

## ✅ علامات النجاح
- ظهور رابط "دليل المستأجر الجديد" في القائمة الجانبية
- عمل الرابط عند النقر عليه
- ظهور الصفحة التفاعلية مع جميع المحتويات
- عمل تتبع التقدم وحفظ البيانات في المتصفح

---
**آخر تحديث**: ديسمبر 2024
**الإصدار**: MaxCon ERP v2.0
