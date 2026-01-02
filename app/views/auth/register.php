<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITENAME; ?> - Create Account</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: linear-gradient(135deg, rgba(15, 23, 42, 0.5), rgba(30, 58, 138, 0.6)), 
                        url('https://images.unsplash.com/photo-1553413077-190dd305871c?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .register-container {
            width: 100%;
            max-width: 440px;
        }

        .register-box {
            background: white;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .register-header {
            padding: 32px 32px 24px;
            border-bottom: 1px solid #e2e8f0;
        }

        .logo {
            width: 80px;
            height: 80px;
            margin-bottom: 24px;
        }

        .logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        h1 {
            font-size: 24px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 4px;
        }

        .subtitle {
            color: #64748b;
            font-size: 14px;
        }

        .register-body {
            padding: 32px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            color: #334155;
            margin-bottom: 6px;
        }

        label .optional {
            color: #94a3b8;
            font-weight: 400;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px 12px;
            font-size: 15px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            transition: all 0.15s;
            font-family: inherit;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: #0891b2;
            box-shadow: 0 0 0 3px rgba(8, 145, 178, 0.1);
        }

        input.error {
            border-color: #ef4444;
        }

        .error-message {
            color: #dc2626;
            font-size: 13px;
            margin-top: 6px;
        }

        .btn-primary {
            width: 100%;
            padding: 11px 16px;
            background: #0891b2;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
            margin-top: 8px;
        }

        .btn-primary:hover {
            background: #0e7490;
        }

        .btn-primary:active {
            background: #155e75;
        }

        .divider {
            text-align: center;
            margin: 24px 0;
            color: #94a3b8;
            font-size: 14px;
        }

        .link {
            color: #0891b2;
            text-decoration: none;
            font-weight: 500;
        }

        .link:hover {
            text-decoration: underline;
        }

        .footer-text {
            text-align: center;
            color: #64748b;
            font-size: 13px;
            margin-top: 24px;
        }

        @media (max-width: 480px) {
            .register-header,
            .register-body {
                padding: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-box">
            <div class="register-header">
                <div class="logo">
                    <img src="<?php echo URL_ROOT; ?>/img/logo.png" alt="Logo">
                </div>
                <h1>Create your faculty account</h1>
                <p class="subtitle">Get started with School IVM System</p>
            </div>

            <div class="register-body">
                <form action="<?php echo URL_ROOT; ?>/auth/register" method="post">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input 
                            type="text" 
                            name="username" 
                            id="username"
                            value="<?php echo $data['username']; ?>"
                            class="<?php echo !empty($data['username_err']) ? 'error' : ''; ?>"
                            placeholder="Choose a username"
                            autofocus
                        >
                        <?php if (!empty($data['username_err'])): ?>
                            <div class="error-message"><?php echo $data['username_err']; ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="full_name">Full name</label>
                        <input 
                            type="text" 
                            name="full_name" 
                            id="full_name"
                            value="<?php echo $data['full_name']; ?>"
                            class="<?php echo !empty($data['full_name_err']) ? 'error' : ''; ?>"
                            placeholder="Enter your full name"
                        >
                        <?php if (!empty($data['full_name_err'])): ?>
                            <div class="error-message"><?php echo $data['full_name_err']; ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="email">Email <span class="optional">(optional)</span></label>
                        <input 
                            type="email" 
                            name="email" 
                            id="email"
                            value="<?php echo $data['email']; ?>"
                            placeholder="your@email.com"
                        >
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input 
                            type="password" 
                            name="password" 
                            id="password"
                            value="<?php echo $data['password']; ?>"
                            class="<?php echo !empty($data['password_err']) ? 'error' : ''; ?>"
                            placeholder="At least 6 characters"
                        >
                        <?php if (!empty($data['password_err'])): ?>
                            <div class="error-message"><?php echo $data['password_err']; ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="confirm_password">Confirm password</label>
                        <input 
                            type="password" 
                            name="confirm_password" 
                            id="confirm_password"
                            value="<?php echo $data['confirm_password']; ?>"
                            class="<?php echo !empty($data['confirm_password_err']) ? 'error' : ''; ?>"
                            placeholder="Re-enter your password"
                        >
                        <?php if (!empty($data['confirm_password_err'])): ?>
                            <div class="error-message"><?php echo $data['confirm_password_err']; ?></div>
                        <?php endif; ?>
                    </div>

                    <button type="submit" class="btn-primary">
                        Create account
                    </button>
                </form>

                <div class="divider">
                    Already have an account? <a href="<?php echo URL_ROOT; ?>/auth/login" class="link">Sign in</a>
                </div>

                <div class="footer-text font-semibold">
                    MAKENI School Inventory Management System
                </div>
            </div>
        </div>
    </div>
</body>
</html>
