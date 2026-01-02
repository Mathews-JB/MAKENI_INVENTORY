<?php
require_once 'config/config.php';
require_once 'core/Database.php';

try {
    $db = new Database();
    
    // 1. Map 'staff' to 'teacher'
    $db->query("UPDATE users SET role = 'teacher' WHERE role = 'staff'");
    $db->execute();
    echo "Mapped 'staff' to 'teacher'.\n";
    
    // 2. Rename 'procurement_officer' to 'procurement officer'
    $db->query("UPDATE users SET role = 'procurement officer' WHERE role = 'procurement_officer'");
    $db->execute();
    echo "Renamed 'procurement_officer' to 'procurement officer'.\n";
    
    // 3. Rename 'storekeeper' to 'store keeper'
    $db->query("UPDATE users SET role = 'store keeper' WHERE role = 'storekeeper'");
    $db->execute();
    echo "Renamed 'storekeeper' to 'store keeper'.\n";

    echo "Database role cleanup completed.\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
