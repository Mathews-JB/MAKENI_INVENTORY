<?php
class Distributions extends Controller {
    private $distributionOrderModel;
    private $materialModel;
    private $notificationModel;

    public function __construct() {
        if (!Session::isLoggedIn()) {
            header('location: ' . URL_ROOT . '/auth/login');
            exit;
        }

        $this->distributionOrderModel = $this->model('DistributionOrder');
        $this->materialModel = $this->model('Material');
        $this->notificationModel = $this->model('Notification');
    }

    public function index() {
        $campus_id = null;
        if (Session::get('role') == 'administrator') {
             // Admin can see all
        }
        
        if (Session::get('role') != 'administrator') {
            $campus_id = Session::get('campus_id');
        }

        $status = isset($_GET['status']) ? $_GET['status'] : null;
        $orders = $this->distributionOrderModel->getAllOrders($campus_id, $status);

        $data = [
            'title' => 'Distributions',
            'orders' => $orders,
            'selected_status' => $status
        ];

        ob_start();
        require_once '../app/views/distributions/index.php';
        $content = ob_get_clean();
        
        $data['content'] = $content;
        $this->view('layouts/main', $data);
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Parse items (Form sends array, not JSON)
            $items = isset($_POST['items']) ? $_POST['items'] : [];

            if (empty($items)) {
                Session::flash('error', 'Please add at least one item to the distribution');
                header('location: ' . URL_ROOT . '/distributions/create');
                exit;
            }

            // Validate campus_id
            if (Session::get('role') == 'administrator') {
                $campus_id = isset($_POST['campus_id']) ? trim($_POST['campus_id']) : null;
                if (empty($campus_id)) {
                    Session::flash('error', 'Please select a campus for this distribution');
                    header('location: ' . URL_ROOT . '/distributions/create');
                    exit;
                }
            } else {
                $campus_id = Session::get('campus_id');
                if (empty($campus_id)) {
                    Session::flash('error', 'No campus assigned to your account. Please contact administrator.');
                    header('location: ' . URL_ROOT . '/distributions/create');
                    exit;
                }
            }

            $data = [
                'campus_id' => $campus_id,
                'recipient_name' => trim($_POST['recipient_name']),
                'recipient_phone' => trim($_POST['recipient_phone']),
                'recipient_email' => trim($_POST['recipient_email']),
                'payment_method' => $_POST['payment_method'],
                'status' => 'completed', // Default to completed for distributions
                'total_amount' => floatval($_POST['total_amount']),
                'created_by' => Session::get('user_id'),
                'items' => $items
            ];

            $result = $this->distributionOrderModel->createOrder($data);

            if ($result['success']) {
                // Get order details for notification
                $order = $this->distributionOrderModel->getOrderById($result['order_id']);
                
                // Create Order Notification
                $this->notificationModel->createOrderNotification(
                    Session::get('user_id'),
                    'distribution',
                    $order->order_number
                );

                // Check for low stock items
                $this->checkLowStock($items);

                Session::flash('success', 'Distribution created successfully');
                header('location: ' . URL_ROOT . '/distributions/viewOrder/' . $result['order_id']);
                exit;
            } else {
                // Show specific error message
                $errorMsg = $result['error'] ?? 'Failed to create distribution';
                Session::flash('error', $errorMsg);
                header('location: ' . URL_ROOT . '/distributions/create');
                exit;
            }
        } else {
            // Get products for search
            $campus_id = null;
            $school_id = null;

            if (Session::get('role') != 'administrator') {
                $campus_id = Session::get('campus_id');
                // Get school ID from campus to filter materials
                if ($campus_id) {
                    $campus = $this->model('Campus')->getCampusById($campus_id);
                    $school_id = $campus ? $campus->school_id : null;
                }
            }
            
            // Get all materials
            $materials = $this->materialModel->getAllMaterials($school_id, $campus_id);
            
            // Get campuses for administrator
            $campuses = [];
            if (Session::get('role') == 'administrator') {
                $campusModel = $this->model('Campus');
                $campuses = $campusModel->getAllCampuses();
            }

            $data = [
                'title' => 'Create Distribution',
                'materials' => $materials,
                'campuses' => $campuses
            ];

            ob_start();
            require_once '../app/views/distributions/create.php';
            $content = ob_get_clean();
            
            $data['content'] = $content;
            $this->view('layouts/main', $data);
        }
    }

    public function viewOrder($id) {
        $order = $this->distributionOrderModel->getOrderById($id);
        
        if (!$order) {
            header('location: ' . URL_ROOT . '/distributions');
            exit;
        }

        // Check campus access
        if (Session::get('role') != 'administrator' && $order->campus_id != Session::get('campus_id')) {
            header('location: ' . URL_ROOT . '/distributions');
            exit;
        }

        $items = $this->distributionOrderModel->getOrderItems($id);

        $data = [
            'title' => 'Distribution Details',
            'order' => $order,
            'items' => $items
        ];

        ob_start();
        require_once '../app/views/distributions/view.php';
        $content = ob_get_clean();
        
        $data['content'] = $content;
        $data['title'] = 'Distribution #' . $order->order_number;
        $this->view('layouts/main', $data);
    }

    public function receipt($id) {
        $order = $this->distributionOrderModel->getOrderById($id);
        
        if (!$order) {
            header('location: ' . URL_ROOT . '/distributions');
            exit;
        }

        $items = $this->distributionOrderModel->getOrderItems($id);

        $data = [
            'order' => $order,
            'items' => $items
        ];

        extract($data);
        require_once __DIR__ . '/../views/distributions/receipt.php';
    }

    // AJAX endpoint for barcode lookup
    public function lookup() {
        header('Content-Type: application/json');
        
        if (!isset($_GET['code'])) {
            echo json_encode(['success' => false, 'message' => 'No code provided']);
            exit;
        }

        $material = $this->materialModel->findByBarcodeOrSku($_GET['code']);

        if ($material) {
            // Security Check
            if (Session::get('role') != 'administrator') {
                $userCampus = $this->model('Campus')->getCampusById(Session::get('campus_id'));
                if ($material->school_id != $userCampus->school_id) {
                    echo json_encode(['success' => false, 'message' => 'Material not found']);
                    exit;
                }
            }

            // Get inventory for current campus
            $campus_id = Session::get('campus_id');
            $inventory = $this->materialModel->getInventoryByMaterialAndCampus($material->id, $campus_id);

            echo json_encode([
                'success' => true,
                'material' => [
                    'id' => $material->id,
                    'name' => $material->name,
                    'sku' => $material->sku,
                    'barcode' => $material->barcode,
                    'available_stock' => $inventory ? $inventory->quantity : 0
                ]
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Material not found']);
        }
        exit;
    }

    // Check for low stock items after distribution
    private function checkLowStock($items) {
        $campus_id = Session::get('campus_id');
        
        foreach ($items as $item) {
            $material = $this->materialModel->getMaterialById($item['material_id']);
            $inventory = $this->materialModel->getInventoryByMaterialAndCampus($item['material_id'], $campus_id);
            
            if ($material && $inventory && $inventory->quantity <= $material->reorder_level) {
                // Create specific low stock notification
                $this->notificationModel->create([
                    'user_id' => Session::get('user_id'),
                    'title' => 'Low Stock Alert: ' . $material->name,
                    'message' => "Stock for {$material->name} has dropped to {$inventory->quantity} (Reorder Level: {$material->reorder_level})",
                    'type' => 'warning',
                    'link' => '/materials/stock'
                ]);
            }
        }
    }

    // AJAX endpoint to get stock levels for a campus
    public function getStockLevels($campus_id) {
        header('Content-Type: application/json');
        
        if (!$campus_id || $campus_id == '0') {
            $campus_id = Session::get('campus_id');
        }

        if (!$campus_id) {
            echo json_encode(['success' => false, 'error' => 'Campus ID required']);
            return;
        }

        // Check access
        if (Session::get('role') != 'administrator' && Session::get('campus_id') != $campus_id) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit;
        }

        $inventory = $this->materialModel->getStockByCampus($campus_id);
        
        $stock_map = [];
        foreach ($inventory as $item) {
            $stock_map[$item->material_id] = $item->quantity;
        }

        echo json_encode(['success' => true, 'stock' => $stock_map]);
        exit;
    }
}
