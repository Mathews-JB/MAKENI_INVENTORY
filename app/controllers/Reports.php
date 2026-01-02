<?php
class Reports extends Controller {
    private $materialModel;
    private $schoolModel;
    private $transactionModel;

    public function __construct() {
        if (!Session::isLoggedIn()) {
            header('location: ' . URL_ROOT . '/auth/login');
            exit;
        }

        // Restrict access for teachers
        if (Session::get('role') == 'teacher') {
            Session::flash('error', 'Access denied. You do not have permission to view reports.');
            header('location: ' . URL_ROOT . '/dashboard');
            exit;
        }

        $this->materialModel = $this->model('Material');
        $this->schoolModel = $this->model('School');
        $this->transactionModel = $this->model('Transaction');
    }

    public function index() {
        // Apply campus filtering for non-administrator users
        $campus_id = null;
        $school_id = null;
        
        if (Session::get('role') != 'administrator') {
            $campus_id = Session::get('campus_id');
            if ($campus_id) {
                // Get school ID from campus
                $campus = $this->model('Campus')->getCampusById($campus_id);
                $school_id = $campus ? $campus->school_id : null;
            }
        }

        // Get inventory statistics for charts
        $materials = $this->materialModel->getAllMaterials($school_id, $campus_id);
        $lowStockItems = $this->materialModel->getLowStockItems($school_id, $campus_id);
        $schools = $this->schoolModel->getAllSchools();

        // Prepare chart data
        $chartData = $this->prepareChartData($materials, $schools, $campus_id);

        $data = [
            'title' => 'Reports',
            'schools' => $schools,
            'total_materials' => count($materials),
            'low_stock_count' => count($lowStockItems),
            'chart_data' => $chartData
        ];

        $this->view('layouts/main', ['title' => 'Reports', 'content' => $this->renderView('reports/index', $data)]);
    }

    // Prepare data for charts
    private function prepareChartData($materials, $schools, $campus_id = null) {
        $stockBySchool = [];
        $materialCategories = [];

        foreach ($schools as $school) {
            $stockBySchool[$school->name] = 0;
        }

        foreach ($materials as $material) {
            // Count stock by school
            if (isset($stockBySchool[$material->school_name])) {
                $stockBySchool[$material->school_name] += ($material->campus_stock ?? 0);
            }

            // Count materials by category
            $category = $material->category_name ?? 'Uncategorized';
            if (!isset($materialCategories[$category])) {
                $materialCategories[$category] = 0;
            }
            $materialCategories[$category]++;
        }

        return [
            'stock_by_school' => $stockBySchool,
            'material_categories' => $materialCategories
        ];
    }

    // Stock Report
    public function stock() {
        $school_id = isset($_GET['school']) ? $_GET['school'] : null;
        $campus_id = null;

        if (Session::get('role') != 'administrator') {
            $campus_id = Session::get('campus_id');
            // Force school_id for non-administrators
            if ($campus_id) {
                $campus = $this->model('Campus')->getCampusById($campus_id);
                $school_id = $campus ? $campus->school_id : null;
            }
        }

        // Get materials with stock info
        $materials = $this->materialModel->getAllMaterials($school_id, $campus_id);
        
        // For each material, get detailed inventory
        foreach ($materials as $material) {
            $material->inventory = $this->materialModel->getInventoryByMaterial($material->id);
            
            // Filter inventory by campus if not administrator
            if ($campus_id) {
                $material->inventory = array_filter($material->inventory, function($inv) use ($campus_id) {
                    return $inv->campus_id == $campus_id;
                });
            }
            
            $material->total_stock = 0;
            foreach ($material->inventory as $inv) {
                $material->total_stock += $inv->quantity;
            }
        }

        $data = [
            'title' => 'Inventory Stock Report',
            'materials' => $materials,
            'schools' => $this->schoolModel->getAllSchools(),
            'selected_school' => $school_id
        ];

        $this->view('layouts/main', ['title' => 'Stock Report', 'content' => $this->renderView('reports/stock', $data)]);
    }

    // Low Stock Report
    public function lowStock() {
        $school_id = isset($_GET['school']) ? $_GET['school'] : null;
        $campus_id = null;
        
        if (Session::get('role') != 'administrator') {
            $campus_id = Session::get('campus_id');
            // Force school_id for non-administrators
            if ($campus_id) {
                $campus = $this->model('Campus')->getCampusById($campus_id);
                $school_id = $campus ? $campus->school_id : null;
            }
        }
        
        $materials = $this->materialModel->getLowStockItems($school_id, $campus_id);

        $data = [
            'title' => 'Low Stock Alert Report',
            'materials' => $materials,
            'schools' => $this->schoolModel->getAllSchools(),
            'selected_school' => $school_id
        ];

        $this->view('layouts/main', ['title' => 'Low Stock Report', 'content' => $this->renderView('reports/low_stock', $data)]);
    }

    // Transactions Report
    public function transactions() {
        $school_id = isset($_GET['school']) ? $_GET['school'] : null;
        $start_date = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-d', strtotime('-30 days'));
        $end_date = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-d');
        $type = isset($_GET['type']) ? $_GET['type'] : null;

        $campus_id = null;
        if (Session::get('role') != 'administrator') {
            $campus_id = Session::get('campus_id');
            // Force school_id for non-administrators
            if ($campus_id) {
                $campus = $this->model('Campus')->getCampusById($campus_id);
                $school_id = $campus ? $campus->school_id : null;
            }
        }

        $transactions = $this->transactionModel->getTransactions($school_id, $campus_id, $start_date, $end_date, $type);

        $data = [
            'title' => 'Stock Movement History Report',
            'transactions' => $transactions,
            'schools' => $this->schoolModel->getAllSchools(),
            'selected_school' => $school_id,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'selected_type' => $type
        ];

        $this->view('layouts/main', ['title' => 'Movements Report', 'content' => $this->renderView('reports/transactions', $data)]);
    }

    // Handle Report Generation Form
    public function generate() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $type = $_POST['report_type'];
            $school = $_POST['school_id'];
            $date_range = $_POST['date_range'];
            
            // Calculate dates based on range
            $end_date = date('Y-m-d');
            $start_date = date('Y-m-d', strtotime('-30 days')); // Default
            
            switch($date_range) {
                case 'this_month':
                    $start_date = date('Y-m-01');
                    break;
                case 'last_month':
                    $start_date = date('Y-m-01', strtotime('last month'));
                    $end_date = date('Y-m-t', strtotime('last month'));
                    break;
                case 'this_year':
                    $start_date = date('Y-01-01');
                    break;
            }

            // Redirect to appropriate report
            switch($type) {
                case 'stock':
                    header('location: ' . URL_ROOT . '/reports/stock?school=' . $school);
                    break;
                case 'low_stock':
                    header('location: ' . URL_ROOT . '/reports/lowStock?school=' . $school);
                    break;
                case 'transactions':
                    header('location: ' . URL_ROOT . '/reports/transactions?school=' . $school . '&start_date=' . $start_date . '&end_date=' . $end_date);
                    break;
                default:
                    header('location: ' . URL_ROOT . '/reports');
            }
        } else {
            header('location: ' . URL_ROOT . '/reports');
        }
    }

    // Helper to render view into variable
    private function renderView($view, $data = []) {
        ob_start();
        extract($data);
        require_once '../app/views/' . $view . '.php';
        return ob_get_clean();
    }
}
