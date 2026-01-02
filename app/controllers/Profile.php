<?php
class Profile extends Controller {
    private $userModel;

    public function __construct() {
        if (!Session::isLoggedIn()) {
            header('location: ' . URL_ROOT . '/auth/login');
            exit;
        }

        $this->userModel = $this->model('User');
    }

    public function index() {
        $user = $this->userModel->getUserById(Session::get('user_id'));
        if (!$user) {
            Session::destroy();
            header('location: ' . URL_ROOT . '/auth/login');
            exit;
        }

        $data = [
            'title' => 'My Profile',
            'user' => $user
        ];

        ob_start();
        require_once __DIR__ . '/../views/profile/index.php';
        $content = ob_get_clean();
        
        $data['content'] = $content;
        $this->view('layouts/main', $data);
    }

    public function edit() {
        $user = $this->userModel->getUserById(Session::get('user_id'));

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'id' => Session::get('user_id'),
                'full_name' => trim($_POST['full_name']),
                'email' => trim($_POST['email']),
                'phone' => trim($_POST['phone']),
                'current_password' => trim($_POST['current_password']),
                'new_password' => trim($_POST['new_password']),
                'confirm_password' => trim($_POST['confirm_password']),
                'user' => $user,
                'full_name_err' => '',
                'password_err' => ''
            ];

            // Validate full name
            if (empty($data['full_name'])) {
                $data['full_name_err'] = 'Please enter full name';
            }

            // Validate password change if provided
            if (!empty($data['new_password'])) {
                if (empty($data['current_password'])) {
                    $data['password_err'] = 'Please enter current password';
                } elseif (!password_verify($data['current_password'], $user->password)) {
                    $data['password_err'] = 'Current password is incorrect';
                } elseif (strlen($data['new_password']) < 6) {
                    $data['password_err'] = 'New password must be at least 6 characters';
                } elseif ($data['new_password'] != $data['confirm_password']) {
                    $data['password_err'] = 'Passwords do not match';
                }
            }

            if (empty($data['full_name_err']) && empty($data['password_err'])) {
                // Update profile
                $updateData = [
                    'id' => $data['id'],
                    'full_name' => $data['full_name'],
                    'email' => $data['email'],
                    'phone' => $data['phone']
                ];

                // Add password if changing
                if (!empty($data['new_password'])) {
                    $updateData['password'] = password_hash($data['new_password'], PASSWORD_DEFAULT);
                }

                if ($this->userModel->updateProfile($updateData)) {
                    // Update session
                    Session::set('full_name', $data['full_name']);
                    Session::flash('success', 'Profile updated successfully');
                    header('location: ' . URL_ROOT . '/profile');
                    exit;
                } else {
                    die('Something went wrong');
                }
            } else {
                ob_start();
                require_once __DIR__ . '/../views/profile/edit.php';
                $content = ob_get_clean();
                
                $data['content'] = $content;
                $data['title'] = 'Edit Profile';
                $this->view('layouts/main', $data);
            }
        } else {
            $data = [
                'title' => 'Edit Profile',
                'user' => $user,
                'full_name' => $user->full_name,
                'email' => $user->email,
                'phone' => $user->phone,
                'current_password' => '',
                'new_password' => '',
                'confirm_password' => '',
                'full_name_err' => '',
                'password_err' => ''
            ];

            ob_start();
            require_once __DIR__ . '/../views/profile/edit.php';
            $content = ob_get_clean();
            
            $data['content'] = $content;
            $this->view('layouts/main', $data);
        }
    }

    public function uploadPicture() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['profile_picture'])) {
            $file = $_FILES['profile_picture'];
            
            // Validate file
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            $filename = $file['name'];
            $fileTmp = $file['tmp_name'];
            $fileSize = $file['size'];
            $fileExt = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

            if (!in_array($fileExt, $allowed)) {
                Session::flash('error', 'Invalid file type. Only JPG, PNG, and GIF allowed.', 'alert alert-danger');
                header('location: ' . URL_ROOT . '/profile');
                exit;
            }

            if ($fileSize > 5000000) { // 5MB
                Session::flash('error', 'File too large. Maximum 5MB allowed.', 'alert alert-danger');
                header('location: ' . URL_ROOT . '/profile');
                exit;
            }

            // Create uploads directory if not exists
            $uploadDir = '../public/uploads/profiles/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            // Generate unique filename
            $newFilename = 'user_' . Session::get('user_id') . '_' . time() . '.' . $fileExt;
            $destination = $uploadDir . $newFilename;

            if (move_uploaded_file($fileTmp, $destination)) {
                // Delete old profile picture if exists
                $user = $this->userModel->getUserById(Session::get('user_id'));
                if ($user->profile_picture && file_exists('../public/' . $user->profile_picture)) {
                    unlink('../public/' . $user->profile_picture);
                }

                // Update database
                $picturePath = 'uploads/profiles/' . $newFilename;
                if ($this->userModel->updateProfilePicture(Session::get('user_id'), $picturePath)) {
                    Session::flash('success', 'Profile picture updated successfully');
                } else {
                    Session::flash('error', 'Failed to update profile picture', 'alert alert-danger');
                }
            } else {
                Session::flash('error', 'Failed to upload file', 'alert alert-danger');
            }

            header('location: ' . URL_ROOT . '/profile');
        } else {
            header('location: ' . URL_ROOT . '/profile');
        }
    }
}
