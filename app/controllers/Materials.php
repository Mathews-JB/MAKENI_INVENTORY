<?php
class Materials extends Controller {
    private $materialModel;
    private $schoolModel;

    public function __construct() {
        if (!Session::isLoggedIn()) {
            header('location: ' . URL_ROOT . '/auth/login');
            exit;
        }

        $this->materialModel = $this->model('Material');
        $this->schoolModel = $this->model('School');
    }

    public function index() {
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

        $materials = $this->materialModel->getAllMaterials($school_id, $campus_id); // Show only active materials
        $schools = $this->schoolModel->getAllSchools();

        $data = [
            'title' => 'Materials',
            'materials' => $materials,
            'schools' => $schools,
            'selected_school' => $school_id
        ];

        ob_start();
        require_once '../app/views/materials/list.php';
        $content = ob_get_clean();
        
        $data['content'] = $content;
        $this->view('layouts/main', $data);
    }

    public function archive() {
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

        // Get only inactive materials
        $db = new Database();
        $sql = 'SELECT p.*, c.name as category_name, co.name as school_name 
                FROM materials p 
                LEFT JOIN categories c ON p.category_id = c.id 
                LEFT JOIN schools co ON p.school_id = co.id 
                WHERE p.is_active = 0';
        
        if ($school_id) {
            $sql .= ' AND p.school_id = :school_id';
        }
        
        $sql .= ' ORDER BY p.created_at DESC';
        
        $db->query($sql);
        if ($school_id) {
            $db->bind(':school_id', $school_id);
        }
        $materials = $db->resultSet();
        
        $schools = $this->schoolModel->getAllSchools();

        $data = [
            'title' => 'Archived Materials',
            'materials' => $materials,
            'schools' => $schools,
            'selected_school' => $school_id
        ];

        ob_start();
        require_once '../app/views/materials/archive.php';
        $content = ob_get_clean();
        
        $data['content'] = $content;
        $this->view('layouts/main', $data);
    }

    public function stock() {
        // Get campus filter for non-administrator users
        $campus_id = null;
        $is_filtered = false;
        $campus_name = '';
        
        if (Session::get('role') != 'administrator') {
            $campus_id = Session::get('campus_id');
            $is_filtered = true;
            
            // Get campus name for display
            $campusModel = $this->model('Campus');
            $campus = $campusModel->getCampusById($campus_id);
            $campus_name = $campus ? $campus->name : '';
        }

        // Get all inventory data with material details using a new Database instance
        $db = new Database();
        
        $sql = 'SELECT i.*, p.name as material_name, p.sku, p.reorder_level, 
                       b.name as campus_name, c.name as school_name
                FROM inventory i
                JOIN materials p ON i.material_id = p.id
                JOIN campuses b ON i.campus_id = b.id
                LEFT JOIN schools c ON p.school_id = c.id
                WHERE p.is_active = 1 AND p.school_id = b.school_id';
        
        if ($campus_id) {
            $sql .= ' AND i.campus_id = :campus_id';
        }
        
        $sql .= ' ORDER BY b.name ASC, p.name ASC';
        
        $db->query($sql);
        if ($campus_id) {
            $db->bind(':campus_id', $campus_id);
        }
        $inventory = $db->resultSet();

        $schools = [];
        if (Session::get('role') == 'administrator') {
            $schoolModel = $this->model('School');
            $schools = $schoolModel->getAllSchools();
        }

        $data = [
            'title' => 'Stock Levels',
            'inventory' => $inventory,
            'is_filtered' => $is_filtered,
            'campus_name' => $campus_name,
            'schools' => $schools
        ];

        ob_start();
        require_once '../app/views/materials/stock.php';
        $content = ob_get_clean();
        
        $data['content'] = $content;
        $this->view('layouts/main', $data);
    }

    public function viewMaterial($id) {
        $material = $this->materialModel->getMaterialById($id);

        if (!$material) {
            Session::flash('error', 'Material not found');
            header('location: ' . URL_ROOT . '/materials');
            exit;
        }

        // Security Check: Ensure user belongs to the same school as the material
        if (Session::get('role') != 'administrator') {
            $campus_id = Session::get('campus_id');
            $campus = $this->model('Campus')->getCampusById($campus_id);
            if ($campus && $material->school_id != $campus->school_id) {
                Session::flash('error', 'Access Denied');
                header('location: ' . URL_ROOT . '/materials');
                exit;
            }
        }

        // Get inventory levels for this material
        $db = new Database();
        
        // Filter by campus for non-administrators
        if (Session::get('role') != 'administrator') {
            $campus_id = Session::get('campus_id');
            $db->query('SELECT i.*, b.name as campus_name 
                       FROM inventory i
                       JOIN campuses b ON i.campus_id = b.id
                       WHERE i.material_id = :material_id AND i.campus_id = :campus_id
                       ORDER BY b.name ASC');
            $db->bind(':material_id', $id);
            $db->bind(':campus_id', $campus_id);
        } else {
            // administrator sees all campuses
            $db->query('SELECT i.*, b.name as campus_name 
                       FROM inventory i
                       JOIN campuses b ON i.campus_id = b.id
                       WHERE i.material_id = :material_id
                       ORDER BY b.name ASC');
            $db->bind(':material_id', $id);
        }
        
        $inventory = $db->resultSet();

        $data = [
            'title' => $material->name . ' - Material Details',
            'material' => $material,
            'inventory' => $inventory
        ];

        ob_start();
        require_once '../app/views/materials/view.php';
        $content = ob_get_clean();
        
        $data['content'] = $content;
        $this->view('layouts/main', $data);
    }

    public function add() {
        // Get user's school if not administrator
        if (Session::get('role') != 'administrator') {
            $user = $this->model('User')->getUserById(Session::get('user_id'));
            if (!$user) {
                Session::destroy();
                header('location: ' . URL_ROOT . '/auth/login');
                exit;
            }
            $userCampus = $this->model('Campus')->getCampusById($user->campus_id);
            if (!$userCampus) {
                 Session::flash('error', 'No campus assigned to your account.');
                 header('location: ' . URL_ROOT . '/dashboard');
                 exit;
            }
            $school = $this->schoolModel->getSchoolById($userCampus->school_id);
            $schools = [$school];
        } else {
            $schools = $this->schoolModel->getAllSchools();
        }

        $categories = $this->materialModel->getCategories();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'school_id' => trim($_POST['school_id']),
                'category_id' => !empty($_POST['category_id']) ? trim($_POST['category_id']) : null,
                'name' => trim($_POST['name']),
                'sku' => trim($_POST['sku']),
                'description' => trim($_POST['description']),
                'type' => trim($_POST['type']),
                'unit' => trim($_POST['unit']),
                'price' => floatval($_POST['price'] ?? 0),
                'reorder_level' => trim($_POST['reorder_level']),
                'opening_stock' => intval($_POST['opening_stock'] ?? 0),
                'campus_id' => Session::get('campus_id'),
                'user_id' => Session::get('user_id'),
                'schools' => $schools,
                'categories' => $categories,
                'name_err' => '',
                'school_err' => ''
            ];

            // Validate
            if (empty($data['name'])) {
                $data['name_err'] = 'Please enter material name';
            }

            if (empty($data['school_id'])) {
                $data['school_err'] = 'Please select a school';
            } elseif (Session::get('role') != 'administrator') {
                // Verify user can only add materials for their campus's school
                $user = $this->model('User')->getUserById(Session::get('user_id'));
                $userCampus = $this->model('Campus')->getCampusById($user->campus_id);
                if ($data['school_id'] != $userCampus->school_id) {
                    $data['school_err'] = 'You can only add materials for your campus\'s school';
                }
            }

            // Make sure no errors
            if (empty($data['name_err']) && empty($data['school_err'])) {
                if ($this->materialModel->addMaterial($data)) {
                    Session::flash('success', 'Material added successfully');
                    header('location: ' . URL_ROOT . '/materials');
                    exit;
                } else {
                    die('Something went wrong');
                }
            } else {
                ob_start();
                require_once '../app/views/materials/add.php';
                $content = ob_get_clean();
                
                $data['content'] = $content;
                $data['title'] = 'Add Material';
                $this->view('layouts/main', $data);
            }
        } else {
            $data = [
                'title' => 'Add Material',
                'schools' => $schools,
                'categories' => $categories,
                'school_id' => '',
                'category_id' => '',
                'name' => '',
                'sku' => '',
                'description' => '',
                'type' => 'stationery',
                'unit' => 'pcs',
                'price' => '0.00',
                'reorder_level' => 10,
                'name_err' => '',
                'school_err' => ''
            ];

            ob_start();
            require_once '../app/views/materials/add.php';
            $content = ob_get_clean();
            
            $data['content'] = $content;
            $this->view('layouts/main', $data);
        }
    }

    public function edit($id) {
        $schools = $this->schoolModel->getAllSchools();
        $categories = $this->materialModel->getCategories();
        $material = $this->materialModel->getMaterialById($id);

        if (!$material) {
            header('location: ' . URL_ROOT . '/materials');
            exit;
        }

        // Security Check
        if (Session::get('role') != 'administrator') {
            $campus_id = Session::get('campus_id');
            $campus = $this->model('Campus')->getCampusById($campus_id);
            if ($campus && $material->school_id != $campus->school_id) {
                Session::flash('error', 'Access Denied');
                header('location: ' . URL_ROOT . '/materials');
                exit;
            }
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'id' => $id,
                'category_id' => !empty($_POST['category_id']) ? trim($_POST['category_id']) : null,
                'name' => trim($_POST['name']),
                'sku' => trim($_POST['sku']),
                'description' => trim($_POST['description']),
                'type' => trim($_POST['type']),
                'unit' => trim($_POST['unit']),
                'reorder_level' => trim($_POST['reorder_level']),
                'schools' => $schools,
                'categories' => $categories,
                'material' => $material,
                'inventory' => $this->materialModel->getInventoryByMaterial($id),
                'name_err' => ''
            ];

            if (empty($data['name'])) {
                $data['name_err'] = 'Please enter material name';
            }

            if (empty($data['name_err'])) {
                if ($this->materialModel->updateMaterial($data)) {
                    Session::flash('success', 'Material updated successfully');
                    header('location: ' . URL_ROOT . '/materials');
                    exit;
                } else {
                    die('Something went wrong');
                }
            } else {
                ob_start();
                require_once '../app/views/materials/edit.php';
                $content = ob_get_clean();
                
                $data['content'] = $content;
                $data['title'] = 'Edit Material';
                $this->view('layouts/main', $data);
            }
        } else {
            $data = [
                'title' => 'Edit Material',
                'schools' => $schools,
                'categories' => $categories,
                'material' => $material,
                'inventory' => $this->materialModel->getInventoryByMaterial($id),
                'id' => $id,
                'category_id' => $material->category_id,
                'name' => $material->name,
                'sku' => $material->sku,
                'description' => $material->description,
                'type' => $material->type,
                'unit' => $material->unit,
                'reorder_level' => $material->reorder_level,
                'name_err' => ''
            ];

            ob_start();
            require_once '../app/views/materials/edit.php';
            $content = ob_get_clean();
            
            $data['content'] = $content;
            $this->view('layouts/main', $data);
        }
    }

    public function delete($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Soft delete
            $result = $this->materialModel->deleteMaterial($id);
            
            if ($result['success']) {
                Session::flash('success', $result['message']);
            } else {
                Session::flash('error', $result['message']);
            }
            
            header('location: ' . URL_ROOT . '/materials');
            exit;
        } else {
            header('location: ' . URL_ROOT . '/materials');
            exit;
        }
    }
    
    // Reactivate material
    public function reactivate($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $result = $this->materialModel->reactivateMaterial($id);
            
            if ($result['success']) {
                Session::flash('success', $result['message']);
            } else {
                Session::flash('error', $result['message']);
            }
            
            header('location: ' . URL_ROOT . '/materials');
            exit;
        }
    }

    // API endpoint to get all materials with inventory
    public function getMaterials() {
        header('Content-Type: application/json');
        
        $campus_id = Session::get('campus_id');
        $materials = $this->materialModel->getAllMaterials(null, $campus_id);
        
        // Add inventory info to each material
        foreach ($materials as $material) {
            $inventory = $this->materialModel->getInventoryByMaterialAndCampus($material->id, $campus_id);
            $material->available_stock = $inventory ? $inventory->quantity : 0;
        }
        
        echo json_encode(['success' => true, 'materials' => $materials]);
        exit;
    }

    // API endpoint to search materials
    public function searchMaterials() {
        header('Content-Type: application/json');
        
        if (!isset($_GET['q'])) {
            echo json_encode(['success' => false, 'materials' => []]);
            exit;
        }
        
        $query = trim($_GET['q']);
        $campus_id = Session::get('campus_id');
        
        // Search materials by name or SKU
        $this->materialModel->db->query('SELECT p.* FROM materials p WHERE (p.name LIKE :query OR p.sku LIKE :query) LIMIT 20');
        $this->materialModel->db->bind(':query', '%' . $query . '%');
        $materials = $this->materialModel->db->resultSet();
        
        // Add inventory info
        foreach ($materials as $material) {
            $inventory = $this->materialModel->getInventoryByMaterialAndCampus($material->id, $campus_id);
            $material->available_stock = $inventory ? $inventory->quantity : 0;
        }
        
        echo json_encode(['success' => true, 'materials' => $materials]);
        exit;
    }

    // API endpoint to get next SKU sequence
    public function ajaxGetNextSku() {
        if (!Session::isLoggedIn()) {
            echo json_encode(['error' => 'Unauthorized']);
            exit;
        }

        $prefix = isset($_GET['prefix']) ? trim($_GET['prefix']) : '';
        
        if (empty($prefix)) {
            echo json_encode(['sequence' => '001']);
            exit;
        }

        $nextSeq = $this->materialModel->getNextSkuSequence($prefix);
        
        // Pad with zeros (e.g., 1 -> 001)
        $sequence = str_pad($nextSeq, 3, '0', STR_PAD_LEFT);
        
        header('Content-Type: application/json');
        echo json_encode(['sequence' => $sequence]);
        exit;
    }

    // API endpoint to add material via AJAX
    public function ajaxAddMaterial() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            if (!Session::isLoggedIn()) {
                echo json_encode(['success' => false, 'message' => 'Unauthorized']);
                exit;
            }

            // Get user's campus for school validation
            if (Session::get('role') != 'administrator') {
                $user = $this->model('User')->getUserById(Session::get('user_id'));
                $userCampus = $this->model('Campus')->getCampusById($user->campus_id);
                $allowedSchoolId = $userCampus->school_id;
                
                if ($_POST['school_id'] != $allowedSchoolId) {
                    echo json_encode(['success' => false, 'message' => 'Unauthorized school']);
                    exit;
                }
            }

            $data = [
                'school_id' => trim($_POST['school_id']),
                'category_id' => null, // Optional
                'name' => trim($_POST['name']),
                'sku' => trim($_POST['sku']),
                'description' => trim($_POST['description']),
                'type' => trim($_POST['type']),
                'unit' => trim($_POST['unit']),
                'price' => floatval($_POST['price'] ?? 0),
                'reorder_level' => trim($_POST['reorder_level'])
            ];

            // Validate
            if (empty($data['name']) || empty($data['school_id'])) {
                echo json_encode(['success' => false, 'message' => 'Missing required fields']);
                exit;
            }

            // Check if SKU exists
            if (!empty($data['sku'])) {
                $existing = $this->materialModel->findByBarcodeOrSku($data['sku']);
                if ($existing) {
                    echo json_encode(['success' => false, 'message' => 'SKU already exists']);
                    exit;
                }
            }

            try {
                $materialId = $this->materialModel->addMaterial($data);
                if ($materialId) {
                    // Fetch full material details
                    $material = $this->materialModel->getMaterialById($materialId);
                    
                    // Format for JSON
                    $responseMaterial = [
                        'id' => $material->id,
                        'name' => $material->name,
                        'sku' => $material->sku,
                        'school_id' => $material->school_id,
                        'school_name' => $material->school_name,
                        'price' => 0.00
                    ];

                    echo json_encode(['success' => true, 'material' => $responseMaterial]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Database error']);
                }
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
        }
    }
}
