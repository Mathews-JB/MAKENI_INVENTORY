<?php
class School extends Model {
    public function __construct() {
        parent::__construct();
    }

    // Get all schools
    public function getAllSchools() {
        $this->db->query('SELECT * FROM schools ORDER BY name ASC');
        return $this->db->resultSet();
    }

    // Get school by ID
    public function getSchoolById($id) {
        $this->db->query('SELECT * FROM schools WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // Get campuses for a school
    public function getCampusesBySchool($school_id) {
        $this->db->query('SELECT * FROM campuses WHERE school_id = :school_id ORDER BY name ASC');
        $this->db->bind(':school_id', $school_id);
        return $this->db->resultSet();
    }

    // Add school
    public function addSchool($data) {
        $this->db->query('INSERT INTO schools (name, email, phone, address) VALUES (:name, :email, :phone, :address)');
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':phone', $data['phone']);
        $this->db->bind(':address', $data['address']);
        return $this->db->execute();
    }

    // Update school
    public function updateSchool($data) {
        $this->db->query('UPDATE schools SET name = :name, email = :email, phone = :phone, address = :address WHERE id = :id');
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':phone', $data['phone']);
        $this->db->bind(':address', $data['address']);
        return $this->db->execute();
    }

    // Delete school
    public function deleteSchool($id) {
        $this->db->query('DELETE FROM schools WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}
