<?php
require_once 'config/config.php';
require_once 'core/Database.php';

try {
    $db = new Database();
    
    echo "Starting schema migration...\n";
    
    // 1. Expand ENUM to include both old and new roles
    $db->query("ALTER TABLE users MODIFY COLUMN role ENUM('administrator','procurement officer','store keeper','teacher','accountant','procurement_officer','storekeeper','super_admin','admin','manager','staff')");
    $db->execute();
    echo "1. Expanded ENUM successfully.\n";
    
    // 2. Map existing data to new roles
    $db->query("UPDATE users SET role = 'teacher' WHERE role = 'staff'");
    $db->execute();
    echo "2. Mapped 'staff' to 'teacher'.\n";
    
    $db->query("UPDATE users SET role = 'procurement officer' WHERE role = 'procurement_officer'");
    $db->execute();
    echo "3. Mapped 'procurement_officer' to 'procurement officer'.\n";
    
    $db->query("UPDATE users SET role = 'store keeper' WHERE role = 'storekeeper'");
    $db->execute();
    echo "4. Mapped 'storekeeper' to 'store keeper'.\n";
    
    $db->query("UPDATE users SET role = 'administrator' WHERE role IN ('super_admin', 'admin', 'manager')");
    $db->execute();
    echo "5. Mapped legacy admins to 'administrator'.\n";
    
    // 3. Shrink ENUM to only include desired roles
    $db->query("ALTER TABLE users MODIFY COLUMN role ENUM('administrator', 'procurement officer', 'store keeper', 'teacher', 'accountant')");
    $db->execute();
    echo "6. Finalized ENUM schema successfully.\n";

    echo "\nDatabase schema standardization completed.\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
