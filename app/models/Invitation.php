<?php
class Invitation extends Model {
    public function __construct() {
        parent::__construct();
    }

    // Create a new invitation
    public function createInvitation($data) {
        $this->db->query('INSERT INTO user_invitations (email, token, role, campus_id, expires_at, created_by) 
                         VALUES (:email, :token, :role, :campus_id, :expires_at, :created_by)');
        
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':token', $data['token']);
        $this->db->bind(':role', $data['role']);
        $this->db->bind(':campus_id', $data['campus_id']);
        $this->db->bind(':expires_at', $data['expires_at']);
        $this->db->bind(':created_by', $data['created_by']);
        
        return $this->db->execute();
    }

    // Get invitation by token
    public function getInvitationByToken($token) {
        $this->db->query('SELECT * FROM user_invitations WHERE token = :token AND status = "pending" AND expires_at > NOW()');
        $this->db->bind(':token', $token);
        
        return $this->db->single();
    }

    // Mark invitation as used
    public function markAsUsed($id) {
        $this->db->query('UPDATE user_invitations SET status = "used" WHERE id = :id');
        $this->db->bind(':id', $id);
        
        return $this->db->execute();
    }
    
    // Get pending invitations
    public function getPendingInvitations() {
        $this->db->query('SELECT i.*, b.name as campus_name 
                         FROM user_invitations i 
                         LEFT JOIN campuses b ON i.campus_id = b.id 
                         WHERE i.status = "pending" AND i.expires_at > NOW() 
                         ORDER BY i.created_at DESC');
        
        return $this->db->resultSet();
    }

    // Get all invitations (Repository)
    public function getAllInvitations() {
        $this->db->query('SELECT i.*, b.name as campus_name, u.full_name as creator_name
                         FROM user_invitations i 
                         LEFT JOIN campuses b ON i.campus_id = b.id 
                         LEFT JOIN users u ON i.created_by = u.id
                         ORDER BY i.created_at DESC');
        
        return $this->db->resultSet();
    }

    // Cancel/Delete invitation
    public function deleteInvitation($id) {
        $this->db->query('DELETE FROM user_invitations WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}
