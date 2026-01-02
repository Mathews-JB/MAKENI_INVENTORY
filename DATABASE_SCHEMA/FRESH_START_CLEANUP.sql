-- ==========================================
-- SCHOOL IVM: FRESH START CLEANUP SCRIPT
-- ==========================================
-- This script purges all legacy data (Maxim, Miltech, etc.)
-- but preserves the core schema and the Administrator account.
-- ==========================================

SET FOREIGN_KEY_CHECKS = 0;

-- 1. Purge Transactional Data
TRUNCATE TABLE `distribution_order_items`;
TRUNCATE TABLE `distribution_orders`;
TRUNCATE TABLE `distribution_items`;
TRUNCATE TABLE `distributions`;
TRUNCATE TABLE `procurement_order_items`;
TRUNCATE TABLE `procurement_orders`;
TRUNCATE TABLE `procurement_items`;
TRUNCATE TABLE `procurements`;
TRUNCATE TABLE `inventory`;
TRUNCATE TABLE `transactions`;
TRUNCATE TABLE `notifications`;

-- 2. Purge Phase 2 Module Data (if any exists)
TRUNCATE TABLE `material_request_items`;
TRUNCATE TABLE `material_requests`;
TRUNCATE TABLE `maintenance_logs`;
TRUNCATE TABLE `disposal_requests`;

-- 3. Purge Master Data
TRUNCATE TABLE `materials`;
TRUNCATE TABLE `categories`;
TRUNCATE TABLE `recipients`;
TRUNCATE TABLE `vendors`;
TRUNCATE TABLE `user_invitations`;

-- 4. Purge Org Structure
TRUNCATE TABLE `campuses`;
TRUNCATE TABLE `schools`;

-- 5. Cleanup Users (Keep the primary Admin)
-- We use DELETE instead of TRUNCATE for users to preserve the admin
DELETE FROM `users` WHERE `username` != 'admin';

-- Optional: Fix Admin Role if it was mapped previously
UPDATE `users` SET `role` = 'administrator' WHERE `username` = 'admin';

SET FOREIGN_KEY_CHECKS = 1;

-- ==========================================
-- SYSTEM IS NOW READY FOR FRESH DATA ENTRY
-- ==========================================
