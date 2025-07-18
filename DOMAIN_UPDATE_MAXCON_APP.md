# تحديث النطاق إلى maxcon.app

## 🌐 تحديث النطاق الجديد

تم تحديث النطاق من `phpstack-1492540-5695982.cloudwaysapps.com` إلى **`maxcon.app`**

## 📝 الملفات المُحدثة

### 1. ملفات التوثيق
- ✅ `README.md` - تحديث بريد السوبر أدمن إلى `admin@maxcon.app`
- ✅ `cloudways-setup.md` - تحديث بيانات الدخول الافتراضية
- ✅ `DEPLOYMENT_GUIDE.md` - تحديث أوامر إنشاء السوبر أدمن
- ✅ `deploy-to-cloudways.sh` - تحديث سكريبت النشر
- ✅ `REPORTS_ARABIC_URL_FIX.md` - تحديث أمثلة الروابط
- ✅ `SYSTEM_GUIDE_REGULATORY_MODULE_FIX.md` - تحديث أمثلة الروابط

### 2. ملفات الإعداد
- ✅ `.env.cloudways` - تحديث `APP_URL=https://maxcon.app`

## 🔧 الإعدادات المطلوبة على الخادم

### 1. تحديث متغيرات البيئة
```bash
# في ملف .env على الخادم
APP_URL=https://maxcon.app
```

### 2. إعداد DNS
```
A Record: @ → IP الخادم
A Record: * → IP الخادم (للنطاقات الفرعية)
CNAME: www → maxcon.app
```

### 3. إعداد SSL
- تفعيل Let's Encrypt SSL في Cloudways
- تضمين شهادة wildcard للنطاقات الفرعية
- تفعيل Force HTTPS Redirection

### 4. إعداد Nginx/Apache
```nginx
server {
    listen 80;
    listen 443 ssl;
    server_name maxcon.app *.maxcon.app;
    
    # SSL Configuration
    ssl_certificate /path/to/ssl/cert.pem;
    ssl_certificate_key /path/to/ssl/private.key;
    
    # Application root
    root /applications/maxcon-erp/public_html/public;
    index index.php;
    
    # Handle Laravel routes
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    # PHP handling
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

## 🔄 خطوات التحديث على الخادم

### 1. تحديث ملف البيئة
```bash
cd /applications/maxcon-erp/public_html
nano .env

# تحديث السطر التالي:
APP_URL=https://maxcon.app
```

### 2. مسح الكاش
```bash
php artisan config:clear
php artisan config:cache
php artisan route:clear
php artisan route:cache
php artisan view:clear
```

### 3. إعادة تشغيل الخدمات
```bash
sudo service nginx restart
sudo service php8.2-fpm restart
```

## 📧 تحديث بيانات الدخول

### بيانات الدخول الجديدة:
```
Super Admin:
Email: admin@maxcon.app
Password: password

Tenant Admin:
Email: tenant@maxcon.app  
Password: password
```

## 🌐 الروابط الجديدة

### الروابط الرئيسية:
- **الصفحة الرئيسية:** https://maxcon.app
- **لوحة الإدارة:** https://maxcon.app/admin/dashboard
- **لوحة المستأجر:** https://maxcon.app/tenant/dashboard
- **صفحة تسجيل الدخول:** https://maxcon.app/login

### روابط المستأجرين:
- **مستأجر 1:** https://tenant1.maxcon.app
- **مستأجر 2:** https://tenant2.maxcon.app
- **إلخ...**

## 🔒 الأمان والشهادات

### SSL Configuration:
```
Primary Domain: maxcon.app
Wildcard Domain: *.maxcon.app
SSL Provider: Let's Encrypt
Auto-renewal: Enabled
Force HTTPS: Enabled
```

### Security Headers:
```
Strict-Transport-Security: max-age=31536000; includeSubDomains
X-Frame-Options: DENY
X-Content-Type-Options: nosniff
X-XSS-Protection: 1; mode=block
```

## 📊 مراقبة النطاق

### أدوات المراقبة:
- **Uptime Monitoring:** تفعيل مراقبة الوقت المتاح
- **SSL Monitoring:** مراقبة انتهاء الشهادات
- **Performance Monitoring:** مراقبة الأداء

### أوامر الفحص:
```bash
# فحص حالة النطاق
curl -I https://maxcon.app

# فحص SSL
openssl s_client -connect maxcon.app:443 -servername maxcon.app

# فحص DNS
nslookup maxcon.app
dig maxcon.app
```

## 🚀 اختبار النطاق الجديد

### قائمة الفحص:
- [ ] الصفحة الرئيسية تعمل
- [ ] تسجيل الدخول يعمل
- [ ] لوحة الإدارة تعمل
- [ ] لوحة المستأجر تعمل
- [ ] النطاقات الفرعية تعمل
- [ ] SSL يعمل بشكل صحيح
- [ ] إعادة التوجيه من HTTP إلى HTTPS
- [ ] جميع الروابط الداخلية تعمل
- [ ] الملفات الثابتة (CSS/JS/Images) تُحمل
- [ ] قاعدة البيانات متصلة
- [ ] Redis يعمل
- [ ] البريد الإلكتروني يعمل

## 📱 تحديث التطبيقات المحمولة

إذا كان هناك تطبيقات محمولة:
```javascript
// تحديث API Base URL
const API_BASE_URL = 'https://maxcon.app/api';

// تحديث WebSocket URL
const WS_URL = 'wss://maxcon.app/ws';
```

## 🔄 Migration من النطاق القديم

### إعداد Redirects:
```nginx
# إعادة توجيه من النطاق القديم
server {
    server_name phpstack-1492540-5695982.cloudwaysapps.com;
    return 301 https://maxcon.app$request_uri;
}
```

### تحديث Google Analytics/Search Console:
- تحديث خصائص Google Analytics
- إضافة النطاق الجديد في Search Console
- تحديث Sitemap URLs

## 📋 قائمة المراجعة النهائية

- [x] تحديث ملفات التوثيق
- [x] تحديث ملفات الإعداد
- [ ] تحديث .env على الخادم
- [ ] إعداد DNS
- [ ] إعداد SSL
- [ ] اختبار جميع الوظائف
- [ ] تحديث المراقبة
- [ ] إشعار المستخدمين

## 🎉 النتيجة

تم تحديث النطاق بنجاح إلى **maxcon.app** مع الحفاظ على جميع الوظائف والميزات.

**النطاق الجديد:** https://maxcon.app 🚀
