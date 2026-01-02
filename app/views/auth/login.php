<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITENAME; ?> - Login</title>
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
                        url('https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-container {
            width: 100%;
            max-width: 440px;
        }

        .login-box {
            background: white;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .login-header {
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

        .login-body {
            padding: 32px;
        }

        .alert {
            padding: 12px 16px;
            border-radius: 6px;
            margin-bottom: 24px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .alert-success {
            background: #f0fdf4;
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        .alert-error {
            background: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            color: #334155;
            margin-bottom: 6px;
        }

        input[type="text"],
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
            .login-header,
            .login-body {
                padding: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <div class="login-header">
                <div class="logo">
                    <img src="<?php echo URL_ROOT; ?>/img/logo.png" alt="Logo">
                </div>
                <h1>Sign in to School IVM</h1>
                <p class="subtitle">Enter your faculty credentials to continue</p>
            </div>

            <div class="login-body">
                <?php if (Session::has('success')): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        <span><?php echo Session::flash('success'); ?></span>
                    </div>
                <?php endif; ?>

                <?php if (Session::has('error')): ?>
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-circle"></i>
                        <span><?php echo Session::flash('error'); ?></span>
                    </div>
                <?php endif; ?>

                <form action="<?php echo URL_ROOT; ?>/auth/login" method="post">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input 
                            type="text" 
                            name="username" 
                            id="username"
                            value="<?php echo $data['username']; ?>"
                            class="<?php echo !empty($data['username_err']) ? 'error' : ''; ?>"
                            placeholder="Enter your username"
                            autofocus
                        >
                        <?php if (!empty($data['username_err'])): ?>
                            <div class="error-message"><?php echo $data['username_err']; ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input 
                            type="password" 
                            name="password" 
                            id="password"
                            value="<?php echo $data['password']; ?>"
                            class="<?php echo !empty($data['password_err']) ? 'error' : ''; ?>"
                            placeholder="Enter your password"
                        >
                        <?php if (!empty($data['password_err'])): ?>
                            <div class="error-message"><?php echo $data['password_err']; ?></div>
                        <?php endif; ?>
                    </div>

                    <button type="submit" class="btn-primary">
                        Sign in
                    </button>
                </form>

                <div class="divider">
                    Don't have an account? <a href="<?php echo URL_ROOT; ?>/auth/register" class="link">Create one</a>
                </div>

                <div class="footer-text font-semibold">
                    MAKENI School Inventory Management System
                </div>
            </div>
        </div>
    </div>
</body>
</html>
