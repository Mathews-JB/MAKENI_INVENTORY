<?php
class Campus extends Model {
    public function __construct() {
        parent::__construct();
    }

    // Get all campuses
    public function getAllCampuses() {
        $this->db->query('SELECT b.*, c.name as school_name 
                         FROM campuses b 
                         LEFT JOIN schools c ON b.school_id = c.id 
                         ORDER BY c.name ASC, b.name ASC');
        return $this->db->resultSet();
    }
    
    // Get campuses by school ID
    public function getCampusesBySchool($school_id) {
        $this->db->query('SELECT * FROM campuses WHERE school_id = :school_id ORDER BY name ASC');
        $this->db->bind(':school_id', $school_id);
        return $this->db->resultSet();
    }

    // Get campus by ID
    public function getCampusById($id) {
        $this->db->query('SELECT b.*, c.name as school_name 
                         FROM campuses b 
                         LEFT JOIN schools c ON b.school_id = c.id 
                         WHERE b.id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // Add campus
    public function addCampus($data) {
        $this->db->query('INSERT INTO campuses (school_id, name, location, phone) VALUES (:school_id, :name, :location, :phone)');
        $this->db->bind(':school_id', $data['school_id']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':location', $data['location']);
        $this->db->bind(':phone', $data['phone']);
        return $this->db->execute();
    }

    // Update campus
    public function updateCampus($data) {
        $this->db->query('UPDATE campuses SET school_id = :school_id, name = :name, location = :location, phone = :phone WHERE id = :id');
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':school_id', $data['school_id']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':location', $data['location']);
        $this->db->bind(':phone', $data['phone']);
        return $this->db->execute();
    }

    // Check if campus has dependencies
    public function hasDependencies($id) {
        // Check purchase orders
        $this->db->query('SELECT COUNT(*) as count FROM purchase_orders WHERE campus_id = :id');
        $this->db->bind(':id', $id);
        $poCount = $this->db->single()->count;
        
        // Check distribution orders
        $this->db->query('SELECT COUNT(*) as count FROM distribution_orders WHERE campus_id = :id');
        $this->db->bind(':id', $id);
        $soCount = $this->db->single()->count;
        
        // Check users
        $this->db->query('SELECT COUNT(*) as count FROM users WHERE campus_id = :id');
        $this->db->bind(':id', $id);
        $userCount = $this->db->single()->count;
        
        return ($poCount > 0 || $soCount > 0 || $userCount > 0);
    }

    // Get detailed dependency counts
    public function getDependencyDetails($id) {
        $details = [];
        
        // Get purchase orders count
        $this->db->query('SELECT COUNT(*) as count FROM purchase_orders WHERE campus_id = :id');
        $this->db->bind(':id', $id);
        $details['purchase_orders'] = $this->db->single()->count;
        
        // Get distribution orders count
        $this->db->query('SELECT COUNT(*) as count FROM distribution_orders WHERE campus_id = :id');
        $this->db->bind(':id', $id);
        $details['distribution_orders'] = $this->db->single()->count;
        
        // Get users count
        $this->db->query('SELECT COUNT(*) as count FROM users WHERE campus_id = :id');
        $this->db->bind(':id', $id);
        $details['users'] = $this->db->single()->count;
        
        return $details;
    }

    // Delete campus
    public function deleteCampus($id) {
        $this->db->query('DELETE FROM campuses WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}
