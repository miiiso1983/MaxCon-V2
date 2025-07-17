-- إصلاح الأعمدة المفقودة في النظام المحاسبي على الخادم
-- شغل هذا الكود في phpMyAdmin أو MySQL على الخادم

-- التحقق من قاعدة البيانات الحالية
SELECT DATABASE() as current_database;

-- التحقق من وجود جدول chart_of_accounts
SHOW TABLES LIKE 'chart_of_accounts';

-- عرض هيكل الجدول الحالي
DESCRIBE chart_of_accounts;

-- إضافة الأعمدة المفقودة واحد تلو الآخر مع التحقق من عدم وجودها

-- إضافة عمود cost_center_id
SET @sql = (
    SELECT IF(
        COUNT(*) = 0,
        'ALTER TABLE chart_of_accounts ADD COLUMN cost_center_id bigint(20) unsigned DEFAULT NULL AFTER currency_code',
        'SELECT "cost_center_id column already exists" as message'
    )
    FROM information_schema.COLUMNS 
    WHERE TABLE_SCHEMA = DATABASE() 
    AND TABLE_NAME = 'chart_of_accounts' 
    AND COLUMN_NAME = 'cost_center_id'
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- إضافة فهرس لعمود cost_center_id
SET @sql = (
    SELECT IF(
        COUNT(*) = 0,
        'ALTER TABLE chart_of_accounts ADD INDEX chart_of_accounts_cost_center_id_index (cost_center_id)',
        'SELECT "cost_center_id index already exists" as message'
    )
    FROM information_schema.STATISTICS 
    WHERE TABLE_SCHEMA = DATABASE() 
    AND TABLE_NAME = 'chart_of_accounts' 
    AND INDEX_NAME = 'chart_of_accounts_cost_center_id_index'
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- إضافة عمود project_id
SET @sql = (
    SELECT IF(
        COUNT(*) = 0,
        'ALTER TABLE chart_of_accounts ADD COLUMN project_id bigint(20) unsigned DEFAULT NULL AFTER cost_center_id',
        'SELECT "project_id column already exists" as message'
    )
    FROM information_schema.COLUMNS 
    WHERE TABLE_SCHEMA = DATABASE() 
    AND TABLE_NAME = 'chart_of_accounts' 
    AND COLUMN_NAME = 'project_id'
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- إضافة عمود department_id
SET @sql = (
    SELECT IF(
        COUNT(*) = 0,
        'ALTER TABLE chart_of_accounts ADD COLUMN department_id bigint(20) unsigned DEFAULT NULL AFTER project_id',
        'SELECT "department_id column already exists" as message'
    )
    FROM information_schema.COLUMNS 
    WHERE TABLE_SCHEMA = DATABASE() 
    AND TABLE_NAME = 'chart_of_accounts' 
    AND COLUMN_NAME = 'department_id'
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- إضافة عمود normal_balance
SET @sql = (
    SELECT IF(
        COUNT(*) = 0,
        'ALTER TABLE chart_of_accounts ADD COLUMN normal_balance enum("debit","credit") DEFAULT "debit" AFTER account_category',
        'SELECT "normal_balance column already exists" as message'
    )
    FROM information_schema.COLUMNS 
    WHERE TABLE_SCHEMA = DATABASE() 
    AND TABLE_NAME = 'chart_of_accounts' 
    AND COLUMN_NAME = 'normal_balance'
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- إضافة عمود allow_posting
SET @sql = (
    SELECT IF(
        COUNT(*) = 0,
        'ALTER TABLE chart_of_accounts ADD COLUMN allow_posting tinyint(1) DEFAULT 1 AFTER is_active',
        'SELECT "allow_posting column already exists" as message'
    )
    FROM information_schema.COLUMNS 
    WHERE TABLE_SCHEMA = DATABASE() 
    AND TABLE_NAME = 'chart_of_accounts' 
    AND COLUMN_NAME = 'allow_posting'
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- إضافة عمود require_cost_center
SET @sql = (
    SELECT IF(
        COUNT(*) = 0,
        'ALTER TABLE chart_of_accounts ADD COLUMN require_cost_center tinyint(1) DEFAULT 0 AFTER allow_posting',
        'SELECT "require_cost_center column already exists" as message'
    )
    FROM information_schema.COLUMNS 
    WHERE TABLE_SCHEMA = DATABASE() 
    AND TABLE_NAME = 'chart_of_accounts' 
    AND COLUMN_NAME = 'require_cost_center'
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- إضافة عمود require_project
SET @sql = (
    SELECT IF(
        COUNT(*) = 0,
        'ALTER TABLE chart_of_accounts ADD COLUMN require_project tinyint(1) DEFAULT 0 AFTER require_cost_center',
        'SELECT "require_project column already exists" as message'
    )
    FROM information_schema.COLUMNS 
    WHERE TABLE_SCHEMA = DATABASE() 
    AND TABLE_NAME = 'chart_of_accounts' 
    AND COLUMN_NAME = 'require_project'
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- إنشاء جدول projects إذا لم يكن موجود
CREATE TABLE IF NOT EXISTS projects (
    id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    tenant_id bigint(20) unsigned NOT NULL,
    code varchar(20) NOT NULL,
    name varchar(255) NOT NULL,
    name_en varchar(255) DEFAULT NULL,
    description text DEFAULT NULL,
    start_date date DEFAULT NULL,
    end_date date DEFAULT NULL,
    status enum('planning','active','on_hold','completed','cancelled') NOT NULL DEFAULT 'planning',
    budget_amount decimal(15,2) DEFAULT NULL,
    actual_amount decimal(15,2) NOT NULL DEFAULT 0.00,
    manager_id bigint(20) unsigned DEFAULT NULL,
    is_active tinyint(1) NOT NULL DEFAULT 1,
    created_by bigint(20) unsigned DEFAULT NULL,
    created_at timestamp NULL DEFAULT NULL,
    updated_at timestamp NULL DEFAULT NULL,
    deleted_at timestamp NULL DEFAULT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY projects_tenant_code_unique (tenant_id, code),
    KEY projects_tenant_id_index (tenant_id),
    KEY projects_status_index (status),
    KEY projects_is_active_index (is_active),
    KEY projects_deleted_at_index (deleted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- إنشاء جدول departments إذا لم يكن موجود
CREATE TABLE IF NOT EXISTS departments (
    id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    tenant_id bigint(20) unsigned NOT NULL,
    code varchar(20) NOT NULL,
    name varchar(255) NOT NULL,
    name_en varchar(255) DEFAULT NULL,
    parent_id bigint(20) unsigned DEFAULT NULL,
    level int(11) NOT NULL DEFAULT 1,
    is_active tinyint(1) NOT NULL DEFAULT 1,
    manager_id bigint(20) unsigned DEFAULT NULL,
    description text DEFAULT NULL,
    created_by bigint(20) unsigned DEFAULT NULL,
    created_at timestamp NULL DEFAULT NULL,
    updated_at timestamp NULL DEFAULT NULL,
    deleted_at timestamp NULL DEFAULT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY departments_tenant_code_unique (tenant_id, code),
    KEY departments_tenant_id_index (tenant_id),
    KEY departments_parent_id_index (parent_id),
    KEY departments_is_active_index (is_active),
    KEY departments_deleted_at_index (deleted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- إدراج بيانات أساسية للأقسام
INSERT IGNORE INTO departments (tenant_id, code, name, name_en, level, is_active, created_at, updated_at) VALUES
(1, 'DEPT001', 'الإدارة العامة', 'General Management', 1, 1, NOW(), NOW()),
(1, 'DEPT002', 'قسم المبيعات', 'Sales Department', 1, 1, NOW(), NOW()),
(1, 'DEPT003', 'قسم المشتريات', 'Purchasing Department', 1, 1, NOW(), NOW()),
(1, 'DEPT004', 'قسم المحاسبة', 'Accounting Department', 1, 1, NOW(), NOW()),
(1, 'DEPT005', 'قسم المخازن', 'Warehouse Department', 1, 1, NOW(), NOW()),
(2, 'DEPT001', 'الإدارة العامة', 'General Management', 1, 1, NOW(), NOW()),
(2, 'DEPT002', 'قسم المبيعات', 'Sales Department', 1, 1, NOW(), NOW()),
(2, 'DEPT003', 'قسم المشتريات', 'Purchasing Department', 1, 1, NOW(), NOW()),
(3, 'DEPT001', 'الإدارة العامة', 'General Management', 1, 1, NOW(), NOW()),
(3, 'DEPT002', 'قسم المبيعات', 'Sales Department', 1, 1, NOW(), NOW()),
(4, 'DEPT001', 'الإدارة العامة', 'General Management', 1, 1, NOW(), NOW()),
(4, 'DEPT002', 'قسم المبيعات', 'Sales Department', 1, 1, NOW(), NOW());

-- إدراج بيانات أساسية للمشاريع
INSERT IGNORE INTO projects (tenant_id, code, name, name_en, status, budget_amount, is_active, created_at, updated_at) VALUES
(1, 'PROJ001', 'مشروع التطوير الأساسي', 'Basic Development Project', 'active', 100000.00, 1, NOW(), NOW()),
(1, 'PROJ002', 'مشروع التوسع', 'Expansion Project', 'planning', 200000.00, 1, NOW(), NOW()),
(2, 'PROJ001', 'مشروع التطوير الأساسي', 'Basic Development Project', 'active', 100000.00, 1, NOW(), NOW()),
(3, 'PROJ001', 'مشروع التطوير الأساسي', 'Basic Development Project', 'active', 100000.00, 1, NOW(), NOW()),
(4, 'PROJ001', 'مشروع التطوير الأساسي', 'Basic Development Project', 'active', 100000.00, 1, NOW(), NOW());

-- عرض النتائج النهائية
SELECT 'chart_of_accounts structure after updates:' as info;
DESCRIBE chart_of_accounts;

SELECT 'Testing the problematic query:' as info;
SELECT COUNT(*) as account_count 
FROM chart_of_accounts 
WHERE cost_center_id IS NULL 
AND tenant_id = 4 
AND deleted_at IS NULL;

SELECT 'Departments count:' as info, COUNT(*) as count FROM departments;
SELECT 'Projects count:' as info, COUNT(*) as count FROM projects;

SELECT '✅ All columns and tables created successfully!' as result;
