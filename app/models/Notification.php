<?php
class Notification extends Model {
    public function __construct() {
        parent::__construct();
    }

    // Get all notifications for a user
    public function getUserNotifications($user_id, $limit = 10) {
        $this->db->query('SELECT * FROM notifications 
                         WHERE user_id = :user_id 
                         ORDER BY created_at DESC 
                         LIMIT :limit');
        $this->db->bind(':user_id', $user_id);
        $this->db->bind(':limit', $limit);
        return $this->db->resultSet();
    }

    // Get unread notifications count
    public function getUnreadCount($user_id) {
        $this->db->query('SELECT COUNT(*) as count FROM notifications 
                         WHERE user_id = :user_id AND is_read = 0');
        $this->db->bind(':user_id', $user_id);
        $result = $this->db->single();
        return $result ? $result->count : 0;
    }

    // Mark notification as read
    public function markAsRead($notification_id) {
        $this->db->query('UPDATE notifications 
                         SET is_read = 1, read_at = NOW() 
                         WHERE id = :id');
        $this->db->bind(':id', $notification_id);
        return $this->db->execute();
    }

    // Mark all notifications as read for a user
    public function markAllAsRead($user_id) {
        $this->db->query('UPDATE notifications 
                         SET is_read = 1, read_at = NOW() 
                         WHERE user_id = :user_id AND is_read = 0');
        $this->db->bind(':user_id', $user_id);
        return $this->db->execute();
    }

    // Create a new notification
    public function create($data) {
        $this->db->query('INSERT INTO notifications (user_id, title, message, type, link) 
                         VALUES (:user_id, :title, :message, :type, :link)');
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':message', $data['message']);
        $this->db->bind(':type', $data['type'] ?? 'info');
        $this->db->bind(':link', $data['link'] ?? null);
        return $this->db->execute();
    }

    // Delete a notification
    public function delete($notification_id) {
        $this->db->query('DELETE FROM notifications WHERE id = :id');
        $this->db->bind(':id', $notification_id);
        return $this->db->execute();
    }

    // Delete all read notifications for a user
    public function deleteRead($user_id) {
        $this->db->query('DELETE FROM notifications 
                         WHERE user_id = :user_id AND is_read = 1');
        $this->db->bind(':user_id', $user_id);
        return $this->db->execute();
    }

    // Create notification for low stock items
    public function createLowStockNotification($user_id, $count) {
        return $this->create([
            'user_id' => $user_id,
            'title' => 'Low Stock Alert',
            'message' => "$count material(s) are running low on stock. Please restock soon.",
            'type' => 'warning',
            'link' => '/materials/stock'
        ]);
    }

    // Create notification for new order
    public function createOrderNotification($user_id, $order_type, $order_number) {
        $type_label = $order_type == 'distributions' ? 'Distribution' : 'Procurement';
        return $this->create([
            'user_id' => $user_id,
            'title' => "New $type_label Order",
            'message' => "$type_label order #$order_number has been created successfully.",
            'type' => 'success',
            'link' => "/$order_type"
        ]);
    }
}
