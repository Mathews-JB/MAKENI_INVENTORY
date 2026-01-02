<?php
class DistributionOrder extends Model {
    public function __construct() {
        parent::__construct();
    }

    // Generate unique order number with database check
    private function generateOrderNumber() {
        $prefix = 'DO-';
        $date = date('Ymd');
        $maxAttempts = 10;
        
        for ($i = 0; $i < $maxAttempts; $i++) {
            $random = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
            $orderNumber = $prefix . $date . '-' . $random;
            
            // Check if order number already exists
            $this->db->query('SELECT id FROM distribution_orders WHERE order_number = :order_number');
            $this->db->bind(':order_number', $orderNumber);
            
            if (!$this->db->single()) {
                return $orderNumber; // Unique number found
            }
        }
        
        // Fallback: use timestamp if random fails
        return $prefix . $date . '-' . time();
    }

    // Create distribution order
    public function createOrder($data) {
        try {
            // Start transaction
            $this->db->query('START TRANSACTION');

            // Validate stock availability first
            foreach ($data['items'] as $item) {
                $this->db->query('SELECT i.quantity, m.name 
                                 FROM inventory i 
                                 JOIN materials m ON i.material_id = m.id
                                 WHERE i.material_id = :material_id AND i.campus_id = :campus_id');
                $this->db->bind(':material_id', $item['material_id']);
                $this->db->bind(':campus_id', $data['campus_id']);
                $stock = $this->db->single();
                
                if (!$stock) {
                    // Material exists but no inventory record for this campus
                    // Auto-create inventory record with 0 quantity
                    $this->db->query('SELECT name FROM materials WHERE id = :material_id');
                    $this->db->bind(':material_id', $item['material_id']);
                    $material = $this->db->single();
                    
                    if (!$material) {
                        throw new Exception("Material ID {$item['material_id']} does not exist");
                    }
                    
                    // Create inventory record
                    $this->db->query('INSERT INTO inventory (material_id, campus_id, quantity) 
                                     VALUES (:material_id, :campus_id, 0)');
                    $this->db->bind(':material_id', $item['material_id']);
                    $this->db->bind(':campus_id', $data['campus_id']);
                    $this->db->execute();
                    
                    // Now throw insufficient stock error since we just created with 0
                    throw new Exception("Insufficient stock for {$material->name}. Available: 0, Required: {$item['quantity']}. Please add stock first.");
                }
                
                if ($stock->quantity < $item['quantity']) {
                    throw new Exception("Insufficient stock for {$stock->name}. Available: {$stock->quantity}, Required: {$item['quantity']}");
                }
            }

            // Generate order number
            $orderNumber = $this->generateOrderNumber();

            // Insert distribution order
            $this->db->query('INSERT INTO distribution_orders (order_number, campus_id, recipient_name, recipient_phone, recipient_email, total_amount, payment_method, status, created_by) 
                             VALUES (:order_number, :campus_id, :recipient_name, :recipient_phone, :recipient_email, :total_amount, :payment_method, :status, :created_by)');
            
            $this->db->bind(':order_number', $orderNumber);
            $this->db->bind(':campus_id', $data['campus_id']);
            $this->db->bind(':recipient_name', $data['recipient_name']);
            $this->db->bind(':recipient_phone', $data['recipient_phone']);
            $this->db->bind(':recipient_email', $data['recipient_email']);
            $this->db->bind(':total_amount', $data['total_amount']);
            $this->db->bind(':payment_method', $data['payment_method']);
            $this->db->bind(':status', $data['status']);
            $this->db->bind(':created_by', $data['created_by']);
            
            $this->db->execute();
            $orderId = $this->db->lastInsertId();

            // Insert order items and update inventory
            foreach ($data['items'] as $item) {
                // Insert order item
                $this->db->query('INSERT INTO distribution_order_items (distribution_order_id, material_id, quantity, unit_price, subtotal) 
                                 VALUES (:distribution_order_id, :material_id, :quantity, :unit_price, :subtotal)');
                
                $this->db->bind(':distribution_order_id', $orderId);
                $this->db->bind(':material_id', $item['material_id']);
                $this->db->bind(':quantity', $item['quantity']);
                $this->db->bind(':unit_price', $item['unit_price']);
                $this->db->bind(':subtotal', $item['subtotal']);
                
                $this->db->execute();

                // Deduct from inventory (already validated above)
                $this->db->query('UPDATE inventory SET quantity = quantity - :quantity 
                                 WHERE material_id = :material_id AND campus_id = :campus_id');
                $this->db->bind(':quantity', $item['quantity']);
                $this->db->bind(':material_id', $item['material_id']);
                $this->db->bind(':campus_id', $data['campus_id']);
                $this->db->execute();

                // Record transaction
                $this->db->query('INSERT INTO transactions (material_id, campus_id, type, quantity, reference, user_id) 
                                 VALUES (:material_id, :campus_id, "distribution", :quantity, :reference, :user_id)');
                $this->db->bind(':material_id', $item['material_id']);
                $this->db->bind(':campus_id', $data['campus_id']);
                $this->db->bind(':quantity', -$item['quantity']);
                $this->db->bind(':reference', $orderNumber);
                $this->db->bind(':user_id', $data['created_by']);
                $this->db->execute();
                
                // Check if stock is now below reorder level and create notification
                $this->db->query('SELECT m.name, m.reorder_level, i.quantity 
                                 FROM materials m 
                                 JOIN inventory i ON m.id = i.material_id 
                                 WHERE m.id = :material_id AND i.campus_id = :campus_id');
                $this->db->bind(':material_id', $item['material_id']);
                $this->db->bind(':campus_id', $data['campus_id']);
                $stockCheck = $this->db->single();
                
                if ($stockCheck && $stockCheck->quantity < $stockCheck->reorder_level) {
                    // Create low stock notification
                    $notificationModel = new Notification();
                    $notificationModel->create([
                        'user_id' => $data['created_by'],
                        'title' => 'Low Stock Alert',
                        'message' => "{$stockCheck->name} is running low on stock ({$stockCheck->quantity} remaining). Reorder level: {$stockCheck->reorder_level}",
                        'type' => 'warning',
                        'link' => '/materials/stock'
                    ]);
                }
            }

            // Commit transaction
            $this->db->query('COMMIT');
            
            return ['success' => true, 'order_id' => $orderId, 'order_number' => $orderNumber];
        } catch (Exception $e) {
            $this->db->query('ROLLBACK');
            
            // Log error for debugging
            error_log('Distribution Order Creation Failed: ' . $e->getMessage());
            
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    // Get all distribution orders
    public function getAllOrders($campus_id = null, $status = null) {
        $sql = 'SELECT so.*, u.full_name as created_by_name, cp.name as campus_name 
                FROM distribution_orders so 
                LEFT JOIN users u ON so.created_by = u.id 
                LEFT JOIN campuses cp ON so.campus_id = cp.id 
                WHERE 1=1';
        
        $params = [];

        if ($campus_id) {
            $sql .= ' AND so.campus_id = :campus_id';
            $params[':campus_id'] = $campus_id;
        }

        if ($status) {
            $sql .= ' AND so.status = :status';
            $params[':status'] = $status;
        }

        $sql .= ' ORDER BY so.created_at DESC';

        $this->db->query($sql);
        
        foreach ($params as $key => $value) {
            $this->db->bind($key, $value);
        }

        return $this->db->resultSet();
    }

    // Get order by ID
    public function getOrderById($id) {
        $this->db->query('SELECT so.*, u.full_name as created_by_name, cp.name as campus_name, 
                         s.name as school_name, s.email as school_email, s.phone as school_phone, s.address as school_address
                         FROM distribution_orders so 
                         LEFT JOIN users u ON so.created_by = u.id 
                         LEFT JOIN campuses cp ON so.campus_id = cp.id 
                         LEFT JOIN schools s ON cp.school_id = s.id
                         WHERE so.id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // Get order items
    public function getOrderItems($order_id) {
        $this->db->query('SELECT soi.*, m.name as material_name, m.sku as material_sku 
                         FROM distribution_order_items soi 
                         JOIN materials m ON soi.material_id = m.id 
                         WHERE soi.distribution_order_id = :order_id');
        $this->db->bind(':order_id', $order_id);
        return $this->db->resultSet();
    }

    // Update order status
    public function updateOrderStatus($id, $status) {
        $this->db->query('UPDATE distribution_orders SET status = :status WHERE id = :id');
        $this->db->bind(':id', $id);
        $this->db->bind(':status', $status);
        return $this->db->execute();
    }
}
