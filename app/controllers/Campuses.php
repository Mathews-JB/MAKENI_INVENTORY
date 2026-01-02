<?php
class Campuses extends Controller {
    private $campusModel;
    private $schoolModel;

    public function __construct() {
        if (!Session::isLoggedIn()) {
            header('location: ' . URL_ROOT . '/auth/login');
            exit;
        }

        // Only administrators can manage campuses
        if (Session::get('role') != 'administrator') {
            header('location: ' . URL_ROOT . '/dashboard');
            exit;
        }

        $this->campusModel = $this->model('Campus');
        $this->schoolModel = $this->model('School');
    }

    public function index() {
        $campuses = $this->campusModel->getAllCampuses();

        $data = [
            'title' => 'Campuses',
            'campuses' => $campuses
        ];

        $this->view('layouts/main', ['title' => 'Campuses', 'content' => $this->renderView('campuses/index', $data)]);
    }

    public function add() {
        $schools = $this->schoolModel->getAllSchools();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'school_id' => trim($_POST['school_id']),
                'name' => trim($_POST['name']),
                'location' => trim($_POST['location']),
                'phone' => trim($_POST['phone']),
                'schools' => $schools,
                'name_err' => '',
                'school_err' => ''
            ];

            if (empty($data['name'])) {
                $data['name_err'] = 'Please enter campus name';
            }

            if (empty($data['school_id'])) {
                $data['school_err'] = 'Please select a school';
            }

            if (empty($data['name_err']) && empty($data['school_err'])) {
                if ($this->campusModel->addCampus($data)) {
                    Session::flash('success', 'Campus added successfully');
                    header('location: ' . URL_ROOT . '/campuses');
                    exit;
                } else {
                    die('Something went wrong');
                }
            } else {
                $this->view('layouts/main', ['title' => 'Add Campus', 'content' => $this->renderView('campuses/add', $data)]);
            }
        } else {
            $data = [
                'school_id' => '',
                'name' => '',
                'location' => '',
                'phone' => '',
                'schools' => $schools,
                'name_err' => '',
                'school_err' => ''
            ];

            $this->view('layouts/main', ['title' => 'Add Campus', 'content' => $this->renderView('campuses/add', $data)]);
        }
    }

    public function edit($id) {
        $campus = $this->campusModel->getCampusById($id);
        $schools = $this->schoolModel->getAllSchools();

        if (!$campus) {
            header('location: ' . URL_ROOT . '/campuses');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'id' => $id,
                'school_id' => trim($_POST['school_id']),
                'name' => trim($_POST['name']),
                'location' => trim($_POST['location']),
                'phone' => trim($_POST['phone']),
                'schools' => $schools,
                'name_err' => '',
                'school_err' => ''
            ];

            if (empty($data['name'])) {
                $data['name_err'] = 'Please enter campus name';
            }

            if (empty($data['school_id'])) {
                $data['school_err'] = 'Please select a school';
            }

            if (empty($data['name_err']) && empty($data['school_err'])) {
                if ($this->campusModel->updateCampus($data)) {
                    Session::flash('success', 'Campus updated successfully');
                    header('location: ' . URL_ROOT . '/campuses');
                    exit;
                } else {
                    die('Something went wrong');
                }
            } else {
                $this->view('layouts/main', ['title' => 'Edit Campus', 'content' => $this->renderView('campuses/edit', $data)]);
            }
        } else {
            $data = [
                'id' => $id,
                'school_id' => $campus->school_id,
                'name' => $campus->name,
                'location' => $campus->location ?? '',
                'phone' => $campus->phone ?? '',
                'schools' => $schools,
                'name_err' => '',
                'school_err' => ''
            ];

            $this->view('layouts/main', ['title' => 'Edit Campus', 'content' => $this->renderView('campuses/edit', $data)]);
        }
    }

    public function delete($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Check if campus has dependencies
            if ($this->campusModel->hasDependencies($id)) {
                // Get detailed counts
                $details = $this->campusModel->getDependencyDetails($id);
                
                $message = '❌ Cannot delete campus. It has associated records:<br><br>';
                if ($details['purchase_orders'] > 0) {
                    $message .= '📦 ' . $details['purchase_orders'] . ' Purchase Order(s)<br>';
                }
                if ($details['distribution_orders'] > 0) {
                    $message .= '🛒 ' . $details['distribution_orders'] . ' Distribution Order(s)<br>';
                }
                if ($details['users'] > 0) {
                    $message .= '👥 ' . $details['users'] . ' User(s)<br>';
                }
                $message .= '<br>Please remove or reassign these records first.';
                
                Session::flash('error', $message);
                header('location: ' . URL_ROOT . '/campuses');
                exit;
            }
            
            if ($this->campusModel->deleteCampus($id)) {
                Session::flash('success', 'Campus deleted successfully');
                header('location: ' . URL_ROOT . '/campuses');
            } else {
                Session::flash('error', 'Failed to delete campus');
                header('location: ' . URL_ROOT . '/campuses');
            }
        } else {
            header('location: ' . URL_ROOT . '/campuses');
        }
    }

    private function renderView($view, $data = []) {
        ob_start();
        extract($data);
        require_once __DIR__ . '/../views/' . $view . '.php';
        return ob_get_clean();
    }
}
