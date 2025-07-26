<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateQuotationsTables extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'quotations:create-tables';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create quotations and quotation items tables';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Creating quotations tables...');

        try {
            // Check if quotations table exists
            if (!Schema::hasTable('quotations')) {
                $this->info('Creating quotations table...');

                DB::statement("
                    CREATE TABLE `quotations` (
                      `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                      `tenant_id` bigint(20) unsigned NOT NULL,
                      `quotation_number` varchar(255) NOT NULL,
                      `supplier_id` bigint(20) unsigned NOT NULL,
                      `purchase_request_id` bigint(20) unsigned DEFAULT NULL,
                      `title` varchar(255) NOT NULL,
                      `description` text DEFAULT NULL,
                      `status` enum('draft','sent','received','under_review','accepted','rejected','expired') NOT NULL DEFAULT 'draft',
                      `quotation_date` date NOT NULL,
                      `valid_until` date NOT NULL,
                      `response_date` date DEFAULT NULL,
                      `subtotal` decimal(15,2) NOT NULL DEFAULT '0.00',
                      `tax_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
                      `discount_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
                      `total_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
                      `currency` varchar(3) NOT NULL DEFAULT 'IQD',
                      `payment_terms` enum('cash','credit_7','credit_15','credit_30','credit_45','credit_60','credit_90','custom') NOT NULL DEFAULT 'credit_30',
                      `delivery_days` int(11) DEFAULT NULL,
                      `delivery_terms` text DEFAULT NULL,
                      `warranty_terms` text DEFAULT NULL,
                      `technical_score` decimal(5,2) DEFAULT NULL,
                      `commercial_score` decimal(5,2) DEFAULT NULL,
                      `overall_score` decimal(5,2) DEFAULT NULL,
                      `evaluation_notes` text DEFAULT NULL,
                      `evaluated_by` bigint(20) unsigned DEFAULT NULL,
                      `evaluated_at` timestamp NULL DEFAULT NULL,
                      `requested_by` bigint(20) unsigned NOT NULL,
                      `special_conditions` text DEFAULT NULL,
                      `attachments` json DEFAULT NULL,
                      `notes` text DEFAULT NULL,
                      `is_selected` tinyint(1) NOT NULL DEFAULT '0',
                      `rejection_reason` text DEFAULT NULL,
                      `created_at` timestamp NULL DEFAULT NULL,
                      `updated_at` timestamp NULL DEFAULT NULL,
                      PRIMARY KEY (`id`),
                      UNIQUE KEY `quotations_quotation_number_unique` (`quotation_number`),
                      KEY `quotations_tenant_id_foreign` (`tenant_id`),
                      KEY `quotations_supplier_id_foreign` (`supplier_id`),
                      KEY `quotations_purchase_request_id_foreign` (`purchase_request_id`),
                      KEY `quotations_requested_by_foreign` (`requested_by`),
                      KEY `quotations_evaluated_by_foreign` (`evaluated_by`),
                      KEY `quotations_tenant_id_status_index` (`tenant_id`,`status`),
                      KEY `quotations_tenant_id_supplier_id_index` (`tenant_id`,`supplier_id`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
                ");

                $this->info('✅ quotations table created successfully!');
            } else {
                $this->info('✅ quotations table already exists.');
            }

            // Check if quotation_items table exists
            if (!Schema::hasTable('quotation_items')) {
                $this->info('Creating quotation_items table...');

                DB::statement("
                    CREATE TABLE `quotation_items` (
                      `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                      `quotation_id` bigint(20) unsigned NOT NULL,
                      `product_id` bigint(20) unsigned DEFAULT NULL,
                      `purchase_request_item_id` bigint(20) unsigned DEFAULT NULL,
                      `item_name` varchar(255) NOT NULL,
                      `item_code` varchar(255) DEFAULT NULL,
                      `description` text DEFAULT NULL,
                      `unit` varchar(255) NOT NULL DEFAULT 'piece',
                      `quantity` decimal(10,2) NOT NULL,
                      `unit_price` decimal(10,2) NOT NULL,
                      `total_price` decimal(12,2) NOT NULL,
                      `specifications` text DEFAULT NULL,
                      `brand` varchar(255) DEFAULT NULL,
                      `model` varchar(255) DEFAULT NULL,
                      `technical_specs` text DEFAULT NULL,
                      `origin_country` varchar(255) DEFAULT NULL,
                      `warranty_months` int(11) NOT NULL DEFAULT '0',
                      `warranty_terms` text DEFAULT NULL,
                      `delivery_days` int(11) DEFAULT NULL,
                      `delivery_terms` text DEFAULT NULL,
                      `availability_percentage` decimal(5,2) DEFAULT NULL,
                      `technical_score` decimal(5,2) DEFAULT NULL,
                      `commercial_score` decimal(5,2) DEFAULT NULL,
                      `evaluation_notes` text DEFAULT NULL,
                      `notes` text DEFAULT NULL,
                      `sort_order` int(11) NOT NULL DEFAULT '0',
                      `is_alternative` tinyint(1) NOT NULL DEFAULT '0',
                      `alternative_reason` text DEFAULT NULL,
                      `created_at` timestamp NULL DEFAULT NULL,
                      `updated_at` timestamp NULL DEFAULT NULL,
                      PRIMARY KEY (`id`),
                      KEY `quotation_items_quotation_id_foreign` (`quotation_id`),
                      KEY `quotation_items_product_id_foreign` (`product_id`),
                      KEY `quotation_items_purchase_request_item_id_foreign` (`purchase_request_item_id`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
                ");

                $this->info('✅ quotation_items table created successfully!');
            } else {
                $this->info('✅ quotation_items table already exists.');
            }

            $this->info('🎉 All quotations tables are ready!');

            // Test the tables
            $this->info('Testing tables...');
            $this->info('quotations table exists: ' . (Schema::hasTable('quotations') ? 'YES' : 'NO'));
            $this->info('quotation_items table exists: ' . (Schema::hasTable('quotation_items') ? 'YES' : 'NO'));

        } catch (\Exception $e) {
            $this->error('Error creating tables: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
