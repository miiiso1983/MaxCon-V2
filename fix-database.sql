-- SQL Script to create missing purchasing module tables
-- Run this directly in MySQL/phpMyAdmin if PHP script doesn't work

-- Create purchase_requests table
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
  KEY `purchase_requests_requested_by_foreign` (`requested_by`),
  KEY `purchase_requests_approved_by_foreign` (`approved_by`),
  KEY `purchase_requests_rejected_by_foreign` (`rejected_by`),
  KEY `purchase_requests_tenant_id_status_index` (`tenant_id`,`status`),
  KEY `purchase_requests_tenant_id_requested_by_index` (`tenant_id`,`requested_by`),
  KEY `purchase_requests_tenant_id_required_date_index` (`tenant_id`,`required_date`),
  CONSTRAINT `purchase_requests_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE,
  CONSTRAINT `purchase_requests_requested_by_foreign` FOREIGN KEY (`requested_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `purchase_requests_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `purchase_requests_rejected_by_foreign` FOREIGN KEY (`rejected_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create purchase_request_items table
CREATE TABLE IF NOT EXISTS `purchase_request_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `purchase_request_id` bigint(20) unsigned NOT NULL,
  `product_id` bigint(20) unsigned DEFAULT NULL,
  `item_name` varchar(255) NOT NULL,
  `item_code` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `unit` varchar(255) NOT NULL DEFAULT 'piece',
  `quantity` decimal(10,2) NOT NULL,
  `estimated_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_estimated` decimal(12,2) NOT NULL DEFAULT 0.00,
  `specifications` text DEFAULT NULL,
  `brand_preference` varchar(255) DEFAULT NULL,
  `model_preference` varchar(255) DEFAULT NULL,
  `technical_requirements` text DEFAULT NULL,
  `status` enum('pending','approved','rejected','ordered') NOT NULL DEFAULT 'pending',
  `notes` text DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `purchase_request_items_purchase_request_id_foreign` (`purchase_request_id`),
  KEY `purchase_request_items_product_id_foreign` (`product_id`),
  KEY `purchase_request_items_purchase_request_id_index` (`purchase_request_id`),
  KEY `purchase_request_items_product_id_index` (`product_id`),
  CONSTRAINT `purchase_request_items_purchase_request_id_foreign` FOREIGN KEY (`purchase_request_id`) REFERENCES `purchase_requests` (`id`) ON DELETE CASCADE,
  CONSTRAINT `purchase_request_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create purchase_orders table
CREATE TABLE IF NOT EXISTS `purchase_orders` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint(20) unsigned NOT NULL,
  `purchase_request_id` bigint(20) unsigned DEFAULT NULL,
  `supplier_id` bigint(20) unsigned NOT NULL,
  `order_number` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('draft','sent','confirmed','partially_received','completed','cancelled') NOT NULL DEFAULT 'draft',
  `priority` enum('low','medium','high','urgent') NOT NULL DEFAULT 'medium',
  `order_date` date NOT NULL,
  `expected_delivery_date` date NOT NULL,
  `actual_delivery_date` date DEFAULT NULL,
  `subtotal` decimal(15,2) NOT NULL DEFAULT 0.00,
  `tax_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `discount_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `shipping_cost` decimal(15,2) NOT NULL DEFAULT 0.00,
  `total_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `currency` varchar(3) NOT NULL DEFAULT 'IQD',
  `payment_terms` enum('cash','credit_30','credit_60','credit_90','custom') NOT NULL DEFAULT 'credit_30',
  `payment_notes` text DEFAULT NULL,
  `delivery_address` text DEFAULT NULL,
  `delivery_contact` varchar(255) DEFAULT NULL,
  `delivery_phone` varchar(255) DEFAULT NULL,
  `delivery_instructions` text DEFAULT NULL,
  `created_by` bigint(20) unsigned NOT NULL,
  `approved_by` bigint(20) unsigned DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `terms_conditions` text DEFAULT NULL,
  `attachments` json DEFAULT NULL,
  `received_percentage` decimal(5,2) NOT NULL DEFAULT 0.00,
  `is_urgent` tinyint(1) NOT NULL DEFAULT 0,
  `reference_number` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `purchase_orders_order_number_unique` (`order_number`),
  KEY `purchase_orders_tenant_id_foreign` (`tenant_id`),
  KEY `purchase_orders_purchase_request_id_foreign` (`purchase_request_id`),
  KEY `purchase_orders_supplier_id_foreign` (`supplier_id`),
  KEY `purchase_orders_created_by_foreign` (`created_by`),
  KEY `purchase_orders_approved_by_foreign` (`approved_by`),
  KEY `purchase_orders_tenant_id_status_index` (`tenant_id`,`status`),
  KEY `purchase_orders_tenant_id_supplier_id_index` (`tenant_id`,`supplier_id`),
  KEY `purchase_orders_tenant_id_order_date_index` (`tenant_id`,`order_date`),
  CONSTRAINT `purchase_orders_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE,
  CONSTRAINT `purchase_orders_purchase_request_id_foreign` FOREIGN KEY (`purchase_request_id`) REFERENCES `purchase_requests` (`id`) ON DELETE SET NULL,
  CONSTRAINT `purchase_orders_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `purchase_orders_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `purchase_orders_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Add is_active column to tenants table if not exists
ALTER TABLE `tenants` ADD COLUMN IF NOT EXISTS `is_active` tinyint(1) NOT NULL DEFAULT 1 AFTER `status`;

-- Create other tables (purchase_order_items, quotations, quotation_items, supplier_contracts)
-- Note: Add these if needed, keeping this file focused on the main issue

-- Verify tables were created
SELECT 'purchase_requests' as table_name, COUNT(*) as record_count FROM purchase_requests
UNION ALL
SELECT 'purchase_request_items' as table_name, COUNT(*) as record_count FROM purchase_request_items
UNION ALL  
SELECT 'purchase_orders' as table_name, COUNT(*) as record_count FROM purchase_orders;
