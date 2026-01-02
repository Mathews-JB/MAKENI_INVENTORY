<?php
class Distribution extends Model {
    public function __construct() {
        parent::__construct();
    }

    // Create new distribution
    public function createDistribution($data, $items) {
        // Start transaction
        $this->db->query('START TRANSACTION');
        
        try {
            // Insert distribution
            $this->db->query('INSERT INTO distributions (distribution_number, recipient_id, campus_id, user_id, distribution_date, subtotal, tax_amount, total_amount, payment_method, notes) 
                             VALUES (:distribution_number, :recipient_id, :campus_id, :user_id, :distribution_date, :subtotal, :tax_amount, :total_amount, :payment_method, :notes)');
            
            $this->db->bind(':distribution_number', $data['distribution_number']);
            $this->db->bind(':recipient_id', $data['recipient_id']);
            $this->db->bind(':campus_id', $data['campus_id']);
            $this->db->bind(':user_id', $data['user_id']);
            $this->db->bind(':distribution_date', $data['distribution_date']);
            $this->db->bind(':subtotal', $data['subtotal']);
            $this->db->bind(':tax_amount', $data['tax_amount']);
            $this->db->bind(':total_amount', $data['total_amount']);
            $this->db->bind(':payment_method', $data['payment_method']);
            $this->db->bind(':notes', $data['notes']);
            
            $this->db->execute();
            $distribution_id = $this->db->lastInsertId();
            
            // Insert distribution items and update inventory
            foreach ($items as $item) {
                // Insert distribution item
                $this->db->query('INSERT INTO distribution_items (distribution_id, material_id, quantity, unit_price, total_price) 
                                 VALUES (:distribution_id, :material_id, :quantity, :unit_price, :total_price)');
                
                $this->db->bind(':distribution_id', $distribution_id);
                $this->db->bind(':material_id', $item['material_id']);
                $this->db->bind(':quantity', $item['quantity']);
                $this->db->bind(':unit_price', $item['unit_price']);
                $this->db->bind(':total_price', $item['total_price']);
                $this->db->execute();
                
                // Update inventory
                $this->db->query('UPDATE inventory SET quantity = quantity - :quantity 
                                 WHERE material_id = :material_id AND campus_id = :campus_id');
                $this->db->bind(':quantity', $item['quantity']);
                $this->db->bind(':material_id', $item['material_id']);
                $this->db->bind(':campus_id', $data['campus_id']);
                $this->db->execute();
                
                // Record transaction
                $this->db->query('INSERT INTO transactions (material_id, campus_id, user_id, type, quantity, reference, notes) 
                                 VALUES (:material_id, :campus_id, :user_id, :type, :quantity, :reference, :notes)');
                $this->db->bind(':material_id', $item['material_id']);
                $this->db->bind(':campus_id', $data['campus_id']);
                $this->db->bind(':user_id', $data['user_id']);
                $this->db->bind(':type', 'distribution');
                $this->db->bind(':quantity', -$item['quantity']);
                $this->db->bind(':reference', $data['distribution_number']);
                $this->db->bind(':notes', 'Material distribution');
                $this->db->execute();
            }
            
            // Commit transaction
            $this->db->query('COMMIT');
            return $distribution_id;
            
        } catch (Exception $e) {
            // Rollback on error
            $this->db->query('ROLLBACK');
            return false;
        }
    }

    // Get all distributions
    public function getAllDistributions($campus_id = null, $limit = 50) {
        if ($campus_id) {
            $this->db->query('SELECT s.*, c.name as recipient_name, u.full_name as user_name 
                             FROM distributions s 
                             LEFT JOIN recipients c ON s.recipient_id = c.id 
                             LEFT JOIN users u ON s.user_id = u.id 
                             WHERE s.campus_id = :campus_id 
                             ORDER BY s.created_at DESC 
                             LIMIT :limit');
            $this->db->bind(':campus_id', $campus_id);
            $this->db->bind(':limit', $limit);
        } else {
            $this->db->query('SELECT s.*, c.name as recipient_name, u.full_name as user_name 
                             FROM distributions s 
                             LEFT JOIN recipients c ON s.recipient_id = c.id 
                             LEFT JOIN users u ON s.user_id = u.id 
                             ORDER BY s.created_at DESC 
                             LIMIT :limit');
            $this->db->bind(':limit', $limit);
        }
        
        return $this->db->resultSet();
    }

    // Get distribution by ID
    public function getDistributionById($id) {
        $this->db->query('SELECT s.*, c.name as recipient_name, c.phone as recipient_phone, 
                         u.full_name as user_name, b.name as campus_name 
                         FROM distributions s 
                         LEFT JOIN recipients c ON s.recipient_id = c.id 
                         LEFT JOIN users u ON s.user_id = u.id 
                         LEFT JOIN campuses b ON s.campus_id = b.id 
                         WHERE s.id = :id');
        $this->db->bind(':id', $id);
        
        return $this->db->single();
    }

    // Get distribution items
    public function getDistributionItems($distribution_id) {
        $this->db->query('SELECT si.*, p.name as material_name, p.sku 
                         FROM distribution_items si 
                         JOIN materials p ON si.material_id = p.id 
                         WHERE si.distribution_id = :distribution_id');
        $this->db->bind(':distribution_id', $distribution_id);
        
        return $this->db->resultSet();
    }

    // Generate distribution number
    public function generateDistributionNumber() {
        $prefix = 'DST';
        $date = date('Ymd');
        
        $this->db->query('SELECT COUNT(*) as count FROM distributions WHERE DATE(created_at) = CURDATE()');
        $result = $this->db->single();
        $count = $result->count + 1;
        
        return $prefix . '-' . $date . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
    }

    // Get distribution statistics
    public function getDistributionStats($campus_id = null, $date_from = null, $date_to = null) {
        $where = [];
        if ($campus_id) $where[] = 's.campus_id = :campus_id';
        if ($date_from) $where[] = 's.distribution_date >= :date_from';
        if ($date_to) $where[] = 's.distribution_date <= :date_to';
        
        $whereClause = count($where) > 0 ? 'WHERE ' . implode(' AND ', $where) : '';
        
        $this->db->query("SELECT COUNT(*) as total_distributions, SUM(total_amount) as total_value 
                         FROM distributions s $whereClause");
        
        if ($campus_id) $this->db->bind(':campus_id', $campus_id);
        if ($date_from) $this->db->bind(':date_from', $date_from);
        if ($date_to) $this->db->bind(':date_to', $date_to);
        
        return $this->db->single();
    }
}
