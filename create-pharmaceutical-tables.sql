-- إنشاء جداول النظام الصيدلاني الشامل
-- شغل هذا الكود في phpMyAdmin أو MySQL

USE rrpkfnxwgn;

-- إنشاء جدول laboratory_tests (الفحوصات المخبرية)
CREATE TABLE IF NOT EXISTS `laboratory_tests` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- إنشاء جدول patient_tests (فحوصات المرضى)
CREATE TABLE IF NOT EXISTS `patient_tests` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint(20) unsigned NOT NULL,
  `patient_id` bigint(20) unsigned NOT NULL,
  `laboratory_test_id` bigint(20) unsigned NOT NULL,
  `test_date` date NOT NULL,
  `result_value` varchar(255) DEFAULT NULL,
  `result_status` enum('normal','abnormal','critical') DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `technician_id` bigint(20) unsigned DEFAULT NULL,
  `doctor_id` bigint(20) unsigned DEFAULT NULL,
  `status` enum('pending','in_progress','completed','cancelled') NOT NULL DEFAULT 'pending',
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `patient_tests_tenant_id_index` (`tenant_id`),
  KEY `patient_tests_patient_id_index` (`patient_id`),
  KEY `patient_tests_laboratory_test_id_index` (`laboratory_test_id`),
  KEY `patient_tests_test_date_index` (`test_date`),
  KEY `patient_tests_status_index` (`status`),
  KEY `patient_tests_deleted_at_index` (`deleted_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- إنشاء جدول drug_interactions (تفاعلات الأدوية)
CREATE TABLE IF NOT EXISTS `drug_interactions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint(20) unsigned NOT NULL,
  `drug_a_id` bigint(20) unsigned NOT NULL,
  `drug_b_id` bigint(20) unsigned NOT NULL,
  `interaction_type` enum('major','moderate','minor') NOT NULL,
  `description` text NOT NULL,
  `clinical_effect` text DEFAULT NULL,
  `management` text DEFAULT NULL,
  `severity_level` int(11) NOT NULL DEFAULT 1,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `source` varchar(255) DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `drug_interactions_tenant_id_index` (`tenant_id`),
  KEY `drug_interactions_drug_a_id_index` (`drug_a_id`),
  KEY `drug_interactions_drug_b_id_index` (`drug_b_id`),
  KEY `drug_interactions_interaction_type_index` (`interaction_type`),
  KEY `drug_interactions_is_active_index` (`is_active`),
  KEY `drug_interactions_deleted_at_index` (`deleted_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- إنشاء جدول prescriptions (الوصفات الطبية)
CREATE TABLE IF NOT EXISTS `prescriptions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint(20) unsigned NOT NULL,
  `prescription_number` varchar(50) NOT NULL,
  `patient_id` bigint(20) unsigned NOT NULL,
  `doctor_id` bigint(20) unsigned DEFAULT NULL,
  `prescription_date` date NOT NULL,
  `diagnosis` text DEFAULT NULL,
  `total_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `discount_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `final_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `status` enum('pending','dispensed','partially_dispensed','cancelled') NOT NULL DEFAULT 'pending',
  `dispensed_by` bigint(20) unsigned DEFAULT NULL,
  `dispensed_at` timestamp NULL DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `prescriptions_tenant_number_unique` (`tenant_id`,`prescription_number`),
  KEY `prescriptions_tenant_id_index` (`tenant_id`),
  KEY `prescriptions_patient_id_index` (`patient_id`),
  KEY `prescriptions_doctor_id_index` (`doctor_id`),
  KEY `prescriptions_prescription_date_index` (`prescription_date`),
  KEY `prescriptions_status_index` (`status`),
  KEY `prescriptions_deleted_at_index` (`deleted_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- إنشاء جدول prescription_items (عناصر الوصفة)
CREATE TABLE IF NOT EXISTS `prescription_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `prescription_id` bigint(20) unsigned NOT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `quantity_prescribed` int(11) NOT NULL,
  `quantity_dispensed` int(11) NOT NULL DEFAULT 0,
  `dosage` varchar(255) DEFAULT NULL,
  `frequency` varchar(255) DEFAULT NULL,
  `duration` varchar(255) DEFAULT NULL,
  `instructions` text DEFAULT NULL,
  `unit_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `prescription_items_prescription_id_index` (`prescription_id`),
  KEY `prescription_items_product_id_index` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- إنشاء جدول patients (المرضى)
CREATE TABLE IF NOT EXISTS `patients` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- إنشاء جدول doctors (الأطباء)
CREATE TABLE IF NOT EXISTS `doctors` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- إدراج بيانات أساسية للفحوصات المخبرية
INSERT IGNORE INTO `laboratory_tests` (
    `tenant_id`, `test_code`, `test_name`, `test_name_en`, `category`, `normal_range`, `unit`, `price`, `duration_hours`, `sample_type`, `is_active`, `created_at`, `updated_at`
) VALUES
-- فحوصات الدم الأساسية
(1, 'CBC', 'تعداد الدم الكامل', 'Complete Blood Count', 'Hematology', 'حسب العمر والجنس', 'cells/μL', 25000.00, 2, 'دم', 1, NOW(), NOW()),
(1, 'ESR', 'سرعة الترسيب', 'Erythrocyte Sedimentation Rate', 'Hematology', '0-20 mm/hr', 'mm/hr', 15000.00, 1, 'دم', 1, NOW(), NOW()),
(1, 'HB', 'الهيموجلوبين', 'Hemoglobin', 'Hematology', '12-16 g/dL', 'g/dL', 10000.00, 1, 'دم', 1, NOW(), NOW()),

-- فحوصات الكيمياء الحيوية
(1, 'GLU', 'السكر', 'Glucose', 'Biochemistry', '70-110 mg/dL', 'mg/dL', 12000.00, 1, 'دم', 1, NOW(), NOW()),
(1, 'CHOL', 'الكوليسترول', 'Cholesterol', 'Biochemistry', '<200 mg/dL', 'mg/dL', 18000.00, 2, 'دم', 1, NOW(), NOW()),
(1, 'UREA', 'اليوريا', 'Urea', 'Biochemistry', '15-45 mg/dL', 'mg/dL', 15000.00, 1, 'دم', 1, NOW(), NOW()),
(1, 'CREAT', 'الكرياتينين', 'Creatinine', 'Biochemistry', '0.6-1.2 mg/dL', 'mg/dL', 15000.00, 1, 'دم', 1, NOW(), NOW()),

-- فحوصات الهرمونات
(1, 'TSH', 'الهرمون المحفز للغدة الدرقية', 'Thyroid Stimulating Hormone', 'Hormones', '0.4-4.0 mIU/L', 'mIU/L', 35000.00, 4, 'دم', 1, NOW(), NOW()),
(1, 'T3', 'هرمون T3', 'Triiodothyronine', 'Hormones', '80-200 ng/dL', 'ng/dL', 30000.00, 4, 'دم', 1, NOW(), NOW()),
(1, 'T4', 'هرمون T4', 'Thyroxine', 'Hormones', '5-12 μg/dL', 'μg/dL', 30000.00, 4, 'دم', 1, NOW(), NOW()),

-- فحوصات البول
(1, 'URINE', 'فحص البول الكامل', 'Complete Urine Analysis', 'Urine', 'طبيعي', '-', 20000.00, 1, 'بول', 1, NOW(), NOW()),
(1, 'UPROT', 'البروتين في البول', 'Urine Protein', 'Urine', '<150 mg/24hr', 'mg/24hr', 15000.00, 1, 'بول', 1, NOW(), NOW());

-- نسخ نفس الفحوصات للمستأجرين الآخرين
INSERT IGNORE INTO `laboratory_tests` (
    `tenant_id`, `test_code`, `test_name`, `test_name_en`, `category`, `normal_range`, `unit`, `price`, `duration_hours`, `sample_type`, `is_active`, `created_at`, `updated_at`
)
SELECT 2, `test_code`, `test_name`, `test_name_en`, `category`, `normal_range`, `unit`, `price`, `duration_hours`, `sample_type`, `is_active`, NOW(), NOW()
FROM `laboratory_tests` WHERE `tenant_id` = 1;

INSERT IGNORE INTO `laboratory_tests` (
    `tenant_id`, `test_code`, `test_name`, `test_name_en`, `category`, `normal_range`, `unit`, `price`, `duration_hours`, `sample_type`, `is_active`, `created_at`, `updated_at`
)
SELECT 3, `test_code`, `test_name`, `test_name_en`, `category`, `normal_range`, `unit`, `price`, `duration_hours`, `sample_type`, `is_active`, NOW(), NOW()
FROM `laboratory_tests` WHERE `tenant_id` = 1;

INSERT IGNORE INTO `laboratory_tests` (
    `tenant_id`, `test_code`, `test_name`, `test_name_en`, `category`, `normal_range`, `unit`, `price`, `duration_hours`, `sample_type`, `is_active`, `created_at`, `updated_at`
)
SELECT 4, `test_code`, `test_name`, `test_name_en`, `category`, `normal_range`, `unit`, `price`, `duration_hours`, `sample_type`, `is_active`, NOW(), NOW()
FROM `laboratory_tests` WHERE `tenant_id` = 1;

-- إدراج أطباء أساسيين
INSERT IGNORE INTO `doctors` (
    `tenant_id`, `doctor_code`, `first_name`, `last_name`, `specialization`, `phone`, `consultation_fee`, `is_active`, `created_at`, `updated_at`
) VALUES
(1, 'DR001', 'د. أحمد', 'محمد', 'طب عام', '07901234567', 50000.00, 1, NOW(), NOW()),
(1, 'DR002', 'د. فاطمة', 'علي', 'طب أطفال', '07901234568', 60000.00, 1, NOW(), NOW()),
(1, 'DR003', 'د. محمد', 'حسن', 'طب باطني', '07901234569', 70000.00, 1, NOW(), NOW()),
(2, 'DR001', 'د. أحمد', 'محمد', 'طب عام', '07901234567', 50000.00, 1, NOW(), NOW()),
(3, 'DR001', 'د. أحمد', 'محمد', 'طب عام', '07901234567', 50000.00, 1, NOW(), NOW()),
(4, 'DR001', 'د. أحمد', 'محمد', 'طب عام', '07901234567', 50000.00, 1, NOW(), NOW());

-- إدراج مرضى أساسيين
INSERT IGNORE INTO `patients` (
    `tenant_id`, `patient_number`, `first_name`, `last_name`, `phone`, `gender`, `blood_type`, `is_active`, `created_at`, `updated_at`
) VALUES
(1, 'P001', 'علي', 'أحمد', '07701234567', 'male', 'O+', 1, NOW(), NOW()),
(1, 'P002', 'فاطمة', 'محمد', '07701234568', 'female', 'A+', 1, NOW(), NOW()),
(1, 'P003', 'محمد', 'علي', '07701234569', 'male', 'B+', 1, NOW(), NOW()),
(2, 'P001', 'علي', 'أحمد', '07701234567', 'male', 'O+', 1, NOW(), NOW()),
(3, 'P001', 'علي', 'أحمد', '07701234567', 'male', 'O+', 1, NOW(), NOW()),
(4, 'P001', 'علي', 'أحمد', '07701234567', 'male', 'O+', 1, NOW(), NOW());

-- التحقق من إنشاء جميع الجداول
SELECT 'laboratory_tests' as table_name, COUNT(*) as record_count FROM laboratory_tests
UNION ALL
SELECT 'patients' as table_name, COUNT(*) as record_count FROM patients
UNION ALL
SELECT 'doctors' as table_name, COUNT(*) as record_count FROM doctors
UNION ALL
SELECT 'prescriptions' as table_name, COUNT(*) as record_count FROM prescriptions
UNION ALL
SELECT 'drug_interactions' as table_name, COUNT(*) as record_count FROM drug_interactions;

-- اختبار الاستعلام الذي كان يفشل
SELECT COUNT(*) as test_count FROM laboratory_tests 
WHERE tenant_id = 4 AND deleted_at IS NULL;

SELECT '✅ All pharmaceutical tables created successfully!' as result;
