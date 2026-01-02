<?php
class Procurements extends Controller {
    private $procurementOrderModel;
    private $materialModel;
    private $notificationModel;

    public function __construct() {
        if (!Session::isLoggedIn()) {
            header('location: ' . URL_ROOT . '/auth/login');
            exit;
        }

        $this->procurementOrderModel = $this->model('ProcurementOrder');
        $this->materialModel = $this->model('Material');
        $this->notificationModel = $this->model('Notification');
    }

    public function index() {
        $campus_id = null;
        if (Session::get('role') != 'administrator') {
            $campus_id = Session::get('campus_id');
        }

        $status = isset($_GET['status']) ? $_GET['status'] : null;
        $orders = $this->procurementOrderModel->getAllOrders($campus_id, $status);

        $data = [
            'title' => 'Procurement Orders',
            'orders' => $orders,
            'selected_status' => $status
        ];

        $this->view('layouts/main', ['title' => 'Procurement Orders', 'content' => $this->renderView('procurements/index', $data)]);
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Parse items from JSON first
            $items = json_decode($_POST['items_json'] ?? '[]', true);

            if (empty($items)) {
                Session::flash('error', 'Please add at least one item to the order');
                header('location: ' . URL_ROOT . '/procurements/create');
                exit;
            }

            // Validate campus_id
            if (Session::get('role') == 'administrator') {
                $campus_id = isset($_POST['campus_id']) ? trim($_POST['campus_id']) : null;
                if (empty($campus_id)) {
                    Session::flash('error', 'Please select a campus for this order');
                    header('location: ' . URL_ROOT . '/procurements/create');
                    exit;
                }
            } else {
                $campus_id = Session::get('campus_id');
                if (empty($campus_id)) {
                    Session::flash('error', 'No campus assigned to your account. Please contact administrator.');
                    header('location: ' . URL_ROOT . '/procurements/create');
                    exit;
                }
            }

            $data = [
                'campus_id' => $campus_id,
                'vendor_name' => htmlspecialchars(trim($_POST['vendor_name'] ?? $_POST['supplier_name'] ?? ''), ENT_QUOTES, 'UTF-8'),
                'vendor_phone' => htmlspecialchars(trim($_POST['vendor_phone'] ?? $_POST['supplier_phone'] ?? ''), ENT_QUOTES, 'UTF-8'),
                'vendor_email' => htmlspecialchars(trim($_POST['vendor_email'] ?? $_POST['supplier_email'] ?? ''), ENT_QUOTES, 'UTF-8'),
                'total_amount' => floatval($_POST['total_amount'] ?? 0),
                'created_by' => Session::get('user_id'),
                'items' => $items
            ];

            $orderId = $this->procurementOrderModel->createOrder($data);

            if ($orderId) {
                // Get order details for notification
                $order = $this->procurementOrderModel->getOrderById($orderId);
                
                // Create Order Notification
                $this->notificationModel->createOrderNotification(
                    Session::get('user_id'),
                    'procurement',
                    $order->order_number
                );

                Session::flash('success', 'Procurement order created successfully');
                header('location: ' . URL_ROOT . '/procurements/viewOrder/' . $orderId);
                exit;
            } else {
                Session::flash('error', 'Failed to create procurement order');
                header('location: ' . URL_ROOT . '/procurements/create');
                exit;
            }
        } else {
            $schoolModel = $this->model('School');
            
            $materials = [];
            if (Session::get('role') != 'administrator') {
                $user = $this->model('User')->getUserById(Session::get('user_id'));
                if (!$user) {
                     Session::destroy();
                     header('location: ' . URL_ROOT . '/auth/login');
                     exit;
                }
                $userCampus = $this->model('Campus')->getCampusById($user->campus_id);
                if (!$userCampus) {
                    Session::flash('error', 'No campus assigned to your account. Please contact administrator.');
                    header('location: ' . URL_ROOT . '/dashboard');
                    exit;
                }
                $schools = [$schoolModel->getSchoolById($userCampus->school_id)];
                $materials = $this->materialModel->getAllMaterials($userCampus->school_id);
            } else {
                $schools = $schoolModel->getAllSchools();
                $materials = $this->materialModel->getAllMaterials();
            }
            
            $campuses = [];
            if (Session::get('role') == 'administrator') {
                $campusModel = $this->model('Campus');
                $campuses = $campusModel->getAllCampuses();
            }

            $data = [
                'title' => 'Create Procurement Order',
                'schools' => $schools,
                'campuses' => $campuses,
                'materials' => $materials
            ];

            $this->view('layouts/main', ['title' => 'Create Procurement Order', 'content' => $this->renderView('procurements/create', $data)]);
        }
    }

    public function viewOrder($id) {
        $order = $this->procurementOrderModel->getOrderById($id);
        
        if (!$order) {
            header('location: ' . URL_ROOT . '/procurements');
            exit;
        }

        // Check campus access
        if (Session::get('role') != 'administrator' && $order->campus_id != Session::get('campus_id')) {
            header('location: ' . URL_ROOT . '/procurements');
            exit;
        }

        $items = $this->procurementOrderModel->getOrderItems($id);

        $data = [
            'title' => 'Procurement Order Details',
            'order' => $order,
            'items' => $items
        ];

        $this->view('layouts/main', ['title' => 'Procurement Order #' . $order->order_number, 'content' => $this->renderView('procurements/view', $data)]);
    }

    public function receipt($id) {
        $order = $this->procurementOrderModel->getOrderById($id);
        
        if (!$order) {
            header('location: ' . URL_ROOT . '/procurements');
            exit;
        }

        // Check campus access
        if (Session::get('role') != 'administrator' && $order->campus_id != Session::get('campus_id')) {
            header('location: ' . URL_ROOT . '/procurements');
            exit;
        }

        $items = $this->procurementOrderModel->getOrderItems($id);

        $data = [
            'order' => $order,
            'items' => $items
        ];

        extract($data);
        require_once __DIR__ . '/../views/procurements/receipt.php';
    }

    public function receive($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->procurementOrderModel->receiveOrder($id, Session::get('user_id'))) {
                // Create notification for received order
                $order = $this->procurementOrderModel->getOrderById($id);
                $this->notificationModel->create([
                    'user_id' => Session::get('user_id'),
                    'title' => 'Procurement Order Received',
                    'message' => "Procurement order #{$order->order_number} has been received and inventory updated.",
                    'type' => 'success',
                    'link' => '/procurements/viewOrder/' . $id
                ]);

                Session::flash('success', 'Procurement order received and inventory updated');
                header('location: ' . URL_ROOT . '/procurements/viewOrder/' . $id);
            } else {
                Session::flash('error', 'Failed to receive procurement order');
                header('location: ' . URL_ROOT . '/procurements/viewOrder/' . $id);
            }
        } else {
            header('location: ' . URL_ROOT . '/procurements');
        }
    }

    public function lookup() {
        header('Content-Type: application/json');
        
        if (!isset($_GET['code'])) {
            echo json_encode(['success' => false, 'message' => 'No code provided']);
            exit;
        }

        $material = $this->materialModel->findByBarcodeOrSku($_GET['code']);

        if ($material) {
            // Security Check: Validate School Ownership
            if (Session::get('role') != 'administrator') {
                $userCampus = $this->model('Campus')->getCampusById(Session::get('campus_id'));
                if ($material->school_id != $userCampus->school_id) {
                    echo json_encode(['success' => false, 'message' => 'Material not found']);
                    exit;
                }
            }

            echo json_encode([
                'success' => true,
                'material' => [
                    'id' => $material->id,
                    'name' => $material->name,
                    'sku' => $material->sku,
                    'barcode' => $material->barcode
                ]
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Material not found']);
        }
        exit;
    }

    private function renderView($view, $data = []) {
        ob_start();
        extract($data);
        require_once __DIR__ . '/../views/' . $view . '.php';
        return ob_get_clean();
    }
}
