<?php
class Transaction extends Model {
    public function __construct() {
        parent::__construct();
    }

    /**
     * Get transactions with filters
     * @param int|null $school_id
     * @param int|null $campus_id
     * @param string|null $start_date
     * @param string|null $end_date
     * @param string|null $type
     * @return array
     */
    public function getTransactions($school_id = null, $campus_id = null, $start_date = null, $end_date = null, $type = null) {
        $sql = 'SELECT t.*, m.name as material_name, c.name as campus_name, u.username 
                FROM transactions t 
                JOIN materials m ON t.material_id = m.id 
                JOIN campuses c ON t.campus_id = c.id 
                JOIN users u ON t.user_id = u.id 
                WHERE 1=1';
        
        $params = [];

        if ($school_id) {
            $sql .= ' AND m.school_id = :school_id';
            $params[':school_id'] = $school_id;
        }

        if ($campus_id) {
            $sql .= ' AND t.campus_id = :campus_id';
            $params[':campus_id'] = $campus_id;
        }

        if ($start_date) {
            $sql .= ' AND DATE(t.created_at) >= :start_date';
            $params[':start_date'] = $start_date;
        }

        if ($end_date) {
            $sql .= ' AND DATE(t.created_at) <= :end_date';
            $params[':end_date'] = $end_date;
        }

        if ($type) {
            $sql .= ' AND t.type = :type';
            $params[':type'] = $type;
        }

        $sql .= ' ORDER BY t.created_at DESC';

        $this->db->query($sql);

        foreach ($params as $key => $value) {
            $this->db->bind($key, $value);
        }

        return $this->db->resultSet();
    }

    /**
     * Add transaction
     * @param array $data
     * @return bool
     */
    public function addTransaction($data) {
        $this->db->query('INSERT INTO transactions (material_id, campus_id, user_id, type, quantity, reference, notes) 
                         VALUES (:material_id, :campus_id, :user_id, :type, :quantity, :reference, :notes)');
        
        $this->db->bind(':material_id', $data['material_id']);
        $this->db->bind(':campus_id', $data['campus_id']);
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':type', $data['type']);
        $this->db->bind(':quantity', $data['quantity']);
        $this->db->bind(':reference', $data['reference']);
        $this->db->bind(':notes', $data['notes']);
        
        return $this->db->execute();
    }
}
