<?php
class Currency extends Controller {
    private $currencyModel;

    public function __construct() {
        if (!Session::isLoggedIn()) {
            header('location: ' . URL_ROOT . '/auth/login');
            exit;
        }

        // Only admins can manage currency rates
        if (Session::get('role') != 'super_admin' && Session::get('role') != 'admin') {
            header('location: ' . URL_ROOT . '/dashboard');
            exit;
        }

        $this->currencyModel = $this->model('CurrencyConverter');
    }

    public function index() {
        // Auto-update rates if needed
        $this->currencyModel->autoUpdate();

        $rates = $this->currencyModel->getAllRates();

        $data = [
            'title' => 'Currency Exchange Rates',
            'rates' => $rates
        ];

        $this->view('layouts/main', ['title' => 'Currency Rates', 'content' => $this->renderView('currency/index', $data)]);
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->currencyModel->updateRates()) {
                Session::flash('success', 'Exchange rates updated successfully');
            } else {
                Session::flash('error', 'Failed to update exchange rates');
            }
            header('location: ' . URL_ROOT . '/currency');
        } else {
            header('location: ' . URL_ROOT . '/currency');
        }
    }

    public function convert() {
        header('Content-Type: application/json');
        
        if (!isset($_GET['amount']) || !isset($_GET['from'])) {
            echo json_encode(['success' => false, 'message' => 'Missing parameters']);
            exit;
        }

        $amount = floatval($_GET['amount']);
        $fromCurrency = $_GET['from'];
        
        $convertedAmount = $this->currencyModel->convertToZMW($amount, $fromCurrency);

        echo json_encode([
            'success' => true,
            'original_amount' => $amount,
            'original_currency' => strtoupper($fromCurrency),
            'converted_amount' => round($convertedAmount, 2),
            'converted_currency' => 'ZMW'
        ]);
        exit;
    }

    private function renderView($view, $data = []) {
        ob_start();
        extract($data);
        require_once __DIR__ . '/../views/' . $view . '.php';
        return ob_get_clean();
    }
}
