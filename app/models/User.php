<?php
class User extends Model {
    public function __construct() {
        parent::__construct();
    }

    // Find user by username (active and inactive)
    public function findUserByUsername($username) {
        $this->db->query('SELECT u.*, b.name as campus_name FROM users u LEFT JOIN campuses b ON u.campus_id = b.id WHERE u.username = :username');
        $this->db->bind(':username', $username);
        
        $row = $this->db->single();
        
        return $row ? $row : false;
    }

    // Login User (only active users can login)
    public function login($username, $password) {
        $user = $this->findUserByUsername($username);
        
        if ($user && $user->is_active == 1) {
            if (password_verify($password, $user->password)) {
                return $user;
            }
        }
        
        return false;
    }

    // Register User
    public function register($data) {
        $this->db->query('INSERT INTO users (campus_id, username, password, full_name, role, is_active) 
                         VALUES (:campus_id, :username, :password, :full_name, :role, :is_active)');
        
        // Bind values
        $this->db->bind(':campus_id', $data['campus_id']);
        $this->db->bind(':username', $data['username']);
        $this->db->bind(':password', $data['password']);
        $this->db->bind(':full_name', $data['full_name']);
        $this->db->bind(':role', $data['role']);
        $this->db->bind(':is_active', isset($data['is_active']) ? $data['is_active'] : 1);
        
        // Execute
        try {
            if ($this->db->execute()) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            // Check for duplicate entry
            if ($e->getCode() == 23000) {
                return false;
            } else {
                throw $e;
            }
        }
    }

    // Get user by ID
    public function getUserById($id) {
        $this->db->query('SELECT u.*, b.name as campus_name, c.name as school_name 
                         FROM users u 
                         LEFT JOIN campuses b ON u.campus_id = b.id 
                         LEFT JOIN schools c ON b.school_id = c.id 
                         WHERE u.id = :id');
        $this->db->bind(':id', $id);
        
        return $this->db->single();
    }

    // Get all users (optional campus filter, only active by default)
    public function getAllUsers($campus_id = null, $include_inactive = false) {
        $sql = 'SELECT u.*, b.name as campus_name, c.name as school_name 
                 FROM users u 
                 LEFT JOIN campuses b ON u.campus_id = b.id 
                 LEFT JOIN schools c ON b.school_id = c.id';
        
        $conditions = [];
        
        if ($campus_id) {
            $conditions[] = 'u.campus_id = :campus_id';
        }
        
        if (!$include_inactive) {
            $conditions[] = 'u.is_active = 1';
        }
        
        if (!empty($conditions)) {
            $sql .= ' WHERE ' . implode(' AND ', $conditions);
        }
        
        $sql .= ' ORDER BY u.is_active DESC, u.created_at DESC';

        $this->db->query($sql);
        
        if ($campus_id) {
            $this->db->bind(':campus_id', $campus_id);
        }
        
        return $this->db->resultSet();
    }

    // Update profile
    public function updateProfile($data) {
        $query = 'UPDATE users SET full_name = :full_name, email = :email, phone = :phone';
        
        if (isset($data['password'])) {
            $query .= ', password = :password';
        }
        
        $query .= ' WHERE id = :id';
        
        $this->db->query($query);
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':full_name', $data['full_name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':phone', $data['phone']);
        
        if (isset($data['password'])) {
            $this->db->bind(':password', $data['password']);
        }
        
        return $this->db->execute();
    }

    // Update profile picture
    public function updateProfilePicture($user_id, $picture_path) {
        $this->db->query('UPDATE users SET profile_picture = :picture WHERE id = :id');
        $this->db->bind(':id', $user_id);
        $this->db->bind(':picture', $picture_path);
        
        return $this->db->execute();
    }

    // Update last login
    public function updateLastLogin($user_id) {
        $this->db->query('UPDATE users SET last_login = NOW() WHERE id = :id');
        $this->db->bind(':id', $user_id);
        return $this->db->execute();
    }

    // Get system statistics for admin
    public function getSystemStats() {
        $this->db->query('SELECT 
            (SELECT COUNT(*) FROM users) as total_users,
            (SELECT COUNT(*) FROM users WHERE role = "administrator") as admin_count,
            (SELECT COUNT(*) FROM users WHERE role = "teacher") as teacher_count,
            (SELECT COUNT(*) FROM users WHERE last_login >= DATE_SUB(NOW(), INTERVAL 7 DAY)) as active_users
        ');
        
        return $this->db->single();
    }

    // Get database version
    public function getDatabaseVersion() {
        $this->db->query('SELECT VERSION() as version');
        $result = $this->db->single();
        return $result ? $result->version : 'Unknown';
    }

    // Check if user has related records
    public function hasRelatedRecords($id) {
        $tables = [
            'distribution_orders' => 'created_by',
            'procurement_orders' => 'created_by',
            'procurements' => 'user_id',
            'distributions' => 'user_id',
            'transactions' => 'user_id',
            'stock_transfers' => 'user_id'
        ];
        
        $relatedRecords = [];
        
        foreach ($tables as $table => $column) {
            // Check if table exists first
            $this->db->query("SHOW TABLES LIKE '$table'");
            if (!$this->db->single()) {
                continue; // Skip if table doesn't exist
            }
            
            $this->db->query("SELECT COUNT(*) as count FROM $table WHERE $column = :id");
            $this->db->bind(':id', $id);
            $result = $this->db->single();
            
            if ($result && $result->count > 0) {
                $relatedRecords[$table] = $result->count;
            }
        }
        
        return $relatedRecords;
    }
    
    // Soft delete user (set is_active to 0)
    public function deleteUser($id) {
        $this->db->query('UPDATE users SET is_active = 0 WHERE id = :id');
        $this->db->bind(':id', $id);
        
        if ($this->db->execute()) {
            return [
                'success' => true,
                'message' => 'User deactivated successfully'
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Failed to deactivate user'
            ];
        }
    }
    
    // Reactivate user (set is_active to 1)
    public function reactivateUser($id) {
        $this->db->query('UPDATE users SET is_active = 1 WHERE id = :id');
        $this->db->bind(':id', $id);
        
        if ($this->db->execute()) {
            return [
                'success' => true,
                'message' => 'User reactivated successfully'
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Failed to reactivate user'
            ];
        }
    }

    // Reset user password
    public function resetPassword($id, $hashedPassword) {
        $this->db->query('UPDATE users SET password = :password WHERE id = :id');
        $this->db->bind(':password', $hashedPassword);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    // Update user details (Admin)
    public function updateUser($data) {
        $query = 'UPDATE users SET full_name = :full_name, campus_id = :campus_id, role = :role, username = :username';
        
        // Only update password if provided
        if (!empty($data['password'])) {
            $query .= ', password = :password';
        }
        
        $query .= ' WHERE id = :id';
        
        $this->db->query($query);
        
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':full_name', $data['full_name']);
        $this->db->bind(':campus_id', $data['campus_id']);
        $this->db->bind(':role', $data['role']);
        $this->db->bind(':username', $data['username']);
        
        if (!empty($data['password'])) {
            $this->db->bind(':password', $data['password']);
        }
        
        return $this->db->execute();
    }

    // Check if user has a valid campus assignment
    public function hasValidCampus($user_id) {
        $this->db->query('SELECT u.campus_id, c.id as campus_exists 
                         FROM users u 
                         LEFT JOIN campuses c ON u.campus_id = c.id 
                         WHERE u.id = :user_id');
        $this->db->bind(':user_id', $user_id);
        $result = $this->db->single();
        
        // User must have a campus_id and it must exist in campuses table
        return $result && $result->campus_id && $result->campus_exists;
    }
}
