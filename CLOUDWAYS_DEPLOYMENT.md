# MaxCon ERP - دليل النشر على Cloudways

## 📋 المتطلبات الأساسية
- حساب Cloudways نشط
- نطاق (اختياري لكن مُوصى به)
- مستودع Git (GitHub/GitLab/Bitbucket)

## 🚀 الخطوة 1: إنشاء خادم جديد

### 1.1 تسجيل الدخول إلى Cloudways
```
الرابط: https://platform.cloudways.com
```

### 1.2 إعدادات الخادم المُوصى بها
```
مقدم الخدمة: DigitalOcean
حجم الخادم: 2GB RAM, 1 vCPU, 50GB SSD
إصدار PHP: 8.2
الموقع: اختر الأقرب لمستخدميك
اسم الخادم: MaxCon-ERP-Production
```

### 1.3 إنشاء التطبيق
```
اسم التطبيق: maxcon-erp
نوع التطبيق: Custom PHP Application
```

## 🗄️ الخطوة 2: إعداد قاعدة البيانات

### 2.1 إنشاء قواعد البيانات
```sql
-- قاعدة البيانات المركزية
CREATE DATABASE maxcon_central CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- قاعدة البيانات الرئيسية
CREATE DATABASE maxcon_erp CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 2.2 إنشاء مستخدم قاعدة البيانات
```sql
CREATE USER 'maxcon_user'@'localhost' IDENTIFIED BY 'secure_password_here';
GRANT ALL PRIVILEGES ON maxcon_central.* TO 'maxcon_user'@'localhost';
GRANT ALL PRIVILEGES ON maxcon_erp.* TO 'maxcon_user'@'localhost';
FLUSH PRIVILEGES;
```

## 📁 الخطوة 3: رفع ملفات المشروع

### 3.1 الطريقة الأولى: Git (مُوصى بها)
```bash
# في Cloudways Dashboard
# اذهب إلى "Deployment via Git"
# أضف رابط المستودع
# اختر الفرع: main
# انقر Deploy
```

### 3.2 الطريقة الثانية: SFTP
```bash
# إنشاء أرشيف للمشروع
tar -czf maxcon-erp.tar.gz \
  --exclude=node_modules \
  --exclude=.git \
  --exclude=storage/logs/* \
  --exclude=.env \
  .

# رفع عبر SFTP إلى /public_html/
```

## ⚙️ الخطوة 4: إعداد متغيرات البيئة

### 4.1 إنشاء ملف .env
```bash
# الاتصال عبر SSH
ssh master@your-server-ip

# الانتقال إلى مجلد التطبيق
cd applications/maxcon-erp/public_html

# نسخ ملف البيئة
cp .env.example .env
```

### 4.2 تحديث ملف .env
```env
APP_NAME="MaxCon ERP"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://yourdomain.com

APP_LOCALE=ar
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=ar_SA

# إعدادات قاعدة البيانات
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=maxcon_erp
DB_USERNAME=maxcon_user
DB_PASSWORD=your_secure_password

# قاعدة البيانات المركزية
CENTRAL_DB_CONNECTION=mysql
CENTRAL_DB_HOST=localhost
CENTRAL_DB_PORT=3306
CENTRAL_DB_DATABASE=maxcon_central
CENTRAL_DB_USERNAME=maxcon_user
CENTRAL_DB_PASSWORD=your_secure_password

# الجلسات والتخزين المؤقت
SESSION_DRIVER=database
SESSION_LIFETIME=120
CACHE_STORE=database
QUEUE_CONNECTION=database

# إعدادات البريد الإلكتروني
MAIL_MAILER=smtp
MAIL_HOST=smtp.cloudways.com
MAIL_PORT=587
MAIL_USERNAME=your_email@yourdomain.com
MAIL_PASSWORD=your_email_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@yourdomain.com"
MAIL_FROM_NAME="${APP_NAME}"

# إعدادات الأمان
TENANCY_ENABLED=true
CENTRAL_DOMAIN=yourdomain.com
```

## 🔧 الخطوة 5: تثبيت التبعيات والتحسين

### 5.1 تثبيت Composer
```bash
# تثبيت تبعيات PHP
composer install --optimize-autoloader --no-dev

# توليد مفتاح التطبيق
php artisan key:generate
```

### 5.2 تثبيت NPM وبناء الأصول
```bash
# تثبيت تبعيات Node.js
npm install

# بناء الأصول للإنتاج
npm run build
```

### 5.3 تحسين الأداء
```bash
# تخزين الإعدادات مؤقتاً
php artisan config:cache

# تخزين المسارات مؤقتاً
php artisan route:cache

# تخزين العروض مؤقتاً
php artisan view:cache

# تخزين الأحداث مؤقتاً
php artisan event:cache
```

### 5.4 إعداد الصلاحيات
```bash
# تعيين صلاحيات المجلدات
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# إنشاء رابط التخزين
php artisan storage:link
```

## 🗃️ الخطوة 6: إعداد قاعدة البيانات

### 6.1 تشغيل Migration
```bash
# تشغيل جميع migrations
php artisan migrate --force

# التحقق من حالة migrations
php artisan migrate:status
```

### 6.2 إدخال البيانات الأولية
```bash
# تشغيل seeders
php artisan db:seed --class=RolePermissionSeeder

# إنشاء مستخدم super admin
php artisan tinker
```

### 6.3 إنشاء Super Admin (في Tinker)
```php
use App\Models\User;
use Spatie\Permission\Models\Role;

$user = User::create([
    'name' => 'Super Admin',
    'email' => 'admin@maxcon.com',
    'password' => bcrypt('your-secure-password'),
    'email_verified_at' => now(),
]);

$superAdminRole = Role::where('name', 'super-admin')->first();
$user->assignRole($superAdminRole);

echo "Super Admin created successfully!";
exit;
```

## 🌐 الخطوة 7: إعداد النطاق و SSL

### 7.1 إضافة النطاق
```
في Cloudways Dashboard:
1. اذهب إلى "Domain Management"
2. أضف النطاق الرئيسي: yourdomain.com
3. أضف النطاق الفرعي: *.yourdomain.com
```

### 7.2 إعداد SSL
```
1. فعّل "Let's Encrypt SSL"
2. تأكد من تضمين شهادة wildcard للنطاقات الفرعية
```

### 7.3 إعداد DNS
```
A Record: @ → عنوان IP الخادم
A Record: * → عنوان IP الخادم (للنطاقات الفرعية)
CNAME: www → yourdomain.com
```

## 🔧 الخطوة 8: إعدادات الخادم النهائية

### 8.1 إعدادات خادم الويب
```
Document Root: /public_html/public
PHP Version: 8.2
Enable OPcache: Yes
```

### 8.2 إعداد Cron Jobs
```bash
# إضافة إلى crontab
* * * * * cd /home/master/applications/maxcon-erp/public_html && php artisan schedule:run >> /dev/null 2>&1
```

### 8.3 تحسينات إضافية (اختيارية)
```bash
# تفعيل Redis إذا كان متاحاً
# تحديث .env
CACHE_STORE=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
```

## ✅ الخطوة 9: الاختبار والتحقق

### 9.1 اختبار التطبيق الرئيسي
```
1. زيارة: https://yourdomain.com
2. تسجيل الدخول بحساب super admin
3. التحقق من لوحة التحكم
```

### 9.2 اختبار نظام المستأجرين المتعددين
```
1. إنشاء مستأجر تجريبي
2. زيارة: https://tenant.yourdomain.com
3. التحقق من عزل البيانات
```

### 9.3 اختبار الوظائف الأساسية
```
- إدارة المستخدمين
- الأدوار والصلاحيات
- اتصالات قاعدة البيانات
- رفع الملفات
- إرسال البريد الإلكتروني
```

## 🔒 إعدادات الأمان

### 10.1 تأمين ملف .env
```bash
chmod 600 .env
```

### 10.2 إعداد Firewall
```
في Cloudways Dashboard:
1. اذهب إلى "Security"
2. فعّل Firewall
3. أضف قواعد الأمان المطلوبة
```

### 10.3 تفعيل Fail2Ban
```
1. اذهب إلى "Security" في Cloudways
2. فعّل Fail2Ban
3. اضبط عدد المحاولات المسموحة
```

## 💾 استراتيجية النسخ الاحتياطية

### 11.1 النسخ الاحتياطية التلقائية
```
في Cloudways Dashboard:
1. اذهب إلى "Backups"
2. فعّل النسخ الاحتياطية التلقائية
3. اختر التكرار: يومي
4. احتفظ بـ 7 نسخ على الأقل
```

### 11.2 النسخ الاحتياطية اليدوية
```bash
# نسخة احتياطية لقاعدة البيانات
mysqldump -u maxcon_user -p maxcon_erp > backup-$(date +%Y%m%d).sql
mysqldump -u maxcon_user -p maxcon_central > backup-central-$(date +%Y%m%d).sql

# نسخة احتياطية للملفات
tar -czf backup-files-$(date +%Y%m%d).tar.gz public_html/
```

## 🚨 استكشاف الأخطاء وإصلاحها

### مشاكل الصلاحيات
```bash
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### مشاكل النطاقات الفرعية
```
1. تحقق من إعداد DNS wildcard
2. تأكد من إعدادات virtual host
3. تحقق من شهادة SSL wildcard
```

### مشاكل اتصال قاعدة البيانات
```
1. تحقق من بيانات الاعتماد في .env
2. تأكد من صلاحيات مستخدم قاعدة البيانات
3. اختبر الاتصال: php artisan tinker
```

---

## 📞 الدعم والمساعدة

إذا واجهت أي مشاكل أثناء النشر، يمكنك:
1. مراجعة سجلات الأخطاء في `/storage/logs/`
2. التحقق من سجلات خادم الويب
3. استخدام أدوات مراقبة Cloudways
4. التواصل مع دعم Cloudways الفني

**🎉 تهانينا! مشروع MaxCon ERP جاهز للعمل على Cloudways**
