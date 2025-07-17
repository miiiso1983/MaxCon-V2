# إعداد سريع لـ MaxCon ERP على Cloudways

## الخطوات السريعة للنشر

### 1. إنشاء الخادم على Cloudways

```
1. سجل دخول إلى Cloudways
2. اضغط "Launch Server"
3. اختر:
   - Cloud Provider: DigitalOcean
   - Server Size: 2GB RAM (أو أكثر)
   - Location: أقرب منطقة لك
   - Application: PHP 8.2
   - Server Name: maxcon-server
   - App Name: maxcon-erp
```

### 2. ربط GitHub Repository

```
1. اذهب إلى Application Management
2. اختر Git Deployment
3. أدخل Repository URL: https://github.com/miiiso1983/MaxCon-V2.git
4. Branch: main
5. اضغط Deploy
```

### 3. إعداد قاعدة البيانات

```sql
-- اتصل بـ phpMyAdmin أو MySQL
CREATE DATABASE maxcon_central CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE DATABASE maxcon_erp CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- إنشاء مستخدم
CREATE USER 'maxcon_user'@'localhost' IDENTIFIED BY 'كلمة_مرور_قوية';
GRANT ALL PRIVILEGES ON maxcon_central.* TO 'maxcon_user'@'localhost';
GRANT ALL PRIVILEGES ON maxcon_erp.* TO 'maxcon_user'@'localhost';
FLUSH PRIVILEGES;
```

### 4. إعداد متغيرات البيئة

في Cloudways Application Settings > Environment Variables:

```
APP_NAME=MaxCon ERP
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=maxcon_erp
DB_USERNAME=maxcon_user
DB_PASSWORD=كلمة_المرور_التي_أنشأتها

CENTRAL_DB_CONNECTION=mysql
CENTRAL_DB_HOST=localhost
CENTRAL_DB_PORT=3306
CENTRAL_DB_DATABASE=maxcon_central
CENTRAL_DB_USERNAME=maxcon_user
CENTRAL_DB_PASSWORD=كلمة_المرور_التي_أنشأتها

CACHE_STORE=redis
REDIS_HOST=127.0.0.1
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@your-domain.com
```

### 5. تشغيل الأوامر الأولية

SSH إلى الخادم وتشغيل:

```bash
cd applications/maxcon-erp/public_html

# إنشاء APP_KEY
php artisan key:generate --force

# تشغيل المايجريشن
php artisan migrate --force

# تشغيل السيدرز
php artisan db:seed --force

# تشغيل سكريبت النشر
chmod +x deploy.sh
./deploy.sh
```

### 6. إعداد SSL والدومين

```
1. في Cloudways اذهب إلى SSL Certificate
2. اختر Let's Encrypt SSL
3. أدخل اسم النطاق
4. فعّل Force HTTPS Redirection
```

### 7. إعداد DNS

```
في إعدادات النطاق:
- A Record: @ -> IP الخادم
- CNAME Record: www -> اسم النطاق
```

## أوامر مفيدة للصيانة

### تحديث التطبيق
```bash
cd applications/maxcon-erp/public_html
git pull origin main
./deploy.sh
```

### مراقبة اللوجز
```bash
tail -f storage/logs/laravel.log
```

### تنظيف الكاش
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### إعادة تشغيل الخدمات
```bash
sudo service nginx restart
sudo service php8.2-fpm restart
sudo service redis-server restart
```

## معلومات مهمة

### بيانات الدخول الافتراضية
```
Super Admin:
Email: admin@maxcon.app
Password: password

Tenant Admin:
Email: tenant@example.com
Password: password
```

### المجلدات المهمة
```
- التطبيق: /applications/maxcon-erp/public_html/
- اللوجز: /applications/maxcon-erp/public_html/storage/logs/
- التخزين: /applications/maxcon-erp/public_html/storage/
```

### المنافذ
```
- HTTP: 80
- HTTPS: 443
- MySQL: 3306
- Redis: 6379
- SSH: 22
```

## استكشاف الأخطاء

### خطأ 500
```bash
# تحقق من اللوجز
tail -f storage/logs/laravel.log

# تحقق من الصلاحيات
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### مشاكل قاعدة البيانات
```bash
# اختبار الاتصال
php artisan tinker
DB::connection()->getPdo();
```

### مشاكل Redis
```bash
# اختبار Redis
redis-cli ping
```

## الدعم الفني

للحصول على المساعدة:
- Cloudways Support: https://support.cloudways.com
- Laravel Documentation: https://laravel.com/docs
- GitHub Issues: https://github.com/miiiso1983/MaxCon-V2/issues
