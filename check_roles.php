<?php
require_once 'config/config.php';
require_once 'core/Database.php';

try {
    $db = new Database();
    $db->query("SELECT DISTINCT role FROM users");
    $roles = $db->resultSet();
    
    echo "Current roles in database:\n";
    foreach ($roles as $role) {
        echo "- " . $role->role . "\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
