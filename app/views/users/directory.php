<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-900 mb-2">School Staff Directory</h2>
    <p class="text-gray-600">View and manage all school staff and system administrators</p>
</div>

<!-- Filters -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
    <form method="GET" class="flex flex-col md:flex-row gap-4">
        <!-- Search -->
        <div class="flex-1">
            <input type="text" name="search" value="<?php echo htmlspecialchars($data['search']); ?>" 
                   placeholder="Search by name, email, or username..." 
                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
        </div>
        
        <!-- Role Filter -->
        <div>
            <select name="role" class="px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <option value="all" <?php echo $data['role_filter'] == 'all' || !$data['role_filter'] ? 'selected' : ''; ?>>All Roles</option>
                <option value="administrator" <?php echo $data['role_filter'] == 'administrator' ? 'selected' : ''; ?>>Administrator</option>
                <option value="procurement officer" <?php echo $data['role_filter'] == 'procurement officer' ? 'selected' : ''; ?>>Procurement Officer</option>
                <option value="store keeper" <?php echo $data['role_filter'] == 'store keeper' ? 'selected' : ''; ?>>Storekeeper</option>
                <option value="teacher" <?php echo $data['role_filter'] == 'teacher' ? 'selected' : ''; ?>>Teacher / Faculty</option>
                <option value="accountant" <?php echo $data['role_filter'] == 'accountant' ? 'selected' : ''; ?>>Accountant</option>
            </select>
        </div>
        
        <button type="submit" class="px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
            <i class="fas fa-search mr-2"></i>Filter
        </button>
    </form>
</div>

<!-- User Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <?php if (empty($data['users'])): ?>
        <div class="col-span-full text-center py-12">
            <i class="fas fa-users text-gray-300 text-6xl mb-4"></i>
            <p class="text-gray-500 text-lg">No staff found</p>
        </div>
    <?php else: ?>
        <?php foreach ($data['users'] as $user): ?>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition">
                <!-- Header with gradient -->
                <div class="h-24 bg-gradient-to-r from-indigo-600 to-purple-700"></div>
                
                <!-- Profile -->
                <div class="relative px-6 pb-6">
                    <!-- Profile Picture -->
                    <div class="flex justify-center -mt-12 mb-4">
                        <?php if ($user->profile_picture): ?>
                            <img src="<?php echo URL_ROOT; ?>/<?php echo $user->profile_picture; ?>" 
                                 alt="<?php echo $user->full_name; ?>" 
                                 class="w-24 h-24 rounded-full object-cover border-4 border-white shadow-lg">
                        <?php else: ?>
                            <div class="w-24 h-24 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-3xl font-bold border-4 border-white shadow-lg">
                                <?php echo strtoupper(substr($user->full_name, 0, 1)); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- User Info -->
                    <div class="text-center mb-4">
                        <h3 class="text-lg font-bold text-gray-900 mb-1"><?php echo $user->full_name; ?></h3>
                        <p class="text-sm text-gray-500 mb-2">@<?php echo $user->username; ?></p>
                        
                        <?php
                        $roleConfig = [
                            'administrator' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'icon' => 'fa-user-shield'],
                            'procurement officer' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'icon' => 'fa-shopping-cart'],
                            'store keeper' => ['bg' => 'bg-indigo-100', 'text' => 'text-indigo-800', 'icon' => 'fa-warehouse'],
                            'teacher' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'icon' => 'fa-graduation-cap'],
                            'accountant' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-800', 'icon' => 'fa-calculator']
                        ];
                        $config = $roleConfig[$user->role] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'icon' => 'fa-user'];
                        ?>
                        
                        <span class="inline-flex items-center px-3 py-1 <?php echo $config['bg'] . ' ' . $config['text']; ?> rounded-full text-xs font-bold">
                            <i class="fas <?php echo $config['icon']; ?> mr-1"></i>
                            <?php echo ucfirst(str_replace('_', ' ', $user->role)); ?>
                        </span>
                    </div>
                    
                    <!-- Details -->
                    <div class="space-y-2 mb-4">
                        <?php if ($user->email): ?>
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-envelope w-5 text-gray-400"></i>
                            <span class="ml-2 truncate"><?php echo $user->email; ?></span>
                        </div>
                        <?php endif; ?>
                        
                        <?php if ($user->campus_name): ?>
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-school w-5 text-gray-400"></i>
                            <span class="ml-2"><?php echo $user->campus_name; ?></span>
                        </div>
                        <?php endif; ?>
                        
                        <?php if ($user->school_name): ?>
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-university w-5 text-gray-400"></i>
                            <span class="ml-2"><?php echo $user->school_name; ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Actions -->
                    <div class="flex gap-2">
                        <a href="<?php echo URL_ROOT; ?>/users/edit/<?php echo $user->id; ?>" 
                           class="flex-1 px-4 py-2 bg-indigo-600 text-white text-center rounded-lg hover:bg-indigo-700 transition text-sm font-semibold">
                            <i class="fas fa-edit mr-1"></i>Edit
                        </a>
                        <?php if ($user->id != Session::get('user_id')): ?>
                        <form method="POST" action="<?php echo URL_ROOT; ?>/users/resetPassword/<?php echo $user->id; ?>" class="flex-1" onsubmit="return confirm('Reset password to default?')">
                            <button type="submit" class="w-full px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition text-sm font-semibold">
                                <i class="fas fa-key mr-1"></i>Reset
                            </button>
                        </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Stats Summary -->
<div class="mt-6 bg-white rounded-xl shadow-sm border border-gray-200 p-6">
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
        <div class="text-center">
            <div class="text-2xl font-bold text-gray-900"><?php echo count($data['users']); ?></div>
            <div class="text-sm text-gray-500">Total Staff</div>
        </div>
        <div class="text-center">
            <div class="text-2xl font-bold text-red-600">
                <?php echo count(array_filter($data['users'], fn($u) => $u->role == 'administrator')); ?>
            </div>
            <div class="text-sm text-gray-500">Administrators</div>
        </div>
        <div class="text-center">
            <div class="text-2xl font-bold text-blue-600">
                <?php echo count(array_filter($data['users'], fn($u) => $u->role == 'procurement officer')); ?>
            </div>
            <div class="text-sm text-gray-500">Procurement</div>
        </div>
        <div class="text-center">
            <div class="text-2xl font-bold text-indigo-600">
                <?php echo count(array_filter($data['users'], fn($u) => $u->role == 'store keeper')); ?>
            </div>
            <div class="text-sm text-gray-500">Storekeepers</div>
        </div>
        <div class="text-center">
            <div class="text-2xl font-bold text-green-600">
                <?php echo count(array_filter($data['users'], fn($u) => $u->role == 'teacher')); ?>
            </div>
            <div class="text-sm text-gray-500">Teachers</div>
        </div>
    </div>
</div>
