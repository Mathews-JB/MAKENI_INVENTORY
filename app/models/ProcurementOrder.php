<?php
class ProcurementOrder extends Model {
    public function __construct() {
        parent::__construct();
    }

    // Generate unique order number
    private function generateOrderNumber() {
        $prefix = 'PR-';
        $date = date('Ymd');
        $random = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
        return $prefix . $date . '-' . $random;
    }

    // Create procurement order
    public function createOrder($data) {
        try {
            // Start transaction
            $this->db->query('START TRANSACTION');

            // Generate order number
            $orderNumber = $this->generateOrderNumber();

            // Insert procurement order
            $this->db->query('INSERT INTO procurement_orders (order_number, campus_id, vendor_name, vendor_phone, vendor_email, total_amount, created_by) 
                             VALUES (:order_number, :campus_id, :vendor_name, :vendor_phone, :vendor_email, :total_amount, :created_by)');
            
            $this->db->bind(':order_number', $orderNumber);
            $this->db->bind(':campus_id', $data['campus_id']);
            $this->db->bind(':vendor_name', $data['vendor_name'] ?? $data['supplier_name']);
            $this->db->bind(':vendor_phone', $data['vendor_phone'] ?? $data['supplier_phone']);
            $this->db->bind(':vendor_email', $data['vendor_email'] ?? $data['supplier_email']);
            $this->db->bind(':total_amount', $data['total_amount']);
            $this->db->bind(':created_by', $data['created_by']);
            
            $this->db->execute();
            $orderId = $this->db->lastInsertId();

            // Insert order items
            foreach ($data['items'] as $item) {
                $this->db->query('INSERT INTO procurement_order_items (procurement_order_id, material_id, quantity, unit_price, subtotal) 
                                 VALUES (:procurement_order_id, :material_id, :quantity, :unit_price, :subtotal)');
                
                $this->db->bind(':procurement_order_id', $orderId);
                $this->db->bind(':material_id', $item['material_id'] ?? $item['product_id']);
                $this->db->bind(':quantity', $item['quantity']);
                $this->db->bind(':unit_price', $item['unit_price']);
                $this->db->bind(':subtotal', $item['subtotal']);
                
                $this->db->execute();
            }

            // Commit transaction
            $this->db->query('COMMIT');
            
            return $orderId;
        } catch (Exception $e) {
            $this->db->query('ROLLBACK');
            return false;
        }
    }

    // Receive procurement order (update inventory)
    public function receiveOrder($id, $user_id) {
        try {
            $this->db->query('START TRANSACTION');

            // Get order details
            $order = $this->getOrderById($id);
            if (!$order || $order->status != 'pending') {
                throw new Exception('Invalid order');
            }

            // Get order items
            $items = $this->getOrderItems($id);

            // Update inventory for each item
            foreach ($items as $item) {
                // Update or insert inventory (Atomic Operation)
                $this->db->query('INSERT INTO inventory (material_id, campus_id, quantity) 
                                 VALUES (:material_id, :campus_id, :quantity) 
                                 ON DUPLICATE KEY UPDATE quantity = quantity + :quantity');
                
                $this->db->bind(':material_id', $item->material_id);
                $this->db->bind(':campus_id', $order->campus_id);
                $this->db->bind(':quantity', $item->quantity);
                $this->db->execute();

                // Record transaction
                $this->db->query('INSERT INTO transactions (material_id, campus_id, type, quantity, reference, user_id) 
                                 VALUES (:material_id, :campus_id, "procurement", :quantity, :reference, :user_id)');
                $this->db->bind(':material_id', $item->material_id);
                $this->db->bind(':campus_id', $order->campus_id);
                $this->db->bind(':quantity', $item->quantity);
                $this->db->bind(':reference', $order->order_number);
                $this->db->bind(':user_id', $user_id);
                $this->db->execute();
            }

            // Update order status
            $this->db->query('UPDATE procurement_orders SET status = "received", received_at = NOW() WHERE id = :id');
            $this->db->bind(':id', $id);
            $this->db->execute();

            $this->db->query('COMMIT');
            return true;
        } catch (Exception $e) {
            $this->db->query('ROLLBACK');
            return false;
        }
    }

    // Get all procurement orders
    public function getAllOrders($campus_id = null, $status = null) {
        $sql = 'SELECT po.*, u.full_name as created_by_name, b.name as campus_name 
                FROM procurement_orders po 
                LEFT JOIN users u ON po.created_by = u.id 
                LEFT JOIN campuses b ON po.campus_id = b.id 
                WHERE 1=1';
        
        $params = [];

        if ($campus_id) {
            $sql .= ' AND po.campus_id = :campus_id';
            $params[':campus_id'] = $campus_id;
        }

        if ($status) {
            $sql .= ' AND po.status = :status';
            $params[':status'] = $status;
        }

        $sql .= ' ORDER BY po.created_at DESC';

        $this->db->query($sql);
        
        foreach ($params as $key => $value) {
            $this->db->bind($key, $value);
        }

        return $this->db->resultSet();
    }

    // Get order by ID
    public function getOrderById($id) {
        $this->db->query('SELECT po.*, u.full_name as created_by_name, b.name as campus_name,
                         c.name as school_name, c.email as school_email, c.phone as school_phone, c.address as school_address
                         FROM procurement_orders po 
                         LEFT JOIN users u ON po.created_by = u.id 
                         LEFT JOIN campuses b ON po.campus_id = b.id 
                         LEFT JOIN schools c ON b.school_id = c.id
                         WHERE po.id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // Get order items
    public function getOrderItems($order_id) {
        $this->db->query('SELECT poi.*, p.name as material_name, p.sku 
                         FROM procurement_order_items poi 
                         JOIN materials p ON poi.material_id = p.id 
                         WHERE poi.procurement_order_id = :order_id');
        $this->db->bind(':order_id', $order_id);
        return $this->db->resultSet();
    }

    // Update order status
    public function updateOrderStatus($id, $status) {
        $this->db->query('UPDATE procurement_orders SET status = :status WHERE id = :id');
        $this->db->bind(':id', $id);
        $this->db->bind(':status', $status);
        return $this->db->execute();
    }
}
