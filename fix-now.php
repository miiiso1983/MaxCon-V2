<?php
/**
 * URGENT FIX for accounting system
 * Upload this file to your server and run: yoursite.com/fix-now.php
 */

echo "<h1>🚨 URGENT: Fixing Accounting System</h1>";

// Get database config from Laravel .env or set manually
$host = 'localhost';
$dbname = 'rrpkfnxwgn';  // UPDATE THIS
$username = 'root';       // UPDATE THIS
$password = '';           // UPDATE THIS

// If you have access to Laravel config, uncomment these:
// $host = env('DB_HOST', 'localhost');
// $dbname = env('DB_DATABASE', 'rrpkfnxwgn');
// $username = env('DB_USERNAME', 'root');
// $password = env('DB_PASSWORD', '');

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<p>✅ Connected to database: <strong>$dbname</strong></p>";
    
    // Check if cost_center_id column exists
    $stmt = $pdo->query("SHOW COLUMNS FROM chart_of_accounts LIKE 'cost_center_id'");
    if ($stmt->rowCount() == 0) {
        echo "<p>🔧 Adding cost_center_id column...</p>";
        $pdo->exec("ALTER TABLE chart_of_accounts ADD COLUMN cost_center_id bigint(20) unsigned DEFAULT NULL");
        echo "<p>✅ cost_center_id column added!</p>";
    } else {
        echo "<p>ℹ️ cost_center_id column already exists</p>";
    }
    
    // Add index
    try {
        $pdo->exec("ALTER TABLE chart_of_accounts ADD INDEX cost_center_id_index (cost_center_id)");
        echo "<p>✅ Index added</p>";
    } catch (Exception $e) {
        echo "<p>ℹ️ Index already exists or error</p>";
    }
    
    // Add other important columns
    $columns = [
        'project_id' => 'bigint(20) unsigned DEFAULT NULL',
        'department_id' => 'bigint(20) unsigned DEFAULT NULL',
        'normal_balance' => 'enum("debit","credit") DEFAULT "debit"',
        'allow_posting' => 'tinyint(1) DEFAULT 1'
    ];
    
    foreach ($columns as $column => $definition) {
        $stmt = $pdo->query("SHOW COLUMNS FROM chart_of_accounts LIKE '$column'");
        if ($stmt->rowCount() == 0) {
            $pdo->exec("ALTER TABLE chart_of_accounts ADD COLUMN $column $definition");
            echo "<p>✅ Added column: $column</p>";
        }
    }
    
    // Test the fix
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM chart_of_accounts WHERE cost_center_id IS NULL AND tenant_id = ? AND deleted_at IS NULL");
    $stmt->execute([4]);
    $result = $stmt->fetch();
    
    echo "<h2>🧪 Test Results</h2>";
    echo "<p>✅ Query successful: <strong>{$result['count']}</strong> accounts found</p>";
    echo "<h2>🎉 SUCCESS!</h2>";
    echo "<p><strong>The accounting system should work now!</strong></p>";
    echo "<p>⚠️ Delete this file after use!</p>";
    
} catch (PDOException $e) {
    echo "<h2>❌ Error</h2>";
    echo "<p>Database error: " . $e->getMessage() . "</p>";
    echo "<h3>Manual Fix:</h3>";
    echo "<p>Run this SQL command in phpMyAdmin:</p>";
    echo "<code>ALTER TABLE chart_of_accounts ADD COLUMN cost_center_id bigint(20) unsigned DEFAULT NULL;</code>";
}
?>
