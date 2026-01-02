<?php
class Admin extends Controller {
    private $userModel;
    private $materialModel;
    private $schoolModel;

    public function __construct() {
        if (!Session::isLoggedIn()) {
            header('location: ' . URL_ROOT . '/auth/login');
            exit;
        }

        // Only administrators can access
        if (Session::get('role') != 'administrator') {
            header('location: ' . URL_ROOT . '/dashboard');
            exit;
        }

        $this->userModel = $this->model('User');
        $this->materialModel = $this->model('Material');
        $this->schoolModel = $this->model('School');
    }

    public function index() {
        // Get system statistics
        $userStats = $this->userModel->getSystemStats();
        $users = $this->userModel->getAllUsers();
        $schools = $this->schoolModel->getAllSchools();
        
        // Get recent users
        $recentUsers = array_slice($users, 0, 10);

        $data = [
            'title' => 'System Administration',
            'user_stats' => $userStats,
            'users' => $users,
            'recent_users' => $recentUsers,
            'schools' => $schools,
            'total_schools' => count($schools)
        ];

        ob_start();
        require_once '../app/views/admin/dashboard.php';
        $content = ob_get_clean();
        
        $data['content'] = $content;
        $this->view('layouts/main', $data);
    }

    public function systemInfo() {
        // Get detailed system information
        $data = [
            'title' => 'System Information',
            'php_version' => phpversion(),
            'server_software' => $_SERVER['SERVER_SOFTWARE'],
            'database_version' => $this->getDatabaseVersion(),
            'upload_max_size' => ini_get('upload_max_filesize'),
            'post_max_size' => ini_get('post_max_size'),
            'memory_limit' => ini_get('memory_limit')
        ];

        ob_start();
        extract($data); // Extract variables so they're accessible in the view
        require_once '../app/views/admin/system_info.php';
        $content = ob_get_clean();
        
        $data['content'] = $content;
        $this->view('layouts/main', $data);
    }

    private function getDatabaseVersion() {
        return $this->userModel->getDatabaseVersion();
    }
}
