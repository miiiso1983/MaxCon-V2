<?php
/**
 * Emergency fix for server accounting system
 * Upload this to your server root and access via browser: yoursite.com/emergency-server-fix.php
 */

// Security check
$allowed_ips = ['127.0.0.1', '::1']; // Add your IP here
if (!in_array($_SERVER['REMOTE_ADDR'] ?? '', $allowed_ips) && !isset($_GET['force'])) {
    die('Access denied. Add ?force=1 to bypass (use with caution)');
}

echo "<h1>🔧 Emergency Accounting System Fix</h1>";
echo "<p>This will add missing columns to the accounting system.</p>";

// Database configuration - UPDATE THESE VALUES
$host = 'localhost';
$dbname = 'rrpkfnxwgn';  // Update this
$username = 'root';       // Update this  
$password = '';           // Update this

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<p>✅ Connected to database: <strong>$dbname</strong></p>";
    
    // Check if chart_of_accounts table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'chart_of_accounts'");
    if ($stmt->rowCount() == 0) {
        echo "<p>❌ chart_of_accounts table not found!</p>";
        exit;
    }
    
    echo "<p>✅ chart_of_accounts table found</p>";
    
    // Function to check if column exists
    function columnExists($pdo, $table, $column) {
        $stmt = $pdo->prepare("SHOW COLUMNS FROM `$table` LIKE ?");
        $stmt->execute([$column]);
        return $stmt->rowCount() > 0;
    }
    
    // Function to add column safely
    function addColumn($pdo, $table, $column, $definition) {
        if (!columnExists($pdo, $table, $column)) {
            $sql = "ALTER TABLE `$table` ADD COLUMN `$column` $definition";
            $pdo->exec($sql);
            echo "<p>✅ Added column: <strong>$column</strong></p>";
            return true;
        } else {
            echo "<p>ℹ️ Column already exists: <strong>$column</strong></p>";
            return false;
        }
    }
    
    // Add missing columns
    echo "<h2>📋 Adding Missing Columns</h2>";
    
    addColumn($pdo, 'chart_of_accounts', 'cost_center_id', 'bigint(20) unsigned DEFAULT NULL');
    addColumn($pdo, 'chart_of_accounts', 'project_id', 'bigint(20) unsigned DEFAULT NULL');
    addColumn($pdo, 'chart_of_accounts', 'department_id', 'bigint(20) unsigned DEFAULT NULL');
    addColumn($pdo, 'chart_of_accounts', 'normal_balance', 'enum("debit","credit") DEFAULT "debit"');
    addColumn($pdo, 'chart_of_accounts', 'allow_posting', 'tinyint(1) DEFAULT 1');
    addColumn($pdo, 'chart_of_accounts', 'require_cost_center', 'tinyint(1) DEFAULT 0');
    addColumn($pdo, 'chart_of_accounts', 'require_project', 'tinyint(1) DEFAULT 0');
    
    // Add indexes
    echo "<h2>📊 Adding Indexes</h2>";
    
    try {
        $pdo->exec("ALTER TABLE chart_of_accounts ADD INDEX cost_center_id_index (cost_center_id)");
        echo "<p>✅ Added index: cost_center_id_index</p>";
    } catch (Exception $e) {
        echo "<p>ℹ️ Index already exists or error: cost_center_id_index</p>";
    }
    
    try {
        $pdo->exec("ALTER TABLE chart_of_accounts ADD INDEX project_id_index (project_id)");
        echo "<p>✅ Added index: project_id_index</p>";
    } catch (Exception $e) {
        echo "<p>ℹ️ Index already exists or error: project_id_index</p>";
    }
    
    // Test the problematic query
    echo "<h2>🧪 Testing Fix</h2>";
    
    try {
        $stmt = $pdo->prepare("SELECT COUNT(*) as account_count FROM chart_of_accounts WHERE cost_center_id IS NULL AND tenant_id = ? AND deleted_at IS NULL");
        $stmt->execute([4]);
        $result = $stmt->fetch();
        echo "<p>✅ Test query successful: <strong>{$result['account_count']}</strong> accounts without cost center for tenant 4</p>";
    } catch (Exception $e) {
        echo "<p>❌ Test query failed: " . $e->getMessage() . "</p>";
    }
    
    // Create basic tables if they don't exist
    echo "<h2>🗃️ Creating Supporting Tables</h2>";
    
    // Create departments table
    $departmentsSQL = "CREATE TABLE IF NOT EXISTS departments (
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
        KEY departments_tenant_id_index (tenant_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    
    $pdo->exec($departmentsSQL);
    echo "<p>✅ Departments table ready</p>";
    
    // Create projects table
    $projectsSQL = "CREATE TABLE IF NOT EXISTS projects (
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
        KEY projects_tenant_id_index (tenant_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    
    $pdo->exec($projectsSQL);
    echo "<p>✅ Projects table ready</p>";
    
    // Insert basic data
    echo "<h2>🌱 Adding Basic Data</h2>";
    
    $departments = [
        [1, 'DEPT001', 'الإدارة العامة', 'General Management'],
        [1, 'DEPT002', 'قسم المبيعات', 'Sales Department'],
        [1, 'DEPT003', 'قسم المشتريات', 'Purchasing Department'],
        [1, 'DEPT004', 'قسم المحاسبة', 'Accounting Department'],
        [2, 'DEPT001', 'الإدارة العامة', 'General Management'],
        [2, 'DEPT002', 'قسم المبيعات', 'Sales Department'],
        [3, 'DEPT001', 'الإدارة العامة', 'General Management'],
        [4, 'DEPT001', 'الإدارة العامة', 'General Management'],
    ];
    
    $stmt = $pdo->prepare("INSERT IGNORE INTO departments (tenant_id, code, name, name_en, level, is_active, created_at, updated_at) VALUES (?, ?, ?, ?, 1, 1, NOW(), NOW())");
    foreach ($departments as $dept) {
        $stmt->execute($dept);
    }
    echo "<p>✅ Basic departments added</p>";
    
    $projects = [
        [1, 'PROJ001', 'مشروع التطوير الأساسي', 'Basic Development Project'],
        [2, 'PROJ001', 'مشروع التطوير الأساسي', 'Basic Development Project'],
        [3, 'PROJ001', 'مشروع التطوير الأساسي', 'Basic Development Project'],
        [4, 'PROJ001', 'مشروع التطوير الأساسي', 'Basic Development Project'],
    ];
    
    $stmt = $pdo->prepare("INSERT IGNORE INTO projects (tenant_id, code, name, name_en, status, budget_amount, is_active, created_at, updated_at) VALUES (?, ?, ?, ?, 'active', 100000.00, 1, NOW(), NOW())");
    foreach ($projects as $proj) {
        $stmt->execute($proj);
    }
    echo "<p>✅ Basic projects added</p>";
    
    echo "<h2>🎉 Fix Complete!</h2>";
    echo "<p><strong>✅ All accounting system columns and tables are now ready!</strong></p>";
    echo "<p>🔗 Your accounting system should work properly now.</p>";
    echo "<p>⚠️ <strong>Important:</strong> Delete this file after use for security!</p>";
    
} catch (PDOException $e) {
    echo "<h2>❌ Database Error</h2>";
    echo "<p>Error: " . $e->getMessage() . "</p>";
    echo "<h3>💡 Manual Solution:</h3>";
    echo "<p>Run this SQL in phpMyAdmin:</p>";
    echo "<pre>";
    echo "ALTER TABLE chart_of_accounts ADD COLUMN cost_center_id bigint(20) unsigned DEFAULT NULL;\n";
    echo "ALTER TABLE chart_of_accounts ADD COLUMN project_id bigint(20) unsigned DEFAULT NULL;\n";
    echo "ALTER TABLE chart_of_accounts ADD COLUMN department_id bigint(20) unsigned DEFAULT NULL;\n";
    echo "ALTER TABLE chart_of_accounts ADD INDEX cost_center_id_index (cost_center_id);\n";
    echo "</pre>";
}
?>
