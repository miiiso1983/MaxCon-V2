# دليل نشر MaxCon ERP على Cloudways

## المتطلبات الأساسية

### 1. حساب Cloudways
- إنشاء حساب على [Cloudways](https://www.cloudways.com)
- اختيار خطة مناسبة (يُنصح بـ DigitalOcean أو AWS)

### 2. إعدادات الخادم المطلوبة
- **PHP**: 8.2 أو أحدث
- **MySQL**: 8.0 أو أحدث
- **Redis**: للتخزين المؤقت
- **SSL Certificate**: مجاني من Let's Encrypt

## خطوات النشر

### الخطوة 1: إنشاء التطبيق على Cloudways

1. **تسجيل الدخول** إلى لوحة تحكم Cloudways
2. **إنشاء خادم جديد**:
   - اختر **DigitalOcean** أو **AWS**
   - حدد **PHP** كنوع التطبيق
   - اختر **PHP 8.2**
   - حدد حجم الخادم (2GB RAM على الأقل)
   - اختر المنطقة الأقرب لك

3. **إنشاء التطبيق**:
   - اسم التطبيق: `maxcon-erp`
   - نوع التطبيق: **PHP**
   - إصدار PHP: **8.2**

### الخطوة 2: ربط GitHub Repository

1. **في لوحة تحكم Cloudways**:
   - اذهب إلى تطبيقك
   - اختر **Git Deployment**
   - أدخل رابط المستودع: `https://github.com/miiiso1983/MaxCon-V2.git`
   - اختر الفرع: `main`
   - فعّل **Auto Deployment** (اختياري)

2. **إعداد SSH Key** (إذا كان المستودع خاص):
   - انسخ SSH Public Key من Cloudways
   - أضفه إلى GitHub Deploy Keys

### الخطوة 3: إعداد قاعدة البيانات

1. **إنشاء قواعد البيانات**:
   ```sql
   -- قاعدة البيانات المركزية
   CREATE DATABASE maxcon_central;
   
   -- قاعدة البيانات الرئيسية
   CREATE DATABASE maxcon_erp;
   ```

2. **إنشاء مستخدم قاعدة البيانات**:
   ```sql
   CREATE USER 'maxcon_user'@'localhost' IDENTIFIED BY 'strong_password_here';
   GRANT ALL PRIVILEGES ON maxcon_central.* TO 'maxcon_user'@'localhost';
   GRANT ALL PRIVILEGES ON maxcon_erp.* TO 'maxcon_user'@'localhost';
   FLUSH PRIVILEGES;
   ```

### الخطوة 4: تكوين متغيرات البيئة

1. **في لوحة تحكم Cloudways**:
   - اذهب إلى **Application Settings**
   - اختر **Environment Variables**

2. **أضف المتغيرات التالية**:
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
   DB_PASSWORD=your_database_password
   
   CENTRAL_DB_CONNECTION=mysql
   CENTRAL_DB_HOST=localhost
   CENTRAL_DB_PORT=3306
   CENTRAL_DB_DATABASE=maxcon_central
   CENTRAL_DB_USERNAME=maxcon_user
   CENTRAL_DB_PASSWORD=your_database_password
   
   CACHE_STORE=redis
   REDIS_HOST=127.0.0.1
   REDIS_PORT=6379
   
   MAIL_MAILER=smtp
   MAIL_HOST=smtp.gmail.com
   MAIL_PORT=587
   MAIL_USERNAME=your_email@gmail.com
   MAIL_PASSWORD=your_app_password
   MAIL_ENCRYPTION=tls
   ```

### الخطوة 5: النشر الأولي

1. **SSH إلى الخادم**:
   ```bash
   ssh master@your-server-ip
   cd applications/your-app/public_html
   ```

2. **تشغيل سكريبت النشر**:
   ```bash
   chmod +x deploy.sh
   ./deploy.sh
   ```

3. **إنشاء APP_KEY**:
   ```bash
   php artisan key:generate --force
   ```

4. **تشغيل المايجريشن**:
   ```bash
   php artisan migrate --force
   php artisan db:seed --force
   ```

### الخطوة 6: إعداد SSL والدومين

1. **في لوحة تحكم Cloudways**:
   - اذهب إلى **SSL Certificate**
   - اختر **Let's Encrypt**
   - أدخل اسم النطاق
   - فعّل **Force HTTPS Redirection**

2. **إعداد DNS**:
   - أشر A Record إلى IP الخادم
   - أشر CNAME للـ www إلى النطاق الرئيسي

### الخطوة 7: تحسين الأداء

1. **تفعيل Redis**:
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

2. **إعداد Cron Jobs**:
   ```bash
   # في crontab
   * * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
   ```

3. **إعداد Queue Worker**:
   ```bash
   # في Supervisor
   php artisan queue:work --sleep=3 --tries=3
   ```

## الصيانة والتحديث

### النشر التلقائي
- فعّل Auto Deployment في Cloudways
- كل push إلى main سيؤدي إلى نشر تلقائي

### النشر اليدوي
```bash
cd applications/your-app/public_html
git pull origin main
./deploy.sh
```

### النسخ الاحتياطي
- فعّل النسخ الاحتياطي التلقائي في Cloudways
- جدولة نسخ احتياطية يومية لقاعدة البيانات

## استكشاف الأخطاء

### مشاكل شائعة:
1. **خطأ 500**: تحقق من logs في `storage/logs/laravel.log`
2. **مشاكل الصلاحيات**: `chmod -R 755 storage bootstrap/cache`
3. **مشاكل قاعدة البيانات**: تحقق من إعدادات الاتصال
4. **مشاكل Redis**: تأكد من تشغيل Redis service

### أوامر مفيدة:
```bash
# مراقبة اللوجز
tail -f storage/logs/laravel.log

# إعادة تشغيل الخدمات
sudo service nginx restart
sudo service php8.2-fpm restart

# تنظيف الكاش
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

## الأمان

### إعدادات مهمة:
- تغيير كلمات المرور الافتراضية
- تفعيل Two-Factor Authentication
- تحديث PHP وMySQL بانتظام
- مراقبة لوجز الأمان
- إعداد Firewall rules

### النسخ الاحتياطي:
- نسخ احتياطية يومية لقاعدة البيانات
- نسخ احتياطية أسبوعية للملفات
- اختبار استعادة النسخ الاحتياطية

## الدعم

للحصول على المساعدة:
- وثائق Cloudways: https://support.cloudways.com
- وثائق Laravel: https://laravel.com/docs
- مجتمع Laravel: https://laracasts.com
