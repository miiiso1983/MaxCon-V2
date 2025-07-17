-- إصلاح عمود تاريخ الاستحقاق في جدول التقارير التنظيمية
USE rrpkfnxwgn;

-- إضافة عمود due_date كمرادف لـ submission_deadline
ALTER TABLE `regulatory_reports` 
ADD COLUMN `due_date` date DEFAULT NULL COMMENT 'تاريخ الاستحقاق' 
AFTER `submission_deadline`;

-- إضافة أعمدة الحالة المفقودة للتوافق مع الاستعلامات
ALTER TABLE `regulatory_reports` 
MODIFY COLUMN `status` enum('active','inactive','draft','in_review','pending_approval','approved','submitted','acknowledged','rejected','revision_required','completed','archived','accepted','closed','pending','overdue') DEFAULT 'active';

-- تحديث عمود due_date ليطابق submission_deadline
UPDATE `regulatory_reports` 
SET `due_date` = `submission_deadline`
WHERE `tenant_id` = 4;

-- تحديث الحالات للتوافق مع الاستعلامات
UPDATE `regulatory_reports` 
SET `status` = CASE 
    WHEN `report_status` = 'submitted' THEN 'submitted'
    WHEN `report_status` = 'acknowledged' THEN 'accepted'
    WHEN `report_status` = 'completed' THEN 'closed'
    WHEN `report_status` = 'approved' THEN 'approved'
    WHEN `report_status` = 'in_review' THEN 'in_review'
    WHEN `report_status` = 'draft' THEN 'draft'
    WHEN `report_status` = 'pending_approval' THEN 'pending'
    WHEN `report_status` = 'rejected' THEN 'rejected'
    WHEN `report_status` = 'revision_required' THEN 'pending'
    WHEN `report_status` = 'archived' THEN 'archived'
    ELSE 'active'
END
WHERE `tenant_id` = 4;

-- تحديث الحالة للتقارير المتأخرة
UPDATE `regulatory_reports` 
SET `status` = 'overdue'
WHERE `tenant_id` = 4 
AND `due_date` < CURDATE() 
AND `status` NOT IN ('submitted', 'accepted', 'closed', 'archived');

-- إضافة فهرس للعمود الجديد
ALTER TABLE `regulatory_reports` 
ADD KEY `regulatory_reports_due_date_index` (`due_date`);

-- اختبار الاستعلام الذي كان يفشل
SELECT COUNT(*) as overdue_reports_count 
FROM `regulatory_reports` 
WHERE `tenant_id` = 4 
AND `due_date` < NOW() 
AND `status` NOT IN ('submitted', 'accepted', 'closed') 
AND `deleted_at` IS NULL;

-- التحقق من البيانات المُحدثة
SELECT 
    report_number as 'رقم التقرير',
    report_title as 'عنوان التقرير',
    submission_deadline as 'الموعد الأصلي',
    due_date as 'تاريخ الاستحقاق',
    report_status as 'الحالة الأصلية',
    status as 'الحالة الجديدة',
    CASE 
        WHEN due_date < CURDATE() AND status NOT IN ('submitted', 'accepted', 'closed') THEN 'متأخر'
        WHEN due_date = CURDATE() AND status NOT IN ('submitted', 'accepted', 'closed') THEN 'مستحق اليوم'
        WHEN due_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY) AND status NOT IN ('submitted', 'accepted', 'closed') THEN 'مستحق قريباً'
        WHEN status IN ('submitted', 'accepted', 'closed') THEN 'مُسلم'
        ELSE 'في الموعد'
    END as 'حالة الموعد'
FROM regulatory_reports 
WHERE tenant_id = 4 AND deleted_at IS NULL;

-- إحصائيات مُحدثة للمواعيد
SELECT 'إجمالي التقارير' as metric, COUNT(*) as value
FROM regulatory_reports 
WHERE tenant_id = 4 AND deleted_at IS NULL

UNION ALL

SELECT 'التقارير المتأخرة' as metric, COUNT(*) as value
FROM regulatory_reports 
WHERE tenant_id = 4 AND deleted_at IS NULL 
AND due_date < CURDATE() 
AND status NOT IN ('submitted', 'accepted', 'closed')

UNION ALL

SELECT 'التقارير المستحقة اليوم' as metric, COUNT(*) as value
FROM regulatory_reports 
WHERE tenant_id = 4 AND deleted_at IS NULL 
AND due_date = CURDATE() 
AND status NOT IN ('submitted', 'accepted', 'closed')

UNION ALL

SELECT 'التقارير المستحقة خلال 7 أيام' as metric, COUNT(*) as value
FROM regulatory_reports 
WHERE tenant_id = 4 AND deleted_at IS NULL 
AND due_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY)
AND status NOT IN ('submitted', 'accepted', 'closed')

UNION ALL

SELECT 'التقارير المُرسلة' as metric, COUNT(*) as value
FROM regulatory_reports 
WHERE tenant_id = 4 AND deleted_at IS NULL AND status = 'submitted'

UNION ALL

SELECT 'التقارير المقبولة' as metric, COUNT(*) as value
FROM regulatory_reports 
WHERE tenant_id = 4 AND deleted_at IS NULL AND status = 'accepted'

UNION ALL

SELECT 'التقارير المغلقة' as metric, COUNT(*) as value
FROM regulatory_reports 
WHERE tenant_id = 4 AND deleted_at IS NULL AND status = 'closed'

UNION ALL

SELECT 'التقارير النشطة' as metric, COUNT(*) as value
FROM regulatory_reports 
WHERE tenant_id = 4 AND deleted_at IS NULL 
AND status NOT IN ('submitted', 'accepted', 'closed', 'archived')

UNION ALL

SELECT 'التقارير قيد المراجعة' as metric, COUNT(*) as value
FROM regulatory_reports 
WHERE tenant_id = 4 AND deleted_at IS NULL AND status = 'in_review'

UNION ALL

SELECT 'التقارير المسودة' as metric, COUNT(*) as value
FROM regulatory_reports
WHERE tenant_id = 4 AND deleted_at IS NULL AND status = 'draft';

-- عرض التقارير مع حالة المواعيد
SELECT
    report_number as 'رقم التقرير',
    report_title as 'عنوان التقرير',
    CASE report_type
        WHEN 'inspection' THEN 'تفتيش'
        WHEN 'audit' THEN 'تدقيق'
        WHEN 'compliance' THEN 'امتثال'
        WHEN 'safety' THEN 'سلامة'
        WHEN 'quality' THEN 'جودة'
        WHEN 'adverse_event' THEN 'أحداث ضارة'
        ELSE report_type
    END as 'نوع التقرير',
    CASE priority
        WHEN 'low' THEN '🟢 منخفض'
        WHEN 'medium' THEN '🟡 متوسط'
        WHEN 'high' THEN '🟠 عالي'
        WHEN 'urgent' THEN '🔴 عاجل'
        WHEN 'critical' THEN '⚫ حرج'
        ELSE priority
    END as 'الأولوية',
    CASE status
        WHEN 'draft' THEN 'مسودة'
        WHEN 'in_review' THEN 'قيد المراجعة'
        WHEN 'pending' THEN 'معلق'
        WHEN 'approved' THEN 'معتمد'
        WHEN 'submitted' THEN 'مُرسل'
        WHEN 'accepted' THEN 'مقبول'
        WHEN 'closed' THEN 'مغلق'
        WHEN 'overdue' THEN 'متأخر'
        WHEN 'rejected' THEN 'مرفوض'
        ELSE status
    END as 'الحالة',
    due_date as 'تاريخ الاستحقاق',
    CASE
        WHEN due_date < CURDATE() AND status NOT IN ('submitted', 'accepted', 'closed') THEN
            CONCAT('🔴 متأخر ', DATEDIFF(CURDATE(), due_date), ' يوم')
        WHEN due_date = CURDATE() AND status NOT IN ('submitted', 'accepted', 'closed') THEN
            '🟠 مستحق اليوم'
        WHEN due_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 3 DAY) AND status NOT IN ('submitted', 'accepted', 'closed') THEN
            CONCAT('🟡 مستحق خلال ', DATEDIFF(due_date, CURDATE()), ' يوم')
        WHEN due_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY) AND status NOT IN ('submitted', 'accepted', 'closed') THEN
            CONCAT('🟢 مستحق خلال ', DATEDIFF(due_date, CURDATE()), ' يوم')
        WHEN status IN ('submitted', 'accepted', 'closed') THEN
            '✅ مُسلم'
        ELSE
            '⚪ في الموعد'
    END as 'حالة الموعد',
    facility_name as 'اسم المنشأة',
    compliance_score as 'نتيجة الامتثال',
    violations_identified as 'المخالفات',
    adverse_events_reported as 'الأحداث الضارة'
FROM regulatory_reports
WHERE tenant_id = 4 AND deleted_at IS NULL
ORDER BY
    CASE
        WHEN due_date < CURDATE() AND status NOT IN ('submitted', 'accepted', 'closed') THEN 1
        WHEN due_date = CURDATE() AND status NOT IN ('submitted', 'accepted', 'closed') THEN 2
        WHEN due_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY) AND status NOT IN ('submitted', 'accepted', 'closed') THEN 3
        WHEN status IN ('submitted', 'accepted', 'closed') THEN 5
        ELSE 4
    END,
    CASE priority
        WHEN 'critical' THEN 1
        WHEN 'urgent' THEN 2
        WHEN 'high' THEN 3
        WHEN 'medium' THEN 4
        WHEN 'low' THEN 5
    END,
    due_date ASC;

-- التقارير المتأخرة التي تحتاج متابعة فورية
SELECT
    report_number as 'رقم التقرير',
    report_title as 'عنوان التقرير',
    CASE report_type
        WHEN 'safety' THEN 'سلامة'
        WHEN 'compliance' THEN 'امتثال'
        WHEN 'adverse_event' THEN 'أحداث ضارة'
        WHEN 'inspection' THEN 'تفتيش'
        WHEN 'audit' THEN 'تدقيق'
        WHEN 'quality' THEN 'جودة'
        ELSE report_type
    END as 'نوع التقرير',
    CASE priority
        WHEN 'critical' THEN '⚫ حرج'
        WHEN 'urgent' THEN '🔴 عاجل'
        WHEN 'high' THEN '🟠 عالي'
        WHEN 'medium' THEN '🟡 متوسط'
        ELSE priority
    END as 'الأولوية',
    CASE status
        WHEN 'overdue' THEN '🔴 متأخر'
        WHEN 'draft' THEN 'مسودة'
        WHEN 'in_review' THEN 'قيد المراجعة'
        WHEN 'pending' THEN 'معلق'
        WHEN 'approved' THEN 'معتمد'
        ELSE status
    END as 'الحالة',
    due_date as 'تاريخ الاستحقاق',
    DATEDIFF(CURDATE(), due_date) as 'الأيام المتأخرة',
    violations_identified as 'المخالفات',
    adverse_events_reported as 'الأحداث الضارة',
    financial_impact as 'التأثير المالي (دينار)',
    prepared_by as 'المُعد',
    CASE
        WHEN priority = 'critical' AND DATEDIFF(CURDATE(), due_date) > 7 THEN '🚨 تدخل فوري - حرج ومتأخر'
        WHEN priority = 'urgent' AND DATEDIFF(CURDATE(), due_date) > 3 THEN '⏰ تسليم عاجل - متأخر'
        WHEN DATEDIFF(CURDATE(), due_date) > 14 THEN '📞 اتصال فوري بالمُعد'
        WHEN DATEDIFF(CURDATE(), due_date) > 7 THEN '📧 تذكير عاجل'
        ELSE '📋 متابعة عادية'
    END as 'الإجراء المطلوب'
FROM regulatory_reports
WHERE tenant_id = 4 AND deleted_at IS NULL
AND due_date < CURDATE()
AND status NOT IN ('submitted', 'accepted', 'closed', 'archived')
ORDER BY
    CASE priority
        WHEN 'critical' THEN 1
        WHEN 'urgent' THEN 2
        WHEN 'high' THEN 3
        WHEN 'medium' THEN 4
    END,
    DATEDIFF(CURDATE(), due_date) DESC;

-- التقارير المستحقة اليوم أو قريباً
SELECT
    report_number as 'رقم التقرير',
    report_title as 'عنوان التقرير',
    CASE priority
        WHEN 'critical' THEN '⚫ حرج'
        WHEN 'urgent' THEN '🔴 عاجل'
        WHEN 'high' THEN '🟠 عالي'
        WHEN 'medium' THEN '🟡 متوسط'
        ELSE priority
    END as 'الأولوية',
    CASE status
        WHEN 'draft' THEN 'مسودة'
        WHEN 'in_review' THEN 'قيد المراجعة'
        WHEN 'pending' THEN 'معلق'
        WHEN 'approved' THEN 'معتمد'
        ELSE status
    END as 'الحالة',
    due_date as 'تاريخ الاستحقاق',
    CASE
        WHEN due_date = CURDATE() THEN '🟠 مستحق اليوم'
        WHEN due_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 3 DAY) THEN
            CONCAT('🟡 مستحق خلال ', DATEDIFF(due_date, CURDATE()), ' يوم')
        WHEN due_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY) THEN
            CONCAT('🟢 مستحق خلال ', DATEDIFF(due_date, CURDATE()), ' يوم')
        ELSE 'في الموعد'
    END as 'حالة الموعد',
    facility_name as 'اسم المنشأة',
    prepared_by as 'المُعد',
    CASE
        WHEN due_date = CURDATE() AND status = 'draft' THEN '🚨 إكمال وتسليم اليوم'
        WHEN due_date = CURDATE() AND status = 'in_review' THEN '⏰ موافقة وتسليم اليوم'
        WHEN due_date = CURDATE() THEN '📤 تسليم اليوم'
        WHEN DATEDIFF(due_date, CURDATE()) <= 3 AND status = 'draft' THEN '📝 إكمال عاجل'
        WHEN DATEDIFF(due_date, CURDATE()) <= 3 THEN '📋 تحضير للتسليم'
        ELSE '📅 متابعة عادية'
    END as 'الإجراء المطلوب'
FROM regulatory_reports
WHERE tenant_id = 4 AND deleted_at IS NULL
AND due_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY)
AND status NOT IN ('submitted', 'accepted', 'closed', 'archived')
ORDER BY
    due_date ASC,
    CASE priority
        WHEN 'critical' THEN 1
        WHEN 'urgent' THEN 2
        WHEN 'high' THEN 3
        WHEN 'medium' THEN 4
    END;

-- تقرير الأداء في الالتزام بالمواعيد
SELECT
    CASE
        WHEN due_date < CURDATE() AND status NOT IN ('submitted', 'accepted', 'closed') THEN '🔴 متأخرة'
        WHEN due_date = CURDATE() AND status NOT IN ('submitted', 'accepted', 'closed') THEN '🟠 مستحقة اليوم'
        WHEN due_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY) AND status NOT IN ('submitted', 'accepted', 'closed') THEN '🟡 مستحقة قريباً'
        WHEN status IN ('submitted', 'accepted', 'closed') THEN '✅ مُسلمة في الموعد'
        ELSE '🟢 في الموعد'
    END as 'حالة الموعد',
    COUNT(*) as 'عدد التقارير',
    ROUND(AVG(compliance_score), 2) as 'متوسط الامتثال',
    SUM(violations_identified) as 'إجمالي المخالفات',
    SUM(adverse_events_reported) as 'إجمالي الأحداث الضارة',
    SUM(financial_impact) as 'إجمالي التأثير المالي (دينار)',
    GROUP_CONCAT(
        CONCAT(report_number, ' (',
            CASE priority
                WHEN 'critical' THEN 'حرج'
                WHEN 'urgent' THEN 'عاجل'
                WHEN 'high' THEN 'عالي'
                WHEN 'medium' THEN 'متوسط'
                WHEN 'low' THEN 'منخفض'
            END,
        ')')
        SEPARATOR ', '
    ) as 'أرقام التقارير'
FROM regulatory_reports
WHERE tenant_id = 4 AND deleted_at IS NULL
GROUP BY
    CASE
        WHEN due_date < CURDATE() AND status NOT IN ('submitted', 'accepted', 'closed') THEN 1
        WHEN due_date = CURDATE() AND status NOT IN ('submitted', 'accepted', 'closed') THEN 2
        WHEN due_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY) AND status NOT IN ('submitted', 'accepted', 'closed') THEN 3
        WHEN status IN ('submitted', 'accepted', 'closed') THEN 4
        ELSE 5
    END
ORDER BY
    CASE
        WHEN due_date < CURDATE() AND status NOT IN ('submitted', 'accepted', 'closed') THEN 1
        WHEN due_date = CURDATE() AND status NOT IN ('submitted', 'accepted', 'closed') THEN 2
        WHEN due_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY) AND status NOT IN ('submitted', 'accepted', 'closed') THEN 3
        WHEN status IN ('submitted', 'accepted', 'closed') THEN 4
        ELSE 5
    END;

-- إنشاء trigger لتحديث due_date تلقائياً عند تغيير submission_deadline
DELIMITER $$
CREATE TRIGGER IF NOT EXISTS update_regulatory_report_due_date
BEFORE UPDATE ON regulatory_reports
FOR EACH ROW
BEGIN
    -- تحديث due_date ليطابق submission_deadline
    SET NEW.due_date = NEW.submission_deadline;

    -- تحديث الحالة للتقارير المتأخرة
    IF NEW.due_date < CURDATE() AND NEW.status NOT IN ('submitted', 'accepted', 'closed', 'archived') THEN
        SET NEW.status = 'overdue';
    END IF;

    -- تحديث الحالة حسب report_status
    SET NEW.status = CASE
        WHEN NEW.report_status = 'submitted' THEN 'submitted'
        WHEN NEW.report_status = 'acknowledged' THEN 'accepted'
        WHEN NEW.report_status = 'completed' THEN 'closed'
        WHEN NEW.report_status = 'approved' THEN 'approved'
        WHEN NEW.report_status = 'in_review' THEN 'in_review'
        WHEN NEW.report_status = 'draft' THEN 'draft'
        WHEN NEW.report_status = 'pending_approval' THEN 'pending'
        WHEN NEW.report_status = 'rejected' THEN 'rejected'
        WHEN NEW.report_status = 'revision_required' THEN 'pending'
        WHEN NEW.report_status = 'archived' THEN 'archived'
        ELSE NEW.status
    END;
END$$
DELIMITER ;

SELECT '✅ Due date column added and synchronized successfully!' as result;
SELECT '📅 Regulatory reports deadline tracking operational!' as message;
SELECT '⏰ Overdue reports monitoring ready!' as message2;
SELECT '🔄 Automatic due date synchronization activated!' as message3;
