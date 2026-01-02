<?php
/**
 * Seed Categories Script
 * This script populates the categories table with common school material categories
 */

require_once 'config/config.php';
require_once 'core/Database.php';

$db = new Database();

// Check if categories already exist
$db->query('SELECT COUNT(*) as count FROM categories');
$result = $db->single();

if ($result->count > 0) {
    echo "Categories table already has data. Skipping seed.\n";
    exit;
}

// Define common school material categories
$categories = [
    ['name' => 'Stationery', 'description' => 'Writing materials, pens, pencils, notebooks, etc.'],
    ['name' => 'Textbooks', 'description' => 'Educational books and learning materials'],
    ['name' => 'Uniforms', 'description' => 'School uniforms and clothing items'],
    ['name' => 'Sports Equipment', 'description' => 'Sports gear, balls, and athletic equipment'],
    ['name' => 'Lab Equipment', 'description' => 'Science laboratory equipment and supplies'],
    ['name' => 'Furniture', 'description' => 'Desks, chairs, and other school furniture'],
    ['name' => 'Electronics', 'description' => 'Computers, projectors, and electronic devices'],
    ['name' => 'Cleaning Supplies', 'description' => 'Cleaning materials and maintenance supplies']
];

// Insert categories
$db->query('INSERT INTO categories (name, description, created_at) VALUES (:name, :description, NOW())');

foreach ($categories as $category) {
    $db->bind(':name', $category['name']);
    $db->bind(':description', $category['description']);
    
    if ($db->execute()) {
        echo "✓ Added category: {$category['name']}\n";
    } else {
        echo "✗ Failed to add category: {$category['name']}\n";
    }
}

echo "\nCategory seeding completed successfully!\n";
?>
