<?php
class Material extends Model {
    public function __construct() {
        parent::__construct();
    }

    // Get all materials
    public function getAllMaterials($school_id = null, $campus_id = null, $include_inactive = false) {
        if ($campus_id) {
            // LEFT JOIN to show all materials (needed for purchase orders)
            $sql = 'SELECT p.*, c.name as category_name, co.name as school_name, COALESCE(i.quantity, 0) as campus_stock 
                    FROM materials p 
                    LEFT JOIN categories c ON p.category_id = c.id 
                    LEFT JOIN schools co ON p.school_id = co.id 
                    LEFT JOIN inventory i ON p.id = i.material_id AND i.campus_id = :campus_id 
                    WHERE 1=1';
        } else {
            $sql = 'SELECT p.*, c.name as category_name, co.name as school_name 
                    FROM materials p 
                    LEFT JOIN categories c ON p.category_id = c.id 
                    LEFT JOIN schools co ON p.school_id = co.id 
                    WHERE 1=1';
        }

        if ($school_id) {
            $sql .= ' AND p.school_id = :school_id';
        }
        
        // Filter inactive materials by default (unless explicitly requested)
        if (!$include_inactive) {
            $sql .= ' AND p.is_active = 1';
        }

        $sql .= ' ORDER BY p.is_active DESC, p.created_at DESC';

        $this->db->query($sql);

        if ($campus_id) {
            $this->db->bind(':campus_id', $campus_id);
        }
        
        if ($school_id) {
            $this->db->bind(':school_id', $school_id);
        }
        
        return $this->db->resultSet();
    }

    // Get material by ID
    public function getMaterialById($id) {
        $this->db->query('SELECT p.*, c.name as category_name, co.name as school_name 
                         FROM materials p 
                         LEFT JOIN categories c ON p.category_id = c.id 
                         LEFT JOIN schools co ON p.school_id = co.id 
                         WHERE p.id = :id');
        $this->db->bind(':id', $id);
        
        return $this->db->single();
    }

    // Add material
    public function addMaterial($data) {
        try {
            // Start transaction
            $this->db->query('START TRANSACTION');
            
            $this->db->query('INSERT INTO materials (school_id, category_id, name, sku, description, type, unit, price, reorder_level) 
                         VALUES (:school_id, :category_id, :name, :sku, :description, :type, :unit, :price, :reorder_level)');
            
            $this->db->bind(':school_id', $data['school_id']);
            $this->db->bind(':category_id', $data['category_id']);
            $this->db->bind(':name', $data['name']);
            $this->db->bind(':sku', $data['sku']);
            $this->db->bind(':description', $data['description']);
            $this->db->bind(':type', $data['type']);
            $this->db->bind(':unit', $data['unit']);
            $this->db->bind(':price', $data['price'] ?? 0);
            $this->db->bind(':reorder_level', $data['reorder_level']);
            
            if ($this->db->execute()) {
                $materialId = $this->db->lastInsertId();
                
                // Auto-create inventory records for all campuses OF THIS SCHOOL
                // This ensures the material appears in stock listings immediately
                $this->db->query('SELECT id FROM campuses WHERE school_id = :school_id');
                $this->db->bind(':school_id', $data['school_id']);
                $campuses = $this->db->resultSet();
                
                foreach ($campuses as $campus) {
                    $quantity = 0;
                    
                    // Set opening stock
                    if (isset($data['opening_stock']) && $data['opening_stock'] > 0) {
                        // If user has a campus_id, assign to that campus
                        if (isset($data['campus_id']) && $campus->id == $data['campus_id']) {
                            $quantity = $data['opening_stock'];
                        } 
                        // If user is Admin (no campus_id), assign to the first campus encountered
                        elseif (empty($data['campus_id']) && $campus === reset($campuses)) {
                            $quantity = $data['opening_stock'];
                        }
                    }

                    $this->db->query('INSERT INTO inventory (material_id, campus_id, quantity) 
                                     VALUES (:material_id, :campus_id, :quantity)');
                    $this->db->bind(':material_id', $materialId);
                    $this->db->bind(':campus_id', $campus->id);
                    $this->db->bind(':quantity', $quantity);
                    $this->db->execute();
                    
                    // Log transaction if stock added
                    if ($quantity > 0) {
                        $this->db->query('INSERT INTO transactions (material_id, campus_id, type, quantity, reference, user_id) 
                                         VALUES (:material_id, :campus_id, "adjustment", :quantity, "Opening Stock", :user_id)');
                        $this->db->bind(':material_id', $materialId);
                        $this->db->bind(':campus_id', $campus->id);
                        $this->db->bind(':quantity', $quantity);
                        $this->db->bind(':user_id', $data['user_id'] ?? 1); // Default to 1 if not set
                        $this->db->execute();
                    }
                }
                
                // Commit transaction
                $this->db->query('COMMIT');
                
                return $materialId;
            }
            
            $this->db->query('ROLLBACK');
            return false;
            
        } catch (Exception $e) {
            $this->db->query('ROLLBACK');
            return false;
        }
    }

    // Update material
    public function updateMaterial($data) {
        $this->db->query('UPDATE materials 
                         SET category_id = :category_id, name = :name, sku = :sku, 
                             description = :description, type = :type, unit = :unit, price = :price, reorder_level = :reorder_level 
                         WHERE id = :id');
        
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':category_id', $data['category_id']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':sku', $data['sku']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':type', $data['type']);
        $this->db->bind(':unit', $data['unit']);
        $this->db->bind(':price', $data['price'] ?? 0);
        $this->db->bind(':reorder_level', $data['reorder_level']);
        
        return $this->db->execute();
    }

    // Soft delete material (set is_active to 0)
    public function deleteMaterial($id) {
        $this->db->query('UPDATE materials SET is_active = 0 WHERE id = :id');
        $this->db->bind(':id', $id);
        
        if ($this->db->execute()) {
            return [
                'success' => true,
                'message' => 'Material deactivated successfully'
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Failed to deactivate material'
            ];
        }
    }
    
    // Reactivate material (set is_active to 1)
    public function reactivateMaterial($id) {
        $this->db->query('UPDATE materials SET is_active = 1 WHERE id = :id');
        $this->db->bind(':id', $id);
        
        if ($this->db->execute()) {
            return [
                'success' => true,
                'message' => 'Material reactivated successfully'
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Failed to reactivate material'
            ];
        }
    }

    // Get inventory for material at campus
    public function getInventoryByMaterialAndCampus($material_id, $campus_id) {
        $this->db->query('SELECT * FROM inventory WHERE material_id = :material_id AND campus_id = :campus_id');
        $this->db->bind(':material_id', $material_id);
        $this->db->bind(':campus_id', $campus_id);
        
        return $this->db->single();
    }

    // Get all inventory for a material
    public function getInventoryByMaterial($material_id) {
        $this->db->query('SELECT i.*, b.name as campus_name, b.location 
                         FROM inventory i 
                         JOIN campuses b ON i.campus_id = b.id 
                         WHERE i.material_id = :material_id');
        $this->db->bind(':material_id', $material_id);
        
        return $this->db->resultSet();
    }

    // Get low stock items
    public function getLowStockItems($school_id = null, $campus_id = null) {
        $sql = 'SELECT p.*, co.name as school_name, SUM(i.quantity) as total_stock 
                FROM materials p 
                LEFT JOIN schools co ON p.school_id = co.id
                LEFT JOIN inventory i ON p.id = i.material_id';
        
        $where = [];
        
        if ($campus_id) {
            // INNER JOIN to only show materials with inventory in this campus
            $sql = 'SELECT p.*, co.name as school_name, COALESCE(SUM(i.quantity), 0) as total_stock 
                    FROM materials p 
                    LEFT JOIN schools co ON p.school_id = co.id
                    INNER JOIN inventory i ON p.id = i.material_id AND i.campus_id = :campus_id';
        }

        // Always filter out inactive materials
        $where[] = 'p.is_active = 1';

        if ($school_id) {
            $where[] = 'p.school_id = :school_id';
        }

        if (!empty($where)) {
            $sql .= ' WHERE ' . implode(' AND ', $where);
        }

        $sql .= ' GROUP BY p.id HAVING total_stock < p.reorder_level';

        $this->db->query($sql);

        if ($campus_id) {
            $this->db->bind(':campus_id', $campus_id);
        }

        if ($school_id) {
            $this->db->bind(':school_id', $school_id);
        }
        
        return $this->db->resultSet();
    }

    // Find material by barcode or SKU
    public function findByBarcodeOrSku($code) {
        $this->db->query('SELECT p.*, c.name as category_name, co.name as school_name 
                         FROM materials p 
                         LEFT JOIN categories c ON p.category_id = c.id 
                         LEFT JOIN schools co ON p.school_id = co.id 
                         WHERE p.barcode = :code OR p.sku = :code');
        $this->db->bind(':code', $code);
        return $this->db->single();
    }
    // Get next SKU sequence
    public function getNextSkuSequence($prefix) {
        $this->db->query('SELECT sku FROM materials WHERE sku LIKE :prefix ORDER BY id DESC LIMIT 1');
        $this->db->bind(':prefix', $prefix . '-%');
        $result = $this->db->single();

        if ($result) {
            // Extract the last part (sequence)
            $parts = explode('-', $result->sku);
            $lastPart = end($parts);
            
            if (is_numeric($lastPart)) {
                return (int)$lastPart + 1;
            }
        }
        
        return 1;
    }

    // Get stock levels for all materials at a specific campus
    public function getStockByCampus($campus_id) {
        $this->db->query('SELECT material_id, quantity FROM inventory WHERE campus_id = :campus_id');
        $this->db->bind(':campus_id', $campus_id);
        return $this->db->resultSet();
    }

    // Get all categories
    public function getCategories() {
        $this->db->query('SELECT * FROM categories ORDER BY name ASC');
        return $this->db->resultSet();
    }
}
