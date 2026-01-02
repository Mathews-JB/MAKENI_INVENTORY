<!-- Header Section -->
<div class="mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Users</h2>
            <p class="text-gray-500 mt-1">Manage school staff, teachers, and system administrators</p>
        </div>
        <div class="flex space-x-3">
            <?php if (Session::get('role') == 'administrator'): ?>
            <a href="<?php echo URL_ROOT; ?>/users/deactivated" class="bg-gray-100 text-gray-700 px-4 py-3 rounded-lg hover:bg-gray-200 transition flex items-center shadow-sm" title="View Deactivated Users">
                <i class="fas fa-trash-alt mr-2"></i>
                Deactivated
            </a>
            <?php endif; ?>
            <div class="inline-flex rounded-md shadow-sm" role="group">
                <a href="<?php echo URL_ROOT; ?>/users/invite" class="bg-white text-indigo-600 border border-indigo-200 px-6 py-3 rounded-l-lg hover:bg-indigo-50 transition flex items-center">
                    <i class="fas fa-paper-plane mr-2"></i>
                    Invite Staff
                </a>
                <a href="<?php echo URL_ROOT; ?>/users/invitations" class="bg-indigo-50 text-indigo-700 border-t border-b border-r border-indigo-200 px-4 py-3 rounded-r-lg hover:bg-indigo-100 transition flex items-center" title="View Invitation History">
                    <i class="fas fa-history"></i>
                </a>
            </div>
            <a href="<?php echo URL_ROOT; ?>/users/add" class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-6 py-3 rounded-lg hover:from-indigo-700 hover:to-purple-700 transition shadow-lg flex items-center transform hover:scale-105">
                <i class="fas fa-user-plus mr-2"></i>
                Add New Staff
            </a>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<?php
$totalUsers = count($data['users']);
$adminCount = count(array_filter($data['users'], function($u) { return $u->role == 'administrator'; }));
$procurementCount = count(array_filter($data['users'], function($u) { return in_array($u->role, ['procurement officer', 'store keeper']); }));
$teacherCount = count(array_filter($data['users'], function($u) { return $u->role == 'teacher'; }));
?>

<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <!-- Total Users -->
    <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl p-6 text-white shadow-lg hover:shadow-xl transition transform hover:-translate-y-1">
        <div class="flex items-center justify-between mb-4">
            <div class="bg-white bg-opacity-20 rounded-lg p-3">
                <i class="fas fa-users text-2xl"></i>
            </div>
        </div>
        <h3 class="text-3xl font-bold mb-1"><?php echo $totalUsers; ?></h3>
        <p class="text-indigo-100 text-sm">Total Users</p>
    </div>

    <!-- Admins -->
    <div class="bg-gradient-to-br from-red-500 to-orange-500 rounded-xl p-6 text-white shadow-lg hover:shadow-xl transition transform hover:-translate-y-1">
        <div class="flex items-center justify-between mb-4">
            <div class="bg-white bg-opacity-20 rounded-lg p-3">
                <i class="fas fa-user-shield text-2xl"></i>
            </div>
        </div>
        <h3 class="text-3xl font-bold mb-1"><?php echo $adminCount; ?></h3>
        <p class="text-red-100 text-sm">Administrators</p>
    </div>

    <!-- Procurement/Store -->
    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-6 text-white shadow-lg hover:shadow-xl transition transform hover:-translate-y-1">
        <div class="flex items-center justify-between mb-4">
            <div class="bg-white bg-opacity-20 rounded-lg p-3">
                <i class="fas fa-warehouse text-2xl"></i>
            </div>
        </div>
        <h3 class="text-3xl font-bold mb-1"><?php echo $procurementCount; ?></h3>
        <p class="text-blue-100 text-sm">Logistics & Store</p>
    </div>

    <!-- Teachers -->
    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-6 text-white shadow-lg hover:shadow-xl transition transform hover:-translate-y-1">
        <div class="flex items-center justify-between mb-4">
            <div class="bg-white bg-opacity-20 rounded-lg p-3">
                <i class="fas fa-graduation-cap text-2xl"></i>
            </div>
        </div>
        <h3 class="text-3xl font-bold mb-1"><?php echo $teacherCount; ?></h3>
        <p class="text-green-100 text-sm">Teachers / Faculty</p>
    </div>
</div>

<!-- Search Bar -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
    <div class="relative">
        <input type="text" id="searchUsers" placeholder="Search by name, username, or role..." 
               class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
        <i class="fas fa-search absolute left-3 top-4 text-gray-400"></i>
    </div>
</div>

<!-- Users Grid/Table -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <?php if (count($data['users']) > 0): ?>
    <!-- Desktop Table -->
    <div class="hidden md:block overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">User</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Username</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Role</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Campus</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">School</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200" id="usersTableBody">
                <?php foreach ($data['users'] as $user): ?>
                <tr class="hover:bg-gray-50 transition user-row" 
                    data-search="<?php echo strtolower($user->full_name . ' ' . $user->username . ' ' . $user->role); ?>">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold shadow-md">
                                <?php echo strtoupper(substr($user->full_name, 0, 1)); ?>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-semibold text-gray-900"><?php echo $user->full_name; ?></div>
                                <div class="text-xs text-gray-500">
                                    <i class="fas fa-envelope mr-1 text-gray-400"></i><?php echo $user->email ?? 'No email'; ?>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-xs font-mono text-gray-600 bg-gray-100 px-2 py-1 rounded">
                            @<?php echo $user->username; ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <?php
                        $roleConfig = [
                            'administrator' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'border' => 'border-red-200', 'icon' => 'fa-user-shield'],
                            'procurement officer' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'border' => 'border-blue-200', 'icon' => 'fa-shopping-cart'],
                            'store keeper' => ['bg' => 'bg-indigo-100', 'text' => 'text-indigo-800', 'border' => 'border-indigo-200', 'icon' => 'fa-warehouse'],
                            'teacher' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'border' => 'border-green-200', 'icon' => 'fa-graduation-cap'],
                            'accountant' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'border' => 'border-yellow-200', 'icon' => 'fa-calculator']
                        ];
                        $config = $roleConfig[$user->role] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'border' => 'border-gray-200', 'icon' => 'fa-user'];
                        ?>
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full <?php echo $config['bg'] . ' ' . $config['text']; ?> border <?php echo $config['border']; ?>">
                            <i class="fas <?php echo $config['icon']; ?> mr-1"></i>
                            <?php echo ucfirst(str_replace('_', ' ', $user->role)); ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <?php if (isset($user->is_active) && $user->is_active == 1): ?>
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-green-100 text-green-800 border border-green-200">
                            <i class="fas fa-check-circle mr-1"></i>Active
                        </span>
                        <?php else: ?>
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-yellow-100 text-yellow-800 border border-yellow-200">
                            <i class="fas fa-clock mr-1"></i>Pending
                        </span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                        <?php echo $user->campus_name ?: '<span class="text-gray-400 italic">Not Assigned</span>'; ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                        <?php echo $user->school_name ?: '<span class="text-gray-400">N/A</span>'; ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                        <?php if ($user->id != Session::get('user_id')): ?>
                        <div class="flex items-center justify-center space-x-2">
                           <?php if (isset($user->is_active) && $user->is_active == 1): ?>
                                <a href="<?php echo URL_ROOT; ?>/users/edit/<?php echo $user->id; ?>" class="text-indigo-600 hover:bg-indigo-50 p-2 rounded-lg transition" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="<?php echo URL_ROOT; ?>/users/resetPassword/<?php echo $user->id; ?>" class="inline" onsubmit="return confirm('Reset password?');">
                                    <button type="submit" class="text-blue-600 hover:bg-blue-50 p-2 rounded-lg transition" title="Reset Password">
                                        <i class="fas fa-key"></i>
                                    </button>
                                </form>
                                <form method="POST" action="<?php echo URL_ROOT; ?>/users/delete/<?php echo $user->id; ?>" class="inline" onsubmit="return confirm('Deactivate?');">
                                    <button type="submit" class="text-orange-600 hover:bg-orange-50 p-2 rounded-lg transition" title="Deactivate">
                                        <i class="fas fa-user-slash"></i>
                                    </button>
                                </form>
                            <?php else: ?>
                                <form method="POST" action="<?php echo URL_ROOT; ?>/users/reactivate/<?php echo $user->id; ?>" class="inline" onsubmit="return confirm('Approve?');">
                                    <button type="submit" class="text-green-600 bg-green-50 px-3 py-1 rounded-lg text-xs font-bold border border-green-100">
                                        <i class="fas fa-check mr-1"></i>Approve
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>
                        <?php else: ?>
                        <span class="text-gray-400 italic text-xs">Self</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Mobile Cards -->
    <div class="md:hidden divide-y divide-gray-100" id="usersMobileCards">
        <?php foreach ($data['users'] as $user): ?>
        <div class="p-4 hover:bg-gray-50 transition user-card" data-search="<?php echo strtolower($user->full_name . ' ' . $user->username . ' ' . $user->role); ?>">
            <div class="flex items-start justify-between mb-4">
                <div class="flex items-center">
                    <div class="h-10 w-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold shadow-sm flex-shrink-0">
                        <?php echo strtoupper(substr($user->full_name, 0, 1)); ?>
                    </div>
                    <div class="ml-3">
                        <div class="text-sm font-bold text-gray-900"><?php echo $user->full_name; ?></div>
                        <div class="text-[10px] font-mono text-gray-500">@<?php echo $user->username; ?></div>
                    </div>
                </div>
                <?php
                $config = $roleConfig[$user->role] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'icon' => 'fa-user'];
                ?>
                <span class="px-2 py-1 text-[10px] font-bold rounded-full <?php echo $config['bg'] . ' ' . $config['text']; ?>">
                    <i class="fas <?php echo $config['icon']; ?> mr-1"></i>
                    <?php echo ucfirst(str_replace('_', ' ', $user->role)); ?>
                </span>
            </div>
            
            <div class="space-y-2 mb-4">
                <div class="flex items-center text-xs text-gray-600">
                    <i class="fas fa-envelope w-5 text-gray-400"></i>
                    <span><?php echo $user->email ?? 'No email'; ?></span>
                </div>
                <div class="flex items-center text-xs text-gray-600">
                    <i class="fas fa-school w-5 text-gray-400"></i>
                    <span class="font-medium"><?php echo $user->campus_name ?: 'Not Assigned'; ?></span>
                </div>
                <div class="flex items-center text-xs text-gray-600">
                    <i class="fas fa-circle w-5 text-[8px] <?php echo (isset($user->is_active) && $user->is_active == 1) ? 'text-green-500' : 'text-yellow-500'; ?> mt-0.5"></i>
                    <span class="font-semibold <?php echo (isset($user->is_active) && $user->is_active == 1) ? 'text-green-700' : 'text-yellow-700'; ?>">
                        <?php echo (isset($user->is_active) && $user->is_active == 1) ? 'Active Account' : 'Pending Approval'; ?>
                    </span>
                </div>
            </div>

            <div class="flex items-center justify-end space-x-2 pt-3 border-t border-gray-50">
                <?php if ($user->id != Session::get('user_id')): ?>
                    <?php if (isset($user->is_active) && $user->is_active == 1): ?>
                        <a href="<?php echo URL_ROOT; ?>/users/edit/<?php echo $user->id; ?>" class="bg-indigo-50 text-indigo-600 p-2 rounded-lg">
                            <i class="fas fa-edit text-xs"></i>
                        </a>
                        <form method="POST" action="<?php echo URL_ROOT; ?>/users/resetPassword/<?php echo $user->id; ?>" class="inline">
                            <button type="submit" class="bg-blue-50 text-blue-600 p-2 rounded-lg">
                                <i class="fas fa-key text-xs"></i>
                            </button>
                        </form>
                        <form method="POST" action="<?php echo URL_ROOT; ?>/users/delete/<?php echo $user->id; ?>" class="inline">
                            <button type="submit" class="bg-orange-50 text-orange-600 p-2 rounded-lg">
                                <i class="fas fa-user-slash text-xs"></i>
                            </button>
                        </form>
                    <?php else: ?>
                        <form method="POST" action="<?php echo URL_ROOT; ?>/users/reactivate/<?php echo $user->id; ?>" class="inline">
                            <button type="submit" class="bg-green-600 text-white px-4 py-1.5 rounded-lg text-xs font-bold shadow-sm">
                                Approve Account
                            </button>
                        </form>
                    <?php endif; ?>
                <?php else: ?>
                    <span class="text-[10px] text-gray-400 italic">Current Session</span>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="text-center py-16">
        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-users text-5xl text-gray-400"></i>
        </div>
        <h3 class="text-lg font-semibold text-gray-900 mb-2">No Users Found</h3>
        <p class="text-gray-500 mb-6">Get started by adding your first school user</p>
        <a href="<?php echo URL_ROOT; ?>/users/add" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg hover:from-indigo-700 hover:to-purple-700 transition shadow-lg">
            <i class="fas fa-user-plus mr-2"></i>
            Add First User
        </a>
    </div>
    <?php endif; ?>
</div>

<!-- Search Script -->
<script>
document.getElementById('searchUsers').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    
    // Rows (Desktop)
    document.querySelectorAll('.user-row').forEach(row => {
        const searchData = row.getAttribute('data-search');
        row.style.display = searchData.includes(searchTerm) ? '' : 'none';
    });
    
    // Cards (Mobile)
    document.querySelectorAll('.user-card').forEach(card => {
        const searchData = card.getAttribute('data-search');
        card.style.display = searchData.includes(searchTerm) ? '' : 'none';
    });
});
</script>
