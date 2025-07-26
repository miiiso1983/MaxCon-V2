-- SQL script to create purchasing tables if they don't exist
-- Run this directly in MySQL production database

-- Create suppliers table
CREATE TABLE IF NOT EXISTS `suppliers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint(20) unsigned NOT NULL,
  `code` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `name_en` varchar(255) DEFAULT NULL,
  `type` enum('manufacturer','distributor','wholesaler','retailer','service_provider') NOT NULL,
  `status` enum('active','inactive','suspended') NOT NULL DEFAULT 'active',
  `contact_person` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `country` varchar(255) NOT NULL DEFAULT 'Iraq',
  `tax_number` varchar(255) DEFAULT NULL,
  `commercial_registration` varchar(255) DEFAULT NULL,
  `payment_terms` enum('cash','credit_15','credit_30','credit_45','credit_60') NOT NULL DEFAULT 'cash',
  `credit_limit` decimal(15,2) NOT NULL DEFAULT 0.00,
  `current_balance` decimal(15,2) NOT NULL DEFAULT 0.00,
  `is_preferred` tinyint(1) NOT NULL DEFAULT 0,
  `notes` text DEFAULT NULL,
  `documents` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `suppliers_code_unique` (`code`),
  KEY `suppliers_tenant_id_status_index` (`tenant_id`,`status`),
  KEY `suppliers_tenant_id_type_index` (`tenant_id`,`type`),
  KEY `suppliers_code_index` (`code`),
  KEY `suppliers_name_index` (`name`),
  CONSTRAINT `suppliers_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create supplier_contracts table
CREATE TABLE IF NOT EXISTS `supplier_contracts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint(20) unsigned NOT NULL,
  `supplier_id` bigint(20) unsigned NOT NULL,
  `contract_number` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `type` enum('supply','service','maintenance','consulting','framework') NOT NULL,
  `status` enum('draft','pending','active','expired','terminated','cancelled') NOT NULL DEFAULT 'draft',
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `signed_date` date DEFAULT NULL,
  `renewal_date` date DEFAULT NULL,
  `renewal_period_months` int(11) DEFAULT NULL,
  `auto_renewal` tinyint(1) NOT NULL DEFAULT 0,
  `contract_value` decimal(15,2) NOT NULL,
  `minimum_order_value` decimal(15,2) DEFAULT NULL,
  `maximum_order_value` decimal(15,2) DEFAULT NULL,
  `currency` varchar(3) NOT NULL DEFAULT 'IQD',
  `payment_terms` text DEFAULT NULL,
  `delivery_terms` text DEFAULT NULL,
  `quality_requirements` text DEFAULT NULL,
  `penalty_terms` text DEFAULT NULL,
  `termination_conditions` text DEFAULT NULL,
  `special_conditions` text DEFAULT NULL,
  `attachments` json DEFAULT NULL,
  `created_by` bigint(20) unsigned NOT NULL,
  `approved_by` bigint(20) unsigned DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `supplier_contracts_contract_number_unique` (`contract_number`),
  KEY `supplier_contracts_tenant_id_status_index` (`tenant_id`,`status`),
  KEY `supplier_contracts_tenant_id_supplier_id_index` (`tenant_id`,`supplier_id`),
  KEY `supplier_contracts_start_date_end_date_index` (`start_date`,`end_date`),
  KEY `supplier_contracts_contract_number_index` (`contract_number`),
  CONSTRAINT `supplier_contracts_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE,
  CONSTRAINT `supplier_contracts_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `supplier_contracts_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `supplier_contracts_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create purchase_requests table
CREATE TABLE IF NOT EXISTS `purchase_requests` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint(20) unsigned NOT NULL,
  `request_number` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('draft','pending','approved','rejected','completed') NOT NULL DEFAULT 'draft',
  `priority` enum('low','medium','high','urgent') NOT NULL DEFAULT 'medium',
  `required_date` date NOT NULL,
  `estimated_total` decimal(15,2) NOT NULL DEFAULT 0.00,
  `requested_by` bigint(20) unsigned NOT NULL,
  `approved_by` bigint(20) unsigned DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `rejection_reason` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `purchase_requests_request_number_unique` (`request_number`),
  KEY `purchase_requests_tenant_id_status_index` (`tenant_id`,`status`),
  KEY `purchase_requests_request_number_index` (`request_number`),
  KEY `purchase_requests_required_date_index` (`required_date`),
  CONSTRAINT `purchase_requests_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE,
  CONSTRAINT `purchase_requests_requested_by_foreign` FOREIGN KEY (`requested_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `purchase_requests_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create quotations table
CREATE TABLE IF NOT EXISTS `quotations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint(20) unsigned NOT NULL,
  `supplier_id` bigint(20) unsigned NOT NULL,
  `quotation_number` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('draft','sent','received','accepted','rejected','expired') NOT NULL DEFAULT 'draft',
  `quotation_date` date NOT NULL,
  `valid_until` date NOT NULL,
  `total_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `currency` varchar(3) NOT NULL DEFAULT 'IQD',
  `terms_conditions` text DEFAULT NULL,
  `created_by` bigint(20) unsigned NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `quotations_quotation_number_unique` (`quotation_number`),
  KEY `quotations_tenant_id_status_index` (`tenant_id`,`status`),
  KEY `quotations_tenant_id_supplier_id_index` (`tenant_id`,`supplier_id`),
  KEY `quotations_quotation_number_index` (`quotation_number`),
  KEY `quotations_valid_until_index` (`valid_until`),
  CONSTRAINT `quotations_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE,
  CONSTRAINT `quotations_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `quotations_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert sample data for testing (optional)
-- You can uncomment these lines if you want sample data

/*
-- Sample suppliers
INSERT IGNORE INTO `suppliers` (`tenant_id`, `code`, `name`, `name_en`, `type`, `status`, `contact_person`, `phone`, `email`, `address`, `payment_terms`, `credit_limit`, `is_preferred`, `created_at`, `updated_at`) VALUES
(1, 'SUP001', 'شركة الأدوية المتقدمة', 'Advanced Pharmaceuticals Co.', 'manufacturer', 'active', 'أحمد محمد', '07901234567', 'info@advancedpharma.com', 'بغداد - الكرادة', 'credit_30', 100000.00, 1, NOW(), NOW()),
(1, 'SUP002', 'شركة المعدات الطبية', 'Medical Equipment Co.', 'distributor', 'active', 'سارة أحمد', '07801234567', 'info@medequip.com', 'بغداد - الجادرية', 'credit_15', 50000.00, 0, NOW(), NOW()),
(1, 'SUP003', 'شركة الخدمات المتكاملة', 'Integrated Services Co.', 'service_provider', 'active', 'محمد علي', '07701234567', 'info@intservices.com', 'بغداد - المنصور', 'cash', 25000.00, 1, NOW(), NOW());

-- Sample contracts
INSERT IGNORE INTO `supplier_contracts` (`tenant_id`, `supplier_id`, `contract_number`, `title`, `description`, `type`, `status`, `start_date`, `end_date`, `signed_date`, `contract_value`, `minimum_order_value`, `maximum_order_value`, `currency`, `created_by`, `auto_renewal`, `renewal_period_months`, `created_at`, `updated_at`) VALUES
(1, 1, 'CON-2024-001', 'عقد توريد الأدوية الأساسية', 'عقد توريد الأدوية الأساسية والمستلزمات الطبية للعام 2024', 'supply', 'active', DATE_SUB(NOW(), INTERVAL 2 MONTH), DATE_ADD(NOW(), INTERVAL 10 MONTH), DATE_SUB(NOW(), INTERVAL 55 DAY), 500000.00, 10000.00, 50000.00, 'IQD', 1, 1, 12, NOW(), NOW()),
(1, 2, 'CON-2024-002', 'عقد صيانة المعدات الطبية', 'عقد صيانة دورية للمعدات الطبية والأجهزة المختبرية', 'maintenance', 'active', DATE_SUB(NOW(), INTERVAL 1 MONTH), DATE_ADD(NOW(), INTERVAL 11 MONTH), DATE_SUB(NOW(), INTERVAL 28 DAY), 120000.00, 5000.00, NULL, 'USD', 1, 0, 12, NOW(), NOW()),
(1, 3, 'CON-2024-003', 'عقد خدمات التنظيف', 'عقد خدمات التنظيف اليومي للمرافق', 'service', 'active', DATE_SUB(NOW(), INTERVAL 15 DAY), DATE_ADD(NOW(), INTERVAL 15 DAY), DATE_SUB(NOW(), INTERVAL 20 DAY), 36000.00, 3000.00, NULL, 'IQD', 1, 1, 6, NOW(), NOW());
*/
