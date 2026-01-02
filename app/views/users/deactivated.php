<?php
// Reuse main list layout but with specific modifications
?>
<!-- Header Section -->
<div class="mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Deactivated Staff Accounts</h2>
            <p class="text-gray-500 mt-1">Repository of inactive school staff accounts with history</p>
        </div>
        <div class="flex space-x-3">
            <a href="<?php echo URL_ROOT; ?>/users" class="bg-gray-100 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-200 transition flex items-center">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Users
            </a>
        </div>
    </div>
</div>

<!-- Users Table -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <?php if (count($data['users']) > 0): ?>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Staff Member</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Role</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Last Login</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php foreach ($data['users'] as $user): ?>
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10 bg-gray-200 rounded-full flex items-center justify-center text-gray-500 font-bold shadow-sm">
                                <?php echo strtoupper(substr($user->full_name, 0, 1)); ?>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-semibold text-gray-900"><?php echo $user->full_name; ?></div>
                                <div class="text-xs text-gray-500">@<?php echo $user->username; ?></div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-600">
                            <?php echo ucfirst(str_replace('_', ' ', $user->role)); ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <?php echo $user->last_login; ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <form method="POST" action="<?php echo URL_ROOT; ?>/users/reactivate/<?php echo $user->id; ?>" 
                                class="inline" onsubmit="return confirm('Restore this staff account?');">
                            <button type="submit" class="text-green-600 hover:text-green-900 hover:bg-green-50 px-3 py-1 rounded-md transition font-medium">
                                <i class="fas fa-trash-restore mr-1"></i> Restore
                            </button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
    <div class="text-center py-16">
        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-user-slash text-3xl text-gray-400"></i>
        </div>
        <h3 class="text-lg font-semibold text-gray-900 mb-2">No Deactivated Accounts</h3>
        <p class="text-gray-500">There are no inactive staff accounts with history.</p>
    </div>
    <?php endif; ?>
</div>
