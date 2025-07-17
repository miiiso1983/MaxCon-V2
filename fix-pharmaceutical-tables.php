<?php
/**
 * URGENT FIX for pharmaceutical system
 * Upload this file to your server and run: yoursite.com/fix-pharmaceutical-tables.php
 */

echo "<h1>🚨 URGENT: Creating Pharmaceutical System Tables</h1>";

// Database configuration
$host = 'localhost';
$dbname = 'rrpkfnxwgn';  // UPDATE THIS
$username = 'root';       // UPDATE THIS
$password = '';           // UPDATE THIS

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<p>✅ Connected to database: <strong>$dbname</strong></p>";
    
    // Check if laboratory_tests table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'laboratory_tests'");
    if ($stmt->rowCount() == 0) {
        echo "<p>🔧 Creating laboratory_tests table...</p>";
        
        $sql = "CREATE TABLE `laboratory_tests` (
            `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            `tenant_id` bigint(20) unsigned NOT NULL,
            `test_code` varchar(50) NOT NULL,
            `test_name` varchar(255) NOT NULL,
            `test_name_en` varchar(255) DEFAULT NULL,
            `category` varchar(100) DEFAULT NULL,
            `description` text DEFAULT NULL,
            `normal_range` varchar(255) DEFAULT NULL,
            `unit` varchar(50) DEFAULT NULL,
            `price` decimal(10,2) DEFAULT NULL,
            `duration_hours` int(11) DEFAULT NULL,
            `requires_fasting` tinyint(1) NOT NULL DEFAULT 0,
            `sample_type` varchar(100) DEFAULT NULL,
            `equipment_required` varchar(255) DEFAULT NULL,
            `is_active` tinyint(1) NOT NULL DEFAULT 1,
            `notes` text DEFAULT NULL,
            `created_by` bigint(20) unsigned DEFAULT NULL,
            `updated_by` bigint(20) unsigned DEFAULT NULL,
            `created_at` timestamp NULL DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT NULL,
            `deleted_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`),
            UNIQUE KEY `laboratory_tests_tenant_code_unique` (`tenant_id`,`test_code`),
            KEY `laboratory_tests_tenant_id_index` (`tenant_id`),
            KEY `laboratory_tests_category_index` (`category`),
            KEY `laboratory_tests_is_active_index` (`is_active`),
            KEY `laboratory_tests_deleted_at_index` (`deleted_at`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        $pdo->exec($sql);
        echo "<p>✅ laboratory_tests table created!</p>";
    } else {
        echo "<p>ℹ️ laboratory_tests table already exists</p>";
    }
    
    // Create patients table
    $stmt = $pdo->query("SHOW TABLES LIKE 'patients'");
    if ($stmt->rowCount() == 0) {
        echo "<p>🔧 Creating patients table...</p>";
        
        $sql = "CREATE TABLE `patients` (
            `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            `tenant_id` bigint(20) unsigned NOT NULL,
            `patient_number` varchar(50) NOT NULL,
            `first_name` varchar(100) NOT NULL,
            `last_name` varchar(100) NOT NULL,
            `date_of_birth` date DEFAULT NULL,
            `gender` enum('male','female') DEFAULT NULL,
            `phone` varchar(20) DEFAULT NULL,
            `email` varchar(255) DEFAULT NULL,
            `address` text DEFAULT NULL,
            `emergency_contact` varchar(255) DEFAULT NULL,
            `blood_type` varchar(10) DEFAULT NULL,
            `allergies` text DEFAULT NULL,
            `medical_history` text DEFAULT NULL,
            `insurance_number` varchar(100) DEFAULT NULL,
            `insurance_provider` varchar(255) DEFAULT NULL,
            `is_active` tinyint(1) NOT NULL DEFAULT 1,
            `notes` text DEFAULT NULL,
            `created_by` bigint(20) unsigned DEFAULT NULL,
            `created_at` timestamp NULL DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT NULL,
            `deleted_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`),
            UNIQUE KEY `patients_tenant_number_unique` (`tenant_id`,`patient_number`),
            KEY `patients_tenant_id_index` (`tenant_id`),
            KEY `patients_phone_index` (`phone`),
            KEY `patients_email_index` (`email`),
            KEY `patients_is_active_index` (`is_active`),
            KEY `patients_deleted_at_index` (`deleted_at`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        $pdo->exec($sql);
        echo "<p>✅ patients table created!</p>";
    } else {
        echo "<p>ℹ️ patients table already exists</p>";
    }
    
    // Create doctors table
    $stmt = $pdo->query("SHOW TABLES LIKE 'doctors'");
    if ($stmt->rowCount() == 0) {
        echo "<p>🔧 Creating doctors table...</p>";
        
        $sql = "CREATE TABLE `doctors` (
            `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            `tenant_id` bigint(20) unsigned NOT NULL,
            `doctor_code` varchar(50) NOT NULL,
            `first_name` varchar(100) NOT NULL,
            `last_name` varchar(100) NOT NULL,
            `specialization` varchar(255) DEFAULT NULL,
            `license_number` varchar(100) DEFAULT NULL,
            `phone` varchar(20) DEFAULT NULL,
            `email` varchar(255) DEFAULT NULL,
            `clinic_address` text DEFAULT NULL,
            `consultation_fee` decimal(10,2) DEFAULT NULL,
            `is_active` tinyint(1) NOT NULL DEFAULT 1,
            `notes` text DEFAULT NULL,
            `created_by` bigint(20) unsigned DEFAULT NULL,
            `created_at` timestamp NULL DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT NULL,
            `deleted_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`),
            UNIQUE KEY `doctors_tenant_code_unique` (`tenant_id`,`doctor_code`),
            KEY `doctors_tenant_id_index` (`tenant_id`),
            KEY `doctors_specialization_index` (`specialization`),
            KEY `doctors_phone_index` (`phone`),
            KEY `doctors_is_active_index` (`is_active`),
            KEY `doctors_deleted_at_index` (`deleted_at`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        $pdo->exec($sql);
        echo "<p>✅ doctors table created!</p>";
    } else {
        echo "<p>ℹ️ doctors table already exists</p>";
    }
    
    // Insert basic laboratory tests
    echo "<h2>🧪 Adding Basic Laboratory Tests</h2>";
    
    $tests = [
        ['CBC', 'تعداد الدم الكامل', 'Complete Blood Count', 'Hematology', '25000.00', 'دم'],
        ['GLU', 'السكر', 'Glucose', 'Biochemistry', '12000.00', 'دم'],
        ['CHOL', 'الكوليسترول', 'Cholesterol', 'Biochemistry', '18000.00', 'دم'],
        ['TSH', 'الهرمون المحفز للغدة الدرقية', 'Thyroid Stimulating Hormone', 'Hormones', '35000.00', 'دم'],
        ['URINE', 'فحص البول الكامل', 'Complete Urine Analysis', 'Urine', '20000.00', 'بول']
    ];
    
    $stmt = $pdo->prepare("INSERT IGNORE INTO laboratory_tests (tenant_id, test_code, test_name, test_name_en, category, price, sample_type, is_active, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, 1, NOW(), NOW())");
    
    foreach ([1, 2, 3, 4] as $tenantId) {
        foreach ($tests as $test) {
            $stmt->execute(array_merge([$tenantId], $test));
        }
        echo "<p>✅ Tests added for tenant $tenantId</p>";
    }
    
    // Insert basic doctors
    echo "<h2>👨‍⚕️ Adding Basic Doctors</h2>";
    
    $doctors = [
        ['DR001', 'د. أحمد', 'محمد', 'طب عام', '50000.00'],
        ['DR002', 'د. فاطمة', 'علي', 'طب أطفال', '60000.00'],
        ['DR003', 'د. محمد', 'حسن', 'طب باطني', '70000.00']
    ];
    
    $stmt = $pdo->prepare("INSERT IGNORE INTO doctors (tenant_id, doctor_code, first_name, last_name, specialization, consultation_fee, is_active, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, 1, NOW(), NOW())");
    
    foreach ([1, 2, 3, 4] as $tenantId) {
        foreach ($doctors as $doctor) {
            $stmt->execute(array_merge([$tenantId], $doctor));
        }
        echo "<p>✅ Doctors added for tenant $tenantId</p>";
    }
    
    // Insert basic patients
    echo "<h2>🏥 Adding Basic Patients</h2>";
    
    $patients = [
        ['P001', 'علي', 'أحمد', 'male', 'O+'],
        ['P002', 'فاطمة', 'محمد', 'female', 'A+'],
        ['P003', 'محمد', 'علي', 'male', 'B+']
    ];
    
    $stmt = $pdo->prepare("INSERT IGNORE INTO patients (tenant_id, patient_number, first_name, last_name, gender, blood_type, is_active, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, 1, NOW(), NOW())");
    
    foreach ([1, 2, 3, 4] as $tenantId) {
        foreach ($patients as $patient) {
            $stmt->execute(array_merge([$tenantId], $patient));
        }
        echo "<p>✅ Patients added for tenant $tenantId</p>";
    }
    
    // Test the fix
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM laboratory_tests WHERE tenant_id = ? AND deleted_at IS NULL");
    $stmt->execute([4]);
    $result = $stmt->fetch();
    
    echo "<h2>🧪 Test Results</h2>";
    echo "<p>✅ Query successful: <strong>{$result['count']}</strong> laboratory tests found for tenant 4</p>";
    echo "<h2>🎉 SUCCESS!</h2>";
    echo "<p><strong>The pharmaceutical system should work now!</strong></p>";
    echo "<p>⚠️ Delete this file after use!</p>";
    
} catch (PDOException $e) {
    echo "<h2>❌ Error</h2>";
    echo "<p>Database error: " . $e->getMessage() . "</p>";
    echo "<h3>Manual Fix:</h3>";
    echo "<p>Upload and run the create-pharmaceutical-tables.sql file in phpMyAdmin</p>";
}
?>
