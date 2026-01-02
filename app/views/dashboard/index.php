<!-- Stats Cards Row -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Materials Card -->
    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-6 text-white shadow-lg hover:shadow-xl transition transform hover:-translate-y-1">
        <div class="flex items-center justify-between mb-4">
            <div class="bg-white bg-opacity-20 rounded-lg p-3">
                <i class="fas fa-book text-2xl"></i>
            </div>
            <span class="text-xs font-semibold bg-white bg-opacity-20 px-3 py-1 rounded-full">Total</span>
        </div>
        <h3 class="text-3xl font-bold mb-1"><?php echo $data['total_materials'] ?? 0; ?></h3>
        <p class="text-blue-100 text-sm">Total Materials</p>
        <div class="mt-4 flex items-center text-xs">
            <i class="fas fa-arrow-up mr-1"></i>
            <span>12% from last month</span>
        </div>
    </div>

    <!-- Total Schools Card -->
    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-6 text-white shadow-lg hover:shadow-xl transition transform hover:-translate-y-1">
        <div class="flex items-center justify-between mb-4">
            <div class="bg-white bg-opacity-20 rounded-lg p-3">
                <i class="fas fa-school text-2xl"></i>
            </div>
            <span class="text-xs font-semibold bg-white bg-opacity-20 px-3 py-1 rounded-full">Active</span>
        </div>
        <h3 class="text-3xl font-bold mb-1"><?php echo $data['total_schools']; ?></h3>
        <p class="text-purple-100 text-sm">Schools</p>
        <div class="mt-4 flex items-center text-xs">
            <i class="fas fa-check-circle mr-1"></i>
            <span>All systems operational</span>
        </div>
    </div>

    <!-- Low Stock Alert Card -->
    <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-xl p-6 text-white shadow-lg hover:shadow-xl transition transform hover:-translate-y-1">
        <div class="flex items-center justify-between mb-4">
            <div class="bg-white bg-opacity-20 rounded-lg p-3">
                <i class="fas fa-exclamation-triangle text-2xl"></i>
            </div>
            <span class="text-xs font-semibold bg-white bg-opacity-20 px-3 py-1 rounded-full">Alert</span>
        </div>
        <h3 class="text-3xl font-bold mb-1"><?php echo $data['low_stock_count']; ?></h3>
        <p class="text-red-100 text-sm">Low Stock Items</p>
        <div class="mt-4">
            <a href="<?php echo URL_ROOT; ?>/materials/stock" class="text-xs hover:underline flex items-center">
                View Details <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
    </div>

    <!-- Total Value Card -->
    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-6 text-white shadow-lg hover:shadow-xl transition transform hover:-translate-y-1">
        <div class="flex items-center justify-between mb-4">
            <div class="bg-white bg-opacity-20 rounded-lg p-3">
                <i class="fas fa-dollar-sign text-2xl"></i>
            </div>
            <span class="text-xs font-semibold bg-white bg-opacity-20 px-3 py-1 rounded-full">Value</span>
        </div>
        <h3 class="text-3xl font-bold mb-1">K<?php echo number_format($data['inventory_value'] ?? 0, 2); ?></h3>
        <p class="text-green-100 text-sm">Inventory Value</p>
        <div class="mt-4 flex items-center text-xs">
            <i class="fas fa-chart-line mr-1"></i>
            <span>8% increase</span>
        </div>
    </div>
</div>

<!-- Main Content Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Left Column - 2/3 width -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Schools Overview -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 flex items-center">
                            <i class="fas fa-school text-blue-600 mr-2"></i>
                            Schools Overview
                        </h3>
                        <p class="text-sm text-gray-500 mt-0.5">Registered educational institutions</p>
                    </div>
                    <?php if (Session::get('role') == 'administrator'): ?>
                    <a href="<?php echo URL_ROOT; ?>/schools" class="text-sm text-blue-600 hover:text-blue-700 font-medium flex items-center">
                        View All <i class="fas fa-arrow-right ml-2 text-xs"></i>
                    </a>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="p-6">
                <div class="space-y-4">
                    <?php foreach (array_slice($data['schools'], 0, 5) as $school): ?>
                    <div class="flex items-center justify-between p-4 rounded-lg hover:bg-gray-50 transition group">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold shadow-md">
                                <?php echo strtoupper(substr($school->name, 0, 1)); ?>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900 group-hover:text-indigo-600 transition"><?php echo $school->name; ?></h4>
                                <p class="text-xs text-gray-500"><?php echo $school->email ?? 'No email'; ?></p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full">Active</span>
                            <i class="fas fa-chevron-right text-gray-400 group-hover:text-indigo-600 transition"></i>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Low Stock Alerts -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-red-50 to-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 flex items-center">
                            <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>
                            Low Stock Alerts
                        </h3>
                        <p class="text-sm text-gray-500 mt-0.5">Items requiring attention</p>
                    </div>
                    <a href="<?php echo URL_ROOT; ?>/materials/stock" class="text-sm text-red-600 hover:text-red-700 font-medium flex items-center">
                        View All <i class="fas fa-arrow-right ml-2 text-xs"></i>
                    </a>
                </div>
            </div>

            <div class="p-6">
                <?php if (count($data['low_stock_items']) > 0): ?>
                <div class="space-y-4">
                    <?php foreach (array_slice($data['low_stock_items'], 0, 5) as $item): ?>
                    <div class="flex items-center justify-between p-4 rounded-lg border border-red-100 bg-red-50 hover:bg-red-100 transition">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 rounded-full bg-red-500 flex items-center justify-center text-white">
                                <i class="fas fa-book-open text-lg"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900"><?php echo $item->name; ?></h4>
                                <p class="text-xs text-gray-600">SKU: <?php echo $item->sku ?? 'N/A'; ?></p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="px-3 py-1 bg-red-600 text-white text-xs font-bold rounded-full"><?php echo $item->total_stock; ?> left</span>
                            <p class="text-xs text-red-600 mt-1 font-medium">Reorder Now</p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php else: ?>
                <div class="text-center py-12">
                    <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-check-circle text-4xl text-green-500"></i>
                    </div>
                    <h4 class="text-lg font-semibold text-gray-900 mb-2">All Stock Levels Healthy!</h4>
                    <p class="text-sm text-gray-500">No items require immediate attention</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Right Column - 1/3 width -->
    <div class="space-y-6">
        <!-- Quick Actions Card -->
        <div class="bg-gradient-to-br from-indigo-600 to-purple-700 rounded-xl p-6 text-white shadow-lg">
            <style>
                .btn-quick-action {
                    background-color: #f59e0b !important; /* Amber 500 */
                    color: white !important;
                    display: block;
                    width: 100%;
                    height: 100%;
                    transition: all 0.3s ease;
                }
                .btn-quick-action:hover {
                    background-color: #d97706 !important; /* Amber 600 */
                    transform: translateY(-2px);
                    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
                }
                .btn-quick-action * {
                    color: white !important;
                }
            </style>
            <h3 class="text-lg font-bold mb-4 flex items-center">
                <i class="fas fa-bolt mr-2"></i>
                Quick Actions
            </h3>
            <div class="grid grid-cols-2 gap-3">
                <a href="<?php echo URL_ROOT; ?>/materials/add" class="btn-quick-action rounded-lg p-4 text-center transition group">
                    <i class="fas fa-plus text-2xl mb-2 group-hover:scale-110 transition transform"></i>
                    <p class="text-xs font-medium">Add Material</p>
                </a>
                <a href="<?php echo URL_ROOT; ?>/distributions/create" class="btn-quick-action rounded-lg p-4 text-center transition group">
                    <i class="fas fa-shipping-fast text-2xl mb-2 group-hover:scale-110 transition transform"></i>
                    <p class="text-xs font-medium">Distribute</p>
                </a>
                <a href="<?php echo URL_ROOT; ?>/procurements/create" class="btn-quick-action rounded-lg p-4 text-center transition group">
                    <i class="fas fa-shopping-bag text-2xl mb-2 group-hover:scale-110 transition transform"></i>
                    <p class="text-xs font-medium">Procurement</p>
                </a>
                <?php if (Session::get('role') != 'teacher'): ?>
                <a href="<?php echo URL_ROOT; ?>/reports" class="btn-quick-action rounded-lg p-4 text-center transition group">
                    <i class="fas fa-chart-bar text-2xl mb-2 group-hover:scale-110 transition transform"></i>
                    <p class="text-xs font-medium">Reports</p>
                </a>
                <?php endif; ?>
            </div>
        </div>

        <!-- System Status Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">System Status</h3>
            <div class="space-y-4">
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm text-gray-600">Inventory Health</span>
                        <span class="text-sm font-semibold text-green-600">Excellent</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-gradient-to-r from-green-400 to-green-600 h-2 rounded-full" style="width: 92%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm text-gray-600">Stock Coverage</span>
                        <span class="text-sm font-semibold text-blue-600">Good</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-gradient-to-r from-blue-400 to-blue-600 h-2 rounded-full" style="width: 78%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm text-gray-600">Order Fulfillment</span>
                        <span class="text-sm font-semibold text-purple-600">Very Good</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-gradient-to-r from-purple-400 to-purple-600 h-2 rounded-full" style="width: 85%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Info Card -->
        <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-xl p-6 text-white shadow-lg">
            <div class="flex items-center space-x-4 mb-4">
                <?php 
                $userModel = new User();
                $currentUser = $userModel->getUserById(Session::get('user_id'));
                ?>
                <?php if ($currentUser->profile_picture): ?>
                    <img src="<?php echo URL_ROOT; ?>/<?php echo $currentUser->profile_picture; ?>" 
                         alt="Profile" class="w-16 h-16 rounded-full border-4 border-white shadow-lg">
                <?php else: ?>
                    <div class="w-16 h-16 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-2xl font-bold border-4 border-white shadow-lg">
                        <?php echo strtoupper(substr(Session::get('full_name'), 0, 1)); ?>
                    </div>
                <?php endif; ?>
                <div>
                    <h4 class="font-bold text-lg"><?php echo Session::get('full_name'); ?></h4>
                    <p class="text-gray-300 text-sm"><?php echo ucfirst(Session::get('role')); ?></p>
                </div>
            </div>
            <div class="pt-4 border-t border-gray-700">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm text-gray-400">Campus</span>
                    <span class="text-sm font-semibold"><?php echo Session::get('campus_name') ?? 'Main'; ?></span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-400">Last Login</span>
                    <span class="text-sm font-semibold"><?php echo date('M j, Y'); ?></span>
                </div>
            </div>
        </div>
    </div>
</div>
