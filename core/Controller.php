<?php
// Base Controller
class Controller {
    // Load model
    public function model($model) {
        // Require model file
        require_once __DIR__ . '/../app/models/' . $model . '.php';
        // Instantiate model
        return new $model();
    }

    // Load view
    public function view($view, $data = []) {
        // Check for view file
        if (file_exists(__DIR__ . '/../app/views/' . $view . '.php')) {
            // Extract data to variables
            extract($data);
            require_once __DIR__ . '/../app/views/' . $view . '.php';
        } else {
            // View does not exist
            die('View does not exist');
        }
    }
}
