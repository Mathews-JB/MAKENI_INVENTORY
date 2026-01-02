<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-xl shadow-md p-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Edit Profile</h2>
        
        <form action="<?php echo URL_ROOT; ?>/profile/edit" method="post">
            <div class="space-y-6">
                <!-- Full Name -->
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">
                        Full Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="full_name" value="<?php echo $data['full_name']; ?>" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600 <?php echo (!empty($data['full_name_err'])) ? 'border-red-500' : ''; ?>">
                    <span class="text-red-500 text-sm"><?php echo $data['full_name_err']; ?></span>
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Email</label>
                    <input type="email" name="email" value="<?php echo $data['email']; ?>" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600" placeholder="your@email.com">
                </div>

                <!-- Phone -->
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Phone</label>
                    <input type="text" name="phone" value="<?php echo $data['phone']; ?>" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600" placeholder="+260 XXX XXX XXX">
                </div>

                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Change Password (Optional)</h3>
                    
                    <!-- Current Password -->
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-semibold mb-2">Current Password</label>
                        <input type="password" name="current_password" value="<?php echo $data['current_password']; ?>" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600 <?php echo (!empty($data['password_err'])) ? 'border-red-500' : ''; ?>">
                    </div>

                    <!-- New Password -->
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-semibold mb-2">New Password</label>
                        <input type="password" name="new_password" value="<?php echo $data['new_password']; ?>" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600">
                        <p class="text-sm text-gray-500 mt-1">Leave blank to keep current password</p>
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-semibold mb-2">Confirm New Password</label>
                        <input type="password" name="confirm_password" value="<?php echo $data['confirm_password']; ?>" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600">
                    </div>

                    <span class="text-red-500 text-sm"><?php echo $data['password_err']; ?></span>
                </div>
            </div>

            <div class="flex items-center justify-end space-x-4 mt-8">
                <a href="<?php echo URL_ROOT; ?>/profile" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                    Cancel
                </a>
                <button type="submit" class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-6 py-3 rounded-lg hover:from-indigo-700 hover:to-purple-700 transition transform hover:scale-105">
                    <i class="fas fa-save mr-2"></i>Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
