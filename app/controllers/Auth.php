<?php
class Auth extends Controller {
    private $userModel;

    public function __construct() {
        $this->userModel = $this->model('User');
    }

    public function index() {
        // Redirect to login
        $this->login();
    }

    public function login() {
        // Check if already logged in
        if (Session::isLoggedIn()) {
            header('location: ' . URL_ROOT . '/dashboard');
            exit;
        }

        // Check for POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Process form
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            // Init data
            $data = [
                'username' => trim($_POST['username']),
                'password' => trim($_POST['password']),
                'username_err' => '',
                'password_err' => ''
            ];

            // Validate Username
            if (empty($data['username'])) {
                $data['username_err'] = 'Please enter username';
            }

            // Validate Password
            if (empty($data['password'])) {
                $data['password_err'] = 'Please enter password';
            }

            // Make sure errors are empty
            if (empty($data['username_err']) && empty($data['password_err'])) {
                // Validated
                $loggedInUser = $this->userModel->login($data['username'], $data['password']);
                
                if ($loggedInUser) {
                    // Create Session
                    $this->createUserSession($loggedInUser);
                } else {
                    $data['password_err'] = 'Password incorrect';
                    $this->view('auth/login', $data);
                }
            } else {
                // Load view with errors
                $this->view('auth/login', $data);
            }
        } else {
            // Init data
            $data = [
                'username' => '',
                'password' => '',
                'username_err' => '',
                'password_err' => ''
            ];

            // Load view
            $this->view('auth/login', $data);
        }
    }

    public function createUserSession($user) {
        // Validate campus assignment for non-administrators
        if ($user->role !== 'administrator') {
            if (!$this->userModel->hasValidCampus($user->id)) {
                $data = [
                    'username' => '',
                    'password' => '',
                    'username_err' => '',
                    'password_err' => 'Your account is not properly configured. No campus has been assigned to your account. Please contact the administrator.'
                ];
                $this->view('auth/login', $data);
                return;
            }
        }
        
        Session::set('user_id', $user->id);
        Session::set('username', $user->username);
        Session::set('full_name', $user->full_name);
        Session::set('role', $user->role);
        Session::set('campus_id', $user->campus_id);
        Session::set('campus_name', $user->campus_name);
        
        // Update last login
        $this->userModel->updateLastLogin($user->id);
        
        header('location: ' . URL_ROOT . '/dashboard');
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            $data = [
                'username' => trim($_POST['username']),
                'full_name' => trim($_POST['full_name']),
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'confirm_password' => trim($_POST['confirm_password']),
                'username_err' => '',
                'full_name_err' => '',
                'email_err' => '',
                'password_err' => '',
                'confirm_password_err' => ''
            ];

            // Validate username
            if (empty($data['username'])) {
                $data['username_err'] = 'Please enter username';
            } else {
                if ($this->userModel->findUserByUsername($data['username'])) {
                    $data['username_err'] = 'Username is already taken';
                }
            }

            // Validate full name
            if (empty($data['full_name'])) {
                $data['full_name_err'] = 'Please enter full name';
            }

            // Validate password
            if (empty($data['password'])) {
                $data['password_err'] = 'Please enter password';
            } elseif (strlen($data['password']) < 6) {
                $data['password_err'] = 'Password must be at least 6 characters';
            }

            // Validate confirm password
            if (empty($data['confirm_password'])) {
                $data['confirm_password_err'] = 'Please confirm password';
            } else {
                if ($data['password'] != $data['confirm_password']) {
                    $data['confirm_password_err'] = 'Passwords do not match';
                }
            }

            // Make sure errors are empty
            if (empty($data['username_err']) && empty($data['full_name_err']) && 
                empty($data['password_err']) && empty($data['confirm_password_err'])) {
                
                // Hash password
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
                $data['role'] = 'teacher'; // Default role
                $data['campus_id'] = null; // Will be assigned by admin
                
                // Register user
                if ($this->userModel->register($data)) {
                    Session::flash('success', 'Registration successful! You can now login with your credentials.');
                    header('location: ' . URL_ROOT . '/auth/login');
                    exit;
                } else {
                    die('Something went wrong');
                }
            } else {
                $this->view('auth/register', $data);
            }
        } else {
            $data = [
                'username' => '',
                'full_name' => '',
                'email' => '',
                'password' => '',
                'confirm_password' => '',
                'username_err' => '',
                'full_name_err' => '',
                'email_err' => '',
                'password_err' => '',
                'confirm_password_err' => ''
            ];

            $this->view('auth/register', $data);
        }
    }

    public function accept_invite($token) {
        // Check if already logged in
        if (Session::isLoggedIn()) {
            Session::destroy(); // Force logout to accept invite
        }

        $invitationModel = $this->model('Invitation');
        $invitation = $invitationModel->getInvitationByToken($token);

        if (!$invitation) {
            Session::flash('error', 'Invalid or expired invitation link.');
            header('location: ' . URL_ROOT . '/auth/login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            $data = [
                'token' => $token,
                'email' => $invitation->email,
                'role' => $invitation->role,
                'campus_id' => $invitation->campus_id,
                'username' => trim($_POST['username']),
                'full_name' => trim($_POST['full_name']),
                'password' => trim($_POST['password']),
                'confirm_password' => trim($_POST['confirm_password']),
                'username_err' => '',
                'full_name_err' => '',
                'password_err' => '',
                'confirm_password_err' => ''
            ];

            // Validate username
            if (empty($data['username'])) {
                $data['username_err'] = 'Please enter username';
            } elseif ($this->userModel->findUserByUsername($data['username'])) {
                $data['username_err'] = 'Username is already taken';
            }

            // Validate full name
            if (empty($data['full_name'])) {
                $data['full_name_err'] = 'Please enter full name';
            }

            // Validate password
            if (empty($data['password'])) {
                $data['password_err'] = 'Please enter password';
            } elseif (strlen($data['password']) < 6) {
                $data['password_err'] = 'Password must be at least 6 characters';
            }

            // Validate confirm password
            if (empty($data['confirm_password'])) {
                $data['confirm_password_err'] = 'Please confirm password';
            } else {
                if ($data['password'] != $data['confirm_password']) {
                    $data['confirm_password_err'] = 'Passwords do not match';
                }
            }

            if (empty($data['username_err']) && empty($data['full_name_err']) && 
                empty($data['password_err']) && empty($data['confirm_password_err'])) {
                
                // Hash password
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
                
                // Register User with Invite Data
                if ($this->userModel->register($data)) {
                    // Mark invitation as used
                    $invitationModel->markAsUsed($invitation->id);

                    Session::flash('success', 'Account created successfully! Please login.');
                    header('location: ' . URL_ROOT . '/auth/login');
                    exit;
                } else {
                    die('Something went wrong');
                }
            } else {
                $this->view('auth/accept_invite', $data);
            }

        } else {
            $data = [
                'token' => $token,
                'email' => $invitation->email,
                'username' => '',
                'full_name' => '',
                'password' => '',
                'confirm_password' => '',
                'username_err' => '',
                'full_name_err' => '',
                'password_err' => '',
                'confirm_password_err' => ''
            ];

            $this->view('auth/accept_invite', $data);
        }
    }

    public function logout() {
        Session::destroy();
        header('location: ' . URL_ROOT . '/auth/login');
    }
}
