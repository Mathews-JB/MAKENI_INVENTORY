<!-- Header Section -->
<div class="mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">System Settings</h2>
            <p class="text-gray-500 mt-1">Configure your application preferences and system behavior</p>
        </div>
        <div class="flex items-center space-x-2 text-sm text-gray-500">
            <i class="fas fa-info-circle"></i>
            <span>Last updated: <?php echo date('M j, Y'); ?></span>
        </div>
    </div>
</div>

<!-- Settings Form -->
<form action="<?php echo URL_ROOT; ?>/settings/update" method="post">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column - Main Settings -->
        <div class="lg:col-span-2 space-y-6">
            <!-- General Settings Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-indigo-50 to-purple-50 border-b border-gray-200">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-indigo-600 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-cog text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">General Settings</h3>
                            <p class="text-sm text-gray-600">Basic system configuration</p>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                             <label class="block text-sm font-semibold text-gray-700 mb-2">
                                 <i class="fas fa-university mr-2 text-indigo-500"></i>School System Name
                             </label>
                             <input type="text" name="settings[system_name]" 
                                    value="<?php echo $settings['system_name'] ?? 'School IVM System'; ?>" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                             <p class="mt-1 text-xs text-gray-500">Official name for your school inventory system</p>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-dollar-sign mr-2 text-green-500"></i>Currency Symbol
                            </label>
                            <input type="text" name="settings[currency_symbol]" 
                                   value="<?php echo $settings['currency_symbol'] ?? '$'; ?>" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            <p class="mt-1 text-xs text-gray-500">Default currency symbol</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- User Registration Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-blue-50 to-cyan-50 border-b border-gray-200">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-user-plus text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">User Registration</h3>
                            <p class="text-sm text-gray-600">Control user access and registration</p>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="flex items-start p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <input type="hidden" name="settings[allow_registration]" value="0">
                        <input type="checkbox" name="settings[allow_registration]" value="1" id="allow_registration" 
                               <?php echo ($settings['allow_registration'] ?? '0') == '1' ? 'checked' : ''; ?> 
                               class="mt-1 h-5 w-5 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                        <div class="ml-3 flex-1">
                            <label for="allow_registration" class="font-semibold text-gray-900 cursor-pointer">
                                Allow new user registration
                            </label>
                            <p class="mt-1 text-sm text-gray-600">
                                <i class="fas fa-info-circle mr-1 text-blue-500"></i>
                                If disabled, only administrators can create new user accounts
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notification Settings Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-yellow-50 to-orange-50 border-b border-gray-200">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-yellow-600 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-bell text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">Notifications</h3>
                            <p class="text-sm text-gray-600">Manage alert and notification preferences</p>
                        </div>
                    </div>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-start p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <input type="hidden" name="settings[email_notifications]" value="0">
                        <input type="checkbox" name="settings[email_notifications]" value="1" id="email_notifications" 
                               <?php echo ($settings['email_notifications'] ?? '0') == '1' ? 'checked' : ''; ?> 
                               class="mt-1 h-5 w-5 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                        <div class="ml-3 flex-1">
                            <label for="email_notifications" class="font-semibold text-gray-900 cursor-pointer">
                                <i class="fas fa-envelope mr-2 text-blue-500"></i>Enable email notifications
                            </label>
                            <p class="mt-1 text-sm text-gray-600">Send email alerts for important events</p>
                        </div>
                    </div>

                    <div class="flex items-start p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <input type="hidden" name="settings[low_stock_alerts]" value="0">
                        <input type="checkbox" name="settings[low_stock_alerts]" value="1" id="low_stock_alerts" 
                               <?php echo ($settings['low_stock_alerts'] ?? '1') == '1' ? 'checked' : ''; ?> 
                               class="mt-1 h-5 w-5 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                        <div class="ml-3 flex-1">
                            <label for="low_stock_alerts" class="font-semibold text-gray-900 cursor-pointer">
                                <i class="fas fa-exclamation-triangle mr-2 text-orange-500"></i>Enable low stock alerts
                            </label>
                            <p class="mt-1 text-sm text-gray-600">Get notified when inventory levels are low</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Info & Actions -->
        <div class="space-y-6">
            <!-- Quick Info Card -->
            <div class="bg-gradient-to-br from-indigo-600 to-purple-700 rounded-xl p-6 text-white shadow-lg">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-school text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-bold">School IVM Environment</h3>
                </div>
                <div class="space-y-3">
                    <div class="flex justify-between items-center pb-3 border-b border-white border-opacity-20">
                        <span class="text-indigo-100">Version</span>
                        <span class="font-semibold">1.0.0</span>
                    </div>
                    <div class="flex justify-between items-center pb-3 border-b border-white border-opacity-20">
                        <span class="text-indigo-100">Environment</span>
                        <span class="font-semibold">Production</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-indigo-100">Status</span>
                        <span class="px-2 py-1 bg-green-500 text-white text-xs font-bold rounded-full">Active</span>
                    </div>
                </div>
            </div>

            <!-- Actions Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Quick Actions</h3>
                <div class="space-y-3">
                    <button type="button" class="w-full px-4 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition flex items-center justify-between">
                        <span class="flex items-center">
                            <i class="fas fa-database mr-3 text-blue-600"></i>
                            Backup Database
                        </span>
                        <i class="fas fa-chevron-right text-gray-400"></i>
                    </button>
                    <button type="button" class="w-full px-4 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition flex items-center justify-between">
                        <span class="flex items-center">
                            <i class="fas fa-broom mr-3 text-orange-600"></i>
                            Clear Cache
                        </span>
                        <i class="fas fa-chevron-right text-gray-400"></i>
                    </button>
                    <button type="button" class="w-full px-4 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition flex items-center justify-between">
                        <span class="flex items-center">
                            <i class="fas fa-file-alt mr-3 text-green-600"></i>
                            View Logs
                        </span>
                        <i class="fas fa-chevron-right text-gray-400"></i>
                    </button>
                </div>
            </div>

            <!-- Save Button -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <button type="submit" class="w-full px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg hover:from-indigo-700 hover:to-purple-700 transition shadow-lg flex items-center justify-center font-semibold">
                    <i class="fas fa-save mr-2"></i>
                    Save All Settings
                </button>
                <p class="mt-3 text-xs text-center text-gray-500">
                    <i class="fas fa-shield-alt mr-1"></i>
                    Changes will take effect immediately
                </p>
            </div>
        </div>
    </div>
</form>
