<?php 
$order = $data['order'] ?? null; 
$items = $data['items'] ?? [];
?>

<!-- Header Section -->
<div class="mb-6 md:mb-8 print:hidden">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-xl md:text-2xl font-bold text-gray-900">Procurement Order Details</h2>
            <p class="text-sm md:text-base text-gray-500 mt-1">View complete procurement information</p>
        </div>
        <div class="flex gap-3">
            <?php if ($order && $order->status == 'pending'): ?>
            <form method="POST" action="<?php echo URL_ROOT; ?>/procurements/receive/<?php echo $order->id; ?>" onsubmit="return confirm('Are you sure you want to receive this order? This will update your school inventory.');">
                <button type="submit" class="px-4 md:px-6 py-2 md:py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-lg hover:from-green-700 hover:to-emerald-700 transition flex items-center text-sm md:text-base shadow-lg">
                    <i class="fas fa-check-circle mr-2"></i>
                    Receive Items
                </button>
            </form>
            <?php endif; ?>
            <a href="<?php echo URL_ROOT; ?>/procurements/receipt/<?php echo $order->id; ?>" class="px-4 md:px-6 py-2 md:py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center text-sm md:text-base">
                <i class="fas fa-file-invoice mr-2"></i>
                Procurement Slip
            </a>
            <a href="<?php echo URL_ROOT; ?>/procurements" class="px-4 md:px-6 py-2 md:py-3 border border-gray-300 rounded-lg hover:bg-gray-50 transition flex items-center text-sm md:text-base">
                <i class="fas fa-arrow-left mr-2"></i>
                Back
            </a>
        </div>
    </div>
</div>

<?php if ($order): ?>

<!-- Order Info -->
<div class="bg-white rounded-lg md:rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
    <div class="px-4 md:px-6 py-3 md:py-4 bg-gradient-to-r from-purple-50 to-pink-50 border-b border-gray-200">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
            <h3 class="text-base md:text-lg font-bold text-gray-900">Order #<?php echo $order->order_number; ?></h3>
            <?php
            $statusConfig = [
                'received' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'icon' => 'fa-check-circle'],
                'pending' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'icon' => 'fa-clock'],
                'cancelled' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'icon' => 'fa-times-circle']
            ];
            $config = $statusConfig[$order->status] ?? $statusConfig['pending'];
            ?>
            <span class="px-3 py-1 inline-flex text-xs md:text-sm font-bold rounded-full <?php echo $config['bg'] . ' ' . $config['text']; ?>">
                <i class="fas <?php echo $config['icon']; ?> mr-1"></i>
                <?php echo ucfirst($order->status); ?>
            </span>
        </div>
    </div>
    <div class="p-4 md:p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
            <div>
                <h4 class="text-sm font-semibold text-gray-700 mb-3">Vendor Information</h4>
                <div class="space-y-2">
                    <div class="flex items-center text-sm">
                        <i class="fas fa-truck w-5 text-gray-400"></i>
                        <span class="ml-2 text-gray-900"><?php echo $order->vendor_name ?? $order->supplier_name; ?></span>
                    </div>
                    <?php if ((isset($order->vendor_phone) && $order->vendor_phone) || (isset($order->supplier_phone) && $order->supplier_phone)): ?>
                    <div class="flex items-center text-sm">
                        <i class="fas fa-phone w-5 text-gray-400"></i>
                        <span class="ml-2 text-gray-900"><?php echo $order->vendor_phone ?? $order->supplier_phone; ?></span>
                    </div>
                    <?php endif; ?>
                    <?php if ((isset($order->vendor_email) && $order->vendor_email) || (isset($order->supplier_email) && $order->supplier_email)): ?>
                    <div class="flex items-center text-sm">
                        <i class="fas fa-envelope w-5 text-gray-400"></i>
                        <span class="ml-2 text-gray-900"><?php echo $order->vendor_email ?? $order->supplier_email; ?></span>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <div>
                <h4 class="text-sm font-semibold text-gray-700 mb-3">Order Information</h4>
                <div class="space-y-2">
                    <div class="flex items-center text-sm">
                        <i class="fas fa-school w-5 text-gray-400"></i>
                        <span class="ml-2 text-gray-900"><?php echo $order->campus_name; ?></span>
                    </div>
                    <div class="flex items-center text-sm">
                        <i class="fas fa-calendar w-5 text-gray-400"></i>
                        <span class="ml-2 text-gray-900"><?php echo date('M j, Y g:i A', strtotime($order->created_at)); ?></span>
                    </div>
                    <?php if (isset($order->expected_delivery_date) && $order->expected_delivery_date): ?>
                    <div class="flex items-center text-sm">
                        <i class="fas fa-truck-loading w-5 text-gray-400"></i>
                        <span class="ml-2 text-gray-900">Expected Reception: <?php echo date('M j, Y', strtotime($order->expected_delivery_date)); ?></span>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Material Items -->
<div class="bg-white rounded-lg md:rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
    <div class="px-4 md:px-6 py-3 md:py-4 bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
        <h3 class="text-base md:text-lg font-bold text-gray-900">Material Items</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 md:px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Material</th>
                    <th class="px-4 md:px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase">Quantity</th>
                    <th class="px-4 md:px-6 py-3 text-right text-xs font-semibold text-gray-700 uppercase">Unit Value</th>
                    <th class="px-4 md:px-6 py-3 text-right text-xs font-semibold text-gray-700 uppercase">Subtotal Value</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php foreach ($items as $item): ?>
                <tr>
                    <td class="px-4 md:px-6 py-4">
                        <div class="text-sm font-semibold text-gray-900"><?php echo $item->material_name; ?></div>
                        <div class="text-xs text-gray-500">SKU: <?php echo $item->sku ?? $item->material_sku ?? 'N/A'; ?></div>
                    </td>
                    <td class="px-4 md:px-6 py-4 text-center text-sm text-gray-900"><?php echo $item->quantity; ?></td>
                    <td class="px-4 md:px-6 py-4 text-right text-sm text-gray-900">K <?php echo number_format($item->unit_price, 2); ?></td>
                    <td class="px-4 md:px-6 py-4 text-right text-sm font-bold text-gray-900">K <?php echo number_format($item->subtotal, 2); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot class="bg-gray-50 border-t-2 border-gray-300">
                <tr>
                    <td colspan="3" class="px-4 md:px-6 py-4 text-right text-base font-bold text-gray-900">Total:</td>
                    <td class="px-4 md:px-6 py-4 text-right text-lg md:text-xl font-bold text-purple-600">K <?php echo number_format($order->total_amount, 2); ?></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<?php if (isset($order->notes) && $order->notes): ?>
<!-- Notes -->
<div class="bg-white rounded-lg md:rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="px-4 md:px-6 py-3 md:py-4 bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
        <h3 class="text-base md:text-lg font-bold text-gray-900">Notes</h3>
    </div>
    <div class="p-4 md:p-6">
        <p class="text-sm text-gray-700"><?php echo nl2br(htmlspecialchars($order->notes)); ?></p>
    </div>
</div>
<?php endif; ?>

<?php else: ?>
<div class="text-center py-12 bg-white rounded-xl border border-gray-200">
    <i class="fas fa-exclamation-triangle text-5xl text-gray-400 mb-4"></i>
    <h3 class="text-lg font-semibold text-gray-900 mb-2">Order Not Found</h3>
    <p class="text-gray-500 mb-6">The requested order could not be found.</p>
    <a href="<?php echo URL_ROOT; ?>/procurements" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-lg">
        <i class="fas fa-arrow-left mr-2"></i>
        Back to Orders
    </a>
</div>
<?php endif; ?>
