<!-- Header Section -->
<div class="mb-6 md:mb-8">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-xl md:text-2xl font-bold text-gray-900">Purchase Orders</h2>
            <p class="text-sm md:text-base text-gray-500 mt-1">Manage vendor procurements and school stock</p>
        </div>
        <a href="<?php echo URL_ROOT; ?>/procurements/create" class="bg-gradient-to-r from-purple-600 to-pink-600 text-white px-4 md:px-6 py-2 md:py-3 rounded-lg hover:from-purple-700 hover:to-pink-700 transition shadow-lg flex items-center justify-center transform hover:scale-105 text-sm md:text-base">
            <i class="fas fa-plus mr-2"></i>
            New Procurement Order
        </a>
    </div>
</div>

<!-- Stats Cards -->
<?php
$totalOrders = count($orders ?? []);
$pendingOrders = count(array_filter($orders ?? [], function($o) { return $o->status == 'pending'; }));
$receivedOrders = count(array_filter($orders ?? [], function($o) { return $o->status == 'received'; }));
$totalSpent = array_sum(array_map(function($o) { return $o->total_amount; }, $orders ?? []));
?>

<div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-6 mb-6 md:mb-8">
    <!-- Total Orders -->
    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg md:rounded-xl p-4 md:p-6 text-white shadow-lg hover:shadow-xl transition transform hover:-translate-y-1">
        <div class="flex items-center justify-between mb-2 md:mb-4">
            <div class="bg-white bg-opacity-20 rounded-lg p-2 md:p-3">
                <i class="fas fa-truck text-lg md:text-2xl"></i>
            </div>
        </div>
        <h3 class="text-2xl md:text-3xl font-bold mb-1"><?php echo $totalOrders; ?></h3>
        <p class="text-purple-100 text-xs md:text-sm">Total Orders</p>
    </div>

    <!-- Pending Orders -->
    <div class="bg-gradient-to-br from-yellow-500 to-orange-500 rounded-lg md:rounded-xl p-4 md:p-6 text-white shadow-lg hover:shadow-xl transition transform hover:-translate-y-1">
        <div class="flex items-center justify-between mb-2 md:mb-4">
            <div class="bg-white bg-opacity-20 rounded-lg p-2 md:p-3">
                <i class="fas fa-clock text-lg md:text-2xl"></i>
            </div>
        </div>
        <h3 class="text-2xl md:text-3xl font-bold mb-1"><?php echo $pendingOrders; ?></h3>
        <p class="text-yellow-100 text-xs md:text-sm">Pending</p>
    </div>

    <!-- Received Orders -->
    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg md:rounded-xl p-4 md:p-6 text-white shadow-lg hover:shadow-xl transition transform hover:-translate-y-1">
        <div class="flex items-center justify-between mb-2 md:mb-4">
            <div class="bg-white bg-opacity-20 rounded-lg p-2 md:p-3">
                <i class="fas fa-check-circle text-lg md:text-2xl"></i>
            </div>
        </div>
        <h3 class="text-2xl md:text-3xl font-bold mb-1"><?php echo $receivedOrders; ?></h3>
        <p class="text-green-100 text-xs md:text-sm">Received</p>
    </div>

    <!-- Total Value -->
    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg md:rounded-xl p-4 md:p-6 text-white shadow-lg hover:shadow-xl transition transform hover:-translate-y-1">
        <div class="flex items-center justify-between mb-2 md:mb-4">
            <div class="bg-white bg-opacity-20 rounded-lg p-2 md:p-3">
                <i class="fas fa-chart-pie text-lg md:text-2xl"></i>
            </div>
        </div>
        <h3 class="text-xl md:text-3xl font-bold mb-1">K<?php echo number_format($totalSpent, 0); ?></h3>
        <p class="text-blue-100 text-xs md:text-sm">Total Procurement Value</p>
    </div>
</div>

<!-- Filters -->
<div class="bg-white rounded-lg md:rounded-xl shadow-sm border border-gray-200 p-4 md:p-6 mb-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 md:gap-4">
        <div class="flex-1">
            <div class="relative">
                <input type="text" id="searchOrders" placeholder="Search by order number or vendor..." 
                       class="w-full pl-10 pr-4 py-2 md:py-3 text-sm md:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                <i class="fas fa-search absolute left-3 top-3 md:top-4 text-gray-400 text-sm md:text-base"></i>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <select id="statusFilter" class="px-3 md:px-4 py-2 md:py-3 text-sm md:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                <option value="">All Status</option>
                <option value="pending">Pending</option>
                <option value="received">Received</option>
                <option value="cancelled">Cancelled</option>
            </select>
            <button onclick="window.print()" class="px-3 md:px-4 py-2 md:py-3 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                <i class="fas fa-print text-gray-600"></i>
            </button>
        </div>
    </div>
</div>

<!-- Orders Table - Desktop -->
<div class="hidden md:block bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <?php if(!empty($orders)): ?>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Order #</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Vendor</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Campus</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Total Value</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200" id="ordersTableBody">
                <?php foreach($orders as $order): ?>
                <tr class="hover:bg-gray-50 transition order-row" 
                    data-search="<?php echo strtolower($order->order_number . ' ' . $order->vendor_name); ?>"
                    data-status="<?php echo $order->status; ?>">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-sm font-mono font-bold text-purple-600 bg-purple-50 px-2 py-1 rounded">
                            <?php echo $order->order_number; ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10 bg-gradient-to-br from-purple-500 to-pink-600 rounded-full flex items-center justify-center text-white font-semibold shadow-md">
                                <?php echo strtoupper(substr($order->vendor_name, 0, 1)); ?>
                            </div>
                            <div class="ml-3">
                                <div class="text-sm font-semibold text-gray-900"><?php echo $order->vendor_name; ?></div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                            <?php echo $order->campus_name; ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-sm font-bold text-gray-900">K <?php echo number_format($order->total_amount, 2); ?></span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <?php
                        $statusConfig = [
                            'received' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'border' => 'border-green-200', 'icon' => 'fa-check-circle'],
                            'pending' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'border' => 'border-yellow-200', 'icon' => 'fa-clock'],
                            'cancelled' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'border' => 'border-red-200', 'icon' => 'fa-times-circle']
                        ];
                        $config = $statusConfig[$order->status] ?? $statusConfig['pending'];
                        ?>
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full <?php echo $config['bg'] . ' ' . $config['text']; ?> border <?php echo $config['border']; ?>">
                            <i class="fas <?php echo $config['icon']; ?> mr-1"></i>
                            <?php echo ucfirst($order->status); ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                        <i class="fas fa-calendar mr-1 text-gray-400"></i>
                        <?php echo date('M j, Y', strtotime($order->created_at)); ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <a href="<?php echo URL_ROOT; ?>/procurements/viewOrder/<?php echo $order->id; ?>" 
                           class="text-purple-600 hover:text-purple-900 hover:bg-purple-50 p-2 rounded-lg transition inline-block mr-2" title="View">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="<?php echo URL_ROOT; ?>/procurements/receipt/<?php echo $order->id; ?>" 
                           class="text-blue-600 hover:text-blue-900 hover:bg-blue-50 p-2 rounded-lg transition inline-block" title="Receipt">
                            <i class="fas fa-receipt"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
    <div class="text-center py-16">
        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-truck text-5xl text-gray-400"></i>
        </div>
        <h3 class="text-lg font-semibold text-gray-900 mb-2">No Procurement Orders Found</h3>
        <p class="text-gray-500 mb-6">Create your first procurement order to get started</p>
        <a href="<?php echo URL_ROOT; ?>/procurements/create" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-lg hover:from-purple-700 hover:to-pink-700 transition shadow-lg">
            <i class="fas fa-plus mr-2"></i>
            Create First Order
        </a>
    </div>
    <?php endif; ?>
</div>

<!-- Orders Cards - Mobile -->
<div class="md:hidden space-y-4">
    <?php if(!empty($orders)): ?>
        <?php foreach($orders as $order): ?>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 order-card" 
             data-search="<?php echo strtolower($order->order_number . ' ' . $order->vendor_name); ?>"
             data-status="<?php echo $order->status; ?>">
            <div class="flex items-start justify-between mb-3">
                <div class="flex items-center">
                    <div class="h-10 w-10 bg-gradient-to-br from-purple-500 to-pink-600 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                        <?php echo strtoupper(substr($order->vendor_name, 0, 1)); ?>
                    </div>
                    <div class="ml-3">
                        <div class="text-sm font-semibold text-gray-900"><?php echo $order->vendor_name; ?></div>
                        <div class="text-xs font-mono text-purple-600"><?php echo $order->order_number; ?></div>
                    </div>
                </div>
                <?php
                $statusConfig = [
                    'received' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'icon' => 'fa-check-circle'],
                    'pending' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'icon' => 'fa-clock'],
                    'cancelled' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'icon' => 'fa-times-circle']
                ];
                $config = $statusConfig[$order->status] ?? $statusConfig['pending'];
                ?>
                <span class="px-2 py-1 inline-flex text-xs font-bold rounded-full <?php echo $config['bg'] . ' ' . $config['text']; ?>">
                    <i class="fas <?php echo $config['icon']; ?> mr-1"></i>
                    <?php echo ucfirst($order->status); ?>
                </span>
            </div>
            
            <div class="grid grid-cols-2 gap-3 mb-3">
                <div>
                    <div class="text-xs text-gray-500">Total Value</div>
                    <div class="text-sm font-bold text-gray-900">K <?php echo number_format($order->total_amount, 2); ?></div>
                </div>
                <div>
                    <div class="text-xs text-gray-500">Campus</div>
                    <div class="text-sm font-semibold text-gray-900"><?php echo $order->campus_name; ?></div>
                </div>
            </div>
            
            <div class="flex items-center justify-between pt-3 border-t border-gray-200">
                <div class="text-xs text-gray-500">
                    <i class="fas fa-calendar mr-1"></i>
                    <?php echo date('M j, Y', strtotime($order->created_at)); ?>
                </div>
                <div>
                    <a href="<?php echo URL_ROOT; ?>/procurements/viewOrder/<?php echo $order->id; ?>" 
                       class="text-purple-600 hover:bg-purple-50 p-2 rounded-lg transition">
                        <i class="fas fa-eye"></i>
                    </a>
                    <a href="<?php echo URL_ROOT; ?>/procurements/receipt/<?php echo $order->id; ?>" 
                       class="text-blue-600 hover:bg-blue-50 p-2 rounded-lg transition">
                        <i class="fas fa-receipt"></i>
                    </a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    <?php else: ?>
    <div class="text-center py-12 bg-white rounded-lg border border-gray-200">
        <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
            <i class="fas fa-truck text-4xl text-gray-400"></i>
        </div>
        <h3 class="text-base font-semibold text-gray-900 mb-2">No Procurement Orders</h3>
        <p class="text-sm text-gray-500 mb-4">Create your first order</p>
        <a href="<?php echo URL_ROOT; ?>/procurements/create" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-lg text-sm">
            <i class="fas fa-plus mr-2"></i>
            Create Order
        </a>
    </div>
    <?php endif; ?>
</div>

<!-- Search & Filter Script -->
<script>
// Search functionality
document.getElementById('searchOrders').addEventListener('input', function(e) {
    filterOrders();
});

// Status filter
document.getElementById('statusFilter').addEventListener('change', function(e) {
    filterOrders();
});

function filterOrders() {
    const searchTerm = document.getElementById('searchOrders').value.toLowerCase();
    const statusFilter = document.getElementById('statusFilter').value;
    
    // Desktop table rows
    const rows = document.querySelectorAll('.order-row');
    rows.forEach(row => {
        const searchData = row.getAttribute('data-search');
        const status = row.getAttribute('data-status');
        const matchesSearch = searchData.includes(searchTerm);
        const matchesStatus = !statusFilter || status === statusFilter;
        
        row.style.display = (matchesSearch && matchesStatus) ? '' : 'none';
    });
    
    // Mobile cards
    const cards = document.querySelectorAll('.order-card');
    cards.forEach(card => {
        const searchData = card.getAttribute('data-search');
        const status = card.getAttribute('data-status');
        const matchesSearch = searchData.includes(searchTerm);
        const matchesStatus = !statusFilter || status === statusFilter;
        
        card.style.display = (matchesSearch && matchesStatus) ? '' : 'none';
    });
}
</script>
