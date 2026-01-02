<!-- Header Section -->
<div class="mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Edit User</h2>
            <p class="text-gray-500 mt-1">Update school staff account details and permissions</p>
        </div>
        <a href="<?php echo URL_ROOT; ?>/users" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition font-medium flex items-center">
            <i class="fas fa-arrow-left mr-2"></i>
            Back to Users
        </a>
    </div>
</div>

<!-- Edit Form -->
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-200">
            <h3 class="text-lg font-bold text-gray-900 flex items-center">
                <i class="fas fa-user-edit mr-2 text-indigo-600"></i>
                Account Details
            </h3>
        </div>
        
        <form action="<?php echo URL_ROOT; ?>/users/edit/<?php echo $data['user']->id; ?>" method="post" class="p-6 space-y-6">
            <!-- Full Name -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Full Name</label>
                <input type="text" name="full_name" 
                       value="<?php echo $data['full_name']; ?>" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 <?php echo (!empty($data['full_name_err'])) ? 'border-red-500' : ''; ?>">
                <span class="text-xs text-red-500 mt-1"><?php echo $data['full_name_err']; ?></span>
            </div>

            <!-- Username -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Username</label>
                <input type="text" name="username" 
                       value="<?php echo $data['username']; ?>" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 <?php echo (!empty($data['username_err'])) ? 'border-red-500' : ''; ?>">
                <span class="text-xs text-red-500 mt-1"><?php echo $data['username_err']; ?></span>
            </div>

            <!-- Password (Optional) -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Password <span class="text-gray-400 font-normal">(Leave blank to keep current)</span></label>
                <input type="password" name="password" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 <?php echo (!empty($data['password_err'])) ? 'border-red-500' : ''; ?>">
                <span class="text-xs text-red-500 mt-1"><?php echo $data['password_err']; ?></span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Role -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Role</label>
                    <select name="role" id="roleSelect" onchange="toggleCampusSelect()" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="teacher" <?php echo $data['role'] == 'teacher' ? 'selected' : ''; ?>>Teacher / Faculty</option>
                        <option value="procurement officer" <?php echo $data['role'] == 'procurement officer' ? 'selected' : ''; ?>>Procurement Officer</option>
                        <option value="store keeper" <?php echo $data['role'] == 'store keeper' ? 'selected' : ''; ?>>Storekeeper</option>
                        <option value="accountant" <?php echo $data['role'] == 'accountant' ? 'selected' : ''; ?>>Accountant</option>
                        
                        <?php if (Session::get('role') == 'administrator'): ?>
                            <option value="administrator" <?php echo $data['role'] == 'administrator' ? 'selected' : ''; ?>>Administrator</option>
                        <?php endif; ?>
                    </select>
                </div>

                <!-- Campus -->
                <div id="campusContainer">
                    <?php if (Session::get('role') == 'administrator'): ?>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Assigned Campus</label>
                        <select name="campus_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="">Select Campus</option>
                            <?php foreach($data['all_campuses'] as $campus): ?>
                                <option value="<?php echo $campus->id; ?>" <?php echo $data['campus_id'] == $campus->id ? 'selected' : ''; ?>>
                                    <?php echo $campus->name; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if(!empty($data['campus_err'])): ?>
                            <span class="text-xs text-red-500 mt-1"><?php echo $data['campus_err']; ?></span>
                        <?php endif; ?>
                    <?php else: ?>
                        <!-- Hidden Campus for Admin/Staff -->
                        <input type="hidden" name="campus_id" value="<?php echo Session::get('campus_id'); ?>">
                        <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <p class="text-sm text-gray-600">
                                <i class="fas fa-school mr-2 text-indigo-500"></i>
                                User belongs to your campus.
                            </p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex items-center justify-end pt-4">
                <button type="submit" class="px-6 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg hover:from-blue-700 hover:to-indigo-700 transition shadow-lg font-semibold">
                    <i class="fas fa-save mr-2"></i>
                    Update User
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function toggleCampusSelect() {
    const role = document.getElementById('roleSelect').value;
    const campusContainer = document.getElementById('campusContainer');
    
    if (role === 'administrator') {
        // Administrators are school-wide, but might still be assigned to a campus for data isolation
        // However, in our system, super_admin was global.
        // If role is administrator, they might not need a campus? 
        // Actually, let's keep it consistent with original logic.
    }
}

// Run on load
document.addEventListener('DOMContentLoaded', toggleCampusSelect);
</script>
