<?php
class Procurement extends Model {
    public function __construct() {
        parent::__construct();
    }

    // Create new procurement
    public function createProcurement($data, $items) {
        $this->db->query('START TRANSACTION');
        
        try {
            // Insert procurement
            $this->db->query('INSERT INTO procurements (procurement_number, supplier_id, campus_id, user_id, procurement_date, total_amount, status, notes) 
                             VALUES (:procurement_number, :supplier_id, :campus_id, :user_id, :procurement_date, :total_amount, :status, :notes)');
            
            $this->db->bind(':procurement_number', $data['procurement_number']);
            $this->db->bind(':supplier_id', $data['supplier_id']);
            $this->db->bind(':campus_id', $data['campus_id']);
            $this->db->bind(':user_id', $data['user_id']);
            $this->db->bind(':procurement_date', $data['procurement_date']);
            $this->db->bind(':total_amount', $data['total_amount']);
            $this->db->bind(':status', $data['status']);
            $this->db->bind(':notes', $data['notes']);
            
            $this->db->execute();
            $procurement_id = $this->db->lastInsertId();
            
            // Insert procurement items
            foreach ($items as $item) {
                $this->db->query('INSERT INTO procurement_items (procurement_id, material_id, quantity, unit_price, total_price) 
                                 VALUES (:procurement_id, :material_id, :quantity, :unit_price, :total_price)');
                
                $this->db->bind(':procurement_id', $procurement_id);
                $this->db->bind(':material_id', $item['material_id'] ?? $item['product_id']);
                $this->db->bind(':quantity', $item['quantity']);
                $this->db->bind(':unit_price', $item['unit_price']);
                $this->db->bind(':total_price', $item['total_price']);
                $this->db->execute();
            }
            
            $this->db->query('COMMIT');
            return $procurement_id;
            
        } catch (Exception $e) {
            $this->db->query('ROLLBACK');
            return false;
        }
    }

    // Receive procurement (update inventory)
    public function receiveProcurement($procurement_id) {
        // Get procurement details
        $this->db->query('SELECT * FROM procurements WHERE id = :id');
        $this->db->bind(':id', $procurement_id);
        $procurement = $this->db->single();
        
        if (!$procurement || $procurement->status != 'pending') {
            return false;
        }
        
        $this->db->query('START TRANSACTION');
        
        try {
            // Get procurement items
            $this->db->query('SELECT * FROM procurement_items WHERE procurement_id = :procurement_id');
            $this->db->bind(':procurement_id', $procurement_id);
            $items = $this->db->resultSet();
            
            foreach ($items as $item) {
                // Update or insert inventory
                $this->db->query('INSERT INTO inventory (material_id, campus_id, quantity) 
                                 VALUES (:material_id, :campus_id, :quantity) 
                                 ON DUPLICATE KEY UPDATE quantity = quantity + :quantity');
                $this->db->bind(':material_id', $item->material_id);
                $this->db->bind(':campus_id', $procurement->campus_id);
                $this->db->bind(':quantity', $item->quantity);
                $this->db->execute();
                
                // Record transaction
                $this->db->query('INSERT INTO transactions (material_id, campus_id, user_id, type, quantity, reference, notes) 
                                 VALUES (:material_id, :campus_id, :user_id, :type, :quantity, :reference, :notes)');
                $this->db->bind(':material_id', $item->material_id);
                $this->db->bind(':campus_id', $procurement->campus_id);
                $this->db->bind(':user_id', $procurement->user_id);
                $this->db->bind(':type', 'procurement');
                $this->db->bind(':quantity', $item->quantity);
                $this->db->bind(':reference', $procurement->procurement_number);
                $this->db->bind(':notes', 'Procurement received');
                $this->db->execute();
            }
            
            // Update procurement status
            $this->db->query('UPDATE procurements SET status = :status WHERE id = :id');
            $this->db->bind(':status', 'received');
            $this->db->bind(':id', $procurement_id);
            $this->db->execute();
            
            $this->db->query('COMMIT');
            return true;
            
        } catch (Exception $e) {
            $this->db->query('ROLLBACK');
            return false;
        }
    }

    // Get all procurements
    public function getAllProcurements($campus_id = null) {
        if ($campus_id) {
            $this->db->query('SELECT p.*, s.name as supplier_name, u.full_name as user_name 
                             FROM procurements p 
                             LEFT JOIN suppliers s ON p.supplier_id = s.id 
                             LEFT JOIN users u ON p.user_id = u.id 
                             WHERE p.campus_id = :campus_id 
                             ORDER BY p.created_at DESC');
            $this->db->bind(':campus_id', $campus_id);
        } else {
            $this->db->query('SELECT p.*, s.name as supplier_name, u.full_name as user_name 
                             FROM procurements p 
                             LEFT JOIN suppliers s ON p.supplier_id = s.id 
                             LEFT JOIN users u ON p.user_id = u.id 
                             ORDER BY p.created_at DESC');
        }
        
        return $this->db->resultSet();
    }

    // Generate procurement number
    public function generateProcurementNumber() {
        $prefix = 'PR';
        $date = date('Ymd');
        
        $this->db->query('SELECT COUNT(*) as count FROM procurements WHERE DATE(created_at) = CURDATE()');
        $result = $this->db->single();
        $count = $result->count + 1;
        
        return $prefix . '-' . $date . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
    }
}
