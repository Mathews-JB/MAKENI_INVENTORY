<?php
class Vendor extends Model {
    public function __construct() {
        parent::__construct();
    }

    // Get all vendors
    public function getAllVendors($school_id = null) {
        $sql = 'SELECT * FROM vendors WHERE 1=1';
        if ($school_id) {
            $sql .= ' AND school_id = :school_id';
        }
        $sql .= ' ORDER BY name ASC';
        
        $this->db->query($sql);
        if ($school_id) {
            $this->db->bind(':school_id', $school_id);
        }
        return $this->db->resultSet();
    }

    // Get vendor by ID
    public function getVendorById($id) {
        $this->db->query('SELECT * FROM vendors WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // Add vendor
    public function addVendor($data) {
        $this->db->query('INSERT INTO vendors (name, contact_person, email, phone, address, school_id) 
                         VALUES (:name, :contact_person, :email, :phone, :address, :school_id)');
        
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':contact_person', $data['contact_person']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':phone', $data['phone']);
        $this->db->bind(':address', $data['address']);
        $this->db->bind(':school_id', $data['school_id']);
        
        return $this->db->execute();
    }

    // Update vendor
    public function updateVendor($data) {
        $this->db->query('UPDATE vendors 
                         SET name = :name, contact_person = :contact_person, 
                             email = :email, phone = :phone, address = :address 
                         WHERE id = :id');
        
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':contact_person', $data['contact_person']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':phone', $data['phone']);
        $this->db->bind(':address', $data['address']);
        
        return $this->db->execute();
    }

    // Delete vendor
    public function deleteVendor($id) {
        $this->db->query('DELETE FROM vendors WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}
