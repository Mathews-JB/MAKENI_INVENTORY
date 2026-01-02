<div class="mb-6">
    <h3 class="text-lg font-semibold text-gray-700">System Overview & Staff Management</h3>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-indigo-100 text-sm font-medium">Total Staff</p>
                <h3 class="text-4xl font-bold mt-2"><?php echo $data['user_stats']->total_users; ?></h3>
            </div>
            <div class="bg-white bg-opacity-20 rounded-full p-4">
                <i class="fas fa-users-cog text-3xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-purple-100 text-sm font-medium">Administrators</p>
                <h3 class="text-4xl font-bold mt-2"><?php echo $data['user_stats']->admin_count; ?></h3>
            </div>
            <div class="bg-white bg-opacity-20 rounded-full p-4">
                <i class="fas fa-user-shield text-3xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-emerald-100 text-sm font-medium">Active Staff (7 days)</p>
                <h3 class="text-4xl font-bold mt-2"><?php echo $data['user_stats']->active_users; ?></h3>
            </div>
            <div class="bg-white bg-opacity-20 rounded-full p-4">
                <i class="fas fa-user-check text-3xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-orange-100 text-sm font-medium">Schools Under Management</p>
                <h3 class="text-4xl font-bold mt-2"><?php echo $data['total_companies']; ?></h3>
            </div>
            <div class="bg-white bg-opacity-20 rounded-full p-4">
                <i class="fas fa-university text-3xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Recent Users & System Info -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Recent Users -->
    <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-bold text-gray-800">Recently Added Staff</h3>
            <a href="<?php echo URL_ROOT; ?>/users" class="text-indigo-600 hover:text-indigo-800 text-sm font-semibold flex items-center">
                All Faculty <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
        <div class="space-y-3">
            <?php foreach ($data['recent_users'] as $user): ?>
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition border border-transparent hover:border-indigo-100">
                <div class="flex items-center space-x-3">
                    <?php if ($user->profile_picture): ?>
                        <img src="<?php echo URL_ROOT; ?>/<?php echo $user->profile_picture; ?>" alt="Profile" class="w-10 h-10 rounded-full object-cover shadow-sm">
                    <?php else: ?>
                        <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold shadow-sm">
                            <?php echo strtoupper(substr($user->full_name, 0, 1)); ?>
                        </div>
                    <?php endif; ?>
                    <div>
                        <p class="font-semibold text-gray-800 text-sm"><?php echo $user->full_name; ?></p>
                        <p class="text-xs text-gray-500 italic">@<?php echo $user->username; ?></p>
                    </div>
                </div>
                <?php
                $roleLabels = [
                    'administrator' => 'bg-red-100 text-red-800',
                    'procurement officer' => 'bg-blue-100 text-blue-800',
                    'store keeper' => 'bg-green-100 text-green-800',
                    'teacher' => 'bg-purple-100 text-purple-800',
                    'accountant' => 'bg-amber-100 text-amber-800'
                ];
                $roleClass = $roleLabels[$user->role] ?? 'bg-gray-100 text-gray-800';
                $roleDisplay = ucfirst(str_replace('_', ' ', $user->role));
                ?>
                <span class="px-2 py-1 <?php echo $roleClass; ?> rounded text-xs font-bold uppercase tracking-wider">
                    <?php echo $roleDisplay; ?>
                </span>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- System Information -->
    <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
        <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
            <i class="fas fa-server mr-2 text-indigo-500"></i>System Environment
        </h3>
        <div class="space-y-3">
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                <div class="flex items-center space-x-3">
                    <i class="fas fa-code text-indigo-600"></i>
                    <span class="text-gray-700">PHP Runtime</span>
                </div>
                <span class="font-semibold text-gray-900"><?php echo phpversion(); ?></span>
            </div>
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                <div class="flex items-center space-x-3">
                    <i class="fas fa-database text-emerald-600"></i>
                    <span class="text-gray-700">Storage Engine</span>
                </div>
                <span class="font-semibold text-gray-900">MySQL / MariaDB</span>
            </div>
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                <div class="flex items-center space-x-3">
                    <i class="fas fa-microchip text-blue-600"></i>
                    <span class="text-gray-700">Execution Platform</span>
                </div>
                <span class="font-semibold text-gray-900">Apache / Localhost</span>
            </div>
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                <div class="flex items-center space-x-3">
                    <i class="fas fa-clock text-purple-600"></i>
                    <span class="text-gray-700">School Server Time</span>
                </div>
                <span class="font-semibold text-gray-900"><?php echo date('g:i A'); ?></span>
            </div>
        </div>
        <a href="<?php echo URL_ROOT; ?>/admin/systemInfo" class="block mt-4 text-center text-indigo-600 hover:text-indigo-800 text-sm font-semibold">
            Technical Audit <i class="fas fa-arrow-right ml-1"></i>
        </a>
    </div>
</div>

<!-- User Distribution by Faculty/Role -->
<div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
    <h3 class="text-xl font-bold text-gray-800 mb-4">Staff Distribution by Role</h3>
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
        <?php
        $roleCounts = [
            'administrator' => 0,
            'procurement officer' => 0,
            'store keeper' => 0,
            'teacher' => 0,
            'accountant' => 0
        ];
        foreach ($data['users'] as $user) {
            if (isset($roleCounts[$user->role])) {
                $roleCounts[$user->role]++;
            }
        }
        ?>
        <div class="text-center p-4 bg-red-50 rounded-lg border border-red-100">
            <i class="fas fa-user-shield text-3xl text-red-600 mb-2"></i>
            <p class="text-2xl font-bold text-gray-900"><?php echo $roleCounts['administrator']; ?></p>
            <p class="text-xs text-gray-600 uppercase font-bold">Admins</p>
        </div>
        <div class="text-center p-4 bg-indigo-50 rounded-lg border border-indigo-100">
            <i class="fas fa-file-invoice text-3xl text-indigo-600 mb-2"></i>
            <p class="text-2xl font-bold text-gray-900"><?php echo $roleCounts['procurement officer']; ?></p>
            <p class="text-xs text-gray-600 uppercase font-bold">Procurement</p>
        </div>
        <div class="text-center p-4 bg-emerald-50 rounded-lg border border-emerald-100">
            <i class="fas fa-warehouse text-3xl text-emerald-600 mb-2"></i>
            <p class="text-2xl font-bold text-gray-900"><?php echo $roleCounts['store keeper']; ?></p>
            <p class="text-xs text-gray-600 uppercase font-bold">Storekeepers</p>
        </div>
        <div class="text-center p-4 bg-purple-50 rounded-lg border border-purple-100">
            <i class="fas fa-chalkboard-teacher text-3xl text-purple-600 mb-2"></i>
            <p class="text-2xl font-bold text-gray-900"><?php echo $roleCounts['teacher']; ?></p>
            <p class="text-xs text-gray-600 uppercase font-bold">Teachers</p>
        </div>
        <div class="text-center p-4 bg-amber-50 rounded-lg border border-amber-100">
            <i class="fas fa-calculator text-3xl text-amber-600 mb-2"></i>
            <p class="text-2xl font-bold text-gray-900"><?php echo $roleCounts['accountant']; ?></p>
            <p class="text-xs text-gray-600 uppercase font-bold">Accountants</p>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="mt-6 bg-gradient-to-r from-gray-800 to-indigo-900 rounded-xl shadow-lg p-6 text-white">
    <h3 class="text-xl font-bold mb-4 flex items-center">
        <i class="fas fa-bolt text-amber-400 mr-2"></i>School Admin Quick Actions
    </h3>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <a href="<?php echo URL_ROOT; ?>/users/invite" class="bg-white bg-opacity-10 hover:bg-opacity-20 rounded-lg p-4 text-center transition group">
            <i class="fas fa-envelope-open-text text-3xl mb-2 group-hover:scale-110 transition"></i>
            <p class="text-sm font-semibold">Invite Staff</p>
        </a>
        <a href="<?php echo URL_ROOT; ?>/users" class="bg-white bg-opacity-10 hover:bg-opacity-20 rounded-lg p-4 text-center transition group">
            <i class="fas fa-users-cog text-3xl mb-2 group-hover:scale-110 transition"></i>
            <p class="text-sm font-semibold">Staff Repo</p>
        </a>
        <a href="<?php echo URL_ROOT; ?>/settings" class="bg-white bg-opacity-10 hover:bg-opacity-20 rounded-lg p-4 text-center transition group">
            <i class="fas fa-sliders-h text-3xl mb-2 group-hover:scale-110 transition"></i>
            <p class="text-sm font-semibold">IVM Settings</p>
        </a>
        <a href="<?php echo URL_ROOT; ?>/reports" class="bg-white bg-opacity-10 hover:bg-opacity-20 rounded-lg p-4 text-center transition group">
            <i class="fas fa-chart-pie text-3xl mb-2 group-hover:scale-110 transition"></i>
            <p class="text-sm font-semibold">System Audit</p>
        </a>
    </div>
</div>
