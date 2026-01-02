<?php
require_once 'config/config.php';
require_once 'core/Database.php';

try {
    $db = new Database();
    
    // Check column definition
    $db->query("SHOW COLUMNS FROM users LIKE 'role'");
    $row = $db->single();
    echo "Current Role Definition: " . $row->Type . "\n\n";
    
    // Check current distinct values in the table
    $db->query("SELECT DISTINCT role FROM users");
    $roles = $db->resultSet();
    echo "Current Values in Table:\n";
    foreach ($roles as $r) {
        echo "- '" . $r->role . "'\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
