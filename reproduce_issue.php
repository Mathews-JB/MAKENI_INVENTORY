<?php
// reproduce_issue.php
require_once 'app/bootstrap.php';

// Mock Session
class MockSession {
    public static function get($key) {
        if ($key == 'user_id') return 1;
        if ($key == 'branch_id') return 1;
        return null;
    }
}

// Instantiate Models
$db = new Database();
$productModel = new Product();
$purchaseOrderModel = new PurchaseOrder();

// 1. Create a Test Product
echo "Creating Test Product...\n";
$productData = [
    'company_id' => 1,
    'category_id' => 1,
    'name' => 'Test Product ' . rand(1000, 9999),
    'sku' => 'TEST-SKU-' . rand(1000, 9999),
    'description' => 'Test',
    'type' => 'standard',
    'unit' => 'pcs',
    'price' => 10.00,
    'reorder_level' => 5,
    'opening_stock' => 10,
    'branch_id' => 1,
    'user_id' => 1
];

$productId = $productModel->addProduct($productData);
if (!$productId) {
    die("Failed to create product.\n");
}
echo "Product Created. ID: $productId\n";

// Check Initial Stock
$initialInventory = $productModel->getInventoryByProductAndBranch($productId, 1);
echo "Initial Stock: " . ($initialInventory ? $initialInventory->quantity : '0') . "\n";

// 2. Create Purchase Order
echo "Creating Purchase Order...\n";
$poData = [
    'branch_id' => 1,
    'supplier_name' => 'Test Supplier',
    'supplier_phone' => '1234567890',
    'supplier_email' => 'supplier@test.com',
    'total_amount' => 100.00,
    'created_by' => 1,
    'items' => [
        [
            'product_id' => $productId,
            'quantity' => 50,
            'unit_price' => 2.00,
            'subtotal' => 100.00
        ]
    ]
];

$orderId = $purchaseOrderModel->createOrder($poData);
if (!$orderId) {
    die("Failed to create purchase order.\n");
}
echo "Purchase Order Created. ID: $orderId\n";

// 3. Receive Purchase Order
echo "Receiving Purchase Order...\n";
$success = $purchaseOrderModel->receiveOrder($orderId, 1);
if ($success) {
    echo "Order Received Successfully.\n";
} else {
    echo "Failed to Receive Order.\n";
}

// 4. Check Final Stock
$finalInventory = $productModel->getInventoryByProductAndBranch($productId, 1);
echo "Final Stock: " . ($finalInventory ? $finalInventory->quantity : '0') . "\n";

if ($finalInventory->quantity == ($initialInventory->quantity + 50)) {
    echo "SUCCESS: Stock updated correctly.\n";
} else {
    echo "FAILURE: Stock not updated.\n";
}
