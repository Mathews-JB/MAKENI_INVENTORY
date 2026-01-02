<?php
class Transactions extends Controller {
    private $transactionModel;
    private $schoolModel;

    public function __construct() {
        if (!Session::isLoggedIn()) {
            header('location: ' . URL_ROOT . '/auth/login');
            exit;
        }

        $this->transactionModel = $this->model('Transaction');
        $this->schoolModel = $this->model('School');
    }

    public function index() {
        $school_id = isset($_GET['school']) ? $_GET['school'] : null;
        $start_date = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-d', strtotime('-30 days'));
        $end_date = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-d');
        $type = isset($_GET['type']) ? $_GET['type'] : null;

        $campus_id = null;
        if (Session::get('role') != 'administrator') {
            $campus_id = Session::get('campus_id');
        }

        $transactions = $this->transactionModel->getTransactions($school_id, $campus_id, $start_date, $end_date, $type);

        $data = [
            'title' => 'Transactions',
            'transactions' => $transactions,
            'schools' => $this->schoolModel->getAllSchools(),
            'selected_school' => $school_id,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'selected_type' => $type
        ];

        $this->view('layouts/main', ['title' => 'Transactions', 'content' => $this->renderView('transactions/index', $data)]);
    }

    // Helper to render view into variable
    private function renderView($view, $data = []) {
        ob_start();
        extract($data);
        require_once __DIR__ . '/../views/' . $view . '.php';
        return ob_get_clean();
    }
}
