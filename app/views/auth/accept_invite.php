<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accept Invitation - <?php echo SITENAME; ?></title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-indigo-900 via-purple-900 to-blue-900 min-h-screen flex items-center justify-center p-4">

    <div class="glass-effect w-full max-w-md p-8 rounded-2xl shadow-2xl relative overflow-hidden">
        <!-- Decoration -->
        <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-indigo-500 to-purple-500"></div>
        
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-indigo-100 text-indigo-600 mb-4 animate-bounce">
                <i class="fas fa-envelope-open-text text-2xl"></i>
            </div>
            <h2 class="text-3xl font-bold text-gray-800">Accept Invitation</h2>
            <p class="text-gray-600 mt-2">Create your account for <span class="font-semibold text-indigo-600"><?php echo $data['email']; ?></span></p>
        </div>

        <form action="<?php echo URL_ROOT; ?>/auth/accept_invite/<?php echo $data['token']; ?>" method="POST">
            
            <!-- Full Name -->
            <div class="mb-5">
                <label for="full_name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-user text-gray-400"></i>
                    </div>
                    <input type="text" name="full_name" id="full_name" 
                           class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 <?php echo (!empty($data['full_name_err'])) ? 'border-red-500' : ''; ?>"
                           value="<?php echo $data['full_name']; ?>" placeholder="John Doe">
                </div>
                <?php if (!empty($data['full_name_err'])): ?>
                    <p class="mt-1 text-xs text-red-600"><?php echo $data['full_name_err']; ?></p>
                <?php endif; ?>
            </div>

            <!-- Username -->
            <div class="mb-5">
                <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-at text-gray-400"></i>
                    </div>
                    <input type="text" name="username" id="username" 
                           class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 <?php echo (!empty($data['username_err'])) ? 'border-red-500' : ''; ?>"
                           value="<?php echo $data['username']; ?>" placeholder="johndoe">
                </div>
                <?php if (!empty($data['username_err'])): ?>
                    <p class="mt-1 text-xs text-red-600"><?php echo $data['username_err']; ?></p>
                <?php endif; ?>
            </div>

            <!-- Password -->
            <div class="mb-5">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-lock text-gray-400"></i>
                    </div>
                    <input type="password" name="password" id="password" 
                           class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 <?php echo (!empty($data['password_err'])) ? 'border-red-500' : ''; ?>"
                           value="<?php echo $data['password']; ?>" placeholder="••••••••">
                </div>
                <?php if (!empty($data['password_err'])): ?>
                    <p class="mt-1 text-xs text-red-600"><?php echo $data['password_err']; ?></p>
                <?php endif; ?>
            </div>

            <!-- Confirm Password -->
            <div class="mb-6">
                <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-lock text-gray-400"></i>
                    </div>
                    <input type="password" name="confirm_password" id="confirm_password" 
                           class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 <?php echo (!empty($data['confirm_password_err'])) ? 'border-red-500' : ''; ?>"
                           value="<?php echo $data['confirm_password']; ?>" placeholder="••••••••">
                </div>
                <?php if (!empty($data['confirm_password_err'])): ?>
                    <p class="mt-1 text-xs text-red-600"><?php echo $data['confirm_password_err']; ?></p>
                <?php endif; ?>
            </div>

            <button type="submit" class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-3 rounded-lg font-bold shadow-lg hover:from-indigo-700 hover:to-purple-700 transition transform hover:-translate-y-0.5">
                Create Account
            </button>
        </form>
    </div>

</body>
</html>
