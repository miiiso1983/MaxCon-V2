# 🚀 MaxCon ERP - دليل النشر السريع على Cloudways

## 📋 نظرة عامة
هذا الدليل يوضح كيفية نشر مشروع MaxCon ERP على منصة Cloudways بطريقة سهلة ومبسطة.

## 🎯 الملفات المطلوبة للنشر
- `CLOUDWAYS_DEPLOYMENT.md` - دليل النشر الشامل
- `deploy-to-cloudways.sh` - سكريبت تحضير المشروع للنشر
- `cloudways-server-setup.sh` - سكريبت إعداد الخادم
- `.env.cloudways` - ملف البيئة المُحسن لـ Cloudways

## ⚡ النشر السريع (5 خطوات)

### الخطوة 1: تحضير المشروع محلياً
```bash
# تشغيل سكريبت التحضير
./deploy-to-cloudways.sh

# اختر الخيار 5 (Full deployment preparation)
```

### الخطوة 2: إنشاء خادم على Cloudways
1. اذهب إلى https://platform.cloudways.com
2. انقر "Launch Server"
3. اختر:
   - **Cloud Provider:** DigitalOcean
   - **Application:** PHP
   - **Server Size:** 2GB RAM (مُوصى به)
   - **Location:** أقرب موقع لمستخدميك

### الخطوة 3: إعداد قاعدة البيانات
```sql
-- في Cloudways Database Manager
CREATE DATABASE maxcon_central;
CREATE DATABASE maxcon_erp;
```

### الخطوة 4: رفع الملفات
```bash
# رفع الأرشيف المُنشأ من الخطوة 1 عبر SFTP
# أو استخدم Git Deployment في Cloudways
```

### الخطوة 5: إعداد الخادم
```bash
# SSH إلى الخادم وتشغيل:
cd applications/your-app/public_html
./cloudways-server-setup.sh
```

## 🔧 الإعدادات المطلوبة

### إعدادات الخادم
```
Document Root: /public_html/public
PHP Version: 8.2
Web Server: Apache/Nginx
```

### إعدادات قاعدة البيانات
```
Character Set: utf8mb4
Collation: utf8mb4_unicode_ci
```

### إعدادات النطاق
```
Primary Domain: yourdomain.com
Wildcard: *.yourdomain.com
SSL: Let's Encrypt (مجاني)
```

## 📊 مواصفات الخادم المُوصى بها

### للاستخدام التجريبي
```
RAM: 1GB
CPU: 1 vCPU
Storage: 25GB SSD
Bandwidth: 1TB
```

### للاستخدام الإنتاجي
```
RAM: 2GB أو أكثر
CPU: 1-2 vCPU
Storage: 50GB SSD
Bandwidth: 2TB
```

### للاستخدام المكثف
```
RAM: 4GB أو أكثر
CPU: 2-4 vCPU
Storage: 100GB SSD
Bandwidth: 3TB
```

## 🔐 إعدادات الأمان

### 1. تأمين ملف .env
```bash
chmod 600 .env
```

### 2. تفعيل Firewall
- اذهب إلى Security في Cloudways
- فعّل Firewall
- أضف القواعد المطلوبة

### 3. تفعيل SSL
- اذهب إلى SSL Certificate
- فعّل Let's Encrypt
- تأكد من تضمين wildcard

## 📧 إعداد البريد الإلكتروني

### استخدام SMTP الخاص بـ Cloudways
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.cloudways.com
MAIL_PORT=587
MAIL_USERNAME=your_email@yourdomain.com
MAIL_PASSWORD=your_email_password
MAIL_ENCRYPTION=tls
```

### استخدام خدمة خارجية (مُوصى به)
```env
# Gmail
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587

# SendGrid
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587

# Mailgun
MAIL_HOST=smtp.mailgun.org
MAIL_PORT=587
```

## 🔄 النسخ الاحتياطية

### تفعيل النسخ التلقائية
1. اذهب إلى Backups في Cloudways
2. فعّل Automated Backups
3. اختر التكرار: يومي
4. احتفظ بـ 7 نسخ على الأقل

### النسخ اليدوية
```bash
# نسخة احتياطية لقاعدة البيانات
mysqldump -u username -p maxcon_erp > backup.sql

# نسخة احتياطية للملفات
tar -czf backup.tar.gz public_html/
```

## 📈 مراقبة الأداء

### أدوات Cloudways المدمجة
- Server Monitoring
- Application Monitoring
- Real-time Monitoring
- Performance Insights

### إعداد التنبيهات
1. اذهب إلى Monitoring
2. فعّل Alerts
3. اضبط حدود الاستخدام
4. أضف بريدك الإلكتروني

## 🚨 استكشاف الأخطاء

### مشاكل شائعة وحلولها

#### 1. خطأ 500 Internal Server Error
```bash
# تحقق من سجلات الأخطاء
tail -f storage/logs/laravel.log

# تحقق من الصلاحيات
chmod -R 755 storage bootstrap/cache
```

#### 2. مشكلة اتصال قاعدة البيانات
```bash
# اختبار الاتصال
php artisan tinker
# ثم: DB::connection()->getPdo();
```

#### 3. مشكلة النطاقات الفرعية
```bash
# تحقق من إعدادات DNS
nslookup subdomain.yourdomain.com

# تحقق من إعدادات Apache/Nginx
```

#### 4. مشكلة SSL
```bash
# تجديد شهادة SSL
# في Cloudways Dashboard > SSL Certificate > Renew
```

## 📞 الدعم والمساعدة

### موارد مفيدة
- [Cloudways Documentation](https://support.cloudways.com/)
- [Laravel Documentation](https://laravel.com/docs)
- [MaxCon ERP Documentation](./DEPLOYMENT_GUIDE.md)

### طرق الحصول على المساعدة
1. **Cloudways Support:** دعم فني 24/7
2. **Community Forums:** منتديات المجتمع
3. **Documentation:** الوثائق الرسمية

## ✅ قائمة التحقق النهائية

### قبل النشر
- [ ] تحديث ملف .env بالإعدادات الصحيحة
- [ ] اختبار المشروع محلياً
- [ ] إنشاء نسخة احتياطية من البيانات
- [ ] تحضير أرشيف النشر

### بعد النشر
- [ ] اختبار الصفحة الرئيسية
- [ ] اختبار تسجيل الدخول
- [ ] اختبار النطاقات الفرعية
- [ ] اختبار إرسال البريد الإلكتروني
- [ ] تفعيل النسخ الاحتياطية
- [ ] إعداد المراقبة

## 🎉 تهانينا!

إذا اتبعت جميع الخطوات بنجاح، فإن مشروع MaxCon ERP الآن يعمل على Cloudways!

### الخطوات التالية
1. إضافة المستخدمين والمستأجرين
2. تخصيص الإعدادات حسب احتياجاتك
3. إضافة المزيد من الوحدات والميزات
4. مراقبة الأداء والاستخدام

---

**💡 نصيحة:** احتفظ بنسخة من هذا الدليل للرجوع إليه عند الحاجة!
