-- ==================================================================================
-- SCHOOL INVENTORY MANAGEMENT SYSTEM - PHASE 1 CONVERSION SCRIPT
-- Consolidated Migration: Roles, Terminology, and Core Entity Rebranding
-- ==================================================================================

-- ----------------------------------------------------------------------------------
-- 1. CORE TABLE RENAMES (TERMINOLOGY REBRANDING)
-- ----------------------------------------------------------------------------------
RENAME TABLE `products` TO `materials`;
RENAME TABLE `companies` TO `schools`;
RENAME TABLE `branches` TO `campuses`;
RENAME TABLE `customers` TO `recipients`;
RENAME TABLE `suppliers` TO `vendors`;
RENAME TABLE `sales` TO `distributions`;
RENAME TABLE `sale_items` TO `distribution_items`;
RENAME TABLE `sales_orders` TO `distribution_orders`;
RENAME TABLE `sales_order_items` TO `distribution_order_items`;
RENAME TABLE `purchases` TO `procurements`;
RENAME TABLE `purchase_items` TO `procurement_items`;
RENAME TABLE `purchase_orders` TO `procurement_orders`;
RENAME TABLE `purchase_order_items` TO `procurement_order_items`;

-- ----------------------------------------------------------------------------------
-- 2. USER ROLE RESTRUCTURING
-- ----------------------------------------------------------------------------------
ALTER TABLE `users` MODIFY COLUMN `role` ENUM('administrator', 'procurement_officer', 'storekeeper', 'teacher', 'accountant', 'super_admin', 'admin', 'manager', 'staff') NOT NULL DEFAULT 'teacher';

UPDATE `users` SET `role` = 'administrator' WHERE `role` = 'super_admin';
UPDATE `users` SET `role` = 'administrator' WHERE `role` = 'admin';
UPDATE `users` SET `role` = 'storekeeper' WHERE `role` = 'manager';
UPDATE `users` SET `role` = 'teacher' WHERE `role` = 'staff';

-- Optional: Remove legacy roles from the enum after verifying data
-- ALTER TABLE `users` MODIFY COLUMN `role` ENUM('administrator', 'procurement_officer', 'storekeeper', 'teacher', 'accountant') NOT NULL DEFAULT 'teacher';

-- ----------------------------------------------------------------------------------
-- 3. COLUMN RENAMES (FOREIGN KEYS AND REFERENCES)
-- ----------------------------------------------------------------------------------

-- inventory table
ALTER TABLE `inventory` CHANGE COLUMN `product_id` `material_id` INT(11) NOT NULL;
ALTER TABLE `inventory` CHANGE COLUMN `branch_id` `campus_id` INT(11) NOT NULL;

-- users table
ALTER TABLE `users` CHANGE COLUMN `branch_id` `campus_id` INT(11) DEFAULT NULL;

-- user_invitations table
ALTER TABLE `user_invitations` CHANGE COLUMN `branch_id` `campus_id` INT(11) DEFAULT NULL;

-- materials table
ALTER TABLE `materials` CHANGE COLUMN `company_id` `school_id` INT(11) NOT NULL;

-- campuses table
ALTER TABLE `campuses` CHANGE COLUMN `company_id` `school_id` INT(11) NOT NULL;

-- categories table
ALTER TABLE `categories` CHANGE COLUMN `company_id` `school_id` INT(11) DEFAULT NULL;

-- vendors (formerly suppliers) table
ALTER TABLE `vendors` ADD COLUMN `school_id` INT(11) DEFAULT NULL AFTER `id`;

-- distributions table
ALTER TABLE `distributions` CHANGE COLUMN `sale_number` `distribution_number` VARCHAR(50) NOT NULL;
ALTER TABLE `distributions` CHANGE COLUMN `customer_id` `recipient_id` INT(11) DEFAULT NULL;
ALTER TABLE `distributions` CHANGE COLUMN `sale_date` `distribution_date` DATE NOT NULL;
ALTER TABLE `distributions` CHANGE COLUMN `branch_id` `campus_id` INT(11) NOT NULL;

-- distribution_items table
ALTER TABLE `distribution_items` CHANGE COLUMN `sale_id` `distribution_id` INT(11) NOT NULL;

-- distribution_orders table
ALTER TABLE `distribution_orders` CHANGE COLUMN `customer_name` `recipient_name` VARCHAR(255) NOT NULL;
ALTER TABLE `distribution_orders` CHANGE COLUMN `customer_phone` `recipient_phone` VARCHAR(50) DEFAULT NULL;
ALTER TABLE `distribution_orders` CHANGE COLUMN `customer_email` `recipient_email` VARCHAR(100) DEFAULT NULL;
ALTER TABLE `distribution_orders` CHANGE COLUMN `branch_id` `campus_id` INT(11) NOT NULL;

-- distribution_order_items table
ALTER TABLE `distribution_order_items` CHANGE COLUMN `sales_order_id` `distribution_order_id` INT(11) NOT NULL;
ALTER TABLE `distribution_order_items` CHANGE COLUMN `product_id` `material_id` INT(11) NOT NULL;

-- procurement_orders table
ALTER TABLE `procurement_orders` CHANGE COLUMN `branch_id` `campus_id` INT(11) NOT NULL;
ALTER TABLE `procurement_orders` CHANGE COLUMN `supplier_name` `vendor_name` VARCHAR(255) NOT NULL;
ALTER TABLE `procurement_orders` CHANGE COLUMN `supplier_phone` `vendor_phone` VARCHAR(50) DEFAULT NULL;
ALTER TABLE `procurement_orders` CHANGE COLUMN `supplier_email` `vendor_email` VARCHAR(100) DEFAULT NULL;

-- procurement_order_items table
ALTER TABLE `procurement_order_items` CHANGE COLUMN `product_id` `material_id` INT(11) NOT NULL;
ALTER TABLE `procurement_order_items` CHANGE COLUMN `purchase_order_id` `procurement_order_id` INT(11) NOT NULL;

-- procurements table
ALTER TABLE `procurements` CHANGE COLUMN `purchase_number` `procurement_number` VARCHAR(50) NOT NULL;
ALTER TABLE `procurements` CHANGE COLUMN `purchase_date` `procurement_date` DATE NOT NULL;
ALTER TABLE `procurements` CHANGE COLUMN `branch_id` `campus_id` INT(11) NOT NULL;
ALTER TABLE `procurements` CHANGE COLUMN `supplier_id` `vendor_id` INT(11) DEFAULT NULL;

-- procurement_items table
ALTER TABLE `procurement_items` CHANGE COLUMN `purchase_id` `procurement_id` INT(11) NOT NULL;
ALTER TABLE `procurement_items` CHANGE COLUMN `product_id` `material_id` INT(11) NOT NULL;

-- transactions table
ALTER TABLE `transactions` CHANGE COLUMN `product_id` `material_id` INT(11) NOT NULL;
ALTER TABLE `transactions` CHANGE COLUMN `branch_id` `campus_id` INT(11) NOT NULL;
ALTER TABLE `transactions` MODIFY COLUMN `type` ENUM('procurement', 'distribution', 'transfer_in', 'transfer_out', 'adjustment', 'purchase', 'sale') NOT NULL;
UPDATE `transactions` SET `type` = 'distribution' WHERE `type` = 'sale';
UPDATE `transactions` SET `type` = 'procurement' WHERE `type` = 'purchase';
ALTER TABLE `transactions` MODIFY COLUMN `type` ENUM('procurement', 'distribution', 'transfer_in', 'transfer_out', 'adjustment') NOT NULL;

-- stock_transfers table
ALTER TABLE `stock_transfers` CHANGE COLUMN `from_branch_id` `from_campus_id` INT(11) NOT NULL;
ALTER TABLE `stock_transfers` CHANGE COLUMN `to_branch_id` `to_campus_id` INT(11) NOT NULL;
ALTER TABLE `stock_transfers` CHANGE COLUMN `product_id` `material_id` INT(11) NOT NULL;

-- ----------------------------------------------------------------------------------
-- 4. EDUCATIONAL CONTEXT ENHANCEMENTS
-- ----------------------------------------------------------------------------------
ALTER TABLE `materials` ADD COLUMN `isbn` VARCHAR(30) DEFAULT NULL AFTER `barcode`;
ALTER TABLE `materials` ADD COLUMN `grade_level` VARCHAR(50) DEFAULT NULL AFTER `isbn`;
ALTER TABLE `materials` ADD COLUMN `subject` VARCHAR(100) DEFAULT NULL AFTER `grade_level`;
ALTER TABLE `materials` ADD COLUMN `condition_status` ENUM('new', 'used', 'damaged', 'obsolete') DEFAULT 'new' AFTER `type`;

-- ----------------------------------------------------------------------------------
-- 5. NEW EDUCATIONAL MODULES (PHASE 2 FOUNDATION)
-- ----------------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `material_requests` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `request_number` VARCHAR(50) NOT NULL,
  `user_id` INT(11) NOT NULL, -- Faculty/Staff Member
  `campus_id` INT(11) NOT NULL,
  `reason` TEXT,
  `status` ENUM('pending', 'approved', 'rejected', 'fulfilled') DEFAULT 'pending',
  `authorized_by` INT(11) DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `request_number` (`request_number`),
  KEY `user_id` (`user_id`),
  KEY `campus_id` (`campus_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `material_request_items` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `request_id` INT(11) NOT NULL,
  `material_id` INT(11) NOT NULL,
  `quantity` INT(11) NOT NULL,
  `fulfilled_quantity` INT(11) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `request_id` (`request_id`),
  KEY `material_id` (`material_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `maintenance_logs` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `material_id` INT(11) NOT NULL,
  `campus_id` INT(11) NOT NULL,
  `maintenance_date` DATE NOT NULL,
  `description` TEXT NOT NULL,
  `cost` DECIMAL(10,2) DEFAULT 0.00,
  `performed_by` VARCHAR(150),
  `next_scheduled_date` DATE DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `material_id` (`material_id`),
  KEY `campus_id` (`campus_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `disposal_requests` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `material_id` INT(11) NOT NULL,
  `campus_id` INT(11) NOT NULL,
  `quantity` INT(11) NOT NULL,
  `reason` TEXT NOT NULL,
  `disposal_method` ENUM('recycling', 'donation', 'disposal', 'sale') NOT NULL,
  `status` ENUM('pending', 'approved', 'rejected', 'completed') DEFAULT 'pending',
  `requested_by` INT(11) NOT NULL,
  `approved_by` INT(11) DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `material_id` (`material_id`),
  KEY `campus_id` (`campus_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
