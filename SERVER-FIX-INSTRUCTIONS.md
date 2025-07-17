# 🔧 إصلاح مشكلة الجداول المفقودة على الخادم

## 📋 المشكلة
```
SQLSTATE[42S02]: Base table or view not found: 1146 Table 'rrpkfnxwgn.purchase_requests' doesn't exist
```

## 🎯 الحلول المتاحة

### **الحل الأول: استخدام Laravel Migrations (الأفضل)**

#### 1. رفع الملفات على الخادم
```bash
# تأكد من أن جميع الملفات محدثة
git pull origin main
```

#### 2. تشغيل المهاجرات
```bash
# الانتقال لمجلد المشروع
cd /home/1492540.cloudwaysapps.com/rrpkfnxwgn/public_html

# تشغيل المهاجرات
php artisan migrate

# إذا فشل، جرب إعادة تعيين المهاجرات
php artisan migrate:reset
php artisan migrate

# أو تشغيل مهاجرة محددة
php artisan migrate --path=database/migrations/2025_07_17_040000_create_missing_purchasing_tables.php
```

---

### **الحل الثاني: استخدام PHP Script**

#### 1. رفع ملف `fix-server-database.php` على الخادم
```bash
# رفع الملف لجذر المشروع
scp fix-server-database.php user@server:/home/1492540.cloudwaysapps.com/rrpkfnxwgn/public_html/
```

#### 2. تشغيل الـ Script
```bash
# تشغيل من سطر الأوامر
cd /home/1492540.cloudwaysapps.com/rrpkfnxwgn/public_html
php fix-server-database.php

# أو من المتصفح
https://your-domain.com/fix-server-database.php
```

---

### **الحل الثالث: استخدام SQL مباشر**

#### 1. الدخول إلى phpMyAdmin أو MySQL
```bash
# الاتصال بقاعدة البيانات
mysql -u username -p database_name
```

#### 2. تشغيل ملف SQL
```sql
-- نسخ محتوى fix-database.sql وتشغيله
-- أو استيراد الملف مباشرة
SOURCE fix-database.sql;
```

#### 3. أو تشغيل الأوامر يدوياً
```sql
-- إنشاء جدول purchase_requests
CREATE TABLE IF NOT EXISTS `purchase_requests` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint(20) unsigned NOT NULL,
  `request_number` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `priority` enum('low','medium','high','urgent') NOT NULL DEFAULT 'medium',
  `status` enum('draft','pending','approved','rejected','cancelled','completed') NOT NULL DEFAULT 'draft',
  `requested_by` bigint(20) unsigned NOT NULL,
  `department_id` bigint(20) unsigned DEFAULT NULL,
  `required_date` date NOT NULL,
  `justification` text DEFAULT NULL,
  `approved_by` bigint(20) unsigned DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `approval_notes` text DEFAULT NULL,
  `rejected_by` bigint(20) unsigned DEFAULT NULL,
  `rejected_at` timestamp NULL DEFAULT NULL,
  `rejection_reason` text DEFAULT NULL,
  `estimated_total` decimal(15,2) NOT NULL DEFAULT 0.00,
  `approved_budget` decimal(15,2) DEFAULT NULL,
  `budget_code` varchar(255) DEFAULT NULL,
  `cost_center` varchar(255) DEFAULT NULL,
  `attachments` json DEFAULT NULL,
  `special_instructions` text DEFAULT NULL,
  `is_urgent` tinyint(1) NOT NULL DEFAULT 0,
  `deadline` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `purchase_requests_request_number_unique` (`request_number`),
  KEY `purchase_requests_tenant_id_foreign` (`tenant_id`),
  CONSTRAINT `purchase_requests_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- إضافة عمود is_active لجدول tenants
ALTER TABLE `tenants` ADD COLUMN IF NOT EXISTS `is_active` tinyint(1) NOT NULL DEFAULT 1 AFTER `status`;
```

---

## 🧪 التحقق من الإصلاح

### **1. اختبار من سطر الأوامر**
```bash
# اختبار الاتصال بقاعدة البيانات
php artisan tinker

# في Tinker
\App\Models\PurchaseRequest::count()
# يجب أن يعيد 0 بدلاً من خطأ
```

### **2. اختبار من المتصفح**
```bash
# زيارة صفحة المشتريات
https://your-domain.com/tenant/purchasing/purchase-requests
```

### **3. اختبار SQL مباشر**
```sql
-- التحقق من وجود الجداول
SHOW TABLES LIKE 'purchase%';

-- عد السجلات
SELECT COUNT(*) FROM purchase_requests;
```

---

## 📊 الجداول المطلوبة

### **الجداول الأساسية:**
- ✅ `purchase_requests` - طلبات الشراء
- ✅ `purchase_request_items` - عناصر طلبات الشراء  
- ✅ `purchase_orders` - أوامر الشراء
- ✅ `purchase_order_items` - عناصر أوامر الشراء
- ✅ `quotations` - عروض الأسعار
- ✅ `quotation_items` - عناصر عروض الأسعار
- ✅ `supplier_contracts` - عقود الموردين

### **التحديثات المطلوبة:**
- ✅ إضافة عمود `is_active` لجدول `tenants`

---

## 🚨 استكشاف الأخطاء

### **إذا فشل الحل الأول:**
```bash
# تحقق من حالة المهاجرات
php artisan migrate:status

# إعادة تعيين المهاجرات
php artisan migrate:reset
php artisan migrate
```

### **إذا فشل الحل الثاني:**
```bash
# تحقق من أذونات الملف
chmod +x fix-server-database.php

# تحقق من إعدادات PHP
php -v
php -m | grep pdo
```

### **إذا فشل الحل الثالث:**
```sql
-- تحقق من أذونات قاعدة البيانات
SHOW GRANTS FOR CURRENT_USER();

-- تحقق من وجود الجداول المرجعية
SHOW TABLES LIKE 'tenants';
SHOW TABLES LIKE 'users';
SHOW TABLES LIKE 'suppliers';
```

---

## ✅ التأكد من النجاح

### **علامات النجاح:**
1. ✅ لا توجد أخطاء عند زيارة صفحات المشتريات
2. ✅ يمكن إنشاء طلبات شراء جديدة
3. ✅ تظهر البيانات بشكل صحيح
4. ✅ لا توجد أخطاء في logs

### **اختبار شامل:**
```bash
# إنشاء بيانات تجريبية
php artisan db:seed --class=PurchasingModuleSeeder

# اختبار الوظائف
# زيارة: /tenant/purchasing/purchase-requests
# إنشاء طلب شراء جديد
# التحقق من عرض البيانات
```

---

## 📞 الدعم

إذا واجهت أي مشاكل:
1. تحقق من logs: `storage/logs/laravel.log`
2. تحقق من إعدادات قاعدة البيانات في `.env`
3. تأكد من أذونات الملفات والمجلدات
4. تحقق من إصدار PHP و MySQL

**🎯 الهدف:** حل مشكلة الجداول المفقودة وتشغيل وحدة المشتريات بنجاح!
