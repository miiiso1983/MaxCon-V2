# تعليمات تطبيق Migration على الخادم

## المشكلة
```
SQLSTATE[42S22]: Column not found: 1054 Unknown column 'currency' in 'SET'
```

## الحل
يجب تطبيق الـ migration الجديد على الخادم لإضافة الأعمدة المفقودة.

## الخطوات المطلوبة على الخادم:

### 1. سحب آخر التحديثات
```bash
cd /path/to/project
git pull origin main
```

### 2. تطبيق الـ Migrations
```bash
php artisan migrate
```

### 3. مسح الكاش
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

## الـ Migration الجديد
- **الملف:** `2025_08_05_213152_add_currency_and_category_to_suppliers_table_safe.php`
- **الوظيفة:** إضافة عمودي `currency` و `category` لجدول `suppliers`
- **الأمان:** يتحقق من وجود الأعمدة قبل إضافتها لتجنب الأخطاء

## الأعمدة المضافة:
1. **currency** - نوع: VARCHAR(3) - افتراضي: 'IQD'
2. **category** - نوع: VARCHAR - قابل للقيم الفارغة

## التحقق من نجاح العملية:
```bash
php artisan tinker
```

ثم تشغيل:
```php
use Illuminate\Support\Facades\Schema;
echo Schema::hasColumn('suppliers', 'currency') ? 'Currency exists' : 'Currency missing';
echo Schema::hasColumn('suppliers', 'category') ? 'Category exists' : 'Category missing';
```

## ملاحظات مهمة:
- الـ migration آمن ولن يسبب أخطاء حتى لو تم تشغيله مرتين
- الكنترولر محدث للتعامل مع حالة عدم وجود الأعمدة مؤقتاً
- بعد تطبيق الـ migration، ستعمل صفحة تعديل المورد بشكل طبيعي

## في حالة استمرار المشكلة:
1. تحقق من أن الـ migration تم تطبيقه: `php artisan migrate:status`
2. تحقق من بنية الجدول: `DESCRIBE suppliers;` (في MySQL)
3. تأكد من أن الخادم يستخدم نفس قاعدة البيانات المحددة في `.env`
