<?php
class Users extends Controller {
    private $userModel;
    private $schoolModel;

    public function __construct() {
        if (!Session::isLoggedIn()) {
            header('location: ' . URL_ROOT . '/auth/login');
            exit;
        }

        // Only administrators can access
        if (Session::get('role') != 'administrator') {
            header('location: ' . URL_ROOT . '/dashboard');
            exit;
        }

        $this->userModel = $this->model('User');
        $this->schoolModel = $this->model('School');
    }

    public function index() {
        // If not administrator, filter by campus
        $campus_id = null;
        if (Session::get('role') != 'administrator') {
            $campus_id = Session::get('campus_id');
        }
        
        // Administrators should see inactive users (pending approval)
        $include_inactive = (Session::get('role') == 'administrator');
        $all_users = $this->userModel->getAllUsers($campus_id, $include_inactive);
        
        // Filter: Show Active users OR Pending users (inactive but never logged in)
        // Hide: Deactivated users (inactive AND have logged in before)
        $users = array_filter($all_users, function($user) {
            // Always show active
            if ($user->is_active == 1) return true;
            
            // Show if inactive but never logged in (Pending)
            if ($user->is_active == 0 && empty($user->last_login)) return true;
            
            // Hide otherwise (Deactivated)
            return false;
        });
        
        $schools = $this->schoolModel->getAllSchools();

        $data = [
            'title' => 'User Management',
            'users' => $users,
            'schools' => $schools
        ];

        ob_start();
        require_once '../app/views/users/list.php';
        $content = ob_get_clean();
        
        $data['content'] = $content;
        $this->view('layouts/main', $data);
    }

    public function directory() {
        // Only administrators can access directory
        if (Session::get('role') != 'administrator') {
            header('location: ' . URL_ROOT . '/dashboard');
            exit;
        }

        $role_filter = isset($_GET['role']) ? $_GET['role'] : '';
        $search = isset($_GET['search']) ? $_GET['search'] : '';
        
        // Get all users
        $users = $this->userModel->getAllUsers();
        
        // Filter by role if specified
        if ($role_filter && $role_filter != 'all') {
            $users = array_filter($users, function($user) use ($role_filter) {
                return $user->role == $role_filter;
            });
        }
        
        // Filter by search if specified
        if ($search) {
            $users = array_filter($users, function($user) use ($search) {
                return stripos($user->full_name, $search) !== false || 
                       stripos($user->email, $search) !== false ||
                       stripos($user->username, $search) !== false;
            });
        }

        $data = [
            'title' => 'User Directory',
            'users' => $users,
            'role_filter' => $role_filter,
            'search' => $search
        ];

        ob_start();
        require_once '../app/views/users/directory.php';
        $content = ob_get_clean();
        
        $data['content'] = $content;
        $this->view('layouts/main', $data);
    }

    public function invite() {
        // Only administrators can invite
        if (Session::get('role') != 'administrator') {
            header('location: ' . URL_ROOT . '/users');
            exit;
        }

        $schools = $this->schoolModel->getAllSchools();
        $campuses = [];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'email' => trim($_POST['email']),
                'role' => trim($_POST['role']),
                'campus_id' => !empty($_POST['campus_id']) && $_POST['campus_id'] !== 'Select Campus' ? trim($_POST['campus_id']) : null,
                'schools' => $schools,
                'campuses' => $campuses,
                'email_err' => '',
                'campus_err' => ''
            ];

            // Validate Email
            if (empty($data['email'])) {
                $data['email_err'] = 'Please enter email';
            } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $data['email_err'] = 'Invalid email format';
            }

            // Validate Campus
            if (!in_array($data['role'], ['administrator']) && empty($data['campus_id'])) {
                $data['campus_err'] = 'Campus is required for this role';
            }

            if (empty($data['email_err']) && empty($data['campus_err'])) {
                // Generate secure token
                $token = bin2hex(random_bytes(32));
                
                $invitationData = [
                    'email' => $data['email'],
                    'token' => $token,
                    'role' => $data['role'],
                    'campus_id' => $data['campus_id'],
                    'expires_at' => date('Y-m-d H:i:s', strtotime('+48 hours')),
                    'created_by' => Session::get('user_id')
                ];

                $invitationModel = $this->model('Invitation');
                if ($invitationModel->createInvitation($invitationData)) {
                    // Send Email Implementation
                    $inviteLink = URL_ROOT . '/auth/accept_invite/' . $token;
                    $subject = "You are invited to join " . SITENAME;
                    $message = "You have been invited to join the School Inventory Management System.\n\n";
                    $message .= "Role: " . ucfirst(str_replace('_', ' ', $data['role'])) . "\n";
                    $message .= "Click the link below to set up your account:\n";
                    $message .= $inviteLink . "\n\n";
                    $message .= "This link expires in 48 hours.";
                    $headers = "From: no-reply@schoolivm.com" . "\r\n";
                    
                    // Send Email
                    $to = $data['email'];
                    $subject = "Invitation to join " . SITENAME;
                    
                    // HTML Email Template
                    $message = '
                    <html>
                    <head>
                        <title>Invitation to join ' . SITENAME . '</title>
                        <style>
                            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                            .header { background: #4f46e5; color: white; padding: 20px; text-align: center; border-radius: 10px 10px 0 0; }
                            .content { background: #f9fafb; padding: 30px; border: 1px solid #e5e7eb; border-radius: 0 0 10px 10px; }
                            .button { display: inline-block; padding: 12px 24px; background: #4f46e5; color: white; text-decoration: none; border-radius: 5px; font-weight: bold; margin: 20px 0; }
                            .footer { text-align: center; padding-top: 20px; font-size: 12px; color: #6b7280; }
                        </style>
                    </head>
                    <body>
                        <div class="container">
                            <div class="header">
                                <h1>Welcome to ' . SITENAME . '</h1>
                            </div>
                            <div class="content">
                                <p>Hello,</p>
                                <p>You have been invited to join <strong>' . SITENAME . '</strong> as a <strong>' . ucfirst(str_replace('_', ' ', $data['role'])) . '</strong>.</p>
                                <p>To set up your account and get started, please click the button below:</p>
                                <div style="text-align: center;">
                                    <a href="' . $inviteLink . '" class="button">Accept Invitation</a>
                                </div>
                                <p>Or copy and paste this link into your browser:</p>
                                <p><small>' . $inviteLink . '</small></p>
                                <p>This link will expire in 48 hours.</p>
                            </div>
                            <div class="footer">
                                <p>&copy; ' . date('Y') . ' ' . SITENAME . '. All rights reserved.</p>
                            </div>
                        </div>
                    </body>
                    </html>
                    ';

                    // Headers for HTML email
                    $headers = "MIME-Version: 1.0" . "\r\n";
                    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                    $headers .= 'From: ' . SITENAME . ' <no-reply@' . $_SERVER['HTTP_HOST'] . '>' . "\r\n";
                    $headers .= 'X-Mailer: PHP/' . phpversion();

                    // Attempt to send email
                    if (Session::get('role') == 'administrator') {
                       @mail($to, $subject, $message, $headers);
                    }

                    // Render Success View
                    $data['success'] = true;
                    $data['invite_link'] = $inviteLink;
                    
                    ob_start();
                    require_once '../app/views/users/invite.php';
                    $content = ob_get_clean();
                    $data['content'] = $content;
                    $data['title'] = 'Invitation Sent';
                    $this->view('layouts/main', $data);
                    exit;
                } else {
                    die('Something went wrong');
                }
            } else {
                ob_start();
                require_once '../app/views/users/invite.php';
                $content = ob_get_clean();
                $data['content'] = $content;
                $data['title'] = 'Invite User';
                $this->view('layouts/main', $data);
            }
        } else {
            $data = [
                'email' => '',
                'role' => 'teacher',
                'campus_id' => '',
                'schools' => $schools,
                'campuses' => $campuses,
                'email_err' => '',
                'campus_err' => ''
            ];

            ob_start();
            require_once '../app/views/users/invite.php';
            $content = ob_get_clean();
            $data['content'] = $content;
            $data['title'] = 'Invite User';
            $this->view('layouts/main', $data);
        }
    }

    public function invitations() {
        if (Session::get('role') != 'administrator') {
            header('location: ' . URL_ROOT . '/users');
            exit;
        }

        $invitations = $this->model('Invitation')->getAllInvitations();
        
        $data = [
            'title' => 'Invitation Repository',
            'invitations' => $invitations
        ];

        ob_start();
        require_once '../app/views/users/invitations.php';
        $content = ob_get_clean();
        
        $data['content'] = $content;
        $this->view('layouts/main', $data);
    }

    public function deleteInvitation($id) {
        if (Session::get('role') != 'administrator') {
            header('location: ' . URL_ROOT . '/users');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->model('Invitation')->deleteInvitation($id)) {
                Session::flash('success', 'Invitation deleted successfully');
            } else {
                Session::flash('error', 'Failed to delete invitation');
            }
            header('location: ' . URL_ROOT . '/users/invitations');
        }
    }

    public function add() {
        $schools = $this->schoolModel->getAllSchools();
        $campuses = [];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'campus_id' => !empty($_POST['campus_id']) && $_POST['campus_id'] !== 'Select Campus' ? trim($_POST['campus_id']) : null,
                'username' => trim($_POST['username']),
                'password' => trim($_POST['password']),
                'full_name' => trim($_POST['full_name']),
                'role' => trim($_POST['role']),
                'schools' => $schools,
                'campuses' => $campuses,
                'username_err' => '',
                'password_err' => '',
                'full_name_err' => ''
            ];

            // Validate and Enforce Permissions
            if (Session::get('role') != 'administrator') {
                // Force campus_id for non-administrators (though only admins can normally access this)
                $data['campus_id'] = Session::get('campus_id');
                
                // Prevent creating administrator
                if ($data['role'] == 'administrator') {
                    $data['role'] = 'teacher'; // Fallback
                    Session::flash('error', 'You cannot create Administrator users', 'alert alert-danger');
                }
                
                // Users created by sub-admins are pending approval (inactive)
                $data['is_active'] = 0;
            } else {
                // Administrators create active users by default
                $data['is_active'] = 1;
            }

            // Validate
            if (empty($data['username'])) {
                $data['username_err'] = 'Please enter username';
            } elseif ($this->userModel->findUserByUsername($data['username'])) {
                $data['username_err'] = 'Username already exists';
            }

            if (empty($data['full_name'])) {
                $data['full_name_err'] = 'Please enter full name';
            }

            if (empty($data['password'])) {
                $data['password_err'] = 'Please enter password';
            } elseif (strlen($data['password']) < 6) {
                $data['password_err'] = 'Password must be at least 6 characters';
            }

            if (!in_array($data['role'], ['administrator']) && empty($data['campus_id'])) {
                $data['campus_err'] = 'Campus is required for this role';
            }

            if (empty($data['username_err']) && empty($data['full_name_err']) && empty($data['password_err']) && empty($data['campus_err'] ?? '')) {
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
                
                if ($this->userModel->register($data)) {
                    if ($data['is_active'] == 0) {
                        Session::flash('success', 'User added successfully and is pending approval by Administrator');
                    } else {
                        Session::flash('success', 'User added successfully');
                    }
                    header('location: ' . URL_ROOT . '/users');
                    exit;
                } else {
                    die('Something went wrong');
                }
            } else {
                ob_start();
                require_once '../app/views/users/add.php';
                $content = ob_get_clean();
                
                $data['content'] = $content;
                $data['title'] = 'Add User';
                $this->view('layouts/main', $data);
            }
        } else {
            $data = [
                'title' => 'Add User',
                'schools' => $schools,
                'campuses' => $campuses,
                'campus_id' => '',
                'username' => '',
                'password' => '',
                'full_name' => '',
                'role' => 'teacher',
                'username_err' => '',
                'password_err' => '',
                'full_name_err' => ''
            ];

            ob_start();
            require_once '../app/views/users/add.php';
            $content = ob_get_clean();
            
            $data['content'] = $content;
            $this->view('layouts/main', $data);
        }
    }

    public function delete($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Prevent self-deletion
            if ($id == Session::get('user_id')) {
                Session::flash('error', 'You cannot delete your own account');
                header('location: ' . URL_ROOT . '/users');
                exit;
            }

            $result = $this->userModel->deleteUser($id);
            
            if ($result['success']) {
                Session::flash('success', $result['message']);
            } else {
                Session::flash('error', $result['message']);
            }
            
            header('location: ' . URL_ROOT . '/users');
            exit;
        }
    }

    public function resetPassword($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $defaultPassword = 'password123';
            $hashedPassword = password_hash($defaultPassword, PASSWORD_DEFAULT);
            
            if ($this->userModel->resetPassword($id, $hashedPassword)) {
                Session::flash('success', 'Password reset successfully. New password: password123');
                header('location: ' . URL_ROOT . '/users');
            } else {
                Session::flash('error', 'Failed to reset password');
                header('location: ' . URL_ROOT . '/users');
            }
        } else {
            header('location: ' . URL_ROOT . '/users');
        }
    }
    
    public function reactivate($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Only administrators can reactivate users
            if (Session::get('role') != 'administrator') {
                Session::flash('error', 'Only administrators can reactivate users');
                header('location: ' . URL_ROOT . '/users');
                exit;
            }
            
            $result = $this->userModel->reactivateUser($id);
            
            if ($result['success']) {
                Session::flash('success', $result['message']);
            } else {
                Session::flash('error', $result['message']);
            }
            
            header('location: ' . URL_ROOT . '/users');
            exit;
        }
    }

    public function edit($id) {
        $user = $this->userModel->getUserById($id);
        
        if (!$user) {
            header('location: ' . URL_ROOT . '/users');
            exit;
        }

        // Security Check: Non-administrators can only edit users in their campus
        if (Session::get('role') != 'administrator') {
            if ($user->campus_id != Session::get('campus_id')) {
                Session::flash('error', 'Access Denied: You can only edit users in your campus');
                header('location: ' . URL_ROOT . '/users');
                exit;
            }
        }

        $schools = $this->schoolModel->getAllSchools();
        $campuses = [];
        
        // If user has a school (derived from campus), fetch campuses for that school
        $userSchoolId = null;
        if ($user->campus_id) {
             $campus = $this->model('Campus')->getCampusById($user->campus_id);
             $userSchoolId = $campus ? $campus->school_id : null;
             if ($userSchoolId) {
                 $campuses = $this->model('Campus')->getCampusesBySchool($userSchoolId);
             }
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'id' => $id,
                'user' => $user,
                'schools' => $schools,
                'all_campuses' => $campuses,
                'campus_id' => !empty($_POST['campus_id']) && $_POST['campus_id'] !== 'Select Campus' ? trim($_POST['campus_id']) : null,
                'username' => trim($_POST['username']),
                'password' => trim($_POST['password']),
                'full_name' => trim($_POST['full_name']),
                'role' => trim($_POST['role']),
                'username_err' => '',
                'password_err' => '',
                'full_name_err' => '',
                'campus_err' => ''
            ];

            // Validate Permissions on Post Data
            if (Session::get('role') != 'administrator') {
                $data['campus_id'] = Session::get('campus_id'); // Force campus
                if ($data['role'] == 'administrator') {
                    $data['role'] = 'teacher'; // Prevent privilege escalation
                }
            }

            // Validate
            if (empty($data['username'])) {
                $data['username_err'] = 'Please enter username';
            } else {
                $existingUser = $this->userModel->findUserByUsername($data['username']);
                if ($existingUser && $existingUser->id != $id) {
                    $data['username_err'] = 'Username already exists';
                }
            }

            if (empty($data['full_name'])) {
                $data['full_name_err'] = 'Please enter full name';
            }

            if (!empty($data['password']) && strlen($data['password']) < 6) {
                $data['password_err'] = 'Password must be at least 6 characters';
            }

            if (!in_array($data['role'], ['administrator']) && empty($data['campus_id'])) {
                $data['campus_err'] = 'Campus is required for this role';
            }

            if (empty($data['username_err']) && empty($data['full_name_err']) && empty($data['password_err']) && empty($data['campus_err'])) {
                
                if (!empty($data['password'])) {
                    $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
                }

                if ($this->userModel->updateUser($data)) {
                    Session::flash('success', 'User updated successfully');
                    header('location: ' . URL_ROOT . '/users');
                    exit;
                } else {
                    die('Something went wrong');
                }
            } else {
                $this->view('layouts/main', ['title' => 'Edit User', 'content' => $this->renderView('users/edit', $data)]);
            }

        } else {
            $data = [
                'id' => $id,
                'user' => $user,
                'schools' => $schools,
                'all_campuses' => $campuses,
                'campus_id' => $user->campus_id,
                'username' => $user->username,
                'password' => '',
                'full_name' => $user->full_name,
                'role' => $user->role,
                'username_err' => '',
                'password_err' => '',
                'full_name_err' => '',
                'campus_err' => ''
            ];

            $this->view('layouts/main', ['title' => 'Edit User', 'content' => $this->renderView('users/edit', $data)]);
        }
    }

    private function renderView($view, $data = []) {
        ob_start();
        extract($data);
        require_once __DIR__ . '/../views/' . $view . '.php';
        return ob_get_clean();
    }

    public function getCampuses($school_id) {
        $campusModel = $this->model('Campus');
        $campuses = $campusModel->getCampusesBySchool($school_id);
        echo json_encode($campuses);
    }

    public function deactivated() {
        if (Session::get('role') != 'administrator') {
            header('location: ' . URL_ROOT . '/users');
            exit;
        }

        $all_users = $this->userModel->getAllUsers(null, true);
        
        $users = array_filter($all_users, function($user) {
            return ($user->is_active == 0 && !empty($user->last_login));
        });

        $data = [
            'title' => 'Deactivated Users',
            'users' => $users
        ];

        ob_start();
        require_once '../app/views/users/deactivated.php';
        $content = ob_get_clean();
        
        $data['content'] = $content;
        $this->view('layouts/main', $data);
    }
}
