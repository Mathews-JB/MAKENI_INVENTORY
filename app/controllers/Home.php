<?php
class Home extends Controller {
    public function index() {
        // Check if already logged in, redirect to dashboard
        if (Session::isLoggedIn()) {
            header('location: ' . URL_ROOT . '/dashboard');
            exit;
        }

        $data = [
            'title' => 'Welcome to IVM System'
        ];

        $this->view('home/index', $data);
    }
}
