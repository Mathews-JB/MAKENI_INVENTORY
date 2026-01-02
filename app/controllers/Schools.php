<?php
class Schools extends Controller {
    private $schoolModel;

    public function __construct() {
        if (!Session::isLoggedIn()) {
            header('location: ' . URL_ROOT . '/auth/login');
            exit;
        }

        // Only administrators can manage schools
        if (Session::get('role') != 'administrator') {
            header('location: ' . URL_ROOT . '/dashboard');
            exit;
        }

        $this->schoolModel = $this->model('School');
    }

    public function index() {
        $schools = $this->schoolModel->getAllSchools();

        $data = [
            'title' => 'Schools',
            'schools' => $schools
        ];

        $this->view('layouts/main', ['title' => 'Schools', 'content' => $this->renderView('schools/index', $data)]);
    }

    public function add() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'name' => trim($_POST['name']),
                'email' => trim($_POST['email']),
                'phone' => trim($_POST['phone']),
                'address' => trim($_POST['address']),
                'name_err' => ''
            ];

            if (empty($data['name'])) {
                $data['name_err'] = 'Please enter school name';
            }

            if (empty($data['name_err'])) {
                if ($this->schoolModel->addSchool($data)) {
                    Session::flash('success', 'School added successfully');
                    header('location: ' . URL_ROOT . '/schools');
                    exit;
                } else {
                    die('Something went wrong');
                }
            } else {
                $this->view('layouts/main', ['title' => 'Add School', 'content' => $this->renderView('schools/add', $data)]);
            }
        } else {
            $data = [
                'name' => '',
                'email' => '',
                'phone' => '',
                'address' => '',
                'name_err' => ''
            ];

            $this->view('layouts/main', ['title' => 'Add School', 'content' => $this->renderView('schools/add', $data)]);
        }
    }

    public function edit($id) {
        $school = $this->schoolModel->getSchoolById($id);

        if (!$school) {
            header('location: ' . URL_ROOT . '/schools');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'id' => $id,
                'name' => trim($_POST['name']),
                'email' => trim($_POST['email']),
                'phone' => trim($_POST['phone']),
                'address' => trim($_POST['address']),
                'name_err' => ''
            ];

            if (empty($data['name'])) {
                $data['name_err'] = 'Please enter school name';
            }

            if (empty($data['name_err'])) {
                if ($this->schoolModel->updateSchool($data)) {
                    Session::flash('success', 'School updated successfully');
                    header('location: ' . URL_ROOT . '/schools');
                    exit;
                } else {
                    die('Something went wrong');
                }
            } else {
                $this->view('layouts/main', ['title' => 'Edit School', 'content' => $this->renderView('schools/edit', $data)]);
            }
        } else {
            $data = [
                'id' => $id,
                'name' => $school->name,
                'email' => $school->email,
                'phone' => $school->phone,
                'address' => $school->address,
                'name_err' => ''
            ];

            $this->view('layouts/main', ['title' => 'Edit School', 'content' => $this->renderView('schools/edit', $data)]);
        }
    }

    public function delete($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->schoolModel->deleteSchool($id)) {
                Session::flash('success', 'School deleted successfully');
                header('location: ' . URL_ROOT . '/schools');
            } else {
                die('Something went wrong');
            }
        } else {
            header('location: ' . URL_ROOT . '/schools');
        }
    }

    private function renderView($view, $data = []) {
        ob_start();
        extract($data);
        require_once __DIR__ . '/../views/' . $view . '.php';
        return ob_get_clean();
    }
}
