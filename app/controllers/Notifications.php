<?php
class Notifications extends Controller {
    private $notificationModel;

    public function __construct() {
        if (!Session::isLoggedIn()) {
            header('location: ' . URL_ROOT . '/auth/login');
            exit;
        }

        $this->notificationModel = $this->model('Notification');
    }

    public function index() {
        $user_id = Session::get('user_id');
        $notifications = $this->notificationModel->getUserNotifications($user_id, 50); // Fetch more for the page
        
        $data = [
            'title' => 'Notifications',
            'notifications' => $notifications,
            'active_tab' => 'notifications' // For sidebar highlighting
        ];

        // Start output buffering to capture the view content
        ob_start();
        require_once '../app/views/notifications/index.php';
        $content = ob_get_clean();
        
        $data['content'] = $content;
        $this->view('layouts/main', $data);
    }

    // Get notifications (AJAX)
    public function get() {
        header('Content-Type: application/json');
        $user_id = Session::get('user_id');
        $notifications = $this->notificationModel->getUserNotifications($user_id, 20);
        $unread_count = $this->notificationModel->getUnreadCount($user_id);
        
        echo json_encode([
            'success' => true,
            'notifications' => $notifications,
            'unread_count' => $unread_count
        ]);
        exit;
    }

    // Mark as read
    public function markRead($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->notificationModel->markAsRead($id);
            echo json_encode(['success' => true]);
        }
        exit;
    }

    // Mark all as read
    public function markAllRead() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $user_id = Session::get('user_id');
            $this->notificationModel->markAllAsRead($user_id);
            echo json_encode(['success' => true]);
        }
        exit;
    }

    // Delete notification
    public function delete($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->notificationModel->delete($id);
            echo json_encode(['success' => true]);
        }
        exit;
    }

    // Clear all read notifications
    public function clearRead() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $user_id = Session::get('user_id');
            $this->notificationModel->deleteRead($user_id);
            echo json_encode(['success' => true]);
        }
        exit;
    }
}
