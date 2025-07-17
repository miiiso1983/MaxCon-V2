-- إصلاح سريع للخادم - إضافة عمود cost_center_id فقط
-- شغل هذا في phpMyAdmin أو MySQL على الخادم

-- التحقق من قاعدة البيانات
SELECT DATABASE() as current_database;

-- التحقق من وجود الجدول
SHOW TABLES LIKE 'chart_of_accounts';

-- عرض الهيكل الحالي
DESCRIBE chart_of_accounts;

-- إضافة عمود cost_center_id إذا لم يكن موجود
ALTER TABLE chart_of_accounts 
ADD COLUMN IF NOT EXISTS cost_center_id bigint(20) unsigned DEFAULT NULL;

-- إضافة فهرس للعمود
ALTER TABLE chart_of_accounts 
ADD INDEX IF NOT EXISTS cost_center_id_index (cost_center_id);

-- إضافة الأعمدة الأخرى المهمة
ALTER TABLE chart_of_accounts 
ADD COLUMN IF NOT EXISTS project_id bigint(20) unsigned DEFAULT NULL;

ALTER TABLE chart_of_accounts 
ADD COLUMN IF NOT EXISTS department_id bigint(20) unsigned DEFAULT NULL;

-- اختبار الاستعلام الذي كان يفشل
SELECT COUNT(*) as account_count 
FROM chart_of_accounts 
WHERE cost_center_id IS NULL 
AND tenant_id = 4 
AND deleted_at IS NULL;

-- عرض النتيجة
SELECT 'SUCCESS: cost_center_id column added!' as result;
SELECT 'The accounting system should work now!' as message;
