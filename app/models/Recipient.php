<?php
class Recipient extends Model {
    public function __construct() {
        parent::__construct();
    }

    // Get all recipients
    public function getAllRecipients() {
        $this->db->query('SELECT * FROM recipients ORDER BY name ASC');
        return $this->db->resultSet();
    }

    // Get recipient by ID
    public function getRecipientById($id) {
        $this->db->query('SELECT * FROM recipients WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // Add recipient
    public function addRecipient($data) {
        $this->db->query('INSERT INTO recipients (name, email, phone, address) 
                         VALUES (:name, :email, :phone, :address)');
        
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':phone', $data['phone']);
        $this->db->bind(':address', $data['address']);
        
        if ($this->db->execute()) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    // Search recipients
    public function searchRecipients($term) {
        $this->db->query('SELECT * FROM recipients 
                         WHERE name LIKE :term OR phone LIKE :term OR email LIKE :term 
                         LIMIT 10');
        $this->db->bind(':term', '%' . $term . '%');
        return $this->db->resultSet();
    }
}
