<!-- Header Section -->
<div class="mb-8 print:hidden">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <a href="<?php echo URL_ROOT; ?>/distributions" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h2 class="text-2xl font-bold text-gray-900">Distribution Order #<?php echo $data['order']->order_number; ?></h2>
            </div>
            <p class="text-gray-500">View complete distribution information and allocated materials</p>
        </div>
        <div class="flex gap-3">
            <a href="<?php echo URL_ROOT; ?>/distributions/receipt/<?php echo $data['order']->id; ?>" class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition flex items-center shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                <i class="fas fa-print mr-2"></i>
                Distribution Slip
            </a>
        </div>
    </div>
</div>

<?php $order = $data['order'] ?? null; ?>
<?php if ($order): ?>

<!-- Main Content Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    
    <!-- Left Column: Order & Recipient Info -->
    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center">
            <i class="fas fa-info-circle text-indigo-600 mr-2"></i>
            Order Information
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Recipient Details -->
            <div class="col-span-1 md:col-span-2 pb-4 border-b border-gray-100">
                <h4 class="text-sm font-bold text-gray-500 uppercase tracking-wide mb-4">Recipient Details</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs text-gray-400 uppercase">Recipient Name</label>
                        <div class="flex items-center mt-1">
                            <div class="h-8 w-8 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-xs font-bold mr-2">
                                <?php echo strtoupper(substr($order->recipient_name, 0, 1)); ?>
                            </div>
                            <span class="font-medium text-gray-900"><?php echo $order->recipient_name; ?></span>
                        </div>
                    </div>
                    <?php if (isset($order->recipient_phone) && $order->recipient_phone): ?>
                    <div>
                        <label class="text-xs text-gray-400 uppercase">Phone / Ext</label>
                        <p class="mt-1 font-medium text-gray-900"><?php echo $order->recipient_phone; ?></p>
                    </div>
                    <?php endif; ?>
                    <?php if (isset($order->recipient_email) && $order->recipient_email): ?>
                    <div>
                        <label class="text-xs text-gray-400 uppercase">Email</label>
                        <p class="mt-1 font-medium text-gray-900"><?php echo $order->recipient_email; ?></p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Order Details -->
            <div>
                <label class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Campus</label>
                <p class="mt-1">
                    <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-blue-100 text-blue-800">
                        <i class="fas fa-school mr-2"></i>
                        <?php echo $order->campus_name; ?>
                    </span>
                </p>
            </div>
            
            <div>
                <label class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Allocation Type</label>
                <p class="mt-1 capitalize font-medium text-gray-900">
                    <?php echo isset($order->payment_method) ? str_replace('_', ' ', $order->payment_method) : 'N/A'; ?>
                </p>
            </div>
            
            <div>
                <label class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Issued By</label>
                <p class="mt-1 text-gray-900 font-medium"><?php echo $order->created_by_name ?? 'System User'; ?></p>
            </div>
            
            <div>
                <label class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Date Issued</label>
                <p class="mt-1 text-gray-900 font-medium"><?php echo date('M j, Y g:i A', strtotime($order->created_at)); ?></p>
            </div>

            <?php if (isset($order->notes) && $order->notes): ?>
            <div class="col-span-1 md:col-span-2">
                <label class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Notes</label>
                <div class="mt-1 p-3 bg-gray-50 rounded-lg text-gray-700 text-sm">
                    <?php echo nl2br(htmlspecialchars($order->notes)); ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Right Column: Quick Stats -->
    <div class="space-y-6">
        <!-- Total Value -->
        <div class="bg-gradient-to-br from-indigo-600 to-purple-600 rounded-xl shadow-lg p-6 text-white transform transition hover:-translate-y-1">
            <div class="flex items-center justify-between mb-2">
                <i class="fas fa-chart-line text-3xl opacity-80"></i>
                <span class="text-sm font-semibold uppercase tracking-wide opacity-90">Total Value</span>
            </div>
            <p class="text-4xl font-bold">K <?php echo number_format($order->total_amount, 2); ?></p>
            <p class="text-sm mt-1 opacity-90">Estimated Value</p>
        </div>
        
        <!-- Status Card -->
        <?php
        $statusConfig = [
            'completed' => ['from' => 'from-green-500', 'to' => 'to-emerald-600', 'icon' => 'fa-check-circle', 'text' => 'Issued'],
            'pending' => ['from' => 'from-yellow-400', 'to' => 'to-orange-500', 'icon' => 'fa-clock', 'text' => 'Pending'],
            'cancelled' => ['from' => 'from-red-500', 'to' => 'to-red-600', 'icon' => 'fa-times-circle', 'text' => 'Cancelled']
        ];
        $config = $statusConfig[$order->status] ?? $statusConfig['pending'];
        ?>
        <div class="bg-gradient-to-br <?php echo $config['from']; ?> <?php echo $config['to']; ?> rounded-xl shadow-lg p-6 text-white transform transition hover:-translate-y-1">
            <div class="flex items-center justify-between mb-2">
                <i class="fas <?php echo $config['icon']; ?> text-3xl opacity-80"></i>
                <span class="text-sm font-semibold uppercase tracking-wide opacity-90">Status</span>
            </div>
            <p class="text-4xl font-bold"><?php echo $config['text']; ?></p>
            <p class="text-sm mt-1 opacity-90">Current Distribution Status</p>
        </div>
        
        <!-- Items Count -->
        <div class="bg-gradient-to-br from-blue-500 to-cyan-600 rounded-xl shadow-lg p-6 text-white transform transition hover:-translate-y-1">
            <div class="flex items-center justify-between mb-2">
                <i class="fas fa-truck-loading text-3xl opacity-80"></i>
                <span class="text-sm font-semibold uppercase tracking-wide opacity-90">Materials</span>
            </div>
            <p class="text-4xl font-bold">
                <?php 
                $itemCount = 0;
                foreach ($data['items'] ?? [] as $item) $itemCount += $item->quantity;
                echo $itemCount;
                ?>
            </p>
            <p class="text-sm mt-1 opacity-90">Total Units Allocated</p>
        </div>
    </div>
</div>

<!-- Order Items Table -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
        <h3 class="text-lg font-bold text-gray-900 flex items-center">
            <i class="fas fa-list-alt text-indigo-600 mr-2"></i>
            Distribution Items
        </h3>
    </div>
    
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Material</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Quantity</th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">Unit Value</th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">Total Value</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php foreach ($data['items'] ?? [] as $item): ?>
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10 bg-indigo-100 rounded-lg flex items-center justify-center text-indigo-600">
                                <i class="fas fa-book"></i>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-semibold text-gray-900"><?php echo $item->material_name; ?></div>
                                <div class="text-xs text-gray-500">SKU: <?php echo $item->material_sku ?? 'N/A'; ?></div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <span class="px-3 py-1 inline-flex text-sm font-bold rounded-full bg-gray-100 text-gray-800">
                            <?php echo $item->quantity; ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-600">
                        K <?php echo number_format($item->unit_price, 2); ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right font-bold text-gray-900">
                        K <?php echo number_format($item->subtotal, 2); ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot class="bg-gray-50">
                <tr>
                    <td colspan="3" class="px-6 py-4 text-right text-sm font-bold text-gray-500 uppercase tracking-wider">Total Value</td>
                    <td class="px-6 py-4 text-right text-xl font-bold text-indigo-600">
                        K <?php echo number_format($order->total_amount, 2); ?>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<?php else: ?>
<!-- Not Found State -->
<div class="text-center py-16 bg-white rounded-xl border border-gray-200 shadow-sm">
    <div class="w-24 h-24 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-4">
        <i class="fas fa-search text-5xl text-red-400"></i>
    </div>
    <h3 class="text-xl font-bold text-gray-900 mb-2">Order Not Found</h3>
    <p class="text-gray-500 mb-6">The distribution order you are looking for does not exist or has been removed.</p>
    <a href="<?php echo URL_ROOT; ?>/distributions" class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition shadow-lg">
        <i class="fas fa-arrow-left mr-2"></i>
        Return to Distributions
    </a>
</div>
<?php endif; ?>
