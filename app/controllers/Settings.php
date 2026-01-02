<?php
class Settings extends Controller {
    private $settingModel;

    public function __construct() {
        if (!Session::isLoggedIn()) {
            header('location: ' . URL_ROOT . '/auth/login');
            exit;
        }

        // Only admin can access settings
        if (Session::get('role') != 'super_admin' && Session::get('role') != 'admin') {
            header('location: ' . URL_ROOT . '/dashboard');
            exit;
        }

        $this->settingModel = $this->model('Setting');
    }

    public function index() {
        $settings = $this->settingModel->getAllAsArray();

        $data = [
            'title' => 'System Settings',
            'settings' => $settings
        ];

        $this->view('layouts/main', ['title' => 'Settings', 'content' => $this->renderView('settings/index', $data)]);
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Process settings update
            $settings = $_POST['settings'];
            
            foreach ($settings as $key => $value) {
                $this->settingModel->set($key, $value);
            }

            Session::flash('success', 'Settings updated successfully');
            header('location: ' . URL_ROOT . '/settings');
        } else {
            header('location: ' . URL_ROOT . '/settings');
        }
    }

    // Helper to render view into variable
    private function renderView($view, $data = []) {
        ob_start();
        extract($data);
        require_once __DIR__ . '/../views/' . $view . '.php';
        return ob_get_clean();
    }
}
