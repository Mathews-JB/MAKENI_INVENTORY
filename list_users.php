<?php
require_once 'config/config.php';
require_once 'core/Database.php';

try {
    $db = new Database();
    $db->query("SELECT id, username, role, is_active FROM users");
    $users = $db->resultSet();
    
    echo "Users in database:\n";
    printf("%-5s | %-15s | %-20s | %-10s\n", "ID", "Username", "Role", "Active");
    echo "------------------------------------------------------------\n";
    foreach ($users as $user) {
        printf("%-5s | %-15s | %-20s | %-10s\n", $user->id, $user->username, $user->role, $user->is_active);
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
