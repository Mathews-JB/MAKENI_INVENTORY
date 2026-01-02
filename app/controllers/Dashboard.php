<?php
class Dashboard extends Controller {
    private $materialModel;
    private $schoolModel;

    public function __construct() {
        // Check if logged in
        if (!Session::isLoggedIn()) {
            header('location: ' . URL_ROOT . '/auth/login');
            exit;
        }

        $this->materialModel = $this->model('Material');
        $this->schoolModel = $this->model('School');

        // Verify active user exists in the current database
        if (Session::isLoggedIn()) {
            $checkUser = $this->model('User')->getUserById(Session::get('user_id'));
            if (!$checkUser) {
                Session::destroy();
                header('location: ' . URL_ROOT . '/auth/login');
                exit;
            }
        }
    }

    public function index() {
        // Additional security check: Validate campus assignment for non-administrators
        if (Session::get('role') !== 'administrator') {
            $userModel = $this->model('User');
            if (!$userModel->hasValidCampus(Session::get('user_id'))) {
                Session::flash('error', 'Your account is not properly configured. Please contact the administrator.');
                Session::destroy();
                header('location: ' . URL_ROOT . '/auth/login');
                exit;
            }
        }
        
        try {
            // Filter schools based on user role
            if (Session::get('role') == 'administrator') {
                // Administrator sees all schools
                $schools = $this->schoolModel->getAllSchools();
            } else {
                // Other roles see only their campus's school
                $campus_id = Session::get('campus_id');
                if ($campus_id) {
                    $campusModel = $this->model('Campus');
                    $campus = $campusModel->getCampusById($campus_id);
                    if ($campus && $campus->school_id) {
                        $school = $this->schoolModel->getSchoolById($campus->school_id);
                        $schools = $school ? [$school] : [];
                    } else {
                        $schools = [];
                    }
                } else {
                    $schools = [];
                }
            }
            
            // Determine school_id and campus_id
            $school_id = null;
            $campus_id = null;
            
            if (Session::get('role') != 'administrator') {
                $campus_id = Session::get('campus_id');
                if ($campus_id) {
                    $campus = $this->model('Campus')->getCampusById($campus_id);
                    $school_id = $campus ? $campus->school_id : null;
                }
            }
            
            // Filter low stock items by school and campus
            $lowStockItems = $this->materialModel->getLowStockItems($school_id, $campus_id);
            
            // Create low stock notifications if there are any low stock items
            if (!empty($lowStockItems) && count($lowStockItems) > 0) {
                $notificationModel = $this->model('Notification');
                
                // Check if we already notified today
                $db = new Database();
                $db->query('SELECT id FROM notifications 
                           WHERE user_id = :user_id 
                           AND type = "warning" 
                           AND title = "Low Stock Alert"
                           AND DATE(created_at) = CURDATE()
                           LIMIT 1');
                $db->bind(':user_id', Session::get('user_id'));
                $existingNotification = $db->single();
                
                // Only create notification once per day
                if (!$existingNotification) {
                    $notificationModel->createLowStockNotification(
                        Session::get('user_id'), 
                        count($lowStockItems)
                    );
                }
            }
            
            // Get total materials count
            $materials = $this->materialModel->getAllMaterials($school_id, $campus_id);
            $totalMaterials = count($materials);
            
            // Calculate inventory value (quantity * price)
            $db = new Database();
            if ($campus_id) {
                $db->query('SELECT SUM(i.quantity * m.price) as total_value
                           FROM inventory i
                           JOIN materials m ON i.material_id = m.id
                           WHERE i.campus_id = :campus_id');
                $db->bind(':campus_id', $campus_id);
            } else {
                $db->query('SELECT SUM(i.quantity * m.price) as total_value
                           FROM inventory i
                           JOIN materials m ON i.material_id = m.id');
            }
            $valueResult = $db->single();
            $inventoryValue = $valueResult ? ($valueResult->total_value ?? 0) : 0;
            
            // Ensure we have arrays even if empty
            $schools = $schools ?: [];
            $lowStockItems = $lowStockItems ?: [];
            
            $data = [
                'title' => 'Dashboard',
                'schools' => $schools,
                'low_stock_items' => $lowStockItems,
                'total_materials' => $totalMaterials,
                'inventory_value' => $inventoryValue,
                'total_schools' => count($schools),
                'low_stock_count' => count($lowStockItems)
            ];

            // Start output buffering
            ob_start();
            require_once '../app/views/dashboard/index.php';
            $content = ob_get_clean();
            
            $data['content'] = $content;
            $this->view('layouts/main', $data);
            
        } catch (Exception $e) {
            // If there's a database error, show setup instructions
            $data = [
                'title' => 'Dashboard - Setup Required',
                'content' => $this->getSetupInstructions()
            ];
            $this->view('layouts/main', $data);
        }
    }
    
    private function getSetupInstructions() {
        return '
        <div class="max-w-4xl mx-auto">
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-6 rounded-lg mb-6">
                <div class="flex items-center mb-4">
                    <i class="fas fa-exclamation-triangle text-yellow-600 text-3xl mr-4"></i>
                    <h2 class="text-2xl font-bold text-gray-800">Database Setup Required</h2>
                </div>
                <p class="text-gray-700 mb-4">It looks like your database hasn\'t been set up yet. Follow these steps to get started:</p>
            </div>
            
            <div class="bg-white rounded-xl shadow-md p-8">
                <h3 class="text-xl font-bold text-gray-800 mb-6">Setup Instructions</h3>
                
                <div class="space-y-6">
                    <div class="flex items-start">
                        <div class="bg-indigo-600 text-white rounded-full w-8 h-8 flex items-center justify-center font-bold mr-4 flex-shrink-0">1</div>
                        <div>
                            <h4 class="font-semibold text-gray-800 mb-2">Open phpMyAdmin</h4>
                            <p class="text-gray-600">Navigate to: <code class="bg-gray-100 px-2 py-1 rounded">http://localhost/phpmyadmin</code></p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="bg-indigo-600 text-white rounded-full w-8 h-8 flex items-center justify-center font-bold mr-4 flex-shrink-0">2</div>
                        <div>
                            <h4 class="font-semibold text-gray-800 mb-2">Create Database</h4>
                            <p class="text-gray-600">Create a new database named: <code class="bg-gray-100 px-2 py-1 rounded">ivm_system</code></p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="bg-indigo-600 text-white rounded-full w-8 h-8 flex items-center justify-center font-bold mr-4 flex-shrink-0">3</div>
                        <div>
                            <h4 class="font-semibold text-gray-800 mb-2">Import Database Files</h4>
                            <p class="text-gray-600 mb-2">Select the <code class="bg-gray-100 px-2 py-1 rounded">ivm_system</code> database and import the latest schema files from the <code class="bg-gray-100 px-2 py-1 rounded">DATABASE_SCHEMA</code> folder.</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="bg-indigo-600 text-white rounded-full w-8 h-8 flex items-center justify-center font-bold mr-4 flex-shrink-0">4</div>
                        <div>
                            <h4 class="font-semibold text-gray-800 mb-2">Refresh This Page</h4>
                            <p class="text-gray-600">Once the database is imported, refresh this page to see your dashboard!</p>
                        </div>
                    </div>
                </div>
                
                <div class="mt-8 p-4 bg-blue-50 rounded-lg">
                    <h4 class="font-semibold text-blue-900 mb-2"><i class="fas fa-info-circle mr-2"></i>Default Administrator Credentials</h4>
                    <p class="text-blue-800">After importing the schema, you can login with:</p>
                    <ul class="text-blue-800 mt-2 ml-4">
                        <li><strong>Username:</strong> admin</li>
                        <li><strong>Password:</strong> admin123</li>
                    </ul>
                </div>
            </div>
        </div>';
    }
}
